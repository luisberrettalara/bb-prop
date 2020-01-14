<?php

namespace Inmuebles\Models\Paquetes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Inmuebles\Models\Paquetes\Credito;

use Carbon\Carbon;

class TipoCredito extends Model {

  use SoftDeletes;

  protected $table = 'tipos_credito';

  protected $fillable = [ 'duracion_en_dias', 'dias_al_vencimiento', 'destacado' ];

  /**
   * The attributes that should be mutated to dates.
   *
   * @var array
   */
  protected $dates = ['deleted_at'];

  public function scopeStandard($query) {
    return $query->where('destacado', false);
  }

  public function scopeDestacados($query) {
    return $query->where('destacado', true);
  }

  /**
   * Creamos un nuevo Crédito para disponibilizarlo a un Usuario
   * inicializándolo al día de la fecha calculando su vencimiento
   * en base a los días especificados.
   * 
   * @return Credito un nuevo Crédito disponible
   */
  public function fabricarCredito() {
    $credito = new Credito;
    $credito->dias_disponibles = $this->duracion_en_dias;
    $credito->dias_totales = $this->duracion_en_dias;
    $credito->destacado = $this->destacado;
    $credito->calcularFechaVencimiento(Carbon::now(), $this->dias_al_vencimiento);

    return $credito;
  }

  public function esDestacado() {
    return $this->destacado;
  }

}
