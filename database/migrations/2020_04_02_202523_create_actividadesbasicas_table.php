<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActividadesbasicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actividadesbasicas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Estado_id')->default(1)->unsigned()->index();
            $table->foreign('Estado_id')->references('id')->on('Estados');
            $table->string('nombre')->nullable();
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
        Schema::dropIfExists('actividadesbasicas');
    }
}
