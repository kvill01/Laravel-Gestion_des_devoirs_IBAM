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
        if (!Schema::hasTable('cours_enseignant')) {
            Schema::create('cours_enseignant', function (Blueprint $table) {
                $table->unsignedBigInteger('cours_id');
                $table->unsignedBigInteger('enseignant_id');
                
                $table->foreign('cours_id')->references('id')->on('cours')->onDelete('cascade');
                $table->foreign('enseignant_id')->references('id')->on('enseignants')->onDelete('cascade');
                
                $table->primary(['cours_id', 'enseignant_id']);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cours_enseignant');
    }
};
