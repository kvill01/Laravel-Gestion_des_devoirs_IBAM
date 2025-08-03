<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Seeders de base pour les données de référence
            DomaineSeeder::class,         // Domaines d'études
            ProgrammeSeeder::class,       // Programmes (Licence, Master)
            AnneeAcademiqueSeeder::class, // Années académiques
            SemestreSeeder::class,        // Semestres
            SalleSeeder::class,           // Salles
            
            // Seeders pour les utilisateurs
            AdminSeeder::class,           // Administrateur
            EnseignantSeeder::class,      // Enseignants de test
            SurveillantSeeder::class,     // Surveillants de test
            
            // Nouveaux seeders pour la structure filière et niveau
            FiliereSeeder::class,         // Filières
            NiveauSeeder::class,          // Niveaux
            
            // Seeder pour les cours (dépend des enseignants et filières/niveaux)
            CoursSeeder::class,           // Cours
            CoursFiliereNiveauSeeder::class, // Associations cours-filière-niveau
            
            // Migration des étudiants vers la nouvelle structure
            EtudiantFiliereMigrationSeeder::class // Migration des étudiants existants
        ]);
    }
}