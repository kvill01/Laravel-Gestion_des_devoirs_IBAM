<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Devoirs;
use App\Models\Cours;
use App\Models\Enseignant;
use App\Models\Salle;
use App\Models\Semestre;
use App\Models\AnneeAcademique;
use App\Models\Surveillant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\Filiere;
use App\Models\Niveau;

class EnseignantController extends Controller
{
    public function index()
    {
        // Vérification manuelle de l'authentification
        if (!Auth::check() || Auth::user()->role !== 'enseignant') {
            return redirect()->route('login');
        }
        
        return redirect()->route('enseignant.dashboard');
    }

    public function dashboard()
    {
        // Vérification manuelle de l'authentification
        if (!Auth::check() || Auth::user()->role !== 'enseignant') {
            return redirect()->route('login');
        }
        
        $enseignant = Auth::user()->enseignant;
        
        // Récupérer tous les devoirs de l'enseignant
        $devoirs = Devoirs::where('enseignants_id', $enseignant->id)->get();
        
        // Récupérer les cours dont l'enseignant est responsable
        $coursResponsable = Cours::where('enseignants_id', $enseignant->id)->get();
        
        // Récupérer les cours associés à l'enseignant via la relation many-to-many
        $coursAssocies = $enseignant->cours;
        
        // Fusionner les deux collections de cours et éliminer les doublons
        $allCours = $coursResponsable->merge($coursAssocies)->unique('id');
        
        // Récupérer les filières et niveaux associés aux cours de l'enseignant
        $filieres = collect();
        $niveaux = collect();
        
        foreach ($allCours as $cours) {
            $filieres = $filieres->merge($cours->filieres);
            $niveaux = $niveaux->merge($cours->niveaux);
        }
        
        // Éliminer les doublons
        $filieres = $filieres->unique('id');
        $niveaux = $niveaux->unique('id');
        
        return view('enseignant.dashboard', compact('devoirs', 'allCours', 'filieres', 'niveaux'));
    }

    public function coursIndex()
    {
        // Vérification manuelle de l'authentification
        if (!Auth::check() || Auth::user()->role !== 'enseignant') {
            return redirect()->route('login');
        }

        $enseignant = Auth::user()->enseignant;
        
        // Récupérer tous les cours associés à l'enseignant
        // 1. Cours où l'enseignant est le responsable principal
        $coursResponsable = Cours::where('enseignants_id', $enseignant->id)->get();
        
        // 2. Cours associés via la relation many-to-many
        $coursAssocies = $enseignant->cours;
        
        // Fusionner les deux collections et éliminer les doublons
        $cours = $coursResponsable->merge($coursAssocies)->unique('id');
        
        return view('enseignant.cours.index', compact('cours'));
    }

    public function devoirsIndex()
    {
        // Vérification manuelle de l'authentification
        if (!Auth::check() || Auth::user()->role !== 'enseignant') {
            return redirect()->route('login');
        }

        $enseignant = Auth::user()->enseignant;
        $devoirs = Devoirs::where('enseignants_id', $enseignant->id)
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('enseignant.devoirs.index', compact('devoirs'));
    }

    public function devoirsCreate()
    {
        // Vérification manuelle de l'authentification
        if (!Auth::check() || Auth::user()->role !== 'enseignant') {
            return redirect()->route('login');
        }

        $enseignant = Auth::user()->enseignant;
        
        // Récupérer tous les cours associés à l'enseignant
        // 1. Cours où l'enseignant est le responsable principal
        $coursResponsable = Cours::where('enseignants_id', $enseignant->id)->get();
        
        // 2. Cours associés via la relation many-to-many
        $coursAssocies = $enseignant->cours;
        
        // Fusionner les deux collections et éliminer les doublons
        $cours = $coursResponsable->merge($coursAssocies)->unique('id');
        
        $annees = AnneeAcademique::orderBy('annee_debut', 'desc')->get();
        
        // Récupérer les filières et niveaux pour le formulaire
        $filieres = Filiere::all();
        $niveaux = Niveau::all();
        
        // Récupérer les semestres pour le formulaire
        $semestres = Semestre::all();

        return view('enseignant.devoirs.create', [
            'cours' => $cours,
            'annees' => $annees,
            'filieres' => $filieres,
            'niveaux' => $niveaux,
            'semestres' => $semestres,
        ]);
    }

    public function devoirsStore(Request $request)
    {
        // Vérification manuelle de l'authentification
        if (!Auth::check() || Auth::user()->role !== 'enseignant') {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'nom_devoir' => 'required|string|max:255',
            'cours_id' => 'required|exists:cours,id',
            'fichier_sujet' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'duree_minutes' => 'required|integer|min:15|max:240',
            'filiere_id' => 'required|exists:filieres,id',
            'niveau_id' => 'required|exists:niveaux,id',
            'date_proposee' => 'nullable|date|after_or_equal:today',
            'heure_proposee' => 'nullable|date_format:H:i',
            'commentaire' => 'nullable|string|max:1000',
            'annee_academique_id' => 'nullable|exists:annees_academiques,id',
        ]);

        $enseignant = Auth::user()->enseignant;
        $cours = Cours::findOrFail($validated['cours_id']);
        $filiere = Filiere::findOrFail($validated['filiere_id']);
        $niveau = Niveau::findOrFail($validated['niveau_id']);

        // Gestion du fichier
        $fileName = null;
        if ($request->hasFile('fichier_sujet')) {
            $file = $request->file('fichier_sujet');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('devoirs', $fileName, 'public');
        }

        // Formatage des données de date/heure proposées
        $dateHeureProposee = null;
        if ($request->filled('date_proposee') && $request->filled('heure_proposee')) {
            $dateHeureProposee = $request->date_proposee . ' ' . $request->heure_proposee;
        }

        try {
            // Récupérer l'année académique actuelle
            $anneeAcademique = null;
            if ($request->filled('annee_academique_id')) {
                $anneeAcademique = $request->annee_academique_id;
            } else {
                // Tentative de récupérer l'année académique la plus récente
                $anneeAcademique = AnneeAcademique::where('annee_debut', '<=', now()->year)
                    ->where('annee_fin', '>=', now()->year)
                    ->first();
                
                if ($anneeAcademique) {
                    $anneeAcademique = $anneeAcademique->id;
                }
            }

            // Création du devoir
            $devoir = new Devoirs();
            $devoir->nom_devoir = $validated['nom_devoir'];
            $devoir->cours_id = $validated['cours_id'];
            $devoir->nom_cours = $cours->intitule; // Ajouter le nom du cours à partir de l'objet cours
            $devoir->enseignants_id = $enseignant->id;
            $devoir->fichier_sujet = $fileName;
            $devoir->duree_minutes = $validated['duree_minutes'];
            
            // Utiliser les codes des filières et niveaux pour la compatibilité avec l'ancien système
            $devoir->type = $filiere->code;
            $devoir->niveau = $niveau->code;
            
            // Stocker également les IDs des filières et niveaux pour la nouvelle architecture
            $devoir->filiere_id = $validated['filiere_id'];
            $devoir->niveau_id = $validated['niveau_id'];
            
            // Ces champs ont maintenant des valeurs par défaut ou sont nullables
            $devoir->semestre_id = $request->semestre_id;
            $devoir->annee_academique_id = $anneeAcademique;
            $devoir->statut = 'en_attente';
            $devoir->date_heure_proposee = $dateHeureProposee;
            $devoir->commentaire_enseignant = $request->commentaire;
            
            $devoir->save();

            return redirect()->route('enseignant.devoirs.index')
                ->with('success', 'Devoir créé avec succès et en attente de validation par l\'administration');
        } catch (\Exception $e) {
            // En cas d'erreur, on supprime le fichier si il a été uploadé
            if ($fileName && Storage::disk('public')->exists('devoirs/' . $fileName)) {
                Storage::disk('public')->delete('devoirs/' . $fileName);
            }
            
            return back()->withInput()->withErrors(['error' => 'Une erreur est survenue lors de la création du devoir: ' . $e->getMessage()]);
        }
    }

    public function devoirsEdit(Devoirs $devoir)
    {
        // Vérification manuelle de l'authentification
        if (!Auth::check() || Auth::user()->role !== 'enseignant') {
            return redirect()->route('login');
        }

        // Vérifier que le devoir appartient à l'enseignant connecté
        $enseignant = Auth::user()->enseignant;
        if ($devoir->enseignants_id !== $enseignant->id) {
            return redirect()->route('enseignant.dashboard')->with('error', 'Vous n\'êtes pas autorisé à modifier ce devoir');
        }

        // Vérifier si le devoir est déjà confirmé
        if ($devoir->statut === 'confirmé' || $devoir->statut === 'terminé') {
            return redirect()->route('enseignant.devoirs.index')
                ->with('error', 'Ce devoir a déjà été confirmé par l\'administration et ne peut plus être modifié.');
        }

        $cours = $enseignant->cours;
        $semestres = Semestre::all();
        $annees = AnneeAcademique::orderBy('annee_debut', 'desc')->get();
        
        // Récupérer les filières et niveaux pour le formulaire
        $filieres = Filiere::all();
        $niveaux = Niveau::all();

        return view('enseignant.devoirs.edit', [
            'devoir' => $devoir,
            'cours' => $cours,
            'semestres' => $semestres,
            'annees' => $annees,
            'filieres' => $filieres,
            'niveaux' => $niveaux,
        ]);
    }

    public function devoirsUpdate(Request $request, Devoirs $devoir)
    {
        // Vérification manuelle de l'authentification
        if (!Auth::check() || Auth::user()->role !== 'enseignant') {
            return redirect()->route('login');
        }
        
        // Vérifier que le devoir appartient à l'enseignant connecté
        $enseignant = Auth::user()->enseignant;
        if ($devoir->enseignants_id !== $enseignant->id) {
            return redirect()->route('enseignant.dashboard')->with('error', 'Vous n\'êtes pas autorisé à modifier ce devoir');
        }
        
        // Vérifier si le devoir est déjà confirmé
        if ($devoir->statut === 'confirmé' || $devoir->statut === 'terminé') {
            return redirect()->route('enseignant.devoirs.index')
                ->with('error', 'Ce devoir a déjà été confirmé par l\'administration et ne peut plus être modifié.');
        }
        
        $validated = $request->validate([
            'nom_devoir' => 'required|string|max:255',
            'cours_id' => 'required|exists:cours,id',
            'fichier_sujet' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'duree_minutes' => 'required|integer|min:15|max:240',
            'filiere_id' => 'required|exists:filieres,id',
            'niveau_id' => 'required|exists:niveaux,id',
            'date_proposee' => 'nullable|date|after_or_equal:today',
            'heure_proposee' => 'nullable|date_format:H:i',
            'commentaire' => 'nullable|string|max:1000',
            'annee_academique_id' => 'nullable|exists:annees_academiques,id',
        ]);
        
        // Gestion du fichier
        $fileName = $devoir->fichier_sujet;
        if ($request->hasFile('fichier_sujet')) {
            // Supprimer l'ancien fichier s'il existe
            if ($fileName && Storage::disk('public')->exists('devoirs/' . $fileName)) {
                Storage::disk('public')->delete('devoirs/' . $fileName);
            }
            
            $file = $request->file('fichier_sujet');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('devoirs', $fileName, 'public');
        }
        
        // Formatage des données de date/heure proposées
        $dateHeureProposee = null;
        if ($request->filled('date_proposee') && $request->filled('heure_proposee')) {
            $dateHeureProposee = $request->date_proposee . ' ' . $request->heure_proposee;
        }
        
        // Récupérer le cours pour avoir son intitulé
        $cours = Cours::findOrFail($validated['cours_id']);
        $filiere = Filiere::findOrFail($validated['filiere_id']);
        $niveau = Niveau::findOrFail($validated['niveau_id']);
        
        try {
            // Récupérer l'année académique
            $anneeAcademique = null;
            if ($request->filled('annee_academique_id')) {
                $anneeAcademique = $request->annee_academique_id;
            } else {
                // Tentative de récupérer l'année académique actuelle
                $anneeAcademique = AnneeAcademique::where('annee_debut', '<=', now()->year)
                    ->where('annee_fin', '>=', now()->year)
                    ->first();
                
                if ($anneeAcademique) {
                    $anneeAcademique = $anneeAcademique->id;
                }
            }
            
            // Mise à jour du devoir
            $devoir->nom_devoir = $validated['nom_devoir'];
            $devoir->cours_id = $validated['cours_id'];
            $devoir->nom_cours = $cours->intitule;
            $devoir->fichier_sujet = $fileName;
            $devoir->duree_minutes = $validated['duree_minutes'];
            
            // Utiliser les codes des filières et niveaux pour la compatibilité avec l'ancien système
            $devoir->type = $filiere->code;
            $devoir->niveau = $niveau->code;
            
            // Stocker également les IDs des filières et niveaux pour la nouvelle architecture
            $devoir->filiere_id = $validated['filiere_id'];
            $devoir->niveau_id = $validated['niveau_id'];
            
            $devoir->semestre_id = $request->semestre_id;
            $devoir->date_heure_proposee = $dateHeureProposee;
            $devoir->commentaire_enseignant = $request->commentaire;
            $devoir->annee_academique_id = $anneeAcademique;
            
            $devoir->save();
            
            return redirect()->route('enseignant.devoirs.index')
                ->with('success', 'Devoir mis à jour avec succès');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour du devoir: ' . $e->getMessage()]);
        }
    }

    public function devoirsDestroy(Devoirs $devoir)
    {
        // Vérification manuelle de l'authentification
        if (!Auth::check() || Auth::user()->role !== 'enseignant') {
            return redirect()->route('login');
        }
        
        // Vérifier que le devoir appartient à l'enseignant connecté
        $enseignant = Auth::user()->enseignant;
        if ($devoir->enseignants_id !== $enseignant->id) {
            return redirect()->route('enseignant.dashboard')->with('error', 'Vous n\'êtes pas autorisé à supprimer ce devoir');
        }
        
        // Vérifier si le devoir peut être supprimé (uniquement les devoirs en attente)
        if ($devoir->statut !== 'en_attente') {
            return redirect()->route('enseignant.devoirs.index')
                ->with('error', 'Seuls les devoirs en attente peuvent être supprimés.');
        }
        
        // Supprimer le fichier associé
        $fileName = $devoir->fichier_sujet;
        if ($fileName && Storage::disk('public')->exists('devoirs/' . $fileName)) {
            Storage::disk('public')->delete('devoirs/' . $fileName);
        }
        
        // Supprimer le devoir
        $devoir->delete();
        
        return redirect()->route('enseignant.devoirs.index')->with('success', 'Devoir supprimé avec succès');
    }

    public function viewDevoir($id)
    {
        // Vérification manuelle de l'authentification
        if (!Auth::check() || Auth::user()->role !== 'enseignant') {
            return redirect()->route('login');
        }

        $devoir = Devoirs::with(['cours', 'enseignant.user', 'anneeAcademique', 'surveillants', 'salles'])
            ->findOrFail($id);

        // Vérifier que le devoir appartient à l'enseignant connecté
        $enseignant = Auth::user()->enseignant;
        if ($devoir->enseignants_id !== $enseignant->id) {
            return redirect()->route('enseignant.dashboard')
                ->with('error', 'Vous n\'êtes pas autorisé à voir ce devoir');
        }

        return view('enseignant.devoirs.show', [
            'devoir' => $devoir
        ]);
    }
}