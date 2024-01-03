<?php

Route::group(['prefix' => 'cups'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::get('all',  'Cups\CupController@all');
        Route::post('create',  'Cups\CupController@store');
        Route::post('search',  'Cups\CupController@searchCup');
        Route::put('edit/{cup}',  'Cups\CupController@update');
        Route::post('cupscapitulo',  'Cups\CupController@cupsCapitulo');
        Route::post('orden',  'Cups\CupController@cupsOrden');
        Route::get('servicios',  'Cups\CupController@serviciosOrden');
        Route::get('interconsultas/{citapaciente}',  'Cups\CupController@cupsOrdenInterconsulta');
        Route::post('import',  'Cups\CupController@import');
        Route::post('cupfamilias',  'Cups\CupController@cupfamilias');
        Route::post('addcupfamilias',  'Cups\CupController@addcupfamilias');
        Route::get('getCupsEntidades/{entidad}',  'Cups\CupController@cupsEntidades');
        Route::get('allEducacion',  'Cups\CupController@allEducacion');
        Route::get('paquetes/servicios','Cups\CupController@paqueteServicios');
        Route::post('guardarPaquete','Cups\CupController@guardarPaquete');
    });
});
