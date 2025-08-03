<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Supprime les anciens champs type et niveau qui sont remplacés par les relations avec les tables filieres et niveaux
     */
    public function up(): void
    {
        Schema::table('etudiants', function (Blueprint $table) {
            // S'assurer que les données sont migrées avant de supprimer les colonnes
            // Cette partie est gérée par un seeder ou un script de migration de données
            
            // Supprimer les anciennes colonnes
            if (Schema::hasColumn('etudiants', 'type')) {
                $table->dropColumn('type');
            }
            
            if (Schema::hasColumn('etudiants', 'niveau')) {
                $table->dropColumn('niveau');
            }
        });
    }

    /**
     * Reverse the migrations.
     * Restaure les anciens champs type et niveau
     */
    public function down(): void
    {
        Schema::table('etudiants', function (Blueprint $table) {
            // Recréer les anciennes colonnes
            if (!Schema::hasColumn('etudiants', 'type')) {
                $table->enum('type', ['MIAGE', 'ABF', 'ADB', 'MID', 'CCA'])->nullable()->after('date_naissance');
            }
            
            if (!Schema::hasColumn('etudiants', 'niveau')) {
                $table->enum('niveau', ['L1','L2','L3'])->nullable()->after('type');
            }
        });
    }
};
