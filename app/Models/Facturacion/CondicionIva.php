<?php

namespace Inmuebles\Models\Facturacion;

use Illuminate\Database\Eloquent\Model;

class CondicionIva extends Model
{
    //
    protected $table = 'condiciones_iva';

    protected $fillable = ['nombre'];

    public function esResponsableInscripto() {
        return $this->nombre === 'Responsable Inscripto';
    }
}
