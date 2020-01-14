<?php

namespace Inmuebles\Http\Controllers;

use Illuminate\Http\Request;
use Inmuebles\Models\Propiedades\Propiedad;
use Inmuebles\Models\Propiedades\TipoPropiedad;
use Inmuebles\Models\Propiedades\Operacion;
use Inmuebles\Models\Propiedades\Uso;
use Inmuebles\Models\Propiedades\Caracteristica;
use Inmuebles\Models\Comun\Localidad;
use Inmuebles\Models\Comun\Provincia;
use Inmuebles\Models\Comun\Barrio;
use Inmuebles\Models\Propiedades\Moneda;
use Inmuebles\Models\Propiedades\Tipologia;
use Inmuebles\Models\Propiedades\UnidadSuperficie;

class BusquedaController extends Controller
{
    public function buscar(Request $request) {
        $filtros = $request->all();
        $tipo = array_key_exists('tipo_propiedad_id', $filtros) ? TipoPropiedad::find($filtros['tipo_propiedad_id']) : null;
        $operacion = array_key_exists('operacion_id', $filtros) ? Operacion::find($filtros['operacion_id']) : null;
        $propiedades = Propiedad::filtros($filtros)
                                ->visibles()
                                ->orderBy('destacada', 'desc')
                                ->latest()
                                ->paginate(10)->appends(request()->query());
        $emailInteresado = $request->session()->get('emailInteresado');
        $localidades = [];
        $barrios = [];
        $localidad = array_key_exists('localidad_id', $filtros) ? Localidad::find($filtros['localidad_id']) : null;
        $provincia = array_key_exists('provincia_id', $filtros) ? Provincia::find($filtros['provincia_id']) : null;

        if ($localidad) {
            $filtros['provincia_id'] = $localidad->provincia->id;
            $localidades = $localidad->provincia->localidades;
            $barrios = $localidad->barrios;
        }

        if ($provincia) {
            $localidades = $provincia->localidades;
        }

        return view('/propiedades/busqueda')->with('propiedades', $propiedades)
                                            ->with('tipo_propiedad', $tipo)
                                            ->with('operacion', $operacion)
                                            ->with('tipos', TipoPropiedad::orderBy('nombre')->get())
                                            ->with('provincias', Provincia::orderBy('nombre')->get())
                                            ->with('localidades', $localidades)
                                            ->with('barrios', $barrios)
                                            ->with('operaciones', Operacion::orderBy('nombre')->get())
                                            ->with('usos', Uso::orderBy('nombre')->get())
                                            ->with('monedas', Moneda::OrderBy('nombre')->get())
                                            ->with('caracteristicas', Caracteristica::orderBy('nombre')->where('es_servicio', 0)->get())
                                            ->with('servicios', Caracteristica::orderBy('nombre')->where('es_servicio', 1)->get())
                                            ->with('tipologias', Tipologia::orderBy('id')->get())
                                            ->with('unidades', UnidadSuperficie::orderBy('id')->get())
                                            ->with('filtros', $filtros)
                                            ->with('emailInteresado', $emailInteresado);
    }
}
