<?php


Route::group(['prefix' => 'detallearticulo'], function () {

    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::get('all',         'Articulos\DetallearticuloController@all');
        Route::post('detalleMedicamento', 'Articulos\DetallearticuloController@detalleMedicamento');
        Route::post('create',   'Articulos\DetallearticuloController@store');
        Route::put('edit/{detallearticulo}', 'Articulos\DetallearticuloController@update');
        Route::get('detalle_medicamentos', 'Articulos\DetallearticuloController@detalle_medicamentos');
        Route::get('detalle_insumo_dispositivo', 'Articulos\DetallearticuloController@detalle_insumo_dispositivo');
        Route::get('getitulares', 'Articulos\DetallearticuloController@getitulares');
        Route::get('getMedicamentos', 'Articulos\DetallearticuloController@getMedicamentos');
        Route::get('getGrupos', 'Articulos\DetallearticuloController@getGrupos');
        Route::get('getTipos', 'Articulos\DetallearticuloController@getTipos');
        Route::get('getGruposTerapeuticos', 'Articulos\DetallearticuloController@getGruposTerapeuticos');
        Route::get('getSubGruposTerapeuticos', 'Articulos\DetallearticuloController@getSubGruposTerapeuticos');
        Route::get('getFormasFarmaceuticas', 'Articulos\DetallearticuloController@getFormasFarmaceuticas');
        Route::get('getViasAdministracion', 'Articulos\DetallearticuloController@getViasAdministracion');
        Route::get('getLineasBase', 'Articulos\DetallearticuloController@getLineasBase');
        Route::get('getUnidadMedida', 'Articulos\DetallearticuloController@getUnidadMedida');
        Route::get('getCums/{expediente}', 'Articulos\DetallearticuloController@getCums');
        Route::get('findCum/{cumValidacion}', 'Articulos\DetallearticuloController@findCum');
        Route::get('proveedoresMedicamentos', 'Articulos\DetallearticuloController@getProveedorMedicamentos');
        Route::get('getDetallePrecio/{detalle}', 'Articulos\DetallearticuloController@getDetallePrecio');

        Route::post('saveCodesumi/{tipo}', 'Articulos\DetallearticuloController@saveCodesumi');
        Route::post('saveDetalle/{tipo}', 'Articulos\DetallearticuloController@saveDetalle');
        Route::post('saveDetallePrecio', 'Articulos\DetallearticuloController@saveDetallePrecio');
        Route::post('editDetallePrecio', 'Articulos\DetallearticuloController@editDetallePrecio');

        //alertas
        Route::post('createMensaje', 'Articulos\DetallearticuloController@createMensaje');
        Route::get('getMensajes', 'Articulos\DetallearticuloController@getMensajes');
        Route::post('updateMensaje', 'Articulos\DetallearticuloController@updateMensaje');
        Route::get('disableMensaje/{id}', 'Articulos\DetallearticuloController@disableMensaje');
        Route::get('enableMensaje/{id}', 'Articulos\DetallearticuloController@enableMensaje');
        Route::post('createTipo', 'Articulos\DetallearticuloController@createTipo');
        Route::get('getTiposAlertas', 'Articulos\DetallearticuloController@getTiposAlertas');
        Route::post('updateTipo', 'Articulos\DetallearticuloController@updateTipo');
        Route::get('disableTipos/{id}', 'Articulos\DetallearticuloController@disableTipos');
        Route::get('enableTipo/{id}', 'Articulos\DetallearticuloController@enableTipo');
        Route::get('getPrincipioActivo', 'Articulos\DetallearticuloController@getPrincipioActivo');
        Route::post('createAlerta', 'Articulos\DetallearticuloController@createAlerta');
        Route::post('createAlertaMedicamento', 'Articulos\DetallearticuloController@createAlertaMedicamento');
        Route::post('updatePrincipal', 'Articulos\DetallearticuloController@updatePrincipal');
        Route::get('enablePrincipal/{id}', 'Articulos\DetallearticuloController@enablePrincipal');
        Route::get('disablePrincipal/{id}', 'Articulos\DetallearticuloController@disablePrincipal');
        Route::post('createPrincipal', 'Articulos\DetallearticuloController@createPrincipal');
        Route::get('getPrincipal', 'Articulos\DetallearticuloController@getPrincipal');
        Route::get('getHistorico/{id}', 'Articulos\DetallearticuloController@getHistorico');
        Route::get('disableHistoricoAlertas/{id}', 'Articulos\DetallearticuloController@disableHistoricoAlertas');
        Route::get('disableHistoricoAlertasMedicamento/{id}', 'Articulos\DetallearticuloController@disableHistoricoAlertasMedicamento');
        Route::get('enableHistoricoAlertasMedicamento/{id}', 'Articulos\DetallearticuloController@enableHistoricoAlertasMedicamento');
        Route::get('enableHistoricoAlertas/{id}', 'Articulos\DetallearticuloController@enableHistoricoAlertas');
        Route::get('disableCodesumi/{id}', 'Articulos\DetallearticuloController@disableCodesumi');
        Route::get('enableCodesumi/{id}', 'Articulos\DetallearticuloController@enableCodesumi');
        Route::post('updateAlerta', 'Articulos\DetallearticuloController@updateAlerta');
        Route::post('updateAlertaMedicamento', 'Articulos\DetallearticuloController@updateAlertaMedicamento');
        Route::get('getCodesumi', 'Articulos\DetallearticuloController@getCodesumi');
        Route::post('createCodesumi', 'Articulos\DetallearticuloController@createCodesumi');
        Route::get('getCodesumiAlerta', 'Articulos\DetallearticuloController@getCodesumiAlerta');
        Route::post('updateCodesumi', 'Articulos\DetallearticuloController@updateCodesumi');
        // EXPORT VADEMECUM
        Route::post('exportVademecum', 'Articulos\DetallearticuloController@exportVademecum');

    });
});
