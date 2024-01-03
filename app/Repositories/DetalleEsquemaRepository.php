<?php

namespace App\Repositories;

use App\Modelos\Esquema;
use App\Modelos\Detallesquema;

class DetalleEsquemaRepository
{

    public function save($data)
    {
        $query = new Detallesquema();
        $query->codesumisId = $data->codesumi;
        $query->esquemaId = $data->esquema;
        $query->unidadMedida = $data->unidadMedida;
        $query->indiceDosis = $data->indiceDosis;
        $query->via = $data->via;
        $query->frecuencia = $data->frecuencia;
        $query->cantidadAplicaciones = $data->cantidadAplicaciones;
        $query->diasAplicacion = $data->diasAplicaciones;
        $query->descripcionDosis = $data->descripcionDosis;
        $query->observaciones = $data->observaciones;

        return $query->save();
    }

    public function getDetalleEsquema()
    {
        return Esquema::with([
            'detallesquemas' => function ($query){
                $query->select(
                    'detallesquemas.*',
                    'c.Nombre'
                )
                    ->join('codesumis as c', 'detallesquemas.codesumisId', 'c.id')
                    ->get();
            }
        ]);

        // select('Detallesquemas.*', 'Codesumis.Codigo as Codesumi', 'Esquemas.nombreEsquema')
        // ->join('Codesumis', 'Detallesquemas.codesumisId', 'Codesumis.id')
        // ->join('Esquemas', 'Detallesquemas.esquemaId', 'Esquemas.id');
        //return Detallesquema::select([
        //    'detallesquemas.*',
        //   'co.Codigo as codigoCodesumi',
        //   'es.abrevEsquema'
        //])
        //   ->join('codesumis as co', 'co.id', 'detallesquemas.codesumisId')
        //    ->join('esquemas as es','es.id','detallesquemas.esquemaId');
    }
}
