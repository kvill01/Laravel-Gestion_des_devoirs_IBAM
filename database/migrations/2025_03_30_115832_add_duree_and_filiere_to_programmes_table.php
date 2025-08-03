<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('programmes', function (Blueprint $table) {
            // Ajouter un champ pour la durée du programme en années
            $table->unsignedTinyInteger('duree_annees')->nullable()->after('description')
                  ->comment('Durée du programme en années');
                  
            // Ajouter une relation avec la table filières
            $table->foreignId('filiere_id')->nullable()->after('duree_annees')
                  ->constrained('filieres')
                  ->onDelete('set null')
                  ->comment('Filière à laquelle appartient ce programme');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programmes', function (Blueprint $table) {
            // Supprimer la contrainte de clé étrangère
            $table->dropForeign(['filiere_id']);
            
            // Supprimer les colonnes
            $table->dropColumn(['duree_annees', 'filiere_id']);
        });
    }
};
