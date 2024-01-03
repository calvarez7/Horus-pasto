<?php

namespace App\Http\Controllers\Historia;

use App\Http\Controllers\Controller;
use App\Modelos\citapaciente;
use App\Modelos\Estilovida;
use Illuminate\Http\Request;

class EstilovidaController extends Controller
{

    public function all()
    {
        //
    }

    public function store(Request $request, citapaciente $citapaciente)
    {

        if (!$this->isOpenCita($citapaciente->Estado_id)) {
            return response()->json([
                'message' => '¡La historia del paciente no se encuentra activa!',
            ], 422);
        }

            $citapacienteid = citapaciente::select('id')
            ->where('id', $citapaciente->id)
            ->first();

        $estilovida = Estilovida::where('estilovidas.Citapaciente_id', $citapaciente->id)
            ->first();

        if (!isset($estilovida)) {
            Estilovida::create([
                'Citapaciente_id'         => $citapaciente->id,
                'Dietasaludable'          => $request->Dietasaludable,
                'Suenoreparador'          => $request->Suenoreparador,
                'TipoSueno'                 => $request->TipoSueno,
                'DuracionSueno'             => $request->DuracionSueno,
                'Duermemenoseishoras'     => $request->DuracionSueno,
                'Altonivelestres'         => $request->Altonivelestres,
                'Actividadfisica'         => $request->Actividadfisica,
                'Frecuenciasemana'        => $request->Frecuenciasemana,
                'Duracion'                => $request->Duracion,
                'Fumacantidad'            => $request->Fumacantidad,
                'Fumainicio'              => $request->Fumainicio,
                'Fumadorpasivo'           => $request->Fumadorpasivo,
                'Cantidadlicor'           => $request->Cantidadlicor,
                'Licorfrecuencia'         => $request->Licorfrecuencia,
                'Consumopsicoactivo'      => $request->Consumopsicoactivo,
                'Psicoactivocantidad'     => $request->Psicoactivocantidad,
                'Estilovidaobservaciones' => $request->Estilovidaobservaciones,
                'checkboxDietasaludable' => $request->checkboxDietasaludable,
                'checkboxSuenoreparador' => $request->checkboxSuenoreparador,
                'checkboxDuermemenoseishoras' => $request->checkboxDuermemenoseishoras,
                'checkboxAltonivelestres' => $request->checkboxAltonivelestres,
                'checkboxActividadfisica' => $request->checkboxActividadfisica,
                'checkboxFuma' => $request->checkboxFuma,
                'checkboxTomalicor' => $request->checkboxTomalicor,
                'checkboxConsumopsicoactivo' => $request->checkboxConsumopsicoactivo,
                'checkboxtv'=> $request->checkboxtv,
                'checkboxjuego' => $request->checkboxjuego,
                'checkboxbano' => $request->checkboxbano,
                'checkboxbucal' => $request->checkboxbucal,
                'checkboxPosiciones' => $request->checkboxPosiciones,
                'checkboxesfinter' => $request->checkboxesfinter,
                'checkboxOrina' => $request->checkboxOrina,
                'tabaquico' => $request->tabaquico,
                'riesgoEpoc' => $request->riesgoEpoc,
                'DietaFrecuencia' => $request->DietaFrecuencia,
                'Fumanos' => $request->Fumanos,
                'Exposicion' => $request->Exposicion,
                'Bano' => $request->Bano,
                'Bucal' => $request->Bucal,
                'Posiciones' => $request->Posiciones,
                'Esfinter' => $request->Esfinter,
                'CaracteristicasOrina' => $request->CaracteristicasOrina,
                'esfinterRectal' => $request->esfinterRectal,
                'checkboxesfinterRectal'=> $request->checkboxesfinterRectal,
                'leche' => $request->leche,
                'alimento' => $request->alimento,
                'introduccionAlimentos' => $request->introduccionAlimentos,
                'juego'=> $request->juego,
                'checkboxexposicionhumo' => $request->checkboxexposicionhumo,
                'expuestohumo' => $request->expuestohumo,
                'expuesttopsicoactivos' => $request->expuesttopsicoactivos,
                'anosexpuesto' => $request->anosexpuesto,
                'checkboxexposicionpsicoactivos' => $request->checkboxexposicionpsicoactivos,
                'anosexpuesto_sustancias' => $request->anosexpuesto_sustancias,
                'cuantas_comidas' => $request->cuantas_comidas,
                'dieta_balanceada' => $request->dieta_balanceada

            ]);
            return response()->json(['message' => 'Estilo de vida guardado con exito!'], 201);
        } else {
            $estilovida->update([
                'Citapaciente_id'         => $citapaciente->id,
                'Dietasaludable'          => $request->Dietasaludable,
                'DietaFrecuencia'          => $request->DietaFrecuencia,
                'Suenoreparador'          => $request->Suenoreparador,
                'TipoSueno'                 => $request->TipoSueno,
                'DuracionSueno'             => $request->DuracionSueno,
                'Duermemenoseishoras'     => $request->Duermemenoseishoras,
                'Altonivelestres'         => $request->Altonivelestres,
                'checkboxActividadfisica' => $request->checkboxActividadfisica,
                'Actividadfisica'         => $request->Actividadfisica,
                'frecuenciaActividad'     => $request->frecuenciaActividad,
                'checkboxtv'              => $request->checkboxtv,
                // 'Exposicion'              => $request->Exposicion,
                'juego'                   => $request->juego,
                'Frecuenciasemana'        => $request->Frecuenciasemana,
                'Duracion'                => $request->Duracion,
                'Fumacantidad'            => $request->Fumacantidad,
                'Fumainicio'              => $request->Fumainicio,
                'Fumadorpasivo'           => $request->Fumadorpasivo,
                'Cantidadlicor'           => $request->Cantidadlicor,
                'Licorfrecuencia'         => $request->Licorfrecuencia,
                'Consumopsicoactivo'      => $request->Consumopsicoactivo,
                'Psicoactivocantidad'     => $request->Psicoactivocantidad,
                'Estilovidaobservaciones' => $request->Estilovidaobservaciones,
                'checkboxDietasaludable' => $request->checkboxDietasaludable,
                'checkboxSuenoreparador' => $request->checkboxSuenoreparador,
                'checkboxDuermemenoseishoras' => $request->checkboxDuermemenoseishoras,
                'checkboxAltonivelestres' => $request->checkboxAltonivelestres,
                'checkboxActividadfisica' => $request->checkboxActividadfisica,
                'checkboxFuma' => $request->checkboxFuma,
                'checkboxTomalicor' => $request->checkboxTomalicor,
                'checkboxConsumopsicoactivo' => $request->checkboxConsumopsicoactivo,
                'checkboxtv'=> $request->checkboxtv,
                'checkboxjuego' => $request->checkboxjuego,
                'checkboxbano' => $request->checkboxbano,
                'checkboxbucal' => $request->checkboxbucal,
                'checkboxPosiciones' => $request->checkboxPosiciones,
                'checkboxesfinter' => $request->checkboxesfinter,
                'checkboxOrina' => $request->checkboxOrina,
                'tabaquico' => $request->tabaquico,
                'riesgoEpoc' => $request->riesgoEpoc,
                'Fumanos' => $request->Fumanos,
                'Exposicion' => $request->Exposicion,
                'Bano' => $request->Bano,
                'Bucal' => $request->Bucal,
                'Posiciones' => $request->Posiciones,
                'Esfinter' => $request->Esfinter,
                'CaracteristicasOrina' => $request->CaracteristicasOrina,
                'esfinterRectal' => $request->esfinterRectal,
                'checkboxesfinterRectal'=> $request->checkboxesfinterRectal,
                'leche' => $request->leche,
                'alimento' => $request->alimento,
                'introduccionAlimentos' => $request->introduccionAlimentos,
                'checkboxexposicionhumo' => $request->checkboxexposicionhumo,
                'expuestohumo' => $request->expuestohumo,
                'expuesttopsicoactivos' => $request->expuesttopsicoactivos,
                'anosexpuesto' => $request->anosexpuesto,
                'checkboxexposicionpsicoactivos' => $request->checkboxexposicionpsicoactivos,
                'anosexpuesto_sustancias' => $request->anosexpuesto_sustancias,
                'cuantas_comidas' => $request->cuantas_comidas,
                'dieta_balanceada' => $request->dieta_balanceada

            ]);
            return response()->json(['message' => 'Estilo de vida actualizado con exito!'], 201);
        }
    }

    public function getLifeStyle(citapaciente $citapaciente)
    {

        $estilovida = Estilovida::where('Citapaciente_id', $citapaciente->id)
            ->first();

        return response()->json(['estilovida' => $estilovida], 201);
    }

    public function show(Estilovida $estilovida)
    {
        //
    }

    private function isOpenCita($estado)
    {
        if ($estado == 8) {
            return true;
        }
        return false;
    }

}
