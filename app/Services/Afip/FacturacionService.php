<?php

namespace Inmuebles\Services\Afip;

use Inmuebles\Models\Pedidos\Pedido;
use Inmuebles\Mail\Pedidos\Facturado;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

use Carbon\Carbon;

class FacturacionService {

    public function facturar(Pedido $pedido) {
        Log::info('Comienza la facturación del Pedido', [ 'pedido' => $pedido ]);

        // solo si este pedido no ha sido facturado
        if ($pedido->estaFacturado()) {
            throw new FacturacionException('El Pedido ' . $pedido->id . ' ya ha sido facturado anteriormente');
        }

        // solo si este pedido está pagado
        if (!$pedido->estaPagado()) {
            throw new FacturacionException('El Pedido ' . $pedido->id . ' aún no ha sido pagado');
        }

        // obtenemos los parámetros de facturación
        $puntoDeVenta = 1;

        // determinamos el tipo de comprobante
        // si es Responsable Inscripto usamos 1: Factura A
        // de lo contrario 6: Factura B
        $tipoComprobante = $pedido->usuario->esResponsableInscripto() ? 1 : 6;

        // consultamos el próximo número
        $numeroComprobante = $this->consultarProximoNumero($tipoComprobante, $puntoDeVenta);
        Log::info('Número de Comprobante: ' . $numeroComprobante);

        // seteamos la ruta del pdf generado
        $pdfPath = storage_path('afip/pdf/Factura' . $numeroComprobante . '.pdf');

        // armamos el vector de JSON para pyafipws
        $facturas = [[
            'id' => $pedido->id,
            'punto_vta' => $puntoDeVenta,
            'tipo_cbte' => $tipoComprobante,
            'cbte_nro' => $numeroComprobante,
            'cbt_desde' => $numeroComprobante,
            'cbt_hasta' => $numeroComprobante,
            'tipo_doc' => 80, // 80: CUIT
            'nro_doc' => $pedido->usuario->cuit,
            'fecha_cbte' => $pedido->created_at->format('Ymd'),
            'concepto' => 1, // 1: Productos
            'nombre_cliente' => $pedido->usuario->razon_social,
            'domicilio_cliente' => $pedido->usuario->domicilio,
            'moneda_ctz' => 1, // 1: Pesos Argentinos
            'moneda_id' => 'PES',
            'forma_pago' => 'Mercadopago',
            'imp_neto' => $pedido->getImporteNetoFormato(),
            'imp_iva' => $pedido->getImporteIvaFormato(),
            'imp_total' => $pedido->getImporteTotalFormato(),
            'descuento' => 0,
            'iva'=> [[
                'base_imp'=> $pedido->getImporteNetoFormato(),
                'importe'=> $pedido->getImporteIvaFormato(),
                'iva_id'=> 4 // IVA 10,5
            ]],
            'datos'=> [],
            'detalles'=> [[
                'bonif'=> 0,
                'cod_mtx'=> $pedido->paquete->id,
                'codigo'=> $pedido->paquete->id,
                'dato_a'=> null,
                'dato_b'=> null,
                'dato_c'=> null,
                'dato_d'=> null,
                'dato_e'=> null,
                'despacho'=> '',
                'ds'=> $pedido->paquete->nombre,
                'imp_iva'=> $pedido->getImporteIvaFormato(),
                'importe'=> $pedido->getImporteTotalFormato(),
                'iva_id'=> 5,
                'precio'=> $pedido->getImporteNetoFormato(),
                'qty'=> 1,
                'u_mtx'=> 1,
                'umed'=> 7
            ]],
            'pdf'=> $pdfPath
        ]];

        // obtenemos el JSON asociado
        $jsonInput = json_encode($facturas);

        // lo guardamos en el storage para su procesamiento
        Storage::disk('afip')->put('in/Factura' . $numeroComprobante . '.json', $jsonInput);

        $pathJsonInput  = storage_path('afip/in/Factura' . $numeroComprobante . '.json');
        $pathJsonOutput = storage_path('afip/out/Factura' . $numeroComprobante . '.json');

        // creamos el proceso para invocar al RECE1
        $receProcess = new Process([
            'python',
            'rece1.py',
            '/json',
            '/entrada', $pathJsonInput,
            '/salida', $pathJsonOutput
        ]);
        $receProcess->setWorkingDirectory('/var/www/pyafipws');
        $receProcess->run();

        // si el proceso se ha ejecutado correctamente
        if ($receProcess->isSuccessful()) {

            // obtenemos los datos de respuesta
            $jsonResultado = Storage::disk('afip')->get('out/Factura' . $numeroComprobante . '.json');
            $resultado = json_decode($jsonResultado, true);
            Log::info('Resultado recibido de AFIP', [ 'resultado' => $resultado ]);

            // consultamos si es exitoso
            if ($resultado[0]['cae']) {

                // actualizamos el pedido con los datos de facturación
                $pedido->punto_venta = $puntoDeVenta;
                $pedido->numero_comprobante = $numeroComprobante;
                $pedido->cae = $resultado[0]['cae'];
                $pedido->fecha_vencimiento_cae = Carbon::createFromFormat('Ymd', $resultado[0]['fch_venc_cae']);
                $pedido->observaciones_cae = $resultado[0]['motivos_obs'];

                $pedido->save();
            }
            else {
                Log::error('Error al obtener CAE', [ 'facturas' => $facturas, 'resultado' => $resultado ]);
                throw new FacturacionException('Error al obtener CAE');
            }

            // intentamos generar el archivo PDF
            try {
                $this->generarPdf($pathJsonOutput, $pdfPath);

                // lo enviamos por email al cliente
                $mailFacturado = (new Facturado($pedido, $pdfPath))->onQueue('emails');
                Mail::to($pedido->usuario->email)->queue($mailFacturado);
            }
            catch (GeneracionPdfException $ex) {
                Log::error('No se pudo generar el PDF', [ 'exception' => $ex ]);
            }
        }
        else {
            Log::error('Error al invocar pyafipws', [ 'proceso' => $receProcess ]);
            throw new FacturacionException($receProcess);
        }
    }

    private function consultarProximoNumero($tipoComprobante, $puntoDeVenta) {
        $proximoNumero = null;

        // creamos el proceso para invocar al RECE1
        $pathUltimoComprobante = storage_path('afip/ultimo-comprobante.json');
        $receProcess = new Process([
            'python',
            'rece1.py',
            '/json',
            '/ult', $tipoComprobante, $puntoDeVenta,
            '/salida', $pathUltimoComprobante
        ]);
        $receProcess->setWorkingDirectory('/var/www/pyafipws');
        $receProcess->run();

        // si el proceso se ha ejecutado correctamente
        if ($receProcess->isSuccessful()) {
            // buscamos el número en la salida
            preg_match_all('/Ultimo numero:  ([\d]+)/', $receProcess->getOutput(), $matches);

            Log::info('Resultado del nro del próximo comprobante', [
                'output' => $receProcess->getOutput(), 'matches' => $matches, 'numero' => $matches[1][0]
            ]);

            $proximoNumero = (int)$matches[1][0] + 1;
        }
        else {
            Log::error('No se pudo consultar el nro del próximo comprobante: ' . $receProcess->getOutput());
            throw new FacturacionException('No se pudo consultar el nro del próximo comprobante');
        }

        return $proximoNumero;
    }

    private function generarPdf($jsonPath, $pdfPath) {
        // creamos el proceso para invocar al PYFEPDF
        $receProcess = new Process([
            'python',
            'pyfepdf.py',
            '--cargar',
            '--json',
            '--entrada', $jsonPath
        ]);
        $receProcess->setWorkingDirectory('/var/www/pyafipws');
        $receProcess->run();

        // si el proceso se ha ejecutado correctamente
        if (!$receProcess->isSuccessful()) {
            throw new GeneracionPdfException($receProcess->getOutput());
        }
    }

}