<?php
namespace App\Formats;
use App\Modelos\Cie10paciente;
use App\Modelos\citapaciente;
use App\Modelos\Cup;
use App\Modelos\Cuporden;
use App\Modelos\Departamento;
use App\Modelos\Municipio;
use App\Modelos\Orden;
use App\Modelos\Paciente;
use App\Modelos\Prestadore;
use App\Modelos\Sedeproveedore;
use App\User;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use DNS1D;


class Servicio extends FPDF
{
    public static $data;
    public static $user;
    public static $TEMPIMGLOC;
    public static $prestador;
    public static $medico;
    public static $citaPaciente;
    public static $orden;
    public static $tipoMensaje;
    public static $ubicacion;
    public static $cie10s;
    public static $servicios;

    var $angle=0;
    public function generar($data,$sendEmail,$tipoMensaje ="S E R V I C I O  M E D I C O")
    {
        $pdf             = new Servicio('p', 'mm', 'A4');
        self::$data      = $data;
//        self::$user      = Paciente::where('Num_Doc', $data["identificacion"])->where('Estado_Afiliado',1)->first();
        self::$tipoMensaje      = $tipoMensaje;
        self::$orden     = Orden::find($data['servicios'][0]['Orden_id']);
        self::$user      = Paciente::join('cita_paciente as cp','pacientes.id','cp.Paciente_id')->where('cp.id',self::$orden->Cita_id)->where('pacientes.Estado_Afiliado',1)->first();
        self::$ubicacion = $data['datos'];
        self::$citaPaciente = citapaciente::find(self::$orden->Cita_id);
        //        self::$servicios = Cuporden::join('cups as c','cupordens.Cup_id','c.id')->whereIn('cupordens.id',$data["servicios"])->where('cupordens.Orden_id',self::$orden->id)->get();
//        dd(self::$servicios);
//        if(self::$user->entidad_id == 1 || self::$user->entidad_id == 3 || self::$user->entidad_id == 5){
//            self::$prestador = Sedeproveedore::select(
//                "Sedeproveedores.*",
//                "presta.Nit as nit",
//                "mp.Nombre as municipioPrerstador",
//                "dp.Nombre as departamentoPrestador")
//                ->join('Prestadores as presta', 'Sedeproveedores.Prestador_id', 'presta.id')
//                ->leftJoin('Municipios as mp', 'Sedeproveedores.Municipio_id', 'mp.id')
//                ->leftJoin('departamentos as dp', 'mp.Departamento_id', 'dp.id')
//                ->where('Sedeproveedores.Nombre', '=', $data["servicios"][0]["ubicacion"])->first();
//        }
        $TEMPIMGLOC = 'tempimg.png';
         $dataURI    = "data:image/png;base64," . DNS1D::getBarcodePNG(self::$orden->id, 'C39');
         $dataPieces = explode(',', $dataURI);
         $encodedImg = $dataPieces[1];
         $decodedImg = base64_decode($encodedImg);
         file_put_contents($TEMPIMGLOC, $decodedImg);
        self::$TEMPIMGLOC = $TEMPIMGLOC;
        $pdf->AliasNbPages();
        $pdf->AddPage();
//        if(self::$user->entidad_id == 1 || self::$user->entidad_id == 3 || self::$user->entidad_id == 5){

        switch (intval(self::$user->entidad_id)){
            case 7:
                $this->bodyUTAntioquia($pdf);
                break;
            default:
                $this->Body($pdf);
                break;
        }
//        }elseif(self::$user->entidad_id == 2 && isset($data["servicios"][0]["Estado_id"])){
//            $this->BodyEntidad($pdf);
//        }
       if($sendEmail){
           $email = Mail::send('send_mail', $data, function ($message) use ($data,$pdf,$sendEmail) {
               $message->to([$sendEmail]);
               $message->subject(self::$user->Num_Doc . " " . self::$user->Primer_Nom . " " . self::$user->Primer_Ape);
               $message->attachData($pdf->Output("S"), 'Orden Sumimedical' . ' ' .self::$user->Num_Doc . ' ' . self::$user->Primer_Nom . " " . self::$user->Primer_Ape. '.pdf', [
                   'mime' => 'application/pdf',
               ]);
           });
       }
        $pdf->Output();
    }
    public function Header()
    {
        switch (intval(self::$user->entidad_id)){
            case 7:
                $this->headerUTAntioquia();
                break;
            default:
                $this->headerSumimedical();
                break;
        }

    }
    public function Footer()
    {
        switch (intval(self::$user->entidad_id)){
            case 7:
                $this->footerUTAntioquia();
                break;
            default:
                $this->footerSumimedical();
                break;
        }


    }
    public function Body($pdf)
    {
//        dd(self::$data["servicios"]);
        $Y = 65;
        $X = 2;
        $pdf->SetFont('Arial', '', 8);
        foreach (self::$data["servicios"] as $key => $servicio) {
            //if(intval($servicio["estado_id"]) != 37 && intval($servicio["estado_id"]) != 36 ){
            if ($Y  > 93) {
                $Y = 65;
                $pdf->AddPage();
            }
            $pdf->SetXY(5, $Y);
            $pdf->MultiCell(30, 4, $servicio["Codigo"], 0, 'C', 0);
            $pdf->SetXY(35, $Y);
//            if(isset($servicio["descripcion"])){
//                $pdf->MultiCell(100, 4, utf8_decode(strtolower($servicio["descripcion"])), 0, 'L', 0);
//                $YN = round($pdf->GetY(), 0, PHP_ROUND_HALF_DOWN);
//            }else{
                $pdf->MultiCell(100, 4, utf8_decode(strtolower($servicio["Nombre"])), 0, 'L', 0);
                $YN = round($pdf->GetY(), 0, PHP_ROUND_HALF_DOWN);
//            }
            $pdf->SetXY(135, $Y);
            $pdf->MultiCell(20, 4, $servicio['Cantidad'], 0, 'C', 0);
            $pdf->SetXY(155, $Y);
            $pdf->MultiCell(48, 4, utf8_decode($servicio["Observacionorden"]), 0, 'L', 0);
            $YO = round($pdf->GetY(), 0, PHP_ROUND_HALF_DOWN);
            $pdf->Line(5, ($YO > $YN ? $YO : $YN), 203, ($YO > $YN ? $YO : $YN));
            $YL = round($pdf->GetY(), 0, PHP_ROUND_HALF_DOWN);
            $Y = $Y + (($YN > $YL ? $YN : $YL) - $Y);
        }
        //}
    }

    public function bodyUTAntioquia($pdf){
        //        dd(self::$data["servicios"]);
        $Y = 65;
        $X = 2;
        $pdf->SetFont('Arial', '', 8);
        foreach (self::$data["servicios"] as $key => $servicio) {
            //if(intval($servicio["estado_id"]) != 37 && intval($servicio["estado_id"]) != 36 ){
            if ($Y  > 93) {
                $Y = 65;
                $pdf->AddPage();
            }
            $pdf->SetXY(5, $Y);
            $pdf->MultiCell(30, 4,substr(self::$orden->Fechaorden,0,10), 0, 'C', 0);
            $pdf->SetXY(35, $Y);
//            if(isset($servicio["descripcion"])){
//                $pdf->MultiCell(100, 4, utf8_decode(strtolower($servicio["descripcion"])), 0, 'L', 0);
//                $YN = round($pdf->GetY(), 0, PHP_ROUND_HALF_DOWN);
//            }else{
            $pdf->MultiCell(100, 4, utf8_decode(strtolower($servicio["Nombre"])), 0, 'L', 0);
            $YN = round($pdf->GetY(), 0, PHP_ROUND_HALF_DOWN);
//            }
            $pdf->SetXY(135, $Y);
            $pdf->MultiCell(20, 4, $servicio['Cantidad'], 0, 'C', 0);
            $pdf->SetXY(155, $Y);
            $pdf->MultiCell(48, 4, utf8_decode($servicio["Observacionorden"]), 0, 'L', 0);
            $YO = round($pdf->GetY(), 0, PHP_ROUND_HALF_DOWN);
            $pdf->Line(5, ($YO > $YN ? $YO : $YN), 203, ($YO > $YN ? $YO : $YN));
            $YL = round($pdf->GetY(), 0, PHP_ROUND_HALF_DOWN);
            $Y = $Y + (($YN > $YL ? $YN : $YL) - $Y);
        }
        //}
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

    public function headerSumimedical(){
        $this->SetFont('Arial','B',50);
        $this->SetTextColor(255,192,203);
        $this->RotatedText(12,134,self::$tipoMensaje,33);
        $this->SetTextColor(0,0,0);
        self::$medico = User::find(self::$orden->Usuario_id);
        self::$cie10s = Cie10paciente::select([
            "c.Codigo_CIE10 as Codigo"
        ])
            ->join('cie10s as c','c.id','cie10pacientes.Cie10_id')
            ->where("cie10pacientes.Citapaciente_id",self::$citaPaciente->id)->get()->toArray();
        $tipo = "(Consulta Medica)";
        if(self::$citaPaciente->Tipocita_id == 1){
            $tipo = "Transcripción";
        }else{
            $tipo = "Consulta Medica";
        }
        $this->SetFont('Arial','I',10);
        $this->SetTextColor(251,44,0);
        $this->TextWithDirection(207, 40, utf8_decode($tipo), 'U');
        $this->SetTextColor(0,0,0);
        $arSedeProveedor = Sedeproveedore::where('id', self::$user->IPS)->first();
        $this->Image(self::$TEMPIMGLOC, 125, 15, 70, 10);
        $Y               = 12;
        $this->SetFont('Arial', 'B', 11);
        $logo = public_path() . "/images/logo.png";
        $this->Image($logo, 8, 7, -400);
        $this->Rect(5, 5, 30, 22);
        $this->Rect(35, 5, 80, 22);
        $this->SetXY(35, 12);
        $this->Cell(80, 4, utf8_decode('SUMIMEDICAL S.A.S'), 0, 0, 'C');
        $this->SetXY(35, $Y+4);
        $this->Cell(80, 4, utf8_decode('NIT: 900033371 Res: 004 '), 0, 0, 'C');
        $this->SetXY(35, $Y + 4);
        $this->SetFont('Arial','B',5);
        $Y = 5;
        $this->SetFont('Arial', 'B', 7);
        $this->Rect(115, 5, 88, 22);
        $this->SetXY(115, $Y);
        $this->Cell(88, 4, utf8_decode('Fecha de Autorizaciòn: ') . self::$data["servicios"][0]['fecha_orden'], 0, 0, 'C');
        $this->SetXY(115, $Y + 3);
        $this->SetFont('Arial', 'B', 6);
        $this->Cell(88, 4, utf8_decode("Régimen: Especial / Número de Orden: " . self::$orden->id." / Contrato: ". self::$user->Ut), 0, 0, 'C');
        $this->SetXY(115, $Y + 6);
        $this->SetFont('Arial', 'B', 6);
        $this->Cell(88, 4, utf8_decode('IPS Primaria: ' . $arSedeProveedor->Nombre), 0, 0, 'C');
        $this->SetFillColor(216, 216, 216);
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(5, 28);
        $this->Cell(144, 4, utf8_decode('Nombre Paciente'), 1, 0, 'C', 1);
        $this->Cell(8, 4, utf8_decode('Sexo'), 1, 0, 'C', 1);
        $this->Cell(20, 4, utf8_decode('Identificación'), 1, 0, 'C', 1);
        $this->Cell(8, 4, 'Edad', 1, 0, 'C', 1);
        $this->Cell(18, 4, 'Nacimiento', 1, 0, 'C', 1);
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', '', 8);
        $this->Cell(144, 4, utf8_decode(self::$user->Primer_Ape." ".self::$user->Segundo_Ape." ".self::$user->Primer_Nom." ".self::$user->SegundoNom), 1, 0, 'C');
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
        $this->Cell(59, 4, utf8_decode(substr(self::$user->Direccion_Residencia, 0, 38)), 1, 0, 'C');
        $this->Cell(59, 4, utf8_decode(self::$user->Telefono) . " - " . utf8_decode(self::$user->Celular1), 1, 0, 'C');
        $this->Cell(40, 4, utf8_decode(self::$user->Correo1), 1, 0, 'C');
        $this->Cell(40, 4, 'Antioquia-Medellin', 1, 0, 'C');
//        if(self::$user->entidad_id == 1 || self::$user->entidad_id == 3 || self::$user->entidad_id == 5) {
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(133, 4, 'Nombre Prestador', 1, 0, 'C', 1);
        $this->Cell(65, 4, utf8_decode('Dirección'), 1, 0, 'C', 1);
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 6);
        if(isset(self::$ubicacion->Nombre)){
            $this->Cell(133, 4, utf8_decode(self::$ubicacion->Nombre), 1, 0, 'L');
            $this->Cell(65, 4, utf8_decode(self::$ubicacion->Direccion), 1, 0, 'C');
            $this->Ln();

        }else{
            $this->Cell(133, 4, utf8_decode(""), 1, 0, 'L');
            $this->Cell(65, 4, utf8_decode(""), 1, 0, 'C');
            $this->Ln();
        }
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(39.6, 4, utf8_decode('NIT'), 1, 0, 'C', 1);
        $this->Cell(39.6, 4, 'Telefono', 1, 0, 'C', 1);
        $this->Cell(39.6, 4, utf8_decode('Cod Habilitaciòn'), 1, 0, 'C', 1);
        $this->Cell(39.6, 4, 'Municipio', 1, 0, 'C', 1);
        $this->Cell(39.6, 4, 'Diagnostico DX', 1, 0, 'C', 1);
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', '', 7.5);
        if(isset(self::$ubicacion->Nombre)){
            $prestador = Prestadore::find(self::$ubicacion->Prestador_id);
            $municipio = Municipio::find(self::$ubicacion->Municipio_id);
            $departamento = Departamento::find($municipio->Departamento_id);
            $this->Cell(39.6, 4, utf8_decode($prestador->Nit), 1, 0, 'C');
            // if(self::$user->entidad_id == 3){
            //     $this->Cell(39.6, 4, "01 800 041 3704", 1, 0, 'C');
            // }else{
            // }
            $this->Cell(39.6, 4, utf8_decode(self::$ubicacion->Telefono1), 1, 0, 'C');
            $this->Cell(39.6, 4, utf8_decode(self::$ubicacion->Codigo_habilitacion), 1, 0, 'C');
            $this->Cell(39.6, 4, utf8_decode($municipio->Nombre.'-'.$departamento->Nombre), 1, 0, 'C');
        }else{
            $this->Cell(39.6, 4, utf8_decode(""), 1, 0, 'C');
            $this->Cell(39.6, 4, utf8_decode(""), 1, 0, 'C');
            $this->Cell(39.6, 4, utf8_decode(""), 1, 0, 'C');
            $this->Cell(39.6, 4, utf8_decode(""), 1, 0, 'C');
        }
        $this->Cell(39.6, 4, (self::$cie10s?implode(', ', array_column(self::$cie10s, 'Codigo')):""), 1, 0, 'C');
        $this->SetFillColor(216, 216, 216);
        $this->SetXY(5, 61);

        $this->SetFont('Arial', 'B', 6);
        $this->Cell(30, 4, utf8_decode('Código'), 1, 0, 'C', 1);
        $this->Cell(100, 4, utf8_decode('Nombre'), 1, 0, 'C', 1);
        $this->Cell(20, 4, utf8_decode('Cantidad'), 1, 0, 'C', 1);
        $this->Cell(48, 4, utf8_decode('Observación'), 1, 0, 'C', 1);
    }

    public function headerUTAntioquia(){
        $Y = 12;
        $this->SetFont('Arial', 'B', 11);
        $logo = public_path() . "/images/logo.png";
        $this->Image($logo, 12, 5, -300);
//        $this->Rect(5, 5, 30, 22);
//        $this->Rect(35, 5, 80, 22);
        $this->SetXY(35, 12);
        $this->Cell(168, 4, utf8_decode('SUMIMEDICAL S.A.S'), 0, 0, 'C');
        $this->SetXY(35, $Y+4);
        $this->Cell(168, 4, utf8_decode('NIT: 900033371 Res: 004 '), 0, 0, 'C');
        $this->SetXY(35, $Y + 4);
        $this->SetFont('Arial','B',5);
        $Y = 5;
        $this->SetFont('Arial', 'B', 7);
//        $this->Rect(115, 5, 88, 22);
        $this->SetXY(115, $Y);
        $this->Cell(88, 4, utf8_decode(""), 0, 0, 'C');
        $this->SetXY(115, $Y + 3);
        $this->SetFont('Arial', 'B', 6);
        $this->Cell(88, 4, utf8_decode(""), 0, 0, 'C');
        $this->SetXY(115, $Y + 6);
        $this->SetFont('Arial', 'B', 6);
        $this->Cell(88, 4, utf8_decode(''), 0, 0, 'C');
        $this->SetFillColor(216, 216, 216);
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(5, 24);
        $this->Cell(198, 4, utf8_decode("Orden Medica"), 0, 0, 'R');
        $this->SetXY(5, 28);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(144, 4, utf8_decode('Nombre Paciente'), 1, 0, 'C', 1);
        $this->Cell(54, 4, utf8_decode('Identificación'), 1, 0, 'C', 1);
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', '', 8);
        $this->Cell(144, 4, utf8_decode(self::$user->Primer_Ape." ".self::$user->Segundo_Ape." ".self::$user->Primer_Nom." ".self::$user->SegundoNom), 1, 0, 'C');
        $this->Cell(54, 4, utf8_decode(self::$user->Tipo_Doc." ".self::$user->Num_Doc), 1, 0, 'C');
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(66, 4, 'Regimen y Programa', 1, 0, 'C', 1);
        $this->Cell(66, 4, 'Fecha Nacimiento', 1, 0, 'C', 1);
        $this->Cell(66, 4, utf8_decode('Genero'), 1, 0, 'C', 1);

        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 4, utf8_decode('Régimen Especial/Universidad de Antioquia'), 1, 0, 'C');
        $this->Cell(66, 4, substr(self::$user->Fecha_Naci,0,10), 1, 0, 'C');
        $this->Cell(66, 4, utf8_decode((strtoupper(self::$user->Sexo) === 'F'?'Femenino':'Masculino')), 1, 0, 'C');



        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(66, 4, 'Edad', 1, 0, 'C', 1);
        $this->Cell(66, 4, 'Direccion', 1, 0, 'C', 1);
        $this->Cell(66, 4, 'Telefono', 1, 0, 'C', 1);

        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', '', 8);
        $this->Cell(66, 4, utf8_decode(self::$user->Edad_Cumplida.' años'), 1, 0, 'C');
        $this->Cell(66, 4, utf8_decode(substr(self::$user->Direccion_Residencia, 0, 38)), 1, 0, 'C');
        $this->Cell(66, 4, utf8_decode(self::$user->Telefono) . " - " . utf8_decode(self::$user->Celular1), 1, 0, 'C');

//        if(self::$user->entidad_id == 1 || self::$user->entidad_id == 3 || self::$user->entidad_id == 5) {
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', 'B', 8);

        $this->Cell(99, 4, 'Correo', 1, 0, 'C', 1);
        $this->Cell(99, 4, 'Municipio', 1, 0, 'C', 1);
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', '', 8);
        $this->Cell(99, 4, utf8_decode(self::$user->Correo1), 1, 0, 'C');
        $this->Cell(99, 4, 'Antioquia-Medellin', 1, 0, 'C');
        $this->Ln();
        $this->SetXY(5, 61);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(30, 4, utf8_decode('Fecha Inicio'), 1, 0, 'C', 1);
        $this->Cell(100, 4, utf8_decode('Nombre'), 1, 0, 'C', 1);
        $this->Cell(20, 4, utf8_decode('Cantidad'), 1, 0, 'C', 1);
        $this->Cell(48, 4, utf8_decode('Observación'), 1, 0, 'C', 1);
    }

    public function footerSumimedical(){
        $nota = '';
        foreach (self::$data["servicios"] as $key => $servicio) {
            if((count(self::$data["servicios"])-1) === $key){
                $nota .= $servicio->Nota;
            }else{
                $nota .= $servicio->Nota.' - ';
            }
        }
        $this->SetFont('Arial','',6);
        $this->Rect(5,102,198,14);
        $this->SetXY(5,103);
        $this->MultiCell(198, 3, utf8_decode("NOTA AUDITORIA: " .$nota), 0, "L", 0);
        $this->SetFont('Arial','B',5);
        $this->Rect(5,99.5,198,3);
        $this->Text(10,101.5,utf8_decode('IMPORTANTE: '));
        $this->SetFont('Arial','',4.8);
        $this->Text(24,101.5,utf8_decode('AUTORIZACION VALIDA SOLAMENTE EN LOS 120 DIAS DESPUES A LA FECHA DE SU EXPEDICION, UNA VEZ CUMPLIDO DICHO PLAZO NO HAY RESPOABILIDAD DE SUMIMEDICAL - RED VITAL. (Resolucion 4331 de 2012) .'));
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

            if(is_numeric(self::$citaPaciente->Medicoordeno)){
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
//        if (isset(self::$data["Firma_Auditor"])) {
//            if (file_exists(storage_path(substr(self::$data["Firma_Auditor"], 9)))) {
//                $this->Image(storage_path(substr(self::$data["Firma_Auditor"], 9)), 143, 121, 56, 11);
//            }
//        }
        $this->SetFont('Arial','I',8);
        $this->TextWithDirection(206,120,'Funcionario que Imprime:','U');
        $this->TextWithDirection(206, 87, utf8_decode(auth()->user()->name . " " . auth()->user()->apellido), 'U');
        $this->TextWithDirection(209,120,utf8_decode('Fecha Impresión:'),'U');
        $this->TextWithDirection(209, 98, date('Y-m-d h:i:s'), 'U');
    }

    public function footerUTAntioquia(){
        self::$cie10s = Cie10paciente::select([
            "c.Codigo_CIE10 as Codigo"
        ])
            ->join('cie10s as c','c.id','cie10pacientes.Cie10_id')
            ->where("cie10pacientes.Citapaciente_id",self::$citaPaciente->id)->get()->toArray();

        $this->SetFillColor(216, 216, 216);
        $this->SetFont('Arial','',8);
        $this->SetXY(5,99);
        $this->Cell(198, 4, utf8_decode("Diagnóstico: ").(self::$cie10s?implode(', ', array_column(self::$cie10s, 'Codigo')):""), 0, 0, 'L');
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial','B',8);
        $this->Cell(198, 4, 'SE REALIZA CARGUE DEL SERVICIO A PLATAFORMA ALMEDA SUJETO A AUTORIZACION POR PARTE DE LA UdeA', 1, 0, 'C', 0);
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial','B',8);
        $this->Cell(50, 4, 'Medico', 1, 0, 'L', 1);
        $this->SetFont('Arial','',8);
        if(is_numeric(self::$citaPaciente->Medicoordeno)){
            $medicoTranscripcion = User::find(intval(self::$citaPaciente->Medicoordeno));
            if($medicoTranscripcion){
                $this->Cell(148, 4, utf8_decode($medicoTranscripcion->name." ".$medicoTranscripcion->apellido."   R.M:".$medicoTranscripcion->cedula." (".$medicoTranscripcion->especialidad_medico.")"), 1, 0, 'L', 0);
                if($medicoTranscripcion->Firma){
                    if (file_exists(storage_path(substr($medicoTranscripcion->Firma, 9)))) {
                        $this->Image(storage_path(substr($medicoTranscripcion->Firma, 9)), 55, 119, 56, 11);
                    }
                }
            }else{
                $this->Cell(148, 4, utf8_decode(self::$citaPaciente->Medicoordeno), 1, 0, 'L', 0);
            }
        }else{
            $this->Cell(148, 4, utf8_decode(self::$citaPaciente->Medicoordeno), 1, 0, 'L', 0);

        }
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial','B',8);
        $this->Cell(50, 23, 'Firma', 1, 0, 'L', 1);

        $this->SetFont('Arial','I',8);
        $this->TextWithDirection(206,120,utf8_decode('Fecha Creacion:'),'U');
        $this->TextWithDirection(206, 98, self::$orden->created_at, 'U');

    }
}
