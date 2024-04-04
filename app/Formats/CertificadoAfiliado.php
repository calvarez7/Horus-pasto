<?php

namespace App\Formats;

use DNS2D;
use App\Modelos\Paciente;
use Milon\Barcode\QRcode;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Modelos\Certificado;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CertificadoAfiliado extends FPDF
{
    public static $paciente;
    public static $cotizante;
    public static $beneficiarios;
    public static $TEMPIMGLOC;
    public static $certificado;


    public function generar($id)
    {

        $pdf = new CertificadoAfiliado('p', 'mm', 'A4');
        setlocale(LC_TIME,"es_ES");
        self::$paciente = Paciente::select(
            DB::raw("CONCAT(Primer_Nom,' ',SegundoNom,' ',Primer_Ape,' ',Segundo_Ape) as NombreCompleto"),
            'Tipo_Afiliado',
            'Doc_Cotizante',
            'pacientes.Ut',
            DB::raw("CONVERT(NVARCHAR(10),Fecha_Afiliado,103) as FechaAfiliado"),
            'Num_Doc as documento',
            'municipios.Nombre as municipio_Atencion',
            'Tipo_Doc',
            'estados.id',
            'estados.Nombre as estado',
            'sedeproveedores.Nombre as IPS_sede',
            'municipios.Nombre as municipio',
        )
            ->join('estados', 'estados.id', 'pacientes.Estado_afiliado')
            ->join('sedeproveedores', 'sedeproveedores.id', 'pacientes.IPS')
            ->join('municipios', 'municipios.id', 'sedeproveedores.Municipio_id')
            ->where('pacientes.id', $id)->first();

        self::$certificado = Certificado::select(DB::raw("CONCAT('Radicado # ',id,' documento ',num_doc,' Nombre ',full_name) as aditoria"))
        ->where('num_doc', self::$paciente->documento)
        ->orderBy('id', 'desc')->first();

        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetTitle('Certificado Afiliacion');
        $pdf->SetMargins(26, 15, 26);
        $this->body($pdf);
        $pdf->Output();
    }
    public function Header()
    {
        $logo1 = public_path() . "/images/logo-redvital-1.png";
        $TEMPIMGLOC = 'tempimg.png';
        $dataURI    = "data:image/png;base64," . DNS2D::getBarcodePNG(self::$certificado->aditoria, 'QRCODE');
        $dataPieces = explode(',', $dataURI);
        $encodedImg = $dataPieces[1];
        $decodedImg = base64_decode($encodedImg);
        file_put_contents($TEMPIMGLOC, $decodedImg);
        self::$TEMPIMGLOC = $TEMPIMGLOC;
        $this->Image(self::$TEMPIMGLOC, 12, 8, 40, 40);
        $this->SetFont('Arial', 'B', 15);
        $this->SetDrawColor(255, 255, 255);
        $this->Rect(5, 5, 200, 287);
        $this->SetDrawColor(0, 0, 0);

        $this->Image($logo1, 140, 15, 50, 10);
        $this->Ln();

        $this->SetXY(10, 40);
        $this->Cell(192, 4, utf8_decode('NIT    901126938-3'), 0, 0, 'C');
        $this->Ln();
    }
    public function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
    }

    public function body($pdf)
    {

        $dia = Carbon::now()->isoFormat('D [ de] MMMM [del] YYYY');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Ln();
        $pdf->Cell(160, 4, utf8_decode("Medellin, ".$dia),0 , 0, 'C');

        $titulo = $pdf->GetY();
        $pdf->SetXY(16, $titulo+16);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->MultiCell(175, 5, utf8_decode('Certificación de afiliación al fondo Nacional de Prestaciones Sociales del Magisterio - FOMAG'), 0, 'C');
        $pdf->Ln();

        $descripcion = $pdf->GetY();
        $pdf->SetXY(16,$descripcion+4);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(175, 5, utf8_decode('La '.utf8_decode(self::$paciente->Ut).' certifica que'. ' ' .utf8_decode(self::$paciente->NombreCompleto) .' '.'identificado(a) con '.' '.self::$paciente->Tipo_Doc.' '
        .' N°' . ' ' . self::$paciente->documento . ' ' . 'su fecha de afiliación es del '.self::$paciente->FechaAfiliado.', Afiliado al municipio de '.self::$paciente->municipio_Atencion.' y registra en estado '.' '.self::$paciente->estado.' '.' como'.' '. self::$paciente->Tipo_Afiliado.' '
        .'en el Fondo Nacional de Prestaciones Sociales del Magisterio- FOMAG, con IPS primaria'.' '.self::$paciente->IPS_sede.' municipio de '.self::$paciente->municipio.'.'), 0, 'J');
        $pdf->Ln();
        if(self::$paciente->Tipo_Afiliado == 'BENEFICIARIO' || self::$paciente->Tipo_Afiliado == 'SUSTITUTO PENSIONAL' || self::$paciente->Tipo_Afiliado == 'COTIZANTE DEPENDIENTE' && self::$paciente->Doc_Cotizante != null){
            self::$cotizante = Paciente::select(
                DB::raw("CONCAT(Primer_Nom,' ',SegundoNom,' ',Primer_Ape,' ',Segundo_Ape) as NombreCompleto"),
                'Tipo_Afiliado',
                'Num_Doc as documento',
                'Parentesco',
                DB::raw("CONVERT(NVARCHAR(10),Fecha_Afiliado,103) as FechaAfiliado"),
                'Tipo_Doc',
                'estados.id',
                'estados.Nombre as estado',
                'sedeproveedores.Nombre as IPS'
            )
                ->join('estados', 'estados.id', 'pacientes.Estado_afiliado')
                ->join('sedeproveedores', 'sedeproveedores .id', 'pacientes.IPS')
                ->where('pacientes.Num_Doc', self::$paciente->Doc_Cotizante)->first();

            if(self::$cotizante != null){

                $pdf->SetX(16);
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->MultiCell(175, 5, utf8_decode('Información del Cotizante'), 0, 'C');
                $pdf->Ln();

                $y=$pdf->GetY();
                $pdf->SetX(16);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->MultiCell(25,4,utf8_decode('Tipo Identificacion'),1,'C');
                $pdf->SetXY(41,$y);
                $pdf->MultiCell(25,4,utf8_decode('Numero Identificación'),1,'C');
                $pdf->SetXY(66,$y);
                $pdf->MultiCell(50,8,utf8_decode('Nombres'),1,'C');
                $pdf->SetXY(116,$y);
                $pdf->MultiCell(25,8,utf8_decode('Parentesco'),1,'C');
                $pdf->SetXY(141,$y);
                $pdf->MultiCell(25,8,utf8_decode('Estado Actual'),1,'C');
                $pdf->SetXY(166,$y);
                $pdf->MultiCell(25,4,utf8_decode('Tipo de Afiliación'),1,'C');
                $inicio=$pdf->GetY();
                $y=$inicio;

                $pdf->SetX(16);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->MultiCell(25,4,utf8_decode(self::$cotizante->Tipo_Doc),0,'C');
                $y1=$pdf->GetY();
                $pdf->SetXY(41,$y);
                $pdf->MultiCell(25,4,utf8_decode(self::$cotizante->documento),0,'C');
                $y2=$pdf->GetY();
                $pdf->SetXY(66,$y);
                $pdf->MultiCell(50,4,utf8_decode(self::$cotizante->NombreCompleto),0,'C');
                $y3=$pdf->GetY();
                $pdf->SetXY(116,$y);
                $pdf->MultiCell(25,4,utf8_decode(self::$cotizante->Parentesco),0,'C');
                $y4=$pdf->GetY();
                $pdf->SetXY(141,$y);
                $pdf->MultiCell(25,4,utf8_decode(self::$cotizante->estado),0,'C');
                $y5=$pdf->GetY();
                $pdf->SetXY(166,$y);
                $pdf->MultiCell(25,4,utf8_decode(self::$cotizante->Tipo_Afiliado),0,'C');
                $y6=$pdf->GetY();

                $y = max($y1,$y2,$y3,$y4,$y5,$y6);

                    $pdf->Line(16, $y, 191, $y);
                    $pdf->Line(191, $inicio, 191, $y);
                    $pdf->Line(16, $inicio, 16, $y);
                    $pdf->Line(41, $inicio, 41, $y);
                    $pdf->Line(66, $inicio, 66, $y);
                    $pdf->Line(116, $inicio, 116, $y);
                    $pdf->Line(141, $inicio, 141, $y);
                    $pdf->Line(166, $inicio, 166, $y);


                $y = $pdf->GetY();
            }
            else{
                $pdf->SetX(16);
                $pdf->SetFont('Arial', '', 12);
                $pdf->MultiCell(175, 5, utf8_decode('La información de grupo familiar no se encuentra Disponible en UT Región 8. Si requiere que se adiciones su grupo familiar al certificado se debe realizar esta solicitud a través de pagina web https://www.horus-health.com/gestion ingresando por la opción TRAMITES ADMINISTRATIVOS Y DE AFILIACIONES.'), 0, 'J');
                $pdf->Ln();
                $inicio=$pdf->GetY();
                $y=$inicio;
            }
        }elseif (self::$paciente->Tipo_Afiliado == 'COTIZANTE' || self::$paciente->Tipo_Afiliado == 'COTIZANTEN FALLECIDO' || self::$paciente->Tipo_Afiliado == 'SUSTITUTO PENSIONAL' && ( empty(self::$paciente->Doc_Cotizante) == true || self::$paciente->Doc_Cotizante == null)) {
            self::$beneficiarios = Paciente::select(
                DB::raw("CONCAT(Primer_Nom,' ',SegundoNom,' ',Primer_Ape,' ',Segundo_Ape) as NombreCompleto"),
                'Tipo_Afiliado',
                'Num_Doc as documento',
                'Parentesco',
                DB::raw("CONVERT(NVARCHAR(10),Fecha_Afiliado,103) as FechaAfiliado"),
                'Tipo_Doc',
                'estados.id',
                'estados.Nombre as estado',
                'sedeproveedores.Nombre as IPS'
            )
                ->join('estados', 'estados.id', 'pacientes.Estado_afiliado')
                ->join('sedeproveedores', 'sedeproveedores .id', 'pacientes.IPS')
                ->where('pacientes.Doc_Cotizante', self::$paciente->documento)->get();
            if(count(self::$beneficiarios) == 0){
                $pdf->SetX(16);
                $pdf->SetFont('Arial', '', 12);
                $pdf->MultiCell(175, 5, utf8_decode('La información de grupo familiar no se encuentra Disponible en en UT Región 8, se debe hacer la gestión por módulo de solicitudes Horus.'), 0, 'J');
                $pdf->Ln();
                $inicio=$pdf->GetY();
                $y=$inicio;
            }else{
                $pdf->SetX(16);
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->MultiCell(175, 5, utf8_decode('Información de los Beneficiarios'), 0, 'C');
                $pdf->Ln();

                $y=$pdf->GetY();
                $pdf->SetX(16);
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->MultiCell(25,4,utf8_decode('Tipo Identificacion'),1,'C');
                $pdf->SetXY(41,$y);
                $pdf->MultiCell(25,4,utf8_decode('Numero Identificación'),1,'C');
                $pdf->SetXY(66,$y);
                $pdf->MultiCell(50,8,utf8_decode('Nombres'),1,'C');
                $pdf->SetXY(116,$y);
                $pdf->MultiCell(25,8,utf8_decode('Parentesco'),1,'C');
                $pdf->SetXY(141,$y);
                $pdf->MultiCell(25,8,utf8_decode('Estado Actual'),1,'C');
                $pdf->SetXY(166,$y);
                $pdf->MultiCell(25,4,utf8_decode('Tipo de Afiliación'),1,'C');
                $inicio=$pdf->GetY();
                $y=$inicio;

                foreach (self::$beneficiarios as $beneficiario){

                    $pdf->SetFont('Arial', 'B', 8);

                    $pdf->SetXY(16,$y);
                    $pdf->MultiCell(25,4,utf8_decode($beneficiario->Tipo_Doc),0,'C');
                    $y1=$pdf->GetY();
                    $pdf->SetXY(41,$y);
                    $pdf->MultiCell(25,4,utf8_decode($beneficiario->documento),0,'C');
                    $y2=$pdf->GetY();
                    $pdf->SetXY(66,$y);
                    $pdf->MultiCell(50,4,utf8_decode($beneficiario->NombreCompleto),0,'C');
                    $y3=$pdf->GetY();
                    $pdf->SetXY(116,$y);
                    $pdf->MultiCell(25,4,utf8_decode($beneficiario->Parentesco),0,'C');
                    $y4=$pdf->GetY();
                    $pdf->SetXY(141,$y);
                    $pdf->MultiCell(25,4,utf8_decode($beneficiario->estado),0,'C');
                    $y5=$pdf->GetY();
                    $pdf->SetXY(166,$y);
                    $pdf->MultiCell(25,4,utf8_decode($beneficiario->Tipo_Afiliado),0,'C');
                    $y6=$pdf->GetY();
                    $y = max($y1,$y2,$y3,$y4,$y5,$y6);

                    $pdf->Line(16, $y, 191, $y);
                    $pdf->Line(191, $inicio, 191, $y);
                    $pdf->Line(16, $inicio, 16, $y);
                    $pdf->Line(41, $inicio, 41, $y);
                    $pdf->Line(66, $inicio, 66, $y);
                    $pdf->Line(116, $inicio, 116, $y);
                    $pdf->Line(141, $inicio, 141, $y);
                    $pdf->Line(166, $inicio, 166, $y);
                }
            }

        }else{
                $pdf->SetX(16);
                $pdf->SetFont('Arial', '', 12);
                $pdf->MultiCell(175, 5, utf8_decode('La información de grupo familiar no se encuentra Disponible en UT Región 8. Si requiere que se adiciones su grupo familiar al certificado se debe realizar esta solicitud a través de pagina web https://www.horus-health.com/gestion ingresando por la opción TRAMITES ADMINISTRATIVOS Y DE AFILIACIONES.'), 0, 'J');
                $pdf->Ln();
                $inicio=$pdf->GetY();
                $y=$inicio;
        }
        if($y > 220){
            $pdf->AddPage();
            $y = 50;
        }

        $pdf->SetXY(16, $y+10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(170, 5, utf8_decode('Cordialmente,'), 0, 0, 'C');

        $y=$pdf->GetY();
        $logo2 = public_path() . "/images/FirmaJanier.png";
        $pdf->Image($logo2, 90, $y+8, 25, 15);
        $y=$pdf->GetY();

        $pdf->SetXY(16, $y+22);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->MultiCell(170, 5, utf8_decode('JANIER ARTURO BUSTAMANTE'), 0, 'C');

        $pdf->SetX(16);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(170, 5, utf8_decode('COORDINADOR DE SISTEMAS DE INFORMACIÓN Y AFILIACIONES'), 0, 'C');

        $pdf->SetX(16);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(170, 5, utf8_decode('Red Vital UT'), 0, 'C');
        $pdf->Ln();
    }
}
