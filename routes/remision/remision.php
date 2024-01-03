<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'remision'], function () {

    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::post('guardar', 'RemisionProgramas\RemisionProgramasController@guardar');
        Route::get('listarProgramas/{id}', 'RemisionProgramas\RemisionProgramasController@listarProgramasPaciente');
        Route::post('generarInforme', 'RemisionProgramas\RemisionProgramasController@generarInforme');
    });
});
