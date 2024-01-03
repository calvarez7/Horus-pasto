<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCupordensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cupordens', function (Blueprint $table) {
            $table->date('fecha_solicitada')->nullable();
            $table->date('fecha_sugerida')->nullable();
            $table->date('fecha_cancelacion')->nullable();
            $table->string('cancelacion')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('responsable')->nullable();
            $table->string('motivo')->nullable();
            $table->string('causa')->nullable();
            $table->json('soportes')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cupordens', function (Blueprint $table) {
            $table->date('fecha_solicitada')->nullable();
            $table->date('fecha_sugerida')->nullable();
            $table->date('fecha_cancelacion')->nullable();
            $table->string('cancelacion')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('responsable')->nullable();
            $table->string('motivo')->nullable();
            $table->string('causa')->nullable();
            $table->json('soportes')->nullable();

        });
    }
}
