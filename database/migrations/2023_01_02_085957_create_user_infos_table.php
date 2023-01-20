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
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id()->from(1100);
            $table->string('full_name', 512);
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->date('birth_date')->nullable();
            $table->string('user_key', 512)->unique()->index('idx_user_info_key');
            $table->unsignedBigInteger('phone')->nullable();
            $table->string('slug', 1024);
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
        Schema::dropIfExists('user_infos');
    }
};
