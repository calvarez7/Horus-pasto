<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'caracterizacion'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::post('registro',  'Caracterizacion\CaracterizacionController@registro');
        Route::put('update/{caracterizacion}',  'Caracterizacion\CaracterizacionController@update');
        Route::get('lista',  'Caracterizacion\CaracterizacionController@lista');
	});
});
