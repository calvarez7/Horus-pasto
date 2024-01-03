<?php

Route::group(['prefix' => 'especialidad'], function () {

    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::post('create',   'Especialidades\EspecialidadeController@store');
        Route::get('all',         'Especialidades\EspecialidadeController@all');
        Route::put('edit/{especialidade}', 'Especialidades\EspecialidadeController@update');
        Route::put('available/{especialidade}', 'Especialidades\EspecialidadeController@available');
        Route::get('{especialidad}/especialidadMedicos', 'Especialidades\EspecialidadeController@especialidadMedico');
        Route::get('tipoactividad/{especialidade}',   'Especialidades\EspecialidadeController@especialidadActividad');

        Route::post('{especialidade}/Especialidadtipoagenda/create',   'Especialidadtipoagendas\EspecialidadtipoagendaController@store');
        Route::post('{especialidade}/getActivityBySpecialty',   'Especialidadtipoagendas\EspecialidadtipoagendaController@getActivityBySpecialty');
        Route::get('Especialidadtipoagenda/all',   'Especialidadtipoagendas\EspecialidadtipoagendaController@all');
        Route::get('Especialidadtipoagenda/allEspecialidades',   'Especialidadtipoagendas\EspecialidadtipoagendaController@allEspecialidades');
        Route::put('Especialidadtipoagenda/cambioEstado/{id}',   'Especialidadtipoagendas\EspecialidadtipoagendaController@cambioEstado');
        Route::post('storespecialidadcups',   'Especialidades\EspecialidadeController@storespecialidadcups');
        Route::get('allespecialidadcups',   'Especialidades\EspecialidadeController@allespecialidadcups');
        Route::put('updatespecialidadcups/{especialidad}', 'Especialidades\EspecialidadeController@updatespecialidadcups');
        Route::get('especialidadCita/{especialidad}',   'Especialidades\EspecialidadeController@especialidadCita');
        Route::post('especialidadNoProgramada',   'Especialidades\EspecialidadeController@especialidadNoProgramada');
        Route::post('guardarOrden',   'Especialidades\EspecialidadeController@guardarOrden');

    });
});
