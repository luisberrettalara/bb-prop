<?php

namespace Inmuebles\Models\Propiedades;

use Illuminate\Database\Eloquent\Model;
use Inmuebles\Models\Usuarios\User;
use Inmuebles\Models\Propiedades\Caracteristica;
use Inmuebles\Models\Propiedades\Uso;
use Inmuebles\Models\Propiedades\TipoPropiedad;
use Inmuebles\Models\Paquetes\Credito;

class Propiedad extends Model
{
    protected $table = 'propiedades';

    protected $fillable = ['titulo', 'direccion', 'piso', 'departamento', 'lat', 'long', 'descripcion', 'superficie', 'monto', 'barrio_id', 'localidad_id', 'tipo_propiedad_id', 'operacion_id', 'oculta', 'foto_portada_id', 'moneda_id', 'google_place_id', 'tipologia_id', 'expensas', 'superficie_cubierta', 'precio_convenir', 'pdf_url', 'video_url', 'referencia', 'unidad_superficie_id', 'mostrar_publicamente'];

    public function tipoPropiedad() {
        return $this->belongsTo('Inmuebles\Models\Propiedades\TipoPropiedad');
    }

    public function operacion() {
        return $this->belongsTo('Inmuebles\Models\Propiedades\Operacion');
    }

    public function localidad() {
        return $this->belongsTo('Inmuebles\Models\Comun\Localidad');
    }

    public function barrio() {
        return $this->belongsTo('Inmuebles\Models\Comun\Barrio');
    }

    public function usuario() {
        return $this->belongsTo('Inmuebles\Models\Usuarios\User');
    }

    public function fotos() {
        return $this->hasMany('Inmuebles\Models\Propiedades\Foto');
    }

    public function fotoPortada() {
        return $this->belongsTo('Inmuebles\Models\Propiedades\Foto');
    }

    public function usos() {
        return $this->belongsToMany('Inmuebles\Models\Propiedades\Uso', 'usos_propiedad');
    }

    public function caracteristicasPropiedad() {
        return $this->hasMany('Inmuebles\Models\Propiedades\CaracteristicaPropiedad');
    }

    public function moneda() {
        return $this->belongsTo('Inmuebles\Models\Propiedades\Moneda');
    }

    public function unidadSuperficie() {
        return $this->belongsTo('Inmuebles\Models\Propiedades\UnidadSuperficie');
    }

    public function tipologia() {
        return $this->belongsTo('Inmuebles\Models\Propiedades\Tipologia');
    }

    public function interesados() {
        return $this->hasMany('Inmuebles\Models\Propiedades\Interesado');
    }

    public function credito() {
        return $this->belongsTo('Inmuebles\Models\Paquetes\Credito');
    }

    public function estado() {
        return $this->belongsTo('Inmuebles\Models\Propiedades\Estado');
    }

    public function estados() {
        return $this->hasMany('Inmuebles\Models\Propiedades\EstadoPropiedad');
    }

    public function caracteristicas() {
        $array = [];
        foreach ($this->caracteristicasPropiedad as $idx => $c) {
            $array[] = [
                'idx' => $idx,
                'tipo_caracteristica_id' => $c->caracteristica->tipoCaracteristica->id,
                'caracteristica_id' => $c->caracteristica->id,
                'nombre' => $c->caracteristica->nombre,
                'unidad' => $c->caracteristica->unidad,
                'es_servicio' => $c->caracteristica->es_servicio,
                'valor' => $c->valor,
                'opciones' => $c->caracteristica->opciones,
            ];
        }
        return $array;
    }

    public function getValorCaracteristica($caracteristica) {
        $caracteristica = $this->caracteristicasPropiedad->where('caracteristica_id', $caracteristica)->first();
        $valor = $caracteristica['valor'];
        return $valor;
    }

    public function caracteristicasFormateadas() {
        $retorno = [];
        $caracteristicas = $this->caracteristicasPropiedad()
            ->join('caracteristicas', 'caracteristicas.id', '=', 'caracteristica_id');

        $caracteristicas->each(function($item, $key) use (&$retorno) {
            $categoria = $item->caracteristica->es_servicio ? 'servicios' : 'basicas';
            if($item->caracteristica->esOpcion()) {
                $retorno[$categoria][] = [
                    'titulo' => $item->caracteristica->nombre,
                    'opcion' => true,
                ];
            }
            else {
                $valor = $item->valor;
                if($item->caracteristica->esDropdown()) {
                    $valor = $item->caracteristica->opciones->filter(function($item, $key) use ($valor) { return $item->id == $valor;})->first()->nombre;
                }
                $retorno[$categoria][] = [
                    'titulo' => $item->caracteristica->nombre,
                    'valor' => $valor,
                    'unidad' => $item->caracteristica->unidad,
                    'opcion' => false,
                ];
            }
        });

        return $retorno;
    }

    public function soyPropietario($usuario) {
        return $this->usuario->id === $usuario->id;
    }

    public function caracteristicasOpcionales() {
        return $this->caracteristicasPropiedad()->where('valor', '>', 0)->whereHas('caracteristica', function ($query) {
            $query->where('es_servicio', 0)->whereHas('tipoCaracteristica', function ($query2) {
                $query2->where('nombre', 'Opcion');
            });
        })->get();
    }

    public function servicios() {
        return $this->caracteristicasPropiedad()->where('valor', '>', 0)->whereHas('caracteristica', function ($query) {
            $query->where('es_servicio', 1)->whereHas('tipoCaracteristica', function ($query2) {
                $query2->where('nombre', 'Opcion');
            });
        })->get();
    }

    public function scopeDelTipo($query, TipoPropiedad $tipo) {
        return $query->where('tipo_propiedad_id', $tipo->id);
    }

    public function scopeDeOtroTipoQue($query, TipoPropiedad $tipo) {
        return $query->where('tipo_propiedad_id', '<>', $tipo->id);
    }

    public function scopeDestacadas($query) {
        return $query->where('destacada', true);
    }

    public function scopeVisibles($query) {
        // dejamos el ID del estado "Publicada" hardcodeado
        // para mejorar la velocidad de carga
        return $query->where('estado_id', 2);
    }

    public function scopeFiltros($query, $filtros) {
        if($filtros && count($filtros)>0) {
            if(array_key_exists('tipo_propiedad_id', $filtros) && isset($filtros['tipo_propiedad_id'])) {
                $query->where('tipo_propiedad_id', $filtros['tipo_propiedad_id']);
            }
            if(array_key_exists('operacion_id', $filtros) && isset($filtros['operacion_id'])) {
                $query->where('operacion_id', $filtros['operacion_id']);
            }
            if(array_key_exists('provincia_id', $filtros) && isset($filtros['provincia_id'])) {
                $query->whereHas('localidad', function($query2) use ($filtros) {
                    $query2->where('provincia_id', $filtros['provincia_id']);
                });
            }
            if(array_key_exists('localidad_id', $filtros) && isset($filtros['localidad_id'])) {
                $query->where('localidad_id', $filtros['localidad_id']);
            }
            if(array_key_exists('barrio_id', $filtros) && isset($filtros['barrio_id'])) {
                $query->where('barrio_id', $filtros['barrio_id']);
            }
            if(array_key_exists('usos', $filtros) && isset($filtros['usos'])) {
                $query->whereHas('usos', function($query2) use ($filtros) {
                    $query2->whereIn('id', $filtros['usos']);
                });
            }

            if(isset($filtros['unidad_superficie_id'])) {
                $query->where('unidad_superficie_id', $filtros['unidad_superficie_id']);
            }

            if(isset($filtros['precioMinimo']) || isset($filtros['precioMaximo'])) {

                if(isset($filtros['moneda_id'])) {
                    $query->where('moneda_id', $filtros['moneda_id']);  
                }
                if (isset($filtros['precioMinimo'])) {
                    $query->where('monto', '>=', $filtros['precioMinimo']);
                }
                if (isset($filtros['precioMaximo'])) {
                    $query->where('monto', '<=', $filtros['precioMaximo']);
                }
            }
            //superficie total
            if(array_key_exists('superficieMinima', $filtros) && array_key_exists('superficieMaxima', $filtros)) {

                if (isset($filtros['superficieMinima'])) {
                    $query->where('superficie', '>=', $filtros['superficieMinima']);
                }
                if (isset($filtros['superficieMaxima'])) {
                    $query->where('superficie', '<=', $filtros['superficieMaxima']);
                }
            }

            if (array_key_exists('caracteristicas', $filtros) && isset($filtros['caracteristicas'])) {
                foreach ($filtros['caracteristicas'] as $id => $valor) {
                    if($valor !== null) {
                        if($valor === 'true') {
                            $query->whereHas('caracteristicasPropiedad', function($query2) use ($id) {
                                $query2->where('caracteristica_id', $id);
                            });
                        }
                        else {
                            $query->whereHas('caracteristicasPropiedad', function($query2) use ($id, $valor) {
                                $query2->where('caracteristica_id', $id);
                                $query2->where('valor', $valor);
                            });
                        }
                    }
                }
            }

            if (array_key_exists('expensaMinima', $filtros) && array_key_exists('expensaMaxima', $filtros)) {
                if(isset($filtros['expensaMinima'])) {
                    $query->where('expensas', '>=', $filtros['expensaMinima']);
                }
                if(isset($filtros['expensaMaxima'])) {
                    $query->where('expensas', '<=', $filtros['expensaMaxima']);
                }
            }
            //superficie descubierta
            if(array_key_exists('cubierta_minima', $filtros) && array_key_exists('cubierta_maxima', $filtros)) {
                if (isset($filtros['cubierta_minima'])) {
                    $query->where('superficie_cubierta', '>=', $filtros['cubierta_minima']);
                }
                if (isset($filtros['cubierta_maxima'])) {
                    $query->where('superficie_cubierta', '<=', $filtros['cubierta_maxima']);
                }
            }
            if(array_key_exists('tipologia_id', $filtros) && isset($filtros['tipologia_id'])) {
                $query->where('tipologia_id', $filtros['tipologia_id']);
            }
        }
        return $query;
    }

    public function scopeEstado($query, $estado) {
        if ($estado) {
            $query->where('estado_id', $estado);
        }
    }

    public function getTituloCorto() {
        $titulo = $this->titulo;
        if (strlen($this->titulo) > 53) {
            $titulo = substr($this->titulo, 0, 53).'...';
        }
        return $titulo;
    }

    public function getDescripcionCorta() {
        $descripcion = html_entity_decode(strip_tags($this->descripcion));
        if (strlen($descripcion) > 40) {
            $descripcion = mb_substr($descripcion, 0, 40, "utf-8") . '...';
        }
        return $descripcion;
    }

    public function getPrecioFormato() {
        $retorno = 'Consultar Precio';

        if (!$this->precio_convenir) {
            $retorno = $this->moneda->nombre . ' ' . number_format($this->monto, 0, ',', '.');
        }

        return $retorno;
    }

    public function agregarInteresado($email, $suscribirse) {
        $interesado = new Interesado;
        $interesado->email = $email;
        $interesado->suscribirse = $suscribirse;

        return $this->interesados()->save($interesado);
    }

    public function estaInteresado($email) {
        return $this->interesados()->where('email', $email)->count() > 0;
    }

    public function tieneVideo() {
        return $this->video_url != null;
    }

    public function tieneFotos() {
        return $this->fotos()->count() > 0;
    }

    public function getYoutubeVideoId()
    {
        $regex = '~(?:http|https|)(?::\/\/|)(?:www.|)(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/ytscreeningroom\?v=|\/feeds\/api\/videos\/|\/user\S*[^\w\-\s]|\S*[^\w\-\s]))([\w\-]{11})[a-z0-9;:@#?&%=+\/\$_.-]*~i';
        $id = preg_replace($regex, '$1', $this->video_url );
        return $id;
    }

    public function destacar() {
        $this->destacada = true;
    }

    public function quitarDestacada() {
        $this->destacada = false;
    }

    public function agregarEstado(Estado $estado, $motivo = null) {
        $estadoPropiedad = new EstadoPropiedad;
        $estadoPropiedad->estado()->associate($estado);
        $estadoPropiedad->motivo = $motivo;

        $this->estados()->save($estadoPropiedad);
        $this->estado()->associate($estado);
        $this->save();
    }

    public function estaCreada() {
        return $this->estado->esCreada();
    }

    public function estaPublicada() {
        return $this->estado->esPublicada();
    }

    public function estaPausada() {
        return $this->estado->esPausada();
    }

    public function estaFinalizada() {
        return $this->estado->esFinalizada();
    }

    public function sePuedeEditar() {
        return !($this->estaPausada() || $this->estaPublicada());
    }

    public function sePuedePublicar() {
        return $this->estado->esCreada() || $this->estado->esFinalizada();
    }

    public function sePuedePausar() {
        return $this->estado->esPublicada();
    }

    public function sePuedeReanudar() {
        return $this->estado->esPausada();
    }

    public function sePuedeFinalizar() {
        return $this->estado->esPublicada() || $this->estado->esPausada();
    }

    public function sePuedeDarDeBaja() {
        return $this->estado->esFinalizada() || $this->estado->esCreada();
    }

    public function descontarUnDiaDeCredito() {
        $this->credito->disminuirDiasDisponibles();
        $this->credito->save();
    }

    public function haFinalizado() {
        return $this->credito->haFinalizado();
    }

    public function finalizar(Estado $finalizada) {
        // liberamos el crédito asociado para poder publicarla nuevamente
        $this->credito()->dissociate();

        // aplicamos el cambio de estado
        $this->agregarEstado($finalizada);
    }

    public function getUbicacionFormateada() {
        $retorno = [];
        $calleNumero = [];

        if ($this->direccion) {
            $calleNumero[] = $this->direccion;
        }

        if ($this->piso) {
            $calleNumero[] = $this->piso;
        }

        if ($this->departamento) {
            $calleNumero[] = $this->departamento;
        }

        if ($calleNumero) {
            $retorno[] = implode(' ', $calleNumero);
        }

        if ($this->barrio) {
            $retorno[] = $this->barrio;
        }

        if ($this->localidad) {
            $retorno[] = $this->localidad;
        }

        if ($this->localidad) {
            $retorno[] = $this->localidad->provincia;
        }

        return implode(', ', $retorno);
    }

    public function tieneReferenciaDeUbicacion() {
        return $this->referencia != null;
    }

    public function getUnidad() {
        return $this->unidadSuperficie;
    }

    public function getLinkWhatsApp($url) {
        $base = 'https://wa.me/';
        $numero = preg_replace('/\D/', '', $this->usuario->telefono_celular);
        if (preg_match("/^549/", $numero)) {
            $numero = substr($numero, 3);
        }
        else if (preg_match("/^54/", $numero)) {
            $numero = substr($numero, 2);
        }
        $numero = '54' . $numero;
        $texto = '?text=' . urlencode('Quisiera tener más detalles del inmueble por favor.') . '%0A' . $url;
        $link = $base . $numero . $texto;
        return $link;
    }
    
    public function __toString() {
        return $this->titulo;
    }
}
