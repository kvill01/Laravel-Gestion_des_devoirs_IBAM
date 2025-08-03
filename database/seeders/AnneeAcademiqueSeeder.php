<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnneeAcademiqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $annees = [
            [
                'annee_debut' => 2023,
                'annee_fin' => 2024,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'annee_debut' => 2024,
                'annee_fin' => 2025,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'annee_debut' => 2025,
                'annee_fin' => 2026,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('annees_academiques')->insert($annees);
    }
}
