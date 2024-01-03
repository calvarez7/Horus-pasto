<?php

namespace App\Services;

use App\Modelos\Cita;
use App\Modelos\citapaciente;
use App\Modelos\Detallecita;
use App\Modelos\Paciente;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Error;

class CitasService {

    /**
     * Valida que el campo documento haya sido enviado y que el paciente exista, de existir retorna el paciente
     * @param $documento
     * @return Paciente $paciente
     * @author David Peláez
     */
    public function validarPaciente($documento){
        if(!$documento){
            throw new Error('Se debe ingresar un documento.');
        }
        /** Se consulta el paciente */
        $paciente = Paciente::where('Num_Doc', $documento)->first();
        if(!$paciente){
            throw new Error('Este documento no es valido.');
        }

        return $paciente;
    }

    /**
     * Lista las citas de un paciente
     * @param $paciente_id
     * @return citapaciente|false $citas
     * @author David Peláez
     */
    public function listarCitas($tipo = 'telemedicina', $fecha = null,  $estados = [4, 6], $formato = null){

        $citas = citapaciente::
            select([
                'cita_paciente.id as id',
                'citas.id as historia',
                DB::raw("concat(pacientes.Primer_Nom,' ',pacientes.SegundoNom,' ',pacientes.Primer_Ape,' ',pacientes.Segundo_Ape) as nombre_paciente"),
                DB::raw("convert(date, citas.Hora_Inicio) as fecha"),
                DB::raw("convert(time, citas.Hora_Inicio) as hora"),
                'especialidade_tipoagenda.Duracion as duracion_cita',
                'tipo_agendas.name as actividad',
                'especialidades.Nombre as especialidad',
                'sedes.Nombre as nombreSede',
                'sedes.Direccion as direccionSede',
                'users.name as nombre_medico',
                DB::raw("'TELEMEDICINA' observacion"),
                'cita_paciente.Estado_id as estado_cita',
                'pacientes.Correo1 as email_paciente',
                'pacientes.Num_Doc as documento_paciente',
                'users.email as email_medico',
                DB::raw("'telemedicina@sumimedical.com' email_salida"),
                'cita_paciente.updated_at as fecha_modificacion',
                'pacientes.Celular1 as numero'
            ])
            ->join('pacientes', 'cita_paciente.Paciente_id', '=', 'pacientes.id')
            ->join('citas', 'cita_paciente.Cita_id', '=', 'citas.id')
            ->join('agendas', 'citas.Agenda_id', '=', 'agendas.id')
            ->join('consultorios', 'agendas.Consultorio_id', '=', 'consultorios.id')
            ->join('sedes', 'consultorios.Sede_id', '=', 'sedes.id')
            ->join('agendausers', 'agendas.id', '=', 'agendausers.Agenda_id')
            ->join('users', 'agendausers.Medico_id', '=', 'users.id')
            ->join('especialidade_tipoagenda', 'agendas.Especialidad_id', '=', 'especialidade_tipoagenda.id')
            ->join('tipo_agendas', 'especialidade_tipoagenda.Tipoagenda_id', '=', 'tipo_agendas.id')
            ->join('especialidades', 'especialidade_tipoagenda.Especialidad_id', '=', 'especialidades.id')
            ->whereTipoDeConsulta($tipo, $fecha)
            ->whereModalidad($tipo)
            ->whereIn('cita_paciente.Estado_id', $estados)
            ->get();

        return $citas ? $citas : false;
    }

    /**
     * Cancela una cita
     * @param Integer $cita_paciente_id identificador de la cita para un afiliado
     * @param Integer $usuario_id Usuario logueado, quien cancelo la cita, en caso de ser por wpp seria el usuario 1
     * @param String $motivo razón por la cual se cancela la cita
     * @return Boolean
     * @author David Peláez
     */
    public function cancelar($cita_paciente_id, $usuario_id = 1,  $motivo = "Cancelado por el paciente, via whatsapp."){
        /* Buscamos el cita paciente , y la cita*/
        $cita_paciente = citapaciente::where('id', $cita_paciente_id)->first();
        $cita = Cita::find($cita_paciente->Cita_id);

        try {
            /** Usamos el transaction en caso de algun error en las actualizaciones */
            DB::beginTransaction();
            /** Para todos los casos se merma la cantidad de la cita en 1,  */
            /* Si la cantidad en cita restandole 1 es igual o menor 0, el estado de la cita pasa a 6 (cancelada)*/
            $cita->Cantidad -= 1;
            if( $cita->Cantidad < 1){
                $cita->Estado_id = 3;
            }
            $cita->update();

            /* Citas grupales y Normales*/
            if(!$cita_paciente->salud_ocupacional){
                /** Cambiamos el cita paciente a estado 6 (cancelado) */
                $cita_paciente->update([
                    'Estado_id' => 6
                ]);
                /** Agregamos un detalle, en este caso el motivo de la cancelacion */
                Detallecita::create([
                    'Citapaciente_id' => $cita_paciente->id,
                    'Estado_id' => 6,
                    'Motivo' => $motivo,
                    'Usuario_id' => $usuario_id
                ]);
            }

            /** Citas ocupacionales */
            else{

                /** Buscamos todas la citas_pacientes que tenga la cita_id que ya consultamos, cambiamos su estado a 6 */
                $citas_pacientes = citapaciente::where('Cita_id', $cita->id)->get();

                /** Agregamos un detalle, para cada uno de los cita pacientes */
                foreach($citas_pacientes as $cita_paciente_item){
                    $cita_paciente_item->update([
                        'Estado_id' => 6
                    ]);
                    Detallecita::create([
                        'Citapaciente_id' => $cita_paciente_item->id,
                        'Estado_id' => 6,
                        'Motivo' => $motivo,
                        'Usuario_id' => $usuario_id
                    ]);
                }
            }

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollback();
            throw new Error($th->getMessage(), $th->getCode());
        }

        return true;
    }

    /**
     * Da formato a las citas para el meet
     * @param Collection $data
     * @return Array
     * @author David Peláez
     */
    public function formatoParaMeet($data){

        return $data->map(function ($item, $key){
            return [
                "id" => $item->id,
                "historia" => $item->historia,
                "nombre_paciente" => $item->nombre_paciente,
                "documento_paciente" => $item->documento_paciente,
                "fecha" => $item->fecha,
                "hora" => explode('.', $item->hora)[0],
                "duracion_cita" => $this->formatoHora(intval($item->duracion_cita)),
                "especialidad" => $item->especialidad,
                "nombre_medico" => $item->nombre_medico,
                "observacion" => $item->observacion,
                "estado_cita" => $this->determinarEstado(intval($item->estado_cita)),
                "email_paciente" => $item->email_paciente,
                "email_medico" => $item->email_medico,
                "email_salida" => $item->email_salida,
                "fecha_modificacion" => $item->fecha_modificacion,
            ];
        });

    }

    /**
     * Da un formato a la duracion de las citas (podria ser mas general pero se hace para avanzar)
     * @param $minutos
     * @return String
     */
    private function formatoHora($minutos){
        $objeto = (Object)['horas' => 0, 'minutos' => 0];

        $objeto->horas = floor($minutos / 60);
        $objeto->minutos = $minutos - ($objeto->horas * 60);

        return '0' . $objeto->horas . ':' . ($objeto->minutos < 10 ? '0' . $objeto->minutos : $objeto->minutos) . ':00';
    }

    /**
     * determinar estado
     * @param int $estado
     * @return String
     */
    public function determinarEstado($estado){
        switch ($estado) {
            case 4:
                return 'P';
                break;
            case 6:
                return 'C';
                break;
            default:
                return 'Sin estado';
                break;
        }
    }

    /**
     * Listar las citas menos las de gestion para enviar notificacion a whatsApp
     * @return citas
     * @author Calvarez
     */
    public function listarCitasWhatsApp(){
        $fechaNotificacion = date("Y-m-d",strtotime(date('Y-m-d')."+ 2 days"));
        $citas = Cita::select(
            'cita_paciente.id as historia',
            'citas.id',
            'pacientes.Num_Doc',
            DB::raw("concat(pacientes.Primer_Nom,' ',pacientes.SegundoNom,' ',pacientes.Primer_Ape,' ',pacientes.Segundo_Ape) as nombre_paciente"),
            DB::raw("convert(date, citas.Hora_Inicio) as fecha"),
            DB::raw("convert(time, citas.Hora_Inicio) as hora"),
            'especialidade_tipoagenda.Duracion as duracion_cita',
            'tipo_agendas.name as actividad',
            'especialidades.Nombre as especialidad',
            'sedes.Nombre as nombreSede',
            'sedes.Direccion as direccionSede',
            'users.name as nombre_medico',
            'cita_paciente.Estado_id as estado_cita',
            'pacientes.Correo1 as email_paciente',
            'pacientes.Num_Doc as documento_paciente',
            'users.email as email_medico',
            'cita_paciente.updated_at as fecha_modificacion',
            'pacientes.Celular1 as numero'
        )
            ->join('cita_paciente', 'citas.id', 'cita_paciente.Cita_id')
            ->join('pacientes', 'cita_paciente.paciente_id', 'pacientes.id')
            ->join('agendas', 'citas.Agenda_id', '=', 'agendas.id')
            ->join('consultorios', 'agendas.Consultorio_id', '=', 'consultorios.id')
            ->join('sedes', 'consultorios.Sede_id', '=', 'sedes.id')
            ->join('agendausers', 'agendas.id', '=', 'agendausers.Agenda_id')
            ->join('users', 'agendausers.Medico_id', '=', 'users.id')
            ->join('especialidade_tipoagenda', 'agendas.Especialidad_id', '=', 'especialidade_tipoagenda.id')
            ->join('tipo_agendas', 'especialidade_tipoagenda.Tipoagenda_id', '=', 'tipo_agendas.id')
            ->join('especialidades', 'especialidade_tipoagenda.Especialidad_id', '=', 'especialidades.id')
        ->whereBetween('citas.Hora_Inicio', [$fechaNotificacion.' 00:00:00.000', $fechaNotificacion. ' 23:59:00.000'])
        ->where('citas.estado_id', 4)
        ->where('cita_paciente.Estado_id', 4)
        ->whereNotIn('tipo_agendas.id', [372,451,474])
        ->get();
        return $citas;
    }
}
