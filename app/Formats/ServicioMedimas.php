<?php
namespace App\Formats;
use App\Modelos\Cie10paciente;
use App\Modelos\citapaciente;
use App\Modelos\Cuporden;
use App\Modelos\Orden;
use App\Modelos\Paciente;
use App\Modelos\Sedeproveedore;
use App\User;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use DNS1D;
class ServicioMedimas extends FPDF
{
    public static $data;
    public static $user;
    public static $TEMPIMGLOC;
    public static $prestador;
    public static $medico;
    public static $citaPaciente;
    public static $orden;
    public static $tipoMensaje;
    public static $cie10s;

    var $angle=0;
    public function generar($data,$tipoMensaje ="S E R V I C I O  M E D I C O")
    {
        $pdf             = new ServicioMedimas('p', 'mm', 'A4');
        self::$data      = $data;
        self::$user      = Paciente::find($data["paciente"]);
        self::$orden     = Orden::find($data["orden"]);
        self::$tipoMensaje      = $tipoMensaje;
        $TEMPIMGLOC = 'tempimg.png';
        $dataURI    = "data:image/png;base64," . DNS1D::getBarcodePNG(self::$data["orden"], 'C39');
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
        $this->SetFont('Arial','B',50);
        $this->SetTextColor(255,192,203);
        $this->RotatedText(12,134,self::$tipoMensaje,33);
        $this->SetTextColor(0,0,0);
        $citaPaciente = citapaciente::find(self::$orden->Cita_id);
        self::$medico = User::find(self::$orden->Usuario_id);
        self::$citaPaciente = $citaPaciente;
        self::$cie10s = Cie10paciente::select([
            "c.Codigo_CIE10 as Codigo"
        ])
            ->join('cie10s as c','c.id','cie10pacientes.Cie10_id')
            ->where("cie10pacientes.Citapaciente_id",$citaPaciente->id)->get()->toArray();
        $tipo = "(Consulta Medica)";
        if($citaPaciente->Tipocita_id == 1){
            $tipo = "Transcripción";
        }else{
            $tipo = "Consulta Medica";
        }
        $this->SetFont('Arial','I',10);
        $this->SetTextColor(251,44,0);
        $this->TextWithDirection(207, 40, utf8_decode($tipo), 'U');
        $this->SetTextColor(0,0,0);
        $arSedeProveedor = Sedeproveedore::where('id', self::$user->IPS)->first();
        $arOrden         = Orden::find(self::$data["orden"]);
        $Y               = 12;
        $this->SetFont('Arial', 'B', 9);
        $logo = public_path() . "/images/logo.png";
        $this->Image($logo, 8, 7, -400);
        $this->Rect(5, 5, 30, 22);
        $this->Rect(35, 5, 80, 22);
        $this->SetXY(35, 8);
        $this->Cell(80, 4, utf8_decode('SUMIMEDICAL S.A.S'), 0, 0, 'C');
        $this->SetXY(35, $Y);
        $this->Cell(80, 4, utf8_decode('NIT: 900033371 Res: 004 '), 0, 0, 'C');
        $Y = 5;
        $this->SetFont('Arial', 'B', 7);
        $this->Rect(115, 5, 88, 22);
        $this->SetXY(115, $Y);
        $this->Cell(88, 4, utf8_decode('Fecha de Autorizaciòn: ') . Carbon::parse($arOrden->Fechaorden)->format("Y-m-d"), 0, 0, 'C');
        $this->SetXY(115, $Y + 3);
        $this->Cell(88, 4, utf8_decode("Régimen: ".self::$user->tipo_categoria." / Número de Orden: " . self::$orden->id." ".(substr(self::$data["tipo"],-5) =='siPgp'?'pgp':"")), 0, 0, 'C');
        $this->SetXY(115, $Y + 6);
        $this->Cell(88, 4, utf8_decode('IPS Primaria: MEDIMAS '.strtoupper(self::$user->Mpio_Afiliado)), 0, 0, 'C');
        $this->Image(self::$TEMPIMGLOC, 125, 15, 70, 10);
        $this->SetFillColor(216, 216, 216);
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(5, 28);
        $this->Cell(134, 4, utf8_decode('Nombre Paciente'), 1, 0, 'C', 1);
        $this->Cell(10, 4, utf8_decode('Nivel'), 1, 0, 'C', 1);
        $this->Cell(8, 4, utf8_decode('Sexo'), 1, 0, 'C', 1);
        $this->Cell(20, 4, utf8_decode('Identificación'), 1, 0, 'C', 1);
        $this->Cell(8, 4, 'Edad', 1, 0, 'C', 1);
        $this->Cell(18, 4, 'Nacimiento', 1, 0, 'C', 1);
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', '', 8);
        $this->Cell(134, 4, utf8_decode(self::$user->Primer_Ape." ".self::$user->Segundo_Ape." ".self::$user->Primer_Nom." ".self::$user->SegundoNom), 1, 0, 'C');
        $this->Cell(10, 4, utf8_decode(self::$user->nivel), 1, 0, 'C');
        $this->Cell(8, 4, utf8_decode(self::$user->Sexo), 1, 0, 'C');
        $this->Cell(20, 4, utf8_decode(self::$user->Tipo_Doc." ".self::$user->Num_Doc), 1, 0, 'C');
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
        $this->SetFont('Arial', '', 6);
        $this->Cell(59, 4, utf8_decode(self::$user->Direccion_Residencia), 1, 0, 'C');
        $this->Cell(59, 4, utf8_decode(self::$user->Telefono) . " - " . utf8_decode(self::$user->Celular1), 1, 0, 'C');
        $this->Cell(40, 4, utf8_decode(self::$user->Correo1), 1, 0, 'C');
        $this->Cell(40, 4, utf8_decode(strtoupper(self::$user->Mpio_Afiliado).'-'.self::$user->Departamento), 1, 0, 'C');
            $this->Ln();
            $this->SetX(5);
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(113, 4, 'Nombre Prestador', 1, 0, 'C', 1);
            $this->Cell(20, 4, 'Telefono', 1, 0, 'C', 1);
            $this->Cell(65, 4, utf8_decode('Dirección'), 1, 0, 'C', 1);
            $this->Ln();
            $sedePrestador = Sedeproveedore::find(intval( self::$data["prestador"]));
            $this->SetX(5);
            $this->SetFont('Arial', '', 6);
            $this->Cell(113, 4, utf8_decode($sedePrestador->Nombre), 1, 0, 'L');
            $this->Cell(20, 4, utf8_decode($sedePrestador->Telefono1."-".$sedePrestador->Telefono2), 1, 0, 'C');
            $this->Cell(65, 4, utf8_decode($sedePrestador->Direccion), 1, 0, 'C');
            $this->Ln();
            $this->SetXY(5,52);
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(25, 4, utf8_decode('Diagnósticos'), 1, 0, 'C', 1);
            $this->SetFont('Arial', '', 7.5);
            $this->Cell(173, 4, "  ".(self::$cie10s?implode(', ', array_column(self::$cie10s, 'Codigo')):""), 1, 0, 'L');
            $this->SetXY(5, 59);

        $this->SetFont('Arial', 'B', 6);
        $this->Cell(30, 4, utf8_decode('Código'), 1, 0, 'C', 1);
        $this->Cell(100, 4, utf8_decode('Nombre'), 1, 0, 'C', 1);
        $this->Cell(20, 4, utf8_decode('Cantidad'), 1, 0, 'C', 1);
        $this->Cell(48, 4, utf8_decode('Observación'), 1, 0, 'C', 1);
    }
    public function Footer()
    {
        $this->SetFont('Arial','',6);
        $this->Rect(5,102,198,14);
        $this->SetXY(5,103);
        $this->MultiCell(198, 3, utf8_decode("NOTA AUDITORIA: " . (isset(self::$data["servicios"][0]['Auth_Nota']) ? self::$data["servicios"][0]['Auth_Nota'] : "")), 0, "L", 0);
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
        if(self::$citaPaciente->Tipocita_id == 1){
            $this->SetFont('Arial','B',6);

            if(self::$user->entidad_id == 2 && is_numeric(self::$citaPaciente->Medicoordeno)){
                $medicoTranscripcion = User::find(intval(self::$citaPaciente->Medicoordeno));
                if($medicoTranscripcion){
                    $this->Text(6,133,utf8_decode($medicoTranscripcion->name." ".$medicoTranscripcion->apellido."   R.M:".$medicoTranscripcion->cedula." (".$medicoTranscripcion->especialidad_medico.")"));
                    if($medicoTranscripcion->Firma){
                        if (file_exists(storage_path(substr($medicoTranscripcion->Firma, 9)))) {
                            $this->Image(storage_path(substr($medicoTranscripcion->Firma, 9)), 10, 120, 56, 11);
                        }
                    }
                }else{
                    $this->Text(10,131,self::$citaPaciente->Medicoordeno);
                }
            }else{
                $this->Text(10,131,self::$citaPaciente->Medicoordeno);
            }
            $transcriptor = User::find(self::$citaPaciente->Usuario_id);
            if (isset($transcriptor)) {
                $this->Text(143,133,utf8_decode($transcriptor->name." ".$transcriptor->apellido));
                if($transcriptor->Firma){
                    if (file_exists(storage_path(substr($transcriptor->Firma, 9)))) {
                        $this->Image(storage_path(substr($transcriptor->Firma, 9)), 143, 120, 56, 11);
                    }
                }
            }
        }else{
            if (isset(self::$medico)) {
                $this->SetFont('Arial','B',6);
                $this->Text(6,133,utf8_decode(self::$medico->name." ".self::$medico->apellido."   R.M:".self::$medico->cedula." (".self::$medico->especialidad_medico.")"));
                if(self::$medico->Firma){
                    if (file_exists(storage_path(substr(self::$medico->Firma, 9)))) {
                        $this->Image(storage_path(substr(self::$medico->Firma, 9)), 10, 121, 56, 11);
                    }
                }
            }
        }
        if(!self::$data["requiere"]){
            $this->SetFont('Arial','I',8);
            $this->SetTextColor(251,44,0);
            $this->TextWithDirection(3,50,'Valor Orden:','U');
            $this->SetTextColor(0,0,0);
            $this->TextWithDirection(3, 33,'$ '.self::$data["valor"], 'U');
        }
        $this->SetFont('Arial','I',8);
        $this->TextWithDirection(206,120,'Funcionario que Imprime:','U');
        $this->TextWithDirection(206, 87, utf8_decode(auth()->user()->name . " " . auth()->user()->apellido), 'U');
        $this->TextWithDirection(209,120,utf8_decode('Fecha Impresión:'),'U');
        $this->TextWithDirection(209, 98, date('Y-m-d'), 'U');
    }
    public function Body($pdf)
    {
        $Y = 65;
        $pdf->SetFont('Arial', '', 8);
        foreach (self::$data["cups"] as $key => $tipos) {

            foreach ($tipos as $cups) {
                foreach ($cups as $cup) {
                    $cupOrden = Cuporden::select(["c.Codigo", "c.Nombre", "cupordens.Cantidad", "cupordens.Observacionorden",'cupordens.Estado_id'])->join("cups as c", "c.id", "cupordens.Cup_id")->where('cupordens.Cup_id', $cup["cup_id"])->where('cupordens.Orden_id', self::$orden->id)->whereIn('cupordens.Estado_id',[1,7])->first();
                    if($Y > 93){
                        $Y = 65;
                        $pdf->AddPage();
                    }
                    $pdf->SetXY(5, $Y);
                    $pdf->MultiCell(30, 4, $cupOrden->Codigo, 0, 'C', 0);
                    $pdf->SetXY(35, $Y);
                    $pdf->MultiCell(100, 4, utf8_decode(strtolower($cupOrden->Nombre)), 0, 'L', 0);
                    $YN = round($pdf->GetY(), 0, PHP_ROUND_HALF_DOWN);
                    $pdf->SetXY(135, $Y);
                    $pdf->MultiCell(20, 4, $cupOrden->Cantidad, 0, 'C', 0);
                    $pdf->SetXY(155, $Y);
                    $pdf->MultiCell(48, 4, utf8_decode($cupOrden->Observacionorden), 0, 'L', 0);
                    $YO = round($pdf->GetY(), 0, PHP_ROUND_HALF_DOWN);
                    $pdf->Line(5, ($YO > $YN ? $YO : $YN), 203, ($YO > $YN ? $YO : $YN));
                    $YL = round($pdf->GetY(), 0, PHP_ROUND_HALF_DOWN);
                    $Y = $Y + (($YN > $YL ? $YN : $YL) - $Y);
                }
            }

            $pdf->SetFont('Arial','I',8);
            $pdf->SetTextColor(251,44,0);
            $pdf->TextWithDirection(3,130,$key,'U');
            $pdf->SetTextColor(0,0,0);
            if(array_key_last (self::$data["cups"]) != $key){
                $Y = 65;
                $pdf->AddPage();
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
    function RotatedText($x, $y, $txt, $angle)
    {
        //Text rotated around its origin
        $this->Rotate($angle,$x,$y);
        $this->Text($x,$y,$txt);
        $this->Rotate(0);
    }
    function Rotate($angle,$x=-1,$y=-1)
    {
        if($x==-1)
            $x=$this->x;
        if($y==-1)
            $y=$this->y;
        if($this->angle!=0)
            $this->_out('Q');
        $this->angle=$angle;
        if($angle!=0)
        {
            $angle*=M_PI/180;
            $c=cos($angle);
            $s=sin($angle);
            $cx=$x*$this->k;
            $cy=($this->h-$y)*$this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
        }
    }
    function _endpage()
    {
        if($this->angle!=0)
        {
            $this->angle=0;
            $this->_out('Q');
        }
        parent::_endpage();
    }
}
