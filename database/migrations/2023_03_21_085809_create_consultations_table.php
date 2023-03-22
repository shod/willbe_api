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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coach_id')->nullable();
            $table->unsignedBigInteger('client_id');
            $table->string('description', 512)->default('');
            $table->text('notice', 2048)->nullable();
            $table->dateTime('meet_time')->nullable();
            $table->unsignedSmallInteger('status_bit')->default(0);
            $table->timestamps();

            $table->index(['coach_id', 'client_id', 'meet_time'], 'idx_users_meet');
            $table->foreign('coach_id')->references('id')->on('users');
            $table->foreign('client_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consultations');
    }
};
