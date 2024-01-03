<?php

Route::group(['prefix' => 'recomendacion-cup'], function () {

    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::get('all',         'RecomendacionCups\RecomendacionCupController@all');
        Route::post('create',   'RecomendacionCups\RecomendacionCupController@store');
        Route::post('edit', 'RecomendacionCups\RecomendacionCupController@update');
        Route::post('editState/{id_recomendacion}', 'RecomendacionCups\RecomendacionCupController@updateState');
        Route::post('getRecomendacion', 'RecomendacionCups\RecomendacionCupController@getRecomendacion');
    });
});
