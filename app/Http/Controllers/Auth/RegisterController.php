<?php

namespace Inmuebles\Http\Controllers\Auth;

use Inmuebles\Models\Usuarios\User;
use Inmuebles\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Inmuebles\Models\Facturacion\CondicionIva;
use Inmuebles\Models\Paquetes\Paquete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inmuebles\Models\Comun\Provincia;
use Inmuebles\Models\Comun\Localidad;
use File;
use Inmuebles\Mail\Registro\Bienvenida;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{

    private function rules() {
        return [
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ];
    }

    private function messages() {
        return [
            'email.unique' => 'Ya existe un usuario con ese email',
            'email.required' => 'Debe ingresar un email',
            'password.required' => 'Debe ingresar una contraseña',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
        ];
    }
    /*
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function showRegistrationForm() {
        return redirect()->route('login');
    }

    public function register(Request $request)
    {
        if($request->validate($this->rules(), $this->messages())){
            $user = $this->create($request);
            Auth::guard()->login($user);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \Inmuebles\Models\Usuarios\User
     */
    private function create(Request $request)
    { 
        $user = new User($request->all());
        $user->password = Hash::make($request->input('password'));
        $user->save();
        Mail::to($user->email)->send(new Bienvenida($user));
        return $user;
    }
}
