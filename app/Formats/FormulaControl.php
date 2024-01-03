<?php
namespace App\Formats;


use Codedge\Fpdf\Fpdf\Fpdf;


class FormulaControl extends FPDF
{


    public function generar($data)
    {
            $pdf = new FormulaControl('P','mm','A4');
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $this->body($pdf);
            $pdf->Output();
    }
    public function header()
    {
        $this->SetFont('Arial', 'B', 9);
        $logo = public_path() . "/images/logo.png";
        $this->Image($logo, 20, 7, 30);

        #lineas horizontales
        $this->Line(5, 5, 205, 5);
        $this->Line(5, 30, 205, 30);
        $this->Line(5, 5, 205, 5);
        $this->Line(173, 17, 205, 17);

        #lineas verticales
        $this->Line(5, 5, 5, 30);
        $this->Line(205, 5, 205, 30);
        $this->Line(60, 5, 60, 30);
        $this->Line(173, 5, 173, 30);

        $this->SetXY(15, 12);
        $this->SetFont('Arial', '', 15);
        $this->Cell(192, 6, utf8_decode('RECETARIO MEDICAMENTOS DE'), 0, 0, 'C');

        $this->SetXY(175, 11);
        $this->SetFont('Arial', '', 10);
        $this->Cell(192, 6, utf8_decode('N° Consecutivo'), 0, 0, 'L');

        $this->SetXY(15, 20);
        $this->SetFont('Arial', '', 15);
        $this->Cell(192, 6, utf8_decode('CONTROL ESPECIAL'), 0, 0, 'C');

        $this->SetXY(15, 30);
        $this->Cell(15, 5, utf8_decode(' '), 0, 0, 'L');
        $this->ln();

    }

    public function footer()
    {
        $this->SetXY(180,282);
        $this->Cell(0,10,'Pagina '.$this->PageNo().' de {nb}',0,0,'C');
    }
    public function body($pdf)
    {

        $y = $pdf->GetY();
        $pdf->SetXY(5,$y+4);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(200, 6, utf8_decode('1. PACIENTE'), 1, 0, 'L',1);
        $y1 = $pdf->GetY();

        $y2 = $pdf->GetY();
        $pdf->Line(70, $y1, 70, $y2+6); #LINA VERTICAL IZQUIERDA
        $pdf->Line(100, $y1, 100, $y2+6); #LINEA VERTICAL DERECHA
        $pdf->Line(140, $y1, 140, $y2+6); #LINEA VERTICAL DERECHA
        $pdf->Line(175, $y1, 175, $y2+6);
        $pdf->Line(5, $y1+6, 5, $y2+45); #LINEA VERTICAL DERECHA
        $pdf->Line(205, $y1+6, 205, $y2+45);

        $pdf->SetXY(70,$y+4);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 6, utf8_decode('Fecha'), 0, 0, 'L');

        $pdf->SetXY(100,$y+4);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 6, utf8_decode('Día: '), 0, 0, 'L');

        $pdf->SetXY(140,$y+4);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 6, utf8_decode('Més: '), 0, 0, 'L');

        $pdf->SetXY(175,$y+4);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 6, utf8_decode('Año: '), 0, 0, 'L');

        $y2 = $pdf->GetY();
        $pdf->Line(85, $y1+8, 85, $y2+40); #LINA VERTICAL IZQUIERDA
        $pdf->Line(145, $y1+8, 145, $y2+18); #LINEA VERTICAL DERECHA
        $pdf->Line(128, $y1+18, 128, $y2+40); #LINEA VERTICAL DERECHA
        $pdf->Line(167, $y1+18, 167, $y2+40); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y1+8, 205, $y2+8); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y1+13, 205, $y2+13); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y1+18, 205, $y2+18); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y1+23, 205, $y2+23); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y1+30, 205, $y2+30); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y1+35, 205, $y2+35); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y1+40, 205, $y2+40); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y1+45, 205, $y2+45); #LINEA VERTICAL DERECHA

        $pdf->SetXY(32,$y+12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Primer apellido'), 0, 0, 'L');

        $pdf->SetXY(98,$y+12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Segundo apellido'), 0, 0, 'L');

        $pdf->SetXY(165,$y+12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Nombres'), 0, 0, 'L');

        $pdf->Line(20, $y1+23, 20, $y2+30); #LINA VERTICAL IZQUIERDA
        $pdf->Line(32, $y1+23, 32, $y2+30); #LINEA VERTICAL DERECHA
        $pdf->Line(46, $y1+23, 46, $y2+30); #LINEA VERTICAL DERECHA
        $pdf->Line(185, $y1+23, 185, $y2+30); #LINEA VERTICAL DERECHA

        $pdf->SetXY(25,$y+22);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Documento de identificación'), 0, 0, 'L');

        $pdf->SetXY(98,$y+22);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Número'), 0, 0, 'L');

        $pdf->SetXY(140,$y+22);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Edad'), 0, 0, 'L');

        $pdf->SetXY(179,$y+22);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Género'), 0, 0, 'L');

        $pdf->SetXY(25,$y+34);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Dirección de residencia'), 0, 0, 'L');

        $pdf->SetXY(97,$y+34);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Teléfono(s)'), 0, 0, 'L');

        $pdf->SetXY(138,$y+34);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Municipio'), 0, 0, 'L');

        $pdf->SetXY(174,$y+34);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Departamento'), 0, 0, 'L');

        $pdf->SetXY(6,$y+28);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(10, 6, utf8_decode('R.C'), 0, 0, 'L');
        $pdf->SetXY(14,$y+29);
        $pdf->MultiCell(4, 4, utf8_decode(''), 1, 'C');

        $pdf->SetXY(20,$y+28);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(10, 6, utf8_decode('T.I'), 0, 0, 'L');
        $pdf->SetXY(27,$y+29);
        $pdf->MultiCell(4, 4, utf8_decode(''), 1, 'C');

        $pdf->SetXY(32,$y+28);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(10, 6, utf8_decode('C.C'), 0, 0, 'L');
        $pdf->SetXY(39,$y+29);
        $pdf->MultiCell(4, 4, utf8_decode(''), 1, 'C');

        $pdf->SetXY(46,$y+28);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(10, 6, utf8_decode('Otro ¿Cual? _____________'), 0, 0, 'L');

        $pdf->SetXY(168,$y+29);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(10, 6, utf8_decode('F___________________M'), 0, 0, 'L');

        $pdf->SetXY(5,$y+44);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 6, utf8_decode('Afiliación al S.G.S.S.S'), 0, 0, 'L');

        $pdf->SetXY(45,$y+44);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 6, utf8_decode('Subsidiado'), 0, 0, 'L');

        $pdf->SetXY(73,$y+44);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 6, utf8_decode('Contributivo'), 0, 0, 'L');

        $pdf->SetXY(100,$y+44);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 6, utf8_decode('Otro ¿Cuál? _____________'), 0, 0, 'L');

        $pdf->SetXY(140,$y+44);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(10, 6, utf8_decode('Nombre de la entidad aseguradora _______________'), 0, 0, 'L');

        $pdf->SetXY(35,$y+45);
        $pdf->MultiCell(3.5, 3.5, utf8_decode(''), 1, 'C');
        $pdf->SetXY(62,$y+45);
        $pdf->MultiCell(3.5, 3.5, utf8_decode(''), 1, 'C');
        $pdf->SetXY(92,$y+45);
        $pdf->MultiCell(3.5, 3.5, utf8_decode(''), 1, 'C');

        $y = $pdf->GetY();
        $pdf->SetXY(5,$y+2);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(200, 6, utf8_decode('2. MEDICAMENTOS'), 1, 0, 'L',1);
        $y1 = $pdf->GetY();

        $y2 = $pdf->GetY();
        $pdf->Line(5, $y1+6, 5, $y2+35); #LINEA VERTICAL DERECHA
        $pdf->Line(205, $y1+6, 205, $y2+35);

        //$y2 = $pdf->GetY();
        $pdf->Line(50, $y1+6, 50, $y2+35); #LINA VERTICAL IZQUIERDA
        $pdf->Line(85, $y1+6, 85, $y2+35); #L
        $pdf->Line(130, $y1+6, 130, $y2+35); #LINEA VERTICAL DERECHA
        $pdf->Line(167, $y1+6, 167, $y2+35); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y1+25, 205, $y2+25); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y1+30, 205, $y2+30); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y1+35, 205, $y2+35); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y1+20, 205, $y2+20); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y1+15, 205, $y2+15); #LINEA VERTICAL DERECHA
        $pdf->Line(167, $y1+11, 205, $y2+11);

        $pdf->SetXY(5,$y+17);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(45, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(5,$y+22);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(45, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(5,$y+27);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(45, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(5,$y+32);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(45, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(50,$y+17);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(35, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(50,$y+27);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(35, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(50,$y+32);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(35, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(85,$y+17);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(45, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(85,$y+22);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(45, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(85,$y+27);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(45, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(85,$y+32);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(45, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(130,$y+17);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(37, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(130,$y+27);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(37, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(167,$y+17);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(38, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(167,$y+27);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(38, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(13,$y+12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Nombre genérico'), 0, 0, 'L');

        $pdf->SetXY(55,$y+12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Concentración'), 0, 0, 'L');

        $pdf->SetXY(92,$y+12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Forma farmacéutica'), 0, 0, 'L');

        $pdf->SetXY(130,$y+9);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(45, 6, utf8_decode('Dosis/vía de administración/'), 0, 0, 'L');

        $pdf->SetXY(130,$y+12);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(45, 6, utf8_decode('frecuencia de administración'), 0, 0, 'L');

        $pdf->SetXY(173,$y+8);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 6, utf8_decode('Cantidad prescrita'), 0, 0, 'L');

        $pdf->SetXY(170,$y+12);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 6, utf8_decode('En números  |'), 0, 0, 'L');

        $pdf->SetXY(190,$y+12);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 6, utf8_decode('En letras'), 0, 0, 'L');

        $pdf->SetXY(5,$y+39);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(200, 6, utf8_decode('Tiempo de tratamiento:'), 1, 0, 'L');
        $y1 = $pdf->GetY();

        $pdf->SetXY(92,$y+39);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 6, utf8_decode('Diagnóstico:'), 0, 0, 'L');

        $pdf->SetXY(155,$y+39);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 6, utf8_decode('Codigo CIE10:'), 0, 0, 'L');


        $y = $pdf->GetY();
        $pdf->SetXY(5,$y+8);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(50, 6, utf8_decode('3. PROFESIONAL'), 1, 0, 'L',1);
        $y1 = $pdf->GetY();

        #lineas verticales
        $y2 = $pdf->GetY();
        $pdf->Line(83, $y1+6, 83, $y2+16); #LINA VERTICAL IZQUIERDA
        $pdf->Line(60, $y1+16, 60, $y2+30); #LINEA VERTICAL DERECHA
        $pdf->Line(83, $y1+30, 83, $y2+40); #LINEA VERTICAL DERECHA
        $pdf->Line(145, $y1+6, 145, $y2+16); #LINEA VERTICAL DERECHA
        $pdf->Line(105, $y1+16, 105, $y2+30); #LINEA VERTICAL DERECHA
        $pdf->Line(170, $y1+16, 170, $y2+40); #LINEA VERTICAL DERECHA
        $pdf->Line(145, $y1+30, 145, $y2+40); #LINEA VERTICAL DERECHA
        $pdf->Line(205, $y1, 205, $y2+40);
        $pdf->Line(5, $y1+6, 5, $y2+40);

        #lineas horizontales
        $pdf->Line(5, $y1, 205, $y2);
        $pdf->Line(5, $y1+6, 205, $y2+6);
        $pdf->Line(5, $y1+11, 205, $y2+11);
        $pdf->Line(5, $y1+16, 205, $y2+16);
        $pdf->Line(5, $y1+23, 205, $y2+23);
        $pdf->Line(5, $y1+30, 205, $y2+30);
        $pdf->Line(5, $y1+35, 205, $y2+35);
        $pdf->Line(5, $y1+40, 205, $y2+40);

        $pdf->SetXY(110,$y+10);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(10, 6, utf8_decode(''), 0, 0, 'L');
        $pdf->SetXY(104,$y+9);
        $pdf->MultiCell(4, 4, utf8_decode(''), 1, 'C');
        $pdf->SetXY(77,$y+9);
        $pdf->MultiCell(4, 4, utf8_decode(''), 1, 'C');

        $pdf->SetXY(65,$y+8);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Médico'), 0, 0, 'L');

        $pdf->SetXY(85,$y+8);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Especialidad'), 0, 0, 'L');

        $pdf->SetXY(120,$y+8);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('¿Cuál?: _____________________________________________'), 0, 0, 'L');

        $pdf->SetXY(95,$y+14);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Segundo apellido'), 0, 0, 'L');

        $pdf->SetXY(160,$y+14);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Nombres'), 0, 0, 'L');

        $pdf->SetXY(25,$y+14);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Primer apellido'), 0, 0, 'L');

        $pdf->SetXY(8,$y+24);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Documento de identificación'), 0, 0, 'L');

        $pdf->SetXY(75,$y+24);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Número'), 0, 0, 'L');

        $pdf->SetXY(105,$y+23);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 6, utf8_decode('Resolución por la que se autoriza el ejercicio'), 0, 0, 'L');

        $pdf->SetXY(105,$y+26);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 6, utf8_decode('de la profesión (N° y fecha'), 0, 0, 'L');

        $pdf->SetXY(183,$y+24);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Firma'), 0, 0, 'L');

        $pdf->SetXY(20,$y+32);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Otro ¿Cuál? ___________'), 0, 0, 'L');

        $pdf->SetXY(6,$y+32);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('C.C'), 0, 0, 'L');
        $pdf->SetXY(13,$y+33);
        $pdf->MultiCell(4, 4, utf8_decode(''), 1, 'C');

        $pdf->SetXY(20,$y+38);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Institución donde labora'), 0, 0, 'L');

        $pdf->SetXY(100,$y+38);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Dirección'), 0, 0, 'L');

        $pdf->SetXY(150,$y+38);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Teléfono(s)'), 0, 0, 'L');

        $pdf->SetXY(180,$y+38);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(30, 6, utf8_decode('Ciudad'), 0, 0, 'L');

        $y = $pdf->GetY();
        $pdf->SetXY(5,$y+12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(200, 6, utf8_decode('4. ENTREGA DEL MEDICAMENTO '), 1, 0, 'L',1);
        $y1 = $pdf->GetY();

        $pdf->SetXY(60,$y+12);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 6, utf8_decode('(Para diligenciar por parte  del establecimiento farmacéutico minorista o institución prestadora de servicios de salud)'), 0, 0, 'L');

        #lineas verticales
        $y2 = $pdf->GetY();
        $pdf->Line(70, $y1+26, 70, $y2+51);
        $pdf->Line(35, $y1+26, 35, $y2+51);
        $pdf->Line(137, $y1+26, 137, $y2+51);
        $pdf->Line(105, $y1+6, 105, $y2+51);
        $pdf->Line(170, $y1+6, 170, $y2+51);
        $pdf->Line(205, $y1, 205, $y2+51);
        $pdf->Line(5, $y1+6, 5, $y2+51);

        #lineas horizontales
        $pdf->Line(5, $y1, 205, $y2);
        $pdf->Line(5, $y1+6, 205, $y2+6);
        $pdf->Line(5, $y1+11, 170, $y2+11);
        $pdf->Line(5, $y1+16, 205, $y2+16);
        $pdf->Line(5, $y1+21, 170, $y2+21);
        $pdf->Line(5, $y1+26, 205, $y2+26);
        $pdf->Line(5, $y1+31, 205, $y2+31);
        $pdf->Line(5, $y1+36, 205, $y2+36);
        $pdf->Line(5, $y1+41, 205, $y2+41);
        $pdf->Line(5, $y1+46, 205, $y2+46);
        $pdf->Line(5, $y1+51, 205, $y2+51);

        $pdf->SetXY(5,$y+18);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 6, utf8_decode('Apellidos y nombres de quién recibe el medicamento'), 0, 0, 'L');

        $pdf->SetXY(5,$y+28);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 6, utf8_decode('Apellidos y nombres de quién recibe el medicamento'), 0, 0, 'L');

        $pdf->SetXY(105,$y+18);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 6, utf8_decode('Documento de identificación'), 0, 0, 'L');

        $pdf->SetXY(105,$y+28);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 6, utf8_decode('Documento de identificación'), 0, 0, 'L');

        $pdf->SetXY(170,$y+18);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 6, utf8_decode('Firma'), 0, 0, 'L');

        $pdf->SetXY(170,$y+28);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 6, utf8_decode('Firma'), 0, 0, 'L');

        $pdf->SetXY(5,$y+38);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 6, utf8_decode('Medicamentos díspensados'), 0, 0, 'L');

        $pdf->SetXY(37,$y+38);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 6, utf8_decode('Cant. Entregada en números'), 0, 0, 'L');

        $pdf->SetXY(72,$y+38);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 6, utf8_decode('Cant. Entregada en letras'), 0, 0, 'L');

        $pdf->SetXY(110,$y+38);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 6, utf8_decode('Fecha(dd/mm/aa'), 0, 0, 'L');

        $pdf->SetXY(138,$y+38);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 6, utf8_decode('Cant. Entregada en números'), 0, 0, 'L');

        $pdf->SetXY(178,$y+38);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 6, utf8_decode('Fecha(dd/mm/aa'), 0, 0, 'L');

        $pdf->SetXY(5,$y+43);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(35, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(70,$y+43);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(35, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(35,$y+43);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(35, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(105,$y+43);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(35, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(137,$y+43);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(35, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(170,$y+43);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(35, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(5,$y+53);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(35, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(70,$y+53);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(35, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(35,$y+53);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(35, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(105,$y+53);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(35, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(137,$y+53);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(35, 5, utf8_decode(' '), 1, 0, 'L',1);

        $pdf->SetXY(170,$y+53);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->Cell(35, 5, utf8_decode(' '), 1, 0, 'L',1);

        $y = $pdf->GetY();
        $pdf->SetXY(5,$y+15);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(200, 6, utf8_decode('     Establecimiento farmacéutico minorista o institución prestadora de servicios de salud'), 1, 0, 'L');
        $y1 = $pdf->GetY();

        $pdf->SetXY(150,$y+15);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(80, 6, utf8_decode('Dirección'), 0, 0, 'L');

        #lineas verticales
        $y2 = $pdf->GetY();
        $pdf->Line(120, $y1, 120, $y2+12);
        $pdf->Line(205, $y1+6, 205, $y2+12);
        $pdf->Line(5, $y1+6, 5, $y2+12);
        $pdf->Line(205, $y1+18, 205, $y2+25);
        $pdf->Line(5, $y1+18, 5, $y2+25);

        #lineas horizontales
        $pdf->Line(5, $y1+12, 205, $y2+12);
        $pdf->Line(5, $y1+18, 205, $y2+18);
        $pdf->Line(5, $y1+25, 205, $y2+25);

        $pdf->SetXY(10,$y+33);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(80, 6, utf8_decode('Señor Usuario: esta fórmula médica con medicamentos de control especial solo tiene una vigencia de (15) dias'), 0, 0, 'L');



    }
}
