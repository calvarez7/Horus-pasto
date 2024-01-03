<?php

Route::group(['prefix' => 'codigoglosa'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
		Route::post('create',   'Codigoglosas\CodigoglosaController@store');
        Route::get('tipos',   'Codigoglosas\CodigoglosaController@tipos');
		Route::get('all',   'Codigoglosas\CodigoglosaController@all');
		Route::put('edit/{codigoglosa}',   'Codigoglosas\CodigoglosaController@update');
	});
});