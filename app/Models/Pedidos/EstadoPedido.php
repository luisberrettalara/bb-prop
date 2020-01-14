<?php

namespace Inmuebles\Models\Pedidos;

use Illuminate\Database\Eloquent\Model;

class EstadoPedido extends Model {

  protected $table = 'pedidos_estados';

  public function estado() {
    return $this->belongsTo('Inmuebles\Models\Pedidos\Estado');
  }

}
