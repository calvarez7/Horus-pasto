<?php

namespace App\Http\Controllers\Tarifarios;

use App\Modelos\Auditoria;
use Illuminate\Http\Request;
use App\Modelos\Salariominimo;
use App\Modelos\Sedeproveedore;
use App\Modelos\Sede_salariominimo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class SalariominimoController extends Controller
{

    public function all()
    {
        $salariominimo = Salariominimo::all();
        return response()->json($salariominimo, 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'anio'      => 'required|unique:salariominimos',
            'valor'       => 'required'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $input = $request->all();
        $encontro = strpos($input['valor'], ',');
        if($encontro == true){
            $valor = str_replace(',', '.', str_replace('.', '', $input['valor']));
            $input['valor'] = $valor;
        }
        
        Salariominimo::create($input);
        return response()->json([
            'message' => 'Salario minimo creado con exito!',
        ], 201);
    }

    public function update(Request $request, Salariominimo $salariominimo){

        $salariominimo->update([
            'valor' => $request->valor
        ]);

        Auditoria::create([
            'Descripcion'        => 'Actualizo salario minimo',
            'Tabla'              => 'salariominimos',
            'Usuariomodifica_id' => auth()->user()->id,
            'Model_id'           => $salariominimo->id
        ]);
        
        return response()->json([
            'message' => 'Salario minimo actualizado con exito!',
        ], 200);

    }

    public function sedeminimo(Request $request){

        if($request->switch1 == true){

            $validate = Validator::make($request->all(), [
                'proveedor_id'      => 'required',
                'salariominimo_id'       => 'required'
            ]);
            if ($validate->fails()) {
                $errores = $validate->errors();
                return response()->json($errores, 422);
            }
            $sedes = Sedeproveedore::where('Prestador_id', $request->proveedor_id)->get();
        }else{

            $validate = Validator::make($request->all(), [
                'sedeproveedor'      => 'required',
                'salariominimo_id'       => 'required'
            ]);
            if ($validate->fails()) {
                $errores = $validate->errors();
                return response()->json($errores, 422);
            }
            $sedes = $request->sedeproveedor;
        }

        foreach ($sedes as $key) {
            $existeSede = Sede_salariominimo::where('sedeproveedor_id', $key['id'])->first();
            if($existeSede){
                Sede_salariominimo::where('sedeproveedor_id', $key['id'])
                ->update([
                    'estado_id' => 2
                ]);

                Sede_salariominimo::create([
                    'sedeproveedor_id' => $key['id'],
                    'salariominimo_id' => $request->salariominimo_id
                ]);
            }else{
                Sede_salariominimo::create([
                    'sedeproveedor_id' => $key['id'],
                    'salariominimo_id' => $request->salariominimo_id
                ]);  
            }

        }
        
        return response()->json([
            'message' => 'Sedeproveedor minimo creado con exito!',
        ], 201);

    }

    public function allsedeminimo()
    {
        $sedeminimo = Sede_salariominimo::select('sede_salariominimos.*', 's.Codigo_habilitacion', 's.Nombre', 'sa.anio')
        ->join('sedeproveedores as s', 'sede_salariominimos.sedeproveedor_id', 's.id')
        ->join('salariominimos as sa', 'sede_salariominimos.salariominimo_id', 'sa.id')
        ->where('sede_salariominimos.estado_id', 1)
        ->get();
        return response()->json($sedeminimo, 200);
    }

    public function updatesedeminimo(Request $request){

        foreach ($request->sedeproveedor as $key) {

            Sede_salariominimo::where('sedeproveedor_id', $key['sedeproveedor_id'])
            ->update([
                'estado_id' => 2
            ]);

            Sede_salariominimo::create([
                'sedeproveedor_id' => $key['sedeproveedor_id'],
                'salariominimo_id' => $request->salariominimo_id
            ]);

        }

        return response()->json([
            'message' => 'Sedeproveedor minimo actualizado con exito!',
        ], 201);

    }

    public function municipioproveedor(Request $request){

        $proveedormunicipio = Sedeproveedore::select(['Sedeproveedores.Municipio_id', 'Sedeproveedores.Prestador_id', 
        'Municipios.Nombre as Municipio', 'Prestadores.Nombre as Nombreprestador', 'Prestadores.Nit'])
        ->join('Municipios', 'Sedeproveedores.Municipio_id', 'Municipios.id')
        ->join('Prestadores', 'Sedeproveedores.Prestador_id', 'Prestadores.id')
        ->where('Sedeproveedores.Estado_id', 1)
        ->where('Prestadores.Estado_id', 1);
        if($request->municipio){
            $proveedormunicipio->whereIn('Municipios.id', $request->municipio);
        }

        return response()->json($proveedormunicipio->get(), 200);

    }

    public function updateproveedorminimo(Request $request){

        if($request->switch2 == true){

            $validate = Validator::make($request->all(), [
                'municipio'      => 'required',
                'salariominimo_id'       => 'required'
            ]);
            if ($validate->fails()) {
                $errores = $validate->errors();
                return response()->json($errores, 422);
            }
            $sedes = Sedeproveedore::whereIn('Municipio_id', $request->municipio)->get();

        }else{

            $validate = Validator::make($request->all(), [
                'proveedor'      => 'required',
                'salariominimo_id'       => 'required'
            ]);
            if ($validate->fails()) {
                $errores = $validate->errors();
                return response()->json($errores, 422);
            }
            $sedes = Sedeproveedore::whereIn('Prestador_id', $request->proveedor)->get();

        }

        foreach ($sedes as $key) {

            Sede_salariominimo::where('sedeproveedor_id', $key['id'])
            ->update([
                'estado_id' => 2
            ]);

            Sede_salariominimo::create([
                'sedeproveedor_id' => $key['id'],
                'salariominimo_id' => $request->salariominimo_id
            ]);
        }
        
        return response()->json([
            'message' => 'Proveedor minimo creado con exito!',
        ], 201);
    }

}
