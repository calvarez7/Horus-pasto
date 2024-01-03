<?php
namespace App\Formats;
use App\Modelos\IntegrantesJuntaGirs;
use Codedge\Fpdf\Fpdf\Fpdf;

class TeleConcepto extends FPDF{

    public static $data;
    public static $teleconcepto;


    public function generar($datos)
    {
        self::$teleconcepto = \App\Modelos\Teleconcepto::find($datos["id"]);
        self::$data = $datos;
        $pdf = new TeleConcepto('p', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->Output();
    }

    public function Header()
    {
        $this->SetDrawColor(140,190,56);
        $this->Rect(5, 5, 200, 287);
        $this->SetDrawColor(0,0,0);

        $this->SetFont('Arial', 'B', 9);
        $logo = public_path() . "/images/logo.png";
        $this->Image($logo, 16, 9, -220);
        $this->SetFont('Arial', '', 7);
        $this->SetXY(8, 37);
        $this->Cell(60, 3, utf8_decode('NIT:900033371-4 Res: 004 '), 0, 0, 'C');
        $pdf = $this;

        $pdf->SetXY(12,45);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(186, 4, utf8_decode('DATOS DEL USUARIO'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetXY(12, 49);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('NOMBRE'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["paciente"]["Primer_Nom"]." ".self::$data["paciente"]["Primer_Ape"]), 1, 0, 'l');
        $pdf->SetXY(105,49);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('TIPO DE IDENTIFICACIÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["paciente"]["Tipo_Doc"]), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('IDENTIFICACIÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["paciente"]["Num_Doc"]), 1, 0, 'l');
        $pdf->SetXY(105,53);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('EDAD'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["paciente"]["Edad_Cumplida"]), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('TELEFONO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["paciente"]["Celular2"]), 1, 0, 'l');
        $pdf->SetXY(105,57);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('CELULAR'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["paciente"]["Celular1"]), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('EMAIL'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["paciente"]["Correo1"]), 1, 0, 'l');
        $pdf->SetXY(105,61);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(46.5, 4, utf8_decode('EPS'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(46.5, 4, utf8_decode(self::$data["Ut"]), 1, 0, 'l');
        $pdf->Ln();
        if(intval(self::$teleconcepto->girs_auditor)){
            $pdf->Setx(12);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetDrawColor(0, 0, 0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(186, 4, utf8_decode('JUNTA GIRS'), 1, 0, 'C', 1);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(93, 4, utf8_decode('JUNTA PROFESIONAL APRUEBA'), 1, 0, 'C', 1);
            $pdf->Cell(93, 4, utf8_decode('CLASIFICACION PRIORIDAD SERVICIO'), 1, 0, 'C', 1);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(93, 4, utf8_decode(strtoupper(self::$teleconcepto->junta_aprueba)), 1, 0, 'C', 0);
            $pdf->Cell(93, 4, utf8_decode(self::$teleconcepto->Tipo_Solicitud), 1, 0, 'C', 0);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(186, 4, utf8_decode('EVALUACION JUNTA'), 1, 0, 'C', 1);
            $pdf->Ln();
            $pdf->SetX(12);
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(186,4,utf8_decode(self::$teleconcepto->evaluacion_junta),1,'L',0);
            $pdf->SetX(12);
            $firmaY = $pdf->GetY();
            $x = 12;
                $this->SetXY($x,$firmaY+5);
                $this->SetFont('Arial', 'B', 15);
                $this->Cell(25, 6, utf8_decode('Firma Junta Medica'), 0, 0, 'L');
                $this->Ln();


        }else{
            $pdf->Setx(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(186, 4, utf8_decode('TELEAPOYO - FECHA: '.self::$teleconcepto->created_at), 1, 0, 'C', 1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 4, utf8_decode('MOTIVO:'), 1, 0, 'L', 0);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(156, 4, utf8_decode(self::$data["motivo"]), 1, 0, 'L', 0);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(186, 4, utf8_decode('RESUMEN HISTORIA CLINICA'), 1, 0, 'C', 1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["ResumenHc"]), 1, "L", 0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(186, 4, utf8_decode('RESPUESTA DEL ESPECIALISTA - FECHA: '. self::$teleconcepto->updated_at), 1, 0, 'C', 1);
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$data["Respuesta"]), 1, "L", 0);
        $pdf->SetX(12);
        $firmaY = $pdf->GetY();
        $pdf->Rect(12,$firmaY,66,18);
        if(self::$data["Firma"]){
            if (file_exists(storage_path(substr(self::$data["Firma"], 9)))) {
                $this->Image(storage_path(substr(self::$data["Firma"], 9)), 5,$firmaY,66,18);
            }
        }
        $this->SetXY(12,$firmaY+18);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 6, utf8_decode('Registro Médico: '), 0, 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(25, 6, utf8_decode(self::$data["Registromedico"]), 0, 0, 'L');
        $this->Ln();
        $this->SetX(12);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(20, 6, utf8_decode('Especialidad: '), 0, 0, 'L');
        $this->SetFont('Arial', '', 7);
        $this->Cell(25, 6, utf8_decode(self::$data["especialidad_medico"]), 0, 0, 'L');
        }

    }
}
