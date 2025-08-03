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
        // Assurons-nous que la table pivot pour les surveillants est correctement configurée
        if (!Schema::hasTable('devoir_surveillant')) {
            Schema::create('devoir_surveillant', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('devoir_id');
                $table->unsignedBigInteger('surveillant_id');
                $table->timestamps();
                
                $table->foreign('devoir_id')->references('id')->on('devoirs')->onDelete('cascade');
                $table->foreign('surveillant_id')->references('id')->on('surveillants')->onDelete('cascade');
                
                $table->unique(['devoir_id', 'surveillant_id']);
            });
        }
        
        // Mise à jour de la table devoir_salles si nécessaire
        if (!Schema::hasTable('devoir_salles')) {
            Schema::create('devoir_salles', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('devoir_id');
                $table->unsignedBigInteger('salle_id');
                $table->timestamps();
                
                $table->foreign('devoir_id')->references('id')->on('devoirs')->onDelete('cascade');
                $table->foreign('salle_id')->references('id')->on('salles')->onDelete('cascade');
                
                $table->unique(['devoir_id', 'salle_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ne pas supprimer les tables existantes dans la migration de rollback
    }
};
