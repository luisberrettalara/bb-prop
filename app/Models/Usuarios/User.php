<?php

namespace Inmuebles\Models\Usuarios;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Inmuebles\Notifications\ResetPassword as ResetPasswordNotification;

use Inmuebles\Models\Paquetes\Paquete;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';
    
    protected $fillable = [
       'razon_social', 'email', 'direccion', 'telefono', 'persona_contacto', 'cuit', 'condicion_iva_id', 'descripcion', 'localidad_id', 'google_place_id', 'telefono_celular', 'sitio_web'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function condicionIva() {
        return $this->belongsTo('Inmuebles\Models\Facturacion\CondicionIva', 'condicion_iva_id');
    }

    public function rol() {
        return $this->belongsTo('Inmuebles\Models\Usuarios\Rol', 'rol_id');
    }

    public function creditos() {
        return $this->hasMany('Inmuebles\Models\Paquetes\Credito', 'usuario_id');
    }

    public function paquetesContratados() {
        return $this->hasMany('Inmuebles\Models\Paquetes\Paquete', 'usuario_id');
    }

    public function propiedades() {
        return $this->hasMany('Inmuebles\Models\Propiedades\Propiedad', 'usuario_id');
    }

    public function favoritas() {
        return $this->belongsToMany('Inmuebles\Models\Propiedades\Propiedad', 'propiedades_users_favoritas');
    }

    public function localidad() {
        return $this->belongsTo('Inmuebles\Models\Comun\Localidad');
    }

    public function esAdmin() {
        return $this->rol->esAdministrador();
    }

    public function esAnunciante() {
        return $this->rol->esAnunciante();
    }

    public function noEsAnunciante() {
        return $this->rol->noEsAnunciante();
    }

    public function getEmailRecortado() {
        $email = substr($this->email, 0, 7);
        return $email;
    }

    public function getTelefonoRecortado() {
        $telefono = substr($this->telefono, 0, 7);
        return $telefono;
    }

    public function getDomicilioCompleto() {
        $retorno = [];
        $calleNumero = [];

        if ($this->direccion) {
            $calleNumero[] = $this->direccion;
        }

        if ($calleNumero) {
            $retorno[] = implode(' ', $calleNumero);
        }

        if ($this->localidad) {
            $retorno[] = $this->localidad;
        }

        if ($this->localidad) {
            $retorno[] = $this->localidad->provincia;
        }

        return implode(', ', $retorno);
    }

    public function contratar(Paquete $paquete) {
        $this->creditos()->saveMany($paquete->fabricarCreditos());
    }

    public function favorita($propiedad) {
        //verificamos que la propiedad ya este marcada como favorita
        $existe = $this->favoritas->find($propiedad);
        if ($existe == null) {
            $this->favoritas()->attach($propiedad);
        }
        else {
            $this->favoritas()->detach($propiedad);
        }
    }

    public function tienePropiedadesPublicadas() {
        $propiedades = [];
        $propiedades = $this->propiedades()->get();
        foreach ($propiedades as $propiedad) {
            if ($propiedad->estaPublicada()) {
                return $propiedad;
            }
        }
    }

    public function esResponsableInscripto() {
        return $this->condicionIva->esResponsableInscripto();
    }

    public function getSitioWebUrl() {
        return 'http://' . $this->sitio_web;
    }
    
    public function __toString() {
        return $this->razon_social;
    }

    public function sendPasswordResetNotification($token) {
      $this->notify(new ResetPasswordNotification($token));
    }
}
