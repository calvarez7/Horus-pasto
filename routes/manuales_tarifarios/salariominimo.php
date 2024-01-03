<?php

Route::group(['prefix' => 'salariominimo'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
		Route::post('create',   'Tarifarios\SalariominimoController@store');
		Route::get('all',         'Tarifarios\SalariominimoController@all'); 
		Route::post('sede',   'Tarifarios\SalariominimoController@sedeminimo');
		Route::get('allsedeminimo',         'Tarifarios\SalariominimoController@allsedeminimo');         
		Route::post('updatesedeminimo',   'Tarifarios\SalariominimoController@updatesedeminimo');
		Route::post('municipioproveedor',   'Tarifarios\SalariominimoController@municipioproveedor');
		Route::post('updateproveedorminimo',   'Tarifarios\SalariominimoController@updateproveedorminimo');
		Route::put('edit/{salariominimo}', 'Tarifarios\SalariominimoController@update');
	});
});
