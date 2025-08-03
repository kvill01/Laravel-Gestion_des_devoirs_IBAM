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
        // Avant de supprimer la table mentions, nous devons supprimer les contraintes de clé étrangère
        Schema::table('cours', function (Blueprint $table) {
            if (Schema::hasColumn('cours', 'mention_id')) {
                $table->dropForeign(['mention_id']);
                $table->dropColumn('mention_id');
            }
        });

        Schema::dropIfExists('mentions');
    }

    /**
     * Reverse the migrations.
     * Nous ne pouvons pas vraiment restaurer la table mentions avec toutes ses données,
     * mais nous pouvons recréer la structure de la table.
     */
    public function down(): void
    {
        Schema::create('mentions', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('type')->nullable();
            $table->string('niveau')->nullable();
            $table->foreignId('domaine_id')->nullable()->constrained('domaines');
            $table->timestamps();
        });

        // Recréer la colonne mention_id dans la table cours
        Schema::table('cours', function (Blueprint $table) {
            $table->foreignId('mention_id')->nullable()->constrained('mentions');
        });
    }
};
