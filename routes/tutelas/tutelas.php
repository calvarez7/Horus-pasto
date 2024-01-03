<?php

Route::group(['prefix' => 'tutelas'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
	Route::post('all', 'Tutelas\TutelaController@all');
	Route::get('activo/{cedula}', 'Tutelas\TutelaController@activo');
	Route::put('marcacion/{paciente}', 'Tutelas\TutelaController@marcacion');
	Route::post('store', 'Tutelas\TutelaController@store');
	Route::post('asignados', 'Tutelas\TutelaController@asignados');
	Route::get('cerradas', 'Tutelas\TutelaController@cerradas');
	Route::post('respuestas', 'Tutelas\TutelaController@respuestas');
	Route::post('cerrartutela', 'Tutelas\TutelaController@cerrartutela');
	Route::post('reasignar', 'Tutelas\TutelaController@reasignar');
	Route::post('compartir', 'Tutelas\TutelaController@compartir');
	Route::post('listresponsable', 'Tutelas\TutelaController@listresponsable');
	Route::get('allresponsables', 'Tutelas\TutelaController@allresponsables');
	Route::get('estadoresponsable/{id}', 'Tutelas\TutelaController@estadoresponsable');
	Route::get('enableresponsable/{id}', 'Tutelas\TutelaController@enableresponsable');
	Route::get('showresponsables', 'Tutelas\TutelaController@showresponsables');
	Route::get('roles', 'Tutelas\TutelaController@roles');
	Route::post('alert', 'Tutelas\TutelaController@alert');
	Route::post('desvincular', 'Tutelas\TutelaController@desvincularRol');
	Route::post('updateRequerimiento', 'Tutelas\TutelaController@updateRequerimiento');
	Route::post('generarInforme', 'Tutelas\TutelaController@generarInforme');

	});
});
