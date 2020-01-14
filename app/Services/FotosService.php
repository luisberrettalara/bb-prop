<?php
namespace Inmuebles\Services;
use File;
use Inmuebles\Models\Propiedades\Foto;
use Image;
  
class FotosService {
    public function guardarTemporal($file) {
        $image = Image::make($file);
        $path = public_path() . '/fotos/';
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        $image->save($path.$fileName);
        $ancho = $image->width();
        $alto = $image->height();
        // usamos el metodo FIT para cropear la imagen en thumbail
        $image->fit(370,270); 
        $image->save($path.'thumb_'.$fileName);

        return [
        	"url" =>  '/fotos/'.$fileName,
        	"thumbUrl" => '/fotos/'.'thumb_'.$fileName,
            "alto" => $alto,
            "ancho" => $ancho,
        ];
    }
}