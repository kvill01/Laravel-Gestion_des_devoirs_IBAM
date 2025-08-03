<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Début de la correction des associations cours-enseignants...\n";

// Récupérer tous les cours avec leur enseignant_id
$cours = DB::table('cours')
    ->whereNotNull('enseignants_id')
    ->get(['id', 'intitule', 'enseignants_id']);

echo "Nombre de cours trouvés : " . $cours->count() . "\n";

$count = 0;
// Pour chaque cours, créer une entrée dans la table pivot
foreach ($cours as $cours_item) {
    // Vérifier si l'association n'existe pas déjà
    $exists = DB::table('cours_enseignant')
        ->where('enseignant_id', $cours_item->enseignants_id)
        ->where('cours_id', $cours_item->id)
        ->exists();
    
    if (!$exists) {
        DB::table('cours_enseignant')->insert([
            'enseignant_id' => $cours_item->enseignants_id,
            'cours_id' => $cours_item->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $count++;
        echo "Association créée pour cours : {$cours_item->intitule} (ID: {$cours_item->id}), enseignant ID: {$cours_item->enseignants_id}\n";
    }
}

echo "Terminé ! $count associations ont été créées dans la table pivot cours_enseignant.\n";
