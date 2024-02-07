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
            $table->bigInteger('threshold_exclusion')->default(1500000)->after('minimum_amount_to_audit');
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
            $table->dropColumn('threshold_exclusion');
        });
    }
};
