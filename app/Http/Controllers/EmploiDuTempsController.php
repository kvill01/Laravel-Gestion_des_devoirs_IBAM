<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmploiDuTemps;
use App\Models\Semestre;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Etudiant;
use App\Models\AnneeAcademique;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

class EmploiDuTempsController extends Controller
{
    /**
     * Affiche l'emploi du temps de la semaine en cours pour l'étudiant connecté
     */
    public function index()
    {
        // Récupérer l'étudiant connecté
        $etudiant = Etudiant::where('user_id', Auth::id())->first();
        
        if (!$etudiant) {
            return Redirect::route('dashboard')->with('error', 'Profil étudiant non trouvé');
        }
        
        // Déboguer les informations de l'étudiant
        Log::info('Informations étudiant:', [
            'etudiant_id' => $etudiant->id,
            'filiere_id' => $etudiant->filiere_id ?? 'non défini',
            'niveau_id' => $etudiant->niveau_id ?? 'non défini',
            'user_id' => $etudiant->user_id
        ]);
        
        // Semaine actuelle
        $dateDebut = Carbon::now()->startOfWeek();
        $dateFin = Carbon::now()->endOfWeek();
        
        return $this->showEmploiDuTemps($etudiant, $dateDebut, $dateFin);
    }
    
    /**
     * Affiche l'emploi du temps pour une semaine spécifique
     */
    public function showByWeek(Request $request)
    {
        // Récupérer l'étudiant connecté
        $etudiant = Etudiant::where('user_id', Auth::id())->first();
        
        if (!$etudiant) {
            return Redirect::route('dashboard')->with('error', 'Profil étudiant non trouvé');
        }
        
        // Récupérer la date de début de semaine depuis la requête
        $date = $request->input('date', Carbon::now()->format('Y-m-d'));
        $dateObj = Carbon::createFromFormat('Y-m-d', $date);
        $dateDebut = $dateObj->copy()->startOfWeek();
        $dateFin = $dateObj->copy()->endOfWeek();
        
        return $this->showEmploiDuTemps($etudiant, $dateDebut, $dateFin);
    }
    
    /**
     * Méthode interne pour afficher l'emploi du temps
     */
    private function showEmploiDuTemps($etudiant, $dateDebut, $dateFin)
    {
        // Obtenir l'année académique en cours
        $anneeAcademique = AnneeAcademique::orderBy('annee_debut', 'desc')->first();
        
        // Récupérer les informations de l'étudiant
        $filiere = $etudiant->filiere;
        $niveau = $etudiant->niveau;
        
        // Déboguer les informations de filière et niveau
        Log::info('Informations académiques:', [
            'filiere' => $filiere ? $filiere->toArray() : 'non définie',
            'niveau' => $niveau ? $niveau->toArray() : 'non défini',
            'annee_academique' => $anneeAcademique ? $anneeAcademique->toArray() : 'non définie'
        ]);
        
        // Vérifier si la filière et le niveau sont définis
        if (!$filiere || !$niveau) {
            return View::make('etudiant.emploi_du_temps', [
                'emploiDuTemps' => [],
                'dateDebut' => $dateDebut,
                'dateFin' => $dateFin,
                'filiere' => $filiere,
                'niveau' => $niveau,
                'semestre' => null,
                'error' => 'Informations incomplètes : filière ou niveau non définis pour votre profil'
            ]);
        }
        
        // Récupérer le semestre en fonction de la filière et de l'année académique
        try {
            $semestre = Semestre::where('annee_academique_id', $anneeAcademique->id)
                            ->whereHas('programme', function($query) use ($filiere) {
                                $query->where('filiere_id', $filiere->id);
                            })
                            ->first();
        } catch (\Exception $e) {
            // Si la requête échoue (probablement à cause de filiere_id manquant dans programmes)
            Log::error('Erreur lors de la récupération du semestre: ' . $e->getMessage());
            $semestre = null;
        }
        
        if (!$semestre) {
            // Si on ne trouve pas de semestre avec cette méthode, essayons une approche plus simple
            $semestre = Semestre::where('annee_academique_id', $anneeAcademique->id)
                              ->first();
            
            Log::info('Semestre alternatif trouvé:', [
                'semestre' => $semestre ? $semestre->toArray() : 'non trouvé'
            ]);
        }
        
        if (!$semestre) {
            return View::make('etudiant.emploi_du_temps', [
                'emploiDuTemps' => [],
                'dateDebut' => $dateDebut,
                'dateFin' => $dateFin,
                'filiere' => $filiere,
                'niveau' => $niveau,
                'semestre' => null,
                'error' => 'Informations incomplètes : aucun semestre trouvé pour votre filière'
            ]);
        }
        
        // Récupérer l'emploi du temps
        $emploiDuTemps = EmploiDuTemps::with(['cours', 'enseignant', 'salle'])
                                     ->where('filiere_id', $filiere->id)
                                     ->where('niveau_id', $niveau->id)
                                     ->where('semestre_id', $semestre->id)
                                     ->get();
        
        Log::info('Emploi du temps récupéré:', [
            'count' => $emploiDuTemps->count(),
            'critères' => [
                'filiere_id' => $filiere->id,
                'niveau_id' => $niveau->id,
                'semestre_id' => $semestre->id
            ]
        ]);
        
        // Organiser l'emploi du temps par jour et par heure
        $emploiParJour = [
            'Lundi' => [],
            'Mardi' => [],
            'Mercredi' => [],
            'Jeudi' => [],
            'Vendredi' => [],
            'Samedi' => [],
            'Dimanche' => []
        ];
        
        // Créneaux horaires standards
        $creneaux = [
            '07:30-12:30' => [],
            '14:00-18:00' => []
        ];
        
        // Initialiser l'emploi du temps
        foreach ($emploiParJour as $jour => &$heures) {
            $heures = $creneaux;
        }
        
        // Remplir l'emploi du temps
        foreach ($emploiDuTemps as $cours) {
            $heureDebut = Carbon::parse($cours->heure_debut)->format('H:i');
            $heureFin = Carbon::parse($cours->heure_fin)->format('H:i');
            $creneau = "$heureDebut-$heureFin";
            
            // Trouver le créneau approprié
            if (Carbon::parse($cours->heure_debut)->hour < 13) {
                $creneauKey = '07:30-12:30';
            } else {
                $creneauKey = '14:00-18:00';
            }
            
            $emploiParJour[$cours->jour][$creneauKey][] = $cours;
        }
        
        return View::make('etudiant.emploi_du_temps', [
            'emploiDuTemps' => $emploiParJour,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
            'filiere' => $filiere,
            'niveau' => $niveau,
            'semestre' => $semestre,
            'error' => null
        ]);
    }
}
