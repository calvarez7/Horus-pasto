<?php

Route::group(['prefix' => 'juzgado'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
	Route::get('all', 'Juzgados\JuzgadoController@all');
	Route::post('create', 'Juzgados\JuzgadoController@store');
	});
});