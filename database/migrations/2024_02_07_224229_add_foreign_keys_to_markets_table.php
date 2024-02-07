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
        Schema::table('markets', function (Blueprint $table) {
            $table->foreignId('cpmp_id')->constrained('c_p_m_p_s')->onDelete('cascade');
            $table->foreignId('secteur_id')->constrained('secteurs')->onDelete('cascade');
            $table->foreignId('attributaire_id')->constrained('attributaires')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('markets', function (Blueprint $table) {
            // Drop foreign key constraint for CPMP
            $table->dropForeign(['cpmp_id']);

            // Drop foreign key constraint for Secteur
            $table->dropForeign(['secteur_id']);

            // Drop foreign key constraint for Attributaire
            $table->dropForeign(['attributaire_id']);
        });
    }
};
