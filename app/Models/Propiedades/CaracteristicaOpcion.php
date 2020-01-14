<?php

namespace Inmuebles\Models\Propiedades;

use Illuminate\Database\Eloquent\Model;

class CaracteristicaOpcion extends Model
{
    protected $table = 'caracteristicas_opciones';

    protected $fillable = ['nombre', 'caracteristica_id'];
}
