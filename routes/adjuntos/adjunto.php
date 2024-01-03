<?php

Route::group(['prefix' => 'adjunto'], function () {
	Route::post('get',   'Adjuntos\AdjuntoController@get');
    Route::post('getType',   'Adjuntos\AdjuntoController@getType');
});