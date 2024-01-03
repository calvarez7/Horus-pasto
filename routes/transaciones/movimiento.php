<?php

Route::group(['prefix' => 'movimiento', 'throttle:60,1'], function () {

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('all',         'Transaciones\MovimientoController@all');
        Route::get('articulos/{movimiento}',  'Transaciones\MovimientoController@articulos');
        Route::post('{bodega}/create',   'Transaciones\MovimientoController@store');
        Route::post('{bodega}/entradafactura',   'Transaciones\MovimientoController@entradaFactura');
        Route::put('edit/{movimiento}',   'Transaciones\MovimientoController@update');
        Route::put('{movimiento}/available', 'Transaciones\MovimientoController@available');
        Route::post('{movimiento}/entrace', 'Transaciones\MovimientoController@entrace');
        Route::put('{movimiento}/cancelar', 'Transaciones\MovimientoController@cancelar');
        Route::post('generateTrasladoSalida', 'Transaciones\MovimientoController@generateTrasladoSalida');
        Route::get('{bodega}/getTraslado', 'Transaciones\MovimientoController@getTraslado');
        Route::post('/acceptTransfer', 'Transaciones\MovimientoController@acceptTransfer');
        Route::post('/updateTransfer/{bodegatransacion}', 'Transaciones\MovimientoController@updateTransfer');
        //Notas
        Route::post('{movimiento}/all/notas',   'Bodegas\NotasController@all');
        Route::post('{movimiento}/create/notas',   'Bodegas\NotasController@store');
        Route::put('edit/{notastransacion}',   'Bodegas\NotasController@update');
        Route::post('/dispensar', 'Transaciones\MovimientoController@dispensar');
        Route::post('/massinvoices', 'Transaciones\MovimientoController@massInvoices');
        Route::post('entradaOrdenComprar/{codigoFactura}','Transaciones\MovimientoController@entradaOrdenComprar');
        Route::post('actaResolucion/{solicitudCompra}','Transaciones\MovimientoController@actaResolucion');
        Route::get('getNovedades/{bodega}',  'Transaciones\MovimientoController@getNovedades');
        Route::post('detallesSolicitudCompra/{solicitud}','Transaciones\MovimientoController@detallesSolicitudCompra');

        Route::post('getAllpendingTraslates', 'Transaciones\MovimientoController@getAllpendingTraslates');

        Route::post('cancelTransfer', 'Transaciones\MovimientoController@cancelTransfer');
        Route::post('anularOrdenCompra', 'Transaciones\MovimientoController@anularOrdenCompra');



    });
});
