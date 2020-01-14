<?php

namespace Inmuebles\Http\Controllers\Admin\Paquetes;

use Inmuebles\Models\Paquetes\TipoCredito;
use Illuminate\Http\Request;
use Inmuebles\Http\Controllers\Controller;

class TiposCreditoController extends Controller {

  private $reglas = [
    'duracion_en_dias' => 'required|integer|min:1',
    'dias_al_vencimiento' => 'required|integer|min:1',
    'destacado' => 'boolean'
  ];

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    $tiposCredito = TipoCredito::orderBy('destacado', 'desc')
                               ->orderBy('duracion_en_dias', 'asc')
                               ->orderBy('dias_al_vencimiento', 'asc')
                               ->paginate(10);

    return view('admin.paquetes.tipos-credito.index')->with('tiposCredito', $tiposCredito);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create() {
    return view('admin.paquetes.tipos-credito.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    $request->validate($this->reglas);

    TipoCredito::create($request->all());

    return redirect()->route('admin.tipos-credito.index')->with('exito', 'Tipo de Crédito agregado con éxito');
  }

  /**
   * Display the specified resource.
   *
   * @param  \Inmuebles\Models\Paquetes\TipoCredito  $tipoCredito
   * @return \Illuminate\Http\Response
   */
  public function show(TipoCredito $tiposCredito)
  {
      dd($tiposCredito);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \Inmuebles\Models\Paquetes\TipoCredito  $tipoCredito
   * @return \Illuminate\Http\Response
   */
  public function edit(TipoCredito $tiposCredito){
    return view('admin.paquetes.tipos-credito.edit')->with('tipo', $tiposCredito);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Inmuebles\Models\Paquetes\TipoCredito  $tipoCredito
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, TipoCredito $tiposCredito) {
    $request->validate($this->reglas);

    $tiposCredito->fill($request->all());
    $tiposCredito->save();

    return redirect()->route('admin.tipos-credito.index')->with('exito', 'Tipo de Crédito actualizado con éxito');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \Inmuebles\Models\Paquetes\TipoCredito  $tipoCredito
   * @return \Illuminate\Http\Response
   */
  public function destroy(TipoCredito $tiposCredito) {
    $tiposCredito->delete();

    return redirect()->route('admin.tipos-credito.index')->with('exito', 'Tipo de Crédito eliminado con éxito');
  }
}
