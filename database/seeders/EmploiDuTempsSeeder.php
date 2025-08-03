<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Enseignant;
use App\Models\Cours;
use App\Models\Salle;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Semestre;
use App\Models\AnneeAcademique;
use Carbon\Carbon;

class EmploiDuTempsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les données nécessaires
        $anneeAcademique = AnneeAcademique::orderBy('annee_debut', 'desc')->first();
        if (!$anneeAcademique) {
            $this->command->info('Aucune année académique trouvée. Création impossible de l\'emploi du temps.');
            return;
        }

        // Trouver ou créer la filière Informatique
        $filiere = Filiere::firstOrCreate(
            ['nom' => 'Informatique'],
            [
                'code' => 'INFO',
                'description' => 'Licence en Informatique'
            ]
        );

        // Trouver ou créer le niveau Licence 2
        $niveau = Niveau::firstOrCreate(
            ['nom' => 'Licence 2'],
            [
                'code' => 'L2',
                'description' => 'Deuxième année de licence'
            ]
        );

        // Trouver ou créer le semestre 4
        $semestre = Semestre::firstOrCreate(
            ['libelle' => 'Semestre 4', 'annee_academique_id' => $anneeAcademique->id],
            ['programme_id' => 1, 'created_at' => now(), 'updated_at' => now()]
        );

        // Trouver les enseignants mentionnés dans l'image
        $enseignantDabone = Enseignant::where('nom', 'like', '%Dabone%')->first();
        $enseignantYacoubaOuattara = Enseignant::where('nom', 'like', '%Ouattara%')->where('prenom', 'like', '%Yacouba%')->first();
        $enseignantDimitriOuattara = Enseignant::where('nom', 'like', '%Ouattara%')->where('prenom', 'like', '%Dimitri%')->first();

        // Créer les enseignants s'ils n'existent pas
        if (!$enseignantDabone) {
            $enseignantDabone = Enseignant::create([
                'nom' => 'Dabone',
                'prenom' => 'Yamba',
                'email' => 'dabone@example.com',
                'grade' => 'Dr',
                'domaine_id' => 1,
                'user_id' => 1, // À adapter selon votre base de données
            ]);
        }

        if (!$enseignantYacoubaOuattara) {
            $enseignantYacoubaOuattara = Enseignant::create([
                'nom' => 'Ouattara',
                'prenom' => 'Yacouba',
                'email' => 'yacouba.ouattara@example.com',
                'grade' => 'Dr',
                'domaine_id' => 1,
                'user_id' => 1, // À adapter selon votre base de données
            ]);
        }

        if (!$enseignantDimitriOuattara) {
            $enseignantDimitriOuattara = Enseignant::create([
                'nom' => 'Ouattara',
                'prenom' => 'Dimitri',
                'email' => 'dimitri.ouattara@example.com',
                'grade' => 'Dr',
                'domaine_id' => 1,
                'user_id' => 1, // À adapter selon votre base de données
            ]);
        }

        // Récupérer ou créer les cours
        $coursRoutageIP = Cours::where('intitule', 'Routage IP')->first();
        $coursPython = Cours::where('intitule', 'Python')->first();
        $coursPOO = Cours::where('intitule', 'POO')->first();
        $coursWebDynamique = Cours::where('intitule', 'Web Dynamique')->first();

        if (!$coursRoutageIP && $enseignantDabone) {
            $coursRoutageIP = Cours::create([
                'intitule' => 'Routage IP',
                'description' => 'Cours sur le routage IP et les réseaux',
                'enseignants_id' => $enseignantDabone->id,
            ]);
            
            // Ajouter l'association dans la table pivot
            DB::table('cours_enseignant')->insert([
                'enseignant_id' => $enseignantDabone->id,
                'cours_id' => $coursRoutageIP->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        if (!$coursPython && $enseignantYacoubaOuattara) {
            $coursPython = Cours::create([
                'intitule' => 'Python',
                'description' => 'Programmation en Python',
                'enseignants_id' => $enseignantYacoubaOuattara->id,
            ]);
            
            // Ajouter l'association dans la table pivot
            DB::table('cours_enseignant')->insert([
                'enseignant_id' => $enseignantYacoubaOuattara->id,
                'cours_id' => $coursPython->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        if (!$coursPOO && $enseignantDimitriOuattara) {
            $coursPOO = Cours::create([
                'intitule' => 'POO',
                'description' => 'Programmation Orientée Objet',
                'enseignants_id' => $enseignantDimitriOuattara->id,
            ]);
            
            // Ajouter l'association dans la table pivot
            DB::table('cours_enseignant')->insert([
                'enseignant_id' => $enseignantDimitriOuattara->id,
                'cours_id' => $coursPOO->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        if (!$coursWebDynamique) {
            $coursWebDynamique = Cours::create([
                'intitule' => 'Web Dynamique',
                'description' => 'Développement web dynamique',
                'enseignants_id' => 1, // À adapter selon votre base de données
            ]);
            
            // Ajouter l'association dans la table pivot
            DB::table('cours_enseignant')->insert([
                'enseignant_id' => 1, // À adapter selon votre base de données
                'cours_id' => $coursWebDynamique->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Récupérer ou créer la salle B6
        $salleB6 = Salle::firstOrCreate(
            ['nom' => 'B6'],
            ['capacite' => 50, 'disponible' => true, 'type' => 'Salle de cours']
        );

        // Date de début et de fin pour la semaine de l'emploi du temps
        $dateDebut = Carbon::createFromDate(2025, 3, 31); // Lundi 31 mars 2025
        $dateFin = Carbon::createFromDate(2025, 4, 5); // Samedi 5 avril 2025

        // Supprimer les anciens emplois du temps pour cette période et cette filière/niveau
        DB::table('emploi_du_temps')
            ->where('filiere_id', $filiere->id)
            ->where('niveau_id', $niveau->id)
            ->where('semestre_id', $semestre->id)
            ->whereBetween('date_debut', [$dateDebut, $dateFin])
            ->delete();

        // Tableau des cours à ajouter selon l'image
        $coursEmploiDuTemps = [
            // Lundi - rien de spécifique dans l'image
            
            // Mardi
            [
                'jour' => 'Mardi',
                'cours_id' => $coursPython ? $coursPython->id : null,
                'enseignants_id' => $enseignantYacoubaOuattara ? $enseignantYacoubaOuattara->id : null,
                'heure_debut' => '07:30:00',
                'heure_fin' => '12:30:00',
                'salle_id' => $salleB6->id,
                'type_cours' => 'Cours',
            ],
            [
                'jour' => 'Mardi',
                'cours_id' => $coursWebDynamique ? $coursWebDynamique->id : null,
                'enseignants_id' => 1, // À adapter
                'heure_debut' => '14:00:00',
                'heure_fin' => '18:00:00',
                'salle_id' => $salleB6->id,
                'type_cours' => 'Devoir',
            ],
            
            // Mercredi
            [
                'jour' => 'Mercredi',
                'cours_id' => $coursRoutageIP ? $coursRoutageIP->id : null,
                'enseignants_id' => $enseignantDabone ? $enseignantDabone->id : null,
                'heure_debut' => '07:30:00',
                'heure_fin' => '12:30:00',
                'salle_id' => $salleB6->id,
                'type_cours' => 'Cours',
            ],
            
            // Jeudi
            [
                'jour' => 'Jeudi',
                'cours_id' => $coursPOO ? $coursPOO->id : null,
                'enseignants_id' => $enseignantDimitriOuattara ? $enseignantDimitriOuattara->id : null,
                'heure_debut' => '07:30:00',
                'heure_fin' => '12:30:00',
                'salle_id' => $salleB6->id,
                'type_cours' => 'Cours',
            ],
            
            // Vendredi
            [
                'jour' => 'Vendredi',
                'cours_id' => $coursPOO ? $coursPOO->id : null,
                'enseignants_id' => $enseignantDimitriOuattara ? $enseignantDimitriOuattara->id : null,
                'heure_debut' => '07:30:00',
                'heure_fin' => '12:30:00',
                'salle_id' => $salleB6->id,
                'type_cours' => 'Cours',
            ],
        ];

        // Insérer les cours dans l'emploi du temps
        foreach ($coursEmploiDuTemps as $cours) {
            if ($cours['cours_id'] && $cours['enseignants_id']) {
                DB::table('emploi_du_temps')->insert([
                    'filiere_id' => $filiere->id,
                    'niveau_id' => $niveau->id,
                    'semestre_id' => $semestre->id,
                    'cours_id' => $cours['cours_id'],
                    'enseignants_id' => $cours['enseignants_id'],
                    'salle_id' => $cours['salle_id'],
                    'jour' => $cours['jour'],
                    'heure_debut' => $cours['heure_debut'],
                    'heure_fin' => $cours['heure_fin'],
                    'type_cours' => $cours['type_cours'],
                    'date_debut' => $dateDebut,
                    'date_fin' => $dateFin,
                    'commentaire' => 'Semaine du ' . $dateDebut->format('d/m/Y') . ' au ' . $dateFin->format('d/m/Y'),
                    'annee_academique_id' => $anneeAcademique->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Emploi du temps créé avec succès pour la semaine du ' . $dateDebut->format('d/m/Y') . ' au ' . $dateFin->format('d/m/Y'));
    }
}
