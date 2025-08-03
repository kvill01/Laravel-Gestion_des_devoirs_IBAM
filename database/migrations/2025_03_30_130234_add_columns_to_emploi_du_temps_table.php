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
        Schema::table('emploi_du_temps', function (Blueprint $table) {
            // Modifier la colonne jour pour être un enum
            $table->dropColumn('jour');
            $table->enum('jour', ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'])->after('id');
            
            // Ajouter les colonnes manquantes
            $table->foreignId('filiere_id')->nullable()->after('jour')->constrained()->onDelete('cascade');
            $table->foreignId('niveau_id')->nullable()->after('filiere_id')->constrained()->onDelete('cascade');
            $table->foreignId('enseignants_id')->nullable()->after('cours_id')->constrained('enseignants')->onDelete('set null');
            $table->foreignId('salle_id')->nullable()->after('enseignants_id')->constrained('salles')->onDelete('set null');
            $table->string('type_cours')->nullable()->after('heure_fin');
            $table->date('date_debut')->nullable()->after('type_cours');
            $table->date('date_fin')->nullable()->after('date_debut');
            $table->string('commentaire')->nullable()->after('date_fin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emploi_du_temps', function (Blueprint $table) {
            $table->string('jour', 50)->nullable()->change();
            
            // Supprimer les colonnes ajoutées
            $table->dropForeign(['filiere_id']);
            $table->dropForeign(['niveau_id']);
            $table->dropForeign(['enseignants_id']);
            $table->dropForeign(['salle_id']);
            
            $table->dropColumn([
                'filiere_id',
                'niveau_id',
                'enseignants_id',
                'salle_id',
                'type_cours',
                'date_debut',
                'date_fin',
                'commentaire'
            ]);
        });
    }
};
