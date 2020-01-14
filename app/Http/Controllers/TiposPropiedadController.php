<?php

namespace Inmuebles\Http\Controllers;

use Inmuebles\Models\Propiedades\TipoPropiedad;

class TiposPropiedadController extends Controller
{
    public function listar($id) {
        return TipoPropiedad::findOrFail($id)
                            ->caracteristicas()
                            ->with('opciones')
                            ->get();
    }
}
