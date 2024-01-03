<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSociosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sarlafs_id')->unsigned()->nullable()->index();
            $table->foreign('sarlafs_id')->references('id')->on('sarlafts');
            $table->string('razon_social')->nullable();
            $table->string('tipo_doc')->nullable();
            $table->string('num_doc')->nullable();
            $table->string('participacion')->nullable();
            $table->string('descripcion_expuevincula')->nullable();
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
        Schema::dropIfExists('socios');
    }
}
