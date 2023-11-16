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
        $plataforma = Plataforma::updateOrCreate([
           'nombre' => $row['plataforma']
        ]);


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
