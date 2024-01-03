<?php

Route::group(['prefix' => 'conteo'], function () {

    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::get('all',         'Conteo\ConteoinvController@all');
        Route::post('create', 'Conteo\ConteoinvController@store');
        Route::get('conteos',   'Conteo\ConteoinvController@conteos');
        Route::post('conteo1/{codigoInventario}', 'Conteo\ConteoinvController@cierreConteo1');
        Route::post('conteo2/{codigoInventario}', 'Conteo\ConteoinvController@cierreConteo2');
        Route::post('conteo3/{codigoInventario}', 'Conteo\ConteoinvController@cierreConteo3');
        Route::post('update/{codigoInventario}', 'Conteo\ConteoinvController@cerrarConteo');
        Route::post('export', 'Conteo\ConteoinvController@exportSaldos');
        Route::post('actualizar', 'Conteo\ConteoinvController@guardarProgreso');
        Route::get('proximos/{codigoInventario}', 'Conteo\ConteoinvController@proximosConteos');


    });
});
