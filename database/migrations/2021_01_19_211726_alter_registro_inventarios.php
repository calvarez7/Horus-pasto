<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRegistroInventarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registro_inventarios', function (Blueprint $table) {
            $table->float('iva')->nullable();
            $table->integer('telefono')->nullable();
            $table->text('municipio')->nullable();
            $table->text('correo')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('registro_inventarios', function (Blueprint $table) {
            $table->float('iva')->nullable();
            $table->integer('telefono')->nullable();
            $table->text('municipio')->nullable();
            $table->text('correo')->nullable();

        });
    }
}
