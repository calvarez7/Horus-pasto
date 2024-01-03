<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTutelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tutelas', function (Blueprint $table) {
            $table->dropIndex('tutelas_rol_id_index');
            $table->dropForeign('tutelas_rol_id_foreign');
            $table->dropColumn('Rol_id');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tutelas', function (Blueprint $table) {
            $table->dropIndex('tutelas_rol_id_index');
            $table->dropForeign('tutelas_rol_id_foreign');
            $table->dropColumn('Rol_id');
        });
    }
}
