<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGithubInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('github_id')->comment('The User\'s GitHub ID');
            $table->json('github_details')->comment('The User\'s Raw GitHub Details');
            $table->text('avatar')->comment('The User\'s GitHub Avatar');
            $table->string('company')->comment('The User\'s GitHub Company Setting');
            $table->dropColumn('password');
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
            $table->dropColumn('github_id');
            $table->dropColumn('github_details');
            $table->dropColumn('avatar');
            $table->dropColumn('company');
            $table->string('password', 60);
        });
    }
}
