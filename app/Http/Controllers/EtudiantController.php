<?php

namespace App\Http\Controllers;

use App\Models\Devoirs;
use App\Models\Cours;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\AnneeAcademique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EtudiantController extends Controller
{
    public function index()
    {
        $etudiant = Auth::user()->etudiant;
        
        // Récupérer les devoirs correspondant à la filière et au niveau de l'étudiant
        // en utilisant la nouvelle structure
        $devoirs = Devoirs::whereHas('cours', function($query) use ($etudiant) {
                $query->whereHas('filieres', function($subQuery) use ($etudiant) {
                    $subQuery->where('filieres.id', $etudiant->filiere_id)
                             ->whereHas('niveaux', function($niveauQuery) use ($etudiant) {
                                 $niveauQuery->where('niveaux.id', $etudiant->niveau_id);
                             });
                });
            })
            ->where('statut', 'confirmé')
            ->where(function($query) use ($etudiant) {
                $query->where('annee_academique_id', $etudiant->annee_academique_id)
                      ->orWhereNull('annee_academique_id');
            })
            ->with(['cours', 'enseignant', 'salles', 'surveillants.user', 'anneeAcademique'])
            ->orderBy('date_heure', 'asc')
            ->take(5)
            ->get();

        // Pour le débogage, afficher tous les devoirs disponibles
        $all_devoirs = Devoirs::with(['cours.filieres', 'cours.niveaux', 'anneeAcademique'])->get();
        $all_devoirs_count = $all_devoirs->count();
        $confirmed_devoirs_count = Devoirs::where('statut', 'confirmé')->count();
        
        // Compter les devoirs correspondant à la filière/niveau de l'étudiant
        $matching_filiere_niveau = Devoirs::whereHas('cours', function($query) use ($etudiant) {
                $query->whereHas('filieres', function($subQuery) use ($etudiant) {
                    $subQuery->where('filieres.id', $etudiant->filiere_id)
                             ->whereHas('niveaux', function($niveauQuery) use ($etudiant) {
                                 $niveauQuery->where('niveaux.id', $etudiant->niveau_id);
                             });
                });
            })->count();

        // Informations détaillées sur tous les devoirs pour le débogage
        $devoirs_details = [];
        foreach ($all_devoirs as $d) {
            $filieres = [];
            $niveaux = [];
            
            foreach ($d->cours->filieres as $filiere) {
                $filieres[] = $filiere->code . ' (' . $filiere->nom . ')';
            }
            
            foreach ($d->cours->niveaux as $niveau) {
                $niveaux[] = $niveau->code;
            }
            
            $devoirs_details[] = [
                'id' => $d->id,
                'nom' => $d->nom_devoir,
                'statut' => $d->statut,
                'cours_id' => $d->cours_id,
                'cours_nom' => $d->cours->intitule ?? 'N/A',
                'filieres' => implode(', ', $filieres),
                'niveaux' => implode(', ', $niveaux),
                'annee_academique' => $d->anneeAcademique->annee ?? 'N/A',
            ];
        }

        // Statistiques simplifiées
        $statistiques = [
            'devoirs_a_venir' => Devoirs::whereHas('cours', function($query) use ($etudiant) {
                $query->whereHas('filieres', function($subQuery) use ($etudiant) {
                    $subQuery->where('filieres.id', $etudiant->filiere_id)
                             ->whereHas('niveaux', function($niveauQuery) use ($etudiant) {
                                 $niveauQuery->where('niveaux.id', $etudiant->niveau_id);
                             });
                });
            })
            ->where('statut', 'confirmé')
            ->where('date_heure', '>', Carbon::now())
            ->where(function($query) use ($etudiant) {
                $query->where('annee_academique_id', $etudiant->annee_academique_id)
                      ->orWhereNull('annee_academique_id');
            })
            ->count(),
            
            'devoirs_passes' => Devoirs::whereHas('cours', function($query) use ($etudiant) {
                $query->whereHas('filieres', function($subQuery) use ($etudiant) {
                    $subQuery->where('filieres.id', $etudiant->filiere_id)
                             ->whereHas('niveaux', function($niveauQuery) use ($etudiant) {
                                 $niveauQuery->where('niveaux.id', $etudiant->niveau_id);
                             });
                });
            })
            ->where('statut', 'terminé')
            ->where(function($query) use ($etudiant) {
                $query->where('annee_academique_id', $etudiant->annee_academique_id)
                      ->orWhereNull('annee_academique_id');
            })
            ->count(),
            
            'total_devoirs' => $all_devoirs_count,
            
            // Informations de débogage
            'debug_info' => [
                'total_devoirs' => $all_devoirs_count,
                'devoirs_confirmes' => $confirmed_devoirs_count,
                'devoirs_matching_type_niveau' => $matching_filiere_niveau, // Renommé pour correspondre à la vue
                'devoirs_matching_filiere_niveau' => $matching_filiere_niveau, // Ajouté pour compatibilité
                'etudiant_filiere_id' => $etudiant->filiere_id,
                'etudiant_filiere' => $etudiant->filiere ? $etudiant->filiere->code : 'N/A',
                'etudiant_type' => $etudiant->filiere ? $etudiant->filiere->code : 'N/A', // Pour compatibilité
                'etudiant_niveau_id' => $etudiant->niveau_id,
                'etudiant_niveau' => $etudiant->niveau ? $etudiant->niveau->code : 'N/A',
                'etudiant_annee_academique' => $etudiant->anneeAcademique ? $etudiant->anneeAcademique->annee_debut . '-' . $etudiant->anneeAcademique->annee_fin : 'N/A',
                'etudiant_annee_id' => $etudiant->annee_academique_id,
                'devoirs_details' => $devoirs_details
            ]
        ];

        return view('etudiant.dashboard', compact('devoirs', 'statistiques'));
    }

    /**
     * Afficher la liste de tous les devoirs de l'étudiant
     */
    public function devoirsIndex()
    {
        $etudiant = Auth::user()->etudiant;
        
        $devoirs_confirmes = Devoirs::whereHas('cours', function($query) use ($etudiant) {
                $query->whereHas('filieres', function($subQuery) use ($etudiant) {
                    $subQuery->where('filieres.id', $etudiant->filiere_id)
                             ->whereHas('niveaux', function($niveauQuery) use ($etudiant) {
                                 $niveauQuery->where('niveaux.id', $etudiant->niveau_id);
                             });
                });
            })
            ->where('statut', 'confirmé')
            ->where('date_heure', '>', Carbon::now())
            ->where(function($query) use ($etudiant) {
                $query->where('annee_academique_id', $etudiant->annee_academique_id)
                      ->orWhereNull('annee_academique_id');
            })
            ->with(['cours', 'enseignant', 'salles', 'surveillants.user', 'anneeAcademique'])
            ->orderBy('date_heure', 'asc')
            ->get();
            
        $devoirs_termines = Devoirs::whereHas('cours', function($query) use ($etudiant) {
                $query->whereHas('filieres', function($subQuery) use ($etudiant) {
                    $subQuery->where('filieres.id', $etudiant->filiere_id)
                             ->whereHas('niveaux', function($niveauQuery) use ($etudiant) {
                                 $niveauQuery->where('niveaux.id', $etudiant->niveau_id);
                             });
                });
            })
            ->whereIn('statut', ['confirmé', 'terminé'])
            ->where(function($query) {
                $query->where('date_heure', '<=', Carbon::now())
                      ->orWhere('statut', 'terminé');
            })
            ->where(function($query) use ($etudiant) {
                $query->where('annee_academique_id', $etudiant->annee_academique_id)
                      ->orWhereNull('annee_academique_id');
            })
            ->with(['cours', 'enseignant', 'salles', 'surveillants.user', 'anneeAcademique'])
            ->orderBy('date_heure', 'desc')
            ->get();

        return view('etudiant.devoirs.index', compact('devoirs_confirmes', 'devoirs_termines'));
    }

    /**
     * Afficher les détails d'un devoir
     */
    public function devoirsShow(Devoirs $devoir)
    {
        $etudiant = Auth::user()->etudiant;
        
        // Vérifier que l'étudiant est autorisé à voir ce devoir
        $authorized = Devoirs::whereId($devoir->id)
            ->whereHas('cours', function($query) use ($etudiant) {
                $query->whereHas('filieres', function($subQuery) use ($etudiant) {
                    $subQuery->where('filieres.id', $etudiant->filiere_id)
                             ->whereHas('niveaux', function($niveauQuery) use ($etudiant) {
                                 $niveauQuery->where('niveaux.id', $etudiant->niveau_id);
                             });
                });
            })
            ->where(function($query) use ($etudiant) {
                $query->where('annee_academique_id', $etudiant->annee_academique_id)
                      ->orWhereNull('annee_academique_id');
            })
            ->exists();
            
        if (!$authorized) {
            return redirect()->route('etudiant.dashboard')
                ->with('error', 'Vous n\'êtes pas autorisé à voir ce devoir.');
        }
        
        $devoir->load(['cours', 'enseignant', 'salles', 'surveillants.user', 'anneeAcademique']);
        
        return view('etudiant.devoirs.show', compact('devoir'));
    }
}