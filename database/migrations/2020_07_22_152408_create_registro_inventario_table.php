<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistroInventarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registro_inventarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('inventario_id')->nullable()->unsigned()->index();
            $table->foreign('inventario_id')->references('id')->on('inventarios');
            $table->string('tipo');
            $table->integer('cantidad');
            $table->integer('valor');
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
        Schema::dropIfExists('registro_inventarios');
    }
}
