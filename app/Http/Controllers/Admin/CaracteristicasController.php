<?php

namespace Inmuebles\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Inmuebles\Models\Propiedades\Caracteristica;
use Inmuebles\Models\Propiedades\TipoCaracteristica;
use Inmuebles\Models\Propiedades\Propiedad;
use Inmuebles\Http\Controllers\Controller;
use Inmuebles\Models\Propiedades\TipoPropiedad;

class CaracteristicasController extends Controller
{
    private $messages = [
        'nombre.required' => 'Debe especificar el nombre de la característica',
        'tipo_caracteristica_id.required' => 'Debe seleccionar el tipo de característica',
    ];

    private $rules = [ 
        'nombre' => 'required',
        'es_servicio' => 'boolean',
        'unidad' => 'nullable',
        'tipo_caracteristica_id' => 'required|exists:tipos_caracteristica,id',
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
        $caracteristica = Caracteristica::orderBy('nombre')->paginate(20);

        return view('admin.caracteristicas.index')->with('caracteristica', $caracteristica);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.caracteristicas.create')->with('tipos', TipoCaracteristica::orderBy('nombre')->get())
                                                   ->with('tipos_propiedad', TipoPropiedad::orderBy('nombre')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->validate($this->rules, $this->messages)) {
            $caracteristica = new Caracteristica($request->all());
            if ($request->has('es_servicio')) {
                $caracteristica->es_servicio = 1;
            }
            else {
                $caracteristica->es_servicio = 0;
            }

            $caracteristica->save();
        }
        return redirect()->route('admin.caracteristicas.index')->with('exito','Característica agregada con éxito');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $caracteristica=Caracteristica::findOrFail($id);
        return view('admin.caracteristicas.edit')->with('caracteristica', $caracteristica)
                                                 ->with('tipos', TipoCaracteristica::orderBy('nombre')->get());
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
        $caracteristica=Caracteristica::findOrFail($id);
        if ($request->validate($this->rules, $this->messages)) {
            if ($request->has('es_servicio')) {
                $caracteristica->es_servicio = 1;
            }
            else {
                $caracteristica->es_servicio = 0;
            }
            $caracteristica->update($request->all());
        }
        return redirect()->route('admin.caracteristicas.index')->with('exito','Característica actualizada con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $caracteristica=Caracteristica::findOrFail($id);
        $caracteristica->delete();
        return redirect()->route('admin.caracteristicas.index')->with('exito','Característica eliminada');
    }

    public function autoCompletar(Request $request) { 
        return Caracteristica::where('nombre',  'LIKE', '%' . $request->query('term'). '%')->get();
    }
}