<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddObservacionCierreToBarreraAccesos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barrera_accesos', function (Blueprint $table) {
            $table->string('observacion_cierre')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barrera_accesos', function (Blueprint $table) {
            $table->string('observacion_cierre')->nullable();
        });
    }
}
