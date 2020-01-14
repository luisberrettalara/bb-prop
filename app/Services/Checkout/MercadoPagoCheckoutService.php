<?php

namespace Inmuebles\Services\Checkout;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

use Inmuebles\Services\Checkout\CheckoutService;
use Inmuebles\Services\Stock\CantidadInvalidaException;

use Inmuebles\Models\Pedidos\Pedido;
use Inmuebles\Models\Pedidos\Estado;
use Inmuebles\Models\Paquetes\Paquete;
use Inmuebles\Models\Usuarios\User;

use MP, DB;

class MercadoPagoCheckoutService implements CheckoutService {

  private $mp;

  public function __construct(MP $mp) {
    $this->mp = $mp;
  }

  public function checkout (Paquete $paquete, User $usuario) {
    Log::info('Iniciando proceso de checkout...');

    // verificamos que el paquete se encuentre disponible
    if (!$paquete->estaDisponible()) {
      Log::warn('Intentando comprar un Paquete no disponible...');

      // de no estarlo arrojamos una excepcion
      throw new MercadoPagoCheckoutException('El Paquete no está disponible');
    }

    // ejecutamos todo dentro de una transaccion
    $pedido = DB::transaction(function () use ($paquete, $usuario) {

      // obtenemos la comisión de mercadopago
      $comision = (float) config('mercadopago.comision');

      // le asignamos el estado 'Creado'
      $creado = Estado::slug('creado')->firstOrFail();

      // creamos el Pedido
      $pedido = new Pedido;
      $pedido->usuario()->associate($usuario);
      $pedido->paquete()->associate($paquete);
      $pedido->estado()->associate($creado);
      $pedido->creditos_normales = $paquete->creditosStandard();
      $pedido->creditos_destacados = $paquete->creditosDestacados();
      $pedido->total = $paquete->precioConComision($comision);

      // guardamos los cambios
      $pedido->save();
      $pedido->agregarEstado($creado);

      // conectamos con Mercadopago
      $this->mp->sandbox_mode(config('mercadopago.sandbox'));

      // obtenemos la preferencia procesada por ML
      $pago = $this->mp->create_preference($pedido->toPreferenceArray());

      // validamos que se haya creado correctamente
      if ($pago['status'] < 200 || $pago['status'] >= 300) {
        throw new MercadoPagoCheckoutException($pago);
      }

      // comprobamos si estamos en la sandbox
      $sandbox = config('mercadopago.sandbox');
      $init_point = 'init_point';
      if ($sandbox) {
        $init_point = 'sandbox_init_point';
      }

      // vinculamos el ID de pago de mercadopago para IPN de Mercadopago
      $pedido->pago_id = $pago['response']['id'];
      $pedido->init_point = $pago['response'][$init_point];
      $pedido->save();

      return $pedido;
    });

    return $pedido;
  }

}
