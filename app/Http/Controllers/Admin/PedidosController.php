<?php

namespace Inmuebles\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Inmuebles\Http\Controllers\Controller;
use Inmuebles\Models\Pedidos\Pedido;
use Inmuebles\Models\Usuarios\User;
use Inmuebles\Models\Pedidos\Estado;

class PedidosController extends Controller
{
    public function index(Request $request) {
      $filtros = $request->except('items');
      $items = $request->items;
      $pedidos = Pedido::filtros($filtros)->orderBy('id')->paginate($items)->appends(request()->query());
      return view('admin.pedidos.index')->with('pedidos', $pedidos)
                                        ->with('estados', Estado::orderBy('nombre')->get())
                                        ->with('items', $items)
                                        ->with('filtros', $filtros)
                                        ->with('usuario', $request->input('usuario-razonSocial'));
    }

    public function autoCompletar(Request $request) {
      return User::where('razon_social', 'like', '%'.$request->query('term').'%')->get(); 
    }
}
