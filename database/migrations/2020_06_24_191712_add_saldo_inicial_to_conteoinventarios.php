<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSaldoInicialToConteoinventarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conteoinventarios', function (Blueprint $table) {
            $table->bigInteger('saldo_inicial')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conteoinventarios', function (Blueprint $table) {
            $table->bigInteger('saldo_inicial')->nullable();
        });
    }
}
