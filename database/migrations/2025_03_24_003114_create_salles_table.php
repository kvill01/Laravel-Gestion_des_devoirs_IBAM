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
        if (!Schema::hasTable('salles')) {
            Schema::create('salles', function (Blueprint $table) {
                $table->id();
                $table->string('nom')->unique();
                $table->integer('capacite');
                $table->string('type');
                $table->string('localisation');
                $table->boolean('disponible')->default(true);
                $table->text('description')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Table pivot pour la relation entre les devoirs et les salles
        if (!Schema::hasTable('devoir_salles')) {
            Schema::create('devoir_salles', function (Blueprint $table) {
                $table->id();
                $table->foreignId('devoir_id')->constrained('devoirs')->onDelete('cascade');
                $table->foreignId('salle_id')->constrained('salles')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devoir_salles');
        Schema::dropIfExists('salles');
    }
};
