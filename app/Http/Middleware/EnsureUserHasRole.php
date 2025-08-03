<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $roles = explode(',', $role);
        
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Accès non autorisé.'], 403);
            }
            
            // Rediriger vers le dashboard approprié selon le rôle
            $userRole = $request->user()->role;
            $route = match($userRole) {
                'admin' => 'admin.dashboard',
                'enseignant' => 'enseignant.dashboard',
                'surveillant' => 'surveillant.dashboard',
                'etudiant' => 'etudiant.dashboard',
                default => 'login'
            };
            
            return redirect()->route($route)->with('error', 'Vous n\'avez pas la permission d\'accéder à cette page.');
        }

        return $next($request);
    }
}
