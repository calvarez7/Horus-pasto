<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDescripcionPacientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('descripcion_pacientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('paciente_id')->nullable()->unsigned()->index();
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->string('Region')->nullable();
            $table->string('Ut')->nullable();
            $table->string('Primer_Nom')->nullable();
            $table->string('SegundoNom')->nullable();
            $table->string('Primer_Ape')->nullable();
            $table->string('Segundo_Ape')->nullable();
            $table->string('Tipo_Doc')->nullable();
            $table->string('Num_Doc')->nullable();
            $table->string('Sexo')->nullable();
            $table->string('Fecha_Afiliado')->nullable();
            $table->string('Fecha_Naci')->nullable();
            $table->string('Edad_Cumplida')->nullable();
            $table->string('Discapacidad')->nullable();
            $table->string('Grado_Discapacidad')->nullable();
            $table->string('Tipo_Afiliado')->nullable();
            $table->string('Orden_Judicial')->nullable();
            $table->string('Num_Folio')->nullable();
            $table->string('Fecha_Emision')->nullable();
            $table->string('Parentesco')->nullable();
            $table->string('TipoDoc_Cotizante')->nullable();
            $table->string('Doc_Cotizante')->nullable();
            $table->string('Tipo_Cotizante')->nullable();
            $table->string('Estado_Afiliado')->nullable();
            $table->string('Dane_Mpio')->nullable();
            $table->string('Mpio_Afiliado')->nullable();
            $table->string('Dane_Dpto')->nullable();
            $table->string('Departamento')->nullable();
            $table->string('Subregion')->nullable();
            $table->string('Dpto_Atencion')->nullable();
            $table->string('Mpio_Atencion')->nullable();
            $table->string('IPS')->nullable();
            $table->string('Sede_Odontologica')->nullable();
            $table->string('Medicofamilia')->nullable();
            $table->string('Etnia')->nullable();
            $table->string('Nivel_Sisben')->nullable();
            $table->string('Laboraen')->nullable();
            $table->string('Mpio_Labora')->nullable();
            $table->string('Direccion_Residencia')->nullable();
            $table->string('Mpio_Residencia')->nullable();
            $table->string('Telefono')->nullable();
            $table->string('Correo1')->nullable();
            $table->string('Correo2')->nullable();
            $table->string('Estrato')->nullable();
            $table->string('Celular1')->nullable();
            $table->string('Celular2')->nullable();
            $table->string('Sexo_Biologico')->nullable();
            $table->string('Tipo_Regimen')->nullable();
            $table->string('Num_Hijos')->nullable();
            $table->string('Vivecon')->nullable();
            $table->string('RH')->nullable();
            $table->string('Tienetutela')->nullable();
            $table->string('Nivelestudio')->nullable();
            $table->string('Nombreacompanante')->nullable();
            $table->string('Telefonoacompanante')->nullable();
            $table->string('Nombreresponsable')->nullable();
            $table->string('Telefonoresponsable')->nullable();
            $table->string('Aseguradora')->nullable();
            $table->string('Tipovinculacion')->nullable();
            $table->string('Ocupacion')->nullable();
            $table->string('nivel')->nullable();
            $table->string('vlr_upc')->nullable();
            $table->string('fecha_ini_cont')->nullable();
            $table->string('fecha_fin_cont')->nullable();
            $table->string('valor_cont_cap')->nullable();
            $table->string('valot_cont_pyp')->nullable();
            $table->string('sem_cot')->nullable();
            $table->string('tipo_categoria')->nullable();
            $table->string('ut_saliente')->nullable();
            $table->string('estado_civil')->nullable();
            $table->string('dx')->nullable();
            $table->string('cups')->nullable();
            $table->string('cums')->nullable();
            $table->string('propios')->nullable();
            $table->string('f_solicitud')->nullable();
            $table->string('anexo')->nullable();
            $table->string('ruta')->nullable();
            $table->string('represa')->nullable();
            $table->string('justifica_represa')->nullable();
            $table->string('observacion_contratista')->nullable();
            $table->string('cargo_laboral')->nullable();
            $table->string('composicion_familiar')->nullable();
            $table->string('vivienda')->nullable();
            $table->string('personas_a_cargo')->nullable();
            $table->string('tipo_contratacion')->nullable();
            $table->string('antiguedad_en_empresa')->nullable();
            $table->string('antiguedad_cargo_actual')->nullable();
            $table->string('numero_cursos_a_cargo')->nullable();
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
        Schema::dropIfExists('descripcion_pacientes');
    }
}
