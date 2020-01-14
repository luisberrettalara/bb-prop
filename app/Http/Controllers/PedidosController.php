<?php

namespace Inmuebles\Http\Controllers;

use Illuminate\Http\Request;
use Inmuebles\Models\Pedidos\Pedido;

use Inmuebles\Services\Mercadopago\IpnService;
use Inmuebles\Services\Mercadopago\TopicoInexistenteException;

use Log, Exception, Cart;

class PedidosController extends Controller {

  public function exito(Pedido $pedido) {
    return view('pedidos.exito')->with('pedido', $pedido);
  }

  public function pendiente(Pedido $pedido) {
    return view('pedidos.pendiente')->with('pedido', $pedido);
  }

  public function rechazado(Pedido $pedido) {
    return view('pedidos.rechazado')->with('pedido', $pedido);
  }

  public function ipn(Request $request, IpnService $ipnService) {
    // obtenemos las variables del request
    $topic     = $request->input('topic');
    $id        = $request->input('id');

    try {
      // realizamos la consulta a Mercadopago por el estado del pago
      $ipnService->procesar($topic, $id);
    }
    catch (TopicoInexistenteException $ex) {
      Log::error($ex->getMessage());
    }

    return 'Gracias :)';
  }

}
