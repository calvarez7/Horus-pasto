<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApgarFamiliarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apgar_familiars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('ayuda_familia')->nullable();
            $table->integer('valor_ayuda_familia')->nullable();
            $table->boolean('familia_habla_con_usted')->nullable();
            $table->integer('valor_familia_habla_con_usted')->nullable();
            $table->boolean('cosas_nuevas')->nullable();
            $table->integer('valor_cosas_nuevas')->nullable();
            $table->boolean('le_gusta_familia_hace')->nullable();
            $table->integer('valor_le_gusta_familia_hace')->nullable();
            $table->boolean('le_gusta_familia_comparte')->nullable();
            $table->integer('valor_le_gusta_familia_comparte')->nullable();
            $table->integer('resultado')->nullable();
            $table->bigInteger('usuario_id')->unsigned()->index();
            $table->foreign('usuario_id')->references('id')->on('Users');
            $table->bigInteger('paciente_id')->unsigned()->index();
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->bigInteger('citapaciente_id')->unsigned()->index();
            $table->foreign('citapaciente_id')->references('id')->on('cita_paciente');
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
        Schema::dropIfExists('apgar_familiars');
    }
}
