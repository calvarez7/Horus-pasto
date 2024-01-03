<?php
namespace App\Repositories;

use App\Modelos\Esquema;
use App\Modelos\Codesumi;
use Illuminate\Http\Request;

class CodesumiRepository
{

    public function enableCodesumis(){
        return Codesumi::select([
            'id', 'Nombre', 'Codigo', 'Frecuencia', 'Cantidadmaxordenar', 'Nivel_Ordenamiento', 'Requiere_autorizacion', 'Genero', 'EdadInicial', 'EdadFinal', 'Ambito'
        ])->where('Estado_id', 1);
    }

    public function codesumiEsquema($esquema){
        return Codesumi::select([
            'codesumis.id',
            'codesumis.Codigo',
            'codesumis.Nombre',
            'det.dosis',
            'det.unidadMedida',
            'det.via',
            'det.dosisFormulada',
            'det.cantidadAplicaciones',
            'det.diasAplicacion',
            'det.frecuencia',
            'det.frecuenciaDuracion',
            'det.indiceDosis',
            'det.observaciones',
        ])->join('Detallesquemas as det', 'codesumis.id', 'det.codesumisId')
        ->where('det.esquemaId', $esquema);
    }

    public function lotesMedicamentos($request){
        return Codesumi::select(['da.CUM_completo','da.Titular','l.*','da.concentracion','codesumis.unidadMedida'])
            ->join('detallearticulos as da', 'da.Codesumi_id', 'codesumis.id')
            ->join('bodegarticulos as ba', 'ba.Detallearticulo_id', 'da.id')
            ->join('bodegas as b', 'ba.Bodega_id', 'b.id')
            ->join('lotes as l', 'l.Bodegarticulo_id', 'ba.id')
            // ->leftjoin('detallesquemas as de','de.codesumisId','codesumis.id')
            ->whereIn('da.Estado_id', [1,3])
            ->where('codesumis.id', $request->Codesumi_id)
            ->where('b.id', $request->Bodega_id)
            ->where('l.Cantidadisponible', '>', 0);
    }

}
