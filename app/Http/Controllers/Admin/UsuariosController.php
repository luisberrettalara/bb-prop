<?php

namespace Inmuebles\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Inmuebles\Models\Usuarios\User; 
use Inmuebles\Http\Controllers\Controller;
use Inmuebles\Models\Facturacion\CondicionIva;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Inmuebles\Models\Usuarios\Rol;
use Inmuebles\Models\Comun\Provincia;
use Inmuebles\Models\Paquetes\Paquete;
use Inmuebles\Models\Comun\Localidad;
use Illuminate\Support\Facades\Hash;
use File;

class UsuariosController extends Controller
{
		private function rules()
    {
      return [
          'foto_url' => 'mimes:jpeg,jpg,png|max:5000',
          'razon_social'=>'required|string|max:100',
          'email' => 'required|string|email|max:100|unique:users',
          'password' => 'required|string|min:6|confirmed',
          'direccion' => 'nullable|string|max:100',
          'telefono' => 'required|string|max:100',
          'condicion_iva_id' => 'required|exists:condiciones_iva,id',  
          'descripcion' => 'max:20000',
          'rol_id' => 'required',
      ];
    }

    private function messages() {
        return [
            'razon_social.required' => 'Debe ingresar la razón social',
            'razon_social.max' => 'La razón social no puede tener más de 100 caracteres',
            'foto_url.mimes' => 'El archivo debe ser en formato jpeg, jpg o png',
            'email.unique' => 'Ya existe un usuario con ese email',
            'email.required' => 'Debe ingresar un email',
            'password.required' => 'Debe ingresar una contraseña',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'direccion.max' => 'El direccion no puede tener más de 100 caracteres',
            'telefono.required' => 'Debe ingresar su número de teléfono',
            'telefono.max' => 'El teléfono no puede tener más de 100 caracteres',
            'cuit.required' => 'Debe ingresar su número de cuit',
            'condicion_iva_id.required' => 'Seleccione su condición de IVA',
            'rol_id.required' => 'Seleccione un rol',
        ];
    }

    public function autoCompletar(Request $request) { 
      return User::where('email', 'like', '%'.$request->query('term').'%')->get(); 
    }

    public function __construct(Request $request) {
      $this->middleware('admin');
    }

    public function index() {
       $usuario = User::orderBy('email')->paginate(8);
       return view('admin.usuarios.index')->with('usuario', $usuario);
    }

    public function create() {
    	return view('admin.usuarios.create')->with('condicion_iva', CondicionIva::orderBy('nombre')->get())
                                          ->with('roles', Rol::orderBy('nombre')->get())
                                          ->with('provincias', Provincia::orderBy('nombre')->get())
                                          ->with('usuario_facebook', session()->has('usuario_facebook') ? session()->get('usuario_facebook') : null);
    }

    public function store(Request $request) {
      if ($request->validate($this->rules(), $this->messages())) {
        $localidad = $this->buscarLocalidad($request->all());
    	  $fileName = '';
        if($request->hasFile('foto_url')) {
            $file = $request->file('foto_url');
            $path = public_path() . '/fotos/perfil/';
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($path, $fileName);
            $fileName = '/fotos/perfil/'. $fileName;
        }
        else if ($request->has('foto_url_facebook')){
            $fileName = $request->input('foto_url_facebook');
        }

        $usuario = new User($request->except('localidad_id'));
        $usuario->localidad_id = $localidad->id;
        $usuario->password = Hash::make($request->input('password'));
        $usuario->rol()->associate($request->input('rol_id'));

        $usuario->save();
        //Verificamos que haya un paquete de bienvenida y se lo asignamos al nuevo anunciante
        $paquetePorDefecto = Paquete::where('por_defecto', 1)->firstOrFail();
        
        if($paquetePorDefecto!=null) {
            $usuario->contratar($paquetePorDefecto);
        }
        return redirect()->route('admin.usuarios.index')->with('exito', 'Usuario agregado con éxito');
      }
    }

    public function edit($id) {
    	$usuario = User::findOrFail($id);
    	return view('admin.usuarios.edit')->with('usuario', $usuario)
   	                                    ->with('condiciones_iva', CondicionIva::orderBy('nombre')->get())
                                        ->with('provincias', Provincia::orderBy('nombre')->get())
   	                                    ->with('roles', Rol::orderBy('nombre')->get());
    }

    public function update(User $usuario, Request $request) {
        $request->validate([
        'foto_url' => 'mimes:jpeg,jpg,png|max:5000',
        'razon_social'=>'required|string|max:100',
        'email' => 'required|string|email|max:100|unique:users,email,' . $usuario->id,
        'password' => 'nullable|string|min:6|confirmed',
        'direccion' => 'nullable|string|max:100',
        'telefono' => 'nullable|string|max:100',
        'condicion_iva' => 'required|exists:condiciones_iva,id',
        'descripcion' => 'nullable|string|max:255',
        'rol_id' => 'required',
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

        // si cambia la condicion de IVA
        if ($usuario->condicionIva->id != $request->get('condicion_iva')) {
          $usuario->condicionIva()->dissociate();
          $usuario->condicionIva()->associate($request->get('condicion_iva'));
        }

        // si cambia el Rol
        if ($usuario->rol->id != $request->get('rol_id')) {
          $usuario->rol()->dissociate();
          $usuario->rol()->associate($request->input('rol_id'));
        }

        $usuario->save();

        return redirect()->route('admin.usuarios.index')->with('exito', 'Usuario actualizado con éxito');
    }

    public function destroy($id) {
    	$usuario = User::findOrFail($id);
      if (File::exists(public_path($usuario->foto_url))) {
        File::delete(public_path($usuario->foto_url));
      }
    	$usuario->delete();
    	return redirect()->route('admin.usuarios.index')->with('exito', 'Usuario eliminado con éxito');
    }

    public function creditos(User $user) {
      if ($user->noEsAnunciante()) {
        abort(400, 'Sólo puedes ver los créditos si el usuario es anunciante');
      }
      $creditos = $user->creditos()
                       ->orderBy('created_at', 'desc')
                       ->paginate(10);

      return view('admin.usuarios.creditos')->with('usuario', $user)
                                            ->with('creditos', $creditos);
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
}
