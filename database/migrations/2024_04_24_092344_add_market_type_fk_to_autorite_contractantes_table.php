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
        Schema::table('autorite_contractantes', function (Blueprint $table) {
            // Adding the nullable foreign key column
            $table->unsignedBigInteger('market_type_id')->nullable()->after('is_exempted');

            // Adding the foreign key constraint
            $table->foreign('market_type_id')->references('id')->on('market_types')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('autorite_contractantes', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['market_type_id']);

            // Drop the column
            $table->dropColumn('market_type_id');
        });
    }
};
