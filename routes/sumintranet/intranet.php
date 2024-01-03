<?php


Route::group(['prefix' => 'intranet'], function () {
    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function () {

        /* Carrusel */
        Route::get('carrusel',  'Sumintranet\CarruselController@carrusel');
        Route::get('index',  'Sumintranet\CarruselController@index');
        Route::post('create',  'Sumintranet\CarruselController@store');
        Route::post('updatecarrusel',  'Sumintranet\CarruselController@updatecarrusel');
        Route::put('estado/{id}',   'Sumintranet\CarruselController@estado');

        /* Blog */
        Route::get('blog',  'Sumintranet\BlogController@blog');
        Route::get('blogs',  'Sumintranet\BlogController@blogs');
        Route::post('crear',  'Sumintranet\BlogController@store');
        Route::put('cambiar/{id}',   'Sumintranet\BlogController@estado');
        Route::post('actualizar',   'Sumintranet\BlogController@update');
        Route::post('opcion/{id}',  'Sumintranet\BlogController@opcion');
        Route::post('opcions',  'Sumintranet\BlogController@opcion');
        Route::get('like',  'Sumintranet\BlogController@like');
        Route::get('detalle/{id}',  'Sumintranet\BlogController@detalle');
        Route::get('verusuario/{id}',  'Sumintranet\BlogController@verusuarios');

        /* Multimedia */

        Route::get('multimedia',  'Sumintranet\VideoController@multimedia');
        Route::get('tabla',  'Sumintranet\VideoController@index');
        Route::post('video',  'Sumintranet\VideoController@store');
        Route::put('estadovideo/{id}',   'Sumintranet\VideoController@estado');
        Route::post('conteovideo/{id}',  'Sumintranet\VideoController@conteovideo');
        Route::get('verusuarios/{id}',  'Sumintranet\VideoController@verusuarios');
        Route::get('conteousuarios/{id}',  'Sumintranet\VideoController@conteousuario');

        /* Cumpleaños */
        Route::get('cumpleaños',  'Sumintranet\CumpleañosController@fecha');
        Route::post('store/{id}',  'Sumintranet\CumpleañosController@store');
        Route::get('traer',  'Sumintranet\CumpleañosController@traer');
        Route::post('leertodos', 'Sumintranet\CumpleañosController@todos');
        Route::put('leer/{id}', 'Sumintranet\CumpleañosController@update');

        /* Directorio */
        Route::get('directorio',  'Sumintranet\DirectorioController@index');
        Route::get('area',  'Sumintranet\DirectorioController@area');
        Route::get('eps',  'Sumintranet\DirectorioController@eps');
        Route::get('tipoFamilia',  'Sumintranet\DirectorioController@tipoFamilia');
        Route::post('dialogo',  'Sumintranet\DirectorioController@store');
        Route::post('familia',  'Sumintranet\DirectorioController@familias');
        Route::get('lista/{id}', 'Sumintranet\DirectorioController@listarfamilia');
        Route::put('editarempleado/{id}', 'Sumintranet\DirectorioController@editarempleado');
        Route::put('editarfamiliar/{id}', 'Sumintranet\DirectorioController@editarfamiliar');
        Route::post('agregar/{id}', 'Sumintranet\DirectorioController@agregar');
        Route::put('cambiarestado/{id}',   'Sumintranet\DirectorioController@cambiar');

        Route::post('auditoriaCertificado', 'Sumintranet\CertificadosController@auditoriaCertificado');

        Route::get('ver','Sumintranet\Gestiondocumental@ver');
        Route::get('gestion','Sumintranet\Gestiondocumental@index');
        Route::get('buscarcarpeta/{id}','Sumintranet\Gestiondocumental@buscarcarpeta');
        Route::get('showsubcarpetas/{id}','Sumintranet\Gestiondocumental@showsubcarpetas');
        Route::get('versubcarpetas/{id}','Sumintranet\Gestiondocumental@subcarpetas');
        Route::get('vercarpetas','Sumintranet\Gestiondocumental@vercarpetas');

        Route::get('show','Sumintranet\Gestiondocumental@show');
        Route::get('shows/{id}','Sumintranet\Gestiondocumental@shows');

        Route::post('carpeta','Sumintranet\Gestiondocumental@crearcarpeta');
        Route::post('subcarpeta','Sumintranet\Gestiondocumental@crearsubcarpeta');
        Route::post('crearcarpetasubcarpeta','Sumintranet\Gestiondocumental@crearcarpetasubcarpeta');

        Route::post('archivos/{id}/{folder}','Sumintranet\Gestiondocumental@archivos');
        Route::post('AgregarCarpeta','Sumintranet\Gestiondocumental@AgregarCarpeta');
        Route::post('subcapetaAgregarCarpeta','Sumintranet\Gestiondocumental@subcapetaAgregarCarpeta');
        Route::post('subCarpetaEnCarpeta','Sumintranet\Gestiondocumental@subCarpetaEnCarpeta');

        Route::get('mostarSubcarpetaEnSubcarpeta/{id}','Sumintranet\Gestiondocumental@mostarSubcarpetaEnSubcarpeta');

        Route::post('subCarpetaEnSubCarpeta','Sumintranet\Gestiondocumental@subCarpetaEnSubCarpeta');

        Route::post('migas','Sumintranet\Gestiondocumental@migas');

        Route::get('getFiles/{id}/{folder}','Sumintranet\Gestiondocumental@getFiles');

        Route::post('eliminarElemento','Sumintranet\Gestiondocumental@eliminarElemento');

        Route::get('getFiles/{id}/{folder}','Sumintranet\Gestiondocumental@getFiles');

        Route::get('getFilesFilters/{filtro}','Sumintranet\Gestiondocumental@getFilesFilters');

        Route::post('migasAutogeneradas','Sumintranet\Gestiondocumental@migasAutogeneradas');


    });
});
