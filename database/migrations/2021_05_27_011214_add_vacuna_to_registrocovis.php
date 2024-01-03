<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVacunaToRegistrocovis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registrocovis', function (Blueprint $table) {
            $table->string('tipo_vacuna')->nullable();
            $table->string('primera_dosis')->nullable();
            $table->string('segunda_dosis')->nullable();
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
            $table->string('tipo_vacuna')->nullable();
            $table->string('primera_dosis')->nullable();
            $table->string('segunda_dosis')->nullable();
        });
    }
}
