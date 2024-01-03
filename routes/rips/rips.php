<?php

Route::group(['prefix' => 'rips'], function () {

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('all',                      'Reps\RepController@all');
        Route::get('all',                      'Reps\RepController@all');
        Route::get('getPendingPaqs',           'Rips\RipController@getPendingPaqs');
        Route::get('getAuditPaqs',             'Rips\RipController@getAuditPaqs');
        Route::post('reporteRips',             'Rips\RipController@reporteRips');
        Route::post('ripsHorus',               'Rips\RipController@ripsHorus');
        Route::post('consolidadoRips',         'Rips\RipController@consolidadoRips');

        Route::post('validar', 'Rips\RipController@validar');
        Route::post('temporarilySave',         'Rips\RipController@temporarilySave');
        Route::post('acceptPendingRips',       'Rips\RipController@acceptPendingRips');
        Route::post('rejectPendingRips',       'Rips\RipController@rejectPendingRips');

        Route::post('resolucion','Rips\RipController@resolucion');
        Route::get('mesNombre/{idMes}',         'Rips\RipController@mes');
        Route::post('formato',         'Rips\RipController@formatoDescarga');
        Route::post('exportError', 'Rips\RipController@exportErrors');
        Route::post('validateExist', 'Rips\RipController@validateExist');
        Route::post('savePartialRips', 'Rips\RipController@savePartialRips');
        Route::post('saveRips', 'Rips\RipController@saveRips');
        Route::post('getRadicados',             'Rips\RipController@getRadicados');
        Route::get('aceptarRips/{id}',             'Rips\RipController@aceptarRips');
        Route::post('rechazarRips/{id}',             'Rips\RipController@rechazarRips');
        Route::get('pendienteRips/{id}',             'Rips\RipController@pendienteRips');
        Route::get('autorizacionPeriodoRips',             'Rips\RipController@autorizacionPeriodoRips');
        Route::get('eliminarProcesoValidacion/{paqRip}',             'Rips\RipController@eliminarProcesoValidacion');


        /** Descargar */
        Route::post('descargar','Rips\RipController@descargar');
        Route::get('enProcesoValidacion',             'Rips\RipController@enProcesoValidacion');
        Route::get('descargarerrores/{id}',             'Rips\RipController@descargarErrores');




    });
});
