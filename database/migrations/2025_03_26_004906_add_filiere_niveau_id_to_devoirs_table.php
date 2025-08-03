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
            $table->foreignId('filiere_id')->nullable()->after('annee_academique_id')->constrained('filieres');
            $table->foreignId('niveau_id')->nullable()->after('filiere_id')->constrained('niveaux');
            
            // Ajout des champs type et niveau s'ils n'existent pas déjà
            if (!Schema::hasColumn('devoirs', 'type')) {
                $table->string('type')->nullable()->after('niveau_id');
            }
            if (!Schema::hasColumn('devoirs', 'niveau')) {
                $table->string('niveau')->nullable()->after('type');
            }
            
            // Ajout du champ nom_cours s'il n'existe pas déjà
            if (!Schema::hasColumn('devoirs', 'nom_cours')) {
                $table->string('nom_cours')->nullable()->after('cours_id');
            }
            
            // Ajout du champ duree_minutes s'il n'existe pas déjà
            if (!Schema::hasColumn('devoirs', 'duree_minutes')) {
                $table->integer('duree_minutes')->default(60)->after('nom_cours');
            }
            
            // Ajout du champ date_heure_proposee s'il n'existe pas déjà
            if (!Schema::hasColumn('devoirs', 'date_heure_proposee')) {
                $table->dateTime('date_heure_proposee')->nullable()->after('duree_minutes');
            }
            
            // Ajout du champ commentaire_enseignant s'il n'existe pas déjà
            if (!Schema::hasColumn('devoirs', 'commentaire_enseignant')) {
                $table->text('commentaire_enseignant')->nullable()->after('date_heure_proposee');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devoirs', function (Blueprint $table) {
            $table->dropForeign(['filiere_id']);
            $table->dropForeign(['niveau_id']);
            $table->dropColumn(['filiere_id', 'niveau_id']);
        });
    }
};
