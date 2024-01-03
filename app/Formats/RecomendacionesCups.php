<?php
namespace App\Formats;

use App\User;
use App\Modelos\Paciente;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Modelos\citapaciente;
use App\Modelos\RecomendacionCup;


class RecomendacionesCups extends FPDF
{
    public static $paciente;
    public static $recomendacion;
    public static $orden;
    public static $citaPaciente;
    public static $medico;
    public static $TEMPIMGLOC;

    public function generar($data)
    {
        self::$recomendacion = RecomendacionCup::where('cup_id',$data['id'])->first();
        self::$citaPaciente = citapaciente::find($data['cita_paciente']);
        self::$paciente = Paciente::find(self::$citaPaciente->Paciente_id);
        $pdf = new RecomendacionesCups('p', 'mm', 'A4');
        $pdf->AddPage();
        $this->bodyrecomendaciones($pdf);
        $pdf->SetFont('Arial','B',16);
        $pdf->Output();
    }

    public function bodyrecomendaciones($pdf)
    {

        $pdf->SetFont('Arial','B',50);
        $pdf->SetTextColor(255,192,203);
        $pdf->SetTextColor(0,0,0);

        $Y = 14;
        $pdf->SetFont('Arial', 'B', 9);
        $logo = public_path() . "/images/logo.png";
        $pdf->Image($logo, 8, 7, -400);
        $pdf->SetXY(50, 10);
        $pdf->Cell(80, 4, utf8_decode('SUMIMEDICAL S.A.S'), 0, 0, 'C');
        $pdf->SetXY(50, $Y);
        $pdf->Cell(80, 4, utf8_decode('NIT: 900033371 Res: 004 '), 0, 0, 'C');
        $pdf->SetXY(50, $Y + 4);
        $pdf->Cell(80, 4, utf8_decode('Email Medico: '), 0, 0, 'C');
        $pdf->SetXY(50, $Y + 12);

        $Y = 8;
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(120, $Y);
        $pdf->Cell(88, 4, utf8_decode('Recomendaciones'), 0, 0, 'C');
        $pdf->SetXY(151, $Y + 4);
        $pdf->Cell(12, 4, utf8_decode("N°: "), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(10, 4, utf8_decode(self::$citaPaciente->id), 0, 0, 'L');
        $pdf->SetXY(151, $Y + 8);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(12, 4, utf8_decode('Fecha: '), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(25, 4, self::$citaPaciente->Datetimeingreso, 0, 0, 'L');
        $pdf->SetXY(151, $Y + 12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(12, 4, utf8_decode('Entidad: '), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(35, 4, self::$paciente->Ut, 0, 0, 'L');

        $pdf->SetFillColor(216, 216, 216);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetXY(5, 28);
        $pdf->Cell(198, 8, utf8_decode('Datos del Paciente'), 0, 0, 'L');
        $pdf->SetLineWidth(0.8);
        $pdf->Line(5,33.8,203,33.8);
        $pdf->SetLineWidth(0.2);

        $pdf->Ln();
        $pdf->SetX(5);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(108, 4, utf8_decode('Nombre Paciente'), 1, 0, 'C', 1);
        $pdf->Cell(25, 4, utf8_decode('Identificación'), 1, 0, 'C', 1);
        $pdf->Cell(25, 4, utf8_decode('Telefono'), 1, 0, 'C', 1);
        $pdf->Cell(30, 4, utf8_decode('Regimen'), 1, 0, 'C', 1);
        $pdf->Cell(10, 4, 'Edad', 1, 0, 'C', 1);
        $pdf->Ln();
        $pdf->SetX(5);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(108, 4, utf8_decode(self::$paciente->Primer_Ape." ".self::$paciente->Segundo_Ape." ".self::$paciente->Primer_Nom." ".self::$paciente->SegundoNom), 1, 0, 'C');
        $pdf->Cell(25, 4, utf8_decode('CC '.self::$paciente->Num_Doc), 1, 0, 'C');
        $pdf->Cell(25, 4, utf8_decode(self::$paciente->Celular1), 1, 0, 'C');
        $pdf->Cell(30, 4, utf8_decode(self::$paciente->Ut), 1, 0, 'C');
        $pdf->Cell(10, 4, utf8_decode(self::$paciente->Edad_Cumplida), 1, 0, 'C');
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetX(5);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(198, 4, utf8_decode('Recomendaciones'), 1, 0, 'C', 1);
        $pdf->Ln();
        $pdf->SetX(5);
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(198,4, utf8_decode(self::$recomendacion->recomendacion_cup),1,'L');

        // $pdf->Ln();
        // $final = $pdf->GetY();
        // $pdf->SetX(12);
        // $pdf->SetFont('Arial', 'B', 8);
        #FIRMAS MEDICO
        // if(isset(self::$medico)){
        //     if (file_exists(storage_path(substr(self::$medico->Firma, 9)))) {
        //         $pdf->Image(storage_path(substr(self::$medico->Firma, 9)), 30, 250, 56, 11);
        //     }
        // }
        // if($final > 250){
        //     $pdf->AddPage();
        // }
        // $y1= $pdf->GetY();
        // $pdf->Line(20, $y1 +3, 90, $y1 +3);
        // $pdf->SetFont('Arial', '', 8);
        // $y2= $pdf->GetY();
        // $pdf->SetXY(23,$y2+3);
        // $pdf->Cell(60, 4, utf8_decode('Atendido por:'. ' '. self::$medico->name." ".self::$medico->apellido), 0, 0, 'l'); #NOMBRE COMPLETO DEL MEDICO
        // $y3= $pdf->GetY();
        // $pdf->SetXY(23,$y3 +4);
        // $pdf->Cell(60, 4, utf8_decode('Especialidad:'. ' '. self::$medico->especialidad_medico), 0, 0, 'l'); #ESPECIALIDAD DEL MEDICO
        // $y4= $pdf->GetY();
        // $pdf->SetXY(23, $y4 +4);
        // $pdf->Cell(32, 4, utf8_decode('REGISTRO:'), 0, 'l'); #REGISTRO MEDICO
        // $pdf->MultiCell(30, 4, utf8_decode(self::$medico->cedula), 0, 'l');


        $pdf->SetFont('Arial','I',8);
        $pdf->TextWithDirection(206,120,'Funcionario que Imprime:','U');
        $pdf->TextWithDirection(206, 87, utf8_decode(auth()->user()->name . " " . auth()->user()->apellido), 'U');

        $pdf->TextWithDirection(209,120,utf8_decode('Fecha Impresión:'),'U');
        $pdf->TextWithDirection(209, 98, date('Y-m-d h:i:s a'), 'U');

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
