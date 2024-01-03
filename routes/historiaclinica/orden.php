<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'orden'], function () {
    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function () {
        Route::get('all', 'Historia\OrdenController@all');
        Route::get('citapaciente/{citapaciente}/ordens', 'Historia\OrdenController@getOrdens');
        Route::post('citapaciente/{citapaciente}/create', 'Historia\OrdenController@store');
        Route::post('cancelEsquema/{orden}', 'Historia\OrdenController@cancelEsquema');
        Route::post('getOrder/{citapaciente}', 'Historia\OrdenController@getOrderById');
        Route::get('getOrderedCodesumi/{citapaciente}', 'Historia\OrdenController@getOrderedCodesumi');
        Route::get('getOrderedScheme/{citapaciente}', 'Historia\OrdenController@getOrderedScheme');
        Route::get('getOrderedCie/{citapaciente}', 'Historia\OrdenController@getOrderedCie');
        Route::put('edit/{orden}', 'Historia\OrdenController@update');
        Route::post('medicamentos', 'Historia\OrdenController@ordenesMedicamento');
        Route::post('lotes', 'Historia\OrdenController@lotesMedicamentos');
        Route::post('getLote', 'Historia\OrdenController@getLote');
        Route::get('getInCupOrden/{orden}', 'Historia\OrdenController@getOrdeninCupOrden');
        Route::get('getInDetaArticuloOrden/{orden}', 'Historia\OrdenController@getOrdeninDetaArticuloOrden');
        Route::post('getOrderedCup/{citapaciente}', 'Historia\OrdenController@getOrderedCups');
        Route::post('getDetaArticuloOrden',   'Historia\OrdenController@getDetaArticuloOrden');
        Route::post('getCupOrden',   'Historia\OrdenController@getCupOrden');
        Route::post('getServicioPropio',   'Historia\OrdenController@getServicioPropio');
        Route::post('getOrderedServicioPropio/{citapaciente}',   'Historia\OrdenController@getOrderedServicioPropio');
        Route::post('historicoDispensado', 'Historia\OrdenController@historicoDispensado');
        Route::post('historicoActivo', 'Historia\OrdenController@historicoActivo');
        Route::post('historicoPendiente', 'Historia\OrdenController@historicoPendiente');
        Route::post('ordenesProximas', 'Historia\OrdenController@ordenesProximas');
        Route::post('getArticulosOrden', 'Historia\OrdenController@getArticulosOrden');
        Route::get('getAllArticulosOrden/{codigoOrden}', 'Historia\OrdenController@getAllArticulosOrden');
        Route::get('getAllServiceOrden/{codigoOrden}', 'Historia\OrdenController@getAllServiceOrden');
        Route::post('historicoPendienteOncologico', 'Historia\OrdenController@historicoPendienteOncologico');
        Route::post('aprovacionFarmacia', 'Historia\OrdenController@setStateRevisionPharmacy');
        Route::post('updadateOrden/{orden}', 'Historia\OrdenController@updateOrdens');
        Route::post('updateOrderInPending/{orden}', 'Historia\OrdenController@updateOrderInPending');
        Route::get('getOrdersOncologyForNursing', 'Historia\OrdenController@getOrdersOncologyForNursing');
        Route::get('getHistoryPendingOncology', 'Historia\OrdenController@historyPendingOncology');
        Route::post('getOrdersPending', 'Historia\OrdenController@getOrdersPending');
        Route::get('assignPending/{codigoOrden}', 'Historia\OrdenController@assignPending');
        Route::post('updadateOrdenByCita/{cita}', 'Historia\OrdenController@updadateOrdenByCita');
        Route::get('getHistoryOncology/{numeroIdentificacion}','Historia\OrdenController@historyOncology');
        Route::post('updateCupOrden/{cupOrden}','Historia\OrdenController@updateCupOrden');
        Route::post('resolucion','Historia\OrdenController@resoluciones');
        Route::post('datosPrestador/{cupOrden}','Historia\OrdenController@datosPrestador');
        Route::get('historias/medimas','Historia\OrdenController@historiasMedimas');
        Route::post('historias/consolidados','Historia\OrdenController@consolidados');
        Route::get('getDetalleOrdenamientoForCita/{citaPaciente}','Historia\OrdenController@getDetalleOrdenamientoForCita');
        Route::get('fetchOptometria/{cita_paciente_id}','Historia\OrdenController@fetchOptometria');
        Route::get('fetchRecomendacion/{cita_paciente_id}','Historia\OrdenController@fetchRecomendacion');
        Route::get('recomendaciones/{codigo}','Historia\OrdenController@recomendaciones');
        Route::post('getCantidadmaxordenar', 'Historia\OrdenController@getCantidadmaxordenar');
        Route::get('getNivel','Historia\OrdenController@getNivel');
        Route::post('cambiarFechaServicios', 'Historia\OrdenController@cambiarFechaServicios');
        Route::post('cambiarfecha','Historia\OrdenController@cambiarfecha');

        //consentimientos
        Route::post('verificacionConsentimiento','Historia\OrdenController@verificacionConsentimiento');
        Route::get('consularOrdenes/{id}','Historia\OrdenController@consularOrdenes');

        //alertas
        Route::post('fecthAlertas','Historia\OrdenController@fecthAlertas');
        Route::post('fecthAlergico','Historia\OrdenController@fecthAlergico');
        Route::post('fecthdesabastesimiento','Historia\OrdenController@fecthdesabastesimiento');
        Route::post('confirmacionAlerta','Historia\OrdenController@confirmacionAlerta');
        Route::get('fechtAlertas','Historia\OrdenController@fechtAlertas');
        Route::post('generarExcelAlertas','Historia\OrdenController@generarExcelAlertas');


    });
});
