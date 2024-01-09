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
        Schema::create('audit_settings', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->decimal('minimum_amount_to_audit');
            $table->foreignId('market_type_id')->nullable()->constrained('market_types');
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
        Schema::dropIfExists('audit_settings');
    }
};
