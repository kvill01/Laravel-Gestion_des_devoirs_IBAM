<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Enseignant;
use App\Models\Surveillant;
use App\Models\Etudiant;
use App\Models\Devoir;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'enseignant') {
            return redirect()->route('enseignant.dashboard');
        } elseif ($user->role === 'etudiant') {
            return redirect()->route('etudiant.dashboard');
        } elseif ($user->role === 'surveillant') {
            // Surveillant
            return redirect()->route('surveillant.dashboard');
        }
        else {
            return redirect()->route('login');
        }
    }

    public function adminDashboard()
    {
        $totalEnseignants = Enseignant::count();
        $totalSurveillants = Surveillant::count();
        $totalEtudiants = Etudiant::count();
        $totalDevoirs = Devoir::count();
        
        return view('admin.dashboard', compact('totalEnseignants', 'totalSurveillants', 'totalEtudiants', 'totalDevoirs'));
    }
    
}