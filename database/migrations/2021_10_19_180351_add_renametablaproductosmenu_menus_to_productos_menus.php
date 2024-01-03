<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRenametablaproductosmenuMenusToProductosMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('productos_menus', 'ciclo_menu_productos');

        Schema::table('ciclo_menu_productos', function (Blueprint $table) {
            $table->bigInteger('ciclo_menu_id')->unsigned()->nullable()->index();
            $table->foreign('ciclo_menu_id')->references('id')->on('ciclos_menus');

            $table->bigInteger('producto_id')->unsigned()->nullable()->index();
            $table->foreign('producto_id')->references('id')->on('productos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('productos_menus', 'ciclo_menu_productos');

        Schema::table('ciclo_menu_productos', function (Blueprint $table) {

            $table->bigInteger('ciclo_menu_id')->unsigned()->nullable()->index();
            $table->foreign('ciclo_menu_id')->references('id')->on('ciclos_menus');

            $table->bigInteger('producto_id')->unsigned()->nullable()->index();
            $table->foreign('producto_id')->references('id')->on('productos');
        });
    }
}
