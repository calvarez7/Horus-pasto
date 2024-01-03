<?php

Route::group(['prefix' => 'solicitud'], function () {
    Route::group(['middleware' => 'auth:api'], function() {
        Route::post('pendientes',   'Solicitudes\SolicitudesController@pendientes');
        Route::get('allTipoSolicitud', 'Redvital\RevitalRadicacionController@allTipoSolicitud');
        Route::post('createSolicitud',   'Redvital\RevitalRadicacionController@createSolicitud');
        Route::post('updateSolicitud',   'Redvital\RevitalRadicacionController@updateSolicitud');
        Route::get('getSolicitudUsers/{solicitud}',   'Redvital\RevitalRadicacionController@getSolicitudUsers');
        Route::post('inactivarUserSolicitud/{user}',   'Redvital\RevitalRadicacionController@inactivarUserSolicitud');
        Route::post('pendientesGestion',   'Solicitudes\SolicitudesController@pendientesGestion');
        Route::post('pendientesAsignadas',   'Solicitudes\SolicitudesController@pendientesAsignadas');
        Route::post('comentar',   'Solicitudes\SolicitudesController@comentar');
        Route::post('showcomentariosPrivados',   'Solicitudes\SolicitudesController@showcomentariosPrivados');
        Route::post('asignar',   'Solicitudes\SolicitudesController@asignar');
        Route::post('respuesta',   'Solicitudes\SolicitudesController@respuesta');
        Route::post('devolver',   'Solicitudes\SolicitudesController@devolver');
        Route::post('showDevoluciones',   'Solicitudes\SolicitudesController@showDevoluciones');
        Route::post('solucionadosGestion',   'Solicitudes\SolicitudesController@solucionadosGestion');
        Route::post('solucionadosAsignadas',   'Solicitudes\SolicitudesController@solucionadosAsignadas');
        Route::post('solucionados',   'Solicitudes\SolicitudesController@solucionados');
        Route::post('saveGestion',   'Solicitudes\SolicitudesController@saveGestion');
        Route::post('informe',   'Solicitudes\SolicitudesController@informe');
    });

    Route::post('showcomentariosPublicos',   'Solicitudes\SolicitudesController@showcomentariosPublicos');
});
