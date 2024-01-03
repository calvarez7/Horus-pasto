<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'pacienteantecedente'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
    Route::get('all', 'Historia\PacienteanteceController@all');
    Route::post('saveAntecedentes', 'Historia\PacienteanteceController@saveAntecedentes');
    Route::post('otrosAntecedentes', 'Historia\PacienteanteceController@otrosAntecedentes');
    Route::post('fetchOtrosAntecedentes', 'Historia\PacienteanteceController@fetchOtrosAntecedentes');
    Route::post('fetchAnt', 'Historia\PacienteanteceController@fetchAnt');
    Route::get('antecedentes/{citapaciente}', 'Historia\PacienteanteceController@antecedentes');
    Route::get('getLastAntecedentesPersonales', 'Historia\PacienteanteceController@getLastAntecedentesPersonales');
    Route::get('getLastAntecedentesFamiliares', 'Historia\PacienteanteceController@getLastAntecedentesFamiliares');
    Route::post('antecedentesFarmacologia', 'Historia\PacienteanteceController@antecedentesFarmacologia');
    Route::get('{citapaciente}/getToxicologicos', 'Historia\PacienteanteceController@getToxicologicos');
    Route::get('antecedentesOcupacional/{cita_paciente_id}', 'Historia\PacienteanteceController@antecedentesOcupacional');
    Route::post('saveAntecedentesOcupacional', 'Historia\PacienteanteceController@saveAntecedentesOcupacional');

	});
});
