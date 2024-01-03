<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEventosAdversosTable0208 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('eventos_adversos', function (Blueprint $table) {
            $table->string('red')->nullable();
            $table->string('servicio_ocurrencia')->nullable();
            $table->string('servicio_reportante')->nullable();
            $table->string('otro_evento')->nullable();
            $table->string('lote_dispositivo')->nullable();
            $table->string('priorizacion')->nullable();
            $table->integer('probabilidad')->nullable();
            $table->integer('impacto')->nullable();
            $table->integer('previsibilidad')->nullable();
            $table->string('nivel_priorizacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('eventos_adversos', function (Blueprint $table) {
            $table->string('red')->nullable();
            $table->string('servicio_ocurrencia')->nullable();
            $table->string('servicio_reportante')->nullable();
            $table->string('otro_evento')->nullable();
            $table->string('lote_dispositivo')->nullable();
        });
    }
}
