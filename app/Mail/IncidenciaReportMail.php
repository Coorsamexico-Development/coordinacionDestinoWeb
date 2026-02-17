<?php

namespace App\Mail;

use App\Models\ConfirmacionDt;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Campo;
use App\Models\DtCampoValor;
use App\Models\Valor;

class IncidenciaReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $confirmacion;
    public $incidenciasData;
    public $bitacoraUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(ConfirmacionDt $confirmacion)
    {
        $this->confirmacion = $confirmacion->load([
            'ocs.incidencias.evidencias',
            'ocs.incidencias.tipoIncidencia',
            'ocs.incidencias.producto',
            'lineaTransporte',
            'cliente',
            'ubicacion'
        ]);

        $this->incidenciasData = $this->prepareIncidenciasData();
        $this->bitacoraUrl = $this->getBitacoraUrl();
    }

    protected function prepareIncidenciasData()
    {
        $data = [];
        foreach ($this->confirmacion->ocs as $oc) {
            foreach ($oc->incidencias as $incidencia) {
                $data[] = [
                    'tipo_rechazo' => $incidencia->tipoIncidencia->nombre ?? '',
                    'confirmacion' => $this->confirmacion->confirmacion,
                    'oc' => $oc->referencia,
                    'fecha_reporte' => $incidencia->created_at->format('Y-m-d'),
                    'clave_producto' => $incidencia->producto->SKU ?? '',
                    'material' => $incidencia->producto->descripcion ?? '',
                    'cantidad' => $incidencia->cantidad,
                    'um' => $incidencia->producto->UM ?? '',
                    'upc_sku' => $incidencia->producto->SKU ?? '',
                    'incidencia_desc' => $incidencia->tipoIncidencia->nombre ?? '',
                    'carga' => '', // Placeholder
                    'linea' => $this->confirmacion->lineaTransporte->nombre ?? '',
                    'cliente' => $this->confirmacion->cliente->nombre ?? '',
                    'localidad' => $this->confirmacion->ubicacion->nombre_ubicacion ?? '',
                    'origen' => '', // Placeholder
                    'evidencias' => $incidencia->evidencias
                ];
            }
        }
        return $data;
    }

    protected function getBitacoraUrl()
    {
        $bitacoraCampo = Campo::where('nombre', 'BITACORA')->first();
        if ($bitacoraCampo) {
            $dtCampoValor = DtCampoValor::where('confirmacion_id', $this->confirmacion->id)
                ->where('campo_id', $bitacoraCampo->id)
                ->first();
            if ($dtCampoValor) {
                $valor = Valor::where('dt_campo_valor_id', $dtCampoValor->id)
                    ->where('activo', 1)
                    ->first();
                return $valor ? $valor->valor : null;
            }
        }
        return null;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'CORREO DE INCIDENCIAS - CONFIRMACIÓN ' . $this->confirmacion->confirmacion,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.incidencia_report',
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
