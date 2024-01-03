<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTutelasTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tutelas', function (Blueprint $table) {
            $table->bigInteger('User_id')->unsigned()->index();
            $table->foreign('User_id')->references('id')->on('Users');
            $table->bigInteger('Paciente_id')->unsigned()->index();
            $table->foreign('Paciente_id')->references('id')->on('Pacientes');
            $table->bigInteger('Municipio_id')->unsigned()->index();
            $table->foreign('Municipio_id')->references('id')->on('Municipios');
            $table->bigInteger('Juzgado_id')->unsigned()->index();
            $table->foreign('Juzgado_id')->references('id')->on('Jusgados');
            $table->bigInteger('Tipo_requerimiento_id')->unsigned()->index();
            $table->foreign('Tipo_requerimiento_id')->references('id')->on('Tiporequerimientos');
            $table->bigInteger('Estado_id')->default(18)->unsigned()->index();
            $table->foreign('Estado_id')->references('id')->on('Estados');
            $table->bigInteger('Quienrea_id')->nullable()->unsigned()->index();
            $table->foreign('Quienrea_id')->references('id')->on('Users');
            $table->text('Motivoreasignar')->nullable();
            $table->date('Fecharea')->nullable();
            $table->string('Direccion');
            $table->string('Telefono');
            $table->string('Radicado');
            $table->string('Exclusion');
            $table->string('Integralidad');
            $table->string('New_conti');
            $table->string('Novedadregistro')->nullable();
            $table->string('gestiondocumental')->nullable();
            $table->string('Medicinalaboral')->nullable();
            $table->string('Reembolso')->nullable();
            $table->string('Transporte')->nullable();
            $table->date('Fecha_radica');
            $table->text('Observacion');
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
            $table->bigInteger('User_id')->unsigned()->index();
            $table->foreign('User_id')->references('id')->on('Users');
            $table->bigInteger('Paciente_id')->unsigned()->index();
            $table->foreign('Paciente_id')->references('id')->on('Pacientes');
            $table->Integer('Rol_id')->unsigned()->index();
            $table->foreign('Rol_id')->references('id')->on('Roles');
            $table->bigInteger('Municipio_id')->unsigned()->index();
            $table->foreign('Municipio_id')->references('id')->on('Municipios');
            $table->bigInteger('Juzgado_id')->unsigned()->index();
            $table->foreign('Juzgado_id')->references('id')->on('Jusgados');
            $table->bigInteger('Tipo_requerimiento_id')->unsigned()->index();
            $table->foreign('Tipo_requerimiento_id')->references('id')->on('Tiporequerimientos');
            $table->bigInteger('Estado_id')->default(18)->unsigned()->index();
            $table->foreign('Estado_id')->references('id')->on('Estados');
            $table->string('Direccion');
            $table->string('Telefono');
            $table->string('Radicado');
            $table->string('Exclusion');
            $table->string('Integralidad');
            $table->string('New_conti');
            $table->string('Novedadregistro')->nullable();
            $table->string('gestiondocumental')->nullable();
            $table->string('Medicinalaboral')->nullable();
            $table->string('Reembolso')->nullable();
            $table->string('Transporte')->nullable();
            $table->date('Fecha_radica');
            $table->text('Observacion');
        });
    }
}
