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
        // First, remove the market_type_id column from the autorite_contractantes table
        Schema::table('autorite_contractantes', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['market_type_id']);

            // Drop the column
            $table->dropColumn('market_type_id');
        });

        // Then, create the intermediate table for the many-to-many relationship
        Schema::create('autorite_market_type', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('autorite_contractante_id');
            $table->unsignedBigInteger('market_type_id');

            // Optional: Add timestamps if you want to track when entries are created or updated
            $table->timestamps();

            // Set foreign key constraints
            $table->foreign('autorite_contractante_id', 'fk_autorite_contractantes')
                ->references('id')->on('autorite_contractantes')
                ->onDelete('cascade');

            $table->foreign('market_type_id', 'fk_market_types')
                ->references('id')->on('market_types')
                ->onDelete('cascade');

            // Optional: Add a unique constraint to prevent duplicate entries
            $table->unique(['autorite_contractante_id', 'market_type_id'], 'unique_autorite_market_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Recreate the market_type_id column in autorite_contractantes table
        Schema::table('autorite_contractantes', function (Blueprint $table) {
            $table->unsignedBigInteger('market_type_id')->nullable()->after('is_exempted');

            // Recreate the foreign key constraint
            $table->foreign('market_type_id')->references('id')->on('market_types')
                ->onDelete('set null');
        });

        // Drop the intermediate table
        Schema::dropIfExists('autorite_market_type');
    }
};
