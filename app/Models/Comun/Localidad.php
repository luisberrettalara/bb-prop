<?php

namespace Inmuebles\Models\Comun;

use Illuminate\Database\Eloquent\Model;

class Localidad extends Model
{
    protected $table = 'localidades';

    protected $fillable = ['nombre', 'provincia_id', 'google_place_id'];

    public function barrios() {
    	return $this->hasMany('Inmuebles\Models\Comun\Barrio');
    }

    public function provincia() {
    	return $this->belongsTo('Inmuebles\Models\Comun\Provincia');
    }

    public function __toString() {
      return $this->nombre;
    }

}
