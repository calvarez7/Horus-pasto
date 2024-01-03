<?php

Route::group(['prefix' => 'imagenologia'], function () {
    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function () {
        Route::post('allgestion', 'Imagenologia\ImagenologiaController@gestion');
        Route::post('saveEnfermeria', 'Imagenologia\ImagenologiaController@saveEnfermeria');
        Route::post('saveInsumos', 'Imagenologia\ImagenologiaController@saveInsumos');
        Route::get('insumos', 'Imagenologia\ImagenologiaController@insumos');
        Route::get('medicamentos', 'Imagenologia\ImagenologiaController@medicamentos');
        Route::post('saveEventos', 'Imagenologia\ImagenologiaController@saveEventos');
        Route::post('saveEstudio', 'Imagenologia\ImagenologiaController@saveEstudio');
        Route::post('acomuladoDosis', 'Imagenologia\ImagenologiaController@acomuladoDosis');
        Route::put('devolverAdmisiones/{cita}', 'Imagenologia\ImagenologiaController@devolverAdmisiones');
        Route::put('confirmarEnfermeria/{cita}', 'Imagenologia\ImagenologiaController@confirmarEnfermeria');
        Route::put('confirmarTecnologo/{cita}', 'Imagenologia\ImagenologiaController@confirmarTecnologo');
        Route::post('observacion_adjunto/{cita}', 'Imagenologia\ImagenologiaController@observacionesyadjuntos_admisiones');
        Route::post('notasEnfermeria', 'Imagenologia\ImagenologiaController@notasEnfermeria');
        Route::post('estudioTecnologo', 'Imagenologia\ImagenologiaController@estudioTecnologo');
        Route::post('createinsumo', 'Imagenologia\ImagenologiaController@createinsumo');
        Route::post('createmedicamento', 'Imagenologia\ImagenologiaController@createmedicamento');
        Route::post('plantillas', 'Imagenologia\ImagenologiaController@plantillas');
        Route::post('informacionCita', 'Imagenologia\ImagenologiaController@informacionCita');
        Route::put('enviarcitaFacturacion/{citapaciente}', 'Imagenologia\ImagenologiaController@enviarcitaFacturacion');
        Route::put('saveTecnica', 'Imagenologia\ImagenologiaController@saveTecnica');
        Route::post('insumosGuardados', 'Imagenologia\ImagenologiaController@insumosGuardados');
        Route::put('cambiarEstadoinsumo/{gastos_imagenologia}', 'Imagenologia\ImagenologiaController@cambiarEstadoinsumo');
        Route::get('tipoCita', 'Imagenologia\ImagenologiaController@tipoCita');
        Route::get('userImagenologia', 'Imagenologia\ImagenologiaController@userImagenologia');
        Route::post('createPlantilla', 'Imagenologia\ImagenologiaController@createPlantilla');
        Route::get('allPlantilla', 'Imagenologia\ImagenologiaController@allPlantilla');
        Route::post('addplantillaUser', 'Imagenologia\ImagenologiaController@addplantillaUser');
        Route::post('allPlantillaUsers', 'Imagenologia\ImagenologiaController@allPlantillaUsers');
        Route::post('addnotaclaratoria', 'Imagenologia\ImagenologiaController@addnotaclaratoria');
        Route::post('notasAclaratorias', 'Imagenologia\ImagenologiaController@notasAclaratorias');
    });
});
