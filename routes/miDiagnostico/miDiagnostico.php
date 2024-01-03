<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'miDaignostico'], function () {
    Route::post('', 'Midiagnostico\MidiagnosticoController@store');
});
