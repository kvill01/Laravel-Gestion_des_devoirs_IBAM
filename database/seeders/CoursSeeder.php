<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Enseignant;

class CoursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer tous les enseignants disponibles
        $enseignants = Enseignant::all();
        
        if ($enseignants->isEmpty()) {
            $this->command->error('Veuillez d\'abord créer au moins un enseignant.');
            return;
        }
        
        // Récupérer les enseignants par nom (ou utiliser le premier disponible)
        $guinko = $enseignants->where('nom', 'Guinko')->first() ?? $enseignants->first();
        $ouattara = $enseignants->where('nom', 'Ouattara')->first() ?? $enseignants->first();
        $dabone = $enseignants->where('nom', 'Dabone')->first() ?? $enseignants->first();

        // Liste des cours avec leurs enseignants spécifiques
        $cours = [
            [
                'intitule' => 'Conception de Système d\'Exploitation',
                'description' => 'Méthodes et outils pour le développement de systèmes d\'exploitation.',
                'enseignants_id' => $guinko->id,
                'filiere' => 'MIAGE',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'intitule' => 'Conception de Système d\'Information',
                'description' => 'Architecture et conception des systèmes d\'information en entreprise.',
                'enseignants_id' => $ouattara->id,
                'filiere' => 'MIAGE',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'intitule' => 'Web Dynamique',
                'description' => 'Développement d\'applications web dynamiques et interactives.',
                'enseignants_id' => $guinko->id,
                'filiere' => 'MIAGE',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'intitule' => 'Complexité des Algorithmes',
                'description' => 'Analyse de la complexité temporelle et spatiale des algorithmes.',
                'enseignants_id' => $ouattara->id,
                'filiere' => 'MIAGE',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'intitule' => 'IPv6',
                'description' => 'Protocole Internet version 6 : implémentation et transition.',
                'enseignants_id' => $dabone->id,
                'filiere' => 'MIAGE',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'intitule' => 'Technologies Réseau',
                'description' => 'Étude des technologies et protocoles de réseaux informatiques.',
                'enseignants_id' => $dabone->id,
                'filiere' => 'MIAGE',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'intitule' => 'Routage IP',
                'description' => 'Principes et protocoles de routage dans les réseaux IP.',
                'enseignants_id' => $dabone->id,
                'filiere' => 'MIAGE',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insérer uniquement les cours qui n'existent pas déjà
        foreach ($cours as $cour) {
            $filiere_code = $cour['filiere'];
            unset($cour['filiere']); // Retirer le champ filière car il n'existe pas dans la table cours
            
            // Récupérer l'ID de la filière correspondante
            $filiere = Filiere::where('code', $filiere_code)->first();
            
            if (!$filiere) {
                $this->command->error("Filière '$filiere_code' non trouvée. Création du cours '{$cour['intitule']}' ignorée.");
                continue;
            }
            
            // Vérifier si le cours existe déjà
            $existingCours = DB::table('cours')
                ->where('intitule', $cour['intitule'])
                ->first();
                
            if ($existingCours) {
                // Mettre à jour l'enseignant du cours existant
                DB::table('cours')
                    ->where('id', $existingCours->id)
                    ->update(['enseignants_id' => $cour['enseignants_id']]);
                    
                $this->command->info("Cours '{$cour['intitule']}' mis à jour avec succès.");
                continue;
            }
            
            // Insérer le nouveau cours
            $cours_id = DB::table('cours')->insertGetId($cour);
            
            // Associer le cours à la filière pour tous les niveaux
            $niveaux = Niveau::all();
            foreach ($niveaux as $niveau) {
                DB::table('cours_filiere_niveau')->insert([
                    'cours_id' => $cours_id,
                    'filiere_id' => $filiere->id,
                    'niveau_id' => $niveau->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            $this->command->info("Cours '{$cour['intitule']}' créé avec succès pour la filière $filiere_code.");
        }
        
        $this->command->info('Tous les cours ont été créés avec succès!');
    }
}