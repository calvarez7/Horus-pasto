<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEliminarcolumnaToProductosMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('productos_menus', function (Blueprint $table) {
            $table->dropForeign('productos_menus_menu_id_foreign');
            $table->dropForeign('productos_menus_producto_id_foreign');
            $table->dropColumn('menu_id');
            $table->dropColumn('producto_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('productos_menus', function (Blueprint $table) {
            $table->dropForeign('productos_menus_menu_id_foreign');
            $table->dropForeign('productos_menus_producto_id_foreign');
            $table->dropColumn('menu_id');
            $table->dropColumn('producto_id');
        });
    }
}
