<?php

Route::group(['prefix' => 'clasificaciones'], function () {
    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::get('getClasificacion',   'Clasificacion\ClasificacionController@getClasificacion');
        Route::post('guardarClasificacion',   'Clasificacion\ClasificacionController@guardarClasificacion');
        Route::post('actualizacionClasificacion/{id}',   'Clasificacion\ClasificacionController@actualizacionClasificacion');
        Route::post('actualizacionClasificacionEstado/{id}',   'Clasificacion\ClasificacionController@actualizacionClasificacionEstado');

        // aseguramiento
        Route::get('getClasificacionActivos',   'Clasificacion\ClasificacionController@getClasificacionActivos');
        Route::get('getClasificacionPacienteActivos/{id}',   'Clasificacion\ClasificacionController@getClasificacionPacienteActivos');
        Route::post('guardarClasificacionPaciente',   'Clasificacion\ClasificacionController@guardarClasificacionPaciente');
        Route::post('eliminarClasificacionPaciente/{id}',   'Clasificacion\ClasificacionController@eliminarClasificacionPaciente');


    });
});