<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrdenComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordencompras', function (Blueprint $table) {
            $table->bigInteger('detallearticulo_id')->nullable()->unsigned()->index();
            $table->foreign('detallearticulo_id')->references('id')->on('detallearticulos');
            $table->bigInteger('solicitudcompra_id')->nullable()->unsigned()->index();
            $table->foreign('solicitudcompra_id')->references('id')->on('solicitud_compras');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordencompras', function (Blueprint $table) {
            //
        });
    }
}
