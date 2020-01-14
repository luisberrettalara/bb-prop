<?php

namespace Inmuebles\Models\Propiedades;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model {

  protected $table = 'estados_propiedades';

  protected $fillable = [ 'nombre' ];

  public function esCreada() {
    return $this->nombre === 'Creada';
  }

  public function esPublicada() {
    return $this->nombre === 'Publicada';
  }

  public function esFinalizada() {
    return $this->nombre === 'Finalizada';
  }

  public function esPausada() {
    return $this->nombre === 'Pausada';
  }

  public function __toString() {
    return $this->nombre;
  }
}
