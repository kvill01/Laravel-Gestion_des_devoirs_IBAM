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
        Schema::table('etudiants', function (Blueprint $table) {
            if (!Schema::hasColumn('etudiants', 'annee_academique_id')) {
                $table->foreignId('annee_academique_id')->nullable()->after('niveau')->constrained('annees_academiques')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('etudiants', function (Blueprint $table) {
            if (Schema::hasColumn('etudiants', 'annee_academique_id')) {
                $table->dropForeign(['annee_academique_id']);
                $table->dropColumn('annee_academique_id');
            }
        });
    }
};
