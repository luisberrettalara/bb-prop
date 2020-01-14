<?php

namespace Inmuebles\Models\Paquetes;

use Inmuebles\Models\Usuarios\User;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Credito extends Model {

  protected $table = 'creditos';

  /**
   * The attributes that should be mutated to dates.
   *
   * @var array
   */
  protected $dates = ['fecha_vencimiento'];

  public function calcularFechaVencimiento($hoy, $diasAlVencimiento) {
    $this->fecha_vencimiento = Carbon::now()->addDays($diasAlVencimiento);
  }

  public function propiedad() {
    return $this->belongsTo('Inmuebles\Models\Propiedades\Propiedad');
  }

  public function scopeHabilitados($query) {
    return $query->whereNull('propiedad_id')
                 ->where('fecha_vencimiento', '>=', Carbon::now());
  }

  public function tienePropiedad() {
    return $this->propiedad()->count() > 0;
  }

  /**
   * Comprobamos si aún quedan días disponibles para este 
   * Crédito, en tal caso devuelve true.
   * 
   * @return boolean si aún quedan días disponibles
   */
  public function estaDisponible() {
    return $this->dias_disponibles > 0;
  }

  /**
   * Comprobamos si el Crédito está vencido según la fecha actual
   * 
   * @return boolean si está vencido
   */
  public function estaVencido() {
    return Carbon::now()->greaterThan($this->fecha_vencimiento);
  }

  /**
   * Devuelve true si este Crédito no se encuentra asociado
   * a ninguna Propiedad, por lo que puede ser vinculado.
   * 
   * @return boolean si está libre para ser asociado
   */
  public function estaLibre() {
    return !$this->tienePropiedad();
  }

  /**
   * Disminuimos los días disponibles para este Crédito en
   * una unidad.
   */
  public function disminuirDiasDisponibles() {
    $this->dias_disponibles--;
  }

  /**
   * Comprobamos si este credito pertenece al usuario
   * indicado por parámetros
   * 
   * @param  User $usuario
   * @return boolean si pertenece al usuario
   */
  public function perteneceA(User $usuario) {
    return $this->usuario_id === $usuario->id;
  }

  /**
   * Comprobamos si quedan días disponibles para este
   * Crédito de Publicación
   */
  public function haFinalizado() {
    return $this->dias_disponibles <= 0 || $this->estaVencido();
  }

  public function __toString() {
    return 'Crédito ' . ($this->destacado ? 'destacado' : 'standard') . ' disponible por ' . $this->dias_disponibles . ' días';
  }

}
