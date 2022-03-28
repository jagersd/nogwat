<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_stats', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->dateTime('last_login');
            $table->integer('mails_triggered')->nullable();
            $table->integer('password_resets')->default(0);
            $table->dateTime('last_password_reset')->nullable();
            $table->integer('errors_triggered')->nullable();
            $table->dateTime('last_error')->nullable();
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
        Schema::dropIfExists('user_stats');
    }
}
