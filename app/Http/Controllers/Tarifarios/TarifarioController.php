<?php

namespace App\Http\Controllers\Tarifarios;

use App\Modelos\Cup;
use App\Modelos\Contrato;
use App\Modelos\Auditoria;
use App\Modelos\Tarifario;
use App\Modelos\Cupfamilia;
use Illuminate\Http\Request;
use App\Modelos\Codigomanual;
use App\Modelos\Sede_salariominimo;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Validator;

class TarifarioController extends Controller
{

    public function all()
    {
        $tarifario = Tarifario::select(['Tarifarios.*', 'Tiposervicios.Nombre as nombreAmbito', 'Sedeproveedores.Nombre as nombreSede'])
            ->join('Cupservicios', 'Tarifarios.Cupservicio_id', 'Cupservicios.id')
            ->join('Tiposervicios', 'Cupservicios.Tiposervicio_id', 'Tiposervicios.id')
            ->join('Contratos', 'Tarifarios.Contrato_id', 'Contratos.id')
            ->join('Sedeproveedores', 'Contratos.Sedeproveedore_id', 'Sedeproveedores.id')
            ->where('Tarifarios.Estado_id', 1)
            ->get();
        return response()->json($tarifario, 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Fechainicio' => 'required|string',
            'Fechafinal'  => 'required|string',
            'Tarifa'      => 'required|string',
            'Valor'       => 'required|string',
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }
        $input = $request->all();
        Tarifario::create($input);
        return response()->json([
            'message' => 'Tarifario creado con Exito!',
        ], 201);
    }

    public function update(Request $request, Tarifario $tarifario)
    {
        $tarifario->update($request->all());
        return response()->json([
            'message' => 'Tarifario actualizado con Exito!',
        ], 200);
    }

    public function cargaMasivaTarifa(Contrato $contrato, Request $request, $familia){

        $existe = [];
        $noExiste = [];
        $msg = '';

        if($contrato->Manual == 'Soat'){

            $aniocodigo = Sede_salariominimo::select(['sa.anio'])
            ->join('salariominimos as sa', 'sede_salariominimos.salariominimo_id', 'sa.id')
            ->where('sede_salariominimos.sedeproveedor_id', $contrato->Sedeproveedor_id)
            ->where('sede_salariominimos.estado_id', 1)
            ->first();

            if(!$aniocodigo){
                return response()->json([
                    'message' => 'Este sedeproveedor no esta parametrizado en sedeproveedor minimo'
                ], 400);
            }
        }
        
        (new FastExcel)->import($request->data, function ($line) use (&$noExiste,&$existe,&$msg) {
            $codigo_string = (string)$line["Codigo"];
            $cup = Cup::where('Codigo', $codigo_string)->first();
            if($cup){
                if($line["Codigo"] == ''){
                    $msg = 'Hay campos obligatorios vacios, revisa: ( Codigo )';
                }else{
                    $data = $line;
                    $data["Cup_id"] = $cup->id;
                    $data["Valor"] = $line["Valor"];
                    $existe[] = $data;
                }

            }elseif(!$cup){
                if($line["Codigo"] == ''){
                    $msg = 'Hay campos obligatorios vacios, revisa: ( Codigo )';
                }else{
                    $noExiste[] = $line;
                }                
            }
        });

        $signo = $contrato->Tarifa[0];
        if ($signo == '-' || $signo == '+') {
            $tarifa = substr($contrato->Tarifa, 1);
        } else {
            $tarifa = $contrato->Tarifa;
        }

        if($msg !== ''){
            return response()->json([
                'message' => $msg
            ], 400);
        }else if($noExiste){
            return response()->json([
                $noExiste
            ], 400);
        }else{

            foreach ($existe as $key) {

                $cup_familia = Cupfamilia::select('f.id')
                ->join('familias as f', 'cupfamilias.Familia_id', 'f.id')
                ->where('cupfamilias.Cup_id', $key['Cup_id'])
                ->where('f.Tipofamilia_id', 4)
                ->first();

                if(!$cup_familia){
                    Cupfamilia::create([
                        'Cup_id' => $key['Cup_id'],
                        'Familia_id' => $familia
                    ]);
                }

                if($cup_familia){
                    $tarifaEnabled_existente = Tarifario::join('contratofamilias as cf', 'tarifarios.Contrato_id', 'cf.Contrato_id')
                    ->where('cf.Familia_id', $cup_familia->id)
                    ->where('tarifarios.Contrato_id', $contrato->id)
                    ->where('tarifarios.Estado_id', 1)
                    ->count();
    
                    if ($tarifaEnabled_existente == 0) {
                        $contrato->familias()->attach($cup_familia->id);
                    }
                }else{
                    $tarifaEnabled_carga = Tarifario::join('contratofamilias as cf', 'tarifarios.Contrato_id', 'cf.Contrato_id')
                    ->where('cf.Familia_id', $familia)
                    ->where('tarifarios.Contrato_id', $contrato->id)
                    ->where('tarifarios.Estado_id', 1)
                    ->count();

                    if ($tarifaEnabled_carga == 0) {
                        $contrato->familias()->attach($familia);
                    }
                }

                Tarifario::join('contratos', 'tarifarios.Contrato_id', 'contratos.id')
                ->where('tarifarios.Cup_id', $key['Cup_id'])
                ->where('contratos.Sedeproveedor_id', $contrato->Sedeproveedor_id)
                ->where('contratos.entidad_id', $contrato->entidad_id)
                ->where('tarifarios.Estado_id', 1)
                ->update([
                    'Estado_id' => 2,
                ]);
                
                if($contrato->Manual == 'Tarifa Propia'){

                    if($key['Valor'] == ''){
                        $valor = 0;
                    }else{
                        $valor = $key['Valor'];
                    }

                    $tarifa_create = Tarifario::create([
                        'Cup_id' => $key['Cup_id'],
                        'Fechainicio' => date('Y-m-d'),
                        'Fechafinal' => date('Y-m-d'),
                        'Tarifa' => $contrato->Tarifa,
                        'Manual' => $contrato->Manual,
                        'Ambito' => $contrato->Ambito,
                        'Valor' => $valor,
                        'Estado_id' => 1,
                        'Contrato_id' => $contrato->id
                    ]);

                }else{

                    if(isset($aniocodigo)){
                        $codigomanual = Codigomanual::select(['codigomanuals.*'])
                        ->join('tipomanuales as tm', 'codigomanuals.Tipomanual_id', 'tm.id')
                        ->where('tm.Nombre', $contrato->Manual)
                        ->where('codigomanuals.Cup_id', $key['Cup_id'])
                        ->where('codigomanuals.anio', $aniocodigo->anio)
                        ->where('codigomanuals.Estado_id', 1)
                        ->first();
                    }else{
                        $codigomanual = Codigomanual::select(['codigomanuals.*'])
                        ->join('tipomanuales as tm', 'codigomanuals.Tipomanual_id', 'tm.id')
                        ->where('tm.Nombre', $contrato->Manual)
                        ->where('codigomanuals.Cup_id', $key['Cup_id'])
                        ->where('codigomanuals.Estado_id', 1)
                        ->first();
                    }
                    
                    if (isset($codigomanual)) {
                        if($contrato->Manual != 'Tarifa Propia' && $contrato->Tarifa == 0){
                            $valor = $codigomanual->Valor;
                        }else{
                            if ($signo == '-') {
                                $valor = $codigomanual->Valor - ($codigomanual->Valor * $tarifa / 100);
                            } else {
                                $valor = ($codigomanual->Valor * $tarifa / 100) + $codigomanual->Valor;
                            }
                        }
                        $valor = round($valor);
                    } else {
                        if($key['Valor'] == ''){
                            $valor = 0;
                        }else{
                            $valor = $key['Valor'];
                        }
                    }

                    if($valor == 0 && $key['Valor'] > 0){
                        $valor = $key['Valor'];
                    }

                    $tarifa_create = Tarifario::create([
                        'Cup_id' => $key['Cup_id'],
                        'Fechainicio' => date('Y-m-d'),
                        'Fechafinal' => date('Y-m-d'),
                        'Tarifa' => $contrato->Tarifa,
                        'Manual' => $contrato->Manual,
                        'Ambito' => $contrato->Ambito,
                        'Valor' => $valor,
                        'Estado_id' => 1,
                        'Contrato_id' => $contrato->id
                    ]);

                }

                $tarifaDisabled = Tarifario::select('tarifarios.id', 'tarifarios.Contrato_id')
                ->join('contratos', 'tarifarios.Contrato_id', 'contratos.id')
                ->where('tarifarios.Cup_id', $key['Cup_id'])
                ->where('contratos.Sedeproveedor_id', $contrato->Sedeproveedor_id)
                ->where('contratos.entidad_id', $contrato->entidad_id)
                ->where('tarifarios.Estado_id', 2)
                ->first();

                if($tarifaDisabled){
                    $tarifaEnabled = Tarifario::where('Contrato_id', $tarifaDisabled->Contrato_id)->where('Estado_id', 1)->count();
                    if ($tarifaEnabled == 0) {
                        $contrato = Contrato::where('id', $tarifaDisabled->Contrato_id)->first();
                        $contrato->familias()->detach();
                    }
                }

                Auditoria::create([
                    'Descripcion'        => 'Creo tarifa',
                    'Tabla'              => 'tarifarios',
                    'Usuariomodifica_id' => auth()->user()->id,
                    'Model_id'           => $tarifa_create->id
                ]);
            }
            
            return response()->json([
                'message' => 'Carga de tarifas con exito!'
            ], 200);

        }

    }
}
