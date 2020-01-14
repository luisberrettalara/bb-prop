<?php

namespace Inmuebles\Models\Propiedades;

use Illuminate\Database\Eloquent\Model;

class EstadoPropiedad extends Model {

  protected $table = 'propiedades_estados';

  public function estado() {
    return $this->belongsTo('Inmuebles\Models\Propiedades\Estado');
  }

}
