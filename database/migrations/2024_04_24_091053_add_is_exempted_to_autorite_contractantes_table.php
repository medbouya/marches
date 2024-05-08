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
            $table->boolean('is_exempted')->default(false)->after('contact_person');
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
            $table->dropColumn('is_exempted');
        });
    }
};
