<?php
namespace App\Formats;

use App\Modelos\Detallecompra;
use App\Modelos\Factura;
use App\Modelos\Empleado;
use App\Modelos\Paciente;
use App\User;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class FacturaVagout extends FPDF
{

    public static $factura;
    public static $facturaDetalles;
    public static $user;
    public function generar($data){
        $pdf = new FacturaVagout('P','mm','A4');
        self::$factura = Factura::find($data["order_id"]);
        if(self::$factura->usuario_id){
            // self::$user = User::find(self::$factura->usuario_id);
            self::$user = Empleado::select([
                'empleados.Nombre as nombre',
                'empleados.Documento as documento',
                'empleados.Area as direccion',
                'empleados.correo as correo'

            ])->where('id',self::$factura->usuario_id)->first();
        }else{
            //self::$user = Paciente::find(self::$factura->paciente_id);
            self::$user = Paciente::select([
                    DB::raw("CONCAT(pacientes.Primer_Nom,' ',pacientes.SegundoNom,' ',pacientes.Primer_Ape,' ',pacientes.Segundo_Ape) as nombre"),
                    'pacientes.Num_Doc as documento',
                    'pacientes.Telefono as telefono',
                    'pacientes.Direccion_Residencia as direccion',
                    'pacientes.Correo1 as correo',
                ]
            )->where('id',self::$factura->paciente_id)->first();

        }
        self::$facturaDetalles = Detallecompra::select([
            'detallecompras.cantidad_producto',
            'detallecompras.valor_unitario',
            'p.nombre',
            'p.presentacion'
        ])->join('productos as p','p.id','detallecompras.producto_id')->where('detallecompras.factura_id',self::$factura->id)->get();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $this->body($pdf);
        $usuario = self::$user;
        if(isset($data["sendmail"])){
            if($usuario->correo){
                $email = Mail::send('send_mail_vagout', $data, function ($message) use ($usuario, $pdf) {
                    $message->to($usuario->correo);
                    $message->subject('Factura Vagout '.date('Y/m/d') .' '.$usuario->nombre);
                    $message->attachData($pdf->Output("S"), 'Factura Vagout' . ' ' .$usuario->documento.'.pdf', [
                        'mime' => 'application/pdf',
                    ]);
                });
            }
        }
        $pdf->Output();
    }

    public function Header()
    {
        $logo = public_path() . "/images/logoVagout.png";
        $this->SetFont('Arial', 'B', 15);
        $this->SetY(5);
        $this->Line(10, 8, 90, 8);

        $this->Line(120, 8, 200, 8);
        $this->Cell(190, 7, utf8_decode('FACTURA'), 0, 0, 'C');
        $this->SetFont('Arial', '', 11);
        $this->SetY(12);
        $this->Cell(73, 7, utf8_decode('Fecha:'), 1, 0, 'R');
        $this->Cell(22, 7,  Carbon::parse(self::$factura->created_at)->format("Y-m-d"), 1, 0, 'R');
        $this->Cell(22, 7, utf8_decode('Numero: '), 1, 0, 'R');
        $this->Cell(73, 7, self::$factura->id, 1, 0, 'L');
        $this->Line(145, 25, 145, 60);

        $this->Image( $logo,10,25,-750);
        $this->SetXY(85,34);
        $this->Cell(50, 4, utf8_decode('CLINICA VICTORIANA'), 0, 0, 'C');
        $this->SetX(150);
        $this->Cell(50, 4, utf8_decode('C.C: '.self::$user->documento), 0, 0, 'C');
        $this->SetXY(85,38);
        $this->Cell(50, 4, utf8_decode('NIT: 900033371 Res: 004'), 0, 0, 'C');
        $this->SetX(150);
        $this->SetFont('Arial', '', 9);
        $this->Cell(50, 4, utf8_decode(self::$user->nombre), 0, 0, 'C');
        $this->SetFont('Arial', '', 11);

        $this->SetXY(85,42);
        $this->Cell(50, 4, utf8_decode('Carrera 49 # 58-19'), 0, 0, 'C');
        $this->SetX(150);
        $this->SetFont('Arial', '', 7);
        $this->Cell(50, 4, utf8_decode(self::$user->direccion), 0, 0, 'C');
        $this->SetFont('Arial', '', 11);
        $this->SetXY(85,46);
        $this->Cell(50, 4, utf8_decode('Telefono: 4481604'), 0, 0, 'C');
        $this->SetX(150);
        if (isset(self::$user->telefono)) {
            $this->Cell(50, 4, utf8_decode('Telefono: '.self::$user->telefono), 0, 0, 'C');
        }
        $this->SetXY(10,70);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(190, 5, utf8_decode('PRODUCTOS'), 1, 0, 'C');
        $this->SetXY(10,75);
        $this->Cell(22, 5, utf8_decode('Cantidad'), 1, 0, 'C');
        $this->Cell(128, 5, utf8_decode('Nombre'), 1, 0, 'C');
        $this->Cell(40, 5, utf8_decode('Precio'), 1, 0, 'C');


    }

    public function body($pdf){
        $Y = 82;
        $totalProductos = 0;
        $pdf->SetFont('Arial', '', 11);
        foreach (self::$facturaDetalles as $detalle){

            for ($i=1; $i <= intval($detalle->cantidad_producto); $i++) {

            if ($Y  > 205) {
                $Y = 82;
                $pdf->AddPage();
            }
            $pdf->SetXY(10, $Y);
            $pdf->MultiCell(22, 5, 1, 0, 'C',0);
            $pdf->SetXY(32, $Y);
            $pdf->MultiCell(128, 5,utf8_decode($detalle->nombre." ".$detalle->presentacion) , 0, 'L',0);
            $YN = round($pdf->GetY(), 0, PHP_ROUND_HALF_DOWN);
            $pdf->SetXY(160, $Y);
            $pdf->MultiCell(40, 5,"$ ". $detalle->valor_unitario, 0, 'R',0);

            $YO = round($pdf->GetY(), 0, PHP_ROUND_HALF_DOWN);

            $pdf->Line(10, ($YO > $YN ? $YO : $YN), 200, ($YO > $YN ? $YO : $YN));
            $YL = round($pdf->GetY(), 0, PHP_ROUND_HALF_DOWN);

            $Y = $Y + (($YN > $YL ? $YN : $YL) - $Y);
            $Y = $Y+2;
            $totalProductos = $totalProductos+(intval($detalle->valor_unitario)*intval(1));
        }

        }

        $YP = $Y+15;
        $pdf->SetXY(120, 220);
        $pdf->Line(120,$YP,200,$YP);
        $pdf->SetXY(120, $YP=$YP+3);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(40, 5, utf8_decode('SUBTOTAL'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(40, 5, '$ '.$totalProductos, 1, 0, 'R');
        if(self::$factura->valor_empaque) {
            $pdf->SetXY(120, $YP = $YP + 5);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(40, 5, utf8_decode('TOTAL EMPAQUES'), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(40, 5, '$ ' . self::$factura->valor_empaque." (x".self::$factura->cantidad_empaques.")", 1, 0, 'R');
        }
        if(self::$factura->valor_domicilio){
            $pdf->SetXY(120, $YP=$YP+5);
            $pdf->SetFont('Arial', 'B', 11);
            $pdf->Cell(40, 5, utf8_decode('TOTAL DOMICILIO'), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(40, 5, '$ '.self::$factura->valor_domicilio, 1, 0, 'R');
        }
        $YP=$YP+8;
        $pdf->Line(120,$YP,200,$YP);
        $pdf->SetXY(120, $YP=$YP+3);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(40, 5, utf8_decode('TOTAL A PAGAR'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(40, 5, '$ '.self::$factura->valor_total, 1, 0, 'R');
        $YP=$YP+8;
        $pdf->Line(120,$YP,200,$YP);

    }

    public function Footer()
    {
        $this->SetXY(10,287);
        $this->SetFont('Arial', '', 11);
        $this->Cell(160, 5, utf8_decode('Cantidad'), 1, 0, 'L');
        $this->Cell(30,5,utf8_decode('Página '.$this->PageNo().' de {nb}'),0,0,'C');
    }

}
