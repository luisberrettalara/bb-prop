<?php

namespace Inmuebles\Models\Propiedades;

use Illuminate\Database\Eloquent\Model;

class TipoCaracteristica extends Model
{
    protected $table = 'tipos_caracteristica';

    protected $fillable = ['nombre'];

    public function esDropdown() {
      return $this->nombre == 'Dropdown';
    }

    public function esOpcion() {
      return $this->nombre == 'Opcion';
    }
}
