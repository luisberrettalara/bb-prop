<?php

namespace Inmuebles\Models\Propiedades;

use Illuminate\Database\Eloquent\Model;

class Interesado extends Model
{
    protected $table = 'propiedades_interesados';
    protected $fillable = [ 'email', 'suscribirse' ];
}
