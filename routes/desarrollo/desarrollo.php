<?php

use Illuminate\Support\Facades\Route;



Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {

    Route::prefix('desarrollo')->group(function(){

        Route::prefix('actividad')->group(function(){
            Route::get('/listar','Desarrollo\ActividadDesarrolloController@listar');
            Route::get('/consultar/{id}','Desarrollo\ActividadDesarrolloController@consultar');
            Route::post('/guardar','Desarrollo\ActividadDesarrolloController@guardar');
            Route::put('/actualizar/{id}','Desarrollo\ActividadDesarrolloController@actualizar');
            Route::post('/cambiarEstado/{id}','Desarrollo\ActividadDesarrolloController@cambiarEstado');
            Route::post('/actualizarFecha/{id}','Desarrollo\ActividadDesarrolloController@actualizarFecha');
        });

        Route::prefix('archivo')->group(function(){
            Route::get('/consultar/{id}','Desarrollo\ArchivoDesarrolloController@consultar');
            Route::post('/guardar/{id}','Desarrollo\ArchivoDesarrolloController@guardar');
            Route::get('/eliminar/{id}','Desarrollo\ArchivoDesarrolloController@eliminar');


        });

        Route::prefix('comentario')->group(function() {
            Route::get('/consultar/{id}', 'Desarrollo\ComentarioDesarrolloController@consultar');
            Route::post('/guardar/{id}', 'Desarrollo\ComentarioDesarrolloController@guardar');
            Route::put('/actualizar/{id}', 'Desarrollo\ComentarioDesarrolloController@actualizar');
            Route::delete('/eliminar/{id}', 'Desarrollo\ComentarioDesarrolloController@eliminar');
        });

    });

});





