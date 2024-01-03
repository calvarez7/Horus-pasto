<?php

namespace App\Formats;

use Codedge\Fpdf\Fpdf\Fpdf;

class CertificadoPrestacionServicios extends FPDF
{
    protected $B = 0;
    protected $I = 0;
    protected $U = 0;
    protected $HREF = '';

    function WriteHTML($html)
    {
        // Intérprete de HTML
        $html = str_replace("\n", ' ', $html);
        $a = preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($a as $i => $e) {
            if ($i % 2 == 0) {
                // Text
                if ($this->HREF)
                    $this->PutLink($this->HREF, $e);
                else
                    $this->Write(5, $e);
            } else {
                // Etiqueta
                if ($e[0] == '/')
                    $this->CloseTag(strtoupper(substr($e, 1)));
                else {
                    // Extraer atributos
                    $a2 = explode(' ', $e);
                    $tag = strtoupper(array_shift($a2));
                    $attr = array();
                    foreach ($a2 as $v) {
                        if (preg_match('/([^=]*)=["\']?([^"\']*)/', $v, $a3))
                            $attr[strtoupper($a3[1])] = $a3[2];
                    }
                    $this->OpenTag($tag, $attr);
                }
            }
        }
    }

    function OpenTag($tag, $attr)
    {
        // Etiqueta de apertura
        if ($tag == 'B' || $tag == 'I' || $tag == 'U')
            $this->SetStyle($tag, true);
        if ($tag == 'A')
            $this->HREF = $attr['HREF'];
        if ($tag == 'BR')
            $this->Ln(5);
    }

    function CloseTag($tag)
    {
        // Etiqueta de cierre
        if ($tag == 'B' || $tag == 'I' || $tag == 'U')
            $this->SetStyle($tag, false);
        if ($tag == 'A')
            $this->HREF = '';
    }

    function SetStyle($tag, $enable)
    {
        // Modificar estilo y escoger la fuente correspondiente
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach (array('B', 'I', 'U') as $s) {
            if ($this->$s > 0)
                $style .= $s;
        }
        $this->SetFont('', $style);
    }

    function PutLink($URL, $txt)
    {
        // Escribir un hiper-enlace
        $this->SetTextColor(0, 0, 255);
        $this->SetStyle('U', true);
        $this->Write(5, $txt, $URL);
        $this->SetStyle('U', false);
        $this->SetTextColor(0);
    }

    function Header()
    {
        $logo1 = public_path() . "/images/descargas.png";

        $this->SetFont('Arial', 'B', 15);
        $this->SetDrawColor(255, 255, 255);
        $this->Rect(5, 5, 200, 287);
        $this->SetDrawColor(0, 0, 0);

        $this->Image($logo1, 158, 10, 40);
        $this->Ln();
    }

    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetXY(0, -15);
        $this->SetFont('Arial', 'I', 12);
        $this->SetFillColor(172, 208, 61);
        $this->SetTextColor(247, 248, 244);
        $this->MultiCell(210, 5, utf8_decode('www.sumimedical.com'), 0, 'C', true);
    }

    function body()
    {
        $this->SetXY(10, 50);
        $this->SetFont('Arial', 'B', 12);
        $this->cell(190, 5, utf8_decode("SUMIMEDICAL S.A.S"), 0, '0', 'C');
        $this->Ln();

        $this->SetXY(10, 64);
        $this->SetFont('Arial', 'B', 12);
        $this->cell(190, 5, utf8_decode("CERTIFICA QUE"), 0, '0', 'C');
        $this->Ln();

        $this->SetXY(25, 80);
        $this->SetFont('Arial', '', 12);
        $this->MultiCell(155, 5, utf8_decode('HERNANDEZ GUTIERREZ CATLEYA, identificado(a) con cédula de ciudadanía número 22.461.253,labora en esta empresa como OFTALMOLOGO, desde el día 23 de noviembre de 2017 hasta la fecha, con un contrato por prestación de servicios, con unos honorarios promedios de $ 20.000.000 (veinte millones de pesos) mensuales' . '.'), 0, 'J');
        $this->Ln();

        $this->SetXY(25, 110);
        $this->SetFont('Arial', '', 12);
        $this->MultiCell(155, 5, utf8_decode('La presente se expide a solicitud del interesado a los 16 días del mes de diciembre de 2021.' . '.'), 0, 'J');
        $this->Ln();

        $this->SetXY(25, 140);
        $this->SetFont('Arial', '', 12);
        $this->Cell(171, 5, utf8_decode('Sumimedical'), 0, 0, 'J');
        $this->Ln();

        $this->SetXY(25, 145);
        $this->SetFont('Arial', '', 12);
        $this->Cell(171, 5, utf8_decode('Nit: ' . '900033371-4'), 0, 0, 'J');
        $this->Ln();

        $this->SetXY(25, 150);
        $this->SetFont('Arial', '', 12);
        $this->Cell(171, 5, utf8_decode('Teléfono: ' . '5201040 extensión 110'), 0, 0, 'J');
        $this->Ln();
        $html = 'Correo: <a href="mailto:talento.humano@sumimedical.com">talento.humano@sumimedical.com</a>';
        $this->SetXY(25, 155);
        $this->SetFont('Arial', '', 12);
        $this->WriteHTML($html);
        $this->Ln();

        $logo2 = public_path() . "/images/sello.png";

        $this->SetXY(125, 150);
        $this->Image($logo2);
        $this->Ln();

        $this->SetXY(25, 190);
        $this->Cell(171, 5, utf8_decode('Atentamente,'), 0, 0, 'J');
        $this->Ln();

        $logo3 = public_path() . "/images/firma.png";

        $this->SetXY(25, 200);
        $this->Image($logo3);
        $this->Ln();

        $this->SetXY(25, 215);
        $this->Cell(155, 5, utf8_decode('PAOLA ISABEL FONSECA DIAZ'), 0, 0, 'J');
        $this->Ln();

        $this->SetXY(25, 220);
        $this->Cell(155, 5, utf8_decode('Directora Gestión Humana'), 0, 0, 'J');
        $this->Ln();
    }

    public function generar($cedula1)
    {
        $pdf = new CertificadoPrestacionServicios('p', 'mm', 'A4');
        // setlocale(LC_TIME, "es_ES");
        // self::$paciente = Empleado::select(
        //     DB::raw("CONCAT(Primer_Nom,' ',SegundoNom,' ',Primer_Ape,' ',Segundo_Ape) as NombreCompleto"),
        //     'Tipo_Afiliado',
        //     'Num_Doc as documento',
        //     'Tipo_Doc',
        //     'estados.id',
        //     'estados.Nombre as estado',
        //     'sedeproveedores.Nombre as IPS'
        // )
        //     ->join('estados', 'estados.id', 'pacientes.Estado_afiliado')
        //     ->join('sedeproveedores', 'sedeproveedores .id', 'pacientes.IPS')
        //     ->where('pacientes.id', $id)->first();

        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->body();
        $pdf->Output();
    }
}
