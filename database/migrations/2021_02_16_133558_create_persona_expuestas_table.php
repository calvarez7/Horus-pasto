<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonaExpuestasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persona_expuestas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sarlafs_id')->unsigned()->nullable()->index();
            $table->foreign('sarlafs_id')->references('id')->on('sarlafts');
            $table->string('razon_social')->nullable();
            $table->string('nacionalidad')->nullable();
            $table->string('relacion')->nullable();
            $table->string('entidad')->nullable();
            $table->string('cargo')->nullable();
            $table->bigInteger('estado_id')->default('1')->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('estados');
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
        Schema::dropIfExists('persona_expuestas');
    }
}
