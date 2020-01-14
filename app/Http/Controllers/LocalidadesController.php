<?php

namespace Inmuebles\Http\Controllers;

use Illuminate\Http\Request;
use Inmuebles\Models\Comun\Localidad;

class LocalidadesController extends Controller
{
    public function listar($id) {
        return Localidad::where('provincia_id', $id)->orderBy('nombre')->get();
    }
}
