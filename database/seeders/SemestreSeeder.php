<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Semestre;

class SemestreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifier si des semestres existent déjà
        if (Semestre::count() > 0) {
            $this->command->info('Des semestres existent déjà dans la base de données.');
            return;
        }
        
        // Récupérer l'ID de l'année académique
        $anneeAcademique = DB::table('annees_academiques')->first();
        $anneeAcademiqueId = $anneeAcademique ? $anneeAcademique->id : null;
        
        // Récupérer l'ID du programme
        $programme = DB::table('programmes')->first();
        $programmeId = $programme ? $programme->id : null;
        
        // Créer les semestres même si les relations ne sont pas disponibles
        $semestres = [
            ['libelle' => 'Semestre 1', 'programme_id' => $programmeId, 'annee_academique_id' => $anneeAcademiqueId, 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Semestre 2', 'programme_id' => $programmeId, 'annee_academique_id' => $anneeAcademiqueId, 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Semestre 3', 'programme_id' => $programmeId, 'annee_academique_id' => $anneeAcademiqueId, 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Semestre 4', 'programme_id' => $programmeId, 'annee_academique_id' => $anneeAcademiqueId, 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Semestre 5', 'programme_id' => $programmeId, 'annee_academique_id' => $anneeAcademiqueId, 'created_at' => now(), 'updated_at' => now()],
            ['libelle' => 'Semestre 6', 'programme_id' => $programmeId, 'annee_academique_id' => $anneeAcademiqueId, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('semestres')->insert($semestres);
        $this->command->info('Semestres créés avec succès!');
    }
}
