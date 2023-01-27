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
        Schema::create('user_steps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('session_step_id');
            $table->unsignedSmallInteger('status_bit');
            $table->timestamps();

            $table->unique(['user_id', 'session_step_id'], 'udx_user_program');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('session_step_id')->references('id')->on('session_steps');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_steps');
    }
};
