<?php
Route::group(['prefix' => 'm202'], function () {
    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function () {
        Route::post('validator', 'M202\M202Controller@validator');
        Route::post('exportError', 'M202\M202Controller@exportErrors');
        Route::post('validateExist', 'M202\M202Controller@validateExist');
        Route::post('savePartial202', 'M202\M202Controller@savePartial202');
        Route::post('certificate/{id}', 'M202\M202Controller@certificate');
        Route::post('save202', 'M202\M202Controller@save202');
        Route::get('loadInspected', 'M202\M202Controller@loadInspected');
        Route::get('file202/{id}', 'M202\M202Controller@file202');
        Route::get('reject202/{id}', 'M202\M202Controller@reject202');
        Route::post('consolidate202', 'M202\M202Controller@consolidate202');
    });
});
