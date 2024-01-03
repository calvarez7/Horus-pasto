<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRadicacionGlosaSumiTable1901 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('radicacion_sumi_glosas', function (Blueprint $table) {
            $table->text('acepta_ips')->nullable();
            $table->text('levanta_sumi')->nullable();
            $table->text('no_acuerdo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('radicacion_sumi_glosas', function (Blueprint $table) {
            $table->text('acepta_ips')->nullable();
            $table->text('levanta_sumi')->nullable();
            $table->text('no_acuerdo')->nullable();
        });
    }
}
