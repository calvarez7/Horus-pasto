<?php

Route::group(['prefix' => 'tipofacturas'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::get('all',         'Vagout\TipofacturaController@all');                   
		Route::post('create',   'Vagout\TipofacturaController@store');
        Route::get('enabled',         'Vagout\TipofacturaController@enabled');                   
        Route::put('edit/{tipofacturas}', 'Vagout\TipofacturaController@update');
        Route::put('available/{tipofacturas}', 'Vagout\TipofacturaController@available');
	});
});