<?php

namespace App\Formats;

use App\Modelos\citapaciente;
use App\Modelos\Cobro;
use App\Modelos\Cuporden;
use App\Modelos\Orden;
use App\Modelos\Paciente;
use Codedge\Fpdf\Fpdf\Fpdf;

class ReciboPago extends FPDF{

    public static $data;
    public static $cobro;
    public static $paciente;
    public static $orden;
    public static $valorTotal;
    public static $cita;
    public static $codigoCobro;



    public function generar($data){

        self::$cobro = Cobro::where('orden_id',$data["orden"])->first();
        self::$paciente = Paciente::find(self::$cobro->paciente_id);
        self::$data = $data;
        self::$orden = Orden::find($data["orden"]);
        if($data["formato"] === 'recibo'){
            $citaPaciente = citapaciente::find(self::$orden->Cita_id);
            self::$cita = $citaPaciente;
            self::$valorTotal = $citaPaciente->valor_cita;
            self::$codigoCobro = $citaPaciente->id;
        }else{
            self::$valorTotal = round($data["valor"]);
            self::$codigoCobro = self::$cobro->id;
        }
        $pdf = new ReciboPago();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        if($data["formato"] === 'recibo'){
            $this->bodyCita($pdf);
        }else{
            $this->body($pdf);
        }
        $pdf->Output();
    }

    public function Header()
    {
        $Y= 12;
        $this->SetFont('Arial', 'B', 9);
        $logo = public_path() . "/images/logo.png";
        $this->Image($logo, 8, 7, -400);
        $this->Rect(5, 5, 30, 22);
        $this->Rect(35, 5, 80, 22);
        $this->SetXY(35, 8);
        $this->Cell(80, 4, utf8_decode('SUMIMEDICAL S.A.S'), 0, 0, 'C');
        $this->SetXY(35, $Y);
        $this->Cell(80, 4, utf8_decode('NIT: 900033371'), 0, 0, 'C');
        $this->SetXY(35, $Y + 4);
        $this->Cell(80, 4, utf8_decode('Carrera 80 c Nùmero 32EE-65'), 0, 0, 'C');
        $this->SetXY(35, $Y + 8);
        $this->Cell(80, 4, utf8_decode('Telefono: 5201040'), 0, 0, 'C');
        $this->SetXY(35, $Y + 12);

        $Y = 8;
        $this->SetFont('Arial', 'B', 9);
        $this->Rect(115, 5, 88, 22);
        $this->SetXY(115, $Y);
        $this->Cell(88, 4, utf8_decode('Recibo de Caja'), 0, 0, 'C');
        $this->SetXY(115, $Y + 4);
        $this->Cell(88, 4, utf8_decode("Numero: ".self::$codigoCobro), 0, 0, 'C');
        $this->SetXY(115, $Y + 8);
        $this->Cell(88, 4, utf8_decode("Fecha: ".date("Y-m-d")), 0, 0, 'C');
        $this->SetXY(115, $Y + 12);
        $this->Cell(88, 4, utf8_decode("Sede: Medellin"), 0, 0, 'C');

        $this->SetFont('Arial', 'B', 9);
        $this->SetXY(5, 28);
        $this->Cell(198, 4, utf8_decode('INFORMACIÓN DEL AFILIADO'), 0, 0, 'C', 0);
        $this->SetXY(5, 33);

        $this->SetFillColor(216, 216, 216);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(34, 4, utf8_decode('Identificacón'), 1, 0, 'C', 1);
        $this->Cell(96, 4, utf8_decode('Nombre'), 1, 0, 'C', 1);
        $this->Cell(24, 4, utf8_decode('Edad'), 1, 0, 'C', 1);
        $this->Cell(44, 4, utf8_decode('Tipo'), 1, 0, 'C', 1);
        $this->Ln();
        $this->SetX(5);
        $this->SetFont('Arial', '', 8);
        $this->Cell(34, 4, self::$paciente->Num_Doc, 1, 0, 'C');
        $this->Cell(96, 4, utf8_decode(self::$paciente->Primer_Nom." ".self::$paciente->SegundoNom." ".self::$paciente->Primer_Ape." ".self::$paciente->Segundo_Ape), 1, 0, 'C');
        $this->Cell(24, 4, self::$paciente->Edad_Cumplida, 1, 0, 'C');
        $this->Cell(44, 4, self::$paciente->Tipo_Afiliado, 1, 0, 'C', 0);
        $this->SetXY(5,44);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(34, 4, "Grupo de ingresos", 1, 0, 'C',1);
        $this->SetFont('Arial', '', 8);
        switch (self::$paciente->nivel){
            case 1:
                $this->Cell(10, 4, "A", 0, 0, 'C');
                break;
            case 2:
                $this->Cell(10, 4, "B", 0, 0, 'C');
                break;
            case 3:
                $this->Cell(10, 4, "C", 0, 0, 'C');
                break;
        }
        $this->SetXY(143,44);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(60, 4, "VALOR:     $ ".self::$valorTotal, 1, 0, 'C');
    }

    public function Footer()
    {
        $this->SetXY(5,103);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(198,7,"FORMA DE PAGO",1,0,'C');
        $this->Ln();
        $this->SetX(5);
        $this->Cell(99,5,"Valor ".self::$cobro->tipo_cobro." :  ",1,0,'R');
        $this->SetFont('Arial', '', 8);
        $this->Cell(99,5,"$ ".self::$valorTotal,1,0,'L');
        $this->SetXY(104,130);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(49.5,5,"Usuario Horus",1,0,'R');
        $this->SetFont('Arial', '', 8);
        $this->Cell(49.5,5,utf8_decode(auth()->user()->name . " " . auth()->user()->apellido),1,0,'L');
    }

    public function body($pdf)
    {
        $pdf->SetXY(5,51);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(34, 4, "Cod CUPS", 1, 0, 'C');
        $pdf->Cell(164, 4, "Procedimientos Autorizados", 1, 0, 'C');

        $pdf->SetXY(5,55);
        $pdf->SetFont('Arial', '', 8);
        foreach (self::$data["cups"] as $key => $tipos) {
            foreach ($tipos as $cups) {
                foreach ($cups as $cup) {
                    $cupOrden = Cuporden::select(["c.Codigo", "c.Nombre", "cupordens.Cantidad", "cupordens.Observacionorden"])->join("cups as c", "c.id", "cupordens.Cup_id")->where('cupordens.Cup_id', $cup["cup_id"])->where('cupordens.Orden_id', self::$orden->id)->first();
                    $pdf->SetX(5);
                    $pdf->Cell(34, 4, $cupOrden->Codigo, 1, 0, 'C');
                    $pdf->Cell(164, 4, utf8_decode(strtolower($cupOrden->Nombre)), 1, 0, 'C');
                    $pdf->Ln();
                }
            }
            if(array_key_last (self::$data["cups"]) != $key){
                $pdf->AddPage();
            }
        }
    }
    public function bodyCita($pdf)
    {
        $pdf->SetXY(5,51);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(34, 4, "Codigo", 1, 0, 'C');
        $pdf->Cell(164, 4, "Procedimientos", 1, 0, 'C');

        $pdf->SetXY(5,55);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(5);
        $pdf->Cell(34, 4, self::$cita->id, 1, 0, 'C');
        $pdf->Cell(164, 4, utf8_decode('Consulta '.strtolower(self::$cita->Finalidad)), 1, 0, 'C');
        $pdf->Ln();
    }
}
