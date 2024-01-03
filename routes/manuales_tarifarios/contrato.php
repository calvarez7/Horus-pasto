<?php

Route::group(['prefix' => 'contrato'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
		Route::post('create',   'Tarifarios\ContratoController@store');
        Route::post('all',         'Tarifarios\ContratoController@all');                   
		Route::put('edit/{contrato}', 'Tarifarios\ContratoController@update');
		Route::put('disable/{contrato}', 'Tarifarios\ContratoController@disable');
        Route::post('{contrato}/unidadfuncional','Tarifarios\ContratoController@unidadFuncionalContrato');
        Route::post('{contrato}/unidadfuncional/remove','Tarifarios\ContratoController@removeunidadFuncionalContrato');
        Route::post('{contrato}/rangounidadfuncional/remove','Tarifarios\ContratoController@removeRangoFuncional');

        Route::post('{contrato}/tarifa/create','Tarifarios\ContratoController@saveTarifa');
        Route::post('{contrato}/tarifa/add','Tarifarios\ContratoController@addTarifa');
        Route::post('{sede}/tarifa/all','Tarifarios\ContratoController@allTarifa');
        Route::post('tarifa/{cup}/edit','Tarifarios\ContratoController@updateTarifa');
        Route::post('{contrato}/tarifa/{tarifa}/disable','Tarifarios\ContratoController@disableTarifa');
	});
});