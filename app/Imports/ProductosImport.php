<?php

namespace App\Imports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductosImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        //dd($row);
        Producto::updateOrCreate([
            'SKU' => $row['SKU'],
            'descripcion' => $row['DESCRIPCION'],
            'DUN 14' => $row['DUN 14'],
            'EAN' => $row['EAN']
        ]);
    }
}
