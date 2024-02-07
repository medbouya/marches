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
            $table->string('numero')->nullable();
            $table->string('financement')->nullable();
            $table->date('date_signature')->nullable();
            $table->date('date_notification')->nullable();
            $table->date('date_publication')->nullable();
            $table->unsignedBigInteger('delai_execution')->nullable();
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
            $table->dropColumn('numero');
            $table->dropColumn('financement');
            $table->dropColumn('date_signature');
            $table->dropColumn('date_notification');
            $table->dropColumn('date_publication');
            $table->dropColumn('delai_execution');

        });
    }
};
