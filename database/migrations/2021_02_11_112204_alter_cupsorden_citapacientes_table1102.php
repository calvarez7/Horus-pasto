<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCupsordenCitapacientesTable1102 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cupsorden_citapacientes', function (Blueprint $table) {
            $table->dropIndex('cupsorden_citapacientes_cup_id_index');
            $table->dropForeign('cupsorden_citapacientes_cup_id_foreign');
            $table->dropColumn('cup_id');
            $table->dropIndex('cupsorden_citapacientes_orden_id_index');
            $table->dropForeign('cupsorden_citapacientes_orden_id_foreign');
            $table->dropColumn('orden_id');
            $table->bigInteger('cupordens_id')->unsigned()->index();
            $table->foreign('cupordens_id')->references('id')->on('cupordens');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cupsorden_citapacientes', function (Blueprint $table) {
            $table->dropIndex('cupsorden_citapacientes_cup_id_index');
            $table->dropForeign('cupsorden_citapacientes_cup_id_foreign');
            $table->dropColumn('cup_id');
            $table->dropIndex('cupsorden_citapacientes_orden_id_index');
            $table->dropForeign('cupsorden_citapacientes_orden_id_foreign');
            $table->dropColumn('orden_id');
            $table->bigInteger('cupordens_id')->unsigned()->index();
            $table->foreign('cupordens_id')->references('id')->on('cupordens');
        });
    }
}
