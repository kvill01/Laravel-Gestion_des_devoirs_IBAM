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
        Schema::table('etudiants', function (Blueprint $table) {
            // Ajouter les nouveaux champs pour la relation avec filiere et niveau
            if (!Schema::hasColumn('etudiants', 'filiere_id')) {
                $table->foreignId('filiere_id')->nullable()->after('niveau')->constrained('filieres');
            }
            
            if (!Schema::hasColumn('etudiants', 'niveau_id')) {
                $table->foreignId('niveau_id')->nullable()->after('filiere_id')->constrained('niveaux');
            }
            
            // Modifier la contrainte de clé étrangère pour l'année académique
            if (Schema::hasColumn('etudiants', 'annee_academique_id')) {
                // D'abord supprimer la contrainte existante
                $table->dropForeign(['annee_academique_id']);
                
                // Puis recréer la contrainte avec cascade au lieu de nullOnDelete
                $table->foreign('annee_academique_id')
                      ->references('id')
                      ->on('annees_academiques')
                      ->onDelete('restrict');
                
                // Rendre le champ non nullable
                $table->foreignId('annee_academique_id')->nullable(false)->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('etudiants', function (Blueprint $table) {
            // Supprimer les clés étrangères et les colonnes
            if (Schema::hasColumn('etudiants', 'filiere_id')) {
                $table->dropForeign(['filiere_id']);
                $table->dropColumn('filiere_id');
            }
            
            if (Schema::hasColumn('etudiants', 'niveau_id')) {
                $table->dropForeign(['niveau_id']);
                $table->dropColumn('niveau_id');
            }
            
            // Remettre l'année académique comme nullable avec la contrainte originale
            if (Schema::hasColumn('etudiants', 'annee_academique_id')) {
                $table->dropForeign(['annee_academique_id']);
                
                // Recréer la contrainte originale
                $table->foreign('annee_academique_id')
                      ->references('id')
                      ->on('annees_academiques')
                      ->nullOnDelete();
                
                // Rendre le champ nullable
                $table->foreignId('annee_academique_id')->nullable()->change();
            }
        });
    }
};
