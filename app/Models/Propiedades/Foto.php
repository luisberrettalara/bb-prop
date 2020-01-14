<?php

namespace Inmuebles\Models\Propiedades;

use Inmuebles\Events\FotoDeleted;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
	use Notifiable;

    protected $table = 'fotos';

    protected $fillable = ['url', 'thumb_url', 'descripcion', 'propiedad_id', 'alto', 'ancho'];
  
  	protected $dispatchesEvents = [
        'deleted' => FotoDeleted::class,
    ];
}
