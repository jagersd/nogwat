<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_configs', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('group_id');
            $table->tinyInteger('use_store')->default(0);
            $table->tinyInteger('use_store_type')->default(0);
            $table->tinyInteger('use_planning')->default(0);
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
        Schema::dropIfExists('group_configs');
    }
}
