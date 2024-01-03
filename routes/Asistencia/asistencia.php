<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'asistencia'],function(){
    Route::group(['middleware'=>'auth:api','throttle:60,1'],function(){
        Route::post('saveAsistencia','Asistencia\AsistenciaController@saveAsistencia');
        Route::post('reporteAsistencia','Asistencia\AsistenciaController@reporteAsistencia');
    });
});
