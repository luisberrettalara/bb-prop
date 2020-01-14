<?php
namespace Inmuebles\Services;

use Inmuebles\Models\Propiedades\Propiedad;
use Inmuebles\Models\Propiedades\Caracteristica;
use Inmuebles\Models\Propiedades\Uso;
use Inmuebles\Models\Propiedades\Foto;
use Inmuebles\Models\Propiedades\TipoPropiedad;
use Inmuebles\Models\Propiedades\Estado;
use Inmuebles\Models\Paquetes\Credito;
use Inmuebles\Models\Usuarios\User;
use Inmuebles\Models\Propiedades\Operacion;

use Inmuebles\Mail\Propiedades\PropiedadCompartida;
use Inmuebles\Mail\Propiedades\Contacto;

use Inmuebles\Services\FotosService;
use Illuminate\Support\Facades\Mail;


use Auth;
use File;
use Debugbar;
use DB;

class PropiedadesService
{
    public function agregar(Propiedad $propiedad, $caracteristicas, $usos, $fotos, $lat, $long) {
        DB::transaction(function () use ($propiedad, $caracteristicas, $usos, $fotos, $lat, $long) {

            //A partir de lat y long, le pedimos a google maps la url con la imágen del mapa
            $url =  'https://maps.googleapis.com/maps/api/staticmap?'
                  .'center='.$lat . ',' .$long
                  .'&zoom=16'
                  .'&size=623x300'
                  .'&markers='.$lat . ','. $long
                  .'&key='.env('GOOGLE_MAPS_API_KEY');

            //Guardamos la imagen del mapa
            $fileName = '/fotos/mapas/'.uniqid().'.png';
            $contents = file_get_contents($url);
            File::put(public_path() . $fileName, $contents);
            $propiedad->imagen_mapa = $fileName;

            $propiedad->save();
            // filtramos las caracteristicas no especificadas (valor nulo)
            $caracteristicas = array_filter($caracteristicas, function ($item) { return array_key_exists('valor', $item) && $item['valor'] != null && $item['valor'] > 0; });
            if($caracteristicas != null && count($caracteristicas) > 0) {
                $propiedad->caracteristicasPropiedad()->createMany($caracteristicas);
            }
            if($usos != null && count($usos) > 0) {
                $propiedad->usos()->attach($usos);
            }
            if($fotos != null && count($fotos) > 0) {
                $propiedad->fotos()->saveMany($this->buscarFotos($fotos));

                // definimos la foto de portada
                $propiedad->fotoPortada()->associate($propiedad->fotos()->first());
                $propiedad->save();
            }

            $propiedad->slug = $propiedad->id . '/' . str_slug($propiedad->titulo, '-');
            // registramos la transición de estado
            $creada = Estado::where('nombre', 'Creada')->firstOrFail();
            $propiedad->agregarEstado($creada);
        });
    }

    public function actualizar(Propiedad $propiedad, $caracteristicas, $usos, $fotos, $fotosEliminar, $lat, $long) {
        $propiedad->slug = $propiedad->id . '/' . str_slug($propiedad->titulo, '-');
        $propiedad->update();
        $propiedad->caracteristicasPropiedad()->delete();
        // filtramos las caracteristicas no especificadas (valor nulo)
        $caracteristicas = array_filter($caracteristicas, function ($item) { return array_key_exists('valor', $item) && $item['valor'] != null && $item['valor'] > 0; });
        if($caracteristicas != null && count($caracteristicas) > 0) {
            $propiedad->caracteristicasPropiedad()->createMany($caracteristicas);
        }
        $propiedad->usos()->detach();
        if($usos != null && count($usos) > 0) {
            $propiedad->usos()->attach($usos);
        }
        if ($fotosEliminar != null && count($fotosEliminar) > 0) {
            if($propiedad->fotoPortada != null && in_array($propiedad->fotoPortada->id, $fotosEliminar)) {
                $propiedad->fotoPortada()->dissociate();
                $propiedad->save();
                $propiedad->fotoPortada()->associate($propiedad->fotos()->first());
            }
            $propiedad->fotos()->whereIn("id", $fotosEliminar)->get()->each(function($foto) {
                $foto->delete();
            });
        }
        if($propiedad->fotos()->count()>0) {
            $propiedad->fotoPortada()->associate($propiedad->fotos()->first());
            $propiedad->save();
        }
        if($fotos != null && count($fotos) > 0) {
            $propiedad->fotos()->saveMany($this->buscarFotos($fotos));
            //Si eliminamos la foto de portada la reemplazamos por la siguiente
            $propiedad->fotoPortada()->associate($propiedad->fotos()->first());
            $propiedad->save();
        }

        //A partir de lat y long, le pedimos a google maps la url con la imágen del mapa
       $url =  'https://maps.googleapis.com/maps/api/staticmap?'
              .'center='.$lat . ',' .$long
              .'&zoom=16'
              .'&size=623x300'
              .'&markers='.$lat . ','. $long
              .'&key='.env('GOOGLE_MAPS_API_KEY');

        //Guardamos la imagen del mapa
        $fileName = '/fotos/mapas/'.uniqid().'.png';
        $contents = file_get_contents($url);
        File::put(public_path() . $fileName, $contents);
        $propiedad->imagen_mapa = $fileName;
        $propiedad->save();
    }

    public function borrar(Propiedad $propiedad) {
        $propiedad->caracteristicasPropiedad()->delete();
        $propiedad->usos()->detach();
        $propiedad->fotoPortada()->dissociate();
        $propiedad->save();
        $propiedad->fotos()->get()->each(function($foto) {
            $foto->delete();
        });
        $propiedad->delete();
    }

    public function publicar(User $usuario, Propiedad $propiedad, Credito $credito) {

        // si no es admin comprobamos la propiedad de las entidades
        if (!$usuario->esAdmin()) {
            // verificamos si la propiedad pertenece al usuario
            if (!$propiedad->soyPropietario($usuario)) {
                throw new \Exception('No tiene permisos para Publicar esta Propiedad');
            }

            // verificamos la propiedad del credito
            if (!$credito->perteneceA($usuario)) {
                throw new \Exception('No tiene permisos para Publicar con este Crédito');
            }
        }

        // verificamos si el crédito está vigente
        if ($credito->estaVencido()) {
            throw new \Exception('El Crédito está vencido');
        }

        // verificamos si el crédito está libre
        if (!$credito->estaLibre()) {
            throw new \Exception('El Crédito ya está tomado');
        }

        // verificamos que la propiedad se pueda publicar
        if (!$propiedad->sePuedePublicar()) {
            throw new \Exception('Esta Propiedad no se puede publicar');
        }

        // si llegamos a este punto podemos proceder a publicar la propiedad
        // mediante una transacción para garantizar que el credito no sea
        // consumido concurrentemente
        DB::transaction(function () use ($propiedad, $credito) {
            // vinculamos el credito con la propiedad
            $credito->propiedad()->associate($propiedad);
            $credito->save();

            // vinculamos la propiedad con el credito
            $propiedad->credito()->associate($credito);

            // asignamos la condición de destacada a la publicación
            $propiedad->destacada = $credito->destacado;

            // cambiamos el estado de la propiedad
            $publicada = Estado::where('nombre', 'Publicada')->firstOrFail();
            $propiedad->agregarEstado($publicada, $credito->__toString());
        });
    }

    public function pausar(User $usuario, Propiedad $propiedad) {
        // si no es admin comprobamos la propiedad de las entidades
        if (!$usuario->esAdmin()) {
            // verificamos si la propiedad pertenece al usuario
            if (!$propiedad->soyPropietario($usuario)) {
                throw new \Exception('No tiene permisos para pausar esta Propiedad');
            }
        }

        // verificamos si la propiedad se puede pausar en base a su estado
        if (!$propiedad->sePuedePausar()) {
            throw new \Exception('No se puede pausar esta Propiedad');
        }

        // aplicamos el cambio de estado
        $pausada = Estado::where('nombre', 'Pausada')->firstOrFail();
        $propiedad->agregarEstado($pausada);
    }

    public function reanudar(User $usuario, Propiedad $propiedad) {
        // si no es admin comprobamos la propiedad de las entidades
        if (!$usuario->esAdmin()) {
            // verificamos si la propiedad pertenece al usuario
            if (!$propiedad->soyPropietario($usuario)) {
                throw new \Exception('No tiene permisos para reanudar esta Propiedad');
            }
        }

        // verificamos si la propiedad se puede pausar en base a su estado
        if (!$propiedad->sePuedeReanudar()) {
            throw new \Exception('No se puede reanudar esta Propiedad');
        }

        // aplicamos el cambio de estado
        $pausada = Estado::where('nombre', 'Publicada')->firstOrFail();
        $propiedad->agregarEstado($pausada);
    }

    public function finalizar(User $usuario, Propiedad $propiedad) {
        // si no es admin comprobamos la propiedad de las entidades
        if (!$usuario->esAdmin()) {
            // verificamos si la propiedad pertenece al usuario
            if (!$propiedad->soyPropietario($usuario)) {
                throw new \Exception('No tiene permisos para reanudar esta Propiedad');
            }
        }

        // verificamos si la propiedad se puede pausar en base a su estado
        if (!$propiedad->sePuedeFinalizar()) {
            throw new \Exception('No se puede finalizar esta Publicación');
        }

        // ejecutamos todo en una transaccion para asegurar atomicidad
        DB::transaction(function () use ($propiedad) {
            $finalizada = Estado::where('nombre', 'Finalizada')->firstOrFail();
            $propiedad->finalizar($finalizada);
        });
    }

    public function darDeBaja(User $usuario, Propiedad $propiedad) {
        // si no es admin comprobamos la propiedad de las entidades
        if (!$usuario->esAdmin()) {
            // verificamos si la propiedad pertenece al usuario
            if (!$propiedad->soyPropietario($usuario)) {
                throw new \Exception('No tiene permisos para reanudar esta Propiedad');
            }
        }

        // verificamos si la propiedad se puede pausar en base a su estado
        if (!$propiedad->sePuedeDarDeBaja()) {
            throw new \Exception('No se puede Dar de Baja esta Publicación');
        }

        $baja = Estado::where('nombre', 'Baja')->firstOrFail();
        $propiedad->agregarEstado($baja);
    }

    public function buscarFotos($fotos) {
        $path = '/fotos/propiedades/';
        $tempPath = '/fotos/';
        $fotosPropiedad = [];
        foreach ($fotos as $idx => $foto) {
            if (File::exists(public_path($foto['url']))) {
                $tempFileName = substr(strrchr($foto['url'], '/'), 1);
                $fileName = $path . $tempFileName;
                $thumbFileName = $path .'thumb_'. $tempFileName;
                
                File::move(public_path($tempPath.$tempFileName), public_path($fileName));
                File::move(public_path($tempPath.'thumb_'.$tempFileName), public_path($thumbFileName));

                $fotoPropiedad = new Foto();

                $fotoPropiedad->url = $fileName;
                $fotoPropiedad->thumb_url = $thumbFileName;
                $fotoPropiedad->ancho = $foto['ancho'];
                $fotoPropiedad->alto = $foto['alto'];
                $fotosPropiedad[] = $fotoPropiedad;
            }
        }
        return $fotosPropiedad;
    }

    public function compartir(Propiedad $propiedad, $email) {
        Debugbar::info('Compartiendo Propiedad', $propiedad);

        Mail::to($email)->send(new PropiedadCompartida($propiedad));
    }

    public function contactar(Propiedad $propiedad, $email, $telefono, $mensaje) {
        Debugbar::info('Contactando Propiedad', $propiedad);

        Mail::to($propiedad->usuario->email)->send(new Contacto($propiedad, $email, $telefono, $mensaje));
    }

    public function obtenerDestacadasAleatoriasDePozo($cantidad = 4) {
        $pozo = $this->getTipoDePozo();

        return Propiedad::destacadas()->visibles()->delTipo($pozo)->inRandomOrder()->take($cantidad)->get();
    }

    public function obtenerDestacadasAleatoriasNoDePozo($cantidad = 4) {
        $pozo = $this->getTipoDePozo();

        return Propiedad::destacadas()->visibles()->deOtroTipoQue($pozo)->inRandomOrder()->take($cantidad)->get();
    }

    public function getTipoDePozo() {
        return $pozo = TipoPropiedad::where('nombre', 'Emprendimiento de pozo')->firstOrFail();
    }

    public function linksBusquedasComunesFooter() {
        $operacionAlquiler = Operacion::where('nombre', 'Alquiler')->firstOrFail();
        $operacionVenta = Operacion::where('nombre', 'Venta')->firstOrFail();
        $operacionAlquilerTemporario = Operacion::where('nombre', 'Alquiler Temporario')->firstOrFail();
        $tipoDepartamento = TipoPropiedad::where('nombre', 'Departamento')->firstOrFail();
        $tipoCasa = TipoPropiedad::where('nombre', 'Casa')->firstOrFail();
        $tipoPozo = TipoPropiedad::where('nombre', 'Emprendimiento de pozo')->firstOrFail();
        $urlBase = '/propiedades/busqueda?';
        $url = [
            'Departamentos en alquiler' => $urlBase . 'tipo_propiedad_id=' . $tipoDepartamento->id . '&' . 'operacion_id=' . $operacionAlquiler->id,
            'Departamentos en venta' => $urlBase . 'tipo_propiedad_id=' . $tipoDepartamento->id . '&' . 'operacion_id=' . $operacionVenta->id,
            'Casas en alquiler' => $urlBase . 'tipo_propiedad_id=' . $tipoCasa->id . '&' . 'operacion_id=' . $operacionAlquiler->id,
            'Casas en venta' => $urlBase . 'tipo_propiedad_id=' . $tipoCasa->id . '&' . 'operacion_id=' . $operacionVenta->id,
            'Departamentos en alquiler temporario' => $urlBase . 'tipo_propiedad_id=' . $tipoDepartamento->id . '&' . 'operacion_id=' . $operacionAlquilerTemporario->id,
            'Casas en alquiler temporario' => $urlBase . 'tipo_propiedad_id=' . $tipoCasa->id . '&' . 'operacion_id=' . $operacionAlquilerTemporario->id,
            'Emprendimientos de pozo' => $urlBase . 'tipo_propiedad_id=' . $tipoPozo->id,
        ];

    return $url;
    }
}