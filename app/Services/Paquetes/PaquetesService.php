<?php

namespace Inmuebles\Services\Paquetes;

use Inmuebles\Models\Paquetes\Paquete;
use Inmuebles\Models\Propiedades\Propiedad;
use Inmuebles\Models\Propiedades\Estado;
use Inmuebles\Models\Usuarios\User;
use Inmuebles\Mail\Creditos\FinalizacionCredito;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;

class PaquetesService {

  /**
   * Registramos la contratación de un Paquete para un Usuario
   * y creamos los Créditos disponibles para el Usuario.
   * 
   * @param  User    $usuario destinatario del Paquete
   * @param  Collection $paquetes Paquetes a asignar
   */
  public function asignarVarios(User $usuario, Collection $paquetes) {

    // iniciamos una transaccion para mantener la consistencia
    // en caso de error
    DB::transaction(function () use ($usuario, $paquetes) {
        // contratamos cada paquete
        $paquetes->each(function ($paquete) use ($usuario) {
          $usuario->contratar($paquete);
        });
    });
  }

  /**
   * Procedemiento que descuenta 1 días a los días disponibles
   * para los Créditos asociados a las Publicaciones que han 
   * estado Publicadas el día de hoy.
   */
  public function descontarDiasDeCreditos() {
    $propiedades = [];
    $estadoPublicada  = Estado::where('nombre', 'Publicada')->firstOrFail();
    $estadoPausada    = Estado::where('nombre', 'Pausada')->firstOrFail();
    $estadoFinalizada = Estado::where('nombre', 'Finalizada')->firstOrFail();
    $hoy = Carbon::today();

    // obtenemos las propiedades que están con estado "Publicada"
    $propiedades = Propiedad::where('estado_id', $estadoPublicada->id)
                            ->orWhere(function ($query) use ($estadoPausada, $hoy) {
                              $query->where('estado_id', $estadoPausada->id)
                                    ->whereHas('estados', function ($q) use ($estadoPausada, $hoy) {
                                        $q->where('estado_id', $estadoPausada->id)
                                          ->where('created_at', '>=', $hoy);
                                    });
                              });

    // recorremos los resultados utlizando un cursor
    // para mejorar la eficiencia ante muchos resultados
    foreach ($propiedades->cursor() as $propiedad) {
      Log::info('Recorriendo Propiedad', [ 'propiedad' => $propiedad ]);

      // le descontamos un día de crédito
      $propiedad->descontarUnDiaDeCredito();

      // controlamos si la publicacion ha finalizado
      if ($propiedad->haFinalizado()) {
        // disociamos el crédito y cambiamos su estado
        $propiedad->finalizar($estadoFinalizada);
      }
    }

    return $propiedades->count();
  }

  public function enviarMailCreditosProximosAFinalizar() {
    $propiedades = [];
    $estadoPublicada  = Estado::where('nombre', 'Publicada')->firstOrFail();
    $estadoPausada    = Estado::where('nombre', 'Pausada')->firstOrFail();
    $hoy = Carbon::today();

    $propiedades = Propiedad::where('estado_id', $estadoPublicada->id)->where('credito_id', '!=', null)
                            ->orWhere(function ($query) use ($estadoPausada, $hoy) {
                              $query->where('estado_id', $estadoPausada->id)
                                    ->whereHas('estados', function ($q) use ($estadoPausada, $hoy) {
                                        $q->where('estado_id', $estadoPausada->id)
                                          ->where('created_at', '>=', $hoy);
                                    });
                              });

    foreach($propiedades->cursor() as $propiedad) {
      Log::info('Recorriendo Propiedad', ['propiedad' => $propiedad]);
      if ($propiedad->credito->dias_disponibles == env('DIAS_AL_VENCIMIENTO', '')) {
        //Esta funcionalidad no se puede probar en local porque mailtrap solo permite enviar hasta 3 mails por segundo.
        Mail::to($propiedad->usuario->email)->queue((new FinalizacionCredito($propiedad))->onQueue('emails'));
      }
    }
  }
}
