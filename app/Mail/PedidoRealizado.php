<?php

namespace App\Mail;

use App\Helpers\CarritoManagement;
use App\Models\ElementoOrden;
use App\Models\Orden;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class pedidoRealizado extends Mailable
{
    public $orden;
    public $elementos_ordenes;
    public $elementos_carrito;
    public $total_final;

    /**
     * Create a new message instance.
     */
    public function __construct(Orden $orden)
    {
        $this->orden = $orden;
        $this->elementos_carrito = CarritoManagement::obtenerElementosDeCookies() ?? [];
        $total_final = CarritoManagement::calcularTotalFinal($this->elementos_carrito);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Detalles de Pedido - Abarrotes Express',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.pedido-realizado',
            with: [
                'orden' => $this->orden,
                'elementos_ordenes' => $this->elementos_ordenes,
                'total_final' => $this->total_final
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [
        ];
    }
}
