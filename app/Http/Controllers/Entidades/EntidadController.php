<?php

namespace App\Http\Controllers\Entidades;

use App\Http\Controllers\PDF\PDFController;
use App\Modelos\citapaciente;
use App\Modelos\Cobro;
use App\Modelos\Codesumi;
use App\Modelos\Conducta;
use App\Modelos\Configuracion;
use App\Modelos\CumEntidade;
use App\Modelos\Cup;
use App\Modelos\CupEntidade;
use App\Modelos\Cupfamilia;
use App\Modelos\Cuporden;
use App\Modelos\Detaarticulorden;
use App\Modelos\DetalleCobro;
use App\Modelos\Entidade;
use App\Modelos\Orden;
use App\Modelos\Paciente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modelos\Estado;
use Illuminate\Support\Facades\DB;
use function foo\func;

class EntidadController extends Controller
{
    public function validarMedicamento($citaPacienteId)
    {
        $citaPaciente = citapaciente::find($citaPacienteId);
        $paciente = Paciente::find($citaPaciente->Paciente_id);
        $ordenes = Orden::where('Cita_id', $citaPaciente->id)->where('Tiporden_id', 3)->get();
        switch ($paciente->entidad_id){
            case 2:
                if (isset($ordenes)) {
                    foreach ($ordenes as $orden) {
                        $valorCobro = 0;
                        if ($paciente->tipo_categoria == 'CONTRIBUTIVO') {
                            $medicamentos = Codesumi::select(['Codesumis.id as codigo', 'da.id as detalle', 'da.Orden_id'])
                                ->join('detaarticulordens as da', 'Codesumis.id', 'da.Codesumi_id')
                                ->where('da.Orden_id', $orden->id)->get();
                            foreach ($medicamentos as $medicamento) {
                                $entidad = CumEntidade::where('codesumi_id', $medicamento->codigo)
                                    ->where('entidad_id', 2)->first();
                                if ($entidad) {
                                    if ($entidad->pbs) {
                                        if ($citaPaciente->Finalidad == "No aplica") {
                                            $configuracion = Configuracion::find(1);
                                            switch ($paciente->nivel) {
                                                case 1:
                                                    $valorCobro = $configuracion->cuota_moderadora_nivel_1;
                                                    break;
                                                case 2:
                                                    $valorCobro = $configuracion->cuota_moderadora_nivel_2;
                                                    break;
                                                case 3:
                                                    $valorCobro = $configuracion->cuota_moderadora_nivel_3;
                                                    break;
                                            }
                                        }
                                    } else {
                                        Detaarticulorden::where('id', $medicamento->detalle)->update(["estado_id" => 36]);
                                    }
                                } else {
                                    Detaarticulorden::where('id', $medicamento->detalle)->update(["estado_id" => 37]);
                                }
                            }
                        }
                        $valorCobroCita = $this->calcularCobroCita($citaPaciente, $paciente);
                        $cobros = Cobro::where('paciente_id', $citaPaciente->Paciente_id)->where('orden_id', $orden->id)->first();
                        if (isset($cobros)) {
                            if ($cobros->estado_id == 4 ) {
                                $cobro = Cobro::where('paciente_id', $citaPaciente->Paciente_id)->where('orden_id', $orden->id)
                                    ->update([
                                        'estado_id' => 7,
                                        'valor' => $valorCobro,
                                        'tipo_cobro' => ($valorCobroCita > 0 ? null : 'n/a'),
                                        'valor_cita' => $valorCobroCita
                                    ]);
                            }else{
                                $cobro = Cobro::where('paciente_id', $citaPaciente->Paciente_id)->where('orden_id', $orden->id)
                                    ->update([
                                        'valor_cita' => round($valorCobroCita)
                                    ]);
                            }
                        } else {
                            $cobro = Cobro::create([
                                'paciente_id' => $citaPaciente->Paciente_id,
                                'orden_id' => $orden->id,
                                'estado_id' => 7,
                                'valor' => $valorCobro,
                                'tipo_cobro' => ($valorCobroCita > 0 ? null : 'n/a'),
                                'fecha_cobro' => ($valorCobroCita > 0 ? null : date('Y-m-d')),
                                'valor_cita' => $valorCobroCita
                            ]);
                        }
                    }
                }
                break;
            case 3:
                    if (isset($ordenes)) {
                        foreach ($ordenes as $orden) {
                            $valorCobro = 0;
                            $detalles = [];
                            $medicamentos = Codesumi::select(['Codesumis.id as codigo', 'da.id as detalle', 'da.Orden_id','Codesumis.pbs'])
                                ->join('detaarticulordens as da', 'Codesumis.id', 'da.Codesumi_id')
                                ->where('da.Orden_id', $orden->id)->get();
                            foreach ($medicamentos as $medicamento) {
                                    if ($medicamento->pbs) {
                                        if($paciente->tipo_categoria === 'POS') {
                                            if ($citaPaciente->Finalidad == "No aplica") {
                                                $configuracion = Configuracion::find(1);
                                                $detalles[] = ['detalleId' => $medicamento->detalle];
                                                switch ($paciente->nivel) {
                                                    case 1:
                                                        $valorCobro = $configuracion->cuota_moderadora_nivel_1;
                                                        break;
                                                    case 2:
                                                        $valorCobro = $configuracion->cuota_moderadora_nivel_2;
                                                        break;
                                                    case 3:
                                                        $valorCobro = $configuracion->cuota_moderadora_nivel_3;
                                                        break;
                                                    }
                                                }
                                            }
                                        }else {
                                        if($paciente->tipo_categoria === 'POS'){
                                            Detaarticulorden::where('id', $medicamento->detalle)->update(["estado_id" => 15,'mipres' => 1]);
                                        }else{
                                            Detaarticulorden::where('id', $medicamento->detalle)->update(["mipres" => 1]);
                                        }
                                    }
                                }
                            if($paciente->tipo_categoria === 'POS') {
                                $cobro = Cobro::where('orden_id', $orden->id)->first();
                                if (!$cobro) {
                                    $cobro = Cobro::create([
                                        'paciente_id' => $citaPaciente->Paciente_id,
                                        'orden_id' => $orden->id,
                                        'estado_id' => ($valorCobro > 0 ? 4 : 7),
                                        'valor' => $valorCobro,
                                        'tipo_cobro' => ($valorCobro > 0 ? null : 'n/a'),
                                        'fecha_cobro' => ($valorCobro > 0 ? null : date('Y-m-d')),
                                        'valor_cita' => 0
                                    ]);
                                }else{
                                    $cobro->update([
                                        'paciente_id' => $citaPaciente->Paciente_id,
                                        'orden_id' => $orden->id,
                                        'estado_id' => ($valorCobro > 0 ? 4 : 7),
                                        'valor' => $valorCobro,
                                        'tipo_cobro' => ($valorCobro > 0 ? null : 'n/a'),
                                        'fecha_cobro' => ($valorCobro > 0 ? null : date('Y-m-d')),
                                        'valor_cita' => 0
                                    ]);
                                }
                                foreach ($detalles as $detalle) {
                                    $detalleCobro = DetalleCobro::where('cobro_id', $cobro->id)->where('medicamento_orden_id', $detalle["detalleId"])->first();
                                    if (!$detalleCobro) {
                                        DetalleCobro::create(['cobro_id' => $cobro->id, 'medicamento_orden_id' => $detalle["detalleId"]]);
                                    }
                                }
                            }
                            }
                        }
                break;
        }
    }

    public function validarServicio($citaPacienteId)
    {
        $citaPaciente = citapaciente::find($citaPacienteId);
        $paciente = Paciente::find($citaPaciente->Paciente_id);
        $ordenes = Orden::where('Cita_id', $citaPaciente->id)->where('Tiporden_id', 4)->get();
        switch ($paciente->entidad_id){
            case 2:
                $ordenes = Orden::where('Cita_id', $citaPaciente->id)->where('Tiporden_id', 4)->get();
                if (isset($ordenes)) {
                    foreach ($ordenes as $orden) {
                        $arrCobros = $this->calcularOrdenamiento($orden->id, $citaPaciente);
                        $valorCobro = 0;
                        foreach ($arrCobros["hojas"] as $arrCobro) {
                            if ($arrCobro["requiere_cobro"]) {
                                $valorCobro = $valorCobro + $arrCobro["valor_cobro"];
                            }
                        }
                        $valorCobroCita = $this->calcularCobroCita($citaPaciente, $paciente);
                        $cobros = Cobro::where('paciente_id', $citaPaciente->Paciente_id)->where('orden_id', $orden->id)->first();
                        if (isset($cobros)) {
                            if ($cobros->tipo_cobro == 'n/a' || is_null($cobros->tipo_cobro)) {
                                $cobro = Cobro::where('paciente_id', $citaPaciente->Paciente_id)->where('orden_id', $orden->id)
                                    ->update([
                                        'estado_id' => ($valorCobro > 0 ? 4 : 7),
                                        'valor' => round($valorCobro),
                                        'tipo_cobro' => ($valorCobro > 0 ? null : 'n/a'),
                                        'valor_cita' => round($valorCobroCita)
                                    ]);
                            }else{
                                $cobro = Cobro::where('paciente_id', $citaPaciente->Paciente_id)->where('orden_id', $orden->id)
                                    ->update([
                                        'valor_cita' => round($valorCobroCita)
                                    ]);
                            }
                        } else {
                            $cobro = Cobro::create([
                                'paciente_id' => $citaPaciente->Paciente_id,
                                'orden_id' => $orden->id,
                                'estado_id' => ($valorCobro > 0 ? 4 : 7),
                                'valor' => round($valorCobro),
                                'tipo_cobro' => ($valorCobro > 0 ? null : 'n/a'),
                                'fecha_cobro' => ($valorCobro > 0 ? null : date('Y-m-d')),
                                'valor_cita' => round($valorCobroCita)
                            ]);
                        }
                    }
                }
                break;
            case 3:
                if($paciente->tipo_categoria === 'POS'){
                    if (isset($ordenes)) {
                        foreach ($ordenes as $orden) {
                            $arrCobros = $this->calcularOrdenamiento($orden->id, $citaPaciente);
                            $valorCobro = 0;
                            foreach ($arrCobros["hojas"] as $arrCobro) {
                                if ($arrCobro["requiere_cobro"]) {
                                    $valorCobro = $valorCobro + $arrCobro["valor_cobro"];
                                }
                            }
                            $cobros = Cobro::where('paciente_id', $citaPaciente->Paciente_id)->where('orden_id', $orden->id)->first();
                            if ($cobros) {
                                if ($cobros->tipo_cobro == 'n/a' || is_null($cobros->tipo_cobro)) {
                                    $cobro = Cobro::where('paciente_id', $citaPaciente->Paciente_id)->where('orden_id', $orden->id)
                                        ->update([
                                            'estado_id' => ($valorCobro > 0 ? 4 : 7),
                                            'valor' => round($valorCobro),
                                            'tipo_cobro' => ($valorCobro > 0 ? null : 'n/a'),
                                            'valor_cita' => 0
                                        ]);
                                }
                            } else {
                                $cobro = Cobro::create([
                                    'paciente_id' => $citaPaciente->Paciente_id,
                                    'orden_id' => $orden->id,
                                    'estado_id' => ($valorCobro > 0 ? 4 : 7),
                                    'valor' => round($valorCobro),
                                    'tipo_cobro' => ($valorCobro > 0 ? null : 'n/a'),
                                    'fecha_cobro' => ($valorCobro > 0 ? null : date('Y-m-d')),
                                    'valor_cita' => 0
                                ]);
                            }
                        }
                    }
                }
                break;
        }
    }

    public function validarInterConsulta($citaPacienteId)
    {
        $citaPaciente = citapaciente::find($citaPacienteId);
        $paciente = Paciente::find($citaPaciente->Paciente_id);
        if ($paciente->entidad_id == 2) {
            $ordenes = Orden::where('Cita_id', $citaPaciente->id)->where('Tiporden_id', 2)->get();
            if (isset($ordenes)) {
                foreach ($ordenes as $orden) {
                    $arrCobros = $this->calcularOrdenamiento($orden->id, $citaPaciente);
                    $valorCobro = 0;
                    foreach ($arrCobros["hojas"] as $arrCobro) {
                        if ($arrCobro["requiere_cobro"]) {
                            $valorCobro = $valorCobro + $arrCobro["valor_cobro"];
                        }
                    }
                    $valorCobroCita = $this->calcularCobroCita($citaPaciente, $paciente);
                    $cobros = Cobro::where('paciente_id', $citaPaciente->Paciente_id)->where('orden_id', $orden->id)->first();
                    if (isset($cobros)) {
                        if ($cobros->tipo_cobro == 'n/a' || is_null($cobros->tipo_cobro)) {
                            $cobro = Cobro::where('paciente_id', $citaPaciente->Paciente_id)->where('orden_id', $orden->id)
                                ->update([
                                    'estado_id' => ($valorCobro > 0 ? 4 : 7),
                                    'valor' => round($valorCobro),
                                    'tipo_cobro' => ($valorCobro > 0 ? null : 'n/a'),
                                    'valor_cita' => round($valorCobroCita)
                                ]);
                        }else{
                            $cobro = Cobro::where('paciente_id', $citaPaciente->Paciente_id)->where('orden_id', $orden->id)
                                ->update([
                                    'valor_cita' => round($valorCobroCita)
                                ]);
                        }
                    } else {
                        $cobro = Cobro::create([
                            'paciente_id' => $citaPaciente->Paciente_id,
                            'orden_id' => $orden->id,
                            'estado_id' => ($valorCobro > 0 ? 4 : 7),
                            'valor' => round($valorCobro),
                            'tipo_cobro' => ($valorCobro > 0 ? null : 'n/a'),
                            'fecha_cobro' => ($valorCobro > 0 ? null : date('Y-m-d')),
                            'valor_cita' => round($valorCobroCita)
                        ]);
                    }
                }
            }
        }
    }

    public function validarValorCup($identificacion, $codigoEntidad, $codigoCups, $finalidad)
    {
        ini_set('max_execution_time', -1);
        $mensaje = "Sin datos";
        $valor = null;
        $valorCobro = 0;
        $valorCopago = 0;
        $valorCuotaModeradora = 0;
        $configuracion = Configuracion::find(1);
        $paciente = Paciente::where('Num_Doc', $identificacion)->first();
        $edadCumplidad = intval($paciente->Edad_Cumplida);
        $acumulado = $this->acumuladoAnio($paciente->id);
        $cup = Cup::where('Codigo', $codigoCups)->first();
        if($cup){
        $entidad = CupEntidade::select(['tarifa','copago as pgp','cup_id'])->where('cup_id', $cup->id)->where('entidad_id', $codigoEntidad)->first();
        if($entidad){
        if ($paciente->tipo_categoria == 'CONTRIBUTIVO') {
            if ($entidad->pgp) {
                if ($finalidad == 10) {
                    if ($paciente->Tipo_Afiliado == "BENEFICIARIO") {
                        switch ($paciente->nivel) {
                            case 1:
                                $valorCalculado = ($entidad->tarifa * $configuracion->porcentaje_copago_nivel_1) / 100;
                                if ($valorCalculado > $configuracion->valor_tope_copago_nivel_1_servicio) {
                                    $valorCalculado = $configuracion->valor_tope_copago_nivel_1_servicio;
                                }
                                if ($acumulado >= $configuracion->valor_tope_copago_nivel_1_anio) {
                                    $valorCalculado = 0;
                                } else {
                                    $faltante = $configuracion->valor_tope_copago_nivel_1_anio - $acumulado;
                                    if ($faltante < $valorCalculado) {
                                        $valorCalculado = $faltante;
                                    }
                                }
                                if ($valorCalculado > $valorCopago) {
                                    $valorCopago = $valorCalculado;
                                }
                                break;
                            case 2:
                                $valorCalculado = ($entidad->tarifa * $configuracion->porcentaje_copago_nivel_2) / 100;
                                if ($valorCalculado > $configuracion->valor_tope_copago_nivel_2_servicio) {
                                    $valorCalculado = $configuracion->valor_tope_copago_nivel_2_servicio;
                                }
                                if ($acumulado >= $configuracion->valor_tope_copago_nivel_2_anio) {
                                    $valorCalculado = 0;
                                } else {
                                    $faltante = $configuracion->valor_tope_copago_nivel_2_anio - $acumulado;
                                    if ($faltante < $valorCalculado) {
                                        $valorCalculado = $faltante;
                                    }
                                }
                                if ($valorCalculado > $valorCopago) {
                                    $valorCopago = $valorCalculado;
                                }
                                break;
                            case 3:
                                $valorCalculado = ($entidad->tarifa * $configuracion->porcentaje_copago_nivel_3) / 100;
                                if ($valorCalculado > $configuracion->valor_tope_copago_nivel_3_servicio) {
                                    $valorCalculado = $configuracion->valor_tope_copago_nivel_3_servicio;
                                }
                                if ($acumulado >= $configuracion->valor_tope_copago_nivel_3_anio) {
                                    $valorCalculado = 0;
                                } else {
                                    $faltante = $configuracion->valor_tope_copago_nivel_3_anio - $acumulado;
                                    if ($faltante < $valorCalculado) {
                                        $valorCalculado = $faltante;
                                    }
                                }
                                if ($valorCalculado > $valorCopago) {
                                    $valorCopago = $valorCalculado;
                                }
                                break;
                        }
                    } elseif ($paciente->Tipo_Afiliado == "COTIZANTE") {
                        switch ($paciente->nivel) {
                            case 1:
                                if ($configuracion->cuota_moderadora_nivel_1 > $valorCuotaModeradora) {
                                    $valorCuotaModeradora = $configuracion->cuota_moderadora_nivel_1;
                                }
                                break;
                            case 2:
                                if ($configuracion->cuota_moderadora_nivel_2 > $valorCuotaModeradora) {
                                    $valorCuotaModeradora = $configuracion->cuota_moderadora_nivel_2;
                                }
                                break;
                            case 3:
                                if ($configuracion->cuota_moderadora_nivel_3 > $valorCuotaModeradora) {
                                    $valorCuotaModeradora = $configuracion->cuota_moderadora_nivel_3;
                                }
                                break;
                        }
                    }
                }
            } else {
                if ($finalidad == 10) {
                    switch ($paciente->nivel) {
                        case 1:
                            if ($configuracion->cuota_moderadora_nivel_1 > $valorCuotaModeradora) {
                                $valorCuotaModeradora = $configuracion->cuota_moderadora_nivel_1;
                            }
                            break;
                        case 2:
                            if ($configuracion->cuota_moderadora_nivel_2 > $valorCuotaModeradora) {
                                $valorCuotaModeradora = $configuracion->cuota_moderadora_nivel_2;
                            }
                            break;
                        case 3:
                            if ($configuracion->cuota_moderadora_nivel_3 > $valorCuotaModeradora) {
                                $valorCuotaModeradora = $configuracion->cuota_moderadora_nivel_3;
                            }
                            break;
                    }
                }
            }
        } elseif ($paciente->tipo_categoria == 'SUBSIDIADO') {
            if ($entidad->pgp) {
                if ($finalidad == 10) {
                    switch ($paciente->nivel) {
                        case 2:
                            if ($edadCumplidad > 1) {
                                $valorCalculado = ($entidad->tarifa * $configuracion->porcentaje_copago_subsidiado) / 100;
                                if ($valorCalculado > $configuracion->valor_tope_copago_subsidiado_servicio) {
                                    $valorCalculado = $configuracion->valor_tope_copago_subsidiado_servicio;
                                }
                                if ($acumulado >= $configuracion->valor_tope_copago_subsidiado_anio) {
                                    $valorCalculado = 0;
                                } else {
                                    $faltante = $configuracion->valor_tope_copago_subsidiado_anio - $acumulado;
                                    if ($faltante < $valorCalculado) {
                                        $valorCalculado = $faltante;
                                    }
                                }
                                if ($valorCalculado > $valorCopago) {
                                    $valorCopago = $valorCalculado;
                                }
                            }
                            break;
                        case 3:
                            if ($edadCumplidad > 1) {
                                $valorCalculado = ($entidad->tarifa * $configuracion->porcentaje_copago_subsidiado) / 100;
                                if ($valorCalculado > $configuracion->valor_tope_copago_subsidiado_servicio) {
                                    $valorCalculado = $configuracion->valor_tope_copago_subsidiado_servicio;
                                }
                                if ($acumulado >= $configuracion->valor_tope_copago_subsidiado_anio) {
                                    $valorCalculado = 0;
                                } else {
                                    $faltante = $configuracion->valor_tope_copago_subsidiado_anio - $acumulado;
                                    if ($faltante < $valorCalculado) {
                                        $valorCalculado = $faltante;
                                    }
                                }
                                if ($valorCalculado > $valorCopago) {
                                    $valorCopago = $valorCalculado;
                                }
                            }
                            break;
                    }
                }
            }
        }
            $valorCobro = $valorCopago + $valorCuotaModeradora;
            $mensaje = "calculo exitoso";
            $valor =  intval($valorCobro);
        }else{
            $mensaje = 'Codigo CUP no hace parte de la red contratada';
            }
        }else{
            $mensaje = 'Codigo CUP no registra en la base de datos';
        }
        $data = [
            'mensaje' => $mensaje,
            'valor' => $valor
        ];
        return $data;
    }

    public function calcularCobroCita($citaPaciente, $paciente)
    {
        $configuracion = Configuracion::find(1);
        $valorCobro = 0;
        if ($citaPaciente->Finalidad == "No aplica") {
            if($paciente->tipo_categoria == 'CONTRIBUTIVO'){
                switch ($paciente->nivel) {
                    case 1:
                        $valorCobro = $configuracion->cuota_moderadora_nivel_1;
                        break;
                    case 2:
                        $valorCobro = $configuracion->cuota_moderadora_nivel_2;
                        break;
                    case 3:
                        $valorCobro = $configuracion->cuota_moderadora_nivel_3;
                        break;
                }
            }
        }
        if ($citaPaciente->cobro_estado_id == 4 || is_null($citaPaciente->cobro_estado_id)) {
            $citaPaciente->valor_cita = $valorCobro;
            $citaPaciente->cobro_estado_id = ($valorCobro > 0 ? 4 : 7);
            $citaPaciente->save();
        }
        return $valorCobro;
    }

    public function acumuladoAnio($paciente)
    {
        $hoy = date('Y');
        $suma = Cobro::select([DB::raw('SUM(valor) as total')])
            ->where("paciente_id", $paciente)
            ->where("estado_id", 7)
            ->whereBetween('updated_at', [$hoy . '-01-01 00:00:00', $hoy . '-12-31 23:59:59'])
            ->first();
        return is_numeric(intval($suma->total)) ? intval($suma->total) : 0;
    }

    public function calcularOrdenamiento($idOrden, $citaPaciente)
    {
        $paciente = Paciente::find($citaPaciente->Paciente_id);
        $configuracion = Configuracion::find(1);
        $cupCobros = [];
        $arrPgp = [];
        $arrNoPgp = [];
        $arrAnexos = [];
        $arrHojas = [];
        $arrServiciosHojas = [];
        $acumulado = $this->acumuladoAnio($citaPaciente->Paciente_id);
        $servicios = Cup::select(['cups.id as codigo', 'cu.id as detalle', 'cu.Orden_id'])
            ->join('cupordens as cu', 'cups.id', 'cu.cup_id')
            ->where('cu.Orden_id', $idOrden)->get();
        foreach ($servicios as $servicio) {
            $entidad = CupEntidade::select(['tarifa','copago as pgp','cup_id'])->where('cup_id', $servicio->codigo)->where('entidad_id', 2)->first();
            if ($entidad) {
                if ($entidad->pgp) {
                    $arrPgp[] = $entidad->toArray();
                } else {
                    $arrNoPgp[] = $entidad->toArray();
                }
            } else {
                Cuporden::where('id', $servicio->detalle)->update(["estado_id" => 15,'anexo3' => 1]);

                $detalleOrdensServicios = Cuporden::select([
                    "cupordens.Cup_id as id",
                    "cupordens.Cantidad as cantidad",
                    "cupordens.Observacionorden as observacion",
                    "c.Codigo as codigo",
                    "c.Nombre as nombre",
                    "cupordens.estado_id",
                    "cupordens.Orden_id",
                    "cupordens.Ubicacion_id",
                    "cupordens.Estado_id"
                ])->join("cups as c", "c.id", "cupordens.Cup_id")
                    ->where("cupordens.id", $servicio->detalle)->first();
                $arrAnexos[] = $detalleOrdensServicios;
            }
        }
        $direccionamientos = DB::select("exec dbo.CupsDireccionamientoEntidad " . intval($idOrden));
        foreach ($direccionamientos as $direccionamiento) {
            $key = array_search(intval($direccionamiento->cup_id), array_column($arrNoPgp, 'cup_id'));
            if (is_numeric($key)) {
                $sedeProveedorNoPgp = $direccionamiento->sedeproveedor_id;
                $cupDetalle = Cuporden::where('Orden_id',$idOrden)->where('Cup_id',$direccionamiento->cup_id)->first();
                if($cupDetalle->Ubicacion_id){
                    $sedeProveedorNoPgp =  $cupDetalle->Ubicacion_id;
                }
                $cupFamilia1 = Cupfamilia::select(["f.Descripcion as Nombre"])->join('familias as f','f.id','cupfamilias.Familia_id')->where('Cup_id',$direccionamiento->cup_id)->first();
                $arrHojas[$sedeProveedorNoPgp . "-noPgp"]["requiere_cobro"] = true;
                $arrHojas[$sedeProveedorNoPgp . "-noPgp"]["valor_cobro"] = 0;
                $arrHojas[$sedeProveedorNoPgp . "-noPgp"]["prestador"] = $sedeProveedorNoPgp;
                $arrHojas[$sedeProveedorNoPgp . "-noPgp"]["tipo"][$cupFamilia1->Nombre] = 0;
                $arrServiciosHojas[$sedeProveedorNoPgp . "-noPgp"][$cupFamilia1->Nombre]["cups"][] = $arrNoPgp[$key];
            } else {
                $key2 = array_search(intval($direccionamiento->cup_id), array_column($arrPgp, 'cup_id'));
                if (is_numeric($key2)) {
                    $sedeProveedorPgp = $direccionamiento->sedeproveedor_id;
                    $cupDetalle = Cuporden::where('Orden_id',$idOrden)->where('Cup_id',$direccionamiento->cup_id)->first();
                    if($cupDetalle->Ubicacion_id){
                        $sedeProveedorPgp =  $cupDetalle->Ubicacion_id;
                    }
                    $cupFamilia2 = Cupfamilia::select(["f.Descripcion as Nombre"])->join('familias as f','f.id','cupfamilias.Familia_id')->where('Cup_id',$direccionamiento->cup_id)->first();
                    $arrHojas[$sedeProveedorPgp. "-siPgp"]["requiere_cobro"] = true;
                    $arrHojas[$sedeProveedorPgp . "-siPgp"]["valor_cobro"] = 0;
                    $arrHojas[$sedeProveedorPgp . "-siPgp"]["prestador"] = $sedeProveedorPgp;
                    $arrHojas[$sedeProveedorPgp . "-siPgp"]["tipo"][$cupFamilia2->Nombre]  = 0;
                    $arrServiciosHojas[$sedeProveedorPgp . "-siPgp"][$cupFamilia2->Nombre]["cups"][] = $arrPgp[$key2];
                }
            }
        }
        foreach ($arrServiciosHojas as $key => $familias) {
            foreach ($familias as $key2 => $arrServiciosHoja) {
                if (substr($key, -5) === "noPgp") {
                    $valorCuotaModeradora = 0;
                    if ($citaPaciente->Finalidad == "No aplica" || $citaPaciente->Finalidad == "Teleconsulta") {
                            switch ($paciente->nivel) {
                                case 1:
                                    $valorCuotaModeradora = $configuracion->cuota_moderadora_nivel_1;
                                    break;
                                case 2:
                                    $valorCuotaModeradora = $configuracion->cuota_moderadora_nivel_2;
                                    break;
                                case 3:
                                    $valorCuotaModeradora = $configuracion->cuota_moderadora_nivel_3;
                                    break;
                            }
                        $arrHojas[$key]["tipo"][$key2] = $valorCuotaModeradora;
                        $arrHojas[$key]["valor_cobro"] = $arrHojas[$key]["valor_cobro"] + $valorCuotaModeradora;

                    }
                } elseif (substr($key, -5) === "siPgp") {
                    $sum = 0;
                    foreach ($arrServiciosHoja["cups"] as $cup)
                        $sum = $sum + intval($cup["tarifa"]);
                    if ($citaPaciente->Finalidad == "No aplica" || $citaPaciente->Finalidad == "Teleconsulta") {
                            if (strtolower($paciente->Tipo_Afiliado) == "beneficiario") {
                                switch ($paciente->nivel) {
                                    case 1:
                                        $valorCalculado = ($sum * $configuracion->porcentaje_copago_nivel_1) / 100;
                                        if ($valorCalculado > $configuracion->valor_tope_copago_nivel_1_servicio) {
                                            $valorCalculado = $configuracion->valor_tope_copago_nivel_1_servicio;
                                        }
                                        if ($acumulado >= $configuracion->valor_tope_copago_nivel_1_anio) {
                                            $valorCalculado = 0;
                                        } else {
                                            $faltante = $configuracion->valor_tope_copago_nivel_1_anio - $acumulado;
                                            if ($faltante < $valorCalculado) {
                                                $valorCalculado = $faltante;
                                            }
                                        }
                                        $arrHojas[$key]["tipo"][$key2] = $valorCalculado;
                                        $arrHojas[$key]["valor_cobro"] = $arrHojas[$key]["valor_cobro"] + $valorCalculado;
                                        break;
                                    case 2:
                                        $valorCalculado = ($sum * $configuracion->porcentaje_copago_nivel_2) / 100;
                                        if ($valorCalculado > $configuracion->valor_tope_copago_nivel_2_servicio) {
                                            $valorCalculado = $configuracion->valor_tope_copago_nivel_2_servicio;
                                        }
                                        if ($acumulado >= $configuracion->valor_tope_copago_nivel_2_anio) {
                                            $valorCalculado = 0;
                                        } else {
                                            $faltante = $configuracion->valor_tope_copago_nivel_2_anio - $acumulado;
                                            if ($faltante < $valorCalculado) {
                                                $valorCalculado = $faltante;
                                            }
                                        }
                                        $arrHojas[$key]["tipo"][$key2] = $valorCalculado;
                                        $arrHojas[$key]["valor_cobro"] = $arrHojas[$key]["valor_cobro"] + $valorCalculado;

                                        break;
                                    case 3:
                                        $valorCalculado = ($sum * $configuracion->porcentaje_copago_nivel_3) / 100;
                                        if ($valorCalculado > $configuracion->valor_tope_copago_nivel_3_servicio) {
                                            $valorCalculado = $configuracion->valor_tope_copago_nivel_3_servicio;
                                        }
                                        if ($acumulado >= $configuracion->valor_tope_copago_nivel_3_anio) {
                                            $valorCalculado = 0;
                                        } else {
                                            $faltante = $configuracion->valor_tope_copago_nivel_3_anio - $acumulado;
                                            if ($faltante < $valorCalculado) {
                                                $valorCalculado = $faltante;
                                            }
                                        }
                                        $arrHojas[$key]["tipo"][$key2] = $valorCalculado;
                                        $arrHojas[$key]["valor_cobro"] = $arrHojas[$key]["valor_cobro"] + $valorCalculado;
                                        break;
                                }
                            }
                    }
                }
            }
        }
        $data = [
            "hojas" => $arrHojas,
            "cupsHojas" => $arrServiciosHojas,
            'anexos' => $arrAnexos,
            'paciente' => $paciente
        ];
        return $data;
    }

    public function calcularOrdenamientoFormatos($idOrden, $citaPacienteId)
    {
        $citaPaciente = citapaciente::find($citaPacienteId);
        $paciente = Paciente::find($citaPaciente->Paciente_id);
        $configuracion = Configuracion::find(1);
        $arrPgp = [];
        $arrNoPgp = [];
        $arrAnexos = [];
        $arrHojas = [];
        $arrServiciosHojas = [];
        $acumulado = $this->acumuladoAnio($citaPaciente->Paciente_id);
        $servicios = Cup::select(['cups.id as codigo', 'cu.id as detalle', 'cu.Orden_id'])
            ->join('cupordens as cu', 'cups.id', 'cu.cup_id')
            ->where('cu.Orden_id', $idOrden)->get();
        foreach ($servicios as $servicio) {
            $entidad = CupEntidade::select(['tarifa','copago as pgp','cup_id'])->where('cup_id', $servicio->codigo)->where('entidad_id', 2)->first();
            if ($entidad) {
                if ($entidad->pgp) {
                    $arrPgp[] = $entidad->toArray();
                } else {
                    $arrNoPgp[] = $entidad->toArray();
                }
            } else {

                $detalleOrdensServicios = Cuporden::select([
                    "cupordens.Cup_id as id",
                    "cupordens.Cantidad as cantidad",
                    "cupordens.Observacionorden as observacion",
                    "c.Codigo as codigo",
                    "c.Nombre as nombre",
                    "cupordens.estado_id",
                    "cupordens.Orden_id",
                    "cupordens.Ubicacion_id",
                    "cupordens.Estado_id"
                ])->join("cups as c", "c.id", "cupordens.Cup_id")
                    ->where("cupordens.id", $servicio->detalle)->first();
                $arrAnexos[] = $detalleOrdensServicios;
            }
        }
        $direccionamientos = DB::select("exec dbo.CupsDireccionamientoEntidad " . intval($idOrden));
        foreach ($direccionamientos as $direccionamiento) {
            $key = array_search(intval($direccionamiento->cup_id), array_column($arrNoPgp, 'cup_id'));
            if (is_numeric($key)) {
                $sedeProveedorNoPgp = $direccionamiento->sedeproveedor_id;
                $cupDetalle = Cuporden::where('Orden_id',$idOrden)->where('Cup_id',$direccionamiento->cup_id)->first();
                if($cupDetalle->Ubicacion_id){
                    $sedeProveedorNoPgp =  $cupDetalle->Ubicacion_id;
                }
                $cupFamilia1 = Cupfamilia::select(["f.Descripcion as Nombre"])->join('familias as f','f.id','cupfamilias.Familia_id')->where('Cup_id',$direccionamiento->cup_id)->first();
                $arrHojas[$sedeProveedorNoPgp . "-noPgp"]["requiere_cobro"] = $this->entidadesCobro($sedeProveedorNoPgp);
                $arrHojas[$sedeProveedorNoPgp . "-noPgp"]["valor_cobro"] = 0;
                $arrHojas[$sedeProveedorNoPgp . "-noPgp"]["prestador"] = $sedeProveedorNoPgp;
                $arrHojas[$sedeProveedorNoPgp . "-noPgp"]["tipo"][$cupFamilia1->Nombre] = 0;
                $arrServiciosHojas[$sedeProveedorNoPgp . "-noPgp"][$cupFamilia1->Nombre]["cups"][] = $arrNoPgp[$key];
            } else {
                $key2 = array_search(intval($direccionamiento->cup_id), array_column($arrPgp, 'cup_id'));
                if (is_numeric($key2)) {
                    $sedeProveedorPgp = $direccionamiento->sedeproveedor_id;
                    $cupDetalle = Cuporden::where('Orden_id',$idOrden)->where('Cup_id',$direccionamiento->cup_id)->first();
                    if($cupDetalle->Ubicacion_id){
                        $sedeProveedorPgp =  $cupDetalle->Ubicacion_id;
                    }
                    $cupFamilia2 = Cupfamilia::select(["f.Descripcion as Nombre"])->join('familias as f','f.id','cupfamilias.Familia_id')->where('Cup_id',$direccionamiento->cup_id)->first();
                    $arrHojas[$sedeProveedorPgp. "-siPgp"]["requiere_cobro"] = true;
                    $arrHojas[$sedeProveedorPgp . "-siPgp"]["valor_cobro"] = 0;
                    $arrHojas[$sedeProveedorPgp . "-siPgp"]["prestador"] = $sedeProveedorPgp;
                    $arrHojas[$sedeProveedorPgp . "-siPgp"]["tipo"][$cupFamilia2->Nombre]  = 0;
                    $arrServiciosHojas[$sedeProveedorPgp . "-siPgp"][$cupFamilia2->Nombre]["cups"][] = $arrPgp[$key2];
                }
            }
        }
        foreach ($arrServiciosHojas as $key => $familias) {
            foreach ($familias as $key2 => $arrServiciosHoja) {
                if (substr($key, -5) === "noPgp") {
                    $valorCuotaModeradora = 0;
                    if ($citaPaciente->Finalidad == "No aplica" || $citaPaciente->Finalidad == "Teleconsulta") {
                        switch ($paciente->nivel) {
                            case 1:
                                $valorCuotaModeradora = $configuracion->cuota_moderadora_nivel_1;
                                break;
                            case 2:
                                $valorCuotaModeradora = $configuracion->cuota_moderadora_nivel_2;
                                break;
                            case 3:
                                $valorCuotaModeradora = $configuracion->cuota_moderadora_nivel_3;
                                break;
                        }
                        $arrHojas[$key]["tipo"][$key2] = $valorCuotaModeradora;
                        $arrHojas[$key]["valor_cobro"] = $arrHojas[$key]["valor_cobro"] + $valorCuotaModeradora;

                    }
                } elseif (substr($key, -5) === "siPgp") {
                    $sum = 0;
                    foreach ($arrServiciosHoja["cups"] as $cup)
                        $sum = $sum + intval($cup["tarifa"]);
                    if ($citaPaciente->Finalidad == "No aplica" || $citaPaciente->Finalidad == "Teleconsulta") {
                        if (strtolower($paciente->Tipo_Afiliado) == "beneficiario") {
                            switch ($paciente->nivel) {
                                case 1:
                                    $valorCalculado = ($sum * $configuracion->porcentaje_copago_nivel_1) / 100;
                                    if ($valorCalculado > $configuracion->valor_tope_copago_nivel_1_servicio) {
                                        $valorCalculado = $configuracion->valor_tope_copago_nivel_1_servicio;
                                    }
                                    if ($acumulado >= $configuracion->valor_tope_copago_nivel_1_anio) {
                                        $valorCalculado = 0;
                                    } else {
                                        $faltante = $configuracion->valor_tope_copago_nivel_1_anio - $acumulado;
                                        if ($faltante < $valorCalculado) {
                                            $valorCalculado = $faltante;
                                        }
                                    }
                                    $arrHojas[$key]["tipo"][$key2] = $valorCalculado;
                                    $arrHojas[$key]["valor_cobro"] = $arrHojas[$key]["valor_cobro"] + $valorCalculado;
                                    break;
                                case 2:
                                    $valorCalculado = ($sum * $configuracion->porcentaje_copago_nivel_2) / 100;
                                    if ($valorCalculado > $configuracion->valor_tope_copago_nivel_2_servicio) {
                                        $valorCalculado = $configuracion->valor_tope_copago_nivel_2_servicio;
                                    }
                                    if ($acumulado >= $configuracion->valor_tope_copago_nivel_2_anio) {
                                        $valorCalculado = 0;
                                    } else {
                                        $faltante = $configuracion->valor_tope_copago_nivel_2_anio - $acumulado;
                                        if ($faltante < $valorCalculado) {
                                            $valorCalculado = $faltante;
                                        }
                                    }
                                    $arrHojas[$key]["tipo"][$key2] = $valorCalculado;
                                    $arrHojas[$key]["valor_cobro"] = $arrHojas[$key]["valor_cobro"] + $valorCalculado;

                                    break;
                                case 3:
                                    $valorCalculado = ($sum * $configuracion->porcentaje_copago_nivel_3) / 100;
                                    if ($valorCalculado > $configuracion->valor_tope_copago_nivel_3_servicio) {
                                        $valorCalculado = $configuracion->valor_tope_copago_nivel_3_servicio;
                                    }
                                    if ($acumulado >= $configuracion->valor_tope_copago_nivel_3_anio) {
                                        $valorCalculado = 0;
                                    } else {
                                        $faltante = $configuracion->valor_tope_copago_nivel_3_anio - $acumulado;
                                        if ($faltante < $valorCalculado) {
                                            $valorCalculado = $faltante;
                                        }
                                    }
                                    $arrHojas[$key]["tipo"][$key2] = $valorCalculado;
                                    $arrHojas[$key]["valor_cobro"] = $arrHojas[$key]["valor_cobro"] + $valorCalculado;
                                    break;
                            }
                        }
                    }
                }
            }
        }
        $data = [
            "hojas" => $arrHojas,
            "cupsHojas" => $arrServiciosHojas,
            'anexos' => $arrAnexos,
            'paciente' => $paciente
        ];
        return response()->json($data, 200);
    }

    public function entidadesCobro($codigoEntidad)
    {
        $result = false;
        switch (intval($codigoEntidad)) {
            case 512:
            case 569:
            case 528:
            case 529:
            case 542:
            case 650:
            case 810:
            case 824:
            case 825:
            case 826:
            case 587:
            case 576:
            case 832:
            case 510:
            case 507:
            case 469:
            case 470:
            case 474:
            case 480:
            case 817:
            case 818:
            case 816:
            case 819:
            case 820:
            case 184:
            case 185:
            case 186:
            case 187:
            case 188:
            case 781:
            case 799:
            case 189:
            case 194:
            case 197:
                $result = true;
                break;
        }
        return $result;
    }

    public function getEntidades(){
        $entidades = Entidade::where('estado_id', 1)->get(['id', 'nombre', 'agendar_paciente', 'entrega_medicamentos', 
                                                            'atender_paciente', 'autorizar_ordenes', 'consutar_historico', 
                                                        'generar_ordenes', 'salud_ocupacional', 'contrato']);
        return response()->json($entidades);
    }

    public function guardarPermisos(Request $request,Entidade $entidad){
        $entidad->update($request->all());
        return response()->json("Entidad Actualizados");
    }

    public function guardar(Request $request){
        Entidade::create($request->all());
        return response()->json("Entidad Creada");
    }
    public function validar(Entidade $entidad,$accion){
        $message = "";
        $acceso = true;
        if(!$entidad->{$accion}){
            $message = "La entidad del paciente no tiene permitido esta accion";
            $acceso = false;
        }
        $data = [
            'message' => $message,
            'acceso' => $acceso
        ];
        return response()->json($data);

    }
    public function entidadesOcpucacionales(){
      $entidades =  Entidade::select('entidades.*')
            ->where('estado_id', 1)
            ->where('salud_ocupacional', 'true')
            ->get();
        return response()->json($entidades);

    }

    public function entidadesNoContrato(){

        $entidades =  Entidade::select('entidades.*')
        ->where('estado_id', 1)
        ->where('contrato', 0)
        ->get();

        return response()->json($entidades, 201);

    }
}
