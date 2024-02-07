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
            // Add foreign key for passation_mode
            $table->unsignedBigInteger('passation_mode')->default(0);
            $table->foreign('passation_mode')->references('id')->on('mode_passations')->onDelete('set null');

            // Add foreign key for authority_contracting
            $table->unsignedBigInteger('authority_contracting')->default(0);
            $table->foreign('authority_contracting')->references('id')->on('autorite_contractantes')->onDelete('set null');
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
            // Drop foreign key for passation_mode
            $table->dropForeign(['passation_mode']);

            // Drop foreign key for authority_contracting
            $table->dropForeign(['authority_contracting']);

            // Drop columns
            $table->dropColumn(['passation_mode', 'authority_contracting']);
        });
    }
};
