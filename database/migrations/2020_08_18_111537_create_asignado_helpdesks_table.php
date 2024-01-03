<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsignadoHelpdesksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignado_helpdesks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->Integer('permission_id')->unsigned()->index();
            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->bigInteger('helpdesk_id')->unsigned()->index();
            $table->foreign('helpdesk_id')->references('id')->on('helpdesks');
            $table->bigInteger('estado_id')->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asignado_helpdesks');
    }
}
