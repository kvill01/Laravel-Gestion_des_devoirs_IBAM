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
        Schema::create('emploi_du_temps', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('jour', 50)->nullable();
            $table->time('heure_debut')->nullable();
            $table->time('heure_fin')->nullable();
            $table->foreignId('semestre_id')->constrained('semestres');
            $table->foreignId('annee_academique_id')->constrained('annees_academiques');
            $table->foreignId('cours_id')->constrained('cours');
            $table->timestamps();
            $table->softDeletes();

            $table->index('jour');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emploi_du_temps');
    }
};
