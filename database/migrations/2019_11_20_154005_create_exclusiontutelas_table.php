<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExclusiontutelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exclusiontutelas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Tutela_id')->unsigned()->index();
            $table->foreign('Tutela_id')->references('id')->on('Tutelas');
            $table->string('Nombre');
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
        Schema::dropIfExists('exclusiontutelas');
    }
}
