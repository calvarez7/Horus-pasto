<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'reporteMedicamentos'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
    Route::post('reporteEntregaMedicamentos', 'ReporteEntregaMedicamentos\ReporteEntregaMedicamentosController@reporteEntregaMedicamentos');
	});
});
