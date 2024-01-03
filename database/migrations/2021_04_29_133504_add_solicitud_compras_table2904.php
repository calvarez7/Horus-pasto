<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSolicitudComprasTable2904 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solicitud_compras', function (Blueprint $table) {
            $table->bigInteger('sedeproveedore_id')->nullable()->unsigned()->index();
            $table->foreign('sedeproveedore_id')->references('id')->on('sedeproveedores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solicitud_compras', function (Blueprint $table) {
            $table->bigInteger('sedeproveedore_id')->nullable()->unsigned()->index();
            $table->foreign('sedeproveedore_id')->references('id')->on('sedeproveedores');
        });
    }
}
