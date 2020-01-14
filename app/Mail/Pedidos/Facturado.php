<?php

namespace Inmuebles\Mail\Pedidos;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use Inmuebles\Models\Pedidos\Pedido;

class Facturado extends Mailable
{
    use Queueable, SerializesModels;

    private $pedido;
    private $pdfPath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Pedido $pedido, $pdfPath)
    {
        $this->pedido = $pedido;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Tu Factura por tu Pedido')
                    ->markdown('emails.pedidos.facturado')
                    ->with('pedido', $this->pedido)
                    ->attach($this->pdfPath);
    }
}
