<?php

namespace App\Http\Controllers\Tarifarios;

use App\Modelos\Cup;
use App\Modelos\Familia;
use App\Modelos\Contrato;
use App\Modelos\Tarifario;
use Illuminate\Http\Request;
use App\Modelos\Codigomanual;
use App\Modelos\Sedeproveedore;
use App\Modelos\Sede_salariominimo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ContratoController extends Controller
{
    public function all(Request $request)
    {
        $contrato = Contrato::select(['Contratos.*', 'ent.nombre'])
            ->join('Sedeproveedores', 'Contratos.Sedeproveedor_id', 'Sedeproveedores.id')
            ->leftjoin('entidades as ent', 'Contratos.entidad_id', 'ent.id')
            ->where('Contratos.Estado_id', 1)
            ->where('Contratos.Sedeproveedor_id', $request->Sedeproveedor_id)
            ->get();
        return response()->json($contrato, 200);
    }

    public function unidadFuncionalContrato(Contrato $contrato)
    {
        $unidadfuncionales = Familia::select(['familias.*'])
            ->join('contratofamilias as cf', 'cf.Familia_id', 'familias.id')
            ->where('cf.Contrato_id', $contrato->id)
            ->get();
        return response()->json($unidadfuncionales, 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Manual'      => 'required',
            'Ambito' => 'required',
            'Anio' =>   'required',
            'entidad_id' => 'required'
        ]);
        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $input = $request->all();
        if ($request->pleno || $request->Manual == 'Tarifa Propia') {
            $input['Tarifa'] = '0';
        }
        $encontro = strpos($input['Tarifa'], ',');
        if($encontro == true){
            $tarifa = str_replace(',', '.', str_replace('.', '', $input['Tarifa']));
            $input['Tarifa'] = $tarifa;
        }
        $contrato = Contrato::create($input);
        return response()->json([
            'message' => 'Contrato creado con exito!',
        ], 201);
    }

    public function update(Request $request, Contrato $contrato)
    {

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
        
        $contrato->update([
            'Estado_id' => 2
        ]);

        if($request->pleno){
            $tarifa = 0;
        }else{
            $encontro = strpos($request->Tarifa, ',');
            if($encontro == true){
                $tarifa_conpunto = str_replace(',', '.', str_replace('.', '', $request->Tarifa));
                $tarifa = $tarifa_conpunto;
            }else{
                $tarifa = $request->Tarifa;
            }
        }

        $newcontrato = Contrato::create([
            'Sedeproveedor_id' => $contrato->Sedeproveedor_id,
            'Tarifa' => $tarifa,
            'Manual' => $request->Manual,
            'Ambito' => $request->Ambito,
            'Anio' => $request->Anio,
            'Estado_id' => 1,
            'entidad_id' => $contrato->entidad_id,
        ]);

        $unidadfuncionales = Familia::select(['familias.*'])
            ->join('contratofamilias as cf', 'cf.Familia_id', 'familias.id')
            ->where('cf.Contrato_id', $contrato->id)
            ->get();
        
        $contrato->familias()->detach();

        if (isset($unidadfuncionales)) {
            foreach ($unidadfuncionales as $unidad){
                $newcontrato->familias()->attach($unidad->id);
            }
        }

        $getarifarios = Tarifario::where('Contrato_id',$contrato->id)->where('Estado_id', 1)->get();

        $tarifarios = Tarifario::where('Contrato_id',$contrato->id)
        ->update([
            'Estado_id' => 2
        ]);

        if($request->Manual == 'Tarifa Propia'){
            foreach ($getarifarios as $tari) {
                Tarifario::create([
                    'Cup_id'           => $tari->Cup_id,
                    'Fechainicio'      => date('Y-m-d'),
                    'Fechafinal'       => date('Y-m-d'),
                    'Tarifa'           => $tarifa,
                    'Manual'           => $request->Manual,
                    'Ambito'           => $request->Ambito,
                    'Valor'            => 0,
                    'Estado_id'        => 1,
                    'Contrato_id'      => $newcontrato->id
                ]); 
            }
        }else{

            $signo = $tarifa[0];
            if ($signo == '-' || $signo == '+') {
                $tarifa_sinsigno = substr($tarifa, 1);
            }else {
                $tarifa_sinsigno = $tarifa;
            }

            foreach ($getarifarios as $tari) {

                if(isset($aniocodigo)){
                    $codigomanual = Codigomanual::select(['codigomanuals.*'])
                    ->join('tipomanuales as tm', 'codigomanuals.Tipomanual_id', 'tm.id')
                    ->where('tm.Nombre', $request->Manual)
                    ->where('codigomanuals.Cup_id', $tari->Cup_id)
                    ->where('codigomanuals.anio', $aniocodigo->anio)
                    ->where('codigomanuals.Estado_id', 1)
                    ->first();
                }else{
                    $codigomanual = Codigomanual::select(['codigomanuals.*'])
                    ->join('tipomanuales as tm', 'codigomanuals.Tipomanual_id', 'tm.id')
                    ->where('tm.Nombre', $request->Manual)
                    ->where('codigomanuals.Cup_id', $tari->Cup_id)
                    ->where('codigomanuals.Estado_id', 1)
                    ->first();
                }

                if (isset($codigomanual)) {
                    if($request->pleno){
                        $valor = $codigomanual->Valor;
                    }else{
                        if ($signo == '-') {
                            $valor = $codigomanual->Valor - ($codigomanual->Valor * $tarifa_sinsigno / 100);
                        } else {
                            $valor = ($codigomanual->Valor * $tarifa_sinsigno / 100) + $codigomanual->Valor;
                        }
                    }
                    $valor = round($valor);
                } else {
                    $valor = 0;
                }

                Tarifario::create([
                    'Cup_id'           => $tari->Cup_id,
                    'Fechainicio'      => date('Y-m-d'),
                    'Fechafinal'       => date('Y-m-d'),
                    'Tarifa'           => $tarifa,
                    'Manual'           => $request->Manual,
                    'Ambito'           => $request->Ambito,
                    'Valor'            => $valor,
                    'Estado_id'        => 1,
                    'Contrato_id'      => $newcontrato->id
                ]); 
            }
        }

        return response()->json([
            'message' => 'Contrato actualizado con exito!',
        ], 200);

    }

    public function disable(Contrato $contrato)
    {
        $contrato->update([
            'Estado_id' => 2,
        ]);

        $contrato->familias()->detach();

        $tarifarios = Tarifario::where('Contrato_id',$contrato->id)
        ->update([
            'Estado_id' => 2
        ]);

        return response()->json([
            'message' => 'Contrato deshabilitado!',
        ], 200);
    }

    public function destroy(Contrato $contrato)
    {
        //
    }

    public function saveTarifa(Request $request, Contrato $contrato)
    {
        $contrato->familias()->detach();
        foreach ($request->opcUnidadFuncional as $unidadfuncional) {
            $contrato->familias()->attach($unidadfuncional['id']);
            if ($contrato->Manual == 'Tarifa Propia') {
                $this->saveTarifaPropia($request, $contrato, $unidadfuncional);
            } else {
                $this->saveManual($request, $contrato, $unidadfuncional);
            }
        }

        return response()->json([
            'message' => 'Tarifa guardada con Exito!',
        ], 201);

    }
    public function saveTarifaPropia($request, $contrato, $uf = null)
    {
        if (isset($uf)) {
            $all        = $uf['all'];
            $Familia_id = $uf['id'];
        } else {
            $all        = $request->all;
            $Familia_id = $request->id;
        }
        if ($all) {
            $cups = Cup::select(['cups.*'])
                ->join('cupfamilias as cf', 'cf.Cup_id', 'cups.id')
                ->where('cf.Familia_id', $Familia_id)
                ->get();

            foreach ($cups as $cup) {

                Tarifario::join('contratos', 'tarifarios.Contrato_id', 'contratos.id')
                ->where('tarifarios.Cup_id', $cup->id)
                ->where('contratos.Sedeproveedor_id', $contrato->Sedeproveedor_id)
                ->where('contratos.entidad_id', $contrato->entidad_id)
                ->where('tarifarios.Estado_id', 1)
                ->update([
                    'Estado_id' => 2,
                ]);

                $isTarifa = Tarifario::where('Cup_id', $cup->id)->where('Contrato_id', $contrato->id)->where('Ambito', $contrato->Ambito)->first();

                if (!isset($isTarifa)) {

                    Tarifario::create([
                        'Cup_id'           => $cup->id,
                        'Fechainicio'      => date('Y-m-d'),
                        'Fechafinal'       => date('Y-m-d'),
                        'Tarifa'           => $contrato->Tarifa,
                        'Manual'           => $contrato->Manual,
                        'Ambito'           => $contrato->Ambito,
                        'Valor'            => 0,
                        'Estado_id'        => 1,
                        'Contrato_id'      => $contrato->id
                    ]);
                } else {
                    $isTarifa->update([
                        'Fechainicio' => date('Y-m-d'),
                        'Fechafinal'  => date('Y-m-d'),
                        'Tarifa'      => $contrato->Tarifa,
                        'Manual'      => $contrato->Manual,
                        'Ambito'      => $contrato->Ambito,
                        'Valor'       => 0,
                        'Estado_id'   => 1,
                        'Contrato_id' => $contrato->id
                    ]);
                }

                $tarifaDisabled = Tarifario::select('tarifarios.id', 'tarifarios.Contrato_id')
                ->join('contratos', 'tarifarios.Contrato_id', 'contratos.id')
                ->where('tarifarios.Cup_id', $cup->id)
                ->where('contratos.Sedeproveedor_id', $contrato->Sedeproveedor_id)
                ->where('contratos.entidad_id', $contrato->entidad_id)
                ->where('tarifarios.Estado_id', 2)
                ->first();

                if($tarifaDisabled){
                    $tarifaEnabled = Tarifario::where('Contrato_id', $tarifaDisabled->Contrato_id)->where('Estado_id', 1)->count();
                    if ($tarifaEnabled == 0) {
                        $contrato = Contrato::where('id', $tarifaDisabled->Contrato_id)->first();
                        $contrato->familias()->detach([$request->id]);
                    }
                }

            }
        } else {
            if (isset($uf)) {
                $cup1 = $unidadfuncional['cup1'];
                $cup2 = $unidadfuncional['cup2'];
            } else {
                $cup1 = $request->cup1;
                $cup2 = $request->cup2;
            }
            $rango1 = Cup::find($cup1);
            $rango2 = Cup::find($cup2);

            if ($rango1->id <= $rango2->id) {
                $id1 = $rango1->id;
                $id2 = $rango2->id;
            } else {
                $id1 = $rango2->id;
                $id2 = $rango1->id;
            }
            $cups = Cup::select(['cups.*'])
                ->join('cupfamilias as cf', 'cf.Cup_id', 'cups.id')
                ->where('cups.id', '>=', $id1)
                ->where('cups.id', '<=', $id2)
                ->where('cf.Familia_id', $Familia_id)
                ->get();

            foreach ($cups as $cup) {

                Tarifario::join('contratos', 'tarifarios.Contrato_id', 'contratos.id')
                ->where('tarifarios.Cup_id', $cup->id)
                ->where('contratos.Sedeproveedor_id', $contrato->Sedeproveedor_id)
                ->where('contratos.entidad_id', $contrato->entidad_id)
                ->where('tarifarios.Estado_id', 1)
                ->update([
                    'Estado_id' => 2,
                ]);

                $isTarifa = Tarifario::where('Cup_id', $cup->id)->where('Contrato_id', $contrato->id)->where('Ambito', $contrato->Ambito)->first();

                if (!isset($isTarifa)) {
                    Tarifario::create([
                        'Cup_id'           => $cup->id,
                        'Fechainicio'      => date('Y-m-d'),
                        'Fechafinal'       => date('Y-m-d'),
                        'Tarifa'           => $contrato->Tarifa,
                        'Manual'           => $contrato->Manual,
                        'Ambito'           => $contrato->Ambito,
                        'Valor'            => 0,
                        'Estado_id'        => 1,
                        'Contrato_id'      => $contrato->id
                    ]);
                } else {
                    $isTarifa->update([
                        'Fechainicio' => date('Y-m-d'),
                        'Fechafinal'  => date('Y-m-d'),
                        'Tarifa'      => $contrato->Tarifa,
                        'Manual'      => $contrato->Manual,
                        'Ambito'      => $contrato->Ambito,
                        'Valor'       => 0,
                        'Estado_id'   => 1,
                        'Contrato_id' => $contrato->id
                    ]);
                }

                $tarifaDisabled = Tarifario::select('tarifarios.id', 'tarifarios.Contrato_id')
                ->join('contratos', 'tarifarios.Contrato_id', 'contratos.id')
                ->where('tarifarios.Cup_id', $cup->id)
                ->where('contratos.Sedeproveedor_id', $contrato->Sedeproveedor_id)
                ->where('contratos.entidad_id', $contrato->entidad_id)
                ->where('tarifarios.Estado_id', 2)
                ->first();

                if($tarifaDisabled){
                    $tarifaEnabled = Tarifario::where('Contrato_id', $tarifaDisabled->Contrato_id)->where('Estado_id', 1)->count();
                    if ($tarifaEnabled == 0) {
                        $contrato = Contrato::where('id', $tarifaDisabled->Contrato_id)->first();
                        $contrato->familias()->detach([$request->id]);
                    }
                }

            }
        }
    }
    public function saveManual($request, $contrato, $uf = null)
    {
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

        $signo = $contrato->Tarifa[0];
        if ($signo == '-' || $signo == '+') {
            $tarifa = substr($contrato->Tarifa, 1);
        } else {
            $tarifa = $contrato->Tarifa;
        }
        if (isset($uf)) {
            $all        = $uf['all'];
            $Familia_id = $uf['id'];
            $cup1       = $uf['cup1'];
            $cup2       = $uf['cup2'];
        } else {
            $all        = $request->all;
            $Familia_id = $request->id;
            $cup1       = $request->cup1;
            $cup2       = $request->cup2;
        }
        if ($all) {

            $cups = Cup::select(['cups.*'])
                ->join('cupfamilias as cf', 'cf.Cup_id', 'cups.id')
                ->where('cf.Familia_id', $Familia_id)
                ->get();

            foreach ($cups as $cup) {

                if(isset($aniocodigo)){
                    $codigomanual = Codigomanual::select(['codigomanuals.*'])
                    ->join('tipomanuales as tm', 'codigomanuals.Tipomanual_id', 'tm.id')
                    ->where('tm.Nombre', $contrato->Manual)
                    ->where('codigomanuals.Cup_id', $cup->id)
                    ->where('codigomanuals.anio', $aniocodigo->anio)
                    ->where('codigomanuals.Estado_id', 1)
                    ->first();
                }else{
                    $codigomanual = Codigomanual::select(['codigomanuals.*'])
                    ->join('tipomanuales as tm', 'codigomanuals.Tipomanual_id', 'tm.id')
                    ->where('tm.Nombre', $contrato->Manual)
                    ->where('codigomanuals.Cup_id', $cup->id)
                    ->where('codigomanuals.Estado_id', 1)
                    ->first();
                }

                if (isset($codigomanual)) {
                    if($request->pleno){
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
                    $valor = 0;
                }

                Tarifario::join('contratos', 'tarifarios.Contrato_id', 'contratos.id')
                ->where('tarifarios.Cup_id', $cup->id)
                ->where('contratos.Sedeproveedor_id', $contrato->Sedeproveedor_id)
                ->where('contratos.entidad_id', $contrato->entidad_id)
                ->where('tarifarios.Estado_id', 1)
                ->update([
                    'Estado_id' => 2,
                ]);

                $isTarifa = Tarifario::where('Cup_id', $cup->id)->where('Contrato_id', $contrato->id)->where('Ambito', $contrato->Ambito)->first();
                if (!isset($isTarifa)) {
                    Tarifario::create([
                        'Cup_id'           => $cup->id,
                        'Fechainicio'      => date('Y-m-d'),
                        'Fechafinal'       => date('Y-m-d'),
                        'Tarifa'           => $contrato->Tarifa,
                        'Manual'           => $contrato->Manual,
                        'Ambito'           => $contrato->Ambito,
                        'Valor'            => $valor,
                        'Estado_id'        => 1,
                        'Contrato_id'      => $contrato->id
                    ]);
                } else {
                    $isTarifa->update([
                        'Fechainicio' => date('Y-m-d'),
                        'Fechafinal'  => date('Y-m-d'),
                        'Tarifa'      => $contrato->Tarifa,
                        'Manual'      => $contrato->Manual,
                        'Ambito'      => $contrato->Ambito,
                        'Valor'       => $valor,
                        'Estado_id'   => 1,
                        'Contrato_id' => $contrato->id
                    ]);
                }

                $tarifaDisabled = Tarifario::select('tarifarios.id', 'tarifarios.Contrato_id')
                ->join('contratos', 'tarifarios.Contrato_id', 'contratos.id')
                ->where('tarifarios.Cup_id', $cup->id)
                ->where('contratos.Sedeproveedor_id', $contrato->Sedeproveedor_id)
                ->where('contratos.entidad_id', $contrato->entidad_id)
                ->where('tarifarios.Estado_id', 2)
                ->first();

                if($tarifaDisabled){
                    $tarifaEnabled = Tarifario::where('Contrato_id', $tarifaDisabled->Contrato_id)->where('Estado_id', 1)->count();
                    if ($tarifaEnabled == 0) {
                        $contrato = Contrato::where('id', $tarifaDisabled->Contrato_id)->first();
                        $contrato->familias()->detach([$request->id]);
                    }
                }

            }
        } else {
            $rango1 = Cup::find($cup1);
            $rango2 = Cup::find($cup2);
            if ($rango1->id <= $rango2->id) {
                $id1 = $rango1->id;
                $id2 = $rango2->id;
            } else {
                $id1 = $rango2->id;
                $id2 = $rango1->id;
            }
            $cups = Cup::select(['cups.*'])
                ->join('cupfamilias as cf', 'cf.Cup_id', 'cups.id')
                ->where('cups.id', '>=', $id1)
                ->where('cups.id', '<=', $id2)
                ->where('cf.Familia_id', $Familia_id)
                ->get();
            foreach ($cups as $cup) {

                if(isset($aniocodigo)){
                    $codigo = Codigomanual::join('tipomanuales as tm', 'Codigomanuals.Tipomanual_id', 'tm.id')
                    ->where('Codigomanuals.Cup_id', $cup->id)
                    ->where('tm.Nombre', $contrato->Manual)
                    ->where('codigomanuals.anio', $aniocodigo->anio)
                    ->where('codigomanuals.Estado_id', 1)
                    ->first();
                }else{
                    $codigo = Codigomanual::join('tipomanuales as tm', 'Codigomanuals.Tipomanual_id', 'tm.id')
                    ->where('Codigomanuals.Cup_id', $cup->id)
                    ->where('tm.Nombre', $contrato->Manual)
                    ->where('codigomanuals.Estado_id', 1)
                    ->first();
                }
                
                if (isset($codigo)) {
                    if($request->pleno){
                        $valor = $codigo->Valor;
                    }else{
                        if ($signo == '-') {
                            $valor = $codigo->Valor - ($codigo->Valor * $tarifa / 100);
                        } else {
                            $valor = ($codigo->Valor * $tarifa / 100) + $codigo->Valor;
                        }
                    }
                    $valor = round($valor);
                } else {
                    $valor = 0;
                }

                Tarifario::join('contratos', 'tarifarios.Contrato_id', 'contratos.id')
                ->where('tarifarios.Cup_id', $cup->id)
                ->where('contratos.Sedeproveedor_id', $contrato->Sedeproveedor_id)
                ->where('contratos.entidad_id', $contrato->entidad_id)
                ->where('tarifarios.Estado_id', 1)
                ->update([
                    'Estado_id' => 2,
                ]);

                $isTarifa = Tarifario::where('Cup_id', $cup->id)->where('Contrato_id', $contrato->id)->where('Ambito', $contrato->Ambito)->first();

                if (!isset($isTarifa)) {
                    Tarifario::create([
                        'Cup_id'           => $cup->id,
                        'Fechainicio'      => date('Y-m-d'),
                        'Fechafinal'       => date('Y-m-d'),
                        'Tarifa'           => $contrato->Tarifa,
                        'Manual'           => $contrato->Manual,
                        'Ambito'           => $contrato->Ambito,
                        'Valor'            => $valor,
                        'Estado_id'        => 1,
                        'Contrato_id'      => $contrato->id
                    ]);
                } else {
                    $isTarifa->update([
                        'Fechainicio' => date('Y-m-d'),
                        'Fechafinal'  => date('Y-m-d'),
                        'Tarifa'      => $contrato->Tarifa,
                        'Manual'      => $contrato->Manual,
                        'Ambito'      => $contrato->Ambito,
                        'Valor'       => $valor,
                        'Estado_id'   => 1,
                        'Contrato_id' => $contrato->id
                    ]);
                }

                $tarifaDisabled = Tarifario::select('tarifarios.id', 'tarifarios.Contrato_id')
                ->join('contratos', 'tarifarios.Contrato_id', 'contratos.id')
                ->where('tarifarios.Cup_id', $cup->id)
                ->where('contratos.Sedeproveedor_id', $contrato->Sedeproveedor_id)
                ->where('contratos.entidad_id', $contrato->entidad_id)
                ->where('tarifarios.Estado_id', 2)
                ->first();

                if($tarifaDisabled){
                    $tarifaEnabled = Tarifario::where('Contrato_id', $tarifaDisabled->Contrato_id)->where('Estado_id', 1)->count();
                    if ($tarifaEnabled == 0) {
                        $contrato = Contrato::where('id', $tarifaDisabled->Contrato_id)->first();
                        $contrato->familias()->detach([$request->id]);
                    }
                }
                
            }
        }
    }

    public function addTarifa(Request $request, Contrato $contrato)
    {
        if($contrato->Manual == 'Soat'){

            $aniocodigo = Sede_salariominimo::select(['sa.anio'])
            ->join('salariominimos as sa', 'sede_salariominimos.salariominimo_id', 'sa.id')
            ->where('sede_salariominimos.sedeproveedor_id', $contrato->Sedeproveedor_id)
            ->where('sede_salariominimos.estado_id', 1)
            ->first();

            if(!$aniocodigo){
                return response()->json([
                    'messageError' => 'Este sedeproveedor no esta parametrizado en sedeproveedor minimo'
                ], 400);
            }
        }

        $unidadfuncionales = Familia::select(['familias.*'])
            ->join('contratofamilias as cf', 'cf.Familia_id', 'familias.id')
            ->where('cf.Contrato_id', $contrato->id)
            ->where('familias.id', $request->id)
            ->first();

        if (!isset($unidadfuncionales)) {
            $contrato->familias()->attach($request->id);
        }

        if ($contrato->Manual == 'Tarifa Propia') {
            $this->saveTarifaPropia($request, $contrato);
        } else {
            $this->saveManual($request, $contrato);
        }

        return response()->json([
            'message' => '¡Tarifa unidad funcional agregada con exito!',
        ], 201);

    }

    public function allTarifa(Sedeproveedore $sede, Request $request)
    {
        $tarifas = Tarifario::select(['tarifarios.*', 'c.Codigo', 'c.Nombre as Cup', 'cf.Familia_id', 'en.nombre'])
            ->join('cups as c', 'tarifarios.Cup_id', 'c.id')
            ->join('cupfamilias as cf', 'cf.Cup_id', 'c.id')
            ->join('familias as f', 'cf.Familia_id', 'f.id')
            ->join('contratos as cr', 'tarifarios.Contrato_id', 'cr.id')
            ->join('entidades as en', 'cr.entidad_id', 'en.id')
            ->where('cr.Sedeproveedor_id', $sede->id)
            ->where('f.Tipofamilia_id', 4)
            ->where('tarifarios.Estado_id', 1);
            if(isset($request->contrato_id)){
                $tarifas->where('tarifarios.Contrato_id', $request->contrato_id);
            }
            if(isset($request->familia['id'])){
                $tarifas->where('cf.Familia_id', $request->familia['id']);
            }

        return response()->json($tarifas->get(), 201);
    }

    public function updateTarifa($cup, Request $request)
    {
        $tarifa = Tarifario::find($cup);
        $tarifa->update([
            'Valor' => $request->Valor,
        ]);

        return response()->json([
            'message' => 'Tarifa actualizada',
        ], 201);
    }

    public function disableTarifa(Contrato $contrato, Tarifario $tarifa, Request $request)
    {
        $tarifa->update([
            'Estado_id' => 2,
        ]);

        $cupsFamilia = Cup::select(['cups.*'])
            ->join('cupfamilias as cf', 'cf.Cup_id', 'cups.id')
            ->where('cf.Familia_id', $request->familia_id)
            ->get();

        $cup_ids = [];
        foreach ($cupsFamilia as $cupfa) {
            array_push($cup_ids, $cupfa->id);
        }

        $tarifaEnabled = Tarifario::whereIn('Cup_id', $cup_ids)
            ->where('Contrato_id', $contrato->id)
            ->where('Ambito', $contrato->Ambito)
            ->where('Estado_id', 1)
            ->count();

        if ($tarifaEnabled == 0) {
            $contrato->familias()->detach([$request->familia_id]);
        }

        return response()->json([
            'message' => 'Tarifa desabilitada',
        ], 201);
    }

    public function removeunidadFuncionalContrato(Contrato $contrato, Request $request)
    {
        $cupsfamilia = Familia::select(['familias.*', 'cf.Cup_id'])
            ->join('cupfamilias as cf', 'cf.Familia_id', 'familias.id')
            ->where('familias.id', $request->id)
            ->get();

        foreach ($cupsfamilia as $cup) {
            $tarifa = Tarifario::where('Cup_id', $cup->Cup_id)
                ->where('Contrato_id', $contrato->id)
                ->where('Ambito', $contrato->Ambito)
                ->first();

            if (isset($tarifa)) {
                $tarifa->update([
                    'Estado_id' => 2,
                ]);
            }
        }

        $contrato->familias()->detach([$request->id]);

        return response()->json([
            'message' => 'Unidad funcional deshabilitada',
        ], 201);
    }

    public function removeRangoFuncional(Contrato $contrato, Request $request){

        $rango1 = Cup::find($request->cup1);
        $rango2 = Cup::find($request->cup2);
        if ($rango1->id <= $rango2->id) {
            $id1 = $rango1->id;
            $id2 = $rango2->id;
        } else {
            $id1 = $rango2->id;
            $id2 = $rango1->id;
        }

        $cups = Cup::select(['cups.*'])
            ->join('cupfamilias as cf', 'cf.Cup_id', 'cups.id')
            ->where('cups.id', '>=', $id1)
            ->where('cups.id', '<=', $id2)
            ->where('cf.Familia_id', $request->id)
            ->get();
        
        foreach ($cups as $cup) {
            $tarifa = Tarifario::where('Cup_id', $cup->id)
                ->where('Contrato_id', $contrato->id)
                ->where('Ambito', $contrato->Ambito)
                ->first();

            if (isset($tarifa)) {
                $tarifa->update([
                    'Estado_id' => 2,
                ]);
            }
        }

        $cupsFamilia = Cup::select(['cups.*'])
            ->join('cupfamilias as cf', 'cf.Cup_id', 'cups.id')
            ->where('cf.Familia_id', $request->id)
            ->get();

        $cup_ids = [];
        foreach ($cupsFamilia as $cupfa) {
            array_push($cup_ids, $cupfa->id);
        }

        $tarifaEnabled = Tarifario::whereIn('Cup_id', $cup_ids)
            ->where('Contrato_id', $contrato->id)
            ->where('Ambito', $contrato->Ambito)
            ->where('Estado_id', 1)
            ->count();

        if ($tarifaEnabled == 0) {
            $contrato->familias()->detach([$request->id]);
        }

        return response()->json([
            'message' => 'Tarifa unidad funcional por rangos deshabilitadas',
        ], 201);
    }
}
