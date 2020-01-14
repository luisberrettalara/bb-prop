<?php

namespace Inmuebles\Models\Propiedades;

use Illuminate\Database\Eloquent\Model;
use Inmuebles\Models\Propiedades\Propiedad;

class TipoPropiedad extends Model
{
    protected $table = 'tipos_propiedad';

    protected $fillable = ['nombre'];

    public function caracteristicas() {
    	return $this->belongsToMany('Inmuebles\Models\Propiedades\Caracteristica','tipos_propiedad_caracteristicas')->withPivot('orden')->orderBy('tipos_propiedad_caracteristicas.orden');
    }

    public function agregarCaracteristica(Caracteristica $caracteristica) {
        //Al agregar una nueva caracteristica, se agrega al final
        $orden = $this->caracteristicas->count();
        $this->caracteristicas()->attach($caracteristica->id, ['orden' => $orden]);
    }

    public function quitarCaracteristica(Caracteristica $caracteristica) {
        $caracteristica = $this->caracteristicas->find($caracteristica);
        $cantidadCaracteristicas = $this->caracteristicas->count();
        $orden = $caracteristica->pivot->orden;
        $subList = $this->caracteristicas->slice($orden, $cantidadCaracteristicas);
        foreach ($subList as $sub) {
            if($sub->id == $caracteristica->id) {
                $this->caracteristicas()->detach($caracteristica);
            }
            else {
                $sub->pivot->orden = $sub->pivot->orden -1;
                $sub->pivot->save();
            }
        }
    }

    public function reordenarCaracteristicas(Caracteristica $car, $orden) {
        $car = $this->caracteristicas->find($car);
        $ordenNuevo = $orden < 0 ? 0 : $orden;
        $ordenNuevo = $ordenNuevo > $this->caracteristicas->count() - 1 ? $this->caracteristicas->count() - 1 : $ordenNuevo;
        $ordenActual = $car->pivot->orden;
        if ($ordenActual == $ordenNuevo) {
            return;
        }
        $limiteInferior = 0;
        $limiteSuperior = 0;
        $multiplicador = 1;
        if ($ordenNuevo > $ordenActual) {
          $limiteInferior = $ordenActual;
          $limiteSuperior = $ordenNuevo + 1;
          $multiplicador = -1;
        } 
        else {
            $limiteInferior = $ordenNuevo;
            $limiteSuperior = $ordenActual + 1;
        }
        $subList = $this->caracteristicas->slice($limiteInferior, $limiteSuperior-$limiteInferior);
        foreach ($subList as $sub) {
            if ($sub->id === $car->id) {
                $sub->pivot->orden = $ordenNuevo;
            }
            else {
                $sub->pivot->orden = $sub->pivot->orden + (1 * $multiplicador);
            }
            $sub->pivot->save();
        }
    }

    public function caracteristicasFormateadas() {
        $array = [];
        foreach ($this->caracteristicas as $idx => $c) {
            $array[] = [
                'idx' => $idx,
                'tipo_caracteristica_id' => $c->tipoCaracteristica->id,
                'caracteristica_id' => $c->id,
                'nombre' => $c->nombre,
                'unidad' => $c->unidad,
                'es_servicio' => $c->es_servicio,
                'opciones' => $c->opciones,
            ];
        }
        return $array;
    }

    public function __toString() {
      return $this->nombre;
    }
}
