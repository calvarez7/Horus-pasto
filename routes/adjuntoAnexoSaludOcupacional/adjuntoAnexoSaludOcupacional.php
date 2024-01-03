<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'adjuntoAnexo_saludOcupacional'], function () {

    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::post('guardar','AdjuntoAnexoSaludOcupacional\AdjuntoAnexoSaludOcupacionalController@guardar');
    });
});
