<?php

Route::group(['prefix' => 'facturas'], function () {
    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::post('setSaveTypeSale', 'Vagout\FacturaController@saveTypeSale');
        Route::post('getInvoices','Vagout\FacturaController@index');
        Route::post('update/{factura}','Vagout\FacturaController@update');
        Route::post('registroInventario', 'Vagout\FacturaController@registroInventario');
        Route::get('generarCorte','Vagout\FacturaController@generarCorte');
        Route::get('seleccionarFechaCorte','Vagout\FacturaController@seleccionarFechaCorte');
        Route::get('descargarCorteFecha','Vagout\FacturaController@descargarCorteFecha');
    });
});
