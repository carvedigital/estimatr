<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetupRateColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('default_rate', 12, 2)->default(null)->nullable();
            $table->string('locale')->default('GBP');
            $table->string('rate_time_unit')->default('daily');
            $table->tinyInteger('hours_in_day')->default(8);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('default_rate');
            $table->dropColumn('locale');
            $table->dropColumn('rate_time_unit');
            $table->dropColumn('hours_in_day');
        });
    }
}
