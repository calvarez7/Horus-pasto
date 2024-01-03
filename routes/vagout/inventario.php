<?php

Route::group(['prefix' => 'inventario'], function () {
    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::apiResource('ingredientes', 'Vagout\InventarioController');
        Route::post('validar', 'Vagout\InventarioController@validar');
        Route::get('getBodegasVagout','Vagout\InventarioController@getBodegasVagout');
        Route::get('consumo','Vagout\InventarioController@ingredientesDia');
        Route::post('salidaMasiva/{personas}','Vagout\InventarioController@salidaMasiva');
        Route::get('getCiclos/{ciclo}','Vagout\InventarioController@getCiclos');
        Route::post('generarCicloDia','Vagout\InventarioController@generarCicloDia');
        Route::get('cicloActual','Vagout\InventarioController@cicloActual');
        Route::get('cicloActualExcel','Vagout\InventarioController@cicloActualExcel');
        Route::post('descargue', 'Vagout\InventarioController@descargue');
        Route::post('cargueInventario', 'Vagout\InventarioController@cargueInventario');
        Route::post('getInventario/{id}', 'Vagout\InventarioController@getInventario');

    });
});
