<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'agenda'], function () {
	Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function() {
		Route::post('create',   'Agendas\AgendaController@store');
		Route::post('change',   'Agendas\AgendaController@saveChangeCitas');
        Route::get('all',         'Agendas\AgendaController@all');
        Route::post('agendaDisponible',  'Agendas\AgendaController@agendaDisponible');
        Route::get('agendaDisponible/especialidades',  'Agendas\AgendaController@agendaEspecialidad');
        Route::post('agendaDisponible/sedes',  'Agendas\AgendaController@agendaSede');
        Route::get('enabled',        'Agendas\AgendaController@enabled');
        Route::put('edit/{agenda}', 'Agendas\AgendaController@update');
        Route::put('cancelar/{agenda}', 'Agendas\AgendaController@cancelarAgenda');
        Route::put('bloquear/{agenda}', 'Agendas\AgendaController@bloquearAgenda');
        Route::get('show/{agenda}',   'Agendas\AgendaController@show');
        Route::post('agendaDisponible', 'Agendas\AgendaController@agendaDisponible');
        Route::post('agendamedicos', 'Agendas\AgendaController@agendamedicos');
        Route::put('inhabilitaragenda/{agenda}', 'Agendas\AgendaController@inhabilitarAgenda');
        Route::post('exportAuditoria', 'Agendas\AgendaController@exportAuditoria');
        Route::post('reporteGrupales', 'Agendas\AgendaController@reporteGrupales');
        Route::get('sedesAgendas', 'Agendas\AgendaController@sedesAgendas');

	});
});
