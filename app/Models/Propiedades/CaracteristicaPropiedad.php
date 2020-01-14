<?php

namespace Inmuebles\Models\Propiedades;

use Illuminate\Database\Eloquent\Model;

class CaracteristicaPropiedad extends Model
{
    protected $table = 'caracteristicas_propiedad';

    protected $fillable = ['caracteristica_id','valor'];

    public function caracteristica() {
      return $this->belongsTo('Inmuebles\Models\Propiedades\Caracteristica');
    }
}
