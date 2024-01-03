<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstadosToRegistroconcurrencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registroconcurrencias', function (Blueprint $table) {
            $table->bigInteger('estado_id')->default(1)->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('estados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('registroconcurrencias', function (Blueprint $table) {
            $table->bigInteger('estado_id')->default(1)->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('estados');
        });
    }
}
