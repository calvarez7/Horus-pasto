<?php

namespace App\Http\Controllers\Tarifarios;

use App\Modelos\Cup;
use Illuminate\Http\Request;
use App\Modelos\Codigomanual;
use App\Modelos\Salariominimo;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Validator;

class CodigomanualController extends Controller
{
    public function all()
    {
        $codigomanual = Codigomanual::select(['codigomanuals.*', 'tm.Nombre as tipomanual', 'c.Codigo as cup'])
            ->join('tipomanuales as tm', 'codigomanuals.Tipomanual_id', 'tm.id')
            ->join('cups as c', 'codigomanuals.Cup_id', 'c.id')
            ->where('codigomanuals.Estado_id', 1)
            ->get();
        return response()->json($codigomanual, 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Codigo'      => 'required',
            'Valor'       => 'required|integer',
            'Cup_id'      => 'required',
            'Tipomanual_id' => 'required',
            'anio'  => 'required'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $noExisteAño = Codigomanual::where('anio', $request->anio)->first();  
        if(!$noExisteAño){
            return response()->json([
                'message' => 'El año aun no existe en codigo manual!',
            ], 401);
        }
        
        $existeCodigoManual = Codigomanual::where('anio', $request->anio)
        ->where('Cup_id', $request->Cup_id)
        ->where('Tipomanual_id', $request->Tipomanual_id)
        ->first();  
        if($existeCodigoManual){
            return response()->json([
                'message' => 'El tipo manual y el cup ya existe en codigo manual en el año '.$request->anio,
            ], 401);
        }

        $cup = Cup::select(['Nombre'])
        ->where('id', $request->Cup_id)
        ->first();

        $input = $request->all();
        $input['Descripcion'] = $cup->Nombre;
        Codigomanual::create($input);
        return response()->json([
            'message' => 'Codigomanual creado con Exito!',
        ], 201);
    }

    public function update(Request $request, Codigomanual $codigomanual)
    {
        if($request->disabled == true){

            $codigomanual->update([
                'Estado_id' => 2
            ]);
            
            return response()->json([
                'message' => 'Codigomanual inhabilitado con exito!',
            ], 200);

        }else{

            $descripcion = strtoupper($request->descripcion);
            $codigomanual->update([
                'Descripcion' => $descripcion
            ]);
            
            return response()->json([
                'message' => 'Codigomanual actualizado con exito!',
            ], 200);
        }
        
    }

    public function import(Request $request)
    {
        $codigomanual = (new FastExcel)->import($request->data, function ($line) {
            return Codigomanual::create([
                'Tipomanual_id' => $line['Tipomanual_id'],
                'Cup_id'        => $line['Cup_id'],
                'Codigo'        => $line['Codigo'],
                'Descripcion'   => $line['Descripcion'],
                'Valor'         => $line['Valor'],
            ]);
        });
        return response()->json([
            'message' => 'Codigomanual Cargado con exito!',
        ], 201);
    }

    public function updateAnio(Request $request){

        $existeBase = Codigomanual::where('anio', $request->aniobase)->first();
        if($existeBase){

            $existeCrear = Codigomanual::where('anio', $request->aniocrear)->first();
            if($existeCrear){

                return response()->json([
                    'message' => 'El año a crear ya existe en codigo manual!',
                ], 401);

            }else{

                $tarifa = Salariominimo::where('anio', $request->aniocrear)->first();
                if($tarifa){

                    $codigosBase = Codigomanual::where('anio', $request->aniobase)->where('Tipomanual_id', 1)->get();
                    foreach ($codigosBase as $key) {

                        $valor = ($key['Valor'] * $tarifa->valor / 100) + $key['Valor'];
                        $valor = round($valor);
    
                        Codigomanual::create([
                            'Tipomanual_id' => $key['Tipomanual_id'],
                            'Cup_id'        => $key['Cup_id'],
                            'Codigo'        => $key['Codigo'],
                            'Descripcion'   => $key['Descripcion'],
                            'Valor'         => $valor,
                            'anio'          => $request->aniocrear
                        ]);
                    }
    
                    return response()->json([
                        'message' => 'Codigos manual creados con exito!',
                    ], 201);
                }
                
                return response()->json([
                    'message' => 'El salario minimo del año a crear no existe!',
                ], 401);

            }
        }

        return response()->json([
            'message' => 'El año base no existe en codigo manual!',
        ], 401);
    }

}
