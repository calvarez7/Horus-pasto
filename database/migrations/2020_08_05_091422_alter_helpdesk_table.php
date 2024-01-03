<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterHelpdeskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('helpdesks', function (Blueprint $table) {
            $table->bigInteger('Actividad_id')->nullable()->change();
            $table->string('Cargo_user')->nullable()->change();
            $table->string('Requerimiento')->nullable()->change();
            $table->bigInteger('area_id')->nullable()->unsigned()->index();
            $table->foreign('area_id')->references('id')->on('areas_help');
            $table->bigInteger('categoria_id')->nullable()->unsigned()->index();
            $table->foreign('categoria_id')->references('id')->on('categorias_help');
            $table->string('prioridad')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('helpdesks', function (Blueprint $table) {
            $table->bigInteger('Actividad_id')->nullable()->change();
            $table->string('Cargo_user')->nullable()->change();
            $table->string('Requerimiento')->nullable()->change();
            $table->bigInteger('area_id')->nullable()->unsigned()->index();
            $table->foreign('area_id')->references('id')->on('areas_help');
            $table->bigInteger('categoria_id')->nullable()->unsigned()->index();
            $table->foreign('categoria_id')->references('id')->on('categorias_help');
            $table->string('prioridad')->nullable();
        });
    }
}
