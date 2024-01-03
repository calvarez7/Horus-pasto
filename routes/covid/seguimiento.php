<?php

Route::group(['prefix' => 'seguimiento'], function () {
    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::post('create',   'Covid\SeguimientoController@create');
        Route::get('lista',   'Covid\SeguimientoController@listar');
        Route::get('verseguimientos/{id}',   'Covid\SeguimientoController@verseguimientos');
        Route::get('showseguimientos/{id}',   'Covid\SeguimientoController@showseguimientos');
        Route::get('seguimiento',   'Covid\SeguimientoController@seguimiento');
        Route::get('pacienteAlta',   'Covid\SeguimientoController@pacienteAlta');
        Route::get('countCovi',   'Covid\SeguimientoController@countCovi');
        Route::get('reporte',   'Covid\SeguimientoController@reporte');
        Route::get('reporteRutacovi',   'Covid\SeguimientoController@reporteRutacovi');
        Route::post('guardarSeguimiento',   'Covid\SeguimientoController@guardarSeguimiento');
        Route::post('control',   'Covid\SeguimientoController@control');

    });
});
