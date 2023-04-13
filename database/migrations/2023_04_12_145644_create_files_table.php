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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('type', 16);
            $table->string('name', 64);
            $table->string('path', 100)->default('tmp');
            $table->integer('size')->unsigned()->default(0);
            $table->bigInteger('object_id')->nullable();
            $table->timestamps();

            $table->unique('name', 'udx_file_name');
            $table->index(['type', 'name'], 'idx_file_typename');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
};
