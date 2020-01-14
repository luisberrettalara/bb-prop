<?php

namespace Inmuebles\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inmuebles\Mail\Admin\Contacto;

class ContactoController extends Controller
{
  public function contactar(Request $request)
  {
    $validatedData = $request->validate([
      'g-recaptcha-response' => 'required|recaptcha',
      'email' => 'required|email',
      'mensaje' => 'required'
    ],
    [
      'g-recaptcha-response.required' => 'Por favor, asegúrate de que eres un humano',
      'g-recaptcha-response.recaptcha' => 'Captcha inválido'
    ]);

    $email = $request->get('email');
    $mensaje = $request->get('mensaje');
    Mail::to(config('mail.from.address'))->send(new Contacto($email, $mensaje));
     
    return 'OK!';
  }
}
