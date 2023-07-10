<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoCampoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('tipos_campos')->insert([
            'nombre' => "text",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        
        DB::table('tipos_campos')->insert([
            'nombre' => "number",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        
        DB::table('tipos_campos')->insert([
            'nombre' => "image",
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
