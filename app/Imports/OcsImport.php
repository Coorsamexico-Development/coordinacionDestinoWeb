<?php

namespace App\Imports;

use App\Models\ConfirmacionDt;
use App\Models\Oc;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OcsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function __construct(string $confirmacion)
    {
      $this->confirmacion = $confirmacion;
    }
    
    public function model(array $row)
    {
        //dd($row);
        $confirmacion_id = ConfirmacionDt::select('confirmacion_dts.*')
        ->where('confirmacion_dts.confirmacion','=',$this->confirmacion)
        ->first();

        Oc::updateOrCreate([
           'referencia' => $row['referencia'],
           'confirmacion_dt_id' => $confirmacion_id['id']
        ]);
    }
}
