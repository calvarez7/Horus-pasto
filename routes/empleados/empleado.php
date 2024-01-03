<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'empleados'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
		Route::post('create',   'Empleados\EmpleadoController@store');
        Route::put('edit/{empleado}', 'Empleados\EmpleadoController@update');
        Route::get('getEmpleados',   'Empleados\EmpleadoController@getEmpleados');
        Route::get('getDatosPersonales/{identificador}',   'Empleados\EmpleadoController@getDatosPersonales');
        Route::get('fethPendientes',   'Empleados\EmpleadoController@fethPendientes');
        Route::get('pendientes',   'Empleados\EmpleadoController@pendientes');
	});

    Route::get('listar','Empleados\EmpleadoController@getEmpleados')->middleware(['token.api']);

});
