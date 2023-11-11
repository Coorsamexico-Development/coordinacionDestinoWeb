<?php

namespace App\Exports;

use App\Models\ConfirmacionDt;
use App\Models\Valor;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Illuminate\Support\Facades\DB;

class ViajesFinalizadosExport implements FromCollection, WithHeadings, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Query
    */
    public function __construct(
     string $fechaInicial,
     string $fechaFinal,
     string $confirmacion,
     string $dt,
     string $status,
     string $ubicacion,
     string $plataforma
     )
    {
        $this->fechaInicial = $fechaInicial;
        $this->fechaFinal = $fechaFinal;
        $this->confirmacion = $confirmacion;
        $this->dt = $dt;
        $this->status = $status;
        $this->ubicacion = $ubicacion;
        $this->plataforma = $plataforma;
    }


    public function collection()
    {
       $reporte = [];
       $viajes = DB::table('incidencias')
        ->select(
            'dts.referencia_dt',
            'confirmacion_dts.confirmacion as confirmacion',
            'status.nombre as status',
            'confirmacion_dts.cita',
            'ubicaciones.nombre_ubicacion as ubicacion',
            'plataformas.nombre as plataforma',
            'linea_transportes.nombre as lt',
            'ocs.referencia',
            'productos.SKU as sku',
            'productos.descripcion as producto',
            'tipo_incidencias.nombre as tipo_incidencia',
            'incidencias.cantidad as cantidad',
            'incidencias.cantidadPOD as cantidadPOD',
            )
        ->join('tipo_incidencias','incidencias.tipo_incidencia_id','tipo_incidencias.id')
        ->join('productos','incidencias.ean_id','productos.id')
        ->join('ocs', 'incidencias.ocs_id','ocs.id')
        ->join('confirmacion_dts','ocs.confirmacion_dt_id','confirmacion_dts.id')
        ->join('dts', 'confirmacion_dts.dt_id','dts.id')
        ->join('status','confirmacion_dts.status_id','status.id')
        ->join('ubicaciones', 'confirmacion_dts.ubicacion_id','ubicaciones.id')
        ->join('plataformas','confirmacion_dts.plataforma_id','plataformas.id')
        ->join('linea_transportes','confirmacion_dts.linea_transporte_id','linea_transportes.id')
        ->whereBetween('confirmacion_dts.cita',[ $this->fechaInicial, $this->fechaFinal])
        ->where(function($query) 
        {
            $query->where('status.id','=',10) //liberado al 100
              ->orWhere('status.id','=',11); //liberado con incidencias
         })
         ->where('confirmacion_dts.confirmacion','LIKE','%'.$this->confirmacion.'%')
         ->where('dts.referencia_dt','LIKE','%'.$this->dt.'%')
         ->orderBy('confirmacion_dts.id')
         ->get();


         for ($i=0; $i < count($viajes) ; $i++) 
         { 
            $viaje = $viajes[$i];
            $confirmacion = ConfirmacionDt::select('confirmacion_dts.*')
                             ->where('confirmacion_dts.confirmacion','=',$viaje->confirmacion)
                             ->first();

            $no_cajas =  Valor::select('valors.valor')
                         ->join('dt_campo_valors','valors.dt_campo_valor_id','dt_campo_valors.id')
                         ->where('dt_campo_valors.campo_id','=', 1)
                         ->where('dt_campo_valors.confirmacion_id','=', $confirmacion->id)
                         ->get();

            $rampa = Valor::select('valors.valor')
            ->join('dt_campo_valors','valors.dt_campo_valor_id','dt_campo_valors.id')
            ->where('dt_campo_valors.campo_id','=', 9)
            ->where('dt_campo_valors.confirmacion_id','=', $confirmacion->id)
            ->get();

           // dd($no_cajas[0]['valor']); 
           if(count($no_cajas) == 0 )
           {
              $no_cajas = 0;
           }
           else
           {
             $no_cajas = $no_cajas[0]['valor'];
           }

           if(count($rampa) == 0 )
           {
              $rampa = 0;
           }
           else
           {
             $rampa = $rampa[0]['valor'];
           }

            $viaje->cajas = $no_cajas;
            $viaje->rampa = $rampa;
        
            array_push($reporte, $viaje);
         }

         return new Collection($reporte);
    }

    public function headings(): array
    {
        return [
        "DT",
        "CONFIRMACION",
        "STATUS",
        "CITA",
        "CEDIS",
        "PLATAFORMA",
        "LT",
        "OC",
        "SKU",
        "DESCRIPCION DE SKU",
        "TIPO DE INCIDENCIAS",
        "CANTIDAD DE INCIDENCIAS",
        "CANTIDAD DE INCIDENCIAS POD",
        "NUMERO DE CAJAS",
        "RAMPA"
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,
            'B' => 15,
            'C' => 20,
            'D' => 22,
            'E' => 12,
            'F' => 12,
            'H' => 20,
            'I' => 20 ,
            'J' => 25,
            'K' => 15,
            'L'  => 12,
            'M' => 12,
            'N' => 12         
        ];
    }
}
