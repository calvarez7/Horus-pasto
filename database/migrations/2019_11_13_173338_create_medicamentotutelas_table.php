<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicamentotutelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicamentotutelas', function (Blueprint $table) {
            $table->bigIncrements('id');            
            $table->bigInteger('Detallearticulo_id')->unsigned()->index();
            $table->foreign('Detallearticulo_id')->references('id')->on('Detallearticulos');            
            $table->bigInteger('Tutela_id')->unsigned()->index();
            $table->foreign('Tutela_id')->references('id')->on('Tutelas');
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
        Schema::dropIfExists('medicamentotutelas');
    }
}
