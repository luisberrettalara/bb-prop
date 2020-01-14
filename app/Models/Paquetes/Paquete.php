<?php

namespace Inmuebles\Models\Paquetes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Inmuebles\Models\Paquetes\TipoCredito;

class Paquete extends Model {

  use SoftDeletes;

  /**
   * The attributes that should be mutated to dates.
   *
   * @var array
   */
  protected $dates = ['deleted_at'];

  protected $fillable = [ 'nombre', 'icono_url', 'disponible', 'precio', 'por_defecto'];

  public function detalle() {
    return $this->hasMany('Inmuebles\Models\Paquetes\DetallePaquete');
  }

  public function scopeDisponibles($query) {
    return $query->where('disponible', true);
  }

  public function creditosStandard() {
    $creditos = 0;

    foreach ($this->detalle as $detalle) {
      if (!$detalle->esDestacado()) {
        $creditos += $detalle->cantidad;
      }
    }

    return $creditos;
  }

  public function obtenerDuracionCreditosStandard() {
    foreach ($this->detalle as $detalle) {
      if(!$detalle->esDestacado())
        return $detalle->tipoCredito->duracion_en_dias;
    }
  }

  public function creditosDestacados() {
    $creditos = 0;

    foreach ($this->detalle as $detalle) {
      if ($detalle->esDestacado()) {
        $creditos += $detalle->cantidad;
      }
    }

    return $creditos;
  }

  public function obtenerDuracionCreditosDestacados() {
    foreach ($this->detalle as $detalle) {
      if($detalle->esDestacado())
        return $detalle->tipoCredito->duracion_en_dias;
    }
  }

  public function obtenerVencimiento() {
    foreach ($this->detalle as $detalle) {
      return $detalle->tipoCredito->dias_al_vencimiento;
    }
  }

  public function precioFinal() {
    return $this->precio;
  }

  public function precioConComision($comision, $iva = 0.105) {
    return round($this->precio * (1 + $comision) * (1 + $iva), 2);
  }

  public function getCantidadParaTipo(TipoCredito $tipo) {
    $retorno = null;

    if ($this->detalle()->tipo($tipo)->count() > 0) {
      $retorno = $this->detalle()->tipo($tipo)->first()->cantidad;
    }

    return $retorno;
  }

  public function agregarDetalle(TipoCredito $tipoCredito, $cantidad) {
    $detalle = new DetallePaquete;
    $detalle->cantidad = $cantidad;
    $detalle->tipoCredito()->associate($tipoCredito);

    // ...y lo agregamos al paquete
    $this->detalle()->save($detalle);
  }

  public function tieneCreditosDeTipo(TipoCredito $tipoCredito) {
    return $this->detalle()->tipo($tipoCredito)->count() > 0;
  }

  public function obtenerDetalleParaTipo(TipoCredito $tipoCredito) {
    return $this->detalle()->tipo($tipoCredito)->first();
  }

  public function cambiarDisponibilidad() {
    $this->disponible = !$this->disponible;
    $this->save();
  }

  public function estaDisponible() {
    return $this->disponible;
  }

  public function fabricarCreditos() {
    $creditos = [];

    // recorremos el detalle de creditos agregados
    foreach ($this->detalle as $detalle) {
      $creditos = array_merge($creditos, $detalle->fabricarCreditos());
    }

    return $creditos;
  }

  public function __toString() {
    return $this->nombre;
  }

}
