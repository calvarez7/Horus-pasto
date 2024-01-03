<?php

Route::group(['prefix' => 'cobro'], function () {
    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function () {
        Route::get('getAllPaciente/{identificacion}', 'Citas\CobroController@getCobrosByPaciente');
        Route::get('getHistoryCobroPaciente/{identificacion}', 'Citas\CobroController@getHistoryCobroPaciente');
        Route::post('generate/{cobro}', 'Citas\CobroController@generate');
        Route::post('getPDF', 'Citas\CobroController@getPDF');
        Route::post('getPDFRecibo', 'Citas\CobroController@getPDFRecibo');
        Route::post('getAnexo3', 'Citas\CobroController@getAnexo3');
        Route::get('validarNumeroReferencia/{numeroReferencia}','Citas\CobroController@validarNumeroReferencia');
        Route::post('informe', 'Citas\CobroController@generarInforme');
        Route::post('getPDFMedicamentos', 'Citas\CobroController@getPDFMedicamentos');


    });
});
