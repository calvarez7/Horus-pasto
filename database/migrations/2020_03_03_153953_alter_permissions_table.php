<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->bigInteger('Tipo_id')->nullable()->unsigned()->index();
            $table->foreign('Tipo_id')->references('id')->on('Tipos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->bigInteger('Tipo_id')->nullable()->unsigned()->index();
            $table->foreign('Tipo_id')->references('id')->on('Tipos');
        });
    }
}
