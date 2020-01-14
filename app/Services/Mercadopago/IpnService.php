<?php

namespace Inmuebles\Services\Mercadopago;

use Inmuebles\Models\Pedidos\Pedido;
use Inmuebles\Models\Pedidos\Estado;

use Log, App, MP, Mail;

class IpnService {

  private $mp;

  public function __construct(MP $mp) {
    $this->mp = $mp;
  }

  public function procesar ($topic, $id) {
    $estadosMp = config('mercadopago.estados');
    Log::info("IPN[$topic] disparado para id: $id");

    // iniciamos el merchant order y pedido
    $merchantOrderInfo = null;
    $pedido = null;

    // conectamos con Mercadopago
    $this->mp->sandbox_mode(config('mercadopago.sandbox'));

    // si el topico notificado es un pago
    if ($topic == 'payment') {

      // obtenemos el detalle del pago
      $paymentInfo = $this->mp->get('/collections/notifications/' . $id);

      // obtenemos los datos del merchant order
      $merchantOrderInfo = $this->mp->get('/merchant_orders/' . $paymentInfo['response']['collection']['merchant_order_id']);

      // obtenemos el pedido y actualizamos el estado
      Log::info('Datos del Pedido: ' . $paymentInfo['response']['collection']['external_reference']);
      $pedido = Pedido::findOrFail ($paymentInfo['response']['collection']['external_reference']);

      // obtenemos el estado del pedido
      $estadoMp = $paymentInfo['response']['collection']['status'];

      // si corresponde a un estado valido
      if (in_array($estadoMp, array_keys($estadosMp))) {
        $slug = $estadosMp[$estadoMp];

        // cambiamos el estado del pedido
        $estado = Estado::slug($slug)->firstOrFail();
        $pedido->agregarEstado($estado);
        Log::info("Estado del Pedido", array($estadoMp));

        // usamos una estrategia para procesar la transicion de estado
        $nombreClase = 'Inmuebles\Services\Mercadopago\Estados\Estado' . ucfirst(str_replace('-', '', $slug));

        // si existe la estrategia creamos una instancia
        if (class_exists($nombreClase)) {
          $iestado = new $nombreClase;

          // procesamos el manejador del estado
          $iestado->procesar ($pedido, $paymentInfo);
        }
        else {
          Log::warning("La estrategia $nombreClase no existe.");
        }
      }
    }
    else if ($topic == 'merchant_order') {
      // obtenemos los datos del merchant order
      $merchantOrderInfo = $this->mp->get('/merchant_orders/' . $id);

      // si la operacion esta vinculada a un Pedido
      $externalReference = $merchantOrderInfo['response']['external_reference'];

      // obtenemos el pedido del merchant order
      $pedido = Pedido::find ((int) $externalReference);
    }
    else {
      // el topico no existe!
      Log::error ("El topico $topic no está soportado.");

      throw new TopicoInexistenteException($topic);
    }

    // si el estado es OK y el Pedido está totalmente pagado
    if ($pedido && $merchantOrderInfo['status'] == 200 && $this->estaTotalmentePagado($merchantOrderInfo)) {
      // actualizamos el estado del pedido
      $estado = Estado::where('nombre', 'Aprobado')->firstOrFail();
      $pedido->agregarEstado($estado);
    }
  }

  private function estaTotalmentePagado ($merchantOrderInfo) {
    $pagado = 0;

    foreach ($merchantOrderInfo['response']['payments'] as $payment) {
      if ($payment['status'] == 'approved') {
        $pagado += $payment['transaction_amount'];
      } 
    }

    return $pagado >= $merchantOrderInfo['response']['total_amount'];
  }

}
