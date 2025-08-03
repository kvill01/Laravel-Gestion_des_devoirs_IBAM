<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cours;
use App\Models\Filiere;
use App\Models\Niveau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoursAssociationController extends Controller
{
    /**
     * Afficher la liste des cours avec leurs associations
     */
    public function index()
    {
        $cours = Cours::with(['filieres', 'niveaux'])->get();
        return view('admin.cours_associations.index', compact('cours'));
    }

    /**
     * Afficher le formulaire d'édition des associations d'un cours
     */
    public function edit(Cours $cours)
    {
        $filieres = Filiere::orderBy('code')->get();
        $niveaux = Niveau::orderBy('code')->get();
        
        // Récupérer les associations existantes
        $associations = DB::table('cours_filiere_niveau')
            ->where('cours_id', $cours->id)
            ->get();
            
        $coursAssociations = [];
        
        foreach ($associations as $assoc) {
            $coursAssociations[] = [
                'filiere_id' => $assoc->filiere_id,
                'niveau_id' => $assoc->niveau_id
            ];
        }
        
        return view('admin.cours_associations.edit', compact('cours', 'filieres', 'niveaux', 'coursAssociations'));
    }

    /**
     * Mettre à jour les associations d'un cours
     */
    public function update(Request $request, Cours $cours)
    {
        // Valider les données
        $request->validate([
            'associations' => 'required|array',
            'associations.*.filiere_id' => 'required|exists:filieres,id',
            'associations.*.niveau_id' => 'required|exists:niveaux,id',
        ]);

        // Supprimer toutes les associations existantes
        DB::table('cours_filiere_niveau')
            ->where('cours_id', $cours->id)
            ->delete();
            
        // Ajouter les nouvelles associations
        foreach ($request->associations as $association) {
            DB::table('cours_filiere_niveau')->insert([
                'cours_id' => $cours->id,
                'filiere_id' => $association['filiere_id'],
                'niveau_id' => $association['niveau_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('admin.cours_associations.index')
            ->with('success', 'Associations du cours mises à jour avec succès');
    }
    
    /**
     * Afficher la page de gestion des associations par lot
     */
    public function batch()
    {
        $cours = Cours::orderBy('intitule')->get();
        $filieres = Filiere::orderBy('code')->get();
        $niveaux = Niveau::orderBy('code')->get();
        
        return view('admin.cours_associations.batch', compact('cours', 'filieres', 'niveaux'));
    }
    
    /**
     * Traiter l'association par lot
     */
    public function batchStore(Request $request)
    {
        // Valider les données
        $request->validate([
            'cours_ids' => 'required|array',
            'cours_ids.*' => 'exists:cours,id',
            'filiere_ids' => 'required|array',
            'filiere_ids.*' => 'exists:filieres,id',
            'niveau_ids' => 'required|array',
            'niveau_ids.*' => 'exists:niveaux,id',
        ]);
        
        // Créer toutes les combinaisons possibles
        foreach ($request->cours_ids as $coursId) {
            foreach ($request->filiere_ids as $filiereId) {
                foreach ($request->niveau_ids as $niveauId) {
                    // Vérifier si l'association existe déjà
                    $exists = DB::table('cours_filiere_niveau')
                        ->where('cours_id', $coursId)
                        ->where('filiere_id', $filiereId)
                        ->where('niveau_id', $niveauId)
                        ->exists();
                        
                    // Si elle n'existe pas, la créer
                    if (!$exists) {
                        DB::table('cours_filiere_niveau')->insert([
                            'cours_id' => $coursId,
                            'filiere_id' => $filiereId,
                            'niveau_id' => $niveauId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
        
        return redirect()->route('admin.cours_associations.index')
            ->with('success', 'Associations par lot créées avec succès');
    }
}
