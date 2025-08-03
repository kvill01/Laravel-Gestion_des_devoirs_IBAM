<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\SurveillantController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DevoirController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route par défaut
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard route - redirection selon le rôle
Route::get('/dashboard', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        return redirect()->route($role . '.dashboard');
    }
    return redirect()->route('login');
})->name('dashboard');

// Routes pour la connexion et le register
Route::middleware('web')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    // Routes pour la vérification d'email
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->middleware('auth')->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/');
    })->middleware(['auth', 'signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Lien de vérification envoyé !');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');
});

// Routes pour les utilisateurs authentifiés
Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    // Routes du profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Route pour la mise à jour du mot de passe
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    
    // Routes pour les dashboards
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
        ->middleware(\App\Http\Middleware\EnsureUserHasRole::class . ':admin')
        ->name('admin.dashboard');
    
    Route::get('/enseignant/dashboard', [EnseignantController::class, 'index'])
        ->middleware(\App\Http\Middleware\EnsureUserHasRole::class . ':enseignant')
        ->name('enseignant.dashboard');
    
    Route::get('/surveillant/dashboard', [SurveillantController::class, 'index'])
        ->middleware(\App\Http\Middleware\EnsureUserHasRole::class . ':surveillant')
        ->name('surveillant.dashboard');
    
    Route::get('/etudiant/dashboard', [EtudiantController::class, 'index'])
        ->middleware(\App\Http\Middleware\EnsureUserHasRole::class . ':etudiant')
        ->name('etudiant.dashboard');

    // Routes pour les étudiants
    Route::middleware(\App\Http\Middleware\EnsureUserHasRole::class . ':etudiant')
        ->prefix('etudiant')
        ->name('etudiant.')
        ->group(function () {
            // Dashboard
            Route::get('/dashboard', [EtudiantController::class, 'index'])->name('dashboard');
            
            // Routes pour les devoirs
            Route::get('/devoirs', [EtudiantController::class, 'devoirsIndex'])->name('devoirs.index');
            Route::get('/devoirs/{devoir}', [EtudiantController::class, 'devoirsShow'])->name('devoirs.show');
            
            // Routes pour l'emploi du temps
            Route::get('/emploi-du-temps', [\App\Http\Controllers\EmploiDuTempsController::class, 'index'])->name('emploi-du-temps');
            Route::get('/emploi-du-temps/semaine', [\App\Http\Controllers\EmploiDuTempsController::class, 'showByWeek'])->name('emploi-du-temps.semaine');
    });

    // Routes pour les surveillants
    Route::middleware(\App\Http\Middleware\EnsureUserHasRole::class . ':surveillant')
        ->prefix('surveillant')
        ->name('surveillant.')
        ->group(function () {
            Route::get('/dashboard', [SurveillantController::class, 'index'])->name('dashboard');
            Route::post('/devoirs/{devoir}/accepter', [SurveillantController::class, 'accepterDevoir'])->name('devoirs.accepter');
            Route::post('/devoirs/{devoir}/refuser', [SurveillantController::class, 'refuserDevoir'])->name('devoirs.refuser');
        });

    // Routes pour les enseignants
    Route::middleware(\App\Http\Middleware\EnsureUserHasRole::class . ':enseignant')->group(function () {
        Route::get('/enseignant/dashboard', [EnseignantController::class, 'dashboard'])->name('enseignant.dashboard');
        Route::get('/enseignant/devoirs', [EnseignantController::class, 'devoirsIndex'])->name('enseignant.devoirs.index');
        Route::get('/enseignant/devoirs/create', [EnseignantController::class, 'devoirsCreate'])->name('enseignant.devoirs.create');
        Route::post('/enseignant/devoirs', [EnseignantController::class, 'devoirsStore'])->name('enseignant.devoirs.store');
        Route::get('/enseignant/devoirs/{devoir}/edit', [EnseignantController::class, 'devoirsEdit'])->name('enseignant.devoirs.edit');
        Route::put('/enseignant/devoirs/{devoir}', [EnseignantController::class, 'devoirsUpdate'])->name('enseignant.devoirs.update');
        Route::delete('/enseignant/devoirs/{devoir}', [EnseignantController::class, 'devoirsDestroy'])->name('enseignant.devoirs.destroy');
        Route::get('/enseignant/cours', [EnseignantController::class, 'coursIndex'])->name('enseignant.cours.index');
    });

    // Routes pour l'administrateur
    Route::middleware(\App\Http\Middleware\EnsureUserHasRole::class . ':admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Gestion des utilisateurs
        Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
        
        // Gestion des filières
        Route::get('/admin/filieres', [\App\Http\Controllers\Admin\FiliereController::class, 'index'])->name('admin.filieres.index');
        Route::get('/admin/filieres/create', [\App\Http\Controllers\Admin\FiliereController::class, 'create'])->name('admin.filieres.create');
        Route::post('/admin/filieres', [\App\Http\Controllers\Admin\FiliereController::class, 'store'])->name('admin.filieres.store');
        Route::get('/admin/filieres/{filiere}/edit', [\App\Http\Controllers\Admin\FiliereController::class, 'edit'])->name('admin.filieres.edit');
        Route::put('/admin/filieres/{filiere}', [\App\Http\Controllers\Admin\FiliereController::class, 'update'])->name('admin.filieres.update');
        Route::delete('/admin/filieres/{filiere}', [\App\Http\Controllers\Admin\FiliereController::class, 'destroy'])->name('admin.filieres.destroy');
        
        // Gestion des niveaux
        Route::get('/admin/niveaux', [\App\Http\Controllers\Admin\NiveauController::class, 'index'])->name('admin.niveaux.index');
        Route::get('/admin/niveaux/create', [\App\Http\Controllers\Admin\NiveauController::class, 'create'])->name('admin.niveaux.create');
        Route::post('/admin/niveaux', [\App\Http\Controllers\Admin\NiveauController::class, 'store'])->name('admin.niveaux.store');
        Route::get('/admin/niveaux/{niveau}/edit', [\App\Http\Controllers\Admin\NiveauController::class, 'edit'])->name('admin.niveaux.edit');
        Route::put('/admin/niveaux/{niveau}', [\App\Http\Controllers\Admin\NiveauController::class, 'update'])->name('admin.niveaux.update');
        Route::delete('/admin/niveaux/{niveau}', [\App\Http\Controllers\Admin\NiveauController::class, 'destroy'])->name('admin.niveaux.destroy');
        
        // Gestion des associations de cours
        Route::get('/admin/cours-associations', [\App\Http\Controllers\Admin\CoursAssociationController::class, 'index'])->name('admin.cours_associations.index');
        Route::get('/admin/cours-associations/{cours}/edit', [\App\Http\Controllers\Admin\CoursAssociationController::class, 'edit'])->name('admin.cours_associations.edit');
        Route::put('/admin/cours-associations/{cours}', [\App\Http\Controllers\Admin\CoursAssociationController::class, 'update'])->name('admin.cours_associations.update');
        Route::get('/admin/cours-associations/batch', [\App\Http\Controllers\Admin\CoursAssociationController::class, 'batch'])->name('admin.cours_associations.batch');
        Route::post('/admin/cours-associations/batch', [\App\Http\Controllers\Admin\CoursAssociationController::class, 'batchStore'])->name('admin.cours_associations.batchStore');
        
        // Gestion des surveillants
        Route::get('/admin/surveillants', [AdminController::class, 'surveillantsIndex'])->name('admin.surveillants.index');
        Route::get('/admin/surveillants/create', [AdminController::class, 'createSurveillant'])->name('admin.surveillants.create');
        Route::post('/admin/surveillants', [AdminController::class, 'storeSurveillant'])->name('admin.surveillants.store');
        Route::get('/admin/surveillants/{surveillant}/edit', [AdminController::class, 'editSurveillant'])->name('admin.surveillants.edit');
        Route::put('/admin/surveillants/{surveillant}', [AdminController::class, 'updateSurveillant'])->name('admin.surveillants.update');
        Route::delete('/admin/surveillants/{surveillant}', [AdminController::class, 'destroySurveillant'])->name('admin.surveillants.destroy');
        
        // Gestion des enseignants
        Route::get('/admin/enseignants', [AdminController::class, 'enseignantsIndex'])->name('admin.enseignants.index');
        Route::get('/admin/enseignants/create', [AdminController::class, 'createEnseignant'])->name('admin.enseignants.create');
        Route::post('/admin/enseignants', [AdminController::class, 'storeEnseignant'])->name('admin.enseignants.store');
        Route::get('/admin/enseignants/{enseignant}/edit', [AdminController::class, 'editEnseignant'])->name('admin.enseignants.edit');
        Route::put('/admin/enseignants/{enseignant}', [AdminController::class, 'updateEnseignant'])->name('admin.enseignants.update');
        Route::delete('/admin/enseignants/{enseignant}', [AdminController::class, 'destroyEnseignant'])->name('admin.enseignants.destroy');
        
        // Gestion des étudiants
        Route::get('/admin/etudiants', [AdminController::class, 'etudiantsIndex'])->name('admin.etudiants.index');
        Route::get('/admin/etudiants/create', [AdminController::class, 'createEtudiant'])->name('admin.etudiants.create');
        Route::post('/admin/etudiants', [AdminController::class, 'storeEtudiant'])->name('admin.etudiants.store');
        Route::get('/admin/etudiants/{etudiant}/edit', [AdminController::class, 'editEtudiant'])->name('admin.etudiants.edit');
        Route::put('/admin/etudiants/{etudiant}', [AdminController::class, 'updateEtudiant'])->name('admin.etudiants.update');
        Route::delete('/admin/etudiants/{etudiant}', [AdminController::class, 'destroyEtudiant'])->name('admin.etudiants.destroy');

        // Gestion des devoirs
        Route::get('/admin/devoirs/en-attente', [AdminController::class, 'devoirsEnAttente'])->name('admin.devoirs.en_attente');
        Route::get('/admin/devoirs/confirmes', [AdminController::class, 'devoirsConfirmes'])->name('admin.devoirs.confirmes');
        Route::get('/admin/devoirs/{devoir}/confirmer', [AdminController::class, 'confirmerDevoirForm'])->name('admin.devoirs.confirmer');
        Route::post('/admin/devoirs/{devoir}/confirmer', [AdminController::class, 'confirmerDevoir'])->name('admin.devoirs.confirmerPost');
        Route::post('/admin/devoirs/{devoir}/terminer', [AdminController::class, 'terminerDevoir'])->name('admin.devoirs.terminer');
        Route::get('/admin/devoirs/{devoir}', [AdminController::class, 'showDevoir'])->name('admin.devoirs.show');
        Route::get('/admin/devoirs/{devoir}/edit', [AdminController::class, 'devoirsEdit'])->name('admin.devoirs.edit');
        Route::put('/admin/devoirs/{devoir}', [AdminController::class, 'devoirsUpdate'])->name('admin.devoirs.update');
        Route::get('/admin/devoirs/{devoir}/download', [AdminController::class, 'downloadDevoir'])->name('admin.devoirs.download');
        
        // Gestion des salles
        Route::resource('/admin/salles', \App\Http\Controllers\Admin\SalleController::class)->names([
            'index' => 'admin.salles.index',
            'create' => 'admin.salles.create',
            'store' => 'admin.salles.store',
            'show' => 'admin.salles.show',
            'edit' => 'admin.salles.edit',
            'update' => 'admin.salles.update',
            'destroy' => 'admin.salles.destroy',
        ]);
        
        // Gestion des cours
        Route::resource('/admin/cours', \App\Http\Controllers\CoursController::class)->names([
            'index' => 'admin.cours.index',
            'create' => 'admin.cours.create',
            'store' => 'admin.cours.store',
            'show' => 'admin.cours.show',
            'edit' => 'admin.cours.edit',
            'update' => 'admin.cours.update',
            'destroy' => 'admin.cours.destroy',
        ]);
    });
});