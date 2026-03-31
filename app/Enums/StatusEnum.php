<?php

namespace App\Enums;

enum StatusEnum: int
{
    // Status padre
    case EN_TRANSITO = 1;
    case EN_PATIO = 2;
    case LIBERACION = 3;

    // Status hijos
    case A_TIEMPO = 4;
    case EN_RIESGO = 5;
    case DOCUMENTADO = 6;
    case EN_ESPERA_DE_RAMPA = 7;
    case ENRAMPADO = 8;
    case DESENRAMPADO = 9;
    case LIBERADA_AL_100 = 10;
    case LIBERADA_CON_INCIDENCIA = 11;

    /**
     * Get the descriptive name of the status.
     */
    public function name(): string
    {
        return match($this) {
            self::EN_TRANSITO => 'En transito',
            self::EN_PATIO => 'En patio',
            self::LIBERACION => 'Liberacion',
            
            self::A_TIEMPO => 'A tiempo',
            self::EN_RIESGO => 'En riesgo',
            
            self::DOCUMENTADO => 'Documentado',
            self::EN_ESPERA_DE_RAMPA => 'En espera de rampa',
            self::ENRAMPADO => 'Enrampado',
            self::DESENRAMPADO => 'Desenrampado',
            
            self::LIBERADA_AL_100 => 'Liberada al 100',
            self::LIBERADA_CON_INCIDENCIA => 'Liberada con incidencia',
        };
    }

    /**
     * Get the associated color for the status.
     */
    public function color(): ?string
    {
        return match($this) {
            self::A_TIEMPO => '#FFAE3F',
            self::EN_RIESGO => '#56D0C1',
            
            self::DOCUMENTADO => '#44BFFC',
            self::EN_ESPERA_DE_RAMPA => '#56D0C1',
            self::ENRAMPADO => '#697FEA',
            self::DESENRAMPADO => '#56D0C1',
            
            self::LIBERADA_AL_100 => '#1D96F1',
            self::LIBERADA_CON_INCIDENCIA => '#1D96F1',
            
            default => null,
        };
    }

    /**
     * Get the parent status ID.
     */
    public function statusPadre(): ?int
    {
        return match($this) {
            self::EN_TRANSITO, self::EN_PATIO, self::LIBERACION => null,
            
            self::A_TIEMPO, self::EN_RIESGO => self::EN_TRANSITO->value,
            
            self::DOCUMENTADO, self::EN_ESPERA_DE_RAMPA, self::ENRAMPADO, self::DESENRAMPADO => self::EN_PATIO->value,
            
            self::LIBERADA_AL_100, self::LIBERADA_CON_INCIDENCIA => self::LIBERACION->value,
        };
    }
}
