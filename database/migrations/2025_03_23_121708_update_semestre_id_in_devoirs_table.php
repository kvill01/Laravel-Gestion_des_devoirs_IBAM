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
            // Rendre semestre_id nullable
            if (Schema::hasColumn('devoirs', 'semestre_id')) {
                $table->unsignedBigInteger('semestre_id')->nullable()->change();
            }
            
            // Ajouter des valeurs par défaut aux autres champs qui pourraient causer des problèmes
            if (Schema::hasColumn('devoirs', 'annee_academique_id')) {
                $table->unsignedBigInteger('annee_academique_id')->nullable()->change();
            }

            // Assurons-nous que le statut a une valeur par défaut
            if (Schema::hasColumn('devoirs', 'statut')) {
                $table->string('statut')->default('en_attente')->change();
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
            // Remettre les colonnes à leur état d'origine
            if (Schema::hasColumn('devoirs', 'semestre_id')) {
                $table->unsignedBigInteger('semestre_id')->nullable(false)->change();
            }
            
            if (Schema::hasColumn('devoirs', 'annee_academique_id')) {
                $table->unsignedBigInteger('annee_academique_id')->nullable(false)->change();
            }
            
            if (Schema::hasColumn('devoirs', 'statut')) {
                $table->string('statut')->default(null)->change();
            }
        });
    }
};
