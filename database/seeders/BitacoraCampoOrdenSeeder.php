<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BitacoraCampo;

class BitacoraCampoOrdenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hasComplete = BitacoraCampo::where('orden', '!=', 0)->exists();
        if ($hasComplete) {
            return;
        }

        $campos = BitacoraCampo::orderBy('id', 'asc')->get();
        $orden = 1;

        foreach ($campos as $campo) {

            $campo->update(['orden' => $orden]);
            $orden++;
        }

        if ($campo->id == 5) {
            $campo->update(['orden' => 6]);
        }
        if ($campo->id == 6) {
            $campo->update(['orden' => 5]);
        }
    }
}
