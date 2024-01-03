<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'reporteCita'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
    Route::post('reporteCitas', 'ReporteCitas\ReporteCitasController@reporteCitas');
	});
});
