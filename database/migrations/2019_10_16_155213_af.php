<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Af extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('afs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('paq_id')->unsigned()->index();
            $table->foreign('paq_id')->references('id')->on('paq_rips')->Ondelete('cascade');
            $table->string('tipo_identificacion');
            $table->string('numero_identificacion');
            $table->string('numero_factura');
            $table->string('fechaexpedicion_factura');
            $table->string('fecha_inicio');
            $table->string('fecha_final');
            $table->string('codigo_entidad')->default('RES004');
            $table->string('Nombre_Admin');
            $table->string('Numero_Contracto')->nullable();
            $table->string('Plan_Beneficios')->nullable();
            $table->string('Numero_Poliza')->nullable();
            $table->string('Valor_copago')->nullable();
            $table->string('Valor_comision')->nullable();
            $table->string('valor_Descuento')->nullable();
            $table->string('valor_Neto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('afs');
    }
}
