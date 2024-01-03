<?php

Route::group(['prefix' => 'imagenologia'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
    	Route::get('{citapaciente}/imagenologia', 'Historia\ImagenologiaController@imagenologia');
    	Route::get('radiologos', 'Historia\ImagenologiaController@radiologos');
    	Route::post('create/{citapaciente}', 'Historia\ImagenologiaController@store');
	});
});