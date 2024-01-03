<?php

Route::group(['prefix' => 'file'], function () {
    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::post('setFiles/{citapaciente}', 'Files\FileController@setFiles');
        Route::get('getFilesByPaciente/{citapaciente}', 'Files\FileController@getFilesByPaciente');
        Route::get('getFilesByCitaPaciente/{citapaciente}', 'Files\FileController@getFilesByCitaPaciente');

        
        Route::post('facturas/import', 'Files\FileController@import');
    });
});