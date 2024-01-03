<?php
namespace App\Formats;
use App\Modelos\AntecedenteFarmacologico;
use App\Modelos\Antecedentesparentesco;
use App\Modelos\AntecedenteToxicologico;
use App\Modelos\Cie10;
use App\Modelos\Cie10paciente;
use App\Modelos\Cita;
use App\Modelos\citapaciente;
use App\Modelos\Conducta;
use App\Modelos\NotaAclaratoria;
use App\Modelos\Paciente;
use App\Modelos\Pacienteantecedente;
use App\Modelos\Parentescoantecedente;
use App\Modelos\TipoAgenda;
use App\User;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class HistoriaClinica extends FPDF
{
    public static $data;
    public static $ordenamientoServ;
    public static $ordenamientoMedica;
    public static $notaAclaratoria;
    public static $toxicologicos;
    public static $citaPaciente;

    public function generar($datos)
    {
        self::$citaPaciente = $datos;
        self::$data = citapaciente::select(
            DB::RAW("CONCAT(b.Primer_Nom, ' ', b.[SegundoNom], ' ', b.[Primer_Ape], ' ', b.[Segundo_Ape]) AS Nombre"),
            'cita_paciente.id',
            'b.id as Paciente_id',
            'b.Num_Doc as Cédula',
            'b.Fecha_Naci as Fecha_Nacimiento',
            'b.Edad_Cumplida as Edad',
            'b.Sexo as Sexo',
            'b.Tipo_Afiliado as Tipo_Afiliado',
            'b.Departamento as Departamento',
            'b.Mpio_Afiliado as Municipio_Afiliado',
            'b.Direccion_Residencia as Direccion_Residencia',
            'b.Telefono as Telefono',
            'b.Celular1 as Celular',
            'b.Laboraen',
            'bb.Nombre as IPS',
            'tipos.Nombre as Tipocita',
            'cita_paciente.Tipocita_id',
            'cita_paciente.Fecha_solicita',
            'cita_paciente.Motivoconsulta',
            DB::RAW("CAST(cita_paciente.Enfermedadactual AS VARCHAR(MAX)) As Enfermedadactual"),
            DB::RAW("CAST(cita_paciente.Resultayudadiagnostica AS VARCHAR(MAX)) As Resultayudadiagnostica"),
			DB::RAW("CAST(nts.alimentacion AS VARCHAR(MAX)) As alimentacion"),
			DB::RAW("CAST(nts.caso_urgencia AS VARCHAR(MAX)) As caso_urgencia"),
			DB::RAW("CAST(nts.cuidados_casa AS VARCHAR(MAX)) As cuidados_casa"),
			DB::RAW("CAST(nts.derechos_deberes AS VARCHAR(MAX)) As derechos_deberes"),
			DB::RAW("CAST(nts.efectos_secundarios AS VARCHAR(MAX)) As efectos_secundarios"),
			DB::RAW("CAST(nts.habito_higiene AS VARCHAR(MAX)) As habito_higiene"),
			DB::RAW("CAST(nts.normas_sala_quimioterapia AS VARCHAR(MAX)) As normas_sala_quimioterapia"),
			DB::RAW("CAST(nts.nota AS VARCHAR(MAX)) As nota"),
			DB::RAW("CAST(nts.signos_alarma AS VARCHAR(MAX)) As signos_alarma"),
            'cita_paciente.Oftalmologico',
            'cita_paciente.Genitourinario',
            'cita_paciente.Otorrinoralingologico',
            'cita_paciente.Linfatico',
            'cita_paciente.Osteomioarticular',
            'cita_paciente.Neurologico',
            'cita_paciente.Cardiovascular',
            'cita_paciente.Tegumentario',
            'cita_paciente.Respiratorio',
            'cita_paciente.Endocrinologico',
            'cita_paciente.Gastrointestinal',
            'cita_paciente.Norefiere',
            'cita_paciente.Timeconsulta',
            'cita_paciente.Medicoordeno',
            'cita_paciente.Entidademite',
            'cita_paciente.Finalidad',
            'cita_paciente.Finalidad as finalidadTrans',
            DB::RAW("CAST(cita_paciente.Observaciones AS VARCHAR(MAX)) As Observaciones"),
            'cita_paciente.Datetimeingreso',
            'cita_paciente.Datetimesalida',
            'cita_paciente.cirujiaoncologica',
            'cita_paciente.ncirujias',
            'cita_paciente.iniciocirujia',
            'cita_paciente.fincirujia',
            'cita_paciente.recibioradioterapia',
            'cita_paciente.inicioradioterapia',
            'cita_paciente.finradioterapia',
            'cita_paciente.nsesiones',
			'cita_paciente.tratamientoncologico',
            'cita_paciente.intencion',
            DB::RAW("CAST(cita_paciente.ingestaAdecuada AS VARCHAR(MAX)) As ingestaAdecuada"),
            DB::RAW("CAST(cita_paciente.Diuresis AS VARCHAR(MAX)) As Diuresis"),
            DB::RAW("CAST(cita_paciente.deposicion AS VARCHAR(MAX)) As deposicion"),
			'pat.Dukes',
			'pat.Estadificaciónclinica',
			DB::RAW("CAST(pat.estadificacioninicial AS VARCHAR(MAX)) As estadificacioninicial"),
			'pat.fdxcanceractual',
			'pat.fechaestadificacion',
			'pat.flaboratoriopatologia',
			'pat.gleason',
			'pat.Her2',
			'pat.localizacioncancer',
			DB::RAW("CAST(pat.Patologiacancelactual AS VARCHAR(MAX)) As Patologiacancelactual"),
			'pat.tumorsegunbiopsia',
            'ex.Abdomen',
            'ex.Agudezavisual',
            'ex.Cabezacuello',
            'ex.Cardiopulmonar',
            'ex.Examenmama',
            'ex.Examenmental',
            'ex.Extremidades',
            'ex.Frecucardiaca',
            'ex.Frecurespiratoria',
            'ex.Genitourinario',
            'ex.Pulsosperifericos',
            'ex.Ojosfondojos',
            'ex.Osteomuscular',
            'ex.Pielfraneras',
            'ex.Reflejososteotendinos',
            'ex.Tactoretal',
            DB::RAW("CAST(es.Dietasaludable AS VARCHAR(MAX)) As Dietasaludable"),
            'es.Suenoreparador',
            'es.Duermemenoseishoras',
            'es.Altonivelestres',
            'es.Actividadfisica',
            'es.Frecuenciasemana',
            'es.Duracion',
            'es.Fumacantidad',
            'es.Fumainicio',
            'es.Fumadorpasivo',
            'es.Cantidadlicor',
            'es.Licorfrecuencia',
            'es.Consumopsicoactivo',
            'es.Psicoactivocantidad',
			DB::RAW("CAST(imag.Indicacion AS VARCHAR(MAX)) As Indicacion"),
			DB::RAW("CAST(imag.Hallazgos AS VARCHAR(MAX)) As Hallazgos"),
			DB::RAW("CAST(imag.Conclusion AS VARCHAR(MAX)) As Conclusion"),
			DB::RAW("CAST(imag.Notaclaratoria AS VARCHAR(MAX)) As Notaclaratoria"),
			DB::RAW("CONCAT(cups.Codigo, ' ', cups.Nombre) AS Cups"),
            'gine.Fechaultimamenstruacion',
            'gine.Gestaciones',
            'gine.Partos',
            'gine.Abortoprovocado',
            'gine.Abortoespontaneo',
            'gine.Mortinato',
            'gine.Gestantefechaparto',
            'gine.Gestantenumeroctrlprenatal',
            'gine.Eutoxico',
            'gine.Cesaria',
            'gine.Cancercuellouterino',
            'gine.Ultimacitologia',
            'gine.Resultado',
            'gine.Menarquia',
            'gine.Ciclos',
            'gine.Regulares',
            DB::RAW("CAST(gine.Observaciongineco AS VARCHAR(MAX)) As Observaciongineco2"),
            'rcv.Glicemia',
            'rcv.Glicemiafecha',
            'rcv.Hemoglobinaglicosilada',
            'rcv.Hemoglobinafecha',
            'rcv.Colesteroltotal',
            'rcv.Colesteroltotalfecha',
            'rcv.Colesterolhdl',
            'rcv.Colesterolhdlfecha',
            'rcv.Colesterolldl',
            'rcv.Colesterolldlfecha',
            'rcv.Trigliceridos',
            'rcv.Trigliceridosfecha',
            'rcv.Proteinuria',
            'rcv.Proteinuriafecha',
            'rcv.Uroanalisis',
            'rcv.Uroanalisisfecha',
            'rcv.Microalbuminuria',
            'rcv.Microalbuminuriafecha',
            'rcv.Creatinina',
            'rcv.Creatininafecha',
            'rcv.Disminuciondetfg',
            'rcv.Resultadoframinghan',
            'rcv.Cumplemetaterapeutica',
            'rcv.Pacienteadherente',
            'rcv.Pacientecontrolado',
            'rcv.Insulinorequiriente',
            'rcv.Signosdealarma',
            'rcv.Hospitalizacionesrecientes',
            'rcv.Valoracionpornutricion',
            'rcv.Valoracionporpsicologia',
            'rcv.Asisteatallergrupaleducativo',
            'rcv.Periodicidadproximocontrol',
            'rcv.Proximocontrolcon',
            'cita_paciente.avcc_ojoderecho',
            'cita_paciente.avcc_ojoizquierdo',
            'cita_paciente.avsc_ojoderecho',
            'cita_paciente.avsc_ojoizquierdo',
            'cita_paciente.ph_ojoderecho',
            'cita_paciente.ph_ojoizquierdo',
            'cita_paciente.motilidad_ojoderecho',
            'cita_paciente.motilidad_ojoizquierdo',
            'cita_paciente.covert_ojoderecho',
            'cita_paciente.covert_ojoizquierdo',
            'cita_paciente.biomicros_ojoderecho',
            'cita_paciente.biomicros_ojoizquierdo',
            'cita_paciente.presionintra_ojoderecho',
            'cita_paciente.presionintra_ojoizquierdo',
            'cita_paciente.gionios_ojoderecho',
            'cita_paciente.gionios_ojoizquierdo',
            'cita_paciente.fondo_ojoderecho',
            'cita_paciente.fondo_ojoizquierdo',
            'marca.Marca as marcapaciente',
            DB::RAW("CAST(es.Estilovidaobservaciones AS VARCHAR(MAX)) As Estilovidaobservaciones"),
            DB::RAW("CONCAT(' - Peso ',c.Peso, ' - Talla ', c.Talla, ' - IMC ', c.Imc, ' - Clasificación ', c.Clasificacion, ' - ASC ', c.ISC, ' - Perimetro Abdominal ',c.Perimetroabdominal) AS Antropometricas"),
            DB::RAW("CONCAT(' - Frecuencia Cardiaca ',c.Frecucardiaca, ' - Frecuencia Respiratoria ', c.Frecurespiratoria) AS Signos_Vitales"),
            DB::RAW("CONCAT(' - Pulsos ', c.Pulsos, ' - Temperatura ', c.Temperatura, ' - Sat.O2 ', c.Saturacionoxigeno) AS Otros_Signos_Vitales"),
            DB::RAW("CONCAT(' - Presión arterial sistólica: ', c.Presionsistolica, ' -  Presión arterial diastólica: ', c.Presiondiastolica, ' -  Posición: ', c.Posicion, ' -  Punto: ', c.Lateralidad, ' -  Presión Arterial Media: ', c.Presionarterialmedia) AS Presión_Arterial"),
            'c.Osteomuscular',
            'c.Condiciongeneral',
			'c.Neurologico as neuro123',
            DB::RAW("CAST(f.Planmanejo AS VARCHAR(MAX)) As Planmanejo"),
            DB::RAW("(select w.diagnostico_prim from dbo.fnBusca_Diagnosticos(cita_paciente.id) w) AS Diagnostico_Principal"),
            DB::RAW("(select w.diagnostico_sec from dbo.fnBusca_Diagnosticos(cita_paciente.id) w) AS Diagnostico_Secundario"),
            DB::RAW("CAST(f.Recomendaciones AS VARCHAR(MAX)) As Recomendaciones"),
            'f.Destinopaciente',
            'f.Finalidad',
            'tt.Registromedico',
            'tt.Firma',
            'tt.especialidad_medico',
            'tt.especialidad_medico as Especialidad',
            DB::raw("CONCAT(tt.name,' ',tt.apellido) as Atendido_Por"),
            DB::RAW("(select antecedentes from [fnBusca_Antecedentes](cita_paciente.id)) Antecedentes"),
            DB::RAW("(select Ant_Parentescos from [fnBusca_Antecedentes](cita_paciente.id)) Antecedentes_Parentesco"))
        ->JOIN('tipocitas as tipos' , 'cita_paciente.Tipocita_id' , 'tipos.id')
        ->LEFTJOIN('citas as r','cita_paciente.Cita_id','r.id')
        ->LEFTJOIN('examenfisicos as ex','cita_paciente.id','ex.Citapaciente_id')
        ->LEFTJOIN('estilovidas as es', 'cita_paciente.id', 'es.Citapaciente_id')
        ->LEFTJOIN('agendausers as z','r.Agenda_id','z.Agenda_id')
        ->JOIN('pacientes as b','cita_paciente.Paciente_id','b.id')
        ->LEFTJOIN('sedeproveedores as bb','b.IPS','bb.id')
        ->LEFTJOIN('examenfisicos as c','cita_paciente.id','c.Citapaciente_id')
        ->LEFTJOIN('ginecologicos as gine','cita_paciente.id','gine.Citapaciente_id')
        ->LEFTJOIN('labgestionriesgos as rcv','cita_paciente.id','rcv.Citapaciente_id')
        ->LEFTJOIN('marcas as marca','cita_paciente.id','marca.Citapaciente_id')
        ->LEFTJOIN('agendas as age','r.Agenda_id','age.id')
        ->LEFTjoin('especialidade_tipoagenda as espTipo','espTipo.id','age.Especialidad_id')
        ->LEFTJOIN('especialidades as esp','espTipo.Especialidad_id','esp.id')
        ->LEFTJOIN('conductas as f','cita_paciente.id','f.Citapaciente_id')
        ->LEFTJOIN('imagenologias as imag','cita_paciente.id','imag.Citapaciente_id')
        ->LEFTJOIN('cups as cups','cita_paciente.Cup_id','cups.id')
        ->LEFTJOIN('users as i','cita_paciente.Usuario_id','i.id')
        ->LEFTJOIN('users as tt','z.Medico_id','tt.id')
        ->LEFTJOIN('patologias as pat','cita_paciente.id','pat.CitaPaciente_id')
        ->LEFTJOIN('notaenfermerias as nts','cita_paciente.id','nts.cita_paciente_id')
        ->where('cita_paciente.id', self::$citaPaciente['id'])->first();

        self::$ordenamientoMedica = citapaciente::Select('detaarticulordens.Cantidadpormedico', 'detaarticulordens.Observacion', 'estadoMedicamento.Nombre as EstadoMedi', 'codesumis.Nombre as Medicamento')
            ->join('ordens', 'ordens.Cita_id', 'cita_paciente.id')
            ->leftjoin('detaarticulordens', 'detaarticulordens.Orden_id', 'ordens.id')
            ->leftjoin('codesumis', 'codesumis.id', 'detaarticulordens.codesumi_id')
            ->leftjoin('estados as estadoMedicamento', 'estadoMedicamento.id', 'detaarticulordens.Estado_id')
            ->where('ordens.Tiporden_id', 3)
            ->where('cita_paciente.id', self::$data['id'])->get();
        self::$ordenamientoServ = citapaciente::Select('cups.Nombre as Servicio', 'estadoCups.Nombre as EstadoServi', 'cupordens.Cantidad as CantidadServi')
            ->join('ordens', 'ordens.Cita_id', 'cita_paciente.id')
            ->leftjoin('cupordens', 'cupordens.Orden_id', 'ordens.id')
            ->leftjoin('cups', 'cups.id', 'cupordens.Cup_id')
            ->leftjoin('estados as estadoCups', 'estadoCups.id', 'cupordens.Estado_id')
            ->whereIn('ordens.Tiporden_id', [2, 4])
            ->where('cita_paciente.id', self::$data['id'])->get();
        self::$notaAclaratoria = NotaAclaratoria::select('nota_aclaratorias.*','users.name','users.apellido')
            ->join('users','users.id','nota_aclaratorias.users_id')
            ->where('Citapaciente_id',self::$data['id'])->get();
        //self::$toxicologicos = AntecedenteToxicologico::where('Citapaciente_id', self::$data['id'])->first();
        $pdf = new HistoriaClinica('P','mm','A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();

        if($datos['Tipocita_id'] == '8' || $datos['Tipocita_id'] == '2' || $datos['Tipocita_id'] == '10' || $datos['Tipocita_id'] == '4'){
            $this->bodyHistoriaClinica($pdf);
        }elseif($datos['Tipocita_id'] == '12'){
            $this->bodyOftalmologia($pdf);
        }elseif($datos['Tipocita_id'] == '7'){
            $this->bodyHistoriaClinicaOncologia($pdf);
        }elseif ($datos['Tipocita_id'] == '6'){
            $this->bodyHistoriaClinicaImagenologia($pdf);
        }
        $pdf->Output();

    }
    public function header()
    {
        $this->SetDrawColor(140,190,56);
        $this->Rect(5, 5, 200, 287);
        $this->SetDrawColor(0,0,0);
    }

    public function footer()
    {
        $this->SetXY(190,287);

        $this->Cell(10,4,utf8_decode('Página ').$this->PageNo().' de {nb}',0,0,'C');
    }

    public function bodyOftalmologia($pdf){
        $Y = 40;
        $pdf->SetDrawColor(140,190,56);
        $pdf->Rect(5, 5, 200, 287);
        $pdf->SetDrawColor(0,0,0);

        $pdf->SetFont('Arial', 'B', 9);
        $logo = "images/logo.png";
        $pdf->Image($logo, 16, 9, -220);
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetXY(8, 37);
        $pdf->Cell(60, 3, utf8_decode('SUMIMEDICAL S.A.S'), 0, 0, 'C');
        $pdf->SetXY(8, $Y);
        $pdf->Cell(60, 3, utf8_decode('NIT: 900033371 Res: 004 '), 0, 0, 'C');
        $pdf->SetXY(8, $Y + 3);
        $pdf->Cell(60, 3, utf8_decode('Carrera 80 c Nùmero 32EE-65'), 0, 0, 'C');
        $pdf->SetXY(8, $Y + 6);
        $pdf->Cell(60, 3, utf8_decode('Telefono: 5201040'), 0, 0, 'C');


        $pdf->SetXY(70, 10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('NOMBRE COMPLETO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$data['Nombre']), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('IDENTIFICACÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$data['Cédula']), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('CELULAR'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$data['Telefono'].' - '.self::$data['Celular']), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('#. DE HISTORIA CLINICA'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$data['id']), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('FECHA HISTORIA'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(substr(self::$data['Datetimeingreso'],0,19)), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('SEXO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$data['Sexo']), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('DIRECCÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$data['Direccion_Residencia']), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('EMAIL'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$data['Direccion_Residencia']), 1, 0, 'l');


        $pdf->SetXY(5, 70);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(200, 4, utf8_decode('HISTORIA CLINICA OFTALMOLOGICA'), 0, 0, 'C');
        $pdf->Ln();
        $pdf->SetX(62);
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->MultiCell(136, 4, utf8_decode(self::$data['Motivoconsulta']), 1,'L');
        $posicionMotivo = $pdf->GetY();
        $pdf->SetXY(12,74);
        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->Cell(50, $posicionMotivo-74, utf8_decode('MOTIVO DE CONSULTA'), 1, 0, 'C');
        $pdf->SetXY(12,$posicionMotivo);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(62);
        $pdf->MultiCell(136, 4, utf8_decode(self::$data['Enfermedadactual']), 1,'L');
        $posicionActual = $pdf->GetY();
        $pdf->SetXY(12,$posicionMotivo);
        $pdf->SetFont('Arial', 'B', 8.5);
        $pdf->MultiCell(50, (($posicionActual-$posicionMotivo)), utf8_decode('ENFERMEDAD ACTUAL'), 1, 'C');



        $antecedentes = Pacienteantecedente::where('citapaciente_id',self::$data["id"])
            ->get()
            ->toArray();
        $pdf->SetXY(12,$posicionActual);
        $pdf->SetFont('Arial', '', 8);
        $count1 = 0;
        if($antecedentes){
            foreach ($antecedentes as $antecedente){
                $pdf->SetX(62);
                $pdf->MultiCell(136, 4, utf8_decode($antecedente["Descripcion"]), 0,'L');
                $count1 = $count1+4;
            }
        }else{
            $pdf->SetX(62);
            $pdf->MultiCell(136, 4, utf8_decode(""), 0,'L');
            $count1 = 4;
        }
        $posicionPersonales = $pdf->GetY();
        $pdf->SetXY(12,$posicionActual);
        $pdf->SetFont('Arial', 'B', 8.5);
        if($count1 > 4){
            $pdf->MultiCell(50, $count1, utf8_decode('ANTECEDENTES PERSONALES'), 1,'C');
        }else{
            $pdf->Cell(50, 4, utf8_decode('ANTECEDENTES PERSONALES'),1,0,'C');
        }
        $pdf->Rect(62,$posicionActual,136,$count1);
        $antecedentesParentesco = Parentescoantecedente::where('Citapaciente_id',self::$data["id"])->get()->toArray();
        $pdf->SetXY(12,$posicionPersonales);
        $pdf->SetFont('Arial', '', 8);
        $count2 = 0;
        if($antecedentesParentesco) {
            foreach ($antecedentesParentesco as $antecedenteParentesco) {
                $pdf->SetX(62);
                $pdf->MultiCell(136, 4, utf8_decode($antecedenteParentesco["Descripcion"]), 0,'L');
                $count2 = $count2+4;
            }
        }else{
            $pdf->SetX(62);
            $pdf->MultiCell(136, 4, utf8_decode(''), 0,'L');
            $count2 = 4;
        }
        $posicionFamiliares = $pdf->GetY();
        $pdf->SetXY(12,$posicionPersonales);
        $pdf->SetFont('Arial', 'B', 8.5);
        if($count2 > 4){
            $pdf->MultiCell(50, $count2, utf8_decode('ANTECEDENTES FAMILIARES'), 1,'C');
        }else{
            $pdf->Cell(50, 4, utf8_decode('ANTECEDENTES FAMILIARES'), 1, 0, 'C');
        }
        $pdf->Rect(62,$posicionPersonales,136,$count2);
        $pdf->SetXY(12,$posicionFamiliares);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(186, 6, utf8_decode('AGUDEZA VISUAL'), 1, 0, 'C');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(62, 4, utf8_decode(''), 1, 0, 'C');
        $pdf->Cell(62, 4, utf8_decode('OJO DERECHO'), 1, 0, 'C');
        $pdf->Cell(62, 4, utf8_decode('OJO IZQUIERDO'), 1, 0, 'C');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->Cell(62, 4, utf8_decode('AVSC (CON CORRECION)'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(62, 4, utf8_decode(self::$data['avcc_ojoderecho']), 1, 0, 'C');
        $pdf->Cell(62, 4, utf8_decode(self::$data['avcc_ojoizquierdo']), 1, 0, 'C');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(62, 4, utf8_decode('AVSC (SIN CORRECION)'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(62, 4, utf8_decode(self::$data['avsc_ojoderecho']), 1, 0, 'C');
        $pdf->Cell(62, 4, utf8_decode(self::$data['avsc_ojoizquierdo']), 1, 0, 'C');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(62, 4, utf8_decode('PH (PINHOLE)'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(62, 4, utf8_decode(self::$data['ph_ojoderecho']), 1, 0, 'C');
        $pdf->Cell(62, 4, utf8_decode(self::$data['ph_ojoizquierdo']), 1, 0, 'C');

        $pdf->SetXY(12,$posicionFamiliares+26);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->MultiCell(62, 8, utf8_decode('MOTILIDAD OCULAR'), 1,'C');
        $pdf->SetXY(74,$posicionFamiliares+26);
        $pdf->Cell(30, 4, utf8_decode('OJO DERECHO'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(32, 4, utf8_decode(self::$data['motilidad_ojoderecho']), 1, 0, 'C');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 4, utf8_decode('CONVERT TEST'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(32, 4, utf8_decode(self::$data['covert_ojoderecho']), 1, 0, 'C');
        $pdf->Ln();
        $pdf->SetX(74);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 4, utf8_decode('OJO IZQUIERDO'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(32, 4, utf8_decode(self::$data['motilidad_ojoizquierdo']), 1, 0, 'C');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 4, utf8_decode('CONVERT TEST'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(32, 4, utf8_decode(self::$data['covert_ojoizquierdo']), 1, 0, 'C');
        $pdf->Ln();
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->MultiCell(186,4,'BIOMICROSCOPIA',1,'C');
        $Inicial = $pdf->GetY();
        $pdf->SetX(12);
        $pdf->Cell(93, 4, utf8_decode('OJO DERECHO'), 1, 0, 'C');
        $pdf->Cell(93, 4, utf8_decode('OJO IZQUIERDO'), 1, 0, 'C');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(93, 4, utf8_decode(self::$data['biomicros_ojoderecho']), 0, 'C');
        $y1 = $pdf->GetY();
        $pdf->SetXY(105,$posicionFamiliares+46);
        $pdf->MultiCell(93, 4, utf8_decode(self::$data['biomicros_ojoizquierdo']), 0, 'C');
        $posicionFamiliares = $pdf->GetY();

        $y = max($y1,$posicionFamiliares);
        $pdf->Ln();
        #cuadrado
        $pdf->Line(12,$y,198,$y);
        $pdf->Line(12,$Inicial,12,$y);
        $pdf->Line(198,$Inicial,198,$y);
        
        #lineas verticales
        $pdf->Line(105,$Inicial,105,$y);

        
        if($posicionFamiliares > 250){
            $pdf->AddPage();
            $posicionFamiliares = 10;
        }

        $pdf->SetXY(12,$posicionFamiliares+4);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->MultiCell(30,8,'PRESION INTRAOCULAR',1,'C');
        $pdf->SetXY(42,$posicionFamiliares+4);
        $pdf->MultiCell(20,4,'OJO DERECHO',1,'C');
        $pdf->SetXY(42,$posicionFamiliares+12);
        $pdf->MultiCell(20,4,'OJO IZQUIERDO',1,'C');
        $pdf->SetXY(62,$posicionFamiliares+4);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(25, 8, utf8_decode(self::$data['presionintra_ojoderecho']), 1, 'C');
        $pdf->SetXY(62,$posicionFamiliares+12);
        $pdf->MultiCell(25, 8, utf8_decode(self::$data['presionintra_ojoizquierdo']), 1, 'C');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(100,$posicionFamiliares+4);
        $pdf->MultiCell(30,16,'GINIOSCOPIA',1,'C');
        $pdf->SetXY(130,$posicionFamiliares+4);
        $pdf->MultiCell(30,8,'OJO DERECHO',1,'C');
        $pdf->SetXY(130,$posicionFamiliares+12);
        $pdf->MultiCell(30,8,'OJO IZQUIERDO',1,'C');
        $pdf->SetXY(160,$posicionFamiliares+4);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(38,8,utf8_decode(self::$data['gionios_ojoderecho']),1,'C');
        $pdf->SetXY(160,$posicionFamiliares+12);
        $pdf->MultiCell(38,8,utf8_decode(self::$data['gionios_ojoizquierdo']),1,'C');

        $posicionFamiliares = $pdf->GetY();
        
        if($posicionFamiliares > 250){
            $pdf->AddPage();
            $posicionFamiliares = 10;
        }

        $pdf->SetXY(12,$posicionFamiliares+4);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->MultiCell(50,8,'FONDO DE OJO',1,'C');
        $pdf->SetXY(62,$posicionFamiliares+4);
        $pdf->Cell(30, 4, utf8_decode('OJO DERECHO'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(106, 4, utf8_decode(self::$data['fondo_ojoderecho']), 1, 0, 'L');
        $pdf->SetXY(62,$posicionFamiliares+8);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 4, utf8_decode('OJO IZQUIERDO'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(106, 4, utf8_decode(self::$data['fondo_ojoizquierdo']), 1, 0, 'L');

        $posicionFamiliares = $pdf->GetY();
        
        if($posicionFamiliares > 250){
            $pdf->AddPage();
            $posicionFamiliares = 10;
        }
        $diagnosticos = Cie10paciente::select('c.Codigo_CIE10','c.Descripcion')
            ->join('cie10s as c','c.id','cie10pacientes.Cie10_id')
            ->where('Citapaciente_id',self::$data["id"])->get()->toArray();


        $pdf->SetXY(62,$posicionFamiliares);
        $pdf->SetFont('Arial', '', 8);
        $count3 = 0;
        if($diagnosticos){
            foreach ($diagnosticos as $diagnostico){
                $pdf->SetX(62);
                $pdf->MultiCell(136,4,utf8_decode($diagnostico['Codigo_CIE10']."-".$diagnostico["Descripcion"]),1,'C');
                $count3 = $count3+4;
            }
        }else{
            $pdf->SetX(62);
            $pdf->MultiCell(136,4,utf8_decode(""),1,'C');
            $count3 = 4;
        }
        $posicionDiagnostico = $pdf->GetY();

        $pdf->SetXY(12,$posicionFamiliares);
        $pdf->SetFont('Arial', 'B', 9);
        if($count3 > 4){
            $pdf->MultiCell(50,$count3,'DIAGNOSTICO(S)',1,'C');
        }else{
            $pdf->Cell(50, 4, utf8_decode('DIAGNOSTICO(S)'), 1, 0, 'C');
        }


        $conducta = Conducta::where('Citapaciente_id',self::$data["id"])->first()->toArray();


        $pdf->SetXY(62,($posicionDiagnostico)+4);
        $pdf->SetFont('Arial', '', 8);
        if($conducta){
            $pdf->MultiCell(136, 4, utf8_decode($conducta["Planmanejo"]), 1,'L');
        }else{
            $pdf->MultiCell(136, 4, utf8_decode(""), 1,'L');
        }
        $posicionConducta = $pdf->GetY();

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(12,($posicionDiagnostico)+4);
        $pdf->MultiCell(50,($posicionConducta-$posicionDiagnostico)-4,'CONDUCTA',1,'C');
    }

    public function bodyHistoriaClinica($pdf)
    {
        #IMAGEN Y DATOS EMPRESARIALES
        $pdf->SetFont('Arial', 'B', 9);

        if(self::$data["Registromedico"] == '1128050718') {
            $logo = public_path() . "/images/victoriana.png";
            $pdf->Image($logo, 16, 9, -500);
        }else{
            $logo = public_path() . "/images/logo.png";
            $pdf->Image($logo, 16, 9, -220);
        }



        $pdf->SetFont('Arial', '', 7);
        $pdf->SetXY(8, 37);
        $pdf->Cell(60, 3, utf8_decode('NIT:900033371-4 Res: 004 '), 0, 0, 'C');

        $informacionInicial = citapaciente::select(['ta.name as Nombre','cita_paciente.Datetimeingreso','s.Nombre as tipo'])
            ->join('citas as c','cita_paciente.Cita_id','c.id')
            ->join('agendas as a','c.Agenda_id','a.id')
            ->join('especialidade_tipoagenda as et','a.Especialidad_id','et.id')
            ->join('tipo_agendas as ta','et.Tipoagenda_id','ta.id')
            ->leftJoin('consultorios as cs','a.Consultorio_id','cs.id')
            ->leftJoin('sedes as s','cs.Sede_id','s.id')
            ->where('cita_paciente.id',self::$data['id'])
            ->first();
        $punto = "";
        $realizada ="Consulta No Programada";
        $fechaConsulta = citapaciente::find(self::$data['id'])->updated_at;
        if($informacionInicial){
            if($informacionInicial->tipo){
                $punto = ucwords(strtolower($informacionInicial->tipo));
            }
            if($informacionInicial->Nombre){
                $realizada = $informacionInicial->Nombre;
            }
            if($informacionInicial->Datetimeingreso){
                $fechaConsulta = $informacionInicial->Datetimeingreso;
            }
        }


        $pdf->SetXY(25, 18);
        $pdf->SetFont('Arial', 'B', 30);
        $pdf->Cell(192, 4, utf8_decode('HISTORIA CLÍNICA'), 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(70, 25);
        $pdf->Cell(46, 4, utf8_decode('PUNTO DE ATENCIÓN:'), 0, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        if(self::$data["Registromedico"] == '1128050718') {
            $pdf->MultiCell(90, 4, utf8_decode("Clinica Victoriana"), 0,'L'); #Donde fue atendido el paciente
        }else{
            $pdf->MultiCell(90, 4, utf8_decode($punto), 0,'L'); #Donde fue atendido el paciente
        }
        $pdf->SetXY(72, 29);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(44, 4, utf8_decode('CONSULTA REALIZADA:'), 0, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(56, 4, utf8_decode($realizada), 0, 0,'L');#ACTIVIDAD DE LA CITA
        $pdf->SetXY(70, 33);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(46, 4, utf8_decode('FECHA DE CONSULTA:'), 0, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(46, 4, utf8_decode($fechaConsulta),0,'L'); #Feha y hora


        #DATOS PERSONALES
        $pdf->SetXY(12,45);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('DATOS DEL USUARIO'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $paciente = Paciente::where('Num_Doc',self::$data["Cédula"])->first();

        $pdf->SetXY(12, 49);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('NOMBRE COMPLETO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Nombre"]), 1, 0, 'l');
        $pdf->SetXY(105,49);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('IDENTIFICACIÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Cédula"]), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('FECHA DE NACIMIENTO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Fecha_Nacimiento"]), 1, 0, 'l');
        $pdf->SetXY(105,53);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('TIPO DE IDENTIFICACIÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode($paciente->Tipo_Doc), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('EDAD'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Edad"]), 1, 0, 'l');
        $pdf->SetXY(105,57);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('SEXO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Sexo"]), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('OCUPACIÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode($paciente->Ocupacion), 1, 0, 'l');
        $pdf->SetXY(105,61);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('DIRECCIÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Direccion_Residencia"]), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('TELÉFONO DEL DOMICILIO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Telefono"]."-".self::$data["Celular"]), 1, 0, 'l');
        $pdf->SetXY(105,65);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('LUGAR DE RESIDENCIA'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Municipio_Afiliado"]), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('NOMBRE DEL ACOMPAÑANTE'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');
        $pdf->SetXY(105,69);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('TELÉFONO DEL ACOMPAÑANTE'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('NOMBRE DEL RESPONSABLE'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');
        $pdf->SetXY(105,73);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('TELÉFONO DEL RESPONSABLE'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('PARENTESCO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');
        $pdf->SetXY(105,77);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('ASEGURADORA'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('TIPO DE VINCULACIÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');
        $pdf->SetXY(105,81);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('N° ATENCIÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');




        $pdf->SetXY(12, 88);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(186, 4, utf8_decode('ANAMNESIS'), 1, 0, 'C', 1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('MOTIVO DE CONSULTA'), 1, 0, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["Motivoconsulta"]), 1, "L", 0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(186, 4, utf8_decode('ENFERMEDAD ACTUAL'), 1, 0, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["Enfermedadactual"]), 1, "L", 0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(186, 4, utf8_decode('RESULTADOS DE AYUDAS DIAGNÓSTICAS'), 1, 0, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["Resultayudadiagnostica"]), 1, "L", 0);
        $pdf->SetX(12);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(186, 4, utf8_decode('REVISIÓN POR SISTEMAS'), 1, 0, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $revisionInicial = $pdf->GetY();
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 4, utf8_decode('OFTALMOLÓGICO:'), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(156, 4, utf8_decode(self::$data["Oftalmologico"]), 0, "L", 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 4, utf8_decode('GENITOURINARIO:'), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(156, 4, utf8_decode(self::$data["Genitourinario"]), 0, "L", 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(40, 4, utf8_decode('OTORRINOLARINGÓLOGO:'), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(146, 4, utf8_decode(self::$data["Otorrinoralingologico"]),  0, "L", 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 4, utf8_decode('LINFÁTICO:'), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(156, 4, utf8_decode(self::$data["Linfatico"]),  0, "L", 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(35, 4, utf8_decode('OSTEOMIOARTICULAR:'), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(151, 4, utf8_decode(self::$data["Osteomioarticular"]),  0, "L", 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 4, utf8_decode('NEUROLÓGICO:'), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(156, 4, utf8_decode(self::$data["neuro123"]='null'?self::$data["Neurologico"]: ''),  0, "L", 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 4, utf8_decode('CARDIOVASCULAR:'), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(156, 4, utf8_decode(self::$data["Cardiovascular"]),  0, "L", 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 4, utf8_decode('TEGUMENTARIO:'), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(156, 4, utf8_decode(self::$data["Tegumentario"]),  0, "L", 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 4, utf8_decode('RESPIRATORIO:'), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(156, 4, utf8_decode(self::$data["Respiratorio"]),  0, "L", 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(32, 4, utf8_decode('ENDOCRINOLÓGICO:'), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(154, 4, utf8_decode(self::$data["Endocrinologico"]),  0, "L", 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(32, 4, utf8_decode('GASTROINTESTINAL:'), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(154, 4, utf8_decode(self::$data["Gastrointestinal"]),  0, "L", 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 4, utf8_decode('OTROS:'),0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(156, 4, utf8_decode(self::$data["Norefiere"]), 0, "L", 0);
        $pdf->Ln();

        $pdf->Line(198,$pdf->GetY(),198,$revisionInicial);
        $pdf->Line(12,$pdf->GetY(),12,$revisionInicial);

        // $pdf->SetX(12);
        // $pdf->SetFont('Arial', 'B', 9);
        // $pdf->SetDrawColor(0, 0, 0);
        // $pdf->SetFillColor(214, 214, 214);
        // $pdf->SetTextColor(0, 0, 0);
        // $pdf->Cell(186, 4, utf8_decode('ANTECEDENTES TOXICOLOGICO'), 1, 0, 'C', 1);
        // $pdf->SetTextColor(0, 0, 0);
        // $pdf->SetDrawColor(0, 0, 0);
        // $pdf->Ln();
        // $pdf->SetX(12);
        // $revisionInicial = $pdf->GetY();
        // $pdf->SetFont('Arial', 'B', 8);
        // $pdf->Cell(30, 4, utf8_decode('MEDICAMENTOS:'), 0, 0, 'L', 0);
        // $pdf->SetFont('Arial', '', 8);
        // $pdf->MultiCell(156, 4, utf8_decode(self::$toxicologicos["medicamento"]), 0, 'L', 0);
        // $pdf->Ln();
        // $pdf->SetX(12);
        // $pdf->SetFont('Arial', 'B', 8);
        // $pdf->Cell(30, 4, utf8_decode('ALIMENTARIOS:'), 0, 0, 'L', 0);
        // $pdf->SetFont('Arial', '', 8);
        // $pdf->MultiCell(156, 4, utf8_decode(self::$toxicologicos["Alimentarios"]),  0, 'L', 0);
        // $pdf->Ln();
        // $pdf->SetX(12);
        // $pdf->SetFont('Arial', 'B', 8);
        // $pdf->Cell(30, 4, utf8_decode('SPA:'), 0, 0, 'L', 0);
        // $pdf->SetFont('Arial', '', 8);
        // $pdf->MultiCell(156, 4, utf8_decode(self::$toxicologicos["SPA"]), 0, 'L', 0);
        // $pdf->Ln();
        // $pdf->SetX(12);
        // $pdf->SetFont('Arial', 'B', 8);
        // $pdf->Cell(30, 4, utf8_decode('ALCOHOL:'), 0, 0, 'L', 0);
        // $pdf->SetFont('Arial', '', 8);
        // $pdf->MultiCell(156, 4, utf8_decode(self::$toxicologicos["Alcohol"]), 0, 'L', 0);
        // $pdf->Ln();
        // $pdf->SetX(12);
        // $pdf->SetFont('Arial', 'B', 8);
        // $pdf->Cell(35, 4, utf8_decode('OTRAS SUSTANCIAS:'), 0, 0, 'L', 0);
        // $pdf->SetFont('Arial', '', 8);
        // $pdf->MultiCell(151, 4, utf8_decode(self::$toxicologicos["OtrasSustacias"]), 0, 'L', 0);
        // $pdf->Ln();
        // $pdf->Line(198,$pdf->GetY(),198,$revisionInicial);
        // $pdf->Line(12,$pdf->GetY(),12,$revisionInicial);

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0, 0, 0);

        $antecedentesPersonales = Pacienteantecedente::select(
            'pacienteantecedentes.created_at AS fecha',
             DB::raw("CONCAT(u.name,' ',u.apellido) AS medico"), 'a.Nombre AS antecedente', 'cie.Descripcion AS antecedenteCie',
             'pacienteantecedentes.Descripcion AS descripcion')
            ->leftjoin('antecedentes as a', 'a.id', 'pacienteantecedentes.Antecedente_id')
            ->leftjoin('cie10s as cie', 'cie.id', 'pacienteantecedentes.cie10_id')
            ->join('cita_paciente as c', 'c.id', 'pacienteantecedentes.citapaciente_id')
            ->join('pacientes as p', 'p.id', 'c.Paciente_id')
            ->join('users as u', 'u.id', 'pacienteantecedentes.Usuario_id')
            ->where('p.id', self::$data["Paciente_id"])->get();

        $pdf->Cell(186, 4, utf8_decode('ANTECEDENTES PERSONALES'), 1, 0, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(24, 4, utf8_decode('FECHA'), 1, 0, 'C', 1);
        $pdf->Cell(30, 4, utf8_decode('MÉDICO'), 1, 0, 'C', 1);
        $pdf->Cell(78, 4, utf8_decode('DX'), 1, 0, 'C', 1);
        $pdf->Cell(54, 4, utf8_decode('DESCRIPCIÓN'), 1, 0, 'C', 1);
        $pdf->Ln();
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        foreach ($antecedentesPersonales as $antedentes) {
            $pdf->Cell(46.5, 4, utf8_decode(substr($antedentes->fecha, 0, 10)), 0, 0, 'C', 0);
            $pdf->Cell(46.5, 4, utf8_decode($antedentes->medico), 0, 0, 'L', 0);
            $pdf->Cell(46.5, 4, utf8_decode($antedentes->antecedente?$antedentes->antecedente:$antedentes->antecedenteCie), 0, 0, 'L', 0);
            $pdf->MultiCell(46.5, 4, utf8_decode($antedentes->descripcion), 0,'L');
            $pdf->Ln();
            $pdf->Line(12,$pdf->GetY(),198,$pdf->GetY());
            $pdf->SetX(12);
        }

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0, 0, 0);

        $anteFarmacologia = AntecedenteFarmacologico::select(['antecedente_farmacologicos.id','antecedente_farmacologicos.created_at',
        'antecedente_farmacologicos.utiempo','antecedente_farmacologicos.frecuencia',
        'antecedente_farmacologicos.alergia','antecedente_farmacologicos.observacionAlergia', 'd.Producto'])
        ->leftjoin('cita_paciente as cp', 'cp.id', 'antecedente_farmacologicos.Citapaciente_id')
        ->leftjoin('detallearticulos as d', 'antecedente_farmacologicos.detallearticulo_id', 'd.id')
        ->where('cp.Paciente_id', self::$data["Paciente_id"])
        ->where('antecedente_farmacologicos.estado_id', 1)
        ->get();

        // farmacologicos
        $pdf->Cell(186, 4, utf8_decode('ANTECEDENTES FARMACOLOGICOS'), 1, 0, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(56, 4, utf8_decode('Medicamento'), 1, 0, 'C', 1);
        $pdf->Cell(24, 4, utf8_decode('U-TIEMPO'), 1, 0, 'C', 1);
        $pdf->Cell(30, 4, utf8_decode('FRECUENCIA'), 1, 0, 'C', 1);
        $pdf->Cell(20, 4, utf8_decode('ALEGIA'), 1, 0, 'C', 1);
        $pdf->Cell(56, 4, utf8_decode('OBSERVACIÓN'), 1, 0, 'C', 1);
        $pdf->Ln();
        $y = $pdf->GetY();
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 8);

        // dd($y);
        foreach ($anteFarmacologia as $farma) {
            $pdf->SetXY(12,$y);
            $pdf->MultiCell(56, 4, utf8_decode($farma->Producto), 0,'L');
            $y1 = $pdf->GetY();
            $pdf->SetXY(68,$y1-4);
            $pdf->Cell(24, 4, utf8_decode($farma->utiempo), 0, 0, 'L', 0);
            $pdf->Cell(30, 4, utf8_decode($farma->frecuencia), 0, 0, 'L', 0);
            $pdf->Cell(20, 4, utf8_decode($farma->alergia), 0,'L');
            $pdf->MultiCell(56, 4, utf8_decode($farma->observacionAlergia), 0,'L');
            $y2 = $pdf->GetY();
            $pdf->Line(12,$y1,198,$y1);
            $y = $y2;

        }
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0, 0, 0);

        $antecedentesParentesto = Parentescoantecedente::select('parentescoantecedentes.created_at AS fecha',
         'u.name AS medico', 'cie.Descripcion as cie10ss', 'p.Nombre as familiar', 'parentescoantecedentes.Descripcion AS descripcion')
            ->leftjoin('cita_paciente as c', 'c.id', 'parentescoantecedentes.Citapaciente_id')
            ->leftjoin('cie10s as cie', 'parentescoantecedentes.cie10_id', 'cie.id')
            ->leftjoin('parentescos as p', 'parentescoantecedentes.parentesco_id', 'p.id')
            ->join('users as u', 'u.id', 'parentescoantecedentes.Usuario_id')
            // ->whereNotNull('parentescoantecedentes.cie10_id')
            ->where('c.Paciente_id', self::$data["Paciente_id"])
            ->orderBy('parentescoantecedentes.created_at', 'desc')
            ->get();
        $pdf->Cell(186, 4, utf8_decode('ANTECEDENTES FAMILIARES'), 1, 0, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(19, 4, utf8_decode('FECHA'), 1, 0, 'C', 1);
        $pdf->Cell(30, 4, utf8_decode('MÉDICO'), 1, 0, 'C', 1);
        $pdf->Cell(63, 4, utf8_decode('DX'), 1, 0, 'C', 1);
        $pdf->Cell(20, 4, utf8_decode('FAMILIAR'), 1, 0, 'C', 1);
        $pdf->Cell(54, 4, utf8_decode('DESCRIPCIÓN'), 1, 0, 'C', 1);
        $pdf->Ln();
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        foreach ($antecedentesParentesto as $antedentes) {
            $pdf->Cell(19, 4, utf8_decode(substr($antedentes->fecha, 0, 10)), 1, 0, 'L', 0);
            $pdf->Cell(30, 4, utf8_decode($antedentes->medico), 1, 0, 'L', 0);
            // $pdf->Cell(63, 4, utf8_decode($antedentes->cie10ss), 1, 0, 'L', 0);
            $pdf->Cell(20, 4, utf8_decode($antedentes->familiar), 1, 0, 'L', 0);
            $pdf->Cell(54, 4, utf8_decode($antedentes->descripcion), 1, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetX(12);
        }


        if (self::$data["Sexo"] == 'F') {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(186, 4, utf8_decode('ANTECEDENTES GINECO OBSTÉTRICOS'), 1, 0, 'C', 1);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->Ln();
            $pdf->SetX(12);
            $ginecoInicial = $pdf->GetY();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(10, 4, utf8_decode('FUM:'), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(176, 4, utf8_decode(self::$data["Fechaultimamenstruacion"]), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(20, 4, utf8_decode('GESTANTE:'), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(166, 4, utf8_decode(self::$data["Gestaciones"]), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(15, 4, utf8_decode('PARTOS:'), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(171, 4, utf8_decode(self::$data["Partos"]), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(35, 4, utf8_decode('ABORTO PROVOCADO:'), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(151, 4, utf8_decode(self::$data["Abortoprovocado"]), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(36, 4, utf8_decode('ABORTO ESPONTÁNEO:'), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(150, 4, utf8_decode(self::$data["Abortoespontaneo"]), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(20, 4, utf8_decode('MORTINATO:'), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(166, 4, utf8_decode(self::$data["Mortinato"]), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(20, 4, utf8_decode('EUTÓXICO:'), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(166, 4, utf8_decode(self::$data["Eutoxico"]), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(20, 4, utf8_decode('CESÁREAS:'), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(166, 4, utf8_decode(self::$data["Cesaria"]), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(41, 4, utf8_decode('CÁNCER CUELLO UTERINO:'), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(145, 4, utf8_decode(self::$data["Cancercuellouterino"]), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(20, 4, utf8_decode('MENARQUÍA:'), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(166, 4, utf8_decode(self::$data["Menarquia"]), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(20, 4, utf8_decode('CICLOS:'), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(166, 4, utf8_decode(self::$data["Ciclos"]), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(20, 4, utf8_decode('REGULARES:'), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(166, 4, utf8_decode(self::$data["Regulares"]), 0, 0, 'L', 0);
            $pdf->Ln();
            $pdf->Line(198,$pdf->GetY(),198,$ginecoInicial);
            $pdf->Line(12,$pdf->GetY(),12,$ginecoInicial);
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(93, 4, utf8_decode('GESTANTE'), 1, 0, 'C', 1);
            $pdf->Cell(93, 4, utf8_decode('CITOLOGÍA'), 1, 0, 'C', 1);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(46.5, 4, utf8_decode('FECHA PROBABLE PARTO'), 1, 0, 'C', 0);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(46.5, 4, utf8_decode(self::$data["Gestantefechaparto"]), 1, 0, 'C', 0);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(46.5, 4, utf8_decode('ÚLTIMA CITOLOGIA'), 1, 0, 'C', 0);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(46.5, 4, utf8_decode(self::$data["Ultimacitologia"]), 1, 0, 'C', 0);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(46.5, 4, utf8_decode('# CONTROL PRENATAL'), 1, 0, 'C', 0);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(46.5, 4, utf8_decode(self::$data["Gestantenumeroctrlprenatal"]), 1, 0, 'C', 0);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(46.5, 4, utf8_decode('RESULTADO'), 1, 0, 'C', 0);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(46.5, 4, utf8_decode(self::$data["Resultado"]), 1, 0, 'C', 0);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(186, 4, utf8_decode('OBSERVACIÓNES:'), 1, 0, 'L', 0);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(186, 4, utf8_decode(self::$data["Observaciongineco"]), 1, "L", 0);
            $pdf->SetX(12);
        }
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('ESTILOS DE VIDA'), 1, 0, 'C',1);
        $pdf->Ln();
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('¿TIENE DIETA SALUDABLE?'), 0, 0, 'L',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(139.5, 4, utf8_decode(self::$data["Dietasaludable"]),  0, "L", 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('¿TIENE SUEÑO REPARADOR?'), 0, 0, 'L',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(139.5, 4, utf8_decode(self::$data["Suenoreparador"]), 0, "L", 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('¿DUERME MENOS DE 6 HORAS?'), 0, 0, 'L',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(139.5, 4, utf8_decode(self::$data["Duermemenoseishoras"]), 0, "L", 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('¿ALTO NIVEL DE ESTRES?'), 0, 0, 'L',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(139.5, 4, utf8_decode(self::$data["Altonivelestres"]), 0, "L", 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('¿REALIZA ACTIVIDAD FÍSICA?'), 0, 0, 'C',1);
        $pdf->Ln();
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(62, 4, utf8_decode('ACTIVIDAD FÍSICA'), 1, 0, 'C',0);
        $pdf->Cell(62, 4, utf8_decode('DURACIÓN'), 1, 0, 'C',0);
        $pdf->Cell(62, 4, utf8_decode('FRECUENCIA'), 1, 0, 'C',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(62, 4, utf8_decode(self::$data["Actividadfisica"]), 1, 0, 'C',0);
        $pdf->Cell(62, 4, utf8_decode(self::$data["Duracion"]), 1, 0, 'C',0);
        $pdf->Cell(62, 4, utf8_decode(self::$data["Frecuenciasemana"]), 1, 0, 'C',0);
        $pdf->Ln();
        //dd(self::$data["Actividadfisica"]);


        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('¿TIENE EXPOSICIÓN AL TABACO?'), 1, 0, 'C',1);
        $pdf->Ln();
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(93, 4, utf8_decode('CANTIDAD'), 1, 0, 'C',0);
        $pdf->Cell(93, 4, utf8_decode('FUMA INICIO'), 1, 0, 'C',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(93, 4, utf8_decode(self::$data["Fumacantidad"]), 1, 0, 'C',0);
        $pdf->Cell(93, 4, utf8_decode(self::$data["Fumainicio"]), 1, 0, 'C',0);
        $pdf->Ln();
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(93, 4, utf8_decode('FUMADOR PASIVO'), 1, 0, 'C',0);
        $pdf->Cell(93, 4, utf8_decode('INICIO DE CONSUMO'), 1, 0, 'C',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(93, 4, utf8_decode(self::$data["Fumadorpasivo"]), 1, 0, 'C',0);
        $pdf->Cell(93, 4, utf8_decode(self::$data["Fumainicio"]), 1, 0, 'C',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('¿CONSUME SPA?'), 1, 0, 'C',1);
        $pdf->Ln();
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(93, 4, utf8_decode('FECHA DE INCIO '), 1, 0, 'C',0);
        $pdf->Cell(93, 4, utf8_decode('CANTIDAD'), 1, 0, 'C',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(93, 4, utf8_decode(self::$data["Consumopsicoactivo"]), 1, 0, 'C',0);
        $pdf->Cell(93, 4, utf8_decode(self::$data["Psicoactivocantidad"]), 1, 0, 'C',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('¿CONSUME LICOR?'), 1, 0, 'C',1);
        $pdf->Ln();
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(93, 4, utf8_decode('CANTIDAD'), 1, 0, 'C',0);
        $pdf->Cell(93, 4, utf8_decode('FRECUENCIA'), 1, 0, 'C',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(93, 4, utf8_decode(self::$data["Cantidadlicor"]), 1, 0, 'C',0);
        $pdf->Cell(93, 4, utf8_decode(self::$data["Licorfrecuencia"]), 1, 0, 'C',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('OBSERVACIONES'), 1, 0, 'C',1);
        $pdf->Ln();
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode(""), 1, "L", 0);

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('EXAMEN FÍSICO'), 1, 0, 'C',1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('MEDIDAS ANTROPOMÉTRICAS'), 1, 0, 'C',1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(186, 4, utf8_decode(self::$data["Antropometricas"]), 1, 0, 'l',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('SIGNOS VITALES'), 1, 0, 'C',1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(186, 4, utf8_decode(self::$data["Signos_Vitales"]), 1, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode(self::$data["Otros_Signos_Vitales"]), 1, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode(self::$data["Presión_Arterial"]), 1, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('EXAMEN FÍSICO'), 1, 0, 'C',1);
        $pdf->Ln();
        $examenInicial = $pdf->GetY();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(186, 4, utf8_decode("CONDICIONES GENERALES:"), 0, 0, 'L',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["Condiciongeneral"]), 0,'L');
        $pdf->Line(12,$pdf->GetY(),198,$pdf->GetY());
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(35, 4, utf8_decode("CABEZA CUELLO:"), 0, 0, 'L',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(151, 4, utf8_decode(self::$data["Cabezacuello"]), 0,'L');
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(35, 4, utf8_decode("CARDIOPULMONAR:"), 0, 0, 'L',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(151, 4, utf8_decode(self::$data["Cardiopulmonar"]), 0,'L');
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(35, 4, utf8_decode("EXTREMIDADES:"), 0, 0, 'L',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(151, 4, utf8_decode(self::$data["Extremidades"]), 0,'L');
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(45, 4, utf8_decode("REFLEJO OSTEOTENDINOSO:"), 0, 0, 'L',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(141, 4, utf8_decode(self::$data["Reflejososteotendinos"]), 0,'L');
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(35, 4, utf8_decode("EXAMEN DE MAMA:"), 0, 0, 'L',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(151, 4, utf8_decode(self::$data["Examenmama"]), 0,'L');
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(40, 4, utf8_decode("OJOS Y FONDO DE OJOS:"), 0, 0, 'L',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(146, 4, utf8_decode(self::$data["Ojosfondojos"]), 0,'L');
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(35, 4, utf8_decode("ABDOMEN:"), 0, 0, 'L',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(151, 4, utf8_decode(self::$data["Abdomen"]), 0,'L');
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(35, 4, utf8_decode("PULSOS PERIFÉRICOS:"), 0, 0, 'L',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(151, 4, utf8_decode(self::$data["Pulsosperifericos"]), 0,'L');
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(35, 4, utf8_decode("PIEL Y FANERAS:"), 0, 0, 'L',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(151, 4, utf8_decode(self::$data["Pielfraneras"]), 0,'L');
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(35, 4, utf8_decode("TACTO RECTAL:"), 0, 0, 'L',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(151, 4, utf8_decode(self::$data["Tactoretal"]), 0,'L');
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(35, 4, utf8_decode("AGUDEZA VISUAL:"), 0, 0, 'L',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(151, 4, utf8_decode(self::$data["Agudezavisual"]), 0,'L');
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(35, 4, utf8_decode("OSTEOMUSCULAR:"), 0, 0, 'L',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(151, 4, utf8_decode(self::$data["Osteomuscular"]), 0,'L');
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(35, 4, utf8_decode("NEUROLÓGICOS:"), 0, 0, 'L',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(151, 4, utf8_decode(self::$data["Neurologico"]), 0,'L');
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(35, 4, utf8_decode("GENITOURINARIO:"), 0, 0, 'L',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(151, 4, utf8_decode(self::$data["Genitourinario"]), 0,'L');
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(35, 4, utf8_decode("EXAMEN MENTAL:"), 0, 0, 'L',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(151, 4, utf8_decode(self::$data["Examenmental"]), 0,'L');
        $pdf->SetX(12);

//        $pdf->Line(198,$pdf->GetY(),198,$examenInicial);
//        $pdf->Line(12,$pdf->GetY(),12,$examenInicial);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('DIAGNÓSTICOS'), 1, 0, 'C',1);
        $pdf->Ln();
        $pdf->SetX(12);
        $diagnosticoPrincipal = Cie10paciente::select(['c.Codigo_CIE10 AS codigo','c.Descripcion AS descripcion','cie10pacientes.Tipodiagnostico AS tipo'])->join('cie10s as c','c.id','cie10pacientes.Cie10_id')->where('cie10pacientes.Citapaciente_id',self::$data["id"])->where('cie10pacientes.Esprimario',true)->first();
        $diagnosticoSecundario = Cie10paciente::select(['c.Codigo_CIE10 AS codigo','c.Descripcion AS descripcion','cie10pacientes.Tipodiagnostico AS tipo'])->join('cie10s as c','c.id','cie10pacientes.Cie10_id')->where('cie10pacientes.Citapaciente_id',self::$data["id"])->where('cie10pacientes.Esprimario',false)->get();

        $pdf->Cell(186, 4, utf8_decode('DIAGNÓSTICO PRINCIPAL'), 1, 0, 'C',1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(62, 4, utf8_decode("CÓDIGO CIE10"), 1, 0, 'C',0);
        $pdf->Cell(62, 4, utf8_decode("DESCRIPCION DEL DIAGNÓSTICO"), 1, 0, 'C',0);
        $pdf->Cell(62, 4, utf8_decode("TIPO DEL DIAGNÓSTICO"), 1, 0, 'C',0);
        $pdf->Ln();
        $inicioDiagnostico = $pdf->GetY();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(12,$inicioDiagnostico);
        $pdf->MultiCell(62, 4, utf8_decode(isset($diagnosticoPrincipal->codigo)?$diagnosticoPrincipal->codigo:""), 0,'L');
        $pdf->SetXY(74,$inicioDiagnostico);
        $pdf->MultiCell(62, 4, utf8_decode(isset($diagnosticoPrincipal->descripcion)?$diagnosticoPrincipal->descripcion:""), 0,'L');
        $pdf->SetXY(136,$inicioDiagnostico);
        $pdf->MultiCell(62, 4, utf8_decode(isset($diagnosticoPrincipal->tipo)?$diagnosticoPrincipal->tipo:""), 0,'L');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('DIAGNÓSTICOS SEGUNDARIOS'), 1, 0, 'C',1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(62, 4, utf8_decode("CÓDIGO CIE10"), 1, 0, 'C',0);
        $pdf->Cell(62, 4, utf8_decode("DESCRIPCION DEL DIAGNÓSTICO"), 1, 0, 'C',0);
        $pdf->Cell(62, 4, utf8_decode("TIPO DEL DIAGNÓSTICO"), 1, 0, 'C',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        foreach ($diagnosticoSecundario as $diagnostico) {
            $pdf->Cell(62, 4, utf8_decode(isset($diagnostico->codigo)?$diagnostico->codigo:""), 1, 0, 'C', 0);
            $pdf->Cell(62, 4, utf8_decode(isset($diagnostico->descripcion)?$diagnostico->descripcion:""), 1, 0, 'C', 0);
            $pdf->Cell(62, 4, utf8_decode(isset($diagnostico->tipo)?$diagnostico->tipo:""), 1, 0, 'C', 0);
            $pdf->Ln();
            $pdf->SetX(12);
        }
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('CONDUCTA'), 1, 0, 'C',1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(186, 4, utf8_decode("PLAN DE MANEJO:"), 0, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        if(self::$data["Registromedico"] == '1128050718' || self::$data["Registromedico"] == '30239874') {
            $pdf->MultiCell(186, 4, utf8_decode(self::$data["nota"]), 0,'L');
        }else{
            $pdf->MultiCell(186, 4, utf8_decode(self::$data["Planmanejo"]), 0,'L');

        }
        $pdf->Line(12,$pdf->GetY(),198,$pdf->GetY());
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(186, 4, utf8_decode("RECOMENDACIONES:"), 0, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["Recomendaciones"]), 0,'L');
        $pdf->SetX(12);
        $pdf->Line(12,$pdf->GetY(),198,$pdf->GetY());
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(38, 4, utf8_decode("DESTINO DEL PACIENTE:"), 0, 0, 'L',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(148, 4, utf8_decode(self::$data["Destinopaciente"]), 0, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->Line(12,$pdf->GetY(),198,$pdf->GetY());
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(186, 4, utf8_decode("FINALIDAD:"), 0, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["Finalidad"]), 0,'L');
        $pdf->Line(12,$pdf->GetY(),198,$pdf->GetY());
        $posicionActual = $pdf->GetY();
        $pdf->Ln();
        $pdf->SetX(8);
        $pdf->SetFont('Arial', 'B',10);
        $pdf->Cell(195, 6, utf8_decode('Prescripción de medicamentos'), 0, 0, 'l');
        $pdf->SetLineWidth(0.5);
        $pdf->Line(8, $posicionActual+13, 203, $posicionActual+13);
        $pdf->SetLineWidth(0.2);
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetX(8);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(125, 5, utf8_decode('Medicamento'), 0, 0, 'l',1);
        $pdf->Cell(70, 5, utf8_decode('Cantidad'), 0, 0, 'l',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $posicionActual = $pdf->GetY();
        $pdf->Ln();
        if(self::$ordenamientoMedica){
            foreach(self::$ordenamientoMedica as $ordenamientoMedicamentos){
                $pdf->SetX(8);
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(130, 5, utf8_decode('* '.($ordenamientoMedicamentos->Medicamento)), 0, 'l');
                $pdf->Cell(35, 5, utf8_decode(($ordenamientoMedicamentos->Cantidadpormedico)), 0, 'R');
                $pdf->Ln();
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(20, 4, utf8_decode('Observacion'), 0, 'C');
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(50, 4, utf8_decode(($ordenamientoMedicamentos->Observacion)), 0, 'L');
                $posicionActual = $pdf->GetY();
                $pdf->Ln();
            }
        }else{
            $pdf->SetX(8);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(195, 4, utf8_decode(''), 1, 'l');
            $posicionActual = $pdf->GetY();
            $pdf->Ln();
        }
        $pdf->Ln();
        $posicionActual = $pdf->GetY();
        $pdf->Ln();
        $pdf->SetX(8);
        $pdf->SetFont('Arial', 'B',10);
        $pdf->Cell(195, 6, utf8_decode('Ayudas diagnósticas'), 0, 0, 'l');
        $pdf->SetLineWidth(0.5);
        $pdf->Line(8, $posicionActual+13, 203, $posicionActual+13);
        $pdf->SetLineWidth(0.2);
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetX(8);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(195, 5, utf8_decode('Servicio'), 0, 0, 'l',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->Ln();
        $posicionActual = $pdf->GetY();
        if(self::$ordenamientoServ){
            foreach(self::$ordenamientoServ as $ordenamientoServicios){
                $pdf->SetX(8);
                $pdf->SetFont('Arial', '', 9);
                $pdf->MultiCell(195, 5, utf8_decode('* '.($ordenamientoServicios->Servicio)), 0, 'l');
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(20, 4, utf8_decode('Cantidad: '), 0, 'C');
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(70, 5, utf8_decode(($ordenamientoServicios->CantidadServi)), 0, 'l');
                $posicionActual = $pdf->GetY();
                $pdf->Ln();
            }
        }else{
            $pdf->SetX(8);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(195, 4, utf8_decode(''), 1, 'l');
            $posicionActual = $pdf->GetY();
            $pdf->Ln();
        }
        $pdf->Ln();
        $final = $pdf->GetY();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        #FIRMAS MEDICO
        if(self::$data["Firma"]){
            if (file_exists(storage_path(substr(self::$data["Firma"], 9)))) {
                $pdf->Image(storage_path(substr(self::$data["Firma"], 9)), 30, 250, 56, 11);
            }
        }
        if($final > 225){
            $pdf->AddPage();
        }
        $pdf->Line(20, 261, 90, 261);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(23, 261);
        $pdf->Cell(60, 4, utf8_decode('Atendido por:'. ' '. self::$data["Atendido_Por"]), 0, 0, 'l'); #NOMBRE COMPLETO DEL MEDICO
        $pdf->SetXY(23, 266);
        $pdf->Cell(60, 4, utf8_decode('Especialidad:'. ' '. self::$data["Especialidad"]), 0, 0, 'l'); #ESPECIALIDAD DEL MEDICO
        $pdf->SetXY(23, 271);
        $pdf->Cell(32, 4, utf8_decode('REGISTRO Y LIC S.O:'), 0, 'l'); #REGISTRO MEDICO
        $pdf->MultiCell(30, 4, utf8_decode(self::$data["Registromedico"]), 0, 'l');

        $pdf->Ln();
        $final = $pdf->GetY();
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('NOTA ACLARATORIA'), 1, 0, 'C',1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        if(self::$notaAclaratoria){
            foreach(self::$notaAclaratoria as $notaAclaratorias){
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 9);
                $pdf->MultiCell(186, 4, utf8_decode(($notaAclaratorias->nota)), 1,'L');
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetX(12);
                $pdf->Cell(20, 4, utf8_decode('Fecha y Hora: '), 0, 'L');
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(70, 4, utf8_decode(($notaAclaratorias->created_at)), 0, 'l');
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(20, 4, utf8_decode('Realizado Por: '), 0, 'L');
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(70, 4, utf8_decode(($notaAclaratorias->name.' '.$notaAclaratorias->apellido)), 0, 'l');
                $pdf->Ln();
            }
        }else{
            $pdf->SetX(8);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(195, 4, utf8_decode(''), 1, 'l');
            $posicionActual = $pdf->GetY();
            $pdf->Ln();
        }
 }

    public function bodyHistoriaClinicaOncologia($pdf){
        #IMAGEN Y DATOS EMPRESARIALES
        $pdf->SetFont('Arial', 'B', 9);
        $logo = public_path() . "/images/logo.png";
        $pdf->Image($logo, 16, 9, -220);
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetXY(8, 37);
        $pdf->Cell(60, 3, utf8_decode('NIT:900033371-4 Res: 004 '), 0, 0, 'C');
#LOGO FINAL HOJA 1
//$logo = "fpdf\Imag\LOGO FINAL.jpg";
//$pdf->Image($logo, 0, 291, -95);

        $informacionInicial = citapaciente::select(['ta.name as Nombre','cita_paciente.Datetimeingreso','s.Nombre as tipo'])
            ->join('citas as c','cita_paciente.Cita_id','c.id')
            ->join('agendas as a','c.Agenda_id','a.id')
            ->join('especialidade_tipoagenda as et','a.Especialidad_id','et.id')
            ->join('tipo_agendas as ta','et.Tipoagenda_id','ta.id')
            ->leftJoin('consultorios as cs','a.Consultorio_id','cs.id')
            ->leftJoin('sedes as s','cs.Sede_id','s.id')
            ->where('cita_paciente.id',self::$data['id'])
            ->first();
        $punto = "";
        $realizada ="Consulta No Programada";
        $fechaConsulta = citapaciente::find(self::$data['id'])->updated_at;
        if($informacionInicial){
            if($informacionInicial->tipo){
                $punto = ucwords(strtolower($informacionInicial->tipo));
            }
            if($informacionInicial->Nombre){
                $realizada = $informacionInicial->Nombre;
            }
            if($informacionInicial->Datetimeingreso){
                $fechaConsulta = $informacionInicial->Datetimeingreso;
            }
        }


        $pdf->SetXY(25, 18);
        $pdf->SetFont('Arial', 'B', 30);
        $pdf->Cell(192, 4, utf8_decode('HISTORIA CLÍNICA'), 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(70, 25);
        $pdf->Cell(46, 4, utf8_decode('PUNTO DE ATENCIÓN:'), 0, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(90, 4, utf8_decode($punto), 0,'L'); #Donde fue atendido el paciente
        $pdf->SetXY(72, 29);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(44, 4, utf8_decode('CONSULTA REALIZADA:'), 0, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(56, 4, utf8_decode($realizada), 0, 0, 'L');  #ACTIVIDAD DE LA CITA
        $pdf->SetXY(70, 33);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(46, 4, utf8_decode('FECHA DE CONSULTA:'), 0, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(46, 4, utf8_decode($fechaConsulta),0,'L'); #Feha y hora


        #DATOS PERSONALES
        $pdf->SetXY(12,45);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('DATOS DEL USUARIO'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $paciente = Paciente::where('Num_Doc',self::$data["Cédula"])->first();

        $pdf->SetXY(12, 49);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('NOMBRE COMPLETO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Nombre"]), 1, 0, 'l');
        $pdf->SetXY(105,49);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('IDENTIFICACIÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Cédula"]), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('FECHA DE NACIMIENTO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Fecha_Nacimiento"]), 1, 0, 'l');
        $pdf->SetXY(105,53);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('TIPO DE IDENTIFICACIÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode($paciente->Tipo_Doc), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('EDAD'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Edad"]), 1, 0, 'l');
        $pdf->SetXY(105,57);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('SEXO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Sexo"]), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('OCUPACIÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode($paciente->Ocupacion), 1, 0, 'l');
        $pdf->SetXY(105,61);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('DIRECCIÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Direccion_Residencia"]), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('TELÉFONO DEL DOMICILIO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Telefono"]."-".self::$data["Celular"]), 1, 0, 'l');
        $pdf->SetXY(105,65);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('LUGAR DE RESIDENCIA'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Municipio_Afiliado"]), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('NOMBRE DEL ACOMPAÑANTE'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');
        $pdf->SetXY(105,69);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('TELÉFONO DEL ACOMPAÑANTE'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('NOMBRE DEL RESPONSABLE'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');
        $pdf->SetXY(105,73);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('TELÉFONO DEL RESPONSABLE'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('PARENTESCO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');
        $pdf->SetXY(105,77);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('ASEGURADORA'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('TIPO DE VINCULACIÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');
        $pdf->SetXY(105,81);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('N° ATENCIÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');

        $pdf->SetXY(12, 88);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(186, 4, utf8_decode('ANAMNESIS'), 1, 0, 'C', 1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode('MOTIVO DE CONSULTA'), 1, 0, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["Motivoconsulta"]), 1, "L", 0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(186, 4, utf8_decode('ENFERMEDAD ACTUAL'), 1, 0, 'C', 1);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["Enfermedadactual"]), 1, "L", 0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(186, 4, utf8_decode('TRATAMIENTO ONCOLOGICO'), 1, 0, 'C', 1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["tratamientoncologico"]), 1, "L", 0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(186, 4, utf8_decode('CIRUGIA ONCOLOGICA'), 1, 0, 'C', 1);
        $pdf->Ln();
        if(self::$data["recibioradioterapia"]){
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('RADIOTERAPIA'), 1, 0, 'C', 1);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(62, 4, utf8_decode('NUMERO SESIONES'), 1, 0, 'C', 1);
            $pdf->Cell(62, 4, utf8_decode('FECHA INICIO'), 1, 0, 'C', 1);
            $pdf->Cell(62, 4, utf8_decode('FECHA FINAL'), 1, 0, 'C', 1);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(62, 4, utf8_decode(self::$data["nsesiones"]), 0, 0, 'C');
            $pdf->Cell(62, 4, utf8_decode(self::$data["inicioradioterapia"]), 0, 0, 'C');
            $pdf->Cell(62, 4, utf8_decode(self::$data["finradioterapia"]), 0, 0, 'C');
            $pdf->Ln();
        }
        if(self::$data["cirujiaoncologica"]){
            $pdf->SetX(12);
            $pdf->Cell(186, 4, utf8_decode('CIRUGIAS'), 1, 0, 'C', 1);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(62, 4, utf8_decode('NUMERO CIRUGIAS'), 1, 0, 'C', 1);
            $pdf->Cell(62, 4, utf8_decode('FECHA INICIO'), 1, 0, 'C', 1);
            $pdf->Cell(62, 4, utf8_decode('FECHA FINAL'), 1, 0, 'C', 1);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(62, 4, utf8_decode(self::$data["ncirujias"]), 0, 0, 'C');
            $pdf->Cell(62, 4, utf8_decode(self::$data["iniciocirujia"]), 0, 0, 'C');
            $pdf->Cell(62, 4, utf8_decode(self::$data["fincirujia"]), 0, 0, 'C');
            $pdf->Ln();
        }
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(186, 4, utf8_decode('INTENCION TRATAMIENTO ONCOLOGICO'), 1, 0, 'C', 1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["intencion"]), 1, "L", 0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(186, 4, utf8_decode('DESCRIPCIÓN PATOLOGÍA ACTUAL'), 1, 0, 'C', 1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["Patologiacancelactual"]), 1, "L", 0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(93, 4, utf8_decode('FECHA INGRESO LABORATORIO PATOLOGIA'), 1, 0, 'C', 1);
        $pdf->Cell(93, 4, utf8_decode('FECHA REPORTE LABORATORIO PATOLOGIA'), 1, 0, 'C', 1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(93, 4, utf8_decode(self::$data["fdxcanceractual"]), 1, 0, 'C', 0);
        $pdf->Cell(93, 4, utf8_decode(self::$data["flaboratoriopatologia"]), 1, 0, 'C', 0);
        $pdf->Ln();
        if(self::$data["localizacioncancer"]){
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(186, 4, utf8_decode('TIPO TUMOR'), 1, 0, 'C', 1);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->Cell(42, 4, utf8_decode('LOCALIZACIÓN CANCER:'), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(144, 4, utf8_decode(self::$data["localizacioncancer"]), 0, "L", 0);
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(14, 4, utf8_decode('DUKES:'), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(172, 4, utf8_decode(self::$data["Dukes"]), 0, "L", 0);
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(18, 4, utf8_decode('GLEASON:'), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(168, 4, utf8_decode(self::$data["gleason"]), 0, "L", 0);
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(12, 4, utf8_decode('HER2:'), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(174, 4, utf8_decode(self::$data["Her2"]), 0, "L", 0);
        }
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(186, 4, utf8_decode('ESTADISTICA TUMOR'), 1, 0, 'C', 1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["estadificacioninicial"]), 1, "L", 0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(33, 4, utf8_decode('F.ESTADIFICACIÓN:'), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(153, 4, utf8_decode(self::$data["gleason"]), 0, "L", 0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(46, 4, utf8_decode('F. REPORTE LABORATORIO:'), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(140, 4, utf8_decode(self::$data["gleason"]), 0, "L", 0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(43, 4, utf8_decode('DIFERENCIACIÓN TUMOR:'), 0, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(143, 4, utf8_decode(self::$data["gleason"]), 0, "L", 0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(186, 4, utf8_decode('MEDIDAS ANTROPOMÉTRICAS'), 1, 0, 'C',1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(186, 4, utf8_decode(self::$data["Antropometricas"]), 1, 0, 'l',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('SIGNOS VITALES'), 1, 0, 'C',1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(186, 4, utf8_decode(self::$data["Signos_Vitales"]), 1, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode(self::$data["Otros_Signos_Vitales"]), 1, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode(self::$data["Presión_Arterial"]), 1, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(186, 4, utf8_decode("CONDICIONES GENERALES"), 1, 0, 'C',1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["Condiciongeneral"]), 0,'L');
        $pdf->Line(12,$pdf->GetY(),198,$pdf->GetY());

        $cie10Primario = Cie10::select(['cie10s.Codigo_CIE10 as codigo', 'cie10s.Descripcion'])
            ->join('cie10pacientes as cp', 'cp.Cie10_id', 'cie10s.id')
            ->where('cp.Esprimario',true)
            ->where('cp.Citapaciente_id',self::$data["id"])
            ->first();

        $cie10s = Cie10::select(['cie10s.Codigo_CIE10 as codigo', 'cie10s.Descripcion'])
            ->join('cie10pacientes as cp', 'cp.Cie10_id', 'cie10s.id')
            ->where('cp.Esprimario',false)
            ->where('cp.Citapaciente_id',self::$data["id"])
            ->get()->toArray();

        $secundarios = "";
        foreach ($cie10s as $key => $cie10) {
            if(isset($cie10s[$key+1])){
                $secundarios = $secundarios.$cie10['codigo'].',';
            }else{
                $secundarios = $secundarios.$cie10['codigo'];
            }
        }
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(186, 4, utf8_decode("DIAGNÓSTICOS"), 1, 0, 'C',1);
        $pdf->SetX(12);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->Cell(45, 4, utf8_decode("DIAGNÓSTICOS PRINCIPAL:"), 0, 0, 'l',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(141, 4, utf8_decode(isset($cie10Primario->codigo)?$cie10Primario->codigo:""), 0, 0, 'l',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(40, 4, utf8_decode("OTROS DIAGNÓSTICOS:"), 0, 0, 'l',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(146, 4, utf8_decode($secundarios), 0, 0, 'l',0);
        $pdf->Ln();
        $pdf->Line(12,$pdf->GetY(),198,$pdf->GetY());
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(186, 4, utf8_decode("PLAN DE MANEJO:"), 0, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["Planmanejo"]), 0,'L');
        $pdf->Line(12,$pdf->GetY(),198,$pdf->GetY());
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(186, 4, utf8_decode("RECOMENDACIONES:"), 0, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["Recomendaciones"]), 0,'L');
        $pdf->Line(12,$pdf->GetY(),198,$pdf->GetY());
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(38, 4, utf8_decode("DESTINO DEL PACIENTE:"), 0, 0, 'L',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(148, 4, utf8_decode(self::$data["Destinopaciente"]), 0, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->Line(12,$pdf->GetY(),198,$pdf->GetY());
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(186, 4, utf8_decode("FINALIDAD:"), 0, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["Finalidad"]), 0,'L');
        $pdf->Line(12,$pdf->GetY(),198,$pdf->GetY());
        $final = $pdf->GetY();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        #FIRMAS MEDICO
        if(self::$data["Firma"]){
            if (file_exists(storage_path(substr(self::$data["Firma"], 9)))) {
                $pdf->Image(storage_path(substr(self::$data["Firma"], 9)), 30, 250, 56, 11);
            }
        }
        if($final > 225){
            $pdf->AddPage();
        }
        $pdf->Line(20, 261, 90, 261);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(23, 261);
        $pdf->Cell(60, 4, utf8_decode('Atendido por:'. ' '. self::$data["Atendido_Por"]), 0, 0, 'l'); #NOMBRE COMPLETO DEL MEDICO
        $pdf->SetXY(23, 266);
        $pdf->Cell(60, 4, utf8_decode('Especialidad:'. ' '. self::$data["Especialidad"]), 0, 0, 'l'); #ESPECIALIDAD DEL MEDICO
        $pdf->SetXY(23, 271);
        $pdf->Cell(32, 4, utf8_decode('REGISTRO Y LIC S.O:'), 0, 'l'); #REGISTRO MEDICO
        $pdf->MultiCell(30, 4, utf8_decode(self::$data["Registromedico"]), 0, 'l');
    }

    public function bodyHistoriaClinicaImagenologia($pdf){
        #IMAGEN Y DATOS EMPRESARIALES
        $pdf->SetFont('Arial', 'B', 9);
        $logo = public_path() . "/images/logo.png";
        $pdf->Image($logo, 16, 9, -220);
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetXY(8, 37);
        $pdf->Cell(60, 3, utf8_decode('NIT:900033371-4 Res: 004 '), 0, 0, 'C');
#LOGO FINAL HOJA 1
//$logo = "fpdf\Imag\LOGO FINAL.jpg";
//$pdf->Image($logo, 0, 291, -95);

        $informacionInicial = citapaciente::select(['ta.name as Nombre','cita_paciente.Datetimeingreso','s.Nombre as tipo'])
            ->join('citas as c','cita_paciente.Cita_id','c.id')
            ->join('agendas as a','c.Agenda_id','a.id')
            ->join('especialidade_tipoagenda as et','a.Especialidad_id','et.id')
            ->join('tipo_agendas as ta','et.Tipoagenda_id','ta.id')
            ->leftJoin('consultorios as cs','a.Consultorio_id','cs.id')
            ->leftJoin('sedes as s','cs.Sede_id','s.id')
            ->where('cita_paciente.id',self::$data['id'])
            ->first();
        $punto = "";
        $realizada ="Consulta No Programada";
        $fechaConsulta = citapaciente::find(self::$data['id'])->updated_at;
        if($informacionInicial){
            if($informacionInicial->tipo){
                $punto = ucwords(strtolower($informacionInicial->tipo));
            }
            if($informacionInicial->Nombre){
                $realizada = $informacionInicial->Nombre;
            }
            if($informacionInicial->Datetimeingreso){
                $fechaConsulta = $informacionInicial->Datetimeingreso;
            }
        }


        $pdf->SetXY(25, 18);
        $pdf->SetFont('Arial', 'B', 30);
        $pdf->Cell(192, 4, utf8_decode('HISTORIA CLÍNICA'), 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(70, 25);
        $pdf->Cell(46, 4, utf8_decode('PUNTO DE ATENCIÓN:'), 0, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(90, 4, utf8_decode($punto), 0,'L'); #Donde fue atendido el paciente
        $pdf->SetXY(72, 29);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(44, 4, utf8_decode('CONSULTA REALIZADA:'), 0, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(56, 4, utf8_decode($realizada), 0, 0, 'L');  #ACTIVIDAD DE LA CITA
        $pdf->SetXY(70, 33);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(46, 4, utf8_decode('FECHA DE CONSULTA:'), 0, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(46, 4, utf8_decode($fechaConsulta),0,'L'); #Feha y hora


        #DATOS PERSONALES
        $pdf->SetXY(12,45);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('DATOS DEL USUARIO'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $paciente = Paciente::where('Num_Doc',self::$data["Cédula"])->first();

        $pdf->SetXY(12, 49);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('NOMBRE COMPLETO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Nombre"]), 1, 0, 'l');
        $pdf->SetXY(105,49);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('IDENTIFICACIÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Cédula"]), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('FECHA DE NACIMIENTO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Fecha_Nacimiento"]), 1, 0, 'l');
        $pdf->SetXY(105,53);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('TIPO DE IDENTIFICACIÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode($paciente->Tipo_Doc), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('EDAD'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Edad"]), 1, 0, 'l');
        $pdf->SetXY(105,57);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('SEXO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Sexo"]), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('OCUPACIÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode($paciente->Ocupacion), 1, 0, 'l');
        $pdf->SetXY(105,61);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('DIRECCIÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Direccion_Residencia"]), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('TELÉFONO DEL DOMICILIO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Telefono"]."-".self::$data["Celular"]), 1, 0, 'l');
        $pdf->SetXY(105,65);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('LUGAR DE RESIDENCIA'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Municipio_Afiliado"]), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('NOMBRE DEL ACOMPAÑANTE'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');
        $pdf->SetXY(105,69);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('TELÉFONO DEL ACOMPAÑANTE'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('NOMBRE DEL RESPONSABLE'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');
        $pdf->SetXY(105,73);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('TELÉFONO DEL RESPONSABLE'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('PARENTESCO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');
        $pdf->SetXY(105,77);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('ASEGURADORA'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('TIPO DE VINCULACIÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');
        $pdf->SetXY(105,81);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('N° ATENCIÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(''), 1, 0, 'l');

        $pdf->SetXY(12, 88);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(186, 4, utf8_decode('ESTUDIO'), 1, 0, 'C', 1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(186, 4, utf8_decode("INDICACIÓN:"), 0, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["Indicacion"]), 0,'L');
        $pdf->Ln();
        $pdf->Line(12,$pdf->GetY(),198,$pdf->GetY());
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(186, 4, utf8_decode("HALLAZGOS:"), 0, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["Hallazgos"]), 0,'L');
        $pdf->Ln();
        $pdf->Line(12,$pdf->GetY(),198,$pdf->GetY());
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(186, 4, utf8_decode("CONCLUSION:"), 0, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["Conclusion"]), 0,'L');
        $pdf->Ln();
        $pdf->Line(12,$pdf->GetY(),198,$pdf->GetY());
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(186, 4, utf8_decode("NOTACLARATORIA:"), 0, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["Notaclaratoria"]), 0,'L');
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(186, 4, utf8_decode('MEDIDAS ANTROPOMÉTRICAS'), 1, 0, 'C',1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(186, 4, utf8_decode(self::$data["Antropometricas"]), 1, 0, 'l',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('SIGNOS VITALES'), 1, 0, 'C',1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(186, 4, utf8_decode(self::$data["Signos_Vitales"]), 1, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode(self::$data["Otros_Signos_Vitales"]), 1, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->Cell(186, 4, utf8_decode(self::$data["Presión_Arterial"]), 1, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(186, 4, utf8_decode("CONDICIONES GENERALES"), 1, 0, 'C',1);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["Condiciongeneral"]), 0,'L');
        $pdf->Line(12,$pdf->GetY(),198,$pdf->GetY());

        $cie10Primario = Cie10::select(['cie10s.Codigo_CIE10 as codigo', 'cie10s.Descripcion'])
            ->join('cie10pacientes as cp', 'cp.Cie10_id', 'cie10s.id')
            ->where('cp.Esprimario',true)
            ->where('cp.Citapaciente_id',self::$data["id"])
            ->first();

        $cie10s = Cie10::select(['cie10s.Codigo_CIE10 as codigo', 'cie10s.Descripcion'])
            ->join('cie10pacientes as cp', 'cp.Cie10_id', 'cie10s.id')
            ->where('cp.Esprimario',false)
            ->where('cp.Citapaciente_id',self::$data["id"])
            ->get()->toArray();

        $secundarios = "";
        foreach ($cie10s as $key => $cie10) {
            if(isset($cie10s[$key+1])){
                $secundarios = $secundarios.$cie10['codigo'].',';
            }else{
                $secundarios = $secundarios.$cie10['codigo'];
            }
        }
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(186, 4, utf8_decode("DIAGNÓSTICOS"), 1, 0, 'C',1);
        $pdf->SetX(12);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->Cell(45, 4, utf8_decode("DIAGNÓSTICOS PRINCIPAL:"), 0, 0, 'l',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(141, 4, utf8_decode(isset($cie10Primario->codigo)?$cie10Primario->codigo:""), 0, 0, 'l',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(40, 4, utf8_decode("OTROS DIAGNÓSTICOS:"), 0, 0, 'l',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(146, 4, utf8_decode($secundarios), 0, 0, 'l',0);
        $pdf->Ln();
        $pdf->Line(12,$pdf->GetY(),198,$pdf->GetY());
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(186, 4, utf8_decode("PLAN DE MANEJO:"), 0, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["Planmanejo"]), 0,'L');
        $pdf->Line(12,$pdf->GetY(),198,$pdf->GetY());
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(186, 4, utf8_decode("RECOMENDACIONES:"), 0, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["Recomendaciones"]), 0,'L');
        $pdf->Line(12,$pdf->GetY(),198,$pdf->GetY());
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(38, 4, utf8_decode("DESTINO DEL PACIENTE:"), 0, 0, 'L',0);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(148, 4, utf8_decode(self::$data["Destinopaciente"]), 0, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->Line(12,$pdf->GetY(),198,$pdf->GetY());
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(186, 4, utf8_decode("FINALIDAD:"), 0, 0, 'L',0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["Finalidad"]), 0,'L');
        $pdf->Line(12,$pdf->GetY(),198,$pdf->GetY());
        $final = $pdf->GetY();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        #FIRMAS MEDICO
        if(self::$data["Firma"]){
            if (file_exists(storage_path(substr(self::$data["Firma"], 9)))) {
                $pdf->Image(storage_path(substr(self::$data["Firma"], 9)), 30, 250, 56, 11);
            }
        }
        if($final > 225){
            $pdf->AddPage();
        }
        $pdf->Line(20, 261, 90, 261);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(23, 261);
        $pdf->Cell(60, 4, utf8_decode('Atendido por:'. ' '. self::$data["Atendido_Por"]), 0, 0, 'l'); #NOMBRE COMPLETO DEL MEDICO
        $pdf->SetXY(23, 266);
        $pdf->Cell(60, 4, utf8_decode('Especialidad:'. ' '. self::$data["Especialidad"]), 0, 0, 'l'); #ESPECIALIDAD DEL MEDICO
        $pdf->SetXY(23, 271);
        $pdf->Cell(32, 4, utf8_decode('REGISTRO Y LIC S.O:'), 0, 'l'); #REGISTRO MEDICO
        $pdf->MultiCell(30, 4, utf8_decode(self::$data["Registromedico"]), 0, 'l');
    }
}
