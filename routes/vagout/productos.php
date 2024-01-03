<?php

Route::group(['prefix' => 'productos'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::get('all',         'Vagout\ProductoController@all');
        Route::get('ciclos',         'Vagout\ProductoController@ciclos');
        Route::get('allProduct',         'Vagout\ProductoController@allProduct');
		Route::post('create',   'Vagout\ProductoController@store');
        Route::get('enabled',         'Vagout\ProductoController@enabled');
        Route::put('edit/{producto}', 'Vagout\ProductoController@update');
        Route::put('available/{productos}', 'Vagout\ProductoController@available');
        Route::get('ingredientes/{codigoProducto}', 'Vagout\ProductoController@getIngredientesProductos');
        Route::get('menus', 'Vagout\ProductoController@getMenus');
        Route::post('guardarMenu',   'Vagout\ProductoController@guardarMenu');
        Route::post('menu/detalle/{id}', 'Vagout\ProductoController@detalleMenu');
        Route::get('disponibilidadMenu', 'Vagout\ProductoController@disponibilidadMenu');
        Route::get('getProductos/{bodega}', 'Vagout\ProductoController@getProductos');


    });
});
