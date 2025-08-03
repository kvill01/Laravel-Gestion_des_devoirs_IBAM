<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Devoirs;
use Carbon\Carbon;

class SurveillantController extends Controller
{
    public function index()
    {
        // Vérification manuelle de l'authentification
        if (!Auth::check() || Auth::user()->role !== 'surveillant') {
            return redirect()->route('login');
        }
        
        $surveillant = Auth::user()->surveillant;
        
        // Récupérer les devoirs à venir (aujourd'hui et futurs)
        $devoirsAVenir = $surveillant->devoirs()
            ->whereDate('date_heure', '>=', Carbon::today())
            ->orderBy('date_heure')
            ->with(['enseignant', 'salle'])
            ->get();

        // Récupérer les devoirs passés
        $devoirsPassés = $surveillant->devoirs()
            ->whereDate('date_heure', '<', Carbon::today())
            ->orderBy('date_heure', 'desc')
            ->with(['enseignant', 'salle'])
            ->take(5)
            ->get();

        // Statistiques
        $totalDevoirs = $surveillant->devoirs()->count();
        $devoirsEnAttente = $surveillant->devoirsEnAttente()->count();
        $devoirsAcceptes = $surveillant->devoirsAcceptes()->count();
        $devoirsRefuses = $surveillant->devoirsRefuses()->count();
        
        return view('surveillant.dashboard', compact(
            'devoirsAVenir',
            'devoirsPassés',
            'totalDevoirs',
            'devoirsEnAttente',
            'devoirsAcceptes',
            'devoirsRefuses'
        ));
    }

    public function accepterDevoir($devoirId)
    {
        // Vérification manuelle de l'authentification
        if (!Auth::check() || Auth::user()->role !== 'surveillant') {
            return redirect()->route('login');
        }
        
        $surveillant = Auth::user()->surveillant;
        $devoir = Devoirs::findOrFail($devoirId);
        
        // Vérifier que le surveillant est bien assigné à ce devoir
        if (!$surveillant->devoirs()->where('devoir_id', $devoirId)->exists()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas assigné à ce devoir.');
        }

        $surveillant->devoirs()->updateExistingPivot($devoirId, [
            'statut' => 'accepte',
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Devoir accepté avec succès.');
    }

    public function refuserDevoir(Request $request, $devoirId)
    {
        // Vérification manuelle de l'authentification
        if (!Auth::check() || Auth::user()->role !== 'surveillant') {
            return redirect()->route('login');
        }
        
        $request->validate([
            'commentaire' => 'required|string|max:255'
        ]);

        $surveillant = Auth::user()->surveillant;
        $devoir = Devoirs::findOrFail($devoirId);
        
        // Vérifier que le surveillant est bien assigné à ce devoir
        if (!$surveillant->devoirs()->where('devoir_id', $devoirId)->exists()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas assigné à ce devoir.');
        }

        $surveillant->devoirs()->updateExistingPivot($devoirId, [
            'statut' => 'refuse',
            'commentaire' => $request->commentaire,
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Devoir refusé avec succès.');
    }
}