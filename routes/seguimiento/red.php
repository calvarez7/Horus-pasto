<?php

Route::group(['prefix' => 'red'], function () {
    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::post('getOportunidad',   'SeguimientoRed\RedController@getOportunidad');
        Route::post('getOportunidadAuditoria',   'SeguimientoRed\RedController@getOportunidadAuditoria');
    });
});
