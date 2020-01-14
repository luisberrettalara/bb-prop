<?php

namespace Inmuebles\Mail\Pedidos;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Inmuebles\Models\Pedidos\Pedido;

class Rechazado extends Mailable
{
    use Queueable, SerializesModels;

    private $pedido;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Pedido $pedido)
    {
        $this->pedido = $pedido;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Tu Pago ha sido Rechazado')
                    ->markdown('emails.pedidos.rechazado')
                    ->with('pedido', $this->pedido);
    }
}
