<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PDFMail extends Mailable
{
    use Queueable, SerializesModels;

    protected  $asunto;
    protected  $pdf;
    protected $excel;
    /**
     * Create a new message instance.
     */
    public function __construct($asunto, $pdf, $excel)
    {
        //
        $this->asunto = $asunto;
        $this->pdf = $pdf;
        $this->excel = $excel;
    }

    /**
     * Get the message envelope.
     */
    /*
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->info['asunto'],
        );
    }
    */

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
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

    public function build()
    {
        return $this->markdown('emails.pdf_watch')
        ->subject($this->asunto)
        ->attachData($this->pdf, 'reporte.pdf', [
            'mime' => 'application/pdf',
        ])
        ->attachData($this->excel, 'incidencias.xlsx', [
            'mime' => 'application/xlsx',
        ]);

    }
}
