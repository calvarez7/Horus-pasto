<?php

Route::group(['prefix' => 'bodega'], function () {

    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
        Route::get('all',         'Bodegas\BodegaController@all');     //nuevo cambio
        Route::post('create',   'Bodegas\BodegaController@store');
        Route::get('getBodegaByRole',   'Bodegas\BodegaController@getBodegaByRole');
        Route::put('edit/{bodega}', 'Bodegas\BodegaController@update');
        Route::put('available/{bodega}', 'Bodegas\BodegaController@available');
        Route::post('/{bodega}/movimientos',   'Bodegas\BodegaController@movimientos');
        Route::post('/{bodega}/pendientes',   'Bodegas\BodegaController@pendientesBodega');
        Route::get('/{bodega}/saldos',   'Bodegas\BodegaController@saldoBodega');
        Route::post('{bodega}/articulo/all',   'Bodegas\BodegarticuloController@all');
        Route::post('/articulo/allWithoutBodega',   'Bodegas\BodegarticuloController@allWithoutBodega');
        Route::post('/inventariobodega',   'Bodegas\BodegarticuloController@inventariobodega');
        Route::post('/loteporBodega',   'Bodegas\BodegarticuloController@loteporBodega');
        Route::post('/faltaconteo1',   'Bodegas\BodegarticuloController@faltaconteo1');
        Route::post('/faltaconteo2',   'Bodegas\BodegarticuloController@faltaconteo2');
        Route::post('/inventarioOtherStock',   'Bodegas\BodegarticuloController@inventarioOtherStock');
        Route::post('updateCant',   'Bodegas\BodegarticuloController@updateCant');
        Route::post('{bodega}/articulo/allArticulos',   'Bodegas\BodegarticuloController@allArticulos');
        Route::post('{bodega}/articulo/create',   'Bodegas\BodegarticuloController@store');
        Route::put('{bodega}/articulo/edit/{detallearticulo}', 'Bodegas\BodegarticuloController@update');
        Route::put('{bodega}/articulo/available/{detallearticulo}',    'Bodegas\BodegarticuloController@available');
        Route::post('{bodega}/ordencompra/allOrdens',   'Bodegas\OrdencompraController@allOrdens');
        Route::post('{bodega}/ordencompra/allacepted',   'Bodegas\OrdencompraController@allacepted');
        Route::post('{bodega}/ordencompra/create',   'Bodegas\OrdencompraController@store');
        Route::put('{bodega}/ordencompra/acceptRequest',   'Bodegas\OrdencompraController@acceptRequest');
        Route::post('{bodega}/solicitud/ajuste/create',   'Bodegas\BodegaController@solicitudAjusteExistencia');
        Route::post('{bodega}/solicitud/ajuste',   'Bodegas\BodegaController@getSolicitudAjusteExistencia');
        Route::post('{bodega}/solicitud/trasladosAprobados',   'Bodegas\BodegaController@trasladosAprobados');
        Route::post('{bodega}/movimiento/ajuste',   'Bodegas\BodegaController@createAjusteExistencia');
        Route::get('{bodega}/solicitud/getSolicitudByBodegaDestino',   'Bodegas\SolicitudbodegasController@getSolicitudByBodegaDestino');
        Route::get('{bodega}/solicitud/trasladosAprobados',   'Bodegas\SolicitudbodegasController@trasladosAprobados');
        Route::post('/solicitud/create',   'Bodegas\SolicitudbodegasController@store');
        Route::post('/solicitud/cancelTransfer/{solicitud}',   'Bodegas\SolicitudbodegasController@cancelTransfer');
        Route::post('/{bodega}/historico/dispensado',   'Bodegas\BodegaController@historicoDispensado');
        Route::post('/{bodega}/historico/entradaFactura',   'Bodegas\BodegaController@historicoEntradaFactura');
        Route::post('/{bodega}/historico/traslado',   'Bodegas\BodegaController@historicoTraslado');
        Route::get('/{bodega}/historico/dispensado/exportar',   'Bodegas\BodegaController@exphistoricoDispensado');
        Route::post('/{bodega}/historico/entradaFactura/exportar',   'Bodegas\BodegaController@exphistoricoEntradaFactura');
        Route::post('/{bodega}/historico/traslado/exportar',   'Bodegas\BodegaController@exphistoricoTraslado');
        Route::get('/inventario/generar/{codigoBodega}', 'Bodegas\InventarioController@generar');
        Route::get('/inventario/consulta', 'Bodegas\InventarioController@inventariosIncompletos');
        Route::get('/inventario/consulta/conteos/{codigoInventario}', 'Bodegas\InventarioController@conteosIncompletos');
        Route::get('/inventarios', 'Bodegas\InventarioController@inventarios');
        Route::get('/inventarios/detalles/{codigoInventario}', 'Bodegas\InventarioController@detalleInventarios');
        Route::post('/lote/editar/{lote}',   'Bodegas\BodegarticuloController@updateLote');
        Route::post('/buscar/lote/{codigoInventario}', 'Bodegas\InventarioController@buscarLoteEnCero');
        Route::get('/inventario/agregar/{codigoInventario}/{codigoLote}', 'Bodegas\InventarioController@agregarLoteEnCero');
        Route::get('usuariosDispensa/{bodega}',   'Bodegas\BodegaController@usuariosDispensa');
        Route::get('proveedores/lista',   'Bodegas\ProveedoreController@index');
        Route::post('proveedores/guardar',   'Bodegas\ProveedoreController@store');
        Route::post('proveedores/edita/{proveedor}',   'Bodegas\ProveedoreController@update');
        Route::post('/{bodega}/historico/dispensado/detalle',   'Bodegas\BodegaController@historicoDispensadoDetalle');
        Route::post('articulo/getMedicamentos',   'Bodegas\BodegarticuloController@getMedicamentos');
        Route::post('{bodega}/{sede}/ordencompra/getSolicitudesPendientes',   'Bodegas\OrdencompraController@getSolicitudesPendientes');
        Route::get('{solicitud}/{proveedor}/ordencompra/getSolicitudesPendientesDetalles',   'Bodegas\OrdencompraController@getSolicitudesPendientesDetalles');
        Route::post('{bodega}/ordencompra/getSolicitudesEntradas',   'Bodegas\OrdencompraController@getSolicitudesEntradas');
        Route::post('autorizacion/{estado}/{proveedor}',   'Bodegas\OrdencompraController@generarAutorizacion');
        Route::get('getDetallesDisponibles/{codesumi}/{solicitudcompra}','Bodegas\OrdencompraController@getDetallesDisponibles');
        Route::post('articulo/getDetallesArticulos',   'Bodegas\BodegarticuloController@getDetallesArticulos');
        Route::post('cargaMasiva',   'Bodegas\BodegarticuloController@cargaMasiva');
        Route::get('/{bodega}/{detalleArticulo}/getLotesDetalleArticulo',   'Bodegas\BodegarticuloController@getLotesDetalleArticulo');
        Route::post('{bodega}/solicitudCompraFacturas',   'Bodegas\BodegaController@solicitudCompraFacturas');
        Route::post('findloteAjuste',   'Bodegas\BodegarticuloController@findloteAjuste');
        Route::post('buscarDetalle', 'Bodegas\InventarioController@buscarDetalle');
        Route::post('/inventario/agregarDetalle/{codigoInventario}', 'Bodegas\InventarioController@agregarDetalle');
        Route::post('/articulo/allMedicamentos',   'Bodegas\BodegarticuloController@allMedicamentos');
        Route::get('min-max/{bodega}','Bodegas\BodegaController@minMax');
        Route::get('detallesCodesumisReposicion/{codigo}/{bodega}','Bodegas\BodegarticuloController@detallesCodesumisReposicion');

    });
});
