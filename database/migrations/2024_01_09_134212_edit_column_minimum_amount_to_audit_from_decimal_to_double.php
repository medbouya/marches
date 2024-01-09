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
            $table->double('minimum_amount_to_audit')->change();
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
            $table->decimal('minimum_amount_to_audit', 8, 2)->change();
        });
    }
};
