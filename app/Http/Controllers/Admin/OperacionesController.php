<?php

namespace Inmuebles\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Inmuebles\Models\Propiedades\Operacion;
use Inmuebles\Models\Propiedades\Propiedad;
use Inmuebles\Http\Controllers\Controller;

class OperacionesController extends Controller
{
    private $messages = [
    'nombre.required' => 'Debe especificar el nombre de la operación',
    ];

    private $rules = [ 
        'nombre' => 'required',
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
        $operacion = Operacion::orderBy('nombre')->paginate(8);
        return view('admin.operaciones.index')->with('operacion', $operacion);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.operaciones.create');
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
        $operacion = new Operacion($request->all());
        $operacion->save();
        }
        return redirect()->route('admin.operaciones.index')->with('exito','Operación agregada con éxito');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $operacion=Operacion::findOrFail($id);
        return view('admin.operaciones.edit')->with('operacion', $operacion);
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
        $operacion=Operacion::findOrFail($id);
        if ($request->validate($this->rules, $this->messages)) {
            $operacion->update($request->all());
        }
            return redirect()->route('admin.operaciones.index')->with('exito','Operación actualizada con éxito');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $propiedad = Propiedad::where('operacion_id', $id);
        $operacion=Operacion::findOrFail($id);
        if ($propiedad->count()>0) {
            return redirect()->route('admin.operaciones.index')->with('error','No se puede eliminar, ya que ya se encuentra asignada a una propiedad existente');
        }
        else {
            $operacion->delete();
        }
        return redirect()->route('admin.operaciones.index')->with('exito','Operación eliminada');
    }
}
