<?php

namespace App\Formats;

use App\Modelos\Bodega;
use App\Modelos\Cie10;
use App\Modelos\Cie10paciente;
use App\Modelos\citapaciente;
use App\Modelos\Incapacidade;
use App\Modelos\Municipio;
use App\Modelos\Orden;
use App\Modelos\Paciente;
use App\Modelos\Sedeproveedore;
use App\User;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Self_;

class IncapacidadesGestion extends Fpdf
{
    public static $data;
    public static $paciente;
    public static $incapacidad;
    public static $orden;
    public static $citaPaciente;
    public static $prestador;

    public function generar($id)
    {
        self::$orden = Orden::find($id);
        self::$citaPaciente = citapaciente::find(self::$orden->Cita_id);
        self::$paciente = Paciente::find(self::$citaPaciente->Paciente_id);
        self::$incapacidad = Incapacidade::where('Orden_id',$id)->first();
        self::$prestador = Sedeproveedore::find(intval(self::$paciente->IPS));
        $pdf = new IncapacidadesGestion('p', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Output();
    }

    public function Header()
    {
        $this->SetFont('Arial','B',50);
        $this->SetTextColor(255,192,203);
        $this->SetTextColor(0,0,0);

        $Y = 12;
        $this->SetFont('Arial', 'B', 9);
        $logo = public_path() . "/images/logo.png";
        $this->Image($logo, 8, 7, -400);
//        $this->Rect(5, 5, 30, 22);
//        $this->Rect(35, 5, 80, 22);
        $this->SetXY(35, 8);
        $this->Cell(80, 4, utf8_decode('SUMIMEDICAL S.A.S'), 0, 0, 'C');
        $this->SetXY(35, $Y);
        $this->Cell(80, 4, utf8_decode('NIT: 900033371 Res: 004 '), 0, 0, 'C');
        $this->SetXY(35, $Y + 4);
        $this->Cell(80, 4, utf8_decode('Carrera 80 c Nùmero 32EE-65'), 0, 0, 'C');
        $this->SetXY(35, $Y + 8);
        $this->Cell(80, 4, utf8_decode('Telefono: 5201040'), 0, 0, 'C');
        $this->SetXY(35, $Y + 12);

        $Y = 8;
        $this->SetFont('Arial', 'B', 8);
//        $this->Rect(115, 5, 88, 22);
        $this->SetXY(115, $Y);
        $this->Cell(88, 4, utf8_decode('CERTIFICADO DE INCAPACIDAD '), 0, 0, 'C');
        $this->SetXY(115, $Y + 4);
        $this->Cell(6, 4, utf8_decode("N°: "), 0, 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(10, 4, utf8_decode(self::$orden->id), 0, 0, 'L');
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(12, 4, utf8_decode('Fecha: '), 0, 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(25, 4, self::$incapacidad->created_at, 0, 0, 'L');
        $this->Cell(12, 4, utf8_decode('Entidad: '), 0, 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(35, 4, self::$paciente->Ut, 0, 0, 'L');

        $this->SetFillColor(216, 216, 216);
        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(5, 28);
        $this->Cell(198, 8, utf8_decode('Datos del Paciente'), 0, 0, 'L');
        $this->SetLineWidth(0.8);
        $this->Line(5,33.8,203,33.8);
        $this->SetLineWidth(0.2);

        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(108, 4, utf8_decode('Nombre Paciente'), 1, 0, 'C', 1);
        $this->Cell(25, 4, utf8_decode('Identificación'), 1, 0, 'C', 1);
        $this->Cell(25, 4, utf8_decode('Telefono'), 1, 0, 'C', 1);
        $this->Cell(30, 4, utf8_decode('Regimen'), 1, 0, 'C', 1);
        $this->Cell(10, 4, 'Nivel', 1, 0, 'C', 1);
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', '', 8);
        $this->Cell(108, 4, utf8_decode(self::$paciente->Primer_Ape." ".self::$paciente->Segundo_Ape." ".self::$paciente->Primer_Nom." ".self::$paciente->SegundoNom), 1, 0, 'C');
        $this->Cell(25, 4, utf8_decode('CC '.self::$paciente->Num_Doc), 1, 0, 'C');
        $this->Cell(25, 4, utf8_decode(self::$paciente->Celular1), 1, 0, 'C');
        if(self::$paciente->entidad_id == 1){
            $this->Cell(30, 4, utf8_decode("Especial"), 1, 0, 'C');
            $this->Cell(10, 4,"", 1, 0, 'C');
        }else{
            $this->Cell(30, 4, utf8_decode(self::$paciente->tipo_categoria), 1, 0, 'C');
            $this->Cell(10, 4, self::$paciente->nivel, 1, 0, 'C');
        }
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(198, 4, 'Labora en', 1, 0, 'C', 1);
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', '', 7);
        $this->Cell(198, 4, utf8_decode(self::$incapacidad->Laboraen), 1, 0, 'C');
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(198, 4, 'IPS Primaria', 1, 0, 'C', 1);
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', '', 7);
        $this->Cell(198, 4, utf8_decode(self::$prestador->Nombre), 1, 0, 'C');
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(68, 4, 'Tipo Incapacidad', 1, 0, 'C', 1);
        $this->Cell(30, 4, utf8_decode('Prorroga'), 1, 0, 'C', 1);
        $this->Cell(30, 4, 'Fecha Inicial', 1, 0, 'C', 1);
        $this->Cell(30, 4, 'Fecha Final', 1, 0, 'C', 1);
        $this->Cell(40, 4, 'Total Dias Incapacidad', 1, 0, 'C', 1);
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', '', 7);
        $this->Cell(68, 4,  utf8_decode(''), 1, 0, 'C');
        if(self::$incapacidad->Prorroga != 'No'){
            $ultimaIncapacida = Incapacidade::where('id','<',self::$incapacidad->id)->orderBy('id', 'desc')->first();
            $this->Cell(30, 4, utf8_decode('SI (' . $ultimaIncapacida->id). ')', 1, 0, 'C');
        }else{
            $this->Cell(30, 4, utf8_decode('NO'), 1, 0, 'C');
        }
        $this->Cell(30, 4,  utf8_decode(self::$incapacidad->Fechainicio), 1, 0, 'C');
        $this->Cell(30, 4,  utf8_decode(self::$incapacidad->Fechafinal), 1, 0, 'C');
        $this->Cell(40, 4,  utf8_decode(self::$incapacidad->Dias), 1, 0, 'C');

        $this->SetXY(5, 67);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(198, 8, utf8_decode('Detalle Incapacidad'), 0, 0, 'L');
        $this->SetLineWidth(0.8);
        $this->Line(5,73,203,73);
        $this->SetLineWidth(0.2);

        $this->SetXY(5, 75);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(158, 4, utf8_decode('Concepto Incapacidad'), 1, 0, 'C', 1);
        $this->Cell(40, 4, utf8_decode('Diagnóstico'), 1, 0, 'C', 1);
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', '', 7);
        $this->Cell(158, 4,  utf8_decode(self::$incapacidad->Contingencia), 1, 0, 'C');
        $cie10 = Cie10::find(self::$incapacidad->Cie10_id);
        $this->Cell(40, 4,  utf8_decode($cie10->Codigo_CIE10), 1, 0, 'C');
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(198, 4, utf8_decode('Observaciones'), 1, 0, 'C', 1);
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', '', 7);
        $this->MultiCell(198,4, utf8_decode(self::$incapacidad->Descripcion),1,'L');
    }
    public function Footer()
    {
        if(self::$citaPaciente->Tipocita_id == 1){

            $transcriptor = User::find(self::$citaPaciente->Usuario_id);
            if (isset($transcriptor)) {
                if($transcriptor->Firma){
                    if (file_exists(storage_path(substr($transcriptor->Firma, 9)))) {
                        $this->Image(storage_path(substr($transcriptor->Firma, 9)), 5,95,66,18);
                    }
                }
            }
            $this->Rect(5,95,66,18);
            $this->SetXY(5,116);
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(18, 6, utf8_decode('Transcriptor: '), 0, 0, 'L');
            $this->SetFont('Arial', '', 7);
            $this->Cell(50, 6, utf8_decode($transcriptor->name." ".$transcriptor->apellido), 0, 0, 'L');
            $this->Ln();
            $this->SetX(5);
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(25, 6, utf8_decode('Profesional: '), 0, 0, 'L');
            $this->SetFont('Arial', '', 7);
            $this->Cell(25, 6, utf8_decode(self::$citaPaciente->Medicoordeno), 0, 0, 'L');
            $this->Ln();
            $this->SetX(5);
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(25, 6, utf8_decode('Registro Medico: '), 0, 0, 'L');
            $this->SetFont('Arial', '', 7);
            $this->Cell(25, 6, utf8_decode($transcriptor->Registromedico), 0, 0, 'L');
        }else{
            $medico = User::find(self::$orden->Usuario_id);
            if (isset($medico)) {
                if($medico->Firma){
                    if (file_exists(storage_path(substr($medico->Firma, 9)))) {
                        $this->Image(storage_path(substr($medico->Firma, 9)), 5,95,66,18);
                    }
                }
                $this->Rect(5,95,66,18);
        $this->SetXY(5,116);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(18, 6, utf8_decode('Profesional: '), 0, 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(50, 6, utf8_decode($medico->name." ".$medico->apellido), 0, 0, 'L');
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 6, utf8_decode('Registro Médico: '), 0, 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(25, 6, utf8_decode($medico->cedula), 0, 0, 'L');
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 6, utf8_decode('Especialidad: '), 0, 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(25, 6, utf8_decode($medico->especialidad_medico), 0, 0, 'L');
        }
        }

        $this->SetFont('Arial','I',8);
        $this->TextWithDirection(206,120,'Funcionario que Imprime:','U');
        $this->TextWithDirection(206, 87, utf8_decode('PACIENTE'), 'U');

        $this->TextWithDirection(209,120,utf8_decode('Fecha Impresión:'),'U');
        $this->TextWithDirection(209, 98, date('Y-m-d h:i:s a'), 'U');

    }
    public function TextWithDirection($x, $y, $txt, $direction = 'R')
    {
        if ($direction == 'R') {
            $s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 1, 0, 0, 1, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        } elseif ($direction == 'L') {
            $s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', -1, 0, 0, -1, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        } elseif ($direction == 'U') {
            $s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 0, 1, -1, 0, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        } elseif ($direction == 'D') {
            $s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 0, -1, 1, 0, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        } else {
            $s = sprintf('BT %.2F %.2F Td (%s) Tj ET', $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        }

        if ($this->ColorFlag) {
            $s = 'q ' . $this->TextColor . ' ' . $s . ' Q';
        }

        $this->_out($s);
    }
}
