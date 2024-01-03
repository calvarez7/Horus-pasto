<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSeguimientoConcurrenciaTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seguimientoconcurrencias', function (Blueprint $table) {
            $table->dropIndex('seguimientoconcurrencias_user_responsable_seguimiento_index');
            $table->dropForeign('seguimientoconcurrencias_user_responsable_seguimiento_foreign');
            $table->dropColumn('user_responsable_seguimiento');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seguimientoconcurrencias', function (Blueprint $table) {
            $table->dropIndex('seguimientoconcurrencias_user_responsable_seguimiento_index');
            $table->dropForeign('seguimientoconcurrencias_user_responsable_seguimiento_foreign');
            $table->dropColumn('user_responsable_seguimiento');
        });
    }
}
