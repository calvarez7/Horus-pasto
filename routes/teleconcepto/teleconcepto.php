<?php

Route::group(['prefix' => 'teleconcepto'], function () {

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('all',         'Teleconcepto\TeleconceptoController@all');
        Route::post('create',         'Teleconcepto\TeleconceptoController@store');
        Route::put('reply/{tele}',         'Teleconcepto\TeleconceptoController@reply');
        Route::post('pending',         'Teleconcepto\TeleconceptoController@pendingTeleconcepto');
        Route::get('solved/{cedula_paciente}',         'Teleconcepto\TeleconceptoController@solvedTeleconcepto');
        Route::get('counter',        'Teleconcepto\TeleconceptoController@counter');
        Route::post('consolidado',    'Teleconcepto\TeleconceptoController@informeTeleconcepto');
        Route::post('reasignarGIRS',    'Teleconcepto\TeleconceptoController@reasignarGIRS');
        Route::post('actualizarPertinencia',    'Teleconcepto\TeleconceptoController@actualizarPertinencia');
        Route::post('guardar',    'Teleconcepto\TeleconceptoController@guardar');
        Route::post('guardarIntegrantes/{teleconcepto}',    'Teleconcepto\TeleconceptoController@guardarIntegrantes');
        Route::get('girsAuditados',        'Teleconcepto\TeleconceptoController@girsAuditados');
    });
});
