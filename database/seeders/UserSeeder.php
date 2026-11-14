<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void {
        DB::table('users')->truncate();  // Optativo: vacía la tabla antes de rellenarla
        DB::table('users')->insert([
            'name' => 'Luigi González Trujillo',
            'address' => 'Ñ 67, Almería',
            'email' => 'luigi@gmail.com',
            'email_verified_at' => '2026-11-13 12:40',     
            'password' => '12345678',
        ]);
        DB::table('users')->insert([
            'name' => 'Ollijurt Zeláznog Siul',
            'address' => 'Ñ 76, Aíremla',
            'email' => 'igiul@gmail.com',
            'email_verified_at' => '2026-11-13 04:12',    
            'password' => '87654321',
        ]);        
    }
}
