<?php

namespace Inmuebles\Services\Mercadopago\Estados;

use Inmuebles\Jobs\FacturarPedido;
use Inmuebles\Models\Pedidos\Pedido;
use Inmuebles\Mail\Pedidos\Aprobado;

use Log, Mail;

class EstadoAprobado extends EstadoAbstract {

  /* (non-PHPdoc)
   * @see IEstado::procesar()
   */
  public function procesar (Pedido $pedido, $paymentInfo) {
    // aplicamos los créditos comprados por el usuario
    $pedido->acreditarAlUsuario();

    // enviamos un email al administrador y al cliente
    $this->enviarMailAdministrador($pedido, $paymentInfo);
    $this->enviarMailCliente($pedido, $paymentInfo);
    Log::info('Correos de notificacion enviados!');

    // ponemos en cola la facturación
    FacturarPedido::dispatch($pedido)->onQueue('facturas');
    Log::info('Facturación electrónica en cola para Pedido #' . $pedido->id);
  }

  /**
   * Enviamos un e-mail al cliente notificando el cambio de estado
   * de su Pago de mercadopago con un mensaje distinto segun el estado
   * 
   * @param Vetas_Model_Pedido $pedido
   * @param mixed $paymentInfo
   */
  protected function enviarMailCliente (Pedido $pedido, $paymentInfo) {
    Mail::to($pedido->usuario->email)->queue((new Aprobado($pedido))->onQueue('emails'));
  }

}
