<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devoirs', function (Blueprint $table) {
            $table->dateTime('date_heure_proposee')->nullable()->after('date_heure');
            $table->text('commentaire_enseignant')->nullable()->after('date_heure_proposee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devoirs', function (Blueprint $table) {
            $table->dropColumn('date_heure_proposee');
            $table->dropColumn('commentaire_enseignant');
        });
    }
};
