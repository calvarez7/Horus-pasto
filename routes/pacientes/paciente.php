<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'paciente'], function () {

    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function () {
        Route::post('create', 'Pacientes\PacienteController@store');
        Route::post('guardarPaciente', 'Pacientes\PacienteController@guardarPaciente');
        Route::get('all', 'Pacientes\PacienteController@all');
        Route::get('pacientesEmpalme', 'Pacientes\PacienteController@pacientesEmpalme');
        Route::get('adjuntosPaciente/{cedula}', 'Pacientes\PacienteController@adjuntosPaciente');
        // Route::post('import',         'Pacientes\PacienteController@import');
        Route::put('edit/{paciente}', 'Pacientes\PacienteController@update');
        Route::post('prueba/{paciente}', 'Pacientes\PacienteController@updateUbicacionData');
        Route::put('prueba2/{paciente}', 'Pacientes\PacienteController@update2');
        Route::put('update_email/{paciente}', 'Pacientes\PacienteController@updateEmail');
        Route::put('edit_ubicacion_data/{paciente}', 'Pacientes\PacienteController@update2');
        Route::get('show/{paciente}', 'Pacientes\PacienteController@show');
        Route::get('getPaciente/{cedula}', 'Pacientes\PacienteController@getPaciente');
        Route::get('eventoPaciente/{cedula}', 'Pacientes\PacienteController@eventoPaciente');
        Route::get('showEnabled/{cedula}', 'Pacientes\PacienteController@showEnabled');

        // RUTAS PARA HISTORIA CLINICA
        Route::get('pacienteEnabled/{paciente_id}', 'Pacientes\PacienteController@pacienteEnabled');
        Route::put('updatePaciente', 'Pacientes\PacienteController@updatePaciente');
        Route::get('pacienteRutacovi/{cedula}', 'Pacientes\PacienteController@pacienteRutacovi');
        Route::get('showCaracterizacion/{cedula}', 'Pacientes\PacienteController@showCaracterizacion');
        Route::post('verPaciente/{cedula}', 'Pacientes\PacienteController@verPaciente');
        Route::get('validacionDerechos/{cedula}', 'Pacientes\PacienteController@validacionDerechos');
        Route::get('trascripciones/{cedula}', 'Pacientes\PacienteController@trascripciones');
        Route::get('showEnabledmedimas/{cedula}', 'Pacientes\PacienteController@showEnabledmedimas');
        Route::get('showEnabledferrocarril/{cedula}', 'Pacientes\PacienteController@showEnabledferrocarril');
        Route::get('citaPendiente/{paciente}', 'Pacientes\PacienteController@citaPendiente');
        Route::get('getPacienteWithCita/{citapaciente}', 'Pacientes\PacienteController@getPacienteWithCita');
        Route::post('guardarNovedades', 'Pacientes\PacienteController@guardarNovedades');
        Route::get('novedadesPaciente/{citapaciente}', 'Pacientes\PacienteController@novedadesPacientes');
        Route::get('consultaBeneficiarios/{citapaciente}', 'Pacientes\PacienteController@consultaBeneficiarios');
        Route::get('consultaPatologias/{citapaciente}', 'Pacientes\PacienteController@consultaPatologias');
        Route::get('consultaComplementarios/{citapaciente}', 'Pacientes\PacienteController@consultaComplementarios');
        Route::post('updateNovedades', 'Pacientes\PacienteController@updateNovedades');
        Route::post('guardarAdjuntos/{novedadId}', 'Pacientes\PacienteController@guardarAdjuntos');
        Route::get('adjuntosNotificaciones/{novedadId}', 'Pacientes\PacienteController@adjuntosNotificaciones');
        Route::post('generarInforme', 'Pacientes\PacienteController@generarInforme');
        Route::post('auditoriaDescarga', 'Pacientes\PacienteController@auditoriaDescarga');
        Route::get('buscarPaciente/{cedula}', 'Pacientes\PacienteController@buscarPaciente');

        //cita no programada
        Route::post('searchPaciente', 'Pacientes\PacienteController@searchPaciente');

        //historicos de la historia clincia
        Route::post('historialClinico','Pacientes\PacienteController@historialCliente');
        Route::post('historialAtencion','Pacientes\PacienteController@historialAtencion');
        Route::post('historialFarmacologico','Pacientes\PacienteController@historialFarmacologico');
        Route::post('historialExamenes','Pacientes\PacienteController@historialExamenes');
        Route::post('historialIncapacidades','Pacientes\PacienteController@historialIncapacidades');
        Route::post('historialLabortario','Pacientes\PacienteController@historialLabortario');
        Route::post('conteoHistoria','Pacientes\PacienteController@conteoHistoria');
        Route::post('conteoMedicamentos','Pacientes\PacienteController@conteoMedicamentos');
        Route::post('conteoServicios','Pacientes\PacienteController@conteoServicios');
        Route::post('conteoIncapacidades','Pacientes\PacienteController@conteoIncapacidades');
        Route::post('conteoAlertas','Pacientes\PacienteController@conteoAlertas');
        Route::post('conteoLaboratorio','Pacientes\PacienteController@conteoLaboratorio');
        Route::post('alertas','Pacientes\PacienteController@alertas');
        Route::post('buscarDatosAlert','Pacientes\PacienteController@buscarDatosAlert');
        Route::post('buscarResultado','Pacientes\PacienteController@buscarResultado');
        Route::post('buscarDatosConsentimientos','Pacientes\PacienteController@buscarDatosConsentimientos');

        //pacientes portabilidad
        Route::get('fetchEstados','Pacientes\PacienteController@fetchEstados');
        Route::get('fetchEntidad','Pacientes\PacienteController@fetchEntidad');
        Route::post('guardarPacientePortabilidad','Pacientes\PacienteController@guardarPacientePortabilidad');
        Route::get('fechtPacientes','Pacientes\PacienteController@fechtPacientes');
        Route::get('portabilidadHistorico/{cedulaHistorico}','Pacientes\PacienteController@portabilidadHistorico');
        Route::get('detallepaciente/{id}','Pacientes\PacienteController@detallepaciente');
        Route::get('detallepacientePortabilidad/{id}','Pacientes\PacienteController@detallepacientePortabilidad');
        Route::get('inactivarpaciente/{id}','Pacientes\PacienteController@inactivarpaciente');
        Route::post('portabilidadSalida','Pacientes\PacienteController@portabilidadSalida');
        Route::post('actualizarPacientePortabilidad/{id}','Pacientes\PacienteController@actualizarPacientePortabilidad');
        Route::post('inactivaPrortabilidad/{id}','Pacientes\PacienteController@inactivaPrortabilidad');
        Route::get('fechtPacientesPortabilidadSalida','Pacientes\PacienteController@fechtPacientesPortabilidadSalida');
        Route::get('buscarOrdenes/{id}','Pacientes\PacienteController@buscarOrdenesPortabilidad');
        Route::get('buscarMedicamentos/{id}','Pacientes\PacienteController@buscarMedicamentosPortabilidad');
        Route::post('cambioEstado','Pacientes\PacienteController@cambioEstado');
        Route::get('novedadesAll','Pacientes\PacienteController@novedadesAll');

        //concurrencia
        Route::get('pacienteConcurrencia/{cedula}', 'Pacientes\PacienteController@pacienteConcurrencia');
        Route::get('pacienteConcurrenciaAlta/{cedula}', 'Pacientes\PacienteController@pacienteConcurrenciaAlta');

        //barreras
        Route::post('agregarBarreras','Pacientes\PacienteController@agregarBarreras');
        Route::get('listarBarreras/{id}','Pacientes\PacienteController@listarBarreras');
        Route::post('solucionarBarreras','Pacientes\PacienteController@solucionarBarreras');

        //laboratorio
        Route::post('buscarDatosExam','Pacientes\PacienteController@buscarDatosExam');

        /** Foto y firma */
        Route::post('guardar-foto-firma', 'Pacientes\PacienteController@guardarFotoFirma');

        /** actualizacion de pacientes */
        Route::post('actualizacionMasiva/{id}', 'Pacientes\PacienteController@actualizacionMasiva');

    });

    /** Info de empleado, abierto */
    Route::get('/consultar/{cedula}', 'Pacientes\PacienteController@consult');

    // consultar paciente existe
    Route::get('consultarPacientes/{cedula}','Pacientes\PacienteController@consultarPacientes');

});
