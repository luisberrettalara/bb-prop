<?php

namespace Inmuebles\Models\Usuarios;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{

	protected $table = 'roles';
    
    protected $fillable = ['nombre'];

    public function esAdministrador() {
      return $this->nombre === 'Administrador';
    }

    public function esAnunciante() {
      return $this->nombre === 'Anunciante';
    }

    public function noEsAnunciante() {
      return $this->nombre === 'Usuario';
    }
}
