<?php

Route::group(['prefix' => 'categoriaprodutos'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::get('allCategory',         'Vagout\CategoriaproductoController@allCategory');
		Route::post('create',   'Vagout\CategoriaproductoController@store');
        Route::get('enabled',         'Vagout\CategoriaproductoController@enabled');
        Route::put('edit/{categoria}', 'Vagout\CategoriaproductoController@update');
        Route::put('available/{categoriaproductos}', 'Vagout\CategoriaproductoController@available');
	});
});
