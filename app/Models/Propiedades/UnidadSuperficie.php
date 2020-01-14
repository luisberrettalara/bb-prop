<?php

namespace Inmuebles\Models\Propiedades;

use Illuminate\Database\Eloquent\Model;

class UnidadSuperficie extends Model
{
    protected $table = 'unidades_superficie';

    protected $fillable = ['nombre'];

    public function __toString() {
      return $this->nombre;
    }
}
