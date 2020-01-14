<?php

namespace Inmuebles\Models\Comun;

use Illuminate\Database\Eloquent\Model;

class Barrio extends Model
{
   protected $table = 'barrios';

   protected $fillable = ['nombre', 'localidad_id'];

   public function localidad() {
   		return $this->belongsTo('Inmuebles\Models\Comun\Localidad');
   }

   public function __toString() {
    return $this->nombre;
   }
}
