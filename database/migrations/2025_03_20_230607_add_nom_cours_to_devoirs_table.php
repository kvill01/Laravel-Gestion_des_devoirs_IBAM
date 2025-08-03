<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNomCoursToDevoirsTable extends Migration
{
    public function up()
    {
        Schema::table('devoirs', function (Blueprint $table) {
            $table->string('nom_cours')->after('sujet_devoir');
        });
    }

    public function down()
    {
        Schema::table('devoirs', function (Blueprint $table) {
            $table->dropColumn('nom_cours');
        });
    }
}
