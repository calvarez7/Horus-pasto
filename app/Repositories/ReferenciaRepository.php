<?php
namespace App\Repositories;

use App\Modelos\Referencia;
use Illuminate\Support\Facades\DB;

class ReferenciaRepository
{
    public function consolidatedReferenciaByDate($request)
    {
        return Referencia::select([
            'referencias.id',
            'referencias.id_paci as id_paci',
            'referencias.id_prestador as id_prestador',
            'u.name as nombre_prestador',
            'referencias.tipo_anexo',
            'p.Tipo_Doc as tipo_Doc',
            'p.Num_Doc as numero_Doc',
            'p.Primer_Nom as primerNombrePaciente',
            'p.SegundoNom as segundoNombrePaciente',
            'p.Primer_Ape as primerApellidoPaciente',
            'p.Segundo_Ape as segundoApellidoPaciente',
            'p.Fecha_Naci',
            'p.Sexo',
            'p.Departamento',
            'p.Mpio_Afiliado',
            'p.Direccion_Residencia',
            'p.Telefono',
            'p.Celular1',
            'p.Correo1',
            'p.Tipo_Afiliado',
            'referencias.triage',
            'referencias.hora_urgencia',
            'referencias.nom_prest',
            'referencias.usuario_remitido',
            'referencias.habi_remitido',
            'referencias.dpto_remitido',
            'referencias.muni_remitido',
            'referencias.origen_atencion',
            'referencias.enti_solicita',
            'referencias.des_paci',
            'referencias.text1',
            'referencias.Consulta_Externa',
            'referencias.servi_hosp',
            'referencias.cama_ubipaci',
            'referencias.cod_habi',
            'referencias.dpto_hospitalizacion',
            'referencias.muni_hospitalizacion',
            'referencias.ori_atencion',
            'referencias.giagnostico_hospi',
            'referencias.priori_atencion',
            'referencias.tipo_servicio',
            'referencias.tipo_servicio',
            'referencias.cantidad',
            'referencias.cup',
            'referencias.text2',
            'referencias.ubi_paci_remi',
            'referencias.Prioridad_remi',
            'referencias.Complejidad_remi',
            'referencias.Especialidad_remi',
            'referencias.TBC_remi',
            'referencias.tbc',
            'referencias.Aislamiento',
            'referencias.Glasglow',
            'referencias.num_glasglow',
            'referencias.presion_sistolica',
            'referencias.presion_diastolica',
            'referencias.fre_cardiaca',
            'referencias.fre_respiratoria',
            'referencias.ambulancia',
            'referencias.req_oxigeno',
            'referencias.text3',
            'referencias.medico_Soli',
            'referencias.Espe_Médico_Soli',
            'referencias.Id_medico_Soli',
            'referencias.Re_medico_Soli',
            'referencias.tipoid_User_Repo',
            'referencias.idusers_reporta',
            'referencias.nom_user_report',
            'referencias.cargo_user_report',
            'referencias.tel_user_report',
            'referencias.cel_user_report',
            'referencias.adjunto',
            'referencias.created_at',
            'referencias.updated_at',
            'referencias.state',
            'referencias.rzeuz as radicado',
            'referencias.tipo_solicitud'])
            ->join('pacientes as p', 'referencias.id_paci', 'p.id')
            ->join('users as u', 'referencias.id_prestador', 'u.id')
            ->with(['cie10s' => function ($q) {
                $q->select(['Codigo_CIE10', 'Descripcion'])->get();
            }])
            ->whereBetween('referencias.created_at', [$request->startDate . ' 00:00:00.000', $request->finishDate . ' 23:59:59.000'])
            ->orderBy('referencias.id', 'ASC');

    }

}
