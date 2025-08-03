<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cours;
use App\Models\Filiere;
use App\Models\Niveau;
use Illuminate\Support\Facades\DB;

class CoursFiliereNiveauSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifier si des associations existent déjà
        if (DB::table('cours_filiere_niveau')->count() > 0) {
            // Si des associations existent déjà, ne pas réexécuter le seeder
            return;
        }
        
        // Récupérer tous les cours, filières et niveaux
        $cours = Cours::all();
        $filieres = Filiere::all();
        $niveaux = Niveau::all();
        
        // Si aucun cours, filière ou niveau n'existe, ne rien faire
        if ($cours->isEmpty() || $filieres->isEmpty() || $niveaux->isEmpty()) {
            $this->command->warn('Aucun cours, filière ou niveau trouvé. Impossible de créer des associations.');
            return;
        }
        
        // Définir les associations entre cours et filières basées sur les commentaires du CoursSeeder
        $coursAssociations = [
            // Cours pour MIAGE
            'Algorithmique et Structures de Données' => ['MIAGE' => [1, 2]],
            'Bases de Données Avancées' => ['MIAGE' => [2, 3]],
            'Génie Logiciel' => ['MIAGE' => [2, 3]],
            'Intelligence Artificielle' => ['MIAGE' => [3]],
            'Systèmes d\'Information' => ['MIAGE' => [1, 2, 3]],
            
            // Cours pour CCA
            'Comptabilité Générale' => ['CCA' => [1, 2, 3]],
            'Comptabilité Analytique' => ['CCA' => [2, 3]],
            'Audit Financier' => ['CCA' => [3]],
            'Contrôle de Gestion' => ['CCA' => [2, 3]],
            'Fiscalité des Entreprises' => ['CCA' => [2, 3]],
            
            // Cours pour ABF
            'Mathématiques Financières' => ['ABF' => [1, 2]],
            'Gestion de Portefeuille' => ['ABF' => [2, 3]],
            'Marchés Financiers' => ['ABF' => [2, 3]],
            'Gestion Bancaire' => ['ABF' => [3]],
            'Analyse Financière' => ['ABF' => [2, 3]],
            
            // Cours pour MID
            'Data Mining' => ['MID' => [2, 3]],
            'Big Data Analytics' => ['MID' => [3]],
            'Machine Learning' => ['MID' => [3]],
            'Visualisation de Données' => ['MID' => [2, 3]],
            'Architectures de Données' => ['MID' => [2, 3]],
            
            // Cours pour ADB
            'Management Stratégique' => ['ADB' => [2, 3]],
            'Gestion des Ressources Humaines' => ['ADB' => [1, 2, 3]],
            'Droit des Affaires' => ['ADB' => [1, 2]],
            'Marketing International' => ['ADB' => [2, 3]],
            'Entrepreneuriat' => ['ADB' => [3]],
            
            // Cours partagés entre filières
            'Intelligence Artificielle' => ['MIAGE' => [3], 'MID' => [2, 3]],
            'Big Data Analytics' => ['MIAGE' => [3], 'MID' => [3]],
            'Gestion des Ressources Humaines' => ['ADB' => [1, 2, 3], 'CCA' => [2]],
            'Droit des Affaires' => ['ADB' => [1, 2], 'CCA' => [2]],
            'Marketing International' => ['ADB' => [2, 3], 'MID' => [2]],
        ];
        
        // Créer les associations
        foreach ($cours as $cours_item) {
            if (isset($coursAssociations[$cours_item->intitule])) {
                $associations = $coursAssociations[$cours_item->intitule];
                
                foreach ($associations as $filiere_code => $niveau_ids) {
                    // Trouver la filière par son code
                    $filiere = $filieres->where('code', $filiere_code)->first();
                    
                    if (!$filiere) {
                        $this->command->warn("Filière avec code '{$filiere_code}' non trouvée pour le cours '{$cours_item->intitule}'");
                        continue;
                    }
                    
                    foreach ($niveau_ids as $niveau_id) {
                        // Trouver le niveau par son ID
                        $niveau = $niveaux->where('id', $niveau_id)->first();
                        
                        if (!$niveau) {
                            $this->command->warn("Niveau avec ID '{$niveau_id}' non trouvé");
                            continue;
                        }
                        
                        // Vérifier si l'association existe déjà
                        $existingAssociation = DB::table('cours_filiere_niveau')
                            ->where('cours_id', $cours_item->id)
                            ->where('filiere_id', $filiere->id)
                            ->where('niveau_id', $niveau->id)
                            ->first();
                        
                        if (!$existingAssociation) {
                            DB::table('cours_filiere_niveau')->insert([
                                'cours_id' => $cours_item->id,
                                'filiere_id' => $filiere->id,
                                'niveau_id' => $niveau->id,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                            
                            $this->command->info("Association créée: Cours '{$cours_item->intitule}' - Filière '{$filiere->nom}' - Niveau '{$niveau->nom}'");
                        }
                    }
                }
            } else {
                $this->command->warn("Aucune association définie pour le cours '{$cours_item->intitule}'");
            }
        }
        
        $this->command->info('Associations cours-filière-niveau créées avec succès!');
    }
}
