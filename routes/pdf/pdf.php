<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'pdf'], function () {
    Route::post('print-pdf',  'PDF\PDFController@print');
    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::post('print-pdf',  'PDF\PDFController@print');
        Route::post('printRipVoucher',  'PDF\PDFController@printRipVoucher');
        Route::post('reporteOcupacional', 'PDF\PDFController@reporteOcupacional');
        Route::post('reporteCaracterizacion', 'PDF\PDFController@reporteCaracterizacion');
    });
});
