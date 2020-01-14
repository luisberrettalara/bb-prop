<?php

namespace Inmuebles\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Inmuebles\Http\Controllers\Controller;
use Inmuebles\Models\Propiedades\Tipologia;
use Inmuebles\Models\Propiedades\Propiedad;

class TipologiasController extends Controller
{

    private $messages = [
        'nombre.required' => 'Debe especificar la tipología',
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
        $tipologias = Tipologia::orderBy('nombre')->paginate(10);
        return view('admin.tipologias.index')->with('tipologias', $tipologias);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tipologias.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->validate($this->rules, $this->messages)) {
            $tipologia = new Tipologia($request->all());
            $tipologia->save();
        }
        return redirect()->route('admin.tipologias.index')->with('exito', 'Tipologia agregada con éxito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tipologia=Tipologia::findOrFail($id);
        return view('admin.tipologias.edit')->with('tipologia', $tipologia);
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
        $tipologia = Tipologia::findOrFail($id);
        if ($request->validate($this->rules, $this->messages)) {
            $tipologia->update($request->all());
        }
        return redirect()->route('admin.tipologias.index')
                         ->with('exito', 'Tipología actualizada con éxito');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $propiedad = Propiedad::where('tipologia_id', $id);
        $tipologia = Tipologia::findOrFail($id);
        if ($propiedad->count()>0) {
            return redirect()->route('admin.tipologias.index')
                             ->with('error','No se puede eliminar, ya que ya se encuentra asignada a una propiedad existente');
        }
        else {
            $tipologia->delete();
        }
        return redirect()->route('admin.tipologias.index')
                         ->with('exito','Tipología eliminada');
    }
}
