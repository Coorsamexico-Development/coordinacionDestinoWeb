<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UbicacionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        DB::table('ubicaciones')->insert([
            'nombre_ubicacion' => "Guadalajara",
            'abreviacion' => 'GDL',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('ubicaciones')->insert([
            'nombre_ubicacion' => "Monterrey",
            'abreviacion' => 'MTY',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('ubicaciones')->insert([
            'nombre_ubicacion' => "Culiacan",
            'abreviacion' => 'CLN',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('ubicaciones')->insert([
            'nombre_ubicacion' => "Merida",
            'abreviacion' => 'MRD',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('ubicaciones')->insert([
            'nombre_ubicacion' => "Cuautitlan Izcalli",
            'abreviacion' => 'CUI',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
