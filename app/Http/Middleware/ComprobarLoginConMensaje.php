<?php

namespace Inmuebles\Http\Middleware;

use Closure;
use Session;

class ComprobarLoginConMensaje
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $msg = null) {
       if($request->user() == null) {
        if($msg != null) {
          $request->session()->flash('mensaje', $msg); 
       }
        return redirect('/login');
       }
        return $next($request);
    }
}
