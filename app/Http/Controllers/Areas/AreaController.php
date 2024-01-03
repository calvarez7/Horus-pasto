<?php

namespace App\Http\Controllers\Areas;

use App\Modelos\Area;
use App\Modelos\Pqrsf;
use App\Modelos\Areapqrsf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AreaController extends Controller
{
    public function all()
    {
        $area = Area::getRepository()->all()->get();
        return response()->json($area, 201);
    }

    public function pqrsfAreas(Pqrsf $pqrsf)
    {
        $pqrsfareas = Areapqrsf::select(['Areas.Nombre', 'Areas.id'])
        ->join('Areas', 'areaspqrsf.Area_id', 'Areas.id')
        ->where('Pqrsf_id', $pqrsf->id)
        ->get();
        return response()->json($pqrsfareas, 201);
    }
}