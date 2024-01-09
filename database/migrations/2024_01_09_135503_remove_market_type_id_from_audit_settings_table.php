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
        Schema::table('audit_settings', function (Blueprint $table) {
            Schema::table('audit_settings', function (Blueprint $table) {
                $table->dropForeign(['market_type_id']);
                $table->dropColumn('market_type_id');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audit_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('market_type_id')->nullable();
            $table->foreign('market_type_id')->references('id')->on('market_types')->onDelete('set null');
        });
    }
};
