<?php

namespace Inmuebles\Http\Controllers;

use Illuminate\Http\Request;
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
use Inmuebles\Models\Propiedades\Estado;
use Inmuebles\Models\Propiedades\Tipologia;
use Illuminate\Support\Facades\Auth;
use Inmuebles\Models\Propiedades\UnidadSuperficie;
use File;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PropiedadesController extends Controller {

    private $rules = [ 
        'titulo' => 'max:120|required',
        'superficie' => 'nullable|numeric',
        'localidad_id' => 'required',
        'monto' => 'nullable|numeric',
        'provincia_id' => 'required|exists:provincias,id',
        'tipo_propiedad_id' => 'required|exists:tipos_propiedad,id',
        'operacion_id' => 'required|exists:operaciones,id',
        'usos' => 'required|exists:usos,id',
        'superficie_descubierta' => 'nullable|numeric',
        'expensas' => 'nullable|numeric',
        'pdf_url' => 'nullable|mimes:pdf|max: 50000',
        'video_url' => ['nullable', 'regex:/^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/'],
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

    public function __construct(Request $request) {
        $this->middleware('comprobar:Para comenzar a publicar debe estar registrado', ['except' => [ 'show', 'compartir', 'estaInteresado', 'interesarse', 'contactar' ]]);
        $this->middleware('comprobarRol:Debes completar tus datos de registro para empezar a publicar', ['except' => ['show', 'compartir', 'estaInteresado', 'interesarse', 'contactar', 'propiedadesFavoritas']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $this->middleware('comprobar:Para comenzar a publicar debe estar registrado');

        $usuario = Auth::user();
        $filtroEstado = $request->get('estado_id');
        $estadoPropiedad = $filtroEstado != null ? Estado::find($filtroEstado)->nombre_filtros : null;
        $propiedades = $usuario->propiedades()
                               ->estado($filtroEstado)
                               ->orderBy('created_at', 'desc')
                               ->paginate(8)->appends(request()->query());

        return view('propiedades.index')->with('propiedades', $propiedades)
                                        ->with('filtro', $request->get('estado_id'))
                                        ->with('estados', Estado::orderBy('nombre')->get())
                                        ->with('estadoPropiedad', $estadoPropiedad);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $this->middleware('comprobarRol:Debes completar tus datos de registro para empezar a publicar');
        $this->middleware('comprobar:Para comenzar a publicar debe estar registrado');

        return view('propiedades.create')->with('provincias', Provincia::orderBy('nombre')->get())
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

        if($request->validate($this->rules, $this->messages)){
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
          }

          if ($request->has('ubicacion_publica')) {
              $propiedad->ubicacion_publica = 1;
          }
          else {
              $propiedad->ubicacion_publica = 0;
          }

          $propiedad->pdf_url = $fileName;
          $propiedad->localidad_id = $localidad->id;
          $propiedad->usuario_id =  Auth::user()->id;
          $service->agregar($propiedad, $request->input('caracteristicas', []), $request->input('usos'), $request->input('fotos'), $request->input('lat'), $request->input('long'));
        
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
    public function show($id, $slug, Request $request) {
        $usuario = Auth::user();
        $propiedad = Propiedad::where('id', $id)
                              ->orWhere('slug', $slug)->firstOrFail();
        $retorno = null;

        // si la propiedad no esta publicada
        if ($propiedad->estaPublicada() || 
          ($usuario && ($propiedad->soyPropietario($usuario) || $usuario->esAdmin()))
        ) {
          $mostrarDatosDeContacto = false;

          $validatedData = $request->validate([
            'email'       => 'email',
            'suscribirse' => 'boolean'
          ]);

          if ($request->has('email')) {
            $this->comprobarInteresado($request->get('email'), $propiedad, $request->get('suscribirse', true));
            $mostrarDatosDeContacto = true;

            $request->session()->put('emailInteresado', $request->get('email'));
          }

          $retorno = view('propiedades.show')->with('propiedad', $propiedad)
                                             ->with('mostrarDatosDeContacto', $mostrarDatosDeContacto);
      }
      else {
        $retorno = response('No Disponible', 404);
      }

      return $retorno;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $this->middleware('comprobar:Para comenzar a publicar debe estar registrado');

        $propiedad=Propiedad::findOrFail($id);
        if (!$propiedad->soyPropietario(Auth::user())) {
            abort(401, 'Acceso no permitido');
        }
        return view('propiedades.edit')->with('propiedad', $propiedad)
                                    ->with('provincias',Provincia::orderBy('nombre')->get())
                                    ->with('localidades', Localidad::where('provincia_id', $propiedad->localidad->provincia->id)->orderBy('nombre')->get())
                                    ->with('barrios',Barrio::where('localidad_id',$propiedad->localidad_id)->orderBy('nombre')->get())
                                    ->with('tipos', TipoPropiedad::orderBy('nombre')->get())
                                    ->with('operaciones', Operacion::orderBy('nombre')->get())
                                    ->with('tiposPropiedad', TipoPropiedad::find($propiedad->tipoPropiedad->id))
                                    ->with('usos', Uso::orderBy('nombre')->get())
                                    ->with('monedas', Moneda::orderBy('nombre')->get())
                                    ->with('tipologias', Tipologia::orderBy('id')->get())
                                    ->with('unidades', UnidadSuperficie::orderBy('id')->get());
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, PropiedadesService $service) {
        $propiedad = Propiedad::findOrFail($id);
        $localidad = $this->buscarLocalidad($request->all());

        // armamos un vector suplementario de reglas de validacion
        $reglasDeEdicion = $this->rules;
        // excluimos los campos de asignacion masiva
        $excluir = [ 'pdf_url' ];

        // si la propiedad no se puede editar por su estado actual
        if (!$propiedad->sePuedeEditar()) {
          // no validamos localidad o provincia
          unset($reglasDeEdicion['localidad_id']);
          unset($reglasDeEdicion['provincia_id']);

          // e ignoramos todos los campos de su ubicacion
          $excluir = array_merge($excluir, [ 
            'provincia_id', 'localidad_id', 'barrio_id', 'direccion', 'piso', 'departamento', 'referencia', 'ubicacion_publica'
          ]);
        }

        if($request->validate($reglasDeEdicion, $this->messages)) {

          $propiedad->fill($request->except($excluir));

          if ($request->has('precio_convenir')) {
            $propiedad->precio_convenir = 1;
            $propiedad->monto = null;
            $propiedad->moneda_id = null;
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
          $propiedad->usuario_id =  Auth::user()->id;
          $service->actualizar($propiedad, $request->input('caracteristicas', []), $request->input('usos'), $request->input('fotos'), $request->input('fotos-eliminar'), $request->input('lat'), $request->input('long'));

          return redirect()->route('propiedades.index')->with('exito', 'Propiedad actualizada con éxito');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, PropiedadesService $service) {
        $propiedad=Propiedad::findOrFail($id);
        if (File::exists(public_path($propiedad->pdf_url))) { 
            File::delete(public_path($propiedad->pdf_url));  
        }
        $service->borrar($propiedad);
        return redirect('/usuarios/mi-perfil')->with('exito', 'Propiedad eliminada con éxito');
    }

    public function ocultarPropiedad($id, PropiedadesService $service) {
        $this->middleware('comprobar:Para comenzar a publicar debe estar registrado');

        $propiedad=Propiedad::findOrFail($id);
        $service->ocultar($propiedad);
        return redirect('/usuarios/mi-perfil')->with('exito', 'Propiedad oculta');
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

    public function compartir($id, PropiedadesService $service, Request $request) {
        $validatedData = $request->validate([
          'email' => 'required|email'
        ]);

        $propiedad=Propiedad::findOrFail($id);
        $service->compartir($propiedad, $request->get('email'));

        return 'OK!';
    }

    public function estaInteresado($id, PropiedadesService $service, Request $request) {
      $propiedad = Propiedad::findOrFail($id);
      $emailInteresado = null;
      $retorno = null;

      // si tenemos un usuario logueado preguntamos
      if (Auth::check()) {
        // obtenemos su email
        $emailInteresado = Auth::user()->email;
      }
      else if ($request->session()->has('emailInteresado')) {
        // de lo contrario consultamos si dejó su email en la sesión
        $emailInteresado = $request->session()->get('emailInteresado');
      }

      // si disponemos del email del interesado
      if ($emailInteresado) {
        // si no estaba ya interesado lo agregamos
        $this->comprobarInteresado($emailInteresado, $propiedad);

        $retorno = $this->obtenerDatosDeContacto($propiedad);
      }

      return $retorno;
    }

    public function interesarse($id, Request $request) {
      $propiedad = Propiedad::findOrFail($id);

      $validatedData = $request->validate([
        'g-recaptcha-response' => 'required|recaptcha',
        'email'       => 'required|email',
        'suscribirse' => 'boolean'
      ], 
      [
        'g-recaptcha-response.required' => 'Por favor, asegúrate de que eres un humano',
        'g-recaptcha-response.recaptcha' => 'Captcha inválido'
      ]);

      $emailInteresado = $request->get('email');
      $suscribirse = $request->get('suscribirse');

      // si no estaba ya interesado lo agregamos
      $this->comprobarInteresado($emailInteresado, $propiedad, $suscribirse);

      // lo guardamos en la sesión para futuras referencias
      $request->session()->put('emailInteresado', $emailInteresado);
      return $this->obtenerDatosDeContacto($propiedad);
    }

    public function contactar($id, PropiedadesService $service, Request $request) {
        $validatedData = $request->validate([
          'g-recaptcha-response' => 'required|recaptcha',
          'email'       => 'required|email',
          'mensaje'     => 'required'
        ], 
        [
          'g-recaptcha-response.required' => 'Por favor, asegúrate de que eres un humano',
          'g-recaptcha-response.recaptcha' => 'Captcha inválido'
        ]);

        $email = $request->get('email');
        $telefono = $request->get('telefono');
        $mensaje = $request->get('mensaje');

        $propiedad = Propiedad::findOrFail($id);
        $this->comprobarInteresado($email, $propiedad);

        $service->contactar($propiedad, $email, $telefono, $mensaje);

        return 'OK!';
    }

    public function seleccionarCreditoParaPublicar(Propiedad $propiedad) {
      $creditos = Auth::user()->creditos()
                              ->habilitados()
                              ->groupBy('dias_totales', 'fecha_vencimiento', 'destacado')
                              ->selectRaw('count(*) as total, dias_totales, fecha_vencimiento, destacado, id')
                              ->get();

      return view('propiedades.publicar')->with('creditos', $creditos)
                                         ->with('propiedad', $propiedad);
    }

    public function publicar(Propiedad $propiedad, PropiedadesService $service, Request $request) {
      $usuario = Auth::user();

      $request->validate([
        'credito' => [ 'required',
          Rule::exists('creditos', 'id')->where(function ($query) use ($usuario) {
            $query->where('usuario_id', $usuario->id)
                  ->whereNull('propiedad_id')
                  ->where('fecha_vencimiento', '>=', Carbon::now());
          }),
        ]
      ], [
        'credito.required' => 'Debe seleccionar un Crédito para Publicar',
        'credito.exists'   => 'El Crédito seleccionado es inválido'
      ]);

      // obtenemos el credito a aplicar
      $credito = $usuario->creditos()
                         ->where('id', $request->get('credito'))
                         ->firstOrFail();

      // está todo ok, procedemos a publicar la propiedad
      $service->publicar($usuario, $propiedad, $credito);
      return redirect()->route('propiedades.show', $propiedad->slug)
                       ->with('exito', 'Felicitaciones! Tu Propiedad ya está publicada');
    }

    public function pausar(Propiedad $propiedad, PropiedadesService $service) {
      $usuario = Auth::user();

      $service->pausar($usuario, $propiedad);

      // redireccionamos sin mensaje porque ya se muestra el estado de la publicación
      return redirect()->route('propiedades.show', $propiedad->slug);
    }

    public function reanudar(Propiedad $propiedad, PropiedadesService $service) {
      $usuario = Auth::user();

      $service->reanudar($usuario, $propiedad);

      return redirect()->route('propiedades.show', $propiedad->slug)
                       ->with('exito', 'Has reanudado esta publicación');
    }

    public function finalizar(Propiedad $propiedad, PropiedadesService $service) {
      $usuario = Auth::user();

      $service->finalizar($usuario, $propiedad);

      // redireccionamos sin mensaje porque ya se muestra el estado de la publicación
      return redirect()->route('propiedades.show', $propiedad->slug);
    }

    public function darDeBaja(Propiedad $propiedad, PropiedadesService $service) {
      $usuario = Auth::user();

      $service->darDeBaja($usuario, $propiedad);

      // redireccionamos sin mensaje porque ya se muestra el estado de la publicación
      return redirect()->route('propiedades.show', $propiedad->slug)
                       ->with('info', 'La Publicación ha sido Dada de Baja de forma definitiva');
    }

    public function imprimir(Propiedad $propiedad) {
      return redirect()->route('propiedades.show', $propiedad->slug)->with('imprimir', true);
    }

    private function comprobarInteresado($email, Propiedad $propiedad, $suscribirse = true) {
      if (!$propiedad->estaInteresado($email)) {
        $propiedad->agregarInteresado($email, $suscribirse);
        $propiedad->save();
      }
    }

    private function obtenerDatosDeContacto(Propiedad $propiedad) {
      return [
        'telefono' => $propiedad->usuario->telefono,
        'email' => $propiedad->usuario->email,
      ];
    }

    public function propiedadesFavoritas() {
      $usuario = Auth::user();
      $propiedadesFavoritas = $usuario->favoritas()->get();

      return view('propiedades.favoritas')->with('favoritas', $propiedadesFavoritas);
    }
}
