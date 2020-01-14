<?php

namespace Inmuebles\Models\Propiedades;

use Illuminate\Database\Eloquent\Model;

class Uso extends Model
{
    protected $table = 'usos';

    protected $fillable = ['nombre'];
}
