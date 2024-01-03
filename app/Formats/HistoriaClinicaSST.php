<?php
namespace App\Formats;

use App\Modelos\Antecedenteocupacional;
use App\Modelos\Cie10;
use App\Modelos\Cie10paciente;
use App\Modelos\citapaciente;
use App\Modelos\Conducta;
use App\Modelos\Paciente;
use App\Modelos\Pacienteantecedente;
use App\Modelos\Parentescoantecedente;
use App\Modelos\Saludocupacional;
use App\User;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Carbon;

class HistoriaClinicaSST extends FPDF
{
    public static $datos;
    public static $paciente;
    public static $citapaciente;
    public static $antecedente_paciente;
    public static $antecedente_familiar;
    public static $antecedentes;

    public function generar($dato)
     {
         self::$citapaciente = citapaciente::find($dato);
         self::$datos = Saludocupacional::where('cita_paciente_id', $dato)->first();
         self::$paciente = Paciente::select('pacientes.*','departamentos.Nombre as dpto_labora')->leftjoin('departamentos','departamentos.id','pacientes.Dpto_Labora_id')->where('pacientes.id',self::$citapaciente->Paciente_id)->first();
         self::$antecedente_paciente = Pacienteantecedente::where('citapaciente_id', $dato)->get();
         self::$antecedente_familiar = Parentescoantecedente::where('Citapaciente_id', $dato)->get();
         self::$antecedentes = Antecedenteocupacional::where('cita_paciente_id',$dato)->get();

        $pdf = new HistoriaClinicaSST('P','mm','A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        if(self::$citapaciente->Tipocita_id == '13'){
            $this->bodyHistoriaPsicologia($pdf);
        }elseif(self::$citapaciente->Tipocita_id == '16'){
            $this->bodyMedicoOcupacional($pdf);
        }elseif(self::$citapaciente->Tipocita_id == '15'){
            $this->bodyVisiometria($pdf);
        }elseif(self::$citapaciente->Tipocita_id == '14'){
            $this->bodyVoz($pdf);
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
        $this->SetFont('Arial', '', 7);
        $this->Cell(10,4,utf8_decode('Página ').$this->PageNo().' de {nb}',0,0,'C');
    }

    public function bodyHistoriaPsicologia($pdf){
        // PSICOLOGIA
        $Y = 40;
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
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Primer_Nom.(self::$paciente->SegundoNom?' '.self::$paciente->SegundoNom:'').' '.self::$paciente->Primer_Ape.' '.self::$paciente->Segundo_Ape), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('IDENTIFICACÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Num_Doc), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('CELULAR'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Telefono.' - '.self::$paciente->Celular), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('#. DE HISTORIA CLINICA'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$citapaciente->id), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('FECHA HISTORIA'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(substr(self::$citapaciente->Datetimeingreso,0,19)), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('SEXO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Sexo), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 5);
        $pdf->Cell(36, 4, utf8_decode('DIRECCÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 4);
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Direccion_Residencia), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 4);
        $pdf->Cell(36, 4, utf8_decode('EMAIL'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 4);
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Direccion_Residencia), 1, 0, 'l');

        $medico = User::find(self::$citapaciente->user_medico_atiende);

        $pdf->SetXY(5, 50);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(200, 4, utf8_decode('DATOS DEL RESPONSABLE'), 0, 0, 'C');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 4, utf8_decode('NOMBRE'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(136, 4, utf8_decode($medico->name.' '. $medico->apellido), 1, 0, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 4, utf8_decode('DOCUMENTO'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(136, 4, utf8_decode($medico->cedula), 1, 0, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 4, utf8_decode('ESPECIALIDAD:'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(136, 4, utf8_decode(self::$citapaciente->salud_ocupacional.' '. 'Ocupacional'), 1, 0, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 4, utf8_decode('CIUDAD:'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(136, 4, utf8_decode('Medellín'), 1, 0, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 4, utf8_decode('FECHA DE CONSULTA:'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(136, 4, utf8_decode(self::$citapaciente->Datetimeingreso), 1, 0, 'C');



        $pdf->SetXY(12,79);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('MOTIVO DE CONSULTA'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(186, 4, utf8_decode(self::$citapaciente->Motivoconsulta), 1, 0, 'L',0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('ENFERMEDAD ACTUAL'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$citapaciente->Enfermedadactual), 1, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('ANTECEDENTES PERSONALES'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);


        $pdf->Ln();
        foreach (self::$antecedente_paciente as $ante) {

            $cie10p = Cie10::find($ante['cie10_id']);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(186, 4, utf8_decode($cie10p["Codigo_CIE10"]."-".$cie10p["Descripcion"]." (". strtolower($ante["Descripcion"]).")"), 1, 'C');
        }


        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('ANTECEDENTES FAMILIARES'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        foreach (self::$antecedente_familiar as $familiantecedente) {
            $cie10p = Cie10::find($familiantecedente['cie10_id']);
            $pdf->SetX(12);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(186, 4, utf8_decode($cie10p["Codigo_CIE10"]."-".$cie10p["Descripcion"]." (". strtolower($familiantecedente["Descripcion"]).")"), 1, 'C');
        }


        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('AREA COGNITIVA'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('PERCEPCION'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$datos->proceso_cognitivo), 1, 'l');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('MEMORIA'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$datos->memoria), 1, 'l');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('ATENCION'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$datos->atencion), 1, 'l');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('LENGUAJE'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$datos->lenguaje), 1, 'l');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('UBICADO EN'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(186, 4, utf8_decode(self::$datos->ubicacion), 1, 'l');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4,utf8_decode('PRESENTACION PERSONAL'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$datos->presentacion_personal), 1, 'l');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('ESTADO MENTAL'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$datos->estado_mental), 1, 'l');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('INTROSPECCION'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$datos->introspeccion), 1, 'l');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('PROSPECCION'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$datos->prospeccion), 1, 'l');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('AREA SOCIAL'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$datos->social), 1, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('AREA FAMILIAR'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$datos->familiar), 1, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('AREA LABORAL'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$datos->laboral), 1, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('AREA ACADEMICA'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$datos->academica), 1, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('ANALISIS DE DIAGNOSTICO'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$datos->analisis_diagnostico), 1, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('TENICA RECOLECCION DE DATOS'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$datos->recoleccion_datos), 1, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('DIAGNÓSTICOS'), 1, 0, 'C', 1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        #DX PRINCIPAL
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('DIAGNÓSTICO PRINCIPAL'), 1, 0, 'C', 1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $dxprincipal = Cie10paciente::select(['c.Codigo_CIE10 as cie10', 'c.Descripcion'])
            ->join('cie10s as c', 'c.id', 'cie10pacientes.Cie10_id')
            ->where('cie10pacientes.Citapaciente_id',self::$citapaciente->id)
            ->where('cie10pacientes.Esprimario', 1)
            ->first();
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 4, utf8_decode('CÓDIGO CIE10'), 1, 0, 'C');
        $pdf->Cell(96, 4, utf8_decode('DESCRIPCION DEL DIAGNÓSTICO'), 1, 0, 'C');
        $pdf->Cell(60, 4, utf8_decode('TIPO DEL DIAGNÓSTICO'), 1, 0, 'C');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode($dxprincipal->cie10), 1, 'l');
        $pdf->Cell(96, 4, utf8_decode($dxprincipal->Descripcion), 1, 'l');
        $pdf->Cell(60, 4, utf8_decode('Principal'), 1, 'l');

        #DX SEGUNDARIO
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('DIAGNÓSTICOS SEGUNDARIOS'), 1, 0, 'C', 1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 4, utf8_decode('CÓDIGO CIE10'), 1, 0, 'C');
        $pdf->Cell(96, 4, utf8_decode('DESCRIPCION DEL DIAGNÓSTICO'), 1, 0, 'C');
        $pdf->Cell(60, 4, utf8_decode('TIPO DEL DIAGNÓSTICO'), 1, 0, 'C');

        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);

        $dxsegundarios = Cie10paciente::select(['c.Codigo_CIE10 as cie10', 'c.Descripcion'])
        ->join('cie10s as c', 'c.id', 'cie10pacientes.Cie10_id')
        ->where('cie10pacientes.Citapaciente_id',self::$citapaciente->id)
        ->where('cie10pacientes.Esprimario', 0)
        ->get();
        foreach ($dxsegundarios as $dxsegu) {
            $pdf->SetX(12);
            $pdf->Cell(30, 4, utf8_decode($dxsegu['cie10']), 1, 'l');
            $pdf->Cell(96, 4, utf8_decode($dxsegu['Descripcion']), 1, 'l');
            $pdf->Cell(60, 4, utf8_decode('Segundario'), 1, 'l');
            $pdf->Ln();
        }



        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('CONCEPTO MÉDICO DE APTITUD LABORAL:'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$datos->aptitud_laboral_psicologia), 1, 'l');
        $pdf->Ln();

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('CONDUCTA'), 1, 0, 'C', 1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES PERSONALES:'), 1, 0, 'L',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $conducta = Conducta::where('citapaciente_id',self::$citapaciente->id)->first();
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode($conducta->Planmanejo), 1, 'l');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 215);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES LABORALES:'), 1, 0, 'l',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode($conducta->Recomendaciones), 1, 'l');
        $pdf->Ln();

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(45, 4, utf8_decode('DESTINO DEL PACIENTE:'), 0, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(130, 4, utf8_decode($conducta->Destinopaciente), 0, 'l');
        $pdf->Ln();

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(45, 4, utf8_decode('FINALIDAD:'), 0, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(130, 4, utf8_decode($conducta->Finalidad), 0, 'l');

        $pdf->Ln();
        #FIRMAS MEDICO
        if (file_exists(storage_path(substr($medico->Firma, 9)))) {
            $pdf->Image(storage_path(substr($medico->Firma, 9)), 30, 250, 56, 11);
        }
        $pdf->Line(20, 261, 90, 261);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(23, 261);
        $pdf->Cell(60, 4, utf8_decode('Atendido por:'. ' '. $medico->name. ' '. $medico->Apellido), 0, 0, 'l'); #NOMBRE COMPLETO DEL MEDICO
        $pdf->SetXY(23, 266);
        $pdf->Cell(60, 4, utf8_decode('Especialidad:'. ' '. $medico->especialidad_medico), 0, 0, 'l'); #ESPECIALIDAD DEL MEDICO
        $pdf->SetXY(23, 271);
        $pdf->Cell(32, 4, utf8_decode('REGISTRO Y LIC S.O:'), 0, 'l'); #REGISTRO MEDICO
        $pdf->MultiCell(30, 4, utf8_decode($medico->Registromedico), 0, 'l');

        // #FIRMAS

        // $pdf->SetX(120);
        // $pdf->MultiCell(55, 4, utf8_decode(''), 0, 'l');
        if(self::$citapaciente->firma_trabajador){
            if (file_exists(public_path().'/storage/saludOcupacional/firma/'.self::$citapaciente->firma_trabajador)) {
                $pdf->Image(public_path().'/storage/saludOcupacional/firma/'.self::$citapaciente->firma_trabajador, 130, 258, 56, 11);
            }
        }
        $pdf->Line(120, 270, 195, 270);
        $pdf->SetXY(120, 272);
        $pdf->Cell(85, 4, utf8_decode('Firma Empleado'), 0, 'l');
        $pdf->SetXY(170, 272);

        #FIRMAS PACIENTE
        // $pdf->Line(110, 261, 174, 261);
        // $pdf->SetFont('Arial', '', 8);
        // $pdf->SetXY(115, 258);
        // $pdf->Cell(50, 4, utf8_decode(self::$paciente->Primer_Nom. ' '.self::$paciente->SegundoNom. ' '.self::$paciente->Primer_Ape. ' '.self::$paciente->Segundo_Ape), 0 ,0, 'l'); #NOMBRE COMPLETO DEL PACIENTE
        // $pdf->SetXY(115, 262);
        // $pdf->Cell(8, 4, utf8_decode('CC'), 0, 'l'); #CEDULA PACIENTE
        // $pdf->MultiCell(36, 4, utf8_decode(self::$paciente->Num_Doc), 0, 'l');


    }

    public function bodyMedicoOcupacional($pdf){
        $Y = 40;
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
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Primer_Nom.(self::$paciente->SegundoNom?' '.self::$paciente->SegundoNom:'').' '.self::$paciente->Primer_Ape.' '.self::$paciente->Segundo_Ape), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('IDENTIFICACÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Num_Doc), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('CELULAR'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Telefono.' - '.self::$paciente->Celular), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('#. DE HISTORIA CLINICA'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$citapaciente->id), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('FECHA HISTORIA'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(substr(self::$citapaciente->Datetimeingreso,0,19)), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('SEXO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Sexo), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('DIRECCÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Direccion_Residencia), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('EMAIL'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Direccion_Residencia), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('CARGO A DESEMPEÑAR'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$datos->cargo), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('EMPRESA'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$datos->nombre_de_la_empresa), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(36, 4, utf8_decode('DEPARTAMENTO LABORA'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->dpto_labora), 1, 0, 'l');
        $pdf->Ln(5);


        $medico = User::find(self::$citapaciente->user_medico_atiende);

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);

        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('DATOS DEL RESPONSABLE'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 4, utf8_decode('NOMBRE'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(136, 4, utf8_decode($medico->name.' '. $medico->apellido), 1, 0, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 4, utf8_decode('DOCUMENTO'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(136, 4, utf8_decode($medico->cedula), 1, 0, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 4, utf8_decode('ESPECIALIDAD:'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(136, 4, utf8_decode(self::$citapaciente->salud_ocupacional.' '. 'Ocupacional'), 1, 0, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 4, utf8_decode('CIUDAD:'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(136, 4, utf8_decode('Medellín'), 1, 0, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 4, utf8_decode('FECHA DE CONSULTA:'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(136, 4, utf8_decode(self::$citapaciente->Datetimeingreso), 1, 0, 'C');


        $pdf->SetXY(12,79);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('MOTIVO DE CONSULTA'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(186, 4, utf8_decode(self::$citapaciente->Motivoconsulta), 1, 0, 'L',0);

        // $pdf->Ln();
        // $pdf->SetX(12);
        // $pdf->SetFont('Arial', 'B', 8);
        // $pdf->SetDrawColor(0,0,0);
        // $pdf->SetFillColor(214, 214, 214);
        // $pdf->SetTextColor(0,0,0);
        // $pdf->Cell(186, 4, utf8_decode('ENFERMEDAD ACTUAL'), 1, 0, 'C',1);
        // $pdf->SetTextColor(0,0,0);
        // $pdf->SetDrawColor(0,0,0);

        // $pdf->Ln();
        // $pdf->SetX(12);
        // $pdf->SetFont('Arial', '', 8);
        // $pdf->MultiCell(186, 4, utf8_decode(self::$citapaciente->Enfermedadactual), 1, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('ANTECEDENTES PERSONALES'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);


        $pdf->Ln();
        foreach (self::$antecedente_paciente as $ante) {

            $cie10p = Cie10::find($ante['cie10_id']);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(186, 4, utf8_decode($cie10p["Codigo_CIE10"]."-".$cie10p["Descripcion"]." (". strtolower($ante["Descripcion"]).")"), 1, 'C');
        }


        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('ANTECEDENTES FAMILIARES'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);


        foreach (self::$antecedente_familiar as $familiantecedente) {
            $pdf->Ln();
            $cie10p = Cie10::find($familiantecedente['cie10_id']);
            $pdf->SetX(12);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(186, 4, utf8_decode($cie10p["Codigo_CIE10"]."-".$cie10p["Descripcion"]." (". strtolower($familiantecedente["Descripcion"]).")"), 1, 'C');
        }

            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(43, 8, utf8_decode('EMPRESA'), 1, 0, 'C',1);
            $pdf->Cell(42, 8, utf8_decode('CARGO'), 1, 0,'C',1);
            $pdf->Cell(47, 4, utf8_decode('FACTORES DE RIESGO'), 1, 0,'C',1);
            $pdf->Cell(34, 8, utf8_decode('TIEMPO'), 1, 0,'C',1);
            $pdf->Cell(20, 8, utf8_decode('USO E.P.P.'), 1, 0,'C',1);

            $pdf->Ln();
            $pdf->SetXY(97,135);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(4, 4, utf8_decode('F'), 1, 0,'C',1);
            $pdf->Cell(4, 4, utf8_decode('Q'), 1, 0,'C',1);
            $pdf->Cell(4, 4, utf8_decode('B'), 1, 0,'C',1);
            $pdf->Cell(7, 4, utf8_decode('ERG'), 1, 0,'C',1);
            $pdf->Cell(7, 4, utf8_decode('PSIC'), 1, 0,'C',1);
            $pdf->Cell(7, 4, utf8_decode('MEC'), 1, 0,'C',1);
            $pdf->Cell(7, 4, utf8_decode('ELEC'), 1, 0,'C',1);
            $pdf->Cell(7, 4, utf8_decode('OTRO'), 1, 0,'C',1);

            $pdf->SetFont('Arial', '', 4);
            foreach(self::$antecedentes as $antecedente){
                if($antecedente['antecedente_empresa']){
                    $pdf->Ln();
                    $pdf->SetX(12);
                    $pdf->Cell(43, 4, utf8_decode($antecedente['antecedente_empresa']),   1, 0, 'C');
                    $pdf->SetFont('Arial', '', 6);
                    $pdf->Cell(42, 4, utf8_decode($antecedente['antecedente_cargo']),   1, 0, 'C');
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(4, 4, utf8_decode($antecedente['f']),   1, 0, 'C');
                    $pdf->Cell(4, 4, utf8_decode($antecedente['q']),   1, 0, 'C');
                    $pdf->Cell(4, 4, utf8_decode($antecedente['b']),   1, 0, 'C');
                    $pdf->Cell(7, 4, utf8_decode($antecedente['erg']),   1, 0, 'C');
                    $pdf->Cell(7, 4, utf8_decode($antecedente['psic']),   1, 0, 'C');
                    $pdf->Cell(7, 4, utf8_decode($antecedente['mec']),   1, 0, 'C');
                    $pdf->Cell(7, 4, utf8_decode($antecedente['elec']),   1, 0, 'C');
                    $pdf->Cell(7, 4, utf8_decode($antecedente['otro']),   1, 0, 'C');
                    $pdf->Cell(34, 4, utf8_decode($antecedente['tiempo']),  1, 0, 'C');
                    $pdf->Cell(20, 4, utf8_decode($antecedente['uso_e_p_p']),  1, 0, 'C');
                    $pdf->Ln();
                }
            }
                $pdf->Ln();
                $pdf->Ln();
                $pdf->SetX(18);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(50, 4, utf8_decode('¿HA SUFRIDO ACCIDENTES DE TRABAJO?'), 0, 0, 'l');
                $pdf->SetX(92);
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(4, 4, utf8_decode(self::$datos->Antecedentedetrabajo == 'SI' ?'X':'X'), 1, 0,'C');
                $pdf->Cell(6, 4, utf8_decode(self::$datos->Antecedentedetrabajo == 'SI' ?'SI':'NO'), 0, 0,'C');
                $pdf->SetX(102);
                $pdf->Ln();
                $pdf->Ln();

                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 214);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(19, 8, utf8_decode('FECHA'), 1, 0, 'C',1);
                $pdf->Cell(32, 8, utf8_decode('EMPRESA'), 1, 0,'C',1);
                $pdf->Cell(37, 8, utf8_decode('FACTOR  RIESGO'),1, 0,'C',1);
                $pdf->Cell(27, 8, utf8_decode('TIPO LESIÓN'), 1, 0,'C',1);
                $pdf->Cell(32, 8, utf8_decode('PARTE AFECTADA'), 1, 0,'C',1);
                $pdf->Cell(18, 8, utf8_decode('DÍAS INCAP'), 1, 0,'C',1);
                $pdf->Cell(21, 8, utf8_decode('SECUELAS'), 1, 0,'C',1);

                $pdf->SetFont('Arial', '', 8);
                foreach(self::$antecedentes as $antecedente){
                    if($antecedente['empresa_accidentes']){
                        $pdf->Ln();
                        $pdf->SetX(12);
                        $pdf->Cell(19, 4, utf8_decode($antecedente['fecha_accidentes']), 1, 0, 'C');
                        $pdf->Cell(32, 4, utf8_decode($antecedente['empresa_accidentes']), 1, 0, 'C');
                        $pdf->Cell(37, 4, utf8_decode($antecedente['causa']), 1, 0, 'C');
                        $pdf->Cell(27, 4, utf8_decode($antecedente['tipo_lesion']), 1, 0, 'C');
                        $pdf->Cell(32, 4, utf8_decode($antecedente['parte_afectada']), 1, 0, 'C');
                        $pdf->Cell(18, 4, utf8_decode($antecedente['dias_incap']), 1, 0, 'C');
                        $pdf->Cell(21, 4, utf8_decode($antecedente['secuelas']), 1, 0, 'C');
                        $pdf->Ln();
                    }

                }
                $pdf->Ln();
                $pdf->Ln();
                $pdf->SetX(18);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(62, 4, utf8_decode('¿HA SUFRIDO ENFERMEDADES PROFESIONALES?'), 0, 0, 'l');
                $pdf->SetX(92);
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(4, 4, utf8_decode(self::$datos->Antecedenteenfermedadlaboral == 'SI' ?'X':'X'), 1, 0,'C');
                $pdf->Cell(6, 4, utf8_decode(self::$datos->Antecedenteenfermedadlaboral == 'SI' ?'SI':'NO'), 0, 0,'C');
                $pdf->SetX(102);
                $pdf->Ln();
                $pdf->Ln();

                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 214);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(19, 4, utf8_decode('FECHA'), 1, 0, 'C',1);
                $pdf->Cell(41, 4, utf8_decode('EMPRESA'), 1, 0,'C',1);
                $pdf->Cell(41, 4, utf8_decode('DIAGNÓSTICO'), 1, 0,'C',1);
                $pdf->Cell(42, 4, utf8_decode('REUBICACIÓN'), 1, 0,'C',1);
                $pdf->Cell(42, 4, utf8_decode('INDEMNIZACIÓN'), 1, 0,'C',1);
                $pdf->SetFont('Arial', '', 8);
                $pdf->Ln();

                foreach(self::$antecedentes as $antecedente){
                    if($antecedente['diagnostico']){
                        $pdf->Ln();
                        $pdf->SetX(12);
                        $pdf->Cell(19, 4, utf8_decode($antecedente['fecha']),  1, 0, 'C');
                        $pdf->Cell(41, 4, utf8_decode($antecedente['empresa']),  1, 0, 'C');
                        $pdf->Cell(41, 4, utf8_decode($antecedente['diagnostico']),  1, 0, 'C');
                        $pdf->Cell(42, 4, utf8_decode($antecedente['reubicacion']),  1, 0, 'C');
                        $pdf->Cell(42, 4, utf8_decode($antecedente['indemnizacion']),  1, 0, 'C');

                    }
                }
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 214);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(186, 4, utf8_decode('VACUNAS'), 1, 0, 'C',1);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetDrawColor(0,0,0);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 214);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(62, 4, utf8_decode('HEPATITIS'), 1, 0, 'C',1);
                $pdf->Cell(62, 4, utf8_decode('DOSIS'), 1, 0, 'C',1);
                $pdf->Cell(62, 4, utf8_decode('ULTIMA DOSIS'), 1, 0, 'C',1);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetDrawColor(0,0,0);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(62, 4, utf8_decode(self::$datos->hepatitis), 1, 'l');
                $pdf->Cell(62, 4, utf8_decode(''), 1, 'l');
                $pdf->Cell(62, 4, utf8_decode(''), 1, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 214);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(62, 4, utf8_decode('TT'), 1, 0, 'C',1);
                $pdf->Cell(62, 4, utf8_decode('TD'), 1, 0, 'C',1);
                $pdf->Cell(62, 4, utf8_decode('DOSIS'), 1, 0, 'C',1);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetDrawColor(0,0,0);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(62, 4, utf8_decode(self::$datos->t_t), 1, 'l');
                $pdf->Cell(62, 4, utf8_decode(self::$datos->t_d), 1, 'l');
                $pdf->Cell(62, 4, utf8_decode(self::$datos->dosi), 1, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 214);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(93, 4, utf8_decode('OTROS'), 1, 0, 'C',1);
                $pdf->Cell(93, 4, utf8_decode('FECHA'), 1, 0, 'C',1);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetDrawColor(0,0,0);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(93, 4, utf8_decode(self::$datos->otras), 1, 'l');
                $pdf->Cell(93, 4, utf8_decode(self::$datos->Fechas), 1, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 214);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(186, 4, utf8_decode('OBSERVACION'), 1, 0, 'C',1);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetDrawColor(0,0,0);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode(self::$datos->observacion_vacunas), 1, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 214);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(186, 4, utf8_decode('HABITOS'), 1, 0, 'C', 1);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetDrawColor(0,0,0);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 214);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(62, 4, utf8_decode('FUMA'), 1, 0, 'C',1);
                $pdf->Cell(62, 4, utf8_decode('AÑOS FUMANDO'), 1, 0, 'C',1);
                $pdf->Cell(62, 4, utf8_decode('CIGARRILLOS'), 1, 0, 'C',1);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetDrawColor(0,0,0);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(62, 4, utf8_decode(self::$datos->fuma), 1, 'l');
                $pdf->Cell(62, 4, utf8_decode(self::$datos->fumo), 1, 'l');
                $pdf->Cell(62, 4, utf8_decode(self::$datos->anos_fumador), 1, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 214);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(93, 4, utf8_decode('FUMO'), 1, 0, 'C',1);
                $pdf->cell(93,4, utf8_decode('HACE CUANTO NO FUMA:'),1,0,'L',1);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetDrawColor(0,0,0);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(93, 4, utf8_decode(self::$datos->cigarrillos_al_dia), 1, 'l');
                $pdf->Cell(93, 4, utf8_decode(self::$datos->hace_cuanto_no_fuma), 1, 'C');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 214);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(62, 4, utf8_decode('ALCOHOL'), 1, 0, 'C',1);
                $pdf->Cell(62, 4, utf8_decode('FRECUENCIA'), 1, 0, 'C',1);
                $pdf->Cell(62, 4, utf8_decode('TIPO'), 1, 0, 'C',1);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetDrawColor(0,0,0);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(62, 4, utf8_decode(self::$datos->alcohol), 1, 'l');
                $pdf->Cell(62, 4, utf8_decode(self::$datos->frecuencia), 1, 'l');
                $pdf->Cell(62, 4, utf8_decode(self::$datos->tipo), 1, 'l');


                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 214);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(93, 4, utf8_decode('SUSTANCIAS PSICO-ACTIVAS'), 1, 0, 'C',1);
                $pdf->Cell(93, 4, utf8_decode('CUALES'), 1, 0, 'C',1);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetDrawColor(0,0,0);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(93, 4, utf8_decode(self::$datos->sustancias_psico_activas), 1, 'l');
                $pdf->Cell(93, 4, utf8_decode(self::$datos->cuales), 1, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 214);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(62, 4, utf8_decode('PRACTICA UN DEPORTE'), 1, 0, 'C',1);
                $pdf->Cell(62, 4, utf8_decode('CUAL'), 1, 0, 'C',1);
                $pdf->Cell(62, 4, utf8_decode('REGULARIDAD'), 1, 0, 'C',1);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetDrawColor(0,0,0);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(62, 4, utf8_decode(self::$datos->practica_deporte), 1, 'l');
                $pdf->Cell(62, 4, utf8_decode(self::$datos->cual), 1, 'l');
                $pdf->Cell(62, 4, utf8_decode(self::$datos->regularidad), 1, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 214);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(186, 4, utf8_decode('REVISION DE SISTEMAS'), 1, 0, 'C', 1);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetDrawColor(0,0,0);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(45, 4, utf8_decode('CABEZA Y ORL:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(130, 4, utf8_decode(self::$datos->cabeza_y_orl), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(45, 4, utf8_decode('SISTEMA NEUROLOGICO:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(130, 4, utf8_decode(self::$datos->sistema_neurologico), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(45, 4, utf8_decode('SISTEMA CARDIOPULMONAR:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(130, 4, utf8_decode(self::$datos->sistema_cardiopulmonar), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(45, 4, utf8_decode('SISTEMA DIGESTIVO:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(130, 4, utf8_decode(self::$datos->sistema_digestivo), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(45, 4, utf8_decode('SISTEMA MUSCULO ESQUELETICO:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(130, 4, utf8_decode(self::$datos->sistema_musculo_esqueletico), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(45, 4, utf8_decode('SISTEMA GENITOURINARIO:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(130, 4, utf8_decode(self::$datos->sistema_genitourinario), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(45, 4, utf8_decode('PIEL Y FRANELAS:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(130, 4, utf8_decode(self::$datos->piel_y_faneras), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(45, 4, utf8_decode('OTROS:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(130, 4, utf8_decode(self::$datos->otros), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 214);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(175, 4, utf8_decode('ESPIROMETRIA'), 1, 0, 'C', 1);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetDrawColor(0,0,0);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(175, 4, utf8_decode(self::$datos->espirometria == 'si'?self::$datos->espirometria_si:self::$datos->espirometria_no), 1, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 214);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(175, 4, utf8_decode('CONDICIONES DE SALUD'), 1, 0, 'C', 1);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetDrawColor(0,0,0);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(45, 4, utf8_decode('TIPO DE EXAMEN:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(130, 4, utf8_decode(self::$datos->tipoExamen), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(62, 4, utf8_decode('EXP. A FACTORES DE RIESGO OCUPACIONALES:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(113, 4, utf8_decode(self::$datos->factoresRiesgo), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(45, 4, utf8_decode('ANTECEDENTES DE ENFERMEDAD:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(130, 4, utf8_decode(self::$datos->antecedentesenfermedad), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(45, 4, utf8_decode('ORIGEN DE ENFERMEDADES:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(130, 4, utf8_decode(self::$datos->origenEnfermedades), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(62, 4, utf8_decode('ANTECEDENTE DE ACCIDENTE DE TRABAJO:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(113, 4, utf8_decode(self::$datos->antecedentedetrabajo), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(62, 4, utf8_decode('ANTECEDENTE DE ENFERMEDAD LABORAL:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(113, 4, utf8_decode(self::$datos->antecedenteenfermedadlaboral), 0, 'l');

                // $pdf->Line(18, 9, 18, 274); #LINA VERTICAL IZQUIERDA
                // $pdf->Line(193, 9, 193, 274); #LINEA VERTICAL DERECHA
                // $pdf->Line(18, 9, 193, 9); #LINEA HORIZONTAL ARRIBA
                // $pdf->Line(18, 274, 193, 274); #LINEA HORIZONTAL FINAL

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(45, 4, utf8_decode('ENFERMEDAD COMÚN PREVIA:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(130, 4, utf8_decode(self::$datos->enfermedadComun), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(45, 4, utf8_decode('ANTECEDENTES FAMILIARES:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(130, 4, utf8_decode(self::$datos->antecedentesfamiliares), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(45, 4, utf8_decode('PATOLOGÍA DE ANTECEDENTE:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(130, 4, utf8_decode(self::$datos->patologiaAntecedente), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(45, 4, utf8_decode('INDICE DE MASA CORPORAL:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(130, 4, utf8_decode(self::$datos->masaCorporal), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(45, 4, utf8_decode('PATOLOGÍA OSTEOMUSCULAR:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(130, 4, utf8_decode(self::$datos->patologiaOsteomuscular), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(45, 4, utf8_decode('PATOLOGÍA CARDIOVASCULAR:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(130, 4, utf8_decode(self::$datos->patologiaCardiovascular), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(45, 4, utf8_decode('PATOLOGÍA METABOLICA:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(130, 4, utf8_decode(self::$datos->patologiaMetabolica), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(62, 4, utf8_decode('DIAGNÓSTICOS INFECCIOSOS O PARASITARIA:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(113, 4, utf8_decode(self::$datos->infecciososParasitaria), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(64, 4, utf8_decode('PA. DE SANGRE Y ORGANOS HEMATOPOYETICOS:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(111, 4, utf8_decode(self::$datos->patologiaSangre), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(62, 4, utf8_decode('TRASTOR. MENTALES Y DEL COMPORTAMIENTO:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(113, 4, utf8_decode(self::$datos->trastronosMentales), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(52, 4, utf8_decode('PATOLOGÍA DEL SISTEMA NERVIOSO:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(123, 4, utf8_decode(self::$datos->patologiaNervioso), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(52, 4, utf8_decode('PATOLOGÍA DE OJO Y SUS ANEXOS:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(123, 4, utf8_decode(self::$datos->patologiaOjo), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(62, 4, utf8_decode('PATOLOGÍA DE OIDO Y LA APOFISIS MASTOIDES:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(113, 4, utf8_decode(self::$datos->patologiaOido), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(62, 4, utf8_decode('PATOLOGÍA DEL SISTEMA RESPIRATORIO:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(113, 4, utf8_decode(self::$datos->patologiaRespiratorio), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(52, 4, utf8_decode('PATOLOGÍA DEL APARATO DIGESTIVO:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(123, 4, utf8_decode(self::$datos->patologiaDigestivo), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(62, 4, utf8_decode('PATOLOGÍA DE LA PIEL O TEJIDO SUBCUTANEO:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(113, 4, utf8_decode(self::$datos->patologiaPiel), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(52, 4, utf8_decode('PATOLOGíA DEL APARATO URINARIO:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(123, 4, utf8_decode(self::$datos->patologiaUrinario), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(82, 4, utf8_decode('MALFORMACIÓN CONGENITAS Y ANOMALIAS CROMOSOMICAS:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(93, 4, utf8_decode(self::$datos->malformacionCongenitas), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 214);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(175, 4, utf8_decode('EXAMEN FÍSICO'), 1, 0, 'C', 1);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetDrawColor(0,0,0);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(44, 4, utf8_decode('CONDICIONES GENERALES:'), 0, 0, 'l');
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(175, 4, utf8_decode(self::$datos->condiciones_generales), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(33, 4, utf8_decode('DOMINACION MOTORA:'), 0, 0, 'l');
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->MultiCell(30, 4, utf8_decode(self::$datos->dominancia_motora), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(33, 4, utf8_decode('TALLA (Centimetros):'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(10, 4, utf8_decode(self::$datos->talla), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(18, 4, utf8_decode('PESO (Kg):'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(10, 4, utf8_decode(self::$datos->peso), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(41, 4, utf8_decode('FRECUENCIA CARDÍACA:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(10, 4, utf8_decode(self::$datos->f_c), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(43, 4, utf8_decode('FRECUENCIA RESPIRATORIA:'), 0, 0, 'l');

                $pdf->MultiCell(10, 4, utf8_decode(self::$datos->f_r), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(9, 4, utf8_decode('IMC:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(10, 4, utf8_decode(self::$datos->imc), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(30, 4, utf8_decode('TENSION ARTERIAL:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(10, 4, utf8_decode(self::$datos->t), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('CABEZA:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->cabeza), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('OJOS:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->ojos), 0, 'l');


                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('FONDO DE OJOS:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->fondo_de_ojos), 0, 'l');
                $pdf->Ln();

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('OIDOS:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->oidos), 0, 'l');
                $pdf->Ln();

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('OTOSCOPIA:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->otoscopia), 0, 'l');
                $pdf->Ln();

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('NARIZ:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->nariz), 0, 'l');
                $pdf->Ln();

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('BOCA:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->boca), 0, 'l');
                $pdf->Ln();

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('DENTADURA:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->dentadura), 0, 'l');
                $pdf->Ln();

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('FARINGE:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->faringe), 0, 'l');
                $pdf->Ln();

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('CUELLO:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->cuello), 0, 'l');
                $pdf->Ln();

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('MAMAS:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->mamas), 0, 'l');
                $pdf->Ln();

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('TORAX:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->torax), 0, 'l');
                $pdf->Ln();

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('CORAZON:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->corazon), 0, 'l');
                $pdf->Ln();

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('PULMONES:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->pulmones), 0, 'l');
                $pdf->Ln();

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('COLUMNA:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->columna), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('ABDOMEN:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->abdomen), 0, 'l');


                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('GENITALES EXTERNOS:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->genitales_externos), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('MIEMBROS SUPERIORES:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->miembros_sup), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('MIEMBROS INFERIORES:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->miembros_inf), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('REFELJOS:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->reflejos), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('MOTILIDAD:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->motilidad), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('FUERZA MUSCULAR:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->fuerza_muscular), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('MARCHA:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->marcha), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('PIEL Y FRANELAS:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(137, 4, utf8_decode(self::$datos->piel_faneras), 0, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(38, 4, utf8_decode('AMPLIACION DE HALLAZGOS:'), 0, 0, 'l');
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(175, 4, utf8_decode(self::$datos->ampliacion_hallazgos), 0, 'l');

                #DX PRINCIPAL
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 214);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(186, 4, utf8_decode('DIAGNÓSTICO PRINCIPAL'), 1, 0, 'C', 1);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetDrawColor(0,0,0);

                $dxprincipal = Cie10paciente::select(['c.Codigo_CIE10 as cie10', 'c.Descripcion'])
                    ->join('cie10s as c', 'c.id', 'cie10pacientes.Cie10_id')
                    ->where('cie10pacientes.Citapaciente_id',self::$citapaciente->id)
                    ->where('cie10pacientes.Esprimario', 1)
                    ->first();
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(30, 4, utf8_decode('CÓDIGO CIE10'), 1, 0, 'C');
                $pdf->Cell(96, 4, utf8_decode('DESCRIPCION DEL DIAGNÓSTICO'), 1, 0, 'C');
                $pdf->Cell(60, 4, utf8_decode('TIPO DEL DIAGNÓSTICO'), 1, 0, 'C');
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->Cell(30, 4, utf8_decode($dxprincipal->cie10), 1, 'l');
                $pdf->Cell(96, 4, utf8_decode($dxprincipal->Descripcion), 1, 'l');
                $pdf->Cell(60, 4, utf8_decode('Principal'), 1, 'l');

                #DX SEGUNDARIO
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 214);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(186, 4, utf8_decode('DIAGNÓSTICOS SEGUNDARIOS'), 1, 0, 'C', 1);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetDrawColor(0,0,0);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(30, 4, utf8_decode('CÓDIGO CIE10'), 1, 0, 'C');
                $pdf->Cell(96, 4, utf8_decode('DESCRIPCION DEL DIAGNÓSTICO'), 1, 0, 'C');
                $pdf->Cell(60, 4, utf8_decode('TIPO DEL DIAGNÓSTICO'), 1, 0, 'C');

                $pdf->Ln();

                $pdf->SetFont('Arial', '', 8);

                $dxsegundarios = Cie10paciente::select(['c.Codigo_CIE10 as cie10', 'c.Descripcion'])
                ->join('cie10s as c', 'c.id', 'cie10pacientes.Cie10_id')
                ->where('cie10pacientes.Citapaciente_id',self::$citapaciente->id)
                ->where('cie10pacientes.Esprimario', 0)
                ->get();
                foreach ($dxsegundarios as $dxsegu) {
                    $pdf->SetX(12);
                    $pdf->Cell(30, 4, utf8_decode($dxsegu['cie10']), 1, 'l');
                    $pdf->Cell(96, 4, utf8_decode($dxsegu['Descripcion']), 1, 'l');
                    $pdf->Cell(60, 4, utf8_decode('Segundario'), 1, 'l');
                    $pdf->Ln();
                }


                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 214);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(186, 4, utf8_decode('CONCEPTO MÉDICO DE APTITUD LABORAL'), 1, 0, 'C',1);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetDrawColor(0,0,0);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode(self::$datos->aptitud_laboral_medico), 1, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 214);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(186, 4, utf8_decode('SISTEMA DE VIGILANCIA EPIDEMIOLOGICA'), 1, 0, 'C',1);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetDrawColor(0,0,0);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode(self::$datos->vigilancia_epidemiologico), 1, 'l');

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 214);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(186, 4, utf8_decode('CONDUCTA'), 1, 0, 'C', 1);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetDrawColor(0,0,0);

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 214);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(186, 4, utf8_decode('PLAN DE MANEJO'), 1, 0, 'L',1);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetDrawColor(0,0,0);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $conducta = Conducta::where('citapaciente_id',self::$citapaciente->id)->first();
                $pdf->MultiCell(186, 4, utf8_decode($conducta->Planmanejo), 1, 'l');
                $pdf->Ln();

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetDrawColor(0,0,0);
                $pdf->SetFillColor(214, 214, 215);
                $pdf->SetTextColor(0,0,0);
                $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES Y/O RESTRICCIONES LABORALES:'), 1, 0, 'l',1);
                $pdf->SetTextColor(0,0,0);
                $pdf->SetDrawColor(0,0,0);
                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(186, 4, utf8_decode($conducta->Recomendaciones), 1, 'l');
                $pdf->Ln();

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(45, 4, utf8_decode('DESTINO DEL PACIENTE:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(130, 4, utf8_decode($conducta->Destinopaciente), 0, 'l');
                $pdf->Ln();

                $pdf->Ln();
                $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->Cell(45, 4, utf8_decode('FINALIDAD:'), 0, 0, 'l');
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(130, 4, utf8_decode($conducta->Finalidad), 0, 'l');

                $pdf->Ln();
                #FIRMAS MEDICO
                if (file_exists(storage_path(substr($medico->Firma, 9)))) {
                    $pdf->Image(storage_path(substr($medico->Firma, 9)), 30, 250, 56, 11);
                }
                $pdf->Line(20, 261, 90, 261);
                $pdf->SetFont('Arial', '', 8);
                $pdf->SetXY(23, 261);
                $pdf->Cell(60, 4, utf8_decode('Atendido por:'. ' '. $medico->name. ' '. $medico->Apellido), 0, 0, 'l'); #NOMBRE COMPLETO DEL MEDICO
                $pdf->SetXY(23, 266);
                $pdf->Cell(60, 4, utf8_decode('Especialidad:'. ' '. $medico->especialidad_medico), 0, 0, 'l'); #ESPECIALIDAD DEL MEDICO
                $pdf->SetXY(23, 271);
                $pdf->Cell(32, 4, utf8_decode('REGISTRO Y LIC S.O:'), 0, 'l'); #REGISTRO MEDICO
                $pdf->MultiCell(30, 4, utf8_decode($medico->Registromedico), 0, 'l');


                #FIRMAS

                // $pdf->SetX(120);
                // $pdf->MultiCell(55, 4, utf8_decode(''), 0, 'l');
                if(self::$citapaciente->firma_trabajador){
                    if (file_exists(public_path().'/storage/saludOcupacional/firma/'.self::$citapaciente->firma_trabajador)) {
                        $pdf->Image(public_path().'/storage/saludOcupacional/firma/'.self::$citapaciente->firma_trabajador, 130, 258, 56, 11);
                    }
                }
                $pdf->Line(120, 270, 195, 270);
                $pdf->SetXY(120, 272);
                $pdf->Cell(85, 4, utf8_decode('Firma Empleado'), 0, 'l');
                $pdf->SetXY(170, 272);

                // #FIRMAS PACIENTE
                // $pdf->Line(110, 261, 174, 261);
                // $pdf->SetFont('Arial', '', 8);
                // $pdf->SetXY(115, 258);
                // $pdf->Cell(50, 4, utf8_decode(self::$paciente->Primer_Nom. ' '.self::$paciente->SegundoNom. ' '.self::$paciente->Primer_Ape. ' '.self::$paciente->Segundo_Ape), 0 ,0, 'l'); #NOMBRE COMPLETO DEL PACIENTE
                // $pdf->SetXY(115, 262);
                // $pdf->Cell(8, 4, utf8_decode('CC'), 0, 'l'); #CEDULA PACIENTE
                // $pdf->MultiCell(36, 4, utf8_decode(self::$paciente->Num_Doc), 0, 'l');

    }

    public function bodyVisiometria($pdf){
        $Y = 40;
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
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Primer_Nom.(self::$paciente->SegundoNom?' '.self::$paciente->SegundoNom:'').' '.self::$paciente->Primer_Ape.' '.self::$paciente->Segundo_Ape), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('IDENTIFICACÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Num_Doc), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('CELULAR'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Telefono.' - '.self::$paciente->Celular), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('#. DE HISTORIA CLINICA'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$citapaciente->id), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('FECHA HISTORIA'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(substr(self::$citapaciente->Datetimeingreso,0,19)), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('SEXO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Sexo), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('DIRECCION'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Direccion_Residencia), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('EMAIL'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Direccion_Residencia), 1, 0, 'l');

        $medico = User::find(self::$citapaciente->user_medico_atiende);

        $pdf->SetXY(5, 50);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(200, 4, utf8_decode('DATOS DEL RESPONSABLE'), 0, 0, 'C');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 4, utf8_decode('NOMBRE'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(136, 4, utf8_decode($medico->name.' '. $medico->apellido), 1, 0, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 4, utf8_decode('DOCUMENTO'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(136, 4, utf8_decode($medico->cedula), 1, 0, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 4, utf8_decode('ESPECIALIDAD:'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(136, 4, utf8_decode(self::$citapaciente->salud_ocupacional.' '. 'Ocupacional'), 1, 0, 'C');


        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 4, utf8_decode('CIUDAD:'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(136, 4, utf8_decode('Medellín'), 1, 0, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 4, utf8_decode('FECHA DE CONSULTA:'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(136, 4, utf8_decode(self::$citapaciente->Datetimeingreso), 1, 0, 'C');


        $pdf->SetXY(12,79);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('MOTIVO DE CONSULTA'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(186, 4, utf8_decode(self::$citapaciente->Motivoconsulta), 1, 0, 'L',0);

        // $pdf->Ln();
        // $pdf->SetX(12);
        // $pdf->SetFont('Arial', 'B', 8);
        // $pdf->SetDrawColor(0,0,0);
        // $pdf->SetFillColor(214, 214, 214);
        // $pdf->SetTextColor(0,0,0);
        // $pdf->Cell(186, 4, utf8_decode('ENFERMEDAD ACTUAL'), 1, 0, 'C',1);
        // $pdf->SetTextColor(0,0,0);
        // $pdf->SetDrawColor(0,0,0);

        // $pdf->Ln();
        // $pdf->SetX(12);
        // $pdf->SetFont('Arial', '', 8);
        // $pdf->MultiCell(186, 4, utf8_decode(self::$citapaciente->Enfermedadactual), 1, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('ANTECEDENTES PERSONALES'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);


        $pdf->Ln();
        foreach (self::$antecedente_paciente as $ante) {

            $cie10p = Cie10::find($ante['cie10_id']);
            $pdf->SetX(12);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(186, 4, utf8_decode($cie10p["Codigo_CIE10"]."-".$cie10p["Descripcion"]." (". strtolower($ante["Descripcion"]).")"), 1, 'C');
            $pdf->Ln();

        }


        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('ANTECEDENTES FAMILIARES'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        foreach (self::$antecedente_familiar as $familiantecedente) {
            $cie10p = Cie10::find($familiantecedente['cie10_id']);
            $pdf->SetX(12);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(186, 4, utf8_decode($cie10p["Codigo_CIE10"]."-".$cie10p["Descripcion"]." (". strtolower($familiantecedente["Descripcion"]).")"), 1, 'C');
            $pdf->Ln();
        }

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 6, utf8_decode('AGUDEZA VISUAL'), 1, 0, 'C',1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(62, 4, utf8_decode(''), 1, 0, 'C');
        $pdf->Cell(62, 4, utf8_decode('OJO DERECHO'), 1, 0, 'C',1);
        $pdf->Cell(62, 4, utf8_decode('OJO IZQUIERDO'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->Cell(62, 4, utf8_decode('AVCC'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(62, 4, utf8_decode(self::$datos->avcc_ojoderecho), 1, 0, 'C');
        $pdf->Cell(62, 4, utf8_decode(self::$datos->avcc_ojoizquierdo), 1, 0, 'C');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(62, 4, utf8_decode('AVSC'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(62, 4, utf8_decode(self::$datos->avsc_ojoderecho), 1, 0, 'C');
        $pdf->Cell(62, 4, utf8_decode(self::$datos->avsc_ojoizquierdo), 1, 0, 'C');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(62, 4, utf8_decode('AVSC Av CERCA'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(62, 4, utf8_decode(self::$datos->avscav_ojoderecho), 1, 0, 'C');
        $pdf->Cell(62, 4, utf8_decode(self::$datos->avscav_ojoizquierdo), 1, 0, 'C');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(62, 4, utf8_decode('Av CERCA'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(62, 4, utf8_decode(self::$datos->avcerca_ojoderecho), 1, 0, 'C');
        $pdf->Cell(62, 4, utf8_decode(self::$datos->avcerca_ojoizquierdo), 1, 0, 'C');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 6, utf8_decode('MOTILIDAD OCULAR'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$datos->motilidad_ocular), 1,'L');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 6, utf8_decode('ESTEREOPSIS'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$datos->estereopsis), 1,'L');
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 6, utf8_decode('VISIÓN AL COLOR'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$datos->vision_color), 1,'L');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 6, utf8_decode('RETINOSCOPIA'), 1, 0, 'C',1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(93, 4, utf8_decode('OJO DERECHO'), 1, 0, 'C',1);
        $pdf->Cell(93, 4, utf8_decode('OJO IZQUIERDO'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(93, 4, utf8_decode(self::$datos->retinoscopia_ojoderecho), 1, 0, 'C');
        $pdf->Cell(93, 4, utf8_decode(self::$datos->retinoscopia_ojoizquierdo), 1, 0, 'C');
        $pdf->Ln(8);

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 6, utf8_decode('BIOMICROSCOPIA'), 1, 0, 'C',1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(93, 4, utf8_decode('OJO DERECHO'), 1, 0, 'C',1);
        $pdf->Cell(93, 4, utf8_decode('OJO IZQUIERDO'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(93, 4, utf8_decode(self::$datos->gionios_ojoderecho), 1, 0, 'C');
        $pdf->Cell(93, 4, utf8_decode(self::$datos->gionios_ojoizquierdo), 1, 0, 'C');
        $pdf->Ln(8);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 6, utf8_decode('FONDO DE OJO'), 1, 0, 'C',1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(93, 4, utf8_decode('OJO DERECHO'), 1, 0, 'C',1);
        $pdf->Cell(93, 4, utf8_decode('OJO IZQUIERDO'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(93, 4, utf8_decode(self::$datos->fondo_ojoderecho), 1, 0, 'C');
        $pdf->Cell(93, 4, utf8_decode(self::$datos->fondo_ojoizquierdo), 1, 0, 'C');
        $pdf->Ln(8);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 6, utf8_decode('CONCEPTO DE ACTITUD LABORAL (VISIOMETRIA)'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$datos->aptitud_laboral_visiomretria), 1,'L');
        $pdf->Ln();
        #FIRMAS MEDICO
        if (file_exists(storage_path(substr($medico->Firma, 9)))) {
            $pdf->Image(storage_path(substr($medico->Firma, 9)), 30, 250, 56, 11);
        }
        $pdf->Line(20, 261, 90, 261);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(23, 261);
        $pdf->Cell(60, 4, utf8_decode('Atendido por:'. ' '. $medico->name. ' '. $medico->Apellido), 0, 0, 'l'); #NOMBRE COMPLETO DEL MEDICO
        $pdf->SetXY(23, 266);
        $pdf->Cell(60, 4, utf8_decode('Especialidad:'. ' '. $medico->especialidad_medico), 0, 0, 'l'); #ESPECIALIDAD DEL MEDICO
        $pdf->SetXY(23, 271);
        $pdf->Cell(32, 4, utf8_decode('REGISTRO Y LIC S.O:'), 0, 'l'); #REGISTRO MEDICO
        $pdf->MultiCell(30, 4, utf8_decode($medico->Registromedico), 0, 'l');

        #FIRMAS PACIENTE
        if(self::$citapaciente->firma_trabajador){
            if (file_exists(public_path().'/storage/saludOcupacional/firma/'.self::$citapaciente->firma_trabajador)) {
                $pdf->Image(public_path().'/storage/saludOcupacional/firma/'.self::$citapaciente->firma_trabajador, 130, 258, 56, 11);
            }
        }
        $pdf->Line(120, 270, 195, 270);
        $pdf->SetXY(120, 272);
        $pdf->Cell(85, 4, utf8_decode('Firma Empleado'), 0, 'l');
        $pdf->SetXY(170, 272);

        // $pdf->Line(110, 261, 174, 261);
        // $pdf->SetFont('Arial', '', 8);
        // $pdf->SetXY(115, 258);
        // $pdf->Cell(50, 4, utf8_decode(self::$paciente->Primer_Nom. ' '.self::$paciente->SegundoNom. ' '.self::$paciente->Primer_Ape. ' '.self::$paciente->Segundo_Ape), 0 ,0, 'l'); #NOMBRE COMPLETO DEL PACIENTE
        // $pdf->SetXY(115, 262);
        // $pdf->Cell(8, 4, utf8_decode('CC'), 0, 'l'); #CEDULA PACIENTE
        // $pdf->MultiCell(36, 4, utf8_decode(self::$paciente->Num_Doc), 0, 'l');

    }

    public function bodyVoz($pdf){
        $Y = 40;
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
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Primer_Nom.(self::$paciente->SegundoNom?' '.self::$paciente->SegundoNom:'').' '.self::$paciente->Primer_Ape.' '.self::$paciente->Segundo_Ape), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('IDENTIFICACÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Num_Doc), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('CELULAR'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Telefono.' - '.self::$paciente->Celular), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('#. DE HISTORIA CLINICA'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$citapaciente->id), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('FECHA HISTORIA'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(substr(self::$citapaciente->Datetimeingreso,0,19)), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('SEXO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Sexo), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('DIRECCÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Direccion_Residencia), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('EMAIL'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->Direccion_Residencia), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('CARGO A DESEMPEÑAR'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$datos->cargo), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(36, 4, utf8_decode('EMPRESA'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$datos->nombre_de_la_empresa), 1, 0, 'l');
        $pdf->Ln();
        $pdf->SetX(70);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(36, 4, utf8_decode('DEPARTAMENTO LABORA'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(92, 4, utf8_decode(self::$paciente->dpto_labora), 1, 0, 'l');
        $pdf->Ln(5);


        $medico = User::find(self::$citapaciente->user_medico_atiende);

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('DATOS DEL RESPONSABLE'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 4, utf8_decode('NOMBRE'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(136, 4, utf8_decode($medico->name.' '. $medico->apellido), 1, 0, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 4, utf8_decode('DOCUMENTO'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(136, 4, utf8_decode($medico->cedula), 1, 0, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 4, utf8_decode('ESPECIALIDAD:'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(136, 4, utf8_decode(self::$citapaciente->salud_ocupacional.' '. 'Ocupacional'), 1, 0, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 4, utf8_decode('CIUDAD:'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(136, 4, utf8_decode('Medellín'), 1, 0, 'C');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 4, utf8_decode('FECHA DE CONSULTA:'), 1, 0, 'C');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(136, 4, utf8_decode(self::$citapaciente->Datetimeingreso), 1, 0, 'C');


        $pdf->SetXY(12,79);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('MOTIVO DE CONSULTA'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(186, 4, utf8_decode(self::$citapaciente->Motivoconsulta), 1, 0, 'L',0);

        $pdf->Ln();
        $pdf->Ln(1);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('ENFERMEDAD ACTUAL'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$citapaciente->Enfermedadactual), 1, 'C');

        $pdf->Ln(1);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('ANTECEDENTES PERSONALES'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);


        $pdf->Ln();
        foreach (self::$antecedente_paciente as $ante) {

            $cie10p = Cie10::find($ante['cie10_id']);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(186, 4, utf8_decode($cie10p["Codigo_CIE10"]."-".$cie10p["Descripcion"]." (". strtolower($ante["Descripcion"]).")"), 1, 'l');
        }

        $pdf->Ln(1);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('ANTECEDENTES FAMILIARES'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        foreach (self::$antecedente_familiar as $familiantecedente) {
            $cie10p = Cie10::find($familiantecedente['cie10_id']);
            $pdf->SetX(12);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(186, 4, utf8_decode($cie10p["Codigo_CIE10"]."-".$cie10p["Descripcion"]." (". strtolower($familiantecedente["Descripcion"]).")"), 1, 'l');
        }

        $pdf->Ln(1);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('RESPIRACION'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $y= $pdf->GetY();

        $pdf->ln();
        $pdf->SetX(12);
        $pdf->Cell(40,4,utf8_decode('MODO'),0,'L');
        $pdf->Cell(40,4,utf8_decode('TIPO'),0,'L');
        $pdf->Cell(70,4,utf8_decode('COORD.FONORESPIRATORIA'),0,'L');
        $pdf->Cell(30,4,utf8_decode('PRUEBA GLATZER'),0,'L');

        $pdf->ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->respiracion_modo), 0, 'L');
        $pdf->SetXY(52,$y+8);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->respiracion_tipo), 0, 'L');
        $pdf->SetXY(107,$y+8);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->respiracion_fonorespiratoria), 0, 'L');
        $pdf->SetXY(173,$y+8);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->respiracion_prueba_glatzer), 0, 'L');

        $y1= $pdf->GetY();

        $pdf->Line(12, $y, 12, $y1+4); #LINA VERTICAL IZQUIERDA
        $pdf->Line(198, $y, 198, $y1+4); #LINEA VERTICAL DERECHA
        $pdf->Line(12, $y1+4, 198, $y1+4);

        $pdf->Ln();
        $pdf->Ln(1);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('PERIMETROS'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $y= $pdf->GetY();

        $pdf->ln();
        $pdf->SetX(33);
        $pdf->Cell(62,4,utf8_decode('BIAXILAR'),0,'L');
        $pdf->Cell(65,4,utf8_decode('XIFOIDEO'),0,'L');
        $pdf->Cell(70,4,utf8_decode('ABDOMINAL'),0,'L');

        $pdf->ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->perimetros_biaxilar), 0, 'C');
        $pdf->SetXY(75,$y+8);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->perimetros_xifoideo), 0, 'C');
        $pdf->SetXY(137,$y+8);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->perimetros_abdominal), 0, 'C');

        $y2= $pdf->GetY();

        $pdf->ln();
        $pdf->SetX(33);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(62,4,utf8_decode('INSPIRACIÓN'),0,'L');
        $pdf->Cell(65,4,utf8_decode('ESPIRACIÓN'),0,'L');

        $pdf->ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->tiempos_respiracion_inspiracion), 0, 'C');
        $pdf->SetXY(75,$y2+8);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->tiempos_respiracion_espiracion), 0, 'C');

        $y1= $pdf->GetY();

        $pdf->Line(12, $y, 12, $y1+2); #LINA VERTICAL IZQUIERDA
        $pdf->Line(198, $y, 198, $y1+2); #LINEA VERTICAL DERECHA
        $pdf->Line(12, $y1+2, 198, $y1+2);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('CUALIDADES DE LA VOZ'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $y= $pdf->GetY();

        $pdf->ln();
        $pdf->SetX(12);
        $pdf->Cell(40,4,utf8_decode('TIMBRE'),0,'L');
        $pdf->Cell(55,4,utf8_decode('INTENSIDAD'),0,'L');
        $pdf->Cell(60,4,utf8_decode('TONO'),0,'L');
        $pdf->Cell(30,4,utf8_decode('CIERRE GLOTICO'),0,'L');

        $pdf->ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->timbre), 0, 'L');
        $pdf->SetXY(52,$y+8);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->intensidad), 0, 'L');
        $pdf->SetXY(107,$y+8);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->tono), 0, 'L');
        $pdf->SetXY(173,$y+8);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->cierre_glotico), 0, 'L');

        $y1= $pdf->GetY();

        $pdf->Line(12, $y, 12, $y1+2); #LINA VERTICAL IZQUIERDA
        $pdf->Line(198, $y, 198, $y1+2); #LINEA VERTICAL DERECHA
        $pdf->Line(12, $y1+2, 198, $y1+2);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('MANEJO DE RESONADORES'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $y= $pdf->GetY();

        $pdf->ln();
        $pdf->SetX(33);
        $pdf->Cell(62,4,utf8_decode('CABEZA'),0,'L');
        $pdf->Cell(65,4,utf8_decode('FRENTE'),0,'L');
        $pdf->Cell(70,4,utf8_decode('NASALES'),0,'L');

        $pdf->ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->lugar_cabeza), 0, 'C');
        $pdf->SetXY(72,$y+8);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->lugar_frente), 0, 'C');
        $pdf->SetXY(137,$y+8);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->lugar_nasales), 0, 'C');

        $pdf->ln();
        $pdf->SetX(33);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(62,4,utf8_decode('MEJILLAS'),0,'L');
        $pdf->Cell(65,4,utf8_decode('CUELLO'),0,'L');

        $pdf->ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->lugar_mejillas), 0, 'C');
        $pdf->SetXY(72,$y+20);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->lugar_cuello), 0, 'C');

        $pdf->ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(62,4,utf8_decode('OBSERVACIÓN'),0,'L');

        $pdf->ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(186, 4, utf8_decode(self::$datos->voz_observaciones), 0, 'L');

        $y1= $pdf->GetY();

        $pdf->Line(12, $y, 12, $y1+2); #LINA VERTICAL IZQUIERDA
        $pdf->Line(198, $y, 198, $y1+2); #LINEA VERTICAL DERECHA
        $pdf->Line(12, $y1+2, 198, $y1+2);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('MUSCULATURA LARINGEA'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $y= $pdf->GetY();

        $pdf->ln();
        $pdf->SetX(12);
        $pdf->Cell(40,4,utf8_decode('NORMAL'),0,'L');
        $pdf->Cell(55,4,utf8_decode('IRRITADA'),0,'L');
        $pdf->Cell(60,4,utf8_decode('INFLAMADA'),0,'L');
        $pdf->Cell(30,4,utf8_decode('PLACAS'),0,'L');

        $pdf->ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->musculatura_laringea_normal), 0, 'L');
        $pdf->SetXY(52,$y+8);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->musculatura_laringea_irritada), 0, 'L');
        $pdf->SetXY(107,$y+8);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->musculatura_laringea_inflamada), 0, 'L');
        $pdf->SetXY(168,$y+8);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->musculatura_laringea_placas), 0, 'L');

        $y1= $pdf->GetY();

        $pdf->Line(12, $y, 12, $y1+2); #LINA VERTICAL IZQUIERDA
        $pdf->Line(198, $y, 198, $y1+2); #LINEA VERTICAL DERECHA
        $pdf->Line(12, $y1+2, 198, $y1+2);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('MUSCULATURA EXTRALARINGEA'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $y= $pdf->GetY();

        $pdf->ln();
        $pdf->SetX(12);
        $pdf->Cell(40,4,utf8_decode('DOLOR AL PALPAR'),0,'L');
        $pdf->Cell(55,4,utf8_decode('DOLOR AL DEGLUTIR'),0,'L');
        $pdf->Cell(60,4,utf8_decode('TONO NOTMAL'),0,'L');
        $pdf->Cell(30,4,utf8_decode('TONO AUMENTADO'),0,'L');

        $pdf->ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->musculatura_extralaringea_dolor_palpar), 0, 'L');
        $pdf->SetXY(52,$y+8);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->musculatura_extralaringea_dolor_deglutir), 0, 'L');
        $pdf->SetXY(107,$y+8);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->musculatura_extralaringea_tono_normal), 0, 'L');
        $pdf->SetXY(168,$y+8);
        $pdf->MultiCell(60, 4, utf8_decode(self::$datos->musculatura_extralaringea_tono_aumentado), 0, 'L');

        $y1= $pdf->GetY();

        $pdf->Line(12, $y, 12, $y1+2); #LINA VERTICAL IZQUIERDA
        $pdf->Line(198, $y, 198, $y1+2); #LINEA VERTICAL DERECHA
        $pdf->Line(12, $y1+2, 198, $y1+2);

        #DX PRINCIPAL
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('DIAGNÓSTICO PRINCIPAL'), 1, 0, 'C', 1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $dxprincipal = Cie10paciente::select(['c.Codigo_CIE10 as cie10', 'c.Descripcion'])
            ->join('cie10s as c', 'c.id', 'cie10pacientes.Cie10_id')
            ->where('cie10pacientes.Citapaciente_id',self::$citapaciente->id)
            ->where('cie10pacientes.Esprimario', 1)
            ->first();
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 4, utf8_decode('CÓDIGO CIE10'), 1, 0, 'C');
        $pdf->Cell(96, 4, utf8_decode('DESCRIPCION DEL DIAGNÓSTICO'), 1, 0, 'C');
        $pdf->Cell(60, 4, utf8_decode('TIPO DEL DIAGNÓSTICO'), 1, 0, 'C');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode($dxprincipal->cie10), 1, 'l');
        $pdf->Cell(96, 4, utf8_decode($dxprincipal->Descripcion), 1, 'l');
        $pdf->Cell(60, 4, utf8_decode('Principal'), 1, 'l');

        #DX SEGUNDARIO
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('DIAGNÓSTICOS SEGUNDARIOS'), 1, 0, 'C', 1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 4, utf8_decode('CÓDIGO CIE10'), 1, 0, 'C');
        $pdf->Cell(96, 4, utf8_decode('DESCRIPCION DEL DIAGNÓSTICO'), 1, 0, 'C');
        $pdf->Cell(60, 4, utf8_decode('TIPO DEL DIAGNÓSTICO'), 1, 0, 'C');

        $pdf->Ln();

        $pdf->SetFont('Arial', '', 8);

        $dxsegundarios = Cie10paciente::select(['c.Codigo_CIE10 as cie10', 'c.Descripcion'])
        ->join('cie10s as c', 'c.id', 'cie10pacientes.Cie10_id')
        ->where('cie10pacientes.Citapaciente_id',self::$citapaciente->id)
        ->where('cie10pacientes.Esprimario', 0)
        ->get();
        foreach ($dxsegundarios as $dxsegu) {
            $pdf->SetX(12);
            $pdf->Cell(30, 4, utf8_decode($dxsegu['cie10']), 1, 'l');
            $pdf->Cell(96, 4, utf8_decode($dxsegu['Descripcion']), 1, 'l');
            $pdf->Cell(60, 4, utf8_decode('Segundario'), 1, 'l');
            $pdf->Ln();
        }


        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('CONCEPTO DE APTITUD LABORAL'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$datos->aptitud_laboral_voz), 1, 'l');

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('CONDUCTA'), 1, 0, 'C', 1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('PLAN DE MANEJO'), 1, 0, 'L',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $conducta = Conducta::where('citapaciente_id',self::$citapaciente->id)->first();
        $pdf->MultiCell(186, 4, utf8_decode($conducta->Planmanejo), 1, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 215);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('RECOMENDACIONES Y/O RESTRICCIONES LABORALES:'), 1, 0, 'l',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode($conducta->Recomendaciones), 1, 'l');
        $pdf->Ln();

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(45, 4, utf8_decode('DESTINO DEL PACIENTE:'), 0, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(130, 4, utf8_decode($conducta->Destinopaciente), 0, 'l');
        $pdf->Ln();

        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(45, 4, utf8_decode('FINALIDAD:'), 0, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(130, 4, utf8_decode($conducta->Finalidad), 0, 'l');

        $pdf->Ln();
        #FIRMAS MEDICO
        if (file_exists(storage_path(substr($medico->Firma, 9)))) {
            $pdf->Image(storage_path(substr($medico->Firma, 9)), 30, 250, 56, 11);
        }
        $pdf->Line(20, 261, 90, 261);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(23, 261);
        $pdf->Cell(60, 4, utf8_decode('Atendido por:'. ' '. $medico->name. ' '. $medico->Apellido), 0, 0, 'l'); #NOMBRE COMPLETO DEL MEDICO
        $pdf->SetXY(23, 266);
        $pdf->Cell(60, 4, utf8_decode('Especialidad:'. ' '. $medico->especialidad_medico), 0, 0, 'l'); #ESPECIALIDAD DEL MEDICO
        $pdf->SetXY(23, 271);
        $pdf->Cell(32, 4, utf8_decode('REGISTRO Y LIC S.O:'), 0, 'l'); #REGISTRO MEDICO
        $pdf->MultiCell(30, 4, utf8_decode($medico->Registromedico), 0, 'l');


        #FIRMAS

        // $pdf->SetX(120);
        // $pdf->MultiCell(55, 4, utf8_decode(''), 0, 'l');
        if(self::$citapaciente->firma_trabajador){
            if (file_exists(public_path().'/storage/saludOcupacional/firma/'.self::$citapaciente->firma_trabajador)) {
                $pdf->Image(public_path().'/storage/saludOcupacional/firma/'.self::$citapaciente->firma_trabajador, 130, 258, 56, 11);
            }
        }
        $pdf->Line(120, 270, 195, 270);
        $pdf->SetXY(120, 272);
        $pdf->Cell(85, 4, utf8_decode('Firma Empleado'), 0, 'l');
        $pdf->SetXY(170, 272);
    }
}
