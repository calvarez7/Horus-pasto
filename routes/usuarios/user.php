<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'user'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
		Route::post('create',   'Usuarios\UserController@store');
                Route::get('all',         'Usuarios\UserController@all');
                Route::get('activos',         'Usuarios\UserController@enableByRole');
                Route::get('medicos',        'Usuarios\UserController@medicos');
                Route::get('medicoSumi',        'Usuarios\UserController@medicoSumi');
                Route::put('edit/{user}', 'Usuarios\UserController@update');
                Route::post('edit/{user}', 'Usuarios\UserController@updatefirma');
                Route::put('password/{user}', 'Usuarios\UserController@updatepass');
                Route::get('show/{user}',   'Usuarios\UserController@show');
                Route::put('available/{user}', 'Usuarios\UserController@available');
                Route::get('getSignByUser', 'Usuarios\UserController@getSignByUser');
                Route::post('addPermissions/{user}', 'Usuarios\UserController@addPermissions');
                Route::post('deletePermission/{user}', 'Usuarios\UserController@deletePermission');
                Route::get('getFindUserForDatabase/{identificacion}', 'Usuarios\UserController@findUserForDatabase');
                Route::post('deleteUser/{user}', 'Usuarios\UserController@deleteUserRol');
                Route::post('addRol/{user}', 'Usuarios\UserController@addRol');
                Route::get('proveedor/{identificacion}','Usuarios\UserController@buscarUsuario');
                Route::post('guardarInformacion','Usuarios\UserController@guardarInformacion');
                Route::post('guardarRepresentante','Usuarios\UserController@guardarRepresentante');
                Route::post('guardarSocio','Usuarios\UserController@guardarSocio');
                Route::get('eliminarSocios/{socio}','Usuarios\UserController@eliminarSocios');
                Route::post('guardarPersonaExpuesta','Usuarios\UserController@guardarPersonaExpuesta');
                Route::get('eliminarPersonaExpuesta/{PersonaExpuesta}','Usuarios\UserController@eliminarPersonaExpuesta');
                Route::post('guardarInformacionFinaciera','Usuarios\UserController@guardarInformacionFinaciera');
                Route::post('guardarActividadesInternacionales','Usuarios\UserController@guardarActividadesInternacionales');
                Route::post('guardarDeclaracionFondos','Usuarios\UserController@guardarDeclaracionFondos');
                Route::post('guardarAdjuntos/{sarlafs}','Usuarios\UserController@guardarAdjuntos');
                Route::post('guardarEspecialidad','Usuarios\UserController@guardarEspecialidad');
                Route::get('allEspecialidades', 'Usuarios\UserController@allEspecialidades');
	});
});



