<?php

namespace App\Http\Controllers\Caracterizacion;

use App\Http\Controllers\Controller;
use App\Modelos\Caracterizacion;
use Illuminate\Http\Request;

class CaracterizacionController extends Controller
{
    public function lista()
    {
        $caracterizacion = Caracterizacion::select('Caracterizacions.*', 'Caracterizacions.id as carac_id', 'p.*')
        ->join('Pacientes as p', 'Caracterizacions.paciente_id', 'p.id')
        ->where('estado_id', 1)
        ->get();
        return response()->json($caracterizacion, 201);
    }

    public function registro(Request $request)
    {
        $caracterizacion = new Caracterizacion;
        $caracterizacion->user_id_registra = auth()->user()->id;
        $caracterizacion->paciente_id = $request->paciente_id;

        $caracterizacion->alergicas = $request->alergicas;
        $caracterizacion->vacunado = $request->vacunado;
        $caracterizacion->vacunarse = $request->vacunarse;
        $caracterizacion->zona_vivienda = $request->zona_vivienda;
        $caracterizacion->correo = $request->correo;
        $caracterizacion->residencia = $request->residencia;
        $caracterizacion->telefono1 = $request->telefono1;
        $caracterizacion->telefono2 = $request->telefono2;
        $caracterizacion->correocontacto = $request->correocontacto;
        $caracterizacion->conforman_hogar = $request->conforman_hogar;
        $caracterizacion->tipo_vivienda = $request->tipo_vivienda;
        $caracterizacion->alimentos = $request->alimentos;
        $caracterizacion->energia = $request->energia;
        $caracterizacion->accesibilidad_vivienda = $request->accesibilidad_vivienda;
        $caracterizacion->seguridad_orden = $request->seguridad_orden;
        $caracterizacion->etnia = $request->etnia;
        $caracterizacion->fisica = $request->fisica;
        $caracterizacion->auditiva = $request->auditiva;
        $caracterizacion->visual = $request->visual;
        $caracterizacion->sordera = $request->sordera;
        $caracterizacion->intelectual = $request->intelectual;
        $caracterizacion->mental = $request->mental;
        $caracterizacion->escolaridad = $request->escolaridad;
        $caracterizacion->estrato = $request->estrato;
        $caracterizacion->orientacion_sexual = $request->orientacion_sexual;
        $caracterizacion->oficio = $request->oficio;
        $caracterizacion->planificando_metodos = $request->planificando_metodos;
        $caracterizacion->planeado_embarazo = $request->planeado_embarazo;
        $caracterizacion->citologia_ultimo_ano = $request->citologia_ultimo_ano;
        $caracterizacion->tamizaje_Mamografia = $request->tamizaje_Mamografia;
        $caracterizacion->tamizaje_Prostata = $request->tamizaje_Prostata;
        $caracterizacion->autocuidado_salud = $request->autocuidado_salud;
        $caracterizacion->victima_violencia = $request->victima_violencia;
        $caracterizacion->victima_desplazamiento = $request->victima_desplazamiento;
        $caracterizacion->exposicion_tabaco = $request->exposicion_tabaco;
        $caracterizacion->tabacos_al_dia = $request->tabacos_al_dia;
        $caracterizacion->expuestotabaco = $request->expuestotabaco;
        $caracterizacion->consume_sustancias_psicoactivas = $request->consume_sustancias_psicoactivas;
        $caracterizacion->Frecuencia_consumo_psicoactivas = $request->Frecuencia_consumo_psicoactivas;
        $caracterizacion->consume_alcohol = $request->consume_alcohol;
        $caracterizacion->Frecuencia_consumo_alcohol = $request->Frecuencia_consumo_alcohol;
        $caracterizacion->actividad_fisica = $request->actividad_fisica;
        $caracterizacion->mama = $request->mama;
        $caracterizacion->prostata = $request->prostata;
        $caracterizacion->pulmon = $request->pulmon;
        $caracterizacion->gastrointestinales = $request->gastrointestinales;
        $caracterizacion->cervicouterino = $request->cervicouterino;
        $caracterizacion->piel = $request->piel;
        $caracterizacion->otros = $request->otros;
        $caracterizacion->obesidad = $request->obesidad;
        $caracterizacion->tiroides = $request->tiroides;
        $caracterizacion->mellitus = $request->mellitus;
        $caracterizacion->dislipidemia = $request->dislipidemia;
        $caracterizacion->ansiedad = $request->ansiedad;
        $caracterizacion->depresion = $request->depresion;
        $caracterizacion->esquizofrenia = $request->esquizofrenia;
        $caracterizacion->consumopsicoativo = $request->consumopsicoativo;
        $caracterizacion->bipolar = $request->bipolar;
        $caracterizacion->hiperactividad = $request->hiperactividad;
        $caracterizacion->conductaalimentaria = $request->conductaalimentaria;
        $caracterizacion->antecedente_mama = $request->antecedente_mama;
        $caracterizacion->antecedente_prostata = $request->antecedente_prostata;
        $caracterizacion->antecedente_pulmon = $request->antecedente_pulmon;
        $caracterizacion->antecedente_gastrointestinales = $request->antecedente_gastrointestinales;
        $caracterizacion->antecedente_cervicouterino = $request->antecedente_cervicouterino;
        $caracterizacion->antecedente_piel = $request->antecedente_piel;
        $caracterizacion->antecedente_otros = $request->antecedente_otros;
        $caracterizacion->antecedente_obesidad = $request->antecedente_obesidad;
        $caracterizacion->antecedente_tiroides = $request->antecedente_tiroides;
        $caracterizacion->antecedente_mellitus = $request->antecedente_mellitus;
        $caracterizacion->antecedente_dislipidemia = $request->antecedente_dislipidemia;
        $caracterizacion->antecedente_cardiopatia = $request->antecedente_cardiopatia;
        $caracterizacion->antecedente_hipertension = $request->antecedente_hipertension;
        $caracterizacion->antecedente_cerebrovascular = $request->antecedente_cerebrovascular;
        $caracterizacion->antecedente_arterial = $request->antecedente_arterial;
        $caracterizacion->antecedente_renal = $request->antecedente_renal;
        $caracterizacion->antecedente_asma = $request->antecedente_asma;
        $caracterizacion->antecedente_epoc = $request->antecedente_epoc;
        $caracterizacion->antecedente_bronquitis = $request->antecedente_bronquitis;
        $caracterizacion->antecedente_apnea = $request->antecedente_apnea;
        $caracterizacion->antecedenterespiratoria_otros = $request->antecedenterespiratoria_otros;
        $caracterizacion->condiciones_vih = $request->condiciones_vih;
        $caracterizacion->condiciones_autoinmunes = $request->condiciones_autoinmunes;
        $caracterizacion->condiciones_biologicos = $request->condiciones_biologicos;
        $caracterizacion->condiciones_quimioterapia = $request->condiciones_quimioterapia;
        $caracterizacion->condiciones_otros = $request->condiciones_otros;
        $caracterizacion->medicamentos = $request->medicamentos;
		$caracterizacion->antecedente_hospitalizacion = $request->antecedente_hospitalizacion;
		$caracterizacion->riesgo_individualizado = $request->riesgo_individualizado;
        $caracterizacion->cicloVital = $request->cicloVital;
		$caracterizacion->Maternoperinatal = $request->Maternoperinatal;
		$caracterizacion->salud_mental = $request->salud_mental;
		$caracterizacion->riesgo_cardiovascular = $request->riesgo_cardiovascular;
		$caracterizacion->Oncologicos = $request->Oncologicos;
		$caracterizacion->nefroproteccion = $request->nefroproteccion;
		$caracterizacion->respiratorios_cronicos = $request->respiratorios_cronicos;
		$caracterizacion->reumatologicos = $request->reumatologicos;
		$caracterizacion->trasmisibles_cronicos = $request->trasmisibles_cronicos;
		$caracterizacion->rehabilitación_integral = $request->rehabilitación_integral;
		$caracterizacion->cuidados_paliativos = $request->cuidados_paliativos;
		$caracterizacion->enfermedades_huerfanas = $request->enfermedades_huerfanas;
		$caracterizacion->Economia_articular = $request->Economia_articular;
        //campos nuevos
        $caracterizacion->alteraciones_nutricionales = $request->alteraciones_nutricionales;
        $caracterizacion->enfermedades_infecciosas = $request->enfermedades_infecciosas;
        $caracterizacion->trastorno_consumo_sustancias_psicoactivas = $request->trastorno_consumo_sustancias_psicoactivas;
        $caracterizacion->enfermedades_cardiovasculares = $request->enfermedades_cardiovasculares;
        $caracterizacion->cancer = $request->cancer;
        $caracterizacion->trastornos_visuales = $request->trastornos_visuales;
        $caracterizacion->trastornos_auditivos = $request->trastornos_auditivos;
        $caracterizacion->trastornos_salud_bucal = $request->trastornos_salud_bucal;
        $caracterizacion->problemas_salud_mental = $request->problemas_salud_mental;
        $caracterizacion->zonóticas = $request->zonóticas;
        $caracterizacion->accidente_enfermedad_laboral = $request->accidente_enfermedad_laboral;
        $caracterizacion->trastornos_degenarativos_neuropatias_autoinmune = $request->trastornos_degenarativos_neuropatias_autoinmune;
        $caracterizacion->violencias = $request->violencias;
        $caracterizacion->habitaciones = $request->habitaciones;
        $caracterizacion->parentesco_cuidador = $request->parentesco_cuidador;
        $caracterizacion->grado_discapacidad_uno = $request->grado_discapacidad_uno;
        $caracterizacion->grado_discapacidad_dos = $request->grado_discapacidad_dos;
        $caracterizacion->grado_discapacidad_tres = $request->grado_discapacidad_tres;
        $caracterizacion->grado_discapacidad_cuatro = $request->grado_discapacidad_cuatro;
        $caracterizacion->grado_discapacidad_cinco = $request->grado_discapacidad_cinco;
        $caracterizacion->antecedente_patologico = $request->antecedente_patologico;

        $caracterizacion->save();
        return response()->json([
            'message' => 'Caracterización creada con exito!',
        ], 201);
    }


    public function update(Request $request, Caracterizacion $caracterizacion)
    {
        $caracterizacion->update([
        'user_id_registra' => auth()->user()->id,
        'alergicas' => $request->alergicas,
        'vacunado' => $request->vacunado,
        'vacunarse' => $request->vacunarse,
        'zona_vivienda' => $request->zona_vivienda,
        'correo' => $request->correo,
        'residencia' => $request->residencia,
        'telefono1' => $request->telefono1,
        'telefono2' => $request->telefono2,
        'correocontacto' => $request->correocontacto,
        'conforman_hogar' => $request->conforman_hogar,
        'tipo_vivienda' => $request->tipo_vivienda,
        'alimentos' => $request->alimentos,
        'energia' => $request->energia,
        'accesibilidad_vivienda' => $request->accesibilidad_vivienda,
        'seguridad_orden' => $request->seguridad_orden,
        'etnia' => $request->etnia,
        'fisica' => $request->fisica,
        'auditiva' => $request->auditiva,
        'visual' => $request->visual,
        'sordera' => $request->sordera,
        'intelectual' => $request->intelectual,
        'mental' => $request->mental,
        'escolaridad' => $request->escolaridad,
        'estrato' => $request->estrato,
        'orientacion_sexual' => $request->orientacion_sexual,
        'oficio' => $request->oficio,
        'planificando_metodos' => $request->planificando_metodos,
        'planeado_embarazo' => $request->planeado_embarazo,
        'citologia_ultimo_ano' => $request->citologia_ultimo_ano,
        'tamizaje_Mamografia' => $request->tamizaje_Mamografia,
        'tamizaje_Prostata' => $request->tamizaje_Prostata,
        'autocuidado_salud' => $request->autocuidado_salud,
        'victima_violencia' => $request->victima_violencia,
        'victima_desplazamiento' => $request->victima_desplazamiento,
        'exposicion_tabaco' => $request->exposicion_tabaco,
        'tabacos_al_dia' => $request->tabacos_al_dia,
        'expuestotabaco' => $request->expuestotabaco,
        'consume_sustancias_psicoactivas' => $request->consume_sustancias_psicoactivas,
        'Frecuencia_consumo_psicoactivas' => $request->Frecuencia_consumo_psicoactivas,
        'consume_alcohol' => $request->consume_alcohol,
        'Frecuencia_consumo_alcohol' => $request->Frecuencia_consumo_alcohol,
        'actividad_fisica' => $request->actividad_fisica,
        'mama' => $request->mama,
        'prostata' => $request->prostata,
        'pulmon' => $request->pulmon,
        'gastrointestinales' => $request->gastrointestinales,
        'cervicouterino' => $request->cervicouterino,
        'piel' => $request->piel,
        'otros' => $request->otros,
        'obesidad' => $request->obesidad,
        'tiroides' => $request->tiroides,
        'mellitus' => $request->mellitus,
        'dislipidemia' => $request->dislipidemia,
        'ansiedad' => $request->ansiedad,
        'depresion' => $request->depresion,
        'esquizofrenia' => $request->esquizofrenia,
        'consumopsicoativo' => $request->consumopsicoativo,
        'bipolar' => $request->bipolar,
        'hiperactividad' => $request->hiperactividad,
        'conductaalimentaria' => $request->conductaalimentaria,
        'antecedente_mama' => $request->antecedente_mama,
        'antecedente_prostata' => $request->antecedente_prostata,
        'antecedente_pulmon' => $request->antecedente_pulmon,
        'antecedente_gastrointestinales' => $request->antecedente_gastrointestinales,
        'antecedente_cervicouterino' => $request->antecedente_cervicouterino,
        'antecedente_piel' => $request->antecedente_piel,
        'antecedente_otros' => $request->antecedente_otros,
        'antecedente_obesidad' => $request->antecedente_obesidad,
        'antecedente_tiroides' => $request->antecedente_tiroides,
        'antecedente_mellitus' => $request->antecedente_mellitus,
        'antecedente_dislipidemia' => $request->antecedente_dislipidemia,
        'antecedente_cardiopatia' => $request->antecedente_cardiopatia,
        'antecedente_hipertension' => $request->antecedente_hipertension,
        'antecedente_cerebrovascular' => $request->antecedente_cerebrovascular,
        'antecedente_arterial' => $request->antecedente_arterial,
        'antecedente_renal' => $request->antecedente_renal,
        'antecedente_asma' => $request->antecedente_asma,
        'antecedente_epoc' => $request->antecedente_epoc,
        'antecedente_bronquitis' => $request->antecedente_bronquitis,
        'antecedente_apnea' => $request->antecedente_apnea,
        'antecedenterespiratoria_otros' => $request->antecedenterespiratoria_otros,
        'condiciones_vih' => $request->condiciones_vih,
        'condiciones_autoinmunes' => $request->condiciones_autoinmunes,
        'condiciones_biologicos' => $request->condiciones_biologicos,
        'condiciones_quimioterapia' => $request->condiciones_quimioterapia,
        'condiciones_otros' => $request->condiciones_otros,
        'medicamentos' => $request->medicamentos,
		'antecedente_hospitalizacion' => $request->antecedente_hospitalizacion,
		'riesgo_individualizado' => $request->riesgo_individualizado,
        'cicloVital' => $request-> cicloVital,
		'Maternoperinatal' => $request->Maternoperinatal,
		'salud_mental' => $request->salud_mental,
		'riesgo_cardiovascular' => $request->riesgo_cardiovascular,
		'Oncologicos' => $request->Oncologicos,
		'nefroproteccion' => $request->nefroproteccion,
		'respiratorios_cronicos' => $request->respiratorios_cronicos,
		'reumatologicos' => $request->reumatologicos,
		'trasmisibles_cronicos' => $request->trasmisibles_cronicos,
		'rehabilitación_integral' => $request->rehabilitación_integral,
		'cuidados_paliativos' => $request->cuidados_paliativos,
		'enfermedades_huerfanas' => $request->enfermedades_huerfanas,
		'Economia_articular' => $request->Economia_articular,
        //campos nuevos
        'alteraciones_nutricionales' => $request->alteraciones_nutricionales,
        'enfermedades_infecciosas'  => $request->enfermedades_infecciosas,
        'trastorno_consumo_sustancias_psicoactivas' => $request->trastorno_consumo_sustancias_psicoactivas,
        'enfermedades_cardiovasculares'  => $request->enfermedades_cardiovasculares,
        'cancer' => $request->cancer,
        'trastornos_visuales' => $request->trastornos_visuales,
        'trastornos_auditivos' => $request->trastornos_auditivos,
        'trastornos_salud_bucal' => $request->trastornos_salud_bucal,
        'problemas_salud_mental' => $request->problemas_salud_mental,
        'zonóticas' => $request->zonóticas,
        'accidente_enfermedad_laboral' => $request->accidente_enfermedad_laboral,
        'trastornos_degenarativos_neuropatias_autoinmune' => $request->trastornos_degenarativos_neuropatias_autoinmune,
        'violencias' => $request->violencias,
        'habitaciones' => $request->habitaciones,
        'parentesco_cuidador' => $request->parentesco_cuidador,
        'grado_discapacidad_uno' => $request->grado_discapacidad_uno,
        'grado_discapacidad_dos' => $request->grado_discapacidad_dos,
        'grado_discapacidad_tres' => $request->grado_discapacidad_tres,
        'grado_discapacidad_cuatro' => $request->grado_discapacidad_cuatro,
        'grado_discapacidad_cinco' => $request->grado_discapacidad_cinco,
        'estado_Paciente' => $request->estado_Paciente,
        'antecedente_patologico' => $request->antecedente_patologico

        ]);
        return response()->json([
            'message' => 'Caracterisación actualizada con exito!',
        ], 201);
    }

}
