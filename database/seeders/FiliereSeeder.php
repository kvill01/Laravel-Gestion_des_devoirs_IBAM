<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Filiere;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FiliereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifier si des filières existent déjà
        if (Filiere::count() > 0) {
            // Si des filières existent déjà, ne pas réexécuter le seeder
            return;
        }
        
        // Filières de base
        $filieres = [
            [
                'code' => 'MIAGE', 
                'nom' => 'Méthode Informatique Appliquée à la Gestion d\'Entreprise', 
                'description' => 'Filière MIAGE - Informatique de gestion'
            ],
            [
                'code' => 'ABF', 
                'nom' => 'Assurance Banque Finance', 
                'description' => 'Filière Assurance, Banque et Finance'
            ],
            [
                'code' => 'ADB', 
                'nom' => 'Assistanat de Direction Bilingue', 
                'description' => 'Filière Assistanat de Direction Bilingue'
            ],
            [
                'code' => 'CCA', 
                'nom' => 'Comptabilité Contrôle Audit', 
                'description' => 'Filière Comptabilité, Contrôle et Audit'
            ],
            [
                'code' => 'MID', 
                'nom' => 'Marketing et Innovation Digitale', 
                'description' => 'Filière Marketing et Innovation Digitale'
            ],
        ];
        
        // Ajouter les filières de base
        foreach ($filieres as $filiere) {
            // Vérifier si la filière existe déjà
            $existingFiliere = Filiere::where('code', $filiere['code'])->first();
            if (!$existingFiliere) {
                Filiere::create($filiere);
            }
        }
    }
}
