<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Referencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referencias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_paci')->unsigned()->index();
            $table->foreign('id_paci')->references('id')->on('pacientes');
            $table->bigInteger('id_prestador')->unsigned()->index();
            $table->foreign('id_prestador')->references('id')->on('users');
            $table->string('tipo_anexo')->nullable();
            $table->string("triage")->nullable();
            $table->string("hora_urgencia")->nullable();
            $table->string("nom_prest")->nullable();
            $table->string("usuario_remitido")->nullable();
            $table->string("habi_remitido")->nullable();
            $table->string("dpto_remitido")->nullable();
            $table->string("muni_remitido")->nullable();
            $table->string("origen_atencion")->nullable();
            $table->string("enti_solicita")->nullable();
            $table->string("des_paci")->nullable();
            $table->string("text1")->nullable();
            $table->string("Consulta_Externa")->nullable();
            $table->string("servi_hosp")->nullable();
            $table->string("cama_ubipaci")->nullable();
            $table->string("cod_habi")->nullable();
            $table->string("dpto_hospitalizacion")->nullable();
            $table->string("muni_hospitalizacion")->nullable();
            $table->string("ori_atencion")->nullable();
            $table->string("giagnostico_hospi")->nullable();
            $table->string("priori_atencion")->nullable();
            $table->string("tipo_servicio")->nullable();
            $table->string("cantidad")->nullable();
            $table->string("cup")->nullable();
            $table->string("text2")->nullable();
            $table->string("ubi_paci_remi")->nullable();
            $table->string("Prioridad_remi")->nullable();
            $table->string("Complejidad_remi")->nullable();
            $table->string("Especialidad_remi")->nullable();
            $table->string("TBC_remi")->nullable();
            $table->string("tbc")->nullable();
            $table->string("Aislamiento")->nullable();
            $table->string("Glasglow")->nullable();
            $table->string("num_glasglow")->nullable();
            $table->string("presion_sistolica")->nullable();
            $table->string("presion_diastolica")->nullable();
            $table->string("fre_cardiaca")->nullable();
            $table->string("fre_respiratoria")->nullable();
            $table->string("ambulancia")->nullable();
            $table->string("req_oxigeno")->nullable();
            $table->string("text3")->nullable();
            $table->string("medico_Soli")->nullable();
            $table->string("Espe_Médico_Soli")->nullable();
            $table->string("Id_medico_Soli")->nullable();
            $table->string("Re_medico_Soli")->nullable();
            $table->string("tipoid_User_Repo")->nullable();
            $table->string("idusers_reporta")->nullable();
            $table->string("nom_user_report")->nullable();
            $table->string("cargo_user_report")->nullable();
            $table->string("tel_user_report")->nullable();
            $table->string("cel_user_report")->nullable();
            $table->string("adjunto")->nullable();
            $table->string("state")->nullable();
            $table->string("rzeuz")->nullable();
            $table->string("tipo_solicitud")->nullable();
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
        Schema::dropIfExists('referencias');
    }
}
