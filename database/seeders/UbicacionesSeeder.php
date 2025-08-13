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

        DB::table('ubicaciones')->insert([
            'nombre_ubicacion' => "Santa Barbara",
            'abreviacion' => 'SBA',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('ubicaciones')->insert([
            'nombre_ubicacion' => "Mexicalli",
            'abreviacion' => 'CUI',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('ubicaciones')->insert([
            'nombre_ubicacion' => "Chihuahua",
            'abreviacion' => 'CH',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('ubicaciones')->insert([
            'nombre_ubicacion' => "San Martin de Obispo",
            'abreviacion' => 'SMO',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

         DB::table('ubicaciones')->insert([
            'nombre_ubicacion' => "Villahermosa",
            'abreviacion' => 'VH',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

         DB::table('ubicaciones')->insert([
            'nombre_ubicacion' => "Chalco",
            'abreviacion' => 'CH',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
