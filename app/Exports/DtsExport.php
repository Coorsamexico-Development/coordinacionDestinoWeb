<?php

namespace App\Exports;

use App\Models\Dt;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DtsExport implements  WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return ["DT", 
        "CONFIRMACION", 
        "STATUS", 
        "CITA", 
        "CEDIS", 
        "PLATAFORMA", 
        "LT",
        'NUMERO DE CAJAS'];
    }
}
