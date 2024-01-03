<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'municipio'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
    Route::get('all', 'Municipios\MunicipioController@all');
    Route::get('lista', 'Municipios\MunicipioController@listaMunicipioDepartamento');
    Route::get('secretaria', 'Municipios\MunicipioController@secretaria');
    Route::get('DatosGeograficos/{municipio}', 'Municipios\MunicipioController@DatosGeograficos');
    Route::get('DatosGeosede/{municipio}', 'Municipios\MunicipioController@DatosGeosede');
    });
});
