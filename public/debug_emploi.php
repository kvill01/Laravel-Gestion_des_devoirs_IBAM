<?php
// Script de débogage pour l'emploi du temps

// Inclure l'autoloader de Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Récupérer les modèles nécessaires
use App\Models\EmploiDuTemps;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Semestre;
use App\Models\Enseignant;
use App\Models\Cours;
use App\Models\AnneeAcademique;
use Carbon\Carbon;

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Diagnostic de l'emploi du temps</h1>";

// Vérifier les années académiques
echo "<h2>Années académiques</h2>";
$anneeAcademiques = AnneeAcademique::all();
if ($anneeAcademiques->count() == 0) {
    echo "<p>Aucune année académique trouvée</p>";
} else {
    echo "<ul>";
    foreach ($anneeAcademiques as $annee) {
        echo "<li>ID: {$annee->id}, Années: {$annee->annee_debut}-{$annee->annee_fin}</li>";
    }
    echo "</ul>";
}

// Vérifier les filières
echo "<h2>Filières</h2>";
$filieres = Filiere::all();
if ($filieres->count() == 0) {
    echo "<p>Aucune filière trouvée</p>";
} else {
    echo "<ul>";
    foreach ($filieres as $filiere) {
        echo "<li>ID: {$filiere->id}, Code: {$filiere->code}, Nom: {$filiere->nom}</li>";
    }
    echo "</ul>";
}

// Vérifier les niveaux
echo "<h2>Niveaux</h2>";
$niveaux = Niveau::all();
if ($niveaux->count() == 0) {
    echo "<p>Aucun niveau trouvé</p>";
} else {
    echo "<ul>";
    foreach ($niveaux as $niveau) {
        echo "<li>ID: {$niveau->id}, Code: {$niveau->code}, Nom: {$niveau->nom}</li>";
    }
    echo "</ul>";
}

// Vérifier les semestres
echo "<h2>Semestres</h2>";
$semestres = Semestre::all();
if ($semestres->count() == 0) {
    echo "<p>Aucun semestre trouvé</p>";
} else {
    echo "<ul>";
    foreach ($semestres as $semestre) {
        echo "<li>ID: {$semestre->id}, Libellé: {$semestre->libelle}, Année académique ID: {$semestre->annee_academique_id}, Programme ID: {$semestre->programme_id}</li>";
    }
    echo "</ul>";
}

// Vérifier les étudiants
echo "<h2>Étudiants</h2>";
$etudiants = Etudiant::with(['user', 'filiere', 'niveau'])->take(5)->get();
if ($etudiants->count() == 0) {
    echo "<p>Aucun étudiant trouvé</p>";
} else {
    echo "<ul>";
    foreach ($etudiants as $etudiant) {
        echo "<li>ID: {$etudiant->id}, Nom: {$etudiant->nom} {$etudiant->prenom}, User ID: {$etudiant->user_id}, ";
        echo "Filière: " . ($etudiant->filiere ? $etudiant->filiere->nom : "Non définie") . ", ";
        echo "Niveau: " . ($etudiant->niveau ? $etudiant->niveau->nom : "Non défini") . "</li>";
    }
    echo "</ul>";
}

// Vérifier l'emploi du temps
echo "<h2>Emploi du temps</h2>";
$emploiDuTemps = EmploiDuTemps::with(['cours', 'enseignant', 'salle', 'filiere', 'niveau', 'semestre'])->take(10)->get();
if ($emploiDuTemps->count() == 0) {
    echo "<p>Aucun emploi du temps trouvé</p>";
} else {
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>ID</th><th>Jour</th><th>Heures</th><th>Cours</th><th>Enseignant</th><th>Salle</th><th>Filière</th><th>Niveau</th><th>Semestre</th></tr>";
    foreach ($emploiDuTemps as $edt) {
        echo "<tr>";
        echo "<td>{$edt->id}</td>";
        echo "<td>{$edt->jour}</td>";
        echo "<td>" . Carbon::parse($edt->heure_debut)->format('H:i') . " - " . Carbon::parse($edt->heure_fin)->format('H:i') . "</td>";
        echo "<td>" . ($edt->cours ? $edt->cours->intitule : "Non défini") . "</td>";
        echo "<td>" . ($edt->enseignant ? $edt->enseignant->nom : "Non défini") . "</td>";
        echo "<td>" . ($edt->salle ? $edt->salle->nom : "Non définie") . "</td>";
        echo "<td>" . ($edt->filiere ? $edt->filiere->nom : "Non définie") . "</td>";
        echo "<td>" . ($edt->niveau ? $edt->niveau->nom : "Non défini") . "</td>";
        echo "<td>" . ($edt->semestre ? $edt->semestre->libelle : "Non défini") . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Vérifier les cours et leurs enseignants associés
echo "<h2>Cours et enseignants associés</h2>";
$cours = Cours::with('enseignants')->get();
if ($cours->count() == 0) {
    echo "<p>Aucun cours trouvé</p>";
} else {
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>ID</th><th>Intitulé</th><th>Enseignant principal</th><th>Enseignants associés</th></tr>";
    foreach ($cours as $c) {
        echo "<tr>";
        echo "<td>{$c->id}</td>";
        echo "<td>{$c->intitule}</td>";
        
        // Enseignant principal (dans la colonne enseignants_id)
        $enseignantPrincipal = Enseignant::find($c->enseignants_id);
        echo "<td>" . ($enseignantPrincipal ? $enseignantPrincipal->nom . ' ' . $enseignantPrincipal->prenom : "Non défini") . "</td>";
        
        // Enseignants associés via la relation many-to-many
        echo "<td>";
        if ($c->enseignants && $c->enseignants->count() > 0) {
            $enseignants = [];
            foreach ($c->enseignants as $enseignant) {
                $enseignants[] = $enseignant->nom . ' ' . $enseignant->prenom;
            }
            echo implode(', ', $enseignants);
        } else {
            echo "Aucun enseignant associé";
        }
        echo "</td>";
        
        echo "</tr>";
    }
    echo "</table>";
}
