<?php

Route::group(['prefix' => 'esquemas'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::post('notas','Esquema\EsquemaController@notas');
        Route::get('all',                 'Esquema\EsquemaController@all');
        Route::get('show/{esquema}',      'Esquema\EsquemaController@show');
        Route::get('miNota/{id}',      'Esquema\EsquemaController@miNota');
        Route::get('getNotas/{orden}','Esquema\EsquemaController@getNotas');
        Route::get('aProgramar',          'Esquema\EsquemaController@programacionCitaAplicacion');
        Route::get('getOrdersOncologyDispensed', 'Historia\OrdenController@getOrdersOncologyDispensed');
		Route::post('create',             'Esquema\EsquemaController@store');
        Route::put('edit/{esquema}',      'Esquema\EsquemaController@update');
        Route::put('available/{esquema}', 'Esquema\EsquemaController@available');
        Route::post('save',   'Esquema\EsquemaController@storeDetail');
        Route::put('editSchema/{detallesquema}',      'Esquema\EsquemaController@updateDetails');
        Route::post('saveNote','Esquema\EsquemaController@saveNotaEnfermeria');
        Route::post('editNote/{citaPaciente}','Esquema\EsquemaController@editNotaEnfermeria');


    });
});
