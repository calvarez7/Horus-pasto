<?php

Route::group(['prefix' => 'codepropio'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::post('all',         'Tarifarios\CodepropioController@all');                   
        Route::post('allBodega',         'Tarifarios\CodepropioController@allBodega');                   
		Route::post('{sedeproveedore}/create',   'Tarifarios\CodepropioController@store');
        Route::put('edit/{codepropio}', 'Tarifarios\CodepropioController@update');                   
        Route::get('enabled', 'Tarifarios\CodepropioController@enabled');  
        Route::get('serviciosPropios', 'Tarifarios\CodepropioController@serviciosPropios');  
	});
});