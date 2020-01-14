<?php

namespace Inmuebles\Observers;

use Inmuebles\Models\Propiedades\Foto;
use File;

class FotoObserver
{
    /**
     * Handle the foto "deleted" event.
     *
     * @param  \Inmuebles\Foto  $foto
     * @return void
     */
    public function deleted(Foto $foto)
    {
        if (File::exists(public_path($foto->url)) && File::exists(public_path($foto->thumb_url))) {
            File::delete(public_path($foto->url));
            File::delete(public_path($foto->thumb_url));  
        }
    }
}
