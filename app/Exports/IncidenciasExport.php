<?php

namespace App\Exports;

use App\Models\Incidencia;
use App\Invoice;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IncidenciasExport implements FromQuery, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    use Exportable;

    public function __construct(int $viaje)
    {
        $this->viaje = $viaje;
    }

    public function query()
    {
        return DB::table('incidencias')
        ->select(
            'confirmacion_dts.confirmacion',
            'dts.referencia_dt',
            'ocs.referencia',
            'productos.SKU as sku',
            'productos.descripcion as producto',
            'tipo_incidencias.nombre as tipo_incidencia',
            'incidencias.cantidad as cantidad',
            'incidencias.cantidadPOD as cantidadPOD'
            )
        ->join('tipo_incidencias','incidencias.tipo_incidencia_id','tipo_incidencias.id')
        ->join('productos','incidencias.ean_id','productos.id')
        ->rightJoin('ocs', 'incidencias.ocs_id','ocs.id')
        ->rightJoin('confirmacion_dts','ocs.confirmacion_dt_id','confirmacion_dts.id')
        ->join('dts', 'confirmacion_dts.dt_id','dts.id')
        ->where('confirmacion_dts.id','=',$this->viaje)
        ->orderBy('incidencias.id');;
    }

    public function headings(): array
    {
        return [
        "Confirmación",
        "DT",
        "OC",
        "SKU", 
        "Descripción", 
        "Tipo de incidencia", 
        "Cantidad", 
        "Reporte POD"];
    }
}
