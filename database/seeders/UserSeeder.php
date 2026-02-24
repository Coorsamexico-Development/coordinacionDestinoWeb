<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // DB::table('users')->insert([
        //     'name' => "admin",
        //     'email' => "admin@coorsamexico.com",
        //     'password' => Hash::make("c00rs4m3x1c0"),
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
        DB::table('users')->insert([
            'name' => "test",
            'email' => "test@coorsamexico.com",
            'password' => Hash::make("t3stc00rs4"),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
