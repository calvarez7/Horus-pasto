<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'cita'], function () {
    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function () {
        Route::post('create', 'Citas\CitaController@store');
        Route::get('all', 'Citas\CitaController@all');
        Route::get('enabled', 'Citas\CitaController@enabled');
        Route::post('citasanteriores', 'Citas\CitaController@citasanterioresPaciente');
        Route::post('citaspendientes', 'Citas\CitaController@citaspendientesPaciente');
        Route::get('exportAppointment', 'Citas\CitaController@exportAppointmentsByDateRange');
        Route::put('asignarcita/{cita}', 'Citas\CitaController@asignarcita');
        Route::put('edit/{cita}', 'Citas\CitaController@update');
        Route::put('cancelar/{cita}', 'Citas\CitaController@cancelar');
        Route::put('bloquear/{cita}', 'Citas\CitaController@bloquearCita');
        Route::put('confirmar/{cita}', 'Citas\CitaController@confirmar');
        Route::get('show/{cita}', 'Citas\CitaController@show');
        Route::post('cronogramahoy', 'Citas\CitaController@cronogramaHoyMedico');
        Route::put('available/{cita}', 'Citas\CitaController@available');
        Route::get('notProgramed', 'Citas\CitaController@notProgramed');
        Route::post('checkEspecialidad', 'Citas\CitaController@checkEspecialidad');
        Route::post('citaOrdenPaciente/{paciente}', 'Citas\CitaController@citaOrdenPaciente');

        //Inicio de Historia clinica en la atención del paciente
        Route::post('{citapaciente}/atender', 'Citas\CitaController@atender');
        Route::put('inasistio/{cita}', 'Citas\CitaController@inasistio');
        Route::post('{citapaciente}/motivo', 'Citas\CitaController@motivo');
        Route::get('{citapaciente}/motivo', 'Citas\CitaController@anamnesis');
        Route::post('{citapaciente}/revisionsistema', 'Citas\CitaController@revisionsistema');
        Route::get('{citapaciente}/datospaciente', 'Citas\CitaController@datospaciente');
        Route::put('{paciente}/editarpaciente/{citapaciente}', 'Citas\CitaController@editarpaciente');
        Route::post('/updatepaciente/{paciente}', 'Citas\CitaController@updatepaciente');
        Route::post('{citapaciente}/setTime', 'Citas\CitaController@setTime');
        Route::post('{citapaciente}/getTime', 'Citas\CitaController@getTimeIngreso');
        Route::put('update-state-consulta/{citapaciente}', 'Citas\CitaController@update_state_consulta');
        Route::put('update-state-atendida/{citapaciente}', 'Citas\CitaController@update_state_atendida');

        //Paciente
        Route::post('create_cita_paciente/{paciente}', 'Citas\CitaController@create_cita_paciente');
        Route::get('cupspaciente/{paciente}', 'Citas\CitaController@cupspaciente');

        //Imagenologia
        Route::post('admisiones', 'Citas\CitaController@admisionesimagenes');
        Route::post('observacion', 'Citas\CitaController@observacionImagenologia');
        Route::post('adjuntoAdmision', 'Citas\CitaController@adjuntoAdmision');
        Route::get('historia/validacion/{citaPaciente}','Citas\CitaController@validacionHistoria');

        //Oftamologia
        Route::post('saveOftamologia/{citaPaciente}', 'Citas\CitaController@saveOftamologia');
        Route::post('getoftamologia/{citaPaciente}', 'Citas\CitaController@getoftamologia');

        //Salud Ocupacional
        Route::post('saveSaludocupacional/{citaPaciente}', 'Citas\CitaController@saveSaludocupacional');
        Route::post('getSaludocupacional/{citaPaciente}', 'Citas\CitaController@getSaludocupacional');
        Route::post('saveAntecedentesOpucacionales/{citaPaciente}', 'Citas\CitaController@saveAntecedentesOpucacionales');
        Route::post('getAntecedentesOpucacionales/{citaPaciente}', 'Citas\CitaController@getAntecedentesOpucacionales');

        // Firma y certificado del paciente OCUPACIONAL
        Route::post('firmaPaciente/{citaPaciente}', 'Citas\CitaController@firmaPaciente');
        Route::post('certificado', 'Citas\CitaController@certificado');
        Route::post('historiaOcupacional', 'Citas\CitaController@historiaOcupacional');

        // nuevos de historia
        Route::post('antecedenteFarmacologico', 'Citas\CitaController@antecedenteFarmacologico');
        Route::post('antecedenteToxico', 'Citas\CitaController@antecedenteToxico');
        Route::post('farmaco/{id}', 'Citas\CitaController@farmaco');

        //demanda_insatisfechas
        Route::post('agregarDemanda', 'Citas\CitaController@agregarDemanda');
        Route::post('agregarCierreDemanda', 'Citas\CitaController@agregarCierreDemanda');
        Route::get('listarDemanda/{id}', 'Citas\CitaController@listarDemanda');
    });

    /** Cancela una cita */
    Route::get('listar', 'Citas\CitaController@consultarCitas')->middleware(['token.api', 'cita.tipo']);
    Route::get('listarConsultasWhatsApp', 'Citas\CitaController@listarConsultasWhatsApp')->middleware('token.api');
    Route::get('consultar/{cita_paciente}', 'Citas\CitaController@consultar')->middleware(['token.api']);
    Route::post('firmar-consentimiento/{cita_paciente}/{documento}', 'Citas\CitaController@firmarConsentimiento')->middleware(['token.api']);
    Route::post('modificar', 'Citas\CitaController@modificar')->middleware(['token.api']);
});
