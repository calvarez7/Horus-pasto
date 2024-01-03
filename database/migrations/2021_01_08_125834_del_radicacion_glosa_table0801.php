<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DelRadicacionGlosaTable0801 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('radicacion_glosas', function (Blueprint $table) {
            $table->dropColumn('repuesta_sumimedical');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('radicacion_glosas', function (Blueprint $table) {
            $table->dropColumn('repuesta_sumimedical');
        });
    }
}
