<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteConstrainUsuarioIdToFacturas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropForeign(['usuario_id']);
            $table->dropUnique('facturas_usuario_id_index');
            $table->foreign('usuario_id')->references('id')->on('empleados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropForeign(['usuario_id']);
            $table->dropUnique('facturas_usuario_id_index');
            $table->foreign('usuario_id')->references('id')->on('empleados');
        });
    }
}
