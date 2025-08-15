<?php

namespace App\Imports;

use App\Models\ConfirmacionDt;
use App\Models\Dt;
use App\Models\LineaTransporte;
use App\Models\Plataforma;
use App\Models\Statu;
use App\Models\StatusDt;
use App\Models\Ubicacione;
use DateTime;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class DtsImport implements ToModel, WithHeadingRow //WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        //dd($row);
        //Evaluador de estandarizado de plataformas
        $plataforma = null;

      if (str_contains($row['plataforma'], 'WAL') || str_contains($row['plataforma'], 'CENTER') || str_contains($row['plataforma'], 'SUPERCENTER') || str_contains($row['plataforma'], 'AUTO')) {
          $plataforma = Plataforma::updateOrCreate([
              'nombre' => 'WALMART'
          ]);
      }
      elseif (str_contains($row['plataforma'], 'SAM')) {
          $plataforma = Plataforma::updateOrCreate([
              'nombre' => 'SAMS'
          ]);
      }
      elseif (str_contains($row['plataforma'], 'MI BODEGA')) {
          $plataforma = Plataforma::updateOrCreate([
              'nombre' => 'MI BODEGA'
          ]);
      }
      elseif (str_contains($row['plataforma'], 'BODEGA')) {
          $plataforma = Plataforma::updateOrCreate([
              'nombre' => 'BODEGA'
          ]);
      }
      elseif (str_contains($row['plataforma'], 'BAE')) {
          $plataforma = Plataforma::updateOrCreate([
              'nombre' => 'BAE'
          ]);
      }
      elseif (str_contains($row['plataforma'], 'ECOM')) {
          $plataforma = Plataforma::updateOrCreate([
              'nombre' => 'ECOMMERCE'
          ]);
      }
       


        $status = Statu::select('status.*')
        ->where('status.nombre','LIKE','%'.$row['status'].'%')
        ->first();

        if($status == null)
        {
          dd('no existe'. $row['status']);
        }
      

        $linea_transporte = LineaTransporte::updateOrCreate([
          'nombre' => $row['lt']
        ]);

        $ubicacion = Ubicacione::updateOrCreate([
            'nombre_ubicacion' => $row['cedis']
        ]);
         
        $dt = Dt::updateOrCreate([
          'referencia_dt' => $row['dt']
        ]);

       date_default_timezone_set('America/Mexico_City');
       $fecha_actual = getdate();
       $hora_actual = ($fecha_actual['hours']-1) . ":" . $fecha_actual['minutes'] . ":" . $fecha_actual['seconds'];
       $newFechaActual = $fecha_actual['year'].'-'.$fecha_actual['mon'].'-'.$fecha_actual['mday'].' '.$hora_actual; 
       $date = ($row['cita'] - 25569) * 86400;
       $gmtDate= gmdate("Y-m-d H:i", $date);
       //$mDate = new DateTime($gmtDate);
       //Buscamos el dt a crear
       $confirmacion_a_buscar = ConfirmacionDt::select('confirmacion_dts.*')
       ->where('confirmacion_dts.confirmacion','=',$row['confirmacion'])
       ->where('confirmacion_dts.dt_id','=', $dt->id)
       ->first();

       if($confirmacion_a_buscar == null)//sino exsite se crea
       {
           $confirmacionDt = ConfirmacionDt::updateOrCreate([
             'confirmacion' => $row['confirmacion'],
             'dt_id' => $dt->id,
             'numero_cajas' => $row['numero_de_cajas'],
             'cita' => $gmtDate,
             'linea_transporte_id' => $linea_transporte->id,
             'plataforma_id' => $plataforma->id,
             'ubicacion_id' => $ubicacion->id,
             'status_id' => $status->id,
           ]);

         //Creamos el primer registro en la tabla de historico
          StatusDt::updateOrCreate([
            'confirmacion_dt_id' => $confirmacionDt->id,
            'status_id' => $status->id,
            'activo' => 1,
          ]);
       }
       //sino manda mensaje de error

    }


    /*
    public function rules(): array
    {
      return [
        'cita' => 'date_format:d/m/Y'
       ];
    }

    public function customValidationMessages()
{
    return [
        'cita' => 'El formato de la cita es erroneo aaaa-mm-dd',
    ];
}
*/

}
