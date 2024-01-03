<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'referencia'], function () {

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('all',         'Referencia\ReferenciaController@all');
        Route::post('create',         'Referencia\ReferenciaController@newreferencia');
        Route::post('internal_process',         'Referencia\ReferenciaController@internalProcessReferences');
        Route::get('pending/{anexo}',         'Referencia\ReferenciaController@pendingReferences');
        Route::get('inProcess/{anexo}',         'Referencia\ReferenciaController@inProcessReferences');
        Route::get('getReferenciaByBitacora/{bitacora}',         'Referencia\ReferenciaController@getReferenciaByBitacora');
        Route::get('consolidado',         'Referencia\ReferenciaController@informeReferencia');
        Route::get('concurrenciaReporte',         'Referencia\ReferenciaController@concurrenciaReporte');
        Route::get('counter',         'Referencia\ReferenciaController@counter');
        Route::get('bitacora/getBitacora/{bitacora}',         'Referencia\ReferenciaController@getBitacora');
        Route::get('bitacora/getAllMessagesBitacora/{bitacora}',         'Referencia\ReferenciaController@getAllMessagesBitacora');
        Route::post('bitacora/sendMessage/{bitacora}',         'Referencia\ReferenciaController@sendMessage');
        Route::post('bitacora/sendFile/{bitacora}',         'Referencia\ReferenciaController@sendFile');
        Route::put('bitacora/endBitacora/{bitacora}',         'Referencia\ReferenciaController@endBitacora');
        Route::post('gestion/{referencia}',         'Referencia\ReferenciaController@gestionar');
        Route::post('/{referencia}/exportar', 'Referencia\ReferenciaController@exportarreferencia');

        // RUtas de concurrencia
        Route::get('seguimiento', 'Referencia\ReferenciaController@enSeguimiento');
        Route::get('Altaconcurrencia', 'Referencia\ReferenciaController@Altaconcurrencia');
        Route::get('verseguimientos/{referencia}', 'Referencia\ReferenciaController@verseguimientos');
        Route::post('editregistro', 'Referencia\ReferenciaController@editregistro');
        Route::post('registrarCocurrencia', 'Referencia\ReferenciaController@registrarCocurrencia');
        Route::post('crearCocurrencia/{id}', 'Referencia\ReferenciaController@crearCocurrencia');
        Route::post('seguimientoConcurrencia/{id}', 'Referencia\ReferenciaController@seguimientoConcurrencia');
        Route::post('eventoConcurrencia', 'Referencia\ReferenciaController@eventoConcurrencia');
        Route::get('concurrenciaseguridad/{referencia}', 'Referencia\ReferenciaController@concurrenciaseguridad');
        Route::get('concurrenciacentinela/{referencia}', 'Referencia\ReferenciaController@concurrenciacentinela');
        Route::get('concurrencianotificacion/{referencia}', 'Referencia\ReferenciaController@concurrencianotificacion');
        Route::get('costoevitado/{referencia}', 'Referencia\ReferenciaController@costoevitado');
        Route::get('costoevitable/{referencia}', 'Referencia\ReferenciaController@costoevitable');

        //rutas nuevo concurrencia
        Route::post('notaDss/{id}', 'Referencia\ReferenciaController@notaDss');
        Route::post('notaAac/{id}', 'Referencia\ReferenciaController@notaAac');
        Route::post('costoConcurrencia/{id}', 'Referencia\ReferenciaController@costoConcurrencia');
        Route::post('guardarCosto/{id}', 'Referencia\ReferenciaController@guardarCosto');
        Route::get('listarSeguimiento', 'Referencia\ReferenciaController@listarSeguimiento');
        Route::get('listarEventosCentinela/{id}', 'Referencia\ReferenciaController@listarEventosCentinela');
        Route::get('listarEventosSeguridad/{id}', 'Referencia\ReferenciaController@listarEventosSeguridad');
        Route::get('listarNotificacion/{id}', 'Referencia\ReferenciaController@listarNotificacion');
        Route::get('listarCostoEvitado/{id}', 'Referencia\ReferenciaController@listarCostoEvitado');
        Route::get('listarCostoEvitable/{id}', 'Referencia\ReferenciaController@listarCostoEvitable');
        Route::get('counterSeguimiento', 'Referencia\ReferenciaController@counterSeguimiento');
        Route::get('counterReporte', 'Referencia\ReferenciaController@counterReporte');
        Route::get('listarNotas/{id}', 'Referencia\ReferenciaController@listarNotas');
        Route::post('editarRegistrarCocurrencia/{id}', 'Referencia\ReferenciaController@editarRegistrarCocurrencia');
        Route::post('editarSeguimientoCocurrencia/{id}', 'Referencia\ReferenciaController@editarSeguimientoCocurrencia');
        Route::post('guardarEventosCentinela/{id}', 'Referencia\ReferenciaController@guardarEventosCentinela');
        Route::post('guardarEventosSeguridad/{id}', 'Referencia\ReferenciaController@guardarEventosSeguridad');
        Route::post('guardarNotificacion/{id}', 'Referencia\ReferenciaController@guardarNotificacion');
        Route::post('guardarCostoEvitado/{id}', 'Referencia\ReferenciaController@guardarCostoEvitado');
        Route::post('guardarCostoEvitable/{id}', 'Referencia\ReferenciaController@guardarCostoEvitable');
        Route::post('altaConcurrenncia/{id}', 'Referencia\ReferenciaController@altaConcurrenncia');
        Route::post('cuentasMedicas/{id}', 'Referencia\ReferenciaController@cuentasMedicas');
        Route::post('editarCuentasMedicas/{id}', 'Referencia\ReferenciaController@editarCuentasMedicas');
        Route::get('getCuentasMedicas/{id}', 'Referencia\ReferenciaController@getCuentasMedicas');
        Route::post('generarInforme', 'Referencia\ReferenciaController@generarInforme');
        Route::post('eliminarEvento/{id}', 'Referencia\ReferenciaController@eliminarEvento');
        Route::post('censoConcurrencia', 'Referencia\ReferenciaController@censoConcurrencia');
        Route::get('listarCenso', 'Referencia\ReferenciaController@listarCenso');
        Route::post('formato', 'Referencia\ReferenciaController@formato');
        Route::post('abrirRegistro/{referencia_id}', 'Referencia\ReferenciaController@abrirRegistro');

    });
});
