<?php

namespace App\Http\Controllers\Pqrsfs;

use App\Modelos\Pqrsf;
use Illuminate\Http\Request;
use App\Modelos\Subcategoria;
use App\Modelos\Subcategoriaspqrsf;
use App\Http\Controllers\Controller;

class SubcategoriaController extends Controller
{
    public function allSubcategorias()
    {   
        $subcategorias = Subcategoria::select(['subcategorias.Nombre', 'subcategorias.id'])->get();
        return response()->json($subcategorias, 201);
    }

    public function pqrsfsubcategorias(Pqrsf $pqrsf)
    {
        $pqrsfsubcategorias = Subcategoriaspqrsf::select(['Subcategorias.Nombre', 'Subcategorias.id'])
        ->join('Subcategorias', 'subcategoriaspqrsf.Subcategoria_id', 'Subcategorias.id')
        ->where('Pqrsf_id', $pqrsf->id)
        ->get();
        return response()->json($pqrsfsubcategorias, 201);
    }
}