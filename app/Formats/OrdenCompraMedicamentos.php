<?php
namespace App\Formats;

use App\Modelos\Bodega;
use App\Modelos\HistoricoPrecioProveedorMedicamento;
use App\Modelos\Ordencompra;
use App\Modelos\Sedeproveedore;
use App\Modelos\SolicitudCompra;
use App\User;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\DB;

class   OrdenCompraMedicamentos extends FPDF
{

    public static $solicitud;
    public static $articulos;
    public static $prestador;

    public function generar($id)
    {
        self::$solicitud = SolicitudCompra::find($id);
        if(self::$solicitud->estado_id == 15){
            self::$articulos = Ordencompra::select(['c.Nombre','ordencompras.Usuario_id','t.nombre as titular','ordencompras.cantidad',
                DB::raw('null as precio_unidad'),DB::raw('null as unidad_compra'), 'c.Codigo'])
                ->join('detallearticulos as da','da.id','ordencompras.detallearticulo_id')
                ->join('codesumis as c','c.id','da.Codesumi_id')
                ->join('titulares as t','t.id','da.titular_id')
                ->where('ordencompras.solicitudcompra_id',$id)
                ->get();
        }else{
            self::$articulos =HistoricoPrecioProveedorMedicamento::select(['c.Nombre','o.Usuario_id','t.nombre as titular','o.cantidad',
                'historico_precio_proveedor_medicamentos.precio_unidad','da.unidad_compra', 'c.Codigo'])
                ->join('ordencompras as o','o.id','historico_precio_proveedor_medicamentos.ordencompra_id')
                ->join('detallearticulos as da','da.id','o.detallearticulo_id')
                ->join('codesumis as c','c.id','da.Codesumi_id')
                ->join('titulares as t','t.id','da.titular_id')
                ->where('o.solicitudcompra_id',$id)
                ->where('o.estado_id',7)
                ->get();
        }


        if(self::$solicitud->sedeproveedore_id){
            self::$prestador = Sedeproveedore::select(['sedeproveedores.Nombre','sedeproveedores.Telefono1','sedeproveedores.Telefono2','sedeproveedores.Direccion','m.Nombre as municipio','p.Nit'])
            ->join('prestadores as p','sedeproveedores.Prestador_id','p.id')
            ->join('municipios as m','m.id','sedeproveedores.Municipio_id')
            ->where('sedeproveedores.id',self::$solicitud->sedeproveedore_id)
            ->first();
        }

        $pdf = new OrdenCompraMedicamentos('p', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $this->Body($pdf);
        $pdf->Output();
    }

    public function Header()
    {
    }

    public function Body($pdf)
    {
        $logo = public_path() . "/images/logo.png";
        $pdf->Image($logo, 15, 8, -280);
        $pdf->SetXY(12, 10);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(186, 4, utf8_decode('SUMIMEDICAL S.A.S'), 0, 0, 'C');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(186, 4, utf8_decode('CRA 80 C # 32 EE 65'), 0, 0, 'C');
        $pdf->SetXY(12, 23);
        $pdf->SetFont('Arial', 'B', 15);
        switch (self::$solicitud->estado_id){
            case 15:
                $pdf->Cell(186, 4, utf8_decode('SOLICITUD DE COMPRA'), 0, 0, 'C');
                break;
            case 17:
            case 7:
                $pdf->Cell(186, 4, utf8_decode('ORDEN DE COMPRA'), 0, 0, 'C');
                break;
        }

        $pdf->Ln(6);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(186, 4, utf8_decode('Nº'.self::$solicitud->id), 0, 0, 'C');
        $pdf->SetXY(12, 40);
        $pdf->SetFont('Arial', '', 7);
        $usuarioCrea = User::find(self::$articulos[0]->Usuario_id);
        $pdf->Cell(93, 4, utf8_decode('Elaborá: '.$usuarioCrea->name.' '.$usuarioCrea->apellido), 0, 0, 'L');
        if(self::$solicitud->estado_id == 17 || self::$solicitud->estado_id == 7){
            $idAuditor = Ordencompra::where('solicitudcompra_id',self::$solicitud->id)->whereNotNull('Usuarioresponde_id')->first();
            $auditor = User::find($idAuditor->Usuarioresponde_id);
            $pdf->Cell(93, 4, utf8_decode('Confirma: '.$auditor->name.' '.$auditor->apellido), 0, 0, 'R');
        }
        $pdf->Line(12,45,198,45);
        $pdf->SetXY(12, 47);
        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->Cell(22, 4, utf8_decode('PROVEEDOR:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(73, 4, utf8_decode(self::$prestador?self::$prestador->Nombre:""), 1, 0, 'L');
        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->Cell(7, 4, utf8_decode('NIT:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(29, 4, utf8_decode(self::$prestador?self::$prestador->Nit:""), 1, 0, 'L');
        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->Cell(22, 4, utf8_decode('FECHA:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(33, 4, utf8_decode(self::$solicitud->created_at), 1, 0, 'L');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->Cell(22, 4, utf8_decode('CIUDAD:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(109, 4, utf8_decode(self::$prestador?self::$prestador->municipio:""), 1, 0, 'L');
        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->Cell(22, 4, utf8_decode('ENTREGA:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(33, 4, utf8_decode((self::$solicitud->estado_id == 17?self::$solicitud->updated_at:"")), 1, 0, 'L');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->Cell(22, 4, utf8_decode('DIRECCION:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(109, 4, utf8_decode(self::$prestador?self::$prestador->Direccion:""), 1, 0, 'L');
        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->Cell(22, 4, utf8_decode('CLASE ORDEN:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(33, 4, utf8_decode('Orden Compra'), 1, 0, 'L');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 7.5);
        if(self::$prestador){
            $pdf->Cell(22, 4, utf8_decode('TELEFONO:'), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(109, 4, utf8_decode(self::$prestador->Telefono1.(self::$prestador->Telefono2?'-'.self::$prestador->Telefono2:"")), 1, 0, 'L');
        }else{
            $pdf->Cell(22, 4, utf8_decode('TELEFONO:'), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(109, 4, utf8_decode(""), 1, 0, 'L');
        }
        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->Cell(22, 4, utf8_decode('MONEDA:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(33, 4, utf8_decode('Pesos'), 1, 0, 'L');
        $pdf->Ln();
        $pdf->SetX(12);
        $bodega = Bodega::find(self::$solicitud->bodega_id);
        $pdf->SetFont('Arial', 'B', 7.5); 
        $pdf->Cell(22, 4, utf8_decode('BODEGA:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(109, 4, utf8_decode($bodega->Nombre), 1, 0, 'L');
        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->Cell(22, 4, utf8_decode('ESTADO:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 7);
        switch (self::$solicitud->estado_id){
            case 15:
                $pdf->Cell(33, 4, utf8_decode('Por Autorizar'), 1, 0, 'L');
            break;
            case 7:
                $pdf->Cell(33, 4, utf8_decode('Autorizado'), 1, 0, 'L');
                break;
            case 17:
                $pdf->Cell(33, 4, utf8_decode('Entregado'), 1, 0, 'L');
                break;
        }
        $pdf->Ln(8);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(10, 4, utf8_decode('CODIGO'), 1, 0, 'C');
        $pdf->Cell(45, 4, utf8_decode('NOMBRE'), 1, 0, 'C');
        $pdf->Cell(45, 4, utf8_decode('FABRICANTE'), 1, 0, 'C');
        $pdf->Cell(20, 4, utf8_decode('PRESENTACION'), 1, 0, 'C');
        $pdf->Cell(13, 4, utf8_decode('CANTIDAD'), 1, 0, 'C');
        $pdf->Cell(15, 4, utf8_decode('VALOR/U'), 1, 0, 'C');
        $pdf->Cell(22, 4, utf8_decode('SUBTOTAL'), 1, 0, 'C');
        $pdf->Cell(8, 4, utf8_decode('%DTO'), 1, 0, 'C');
        $pdf->Cell(8, 4, utf8_decode('%IVA'), 1, 0, 'C');
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 6);
        $Y = 75;
        $YFin = 0;
        $totalCompra = 0;
        foreach (self::$articulos as $articulo) {
            if($articulo->precio_unidad){
                $suma = round((floatval($articulo->precio_unidad)*floatval($articulo->cantidad)),2);
            }
            $pdf->SetXY(12, $Y);
            $pdf->Cell(10, 4, utf8_decode($articulo->Codigo), 0, 0, 'C');
            $pdf->MultiCell(45, 4, utf8_decode($articulo->Nombre), 0, 'L', 0);
            $YNombre = $pdf->GetY();
            $pdf->SetXY(67, $Y);
            $pdf->MultiCell(45, 4, utf8_decode($articulo->titular), 0, 'L', 0);
            $YFabricante = $pdf->GetY();
            $pdf->SetXY(112, $Y);
            $pdf->Cell(20, 4, utf8_decode(substr($articulo->unidad_compra, 0, 20)), 0, 0, 'C');
            $pdf->Cell(13, 4, utf8_decode($articulo->cantidad), 0, 0, 'C');
            if($articulo->precio_unidad){
                $pdf->Cell(15, 4, utf8_decode("$".number_format(round($articulo->precio_unidad, 2),2)), 0, 0, 'C');
            }
            if($articulo->precio_unidad){
                $pdf->Cell(22, 4, utf8_decode("$".number_format($suma,2)), 0, 0, 'C');
            }
            $pdf->Ln();
            $Y = ($YNombre > $YFabricante?$YNombre:$YFabricante);
            $YFin = $pdf->GetY();
            if($articulo->precio_unidad){
                $totalCompra = $totalCompra+$suma;
            }
        }
        $pdf->Line(12,$YFin+8,198,$YFin+8);
        $pdf->SetXY(12, $YFin+8);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(15, 4, utf8_decode('DETALLE'), 0, 0, 'L');
        $pdf->Ln();
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 6.5);
        $pdf->MultiCell(100, 4, utf8_decode(''), 0, 'L', 0);
        $pdf->SetXY(153, $YFin+8);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(20, 4, utf8_decode('SUBTOTAL:'), 0, 0, 'R');
        $pdf->SetFont('Arial', '', 6.5);
        $pdf->Cell(25, 4, utf8_decode('$ '.(self::$solicitud->estado_id != 15?number_format($totalCompra,2):"")), 0, 0, 'R');
        $pdf->Ln();
        $pdf->SetX(153);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(20, 4, utf8_decode('DESCUENTO:'), 0, 0, 'R');
        $pdf->SetFont('Arial', '', 6.5);
        $pdf->Cell(25, 4, utf8_decode('$'), 0, 0, 'R');
        $pdf->Ln();
        $pdf->SetX(153);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(20, 4, utf8_decode('IMPUESTO:'), 0, 0, 'R');
        $pdf->SetFont('Arial', '', 6.5);
        $pdf->Cell(25, 4, utf8_decode('$'), 0, 0, 'R');
        $pdf->Ln();
        $pdf->SetX(153);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(20, 4, utf8_decode('TOTAL ORDEN:'), 0, 0, 'R');
        $pdf->SetFont('Arial', '', 6.5);
        $pdf->Cell(25, 4, utf8_decode('$ '.(self::$solicitud->estado_id != 15?number_format($totalCompra,2):"")), 0, 0, 'R');
    }

    public function Footer()
    {
        $this->SetTextColor(120,120,120);
        $this->SetXY(12,275);
        $this->SetFont('Arial', 'B', 7.5);
        $this->Cell(120, 4, utf8_decode('Nombre reporte : OrdenCompra'), 0, 0, 'L');
        $this->Cell(55, 4, utf8_decode('Usuario'), 0, 0, 'L');
        $this->Ln(8);
        $this->SetX(12);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(90, 4, utf8_decode('LICENCIADO A: [SUMIMEDICAL S.A.S.] NIT [900033371-4]'), 0, 0, 'L');
    }
}
