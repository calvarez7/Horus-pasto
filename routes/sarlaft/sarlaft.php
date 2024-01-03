<?php
Route::group(['prefix' => 'sarlaft'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
		Route::get('all/{tipo}', 'sarlaft\SarlaftController@all');
		Route::post('guardarRevision', 'sarlaft\SarlaftController@guardarRevision');
		Route::get('revisionBuscar/{estado}', 'sarlaft\SarlaftController@revisionBuscar');
		Route::put('updateRevision/{idsarlaft}','sarlaft\SarlaftController@updateRevision');
		Route::get('adjuntosSarlafts/{sarlaft_id}', 'sarlaft\SarlaftController@adjuntosSarlafts');
		Route::post('envioCorreo/{adjuntos}','sarlaft\SarlaftController@envioCorreo');
		Route::get('consultaRevision/{sarlafts_id}', 'sarlaft\SarlaftController@consultarRevision');
        Route::get('adjuntosRevision/{idRevision}', 'sarlaft\SarlaftController@adjuntosRevision');
        Route::post('guardarAdjuntosrevision/{idRevision}','sarlaft\SarlaftController@guardarAdjuntos');
        Route::put('updateNotifiacion/{idsarlaft}','sarlaft\SarlaftController@updateNotifiacion');
	});
});
