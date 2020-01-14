<?php

namespace Inmuebles\Mail\Creditos;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Inmuebles\Models\Propiedades\Propiedad;

class FinalizacionCredito extends Mailable
{
    use Queueable, SerializesModels;

    private $propiedad;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Propiedad $propiedad)
    {
        $this->propiedad = $propiedad;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Créditos próximos a vencer')
                    ->markdown('emails.creditos.finalizacion-credito')
                    ->with('propiedad', $this->propiedad);
    }
}
