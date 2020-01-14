<?php

namespace Inmuebles\Http\Controllers;

use Illuminate\Http\Request;
use Inmuebles\Models\Propiedades\Propiedad;
use Inmuebles\Models\Propiedades\Foto;
use Inmuebles\Services\FotosService;
use File;
use Flash;
use Session;
use Auth;

class FotosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $propiedad=Propiedad::findOrFail($id);
        return view('propiedades.fotos.index')->with('propiedad', $propiedad);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {   
        $propiedad=Propiedad::findOrFail($id);
        if($request->validate(['foto'=>'required|image'])){
            $file = $request->file('foto');
            $path = public_path() . '/fotos/propiedades/';
            $fileName = uniqid() . $file->getClientOriginalExtension();

            $file->move($path, $fileName);

            $fotoPropiedad = new Foto();
            $fotoPropiedad->url = '/fotos/propiedades/' . $fileName;
            $fotoPropiedad->descripcion = $request->input('descripcion');

            $propiedad->fotos()->save($fotoPropiedad);
            
            return redirect("/propiedades/$propiedad->id/fotos")->with('mensaje_exito','Foto cargada con exito');
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $fid) {
        $propiedad=Propiedad::findOrFail($id);
        $foto=Foto::findOrFail($fid);
        if($propiedad->fotoPortada != null && $propiedad->fotoPortada->id === $foto->id) {
            $propiedad->fotoPortada()->dissociate($fid);
            $propiedad->fotoPortada()->associate($propiedad->fotos()->first());
            $propiedad->save();
        }
        if(File::exists(public_path($foto->url))) {
           File::delete(public_path($foto->url));
        }
        $foto->delete();
        
        return redirect("/propiedades/$propiedad->id/fotos")->with('mensaje_exito', 'Foto eliminada con exito');  
    }

    public function definirPortada($id,$fid) {
        $propiedad=Propiedad::findOrFail($id);
        $foto=Foto::findOrFail($fid);
        $propiedad->fotoPortada()->associate($fid);
        $propiedad->save();
        if (Auth::user()->esAdmin()) {
            return redirect()->route('admin.propiedades.edit', $propiedad->id)->with('exito', 'Foto de portada definida con éxito');
        }
        else {
            return redirect()->route('propiedades.edit', $propiedad->id)->with('exito', 'Foto de portada definida con éxito');
        }
    }

    public function subirTemporal(Request $request, FotosService $service) {
        if($request->validate(['foto'=>'required|image'])) {            
            return $service->guardarTemporal($request->file('foto'));
        }
    }
}
