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

    public function __construct(int $oc)
    {
        $this->oc = $oc;
    }

    public function query()
    {
        return DB::table('incidencias')
        ->select(
            'productos.SKU as sku',
            'productos.descripcion as producto',
            'tipo_incidencias.nombre as tipo_incidencia',
            'incidencias.cantidad as cantidad',
            'incidencias.cantidadPOD as cantidadPOD'
            )
        ->join('tipo_incidencias','incidencias.tipo_incidencia_id','tipo_incidencias.id')
        ->join('productos','incidencias.ean_id','productos.id')
        ->where('incidencias.ocs_id','=',$this->oc)
        ->orderBy('incidencias.id');;
    }

    public function headings(): array
    {
        return [
        "SKU", 
        "Descripción", 
        "Tipo de incidencia", 
        "Cantidad", 
        "Reporte POD"];
    }
}
