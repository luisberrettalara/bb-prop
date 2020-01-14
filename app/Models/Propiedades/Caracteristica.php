<?php

namespace Inmuebles\Models\Propiedades;

use Illuminate\Database\Eloquent\Model;

class Caracteristica extends Model
{
    protected $table = 'caracteristicas';

    protected $fillable = ['nombre', 'es_servicio', 'unidad', 'tipo_caracteristica_id'];

    public function tipoCaracteristica() {
        return $this->belongsTo('Inmuebles\Models\Propiedades\TipoCaracteristica');
    }

    public function tiposPropiedad() {
        return $this->belongsToMany('Inmuebles\Models\Propiedades\TipoPropiedad', 'tipos_propiedad_caracteristicas');
    }

    public function opciones() {
        return $this->hasMany('Inmuebles\Models\Propiedades\CaracteristicaOpcion');
    }

    public function esDropdown() {
        return $this->tipoCaracteristica->esDropdown();
    }

    public function esOpcion() {
        return $this->tipoCaracteristica->esOpcion();
    }
}
