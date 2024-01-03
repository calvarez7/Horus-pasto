<?php

Route::group(['prefix' => 'configuraciones'], function () {
    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::get('getConfiguraciones',   'Configuraciones\ConfiguracionController@getConfiguraciones');
        Route::post('guardarConfiguraciones',   'Configuraciones\ConfiguracionController@guardarConfiguraciones');
    });
});
