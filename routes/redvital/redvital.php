<?php

Route::group(['prefix' => 'redvital'], function () {
    Route::post('validacionPaciente', 'Redvital\RedvitalController@validacionPaciente');
    Route::post('citaspendientes', 'Citas\CitaController@citaspendientesPaciente');
    Route::put('cancelar/{cita}', 'Citas\CitaController@cancelar');
    Route::post('agendaDisponible/{actividad}', 'Redvital\RedvitalController@agendaDisponible');
    Route::put('asignarcita/{cita}', 'Redvital\RedvitalController@asignarcita');
    Route::put('updatepaciente/{paciente}', 'Redvital\RedvitalController@updatepaciente');
    Route::get('datapaciente/{paciente}', 'Redvital\RedvitalController@datapaciente');
    Route::post('generarPassword', 'Redvital\RedvitalController@generarPassword');
    Route::post('createPassword', 'Redvital\RedvitalController@createPassword');
    Route::get('actividades', 'Redvital\RedvitalController@actividades');
    Route::get('actividades', 'Redvital\RedvitalController@actividades');
    Route::post('auditoriaCertificado', 'Redvital\RedvitalController@auditoriaCertificado');
    Route::post('print-pdf', 'Redvital\RedvitalController@imprimirCertificado');
    Route::post('print-recordatorio', 'Redvital\RedvitalController@recordatorio');

    //radicación
    Route::post('radicacion', 'Redvital\RevitalRadicacionController@radicacion');
    Route::get('getTipoSolicitud', 'Redvital\RevitalRadicacionController@getTipoSolicitud');
    Route::post('getRadicadosPaciente', 'Redvital\RevitalRadicacionController@getRadicadosPaciente');
    Route::post('comentar',   'Redvital\RevitalRadicacionController@comentar');
    Route::get('ordenMedicamentos/{documento}', 'Redvital\RevitalRadicacionController@ordenMedicamentos');
    Route::get('ordenservicios/{documento}', 'Redvital\RevitalRadicacionController@ordenservicios');
    Route::get('incapacidades/{documento}', 'Redvital\RevitalRadicacionController@incapacidades');
    Route::post('printMedicamentos', 'Redvital\RevitalRadicacionController@printMedicamentos');
    Route::post('print-pdf_redvital',  'Redvital\RevitalRadicacionController@print');
    Route::get('printServicios/{codigoOrden}', 'Redvital\RevitalRadicacionController@printServicios');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::post('radicacionInterna', 'Redvital\RevitalRadicacionController@radicacion');
        Route::post('validacionPacienteAuth', 'Redvital\RedvitalController@validacionPaciente');
    });
});
