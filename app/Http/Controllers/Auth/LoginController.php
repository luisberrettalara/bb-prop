<?php

namespace Inmuebles\Http\Controllers\Auth;

use Inmuebles\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Inmuebles\Models\Facturacion\CondicionIva;
use Inmuebles\Models\Usuarios\User;
use Illuminate\Support\Facades\Auth;
use Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleProviderCallback()
    {
        // Obtenemos los datos del usuario
        $social_user = Socialite::driver('facebook')->stateless()->user(); 
        // Comprobamos si el usuario ya existe
        if ($user = User::where('email', $social_user->email)->first()) { 
            Auth::login($user);
            return redirect()->to($this->redirectTo); 
        } else {
            // En caso de que no exista creamos un nuevo usuario con sus datos.
            $user = User::create([
                'persona_contacto' => $social_user->name,
                'email' => $social_user->email,
                'foto_url' => $social_user->avatar,
            ]);
            Auth::login($user);
            return redirect()->to($this->redirectTo); // Login y redirección
        }
    }
}
