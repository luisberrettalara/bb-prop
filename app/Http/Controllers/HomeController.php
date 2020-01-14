<?php

namespace Inmuebles\Http\Controllers;

use Illuminate\Http\Request;
use Inmuebles\Models\Propiedades\Propiedad;
use Inmuebles\Models\Propiedades\TipoPropiedad;
use Inmuebles\Models\Propiedades\Operacion;
use Inmuebles\Models\Comun\Localidad;
use Inmuebles\Models\Usuarios\User;
use Inmuebles\Models\Propiedades\Estado;

use Inmuebles\Services\PropiedadesService;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, PropiedadesService $service) {
      // primera fila de destacadas
      $propiedades = $service->obtenerDestacadasAleatoriasDePozo();

      // segunda fila de destacadas
      $propiedadesRecientes = $service->obtenerDestacadasAleatoriasNoDePozo();

      // obtenemos el email del interesado si no esta logueado
      $emailInteresado = $request->session()->get('emailInteresado');

      // obtenemos el tipo de propiedad 'Emprendimiento de Pozo'
      // para armar la URL de referencia
      $tipoPozo = $service->getTipoDePozo();

      // TODO agregar filtro de publicaciones activas como un JOIN con sus
      // propiedades que estén publicadas, y luego quitar la condición en la vista
      $estadoPublicada = Estado::where('nombre', 'Publicada')->firstOrFail();
      $usuarios = User::where('foto_url', '!=', null)
                        ->whereHas('propiedades', function($query) use ($estadoPublicada) {
                          $query->where('estado_id', $estadoPublicada->id);
                        }, '>=', 5)->inRandomOrder()->take(10)->get();

      return view('home')->with('propiedades', $propiedades)
                         ->with('propiedades_recientes', $propiedadesRecientes)
                         ->with('tipos_propiedad', TipoPropiedad::orderBy('nombre')->get())
                         ->with('operaciones', Operacion::orderBy('nombre')->get())
                         ->with('localidades', Localidad::orderBy('nombre')->get())
                         ->with('emailInteresado', $emailInteresado)
                         ->with('tipoPozo', $tipoPozo)
                         ->with('usuarios', $usuarios);
    }
}
