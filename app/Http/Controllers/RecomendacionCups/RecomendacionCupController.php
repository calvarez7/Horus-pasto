<?php

namespace App\Http\Controllers\RecomendacionCups;

use App\Http\Controllers\Controller;
use App\Modelos\RecomendacionCup;
use Illuminate\Http\Request;

class RecomendacionCupController extends Controller
{
    public function all()
    {
        $recomendaciones = RecomendacionCup::select(
            'recomendacion_cups.id as id_recomendacion',
            'cups.Nombre as Nombre_cups',
            'cups.codigo',
            'recomendacion_cups.cup_id',
            'recomendacion_cups.recomendacion_cup',
            'recomendacion_cups.estado')
        ->join('cups','cups.id','recomendacion_cups.cup_id')
        ->join('users','users.id','recomendacion_cups.user_id')
        ->get();
        return response()->json($recomendaciones, 200);
    }

    public function store(Request $request)
    {

        RecomendacionCup::create([
            'cup_id' => $request->cup_id,
            'recomendacion_cup' => $request->recomendacion_cup,
            'user_id' => auth()->user()->id,
        ]);

        return response()->json('Creado exitosamente', 200);
    }

    public function update(Request $request)
    {
        RecomendacionCup::where('id',$request->id_recomendacion)
        ->update([
            'recomendacion_cup' => $request->recomendacion_cup,
            'user_id' => auth()->user()->id,
        ]);

        return response()->json('Actualizado exitosamente', 200);
    }

    public function updateState($id_recomendacion) {

        $recomentadion = RecomendacionCup::where('id',$id_recomendacion)->first();
        if($recomentadion->estado == "1"){
            $recomentadion->update([
                'estado' => false
            ]);
        }else{
            $recomentadion->update([
                'estado' => true
            ]);
        }

        return response()->json('Actualizado exitosamente', 200);
    }

    public function getRecomendacion(Request $request) {

        $recomentadion = RecomendacionCup::select('recomendacion_cup')->where('cup_id',$request->id)->first();

        if($recomentadion == null){
            return response()->json(['respuesta' => false], 200);
        }else{
            return response()->json(['respuesta' => true], 200);
        }
    }
}
