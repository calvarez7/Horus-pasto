<?php

namespace App\Http\Controllers\Tarifarios;

use App\Http\Controllers\Controller;
use App\Modelos\Codepropio;
use App\Modelos\Codesumi;
use App\Modelos\Sedeproveedore;
use Illuminate\Http\Request;

class CodepropioController extends Controller
{
    public function all(Request $request)
    {
        $codepropio = Codepropio::all(['id', 'Codigo', 'Descripcion']);
        return response()->json($codepropio, 200);
    }

    public function allBodega(Request $request)
    {
        $codepropio = Codepropio::select([
            'cpsp.id',
            'Codepropios.id as Codigopropio_id',
            'Codepropios.Codigo',
            'Codepropios.Descripcion',
            'cpsp.Valor',
            'cpsp.Ambito',
            'Codesumis.Nombre as Nombrecodesumi',
            'sp.Nombre as Nombreprestador'])
            ->join('Codesumis', 'Codepropios.Codesumi_id', 'Codesumis.id')
            ->join('code_propio_sede_prestador as cpsp', 'cpsp.codepropio_id', 'Codepropios.id')
            ->join('sedeproveedores as sp', 'cpsp.sedeproveedor_id', 'sp.id')
            ->where('Codepropios.Estado_id', 1)
            ->where('sp.id', $request->Prestador_id)
            ->get();
        return response()->json($codepropio, 200);
    }

    public function store(Request $request, Sedeproveedore $sedeproveedore)
    {
        // return [$request->all(), $sedeproveedore];

        //other is because codepropio not exist
        if ($request->other) {
            $codepropio = Codepropio::create([
                'Codigo'      => $request->Codigo,
                'Descripcion' => $request->Descripcion,
                'Codesumi_id' => $request->Codesumi_id,
            ]);

            $sedeproveedore->codepropios()->attach($codepropio->id, ['Ambito' => $request->Ambito, 'Valor' => $request->Valor]);
        } else {
            $sedeproveedore->codepropios()->attach($request->Codepropio_id, ['Ambito' => $request->Ambito, 'Valor' => $request->Valor]);
        }

        return response()->json([
            'message' => 'Codepropio se guardo con Exito!',
        ], 200);

    }

    public function update(Request $request, Codepropio $codepropio)
    {
        $codepropio->update($request->all());
        return response()->json([
            'message' => 'Codepropio actualizado con Exito!',
        ], 200);
    }

    public function enabled()
    {
        $codepropio = Codepropio::where('Estado', 1)->get();
        return response()->json($codepropio, 200);
    }

    public function serviciosPropios()
    {

        $codepropios = Codesumi::where('Estado_id', 1)
            ->where('Tipocodesumi_id', 3)
            ->get(['id', 'Nombre', 'Codigo', 'Frecuencia', 'Cantidadmaxordenar', 'Requiere_autorizacion', 'Nivel_ordenamiento']);

        return response()->json($codepropios, 200);
    }
}
