<?php

Route::group(['prefix' => 'domiciliaria'], function () {

    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::get('remisionesall',     'Domiciliaria\RemisionController@remisionPendiente');
        Route::get('departamento',     'Domiciliaria\RemisionController@departamento');
        Route::post('create/{paciente}', 'Domiciliaria\RemisionController@createRemesion');
    });
});
