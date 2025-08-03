<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\AnneeAcademique;
use Illuminate\Support\Facades\DB;

class EtudiantFiliereMigrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Migre les étudiants existants vers la nouvelle structure avec filiere_id et niveau_id
     */
    public function run(): void
    {
        // Récupérer l'année académique la plus récente ou en créer une si nécessaire
        $anneeAcademique = AnneeAcademique::orderBy('annee_debut', 'desc')->first();
        if (!$anneeAcademique) {
            $anneeAcademique = AnneeAcademique::create([
                'annee_debut' => 2024,
                'annee_fin' => 2025,
            ]);
        }

        // Récupérer tous les étudiants
        $etudiants = Etudiant::all();
        
        foreach ($etudiants as $etudiant) {
            // Trouver la filière correspondante au champ 'type' de l'étudiant
            $filiere = Filiere::where('code', $etudiant->type)->first();
            
            // Trouver le niveau correspondant au champ 'niveau' de l'étudiant
            $niveau = Niveau::where('code', $etudiant->niveau)->first();
            
            if ($filiere && $niveau) {
                // Mettre à jour l'étudiant avec les IDs de filière et niveau
                $etudiant->filiere_id = $filiere->id;
                $etudiant->niveau_id = $niveau->id;
                $etudiant->annee_academique_id = $anneeAcademique->id;
                $etudiant->save();
                
                $this->command->info("Étudiant {$etudiant->name} {$etudiant->prenom} migré vers {$filiere->nom} - {$niveau->nom}");
            } else {
                $this->command->warn("Impossible de trouver la filière ou le niveau pour l'étudiant {$etudiant->name} {$etudiant->prenom}");
                
                // Si la filière n'existe pas, la créer
                if (!$filiere && $etudiant->type) {
                    $filiere = Filiere::create([
                        'code' => $etudiant->type,
                        'nom' => $etudiant->type,
                        'description' => 'Créé automatiquement lors de la migration'
                    ]);
                    $etudiant->filiere_id = $filiere->id;
                    $this->command->info("Filière {$filiere->nom} créée automatiquement");
                }
                
                // Si le niveau n'existe pas, le créer
                if (!$niveau && $etudiant->niveau) {
                    $niveau = Niveau::create([
                        'code' => $etudiant->niveau,
                        'nom' => 'Niveau ' . $etudiant->niveau,
                        'description' => 'Créé automatiquement lors de la migration'
                    ]);
                    $etudiant->niveau_id = $niveau->id;
                    $this->command->info("Niveau {$niveau->nom} créé automatiquement");
                }
                
                $etudiant->annee_academique_id = $anneeAcademique->id;
                $etudiant->save();
            }
        }
        
        $this->command->info('Migration des étudiants terminée avec succès');
    }
}
