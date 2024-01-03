<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'departamento'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::get('all', 'Departamentos\DepartamentoController@all');
	});
});
// hola
