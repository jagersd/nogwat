<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActiveListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('active_lists', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id');
            $table->integer('user_id_added');
            $table->integer('store_id')->nullable();
            $table->string('item_name');
            $table->smallInteger('measurement_type_id')->nullable();
            $table->float('measurement_amount', 8,2)->nullable();
            $table->date('date_added')->useCurrent();
            $table->date('date_purchased')->nullable();
            $table->integer('user_id_purchased')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('recipe_id')->nullable();
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
        Schema::dropIfExists('active_lists');
    }
}
