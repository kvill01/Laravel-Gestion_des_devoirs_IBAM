<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MentionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer le domaine (on suppose qu'il existe déjà)
        $domaine = DB::table('domaines')->first();
        if (!$domaine) {
            throw new \Exception('Veuillez d\'abord exécuter le seeder DomaineSeeder');
        }

        $mentions = [
            [
                'nom' => 'MIAGE',
                'niveau' => 'Licence',
                'type' => 'Méthode Informatique Appliquée à la Gestion d\'Entreprise',
                'domaine_id' => $domaine->id,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'ABF',
                'niveau' => 'Licence',
                'type' => 'Assurance Banque Finance',
                'domaine_id' => $domaine->id,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'ADB',
                'niveau' => 'Licence',
                'type' => 'Assistanat de Direction Bilingue',
                'domaine_id' => $domaine->id,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'MID',
                'niveau' => 'Licence',
                'type' => 'Marketing et Innovation Digitale',
                'domaine_id' => $domaine->id,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nom' => 'CCA',
                'niveau' => 'Licence',
                'type' => 'Comptabilité Contrôle Audit',
                'domaine_id' => $domaine->id,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('mentions')->insert($mentions);
    }
}
