<?php

namespace Inmuebles\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Inmuebles\Models\Propiedades\TipoPropiedad;
use Inmuebles\Models\Propiedades\Propiedad;
use Inmuebles\Http\Controllers\Controller;

class TiposPropiedadController extends Controller
{
    private $messages = [
    'nombre.required' => 'Debe especificar el tipo de propiedad',
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
        $tipoPropiedad = TipoPropiedad::orderBy('nombre')->paginate(10);
        return view('admin.tipos-propiedad.index')->with('tipos', $tipoPropiedad);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tipos-propiedad.create');
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
            $tipoPropiedad = new TipoPropiedad($request->all());
            $tipoPropiedad->save();
        }
        return redirect()->route('admin.tipos-propiedad.index')->with('exito','Tipo de propiedad agregado con éxito');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tipoPropiedad=TipoPropiedad::findOrFail($id);
        return view('admin.tipos-propiedad.edit')->with('tipos', $tipoPropiedad);
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
        $tipoPropiedad=TipoPropiedad::findOrFail($id);
        if ($request->validate($this->rules, $this->messages)) {
            $tipoPropiedad->update($request->all());
        }
        return redirect()->route('admin.tipos-propiedad.index')
                         ->with('exito','Tipo de propiedad actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $propiedad = Propiedad::where('tipo_propiedad_id', $id);
        $tipoPropiedad = TipoPropiedad::findOrFail($id);
        if ($propiedad->count()>0) {
            return redirect()->route('admin.tipos-propiedad.index')
                             ->with('error','No se puede eliminar, ya que ya se encuentra asignado a una propiedad existente');
        }
        else {
            $tipoPropiedad->delete();
        }
        return redirect()->route('admin.tipos-propiedad.index')
                         ->with('exito','Tipo de propiedad eliminado');
    }
}
