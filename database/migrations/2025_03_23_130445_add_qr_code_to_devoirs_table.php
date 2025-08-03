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
        Schema::table('devoirs', function (Blueprint $table) {
            if (!Schema::hasColumn('devoirs', 'code_QR')) {
                $table->string('code_QR')->nullable()->after('nom_cours');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devoirs', function (Blueprint $table) {
            if (Schema::hasColumn('devoirs', 'code_QR')) {
                $table->dropColumn('code_QR');
            }
        });
    }
};
