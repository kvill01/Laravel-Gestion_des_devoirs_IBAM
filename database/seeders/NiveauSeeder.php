<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Niveau;
use Illuminate\Support\Facades\DB;

class NiveauSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifier si des niveaux existent déjà
        if (Niveau::count() > 0) {
            // Si des niveaux existent déjà, ne pas réexécuter le seeder
            return;
        }
        
        // Niveaux de base
        $niveaux = [
            ['code' => 'L1', 'nom' => 'Licence 1', 'description' => 'Première année de licence'],
            ['code' => 'L2', 'nom' => 'Licence 2', 'description' => 'Deuxième année de licence'],
            ['code' => 'L3', 'nom' => 'Licence 3', 'description' => 'Troisième année de licence'],
            ['code' => 'M1', 'nom' => 'Master 1', 'description' => 'Première année de master'],
            ['code' => 'M2', 'nom' => 'Master 2', 'description' => 'Deuxième année de master'],
        ];
        
        // Ajouter les niveaux de base
        foreach ($niveaux as $niveau) {
            // Vérifier si le niveau existe déjà
            $existingNiveau = Niveau::where('code', $niveau['code'])->first();
            if (!$existingNiveau) {
                Niveau::create($niveau);
            }
        }
        
        $this->command->info('Niveaux créés avec succès!');
    }
}
