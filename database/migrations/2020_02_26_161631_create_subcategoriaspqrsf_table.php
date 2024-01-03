<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubcategoriaspqrsfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subcategoriaspqrsf', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Subcategoria_id')->unsigned()->index();
            $table->foreign('Subcategoria_id')->references('id')->on('Subcategorias');            
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
        Schema::dropIfExists('subcategoriaspqrsf');
    }
}
