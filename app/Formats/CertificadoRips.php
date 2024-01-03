<?php
namespace App\Formats;


use App\Modelos\Ac;
use App\Modelos\Af;
use App\Modelos\Au;
use App\Modelos\Ct;
use App\Modelos\PaqRip;
use App\Modelos\Prestadore;
use App\Modelos\Sedeproveedore;
use Codedge\Fpdf\Fpdf\Fpdf;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CertificadoRips extends FPDF
{
    public static $package;
    public static $provider;
    public static $sum;
    public static $type;
    public static $details;
    public static $total;
    public static $prestador;


    public function generate($id,$type)
    {
        self::$package = PaqRip::with(['cts','afs'])->where('id',$id)->first();
        self::$provider = Sedeproveedore::where('id',self::$package->Reps_id)->first();
        self::$prestador = Prestadore::find(self::$provider->Prestador_id);
        self::$total = Af::select([DB::raw('SUM(valor_Neto) AS total')])->where('paq_id',$id)->first();
        self::$type = $type;
        if($type === 1){
            self::$sum = Ct::select([DB::raw("(SUM(cast(Cantidad_Registros as int))) as total")])->where('Id_Paquete',$id)->first();
            self::$details = Ct::select([DB::raw("1 as num_prestadores"),"Nombre_Archivo",DB::raw("SUM(cast(Cantidad_Registros as int)) AS total")])->where('Id_Paquete',$id)->groupBy('Nombre_Archivo')->get()->toArray();
        }else{
            self::$sum = ['total' => 0];
            self::$details = $this->getDetalle();
        }
        $pdf = new CertificadoRips('P','mm','A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $this->body($pdf);
        $pdf->Output();
    }

    public function header()
    {
        $logo = public_path() . "/images/logo.png";
        $this->Image($logo, 85, 7, -200);
        $this->SetFont('Arial','B',20);
        $this->SetXY(20,60);
        $this->Cell(120,10,utf8_decode(self::$package->estado_id == '7'?"CERTIFICADO DE RADICACIÓN":"CERTIFICADO DE CARGA"),0,0,'L',false,'');
        $this->Ln();
        $this->SetX(20);
        $this->SetFont('Arial','B',15);
        $this->Cell(120,10,utf8_decode("N° ".self::$package->id),0,0,'L',false,'');
        $this->Ln();
        $this->Ln();
//        if(self::$type != 1){
//            $this->SetX(20);
//            $this->SetFont('Arial','',10);
//            $this->Cell(170,5,utf8_decode("Este Reporte no es válido como radicado, las facturas relacionadas serán revisadas, una vez superen "),0,0,'L',false,'');
//            $this->Ln();
//            $this->SetX(20);
//            $this->Cell(170,5,utf8_decode("esa revisión pueden descargar el Informe de Radicado."),0,0,'L',false,'');
//        }
        $this->Ln();
        $this->SetFont('Arial','B',20);
        $this->SetX(20);
//        $this->Cell(150,10,utf8_decode(self::$provider->Nombre.(self::$type != 1?' (Parcial)':'')),0,0,'L',false,'');
        $this->MultiCell(150,10,utf8_decode(self::$provider->Nombre." (".self::$package->afs[0]['codigo_entidad'].")"),0,'L',false);
//        $this->Ln();
        $this->SetX(20);
        $this->SetFont('Arial','',10);
        $this->Cell(150,5,utf8_decode("Cód. Hab. ".self::$provider->Codigo."- NI ".self::$prestador->Nit),0,0,'L',false,'');
        $this->Ln();
        $this->SetX(20);
        $this->SetFont('Arial','B',10);
        $this->Cell(32,5,utf8_decode('Fecha Radicación: '),0,0,'L',false,'');
        $this->SetFont('Arial','',10);
        $this->Cell(118,5,utf8_decode(self::$package->created_at),0,0,'L',false,'');
        $this->Ln();
        $this->SetX(20);
        $this->SetFont('Arial','B',10);
        $this->Cell(27,5,utf8_decode('Total Facturas: '),0,0,'L',false,'');
        $this->SetFont('Arial','',10);
        $totalFacturas = Af::select('numero_factura')->where('paq_id',self::$package->id)->count();
        $this->Cell(122,5,utf8_decode($totalFacturas),0,0,'L',false,'');
        $this->Ln();
        $this->SetX(20);
        $this->SetFont('Arial','B',10);
        $this->Cell(12,5,utf8_decode('Valor: '),0,0,'L',false,'');
        $this->SetFont('Arial','',10);
        $this->Cell(122,5,'$ '.number_format(self::$total['total']),0,0,'L',false,'');
        $this->Ln();
        $this->Ln();
        $this->Ln();
        $this->Ln();

//        if(self::$type == 1){
        $this->Ln();
        $this->SetX(20);
        $this->SetFont('Arial','B',10);
        $this->Cell(150,5,utf8_decode("REGISTRO FACTURAS"),0,0,'L',false,'');
        $this->Ln();

        if(self::$type == 1){
            $this->Ln();
            $this->SetX(41.25);
            $this->Cell(5,5,utf8_decode('#'),1,0,'C',false,'');
            $this->Cell(38.5,5,utf8_decode('Número Factura'),1,0,'C',false,'');
            $this->Cell(40,5,utf8_decode('Fecha Expedición'),1,0,'C',false,'');
            $this->Cell(43.5,5,utf8_decode('Valor Neto'),1,0,'C',false,'');

        }else{
            $this->Ln();
            $this->SetX(41.25);
            $this->Cell(35.75,5,utf8_decode('Prestador'),1,0,'C',false,'');
            $this->Cell(20.75,5,utf8_decode('Archivos'),1,0,'C',false,'');
            $this->Cell(24.75,5,utf8_decode('Registros'),1,0,'C',false,'');
            $this->Cell(24.75,5,utf8_decode('Exitosos'),1,0,'C',false,'');
            $this->Cell(21,5,utf8_decode('Faltantes'),1,0,'C',false,'');

        }
        $this->SetFont('Arial','',10);
        $this->Ln();

    }

    public function footer(){
        $this->SetXY(180,280);
        $this->Cell(0,10,'Pagina '.$this->PageNo().' de {nb}',0,0,'C');
    }

    public function body($pdf){
        $i = 1;
        foreach ($this->record_sort(self::$package->afs,"numero_factura") as $af){
                $pdf->Ln();
                $pdf->SetX(41.25);
                $pdf->Cell(5,5,utf8_decode($i),0,0,'C',false,'');
                $pdf->Cell(38,5,utf8_decode($af->numero_factura),0,0,'C',false,'');
                $pdf->Cell(40,5,utf8_decode($af->fechaexpedicion_factura),0,0,'C',false,'');
                $pdf->Cell(43,5,utf8_decode('$ '.number_format($af->valor_Neto,0,',','.')),0,0,'C',false,'');
                $i++;
        }
    }

    public function bodyPartial($pdf){
        $i = 1;
        foreach (self::$details as $detalle){
            $pdf->Ln();
            $pdf->SetX(41.25);
            $pdf->Cell(35.75,5,utf8_decode(self::$provider->Codigo),0,0,'C',false,'');
            $pdf->Cell(20.75,5,utf8_decode($detalle["file_name"]),0,0,'C',false,'');
            $pdf->Cell(24.75,5,utf8_decode($detalle["total"]),0,0,'C',false,'');
            $pdf->Cell(24.75,5,utf8_decode($detalle["success"]),0,0,'C',false,'');
            $pdf->Cell(21.75,5,(intval($detalle["total"])-intval($detalle["success"])),0,0,'C',false,'');
            $i++;
        }
    }

    public function getDetalle(){
        $data = [];
        $id = self::$package->id;
        foreach (self::$package['cts'] as $file){
            $arr = [];
            $f = strtoupper(substr($file->Nombre_Archivo,0,2));
            $modelName = strtoupper(substr($file->Nombre_Archivo,0,1)).strtolower(substr($file->Nombre_Archivo,1,1));
            $model = 'App\\'.'Modelos\\'.$modelName;
            $arr['num_prestadores'] = 1;
            $arr['file_name'] = $f;
            $arr['total'] = $file->Cantidad_Registros;
            $success = $model::whereIn('Af_id',function ($q) use ($id){
                $q->from('afs')
                    ->selectRaw('id')
                    ->where('paq_id',$id);
            })->get();
            $arr['success'] = ($success?$success->count():0);
            self::$sum["total"] += ($success?$success->count():0);
            $data[] = $arr;
        }

        return $data;
    }

    function record_sort($records, $field, $reverse=false)
    {
        $hash = array();

        foreach($records as $record)
        {
            $hash[$record[$field]] = $record;
        }

        ($reverse)? krsort($hash) : ksort($hash);

        $records = array();

        foreach($hash as $record)
        {
            $records []= $record;
        }

        return $records;
    }
}
