<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmpresasstToSaludocupacionals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saludocupacionals', function (Blueprint $table) {
            $table->string('nombre_de_la_empresa')->nullable();
            $table->string('cargo')->nullable();
            $table->string('antiguedad')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saludocupacionals', function (Blueprint $table) {
            $table->string('nombre_de_la_empresa')->nullable();
            $table->string('cargo')->nullable();
            $table->string('antiguedad')->nullable();
        });
    }
}
