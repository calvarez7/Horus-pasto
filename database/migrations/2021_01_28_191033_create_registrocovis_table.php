<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrocovisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrocovis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cita_paciente_id')->unsigned()->index();
            $table->foreign('cita_paciente_id')->references('id')->on('cita_paciente');
            $table->string('medida_edad');
            $table->string('tipo_vivienda')->nullable();
            $table->string('tipo_habitacion')->nullable();
            $table->string('tipo_contacto')->nullable();
            $table->string('reportar_contactos')->nullable();
            $table->string('nombre_cuidador')->nullable();
            $table->string('documento_cuidador')->nullable();
            $table->string('aseguradora_cuidador')->nullable();
            $table->bigInteger('municipio_cuidador_id')->nullable()->unsigned()->index();
            $table->foreign('municipio_cuidador_id')->references('id')->on('Municipios');
            $table->string('direccion_cuidador')->nullable();
            $table->string('telefono_cuidador')->nullable();
            $table->string('correo_cuidador')->nullable();
            $table->string('parentesco_cuidador')->nullable();
            $table->string('cumplir_aislamiento')->nullable();
            $table->string('contactado')->nullable();
            $table->string('vacunado_influenza')->nullable();
            $table->string('utilizado_antibioticos')->nullable();
            $table->string('fecha_inicio_aislamiento')->nullable();
            $table->string('presenta_discapacidades')->nullable();
            $table->string('presenta_impedimento_aislamiento_domi')->nullable();
            $table->string('clasificacion_resolucion_521')->nullable();
            $table->bigInteger('user_asignado_id')->nullable()->unsigned()->index();
            $table->foreign('user_asignado_id')->references('id')->on('users');
            $table->bigInteger('user_admisiona_id')->nullable()->unsigned()->index();
            $table->foreign('user_admisiona_id')->references('id')->on('users');
            $table->bigInteger('pais_nacionalidad_id')->nullable()->unsigned()->index();
            $table->foreign('pais_nacionalidad_id')->references('id')->on('paises');
            $table->bigInteger('pais_ocurrencia_id')->nullable()->unsigned()->index();
            $table->foreign('pais_ocurrencia_id')->references('id')->on('paises');
            $table->bigInteger('municipio_procedencia_id')->nullable()->unsigned()->index();
            $table->foreign('municipio_procedencia_id')->references('id')->on('Municipios');
            $table->string('area_ocurrencia_caso');
            $table->string('localidad_ocurrencia_caso');
            $table->string('barrio_ocurrencia_caso');
            $table->string('detalle_area_municipal')->nullable();
            $table->string('zona');
            $table->string('ocupacion_paciente');
            $table->string('tipo_regimen_salud');
            $table->string('administradora_planes_beneficios');
            $table->string('pertenencia_etnica');
            $table->string('estrato');
            $table->string('discapacitados')->nullable();
            $table->string('Migrantes')->nullable();
            $table->string('Gestantes')->nullable();
            $table->string('Sem_de_gestacion')->nullable();
            $table->string('desplazados')->nullable();
            $table->string('carcelarios')->nullable();
            $table->string('indigentes')->nullable();
            $table->string('poblacion_infantil_ICBF')->nullable();
            $table->string('madres_comunitarias')->nullable();
            $table->string('desmovilizados')->nullable();
            $table->string('centros_psiquiatricos')->nullable();
            $table->string('victimas_violencia_armada')->nullable();
            $table->string('otros_grupos_poblacionales')->nullable();
            $table->string('fuente');
            $table->bigInteger('municipio_residencia_paciente_id')->nullable()->unsigned()->index();
            $table->foreign('municipio_residencia_paciente_id')->references('id')->on('Municipios');
            $table->string('direccion_residencia');
            $table->date('fecha_consulta');
            $table->date('fecha_inicio_sintomas');
            $table->string('clasificacion');
            $table->string('hospitalizado')->nullable();
            $table->date('fecha_hospitalizacion')->nullable();
            $table->string('condicion_final')->nullable();
            $table->date('fecha_defuncion')->nullable();
            $table->string('numero_certificado_defuncion')->nullable();
            $table->string('causa_basica_muerte')->nullable();
            $table->string('nombre_profesional');
            $table->string('telefono');
            $table->bigInteger('estado_id')->default(1)->unsigned()->index();
            $table->foreign('estado_id')->references('id')->on('estados');
            $table->bigInteger('usuario_seguimiento_id')->nullable()->unsigned()->index();
            $table->foreign('usuario_seguimiento_id')->references('id')->on('users');


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
        Schema::dropIfExists('registrocovis');
    }
}
