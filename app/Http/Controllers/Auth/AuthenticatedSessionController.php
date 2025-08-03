<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
        
        // Rediriger l'utilisateur en fonction de son rôle
        return $this->redirectBasedOnRole(Auth::user());
    }

    /**
     * Rediriger l'utilisateur en fonction de son rôle
     */
    protected function redirectBasedOnRole($user): RedirectResponse
    {
        // Déboguer pour voir le rôle de l'utilisateur
        //dd($user->role, $user->isAdmin(), $user->isEnseignant(), $user->isSurveillant(), $user->isEtudiant());

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isEnseignant()) {
            return redirect()->route('enseignant.dashboard');
        } elseif ($user->isSurveillant()) {
            return redirect()->route('surveillant.dashboard');
        } elseif ($user->isEtudiant()) {
            return redirect()->route('etudiant.dashboard');
        }
        
        // Redirection par défaut si aucun rôle spécifique n'est trouvé
        return redirect('/');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}