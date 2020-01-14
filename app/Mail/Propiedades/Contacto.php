<?php

namespace Inmuebles\Mail\Propiedades;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Inmuebles\Models\Propiedades\Propiedad;

class Contacto extends Mailable
{
    use Queueable, SerializesModels;

    private $propiedad;
    private $email;
    private $telefono;
    private $mensaje;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Propiedad $propiedad, $email, $telefono, $mensaje)
    {
        $this->propiedad = $propiedad;
        $this->email = $email;
        $this->telefono = $telefono;
        $this->mensaje = $mensaje;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.propiedades.contacto')
                    ->with('propiedad', $this->propiedad)
                    ->with('email', $this->email)
                    ->with('telefono', $this->telefono)
                    ->with('mensaje', $this->mensaje);
    }
}
