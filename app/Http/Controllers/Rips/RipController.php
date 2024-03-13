<?php

namespace App\Http\Controllers\Rips;

use App\Events\EventEstadoRips;
use App\Modelos\Configuracion;
use App\Modelos\IntentosCargueRip;
use App\Modelos\PaqRip;
use Illuminate\Support\Facades\DB;
use App\Modelos\RipsErrores;
use App\Modelos\Sedeproveedore;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\FileTrait;
use App\Jobs\ValidacionEstructuraRips;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Http\Response;
use App\Services\Rips\RipsService;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\SheetCollection;

class RipController extends Controller
{
    use FileTrait;
    private $errorMessages = [];
    private $CT = [];
    private $US = [];
    private $AF = [];
    private $AC = [];
    private $AP = [];
    private $AM = [];
    private $AT = [];
    private $AU = [];
    private $AH = [];
    private $AN = [];
    private $item = [];

    private function setCT($CT)
    {
        $this->CT = $CT;
    }

    private function setUS($US)
    {
        $this->US = $US;
    }

    private function setAF($AF)
    {
        $this->AF = $AF;
    }

    private function setAC($AC)
    {
        $this->AC = $AC;
    }

    private function setAP($AP)
    {
        $this->AP = $AP;
    }

    private function setAM($AM)
    {
        $this->AM = $AM;
    }

    private function setAT($AT)
    {
        $this->AT = $AT;
    }

    private function setAU($AU)
    {
        $this->AU = $AU;
    }

    private function setAH($AH)
    {
        $this->AH = $AH;
    }

    private function setAN($AN)
    {
        $this->AN = $AN;
    }

    public function validar(Request $request)
    {
        $this->fillFileNames($request->all());
        $this->checkRequiredFiles();
        if (count($this->errorMessages) > 0) {
            return response()->json([
                'errors' => $this->errorMessages,
            ], 400);
        }
        $entidad = null;
        switch (intval($request->entidad)) {
            case 1:
                $entidad = "RES004";
                break;
            case 3:
                $entidad = "EAS027";
                break;
            case 8:
                $entidad = "EAS027-1";
                break;
        }
        $paqueteRip = new PaqRip();
        $sedePrestador = Sedeproveedore::where('Codigo', $this->CT['content'][0][0])->first();
        $paqueteRechazado = PaqRip::where("Nombre", substr($this->CT['fileName'], 2))->where('Reps_id', $sedePrestador->id)->where('mes', date('m'))->where('anio', date('Y'))->whereIn('estado_id', [54, 56])->first();

        if ($paqueteRechazado) {
            $paqueteRechazado->User_id = auth()->user()->id;
            $paqueteRechazado->entidad = $entidad;
            $paqueteRechazado->save();
            $paqueteRip = $paqueteRechazado;
            RipsErrores::where('paq_rip_id', $paqueteRip->id)->delete();
            $path = storage_path("app/public/temporalesrips/" . $paqueteRip->id);
            if (file_exists($path)) {
                foreach (scandir($path) as $file) {
                    if ($file !== '.' && $file !== '..') {
                        unlink($path . '/' . $file);
                    }
                }
            }
        } else {
            $paqueteRip->Nombre = substr($this->CT['fileName'], 2);
            $paqueteRip->nombre_rep = $sedePrestador->Nombre;
            $paqueteRip->codigo = $sedePrestador->Codigo;
            $paqueteRip->ac_size = $this->AC['size'] ?? null;
            $paqueteRip->af_size = $this->AF['size'] ?? null;
            $paqueteRip->ah_size = $this->AH['size'] ?? null;
            $paqueteRip->am_size = $this->AM['size'] ?? null;
            $paqueteRip->ap_size = $this->AP['size'] ?? null;
            $paqueteRip->at_size = $this->AT['size'] ?? null;
            $paqueteRip->au_size = $this->AU['size'] ?? null;
            $paqueteRip->ct_size = $this->CT['size'] ?? null;
            $paqueteRip->us_size = $this->US['size'] ?? null;
            $paqueteRip->entidad = $entidad;
            $paqueteRip->mes = date('m');
            $paqueteRip->anio = date('Y');
            $paqueteRip->Reps_id = $sedePrestador->id;
            $paqueteRip->estado_id = 53;
            $paqueteRip->User_id = auth()->user()->id;
            $paqueteRip->entidad_id = $request->entidad;
            $paqueteRip->save();
        }

        IntentosCargueRip::create([
            'nombre_paquete' => substr($this->CT['fileName'], 2),
            'codigo' => $sedePrestador->Codigo,
            'user_id' => auth()->user()->id
        ]);

        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                $file_name = $file->getClientOriginalName();
                $path = storage_path('app/public/temporalesrips/' . $paqueteRip->id);
                $file->move($path, $file_name);
            }
        }
        broadcast(new EventEstadoRips());
        ValidacionEstructuraRips::dispatch($paqueteRip->id)->onQueue('validacionestructurarips');
        return response()->json(['message' => 'Validando ...']);
    }

    private function fillFileNames($data)
    {
        $currentFile = [];
        foreach ($data["files"] as $file) {
            list($fileName, $ext) = explode('.', $file->getClientOriginalName());
            $open_file = fopen($file, 'r');
            if (filesize($file) > 0) {
                $content = fread($open_file, filesize($file));
                $rows = preg_split('/\r\n/', $content);
                $i = 0;
                $cols = [];
                $newCols = [];
                foreach ($rows as $row) {
                    if ($row) {
                        $cols = explode(',', $row);
                        $j = 0;
                        foreach ($cols as $col) {
                            $newCols[$j] = utf8_encode(trim($col));
                            $j++;
                        }
                        $this->item[$fileName][$i] = $newCols;
                        $i++;
                    }
                    $currentFile['content'] = $this->item[$fileName];
                    $currentFile['fileName'] = $fileName;
                    $currentFile['size'] = $file->getSize();
                    $currentFile['success'] = [];
                    if (preg_match('/CT/i', $fileName)) $this->setCT($currentFile);
                    if (preg_match('/US/i', $fileName)) $this->setUS($currentFile);
                    if (preg_match('/AF/i', $fileName)) $this->setAF($currentFile);
                    if (preg_match('/AC/i', $fileName)) $this->setAC($currentFile);
                    if (preg_match('/AP/i', $fileName)) $this->setAP($currentFile);
                    if (preg_match('/AM/i', $fileName)) $this->setAM($currentFile);
                    if (preg_match('/AT/i', $fileName)) $this->setAT($currentFile);
                    if (preg_match('/AU/i', $fileName)) $this->setAU($currentFile);
                    if (preg_match('/AH/i', $fileName)) $this->setAH($currentFile);
                    if (preg_match('/AN/i', $fileName)) $this->setAN($currentFile);
                }
            } else {
                $msg = 'Archivo ' . $fileName . ' invalido';
                $this->pushErrorMessage($msg);
            }
        }
    }

    private function checkRequiredFiles()
    {
        $configuraciones = $this->autorizacionPeriodoRips();
        if (!$configuraciones['enabled']) {
            $msg = 'La Radicacion de las facturas aplica del 01 al 10 de cada mes de lunes a viernes de 7 am a 5pm. Se reciben facturas hasta el último día hábil de la fecha del cierre de radicación, es decir, si el 10 cae sábado, domingo o días festivo la fecha límite de recepción será el viernes hasta las 5 pm.';
            $this->pushErrorMessage($msg);
        }
        if (count($this->item) < 3) {
            $msg = 'Para la validación RIPS se deben cargar minimo 3 archivos';
            $this->pushErrorMessage($msg);
        }
        if (!isset($this->AF['content'])) {
            $msg = 'El archivo AF es requerido. (Error A)';
            $this->pushErrorMessage($msg);
        }
        if (isset($this->AC['content']) && !isset($this->CT['content'])) {
            $msg = 'No se tiene el número archivos mínimos para hacer el cargue, si carga el AC debe tener CT. (Error A17)';
            $this->pushErrorMessage($msg);
        }

        if (isset($this->AP['content']) && !isset($this->CT['content'])) {
            $msg = 'No se tiene el número archivos mínimos para hacer el cargue, si carga el AP debe existir un archivo CT. (Error A18)';
            $this->pushErrorMessage($msg);
        }

        if (isset($this->AM['content']) && !isset($this->CT['content'])) {
            $msg = 'No se tiene el número archivos mínimos para hacer el cargue, si carga el AM debe existir un archivo CT. (Error A19)';
            $this->pushErrorMessage($msg);
        }

        if (isset($this->AU['content']) && (!isset($this->CT['content']) || !isset($this->AC['content']) || !isset($this->AT['content']))) {
            $msg = 'No se tiene el número archivos mínimos para hacer el cargue, si carga el AU debe existir archivo CT, AC, AT. (Error A20)';
            $this->pushErrorMessage($msg);
        }

        if (isset($this->AH['content']) && (!isset($this->CT['content'])  || !isset($this->AT['content']))) {
            $msg = 'No se tiene el número archivos mínimos para hacer el cargue, si carga el AH debe existir archivo CT, AT. (Error A21)';
            $this->pushErrorMessage($msg);
        }

        if (isset($this->AN['content']) && !isset($this->CT['content'])) {
            $msg = 'No se tiene el número archivos mínimos para hacer el cargue, si carga el AN debe existir archivo CT. (Error A22)';
            $this->pushErrorMessage($msg);
        }

        if (isset($this->CT['content'])) {
            if (strlen($this->CT['fileName']) != 8) {
                $this->pushErrorMessage('El nombre del archivo CT no cumple especificaciones para el nombre del archivo.');
            }
        }

        if (isset($this->US['content'])) {
            if (strlen($this->US['fileName']) != 8) {
                $this->pushErrorMessage('El nombre del archivo US no cumple especificaciones para el nombre del archivo.');
            }
        }

        if (isset($this->AC['content'])) {
            if (strlen($this->AC['fileName']) != 8) {
                $this->pushErrorMessage('El nombre del archivo AC no cumple especificaciones para el nombre del archivo.');
            }
        }

        if (isset($this->AP['content'])) {
            if (strlen($this->AP['fileName']) != 8) {
                $this->pushErrorMessage('El nombre del archivo AP no cumple especificaciones para el nombre del archivo.');
            }
        }

        if (isset($this->AM['content'])) {
            if (strlen($this->AM['fileName']) != 8) {
                $this->pushErrorMessage('El nombre del archivo AM no cumple especificaciones para el nombre del archivo.');
            }
        }

        if (isset($this->AT['content'])) {
            if (strlen($this->AT['fileName']) != 8) {
                $this->pushErrorMessage('El nombre del archivo AT no cumple especificaciones para el nombre del archivo.');
            }
        }

        if (isset($this->AU['content'])) {
            if (strlen($this->AU['fileName']) != 8) {
                $this->pushErrorMessage('El nombre del archivo AU no cumple especificaciones para el nombre del archivo.');
            }
        }

        if (isset($this->AH['content'])) {
            if (strlen($this->AH['fileName']) != 8) {
                $this->pushErrorMessage('El nombre del archivo AH no cumple especificaciones para el nombre del archivo.');
            }
        }

        if (isset($this->AN['content'])) {
            if (strlen($this->AN['fileName']) != 8) {
                $this->pushErrorMessage('El nombre del archivo AN no cumple especificaciones para el nombre del archivo.');
            }
        }

        $reps = Sedeproveedore::where('Codigo', $this->CT['content'][0][0])->first();
        if ($reps) {
            $cargueReps = PaqRip::where('Reps_id', $reps->id)->whereIn('estado_id', [53, 55, 54, 56, 57])->first();
            if ($cargueReps) {
                if ($cargueReps->Nombre != substr($this->CT['fileName'], 2)) {
                    $this->pushErrorMessage('El prestador tiene un cargue pendiente por definir.');
                }
            }

            $packages = PaqRip::where('Nombre', substr($this->CT['fileName'], 2))->where('Reps_id', $reps->id)->where('mes', date('m'))->where('anio', date('Y'))->whereIn('estado_id', [7, 4])->first();
            if ($packages) {
                $this->pushErrorMessage('El codigo del paquete "' . substr($this->CT['fileName'], 2) . '" ya esta radicado con el mismo prestador.');
            }

            $paqueteProceso = PaqRip::where("Nombre", substr($this->CT['fileName'], 2))->where('Reps_id', $reps->id)->where('mes', date('m'))->where('anio', date('Y'))->whereIn('estado_id', [53, 55])->first();
            if ($paqueteProceso) {
                $this->pushErrorMessage('El codigo del paquete "' . substr($this->CT['fileName'], 2) . '" esta en un proceso de validacion');
            }

            $paqueteError = PaqRip::where("Nombre", substr($this->CT['fileName'], 2))->where('Reps_id', $reps->id)->where('mes', date('m'))->where('anio', date('Y'))->where('estado_id', 57)->first();
            if ($paqueteError) {
                $this->pushErrorMessage('El sistema encontro un error inesperado en la validación, comuníquese con el area de desarrollo para su solucion');
            }
        } else {
            $this->pushErrorMessage('El prestador del Archivo CT no registra en la base de datos.');
        }
    }

    private function pushErrorMessage($msg)
    {
        $this->errorMessages[] = ['linea' => '', 'tipo_archivo' => '', 'mensaje' => $msg];
    }

    public function enProcesoValidacion()
    {
        return response()->json(
            PaqRip::where('user_id', auth()->user()->id)
                ->whereIn('estado_id', [53, 54, 55, 56, 57])->get()
        );
    }

    public function descargarErrores($id)
    {
        $errores = collect(RipsErrores::select(
            'archivo',
            'mensaje',
            'lineas'
        )
            ->where('paq_rip_id', $id)
            ->get()->toArray());
        return (new FastExcel($errores))->download('file.xls');
    }

    public function getRadicados(Request $request)
    {
        if (auth()->user()->can('rips.pendientes.verTodos') || auth()->user()->can('dev')) {
            $query = PaqRip::select([
                'paq_rips.id',
                'paq_rips.Nombre',
                DB::raw('CAST(paq_rips.motivo AS NVARCHAR(100)) as motivo'),
                's.Nombre as sede',
                'paq_rips.estado_id',
                'paq_rips.partial',
                'p.Nit',
                's.Codigo_habilitacion',
                'paq_rips.created_at',
                'paq_rips.updated_at',
                DB::raw('SUM(valor_Neto) as valor')
            ])->with(['afs'])
                ->join('sedeproveedores as s', 's.id', 'paq_rips.Reps_id')
                ->join('prestadores as p', 'p.id', 's.Prestador_id')
                ->join('afs as a', 'a.paq_id', 'paq_rips.id')
                ->whereNotNull('paq_rips.estado_id')
                ->where('paq_rips.created_at', '>=', $request->fechaDesde . " 00:00:00.000")
                ->where('paq_rips.created_at', '<=', $request->fechaHasta . " 23:59:00.000");
            if (isset($request->prestadorId)) {
                $sedePrestador = Sedeproveedore::where('Prestador_id', $request->prestadorId)->get()->toArray();
                $query->whereIn('paq_rips.Reps_id', $sedePrestador);
            }
            $query->groupBy(DB::raw('CAST(paq_rips.motivo AS NVARCHAR(100))'), 'paq_rips.updated_at', 'paq_rips.id', 'paq_rips.Nombre', 's.Nombre', 'paq_rips.estado_id', 'paq_rips.partial', 'p.Nit', 's.Codigo_habilitacion', 'paq_rips.created_at')
                ->distinct();
            $rips = $query->get();
        } else {
            $rips = PaqRip::select([
                'paq_rips.id',
                'paq_rips.Nombre',
                DB::raw('CAST(paq_rips.motivo AS NVARCHAR(100)) as motivo'),
                's.Nombre as sede',
                'paq_rips.estado_id',
                'paq_rips.partial',
                'p.Nit',
                's.Codigo_habilitacion',
                'paq_rips.created_at',
                'paq_rips.updated_at',
                DB::raw('SUM(valor_Neto) as valor')
            ])->with(['afs'])
                ->join('sedeproveedores as s', 's.id', 'paq_rips.Reps_id')
                ->join('prestadores as p', 'p.id', 's.Prestador_id')
                ->join('afs as a', 'a.paq_id', 'paq_rips.id')
                ->where('paq_rips.User_id', auth()->user()->id)
                ->where('paq_rips.created_at', '>=', $request->fechaDesde)
                ->where('paq_rips.created_at', '<=', $request->fechaHasta)
                ->whereNotNull('paq_rips.estado_id')
                ->groupBy(DB::raw('CAST(paq_rips.motivo AS NVARCHAR(100))'), 'paq_rips.updated_at', 'paq_rips.id', 'paq_rips.Nombre', 's.Nombre', 'paq_rips.estado_id', 'paq_rips.partial', 'p.Nit', 's.Codigo_habilitacion', 'paq_rips.created_at')
                ->distinct()
                ->get();
        }
        return response()->json($rips);
    }


    public function aceptarRips($id)
    {
        PaqRip::where('id', $id)
            ->update(['estado_id' => 7]);
        return response()->json(['message' => 'RIPS aceptado con exito']);
    }

    public function rechazarRips($id, Request $request)
    {
        PaqRip::where('id', $id)
            ->update(['estado_id' => 6, 'motivo' => $request->motivo]);
        return response()->json(['message' => 'RIPS rechazado con exito']);
    }

    public function reporteRips(Request $request)
    {
        $appointments = Collect(DB::select("exec dbo.RIPS ?,?,?", [$request->startDate, $request->finishDate, $request->financiero]));
        $array = json_decode($appointments, true);
        return (new FastExcel($array))->download('file.xls');
    }

    public function ripsHorus(Request $request)
    {
        $appointments = Collect(DB::select("exec dbo.RipsHorus ?,?,?", [$request->archivo12, $request->startDate1, $request->finishDate1]));
        $array = json_decode($appointments, true);
        return (new FastExcel($array))->download('file.xls');
    }

    public function consolidadoRips(Request $request)
    {
        set_time_limit(300);
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '-1');
        if ($request->archivos1 === 'CT') {
            $archivos = ['AF', 'AT', 'AC', 'AP', 'AU', 'AN', 'AM', 'AH', 'US'];
            $txt = "";
            foreach ($archivos as $archivo) {
                $consulta = json_decode(Collect(DB::select("exec dbo.Consolidado ?,?,?,?", [$archivo, $request->fechainicio . ' 00:00:00.000', $request->fechafin . ' 23:59:00.000', $request->consolidado])), true);
                $reps = array_unique(array_column($consulta, 'prestador'));
                if (count($consulta) > 0) {
                    $txt .= implode("|", $reps) . "," . date("d/m/Y", strtotime($request->fechainicio)) . "," . $archivo . date("mY", strtotime($request->fechainicio)) . "," . count($consulta) . "\r" . PHP_EOL;
                }
            }
            return Response()->make($txt);
        }

        $appointments = Collect(DB::select("exec dbo.Consolidado ?,?,?,?", [$request->archivos1, $request->fechainicio, $request->fechafin, $request->consolidado]));
        $array = json_decode($appointments, true);
        return (new FastExcel($array))->download('file.xls');
    }

    public function autorizacionPeriodoRips()
    {
        $day = intval(date('d'));
        $settings = Configuracion::find(1);
        $enable = false;
        if (!$settings->excepcion_habilitacion_validador_rips) {
            if ($day >= $settings->dia_inicio_habilitacion_validador_rips && $day <= $settings->dia_final_habilitacion_validador_rips) {
                $enable = true;
            }
        } else {
            $enable = true;
        }

        $result = [
            'enabled' => $enable,
            'ini_day' => $settings->dia_inicio_habilitacion_validador_rips,
            'fin_day' => $settings->dia_final_habilitacion_validador_rips
        ];
        return $result;
    }

    /**
     * Genera un archivo txt para descargar
     * @param Request $request
     * @return File
     * @author David Peláez
     */
    public function descargar(Request $request)
    {
        try {
            /** Cerramos tiempo de memoria y de tiempo */
            ini_set('memory_limit', '-1');
            set_time_limit(3000000);
            /** Se recupera la instancia correspondiente */
            $ripsService = new RipsService();
            $instancia = $ripsService->determinarRips($request->tipo_rips);
            /** Se realiza la consulta */
            $consulta = $instancia->consultar($request);
            /** Retorno de consulta */
            return response()->json($consulta);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function eliminarProcesoValidacion(PaqRip $paqRip)
    {
        try {

            $path = storage_path("app/public/temporalesrips/" . $paqRip->id);
            if (file_exists($path)) {
                foreach (scandir($path) as $file) {
                    if ($file !== '.' && $file !== '..') {
                        unlink($path . '/' . $file);
                    }
                }
                rmdir($path);
            }
            RipsErrores::where('paq_rip_id', $paqRip->id)->delete();
            $paqRip->delete();
            return response()->json(["message" => "Registro eliminado con exito"]);
        } catch (\Exception $e) {
            return response()->json(["message" => "Ocurrio un error al eliminar el registro, por favor comuniquese con soporte para su corrección"], 500);
        }
    }

    public function exportErrors(Request $request)
    {
        $appointments = collect($request->all());
        $array = json_decode($appointments, true);
        return (new FastExcel($array))->download('file.xls');
    }

    public function pendienteRips($id)
    {
        PaqRip::where('id', $id)
            ->update(['estado_id' => 4, 'motivo' => null]);
        return response()->json(['message' => 'RIPS en pendiente']);
    }


    public function guardarRipsJson(Request $request)
    {
//        dd($request->all());
        $request->archivo->getClientOriginalName();
        $json = file_get_contents($request->archivo);
//        json_validate($json);
//        $mensajeError = '';
//        switch (json_last_error()) {
//            case JSON_ERROR_NONE:
////                echo "No errors";
//                $mensajeError = false;
//                break;
//            case JSON_ERROR_DEPTH:
//                //echo "Maximum stack depth exceeded";
//                $mensajeError = 'El archivo JSON supera la cantidad de anidamientos permitidos.';
//                break;
//            case JSON_ERROR_STATE_MISMATCH:
//                //echo "Invalid or malformed JSON";
//                $mensajeError = 'El archivo JSON tiene una estructura no valida (malformado).';
//                break;
//            case JSON_ERROR_CTRL_CHAR:
//                //echo "Control character error";
//                $mensajeError = 'El archivo JSON tiene  un error de codificacion en los datos.';
//                break;
//            case JSON_ERROR_SYNTAX:
//                //echo "Syntax error";
//                $mensajeError = 'El archivo JSON tiene un error de sintaxis en su estructura';
//                break;
//            case JSON_ERROR_UTF8:
//                //echo "Malformed UTF-8 characters";
//                $mensajeError = 'El archivo JSON tiene un error de sintaxis en su estructura';
//                break;
//            default:
////                echo "Unknown error";
//                $mensajeError = 'El archivo JSON genera un error desconocido en el sistema';
//                break;
//        }
//        if($mensajeError){
//            $this->agregarError('Archivo','JSON',0,$mensajeError);
////            return $this->erroresJSON;
//        }

        $json_data = json_decode($json,true);
//        dd($json_data);
        $transaccion = new RipsTransaccion();
        $transaccion->numDocumentoIdObligado = $json_data['numDocumentoIdObligado'];
        $transaccion->numFactura = $json_data['numFactura'];
        $transaccion->numNota = $json_data['numNota'];
        $transaccion->tipoNota = $json_data['tipoNota'];
        $transaccion->user_id = auth()->user()->id;
//        $transaccion->entidad_id = 1;
//        $transaccion->prestador_id = Prestadore::where('Nit',$json_data['numDocumentoIdObligado'])->first()->id;
        $transaccion->estado_id = 4;
        $transaccion->save();
//        $transaccion = RipsTransaccion::find(1);
        foreach ($json_data["usuarios"] as $usuario){
            $usuarioRecorrido = $usuario;
            $usuarioRecorrido['rips_transaccion_id'] = $transaccion->id;
            unset($usuarioRecorrido["servicios"]);
            $usuarioGuardado = RipsUsuario::create($usuarioRecorrido);
            ////////////////////////////// CONSULTAS //////////////////////////////
            $consultas = array_map(function ($data) use ($usuarioGuardado) {
                $array = $data;
                $array["rips_usuario_id"] = $usuarioGuardado["id"];
                return $array;
            },$usuario["servicios"]["consultas"]);
            $chunk_size = floor(2000 / count($consultas[0]));
            foreach (array_chunk($consultas, $chunk_size) as $chunk) {
                RipsConsulta::insert($chunk);
            }
            ////////////////////////////// FIN CONSULTAS //////////////////////////////
            ////////////////////////////// PROCEDIMIENTOS //////////////////////////////
            $procedimientos = array_map(function ($data) use ($usuarioGuardado) {
                $array = $data;
                $array["rips_usuario_id"] = $usuarioGuardado["id"];
                return $array;
            },$usuario["servicios"]["procedimientos"]);
            $chunk_size = floor(2000 / count($procedimientos[0]));
            foreach (array_chunk($procedimientos, $chunk_size) as $chunk) {
                RipsProcedimiento::insert($chunk);
            }
            ////////////////////////////// FIN PROCEDIMIENTOS //////////////////////////////
            ////////////////////////////// URGENCIAS //////////////////////////////
            $urgencias = array_map(function ($data) use ($usuarioGuardado) {
                $array = $data;
                $array["rips_usuario_id"] = $usuarioGuardado["id"];
                return $array;
            },$usuario["servicios"]["urgencias"]);
            $chunk_size = floor(2000 / count($urgencias[0]));
            foreach (array_chunk($urgencias, $chunk_size) as $chunk) {
                RipsUrgencia::insert($chunk);
            }
            ////////////////////////////// FIN URGENCIAS ////////////////////////////
            ////////////////////////////// HOSPITALIZACION //////////////////////////////
            $hospitalizaciones = array_map(function ($data) use ($usuarioGuardado) {
                $array = $data;
                $array["rips_usuario_id"] = $usuarioGuardado["id"];
                return $array;
            },$usuario["servicios"]["hospitalizacion"]);
            $chunk_size = floor(2000 / count($hospitalizaciones[0]));
            foreach (array_chunk($hospitalizaciones, $chunk_size) as $chunk) {
                RipsHospitalizacion::insert($chunk);
            }
            ////////////////////////////// FIN HOSPITALIZACION //////////////////////////////
            ////////////////////////////// RECIEN NACIDOS //////////////////////////////
            $recienNacidos = array_map(function ($data) use ($usuarioGuardado) {
                $array = $data;
                $array["rips_usuario_id"] = $usuarioGuardado["id"];
                return $array;
            },$usuario["servicios"]["recienNacidos"]);
            $chunk_size = floor(2000 / count($recienNacidos[0]));
            foreach (array_chunk($recienNacidos, $chunk_size) as $chunk) {
                RipsRecienNacido::insert($chunk);
            }
            ////////////////////////////// FIN RECIEN NACIDOS //////////////////////////////
            ////////////////////////////// MEDICAMENTOS //////////////////////////////
            $medicamentos = array_map(function ($data) use ($usuarioGuardado) {
                $array = $data;
                $array["rips_usuario_id"] = $usuarioGuardado["id"];
                return $array;
            },$usuario["servicios"]["medicamentos"]);
            $chunk_size = floor(2000 / count($medicamentos[0]));
            foreach (array_chunk($medicamentos, $chunk_size) as $chunk) {
                RipsMedicamento::insert($chunk);
            }
            ////////////////////////////// FIN MEDICAMENTOS //////////////////////////////
            ////////////////////////////// OTROS SERVICIOS //////////////////////////////
            $otroservicios = array_map(function ($data) use ($usuarioGuardado) {
                $array = $data;
                $array["rips_usuario_id"] = $usuarioGuardado["id"];
                return $array;
            },$usuario["servicios"]["otrosServicios"]);
            $chunk_size = floor(2000 / count($otroservicios[0]));
            foreach (array_chunk($otroservicios, $chunk_size) as $chunk) {
                RipsOtrosServicio::insert($chunk);
            }
            ////////////////////////////// FIN OTROS SERVICIOS //////////////////////////////

            return $consultas;
        }
        return response()->json("Registro Exitoso");
    }

    public function ripsJsonCargados()
    {
        return response()->json(RipsTransaccion::all());
    }

    public function estadoRipJson($id,$estado)
    {
        RipsTransaccion::where('id',$id)
            ->update(['estado_id' => $estado]);
        return response()->json("Registro Actualizado");
    }

    public function consolidadoRipsJson(Request $request)
    {
        set_time_limit(300);
        ini_set('max_execution_time',300);
        ini_set('memory_limit','-1');
        $appointments = Collect(DB::select("exec dbo.ConsolidadoJson ?,?,?,?", [$request->archivos1,$request->fechainicio.' 00:00:00.000',$request->fechafin.' 23:59:00.000',$request->consolidado]));
        $array = json_decode($appointments, true);
        return (new FastExcel($array))->download('file.xls');
    }

    public function conversor($tipo,Request $request)
    {
        switch (intval($tipo)){
            case 1:
                set_time_limit(900);
                ini_set('max_execution_time', '-1');
                ini_set('memory_limit', '-1');
                ini_set('upload_max_filesize ', '500M');
                ini_set('post_max_size ', '500M');
                $arrFinal = [];
                $archivos = [
                    'AC' => 'consultas',
                    'AP' => 'procedimientos',
                    'AM' => 'medicamentos',
                    'AT' => 'otrosServicios',
                    'AU' => 'urgencias',
                    'AH' => 'hospitalizacion',
                    'AN' => 'recienNacidos'];


                $inputFileType = 'Xlsx';
                $inputFileName = $request["archivo"];

                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                $reader->setReadDataOnly(true);
                $worksheetData = $reader->listWorksheetInfo($inputFileName);

                $reader->setLoadSheetsOnly('AF');
                $spreadsheet = $reader->load($inputFileName);
                $worksheet = $spreadsheet->getActiveSheet();
                $af = $worksheet->toArray();
                $arrFinal = [
                    $af[0][0] => $af[1][0],
                    $af[0][1] => $af[1][1],
                    $af[0][2] => strtoupper($af[1][2]) === "NULL" ? null : $af[1][2],
                    $af[0][3] => strtoupper($af[1][3]) === "NULL" ? null : $af[1][3],
                    'usuarios' => []
                ];
                $reader->setLoadSheetsOnly('US');
                $spreadsheet = $reader->load($inputFileName);
                $worksheet = $spreadsheet->getActiveSheet();
                $us = $worksheet->toArray();
                $usCabeceras = $us[0];
                unset($us[0]);
                foreach ($us as $usuarios) {
                    $arrUs = [];
                    for ($i = 0; $i < count($usCabeceras); $i++) {
                        $arrUs[$usCabeceras[$i]] = $usuarios[$i];
                    }
                    $arrUs['servicios'] = [
                        'consultas' => [],
                        'procedimientos' => [],
                        'medicamentos' => [],
                        'otrosServicios' => [],
                        'urgencias' => [],
                        'hospitalizacion' => [],
                        'recienNacidos' => []
                    ];
                    $arrFinal['usuarios'][] = $arrUs;
                }

                foreach ($worksheetData as $worksheet) {
                    if (strtoupper($worksheet['worksheetName']) !== 'US' && strtoupper($worksheet['worksheetName']) !== 'AF') {
                        $sheetName = $worksheet['worksheetName'];
                        $reader->setLoadSheetsOnly($sheetName);
                        $spreadsheet = $reader->load($inputFileName);
                        $worksheet = $spreadsheet->getActiveSheet();
                        $registros = $worksheet->toArray();
                        $cabeceras = $registros[0];
                        unset($registros[0]);
                        foreach ($registros as $registro) {
                            $arrRegistro = [];
                            for ($i = 0; $i < count($cabeceras); $i++) {
                                $arrRegistro[$cabeceras[$i]] = $registro[$i];
                            }

                            $keyUsuario = array_search($arrRegistro['numDocumentoIdentificacion'], array_column($arrFinal['usuarios'], 'numDocumentoIdentificacion'));
                            if ($keyUsuario !== false) {
                                $arrFinal['usuarios'][$keyUsuario]['servicios'][$archivos[strtoupper($sheetName)]][] = $arrRegistro;
                            }
                        }
                    }
                }

                return response()->json($arrFinal);
            case 2:
                $json = file_get_contents($request["archivo"]);
                $json_data = json_decode($json,true);
                $arrFinal = [
                    'af' => [],
                    'us' => [],
                    'ac' => [],
                    'at' => [],
                    'an' => [],
                    'ap' => [],
                    'am' => [],
                    'au' => [],
                    'ah' => []
                ];
                foreach ($json_data as $keyTransaccion => $valorTransaccion) {
                    if($keyTransaccion !== 'usuarios'){
                        $arrFinal['af'][0][$keyTransaccion] = $valorTransaccion;
                    }
                    if($keyTransaccion === 'usuarios'){
                        foreach ($valorTransaccion as $usuario){
                            $us = $usuario;
                            $servicios = $usuario['servicios'];
                            unset($us['servicios']);
                            $arrFinal['us'][] = $us;
                            $arrFinal['ac'] = array_merge($arrFinal['ac'],$servicios['consultas']);
                            $arrFinal['at'] = array_merge($arrFinal['at'],$servicios['otrosServicios']);
                            $arrFinal['an'] = array_merge($arrFinal['an'],$servicios['recienNacidos']);
                            $arrFinal['ap'] = array_merge($arrFinal['ap'],$servicios['procedimientos']);
                            $arrFinal['am'] = array_merge($arrFinal['am'],$servicios['medicamentos']);
                            $arrFinal['au'] = array_merge($arrFinal['au'],$servicios['urgencias']);
                            $arrFinal['ah'] = array_merge($arrFinal['ah'],$servicios['hospitalizacion']);
                        }
                    }

                }
                $sheets = new SheetCollection([
                    'AF' => $arrFinal['af'],
                    'AC' => $arrFinal['ac'],
                    'AT' => $arrFinal['at'],
                    'AN' => $arrFinal['an'],
                    'AP' => $arrFinal['ap'],
                    'AM' => $arrFinal['am'],
                    'AU' => $arrFinal['au'],
                    'AH' => $arrFinal['ah'],
                    'US' => $arrFinal['us'],
                ]);
                return (new FastExcel($sheets))->download('file.xls');
        }
    }
}
