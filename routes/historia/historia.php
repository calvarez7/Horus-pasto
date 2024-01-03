<?php
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'historia'], function () {
    Route::group(['middleware' => 'auth:api', 'throttle:60,1'], function () {
        Route::get('UserLogin', 'Historia\HistoriaController@UserLogin');
        Route::get('fetchMotivoConsulta/{cita_paciente_id}', 'Historia\HistoriaController@fetchMotivoConsulta');
        Route::get('fetchRevisionSistemas/{cita_paciente_id}', 'Historia\HistoriaController@fetchRevisionSistemas');
        Route::post('fetchAntecedentePersonal', 'Historia\HistoriaController@fetchAntecedentePersonal');
        Route::get('fetchDesarrolloAprendisaje/{cita_paciente_id}', 'Historia\HistoriaController@fetchDesarrolloAprendisaje');
        Route::get('fetchEsquemaVacunacion/{cita_paciente_id}', 'Historia\HistoriaController@fetchEsquemaVacunacion');
        Route::get('fetchGinecologico/{cita_paciente_id}', 'Historia\HistoriaController@fetchGinecologico');
        Route::get('fetcAntecedentesFamiliares/{paciente_id}', 'Historia\HistoriaController@fetcAntecedentesFamiliares');
        Route::get('fetchAntecenetesObstetricos/{cita_paciente_id}', 'Historia\HistoriaController@fetchAntecenetesObstetricos');
        Route::get('fetchAntTransfusiones/{paciente_id}', 'Historia\HistoriaController@fetchAntTransfusiones');
        Route::get('fetchAnteHospitalizacion/{cita_paciente_id}', 'Historia\HistoriaController@fetchAnteHospitalizacion');
        Route::get('fetchAnteTraumatismo/{cita_paciente_id}', 'Historia\HistoriaController@fetchAnteTraumatismo');
        Route::get('fetchHabitosAlimentarios/{cita_paciente_id}', 'Historia\HistoriaController@fetchHabitosAlimentarios');
        Route::get('fetchHabitosToxicos/{paciente_id}', 'Historia\HistoriaController@fetchHabitosToxicos');
        Route::get('fetchRutinaHabito/{cita_paciente_id}', 'Historia\HistoriaController@fetchRutinaHabito');
        Route::get('fetchAntQuirurgico/{cita_paciente_id}', 'Historia\HistoriaController@fetchAntQuirurgico');
        Route::get('fetchActividadLaboral/{paciente_id}', 'Historia\HistoriaController@fetchActividadLaboral');
        Route::get('fetchGestanteGinecologico/{paciente_id}', 'Historia\HistoriaController@fetchGestanteGinecologico');
        Route::get('fetchGestacionGinecologico/{paciente_id}', 'Historia\HistoriaController@fetchGestacionGinecologico');
        Route::get('fetchRedesApoyo/{cita_paciente_id}', 'Historia\HistoriaController@fetchRedesApoyo');
        Route::get('fetchApgarFamiliar/{paciente_id}', 'Historia\HistoriaController@fetchApgarFamiliar');
        Route::get('fetchlistaApgar/{citapaciente_id}', 'Historia\HistoriaController@fetchlistaApgar');
        Route::get('fetchFamiliograma/{paciente_id}', 'Historia\HistoriaController@fetchFamiliograma');
        Route::get('fetchBiopsicosocial/{paciente_id}', 'Historia\HistoriaController@fetchBiopsicosocial');
        Route::get('fetchAntropoSignos/{paciente_id}', 'Historia\HistoriaController@fetchAntropoSignos');
        Route::get('fetchLaboratorios/{paciente_id}', 'Historia\HistoriaController@fetchLaboratorios');
        Route::get('fetchAyudasDx/{paciente_id}', 'Historia\HistoriaController@fetchAyudasDx');
        Route::get('fetchExamenFisicos/{citapaciente_id}', 'Historia\HistoriaController@fetchExamenFisicos');
        Route::get('fetchImpresionDx/{citapaciente_id}', 'Historia\HistoriaController@fetchImpresionDx');
        Route::get('dxExistente/{citapaciente_id}', 'Historia\HistoriaController@dxExistente');
        Route::get('fetchPlancuidado/{paciente_id}', 'Historia\HistoriaController@fetchPlancuidado');
        Route::get('fetchProximoControl/{Citapaciente_id}', 'Historia\HistoriaController@fetchProximoControl');
        Route::get('fetchPlanevaluacion/{paciente_id}', 'Historia\HistoriaController@fetchPlanevaluacion');
        Route::get('fetchInforSalud/{paciente_id}', 'Historia\HistoriaController@fetchInforSalud');
        Route::get('fetchCrianzaCuidado/{paciente_id}', 'Historia\HistoriaController@fetchCrianzaCuidado');
        Route::get('fetchConducta/{citapaciente_id}', 'Historia\HistoriaController@fetchConducta');
        Route::get('finalizarConsulta/{citapaciente}', 'Historia\HistoriaController@finalizarConsulta');
        Route::get('alertasPaciente/{paciente_id}', 'Historia\HistoriaController@alertasPaciente');
        Route::post('datosPaciente', 'Historia\HistoriaController@datosPaciente');
        Route::post('datosPaciente', 'Historia\HistoriaController@datosPaciente');
        Route::post('fetchUser', 'Historia\HistoriaController@fetchUser');

        Route::post('atenderPaciente', 'Historia\HistoriaController@atenderPaciente');
        Route::post('citaNoProgramada/{paciente}', 'Historia\HistoriaController@citaNoProgramada');
        Route::post('saveMotivoConsulta', 'Historia\HistoriaController@saveMotivoConsulta');
        Route::post('saveRevisionSistemas', 'Historia\HistoriaController@saveRevisionSistemas');
        Route::post('saveAntecedentePersonal', 'Historia\HistoriaController@saveAntecedentePersonal');
        Route::post('saveDesarrolloAprendisaje', 'Historia\HistoriaController@saveDesarrolloAprendisaje');
        Route::post('saveAntecedentesVacunales', 'Historia\HistoriaController@saveAntecedentesVacunales');
        Route::post('saveGinecologico', 'Historia\HistoriaController@saveGinecologico');
        Route::post('saveGestanteGinecologico', 'Historia\HistoriaController@saveGestanteGinecologico');
        Route::post('saveGestacionGinecologico', 'Historia\HistoriaController@saveGestacionGinecologico');
        Route::post('saveAntecedentesFamiliares', 'Historia\HistoriaController@saveAntecedentesFamiliares');
        Route::post('saveExamenFisico', 'Historia\HistoriaController@saveExamenFisico');
        Route::post('saveAntropometricas', 'Historia\HistoriaController@saveAntropometricas');
        Route::post('saveSignosVitales', 'Historia\HistoriaController@saveSignosVitales');
        Route::post('saveAntecedentesPersonal', 'Historia\HistoriaController@saveAntecedentesPersonal');
        Route::post('saveAntecenetesObstetricos', 'Historia\HistoriaController@saveAntecenetesObstetricos');
        Route::post('saveAntFarmaco', 'Historia\HistoriaController@saveAntFarmaco');
        Route::post('saveAntTransfusiones', 'Historia\HistoriaController@saveAntTransfusiones');
        Route::post('saveAnteHospitalizacion', 'Historia\HistoriaController@saveAnteHospitalizacion');
        Route::post('saveAnteTraumatismo', 'Historia\HistoriaController@saveAnteTraumatismo');
        Route::post('saveHabitosAlimentarios', 'Historia\HistoriaController@saveHabitosAlimentarios');
        Route::post('saveHabitosToxicos', 'Historia\HistoriaController@saveHabitosToxicos');
        Route::post('saveRutinaHabito', 'Historia\HistoriaController@saveRutinaHabito');
        Route::post('saveAntQuirurgico', 'Historia\HistoriaController@saveAntQuirurgico');
        Route::post('saveActividadLaboral', 'Historia\HistoriaController@saveActividadLaboral');
        Route::post('saveRedesApoyo', 'Historia\HistoriaController@saveRedesApoyo');
        Route::post('saveApgarFamiliar', 'Historia\HistoriaController@saveApgarFamiliar');
        Route::post('saveFamiliograma', 'Historia\HistoriaController@saveFamiliograma');
        Route::post('saveBiopsicosocial', 'Historia\HistoriaController@saveBiopsicosocial');
        Route::post('getAntecedentesQuimico', 'Historia\HistoriaController@getAntecedentesQuimico');
        Route::post('savePorcentaje/{cita_paciente_id}', 'Historia\HistoriaController@savePorcentaje');
        Route::post('saveLaboratorios', 'Historia\HistoriaController@saveLaboratorios');
        Route::post('saveAyudasDx', 'Historia\HistoriaController@saveAyudasDx');
        Route::post('getAntecedentesFarmacologia', 'Historia\HistoriaController@getAntecedentesFarmacologia');
        Route::post('farmaco/{id}', 'Citas\CitaController@farmaco');
        Route::post('alimento/{id}', 'Citas\CitaController@alimento');
        Route::post('ambiente/{id}', 'Citas\CitaController@ambiente');
        Route::post('otralaergia/{id}', 'Citas\CitaController@otralaergia');
        Route::post('antePersonal/{id}', 'Citas\CitaController@antePersonal');
        Route::post('deleteOtrosAntece/{id}', 'Citas\CitaController@deleteOtrosAntece');
        Route::post('deleteQuirurg/{id}', 'Citas\CitaController@deleteQuirurg');
        Route::post('saveImpresionDx', 'Historia\HistoriaController@saveImpresionDx');
        Route::post('deleteDX/{id}', 'Historia\HistoriaController@deleteDX');
        Route::post('savePlanCuidado', 'Historia\HistoriaController@savePlanCuidado');
        Route::post('deletePlan/{id}', 'Historia\HistoriaController@deletePlan');
        Route::post('saveEvaluacionPlan', 'Historia\HistoriaController@saveEvaluacionPlan');
        Route::post('deleteEvaluacion/{id}', 'Historia\HistoriaController@deleteEvaluacion');
        Route::post('saveinforSalud', 'Historia\HistoriaController@saveinforSalud');
        Route::post('deleteInforSalud/{id}', 'Historia\HistoriaController@deleteInforSalud');
        Route::post('saveCrianzaCuidado', 'Historia\HistoriaController@saveCrianzaCuidado');
        Route::post('saveProximoControl', 'Historia\HistoriaController@saveProximoControl');
        Route::post('deleteCrianzaCuidado/{id}', 'Historia\HistoriaController@deleteCrianzaCuidado');
        Route::post('savePlanManejo', 'Historia\HistoriaController@savePlanManejo');
        Route::post('pacienteInasiste', 'Historia\HistoriaController@pacienteInasiste');
        Route::post('allCodesumis', 'Historia\HistoriaController@allCodesumis');
        Route::post('citasGrupales', 'Historia\HistoriaController@citasGrupales');
        Route::post('citasOcupacionales','Historia\HistoriaController@citasOcupacionales');
        Route::post('citasIndividales','Historia\HistoriaController@citasIndividales');
        Route::post('citasNoProgramadas','Historia\HistoriaController@citasNoProgramadas');
        Route::post('getActividad','Historia\HistoriaController@getActividad');
        Route::post('cambioActividad','Historia\HistoriaController@cambioActividad');
        Route::post('historicoOrdenes','Historia\HistoriaController@historicoOrdenes');
        Route::post('historicoMedicamentos','Historia\HistoriaController@historicoMedicamentos');
        Route::get('FechthistoricoMedicamentos/{cedula}','Historia\HistoriaController@FechthistoricoMedicamentos');
        Route::post('signosvitales','Historia\HistoriaController@signosvitales');
        Route::post('suspenderMedicamento','Historia\HistoriaController@suspenderMedicamento');
        Route::get('motivoNegacion/{detaarticulordens_id}','Historia\HistoriaController@motivoNegacion');
        Route::get('motivoSuspencion/{detaarticulordens_id}','Historia\HistoriaController@motivoSuspencion');
        Route::get('fechtMedicamentoInsumos','Historia\HistoriaController@fechtMedicamentoInsumos');
        Route::get('datosPacientes/{cita_paciente_id}','Historia\HistoriaController@datosPacientes');
        Route::get('datosParaclinicos/{paciente_id}','Historia\HistoriaController@datosParaclinicos');

        //Procedimientos menores
        Route::post('saveProcedimientoMenor','Historia\HistoriaController@saveProcedimientoMenor');
        Route::get('fethProcedimientoMenor/{cita_paciente_id}','Historia\HistoriaController@fethProcedimientoMenor');
        Route::post('saveInsumoProcedimientos','Historia\HistoriaController@saveInsumoProcedimientos');
        Route::get('fethInsumoProcedimientos/{cita_paciente_id}','Historia\HistoriaController@fethInsumoProcedimientos');
        Route::get('fechtCups','Historia\HistoriaController@fechtCups');
        Route::get('medicamento/{id}', 'Historia\HistoriaController@medicamento');
        Route::post('fechtMedicamentosOrdenados','Historia\HistoriaController@fechtMedicamentosOrdenados');


        Route::post('saveAntAlergicosAlimentos', 'Historia\HistoriaController@saveAntAlergicosAlimentos');
        Route::post('getAntecedenteAlimentos', 'Historia\HistoriaController@getAntecedenteAlimentos');
        Route::post('saveAntAlergicosAmbientales', 'Historia\HistoriaController@saveAntAlergicosAmbientales');
        Route::post('getAntecedenteAmbientales', 'Historia\HistoriaController@getAntecedenteAmbientales');
        Route::post('getAntecedenteOtros', 'Historia\HistoriaController@getAntecedenteOtros');
        Route::post('saveAntAlergicosOtros', 'Historia\HistoriaController@saveAntAlergicosOtros');
        Route::get('fetchValoraciones/{paciente_id}','Historia\HistoriaController@fetchValoraciones');

        Route::post('savecronicos','Historia\HistoriaController@savecronicos');
        Route::post('getCronicos','Historia\HistoriaController@getCronicos');
        Route::post('cronico/{id}', 'Historia\HistoriaController@cronico');
        Route::post('saveBiologicos','Historia\HistoriaController@saveBiologicos');
        Route::post('getBiologicos','Historia\HistoriaController@getBiologicos');
        Route::post('biologico/{id}', 'Historia\HistoriaController@biologico');
        Route::post('saveQuimio','Historia\HistoriaController@saveQuimio');
        Route::post('getQuimio','Historia\HistoriaController@getQuimio');
        Route::post('quimio/{id}', 'Historia\HistoriaController@quimio');
        Route::post('saveTraumaticos','Historia\HistoriaController@saveTraumaticos');
        Route::post('getTraumaticos','Historia\HistoriaController@getTraumaticos');
        Route::post('traumatico/{id}', 'Historia\HistoriaController@traumatico');

        //historia quimicofarmacologica
        Route::post('saveAdherenciaFarmacologica', 'Historia\HistoriaController@saveAdherenciaFarmacologica');
        Route::post('saveAdherenciaFarmacologicaNoHistoria', 'Historia\HistoriaController@saveAdherenciaFarmacologicaNoHistoria');
        Route::get('fetchAdherenciaFarmacologica/{cita_paciente_id}', 'Historia\HistoriaController@fetchAdherenciaFarmacologica');
        Route::post('saveAdherencia', 'Historia\HistoriaController@saveAdherencia');
        Route::post('fetchAdherencia', 'Historia\HistoriaController@fetchAdherencia');
        Route::post('saveSeguridad', 'Historia\HistoriaController@saveSeguridad');
        Route::post('fetchSeguridad', 'Historia\HistoriaController@fetchSeguridad');
        Route::post('saveEfectividad', 'Historia\HistoriaController@saveEfectividad');
        Route::post('fetchEfectividad', 'Historia\HistoriaController@fetchEfectividad');
        Route::post('saveNecesidad', 'Historia\HistoriaController@saveNecesidad');
        Route::post('fetchNecesidad', 'Historia\HistoriaController@fetchNecesidad');
        Route::post('saveOtro', 'Historia\HistoriaController@saveOtro');
        Route::post('fetchOtros', 'Historia\HistoriaController@fetchOtros');
        Route::post('perfilFarmacologico/{cedula_paciente}','Historia\HistoriaController@perfilFarmacologico');


        //historia optometria
        Route::post('saveOptometria','Historia\HistoriaController@saveOptometria');
        Route::get('fetchOptometria/{citapaciente_id}', 'Historia\HistoriaController@fetchOptometria');
        Route::post('savebiooftalmoscopia','Historia\HistoriaController@savebiooftalmoscopia');
        Route::get('fetchsbiooftalmoscopia/{citapaciente_id}', 'Historia\HistoriaController@fetchsbiooftalmoscopia');
        Route::post('saveRefraccion','Historia\HistoriaController@saveRefraccion');
        Route::get('fetchsRefraccion/{citapaciente_id}', 'Historia\HistoriaController@fetchsRefraccion');

        //impresion de historia clinica integral
        Route::post('imprimirhc','PDF\PDFController@print');
        Route::post('enviarHC','PDF\PDFController@enviarHC');
        Route::post('CitasnoProgramada/{paciente}','Historia\HistoriaController@CitasnoProgramada');

        // DESCARGA DE FIAS
        Route::get('descargaFias/{fia}', 'Historia\HistoriaController@descargaFias');

        // CONSULTA DE PACIENTES PARA ASISTENCIA EDUCATIVA
        Route::post('asistenciaEducativa', 'Historia\HistoriaController@asistenciaEducativa');

        // Estadificacion RCV
        Route::post('saveFramingham', 'Historia\HistoriaController@saveFramingham');
        Route::post('savefindrisk', 'Historia\HistoriaController@savefindrisk');
        Route::get('fetchFramingham/{citapaciente_id}','Historia\HistoriaController@fetchFramingham');
        Route::get('fetchFindrisk/{citapaciente_id}','Historia\HistoriaController@fetchFindrisk');
        Route::post('saveParaclinicos','Historia\HistoriaController@saveParaclinicos');

        //Nueva ruta rcv
        Route::get('fetchDataParaclinicos/{citaPaciente}','Historia\HistoriaController@fetchDataParaclinicos');

        //Guardar escala de karnofski
        Route::post('saveKarnofski', 'Historia\HistoriaController@saveKarnofski');
        Route::get('fetchKarnofski/{citapaciente_id}', 'Historia\HistoriaController@fetchKarnofski');

        //Guardar escala de Ecog
        Route::post('saveEcog', 'Historia\HistoriaController@saveEcog');
        Route::get('fetchEcog/{citapaciente_id}', 'Historia\HistoriaController@fetchEcog');

        //Guardar escala de Esas
        Route::post('saveEsas', 'Historia\HistoriaController@saveEsas');
        Route::get('fetchEsas/{citapaciente_id}', 'Historia\HistoriaController@fetchEsas');

        //Ruta de demanda inducida
        Route::get('demandaInducida/{cedulaPaciente}', 'Historia\HistoriaController@demandaInducida');
        Route::post('saveDemandaInducida', 'Historia\HistoriaController@saveDemandaInducida');
        Route::get('consultarDemandaInducida', 'Historia\HistoriaController@consultarDemandaInducida');
        Route::put('asignarCitaDemandaInducida/{codigoDemanda}/{citaId}','Historia\HistoriaController@asignarCitaDemandaInducida');

        // Ruta indice de barthel
        Route::post('saveIndiceBerthel', 'Historia\HistoriaController@saveIndiceBerthel');
        Route::get('fetchIndiceBarthel/{citapaciente_id}', 'Historia\HistoriaController@fetchIndiceBarthel');
        Route::get('mis-demandas-inducida', 'Historia\HistoriaController@misdemandasinducidas');
        Route::post('reporteDemandaInducida', 'Historia\HistoriaController@reporteDemandaInducida');
    });
});
