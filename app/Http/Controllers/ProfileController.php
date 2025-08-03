<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Mise à jour des données de base de l'utilisateur
        $user->fill($request->safe()->only(['name', 'email']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        
        // Mise à jour des données spécifiques selon le rôle
        if ($user->role === 'etudiant') {
            $etudiant = \App\Models\Etudiant::where('user_id', $user->id)->first();
            if ($etudiant) {
                $etudiant->nom = $request->nom;
                $etudiant->prenom = $request->prenom;
                $etudiant->save();
                
                // Mettre à jour le nom dans la table users pour la cohérence
                $user->name = $etudiant->nom . ' ' . $etudiant->prenom;
                $user->save();
            }
        } elseif ($user->role === 'enseignant') {
            $enseignant = \App\Models\Enseignant::where('user_id', $user->id)->first();
            if ($enseignant) {
                $enseignant->nom = $request->nom;
                $enseignant->prenom = $request->prenom;
                $enseignant->save();
                
                // Mettre à jour le nom dans la table users pour la cohérence
                $user->name = $enseignant->nom . ' ' . $enseignant->prenom;
                $user->save();
            }
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $request->user()->update([
            'password' => bcrypt($request->password)
        ]);

        return Redirect::route('profile.edit')->with('status', 'password-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
