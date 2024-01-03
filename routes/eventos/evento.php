<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'evento'], function () {
    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {

        //Eventos
        Route::get('all', 'Eventos\EventoController@all');
        Route::get('getclasificacion/{evento}', 'Eventos\EventoController@getclasificacion');
        Route::get('allsumimedical', 'Eventos\EventoController@allsumimedical');
        Route::get('getTipoevento/{clasificacion}', 'Eventos\EventoController@getTipoevento');
        Route::get('allsuceso', 'Eventos\EventoController@allsuceso');
        Route::get('allclasificaciones', 'Eventos\EventoController@allclasificaciones');
        Route::get('alltipoactividades', 'Eventos\EventoController@alltipoactividades');
        Route::post('saveSuceso', 'Eventos\EventoController@saveSuceso');
        Route::post('updateEstadoSuceso/{eventoId}', 'Eventos\EventoController@updateEstadoSuceso');
        Route::post('saveClasificacion', 'Eventos\EventoController@saveClasificacion');
        Route::post('saveTipoactividad', 'Eventos\EventoController@saveTipoactividad');

        //Eventos Adversos
        Route::post('create', 'Eventos\EventoAdversoController@create');
        Route::post('getpendientes', 'Eventos\EventoAdversoController@getpendientes');
        Route::post('saveAnalisis', 'Eventos\EventoAdversoController@saveAnalisis');
        Route::post('getAnalisis', 'Eventos\EventoAdversoController@getAnalisis');
        Route::post('closeAnalisis', 'Eventos\EventoAdversoController@closeAnalisis');
        Route::post('getcerrados', 'Eventos\EventoAdversoController@getcerrados');
        Route::post('informe', 'Eventos\EventoAdversoController@informe');
        Route::post('updateArea', 'Eventos\EventoAdversoController@updateArea');
        Route::post('history', 'Eventos\EventoAdversoController@history');
        Route::get('getUserNotification', 'Eventos\EventoAdversoController@getUserNotification');
        Route::post('createUserNotification', 'Eventos\EventoAdversoController@createUserNotification');
        Route::get('sedesUserNotification/{user}', 'Eventos\EventoAdversoController@sedesUserNotification');
        Route::post('updateUserNotification', 'Eventos\EventoAdversoController@updateUserNotification');
        Route::post('asignar/{evento}', 'Eventos\EventoAdversoController@asignar');
        Route::get('getpendientesAsignado', 'Eventos\EventoAdversoController@getpendientesAsignado');
        Route::post('anular/{evento}', 'Eventos\EventoAdversoController@anular');
        Route::post('preAnalisis', 'Eventos\EventoAdversoController@preAnalisis');
        Route::post('addAcciones', 'Eventos\EventoAdversoController@addAcciones');
        Route::post('editAcciones/{acciones_insegura}', 'Eventos\EventoAdversoController@editAcciones');
        Route::post('deleteAcciones/{acciones_insegura}', 'Eventos\EventoAdversoController@deleteAcciones');
        Route::post('savepreAnalisis/{evento}', 'Eventos\EventoAdversoController@savepreAnalisis');
        Route::post('addAccionesMejoras', 'Eventos\EventoAdversoController@addAccionesMejoras');
        Route::post('editAccionMejora/{acciones_mejoras}', 'Eventos\EventoAdversoController@editAccionMejora');
        Route::post('deleteAccioneMejora/{acciones_mejoras}', 'Eventos\EventoAdversoController@deleteAccioneMejora');
        Route::post('reasignar/{evento}', 'Eventos\EventoAdversoController@reasignar');
        Route::get('getcerradosAsignado', 'Eventos\EventoAdversoController@getcerradosAsignado');
        Route::get('buscarAnalisis/{id}', 'Eventos\EventoAdversoController@buscarAnalisis');
        Route::get('getAccion_inseguras/{id}', 'Eventos\EventoAdversoController@getAccion_inseguras');
        Route::get('getAccion_mejoras/{id}', 'Eventos\EventoAdversoController@getAccion_mejoras');
        Route::post('adjuntos', 'Eventos\EventoAdversoController@adjuntos');
        Route::post('getPlanMejoras', 'Eventos\EventoAdversoController@getPlanMejoras');
        Route::post('savePlanMejora', 'Eventos\EventoAdversoController@savePlanMejora');
        
    });
});