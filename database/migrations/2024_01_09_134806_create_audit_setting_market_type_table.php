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
        Schema::create('audit_setting_market_type', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('audit_setting_id');
            $table->unsignedBigInteger('market_type_id');
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('audit_setting_id')->references('id')->on('audit_settings')->onDelete('cascade');
            $table->foreign('market_type_id')->references('id')->on('market_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audit_setting_market_type');
    }
};
