<?php
namespace App\Http\Controllers\Tarifarios;

use App\Modelos\Auditoria;
use App\Modelos\Codesumi;
use App\Modelos\InventarioBodega;
use Illuminate\Http\Request;
use App\Modelos\citapaciente;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CodesumiController extends Controller
{
    public function all()
    {
        $codesumi = Codesumi::select('codesumis.*', 'tipocodigos.Nombre as Nombredetallearticulo')
            ->join('tipocodigos', 'codesumis.Tipocodesumi_id', 'tipocodigos.id')
            ->get();

        return response()->json($codesumi, 200);
    }

    public function kardex(Request $request)
    {
        $inventarios = [];
        $kardex = Collect(DB::select("exec dbo.tuerto ?,?,?,?,?", [$request->bodega_id,$request->codigos,$request->startDate,$request->finishDate,$request->codigoFactura]));
        if(!$request->codigoFactura){
            $inventarios = InventarioBodega::select([
                'bdg.Nombre as Bodega',
                'ci.created_at as Creacion_del_movimiento',
                DB::raw("CONCAT('Inventario','') as Transación"),
                'ci.Value1 as Cantidadtotal',
                'ci.Value1 as CantidadtotalFactura',
                'ci.Value1 as Cantidadtotalinventario',
                'l.Numlote as Numlote',
                'l.Fvence',
                'c.Nombre',
                'u.name',
                'u.apellido',
                'especialidad_medico as Cargo'
            ])
                ->join('conteoinventarios as ci','ci.inventario_bodega_id','inventario_bodegas.id')
                ->join('lotes as l','l.id','ci.Lote_id')
                ->join('bodegarticulos as b','b.id','l.Bodegarticulo_id')
                ->join('detallearticulos as da','da.id','b.Detallearticulo_id')
                ->join('codesumis as c','c.id','da.Codesumi_id')
                ->join('bodegas as bdg','bdg.id','inventario_bodegas.bodega_id')
                ->join('users as u','u.id','ci.UserCrea_id')
                ->where('inventario_bodegas.bodega_id',$request->bodega_id)
                ->where('c.Codigo',$request->codigos)
                ->whereBetween('ci.created_at',[$request->startDate,$request->finishDate])
                ->get();
        }
            $data =[
                'kardex' => $kardex,
                'inventario' => $inventarios
            ];
              return response()->json($data);
    }

    public function codesumiByType(Request $request)
    {
        $codesumi = Codesumi::select('codesumis.*', 'tipocodigos.Nombre as Nombredetallearticulo')
            ->join('tipocodigos', 'codesumis.Tipocodesumi_id', 'tipocodigos.id')
            ->where('codesumis.Tipocodesumi_id', $request->type)
            ->get();

        return response()->json($codesumi, 200);
    }

    public function medicamentos($citapaciente)
    {
        $niveles = auth()->user()->getRepository()->getQueryLevelItemsOrder();

        $medicamentos = Codesumi::whereIn('Tipocodesumi_id', [1, 2, 7, 8])
            ->where(function ($query) {
                $query->where('Nombre', '<>', '')
                    ->whereNotNull('Nombre');
            })
            ->whereNotNull('Codigo')
            ->whereNotNull('Frecuencia')
            ->whereNotNull('Cantidadmaxordenar')
            ->whereNotNull('Requiere_autorizacion')
            ->whereNotNull('Nivel_Ordenamiento')
            ->whereIn('Nivel_Ordenamiento', $niveles)
            ->where('Estado_id', 1)
            ->get();

        return response()->json($medicamentos, 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'Nombre' => 'required|string',
            'Codigo' => 'required|string|unique:Codesumis|unique:Cups',
        ]);

        if ($validate->fails()) {
            $errores = $validate->errors();
            return response()->json($errores, 422);
        }

        $codesumi = Codesumi::select('Codesumis.*')
            ->where('Codesumis.Codigo', $request->Codigo)
            ->first();

        if (isset($codesumi)) {
            return response()->json([
                'message' => 'Codesumi ya existe',
            ], 422);
        }

        $codesumi = Codesumi::create([
            'Nombre'                => $request->Nombre,
            'Codigo'                => $request->Codigo,
            'Frecuencia'            => $request->Frecuencia,
            'Cantidadmaxordenar'    => $request->Cantidadmaxordenar,
            'Requiere_autorizacion' => $request->Requiere_autorizacion,
            'Nivel_Ordenamiento'    => $request->Nivel_Ordenamiento,
            'Tipocodesumi_id'       => $request->Tipocodesumi_id,
            'Genero'                => $request->Genero,
            'EdadInicial'           => $request->EdadInicial,
            'EdadFinal'             => $request->EdadFinal,
            'Ambito'                => $request->Ambito,
            'Estado_id'             => 1,
        ]);

        return response()->json([
            'message' => 'Codesumi creado con Exito!',
        ], 201);
    }

    public function show($Codesumi)
    {
        $codesumi = Codesumi::find($Codesumi);
        if (!isset($codesumi)) {
            return response()->json([
                'message' => 'El codigo buscado no existe verifica la consulta!',
            ], 404);
        }
        return response()->json($codesumi, 200);
    }

    public function update(Request $request, Codesumi $codesumi)
    {
        $msg    = 'Actualizó';
        $cambio = false;
        if ($request->Nombre != $codesumi->Nombre) {
            $msg    = $msg . ' Nombre de "' . $codesumi->Nombre . '" a "' . $request->Nombre . '"';
            $cambio = true;
        }
        if ($request->Codigo != $codesumi->Codigo) {
            $msg    = $msg . ' Codigo de "' . $codesumi->Codigo . '" a "' . $request->Codigo . '"';
            $cambio = true;
        }
        if ($request->Frecuencia != $codesumi->Frecuencia) {
            $msg    = $msg . ' Frecuencia de "' . $codesumi->Frecuencia . '" a "' . $request->Frecuencia . '"';
            $cambio = true;
        }
        if ($request->Cantidadmaxordenar != $codesumi->Cantidadmaxordenar) {
            $msg    = $msg . ' Cantidadmaxordenar de "' . $codesumi->Cantidadmaxordenar . '" a "' . $request->Cantidadmaxordenar . '"';
            $cambio = true;
        }
        if ($request->Requiere_autorizacion != $codesumi->Requiere_autorizacion) {
            $msg    = $msg . ' requiere auditoria de "' . $codesumi->Requiere_autorizacion . '" a "' . $request->Requiere_autorizacion . '"';
            $cambio = true;
        }
        if ($request->Nivel_Ordenamiento != $codesumi->Nivel_Ordenamiento) {
            $msg    = $msg . ' nivel de ordenamiento de "' . $codesumi->Nivel_Ordenamiento . '" a "' . $request->Nivel_Ordenamiento . '"';
            $cambio = true;
        }
        if ($request->concentracion != $codesumi->concentracion) {
            $msg    = $msg . ' concentracion de "' . $codesumi->concentracion . '" a "' . $request->concentracion . '"';
            $cambio = true;
        }
        if ($request->unidadMedida != $codesumi->unidadMedida) {
            $msg    = $msg . ' unidadMedida de "' . $codesumi->unidadMedida . '" a "' . $request->unidadMedida . '"';
            $cambio = true;
        }

        if (!$cambio) {
            return response()->json([
                'message' => 'codesumi actualizado con Exito!!',
            ], 200);
        }

        $codesumi->update([
            'Nombre'                => $request->Nombre,
            'Codigo'                => $request->Codigo,
            'Requiere_autorizacion' => $request->Requiere_autorizacion,
            'Nivel_Ordenamiento'    => $request->Nivel_Ordenamiento,
            'Frecuencia'            => $request->Frecuencia,
            'Cantidadmaxordenar'    => $request->Cantidadmaxordenar,
            'concentracion'    => $request->concentracion,
            'unidadMedida'    => $request->unidadMedida,
        ]);

        Auditoria::create([
            'Descripcion'        => $msg,
            'Tabla'              => 'Codesumis',
            'Usuariomodifica_id' => auth()->user()->id,
            'Model_id'           => $codesumi->id,
            'Motivo'             => '',
        ]);

        return response()->json([
            'message' => 'codesumi actualizado con Exito!',
        ], 200);

    }

    public function available(Request $request, Codesumi $Codesumi)
    {
        $Codesumi->update([
            'Estado' => $request->Estado,
        ]);
        return response()->json([
            'message' => 'Estado de la Actualizada con Exito!',
        ], 200);
    }

    public function enabled()
    {
        $Codesumi = Codesumi::where('Estado', 1)->get();
        return response()->json($Codesumi, 200);
    }

    public function import(Request $request)
    {
        $codesumi = (new FastExcel)->import($request->data, function ($line) {
            return Codesumi::create([
                'Nombre' => $line['Nombre'],
                'Codigo' => $line['Codigo'],
                'Estado' => $line['Estado'],
            ]);
        });
        return response()->json([
            'message' => 'Carga de codigos sumimedical exitosa!',
        ], 200);
    }

    public function enableCodesumis()
    {
        $codeSumis = Codesumi::getRepository()->enableCodesumis();
        return response()->json($codeSumis->get(), 200);
    }

    public function codesumiEsquema($esquema)
    {
        $codeSumisEsquea = Codesumi::getRepository()->codesumiEsquema($esquema);
        return response()->json($codeSumisEsquea->get(), 200);
    }
}
