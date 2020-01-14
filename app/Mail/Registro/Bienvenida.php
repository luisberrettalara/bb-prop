<?php

namespace Inmuebles\Mail\Registro;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Inmuebles\Models\Usuarios\User;

class Bienvenida extends Mailable
{
    use Queueable, SerializesModels;

    private $usuario;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Bienvenido a BB-Prop')
                    ->markdown('emails.registro.bienvenida')
                    ->with('usuario', $this->usuario);
    }
}
