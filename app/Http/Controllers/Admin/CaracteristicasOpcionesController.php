<?php

namespace Inmuebles\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Inmuebles\Http\Controllers\Controller;
use Inmuebles\Models\Propiedades\Caracteristica;
use Inmuebles\Models\Propiedades\CaracteristicaOpcion;

class CaracteristicasOpcionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Caracteristica $caracteristica)
    {
        return view('admin.caracteristicas.opciones.index')->with('caracteristica', $caracteristica);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Caracteristica $caracteristica, Request $request)
    {   
        $opcion = CaracteristicaOpcion::create([
            'nombre' => $request->opcion,
        ]);
        $caracteristica->opciones()->save($opcion);
        return redirect()->route('admin.caracteristicas.opciones.index', $caracteristica)->with('exito', 'Opción agregada con éxito');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Caracteristica $caracteristica, $id)
    {
        $caracteristicaOpcion = CaracteristicaOpcion::findOrFail($id);
        return view('admin.caracteristicas.opciones.edit')->with('opcion', $caracteristicaOpcion)
                                                          ->with('caracteristica', $caracteristica);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Caracteristica $caracteristica, Request $request, $id) {
        $caracteristicaOpcion = CaracteristicaOpcion::findOrFail($id);
        $caracteristicaOpcion->update($request->only('nombre'));
        return redirect()->route('admin.caracteristicas.opciones.index', $caracteristica)->with('exito', 'Opción actualizada con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Caracteristica $caracteristica, $caracteristicaOpcion) {

        $caracteristicaOpcion = CaracteristicaOpcion::findOrFail($caracteristicaOpcion);
        $caracteristicaOpcion->delete();
        return redirect()->route('admin.caracteristicas.opciones.index', $caracteristica)->with('exito', 'Opción eliminada con éxito');
    }
}
