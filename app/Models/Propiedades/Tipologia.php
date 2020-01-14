<?php

namespace Inmuebles\Models\Propiedades;

use Illuminate\Database\Eloquent\Model;

class Tipologia extends Model
{
    protected $table = 'tipologias';

    protected $fillable = ['nombre'];
}
