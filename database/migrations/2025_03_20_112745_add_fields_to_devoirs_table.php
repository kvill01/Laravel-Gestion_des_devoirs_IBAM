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
        Schema::table('devoirs', function (Blueprint $table) {
            // Renommer fichier_devoir en fichier_sujet s'il existe
            if (Schema::hasColumn('devoirs', 'fichier_devoir')) {
                $table->renameColumn('fichier_devoir', 'fichier_sujet');
            } else {
                // Ajouter fichier_sujet s'il n'existe pas
                $table->string('fichier_sujet')->nullable();
            }
            
            // Ajouter les champs manquants
            if (!Schema::hasColumn('devoirs', 'sujet_devoir')) {
                $table->text('sujet_devoir')->after('nom_devoir');
            }
            
            if (!Schema::hasColumn('devoirs', 'date_heure')) {
                $table->dateTime('date_heure')->nullable();
            }
            
            if (!Schema::hasColumn('devoirs', 'duree_minutes')) {
                $table->integer('duree_minutes')->default(60);
            }
            
            if (!Schema::hasColumn('devoirs', 'type')) {
                $table->string('type')->nullable(); // Pour la filière
            }
            
            if (!Schema::hasColumn('devoirs', 'niveau')) {
                $table->string('niveau')->nullable(); // L1, L2, L3
            }
            
            // Modifier le champ statut pour inclure toutes les valeurs nécessaires
            $table->enum('statut', ['en_attente', 'confirmé', 'terrminé', 'annulé'])->default('en_attente')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devoirs', function (Blueprint $table) {
            // Supprimer les champs ajoutés
            if (Schema::hasColumn('devoirs', 'sujet_devoir')) {
                $table->dropColumn('sujet_devoir');
            }
            
            if (Schema::hasColumn('devoirs', 'date_heure')) {
                $table->dropColumn('date_heure');
            }
            
            if (Schema::hasColumn('devoirs', 'duree_minutes')) {
                $table->dropColumn('duree_minutes');
            }
            
            if (Schema::hasColumn('devoirs', 'type')) {
                $table->dropColumn('type');
            }
            
            if (Schema::hasColumn('devoirs', 'niveau')) {
                $table->dropColumn('niveau');
            }
            
            // Renommer fichier_sujet en fichier_devoir
            if (Schema::hasColumn('devoirs', 'fichier_sujet')) {
                $table->renameColumn('fichier_sujet', 'fichier_devoir');
            }
            
            // Restaurer le statut d'origine
            $table->enum('statut', ['en_attente', 'confirmé', 'terrminé'])->default('en_attente')->change();
        });
    }
};
