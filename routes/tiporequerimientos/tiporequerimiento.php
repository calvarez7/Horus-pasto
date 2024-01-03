<?php

Route::group(['prefix' => 'tiporequerimiento'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
    Route::get('all', 'Tiporequerimientos\TiporequerimientoController@all');
    Route::post('create', 'Tiporequerimientos\TiporequerimientoController@store');
	});
});