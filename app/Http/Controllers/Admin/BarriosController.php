<?php

namespace Inmuebles\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Inmuebles\Models\Comun\Barrio;
use Inmuebles\Models\Comun\Localidad;
use Inmuebles\Models\Comun\Provincia;
use Inmuebles\Models\Propiedades\Propiedad;
use Inmuebles\Http\Controllers\Controller;


class BarriosController extends Controller
{
    private $messages = [
        'nombre.required' => 'Debe especificar el nombre del barrio',
        'localidad.required' => 'Debe especificar la localidad',
    ];

    private $rules = [ 
        'nombre' => 'required',
        'localidad' => 'required',
    ];

    public function __construct(Request $request) {
        $this->middleware('admin');    
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $barrio = Barrio::orderBy('nombre')->paginate(30);
       return view('admin.barrios.index')->with('barrio', $barrio)
                                         ->with('provincia', Provincia::orderBy('nombre')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.barrios.create')->with('localidades', Localidad::orderBy('nombre')->get())
                                           ->with('provincias', Provincia::orderBy('nombre')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $localidad = $this->buscarLocalidad($request->all());
        if ($request->validate($this->rules, $this->messages)) {
            $barrio = Barrio::create([
              'nombre' => $request['nombre'],
              'localidad_id' => $localidad->id,
            ]);

            $barrio->save();
        }

        return redirect()->route('admin.barrios.index')->with('exito', 'Barrio agregado con éxito');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $barrio=Barrio::findOrFail($id);
        return view('admin.barrios.edit')->with('barrio', $barrio)
                                         ->with('localidades', Localidad::orderBy('nombre')->get())
                                         ->with('provincias', Provincia::orderBy('nombre')->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        $barrio = Barrio::findOrFail($id);
        $localidad = $this->buscarLocalidad($request->all());
        if ($request->validate($this->rules, $this->messages)) {
            $barrio->nombre = $request->input('nombre');
            $barrio->localidad_id = $localidad->id;
            $barrio->save();
        }
        return redirect()->route('admin.barrios.index')->with('exito', 'Barrio actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $propiedad=Propiedad::where('barrio_id', $id);
        $barrio=Barrio::findOrFail($id);
        if ($propiedad->count()>0) {
            return redirect()->route('admin.barrios.index')->with('error', 'No se puede eliminar, ya que ya se encuentra asignado a una propiedad existente');
        }
        else {
            $barrio->delete();
        }
        return redirect()->route('admin.barrios.index')->with('exito', 'Barrio eliminado');
    }

    private function buscarLocalidad($request) {
      $localidad = Localidad::where('google_place_id', $request['loc_place_id'])->first();
      if($localidad == null) {
        $localidad = Localidad::create([
          'nombre' => $request['localidad'],
          'provincia_id' => $request['provincia_id'],
          'google_place_id' => $request['loc_place_id'],
        ]);
      }
      return $localidad;
    }
}
