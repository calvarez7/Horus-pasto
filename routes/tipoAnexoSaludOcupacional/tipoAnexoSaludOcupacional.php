<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'tipoAnexo_saludOcupacional'], function () {

    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::post('guardar','TipoAnexoSaludOcupacional\TipoAnexoSaludOcupacionalController@guardar');
        Route::get('listar','TipoAnexoSaludOcupacional\TipoAnexoSaludOcupacionalController@listar');
    });
});
