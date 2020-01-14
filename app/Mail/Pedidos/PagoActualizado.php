<?php

namespace Inmuebles\Mail\Pedidos;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Inmuebles\Models\Pedidos\Pedido;

class PagoActualizado extends Mailable
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
        return $this->subject('Actualización de Pago para Pedido #' . $this->pedido->id)
                    ->markdown('emails.pedidos.pago-actualizado')
                    ->with('pedido', $this->pedido);
    }
}