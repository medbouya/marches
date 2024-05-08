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
        Schema::create('autorite_contractante_secteur', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('autorite_contractante_id');
            $table->unsignedBigInteger('secteur_id');
            $table->foreign('autorite_contractante_id')->references('id')->on('autorite_contractantes')->onDelete('cascade');
            $table->foreign('secteur_id')->references('id')->on('secteurs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('autorite_contractante_secteur');
    }
};
