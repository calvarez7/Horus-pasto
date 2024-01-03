<?php

Route::group(['prefix' => 'estadistica'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::get('', 'Estadistica\EstadisticaController@all');
        Route::post('', 'Estadistica\EstadisticaController@crear');
	});
});
