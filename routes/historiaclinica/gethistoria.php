<?php

Route::group(['prefix' => 'historiapaciente', 'throttle:60,1'], function () {
	Route::group(['middleware' => 'auth:api'], function() {
        Route::get('{patientAppointment}/examen_fisico', 'Historia\ResumenhistoriaController@getPhysicalExam');
        Route::post('gethistoria', 'Historia\ResumenhistoriaController@gethistoria');
        Route::post('getdomiciliaria', 'Historia\ResumenhistoriaController@getdomiciliaria');
        Route::post('getlaboratorios', 'Historia\ResumenhistoriaController@getlaboratorios');
        Route::post('getResultadolaboratorios', 'Historia\ResumenhistoriaController@getResultadolaboratorios');
        Route::post('gethistoriaradium', 'Historia\ResumenhistoriaController@gethistoriaradium');
        Route::get('getHistoryByCita/{citaPaciente}','Historia\ResumenhistoriaController@getHistoryByCita');
        Route::get('historiaid/{referenciaid}', 'Historia\ResumenhistoriaController@historiaid');

    });
});
