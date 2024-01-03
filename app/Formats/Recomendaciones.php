<?php
namespace App\Formats;
use DNS1D;
use DNS2D;
use App\User;
use App\Modelos\Orden;
use App\Modelos\Paciente;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Modelos\citapaciente;
use App\Modelos\RecomendacionesClinicas;


class Recomendaciones extends FPDF
{
    public static $paciente;
    public static $recomendacion;
    public static $orden;
    public static $citaPaciente;
    public static $medico;
    public static $TEMPIMGLOC;

    public function generar($id)
    {
        self::$orden = Orden::find($id);
        self::$citaPaciente = citapaciente::find(self::$orden->Cita_id);
        self::$paciente = Paciente::find(self::$citaPaciente->Paciente_id);
        self::$recomendacion = RecomendacionesClinicas::where('Orden_id',$id)->first();
        self::$medico = User::find(self::$orden->Usuario_id);
        $pdf = new Recomendaciones('p', 'mm', 'A4');
        $pdf->AddPage();
        if(self::$recomendacion["fecha_aislamiento"] == null){
            $this->bodyrecomendaciones($pdf);
        }
        else{
            $this->bodyAislamiento($pdf);
        }
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
        $pdf->Cell(80, 4, utf8_decode('Email Medico: '.self::$medico->email), 0, 0, 'C');
        $pdf->SetXY(50, $Y + 12);

        $Y = 8;
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(120, $Y);
        $pdf->Cell(88, 4, utf8_decode('Recomendaciones'), 0, 0, 'C');
        $pdf->SetXY(151, $Y + 4);
        $pdf->Cell(12, 4, utf8_decode("N°: "), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(10, 4, utf8_decode(self::$orden->id), 0, 0, 'L');
        $pdf->SetXY(151, $Y + 8);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(12, 4, utf8_decode('Fecha: '), 0, 0, 'L');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(25, 4, self::$orden->Fechaorden, 0, 0, 'L');
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
        $pdf->MultiCell(198,4, utf8_decode(self::$recomendacion->recomedacion),1,'L');

        $pdf->Ln();
        $final = $pdf->GetY();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        #FIRMAS MEDICO
        if(isset(self::$medico)){
            if (file_exists(storage_path(substr(self::$medico->Firma, 9)))) {
                $pdf->Image(storage_path(substr(self::$medico->Firma, 9)), 30, 250, 56, 11);
            }
        }
        if($final > 250){
            $pdf->AddPage();
        }
        $y1= $pdf->GetY();
        $pdf->Line(20, $y1 +3, 90, $y1 +3);
        $pdf->SetFont('Arial', '', 8);
        $y2= $pdf->GetY();
        $pdf->SetXY(23,$y2+3);
        $pdf->Cell(60, 4, utf8_decode('Atendido por:'. ' '. self::$medico->name." ".self::$medico->apellido), 0, 0, 'l'); #NOMBRE COMPLETO DEL MEDICO
        $y3= $pdf->GetY();
        $pdf->SetXY(23,$y3 +4);
        $pdf->Cell(60, 4, utf8_decode('Especialidad:'. ' '. self::$medico->especialidad_medico), 0, 0, 'l'); #ESPECIALIDAD DEL MEDICO
        $y4= $pdf->GetY();
        $pdf->SetXY(23, $y4 +4);
        $pdf->Cell(32, 4, utf8_decode('REGISTRO:'), 0, 'l'); #REGISTRO MEDICO
        $pdf->MultiCell(30, 4, utf8_decode(self::$medico->cedula), 0, 'l');


        $pdf->SetFont('Arial','I',8);
        $pdf->TextWithDirection(206,120,'Funcionario que Imprime:','U');
        $pdf->TextWithDirection(206, 87, utf8_decode(auth()->user()->name . " " . auth()->user()->apellido), 'U');

        $pdf->TextWithDirection(209,120,utf8_decode('Fecha Impresión:'),'U');
        $pdf->TextWithDirection(209, 98, date('Y-m-d h:i:s a'), 'U');

    }

    public function bodyAislamiento($pdf)
    {
        $logo1 = public_path() . "/images/logo-redvital-1.png";
        $logo2 = public_path() . "/storage/images/piesumi.PNG";
        $TEMPIMGLOC = 'tempimg.png';
        $dataURI    = "data:image/png;base64," . DNS2D::getBarcodePNG(self::$recomendacion->Orden_id, 'QRCODE');
        $dataPieces = explode(',', $dataURI);
        $encodedImg = $dataPieces[1];
        $decodedImg = base64_decode($encodedImg);
        file_put_contents($TEMPIMGLOC, $decodedImg);
        self::$TEMPIMGLOC = $TEMPIMGLOC;
        $pdf->Image(self::$TEMPIMGLOC, 12, 8, 40, 40);
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->SetDrawColor(255, 255, 255);
        $pdf->Rect(5, 5, 200, 287);
        $pdf->SetDrawColor(0, 0, 0);

        $pdf->Image($logo1, 140, 15, 50, 10);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 10);
        $pdf->SetY(28);
        $pdf->Cell(180, 4, utf8_decode('Sede administrativa'), 0, 0, 'R');
        $pdf->Ln();
        $pdf->Cell(180, 4, utf8_decode('Tel: +57(4) 520 10 40'), 0, 0, 'R');
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Cell(180, 4, utf8_decode('Linea Gratuita Nacional'), 0, 0, 'R');
        $pdf->Ln();
        $pdf->Cell(180, 4, utf8_decode('01 8000 423 762'), 0, 0, 'R');
        $pdf->Ln();

        $pdf->SetXY(10, 70);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(192, 4, utf8_decode('REDVITAL UT'), 0, 0, 'C');
        $pdf->Ln();

        $pdf->SetXY(10, 80);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(192, 4, utf8_decode('CERTIFICA'), 0, 0, 'C');
        $pdf->Ln();

        $pdf->SetXY(10, 100);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(186, 4, utf8_decode('A quien pueda interesar, que el usuario (a) '. self::$paciente->Primer_Ape." ".self::$paciente->Segundo_Ape." ".self::$paciente->Primer_Nom." ".self::$paciente->SegundoNom
        .'- '. self::$paciente->Tipo_Doc.' '.self::$paciente->Num_Doc .' Considerando aislamiento preventivo en casa hasta terminar los días obligatorios de aislamiento '. self::$recomendacion->fecha_aislamiento .'. Considerar alta según evolución de la paciente, de acuerdo a los criterios definidos por el ministerio de salud y protección social.

Por lo anterior se recomienda respetar las medidas preventivas generales y obligatorias que debe seguir toda la población, como los son, lavado de manos, uso de mascarilla, distanciamiento social y demás disposiciones para evitar el contagio.'),0,'J');

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        #FIRMAS MEDICO
        if(isset(self::$medico)){
            if (file_exists(storage_path(substr(self::$medico->Firma, 9)))) {
                $pdf->Image(storage_path(substr(self::$medico->Firma, 9)), 85, 180, 56, 11);
            }
        }
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(10,200);
        $pdf->Cell(192, 4, utf8_decode('MEDICO SUMIMEDICAL RED VITAL'), 0, 0, 'C'); 
        $pdf->Image($logo2, 0, 290, 218, 8);
        $pdf->Ln();
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
