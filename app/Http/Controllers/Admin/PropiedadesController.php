<?php

namespace Inmuebles\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Inmuebles\Http\Controllers\Controller;
use Inmuebles\Models\Propiedades\Propiedad;
use Inmuebles\Models\Comun\Localidad;
use Inmuebles\Models\Propiedades\Operacion;
use Inmuebles\Models\Propiedades\TipoPropiedad;
use Inmuebles\Models\Comun\Barrio;
use Inmuebles\Models\Comun\Provincia;
use Inmuebles\Models\Propiedades\Caracteristica;
use Inmuebles\Services\PropiedadesService;
use Inmuebles\Models\Propiedades\Uso;
use Inmuebles\Models\Propiedades\Foto;
use Inmuebles\Models\Propiedades\Moneda;
use Inmuebles\Models\Usuarios\User;
use Inmuebles\Models\Propiedades\Tipologia;
use Inmuebles\Models\Propiedades\UnidadSuperficie;

use File;
use Auth;

class PropiedadesController extends Controller
{
    private $rules = [ 
        'titulo' => 'max:120|required',
        'superficie' => 'nullable|numeric',
        'localidad_id' => 'required',
        'monto' => 'nullable|numeric',
        'provincia_id' => 'required|exists:provincias,id',
        'tipo_propiedad_id' => 'required|exists:tipos_propiedad,id',
        'operacion_id' => 'required|exists:operaciones,id',
        'usos' => 'required|exists:usos,id',
        'expensas' => 'nullable|numeric',
        'superficie_descubierta' => 'nullable|numeric',
        'pdf_url' => 'nullable|mimes:pdf|max:50000',
        'video_url' => ['nullable', 'regex:/^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/']
    ];

     private $messages = [
        'titulo.max' => 'El título no puede ser mayor a :max caracteres.',
        'descripcion.required' => 'Agrega una descripción para la propiedad.',
        'titulo.required' => 'Agrega el título de la propiedad.',
        'localidad_id.required' => 'Agrega la localidad donde se encuentra la propiedad.', 
        'provincia_id.required' => 'Selecciona la provincia donde se encuentra la propiedad.',
        'tipo_propiedad_id.required' => 'Selecciona un tipo de propiedad.',
        'operacion_id.required' => 'Selecciona una operación.',
        'usos.required' => 'Selecciona al menos un uso.',
        'pdf_url.max' => 'El PDF no puede ser mayor a 50 megas',
        'pdf_url.mimes' => 'La extensión del archivo debe ser del tipo .pdf',
        'video_url.regex' => 'Debe ser una URL de YouTube válida'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $propiedades = Propiedad::orderBy('id')->paginate(20);
        return view('admin.propiedades.index')->with('propiedades', $propiedades);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.propiedades.create')->with('provincias', Provincia::orderBy('nombre')->get())
                                               ->with('usuario', User::orderBy('email')->get())
                                               ->with('tipos', TipoPropiedad::orderBy('nombre')->get())
                                               ->with('operaciones', Operacion::orderBy('nombre')->get())
                                               ->with('usos', Uso::orderBy('nombre')->get())
                                               ->with('monedas', Moneda::OrderBy('nombre')->get())
                                               ->with('tipologias', Tipologia::orderBy('id')->get())
                                               ->with('unidades', UnidadSuperficie::orderBy('id')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PropiedadesService $service) {
      
      if($request->validate($this->rules, $this->messages)) {

        $localidad = $this->buscarLocalidad($request->all());

        $propiedad = new Propiedad;
        $propiedad->fill($request->except('pdf_url'));
        if ($request->has('precio_convenir')) {
            $propiedad->precio_convenir = 1;
        }
        else {
            $propiedad->precio_convenir = 0;
        }
        $fileName = '';
        if($request->hasFile('pdf_url')) {
            $file = $request->file('pdf_url');
            $path = public_path() . '/pdfs/';
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($path, $fileName);
            $fileName = '/pdfs/'. $fileName;
            $propiedad->pdf_url = $fileName;
        }

        if ($request->has('ubicacion_publica')) {
            $propiedad->ubicacion_publica = 1;
        }
        else {
            $propiedad->ubicacion_publica = 0;
        }

        $propiedad->localidad_id = $localidad->id;
        $propiedad->usuario_id =  Auth::user()->id;
        $service->agregar($propiedad, $request->input('caracteristicas', []), $request->input('usos'), $request->input('fotos', []), $request->input('lat'), $request->input('long'));
      
      return redirect()->route('propiedades.publicar', $propiedad)
                       ->with('exito', 'Tu Propiedad ha sido creada con éxito, ahora tienes que Publicarla');
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $propiedad = Propiedad::findOrFail($id);
        return view('admin.propiedades.show')->with('propiedad', $propiedad);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $propiedad=Propiedad::findOrFail($id);
        $usuario=User::where('email', $propiedad->usuario->email)->orderBy('email')->get();
        return view('admin.propiedades.edit')->with('propiedad', $propiedad)
                                             ->with('usuario', $usuario)
                                             ->with('provincias',Provincia::orderBy('nombre')->get())
                                             ->with('localidades', Localidad::where('provincia_id', $propiedad->localidad->provincia->id)->orderBy('nombre')->get())
                                             ->with('barrios',Barrio::where('localidad_id',$propiedad->localidad_id)->orderBy('nombre')->get())
                                             ->with('tipos', TipoPropiedad::orderBy('nombre')->get())
                                             ->with('operaciones', Operacion::orderBy('nombre')->get())
                                             ->with('tiposPropiedad', TipoPropiedad::find($propiedad->tipoPropiedad->id))
                                             ->with('usos', Uso::orderBy('nombre')->get())
                                             ->with('monedas', Moneda::orderBy('nombre')->get())
                                             ->with('tipologias', Tipologia::orderBy('nombre')->get())
                                             ->with('unidades', UnidadSuperficie::orderBy('id')->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, PropiedadesService $service)
    {
        $propiedad = Propiedad::findOrFail($id);
        $localidad = $this->buscarLocalidad($request->all());
        if($request->validate($this->rules, $this->messages)) {
          $propiedad->fill($request->all());
          if ($request->has('precio_convenir')) {
            $propiedad->precio_convenir = 1;
            $propiedad->monto = null;
          }
          else {
            $propiedad->precio_convenir = 0;
          }

          if (File::exists(public_path($propiedad->pdf_url))) {
            File::delete(public_path($propiedad->pdf_url));
          }

          $fileName = '';
          if($request->hasFile('pdf_url')) {
            $file = $request->file('pdf_url');
            $path = public_path() . '/pdfs/';
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($path, $fileName);
            $fileName = '/pdfs/'. $fileName;
            $propiedad->pdf_url = $fileName;
          }

          if ($request->has('ubicacion_publica')) {
            $propiedad->ubicacion_publica = 1;
          }
          else {
            $propiedad->ubicacion_publica = 0;
          }

          $propiedad->localidad_id = $localidad->id;
          $propiedad->usuario()->associate($request->input('usuario'));
          $service->actualizar($propiedad, $request->input('caracteristicas', []), $request->input('usos'), $request->input('fotos'), $request->input('fotos-eliminar'), $request->input('lat'), $request->input('long'));
          return redirect()->route('admin.propiedades.index')->with('exito', 'Propiedad actualizada con éxito');
        }
    }

    private function buscarLocalidad($request) {
      $localidad = Localidad::where('google_place_id', $request['localidad_place_id'])->first();
      
      if($localidad == null) {
        $localidad = Localidad::create([
          'nombre' => $request['localidad_id'],
          'provincia_id' => $request['provincia_id'],
          'google_place_id' => $request['localidad_place_id'],
        ]);
      }
      return $localidad;
    }

    public function descargar($id) {
      $propiedad = Propiedad::findOrFail($id);
      return response()->download(public_path().$propiedad->pdf_url);
    }

    public function estados(Propiedad $propiedad) {
      $estados = $propiedad->estados()->orderBy('created_at', 'desc')->paginate(10);

      return view('admin.propiedades.estados')->with('propiedad', $propiedad)
                                              ->with('estados', $estados);
    }
}
