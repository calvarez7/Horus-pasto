<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTarifariosDeletesedeproTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tarifarios', function (Blueprint $table) {
            $table->dropIndex('tarifarios_sedeproveedor_id_index');
            $table->dropForeign('tarifarios_sedeproveedor_id_foreign');
            $table->dropColumn('Sedeproveedor_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tarifarios', function (Blueprint $table) {
            $table->dropIndex('tarifarios_sedeproveedor_id_index');
            $table->dropForeign('tarifarios_sedeproveedor_id_foreign');
            $table->dropColumn('Sedeproveedor_id');
        });
    }
}
