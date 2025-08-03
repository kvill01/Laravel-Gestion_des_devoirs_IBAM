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
        Schema::table('filieres', function (Blueprint $table) {
            if (Schema::hasColumn('filieres', 'domaine_id')) {
                $table->dropForeign(['domaine_id']);
                $table->dropColumn('domaine_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('filieres', function (Blueprint $table) {
            if (!Schema::hasColumn('filieres', 'domaine_id')) {
                $table->foreignId('domaine_id')->nullable()->after('description')->constrained('domaines');
            }
        });
    }
};
