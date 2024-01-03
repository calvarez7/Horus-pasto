<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSedesSumimedicalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sedes_sumimedical', function (Blueprint $table) {
            $table->bigInteger('Sedeprestador_id')->nullable()->unsigned()->index();
            $table->foreign('Sedeprestador_id')->references('id')->on('sedeproveedores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sedes_sumimedical', function (Blueprint $table) {
            $table->bigInteger('Sedeprestador_id')->nullable()->unsigned()->index();
            $table->foreign('Sedeprestador_id')->references('id')->on('sedeproveedores');
        });
    }
}
