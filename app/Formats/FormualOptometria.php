<?php

namespace App\Formats;

use App\User;
use App\Modelos\Orden;
use App\Modelos\Paciente;
use App\Modelos\Municipio;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Modelos\citapaciente;
use Illuminate\Support\Carbon;
use App\Modelos\FormulaOptometria;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Self_;

class FormualOptometria extends Fpdf
{
    public static $data;
    public static $paciente;
    public static $optometria;
    public static $orden;
    public static $citaPaciente;
    public static $prestador;

    public function generar($id)
    {
        self::$orden = Orden::find($id);
        self::$citaPaciente = citapaciente::find(self::$orden->Cita_id);
        self::$paciente = Paciente::find(self::$citaPaciente->Paciente_id);
        self::$optometria = FormulaOptometria::where('Orden_id',$id)->first();
        $pdf = new FormualOptometria('p', 'mm', 'A4');
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
        $this->SetXY(50, 8);
        $this->Cell(80, 4, utf8_decode('SUMIMEDICAL S.A.S'), 0, 0, 'C');
        $this->SetXY(50, $Y);
        $this->Cell(80, 4, utf8_decode('NIT: 900033371 Res: 004 '), 0, 0, 'C');
        $this->SetXY(50, $Y + 4);
        $this->Cell(80, 4, utf8_decode('Apoyo Terapéutico Cll 45E # 73 -40 Medellín'), 0, 0, 'C');
        $this->SetXY(50, $Y + 8);
        $this->Cell(80, 4, utf8_decode('Tel: Solo WhatsApp 3053150275 - Email: optica@sumimedical.com'), 0, 0, 'C');
        $this->SetXY(50, $Y + 12);

        $Y = 8;
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(115, $Y);
        $this->Cell(88, 4, utf8_decode('FORMULA OPTOMETRIA'), 0, 0, 'C');
        $this->SetXY(141, $Y + 4);
        $this->Cell(12, 4, utf8_decode("N°: "), 0, 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(10, 4, utf8_decode(self::$orden->id), 0, 0, 'L');
        $this->SetXY(141, $Y + 8);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(12, 4, utf8_decode('Fecha: '), 0, 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(25, 4, self::$orden->Fechaorden, 0, 0, 'L');
        $this->SetXY(141, $Y + 12);
        $this->SetFont('Arial', 'B', 8);
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
        $this->Cell(10, 4, 'Edad', 1, 0, 'C', 1);
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', '', 8);
        $this->Cell(108, 4, utf8_decode(self::$paciente->Primer_Ape." ".self::$paciente->Segundo_Ape." ".self::$paciente->Primer_Nom." ".self::$paciente->SegundoNom), 1, 0, 'C');
        $this->Cell(25, 4, utf8_decode('CC '.self::$paciente->Num_Doc), 1, 0, 'C');
        $this->Cell(25, 4, utf8_decode(self::$paciente->Celular1), 1, 0, 'C');
        $this->Cell(30, 4, utf8_decode(self::$paciente->Ut), 1, 0, 'C');
        $this->Cell(10, 4, utf8_decode(self::$paciente->Edad_Cumplida), 1, 0, 'C');
        $this->Ln();
        $this->Ln();
        $this->SetX(28);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(22, 4, utf8_decode('Esfera'), 1, 0, 'C', 1);
        $this->Cell(22, 4, utf8_decode('Cilindro'), 1, 0, 'C', 1);
        $this->Cell(22, 4, utf8_decode('Eje'), 1, 0, 'C', 1);
        $this->Cell(22, 4, utf8_decode('Adicion'), 1, 0, 'C', 1);
        $this->Cell(22, 4, utf8_decode('Prisma Base'), 1, 0, 'C', 1);
        $this->Cell(22, 4, utf8_decode('Grados'), 1, 0, 'C', 1);
        $this->Cell(22, 4, utf8_decode('AV Lejos'), 1, 0, 'C', 1);
        $this->Cell(21, 4, utf8_decode('AV Cerca'), 1, 0, 'C', 1);
        $this->Ln();
        $this->SetX(5);
        $this->Cell(23, 4, utf8_decode('Ojo Derecho'), 1, 0, 'C', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(22, 4, utf8_decode(self::$optometria->esfera_od), 1, 0, 'C');
        $this->Cell(22, 4, utf8_decode(self::$optometria->cilindro_od), 1, 0, 'C');
        $this->Cell(22, 4, utf8_decode(self::$optometria->eje_od), 1, 0, 'C');
        $this->Cell(22, 4, utf8_decode(self::$optometria->adicion_od), 1, 0, 'C');
        $this->Cell(22, 4, utf8_decode(self::$optometria->prisma_od), 1, 0, 'C');
        $this->Cell(22, 4, utf8_decode(self::$optometria->grados_od), 1, 0, 'C');
        $this->Cell(22, 4, utf8_decode(self::$optometria->lejos_od), 1, 0, 'C');
        $this->Cell(21, 4, utf8_decode(self::$optometria->cerca_od), 1, 0, 'C');
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(23, 4, utf8_decode('Ojo Izquierdo'), 1, 0, 'C', 1);
        $this->SetFont('Arial', '', 8);
        $this->Cell(22, 4, utf8_decode(self::$optometria->esfera_oi), 1, 0, 'C');
        $this->Cell(22, 4, utf8_decode(self::$optometria->cilindro_oi), 1, 0, 'C');
        $this->Cell(22, 4, utf8_decode(self::$optometria->eje_oi), 1, 0, 'C');
        $this->Cell(22, 4, utf8_decode(self::$optometria->adicion_oi), 1, 0, 'C');
        $this->Cell(22, 4, utf8_decode(self::$optometria->prisma_oi), 1, 0, 'C');
        $this->Cell(22, 4, utf8_decode(self::$optometria->grados_oi), 1, 0, 'C');
        $this->Cell(22, 4, utf8_decode(self::$optometria->lejos_oi), 1, 0, 'C');
        $this->Cell(21, 4, utf8_decode(self::$optometria->cerca_oi), 1, 0, 'C');
        $this->Ln();
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(28, 4, utf8_decode('Tipo Lentes:'), 1, 0, 'L', 1);
        $this->Cell(170, 4, utf8_decode(self::$optometria->tipo_lente), 1, 0, 'L');
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(28, 4, utf8_decode('Detalle:'), 1, 0, 'L', 1);
        $this->Cell(170, 4, utf8_decode(self::$optometria->detalle), 1, 0, 'L');
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(28, 4, utf8_decode('Altura:'), 1, 0, 'L', 1);
        $this->Cell(170, 4, utf8_decode(self::$optometria->altura), 1, 0, 'L');
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(28, 4, utf8_decode('Color y Ttos:'), 1, 0, 'L', 1);
        $this->Cell(170, 4, utf8_decode(self::$optometria->color ), 1, 0, 'L');
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(28, 4, utf8_decode('Dp:'), 1, 0, 'L', 1);
        $this->Cell(170, 4, utf8_decode(self::$optometria->dp), 1, 0, 'L');
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(28, 4, utf8_decode('Uso:'), 1, 0, 'L', 1);
        $this->Cell(170, 4, utf8_decode(self::$optometria->dispositivos), 1, 0, 'L');
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(28, 4, utf8_decode('Control:'), 1, 0, 'L', 1);
        $this->Cell(170, 4, utf8_decode(self::$optometria->control), 1, 0, 'L');
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(28, 4, utf8_decode('Duracion Vigencia:'), 1, 0, 'L', 1);
        $this->Cell(170, 4, utf8_decode(self::$optometria->vigencia), 1, 0, 'L');
        $this->Ln();
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(198, 4, utf8_decode('Observaciones'), 1, 0, 'C', 1);
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', '', 7);
        $this->MultiCell(198,4, utf8_decode(self::$optometria->observacion),1,'L');

        $this->Ln();
        $final = $this->GetY();
        $this->SetX(12);
        $this->SetFont('Arial', 'B', 8);
        $medico = User::find(self::$orden->Usuario_id);
        #FIRMAS MEDICO
        if(isset($medico)){
            if (file_exists(storage_path(substr($medico->Firma, 9)))) {
                $this->Image(storage_path(substr($medico->Firma, 9)), 30, 250, 56, 11);
            }
        }
        if($final > 250){
            $this->AddPage();
        }
        $y1= $this->GetY();
        $this->Line(20, $y1 +3, 90, $y1 +3);
        $this->SetFont('Arial', '', 8);
        $y2= $this->GetY();
        $this->SetXY(23,$y2+3);
        $this->Cell(60, 4, utf8_decode('Atendido por:'. ' '. $medico->name." ".$medico->apellido), 0, 0, 'l'); #NOMBRE COMPLETO DEL MEDICO
        $y3= $this->GetY();
        $this->SetXY(23,$y3 +4);
        $this->Cell(60, 4, utf8_decode('Especialidad:'. ' '. $medico->especialidad_medico), 0, 0, 'l'); #ESPECIALIDAD DEL MEDICO
        $y4= $this->GetY();
        $this->SetXY(23, $y4 +4);
        $this->Cell(32, 4, utf8_decode('REGISTRO:'), 0, 'l'); #REGISTRO MEDICO
        $this->MultiCell(30, 4, utf8_decode($medico->cedula), 0, 'l');


        $this->SetFont('Arial','I',8);
        $this->TextWithDirection(206,120,'Funcionario que Imprime:','U');
        $this->TextWithDirection(206, 87, utf8_decode(auth()->user()->name . " " . auth()->user()->apellido), 'U');

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
