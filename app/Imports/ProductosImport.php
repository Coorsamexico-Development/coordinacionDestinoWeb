<?php

namespace App\Imports;

use App\Models\Producto;
use Illuminate\Support\Facades\Log;
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
        //
        Producto::updateOrCreate(
            [
                'clave_producto' => trim($row['clave_de_producto']),
            ],
            [
                'descripcion' => trim($row['descripcion']),
                'SKU' => trim($row['upcsku']),
                'UM' => trim($row['um'])
            ]
        );

        //dd($newProd);
    }
}
