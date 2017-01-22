<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstimateFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->json('estimate_data')->nullable()->default(null);
            $table->string('total_time', 20)->nullable()->default(null);
            $table->double('total_cost', 9, 2)->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estimates', function (Blueprint $table) {
            $table->dropColumn('estimate_data');
            $table->dropColumn('total_time');
            $table->dropColumn('total_cost');
        });
    }
}
