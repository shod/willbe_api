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
        Schema::create('session_storage_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id');
            $table->enum('storage', ['stuff', 'memo'])->default('memo');
            $table->enum('type', ['text', 'link', 'file'])->default('text');
            $table->enum('role', ['client', 'coach'])->default('client');
            $table->string('name', 100);
            $table->text('text')->nullable();
            $table->string('url', 1024)->nullable();
            $table->timestamps();

            $table->foreign('session_id')->references('id')->on('sessions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('session_storage_infos');
    }
};
