<?php

namespace Inmuebles\Http\Middleware;

use Closure;
use Session;
use Auth;

class ComprobarRol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $msg = null)
    {
        if (Auth::user() && Auth::user()->noEsAnunciante()) {
            if ($msg != null) {
                $request->session()->flash('mensaje', $msg);
            }
            return redirect('/usuarios/completar-perfil');
        }
        return $next($request);
    }
}
