<?php

Route::group(['prefix' => 'entidades'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::get('CalcularMedicamento/{citaPacienteId}', 'Entidades\EntidadController@validarMedicamento');
        Route::get('CalcularServicio/{citaPacienteId}', 'Entidades\EntidadController@validarServicio');
        Route::get('CalcularDireccionamiento/{idOrden}/{citaPacienteId}', 'Entidades\EntidadController@calcularOrdenamientoFormatos');
        Route::get('getEntidades', 'Entidades\EntidadController@getEntidades');
        Route::post('guardarPermisos/{entidad}','Entidades\EntidadController@guardarPermisos');
        Route::post('guardar','Entidades\EntidadController@guardar');
        Route::get('validar/{entidad}/{accion}', 'Entidades\EntidadController@validar');
        Route::get('entidadesOcpucacionales', 'Entidades\EntidadController@entidadesOcpucacionales');
        Route::get('entidadesNoContrato', 'Entidades\EntidadController@entidadesNoContrato');
    });
});
