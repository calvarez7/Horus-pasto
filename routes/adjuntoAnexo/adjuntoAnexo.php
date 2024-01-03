<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'adjuntoAnexo'], function () {

    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::post('guardar','AdjuntoAnexos\AdjuntoAnexosController@guardar');
    });
});
