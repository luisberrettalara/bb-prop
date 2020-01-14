<?php

namespace Inmuebles\Models\Pedidos;

use Illuminate\Database\Eloquent\Model;

use Inmuebles\Models\Usuarios\User;
use DateTime;


class Pedido extends Model {

  public function paquete() {
    return $this->belongsTo('Inmuebles\Models\Paquetes\Paquete');
  }

  public function usuario() {
    return $this->belongsTo('Inmuebles\Models\Usuarios\User');
  }

  public function estado() {
    return $this->belongsTo('Inmuebles\Models\Pedidos\Estado');
  }

  public function estados() {
    return $this->hasMany('Inmuebles\Models\Pedidos\EstadoPedido');
  }

  public function scopeFiltros($query, $filtros) {
    if($filtros && count($filtros)>0) {
      if(array_key_exists('fechaDesde', $filtros) && array_key_exists('fechaHasta', $filtros)) {
        if(isset($filtros['fechaDesde'])) {
          $query->whereDate('created_at', '>=', $filtros['fechaDesde']);
        }
        if(isset($filtros['fechaHasta'])) {
          $query->whereDate('created_at', '<=', $filtros['fechaHasta']);
        }
      }
      if(array_key_exists('usuario_id', $filtros) && isset($filtros['usuario_id'])) {
        $query->where('usuario_id', $filtros['usuario_id']);
      }
      if(array_key_exists('estado_id', $filtros) && isset($filtros['estado_id'])) {
        $query->where('estado_id', $filtros['estado_id']);
      }
    }
  }

  public function scopeDelUsuario($query, User $usuario) {
    return $query->where('usuario_id', $usuario->id);
  }

  public function getTotalFormateado () {
    return number_format($this->total, 0, "", "");
  }

  public function agregarEstado(Estado $estado, $motivo = null) {
    $estadoPedido = new EstadoPedido;
    $estadoPedido->estado()->associate($estado);

    $this->estados()->save($estadoPedido);
    $this->estado()->associate($estado);
    $this->save();
  }

  public function acreditarAlUsuario() {
    $this->usuario->contratar($this->paquete);
  }

  public function toPreferenceArray () {
    // creamos la preferencia de mercadopago
    return [
      'items' => [
        [
          'id'          => $this->paquete->id,
          'title'       => $this->paquete->nombre,
          'quantity'    => 1,
          'currency_id' => 'ARS',
          'unit_price'  => (float) $this->total
        ]
      ],
      'external_reference' => $this->id,
      'back_urls' => [
        'success' => route('pedidos.exito', $this),
        'pending' => route('pedidos.pendiente', $this),
        'failure' => route('pedidos.rechazado', $this)
      ],
      'auto_return' => 'approved'
    ];
  }

  public function getImporteTotal() {
    return $this->getImporteNeto() + $this->getImporteIva();
  }

  public function getImporteIva($iva = 0.105) {
    return $this->total * $iva;
  }

  public function getImporteNeto() {
    return $this->total;
  }

  public function getImporteTotalFormato() {
    return number_format($this->getImporteTotal(), 2, '.', '');
  }

  public function getImporteIvaFormato($iva = 0.105) {
    return number_format($this->getImporteIva($iva), 2, '.', '');
  }

  public function getImporteNetoFormato() {
    return number_format($this->getImporteNeto(), 2, '.', '');
  }

  public function estaFacturado() {
    return $this->cae != null;
  }

  public function estaPagado() {
    return $this->estado->esAprobado();
  }

  public function getNumeroFacturaFormato() {
    return str_pad($this->punto_venta, 4, '0', STR_PAD_LEFT) . '-' . str_pad($this->numero_comprobante, 8, '0', STR_PAD_LEFT);;
  }

}
