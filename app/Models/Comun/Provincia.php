<?php

namespace Inmuebles\Models\Comun;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected $table = 'provincias';

    protected $fillable = ['nombre'];

    public function localidades(){
    	return $this->hasMany('Inmuebles\Models\Comun\Localidad');
    }

    public function __toString() {
      return $this->nombre;
    }
}
