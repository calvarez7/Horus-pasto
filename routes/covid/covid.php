<?php

Route::group(['prefix' => 'covid'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::get('admision',   'Covid\CovidController@admision');
        Route::get('paises',   'Covid\CovidController@paises');
        Route::get('listarregistros',   'Covid\CovidController@listarregistros');
        Route::get('alta',   'Covid\CovidController@alta');
        Route::get('verseguimientos/{registro}',   'Covid\CovidController@verseguimientos');
        Route::post('consulta',  'Covid\CovidController@all');
		Route::post('create',   'Covid\CovidController@store');
        Route::post('registro',   'Covid\CovidController@registro');
        Route::post('registroadmision',   'Covid\CovidController@registroadmision');
        Route::post('guardarSeguimientos',   'Covid\CovidController@guardarSeguimientos');
        Route::post('dealtaCovi',   'Covid\CovidController@dealtaCovi');
        Route::get('asignados',   'Covid\CovidController@asignados');
        Route::get('ocupaciones',   'Covid\CovidController@ocupaciones');
        Route::get('reporte',   'Covid\CovidController@reporte');

    });
});
