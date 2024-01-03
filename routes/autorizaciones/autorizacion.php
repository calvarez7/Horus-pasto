<?php

Route::group(['prefix' => 'autorizacion'], function () {
    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function () {
        Route::post('allMedicamentos', 'Historia\AutorizacionController@listaAutorizacionesMedicamentos');
        Route::post('getOncologicalOrdersToAuthorizate', 'Historia\AutorizacionController@getOncologicalOrdersToAuthorizate');
        Route::post('allServicios', 'Historia\AutorizacionController@listaAutorizacionesServicios');
        Route::post('listAuthMedicByState', 'Historia\AutorizacionController@listAuthMedicByState');
        Route::post('listAuthServByState', 'Historia\AutorizacionController@listAuthServByState');
        Route::post('OwnServicesOrdened', 'Historia\AutorizacionController@OwnServicesOrdened');
        Route::post('listaAutorizacionesServiciosByType', 'Historia\AutorizacionController@listaAutorizacionesServiciosByType');
        Route::post('StateAprob', 'Historia\AutorizacionController@autorizacionStateAprob');
        Route::post('StateAprobOne', 'Historia\AutorizacionController@autorizacionStateAprobOne');
        Route::post('StateInad', 'Historia\AutorizacionController@autorizacionStateInad');
        Route::post('StateNeg', 'Historia\AutorizacionController@autorizacionStateNeg');
        Route::post('StateAnu', 'Historia\AutorizacionController@autorizacionStateAnu');
        Route::post('UpdateMedic/{detaarticulorden}', 'Historia\AutorizacionController@updateMedicamento');
        Route::post('UpdateServ/{cuporden}', 'Historia\AutorizacionController@updateServicio');
        Route::post('UpdatePresServ/{cuporden}', 'Historia\AutorizacionController@updateServicioPrestador');
        Route::post('UpdatePresServPropio/{cuporden}', 'Historia\AutorizacionController@updateServicioPropioPrestador');
        Route::post('getExcelForMedicamentos', 'Historia\AutorizacionController@getExcelForMedicamentos');
        Route::post('getExcelForServicios', 'Historia\AutorizacionController@getExcelForServicios');
        Route::post('agregarFirmaPaciente/{codigoOrden}', 'Historia\OrdenController@agregarFirmaPaciente');
        Route::get('orden/paciente/{codigoOrden}', 'Historia\OrdenController@ordenPaciente');
        Route::get('ordenes/{documento}', 'Historia\OrdenController@index');
        Route::get('ordenes/servicios/{documento}', 'Historia\OrdenController@getServicios');
        Route::get('ordenes/optometria/{documento}', 'Historia\OrdenController@getOptometria');
        Route::get('contadorSeguimiento/{prestadorId}', 'Historia\AutorizacionController@contadorSeguimiento');
        Route::post('exportar/servicios/prestador', 'Historia\AutorizacionController@exportarServiciosPrestador');
        Route::post('exportar/visionTotal', 'Historia\AutorizacionController@visionTotal');
        Route::post('getOrdenesPortabilidad', 'Historia\AutorizacionController@getOrdenesPortabilidad');



    });
});
