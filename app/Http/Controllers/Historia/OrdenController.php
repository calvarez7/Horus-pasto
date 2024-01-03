<?php

namespace App\Http\Controllers\Historia;

use Carbon\Carbon;
use App\Modelos\Cup;
use App\Modelos\Lote;
use App\Modelos\AmRip;
use App\Modelos\Orden;
use App\Modelos\Alerta;
use App\Modelos\Codesumi;
use App\Modelos\Cuporden;
use App\Modelos\hcmedima;
use App\Modelos\Paciente;
use App\Modelos\Codepropio;
use App\Modelos\Movimiento;
use Illuminate\Http\Request;
use App\Modelos\citapaciente;
use App\Modelos\Incapacidade;
use App\Modelos\Recomendacione;
use App\Modelos\Detallearticulo;
use App\Modelos\AuditoriaAlertas;
use App\Modelos\Bodegatransacion;
use App\Modelos\Detaarticulorden;
use App\Modelos\FormulaOptometria;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Modelos\RecomendacionesClinicas;
use App\Modelos\AntecedenteFarmacologico;
use App\Http\Controllers\Entidades\EntidadController;

class OrdenController extends Controller
{
    public function index(Request $request,$documento)
    {
        $ordenes = Detaarticulorden::select(
            'detaarticulordens.id as id',
            'detaarticulordens.created_at as FechaOrdenamiento',
            'detaarticulordens.Cantidadosis as Cantidad_Dosis',
            'detaarticulordens.codesumi_id as Codesumi_id',
            'detaarticulordens.Via as Via',
            'detaarticulordens.Unidadmedida as Unidad_Medida',
            'detaarticulordens.Frecuencia as Frecuencia',
            'detaarticulordens.Unidadtiempo as Unidad_Tiempo',
            'detaarticulordens.Duracion as Duracion',
            'detaarticulordens.Cantidadmensual as Cantidad_Mensual',
            'detaarticulordens.Nummeses as Num_Meses',
            'detaarticulordens.Observacion as Observacion',
            'detaarticulordens.Cantidadpormedico as Cantidad_Medico',
            'detaarticulordens.Cantidadmensualdisponible as Cantidad_Mensual_Disponible',
            'detaarticulordens.Orden_id as Orden_id',
            'detaarticulordens.Fechaorden as Fecha_Orden',
            'detaarticulordens.Estado_id as Estado_id',
            'detaarticulordens.mipres',
            'au.Nota as nota_autorizacion',
            'au.created_at as fechaAutorizacion',
            'e.Nombre as Estado',
            'c.Nombre as Nombre',
            'c.Codigo as Codigo',
            'c.id as id_codesumi',
            'c.Requiere_autorizacion as Requiere_Autorizacion',
            'o.id as or',
            'o.paginacion',
            'o.Fechaorden'
        )
            ->join('codesumis as c', 'detaarticulordens.codesumi_id', 'c.id')
            ->join('estados as e','e.id','detaarticulordens.Estado_id')
            ->join('ordens as o','detaarticulordens.Orden_id','o.id')
            ->join('cita_paciente as cp','o.Cita_id','cp.id')
            ->join('pacientes as p','cp.Paciente_id','p.id')
            ->join('tipocitas as tp','tp.id','cp.Tipocita_id')
            ->leftjoin('users as u','u.id','cp.Usuario_id')
            ->where('p.Num_doc',$documento)
            ->where('o.Tiporden_id',3)
            ->orderBy('o.id','desc')
            ->leftjoin('autorizacions as au', 'au.Articulorden_id', 'detaarticulordens.id')
            ->get();

        return response()->json($ordenes);
    }

    public function getServicios(Request $request,$documento){

        $ordenes = Cuporden::select(
            'cupordens.id',
            'cupordens.created_at as FechaOrdenamiento',
            'cupordens.Cantidad',
            'cupordens.anexo3',
            'e.Nombre as Estado',
            'cupordens.Estado_id',
            'c.Codigo',
            'c.Nombre',
            'c.Requiere_auditoria as autorizacion',
            'o.id as orden',
            'o.Fechaorden',
            'cupordens.cancelacion',
            'cupordens.fecha_solicitada',
            'cupordens.fecha_sugerida',
            'cupordens.cancelacion',
            'cupordens.observaciones',
            'cupordens.responsable',
            'cupordens.motivo',
            'cupordens.causa',
            'cupordens.cirujano',
            'cupordens.especialidad',
            'cupordens.fecha_Preanestesia',
            'cupordens.fecha_cirugia',
            'cupordens.fecha_ejecucion',
            'cupordens.fecha_resultado',
            's.Nombre as Sede_Nombre'
        )
            ->join('cups as c', 'cupordens.Cup_id' , 'c.id')
            ->join('ordens as o','cupordens.Orden_id', 'o.id')
            ->join('estados as e' , 'cupordens.Estado_id','e.id')
            ->join('cita_paciente as cp','o.Cita_id','cp.id')
            ->join('pacientes as p' , 'cp.Paciente_id', 'p.id')
            ->join('tipocitas as t' , 'cp.Tipocita_id' , 't.id')
            ->leftjoin('users as u' , 'cp.Usuario_id' , 'u.id')
            ->leftjoin('autorizacions as a' , 'cupordens.id', 'a.Cuporden_id')
            ->leftJoin('sedeproveedores as s', 'cupordens.Ubicacion_id', DB::raw("CONVERT(VARCHAR, s.id)"))
            ->where('p.Num_doc',$documento)
            ->whereIn('o.Tiporden_id',[2,4])
            ->get();

        return response()->json($ordenes);
    }

    public function getOrdens(citapaciente $citapaciente)
    {
        $ordenes = Orden::where('Cita_id', $citapaciente->id)->where('Estado_id', 1)->where('Tiporden_id', 3)->get();

        return response()->json($ordenes, 201);
    }

    public function ordenesMedicamento(Request $request)
    {
        $ordenes = Orden::select(['ordens.*', 'to.Nombre as tipo_orden'])
            ->join('cita_paciente as cp', 'ordens.Cita_id', 'cp.id')
            ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
            ->join('tipordens as to', 'to.id', 'ordens.Tiporden_id')
            ->join('detaarticulordens as do', 'do.Orden_id', 'ordens.id')
            ->with(['detaarticulordens' => function ($query) {
                $query->select('detaarticulordens.*', 'cs.Codigo as Codigo', 'cs.Nombre as medicamento')
                    ->join('codesumis as cs', 'detaarticulordens.Codesumi_id', 'cs.id')
                    ->whereIn('detaarticulordens.Estado_id', [1, 7, 18, 19])
                    ->get();
            }])
            ->whereHas('detaarticulordens', function ($query) {
                $query->whereIn('Estado_id', [1, 7, 18, 19]);
            })
            ->distinct()
            ->where('p.Num_Doc', $request->Num_Doc)
            ->whereMonth('ordens.Fechaorden', '<=', $request->Month)
            ->get();

        return response()->json($ordenes, 201);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function lotesMedicamentos(Request $request)
    {
        $codesumi = Codesumi::getRepository()->lotesMedicamentos($request);

        return response()->json($codesumi->get(), 201);
    }

    public function getLote(Request $request)
    {
        $lotes = Lote::select('lotes.*')
            ->join('bodegarticulos as ba', 'ba.id', 'lotes.Bodegarticulo_id')
            ->join('detallearticulos as da', 'da.id', 'ba.Detallearticulo_id')
            ->where('da.Codesumi_id', $request->Codesumi_id)
            ->where('da.CUM_completo', $request->CUM_completo)
            ->where('ba.Bodega_id', $request->Bodega_id)
            ->where('lotes.Cantidadisponible', '>', 0)
            ->get();

        return response()->json($lotes, 201);
    }

    public function store(citapaciente $citapaciente, Request $request)
    {
        $ubicaciones = [];
        $niveluser   = auth()->user()->getRepository()->getOrderingLevelItemsOrder();

        $orden_return = '';
        switch ($request->Tiporden_id) {
            case 1:
                $date = date("Y-m-d");

                $incapacidad = $this->getRange($request);

                if (!empty($incapacidad)) {
                    return response()->json([
                        'message' =>
                            'Ya existe una incapacidad activa con fecha de inicio ' . $incapacidad->Fechainicio . ' y fecha final ' . $incapacidad->Fechafinal,
                    ], 404);
                }

                $orden = Orden::create([
                    'Tiporden_id' => $request->Tiporden_id,
                    'Cita_id' => $citapaciente->id,
                    'Usuario_id' => auth()->user()->id,
                    'paginacion' => '1',
                    'Estado_id' => 1,
                    'Fechaorden' => $date,
                ]);
                Incapacidade::create([
                    'Orden_id' => $orden->id,
                    'Fechainicio' => $request->Fechainicio,
                    'Dias' => $request->Dias,
                    'Fechafinal' => $request->Fechafinal,
                    'Prorroga' => $request->Prorroga,
                    'Cie10_id' => $request->Cie10_id,
                    'Contingencia' => $request->Contingencia,
                    'Descripcion' => $request->Descripcion,
                    'Usuarioordena_id' => auth()->user()->id,
                    'Usuarioedit_id' => auth()->user()->id,
                    'Laboraen' => $request->Laboraen,
                ]);
                break;
            case 3:
                $datos = ['sinAutorizacion' => [], 'conAutorizacion' => []];
                foreach ($request->articulos as $medicamento) {
                    if ($medicamento["Requiere_autorizacion"] == 'SI') {
                        $datos["conAutorizacion"][] = $medicamento;
                    } else {
                        $datos["sinAutorizacion"][] = $medicamento;
                    }
                }

                foreach ($datos as $key => $tipo) {
                    if (count($tipo) >= 1) {
                        $numOrdenes = [];
                        foreach ($tipo as $articulo) {
                            for ($i = 0; $i < $articulo['NumMeses']; $i++) {
                                if (!isset($numOrdenes[$i])) {
                                    array_push($numOrdenes, []);
                                }
                                array_push($numOrdenes[$i], $articulo);
                            }
                        }
                        for ($i = 0; $i < count($numOrdenes); $i++) {
                            $pagina = $i + 1;
                            $date = date('Y-m-d', strtotime("+" . strval($i * 28) . " day"));
//                            if ($date < '2023-10-31') {
                            $orden = Orden::where('Tiporden_id', $request->Tiporden_id)->where('Cita_id', $citapaciente->id)->where('paginacion', 'like', $pagina . '/%')->where('autorizacion', ($key === 'conAutorizacion' ? true : false))->first();
                            if (!isset($orden)) {
                                $orden = Orden::create([
                                    'Tiporden_id' => $request->Tiporden_id,
                                    'Cita_id' => $citapaciente->id,
                                    'Usuario_id' => auth()->user()->id,
                                    'paginacion' => $pagina . '/' . count($numOrdenes),
                                    'Estado_id' => 1,
                                    'Fechaorden' => $date,
                                ]);
                            } else {
                                $countNumOrdenes = count($numOrdenes);
                                $paginacionLastOrden = explode("/", $orden->paginacion);

                                if (intval($paginacionLastOrden[1]) > $countNumOrdenes) {
                                    $countNumOrdenes = $paginacionLastOrden[1];
                                }

                                $orden->update([
                                    'Usuario_id' => auth()->user()->id,
                                    'paginacion' => $pagina . '/' . $countNumOrdenes,
                                    'Estado_id' => 1,
                                    'Fechaorden' => $date,
                                ]);
                            }

                            if ($i == 0) {
                                $orden_return = $orden->id;
                            }

                            for ($j = 0; $j < count($numOrdenes[$i]); $j++) {
                                $codigo = Codesumi::find($numOrdenes[$i][$j]['id']);
                                $estado = 1;
                                $paciente = Paciente::find($citapaciente->Paciente_id);
                                if ($codigo->Requiere_autorizacion == 'SI' || ($niveluser < $codigo->Nivel_Ordenamiento)) {
                                    if (intval($paciente->entidad_id) !== 7) {
                                        $estado = 15;
                                        $orden->update(['autorizacion' => 1]);
                                    }
                                }else{
                                    if ($date > '2024-04-30' && intval($paciente->entidad_id) === 1) {
                                        $estado = 58;
                                    }
                                }
                                if ($paciente->portabilidad == 1 && $paciente->Estado_Afiliado != 27 && $codigo->nivelportabilidad != 1) {
                                    $estado = 37;
                                }
                                if(count($numOrdenes) === $i+1 && $codigo->insulina === "1"){
                                    $cantidadTotales = ceil((intval($numOrdenes[$i][$j]['Cantidadmensual'])/intval($codigo->contenido))*intval($numOrdenes[$i][$j]['NumMeses']));
                                    if (intval($cantidadTotales)%2==0){
                                        $Cantidadmensual = $numOrdenes[$i][$j]['Cantidadmensual'];
                                        $Cantidadmensualdisponible = round($numOrdenes[$i][$j]['Cantidadpormedico']);
                                        $Cantidadpormedico = round($numOrdenes[$i][$j]['Cantidadpormedico']);
                                    }else{
                                        if(count($numOrdenes) > 1){
                                            $Cantidadmensual = $numOrdenes[$i][$j]['Cantidadmensual'];
                                            $Cantidadmensualdisponible = round($numOrdenes[$i][$j]['Cantidadpormedico'])-1;
                                            $Cantidadpormedico = round($numOrdenes[$i][$j]['Cantidadpormedico'])-1;
                                        }else{
                                            $Cantidadmensual = $numOrdenes[$i][$j]['Cantidadmensual'];
                                            $Cantidadmensualdisponible = round($numOrdenes[$i][$j]['Cantidadpormedico']);
                                            $Cantidadpormedico = round($numOrdenes[$i][$j]['Cantidadpormedico']);
                                        }
                                    }

                                }else{
                                    $Cantidadmensual = $numOrdenes[$i][$j]['Cantidadmensual'];
                                    $Cantidadmensualdisponible = round($numOrdenes[$i][$j]['Cantidadpormedico']);
                                    $Cantidadpormedico = round($numOrdenes[$i][$j]['Cantidadpormedico']);
                                }
                                $detalleMedicamentosCreados = Detaarticulorden::where('Orden_id',$orden->id)->where('codesumi_id',$numOrdenes[$i][$j]['id'])->first();
                                if(!$detalleMedicamentosCreados){
                                    Detaarticulorden::create([
                                        'Orden_id' => $orden->id,
                                        'codesumi_id' => $numOrdenes[$i][$j]['id'],
                                        'Estado_id' => $estado,
                                        'Cantidadosis' => $numOrdenes[$i][$j]['Cantidadosis'],
                                        'Via' => $numOrdenes[$i][$j]['Via'],
                                        'Unidadmedida' => $numOrdenes[$i][$j]['Unidadmedida'],
                                        'Frecuencia' => $numOrdenes[$i][$j]['Frecuencia'],
                                        'Unidadtiempo' => $numOrdenes[$i][$j]['Unidadtiempo'],
                                        'Duracion' => $numOrdenes[$i][$j]['Duracion'],
                                        'Cantidadmensual' => $Cantidadmensual,
                                        'Cantidadmensualdisponible' => $Cantidadmensualdisponible,
                                        'Cantidadpormedico' => $Cantidadpormedico,
                                        'NumMeses' => $numOrdenes[$i][$j]['NumMeses'],
                                        'Observacion' => $numOrdenes[$i][$j]['Observacion'],
                                        'Fechaorden' => $date,
                                    ]);
                                }
                                //$this->guardarRips($orden->id,intval($citapaciente->Paciente_id),intval($citapaciente->sedeprestador_id));
                            }
//                        }
                    }
                        $cobroEntidades = new EntidadController();
                        $cobroEntidades->validarMedicamento($citapaciente->id);
                    }
                }
                break;
            case 2:
            case 4:
                $numOrdenes = [];
                $date       = date("Y-m-d h:i:s");
                $orden      = Orden::where('Tiporden_id', $request->Tiporden_id)->where('Cita_id', $citapaciente->id)->first();
                if (!isset($orden)) {
                    $orden = Orden::create([
                        'Tiporden_id' => $request->Tiporden_id,
                        'Cita_id'     => $citapaciente->id,
                        'Usuario_id'  => auth()->user()->id,
                        'Estado_id'   => 1,
                        'Fechaorden'  => $date,
                    ]);
                }
                foreach ($request->procedimientos as $procedimiento) {
//                if($procedimiento['date'] < "2023-10-31"){
                    $cup    = Cup::find($procedimiento['id']);
                    $estado = 1;
                    $paciente = Paciente::find($citapaciente->Paciente_id);
                    if ($cup->Requiere_auditoria == 'SI' || ($niveluser < $cup->Nivelordenamiento)) {
                        if(intval($paciente->entidad_id) !== 7){
                            $estado = 15;
                        }
                    }else{
                        if($procedimiento['date'] > "2024-04-30" && intval($paciente->entidad_id) === 1){
                            $estado = 58;
                        }
                    }
                    if($paciente->portabilidad == 1 && $paciente->Estado_Afiliado != 27 && $cup->nivelportabilidad != 1 ){
                        $estado = 37;
                    }
                    Cuporden::create([
                        'Orden_id'         => $orden->id,
                        'Cup_id'           => $procedimiento['id'],
                        'Cantidad'         => $procedimiento['cantidad'],
                        'Observacionorden' => $procedimiento['observacion'],
                        'Estado_id'        => $estado,
                        'fecha_orden'      => $procedimiento['date'],
                    ]);
//                }

                }

                if($paciente->entidad_id == 1 || $paciente->entidad_id == 3 || $paciente->entidad_id == 5){
                    $direcciones = DB::select("SET NOCOUNT ON ; exec dbo.cupsdireccionamiento" . "'$orden->id'");
                    foreach ($direcciones as $direccion) {
                        $cuporden = Cuporden::where('Cup_id', $direccion->tarifacup_id)->where('Orden_id', $orden->id)->first();

                        $cuporden->update([
                            'Ubicacion_id'     => $direccion->sedeproveedor_id,
                            'valor_tarifa'     => $direccion->valor_tarifa,
                            'valor_total'      => $direccion->valor_total,
                            'valor_transporte' => $direccion->valor_transporte,
                        ]);
                    }
                }else{
                    $direcciones = DB::select("exec dbo.CupsDireccionamientoEntidad " . intval($orden->id));
                    foreach ($direcciones as $direccion) {
                        $cuporden = Cuporden::where('Cup_id', intval($direccion->cup_id))->where('Orden_id', $orden->id)->first();
                        if(isset($cuporden)){
                            $cuporden->update([
                                'Ubicacion_id'     => $direccion->sedeproveedor_id,
                                'valor_tarifa'     => $direccion->valor_tarifa,
                            ]);
                        }
                    }
                }
                if(intval($request->Tiporden_id) == 4){
                    $cobroEntidades = new EntidadController();
                    $cobroEntidades->validarServicio($citapaciente->id);
                }elseif(intval($request->Tiporden_id) == 2){
                    $cobroEntidades = new EntidadController();
                    $cobroEntidades->validarInterConsulta($citapaciente->id);
                }
                break;
            case 6:
                // return response()->json($request->all(), 200);

                $numOrdenes = [];
                $date       = date("Y-m-d h:i:s");
                $orden      = Orden::where('Tiporden_id', $request->Tiporden_id)->where('Cita_id', $citapaciente->id)->first();

                if (!isset($orden)) {
                    $orden = Orden::create([
                        'Tiporden_id' => $request->Tiporden_id,
                        'Cita_id'     => $citapaciente->id,
                        'Usuario_id'  => auth()->user()->id,
                        'Estado_id'   => 1,
                        'Fechaorden'  => $date,
                    ]);
                }

                foreach ($request->serviciosPropios as $servicioPropio) {
                    $codePropio = Codepropio::find($servicioPropio['id']);
                    $estado     = 1;
                    if ($servicioPropio['auditoria'] == 'SI' || ($niveluser < $servicioPropio['nivelordenamiento'])) {
                        $estado = 15;
                    }
                    $ownCode = Codepropio::where('Codesumi_id', $servicioPropio['id'])
                        ->where('Estado_id', 1)
                        ->first();

                    if (!isset($ownCode)) {
                        continue;
                    }
                    $cupOrden = Cuporden::join('codepropios as cp', 'cupordens.Serviciopropio_id', 'cp.id')
                        ->where('cupordens.Orden_id', $orden->id)
                        ->where(function ($q) use ($servicioPropio, $ownCode) {
                            $q->where('cp.Codesumi_id', $servicioPropio['id'])
                                ->orWhere('cupordens.Serviciopropio_id', $ownCode->id);
                        })->first();

                    if (isset($cupOrden)) {
                        continue;
                    }

                    Cuporden::create([
                        'Orden_id'          => $orden->id,
                        'Serviciopropio_id' => $ownCode->id,
                        'Cantidad'          => $servicioPropio['cantidad'],
                        'Observacionorden'  => $servicioPropio['observacion'],
                        // 'Ubicacion_id' => $servicioPropio['sedeproveedor_id'],
                        // 'valor_tarifa' => $servicioPropio['valor'],
                        'Estado_id'         => $estado,
                    ]);
                }

                $direcciones = DB::select("exec dbo.CodpropioDireccionamiento" . "'$orden->id'");

                foreach ($direcciones as $direccion) {
                    $cuporden = Cuporden::join('codepropios as cp', 'cupordens.Serviciopropio_id', 'cp.id')
                        ->where('cupordens.Orden_id', $orden->id)
                        ->where(function ($q) use ($direccion) {
                            $q->where('cp.Codesumi_id', $direccion->codesumi_id)
                                ->orWhere('cupordens.Serviciopropio_id', $direccion->tarifacp_id);
                        })->first(['cupordens.id', 'cupordens.Ubicacion_id', 'cupordens.valor_tarifa', 'cupordens.valor_total', 'cupordens.valor_transporte', 'cupordens.Serviciopropio_id']);

                    $cuporden->update([
                        'Ubicacion_id'      => $direccion->sedeproveedor_id,
                        'valor_tarifa'      => $direccion->valor_tarifa,
                        'valor_total'       => $direccion->valor_total,
                        'valor_transporte'  => $direccion->valor_transporte,
                        'Serviciopropio_id' => $direccion->tarifacp_id,
                    ]);
                }
                break;
            case 7:
                $articulosSelected = $request->codeSumiEsquemaSelected;
                $ciclo             = $request->cycleFrom;
                foreach ($articulosSelected as $articulo) {
                    $numberOfdays = explode(',', $articulo['diasAplicacion']);

                    foreach ($numberOfdays as $dayNumber) {
                        $orden = Orden::where('Tiporden_id', $request->Tiporden_id)
                            ->where('Cita_id', $citapaciente->id)
                            ->where('scheme_name', $request->nombreEsquema)
                            ->where('page', $ciclo)
                            ->where('day', $dayNumber)
                            ->where('Estado_id', 1)
                            ->first();
                        if (!isset($orden)) {
                            $orden = Orden::create([
                                'Tiporden_id'          => $request->Tiporden_id,
                                'Cita_id'              => $citapaciente->id,
                                'Usuario_id'           => auth()->user()->id,
                                'paginacion'           => $ciclo . '/' . $request->ciclos,
                                'Estado_id'            => 1,
                                'scheme_name'          => $request->nombreEsquema,
                                'page'                 => $ciclo,
                                'day'                  => $dayNumber,
                                'total_pages'          => $request->ciclos,
                                'repetition_frequency' => $request->frecuenciaRepeat,
                                'biography'            => $request->biografia,
                            ]);
                        } else {
                            $orden->update([
                                'Usuario_id'           => auth()->user()->id,
                                'Estado_id'            => 1,
                                'paginacion'           => $ciclo . '/' . $request->ciclos,
                                'page'                 => $ciclo,
                                'day'                  => $dayNumber,
                                'totalPages'           => $request->ciclos,
                                'repetition_frequency' => $request->frecuenciaRepeat,
                                'biography'            => $request->biografia,
                            ]);
                        }

                        $estado               = 15;
                        $frecuencia           = 1;
                        $numeroMeses          = 1;
                        $articuloDetalleOrden = Detaarticulorden::where('Orden_id', $orden->id)
                            ->where('codesumi_id', $articulo['id'])
                            ->first();
                        if (isset($articuloDetalleOrden)) {
                            continue;
                        }

                        Detaarticulorden::create([
                            'Orden_id'                  => $orden->id,
                            'codesumi_id'               => $articulo['id'],
                            'Estado_id'                 => $estado,
                            'Cantidadosis'              => $articulo['dosis'],
                            'Via'                       => $articulo['via'],
                            'Unidadmedida'              => $articulo['unidadMedida'],
                            'Frecuencia'                => $frecuencia,
                            'Unidadtiempo'              => $articulo['frecuencia'],
                            'Cantidadmensual'           => round(intval($articulo['dosisFormulada'])),
                            'Cantidadmensualdisponible' => round(intval($articulo['dosisFormulada'])),
                            'Cantidadpormedico'         => round(intval($articulo['dosisFormulada'])),
                            'frecuenciaDuracion'        => $articulo['frecuenciaDuracion'],
                            'NumMeses'                  => $numeroMeses,
                            'Observacion'               => $articulo['observaciones'],
                        ]);
                    }
                }

                break;
            case 8:
                $date = date("Y-m-d");

                $orden = Orden::create([
                    'Tiporden_id' => $request->Tiporden_id,
                    'Cita_id' => $citapaciente->id,
                    'Usuario_id' => auth()->user()->id,
                    'paginacion' => '1',
                    'Estado_id' => 1,
                    'Fechaorden' => $date,
                ]);

                FormulaOptometria::create([
                    'Orden_id'          => $orden->id,
                    'esfera_od'         => $request->esfera_od,
                    'esfera_oi'         => $request->esfera_oi,
                    'cilindro_od'       => $request->cilindro_od,
                    'cilindro_oi'       => $request->cilindro_oi,
                    'eje_od'            => $request->eje_od,
                    'eje_oi'            => $request->eje_oi,
                    'adicion_od'        => $request->adicion_od,
                    'adicion_oi'        => $request->adicion_oi,
                    'prisma_od'         => $request->prisma_od,
                    'prisma_oi'         => $request->prisma_oi,
                    'grados_od'         => $request->grados_od,
                    'grados_oi'         => $request->grados_oi,
                    'lejos_od'          => $request->lejos_od,
                    'lejos_oi'          => $request->lejos_oi,
                    'cerca_od'          => $request->cerca_od,
                    'cerca_oi'          => $request->cerca_oi,
                    'tipo_lente'        => $request->tipo_lente,
                    'detalle'           => $request->detalle,
                    'altura'            => $request->altura,
                    'color '            => $request->color,
                    'dp'                => $request->dp,
                    'dispositivos'      => $request->dispositivos,
                    'control'           => $request->control,
                    'vigencia'          => $request->vigencia,
                    'observacion'       => $request->observacion,
                    'usuario_id'        => auth()->user()->id,
                    'Estado_id'         => 1,
                ]);
                break;
            case 9:
                    $date = date("Y-m-d");
                    $orden = Orden::create([
                        'Tiporden_id' => $request->Tiporden_id,
                        'Cita_id' => $citapaciente->id,
                        'Usuario_id' => auth()->user()->id,
                        'paginacion' => '1',
                        'Estado_id' => 1,
                        'Fechaorden' => $date,
                    ]);

                    RecomendacionesClinicas::create([
                        'Orden_id'          => $orden->id,
                        'recomedacion'       => $request->recomedacion,
                        'fecha_aislamiento' => $request->FechaFinal,
                        'usuario_id'        => auth()->user()->id,
                        'Estado_id'         => 1,
                    ]);
                    break;

            default:
                return response()->json([
                    'message' => 'Tipo de orden no existe',
                ], 404);
                break;
        }

        return response()->json([
            'orden_id' => $orden->id,
            'message'  => 'Orden generada',
        ], 201);
    }

    public function cancelEsquema(Orden $orden)
    {
        $ordens = Orden::where('Tiporden_id', 7)
            ->update([
                'Estado_id' => 6,
            ]);

        Detaarticulorden::where('Orden_id',$orden->id)
            ->update([
                'Estado_id' => 6,
            ]);

        return response()->json([
            'message' => 'Esquema Cancelado'
        ], 200);
    }

    public function getOrdeninCupOrden(Orden $orden)
    {
        $cuporden = Cuporden::where('Orden_id', $orden->id)->get();

        return response()->json($cuporden, 201);
    }

    public function getOrdeninDetaArticuloOrden(Orden $orden)
    {
        $detaarticuloorden = Detaarticulorden::where('Orden_id', $orden->id)->get();

        return response()->json($detaarticuloorden, 201);
    }

    public function getOrderById(citapaciente $citapaciente, Request $request)
    {
        $orden = Orden::where('Cita_id', $citapaciente->id)->where('Tiporden_id', $request->Tiporden_id)->first();

        return response()->json($orden, 201);
    }

    public function getOrderedCups(citapaciente $citapaciente, Request $request)
    {

        $ordereds = Orden::select(['co.id', 'co.Orden_id', 'co.Observacionorden as observacion', 'co.Estado_id', 'co.Cantidad as cantidad', 'co.Ubicacion_id', 'c.Codigo as codigo', 'co.created_at', 'c.Nombre as nombre', 'sp.Nombre as ubicacion', 'sp.Direccion as direccion', 'sp.Telefono1 as telefono', 'e.Nombre as estado', 'c.id as cup_id','co.anexo3','co.id as identificador'])
            ->join('cupordens as co', 'co.Orden_id', 'ordens.id')
            ->join('estados as e', 'co.Estado_id', 'e.id')
            ->join('cups as c', 'co.Cup_id', 'c.id')
            ->leftJoin('sedeproveedores as sp', 'co.Ubicacion_id', 'sp.id')
            ->where('ordens.Cita_id', $citapaciente->id)
            ->where('ordens.Tiporden_id', $request->Tiporden_id)
            ->get();

        return response()->json($ordereds, 200);
    }

    public function getOrderedServicioPropio(citapaciente $citapaciente, Request $request)
    {

        $ordereds = Orden::select([
            'co.id',
            'co.Orden_id',
            'co.Observacionorden as observacion',
            'co.Estado_id',
            'co.Cantidad as cantidad',
            'co.Ubicacion_id',
            'co.Serviciopropio_id',
            'cp.Codigo as codigo',
            'cp.Descripcion as nombre',
            'sp.Nombre as ubicacion',
            'sp.Direccion as direccion',
            'sp.Telefono1 as telefono',
            'co.created_at',
            'e.Nombre as estado'
        ])
            ->join('cupordens as co', 'co.Orden_id', 'ordens.id')
            ->join('estados as e', 'co.Estado_id', 'e.id')
            ->join('codepropios as cp', 'co.Serviciopropio_id', 'cp.id')
            ->leftJoin('sedeproveedores as sp', 'co.Ubicacion_id', 'sp.id')
            ->where('ordens.Cita_id', $citapaciente->id)
            ->where('ordens.Tiporden_id', $request->Tiporden_id)
            ->get();

        return response()->json($ordereds, 200);
    }

    public function getOrderedScheme(citapaciente $citapaciente)
    {

        $ordereds = Orden::select(['ordens.*'])
            ->with(['detaarticulordens' => function ($query) {
                $query->select('detaarticulordens.*', 'e.Nombre as estado', 'c.Nombre as nombre', 'c.Codigo')
                    ->join('estados as e', 'detaarticulordens.Estado_id', 'e.id')
                    ->join('codesumis as c', 'detaarticulordens.codesumi_id', 'c.id');
            }])
            ->where('ordens.Cita_id', $citapaciente->id)
            ->where('ordens.Tiporden_id', 7)
            ->whereIn('ordens.Estado_id', [1])
            ->get();

        if (count($ordereds) == 0) {
            $paciente = Paciente::select(['pacientes.id'])
                ->join('cita_paciente as cp', 'cp.Paciente_id', 'pacientes.id')
                ->where('cp.id', $citapaciente->id)
                ->first();

            $lastOrdersOncologica = Orden::select(['ordens.*'])
                ->join('cita_paciente as cp', 'ordens.Cita_id', 'cp.id')
                ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
                ->where('p.id', $paciente->id)
                ->where('ordens.Tiporden_id', 7)
                ->orderBy('ordens.id', 'Desc')
                ->first();
            if (intval($lastOrdersOncologica->page) < intval($lastOrdersOncologica->total_pages) && ($lastOrdersOncologica->Estado_id != 6 && $lastOrdersOncologica->Estado_id != 24 && $lastOrdersOncologica->Estado_id != 25 && $lastOrdersOncologica->Estado_id != 26)) {
                $ordereds = Orden::select(['ordens.*'])
                    ->with(['detaarticulordens' => function ($query) {
                        $query->select('detaarticulordens.*', 'e.Nombre as estado', 'c.Nombre as nombre', 'c.Codigo')
                            ->join('estados as e', 'detaarticulordens.Estado_id', 'e.id')
                            ->join('codesumis as c', 'detaarticulordens.codesumi_id', 'c.id');
                    }])
                    ->where('ordens.Cita_id', $lastOrdersOncologica->Cita_id)
                    ->where('ordens.scheme_name', $lastOrdersOncologica->scheme_name)
                    ->where('ordens.Tiporden_id', 7)
                    ->get();

                $finished = true;
                foreach ($ordereds as $order) {
                    if ($order->Estado_id != 17) {
                        $finished =  false;
                        break;
                    }
                }
                if ($finished) {
                    foreach ($ordereds as $order) {
                        $orden = Orden::create([
                            'Tiporden_id'          => $order->Tiporden_id,
                            'Cita_id'              => $citapaciente->id,
                            'Usuario_id'           => auth()->user()->id,
                            'paginacion'           => intval($order->page) + 1 . '/' . $order->total_pages,
                            'Estado_id'            => 1,
                            'scheme_name'          => $order->scheme_name,
                            'page'                 => intval($order->page) + 1,
                            'day'                  => $order->day,
                            'total_pages'          => $order->total_pages,
                            'repetition_frequency' => $order->repetition_frequency,
                            'biography'            => $order->biography,
                        ]);
                        foreach ($order['detaarticulordens'] as $detailItem) {

                            Detaarticulorden::create([
                                'Orden_id'                  => $orden->id,
                                'codesumi_id'               => $detailItem['codesumi_id'],
                                'Estado_id'                 => 7,
                                'Cantidadosis'              => $detailItem['Cantidadosis'],
                                'Via'                       => $detailItem['Via'],
                                'Unidadmedida'              => $detailItem['Unidadmedida'],
                                'Frecuencia'                => $detailItem['Frecuencia'],
                                'Unidadtiempo'              => $detailItem['Unidadtiempo'],
                                'Cantidadmensual'           => $detailItem['Cantidadmensual'],
                                'Cantidadmensualdisponible' => $detailItem['Cantidadmensual'],
                                'Cantidadpormedico'         => $detailItem['Cantidadpormedico'],
                                'frecuenciaDuracion'        => $detailItem['frecuenciaDuracion'],
                                'NumMeses'                  => $detailItem['NumMeses'],
                                'Observacion'               => $detailItem['Observacion'],
                            ]);
                        }
                    }

                    $ordereds = Orden::select(['ordens.*'])
                        ->with(['detaarticulordens' => function ($query) {
                            $query->select('detaarticulordens.*', 'e.Nombre as estado', 'c.Nombre as nombre', 'c.Codigo')
                                ->join('estados as e', 'detaarticulordens.Estado_id', 'e.id')
                                ->join('codesumis as c', 'detaarticulordens.codesumi_id', 'c.id');
                        }])
                        ->where('ordens.Cita_id', $citapaciente->id)
                        ->where('ordens.Tiporden_id', 7)
                        ->whereIn('ordens.Estado_id', [1])
                        ->get();
                }
            }
        }

        return response()->json($ordereds, 200);
    }

    public function getOrderedCodesumi($citapaciente)
    {

        $ordereds = Orden::select(['ordens.*'])
            ->join('detaarticulordens as da', 'da.Orden_id', 'ordens.id')
            ->with(['detaarticulordens' => function ($query) {
                $query->select('detaarticulordens.*', 'e.Nombre as estado', 'c.Nombre as nombre', 'c.Codigo')
                    ->join('estados as e', 'detaarticulordens.Estado_id', 'e.id')
                    ->join('codesumis as c', 'detaarticulordens.codesumi_id', 'c.id');
            }])
            ->where('Cita_id', $citapaciente)
            ->where('Tiporden_id', 3)
            ->distinct()
            ->get();

        return response()->json($ordereds, 200);
    }

    public function getOrderedCie(citapaciente $citapaciente)
    {
        $incapacidades = Incapacidade::select(['incapacidades.*'])
            ->join('ordens as o', 'incapacidades.Orden_id', 'o.id')
            ->where('o.Cita_id', $citapaciente->id)
            ->get();

        return response()->json($incapacidades, 200);
    }

    public function getDetaArticuloOrden(Request $request)
    {
        $detaarticuloorden = Detaarticulorden::select(['detaarticulordens.*', 'c.*'])
            ->join('Codesumis as c', 'detaarticulordens.Codesumi_id', 'c.id')
            ->join('ordens as o', 'detaarticulordens.Orden_id', 'o.id')
            ->join('cita_paciente as cp', 'o.Cita_id', 'cp.id')
            ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
            ->where('detaarticulordens.Codesumi_id', $request->Codesumi_id)
            ->where('c.Frecuencia', $request->Frecuencia)
            ->where('p.Num_Doc', $request->Num_Doc)
            ->whereIn('detaarticulordens.Estado_id', [1, 7, 15])
            ->whereBetween('detaarticulordens.Fechaorden', [$request->Ago, $request->Next])
            ->get();

        return response()->json($detaarticuloorden, 200);
    }

    public function getCupOrden(Request $request)
    {
        $cuporden = Cuporden::select(['cupordens.*', 'c.*', 'cupordens.id as cuporde_id', 'c.id as cup_id', 'cupordens.created_at as created_at', 'c.created_at as cup_created'])
            ->join('cups as c', 'cupordens.Cup_id', 'c.id')
            ->join('ordens as o', 'cupordens.Orden_id', 'o.id')
            ->join('cita_paciente as cp', 'o.Cita_id', 'cp.id')
            ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
            ->where('cupordens.Cup_id', $request->Cup_id)
            ->where('c.Peridiocidad', $request->Peridiocidad)
            ->where('p.Num_Doc', $request->Num_Doc)
            ->whereIn('cupordens.Estado_id', [1, 7, 15, 37])
            ->whereBetween('cupordens.fecha_orden', [$request->Ago, $request->Next])
            ->get();

        if(count($cuporden) > 0){
            $flag = false;
            foreach ($cuporden as $cup) {
                $disponibleDesde = date('Y-m-d', strtotime($cup->fecha_orden. ' - '.$cup->Peridiocidad.' days'));
                $disponibleHasta = date('Y-m-d', strtotime($cup->fecha_orden. ' + '.$cup->Peridiocidad.' days'));
                if($request->fechaOrden >= $disponibleDesde && $request->fechaOrden <= $disponibleHasta){
                    $flag = true;
                }
            }
            if(!$flag){
                $cuporden = [];
            }
        }


        return response()->json($cuporden, 200);
    }

    public function getServicioPropio(Request $request)
    {
        $cuporden = Cuporden::select([
            'cupordens.*',
            'c.*',
            'cupordens.id as cuporde_id',
            'c.id as cup_id',
            'cupordens.created_at as created_at',
            'c.created_at as cup_created'
        ])
            ->join('cups as c', 'cupordens.Cup_id', 'c.id')
            ->join('ordens as o', 'cupordens.Orden_id', 'o.id')
            ->join('cita_paciente as cp', 'o.Cita_id', 'cp.id')
            ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
            ->where('cupordens.Serviciopropio_id', $request->Serviciopropio_id)
            ->where('c.Peridiocidad', $request->Peridiocidad)
            ->where('p.Num_Doc', $request->Num_Doc)
            ->whereIn('cupordens.Estado_id', [1, 7, 15])
            ->whereBetween('cupordens.created_at', [$request->Ago, $request->Next])
            ->get();

        return response()->json($cuporden, 200);
    }

    private function getRange($request)
    {
        $incapacidades = Incapacidade::select(['incapacidades.*'])
            ->join('ordens as o', 'incapacidades.Orden_id', 'o.id')
            ->join('cita_paciente as c', 'o.Cita_id', 'c.id')
            ->join('pacientes as p', 'c.Paciente_id', 'p.id')
            ->where('p.Num_Doc', $request->Cedula)
            ->where('incapacidades.Estado_id', 1)
            ->where(function ($query) use ($request) {
                $query->whereBetween('incapacidades.Fechainicio', [$request->Fechainicio, $request->Fechafinal])
                    ->orWhereBetween('incapacidades.Fechafinal', [$request->Fechainicio, $request->Fechafinal]);
            })
            ->first();

        return $incapacidades;
    }

    public function historicoDispensado(Request $request)
    {
        $ordenes = Bodegatransacion::select(['o.id', 'd.Producto', 'bodegatransacions.CantidadtotalFactura', 'bodegatransacions.created_at', 'b.Nombre as bodega'])
            ->join('movimientos as m', 'bodegatransacions.Movimiento_id', 'm.id')
            ->join('ordens as o', 'm.Orden_id', 'o.id')
            ->join('cita_paciente as cp', 'o.Cita_id', 'cp.id')
            ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
            ->join('tipordens as to', 'to.id', 'o.Tiporden_id')
            ->join('bodegarticulos as ba', 'ba.id', 'bodegatransacions.Bodegarticulo_id')
            ->join('detallearticulos as d', 'ba.Detallearticulo_id', 'd.id')
            ->join('bodegas as b', 'ba.Bodega_id', 'b.id')
            ->where('p.Num_Doc', $request->Num_Doc)
            ->whereBetween('m.created_at', [$request->inicialdate, $request->finishdate . ' 23:59:59.000'])
            ->get();

        return response()->json($ordenes, 201);
    }

    public function ordenesProximas(Request $request)
    {
        $ordenes = Orden::select(['ordens.id', 'ordens.Cita_id',
            'ordens.Fechaorden',
            DB::raw("CAST(ordens.Fechaorden AS date) as Fechaorden"),
            'ordens.paginacion',
            'ordens.Estado_id',
            'to.Nombre as tipo_orden'])
            ->join('cita_paciente as cp', 'ordens.Cita_id', 'cp.id')
            ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
            ->join('tipordens as to', 'to.id', 'ordens.Tiporden_id')
            ->join('detaarticulordens as da', 'ordens.id', 'da.Orden_id')
            ->whereIn('da.Estado_id', [1, 7, 18, 19])
            ->where('ordens.Fechaorden', '>', date('Y-m-d'))
            ->where('p.Num_Doc', $request->Num_Doc)
            ->groupBy('ordens.id', 'ordens.Cita_id', 'ordens.Fechaorden', 'ordens.paginacion', 'ordens.Estado_id', 'to.Nombre')
            ->get();

        return response()->json($ordenes, 201);
    }

    public function historicoPendiente(Request $request)
    {
        if($request->tipo == 'pendiente'){
            $ordenes = Orden::select(['ordens.id', 'ordens.Cita_id', 'ordens.Fechaorden', 'ordens.paginacion', 'ordens.Estado_id',
                'to.Nombre as tipo_orden', DB::raw('COUNT(da.id) as cant')])
                ->join('cita_paciente as cp', 'ordens.Cita_id', 'cp.id')
                ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
                ->join('tipordens as to', 'to.id', 'ordens.Tiporden_id')
                ->join('detaarticulordens as da', 'ordens.id', 'da.Orden_id')
                ->where('p.Num_Doc', $request->Num_Doc)
                ->whereIn('p.entidad_id', [1,3])
                ->whereIn('da.Estado_id', [18, 19])
                ->whereNotIn('ordens.Tiporden_id', [7])
                ->whereBetween('ordens.Fechaorden', [date('Y-m-d', strtotime(date("d-m-Y") . "- 2 month")), date('Y-m-d 23:59:59.00')])
                ->groupBy('ordens.id', 'ordens.Cita_id', 'ordens.Fechaorden', 'ordens.paginacion', 'ordens.Estado_id', 'to.Nombre')
                ->havingRaw('COUNT(da.id) > 0')
                ->get();
        }else{
            $ordenes = Orden::select(['ordens.id', 'ordens.Cita_id', 'ordens.Fechaorden', 'ordens.paginacion', 'ordens.Estado_id', 'to.Nombre as tipo_orden', DB::raw('COUNT(da.id) as cant'),'c.valor as valorOrden'])
                ->leftjoin('cobros as c','c.orden_id','ordens.id')
                ->join('cita_paciente as cp', 'ordens.Cita_id', 'cp.id')
                ->join('pacientes as p', 'cp.Paciente_id', 'p.id')
                ->join('tipordens as to', 'to.id', 'ordens.Tiporden_id')
                ->join('detaarticulordens as da', 'ordens.id', 'da.Orden_id')
                ->whereBetween('ordens.Fechaorden', [date('Y-m-d', strtotime(date("d-m-Y") . "- 1 month")), date('Y-m-d 23:59:59.00')])
                ->where('p.Num_Doc', $request->Num_Doc)
                ->whereIn('p.entidad_id', [1,3])
                ->whereIn('da.Estado_id', [1, 7])
                ->whereNotIn('ordens.Tiporden_id', [7])
                ->groupBy('ordens.id', 'ordens.Cita_id', 'ordens.Fechaorden', 'ordens.paginacion', 'ordens.Estado_id', 'to.Nombre','c.valor')
                ->havingRaw('COUNT(da.id) > 0')
                ->get();

        }

        return response()->json($ordenes, 201);
    }

    public function getArticulosOrden(Request $request)
    {
        if($request->msg === 'pendiente'){
            $articulos = Detaarticulorden::select('detaarticulordens.*', 'cs.Codigo as Codigo', 'cs.color',
                'cs.Nombre as medicamento', 'cs.unidadMedida as unidadMedida', 'cs.concentracion as concentracion')
                ->join('codesumis as cs', 'detaarticulordens.Codesumi_id', 'cs.id')
                ->where('detaarticulordens.Orden_id', $request->Orden_id)
                ->whereIn('detaarticulordens.Estado_id', [18,19])
                ->get();
        }else{
            $articulos = Detaarticulorden::select(
                    'detaarticulordens.*','cs.Codigo as Codigo', 'cs.Nombre as medicamento', 'cs.color',
                    'cs.unidadMedida as unidadMedida', 'cs.concentracion as concentracion','dc.id as cobro')
                ->join('codesumis as cs', 'detaarticulordens.Codesumi_id', 'cs.id')
                ->leftjoin('detalle_cobros as dc','dc.medicamento_orden_id','detaarticulordens.id')
                ->where('detaarticulordens.Orden_id', $request->Orden_id)
                ->whereIn('detaarticulordens.Estado_id', [1, 7, 35])
                ->get();
        }

        return response()->json($articulos, 201);
    }

    public function historicoPendienteOncologico(Request $request)
    {
        $ordenes = Orden::getRepository()->ordersPendingTypeOncology($request);
        return response()->json($ordenes->get(), 201);
    }

    public function getOrdersOncologyDispensed()
    {
        $ordenes = Orden::getRepository()->ordersOncologyDispensed();
        return response()->json($ordenes->get(), 201);
    }


    public function setStateRevisionPharmacy(Request $request)
    {
        $ordens = Orden::select(['ordens.id'])
            ->join('detaarticulordens as da', 'da.Orden_id', 'ordens.id')
            ->where('Cita_id', $request->citaPaciente)
            ->where('da.Estado_id', 7)
            ->distinct()
            ->get();

        foreach ($ordens as $orden) {
            $orden->update(['Estado_id' => 35]);
            $ordenDetalle = Detaarticulorden::getRepository()->setStateRevisionPharmacy($orden->id, $request->observacion);
        }
        return response()->json(['message'  => 'Orden Actualizada'], 201);
    }

    public function updateOrdens(Request $request, Orden $orden)
    {
        Orden::getRepository()->updateOrdens($request, $orden);
        return response()->json([
            'message' => 'Orden Actualizada!'
        ], 200);
    }

    public function updateOrderInPending(Request $request, Orden $orden){
        $orden->update(['Estado_id'=>$request->Estado_id,'fecha_pendiente'=> date("Y-m-d H:i:s"),'usuario_pendiente' => auth()->user()->id]);
        $detalles = Detaarticulorden::where('Orden_id',$orden->id)->whereIn('Estado_id',[1,7])->get();
        foreach ($detalles as $detalle){
            $detalle->update(['Estado_id'=>19,'fecha_pendiente' => date("Y-m-d H:i:s")]);
        }
        return response()->json([
            'message' => 'Orden Actualizada!'
        ], 200);
    }

    public function updadateOrdenByCita(Request $request, $cita)
    {
        Orden::where('Cita_id', $cita)
            ->update($request->all());
        return response()->json([
            'message' => 'Orden Actualizada!'
        ], 200);
    }

    public function getOrdersOncologyForNursing()
    {
        $ordens = citapaciente::select([
            'cita_paciente.id', 'o.paginacion', 'to.Nombre as tipo_orden',
            'p.Num_Doc',
            DB::raw("CONCAT(p.Segundo_Ape,' ',p.Primer_Ape,' ',p.Primer_Nom,' ',p.SegundoNom) as paciente"),
            'o.scheme_name'
        ])
            ->join('ordens as o', 'o.Cita_id', 'cita_paciente.id')
            ->join('pacientes as p', 'cita_paciente.Paciente_id', 'p.id')
            ->join('tipordens as to', 'to.id', 'o.Tiporden_id')
            ->join('detaarticulordens as da', 'da.Orden_id', 'o.id')
            ->with(['ordens' => function ($query) {
                $query->select([
                    'ordens.id',
                    'ordens.Cita_id',
                    'ordens.paginacion',
                    'ordens.day',
                    'ordens.Estado_id',
                    'e.Nombre as estado',
                    'ordens.FechaAgendamiento',
                    'ordens.estadoEnfermeria'
                ])
                    ->join('estados as e', 'e.id', 'ordens.Estado_id')
                    ->join('detaarticulordens as da', 'da.Orden_id', 'ordens.id')
                    ->where(function($q){
                        $q->whereIn('ordens.Estado_id', [35,18])
                            ->orWhereNull('ordens.estadoEnfermeria');
                    })
                    ->distinct()
                    ->get();
            }, 'ordens.detaarticulordens' => function ($query) {
                $query->select([
                    'detaarticulordens.id as id',
                    'detaarticulordens.created_at as FechaOrdenamiento',
                    'detaarticulordens.Cantidadosis as Cantidad_Dosis',
                    'detaarticulordens.codesumi_id as Codesumi_id',
                    'detaarticulordens.Via as Via',
                    'detaarticulordens.Unidadmedida as Unidad_Medida',
                    'detaarticulordens.Frecuencia as Frecuencia',
                    'detaarticulordens.Unidadtiempo as Unidad_Tiempo',
                    'detaarticulordens.Duracion as Duracion',
                    'detaarticulordens.Cantidadmensual as Cantidad_Mensual',
                    'detaarticulordens.Nummeses as Num_Meses',
                    'detaarticulordens.Observacion as Observacion',
                    'detaarticulordens.Cantidadpormedico as Cantidad_Medico',
                    'detaarticulordens.Orden_id',
                    'detaarticulordens.Fechaorden as Fecha_Orden',
                    'detaarticulordens.Estado_id as Estado_id',
                    'detaarticulordens.notaFarmacia',
                    'au.Nota as nota_autorizacion',
                    'c.Nombre as Nombre',
                    'c.Codigo as Codigo',
                    'c.Requiere_autorizacion as Requiere_Autorizacion',
                    'au.created_at as fechaAutorizacion'
                ])
                    ->join('codesumis as c', 'detaarticulordens.codesumi_id', 'c.id')
                    ->leftjoin('autorizacions as au', 'au.Articulorden_id', 'detaarticulordens.id')
                    ->get();
            }])
            ->with(['notaenfermeria'])
            ->where('o.Tiporden_id', 7)
            ->whereIn('da.Estado_id', [35,18,17])
            ->whereNull('o.estadoEnfermeria')
            ->groupBy('cita_paciente.id', 'o.paginacion', 'to.Nombre', 'p.Num_Doc', 'p.Segundo_Ape', 'p.Primer_Ape', 'p.Primer_Nom', 'p.SegundoNom', 'o.scheme_name')
            ->get();

        return response()->json($ordens, 201);
    }

    public function historyPendingOncology()
    {
        $ordenes = citapaciente::select([
            'cita_paciente.id', 'cita_paciente.Tipocita_id', 'cita_paciente.created_at', 'o.paginacion', 'o.Estado_id', 'to.Nombre as tipo_orden', DB::raw('COUNT(da.id) as cant'),
            'p.Num_Doc',
            DB::raw("CONCAT(p.Segundo_Ape,' ',p.Primer_Ape,' ',p.Primer_Nom,' ',p.SegundoNom) as paciente"),
            'o.scheme_name'
        ])
            ->join('ordens as o', 'o.Cita_id', 'cita_paciente.id')
            ->join('pacientes as p', 'cita_paciente.Paciente_id', 'p.id')
            ->join('tipordens as to', 'to.id', 'o.Tiporden_id')
            ->join('detaarticulordens as da', 'o.id', 'da.Orden_id')
            ->with(['ordens' => function ($query) {
                $query->select(['ordens.*'])
                    ->join('detaarticulordens as da', 'da.Orden_id', 'ordens.id')
                    ->where('da.Estado_id', 7)
                    ->distinct()
                    ->get();
            }, 'ordens.detaarticulordens' => function ($query) {
                $query->select([
                    'detaarticulordens.id as id',
                    'detaarticulordens.created_at as FechaOrdenamiento',
                    'detaarticulordens.Cantidadosis as Cantidad_Dosis',
                    'detaarticulordens.codesumi_id as Codesumi_id',
                    'detaarticulordens.Via as Via',
                    'detaarticulordens.Unidadmedida as Unidad_Medida',
                    'detaarticulordens.Frecuencia as Frecuencia',
                    'detaarticulordens.Unidadtiempo as Unidad_Tiempo',
                    'detaarticulordens.Duracion as Duracion',
                    'detaarticulordens.Cantidadmensual as Cantidad_Mensual',
                    'detaarticulordens.Nummeses as Num_Meses',
                    'detaarticulordens.Observacion as Observacion',
                    'detaarticulordens.Cantidadpormedico as Cantidad_Medico',
                    'detaarticulordens.Orden_id',
                    'detaarticulordens.Fechaorden as Fecha_Orden',
                    'detaarticulordens.Estado_id as Estado_id',
                    'detaarticulordens.notaFarmacia',
                    'c.Nombre as Nombre',
                    'c.Codigo as Codigo',
                    'c.Requiere_autorizacion as Requiere_Autorizacion',
                    'au.created_at as fechaAutorizacion'
                ])
                    ->join('codesumis as c', 'detaarticulordens.codesumi_id', 'c.id')
                    ->leftjoin('autorizacions as au', 'au.Articulorden_id', 'detaarticulordens.id')
                    ->get();
            }])
            ->where('o.Tiporden_id', 7)
            ->whereIn('da.Estado_id', [7])
            ->whereIn('o.Estado_id', [7])
            ->groupBy('cita_paciente.id', 'cita_paciente.Tipocita_id', 'cita_paciente.created_at', 'o.paginacion', 'o.Estado_id', 'to.Nombre', 'p.Num_Doc', 'p.Segundo_Ape', 'p.Primer_Ape', 'p.Primer_Nom', 'p.SegundoNom', 'o.scheme_name')
            ->havingRaw('COUNT(da.id) > 0')
            ->get();

        return response()->json($ordenes, 201);
    }

    public function getOrdersPending(Request $request)
    {

        $ordenes = Orden::getRepository()->ordersPendingTypeOncology($request);
        return response()->json($ordenes->get(), 201);
    }

    public function assignPending($codigoOrden)
    {
        $detalleArticulo = Detaarticulorden::where('Orden_id', $codigoOrden)->update(['Estado_id' => 18]);
        return response()->json("Orden Actualizada!", 201);
    }

    public function historyOncology($numeroIdentificacion){
        $ordens = citapaciente::select([
            'cita_paciente.id', 'o.paginacion', 'to.Nombre as tipo_orden',
            'p.Num_Doc',
            DB::raw("CONCAT(p.Segundo_Ape,' ',p.Primer_Ape,' ',p.Primer_Nom,' ',p.SegundoNom) as paciente"),
            'o.scheme_name'
        ])
            ->join('ordens as o', 'o.Cita_id', 'cita_paciente.id')
            ->join('pacientes as p', 'cita_paciente.Paciente_id', 'p.id')
            ->join('tipordens as to', 'to.id', 'o.Tiporden_id')
            ->with(['ordens' => function ($query) {
                $query->select([
                    'ordens.id',
                    'ordens.Cita_id',
                    'ordens.paginacion',
                    'ordens.day',
                    'ordens.Estado_id',
                    'e.Nombre as estado',
                    'ordens.FechaAgendamiento',
                    'ordens.estadoEnfermeria'
                ])
                    ->join('estados as e', 'e.id', 'ordens.Estado_id')
                    ->join('detaarticulordens as da', 'da.Orden_id', 'ordens.id')
                    ->whereNotNull('ordens.estadoEnfermeria')
                    ->distinct()
                    ->get();
            }, 'ordens.detaarticulordens' => function ($query) {
                $query->select([
                    'detaarticulordens.id as id',
                    'detaarticulordens.created_at as FechaOrdenamiento',
                    'detaarticulordens.Cantidadosis as Cantidad_Dosis',
                    'detaarticulordens.codesumi_id as Codesumi_id',
                    'detaarticulordens.Via as Via',
                    'detaarticulordens.Unidadmedida as Unidad_Medida',
                    'detaarticulordens.Frecuencia as Frecuencia',
                    'detaarticulordens.Unidadtiempo as Unidad_Tiempo',
                    'detaarticulordens.Duracion as Duracion',
                    'detaarticulordens.Cantidadmensual as Cantidad_Mensual',
                    'detaarticulordens.Nummeses as Num_Meses',
                    'detaarticulordens.Observacion as Observacion',
                    'detaarticulordens.Cantidadpormedico as Cantidad_Medico',
                    'detaarticulordens.Orden_id',
                    'detaarticulordens.Fechaorden as Fecha_Orden',
                    'detaarticulordens.Estado_id as Estado_id',
                    'detaarticulordens.notaFarmacia',
                    'au.Nota as nota_autorizacion',
                    'c.Nombre as Nombre',
                    'c.Codigo as Codigo',
                    'c.Requiere_autorizacion as Requiere_Autorizacion',
                    'au.created_at as fechaAutorizacion'
                ])
                    ->join('codesumis as c', 'detaarticulordens.codesumi_id', 'c.id')
                    ->leftjoin('autorizacions as au', 'au.Articulorden_id', 'detaarticulordens.id')
                    ->get();
            }])
            ->with(['notaenfermeria']);
        if($numeroIdentificacion){
            $ordens->where('p.Num_Doc',$numeroIdentificacion);
        }
        $ordens->where('o.Tiporden_id', 7)
            ->whereNotNull('o.estadoEnfermeria')
            ->groupBy('cita_paciente.id', 'o.paginacion', 'to.Nombre', 'p.Num_Doc', 'p.Segundo_Ape', 'p.Primer_Ape', 'p.Primer_Nom', 'p.SegundoNom', 'o.scheme_name');


        return response()->json($ordens->get(), 201);
    }

    public function agregarFirmaPaciente(Request $request,$codigoOrden){
        $datos = $this->ordenPaciente($codigoOrden);
        $name = $codigoOrden.'_'.date('Y-m-d').'.png';
        $path   = '../storage/app/public/ordenes/'.$datos->getOriginalContent()->Num_Doc.'/';
        if (!file_exists($path)) {
            mkdir("../storage/app/public/ordenes/" . $datos->getOriginalContent()->Num_Doc, 0777);
        }
        $img = substr($request->file, strpos($request->file, ",")+1);
        $data = base64_decode($img);
        $success = file_put_contents($path.$name, $data);
        if($success){
            $orden = Orden::find($datos->getOriginalContent()->id);
            if($orden->Estado_id == 17){
                $orden->update(['firma_orden' => $name]);
                Detaarticulorden::where('Orden_id',$orden->id)
                    ->update(['firma_orden'=>$name]);
            }elseif($orden->Estado == 18){
                Detaarticulorden::where('Orden_id',$orden->id)
                    ->whereIN('Estado_id',[19,18])
                    ->update(['firma_orden'=>$name]);
            }
        }
        return response()->json('Firma guardada con exito');

    }

    public  function ordenPaciente($codigoOrden){
        $orden = Orden::select(['ordens.Fechaorden','ordens.id',DB::raw("CONCAT(p.Segundo_Ape,' ',p.Primer_Ape,' ',p.Primer_Nom,' ',p.SegundoNom) as paciente"),'p.Num_Doc'])
            ->join('cita_paciente as cp','ordens.Cita_id','cp.id')
            ->join('pacientes as p','p.id','cp.Paciente_id')
            ->where('ordens.id',$codigoOrden)
            ->whereIN('ordens.Estado_id',[18,17])
            ->whereNull('ordens.firma_orden')
            ->first();
        return response()->json($orden);
    }

    public function getAllArticulosOrden($codigoOrden)
    {
        $articulos = Detaarticulorden::select('detaarticulordens.*', 'cs.Codigo as Codigo', 'cs.Nombre as medicamento', 'cs.unidadMedida as unidadMedida', 'cs.concentracion as concentracion','e.Nombre as Estado','p.Num_Doc as cedula','o.Estado_id as estadoOrden','cs.Requiere_autorizacion as Requiere_Autorizacion','au.Nota as nota_autorizacion')
            ->join('codesumis as cs', 'detaarticulordens.Codesumi_id', 'cs.id')
            ->join('ordens as o','detaarticulordens.Orden_id','o.id')
            ->join('cita_paciente as cp','o.Cita_id','cp.id')
            ->join('pacientes as p','cp.Paciente_id','p.id')
            ->join('estados as e','detaarticulordens.Estado_id','e.id')
            ->leftjoin('autorizacions as au', 'au.Articulorden_id', 'detaarticulordens.id')
            ->where('detaarticulordens.Orden_id', $codigoOrden)
            ->whereIn('detaarticulordens.Estado_id', [7,1,17])
            ->get();

        return response()->json($articulos);
    }

    public function getAllServiceOrden($codigoOrden){
        $servicios = Cuporden::select('cupordens.*','c.Codigo', 'c.Nombre','e.Nombre as Estado','p.Num_Doc as cedula','sp.Nombre as ubicacion','sp.Direccion as direccion', 'sp.Telefono1 as telefono')
            ->join('cups as c', 'cupordens.Cup_id' , 'c.id')
            ->join('ordens as o' , 'cupordens.Orden_id' , 'o.id')
            ->join('cita_paciente as cp','o.Cita_id','cp.id')
            ->join('pacientes as p','cp.Paciente_id','p.id')
            ->join('estados as e','cupordens.Estado_id','e.id')
            ->leftjoin('autorizacions as a' , 'cupordens.id' , 'a.Cuporden_id')
            ->leftJoin('sedeproveedores as sp', 'cupordens.Ubicacion_id', 'sp.id')
            ->where('cupordens.Orden_id', $codigoOrden)
            ->whereIn('cupordens.Estado_id', [1,7])
            ->get();


        return response()->json($servicios);
    }

    public  function updateCupOrden(Cuporden $cupOrden,Request $request){
        $cupOrden->update($request->all());
        return response()->json([
            'message' => 'Servicios Actualizada!'
        ], 200);
    }

    public function resoluciones(Request $request){
        $result = [];
        switch ($request->resolucion){
            case 256:
                $result = Cuporden::select([
                    'cupordens.id AS Consecutivo del Registro',
                    'cupordens.created_at AS Registre la Fecha de Generacion de la Orden Medica',
                    'cupordens.created_at AS Registre Fecha de Radicacion de la Orden Medica',
                    'a.id AS Numero de Autorizacion del Servicio, Procedimiento o Medicamento',
                    'p.Codigo_prestador as Codigo Prestador',
                    'pa.Tipo_Doc AS Tipo de Identificación del Usuario',
                    'pa.Num_Doc AS Número de Identificación del Ususario',
                    'pa.Primer_Ape AS Primer Apellido',
                    'pa.Segundo_Ape AS Segundo Apellido',
                    'pa.Primer_Nom AS Primer Nombre',
                    'pa.SegundoNom AS Segundo Nombre',
                    'c.Ambito AS Ubicación del Paciente ',
                    DB::raw("(select TOP 1 cie10s.Codigo_CIE10 from cie10pacientes LEFT JOIN cie10s ON cie10pacientes.Cie10_id = cie10s.id where cie10pacientes.Citapaciente_id = cp.id and cie10pacientes.Esprimario = 1 ) as [Diagnostico Principal Código]"),
                    DB::raw("(select TOP 1 cie10s.Codigo_CIE10 from cie10pacientes LEFT JOIN cie10s ON cie10pacientes.Cie10_id = cie10s.id where cie10pacientes.Citapaciente_id = cp.id and cie10pacientes.Esprimario != 1 ) as [Diagnostico Secundario Código]"),
                    'c.Codigo AS Codigo Procedimiento',
                    'cupordens.Cantidad AS Cantidad',
                    'o.id AS Solicitud Origen',
                    DB::raw("FORMAT (o.created_at,'yyyy-MM-dd') AS [Fecha Solicitud de Origen]"),
                    DB::raw("FORMAT (o.created_at,'hh:mm:ss') AS [Hora Solicitud de Origen]"),
                    'cupordens.fecha_sugerida AS Fecha de Realización del Servicio'
                ])
                    ->join('ordens as o','cupordens.Orden_id','o.id')
                    ->join('autorizacions as a','a.Cuporden_id','cupordens.id')
                    ->join('sedeproveedores as sd','cupordens.Ubicacion_id','sd.id')
                    ->join('prestadores as p','sd.Prestador_id','p.id')
                    ->join('cita_paciente as cp','o.Cita_id','cp.id')
                    ->join('pacientes as pa','cp.Paciente_id','pa.id')
                    ->join('cups as c','cupordens.Cup_id','c.id')
                    ->where('cupordens.estado_id',7)
                    ->where('pa.entidad_id',$request->entidad)
                    ->whereBetween('o.Fechaorden',array($request->fechaDesde.' 00:00:00.000',$request->fechaHasta.' 23:59:00.000'))
                    ->get();
                break;
            case 1604:
                $array0 = Detaarticulorden::select([
                    'b.Nombre AS Bodega',
                    'pa.Departamento AS DEPARTAMENTO',
                    'pa.Mpio_Afiliado AS MUNICIPIO',
                    'pa.Tipo_Doc AS TIPO DE DOCUMENTO',
                    'pa.Num_Doc AS DOCUMENTO',
                    'pa.Primer_Nom AS Primer Nombre',
                    'pa.SegundoNom AS Segundo Nombre',
                    'pa.Primer_Ape AS Primer Apellido',
                    'pa.Segundo_Ape AS Segundo Apellido',
                    'pa.Telefono AS Teléfono',
                    'pa.Celular1 AS Celular1',
                    'o.id AS Numero orden',
                    'cs.Codigo AS CODIGO SUMI',
                    'cs.Nombre AS NOMBRE DEL MEDICAMENTO',
                    DB::raw("(select TOP 1 Concentracion from detallearticulos where Codesumi_id = cs.id AND Concentracion IS NOT NULL) AS [CONCENTRACION]"),
                    'detaarticulordens.Via AS VÍA ADMINISTRACIÓN',
                    'detaarticulordens.Frecuencia AS FRECUENCIA',
                    'detaarticulordens.Duracion AS DURACION DEL TRATAMIENTO',
                    'detaarticulordens.Cantidadpormedico AS CANTIDAD ORDENADA',
                    'detaarticulordens.Unidadmedida AS PRESENTACIÓN MEDICAMENTO',
                    'o.Fechaorden AS FECHA DE SOLICITUD',
                    DB::raw("(select TOP 1 bodegatransacions.created_at from bodegatransacions join bodegarticulos b on bodegatransacions.Bodegarticulo_id = b.id join detallearticulos d on b.Detallearticulo_id = d.id join codesumis c on d.Codesumi_id = c.id join movimientos m on bodegatransacions.Movimiento_id = m.id where m.Orden_id = o.id and c.id = cs.id order by bodegatransacions.id ASC) AS [FECHA DE ENTREGA]"),
                    DB::raw("(select SUM(bodegatransacions.CantidadtotalFactura) from bodegatransacions join bodegarticulos b on bodegatransacions.Bodegarticulo_id = b.id join detallearticulos d on b.Detallearticulo_id = d.id join codesumis c on d.Codesumi_id = c.id join movimientos m on bodegatransacions.Movimiento_id = m.id where m.Orden_id = o.id and c.id = cs.id) AS [CANTIDAD ENTREGADA]"),
                    DB::raw("(detaarticulordens.Cantidadpormedico)-(select SUM (bodegatransacions.CantidadtotalFactura) from bodegatransacions join bodegarticulos b on bodegatransacions.Bodegarticulo_id = b.id join detallearticulos d on b.Detallearticulo_id = d.id join codesumis c on d.Codesumi_id = c.id join movimientos m on bodegatransacions.Movimiento_id = m.id where m.Orden_id = o.id and c.id = cs.id) AS [CANTIDAD PENDIENTE]"),
                    DB::raw("CASE WHEN detaarticulordens.Estado_id = 17 THEN detaarticulordens.updated_at ELSE null END AS [FECHA ENTREGA DE PENDIENTE]"),
                    DB::raw("CASE WHEN detaarticulordens.Estado_id = 17 THEN DATEDIFF(day,(select TOP 1 bodegatransacions.created_at from bodegatransacions join bodegarticulos b on bodegatransacions.Bodegarticulo_id = b.id join detallearticulos d on b.Detallearticulo_id = d.id join codesumis c on d.Codesumi_id = c.id join movimientos m on bodegatransacions.Movimiento_id = m.id where m.Orden_id = o.id and c.id = cs.id order by bodegatransacions.id ASC),detaarticulordens.updated_at) ELSE null END AS [DIAS]"),
                    DB::raw("CASE WHEN detaarticulordens.adomicilio = 1 THEN 'SI' ELSE 'NO' END AS DOMICILIO"),
                ])
                    ->join('ordens as o','detaarticulordens.Orden_id','o.id')
                    ->join('cita_paciente as cp','o.Cita_id','cp.id')
                    ->join('pacientes as pa','cp.Paciente_id','pa.id')
                    ->join('codesumis as cs','detaarticulordens.codesumi_id','cs.id')
                    ->join('movimientos as m','m.Orden_id','o.id')
                    ->join('bodegas as b','m.BodegaOrigen_id','b.id')
                    ->where('pa.entidad_id',$request->entidad)
                    ->whereIn('detaarticulordens.Estado_id',[18,19]);
                if($request->bodega >= 1){
                    $array0->where('m.BodegaOrigen_id',$request->bodega);
                }
                $array0->whereBetween('o.Fechaorden',array($request->fechaDesde.' 00:00:00.000',$request->fechaHasta.' 23:59:00.000'))
                    ->groupBy('b.Nombre','pa.Departamento','pa.Mpio_Afiliado','pa.Tipo_Doc','pa.Primer_Nom','pa.SegundoNom','pa.Primer_Ape','pa.Segundo_Ape','pa.Telefono','pa.Celular1','o.id','cs.Nombre','detaarticulordens.Cantidadpormedico','detaarticulordens.Unidadmedida','o.Fechaorden','cs.id','detaarticulordens.updated_at','detaarticulordens.adomicilio','detaarticulordens.Estado_id','pa.Num_Doc','detaarticulordens.Via','detaarticulordens.Frecuencia','detaarticulordens.Duracion','cs.Codigo')
                    ->havingRaw("(select TOP 1 bodegatransacions.CantidadtotalFactura from bodegatransacions join bodegarticulos b on bodegatransacions.Bodegarticulo_id = b.id join detallearticulos d on b.Detallearticulo_id = d.id join codesumis c on d.Codesumi_id = c.id join movimientos m on bodegatransacions.Movimiento_id = m.id where m.Orden_id = o.id and c.id = cs.id order by bodegatransacions.id ASC) < detaarticulordens.Cantidadpormedico")
                    ->distinct();

                $array1 = Detaarticulorden::select([
                    'b.Nombre AS Bodega',
                    'pa.Departamento AS DEPARTAMENTO',
                    'pa.Mpio_Afiliado AS MUNICIPIO',
                    'pa.Tipo_Doc AS TIPO DE DOCUMENTO',
                    'pa.Num_Doc AS DOCUMENTO',
                    'pa.Primer_Nom AS Primer Nombre',
                    'pa.SegundoNom AS Segundo Nombre',
                    'pa.Primer_Ape AS Primer Apellido',
                    'pa.Segundo_Ape AS Segundo Apellido',
                    'pa.Telefono AS Teléfono',
                    'pa.Celular1 AS Celular1',
                    'o.id AS Numero orden',
                    'cs.Codigo AS CODIGO SUMI',
                    'cs.Nombre AS NOMBRE DEL MEDICAMENTO',
                    DB::raw("(select TOP 1 Concentracion from detallearticulos where Codesumi_id = cs.id AND Concentracion IS NOT NULL) AS [CONCENTRACION]"),
                    'detaarticulordens.Via AS VÍA ADMINISTRACIÓN',
                    'detaarticulordens.Frecuencia AS FRECUENCIA',
                    'detaarticulordens.Duracion AS DURACION DEL TRATAMIENTO',
                    'detaarticulordens.Cantidadpormedico AS CANTIDAD ORDENADA',
                    'detaarticulordens.Unidadmedida AS PRESENTACIÓN MEDICAMENTO',
                    'o.Fechaorden AS FECHA DE SOLICITUD',
                    DB::raw("(select TOP 1 bodegatransacions.created_at from bodegatransacions join bodegarticulos b on bodegatransacions.Bodegarticulo_id = b.id join detallearticulos d on b.Detallearticulo_id = d.id join codesumis c on d.Codesumi_id = c.id join movimientos m on bodegatransacions.Movimiento_id = m.id where m.Orden_id = o.id and c.id = cs.id order by bodegatransacions.id ASC) AS [FECHA DE ENTREGA]"),
                    DB::raw("(select SUM(bodegatransacions.CantidadtotalFactura) from bodegatransacions join bodegarticulos b on bodegatransacions.Bodegarticulo_id = b.id join detallearticulos d on b.Detallearticulo_id = d.id join codesumis c on d.Codesumi_id = c.id join movimientos m on bodegatransacions.Movimiento_id = m.id where m.Orden_id = o.id and c.id = cs.id) AS [CANTIDAD ENTREGADA]"),
                    DB::raw("(detaarticulordens.Cantidadpormedico)-(select SUM(bodegatransacions.CantidadtotalFactura) from bodegatransacions join bodegarticulos b on bodegatransacions.Bodegarticulo_id = b.id join detallearticulos d on b.Detallearticulo_id = d.id join codesumis c on d.Codesumi_id = c.id join movimientos m on bodegatransacions.Movimiento_id = m.id where m.Orden_id = o.id and c.id = cs.id) AS [CANTIDAD PENDIENTE]"),
                    DB::raw("CASE WHEN detaarticulordens.Estado_id = 17 THEN detaarticulordens.updated_at ELSE null END AS [FECHA ENTREGA DE PENDIENTE]"),
                    DB::raw("CASE WHEN detaarticulordens.Estado_id = 17 THEN DATEDIFF(day,(select TOP 1 bodegatransacions.created_at from bodegatransacions join bodegarticulos b on bodegatransacions.Bodegarticulo_id = b.id join detallearticulos d on b.Detallearticulo_id = d.id join codesumis c on d.Codesumi_id = c.id join movimientos m on bodegatransacions.Movimiento_id = m.id where m.Orden_id = o.id and c.id = cs.id order by bodegatransacions.id ASC),detaarticulordens.updated_at) ELSE null END AS [DIAS]"),
                    DB::raw("CASE WHEN detaarticulordens.adomicilio = 1 THEN 'SI' ELSE 'NO' END AS DOMICILIO"),
                ])
                    ->join('ordens as o','detaarticulordens.Orden_id','o.id')
                    ->join('cita_paciente as cp','o.Cita_id','cp.id')
                    ->join('pacientes as pa','cp.Paciente_id','pa.id')
                    ->join('codesumis as cs','detaarticulordens.codesumi_id','cs.id')
                    ->join('movimientos as m','m.Orden_id','o.id')
                    ->join('bodegas as b','m.BodegaOrigen_id','b.id')
                    ->where('pa.entidad_id',$request->entidad)
                    ->where('detaarticulordens.Estado_id',17);
                if($request->bodega >= 1){
                    $array1->where('m.BodegaOrigen_id',$request->bodega);
                }
                $array1->whereBetween('o.Fechaorden',array($request->fechaDesde.' 00:00:00.000',$request->fechaHasta.' 23:59:00.000'))
                    ->groupBy('b.Nombre','pa.Departamento','pa.Mpio_Afiliado','pa.Tipo_Doc','pa.Primer_Nom','pa.SegundoNom','pa.Primer_Ape','pa.Segundo_Ape','pa.Telefono','pa.Celular1','o.id','cs.Nombre','detaarticulordens.Cantidadpormedico','detaarticulordens.Unidadmedida','o.Fechaorden','cs.id','detaarticulordens.updated_at','detaarticulordens.adomicilio','detaarticulordens.Estado_id','pa.Num_Doc','detaarticulordens.Via','detaarticulordens.Frecuencia','detaarticulordens.Duracion','cs.Codigo')
                    ->havingRaw("(select TOP 1 bodegatransacions.CantidadtotalFactura from bodegatransacions join bodegarticulos b on bodegatransacions.Bodegarticulo_id = b.id join detallearticulos d on b.Detallearticulo_id = d.id join codesumis c on d.Codesumi_id = c.id join movimientos m on bodegatransacions.Movimiento_id = m.id where m.Orden_id = o.id and c.id = cs.id order by bodegatransacions.id ASC) < detaarticulordens.Cantidadpormedico AND DATEDIFF(day,(select TOP 1 bodegatransacions.created_at from bodegatransacions join bodegarticulos b on bodegatransacions.Bodegarticulo_id = b.id join detallearticulos d on b.Detallearticulo_id = d.id join codesumis c on d.Codesumi_id = c.id join movimientos m on bodegatransacions.Movimiento_id = m.id where m.Orden_id = o.id and c.id = cs.id order by bodegatransacions.id ASC),detaarticulordens.updated_at) > 0")
                    ->distinct();

                $array2 = Detaarticulorden::select([
                    'b.Nombre AS Bodega',
                    'pa.Departamento AS DEPARTAMENTO',
                    'pa.Mpio_Afiliado AS MUNICIPIO',
                    'pa.Tipo_Doc AS TIPO DE DOCUMENTO',
                    'pa.Num_Doc AS DOCUMENTO',
                    'pa.Primer_Nom AS Primer Nombre',
                    'pa.SegundoNom AS Segundo Nombre',
                    'pa.Primer_Ape AS Primer Apellido',
                    'pa.Segundo_Ape AS Segundo Apellido',
                    'pa.Telefono AS Teléfono',
                    'pa.Celular1 AS Celular1',
                    'o.id AS Numero orden',
                    'cs.Codigo AS CODIGO SUMI',
                    'cs.Nombre AS NOMBRE DEL MEDICAMENTO',
                    DB::raw("(select TOP 1 Concentracion from detallearticulos where Codesumi_id = cs.id AND Concentracion IS NOT NULL) AS [CONCENTRACION]"),
                    'detaarticulordens.Via AS VÍA ADMINISTRACIÓN',
                    'detaarticulordens.Frecuencia AS FRECUENCIA',
                    'detaarticulordens.Duracion AS DURACION DEL TRATAMIENTO',
                    'detaarticulordens.Cantidadpormedico AS CANTIDAD ORDENADA',
                    'detaarticulordens.Unidadmedida AS PRESENTACIÓN MEDICAMENTO',
                    'o.Fechaorden AS FECHA DE SOLICITUD',
                    DB::raw("(select TOP 1 bodegatransacions.created_at from bodegatransacions join bodegarticulos b on bodegatransacions.Bodegarticulo_id = b.id join detallearticulos d on b.Detallearticulo_id = d.id join codesumis c on d.Codesumi_id = c.id join movimientos m on bodegatransacions.Movimiento_id = m.id where m.Orden_id = o.id and c.id = cs.id order by bodegatransacions.id ASC) AS [FECHA DE ENTREGA]"),
                    DB::raw("(select SUM(bodegatransacions.CantidadtotalFactura) from bodegatransacions join bodegarticulos b on bodegatransacions.Bodegarticulo_id = b.id join detallearticulos d on b.Detallearticulo_id = d.id join codesumis c on d.Codesumi_id = c.id join movimientos m on bodegatransacions.Movimiento_id = m.id where m.Orden_id = o.id and c.id = cs.id) AS [CANTIDAD ENTREGADA]"),
                    DB::raw("(detaarticulordens.Cantidadpormedico)-(select SUM(bodegatransacions.CantidadtotalFactura) from bodegatransacions join bodegarticulos b on bodegatransacions.Bodegarticulo_id = b.id join detallearticulos d on b.Detallearticulo_id = d.id join codesumis c on d.Codesumi_id = c.id join movimientos m on bodegatransacions.Movimiento_id = m.id where m.Orden_id = o.id and c.id = cs.id) AS [CANTIDAD PENDIENTE]"),
                    DB::raw("CASE WHEN detaarticulordens.Estado_id = 17 THEN detaarticulordens.updated_at ELSE null END AS [FECHA ENTREGA DE PENDIENTE]"),
                    DB::raw("CASE WHEN detaarticulordens.Estado_id = 17 THEN DATEDIFF(day,(select TOP 1 bodegatransacions.created_at from bodegatransacions join bodegarticulos b on bodegatransacions.Bodegarticulo_id = b.id join detallearticulos d on b.Detallearticulo_id = d.id join codesumis c on d.Codesumi_id = c.id join movimientos m on bodegatransacions.Movimiento_id = m.id where m.Orden_id = o.id and c.id = cs.id order by bodegatransacions.id ASC),detaarticulordens.updated_at) ELSE null END AS [DIAS]"),
                    DB::raw("CASE WHEN detaarticulordens.adomicilio = 1 THEN 'SI' ELSE null END AS DOMICILIO"),
                ])
                    ->join('ordens as o','detaarticulordens.Orden_id','o.id')
                    ->join('cita_paciente as cp','o.Cita_id','cp.id')
                    ->join('pacientes as pa','cp.Paciente_id','pa.id')
                    ->join('codesumis as cs','detaarticulordens.codesumi_id','cs.id')
                    ->leftjoin('movimientos as m','m.Orden_id','o.id')
                    ->leftjoin('bodegas as b','m.BodegaOrigen_id','b.id')
                    ->where('pa.entidad_id',$request->entidad)
                    ->where('detaarticulordens.Cantidadpormedico','=',DB::raw('CONVERT(INT,detaarticulordens.Cantidadmensualdisponible)'))
                    ->whereIN('detaarticulordens.Estado_id',[18,19]);
                if($request->bodega >= 1){
                    $array2->where('m.BodegaOrigen_id',$request->bodega);
                }
                $array2->whereBetween('o.Fechaorden',array($request->fechaDesde.' 00:00:00.000',$request->fechaHasta.' 23:59:00.000'))
                    ->distinct();
                $ar0 = $array0->get()->toArray();
                $ar1 = $array1->get()->toArray();
                $ar2 = $array2->get()->toArray();
                $result = array_merge($ar0,$ar1,$ar2);
                break;
            case 1:
                $array1 = Bodegatransacion::select([
                    'bo.Nombre AS Bodegas',
                    'p.Departamento AS DEPARTAMENTO',
                    'p.Mpio_Afiliado AS MUNICIPIO',
                    'p.Tipo_Doc AS TIPO DE DOCUMENTO',
                    'p.Num_Doc AS DOCUMENTO',
                    'p.Primer_Nom AS Primer Nombre',
                    'p.SegundoNom AS Segundo Nombre',
                    'p.Primer_Ape AS Primer Apellido',
                    'p.Segundo_Ape AS Segundo Apellido',
                    'p.Telefono AS Teléfono',
                    'p.Celular1 AS Celular1',
                    'o.id AS Numero orden',
                    'c.Codigo AS CODIGO SUMI',
                    'c.Nombre AS NOMBRE DEL MEDICAMENTO',
                    DB::raw("(CONCAT(d.Cantidad,' ',d.Unidad_Medida)) AS [CONCENTRACION]"),
                    'da.Via AS VÍA ADMINISTRACIÓN',
                    'da.Frecuencia AS FRECUENCIA',
                    'da.Duracion AS DURACION DEL TRATAMIENTO',
                    'da.Cantidadpormedico AS CANTIDAD ORDENADA',
                    'bodegatransacions.CantidadtotalFactura AS CANTIDAD ENTREGADA',
                    'da.Unidadmedida AS PRESENTACIÓN MEDICAMENTO',
                    'o.Fechaorden AS FECHA DE SOLICITUD',
                    'bodegatransacions.created_at AS [FECHA DE ENTREGA]',
                    DB::raw("CASE WHEN da.Estado_id = 17 THEN DATEDIFF(day,o.Fechaorden,da.updated_at) ELSE null END AS [DIAS]"),
                    DB::raw("CASE WHEN da.adomicilio = 1 THEN 'SI' ELSE 'NO' END AS DOMICILIO"),
                ])
                    ->join("bodegarticulos as b","bodegatransacions.Bodegarticulo_id","b.id")
                    ->join("detallearticulos as d ","b.Detallearticulo_id" , "d.id")
                    ->join("codesumis as c" , "d.Codesumi_id" , "c.id")
                    ->join("movimientos as m" ,"bodegatransacions.Movimiento_id" , "m.id")
                    ->join("ordens as o" , "m.Orden_id" , "o.id")
                    ->join("cita_paciente as cp" , "o.Cita_id" , "cp.id")
                    ->join("pacientes as p" , "cp.Paciente_id" , "p.id")
                    ->join('bodegas as bo','m.BodegaOrigen_id','bo.id')
                    ->join('detaarticulordens as da',function ($join){
                        $join->on('da.codesumi_id', '=', 'c.id');
                        $join->on('da.Orden_id', '=', 'o.id');
                    })
                    ->where('p.entidad_id',$request->entidad)
                    ->where('bodegatransacions.created_at','>=',$request->fechaDesde.' 00:00:00.000')
                    ->where('bodegatransacions.created_at','<=',$request->fechaHasta.' 23:59:00.000');

                if($request->bodega >= 1){
                    $array1->where('m.BodegaOrigen_id',$request->bodega);
                }
                $result = $array1->get()->toArray();
                break;
            case 5:
                $Hoy= Carbon::now();
                $fechaDomingo = $Hoy->isoFormat('dddd');
                if($fechaDomingo == 'domingo'){
                    ini_set('max_execution_time', 400);
                    ini_set('memory_limit', '3070M');
                    $appointments = [];

                    $parte1 = DB::table('detaarticulordens as a')
                        ->select(DB::raw("case e.entidad_id when 1 then 'RedVital' when 2 then 'Medimas' when 3 then 'Ferrocarriles' end as ENTIDADES"),
                            's.Codigo as CÓDIGO DE LA IPS', 'e.Tipo_Doc as TIPO DE DOCUMENTO', 'e.Num_Doc as # DOCUMENTO', 'e.Estado_Afiliado as ESTADO DE AFILIACION', 'e.Primer_Ape as PRIMER APELLIDO',
                            'e.Segundo_Ape as SEGUNDO APELLIDO', 'e.Primer_Nom as "PRIMER NOMBRE"', 'e.SegundoNom as "SEGUNDO NOMBRE"', 'e.Fecha_Naci as "FECHA NACIMIENTO"', 'e.Edad_Cumplida as EDAD',
                            'e.Sexo as GENERO', 'e.Mpio_Residencia as CIUDAD', 'e.Departamento as DEPARTAMENTO', 'e.Telefono as TELEFONO', 'e.Direccion_Residencia as DIRECCION', 'e.Mpio_Residencia as BARRIO',
                            's.Nombre as IPS PRIMARIA', 'e.Nombreresponsable as NOMBRE DEL ACUDIENTE', 'b.Ambito as AMBITO DE LA FORMULACION', 'b.Nombre as NOMBRE DEL MEDICAMENTO EN DENOMINACION COMUN INTERNACIONAL',
                            'deta.Concentracion as CONCENTRACION', 'deta.Forma_Farmaceutica as FORMA FARMACEUTICA', 'a.Via as VIA DE ADMINISTRACIÓN', 'a.Frecuencia as FRECUENCIA', 'a.Duracion as DURACION DEL TRATAMIENTO',
                            'a.Cantidadpormedico as CANTIDAD PRESCRITA', 'bt.Cantidadtotal as CANTIDAD ENTREGADA', 'c10.Codigo_CIE10 as DIAGNOSTICO PRINCIPAL CODIGO CIE10', 'c10.Descripcion as DIAGNOSTICO RELACIONADO CODIGO CIE10',
                            'a.Fechaorden as FECHA DE PRESCRIPCION DEL MEDICAMENTO', 'a.Fechaorden as FECHA REGISTRO DE ENTREGA MEDICAMENTOS',
                            DB::raw("case a.adomicilio when 1 then 'Si' else 'No' end as A_DOMICILIO"))
                        ->join('codesumis as b','b.id','a.codesumi_id')
                        ->join('detallearticulos as deta','deta.Codesumi_id','b.id')
                        ->join('ordens as c','a.Orden_id','c.id')
                        ->join('movimientos as mo','mo.Orden_id','c.id')
                        ->join('bodegatransacions as bt','bt.Movimiento_id','mo.id')
                        ->join('cita_paciente as d','c.Cita_id','d.id')
                        ->leftjoin('cie10pacientes as ci','ci.Citapaciente_id','d.id')
                        ->leftjoin('cie10s as c10','c10.id','ci.Cie10_id')
                        ->join('pacientes as e','e.id','d.Paciente_id')
                        ->join('sedeproveedores as s','s.id','e.IPS')
                        ->whereBetween('a.Fechaorden', [$request->fechaDesde.' 00:00:00.000', $request->fechaHasta.' 23:59:00.000'])
                        //->where('ci.Esprimario',1)
                        ->where('a.Estado_id',18);

                    DB::table('detaarticulordens as da')
                        ->select(DB::raw("case e.entidad_id when 1 then 'RedVital' when 2 then 'Medimas' when 3 then 'Ferrocarriles' end as ENTIDADES"),
                            's.Codigo as CÓDIGO DE LA IPS', 'e.Tipo_Doc as TIPO DE DOCUMENTO', 'e.Num_Doc as # DOCUMENTO', 'e.Estado_Afiliado as ESTADO DE AFILIACION', 'e.Primer_Ape as PRIMER APELLIDO',
                            'e.Segundo_Ape as SEGUNDO APELLIDO', 'e.Primer_Nom as PRIMER NOMBRE', 'e.SegundoNom as SEGUNDO NOMBRE', 'e.Fecha_Naci as FECHA NACIMIENTO', 'e.Edad_Cumplida as EDAD',
                            'e.Sexo as GENERO', 'e.Mpio_Residencia as CIUDAD', 'e.Departamento as DEPARTAMENTO', 'e.Telefono as TELEFONO', 'e.Direccion_Residencia as DIRECCION', 'e.Mpio_Residencia as BARRIO',
                            's.Nombre as IPS PRIMARIA', 'e.Nombreresponsable as NOMBRE DEL ACUDIENTE', 'b.Ambito as AMBITO DE LA FORMULACION', 'b.Nombre as NOMBRE DEL MEDICAMENTO EN DENOMINACION COMUN INTERNACIONAL',
                            'deta.Concentracion as CONCENTRACION', 'deta.Forma_Farmaceutica as FORMA FARMACEUTICA', 'da.Via as VIA DE ADMINISTRACIÓN', 'da.Frecuencia as FRECUENCIA', 'da.Duracion as DURACION DEL TRATAMIENTO',
                            'da.Cantidadpormedico as CANTIDAD PRESCRITA', 'bt.Cantidadtotal as CANTIDAD ENTREGADA', 'c10.Codigo_CIE10 as DIAGNOSTICO PRINCIPAL CODIGO CIE10', 'c10.Descripcion as DIAGNOSTICO RELACIONADO CODIGO CIE10',
                            'da.Fechaorden as FECHA DE PRESCRIPCION DEL MEDICAMENTO', 'da.Fechaorden as FECHA REGISTRO DE ENTREGA MEDICAMENTOS',
                            DB::raw("case da.adomicilio when 1 then 'Si' else 'No' end as A_DOMICILIO"))
                        ->join('codesumis as b','b.id','da.codesumi_id')
                        ->join('detallearticulos as deta','deta.Codesumi_id','b.id')
                        ->join('ordens as c','da.Orden_id','c.id')
                        ->join('movimientos as mo','mo.Orden_id','c.id')
                        ->join('bodegatransacions as bt','bt.Movimiento_id','mo.id')
                        ->join('cita_paciente as d','c.Cita_id','d.id')
                        ->leftjoin('cie10pacientes as ci','ci.Citapaciente_id','d.id')
                        ->leftjoin('cie10s as c10','c10.id','ci.Cie10_id')
                        ->join('pacientes as e','e.id','d.Paciente_id')
                        ->join('sedeproveedores as s','s.id','e.IPS')
                        ->whereBetween(DB::raw("CONVERT(nvarchar,da.Fechaorden)"), [$request->fechaDesde.' 00:00:00.000', $request->fechaHasta.' 23:59:00.000'])
                        //->where('ci.Esprimario',1)
                        ->union($parte1)
                        ->orderBy('FECHA DE PRESCRIPCION DEL MEDICAMENTO', 'DESC')
                        ->chunk(10000, function ($detalles) use (&$appointments){
                            foreach ($detalles->toArray() as $detalle){
                                $appointments[] = $detalle;
                            }
                        });
                    $result = json_decode(json_encode($appointments), true);
                    break;
                }
                else{
                    $error='No puede descargar, solo los Dias Domingos.';
                    return response()->json(['mensaje'=>$error], 402);
                }

            case 6:
                $appointments = Collect(DB::select("exec dbo.RecaudoMedicamentos ?,?",[$request->fechaDesde,$request->fechaHasta]));
                $result = json_decode($appointments, true);
                break;
            case 7:
                $appointments = Collect(DB::select("exec dbo.RecaudoConsultas ?,?",[$request->fechaDesde,$request->fechaHasta]));
                $result = json_decode($appointments, true);
                break;
            case 8:
                $appointments = Collect(DB::select("exec dbo.RecaudoProcedimientos ?,?",[$request->fechaDesde,$request->fechaHasta]));
                $result = json_decode($appointments, true);
                break;
            case 9:
                $appointments = Collect(DB::select("exec dbo.SP_Ordenamientos_Inadecuacionesv2 ?,?,?",[$request->fechaDesde,$request->fechaHasta,intval($request->entidad)]));
                $result = json_decode($appointments, true);
                break;
        }
        return (new FastExcel($result))->download('file.xls');

    }
    public function datosPrestador(Cuporden $cupOrden,Request $request){
        $paciente = Paciente::select(['pacientes.*'])
            ->join('cita_paciente as cp','cp.Paciente_id','pacientes.id')
            ->join('ordens as o','o.Cita_id','cp.id')
            ->where('o.id',$cupOrden->Orden_id)
            ->first();

        $file_name = [];
        $paths     = [];
        if ($request->hasFile('adjuntos')) {
            $files = $request->file('adjuntos');

            $i = 0;
            foreach ($files as $file) {
                $file_name[$i] = time().$file->getClientOriginalName();
                $path          = '../storage/app/public/adjuntosSoportes/' . $paciente->Num_Doc;
                $paths[$i]     = '/storage/adjuntosSoportes/' . $paciente->Num_Doc . '/' . time() . $file_name[$i];
                $file->move($path,$file_name[$i]);
                $i++;
            }
        }
        $cupOrden->update([
            'fecha_solicitada' => ($request->fecha_solicitada !== 'null'?$request->fecha_solicitada:null),
            'fecha_sugerida'=> ($request->fecha_sugerida !== 'null'?$request->fecha_sugerida:null),
            'fecha_resultado'=> ($request->fecha_resultado !== 'null'?$request->fecha_resultado:null),
            'observaciones' => ($request->observaciones !== 'null'?$request->observaciones:null),
            'fecha_cancelacion' => ($request->fecha_cancelacion !== 'null'?$request->fecha_cancelacion:null),
            'cancelacion' => ($request->cancelacion !== 'null'?$request->cancelacion:null),
            'causa' => ($request->causa !== 'null'?$request->causa:null),
            'motivo' => ($request->motivo !== 'null'?$request->motivo:null),
            'responsable' => $request->responsable,
            'soportes' => json_encode($file_name),
            'cirujano' => ($request->cirujano !== 'null'?$request->cirujano:null),
            'especialidad' => ($request->especialidad !== 'null'?$request->especialidad:null),
            'fecha_Preanestesia' => ($request->fecha_Preanestesia !== 'null'?$request->fecha_Preanestesia:null),
            'fecha_cirugia' => ($request->fecha_cirugia !== 'null'?$request->fecha_cirugia:null),
            'fecha_ejecucion' => ($request->fecha_ejecucion !== 'null'?$request->fecha_ejecucion:null),
        ]);

        return response()->json([
            'message' => 'Servicios Actualizado!'
        ], 200);
    }

    public function historiasMedimas(){
//        $Historias = hcmedima::skip(0)->take(20)->get();
        $Historias = hcmedima::all();

        return response()->json($Historias);
    }


    public function consolidados(Request $request){
        $historia = DB::select('exec dbo.ConsolidadoHC ?,?,?', [$request->cedula,($request->desde?$request->desde." 00:00:00.000":null),($request->hasta?$request->hasta." 23:59:00.000":null)]);
        return response()->json($historia, 200);
    }

    public function getDetalleOrdenamientoForCita($citaPaciente){
        $medicamentos = [];
        $servicios = [];
        $arrIdMedicamentos = [];
        $arrIdServicios = [];
        $ordenMedicamento = Orden::where('Cita_id',$citaPaciente)->where('Tiporden_id',3)->orderBy('id', 'ASC')->get(["id"])->toArray();
        foreach ($ordenMedicamento as $orden){
            $arrIdMedicamentos[] = $orden["id"];
        }
        $ordenServicios = Orden::where('Cita_id',$citaPaciente)->whereIn('Tiporden_id',[4,2])->orderBy('id', 'ASC')->get(["id"])->toArray();
        foreach($ordenServicios as $orden){
            $arrIdServicios[] = $orden["id"];
        }
        if($ordenMedicamento){
            $medicamentos = Detaarticulorden::select(['detaarticulordens.id',
                'c.Nombre',
                'c.Codigo',
                DB::raw("CONCAT(detaarticulordens.Cantidadosis,' ',detaarticulordens.Unidadmedida,' cada ',detaarticulordens.Frecuencia,' ',detaarticulordens.Unidadtiempo,' por ',detaarticulordens.Duracion,' dias') AS Cantidad"),
                'detaarticulordens.Observacion',
                DB::raw("'medicamento' as tipo"),
                'detaarticulordens.Orden_id',
                DB::raw("'3' as Tiporden_id"),
                'detaarticulordens.codesumi_id AS referencia',
                'detaarticulordens.Estado_id',
                'o.paginacion'
            ])
                ->join('codesumis as c','c.id','detaarticulordens.codesumi_id')
                ->join('ordens as o','o.id','detaarticulordens.Orden_id')
                ->whereIn('detaarticulordens.Orden_id',$arrIdMedicamentos)->get()->toArray();
        }
        if($ordenServicios){
            $servicios = Cuporden::select(['cupordens.id',
                'c.Codigo',
                'c.Nombre',
                'cupordens.Cantidad',
                'cupordens.Observacionorden AS Observacion',
                DB::raw("'servicio' as tipo"),
                'cupordens.Orden_id',
                DB::raw("'4' as Tiporden_id"),
                'cupordens.Cup_id as referencia',
                'cupordens.Estado_id',
                DB::raw("'1/1' as paginacion"),
            ])
                ->join('cups as c','c.id','cupordens.Cup_id')
                ->whereIn('cupordens.Orden_id',$arrIdServicios)->get()->toArray();
        }
        $ordenes = array_merge($medicamentos,$servicios);

        return response()->json($ordenes);
    }

    public function recomendaciones($codigo){

        $rutas = Recomendacione::whereIn('cup_id', function ($q) use ($codigo){
            $q->select('id')
                ->from('cups')
                ->where('Codigo',$codigo);
        })->get()->toArray();

        return response()->json(['file' => $rutas]);

    }

    public function getNivel(){
        return response()->json(auth()->user()->getRepository()->getOrderingLevelItemsOrder());
    }

    public function fetchOptometria($cita_paciente_id)
    {
        $orden = Orden::select(
            'formula_optometrias.*','formula_optometrias.observacion as Observacion','ordens.*'
        )
        ->join('formula_optometrias','formula_optometrias.Orden_id','ordens.id')
        ->where('ordens.Cita_id',$cita_paciente_id)->get();

        return response()->json($orden,200);
    }

    public function fetchRecomendacion($cita_paciente_id)
    {
        $orden = Orden::select(
            'recomendaciones_clinicas.recomedacion','recomendaciones_clinicas.fecha_aislamiento',
            'ordens.created_at','ordens.id'
        )
        ->join('recomendaciones_clinicas','recomendaciones_clinicas.Orden_id','ordens.id')
        ->where('ordens.Cita_id',$cita_paciente_id)->get();

        return response()->json($orden,200);
    }


    public function getOptometria($documento)
    {
        $orden = Orden::select(
            'formula_optometrias.*','formula_optometrias.observacion as Observacion','ordens.id as orden','ordens.*'
            ,'e.id as Estado_id','e.Nombre as Estado'
        )
        ->join('formula_optometrias','formula_optometrias.Orden_id','ordens.id')
        ->join('cita_paciente','cita_paciente.id','ordens.Cita_id')
        ->join('pacientes','pacientes.id','cita_paciente.Paciente_id')
        ->join('estados as e','ordens.Estado_id','e.id')
        ->where('pacientes.Num_Doc',$documento)->get();

        return response()->json($orden,200);
    }

    public function fecthAlertas(request $request)
    {

        $principioActivo = Detallearticulo::select('detallearticulos.descripcion_atc as descripcion_atc')
        ->distinct()
        ->join('alertas','alertas.principal','detallearticulos.descripcion_atc')
        ->where('detallearticulos.Codesumi_id',$request->medicamento['id'])->first();

        $fecha_hoy  = carbon::now()->format('Y-m-d');
        $fecha_menos_6mes = carbon::now()->subMonth(6)->format('Y-m-d');


        $principioOrdenados = citapaciente::select('alerta_detalles.id as id')
        ->distinct()
        ->join('ordens','cita_paciente.id','ordens.Cita_id')
        ->join('detaarticulordens','detaarticulordens.Orden_id','ordens.id')
        ->join('codesumis','codesumis.id','detaarticulordens.codesumi_id')
        ->join('detallearticulos','detallearticulos.Codesumi_id','codesumis.id')
        ->join('alerta_detalles','alerta_detalles.interaccion','detallearticulos.Descripcion_Atc')
        ->whereIn('detaarticulordens.Estado_id',[1,7,17,18])
        ->whereBetween('ordens.Fechaorden',[$fecha_menos_6mes, $fecha_hoy])
        ->where('cita_paciente.Paciente_id',$request->paciente_id)->get();

    if($principioActivo != null & count($principioOrdenados)>0){
        foreach ($principioOrdenados as $principioOrdenado) {
            $mensajeInteracciones = Alerta::select('alerta_detalles.id','tipo_alertas.Nombre as Nombre_Alerta','alerta_detalles.interaccion','mensajes_alertas.Mensaje')
                ->join('alerta_detalles','alertas.id','alerta_detalles.alertas_id')
                ->join('mensajes_alertas','alerta_detalles.mensaje_id','mensajes_alertas.id')
                ->join('tipo_alertas','alerta_detalles.tipo_id','tipo_alertas.id')
                ->where('alertas.principal',$principioActivo->descripcion_atc)
                ->where('alerta_detalles.id',$principioOrdenado->id)->get();

        }
        $mensajeNoInteracciones = Codesumi::select('mensajes_alertas.Mensaje','tipo_alertas.Nombre as Nombre_Alerta')
        ->join('alertas','alertas.principal','codesumis.descripcion_atc')
        ->join('alerta_detalles','alerta_detalles.alertas_id','alertas.id')
        ->join('tipo_alertas','alerta_detalles.tipo_id','tipo_alertas.id')
        ->join('mensajes_alertas','mensajes_alertas.id','alerta_detalles.mensaje_id')
        ->where('codesumis.id',$request->medicamento['id'])
        ->whereNull('alerta_detalles.interaccion')->get();
    }else{
        $mensajeNoInteracciones = Codesumi::select('mensajes_alertas.Mensaje','tipo_alertas.Nombre as Nombre_Alerta')
        ->join('alertas','alertas.principal','codesumis.descripcion_atc')
        ->join('alerta_detalles','alerta_detalles.alertas_id','alertas.id')
        ->join('tipo_alertas','alerta_detalles.tipo_id','tipo_alertas.id')
        ->join('mensajes_alertas','mensajes_alertas.id','alerta_detalles.mensaje_id')
        ->where('codesumis.id',$request->medicamento['id'])
        ->whereNull('alerta_detalles.interaccion')->get();
        $mensajeInteracciones = null;
        if(count($mensajeNoInteracciones) == 0){
            $mensajeNoInteracciones = null;
        }
    }


        return response()->json(['mensajeInteracciones' =>$mensajeInteracciones, 'mensajeNoInteracciones' => $mensajeNoInteracciones],200);
    }

    public function getCantidadmaxordenar(request $request)
    {
        $medicamento = Codesumi::select('codesumis.Cantidadmaxordenar','codesumis.Requiere_autorizacion')
        ->where('codesumis.id',$request->Codesumi_id)->first();
        return response()->json($medicamento,200);
    }

    public function fecthAlergico(request $request)
    {
       if($request->medicamento['descripcion_atc'] != null){
            $alergico = AntecedenteFarmacologico::select('antecedente_farmacologicos.observacionAlergia')
            ->join('detallearticulos','detallearticulos.id','antecedente_farmacologicos.detallearticulo_id')
            ->where('antecedente_farmacologicos.estado_id',1)
            ->where('antecedente_farmacologicos.paciente_id',$request->paciente_id)
            ->where('detallearticulos.Descripcion_Atc',$request->medicamento['descripcion_atc'])->first();

            if($alergico == null){
                $alergico = false;
            }
       }
       else{
        $alergico = false;
       }

        return response()->json($alergico,200);
    }

    public function fecthdesabastesimiento(request $request)
    {
        if($request->medicamento['id'] != null){
            $alergico = Alerta::select('mensajes_alertas.Mensaje','tipo_alertas.Nombre as tipo_alerta')
            ->join('alerta_detalles','alerta_detalles.alertas_id','alertas.id')
            ->join('mensajes_alertas','mensajes_alertas.id','alerta_detalles.mensaje_id')
            ->join('tipo_alertas','tipo_alertas.id','alerta_detalles.tipo_id')
            ->where('alerta_detalles.Estado_id',1)
            ->where('alertas.codesumis_id',$request->medicamento['id'])->first();

            if($alergico == null){
                $alergico = false;
            }
       }
       else{
        $alergico = false;
       }

        return response()->json($alergico,200);
    }

    public function confirmacionAlerta(request $request)
    {
        AuditoriaAlertas::create([
            'Usuario_id'            => auth()->user()->id,
            'acepto'                => $request->tipo,
            'paciente_id'           => $request->paciente_id,
            'Citapaciente_id'       => $request->cita_paciente_id,
            'alertas_detalle_id'    => $request->id,
        ]);
        if($request->tipo == 'Si'){
            $AuditoriaAlertas = 'Has Aceptado la alerta por favor tomar las medidas necesarias';
        }else{
            $AuditoriaAlertas = 'No has Aceptado la alerta por favor tomar las medidas necesarias';
        }


        return response()->json($AuditoriaAlertas,200);
    }

    public function fechtAlertas()
    {
        $fechAlertas = AuditoriaAlertas::select('alerta_detalles.interaccion','alertas.principal',
        DB::raw("CONCAT(pacientes.Segundo_Ape,' ',pacientes.Primer_Ape,' ',pacientes.Primer_Nom,' ',pacientes.SegundoNom) as paciente"),
        DB::raw("CONCAT(users.name,' ',users.apellido) as Medico"),
        'mensajes_alertas.titulo', 'tipo_alertas.Nombre as alerta_nombre','cita_paciente.updated_at as fecha_alerta',
        'pacientes.Num_Doc','auditoria_alertas.acepto'
        )
        ->join('alerta_detalles','auditoria_alertas.alertas_detalle_id','alerta_detalles.id')
        ->join('users','users.id','auditoria_alertas.usuario_id')
        ->join('pacientes','pacientes.id','auditoria_alertas.paciente_id')
        ->join('cita_paciente','cita_paciente.id','auditoria_alertas.Citapaciente_id')
        ->join('alertas','alertas.id','alerta_detalles.alertas_id')
        ->join('tipo_alertas','tipo_alertas.id','alerta_detalles.tipo_id')
        ->join('mensajes_alertas','mensajes_alertas.id','alerta_detalles.mensaje_id')->get();

        return response()->json($fechAlertas,200);
    }

    public function generarExcelAlertas(request $request)
    {
    $alertaExcel = AuditoriaAlertas::select(
        'auditoria_alertas.acepto',
        'ad.interaccion',
        'ma.Mensaje',
        'ta.Nombre',
        'p.ut',
        DB::raw("concat(Primer_Nom,' ',SegundoNom,' ',Primer_Ape,' ',Segundo_Ape) as Paciente"),
        'p.Tipo_Doc',
        'p.Num_Doc',
        'se.Nombre as ips',
        DB::raw("concat(name,' ',apellido) as medico"),
        'u.especialidad_medico')
    ->distinct()
    ->join('alerta_detalles as ad', 'auditoria_alertas.alertas_detalle_id','ad.id')
    ->join('pacientes as p', 'auditoria_alertas.paciente_id','p.id')
    ->join('users as u', 'auditoria_alertas.usuario_id','u.id')
    ->join('mensajes_alertas as ma', 'ad.mensaje_id','ma.id')
    ->join('tipo_alertas as ta','ad.tipo_id','ta.id')
    ->join('sedeproveedores as se', 'p.IPS','se.id')
    ->join('cita_paciente as cp', 'auditoria_alertas.Citapaciente_id', 'cp.id')
    ->where('auditoria_alertas.estado_id',1)
    ->whereBetween('cp.Datetimeingreso', [$request->fecha_inicio, $request->fecha_fin])->get();

    return (new FastExcel($alertaExcel))->download('file.xls');
    }

    public function cambiarFechaServicios(Request $request){
        foreach ($request->servicios as $servicio){
            Cuporden::where('id',$servicio['id'])->update(['fecha_orden' => $request->Fechaorden]);
        }
        return response()->json(["message" => "Fecha Servicios Actualizada"]);
    }

    public function verificacionConsentimiento(Request $request){
        $validador = Cup::select('consentimientoInformado','id')->where('id',$request->id)->first();
        return response()->json($validador,200);
    }

    public function cambiarfecha(Request $request){
        $orden = Orden::find($request->id);
        $orden->Fechaorden = $request->Fechaorden;
        $orden->save();
        $fechadispensacion = $orden->Fechaorden;

        if (isset($orden->paginacion)) {
            $pagination = explode('/', $orden->paginacion);

            if (isset($pagination[1])) {
                $j = intval($pagination[0]) + 1;
                for ($i = $j; $i <= intval($pagination[1]); $i++) {
                    $date = date('Y-m-d', strtotime($fechadispensacion . "+" . strval(($i - intval($pagination[0])) * 28) . " day"));
                    $o    = Orden::where('paginacion', $i . "/" . $pagination[1])->where('Cita_id', $orden->Cita_id)->where('autorizacion',$orden->autorizacion)->first();
                    $o->update(['Fechaorden' => $date]);
                }
            }
        }
        return response()->json(["message" => "Fechas Actualizadas"]);
    }

    public function consularOrdenes($id){
        $ordenamiento = citapaciente::select(
        'cups.id as id',
        'cita_paciente.paciente_id',
        'cita_paciente.id as cita_paciente_id')
        ->leftjoin('pacientes','pacientes.id','cita_paciente.Paciente_id')
        ->leftjoin('ordens','ordens.Cita_id','cita_paciente.id')
        ->leftjoin('cupordens','ordens.id','cupordens.Orden_id')
        ->leftjoin('cups','cups.id','cupordens.Cup_id')
        ->where('cups.consentimientoInformado',1)
        ->where('cita_paciente.id',$id)
        ->get();

        return response()->json($ordenamiento,200);
    }
}
