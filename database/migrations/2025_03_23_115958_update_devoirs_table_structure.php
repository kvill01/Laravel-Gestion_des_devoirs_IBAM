<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devoirs', function (Blueprint $table) {
            // Rendre la colonne cours_id nullable
            if (Schema::hasColumn('devoirs', 'cours_id')) {
                $table->foreignId('cours_id')->nullable()->change();
            }
            
            // Supprimer la colonne sujet_devoir
            if (Schema::hasColumn('devoirs', 'sujet_devoir')) {
                $table->dropColumn('sujet_devoir');
            }
            
            // Modifier la colonne nom_cours pour qu'elle soit nullable
            if (Schema::hasColumn('devoirs', 'nom_cours')) {
                $table->string('nom_cours')->nullable()->change();
            } else {
                // Ajouter la colonne nom_cours si elle n'existe pas
                $table->string('nom_cours')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devoirs', function (Blueprint $table) {
            // Restaurer les colonnes à leur état d'origine
            if (Schema::hasColumn('devoirs', 'cours_id')) {
                $table->foreignId('cours_id')->nullable(false)->change();
            }
            
            if (!Schema::hasColumn('devoirs', 'sujet_devoir')) {
                $table->text('sujet_devoir');
            }
            
            if (Schema::hasColumn('devoirs', 'nom_cours')) {
                $table->string('nom_cours')->nullable(false)->change();
            }
        });
    }
};
