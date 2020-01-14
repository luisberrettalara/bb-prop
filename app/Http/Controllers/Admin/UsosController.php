<?php

namespace Inmuebles\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Inmuebles\Models\Propiedades\Uso;
use Inmuebles\Models\Propiedades\Propiedad;
use Inmuebles\Http\Controllers\Controller;

class UsosController extends Controller
{
    private $messages = [
    'nombre.required' => 'Debe especificar el nombre del uso',
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
        $uso = Uso::orderBy('nombre')->paginate(8);
        return view('admin.usos.index')->with('uso', $uso);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.usos.create');
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
        $uso = new Uso($request->all());
        $uso->save();
        }
        return redirect()->route('admin.usos.index')->with('exito','Uso agregado con éxito');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $uso=Uso::findOrFail($id);
        return view('admin.usos.edit')->with('uso', $uso);
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
        $uso=Uso::findOrFail($id);
        if ($request->validate($this->rules, $this->messages)) {
            $uso->update($request->all());
        }
        return redirect()->route('admin.usos.index')->with('exito','Uso actualizado con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $propiedad=Propiedad::join('usos_propiedad', 'propiedades.id','=', 'usos_propiedad.propiedad_id')
                            ->join('usos', 'usos_propiedad.uso_id', '=', 'usos.id')
                            ->where('usos_propiedad.uso_id',$id);
        $uso=Uso::findOrFail($id);
        if ($propiedad->count()>0) {
            return redirect()->route('admin.usos.index')->with('error','No se puede eliminar, ya que ya se encuentra asignada a una propiedad existente');
        }
        else {
            $uso->delete();
        }       
        return redirect()->route('admin.usos.index')->with('exito','Uso eliminado');
    }
}
