<?php

namespace Inmuebles\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Inmuebles\Models\Paquetes\Paquete;
use Inmuebles\Services\Checkout\CheckoutService;

class PaquetesController extends Controller {

  public function __construct(Request $request) {
        $this->middleware('comprobar:Debes iniciar sesión');
  }

  public function listar() {
    $paquetes = Paquete::where('disponible', 1)
                      ->where('precio', '>', 0)
                      ->where(function($query) {
                        $query->whereNull('por_defecto')
                              ->orWhere('por_defecto', 0);
                      })->get();
    // obtenemos la comisión de mercadopago
    $comision = (float) config('mercadopago.comision');

    return view('paquetes.comprar')->with('paquetes', $paquetes)
                                   ->with('comision', $comision);
  }

  public function comprar(Request $request, CheckoutService $service) {
    $request->validate([
      'paquete' => 'required|integer|exists:paquetes,id'
    ],
    [
      'paquete.required' => 'Debe seleccionar un paquete'
    ]);

    // obtenemos el Paquete a comprar
    $paquete = Paquete::findOrFail($request->get('paquete'));
    $usuario = Auth::user();

    // en caso de no tener errores procedemos a crear el pedido
    $pedido = $service->checkout($paquete, $usuario);

    return $pedido->init_point;
  }
}
