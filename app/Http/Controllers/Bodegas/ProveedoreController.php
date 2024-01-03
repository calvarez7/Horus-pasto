<?php

namespace App\Http\Controllers\Bodegas;

use App\Http\Controllers\Controller;
use App\Modelos\Proveedore;
use Illuminate\Http\Request;


class ProveedoreController extends Controller
{
    public function index(){
        $prestadores = Proveedore::all();
        return response()->json($prestadores);
    }
    public function store(Request $request){
        Proveedore::create($request->all());
        return response()->json(['message'=>'Registro Exitoso']);

    }
    public function update(Request $request,Proveedore $proveedor){
        $proveedor->update($request->all());
        return response()->json(['message'=>'Registro Actualizado']);
    }
}
