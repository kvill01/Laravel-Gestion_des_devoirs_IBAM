<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Enseignant;
use App\Models\Filiere;
use App\Models\Niveau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CoursController extends Controller
{
    /**
     * Affiche la liste des cours pour l'administration
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Optimisation avec eager loading pour éviter le problème N+1
            $cours = Cours::with(['enseignant', 'filieres', 'niveaux'])->latest()->get();
            
            return view('admin.cours.index', compact('cours'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des cours: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors du chargement des cours.');
        }
    }

    /**
     * Affiche le formulaire de création d'un cours
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            $enseignants = Enseignant::orderBy('nom')->get();
            $filieres = Filiere::orderBy('code')->get();
            $niveaux = Niveau::orderBy('code')->get();
            
            return view('admin.cours.create', compact('enseignants', 'filieres', 'niveaux'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement du formulaire de création: ' . $e->getMessage());
            return redirect()->route('admin.cours.index')->with('error', 'Une erreur est survenue lors du chargement du formulaire.');
        }
    }

    /**
     * Enregistre un nouveau cours
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'intitule' => 'required|string|max:255',
                'description' => 'required|string',
                'enseignants_id' => 'nullable|exists:enseignants,id',
                'filieres' => 'required|array',
                'filieres.*' => 'exists:filieres,id',
                'niveaux' => 'required|array',
                'niveaux.*' => 'exists:niveaux,id',
            ]);

            DB::beginTransaction();
            
            // Créer le cours
            $cours = Cours::create([
                'intitule' => $validated['intitule'],
                'description' => $validated['description'],
                'enseignants_id' => $validated['enseignants_id'] ?? null,
            ]);

            // Associer les filières et niveaux
            $this->syncFilieresEtNiveaux($cours, $validated['filieres'], $validated['niveaux']);
            
            DB::commit();

            return redirect()->route('admin.cours.index')
                ->with('success', 'Le cours "' . $cours->intitule . '" a été créé avec succès.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création du cours: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la création du cours.')->withInput();
        }
    }

    /**
     * Affiche les détails d'un cours
     * 
     * @param  \App\Models\Cours  $cours
     * @return \Illuminate\View\View
     */
    public function show(Cours $cours)
    {
        try {
            $cours->load(['enseignant', 'filieres', 'niveaux']);
            return view('admin.cours.show', compact('cours'));
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affichage du cours: ' . $e->getMessage());
            return redirect()->route('admin.cours.index')->with('error', 'Une erreur est survenue lors de l\'affichage du cours.');
        }
    }

    /**
     * Affiche le formulaire de modification d'un cours
     * 
     * @param  \App\Models\Cours  $cours
     * @return \Illuminate\View\View
     */
    public function edit(Cours $cours)
    {
        try {
            $enseignants = Enseignant::orderBy('nom')->get();
            $filieres = Filiere::orderBy('code')->get();
            $niveaux = Niveau::orderBy('code')->get();
            
            // Récupérer les filières et niveaux associés à ce cours
            $cours->load(['filieres', 'niveaux']);
            $filieresIds = $cours->filieres->pluck('id')->unique()->toArray();
            $niveauxIds = $cours->niveaux->pluck('id')->unique()->toArray();
            
            return view('admin.cours.edit', compact('cours', 'enseignants', 'filieres', 'niveaux', 'filieresIds', 'niveauxIds'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement du formulaire d\'édition: ' . $e->getMessage());
            return redirect()->route('admin.cours.index')->with('error', 'Une erreur est survenue lors du chargement du formulaire d\'édition.');
        }
    }

    /**
     * Met à jour un cours
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cours  $cours
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Cours $cours)
    {
        try {
            $validated = $request->validate([
                'intitule' => 'required|string|max:255',
                'description' => 'required|string',
                'enseignants_id' => 'nullable|exists:enseignants,id',
                'filieres' => 'required|array',
                'filieres.*' => 'exists:filieres,id',
                'niveaux' => 'required|array',
                'niveaux.*' => 'exists:niveaux,id',
            ]);

            DB::beginTransaction();
            
            // Mettre à jour le cours
            $cours->update([
                'intitule' => $validated['intitule'],
                'description' => $validated['description'],
                'enseignants_id' => $validated['enseignants_id'] ?? null,
            ]);

            // Mettre à jour les associations
            $this->syncFilieresEtNiveaux($cours, $validated['filieres'], $validated['niveaux'], true);
            
            DB::commit();

            return redirect()->route('admin.cours.index')
                ->with('success', 'Le cours "' . $cours->intitule . '" a été modifié avec succès.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la mise à jour du cours: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du cours.')->withInput();
        }
    }

    /**
     * Supprime un cours
     * 
     * @param  \App\Models\Cours  $cours
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Cours $cours)
    {
        try {
            $nomCours = $cours->intitule;
            
            DB::beginTransaction();
            
            // Supprimer d'abord les associations
            DB::table('cours_filiere_niveau')->where('cours_id', $cours->id)->delete();
            
            // Puis supprimer le cours
            $cours->delete();
            
            DB::commit();

            return redirect()->route('admin.cours.index')
                ->with('success', 'Le cours "' . $nomCours . '" a été supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression du cours: ' . $e->getMessage());
            return redirect()->route('admin.cours.index')->with('error', 'Une erreur est survenue lors de la suppression du cours.');
        }
    }
    
    /**
     * Synchronise les filières et niveaux pour un cours
     * 
     * @param  \App\Models\Cours  $cours
     * @param  array  $filieres
     * @param  array  $niveaux
     * @param  bool  $resetExisting
     * @return void
     */
    private function syncFilieresEtNiveaux(Cours $cours, array $filieres, array $niveaux, bool $resetExisting = false)
    {
        // Supprimer les anciennes associations si nécessaire
        if ($resetExisting) {
            DB::table('cours_filiere_niveau')->where('cours_id', $cours->id)->delete();
        }
        
        // Créer les nouvelles associations
        $insertData = [];
        foreach ($filieres as $filiere_id) {
            foreach ($niveaux as $niveau_id) {
                $insertData[] = [
                    'cours_id' => $cours->id,
                    'filiere_id' => $filiere_id,
                    'niveau_id' => $niveau_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        // Insertion en masse pour de meilleures performances
        if (!empty($insertData)) {
            DB::table('cours_filiere_niveau')->insert($insertData);
        }
    }
}
