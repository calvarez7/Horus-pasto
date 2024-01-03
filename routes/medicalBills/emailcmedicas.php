<?php

Route::group(['prefix' => 'emailcmedica'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::post('create',   'MedicalBills\EmailMedicalBillsController@store');
        Route::get('all',   'MedicalBills\EmailMedicalBillsController@all');
        Route::put('edit/{email_cmedica}',   'MedicalBills\EmailMedicalBillsController@update');
	});
});