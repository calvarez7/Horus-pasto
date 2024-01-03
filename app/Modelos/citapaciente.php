<?php

namespace App\Modelos;

use App\Repositories\citaPacienteRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class citapaciente extends Model implements Auditable
{
    use AuditableTrait;

    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $casts = [
        'valor_scala_karnofski' => 'integer',
        'valor_scala_ecog' => 'integer',
        'sin_dolor' => 'integer',
        'sin_cansancio' => 'integer',
        'sin_nauseas' => 'integer',
        'sin_tristeza' => 'integer',
        'sin_ansiedad' => 'integer',
        'sin_somnolencia' => 'integer',
        'sin_disnea' => 'integer',
        'buen_apetito' => 'integer',
        'maximo_bienestar' => 'integer',
        'sin_dificulta_para_dormir' => 'integer',
    ];

    protected $fillable = ['Paciente_id', 'Cita_id', 'Usuario_id', 'Tipocita_id', 'Cup_id', 'Fecha_solicita',
        'Motivoconsulta', 'Enfermedadactual', 'Resultayudadiagnostica',
        'tratamientoncologico', 'cirujiaoncologica', 'ncirujias', 'iniciocirujia', 'fincirujia',
        'recibioradioterapia', 'inicioradioterapia', 'finradioterapia', 'nsesiones', 'intencion',
        'Oftalmologico', 'Genitourinario', 'Otorrinoralingologico', 'Linfatico', 'Osteomioarticular',
        'Neurologico', 'Cardiovascular', 'Tegumentario', 'Respiratorio', 'Endocrinologico', 'Gastrointestinal',
        'Norefiere', 'Timeconsulta', 'Medicoordeno', 'Entidademite', 'Finalidad', 'Observaciones', 'Adjuntotranscripcion',
        'Datetimeingreso', 'Datetimesalida', 'Ambito', 'Estado_id','ingestaAdecuada', 'Diuresis', 'deposicion','valor_cita','cobro_estado_id','lugar_atencion',
        'avcc_ojoderecho', 'avcc_ojoizquierdo', 'avsc_ojoderecho', 'avsc_ojoizquierdo', 'ph_ojoderecho', 'ph_ojoizquierdo',
        'motilidad_ojoderecho', 'covert_ojoderecho', 'motilidad_ojoizquierdo', 'covert_ojoizquierdo', 'biomicros_ojoderecho',
        'biomicros_ojoizquierdo', 'presionintra_ojoderecho', 'presionintra_ojoizquierdo', 'gionios_ojoderecho',
        'gionios_ojoizquierdo', 'fondo_ojoderecho', 'fondo_ojoizquierdo','user_medico_atiende', 'salud_ocupacional', 'referencia_id',
        'checkboxOftalmologico','checkboxGenitourinario','checkboxOtorrinoralingologico','checkboxLinfatico','checkboxOsteomioarticular',
        'checkboxNeurologico','checkboxCardiovascular','checkboxTegumentario','checkboxRespiratorio','checkboxEndocrinologico',
        'checkboxGastrointestinal','checkboxNorefiere','especialidad', 'ciclo_vida','especialidad_id','actividad_id','Observacionesinasistido', 'cita_no_programada',
        'observaciones_generales','TegumentarioSiNo','FlujoVaginal','SintomaticoRespiratorio', 'ruta_foto', 'firma_paciente', 'firma_acudiente','aceptacion_consentimiento',
        'firmante','numero_documento_representante', 'valor_scala_karnofski', 'valor_scala_ecog','sin_dolor',
        'sin_cansancio','sin_nauseas','sin_tristeza','sin_ansiedad','sin_somnolencia','sin_disnea','buen_apetito',
        'maximo_bienestar','sin_dificulta_para_dormir'
    ];

    protected $table = 'cita_paciente';

    public function ordens()
    {
        return $this->hasMany('App\Modelos\Orden','Cita_id');
    }

    public function conduta()
    {
        return $this->belongsTo('App\Modelos\Conducta');
    }

    public function tipordens()
    {
        return $this->hasMany('App\Modelos\Tiporden');
    }

    public function cie10s()
    {
        return $this->belongsToMany('App\Modelos\Cie10', 'cie10pacientes', 'Citapaciente_id', 'Cie10_id')->withPivot('Esprimario', 'Tipodiagnostico');
    }

    public function ginecologicos()
    {
        return $this->hasMany('App\Modelos\Ginecologico');
    }

    public function examenfisico()
    {
        return $this->hasOne('App\Modelos\Examenfisico', 'Citapaciente_id');
    }

    public function estilovida()
    {
        return $this->belongsTo('App\Modelos\Estilovida');
    }

    public function motivos()
    {
        return $this->hasMany('App\Modelos\Motivo');
    }

    public function files()
    {
        return $this->hasMany('App\Modelos\Files');
    }

    public function cups()
    {
        return $this->belongsToMany('App\Modelos\Cup', 'cuprocedimiento', 'Citapaciente_id', 'Cup_id');
    }

    public function patologia()
    {
        return $this->belongsTo('App\Modelos\Patologia');
    }

    public function notaenfermeria()
    {
        return $this->hasMany('App\Modelos\notaenfermeria','cita_paciente_id');
    }

    public static function getRepository()
    {
        return new citaPacienteRepository();
    }

    public function cita(){
        return $this->belongsTo(Cita::class, 'Cita_id');
    }

    public function paciente(){
        return $this->belongsTo(Paciente::class, 'Paciente_id');
    }

    public function scopeWhereTipoDeConsulta($query, $tipo, $fecha){

        if($tipo === 'telemedicina'){
            return $query->whereDate('citas.Hora_Inicio', '>=', Carbon::now()->format('Y-m-d'))
                ->whereDate('citas.Hora_Inicio', '<=', Carbon::now()->addDays(6)->format('Y-m-d'));
        }

        if($tipo === 'whatsapp'){
            return $query->whereDate('citas.Hora_Inicio', Carbon::now()->addDay()->format('Y-m-d'));
        }

        if($tipo === 'firma'){
            if($fecha){
                return $query->whereDate('citas.Hora_Inicio', $fecha);
            }
        }
    }

    public function scopeWhereModalidad($query, $teleconsulta){
        if($teleconsulta === 'telemedicina'){
            return $query->where('tipo_agendas.modalidad', 'TELECONSULTA');
        }else{
            return $query->where('tipo_agendas.modalidad', '!=', 'TELECONSULTA');
        }
    }

    /** Funciones */
    
    /**
     * valida que la cita pertenezca a el documento enviado
     * @param String $documento
     * @return Boolean
     * @author David Peláez
     */
    public function validarPaciente($documento){
        return $this->paciente->Num_Doc === $documento;
    }

    /**
     * Almacena la firma del consentimiento
     * @param String $firma, debe de ser en base_64
     * @return Boolean
     * @author David Peláez
     */
    public function guardarFirma($firma){
        $this->firma_consentimiento = DB::raw('0x'.bin2hex($firma));
        $this->firma_consentimiento_time = Carbon::now();
        return $this->save();
    }
}
