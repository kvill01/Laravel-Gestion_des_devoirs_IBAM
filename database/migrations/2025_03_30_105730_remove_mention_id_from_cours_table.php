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
        Schema::table('cours', function (Blueprint $table) {
            // Supprimer la clé étrangère si elle existe
            if (Schema::hasColumn('cours', 'mention_id')) {
                $table->dropForeign(['mention_id']);
                $table->dropColumn('mention_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cours', function (Blueprint $table) {
            // Recréer la colonne si nécessaire
            if (!Schema::hasColumn('cours', 'mention_id')) {
                $table->unsignedBigInteger('mention_id')->nullable();
            }
        });
    }
};
