<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Messages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('message',2500)->nullable();
            $table->string('Archivo',2500)->nullable();
            $table->bigInteger('User_id')->nullable()->unsigned()->index();
            $table->foreign('User_id')->references('id')->on('users');
            $table->bigInteger('Bitacora_id')->nullable()->unsigned()->index();
            $table->foreign('Bitacora_id')->references('id')->on('bitacoras');
            $table->bigInteger('Estado_id')->default(1)->unsigned()->index();
            $table->foreign('Estado_id')->references('id')->on('estados');
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
        Schema::dropIfExists('messages');
    }
}
