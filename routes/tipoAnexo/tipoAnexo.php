<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'tipoAnexo'], function () {

    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::post('guardar','TipoAnexos\TipoAnexoController@guardar');
        Route::get('listar','TipoAnexos\TipoAnexoController@listar');
    });
});
