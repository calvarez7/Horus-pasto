<?php

Route::group(['prefix' => 'pqrsf'], function () {
    Route::post('store', 'Pqrsfs\PqrsfController@store');
    Route::post('checkStatus', 'Pqrsfs\PqrsfController@checkStatus');

    Route::group(['middleware' => 'auth:api'], function() {
        Route::post('storeuser', 'Pqrsfs\PqrsfController@store');

        // PqrsfController

        Route::post('update/{pqrsf}', 'Pqrsfs\PqrsfController@update');
        Route::post('pqrfscancel/{pqrsf}', 'Pqrsfs\PqrsfController@pqrfscancel');
        Route::post('assign/{pqrsf}', 'Pqrsfs\PqrsfController@assign');
        Route::post('solution/{pqrsf}', 'Pqrsfs\PqrsfController@solution');
        Route::post('alert', 'Pqrsfs\PqrsfController@alert');
        Route::post('reasignar', 'Pqrsfs\PqrsfController@reasignar');
        Route::post('history', 'Pqrsfs\PqrsfController@historybypaciente');
        Route::get('validationPqrsf/{pqrsf}', 'Pqrsfs\PqrsfController@validationPqrsf');
        Route::post('reclasificar', 'Pqrsfs\PqrsfController@reclasificar');
        Route::post('import', 'Pqrsfs\PqrsfController@import');

        Route::post('saveCups/{pqrsf}', 'Pqrsfs\PqrsfController@saveCups');
        Route::post('savesubcategoria/{pqrsf}', 'Pqrsfs\PqrsfController@savesubcategoria');
        Route::post('actualizarSubcategoria/{pqrsf}', 'Pqrsfs\PqrsfController@actualizarSubcategoria');
        Route::post('saveDetallearticulos/{pqrsf}', 'Pqrsfs\PqrsfController@saveDetallearticulos');
        Route::post('saveAreas/{pqrsf}', 'Pqrsfs\PqrsfController@saveAreas');
        Route::post('ActualizarAreas/{pqrsf}', 'Pqrsfs\PqrsfController@ActualizarAreas');
        Route::post('saveIps/{pqrsf}', 'Pqrsfs\PqrsfController@saveIps');
        Route::post('saveEmpleado/{pqrsf}', 'Pqrsfs\PqrsfController@saveEmpleado');
        Route::post('deletecup', 'Pqrsfs\PqrsfController@deletecup');
        Route::post('deletesubcategoria', 'Pqrsfs\PqrsfController@deletesubcategoria');
        Route::post('deletedetallearticulo', 'Pqrsfs\PqrsfController@deletedetallearticulo');
        Route::post('deletearea', 'Pqrsfs\PqrsfController@deletearea');
        Route::post('deleteips', 'Pqrsfs\PqrsfController@deleteips');
        Route::post('deletempleado', 'Pqrsfs\PqrsfController@deletempleado');
        Route::post('generarInforme', 'Pqrsfs\PqrsfController@generarInforme');

        // PqrsfexternaController
        Route::post('slope',   'Pqrsfs\PqrsfexternaController@slope');
        Route::post('assigned',   'Pqrsfs\PqrsfexternaController@assigned');
        Route::post('pre_solution',   'Pqrsfs\PqrsfexternaController@pre_solution');
        Route::post('solved',   'Pqrsfs\PqrsfexternaController@solved');
        Route::post('countPqrsfs',   'Pqrsfs\PqrsfexternaController@countPqrsfs');

        // PqrsfinternaController
        Route::post('slopeInterna',   'Pqrsfs\PqrsfinternaController@slope');
        Route::post('assignedInterna',   'Pqrsfs\PqrsfinternaController@assigned');
        Route::post('pre_solutionInterna',   'Pqrsfs\PqrsfinternaController@pre_solution');
        Route::post('solvedInterna',   'Pqrsfs\PqrsfinternaController@solved');
        Route::post('countPqrsfsInterna',   'Pqrsfs\PqrsfinternaController@countPqrsfs');

        // PqrsfgestionController
        Route::post('assignedGestion',   'Pqrsfs\PqrsfgestionController@assigned');
        Route::post('countPqrsfsGestion',   'Pqrsfs\PqrsfgestionController@countPqrsfs');
        Route::post('solvedGestion',   'Pqrsfs\PqrsfgestionController@solvedgestion');
        Route::get('permisos', 'Pqrsfs\PqrsfgestionController@permisos');
        Route::post('respuestas', 'Pqrsfs\PqrsfgestionController@respuestas');

        // SubcategoriaController
        Route::get('allSubcategorias',   'Pqrsfs\SubcategoriaController@allSubcategorias');
        Route::post('pqrsfsubcategorias/{pqrsf}',   'Pqrsfs\SubcategoriaController@pqrsfsubcategorias');

        // DetallearticuloController
        Route::post('pqrsfDetallearticulos/{pqrsf}',   'Articulos\DetallearticuloController@pqrsfDetallearticulos');

        // CupController
        Route::post('pqrsfCups/{pqrsf}',   'Cups\CupController@pqrsfCups');

        // PermisoController
        Route::get('permissionpqrsf',   'Permisos\PermisoController@permissionpqrsf');

        // EmpleadoController
        Route::get('allactive',   'Empleados\EmpleadoController@allactive');
        Route::post('empleado/{empleado}',   'Empleados\EmpleadoController@editEmpleado');
        Route::post('pqrsfEmpleados/{pqrsf}',   'Empleados\EmpleadoController@pqrsfEmpleados');

        // AreaController
        Route::get('allareas',   'Areas\AreaController@all');
        Route::post('pqrsfAreas/{pqrsf}',   'Areas\AreaController@pqrsfAreas');

        // SedeproveedorController
        Route::get('getSedePrestadorPqrsf',   'Sedeprestadores\SedeproveedorController@getSedePrestadorPqrsf');
        Route::post('pqrsfIps/{pqrsf}',   'Sedeprestadores\SedeproveedorController@pqrsfIps');
    });

});
