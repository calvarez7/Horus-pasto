<?php
    Route::group(['prefix' => 'password', 'throttle:60,1'], function () {
            Route::post('create', 'Auth\PasswordResetController@create');
            Route::post('find', 'Auth\PasswordResetController@find');
            Route::post('reset', 'Auth\PasswordResetController@reset');
    });
