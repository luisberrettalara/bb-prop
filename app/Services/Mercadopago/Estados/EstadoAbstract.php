<?php

namespace Inmuebles\Services\Mercadopago\Estados;

use Inmuebles\Mail\Pedidos\PagoActualizado;

use Inmuebles\Models\Pedidos\Pedido;
use Mail, Log, Config;

abstract class EstadoAbstract implements IEstado {

  /* (non-PHPdoc)
   * @see IEstado::procesar()
   */
  public function procesar (Pedido $pedido, $paymentInfo) {
    // enviamos un email al administrador y al cliente
    $this->enviarMailAdministrador($pedido, $paymentInfo);
    $this->enviarMailCliente($pedido, $paymentInfo);

    Log::info('Correos de notificacion enviados!');
  }
  
  /**
   * Enviamos un e-mail al administrador notificando el cambio de estado
   * de un Pago de mercadopago.
   * 
   * @param Pedido $pedido
   * @param mixed $paymentInfo
   */
  protected function enviarMailAdministrador (Pedido $pedido, $paymentInfo) {
    $estadosMp = config('mercadopago.estados');
    $estado = $paymentInfo['response']['collection']['status'];

    // colocamos el e-mail en la cola de envios
    Mail::to(config('mail.from.address'))->queue((new PagoActualizado($pedido))->onQueue('emails'));
  }
  
  /**
   * Enviamos un e-mail al cliente notificando el cambio de estado
   * de su Pago de mercadopago con un mensaje distinto segun el estado
   * 
   * @param Vetas_Model_Pedido $pedido
   * @param mixed $paymentInfo
   */
  protected function enviarMailCliente (Pedido $pedido, $paymentInfo) {
  }

}
