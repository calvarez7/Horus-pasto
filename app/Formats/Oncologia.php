<?php

namespace App\Formats;

use App\Modelos\agendauser;
use App\Modelos\Autorizacion;
use App\Modelos\citapaciente;
use App\Modelos\Codesumi;
use App\Modelos\Detallearticulo;
use App\Modelos\Examenfisico;
use App\Modelos\Orden;
use App\Modelos\Paciente;
use App\Modelos\Us;
use App\User;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use \Milon\Barcode\DNS1D;

class Oncologia extends FPDF
{

    public static $user;
    public static $orden;
    public static $TEMPIMGLOC;
    public static $notaAuditoria;


    public function generar($orden)
    {
        $pdf             = new Oncologia('p', 'mm', 'A4');
        self::$orden     = $orden;
        $citaP           = citapaciente::find(self::$orden->Cita_id);
        self::$user      = Paciente::find($citaP->Paciente_id);

        $TEMPIMGLOC = 'tempimg.png';
        $dataURI    = "data:image/png;base64," . DNS1D::getBarcodePNG(self::$orden->id, 'C39');
        $dataPieces = explode(',', $dataURI);
        $encodedImg = $dataPieces[1];
        $decodedImg = base64_decode($encodedImg);
        file_put_contents($TEMPIMGLOC, $decodedImg);
        self::$TEMPIMGLOC = $TEMPIMGLOC;

        $pdf->AliasNbPages();
        $pdf->AddPage();
        $this->Body($pdf);
        $pdf->Output();
    }

    public function Header()
    {
        $Y = 12;
        $this->SetFont('Arial', 'B', 9);
        $logo = public_path() . "/images/logoVictoriana.png";
        $this->Image($logo, 7, 7, -550);
        $this->Rect(5, 5, 35, 22);
        $this->Rect(40, 5, 75, 22);
        $this->SetXY(40, 8);
        $this->Cell(75, 4, utf8_decode('CLINICA VICTORIANA'), 0, 0, 'C');
        $this->SetXY(40, $Y);
        $this->Cell(75, 4, utf8_decode('NIT: 900033371 Res: 004 '), 0, 0, 'C');
        $this->SetXY(40, $Y + 4);
        $this->Cell(75, 4, utf8_decode('Carrera 49 # 58-19'), 0, 0, 'C');
        $this->SetXY(40, $Y + 8);
        $this->Cell(75, 4, utf8_decode('Telefono: 4481604'), 0, 0, 'C');
        $this->SetXY(40, $Y + 12);

        $Y = 5;
        $this->SetFont('Arial', 'B', 7);
        $this->Rect(115, 5, 88, 22);
        $this->SetXY(115, $Y);
        $this->Cell(88, 4, utf8_decode('Fecha de Autorizaciòn: ') . Carbon::parse(self::$orden->updated_at)->format("Y-m-d"), 0, 0, 'C');
        $this->SetXY(115, $Y + 3);
        $this->Cell(88, 4, utf8_decode("Tipo: Medicamentos(Oncologia) / Nùmero de Autorizaciòn: " . self::$orden->id), 0, 0, 'C');
        $this->SetXY(115, $Y + 6);
        $this->Cell(88, 4, utf8_decode('Esquema: ' . self::$orden->scheme_name.' | Ciclo: '.self::$orden->paginacion.' | Dia: '.self::$orden->day), 0, 0, 'C');
        $this->Image(self::$TEMPIMGLOC, 125, 15, 70, 10);

        $this->SetFillColor(216, 216, 216);
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(5, 28);
        $this->Cell(114, 4, utf8_decode('Nombre Paciente'), 1, 0, 'C', 1);

        $this->Cell(10, 4, utf8_decode('ISC'), 1, 0, 'C', 1);
        $this->Cell(10, 4, utf8_decode('Talla'), 1, 0, 'C', 1);
        $this->Cell(10, 4, utf8_decode('Peso'), 1, 0, 'C', 1);


        $this->Cell(8, 4, utf8_decode('Sexo'), 1, 0, 'C', 1);
        $this->Cell(20, 4, utf8_decode('Identificación'), 1, 0, 'C', 1);
        $this->Cell(8, 4, 'Edad', 1, 0, 'C', 1);
        $this->Cell(18, 4, 'Nacimiento', 1, 0, 'C', 1);
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', '', 8);
        $this->Cell(114, 4, utf8_decode(self::$user->Primer_Ape." ".self::$user->Segundo_Ape." ".self::$user->Primer_Nom." ".self::$user->SegundoNom), 1, 0, 'C');

        $examenFisico = Examenfisico::where('Citapaciente_id',self::$orden->Cita_id)->first();

        if($examenFisico){
            $this->Cell(10, 4, $examenFisico->ISC, 1, 0, 'C');
            $this->Cell(10, 4, $examenFisico->Talla, 1, 0, 'C');
            $this->Cell(10, 4, $examenFisico->Peso, 1, 0, 'C');
        }else{
            $this->Cell(10, 4, "", 1, 0, 'C');
            $this->Cell(10, 4, "", 1, 0, 'C');
            $this->Cell(10, 4, "", 1, 0, 'C');
        }

        $this->SetFont('Arial', '', 7.5);
        $this->Cell(8, 4, utf8_decode(self::$user->Sexo), 1, 0, 'C');
        $this->Cell(20, 4, utf8_decode(self::$user->Num_Doc), 1, 0, 'C');
        $this->Cell(8, 4, self::$user->Edad_Cumplida, 1, 0, 'C');
        $this->Cell(18, 4, substr(self::$user->Fecha_Naci,0,10), 1, 0, 'C');
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(59, 4, 'Direccion', 1, 0, 'C', 1);
        $this->Cell(59, 4, 'Telefono', 1, 0, 'C', 1);
        $this->Cell(40, 4, 'Correo', 1, 0, 'C', 1);
        $this->Cell(40, 4, 'Municipio', 1, 0, 'C', 1);
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', '', 7.5);
        $this->Cell(59, 4, utf8_decode(self::$user->Direccion_Residencia), 1, 0, 'C');
        $this->Cell(59, 4, utf8_decode(self::$user->Telefono) . " - " . utf8_decode(self::$user->Celular1), 1, 0, 'C');
        $this->Cell(40, 4, utf8_decode(self::$user->Correo1), 1, 0, 'C');
        $this->Cell(40, 4, 'Antioquia-Medellin', 1, 0, 'C');
        $this->Ln();
        $this->SetXY(5, 46);
        $this->SetFont('Arial', 'B', 6);
        $this->Cell(15, 4, utf8_decode('Código'), 1, 0, 'C', 1);
        $this->Cell(55, 4, utf8_decode('Nombre'), 1, 0, 'C', 1);
        $this->Cell(12, 4, utf8_decode('Via Admin'), 1, 0, 'C', 1);
        $this->Cell(30, 4, utf8_decode('Dosificación'), 1, 0, 'C', 1);
        $this->Cell(13, 4, utf8_decode('Duración'), 1, 0, 'C', 1);
        $this->Cell(15, 4, utf8_decode('Dosis Totales'), 1, 0, 'C', 1);
        $this->Cell(23, 4, utf8_decode('No Total a Dispensar'), 1, 0, 'C', 1);
        $this->Cell(35, 4, utf8_decode('Observación'), 1, 0, 'C', 1);

    }

    public function Footer()
    {
        $this->SetFont('Arial','',6);

        $this->Rect(5,102,198,14);
        $this->SetXY(5,103);
        $this->MultiCell(198, 3, utf8_decode("NOTA AUDITORIA: " .self::$notaAuditoria), 0, "L", 0);

        $this->SetFont('Arial','B',5);
        $this->Rect(5,99.5,198,3);
        $this->Text(10,101.5,utf8_decode('IMPORTANTE: '));
        $this->SetFont('Arial','',4.8);
        $this->Text(24,101.5,utf8_decode('AUTORIZACION VALIDA SOLAMENTE EN LOS 60 DIAS DESPUES A LA FECHA DE SU EXPEDICION, UNA VEZ CUMPLIDO DICHO PLAZO NO HAY RESPOABILIDAD DE SUMIMEDICAL - RED VITAL. (Resolucion 4331 de 2012) .'));

        $this->SetFont('Arial','B',8);
        $this->Text(6,119,utf8_decode('Firma del Medico que Ordena'));
        $this->Text(72,119,utf8_decode('Firma del Usuario'));
        $this->Text(138,119,utf8_decode('Firma de quien Transcribe'));

        $this->Rect(5,116,66,18);

        //$this->Rect(10,121,56,11);


        $this->Rect(71,116,66,18);

        //$this->Rect(75,121,56,11);


        $this->Rect(136.8,116,66.1,18);

        //$this->Rect(143,121,56,11);
        $agendaUser = citapaciente::select([
            'u.Firma',
            'cita_paciente.Tipocita_id as tipo',
            'cita_paciente.Medicoordeno'
        ])
            ->join('users as u','u.id','cita_paciente.Usuario_id')
            ->where('cita_paciente.id',self::$orden->Cita_id)
            ->first();

        if ($agendaUser["tipo"] != 1) {
        if (file_exists(storage_path(substr($agendaUser["Firma"], 9)))) {
                $this->Image(storage_path(substr($agendaUser["Firma"], 9)), 10, 121, 56, 11);
            }
        }else{
        $this->SetFont('Arial','',7);
        $this->Text(6,125,utf8_decode($agendaUser["Medicoordeno"]));
        }

        $this->SetFont('Arial','I',8);
        $this->TextWithDirection(206,120,'Funcionario que Imprime:','U');
        $this->TextWithDirection(206, 87, utf8_decode(auth()->user()->name . " " . auth()->user()->apellido), 'U');

        $this->TextWithDirection(209,120,utf8_decode('Fecha Impresión:'),'U');
        $this->TextWithDirection(209, 98, date('Y-m-d'), 'U');
    }

    public function Body($pdf)
    {
        $Y = 50;
        $X = 2;
        $pdf->SetFont('Arial', '', 6.5);
        $idAuditor = null;

        $codeSumis = Codesumi::select([
            'codesumis.id',
            'codesumis.Codigo as codigo',
            'codesumis.Nombre as nombre',
            'da.Cantidadpormedico as cantidad',
            'da.Observacion as observacion',
            'da.Via',
            'da.Cantidadosis',
            'da.Unidadmedida',
            'da.Frecuencia',
            'da.Unidadtiempo',
            'da.Duracion',
            'da.Cantidadmensual',
            'da.Cantidadpormedico',
            'da.id as codigo'
        ])
            ->join('detaarticulordens as da','da.codesumi_id','codesumis.id')
            ->where('da.Orden_id',self::$orden->id)
            ->get();
        foreach ($codeSumis as $codeSumi) {
            $autorizacion = Autorizacion::where('Articulorden_id',$codeSumi->codigo)->first();
            self::$notaAuditoria = $autorizacion["Nota"];
            $idAuditor = $autorizacion["Usuario_id"];

            if ($Y  > 93) {
                $Y = 50;
                $pdf->AddPage();
            }
            $pdf->SetXY(5, $Y);

            $pdf->MultiCell(15, 4, $codeSumi->codigo, 1, 'C', 0);

            $pdf->SetXY(20, $Y);
            $pdf->MultiCell(55, 4, utf8_decode(strtolower($codeSumi->nombre)), 1, 'L', 0);
            $YN = round($pdf->GetY(), 0, PHP_ROUND_HALF_DOWN);

            $pdf->SetXY(75, $Y);
            $pdf->MultiCell(12, 4, utf8_decode($codeSumi->Via), 1, 'L', 0);

            $pdf->SetXY(87, $Y);
            $pdf->MultiCell(30, 4, utf8_decode(intval($codeSumi->Cantidadosis)." ".$codeSumi->Unidadmedida." ".strtolower($codeSumi->Unidadtiempo)), 1, 'L', 0);

            $pdf->SetXY(117, $Y);
            $pdf->MultiCell(13, 4, utf8_decode($codeSumi->Duracion." días"), 1, 'L', 0);

            $pdf->SetXY(130, $Y);
            $pdf->MultiCell(15, 4,intval($codeSumi->Cantidadmensual)." ".$codeSumi->Unidadmedida, 1, 'C', 0);

            $pdf->SetXY(145, $Y);
            $pdf->MultiCell(23, 4,intval($codeSumi->Cantidadpormedico)." ".$codeSumi->Unidadmedida, 1, 'C', 0);

            $pdf->SetXY(168, $Y);
            $pdf->MultiCell(35, 4, utf8_decode(strtolower($codeSumi->Observacion)), 1, 'L', 0);
            $YO = round($pdf->GetY(), 0, PHP_ROUND_HALF_DOWN);

            $pdf->Line(5, ($YO > $YN ? $YO : $YN), 203, ($YO > $YN ? $YO : $YN));
            $YL = round($pdf->GetY(), 0, PHP_ROUND_HALF_DOWN);

            $Y = $Y + (($YN > $YL ? $YN : $YL) - $Y);
        }

        $userAuditor = User::find($idAuditor);
        $firmaAuditor = $userAuditor['Firma'];
        if ($firmaAuditor) {
            if (file_exists(storage_path(substr($firmaAuditor, 9)))) {
                $this->Image(storage_path(substr($firmaAuditor, 9)), 143, 121, 56, 11);
            }
        }

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

    public function  calcularDosis($idCodesumi,$cantidad){
        $detalleArticulo = Detallearticulo::where('Codesumi_id',$idCodesumi)->first();
        $concentracion = 1;
        if($detalleArticulo->Concentracion && is_numeric($detalleArticulo->Concentracion)){
            $concentracion = $detalleArticulo->Concentracion;
        }
        $valor = round($cantidad)/$concentracion;
        return ceil($valor);
    }

}
