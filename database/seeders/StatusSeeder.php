<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Status padre
        DB::table('status')->insert([
            'nombre' => "En transito",
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('status')->insert([
            'nombre' => "En patio",
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('status')->insert([
            'nombre' => "En rampa",
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('status')->insert([
            'nombre' => "Liberada",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('status')->insert([
            'nombre' => "Liberada al 100",
            'created_at' => now(),
            'updated_at' => now(),
        ]);
      //Status hijos
        DB::table('status')->insert([
          'nombre' => "En riesgo",
          'status_padre' => 1,
          'color' => '#FFAE3F',
          'created_at' => now(),
          'updated_at' => now(),
        ]);
        DB::table('status')->insert([
            'nombre' => "Por recibir",
            'status_padre' => 1,
            'color' => '#56D0C1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('status')->insert([
            'nombre' => "Documentado",
            'status_padre' => 2,
            'color' => '#44BFFC',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('status')->insert([
            'nombre' => "En espera",
            'status_padre' => 2,
            'color' => '#56D0C1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('status')->insert([
            'nombre' => "Enrampado",
            'status_padre' => 3,
            'color' => '#697FEA',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('status')->insert([
            'nombre' => "Descarga",
            'status_padre' => 3,
            'color' => '#56D0C1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
    }
}
