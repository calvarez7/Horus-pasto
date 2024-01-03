<?php

Route::group(['prefix' => 'historia'], function () {

	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {

        Route::get('UserLogin', 'Historia\HistoriaController@UserLogin');

	});
});