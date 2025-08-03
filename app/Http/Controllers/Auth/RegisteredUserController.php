<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Etudiant;
use App\Models\Enseignant;
use App\Models\Surveillant;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\AnneeAcademique;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Récupérer les filières, niveaux et années académiques pour le formulaire
        $filieres = Filiere::orderBy('code')->get();
        $niveaux = Niveau::orderBy('code')->get();
        $annees_academiques = AnneeAcademique::orderBy('annee_debut', 'desc')->get();
        
        // Récupérer l'année académique actuelle (la plus récente)
        $annee_academique_actuelle = AnneeAcademique::orderBy('annee_debut', 'desc')->first();
        
        return view('auth.register', compact('filieres', 'niveaux', 'annees_academiques', 'annee_academique_actuelle'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'date_naissance' => ['required', 'date'],
            'filiere_id' => ['required', 'exists:filieres,id'],
            'niveau_id' => ['required', 'exists:niveaux,id'],
            'annee_academique_id' => ['required', 'exists:annees_academiques,id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Création de l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'etudiant', // Rôle fixé à étudiant
        ]);

        // Création de l'étudiant associé
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

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('etudiant.dashboard');
    }
}
