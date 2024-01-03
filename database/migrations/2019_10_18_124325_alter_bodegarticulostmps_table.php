<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBodegarticulostmpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bodegarticulostmps', function (Blueprint $table) {            
            $table->bigInteger('Estado_id')->default('1')->unsigned()->index();
            $table->foreign('Estado_id')->references('id')->on('Estados');
            $table->bigInteger('Usuario_id')->unsigned()->index()->nullable();
            $table->foreign('Usuario_id')->references('id')->on('Users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bodegarticulostmps', function (Blueprint $table) {
            $table->bigInteger('Estado_id')->default('1')->unsigned()->index();
            $table->foreign('Estado_id')->references('id')->on('Estados');
            $table->bigInteger('Usuario_id')->unsigned()->index()->nullable();
            $table->foreign('Usuario_id')->references('id')->on('Users');
        });
    }
}
