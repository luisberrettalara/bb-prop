<?php

namespace Inmuebles\Http\Controllers;

use Inmuebles\Models\Usuarios\User; 
use Inmuebles\Models\Facturacion\CondicionIva;
use Inmuebles\Models\Propiedades\Propiedad;
use Inmuebles\Models\Pedidos\Pedido;
use Inmuebles\Models\Propiedades\Estado;
use Inmuebles\Models\Paquetes\Paquete;
use Inmuebles\Models\Comun\Provincia;
use Inmuebles\Models\Comun\Localidad;
use Inmuebles\Models\Usuarios\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use File;

class UsuariosController extends Controller
{

    public function __construct(Request $request) {
        $this->middleware('comprobar:Primero debes iniciar sesión');
    }

    public function verMiPerfil() {
      $usuario = Auth::user();
      $estadoPublicada  = Estado::where('nombre', 'Publicada')->firstOrFail();
      $creditos = $usuario->creditos()
                          ->habilitados()
                          ->orderBy('destacado', 'desc')
                          ->orderBy('fecha_vencimiento', 'asc')
                          ->get();

      $cantidadDestacados = $usuario->creditos()
                                    ->where('destacado', 1)
                                    ->habilitados()
                                    ->count();

      $cantidadStandard = $usuario->creditos()
                                  ->where('destacado', 0)
                                  ->habilitados()
                                  ->count();

      $propiedades = $usuario->propiedades()->where('estado_id', $estadoPublicada->id)->orderBy('created_at', 'desc')->take(4)->get();
      $favoritas = $usuario->favoritas()->take(4)->get();
      $pedidos = Pedido::delUsuario($usuario)->orderBy('id', 'desc')->get();

      return view('usuarios.perfil')->with('usuario', $usuario)
                                    ->with('usuario_facebook', session()->has('usuario_facebook') ? session()->get('usuario_facebook') : null)
                                    ->with('condiciones', CondicionIva::orderBy('nombre')->get())
                                    ->with('propiedades', $propiedades)
                                    ->with('favoritas', $favoritas)
                                    ->with('creditos', $creditos)
                                    ->with('pedidos', $pedidos)
                                    ->with('destacados', $cantidadDestacados)
                                    ->with('standard', $cantidadStandard);
    }

    public function editarMiPerfil() {
        $usuario = Auth::user();
        return view('usuarios.edit')->with('usuario', $usuario)
                                    ->with('provincias', Provincia::orderBy('nombre')->get())
                                    ->with('condiciones_iva', CondicionIva::orderBy('nombre')->get());
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(User $usuario, Request $request) {
        $request->validate([
          'foto_url' => 'mimes:jpeg,jpg,png|max:5000',
          'razon_social'=>'nullable|string|max:100',
          'email' => 'required|string|email|max:100|unique:users,email,' . $usuario->id,
          'password' => 'nullable|string|min:6|confirmed',
          'direccion' => 'nullable|string|max:100',
          'telefono' => 'nullable|string|max:100',
          'cuit' => 'nullable|string|max:30', 
          'condicion_iva' => 'nullable|exists:condiciones_iva,id',
          'descripcion' => 'nullable|string|max:255',
          'telefono_celular' => 'nullable|string|max:100',
        ]);

        $usuario->update($request->except('localidad_id'));
        if($request->hasFile('foto_url')) {
          if (File::exists(public_path($usuario->foto_url))) {
            File::delete(public_path($usuario->foto_url));
          }
          $fileName = ''; 
          $file = $request->file('foto_url');
          $path = public_path() . '/fotos/perfil/';
          $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
          $file->move($path, $fileName);
          $fileName = '/fotos/perfil/'. $fileName;
          $usuario->foto_url = $fileName;
        }

        if($request->get('localidad_id')!=null) {
          $localidad = $this->buscarLocalidad($request->all());
          $usuario->localidad_id = $localidad->id;
        }

        // si cambiamos el password
        if ($request->has('password') && !empty($request->get('password'))) {
          $usuario->password = Hash::make($request->get('password'));
        }
        // si cambia la condicion de IVA
        if ($request->has('condicion_iva') && (!$usuario->condicionIva || $usuario->condicionIva->id != $request->get('condicion_iva'))) {
          $usuario->condicionIva()->dissociate();
          $usuario->condicionIva()->associate($request->get('condicion_iva'));
        }

        $usuario->save();

        return redirect('/usuarios/mi-perfil')->with('mensaje', 'Tus datos fueron actualizados correctamente');
    }

    public function favorita(Propiedad $propiedad, User $usuario) {
      $usuario->favorita($propiedad->id);
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

    //Metodo para vista de completar datos cuando se quiere publicar y el usuario no es del tipo anunciante
    public function completarDatos() {
      $usuario = Auth::user();
      return view('usuarios.completar-perfil')->with('usuario', $usuario)
                                              ->with('provincias', Provincia::orderBy('nombre')->get())
                                              ->with('condiciones_iva', CondicionIva::orderBy('nombre')->get());
    }

    public function updateARolAnunciante(User $usuario, Request $request) {
        $request->validate([
          'foto_url' => 'mimes:jpeg,jpg,png|max:5000',
          'razon_social'=>'required|string|max:100',
          'localidad_id' => 'required|string',
          'provincia_id' => 'required|exists:provincias,id',
          'direccion' => 'required|string|max:100',
          'telefono' => 'required|string|max:100',
          'cuit' => 'required|string|max:30', 
          'condicion_iva' => 'required|exists:condiciones_iva,id',
          'descripcion' => 'nullable|string|max:255',
          'telefono_celular' => 'nullable|string|max:100',
        ], ['razon_social.required' => 'Ingresa la razón social',
            'direccion.required' => 'Ingresa la dirección',
            'telefono.required' => 'Ingresa el número de teléfono',
            'cuit.required' => 'Ingresa la número de CUIT',
            'condicion_iva.required' => 'Selecciona la condición de IVA',
            'foto_url.mimes' => 'La foto debe ser del tipo jpeg, jpg o png',
            'localidad_id.required' => 'Ingresa la localidad',
            'provincia_id.required' => 'Selecciona la provincia',
        ]);

        $localidad = $this->buscarLocalidad($request->all());
        $usuario->update($request->except('localidad_id'));
        if($request->hasFile('foto_url')) {
          if (File::exists(public_path($usuario->foto_url))) {
            File::delete(public_path($usuario->foto_url));
          }

          $fileName = ''; 
          $file = $request->file('foto_url');
          $path = public_path() . '/fotos/perfil/';
          $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
          $file->move($path, $fileName);
          $fileName = '/fotos/perfil/'. $fileName;

          $usuario->foto_url = $fileName;
        }

        $usuario->localidad_id = $localidad->id;

        // si cambiamos el password
        if ($request->has('password') && !empty($request->get('password'))) {
          $usuario->password = Hash::make($request->get('password'));
        }

        $usuario->condicionIva()->associate($request->get('condicion_iva'));

        //buscamos el rol del anunciante para luego asociarlo al usuario
        $rol = Rol::where('nombre', 'Anunciante')->first();
        $usuario->rol()->dissociate();
        $usuario->rol()->associate($rol);

        //Verificamos que haya un paquete de bienvenida y se lo asignamos al nuevo anunciante
        $paquetePorDefecto = Paquete::where('por_defecto', 1)->first();
        
        if($paquetePorDefecto!=null) {
            $usuario->contratar($paquetePorDefecto);
        }

        $usuario->save();
        return redirect()->route('propiedades.create')->with('mensaje', 'Tus datos fueron completados correctamente, ahora ya podés publicar tus propiedades!');
    }
}
