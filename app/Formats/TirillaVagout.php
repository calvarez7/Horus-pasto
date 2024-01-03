<?php
namespace App\Formats;
use App\Modelos\Detallecompra;
use App\Modelos\Factura;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Database\Eloquent\Model;

class TirillaVagout extends FPDF{

    public static $factura;
    public static $detalleFactura;


    public function generar($id)
    {
        $pdf = new TirillaVagout('p', 'mm', array(58,250));
        self::$factura = Factura::find($id);
        self::$detalleFactura = Detallecompra::select(['detallecompras.cantidad_producto as cantidad','p.nombre','detallecompras.valor_unitario as valor'])->where('detallecompras.factura_id',$id)->join('productos as p','p.id','detallecompras.producto_id')->get();
//        dd(self::$detalleFactura);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $this->body($pdf);
        $pdf->Output();
    }
    public function body($pdf){
        $logo = public_path() . "/images/logoVagout.png";
        $pdf->Image($logo,10,5,-1500);
        $pdf->Line(4,19,47,19);
        $pdf->SetXY(4,20);
        $pdf->SetFont('Arial','',6);
        $pdf->Cell(15.6,4,utf8_decode('FECHA:'),0,0,'L');
        $pdf->Cell(15.6,4,utf8_decode(date('d/m/Y')),0,0,'C');
        $pdf->Cell(15.6,4,utf8_decode(date('h:i:s')),0,0,'C');
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetX(4);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(47,4,utf8_decode('VAGOUT S.A.S'),0,0,'C');
        $pdf->Ln();
        $pdf->SetX(4);
        $pdf->Cell(47,4,utf8_decode('NIT: 901346723-0'),0,0,'C');
        $pdf->Ln();
        $pdf->SetX(4);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(47,4,utf8_decode('carrera 49 No 58-19'),0,0,'C');
        $pdf->Ln();
        $pdf->SetX(4);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(47,4,utf8_decode('MEDELLIN-ANTIOQUIA'),0,0,'C');
        $pdf->Ln();
        $pdf->SetX(4);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(47,4,utf8_decode('5201040 ext. 2004'),0,0,'C');
        $pdf->Ln();
        $pdf->SetX(4);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(47,4,utf8_decode('catering.vagout@gmail.com'),0,0,'C');
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetX(4);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(47,4,utf8_decode('FACTURA DE VENTA'),0,0,'C');
        $pdf->Ln();
        $pdf->SetX(4);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(47,4,utf8_decode('No.  '.self::$factura->id),0,0,'C');
        $pdf->Ln();
        $pdf->SetX(4);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(47,4,utf8_decode('VEND:'.auth()->user()->name.' '.auth()->user()->apellido),0,0,'C');
        $pdf->Ln();
        $pdf->SetX(4);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(47,4,utf8_decode('CLIENTE: VENTAS DE CONTADO'),0,0,'C');
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetX(4);
        $pdf->SetFont('Arial','B',7);
        $pdf->Cell(21.2,4,utf8_decode('DESCRIPCION'),0,0,'L');
        $pdf->Cell(10,4,utf8_decode('CANT'),0,0,'C');
        $pdf->Cell(15.6,4,utf8_decode('VALOR'),0,0,'C');
        $pdf->Ln();
        $pdf->SetFont('Arial','',6);
        $Y= 80;
        $total = 0;
        foreach (self::$detalleFactura as $detalle){
            for ($i = 1;$i<=$detalle->cantidad;$i++){
            $pdf->SetXY(4,$Y);
            $pdf->MultiCell(21.2,2.5,utf8_decode($detalle["nombre"]),0,'L',0);
            $lastY = $pdf->GetY();
            $pdf->SetXY(25.2,$Y);
            $pdf->Cell(10,4,utf8_decode('1'),0,0,'C');
            $pdf->SetXY(35.2,$Y);
            $pdf->Cell(15.6,4,utf8_decode($detalle["valor"]),0,0,'C');
            $Y = $lastY;
            }

        }
        $pdf->SetXY(4,$Y+4);
        $pdf->SetFont('Arial','B',6.5);
        $pdf->Cell(23.5,4,utf8_decode('SUBTOTAL'),0,0,'L');
        $pdf->Cell(23.5,4,utf8_decode(self::$factura->valor_total),0,0,'R');
        $pdf->Ln();
        $pdf->SetX(4);
        $pdf->Cell(23.5,4,utf8_decode('TOTAL IVA'),0,0,'L');
        $pdf->Cell(23.5,4,utf8_decode(0),0,0,'R');
        $pdf->Ln();
        $pdf->SetX(4);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(23.5,4,utf8_decode('TOTAL A PAGAR'),0,0,'L');
        $pdf->Cell(23.5,4,utf8_decode(self::$factura->valor_total),0,0,'R');
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetX(4);
        $pdf->SetFont('Arial','B',7);
        $pdf->Cell(47,4,utf8_decode('MEDIOS DE PAGO'),0,0,'C');
        $pdf->Ln();
        $pdf->SetX(4);
        $pdf->SetFont('Arial','',7);
        $pdf->Cell(23.5,4,utf8_decode(strtoupper(self::$factura->forma_pago)),0,0,'L');
        $pdf->Cell(23.5,4,utf8_decode(self::$factura->forma_pago === 'Descuento Nomina'?self::$factura->valor_total:self::$factura->total_efectivo),0,0,'R');
        if(self::$factura->forma_pago === 'Efectivo'){
            $pdf->Ln();
            $pdf->SetX(4);
            $pdf->SetFont('Arial','',7);
            $pdf->Cell(23.5,4,utf8_decode("CAMBIO"),0,0,'L');
            $pdf->Cell(23.5,4,utf8_decode(intval(self::$factura->total_efectivo)-intval(self::$factura->valor_total)),0,0,'R');
        }
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetX(4);
        $pdf->Cell(47,2.5,utf8_decode('GRACIAS POR SU COMPRA'),0,0,'C');



    }
}
