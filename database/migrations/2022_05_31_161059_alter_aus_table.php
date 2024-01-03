<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aus', function (Blueprint $table) {
            $table->string('numero_documento')->nullable();
            $table->string('tipo_documento')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aus', function (Blueprint $table) {
            $table->string('numero_documento')->nullable();
            $table->string('tipo_documento')->nullable();
        });
    }
}
