<?php

namespace Inmuebles\Models\Pedidos;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model {

  protected $table = 'estados_pedidos';

  public function scopeSlug($query, $slug) {
    return $query->where('slug', $slug);
  }

  public function esAprobado() {
    return $this->nombre === 'Aprobado';
  }

  public function __toString() {
    return $this->nombre;
  }

}
