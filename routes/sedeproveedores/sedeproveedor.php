<?php

Route::group(['prefix' => 'sedeproveedore'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {

        Route::post('all',   'Sedeprestadores\SedeproveedorController@all');
        Route::get('allproveedores', 'Sedeprestadores\SedeproveedorController@allproveedores');
        Route::get('proveedores', 'Sedeprestadores\SedeproveedorController@proveedores');
        Route::get('sedeproveedores', 'Sedeprestadores\SedeproveedorController@sedeproveedores');
        Route::post('create',   'Sedeprestadores\SedeproveedorController@store');
        Route::put('edit/{sedeproveedore}', 'Sedeprestadores\SedeproveedorController@update');
        Route::put('disable/{sedeproveedore}', 'Sedeprestadores\SedeproveedorController@disable');
        Route::post('getSedePrestadorByName', 'Sedeprestadores\SedeproveedorController@getSedePrestadorByName');
        Route::get('getSedePrestador', 'Sedeprestadores\SedeproveedorController@getSedePrestador');

        /** David ruta */
        Route::get('/listar', 'Sedeprestadores\SedeproveedorController@listar');

        //Cuentas Medicas
        Route::get('getProveedores', 'Sedeprestadores\SedeproveedorController@getProveedores');
        Route::post('acumuladoPrestador', 'Sedeprestadores\SedeproveedorController@acumuladoPrestador');
        Route::post('facturaPrestador/{prestador}','Sedeprestadores\SedeproveedorController@facturaPrestador');

        //Rips
        Route::post('prestadoresRips','Sedeprestadores\SedeproveedorController@prestadoresRips');
        Route::get('prestadores','Sedeprestadores\SedeproveedorController@prestadores');
        Route::post('guardarRepsRips','Sedeprestadores\SedeproveedorController@guardarRepsRips');
        Route::post('guardarPrestadorRips','Sedeprestadores\SedeproveedorController@guardarPrestadorRips');
    });
});
