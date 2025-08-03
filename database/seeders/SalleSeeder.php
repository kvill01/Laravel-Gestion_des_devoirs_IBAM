<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $salles = [
            ['nom' => 'A1', 'capacite' => 50, 'localisation' => 'Bâtiment A, Rez-de-chaussée', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'A2', 'capacite' => 50, 'localisation' => 'Bâtiment A, Rez-de-chaussée', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'A3', 'capacite' => 50, 'localisation' => 'Bâtiment A, Rez-de-chaussée', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'A4', 'capacite' => 50, 'localisation' => 'Bâtiment A, 1ère étage', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'A5', 'capacite' => 50, 'localisation' => 'Bâtiment A, 1ère étage', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'A6', 'capacite' => 50, 'localisation' => 'Bâtiment A, 1ère étage', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'B1', 'capacite' => 45, 'localisation' => 'Bâtiment B, Rez-de-chaussée', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'B2', 'capacite' => 35, 'localisation' => 'Bâtiment B, Rez-de-chaussée', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'B3', 'capacite' => 35, 'localisation' => 'Bâtiment B, Rez-de-chaussée', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'B4', 'capacite' => 35, 'localisation' => 'Bâtiment B, 1ère étage', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'B5', 'capacite' => 35, 'localisation' => 'Bâtiment B, 1ère étage', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'B6', 'capacite' => 35, 'localisation' => 'Bâtiment B, 1ère étage', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('salles')->insert($salles);
    }
}
