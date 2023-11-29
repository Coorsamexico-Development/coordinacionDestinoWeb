<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PDFExcel extends Mailable
{
    use Queueable, SerializesModels;

    protected  $asunto;
    protected $excel;
    /**
     * Create a new message instance.
     */
    public function __construct($asunto, $excel)
    {
        //
        $this->asunto = $asunto;
        $this->excel = $excel;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'P D F Excel',
        );
    }

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
        ->attachData($this->excel, 'incidencias.xlsx', [
            'mime' => 'application/xlsx',
        ]);

    }
}
