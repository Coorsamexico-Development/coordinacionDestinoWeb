<?php

namespace App\Mail;

use App\Models\ConfirmacionDt;
use App\Models\BitacoraCampo;
use App\Models\BitacoraValor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BitacoraReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $confirmacion;
    public $bitacoraData;

    /**
     * Create a new message instance.
     */
    public function __construct(ConfirmacionDt $confirmacion)
    {
        $this->confirmacion = $confirmacion->load([
            'lineaTransporte',
            'cliente',
            'ubicacion'
        ]);

        $this->bitacoraData = $this->prepareBitacoraData();
    }

    protected function prepareBitacoraData()
    {
        $campos = BitacoraCampo::selectValores($this->confirmacion->id);
        $data = [];

        foreach ($campos as $campo) {
            $valores = $campo->valores;

            if ($campo->tipo_campo_id == 4) { // Archivo
                $data[$campo->nombre] = [
                    'tipo' => 'archivo',
                    'valores' => $valores->pluck('valor')->toArray()
                ];
            } else {
                $data[$campo->nombre] = [
                    'tipo' => 'texto',
                    'valor' => $valores->isNotEmpty() ? $valores->first()->valor : ''
                ];
            }
        }
        return $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'MANIOBRA - CONFIRMACIÓN ' . $this->confirmacion->confirmacion,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.bitacora_report',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
