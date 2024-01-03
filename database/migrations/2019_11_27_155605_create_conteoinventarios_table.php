<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConteoinventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conteoinventarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Lote_id')->nullable()->unsigned()->index();
            $table->foreign('Lote_id')->references('id')->on('Lotes');
            $table->bigInteger('UserCrea_id')->unsigned()->index();
            $table->foreign('UserCrea_id')->references('id')->on('Users');            
            $table->bigInteger('Estado_id')->default('1')->unsigned()->index();
            $table->foreign('Estado_id')->references('id')->on('Estados');
            $table->bigInteger('Conteo1')->nullable();
            $table->bigInteger('Conteo2')->nullable();
            $table->bigInteger('Value1')->nullable();
            $table->bigInteger('Conteo3')->nullable();
            $table->bigInteger('Conteo4')->nullable();            
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
        Schema::dropIfExists('conteoinventarios');
    }
}
