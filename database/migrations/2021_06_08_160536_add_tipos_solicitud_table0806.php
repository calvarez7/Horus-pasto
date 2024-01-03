<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTiposSolicitudTable0806 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tipos_solicitud', function (Blueprint $table) {
            $table->string('opcion1')->nullable();
            $table->string('opcion2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tipos_solicitud', function (Blueprint $table) {
            $table->string('opcion1')->nullable();
            $table->string('opcion2')->nullable();
        });
    }
}
