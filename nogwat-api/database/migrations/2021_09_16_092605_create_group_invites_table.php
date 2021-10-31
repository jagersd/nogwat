<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupInvitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_invites', function (Blueprint $table) {
            $table->id();
            $table->integer('invited_user_id')->nullable();
            $table->integer('invitor_user_id');
            $table->integer('group_id');
            $table->enum('status',['pending','accepted','rejected'])->default('pending');
            $table->string('unregistered_email')->nullable();
            $table->timestamps();
            $table->unique(['invited_user_id','group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_invites');
    }
}
