<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetallearticulospqrsfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detallearticulospqrsf', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Detallearticulo_id')->unsigned()->index();
            $table->foreign('Detallearticulo_id')->references('id')->on('Detallearticulos');            
            $table->bigInteger('Pqrsf_id')->unsigned()->index();
            $table->foreign('Pqrsf_id')->references('id')->on('Pqrsfs');
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
        Schema::dropIfExists('detallearticulospqrsf');
    }
}
