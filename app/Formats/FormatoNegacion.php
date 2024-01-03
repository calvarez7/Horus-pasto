<?php
namespace App\Formats;


use App\User;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Modelos\Detaarticulorden;
use App\Modelos\Cuporden;

class FormatoNegacion extends FPDF
{
    public static $paciente;
    public static $data;

    public function generar($data)
    {
        if($data["tipos"] == 'medicamentos') {
            self::$data = $data;
            self::$paciente = Detaarticulorden::select('pacientes.Primer_Nom', 'pacientes.SegundoNom', 'pacientes.Primer_Ape', 'pacientes.Segundo_Ape', 'pacientes.Tipo_Doc'
                , 'pacientes.Num_Doc', 'pacientes.Celular1', 'pacientes.Mpio_Afiliado', 'pacientes.Departamento', 'codesumis.Codigo', 'codesumis.Nombre as Medicamento',
                'detaarticulordens.Observacion', 'auditorias.Motivo', 'users.name', 'users.apellido', 'users.especialidad_medico')
                ->join('ordens', 'ordens.id', 'detaarticulordens.Orden_id')
                ->join('cita_paciente', 'cita_paciente.id', 'ordens.Cita_id')
                ->join('pacientes', 'pacientes.id', 'cita_paciente.Paciente_id')
                ->join('codesumis', 'codesumis.id', 'detaarticulordens.codesumi_id')
                ->leftjoin('auditorias', 'auditorias.Model_id', 'detaarticulordens.id')
                ->leftjoin('users', 'users.id', 'auditorias.Usuariomodifica_id')
                ->where('detaarticulordens.id', $data["id"])->first();
        }
        if($data["tipos"] == 'servicios'){
            self::$data = $data;
            self::$paciente = Cuporden::select('pacientes.Primer_Nom', 'pacientes.SegundoNom', 'pacientes.Primer_Ape', 'pacientes.Segundo_Ape', 'pacientes.Tipo_Doc'
                , 'pacientes.Num_Doc', 'pacientes.Celular1', 'pacientes.Mpio_Afiliado', 'pacientes.Departamento', 'cups.Codigo', 'cups.Nombre as Servicio', 'auditorias.Motivo', 'users.name', 'users.apellido', 'users.especialidad_medico')
                ->join('ordens', 'ordens.id', 'cupordens.Orden_id')
                ->join('cita_paciente', 'cita_paciente.id', 'ordens.Cita_id')
                ->join('pacientes', 'pacientes.id', 'cita_paciente.Paciente_id')
                ->join('cups', 'cups.id', 'cupordens.Cup_id')
                ->leftjoin('auditorias', 'auditorias.Model_id', 'cupordens.id')
                ->leftjoin('users', 'users.id', 'auditorias.Usuariomodifica_id')
                ->where('cupordens.id', $data["id"])->first();
        }
        $pdf = new FormatoNegacion('P','mm','A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $this->body($pdf);
        $pdf->Output();
    }
    public function body($pdf)
    {
        if(self::$data["tipos"] == 'medicamentos') {
            $pdf->SetFont('Arial', 'B', 9);
            $logo = public_path() . "/images/logo_instituto_salud.png";
            $pdf->Image($logo, 10, 12, -99);

            $pdf->SetFont('Arial', 'B', 9);
            $logo = public_path() . "/images/logo_republica_colombia.png";
            $pdf->Image($logo, 185, 9, -190);

            $pdf->SetXY(10, 13);
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(192, 4, utf8_decode('REPUBLICA DE COLOMBIA'), 0, 0, 'C');
            $pdf->SetFont('Arial', '', 12);
            $pdf->SetXY(10, 18);
            $pdf->Cell(192, 4, utf8_decode('SUPERITENDENCIA NACIONAL DE SALUD'), 0, 0, 'C');
            $pdf->SetXY(10, 23);
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(192, 4, utf8_decode('FORMATO NEGACION DE SERVICIOS DE SALUD Y/O MEDICAMENTOS'), 0, 0, 'C');
            $pdf->SetXY(10, 30);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(185, 4, utf8_decode('CUANDO NO SE AUTORICE LA PRESTACION DE UN SERVICIO DE SALUD O EL SUMINISTRO DE MEDICAMENTOS, ENTREGUE ESTE FORMULARIO AL USUARIO, DEBIDAMENTE DILIGENCIADO'), 0, 'C');
            $pdf->SetXY(10, 45);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(120, 5, utf8_decode('NOMBRE DE LA ADMINISTRADORA I.P.S. O ENTIDAD TERRITORIAL'), 1, 0, 'L');
            $pdf->Cell(70, 5, utf8_decode('NUMERO'), 1, 0, 'L');
            $pdf->ln();
            $pdf->SetX(10);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(120, 5, utf8_decode('Sumimedical S.A.S'), 1, 0, 'L');
            $pdf->Cell(70, 5, utf8_decode(''), 1, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(93, 4, utf8_decode('FECHA DE SOLICITUD'), 1, 0, 'C');
            $pdf->Cell(5, 4, utf8_decode(''), 0, 0, 'C');
            $pdf->Cell(92, 4, utf8_decode('FECHA DE DILIGENCIAMIENTO'), 1, 0, 'C');
            $pdf->ln();
            $pdf->Cell(31, 4, utf8_decode('DD'), 1, 0, 'C');
            $pdf->Cell(31, 4, utf8_decode('MM'), 1, 0, 'C');
            $pdf->Cell(31, 4, utf8_decode('ANO'), 1, 0, 'C');
            $pdf->Cell(5, 4, utf8_decode(''), 0, 0, 'C');
            $pdf->Cell(31, 4, utf8_decode('DD'), 1, 0, 'C');
            $pdf->Cell(31, 4, utf8_decode('MM'), 1, 0, 'C');
            $pdf->Cell(30, 4, utf8_decode('ANO'), 1, 0, 'C');
            $pdf->ln();
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(31, 4, utf8_decode(''), 1, 0, 'C');
            $pdf->Cell(31, 4, utf8_decode(''), 1, 0, 'C');
            $pdf->Cell(31, 4, utf8_decode(''), 1, 0, 'C');
            $pdf->Cell(5, 4, utf8_decode(''), 0, 0, 'C');
            $pdf->Cell(31, 4, utf8_decode(''), 1, 0, 'C');
            $pdf->Cell(31, 4, utf8_decode(''), 1, 0, 'C');
            $pdf->Cell(30, 4, utf8_decode(''), 1, 0, 'C');

            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(192, 4, utf8_decode('1. DATOS GENERALES DEL SOLICITANTE DEL SERVICIO'), 0, 0, 'l');
            $pdf->ln();
            $pdf->Cell(63, 4, utf8_decode('1er. APELLIDO'), 1, 0, 'C');
            $pdf->Cell(63, 4, utf8_decode('2do. APELLIDO'), 1, 0, 'C');
            $pdf->Cell(64, 4, utf8_decode('NOMBRES'), 1, 0, 'C');
            $pdf->ln();
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(63, 4, utf8_decode(self::$paciente->Primer_Ape), 1, 0, 'C');
            $pdf->Cell(63, 4, utf8_decode(self::$paciente->Segundo_Ape), 1, 0, 'C');
            $pdf->Cell(64, 4, utf8_decode(self::$paciente->Primer_Nom . ' ' . self::$paciente->SegundoNom), 1, 0, 'C');
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(63, 4, utf8_decode('TIPO DE IDENTIFICACION'), 1, 0, 'C');
            $pdf->Cell(63, 4, utf8_decode('No. DOCUMENTO IDENTIFICACION'), 1, 0, 'C');
            $pdf->Cell(64, 4, utf8_decode('No. DE CONTRATO'), 1, 0, 'C');
            $pdf->ln();
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(10, 4, utf8_decode('TI'), 0, 0, 'C');
            $pdf->Cell(10, 4, utf8_decode(self::$paciente->Tipo_Doc === 'TI' ? 'X' : ''), 1, 0, 'C');
            $pdf->Cell(10, 4, utf8_decode('CC'), 0, 0, 'C');
            $pdf->Cell(10, 4, utf8_decode(self::$paciente->Tipo_Doc === 'CC' ? 'X' : ''), 1, 0, 'C');
            $pdf->Cell(10, 4, utf8_decode('CE'), 0, 0, 'C');
            $pdf->Cell(10, 4, utf8_decode(self::$paciente->Tipo_Doc === 'CE' ? 'X' : ''), 1, 0, 'C');
            $pdf->Cell(3, 4, utf8_decode(''), 0, 0, 'C');
            $pdf->Cell(63, 4, utf8_decode(self::$paciente->Num_Doc), 1, 0, 'C');
            $pdf->Cell(64, 4, utf8_decode(self::$paciente->Num_Doc), 1, 0, 'C');
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(63, 4, utf8_decode('TELEFONO'), 1, 0, 'C');
            $pdf->Cell(63, 4, utf8_decode('CIUDAD / MUNICIPIO'), 1, 0, 'C');
            $pdf->Cell(64, 4, utf8_decode('DEPARTAMENTO'), 1, 0, 'C');
            $pdf->ln();
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(63, 4, utf8_decode(self::$paciente->Celular1), 1, 0, 'C');
            $pdf->Cell(63, 4, utf8_decode(self::$paciente->Mpio_Afiliado), 1, 0, 'C');
            $pdf->Cell(64, 4, utf8_decode(self::$paciente->Departamento), 1, 0, 'C');
            $pdf->Line(10, 122, 10, 162);
            $pdf->Line(200, 122, 200, 162);
            $pdf->Line(10, 122, 200, 122);
            $pdf->Line(10, 162, 200, 162);
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(192, 4, utf8_decode('TIPO PLAN USUARIO'), 0, 0, 'l');
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(20, 4, utf8_decode("POS"), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(8, 4, utf8_decode(''), 1, 0, 'L');
            $pdf->SetX(70);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(25, 4, utf8_decode("POS-S"), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(8, 4, utf8_decode(''), 1, 0, 'L');
            $pdf->SetX(110);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(50, 4, utf8_decode("PLAN COMPLENTARIO (PAC)"), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(8, 4, utf8_decode(''), 1, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(110, 4, utf8_decode("POBLACION POBRE NO CUBIERTA CON SUBSIDIO A LA DEMANDA"), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(8, 4, utf8_decode(''), 1, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(110, 4, utf8_decode("NRO. DE SEMANAS COTIZADAS POR EL USUARIO AL SGSSS"), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(8, 4, utf8_decode(''), 1, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(110, 4, utf8_decode("ESTADO DE LA AFILIACION / CONTRATO DEL USUARIO"), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(8, 4, utf8_decode(''), 1, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(20, 4, utf8_decode("VIGENTE"), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(8, 4, utf8_decode(''), 1, 0, 'L');
            $pdf->SetX(70);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(25, 4, utf8_decode("SUSPENDIDO"), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(8, 4, utf8_decode(''), 1, 0, 'L');
            $pdf->SetX(110);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(50, 4, utf8_decode("REMITIR SIN ASEGURAMIENTO"), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(8, 4, utf8_decode(''), 1, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(192, 4, utf8_decode('2. CLASE DE SERVICIO NO AUTORIZADO Y RECOMENDACIONES AL USUARIO'), 0, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(190, 4, utf8_decode('SERVICIO NO AUTORIZADO / CODIGO O MEDICAMENTO NO AUTORIZADO'), 1, 0, 'C', 1);
            $pdf->ln();
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(190, 4, utf8_decode(self::$paciente->Codigo . ' - ' . self::$paciente->Medicamento), 1, 'l');
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(190, 4, utf8_decode('DESCRIPCION : (Señale el servicio / procedimiento / intervención)'), 1, 0, 'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->ln();
            $pdf->MultiCell(190, 4, utf8_decode(self::$paciente->Observacion), 1, 'l');
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(190, 4, utf8_decode('JUSTIFICACION: Indique el motivo de la negación'), 1, 0, 'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->ln();
            $pdf->MultiCell(190, 4, utf8_decode(self::$paciente->Motivo), 1, 'l');
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(190, 4, utf8_decode('FUNDAMENTO LEGAL: Relacione las disposiciones que presuntamente respaldan la decisión'), 1, 0, 'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->ln();
            $pdf->MultiCell(190, 4, utf8_decode(''), 1, 'l');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->MultiCell(192, 4, utf8_decode('3. ALTERNATIVAS PARA QUE EL USUARIO ACCEDA AL SERVICIO DE SALUD O MEDICAMENTO SOLICITADO Y HAGA VALER SUS DERECHOS LEGALES Y CONSTITUCIONALES'), 0, 'J');
            $pdf->ln();
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(190, 4, utf8_decode('1.
2.
3.
4.'), 1, 'l');
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(120, 4, utf8_decode('NOMBRE Y CARGO DEL FUNCIONARIO QUE NIEGA EL SERVICIO'), 1, 0, 'L');
            $pdf->Cell(70, 4, utf8_decode('FIRMA'), 1, 0, 'L');
            $pdf->ln();
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(120, 4, utf8_decode(self::$paciente->name . ' ' . self::$paciente->apellido . ' (' . self::$paciente->especialidad_medico . ')'), 1, 0, 'L');
            $pdf->Cell(70, 4, utf8_decode(''), 1, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(190, 4, utf8_decode('FIRMA DEL USUARIO O DE QUIEN RECIBE'), 1, 0, 'L');
            $pdf->ln();
            $pdf->Cell(190, 4, utf8_decode(''), 1, 0, 'L');
            $pdf->ln();
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(185, 4, utf8_decode('Si está en desacuerdo con la decisión adoptada, acuda a la Oficina de Atención al Usuario de su EPS, si su queja no es resuelta, eleve consulta a la Superintendencia Nacional de Salud, anexando copia de este formato totalmente diligenciado a la carrera 13 No. 32-76 PBX 3300210 Ext. 3011 – 3039 Nota: Esto no indica que su reclamación sea aprobada por parte de la Superintendencia, es necesario hacer un estudio previo'), 0, 'C');
        }

        elseif(self::$data["tipos"] == 'servicios'){

            $pdf->SetFont('Arial', 'B', 9);
            $logo = public_path() . "/images/logo_instituto_salud.png";
            $pdf->Image($logo, 10, 12, -99);

            $pdf->SetFont('Arial', 'B', 9);
            $logo = public_path() . "/images/logo_republica_colombia.png";
            $pdf->Image($logo, 185, 9, -190);

            $pdf->SetXY(10, 13);
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(192, 4, utf8_decode('REPUBLICA DE COLOMBIA'), 0, 0, 'C');
            $pdf->SetFont('Arial', '', 12);
            $pdf->SetXY(10, 18);
            $pdf->Cell(192, 4, utf8_decode('SUPERITENDENCIA NACIONAL DE SALUD'), 0, 0, 'C');
            $pdf->SetXY(10, 23);
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(192, 4, utf8_decode('FORMATO NEGACION DE SERVICIOS DE SALUD Y/O MEDICAMENTOS'), 0, 0, 'C');
            $pdf->SetXY(10, 30);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(185, 4, utf8_decode('CUANDO NO SE AUTORICE LA PRESTACION DE UN SERVICIO DE SALUD O EL SUMINISTRO DE MEDICAMENTOS, ENTREGUE ESTE FORMULARIO AL USUARIO, DEBIDAMENTE DILIGENCIADO'), 0, 'C');
            $pdf->SetXY(10, 45);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(120, 5, utf8_decode('NOMBRE DE LA ADMINISTRADORA I.P.S. O ENTIDAD TERRITORIAL'), 1, 0, 'L');
            $pdf->Cell(70, 5, utf8_decode('NUMERO'), 1, 0, 'L');
            $pdf->ln();
            $pdf->SetX(10);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(120, 5, utf8_decode('Sumimedical S.A.S'), 1, 0, 'L');
            $pdf->Cell(70, 5, utf8_decode(''), 1, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(93, 4, utf8_decode('FECHA DE SOLICITUD'), 1, 0, 'C');
            $pdf->Cell(5, 4, utf8_decode(''), 0, 0, 'C');
            $pdf->Cell(92, 4, utf8_decode('FECHA DE DILIGENCIAMIENTO'), 1, 0, 'C');
            $pdf->ln();
            $pdf->Cell(31, 4, utf8_decode('DD'), 1, 0, 'C');
            $pdf->Cell(31, 4, utf8_decode('MM'), 1, 0, 'C');
            $pdf->Cell(31, 4, utf8_decode('ANO'), 1, 0, 'C');
            $pdf->Cell(5, 4, utf8_decode(''), 0, 0, 'C');
            $pdf->Cell(31, 4, utf8_decode('DD'), 1, 0, 'C');
            $pdf->Cell(31, 4, utf8_decode('MM'), 1, 0, 'C');
            $pdf->Cell(30, 4, utf8_decode('ANO'), 1, 0, 'C');
            $pdf->ln();
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(31, 4, utf8_decode(''), 1, 0, 'C');
            $pdf->Cell(31, 4, utf8_decode(''), 1, 0, 'C');
            $pdf->Cell(31, 4, utf8_decode(''), 1, 0, 'C');
            $pdf->Cell(5, 4, utf8_decode(''), 0, 0, 'C');
            $pdf->Cell(31, 4, utf8_decode(''), 1, 0, 'C');
            $pdf->Cell(31, 4, utf8_decode(''), 1, 0, 'C');
            $pdf->Cell(30, 4, utf8_decode(''), 1, 0, 'C');

            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(192, 4, utf8_decode('1. DATOS GENERALES DEL SOLICITANTE DEL SERVICIO'), 0, 0, 'l');
            $pdf->ln();
            $pdf->Cell(63, 4, utf8_decode('1er. APELLIDO'), 1, 0, 'C');
            $pdf->Cell(63, 4, utf8_decode('2do. APELLIDO'), 1, 0, 'C');
            $pdf->Cell(64, 4, utf8_decode('NOMBRES'), 1, 0, 'C');
            $pdf->ln();
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(63, 4, utf8_decode(self::$paciente->Primer_Ape), 1, 0, 'C');
            $pdf->Cell(63, 4, utf8_decode(self::$paciente->Segundo_Ape), 1, 0, 'C');
            $pdf->Cell(64, 4, utf8_decode(self::$paciente->Primer_Nom . ' ' . self::$paciente->SegundoNom), 1, 0, 'C');
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(63, 4, utf8_decode('TIPO DE IDENTIFICACION'), 1, 0, 'C');
            $pdf->Cell(63, 4, utf8_decode('No. DOCUMENTO IDENTIFICACION'), 1, 0, 'C');
            $pdf->Cell(64, 4, utf8_decode('No. DE CONTRATO'), 1, 0, 'C');
            $pdf->ln();
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(10, 4, utf8_decode('TI'), 0, 0, 'C');
            $pdf->Cell(10, 4, utf8_decode(self::$paciente->Tipo_Doc === 'TI' ? 'X' : ''), 1, 0, 'C');
            $pdf->Cell(10, 4, utf8_decode('CC'), 0, 0, 'C');
            $pdf->Cell(10, 4, utf8_decode(self::$paciente->Tipo_Doc === 'CC' ? 'X' : ''), 1, 0, 'C');
            $pdf->Cell(10, 4, utf8_decode('CE'), 0, 0, 'C');
            $pdf->Cell(10, 4, utf8_decode(self::$paciente->Tipo_Doc === 'CE' ? 'X' : ''), 1, 0, 'C');
            $pdf->Cell(3, 4, utf8_decode(''), 0, 0, 'C');
            $pdf->Cell(63, 4, utf8_decode(self::$paciente->Num_Doc), 1, 0, 'C');
            $pdf->Cell(64, 4, utf8_decode(self::$paciente->Num_Doc), 1, 0, 'C');
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(63, 4, utf8_decode('TELEFONO'), 1, 0, 'C');
            $pdf->Cell(63, 4, utf8_decode('CIUDAD / MUNICIPIO'), 1, 0, 'C');
            $pdf->Cell(64, 4, utf8_decode('DEPARTAMENTO'), 1, 0, 'C');
            $pdf->ln();
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(63, 4, utf8_decode(self::$paciente->Celular1), 1, 0, 'C');
            $pdf->Cell(63, 4, utf8_decode(self::$paciente->Mpio_Afiliado), 1, 0, 'C');
            $pdf->Cell(64, 4, utf8_decode(self::$paciente->Departamento), 1, 0, 'C');
            $pdf->Line(10, 122, 10, 162);
            $pdf->Line(200, 122, 200, 162);
            $pdf->Line(10, 122, 200, 122);
            $pdf->Line(10, 162, 200, 162);
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(192, 4, utf8_decode('TIPO PLAN USUARIO'), 0, 0, 'l');
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(20, 4, utf8_decode("POS"), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(8, 4, utf8_decode(''), 1, 0, 'L');
            $pdf->SetX(70);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(25, 4, utf8_decode("POS-S"), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(8, 4, utf8_decode(''), 1, 0, 'L');
            $pdf->SetX(110);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(50, 4, utf8_decode("PLAN COMPLENTARIO (PAC)"), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(8, 4, utf8_decode(''), 1, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(110, 4, utf8_decode("POBLACION POBRE NO CUBIERTA CON SUBSIDIO A LA DEMANDA"), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(8, 4, utf8_decode(''), 1, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(110, 4, utf8_decode("NRO. DE SEMANAS COTIZADAS POR EL USUARIO AL SGSSS"), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(8, 4, utf8_decode(''), 1, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(110, 4, utf8_decode("ESTADO DE LA AFILIACION / CONTRATO DEL USUARIO"), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(8, 4, utf8_decode(''), 1, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(20, 4, utf8_decode("VIGENTE"), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(8, 4, utf8_decode(''), 1, 0, 'L');
            $pdf->SetX(70);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(25, 4, utf8_decode("SUSPENDIDO"), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(8, 4, utf8_decode(''), 1, 0, 'L');
            $pdf->SetX(110);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(50, 4, utf8_decode("REMITIR SIN ASEGURAMIENTO"), 0, 0, 'L', 0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(8, 4, utf8_decode(''), 1, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(192, 4, utf8_decode('2. CLASE DE SERVICIO NO AUTORIZADO Y RECOMENDACIONES AL USUARIO'), 0, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(190, 4, utf8_decode('SERVICIO NO AUTORIZADO / CODIGO O MEDICAMENTO NO AUTORIZADO'), 1, 0, 'C', 1);
            $pdf->ln();
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(190, 4, utf8_decode(self::$paciente->Codigo . ' - ' . self::$paciente->Servicio), 1, 'l');
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(190, 4, utf8_decode('DESCRIPCION : (Señale el servicio / procedimiento / intervención)'), 1, 0, 'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->ln();
            $pdf->MultiCell(190, 4, utf8_decode(self::$paciente->Observacion), 1, 'l');
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(190, 4, utf8_decode('JUSTIFICACION: Indique el motivo de la negación'), 1, 0, 'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->ln();
            $pdf->MultiCell(190, 4, utf8_decode(self::$paciente->Motivo), 1, 'l');
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(190, 4, utf8_decode('FUNDAMENTO LEGAL: Relacione las disposiciones que presuntamente respaldan la decisión'), 1, 0, 'C', 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->ln();
            $pdf->MultiCell(190, 4, utf8_decode(''), 1, 'l');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->MultiCell(192, 4, utf8_decode('3. ALTERNATIVAS PARA QUE EL USUARIO ACCEDA AL SERVICIO DE SALUD O MEDICAMENTO SOLICITADO Y HAGA VALER SUS DERECHOS LEGALES Y CONSTITUCIONALES'), 0, 'J');
            $pdf->ln();
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(190, 4, utf8_decode('1.
2.
3.
4.'), 1, 'l');
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(120, 4, utf8_decode('NOMBRE Y CARGO DEL FUNCIONARIO QUE NIEGA EL SERVICIO'), 1, 0, 'L');
            $pdf->Cell(70, 4, utf8_decode('FIRMA'), 1, 0, 'L');
            $pdf->ln();
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(120, 4, utf8_decode(self::$paciente->name . ' ' . self::$paciente->apellido . ' (' . self::$paciente->especialidad_medico . ')'), 1, 0, 'L');
            $pdf->Cell(70, 4, utf8_decode(''), 1, 0, 'L');
            $pdf->ln();
            $pdf->ln();
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(190, 4, utf8_decode('FIRMA DEL USUARIO O DE QUIEN RECIBE'), 1, 0, 'L');
            $pdf->ln();
            $pdf->Cell(190, 4, utf8_decode(''), 1, 0, 'L');
            $pdf->ln();
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(185, 4, utf8_decode('Si está en desacuerdo con la decisión adoptada, acuda a la Oficina de Atención al Usuario de su EPS, si su queja no es resuelta, eleve consulta a la Superintendencia Nacional de Salud, anexando copia de este formato totalmente diligenciado a la carrera 13 No. 32-76 PBX 3300210 Ext. 3011 – 3039 Nota: Esto no indica que su reclamación sea aprobada por parte de la Superintendencia, es necesario hacer un estudio previo'), 0, 'C');

        }
    }
}




