<?php

namespace Inmuebles\Models\Paquetes;

use Inmuebles\Models\Paquetes\TipoCredito;
use Illuminate\Database\Eloquent\Model;

class DetallePaquete extends Model {

  protected $table = 'paquetes_detalle';

  public function tipoCredito() {
    return $this->belongsTo('Inmuebles\Models\Paquetes\TipoCredito');
  }

  public function esDestacado() {
    return $this->tipoCredito->esDestacado();
  }

  public function scopeTipo($query, TipoCredito $tipo) {
    return $query->where('tipo_credito_id', $tipo->id);
  }

  public function actualizarCantidad($nuevaCantidad) {
    $this->cantidad = $nuevaCantidad;
    $this->save();
  }

  public function fabricarCreditos() {
    $creditos = [];

    for ($i = 0; $i < $this->cantidad; $i++) {
      $creditos[] = $this->tipoCredito->fabricarCredito();
    }

    return $creditos;
  }

}
