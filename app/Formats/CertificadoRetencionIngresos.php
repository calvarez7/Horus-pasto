<?php

namespace App\Formats;

use Carbon\Carbon;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\DB;

class CertificadoRetencionIngresos extends FPDF
{
    public static $empleado;
    public static $fechaI;
    public static $fechaF;


    public function generar($cedula3, $tipo, $fechaI, $fechaF)
    {
        $pdf = new CertificadoRetencionIngresos('p', 'mm', 'A4');
        self::$empleado = DB::select("exec SP_CERTIFICADO_DIAN  ?,?,?,?", [$cedula3, $fechaI, $fechaF, $tipo]);

        self::$fechaF = $fechaF;
        self::$fechaI = $fechaI;

        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetTitle('Certificado retencion ingresos');
        $pdf->body();
        $pdf->Output();
    }

    public function TextWithDirection($x, $y, $txt, $direction = 'R')
    {
        if ($direction == 'R')
            $s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 1, 0, 0, 1, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        elseif ($direction == 'L')
            $s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', -1, 0, 0, -1, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        elseif ($direction == 'U')
            $s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 0, 1, -1, 0, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        elseif ($direction == 'D')
            $s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', 0, -1, 1, 0, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        else
            $s = sprintf('BT %.2F %.2F Td (%s) Tj ET', $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        if ($this->ColorFlag)
            $s = 'q ' . $this->TextColor . ' ' . $s . ' Q';
        $this->_out($s);
    }

    public function TextWithRotation($x, $y, $txt, $txt_angle, $font_angle = 0)
    {
        $font_angle += 90 + $txt_angle;
        $txt_angle *= M_PI / 180;
        $font_angle *= M_PI / 180;

        $txt_dx = cos($txt_angle);
        $txt_dy = sin($txt_angle);
        $font_dx = cos($font_angle);
        $font_dy = sin($font_angle);

        $s = sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET', $txt_dx, $txt_dy, $font_dx, $font_dy, $x * $this->k, ($this->h - $y) * $this->k, $this->_escape($txt));
        if ($this->ColorFlag)
            $s = 'q ' . $this->TextColor . ' ' . $s . ' Q';
        $this->_out($s);
    }

    public function body()
    {
        $this->SetLineWidth(0.4);
        $this->SetDrawColor(64, 100, 148);

        $logo = public_path() . "/images/dian.png";
        $logo1 = public_path() . "/images/220.png";

        $this->SetXY(50, 10);
        $this->SetFont('Arial', 'B', 8);
        $this->MultiCell(116, 5.5, utf8_decode('Certificado de Ingresos y Retenciones por Rentas de Trabajo y de Pensiones
        Año gravable 2020'), 1, 'C');
        $this->Ln();

        $this->SetXY(10, 10);
        $this->Cell(40, 11, $this->Image($logo, $this->GetX(), $this->GetY(), 40, 10), 1, 1, 'C', false);
        $this->Ln();

        $this->SetXY(166, 10);
        $this->Cell(35, 11, $this->Image($logo1, $this->GetX(), $this->GetY(), 35, 11), 1, 1, 'C', false);
        $this->Ln();

        $this->SetXY(10, 21);
        $this->SetFont('Arial', '', 7);
        $this->MultiCell(95.5, 7, utf8_decode('Antes de diligenciar este formulario lea cuidadosamente las instrucciones'), 1, 'C');
        $this->Ln();

        $this->SetXY(105.5, 21);
        $this->SetFont('Arial', '', 7);
        $this->MultiCell(95.5, 7, utf8_decode(' 4. Número de formulario'), 1, 'L');
        $this->Ln();

        $this->SetXY(140.5, 21);
        $this->SetFont('Arial', '', 7);
        $this->MultiCell(55, 7, utf8_decode('123456789123456789'), 0, 'L');
        $this->Ln();

        $this->SetFont('Arial', '', 7);
        $this->TextWithDirection(14, 42.6, 'Retenedor', 'U');
        $this->Ln();

        $this->Line(10,  28,  10,  266); ////
        $this->Line(17,  28,  17, 53); //
        foreach (self::$empleado as $key) {
            $this->SetXY(17, 28);
            $this->SetFont('Arial', '', 6);
            $this->MultiCell(88.5, 5, utf8_decode(' 5. Número de Identificación Tributaria (NIT)'), 0, 'J');
            $this->Ln();

            $this->SetXY(17, 32);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(88.5, 5, utf8_decode($key->NUMERO_IDENTIFICACION_TRIBUTARIA), 0, 'J');
            $this->Ln();

            $this->SetXY(76, 28);
            $this->SetFont('Arial', '', 6);
            $this->MultiCell(88.5, 5, utf8_decode(' 6. DV.'), 0, 'J');
            $this->Ln();

            $this->SetXY(77, 32);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(88.5, 5, utf8_decode($key->DV), 0, 'J');
            $this->Ln();

            $this->Line(77.5,  33,  77.5,  36.3);

            $this->Line(201,  36.3,  17,  36.3);
            $this->Line(86,  28,  86,  36.3);

            $this->SetXY(86, 28);
            $this->SetFont('Arial', '', 6);
            $this->MultiCell(25.5, 5, utf8_decode('7. Primer apellido'), 0, 'J');
            $this->Ln();


            $this->SetXY(86, 32);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(25.5, 5, utf8_decode($key->PRIMER_APELLIDO), 0, 'J');
            $this->Ln();

            $this->Line(112,  34,  112,  36.3);

            $this->SetXY(112, 28);
            $this->SetFont('Arial', '', 6);
            $this->MultiCell(30, 5, utf8_decode('8. Segundo apellido'), 0, 'J');
            $this->Ln();

            $this->SetXY(112, 32);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(30, 5, utf8_decode($key->SEGUNDO_APELLIDO), 0, 'J');
            $this->Ln();

            $this->Line(142,  34,  142,  36.3);

            $this->SetXY(142, 28);
            $this->SetFont('Arial', '', 6);
            $this->MultiCell(30, 5, utf8_decode('9. Primer nombre'), 0, 'J');
            $this->Ln();

            $this->SetXY(142, 32);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(30, 5, utf8_decode($key->PRIMER_NOMBRE), 0, 'J');
            $this->Ln();

            $this->Line(172,  34,  172,  36.3);

            $this->SetXY(171, 28);
            $this->SetFont('Arial', '', 6);
            $this->MultiCell(30, 5, utf8_decode('10. Otros nombres'), 0, 'J');
            $this->Ln();

            $this->SetXY(172, 32);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(30, 5, utf8_decode($key->SEGUNDO_NOMBRE), 0, 'J');
            $this->Ln();

            $this->SetXY(17, 36);
            $this->SetFont('Arial', '', 6);
            $this->MultiCell(184, 5, utf8_decode(' 11. Razón social'), 0, 'J');
            $this->Ln();

            $this->SetXY(17.5, 40);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(184, 5, utf8_decode($key->RAZON_SOCIAL), 0, 'J');
            $this->Ln();

            $this->Line(201,  28,  201,  266); ////
            $this->Line(201,  45,  10,  45);

            $this->SetFont('Arial', 'B', 4);
            $this->TextWithDirection(14, 52.5, 'Trabajador', 'U');
            $this->Ln();

            $this->SetXY(17, 45);
            $this->SetFont('Arial', '', 6);
            $this->MultiCell(30, 5, utf8_decode(' 24. Tipo de documento'), 0, 'J');
            $this->Ln();

            $this->SetXY(17, 48.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(30, 5, utf8_decode(' C.C'), 0, 'J');
            $this->Ln();

            $this->Line(47,  45.4,  47,  53);

            $this->SetXY(47, 45);
            $this->SetFont('Arial', '', 6);
            $this->MultiCell(39, 5, utf8_decode('25. Número de Identificación'), 0, 'J');
            $this->Ln();

            $this->SetXY(47, 49);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(39, 5, utf8_decode($key->NUMERO_FORMULARIO), 0, 'J');
            $this->Ln();

            $this->Line(85,  45.4,  85,  53);

            $this->SetXY(86, 45);
            $this->SetFont('Arial', '', 6);
            $this->MultiCell(25.5, 5, utf8_decode('26. Primer apellido'), 0, 'J');
            $this->Ln();

            $this->SetXY(86, 49);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(25.5, 5, utf8_decode($key->PRIMER_APELLIDO), 0, 'J');
            $this->Ln();

            $this->Line(112,  50,  112,  53);

            $this->SetXY(112, 45);
            $this->SetFont('Arial', '', 6);
            $this->MultiCell(30, 5, utf8_decode('27. Segundo apellido'), 0, 'J');
            $this->Ln();

            $this->SetXY(112, 49);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(30, 5, utf8_decode($key->SEGUNDO_APELLIDO), 0, 'J');
            $this->Ln();

            $this->Line(142,  50,  142,  53);

            $this->SetXY(142, 45);
            $this->SetFont('Arial', '', 6);
            $this->MultiCell(30, 5, utf8_decode('28. Primer nombre'), 0, 'J');
            $this->Ln();

            $this->SetXY(142, 49);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(30, 5, utf8_decode($key->PRIMER_NOMBRE), 0, 'J');
            $this->Ln();

            $this->Line(172,  50,  172,  53);

            $this->SetXY(171, 45);
            $this->SetFont('Arial', '', 6);
            $this->MultiCell(30, 5, utf8_decode('29. Otros nombres'), 0, 'J');
            $this->Ln();

            $this->SetXY(172, 49);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(30, 5, utf8_decode($key->SEGUNDO_NOMBRE), 0, 'J');
            $this->Ln();

            $this->Line(201,  53.3,  10,  53.3); //

            $this->SetXY(10, 53.5);
            $this->SetFont('Arial', '', 6);
            $this->MultiCell(76, 4, utf8_decode('Período de la Certificación'), 0, 'C');
            $this->Ln();

            $this->SetXY(10, 57.5);
            $this->SetFont('Arial', 'B', 6);
            $this->MultiCell(76, 4, utf8_decode('30. DE:'), 0, 'J');
            $this->Ln();

            $año = Carbon::now()->year;
            $resta = ($año - 1);

            $this->SetXY(24, 57.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(19, 4, utf8_decode(self::$fechaI), 0, 'C');
            $this->Ln();

            $this->SetXY(50, 57.5);
            $this->SetFont('Arial', 'B', 6);
            $this->MultiCell(8, 4, utf8_decode('31. A:'), 0, 'C');
            $this->Ln();

            $this->SetXY(61, 57.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(20, 4, utf8_decode(self::$fechaF), 0, 'C');
            $this->Ln();

            $this->Line(86,  53.5,  86,  61); ///

            $this->SetXY(86.3, 53.5);
            $this->SetFont('Arial', '', 6);
            $this->MultiCell(27, 4, utf8_decode('32. Fecha de expedición'), 0, 'C');
            $this->Ln();

            $this->SetXY(86.3, 57.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(27, 3.5, utf8_decode($key->FECHA_EXPEDICION), 0, 'C');
            $this->Ln();

            $this->Line(115,  53.5,  115,  61); ///

            $this->SetXY(113.3, 53.5);
            $this->SetFont('Arial', '', 6);
            $this->MultiCell(44, 4, utf8_decode('33. Lugar donde se practicó la retención'), 0, 'C');
            $this->Ln();

            $this->SetXY(113.3, 57.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(44, 3.5, utf8_decode($key->LUGAR_EXPEDICION), 0, 'C');
            $this->Ln();

            $this->Line(158,  53.5,  158,  61); ///

            $this->SetXY(157.3, 53.5);
            $this->SetFont('Arial', '', 6);
            $this->MultiCell(17.5, 4, utf8_decode('34. Cód.Dpto.'), 0, 'C');
            $this->Ln();

            $this->SetXY(157.3, 57.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(17.5, 3.5, utf8_decode($key->COD_DPTO), 0, 'C');
            $this->Ln();

            $this->Line(175,  53.5,  175,  61); ///

            $this->SetXY(175, 53.5);
            $this->SetFont('Arial', '', 6);
            $this->MultiCell(26, 4, utf8_decode('35.Cód.Ciudad/Municipio'), 0, 'C');
            $this->Ln();

            $this->SetXY(175, 57.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(26, 3.5, utf8_decode($key->COD_MUNICIPIO), 0, 'C');
            $this->Ln();

            $this->Line(201,  61,  10,  61); ///

            $this->Line(150.5,  61.3,  150.5,  150.5); ///VV
            $this->Line(156.5,  66,  156.5,  117.7); ///VV


            $this->SetXY(10.5, 61.5);
            $this->SetFont('Arial', 'B', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(139.5, 4, utf8_decode('Concepto de los Ingresos'), 0, 'C', true);
            $this->Ln();

            $this->SetXY(10, 65.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(140, 4, utf8_decode('Pagos por salarios o emolumentos eclesiásticos'), 0, 'J');
            $this->Ln();

            $this->SetXY(151, 65.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(5, 4, utf8_decode('36'), 0, 'J');
            $this->Ln();

            $this->SetXY(10.5, 69.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(139.5, 4, utf8_decode('Pagos realizados con bonos electrónicos o de papel de servicio, cheques, tarjetas, vales, etc.'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(151, 69.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(5, 4, utf8_decode('37'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(10, 73.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(140, 4, utf8_decode('Pagos por honorarios'), 0, 'J');
            $this->Ln();

            $this->SetXY(151, 73.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(5, 4, utf8_decode('38'), 0, 'J');
            $this->Ln();

            $this->SetXY(10.5, 77.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(139.5, 4, utf8_decode('Pagos por servicios'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(151, 77.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(5, 4, utf8_decode('39'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(10, 81.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(140, 4, utf8_decode('Pagos por comisiones'), 0, 'J');
            $this->Ln();

            $this->SetXY(151, 81.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(5, 4, utf8_decode('40'), 0, 'J');
            $this->Ln();

            $this->SetXY(10.5, 85.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(139.5, 4, utf8_decode('Pagos por prestaciones sociales'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(151, 85.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(5, 4, utf8_decode('41'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(10, 89.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(140, 4, utf8_decode('Pagos por viáticos'), 0, 'J');
            $this->Ln();

            $this->SetXY(151, 89.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(5, 4, utf8_decode('42'), 0, 'J');
            $this->Ln();

            $this->SetXY(10.5, 93.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(139.5, 4, utf8_decode('Pagos por gastos de representación'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(151, 93.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(5, 4, utf8_decode('43'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(10, 97.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(140, 4, utf8_decode('Pagos por compensaciones por el trabajo asociado cooperativo'), 0, 'J');
            $this->Ln();

            $this->SetXY(151, 97.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(5, 4, utf8_decode('44'), 0, 'J');
            $this->Ln();

            $this->SetXY(10.5, 101.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(139.5, 4, utf8_decode('Otros pagos'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(151, 101.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(5, 4, utf8_decode('45'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(10, 105.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(140, 4, utf8_decode('Cesantías e intereses de cesantías efectivamente pagadas en el periodo'), 0, 'J');
            $this->Ln();

            $this->SetXY(151, 105.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(5, 4, utf8_decode('46'), 0, 'J');
            $this->Ln();

            $this->SetXY(10.5, 109.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(139.5, 4, utf8_decode('Pensiones de jubilación, vejez o invalidez'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(151, 109.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(5, 4, utf8_decode('47'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(10, 113.5);
            $this->SetFont('Arial', 'B', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(140, 4, utf8_decode('Total de ingresos brutos (Sume 36 a 47)'), 0, 'J');
            $this->Ln();

            $this->SetXY(151, 113.5);
            $this->SetFont('Arial', 'B', 7);
            $this->MultiCell(5, 4, utf8_decode('48'), 0, 'J');
            $this->Ln();

            $this->SetXY(150.9, 61.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(49.5, 4, utf8_decode('Valor'), 0, 'C', true);
            $this->Ln();

            $this->SetXY(157, 65.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(43.5, 4, utf8_decode(number_format($key->SUELDO)), 0, 'C');
            $this->Ln();

            $this->SetXY(157, 69.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(43.5, 4, utf8_decode(number_format(0)), 0, 'C', true);
            $this->Ln();

            $this->SetXY(157, 73.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(43.5, 4, utf8_decode(number_format(0)), 0, 'C');
            $this->Ln();

            $this->SetXY(157, 77.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(43.5, 4, utf8_decode(number_format(0)), 0, 'C', true);
            $this->Ln();

            $this->SetXY(157, 81.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(43.5, 4, utf8_decode(number_format(0)), 0, 'C');
            $this->Ln();

            $this->SetXY(157, 85.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(43.5, 4, utf8_decode(number_format($key->PRESTACIONES)), 0, 'C', true);
            $this->Ln();

            $this->SetXY(157, 89.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(43.5, 4, utf8_decode(number_format(0)), 0, 'C');
            $this->Ln();

            $this->SetXY(157, 93.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(43.5, 4, utf8_decode(number_format(0)), 0, 'C', true);
            $this->Ln();

            $this->SetXY(157, 97.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(43.5, 4, utf8_decode(number_format(0)), 0, 'C');
            $this->Ln();

            $this->SetXY(157, 101.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(43.5, 4, utf8_decode(number_format($key->PAGOS)), 0, 'C', true);
            $this->Ln();

            $this->SetXY(157, 105.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(43.5, 4, utf8_decode(number_format($key->CESANTIAS)), 0, 'C');
            $this->Ln();

            $this->SetXY(157, 109.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(43.5, 4, utf8_decode(number_format(0)), 0, 'C', true);
            $this->Ln();

            $this->SetXY(157, 113.5);
            $this->SetFont('Arial', 'B', 7);
            $this->MultiCell(43.5, 4, utf8_decode(number_format($key->SUELDO + $key->CESANTIAS + $key->PAGOS + $key->PRESTACIONES)), 0, 'C');
            $this->Ln();

            $this->Line(201,  118,  10,  118); ///

            $this->SetXY(10.5, 118.5);
            $this->SetFont('Arial', 'B', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(139.6, 4, utf8_decode('Concepto de los aportes'), 0, 'C', true);
            $this->Ln();

            $this->SetXY(10.5, 122.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(139.6, 4, utf8_decode('Aportes obligatorios por salud a cargo del trabajador'), 0, 'J');
            $this->Ln();

            $this->SetXY(151, 122.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(5, 4, utf8_decode('49'), 0, 'J');
            $this->Ln();

            $this->SetXY(10.5, 126.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(139.6, 4, utf8_decode('Aportes obligatorios a fondos de pensiones y solidaridad pensional a cargo del trabajador'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(151, 126.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(5, 4, utf8_decode('50'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(10.5, 130.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(139.6, 4, utf8_decode('Cotizaciones voluntarias al régimen de ahorro individual con solidaridad - RAIS'), 0, 'J');
            $this->Ln();

            $this->SetXY(151, 130.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(5, 4, utf8_decode('51'), 0, 'J');
            $this->Ln();

            $this->SetXY(10.5, 134.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(139.6, 4, utf8_decode('Aportes voluntarios al impuesto solidario por COVID 19'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(151, 134.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(5, 4, utf8_decode('52'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(10.5, 138.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(139.6, 4, utf8_decode('Aportes voluntarios a fondos de pensiones '), 0, 'J');
            $this->Ln();

            $this->SetXY(151, 138.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(5, 4, utf8_decode('53'), 0, 'J');
            $this->Ln();

            $this->SetXY(10.5, 142.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(139.6, 4, utf8_decode('Aportes a cuentas AFC'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(151, 142.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(5, 4, utf8_decode('54'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(150.9, 118.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(49.5, 4, utf8_decode('Valor'), 0, 'C', true);
            $this->Ln();

            $this->SetXY(157, 122.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(43.5, 4, utf8_decode(number_format($key->APORTES_SALUD)), 0, 'C');
            $this->Ln();

            $this->SetXY(157, 126.6);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(43.5, 4, utf8_decode(number_format($key->APORTES_PENSION)), 0, 'C', true);
            $this->Ln();

            $this->SetXY(157, 130.6);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(43.5, 4, utf8_decode(number_format(0)), 0, 'C');
            $this->Ln();

            $this->SetXY(157, 134.6);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(43.5, 4, utf8_decode(number_format(0)), 0, 'C', true);
            $this->Ln();

            $this->SetXY(157, 138.6);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(43.5, 4, utf8_decode(number_format($key->APORTES_PENSION_VOLUNTARIOS)), 0, 'C');
            $this->Ln();

            $this->SetXY(157, 142.6);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(43.5, 4, utf8_decode(number_format($key->APORTES_AFC)), 0, 'C', true);
            $this->Ln();

            $this->SetXY(156.5, 146.6);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(44.5, 4, utf8_decode(number_format($key->RETENCION_FUENTE)), 1, 'C');
            $this->Ln();

            $this->SetXY(156.5, 150.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(44.5, 4.5, utf8_decode(number_format(0)), 1, 'C');
            $this->Ln();

            $this->SetXY(10.3, 146.5);
            $this->SetFont('Arial', '', 7);
            $this->SetFillColor(60, 100, 148);
            $this->SetTextColor(255, 255, 255);
            $this->MultiCell(139.9, 4.5, utf8_decode('Valor de la retención en la fuente por ingresos laborales y de pensiones'), 1, 'J', true);
            $this->Ln();

            $this->SetXY(151, 146.5);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(5, 4, utf8_decode('55'), 0, 'J');
            $this->Ln();

            $this->SetXY(10, 150.9);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(140.5, 4, utf8_decode(' Retenciones por aportes obligatorios al impuesto solidario por COVID 19'), 1, 'J');
            $this->Ln();

            $this->SetXY(151, 150.5);
            $this->SetFont('Arial', '', 7);
            $this->MultiCell(5, 4, utf8_decode('56'), 0, 'J');
            $this->Ln();

            $this->SetXY(10, 154.9);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(191, 4, utf8_decode(' Nombre del pagador o agente retenedor'), 1, 'J');
            $this->Ln();

            $this->SetXY(10, 158.9);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(191, 4, utf8_decode('Datos a cargo del trabajador o pensionado'), 1, 'C');
            $this->Ln();

            $this->Line(156.5,  122.8,  156.5,  155); ///VV

            $this->SetXY(10.3, 163.3);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(90, 4, utf8_decode('Concepto de otros ingresos'), 0, 'C', true);
            $this->Ln();

            $this->SetXY(10.3, 167.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(90, 4, utf8_decode('Arrendamientos'), 0, 'J');
            $this->Ln();

            $this->SetXY(101.3, 167.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(5, 4, utf8_decode('57'), 0, 'J');
            $this->Ln();

            $this->SetXY(10.3, 171.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(90, 4, utf8_decode('Honorarios, comisiones y servicios'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(101.3, 171.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(5, 4, utf8_decode('58'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(10.3, 175.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(90, 4, utf8_decode('Intereses y rendimientos financieros'), 0, 'J');
            $this->Ln();

            $this->SetXY(101.3, 175.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(5, 4, utf8_decode('59'), 0, 'J');
            $this->Ln();

            $this->SetXY(10.3, 179.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(90, 4, utf8_decode('Enajenación de activos fijos'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(101.3, 179.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(5, 4, utf8_decode('60'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(10.3, 183.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(90, 4, utf8_decode('Loterías, rifas, apuestas y similares'), 0, 'J');
            $this->Ln();

            $this->SetXY(101.3, 183.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(5, 4, utf8_decode('61'), 0, 'J');
            $this->Ln();

            $this->SetXY(10.3, 187.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(90, 4, utf8_decode('Otros'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(101.3, 187.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(5, 4, utf8_decode('62'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(10.3, 191.3);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(90, 4, utf8_decode('Totales: (Valor recibido: Sume 57 a 62), (Valor retenido: Sume 64 a 69)'), 0, 'J');
            $this->Ln();

            $this->SetXY(101.3, 191.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(5, 4, utf8_decode('63'), 0, 'J');
            $this->Ln();

            $this->Line(100.8,  163.2,  100.8,  199.2); ///VV
            $this->Line(106.8,  163.2,  106.8,  199.2); ///VV


            $this->SetXY(101.3, 163.3);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(50.2, 4, utf8_decode('Valor recibido'), 0, 'C', true);
            $this->Ln();

            $this->SetXY(107.3, 167.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(44.2, 4, utf8_decode('0'), 0, 'C');
            $this->Ln();

            $this->SetXY(107.3, 171.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(44.2, 4, utf8_decode('0'), 0, 'C', true);
            $this->Ln();

            $this->SetXY(107.3, 175.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(44.2, 4, utf8_decode('0'), 0, 'C');
            $this->Ln();

            $this->SetXY(107.3, 179.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(44.2, 4, utf8_decode('0'), 0, 'C', true);
            $this->Ln();

            $this->SetXY(107.3, 183.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(44.2, 4, utf8_decode('0'), 0, 'C');
            $this->Ln();

            $this->SetXY(107.3, 187.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->SetFillColor(194, 209, 226);
            $this->MultiCell(44.2, 4, utf8_decode('0'), 0, 'C', true);
            $this->Ln();

            $this->SetXY(107.3, 191.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(44.2, 4, utf8_decode('0'), 0, 'C');
            $this->Ln();

            $this->Line(151.8,  163.2,  151.8,  199.2); ///VV
            $this->Line(157.8,  167.2,  157.8,  232.3); ///VV

            $this->SetXY(152.5, 167.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(5, 4, utf8_decode('64'), 0, 'C');
            $this->Ln();

            $this->SetXY(152.5, 171.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(5, 4, utf8_decode('65'), 0, 'C', true);
            $this->Ln();

            $this->SetXY(152.5, 175.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(5, 4, utf8_decode('66'), 0, 'C');
            $this->Ln();

            $this->SetXY(152.5, 179.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(5, 4, utf8_decode('67'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(152.5, 183.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(5, 4, utf8_decode('68'), 0, 'J');
            $this->Ln();

            $this->SetXY(152.5, 187.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(5, 4, utf8_decode('69'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(152.5, 191.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(5, 4, utf8_decode('70'), 0, 'J');
            $this->Ln();

            $this->SetXY(152.5, 195.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(5, 4, utf8_decode('71'), 0, 'J', true);
            $this->Ln();

            $this->SetXY(152.2, 163.3);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(48.5, 4, utf8_decode('Valor retenido'), 0, 'C', true);
            $this->Ln();

            $this->SetXY(158.2, 167.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(42.5, 4, utf8_decode(0), 0, 'C');
            $this->Ln();

            $this->SetXY(158.2, 171.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(42.5, 4, utf8_decode(0), 0, 'C', true);
            $this->Ln();

            $this->SetXY(158.2, 175.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(42.5, 4, utf8_decode(0), 0, 'C');
            $this->Ln();

            $this->SetXY(158.2, 179.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(42.5, 4, utf8_decode(0), 0, 'C', true);
            $this->Ln();

            $this->SetXY(158.2, 183.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(42.5, 4, utf8_decode(0), 0, 'C');
            $this->Ln();

            $this->SetXY(158.2, 187.3);
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(42.5, 4, utf8_decode(0), 0, 'C', true);
            $this->Ln();

            $this->SetXY(158.2, 191.3);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(42.5, 4, utf8_decode(0), 0, 'C');
            $this->Ln();

            $this->SetXY(158.2, 195.3);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(42.5, 4, utf8_decode(number_format($key->RETENCION_FUENTE)), 0, 'C', true);
            $this->Ln();

            $this->SetXY(10, 195.3);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(141.5, 4, utf8_decode('Total retenciones año gravable 2020 (Sume 55 + 56 + 70)'), 0, 'J', true);
            $this->Ln();

            $this->Line(201,  199.3,  10,  199.3); ///

            $this->SetXY(10.5, 200);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(10, 4, utf8_decode('Item'), 0, 'C', true);
            $this->Ln();

            $this->SetXY(10.5, 204);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(10, 4, utf8_decode('1'), 0, 'C');
            $this->Ln();

            $this->SetXY(10.5, 208);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(10, 4, utf8_decode('2'), 0, 'C', true);
            $this->Ln();

            $this->SetXY(10.5, 212);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(10, 4, utf8_decode('3'), 0, 'C');
            $this->Ln();

            $this->SetXY(10.5, 216);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(10, 4, utf8_decode('4'), 0, 'C', true);
            $this->Ln();

            $this->SetXY(10.5, 220);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(10, 4, utf8_decode('5'), 0, 'C');
            $this->Ln();

            $this->SetXY(10.5, 224);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(10, 4, utf8_decode('6'), 0, 'C', true);
            $this->Ln();

            $this->Line(20.6,  199.5,  20.6,  227.7); ///VV


            $this->SetXY(21.2, 200);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(136, 4, utf8_decode('72. Identificación de los bienes poseídos'), 0, 'C', true);
            $this->Ln();

            $this->SetXY(21.2, 204);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(136, 4, utf8_decode(''), 0, 'C');
            $this->Ln();

            $this->SetXY(21.2, 208);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(136, 4, utf8_decode(''), 0, 'C', true);
            $this->Ln();

            $this->SetXY(21.2, 212);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(136, 4, utf8_decode(''), 0, 'C');
            $this->Ln();

            $this->SetXY(21.2, 216);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(136, 4, utf8_decode(''), 0, 'C', true);
            $this->Ln();

            $this->SetXY(21.2, 220);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(136, 4, utf8_decode(''), 0, 'C');
            $this->Ln();

            $this->SetXY(21.2, 224);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(136, 4, utf8_decode(''), 0, 'C', true);
            $this->Ln();

            $this->SetXY(158.5, 200);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(41.9, 4, utf8_decode('73. Valor patrimonial'), 0, 'C', true);
            $this->Ln();

            $this->SetXY(158.5, 204);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(41.9, 4, utf8_decode(''), 0, 'C');
            $this->Ln();

            $this->SetXY(158.5, 208);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(41.9, 4, utf8_decode(''), 0, 'C', true);
            $this->Ln();

            $this->SetXY(158.5, 212);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(41.9, 4, utf8_decode(''), 0, 'C');
            $this->Ln();

            $this->SetXY(158.5, 216);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(41.9, 4, utf8_decode(''), 0, 'C', true);
            $this->Ln();

            $this->SetXY(158.5, 220);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(41.9, 4, utf8_decode(''), 0, 'C');
            $this->Ln();

            $this->SetXY(158.5, 224);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(41.9, 4, utf8_decode(''), 0, 'C', true);
            $this->Ln();

            $this->SetXY(10, 228.3);
            $this->SetFont('Arial', 'B', 7);
            $this->SetFillColor(60, 100, 148);
            $this->SetTextColor(255, 255, 255);
            $this->MultiCell(140, 4, utf8_decode('Deudas vigentes a 31 de Diciembre de 2020'), 1, 'J', true);
            $this->Ln();

            $this->SetXY(150.3, 228.3);
            $this->SetFont('Arial', 'B', 7);
            $this->SetFillColor(60, 100, 148);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(7.5, 4, utf8_decode('74'), 1, 'C');
            $this->Ln();

            $this->SetXY(158, 228.3);
            $this->SetFont('Arial', 'B', 7);
            $this->SetFillColor(60, 100, 148);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(43, 4, utf8_decode(''), 1, 'C');
            $this->Ln();

            $this->SetXY(10, 232.3);
            $this->SetFont('Arial', 'B', 7);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(191, 4, utf8_decode('dentificación del dependiente económico de acuerdo al parágrafo 2 del artículo 387 del Estatuto Tributario'), 1, 'C');
            $this->Ln();

            $this->SetXY(10, 236.3);
            $this->SetFont('Arial', 'B', 6);
            $this->SetFillColor(60, 100, 148);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(35, 4, utf8_decode('75. Tipo documento'), 0, 'J');
            $this->Ln();

            $this->SetXY(10, 240.3);
            $this->SetFont('Arial', 'B', 6);
            $this->SetFillColor(60, 100, 148);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(35, 4, utf8_decode(''), 0, 'J');
            $this->Ln();

            $this->SetXY(45.2, 236.3);
            $this->SetFont('Arial', 'B', 6);
            $this->SetFillColor(60, 100, 148);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(35, 4, utf8_decode('76. No. Documento'), 0, 'J');
            $this->Ln();

            $this->SetXY(45.2, 240.3);
            $this->SetFont('Arial', 'B', 6);
            $this->SetFillColor(60, 100, 148);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(35, 4, utf8_decode(''), 0, 'J');
            $this->Ln();

            $this->SetXY(80.2, 236.3);
            $this->SetFont('Arial', 'B', 6);
            $this->SetFillColor(60, 100, 148);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(95, 4, utf8_decode('77. Apellidos y Nombres'), 0, 'J');
            $this->Ln();

            $this->Line(78.6,  236.5,  78.6,  245); ///VV

            $this->SetXY(80.2, 240.3);
            $this->SetFont('Arial', 'B', 6);
            $this->SetFillColor(60, 100, 148);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(95, 4, utf8_decode(''), 0, 'J');
            $this->Ln();

            $this->Line(174.6,  236.5,  174.6,  245); ///VV

            $this->SetXY(175.2, 236.3);
            $this->SetFont('Arial', 'B', 6);
            $this->SetFillColor(60, 100, 148);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(25, 4, utf8_decode('78. Parentesco'), 0, 'J');
            $this->Ln();

            $this->Line(43.6,  236.5,  43.6,  245); ///VV

            $this->SetXY(175.2, 240.3);
            $this->SetFont('Arial', 'B', 6);
            $this->SetFillColor(60, 100, 148);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(25, 4, utf8_decode(''), 0, 'J');
            $this->Ln();

            $this->Line(201,  245,  10,  245);

            $this->SetXY(10, 245);
            $this->SetFont('Arial', 'B', 6);
            $this->SetFillColor(60, 100, 148);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(145, 4, utf8_decode('Certifico que durante el año gravable 2020'), 0, 'J');
            $this->Ln();

            $this->SetXY(10, 248.9);
            $this->SetFont('Arial', '', 5);
            $this->SetFillColor(60, 100, 148);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(145, 2, utf8_decode('1. Mi patrimonio bruto no excedió de 4.500 UVT ($160.232.000).'), 0, 'J');
            $this->Ln();

            $this->SetXY(10, 251);
            $this->SetFont('Arial', '', 5);
            $this->SetFillColor(60, 100, 148);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(145, 2, utf8_decode('2. Mis ingresos brutos fueron inferiores a 1.400 UVT ($49.850.000).'), 0, 'J');
            $this->Ln();

            $this->SetXY(10, 253);
            $this->SetFont('Arial', '', 5);
            $this->SetFillColor(60, 100, 148);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(145, 2, utf8_decode('3. No fui responsable del impuesto sobre las ventas.'), 0, 'J');
            $this->Ln();

            $this->SetXY(10, 255);
            $this->SetFont('Arial', '', 5);
            $this->SetFillColor(60, 100, 148);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(145, 2, utf8_decode('4. Mis consumos mediante tarjeta de crédito no excedieron la suma de 1.400 UVT ($49.850.000).'), 0, 'J');
            $this->Ln();

            $this->SetXY(10, 257);
            $this->SetFont('Arial', '', 5);
            $this->SetFillColor(60, 100, 148);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(145, 2, utf8_decode('5. Que el total de mis compras y consumos no superaron la suma de 1.400 UVT ($49.850.000).'), 0, 'J');
            $this->Ln();

            $this->SetXY(10, 259);
            $this->SetFont('Arial', '', 5);
            $this->SetFillColor(60, 100, 148);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(145, 2, utf8_decode('6. Que el valor total de mis consignaciones bancarias, depósitos o inversiones financieras no excedieron los 1.400 UVT ($49.850.000).'), 0, 'J');
            $this->Ln();

            $this->SetXY(10, 261);
            $this->SetFont('Arial', '', 6);
            $this->SetFillColor(60, 100, 148);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(191, 4, utf8_decode('Por lo tanto, manifiesto que no estoy obligado a presentar declaración de renta y complementario por el año gravable 2020.'), 0, 'J');
            $this->Ln();

            $this->SetXY(155, 245);
            $this->SetFont('Arial', 'B', 7);
            $this->SetFillColor(60, 100, 148);
            $this->SetTextColor(0, 0, 0);
            $this->MultiCell(46, 4, utf8_decode('Firma del Trabajador o Pensionado'), 0, 'J');
            $this->Ln();
        }

        $this->Line(201,  266,  10,  266);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', '', 7);
        $this->MultiCell(191, 4, utf8_decode('NOTA: este certificado sustituye para todos los efectos legales la declaración de Renta y Complementario para el trabajador o pensionado que lo firme. Para aquellos trabajadores independientes contribuyentes del impuesto unificado deberán presentar la declaración anual consolidada del Régimen Simple de Tributación (SIMPLE)..'),  0, 'L');
    }
}
