<?php

namespace Inmuebles\Services\Mercadopago\Estados;

use Inmuebles\Models\Pedidos\Pedido;

use Mail;

class EstadoCancelado extends EstadoAbstract {

  /**
   * Enviamos un e-mail al cliente notificando el cambio de estado
   * de su Pago de mercadopago con un mensaje distinto segun el estado
   * 
   * @param Vetas_Model_Pedido $pedido
   * @param mixed $paymentInfo
   */
  protected function enviarMailCliente (Pedido $pedido, $paymentInfo) {
    Mail::to($pedido->usuario->email)->queue((new Cancelado($pedido))->onQueue('emails'));
  }

}
