<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGestionsHelpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gestions_help', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('Helpdesk_id')->unsigned()->index();
            $table->foreign('Helpdesk_id')->references('id')->on('Helpdesks');
            $table->bigInteger('User_id')->unsigned()->index();
            $table->foreign('User_id')->references('id')->on('Users');
            $table->bigInteger('Tipo_id')->unsigned()->index();
            $table->foreign('Tipo_id')->references('id')->on('Tipos');
            $table->text('Motivo');
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
        Schema::dropIfExists('gestions_help');
    }
}
