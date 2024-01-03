<?php

Route::group(['prefix' => 'helpdesk'], function () {

    Route::group(['middleware' => 'auth:api'], function() {

        // Helpdesk
        Route::post('create',   'Helpdesk\HelpdeskController@store');
        Route::get('pendientesUser',   'Helpdesk\HelpdeskController@pendientesUser');
        Route::post('pendientesArea',   'Helpdesk\HelpdeskController@pendientesArea');
        Route::post('solucion/{helpdesk}',   'Helpdesk\HelpdeskController@solucion');
        Route::post('anular/{helpdesk}',   'Helpdesk\HelpdeskController@anular');
        Route::post('reasignar/{helpdesk}',   'Helpdesk\HelpdeskController@reasignar');
        Route::post('asignar/{helpdesk}',   'Helpdesk\HelpdeskController@asignar');
        Route::post('comentar',   'Helpdesk\HelpdeskController@comentar');
        Route::get('permisos',   'Helpdesk\HelpdeskController@permisos');
        Route::post('solucionadosArea',   'Helpdesk\HelpdeskController@solucionadosArea');
        Route::get('solucionadosUser',   'Helpdesk\HelpdeskController@solucionadosUser');
        Route::post('showcomentariosPublicos',   'Helpdesk\HelpdeskController@showcomentariosPublicos');
        Route::post('showcomentariosPrivados',   'Helpdesk\HelpdeskController@showcomentariosPrivados');
        Route::post('pendientesAsignada',   'Helpdesk\HelpdeskController@pendientesAsignada');
        Route::post('showAsignadosinArea',   'Helpdesk\HelpdeskController@showAsignadosinArea');
        Route::get('permisoUser',   'Helpdesk\HelpdeskController@permisoUser');
        Route::post('permisoAsignadoHelp',   'Helpdesk\HelpdeskController@permisoAsignadoHelp');
        Route::post('respuesta/{helpdesk}',   'Helpdesk\HelpdeskController@respuesta');
        Route::post('showSolucion',   'Helpdesk\HelpdeskController@showSolucion');
        Route::post('showRespuestas',   'Helpdesk\HelpdeskController@showRespuestas');
        Route::post('devolver',   'Helpdesk\HelpdeskController@devolver');
        Route::get('solucionadasAsignada',   'Helpdesk\HelpdeskController@solucionadasAsignada');
        Route::post('informe',   'Helpdesk\HelpdeskController@informe');
        Route::get('sedes',   'Helpdesk\HelpdeskController@sedes');
        Route::post('reabrir',   'Helpdesk\HelpdeskController@reabrir');
        Route::post('calificar',   'Helpdesk\HelpdeskController@calificar');
        Route::post('comentariosReabierto',   'Helpdesk\HelpdeskController@comentariosReabierto');
        Route::post('comentarioAnulado',   'Helpdesk\HelpdeskController@comentarioAnulado');

        // Áreas
        Route::post('storearea', 'Helpdesk\AreahelpController@store');
        Route::get('getarea',   'Helpdesk\AreahelpController@getarea');
        Route::get('getAreaEnable',   'Helpdesk\AreahelpController@getAreaEnable');
        Route::post('updatearea/{areahelp}', 'Helpdesk\AreahelpController@updatearea');
        Route::get('enable/{areahelp}', 'Helpdesk\AreahelpController@enable');
        Route::get('disable/{areahelp}', 'Helpdesk\AreahelpController@disable');

        // Categorias
        Route::post('storecategoria', 'Helpdesk\CategoriahelpController@store');
        Route::get('allcategoria',   'Helpdesk\CategoriahelpController@allcategoria');
        Route::get('getcategoria/{area}',   'Helpdesk\CategoriahelpController@getcategoria');
        Route::post('crearAlerta/{id}',   'Helpdesk\CategoriahelpController@crearAlerta');

        // Actividades
        Route::post('storeactividad', 'Helpdesk\ActividadhelpController@store');
        Route::get('allactividad',   'Helpdesk\ActividadhelpController@allactividad');
        Route::get('getactividad/{categoria}',   'Helpdesk\ActividadhelpController@getactividad');
    });
});
