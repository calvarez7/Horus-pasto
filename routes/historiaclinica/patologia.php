<?php

Route::group(['prefix' => 'patologia'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::get('all', 'Historia\PatologiaController@all');
        Route::post('{citaPaciente}/create', 'Historia\PatologiaController@store');
        Route::get('{citaPaciente}/datospatologia', 'Historia\PatologiaController@fetchPatologia');
	});
});