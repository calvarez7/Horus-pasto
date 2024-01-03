<?php
namespace App\Formats;

use App\Modelos\Bodega;
use App\Modelos\NovedadesOrdenCompra;
use App\Modelos\Sedeproveedore;
use App\Modelos\SolicitudCompra;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class NovedadesOrdenCompraMedicamentos extends FPDF
{
    public static $novedades;
    public static $solicitud;

    public function generar($data)
    {
        self::$solicitud = SolicitudCompra::find($data["id"]);
        self::$novedades = NovedadesOrdenCompra::select(['sc.id',
            'novedades_orden_compras.numero_factura',
            'da.codigo',
            'da.Producto',
            't.nombre',
            'novedades_orden_compras.lote',
            'novedades_orden_compras.fecha_vence',
            'novedades_orden_compras.tipo_novedad',
            'novedades_orden_compras.observacion',
            'novedades_orden_compras.Cantidad',
            'novedades_orden_compras.devolver',
            'novedades_orden_compras.created_at',
            'novedades_orden_compras.observacion',
            'sp.Nombre as proveedor',
        ])
            ->join('ordencompras as o','o.id','novedades_orden_compras.ordencompras_id')
            ->join('detallearticulos as da','da.id','o.detallearticulo_id')
            ->join('solicitud_compras as sc','sc.id','o.solicitudcompra_id')
            ->join('titulares as t','t.id','da.titular_id')
            ->join('sedeproveedores as sp','sp.id','sc.sedeproveedore_id')
            ->where('sc.id',$data["id"])
            ->where('novedades_orden_compras.numero_factura',$data["numfactura"])
            ->get();
        $pdf= new NovedadesOrdenCompraMedicamentos('p', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->Output();
    }

    public function Header(){
        $bodega = Bodega::find(self::$solicitud->bodega_id);
        $proveedor = Sedeproveedore::find(self::$solicitud->sedeproveedore_id);
        $pdf = $this;
        $Y = 40;
        $pdf->SetDrawColor(140,190,56);
        $pdf->Rect(5, 5, 200, 287);
        $pdf->SetDrawColor(0,0,0);

        $pdf->SetFont('Arial', 'B', 9);
        $logo = "images/logo.png";
        $pdf->Rect(9, 9, 49,14);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->SetXY(147, 9);
        $pdf->Cell(54, 7, utf8_decode($bodega->Nombre), 1, 0, 'C');
        $pdf->Ln();
        $pdf->SetX(147);
        $pdf->Cell(54, 7, utf8_decode('Consecutivo:'.self::$solicitud->id), 1, 0, 'L');
        $pdf->SetXY(58, 9);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->MultiCell(89, 7, utf8_decode('NOVEDADES DE MEDICAMENTOS,  DISPOSITIVOS MEDICOS E INSUMOS'), 1,'C',0);
        $pdf->Image($logo, 23, 10, -480);

        $pdf->SetXY(9, 28);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(32, 4, utf8_decode('Fecha de ingreso:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(32, 4, utf8_decode(self::$novedades[0]["created_at"]), 1, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(32, 4, utf8_decode('Remision y/o factura:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(32, 4, utf8_decode(self::$novedades[0]["numero_factura"]), 1, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(32, 4, utf8_decode('Fecha de pedido'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(32, 4, utf8_decode(''), 1, 0, 'L');
        $pdf->Ln();
        $pdf->SetX(9);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(32, 4, utf8_decode('Fecha de Solicitud:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(32, 4, utf8_decode(''), 1, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(32, 4, utf8_decode('Proveedor:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(32, 4, utf8_decode($proveedor->Nombre), 1, 0, 'L');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(32, 4, utf8_decode('Transportador:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(32, 4, utf8_decode(''), 1, 0, 'L');

        $pdf->SetXY(9, 45);
        $pdf->SetFillColor(216, 216, 216);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(20, 4, utf8_decode('FACTURA'), 1, 0, 'C', 1);
        $pdf->Cell(20, 4, utf8_decode('CODIGO'), 1, 0, 'C', 1);
        $pdf->Cell(30, 4, utf8_decode('DESCRIPCION'), 1, 0, 'C', 1);
        $pdf->Cell(20, 4, utf8_decode('LOTE'), 1, 0, 'C', 1);
        $pdf->Cell(15, 4, utf8_decode('CANTIDAD'), 1, 0, 'C', 1);
        $pdf->Cell(25, 4, utf8_decode('FECHA VENCIMIENTO'), 1, 0, 'C', 1);
        $pdf->Cell(30, 4, utf8_decode('PROVEEDOR'), 1, 0, 'C', 1);
        $pdf->Cell(32, 4, utf8_decode('MOTIVO'), 1, 0, 'C', 1);
        $pdf->Ln();
        $final = 0;
        $y = 49;
        $pdf->SetFont('Arial', '', 6);
        foreach (self::$novedades as $novedad){


            $this->SetXY(9, $y);
            $x = 0;
            $this->MultiCell(20,4,utf8_decode($novedad["numero_factura"]),0,'C',0);
            $final = $this->GetY() > $final?$this->GetY():$final;
            $this->SetXY($x = $x+29, $y);
            $this->MultiCell(20,4,utf8_decode($novedad["codigo"]),0,'C',0);
            $final = $this->GetY() > $final?$this->GetY():$final;

            $this->SetXY($x = $x+20, $y);
            $this->MultiCell(30,4,utf8_decode($novedad["Producto"]),0,'C',0);
            $final = $this->GetY() > $final?$this->GetY():$final;

            $this->SetXY($x = $x+30, $y);
            $this->MultiCell(20,4,utf8_decode($novedad["lote"]),0,'C',0);
            $final = $this->GetY() > $final?$this->GetY():$final;

            $this->SetXY($x = $x+20, $y);
            $this->MultiCell(15,4,utf8_decode($novedad["Cantidad"]),0,'C',0);
            $final = $this->GetY() > $final?$this->GetY():$final;

            $this->SetXY($x = $x+15, $y);
            $this->MultiCell(25,4,utf8_decode($novedad["fecha_vence"]),0,'C',0);
            $final = $this->GetY() > $final?$this->GetY():$final;

            $this->SetXY($x = $x+25, $y);
            $this->MultiCell(30,4,utf8_decode($novedad["proveedor"]),0,'C',0);
            $final = $this->GetY() > $final?$this->GetY():$final;

            $this->SetXY($x = $x+30, $y);
            $this->MultiCell(32,4,utf8_decode($novedad["observacion"]),0,'C',0);
            $final = $this->GetY() > $final?$this->GetY():$final;

            $this->Line(9,$final,201,$final);
            $y = $final;
        }






    }

}
