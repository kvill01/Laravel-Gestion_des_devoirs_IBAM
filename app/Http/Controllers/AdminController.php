<?php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use App\Models\Surveillant;
use App\Models\User;
use App\Models\Devoirs;
use App\Models\Etudiant;
use App\Models\Domaine;
use App\Models\Mention;
use App\Models\Cours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AdminController extends Controller
{
    public function dashboard()
    {
        $countEtudiants = Etudiant::count();
        $countEnseignants = Enseignant::count();
        $countSurveillants = Surveillant::count();
        $countDevoirs = Devoirs::count();
        
        // Statistiques détaillées des devoirs par statut
        $countDevoirsAttente = Devoirs::where('statut', 'en_attente')->count();
        $countDevoirsConfirmes = Devoirs::where('statut', 'confirmé')->count();
        $countDevoirsTermines = Devoirs::where('statut', 'terminé')->count();
        
        // Récupération des devoirs récents avec plus d'informations
        $recentDevoirs = Devoirs::with(['enseignant', 'cours'])
            ->latest()
            ->take(5)
            ->get();
            
        // Statistiques des cours pour le tableau de bord
        $countCours = Cours::count();

        return view('admin.dashboard', compact(
            'countEtudiants',
            'countEnseignants',
            'countSurveillants',
            'countDevoirs',
            'countDevoirsAttente',
            'countDevoirsConfirmes',
            'countDevoirsTermines',
            'recentDevoirs',
            'countCours'
        ));
    }

    /**
     * Afficher la liste des utilisateurs
     */
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Afficher la liste des étudiants
     */
    public function etudiantsIndex()
    {
        $etudiants = Etudiant::with(['user', 'filiere', 'niveau'])->get();
        return view('admin.etudiants.index', compact('etudiants'));
    }

    /**
     * Afficher le formulaire de création d'un étudiant
     */
    public function createEtudiant()
    {
        $filieres = \App\Models\Filiere::all();
        $niveaux = \App\Models\Niveau::all();
        $anneeAcademiques = \App\Models\AnneeAcademique::all();
        
        return view('admin.etudiants.create', compact('filieres', 'niveaux', 'anneeAcademiques'));
    }

    /**
     * Enregistrer un nouvel étudiant
     */
    public function storeEtudiant(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'date_naissance' => 'required|date',
            'email' => 'required|email|unique:users,email',
            'filiere_id' => 'required|exists:filieres,id',
            'niveau_id' => 'required|exists:niveaux,id',
            'annee_academique_id' => 'required|exists:annees_academiques,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Créer l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'etudiant',
        ]);

        // Créer l'étudiant avec les nouvelles relations
        Etudiant::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'prenom' => $request->prenom,
            'date_naissance' => $request->date_naissance,
            'filiere_id' => $request->filiere_id,
            'niveau_id' => $request->niveau_id,
            'annee_academique_id' => $request->annee_academique_id,
            'email' => $request->email,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.etudiants.index')
            ->with('success', 'Étudiant créé avec succès.');
    }

    /**
     * Afficher le formulaire de modification d'un étudiant
     */
    public function editEtudiant(Etudiant $etudiant)
    {
        $filieres = \App\Models\Filiere::all();
        $niveaux = \App\Models\Niveau::all();
        $anneeAcademiques = \App\Models\AnneeAcademique::all();
        
        return view('admin.etudiants.edit', compact('etudiant', 'filieres', 'niveaux', 'anneeAcademiques'));
    }

    /**
     * Mettre à jour un étudiant
     */
    public function updateEtudiant(Request $request, Etudiant $etudiant)
    {
        // Log pour déboguer
        Log::info('Données reçues:', $request->all());
        Log::info('Étudiant avant mise à jour:', ['id' => $etudiant->id, 'user_id' => $etudiant->user_id, 'filiere_id' => $etudiant->filiere_id, 'niveau_id' => $etudiant->niveau_id]);
        
        $request->validate([
            'name' => 'nullable|string|max:100',
            'prenom' => 'nullable|string|max:100',
            'email' => 'nullable|email|unique:users,email,' . $etudiant->user_id,
            'filiere_id' => 'nullable|exists:filieres,id',
            'niveau_id' => 'nullable|exists:niveaux,id',
            'annee_academique_id' => 'nullable|exists:annees_academiques,id',
        ]);

        try {
            DB::beginTransaction();
            
            // Préparer les données utilisateur à mettre à jour
            $userData = [];
            $etudiantData = [];
            
            // Synchroniser les données communes entre User et Etudiant
            if ($request->filled('name')) {
                $userData['name'] = $request->name;
                $etudiantData['name'] = $request->name;
            }
            
            if ($request->filled('prenom')) {
                $userData['prenom'] = $request->prenom;
                $etudiantData['prenom'] = $request->prenom;
            }
            
            if ($request->filled('email')) {
                $userData['email'] = $request->email;
                $etudiantData['email'] = $request->email;
            }
            
            // Mettre à jour l'utilisateur
            if (!empty($userData)) {
                User::where('id', $etudiant->user_id)->update($userData);
                Log::info('Utilisateur mis à jour:', $userData);
            }

            // Ajouter les champs spécifiques à l'étudiant
            if ($request->filled('filiere_id')) {
                $etudiantData['filiere_id'] = $request->filiere_id;
            }
            
            if ($request->filled('niveau_id')) {
                $etudiantData['niveau_id'] = $request->niveau_id;
            }
            
            if ($request->filled('annee_academique_id')) {
                $etudiantData['annee_academique_id'] = $request->annee_academique_id;
            }
            
            // Mettre à jour l'étudiant
            if (!empty($etudiantData)) {
                Etudiant::where('id', $etudiant->id)->update($etudiantData);
                Log::info('Étudiant mis à jour:', $etudiantData);
            }
            
            DB::commit();
            
            // Rafraîchir l'instance de l'étudiant pour les logs
            $etudiant = Etudiant::find($etudiant->id);
            
            // Log après mise à jour
            Log::info('Étudiant après mise à jour:', [
                'id' => $etudiant->id, 
                'name' => $etudiant->name, 
                'prenom' => $etudiant->prenom, 
                'email' => $etudiant->email, 
                'user_id' => $etudiant->user_id, 
                'filiere_id' => $etudiant->filiere_id, 
                'niveau_id' => $etudiant->niveau_id
            ]);

            return redirect()->route('admin.etudiants.index')
                ->with('success', 'Étudiant modifié avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la mise à jour de l\'étudiant: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la modification de l\'étudiant: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un étudiant
     */
    public function destroyEtudiant(Etudiant $etudiant)
    {
        try {
            DB::beginTransaction();
            
            // Récupérer l'ID de l'utilisateur
            $userId = $etudiant->user_id;
            
            // Désactiver temporairement les contraintes de clé étrangère
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // Supprimer l'étudiant
            $etudiant->delete();
            
            // Supprimer l'utilisateur associé
            User::destroy($userId);
            
            // Réactiver les contraintes de clé étrangère
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            DB::commit();
            
            return redirect()->route('admin.etudiants.index')
                ->with('success', 'Étudiant supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Réactiver les contraintes de clé étrangère en cas d'erreur
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            return redirect()->route('admin.etudiants.index')
                ->with('error', 'Une erreur est survenue lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Afficher la liste des enseignants
     */
    public function enseignantsIndex()
    {
        $enseignants = Enseignant::with(['user', 'domaine'])->get();
        return view('admin.enseignants.index', compact('enseignants'));
    }

    /**
     * Afficher le formulaire de création d'un enseignant
     */
    public function createEnseignant()
    {
        $domaines = Domaine::all();
        $cours = Cours::all();
        return view('admin.enseignants.create', compact('domaines', 'cours'));
    }

    /**
     * Enregistrer un nouvel enseignant
     */
    public function storeEnseignant(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'domaine_id' => 'required|exists:domaines,id',
            'grade' => 'nullable|string|max:50',
            'password' => 'required|string|min:8|confirmed',
            'cours' => 'nullable|array',
            'cours.*' => 'exists:cours,id',
        ]);

        // Créer l'utilisateur
        $user = User::create([
            'name' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'enseignant',
        ]);

        // Créer l'enseignant
        $enseignant = Enseignant::create([
            'user_id' => $user->id,
            'domaine_id' => $request->domaine_id,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'grade' => $request->grade,
        ]);

        // Associer les cours sélectionnés à l'enseignant
        if ($request->has('cours')) {
            $enseignant->cours()->attach($request->cours);
        }

        return redirect()->route('admin.enseignants.index')
            ->with('success', 'Enseignant créé avec succès.');
    }

    /**
     * Afficher le formulaire de modification d'un enseignant
     */
    public function editEnseignant(Enseignant $enseignant)
    {
        $domaines = Domaine::all();
        $cours = Cours::all();
        $coursAssignes = $enseignant->cours->pluck('id')->toArray();
        return view('admin.enseignants.edit', compact('enseignant', 'domaines', 'cours', 'coursAssignes'));
    }

    /**
     * Mettre à jour un enseignant
     */
    public function updateEnseignant(Request $request, Enseignant $enseignant)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $enseignant->user_id,
            'domaine_id' => 'required|exists:domaines,id',
            'grade' => 'nullable|string|max:50',
            'cours' => 'nullable|array',
            'cours.*' => 'exists:cours,id',
        ]);

        // Mettre à jour l'utilisateur
        $enseignant->user->update([
            'name' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
        ]);

        // Mettre à jour l'enseignant
        $enseignant->update([
            'domaine_id' => $request->domaine_id,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'grade' => $request->grade,
        ]);

        // Mettre à jour les cours associés
        $enseignant->cours()->sync($request->input('cours', []));

        return redirect()->route('admin.enseignants.index')
            ->with('success', 'Enseignant modifié avec succès.');
    }

    /**
     * Supprimer un enseignant
     */
    public function destroyEnseignant(Enseignant $enseignant)
    {
        try {
            DB::beginTransaction();
            
            // Récupérer l'ID de l'utilisateur
            $userId = $enseignant->user_id;
            
            // Désactiver temporairement les contraintes de clé étrangère
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // Supprimer l'enseignant
            $enseignant->delete();
            
            // Supprimer l'utilisateur associé
            User::destroy($userId);
            
            // Réactiver les contraintes de clé étrangère
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            DB::commit();
            
            return redirect()->route('admin.enseignants.index')
                ->with('success', 'Enseignant supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Réactiver les contraintes de clé étrangère en cas d'erreur
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            return redirect()->route('admin.enseignants.index')
                ->with('error', 'Une erreur est survenue lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Afficher la liste des surveillants
     */
    public function surveillantsIndex()
    {
        $surveillants = Surveillant::with('user')->get();
        return view('admin.surveillants.index', compact('surveillants'));
    }

    /**
     * Afficher le formulaire de création d'un surveillant
     */
    public function createSurveillant()
    {
        return view('admin.users.create_surveillant');
    }

    /**
     * Enregistrer un nouveau surveillant
     */
    public function storeSurveillant(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Créer l'utilisateur
        $user = User::create([
            'name' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'surveillant',
        ]);

        // Créer le surveillant
        $surveillant = Surveillant::create([
            'user_id' => $user->id,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.surveillants.index')
            ->with('success', 'Surveillant créé avec succès.');
    }

    /**
     * Afficher le formulaire de modification d'un surveillant
     */
    public function editSurveillant(Surveillant $surveillant)
    {
        return view('admin.surveillants.edit', compact('surveillant'));
    }

    /**
     * Mettre à jour un surveillant
     */
    public function updateSurveillant(Request $request, Surveillant $surveillant)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $surveillant->user_id,
        ]);
    
        // Mettre à jour l'utilisateur
        $surveillant->user->update([
            'name' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
        ]);
    
        // Mettre à jour le surveillant
        $surveillant->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
        ]);
    
        return redirect()->route('admin.surveillants.index')
            ->with('success', 'Surveillant modifié avec succès.');
    }

    /**
     * Supprimer un surveillant
     */
    public function destroySurveillant(Surveillant $surveillant)
    {
        try {
            DB::beginTransaction();
            
            // Récupérer l'ID de l'utilisateur
            $userId = $surveillant->user_id;
            
            // Désactiver temporairement les contraintes de clé étrangère
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // Supprimer le surveillant
            $surveillant->delete();
            
            // Supprimer l'utilisateur associé
            User::destroy($userId);
            
            // Réactiver les contraintes de clé étrangère
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            DB::commit();
            
            return redirect()->route('admin.surveillants.index')
                ->with('success', 'Surveillant supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Réactiver les contraintes de clé étrangère en cas d'erreur
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            return redirect()->route('admin.surveillants.index')
                ->with('error', 'Une erreur est survenue lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroyUser(User $user)
    {
        try {
            DB::beginTransaction();
            
            // Désactiver temporairement les contraintes de clé étrangère
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // Supprimer les entités associées en fonction du rôle
            if ($user->role === 'enseignant' && $user->enseignant) {
                $user->enseignant->delete();
            } elseif ($user->role === 'etudiant' && $user->etudiant) {
                $user->etudiant->delete();
            } elseif ($user->role === 'surveillant' && $user->surveillant) {
                $user->surveillant->delete();
            }
            
            // Supprimer l'utilisateur
            $user->delete();
            
            // Réactiver les contraintes de clé étrangère
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            DB::commit();
            
            return redirect()->route('admin.users.index')
                ->with('success', 'Utilisateur supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Réactiver les contraintes de clé étrangère en cas d'erreur
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            return redirect()->route('admin.users.index')
                ->with('error', 'Une erreur est survenue lors de la suppression: ' . $e->getMessage());
        }
    }
    
    /**
     * Afficher le formulaire pour confirmer un devoir
     */
    public function confirmerDevoirForm(Devoirs $devoir)
    {
        // Récupérer toutes les salles disponibles
        $salles = \App\Models\Salle::all();
        
        // Récupérer tous les surveillants disponibles
        $surveillants = \App\Models\Surveillant::with('user')->get();
        
        return view('admin.devoirs.confirmer', compact('devoir', 'salles', 'surveillants'));
    }

    /**
     * Confirmer un devoir et lui attribuer une date, une salle et des surveillants
     */
    public function confirmerDevoir(Request $request, Devoirs $devoir)
    {
        $validated = $request->validate([
            'date_heure' => 'required|date|after:now',
            'salles' => 'required|array',
            'salles.*' => 'exists:salles,id',
            'surveillants' => 'required|array',
            'surveillants.*' => 'exists:surveillants,id'
        ], [
            'date_heure.after' => 'La date du devoir doit être dans le futur.',
            'salles.required' => 'Vous devez sélectionner au moins une salle.',
            'surveillants.required' => 'Vous devez sélectionner au moins un surveillant.'
        ]);

        try {
            DB::beginTransaction();
            
            // Génération d'un code QR unique
            $qrCodeId = 'DEVOIR-' . $devoir->id . '-' . time();
            $qrFilename = 'devoir_' . $devoir->id . '_' . time() . '.png';
            $qrPath = public_path('qrcodes/' . $qrFilename);
            
            // Données à encoder dans le QR code (informations du devoir)
            $qrData = [
                'id' => $devoir->id,
                'nom' => $devoir->nom_devoir,
                'cours' => $devoir->cours->intitule ?? $devoir->nom_cours,
                'date' => $validated['date_heure'],
                'type' => $devoir->type,
                'salles' => $validated['salles'],
                'timestamp' => time()
            ];
            
            // Génération du QR code
            QrCode::format('png')
                ->size(300)
                ->errorCorrection('H')
                ->generate(json_encode($qrData), $qrPath);
            
            // Mise à jour des informations de base du devoir
            $devoir->update([
                'date_heure' => $validated['date_heure'],
                'statut' => 'confirmé',
                'code_QR' => $qrFilename
            ]);

            // Associer les salles au devoir
            $devoir->salles()->sync($validated['salles']);
            
            // Mettre à jour le statut de disponibilité des salles
            foreach ($validated['salles'] as $salleId) {
                $salle = \App\Models\Salle::find($salleId);
                if ($salle) {
                    $salle->update(['disponible' => false]);
                }
            }

            // Associer les surveillants au devoir
            $devoir->surveillants()->sync($validated['surveillants']);
            
            DB::commit();

            return redirect()->route('admin.devoirs.en_attente')
                ->with('success', 'Le devoir a été confirmé et planifié avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors([
                'general' => 'Une erreur est survenue lors de la confirmation du devoir: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Marquer un devoir comme terminé
     */
    public function terminerDevoir(Devoirs $devoir)
    {
        // Mettre à jour le statut du devoir
        $devoir->update(['statut' => 'terminé']);
        
        // Rendre les salles à nouveau disponibles
        $salles = $devoir->salles;
        foreach ($salles as $salle) {
            $salle->update(['disponible' => true]);
        }
        
        return redirect()->route('admin.devoirs.confirmes')
            ->with('success', 'Devoir marqué comme terminé avec succès et salles rendues disponibles');
    }

    /**
     * Afficher la liste des devoirs en attente
     */
    public function devoirsEnAttente()
    {
        $devoirs = Devoirs::where('statut', 'en_attente')
            ->with(['enseignant', 'cours', 'semestre', 'anneeAcademique'])
            ->latest()
            ->get();

        return view('admin.devoirs.en_attente', compact('devoirs'));
    }

    /**
     * Afficher le formulaire d'édition d'un devoir
     */
    public function devoirsEdit(Devoirs $devoir)
    {
        return view('admin.devoirs.edit', compact('devoir'));
    }

    /**
     * Mettre à jour un devoir
     */
    public function devoirsUpdate(Request $request, Devoirs $devoir)
    {
        $validated = $request->validate([
            'date_heure' => 'required|date',
            'salles' => 'required|array',
            'salles.*' => 'exists:salles,id',
            'surveillants' => 'required|array',
            'surveillants.*' => 'exists:surveillants,id'
        ], [
            'date_heure.required' => 'La date et l\'heure sont obligatoires.',
            'salles.required' => 'Vous devez sélectionner au moins une salle.',
            'surveillants.required' => 'Vous devez sélectionner au moins un surveillant.'
        ]);

        try {
            DB::beginTransaction();
            
            // Mise à jour du devoir
            $devoir->update([
                'date_heure' => $validated['date_heure'],
                'statut' => 'confirmé'
            ]);

            // Synchroniser les salles
            $devoir->salles()->sync($validated['salles']);
            
            // Mettre à jour le statut de disponibilité des salles
            foreach ($validated['salles'] as $salleId) {
                $salle = \App\Models\Salle::find($salleId);
                if ($salle) {
                    $salle->update(['disponible' => false]);
                }
            }

            // Synchroniser les surveillants
            $devoir->surveillants()->sync($validated['surveillants']);
            
            DB::commit();

            return redirect()->route('admin.devoirs.en_attente')
                ->with('success', 'Le devoir a été mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors([
                'general' => 'Une erreur est survenue lors de la mise à jour du devoir: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Afficher la liste des devoirs confirmés
     */
    public function devoirsConfirmes()
    {
        $devoirs = Devoirs::whereIn('statut', ['confirmé', 'terminé'])
            ->with(['enseignant', 'cours', 'salles', 'surveillants.user'])
            ->latest('date_heure')
            ->get();

        return view('admin.devoirs.confirmes', compact('devoirs'));
    }

    /**
     * Afficher les détails d'un devoir
     */
    public function showDevoir(Devoirs $devoir)
    {
        return view('admin.devoirs.show', compact('devoir'));
    }

    /**
     * Télécharger le fichier d'un devoir
     */
    public function downloadDevoir(Devoirs $devoir)
    {
        // Vérifier si le fichier existe
        if (!$devoir->fichier_sujet || !Storage::disk('public')->exists('devoirs/' . $devoir->fichier_sujet)) {
            return back()->with('error', 'Le fichier n\'existe pas.');
        }

        // Retourner le fichier pour téléchargement
        return response()->download(storage_path('app/public/devoirs/' . $devoir->fichier_sujet));
    }
}
