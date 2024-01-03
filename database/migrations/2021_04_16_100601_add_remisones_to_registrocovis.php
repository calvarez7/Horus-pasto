<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemisonesToRegistrocovis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registrocovis', function (Blueprint $table) {
            $table->string('requiere_prueba')->nullable();
            $table->string('tipo_prueba')->nullable();
            $table->string('modalida_prueba')->nullable();
            $table->string('destino_paciente')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('registrocovis', function (Blueprint $table) {
            $table->string('requiere_prueba')->nullable();
            $table->string('tipo_prueba')->nullable();
            $table->string('modalida_prueba')->nullable();
            $table->string('destino_paciente')->nullable();
        });
    }
}
