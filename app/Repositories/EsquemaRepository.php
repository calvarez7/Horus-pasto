<?php
namespace App\Repositories;

use App\Modelos\Esquema;
use App\Modelos\Codesumi;
use App\Modelos\Detallesquema;

class EsquemaRepository
{

    public function enableEsquemas()
    {
        return Esquema::select([
            'id', 'nombreEsquema', 'abrevEsquema', 'ciclos', 'frecuenciaRepeat', 'biografia'
        ])
            ->orderBy('id', 'desc')
            ->where('estadoId', 1);
    }

    public function getDetalleEsquema()
    {
        return Esquema::with(['detallesquemas']);

        // select('Detallesquemas.*', 'Codesumis.Codigo as Codesumi', 'Esquemas.nombreEsquema')
        // ->join('Codesumis', 'Detallesquemas.codesumisId', 'Codesumis.id')
        // ->join('Esquemas', 'Detallesquemas.esquemaId', 'Esquemas.id');
    }

}
