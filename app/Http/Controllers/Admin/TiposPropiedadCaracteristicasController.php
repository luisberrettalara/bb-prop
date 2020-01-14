<?php

namespace Inmuebles\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Inmuebles\Models\Propiedades\Caracteristica;
use Inmuebles\Models\Propiedades\TipoPropiedad;
use Inmuebles\Http\Controllers\Controller;

class TiposPropiedadCaracteristicasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TipoPropiedad $tiposPropiedad)
    {
        return view('admin.tipos-propiedad.caracteristicas.index')->with('tipo_propiedad', $tiposPropiedad);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TipoPropiedad $tiposPropiedad, Request $request)
    {   
        $caracteristica = $tiposPropiedad->caracteristicas()->where('id', $request->caracteristica)->get();
        if ($request->caracteristica) {
            if (count($caracteristica) > 0) {
               return redirect()->route('admin.tipos-propiedad.caracteristicas.index', $tiposPropiedad)->with('error', 'Esa característica ya se encuentra asignada a este tipo de propiedad');
            }
            else {
                $c = Caracteristica::find($request->caracteristica);
                $tiposPropiedad->agregarCaracteristica($c);
                return redirect()->route('admin.tipos-propiedad.caracteristicas.index', $tiposPropiedad)->with('exito', 'Caracteristica agregada con éxito');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoPropiedad $tiposPropiedad, Caracteristica $caracteristica)
    {   
        $tiposPropiedad->quitarCaracteristica($caracteristica);
        return redirect()->route('admin.tipos-propiedad.caracteristicas.index', $tiposPropiedad)->with('exito', 'Característica eliminada de este tipo de propiedad');
    }

    public function reordenar(TipoPropiedad $tipoPropiedad, Caracteristica $caracteristica, Request $request) {
        $tipoPropiedad->reordenarCaracteristicas($caracteristica, $request->orden);
        return response()->json(['status' => 'ok']);
    }
}