<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'fias'], function () {

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('all', 'Reps\RepController@all');
        Route::post('allCacRcv', 'Fias\FiasController@allCacRcv');
        Route::post('allCancerFias', 'Fias\FiasController@allCancerFias');
        Route::post('allHemofiliaFias', 'Fias\FiasController@allHemofiliaFias');
        Route::post('reportRcv', 'Fias\FiasController@reportRcv');
        Route::get('counterRCV',  'Fias\FiasController@counterRCV');
        Route::post('consulta', 'Fias\FiasController@consulta');
        Route::post('consultaCancer', 'Fias\FiasController@consultaCancer');
        Route::post('consultaHemofilia', 'Fias\FiasController@consultaHemofilia');
        Route::post('historicoSignos', 'Fias\FiasController@historicoSignos');
        Route::post('histoPacienteVital', 'Fias\FiasController@histoPacienteVital');
        Route::get('sedes',  'Fias\FiasController@sedes');
        Route::post('allArtritis', 'Fias\FiasController@allArtritis');
        Route::post('consultartritis', 'Fias\FiasController@consultartritis');
        Route::post('allHuerfanas', 'Fias\FiasController@allHuerfanas');
        Route::post('consulthuerfanas', 'Fias\FiasController@consulthuerfanas');
        Route::post('allTransplantes', 'Fias\FiasController@allTransplantes');
        Route::post('consultTransplantes', 'Fias\FiasController@consultTransplantes');

        /** Descargar fias */
        Route::post('descargar', 'Fias\FiasController@descargar');  
        Route::post('descargarCac', 'Fias\FiasController@descargarCac');  

    });
});
