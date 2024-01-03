<?php
namespace App\Formats;

use Codedge\Fpdf\Fpdf\Fpdf;
use App\Modelos\Antecedenteocupacional;



class HistoriaOcupacional extends FPDF
{
    public static $data;
    public static $antecedentes;
    public function generar($datos)
    {
        // dd($datos);
        self::$data = $datos;
        self::$antecedentes = Antecedenteocupacional::where('cita_paciente_id',$datos['cita_paciente_id'])->get();
        $pdf = new HistoriaOcupacional('p', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $this->body($pdf);
        $pdf->Output();
    }

    public function body($pdf){
        $Y = 40;
        #IMAGEN Y DATOS EMPRESARIALES
        $pdf->SetFont('Arial', 'B', 9);
        $logo = "images/logo.png";
        $pdf->Image($logo, 16, 9, -220);
        #NIT
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetXY(8, 37);
        $pdf->Cell(60, 3, utf8_decode('NIT:900033371-4 Res: 004 '), 0, 0, 'C');
        #LOGO FINAL HOJA 1
        // $logo = "fpdf\Imag\LOGO FINAL.jpg";
        // $pdf->Image($logo, 0, 291, -95);


        $pdf->SetXY(25, 16);
        $pdf->SetFont('Arial', 'B', 30);
        $pdf->Cell(192, 4, utf8_decode('HISTORIA CLÍNICA'), 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetXY(25, 23);
        $pdf->Cell(192, 4, utf8_decode('EVALUACIONES MÉDICAS OCUPACIONALES'), 0, 0, 'C');
        $pdf->SetXY(72, 31);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(44, 4, utf8_decode('CONSULTA REALIZADA:'), 0, 0, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell(56, 4, utf8_decode(self::$data['salud_ocupacional']),0,'L'); #es el tipo de consulta, que solo aparezca el que escogio
        $pdf->SetXY(70, 35);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(46, 4, utf8_decode('FECHA DE CONSULTA:'), 0, 0, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell(46, 4, utf8_decode(self::$data['Datetimeingreso']),0,'L'); #Feha y hora
        $pdf->SetXY(72, 39);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(16, 4, utf8_decode('CIUDAD:'), 0, 0, 'l');
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell(46, 4, utf8_decode('Medellín'),0,'L'); #Feha y hora

        // #DATOS PERSONALES
        $pdf->SetXY(18,45);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(175, 4, utf8_decode('DATOS DEL USUARIO'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->SetXY(18, 49);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(40, 4, utf8_decode('NOMBRE COMPLETO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(47, 4, utf8_decode(self::$data['Primer_Nom']. ' '. self::$data['SegundoNom']. ' '. self::$data['Primer_Ape']. ' '. self::$data['Segundo_Ape']), 1, 'l');
        $pdf->SetXY(105,49);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(42, 4, utf8_decode('IDENTIFICACIÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(46, 4, utf8_decode(self::$data['Num_Doc']), 1, 'l');
        $pdf->Ln();

        $pdf->SetXY(18,53);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(40, 4, utf8_decode('FECHA DE NACIMIENTO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(47, 4, utf8_decode(self::$data['Fecha_Naci']),1, 'l'); #SOLO FECHA
        $pdf->SetXY(105,53);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(42, 4, utf8_decode('LUGAR DE NACIMIENTO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(46, 4, utf8_decode('') ,1, 'l');
        $pdf->Ln();

        $pdf->SetXY(18,57);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(40, 4, utf8_decode('EDAD'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(47, 4, utf8_decode(self::$data['Edad_Cumplida']), 1, 'l');
        $pdf->SetXY(105,57);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(42, 4, utf8_decode('SEXO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(46, 4, utf8_decode(self::$data['Sexo']), 1, 'l');
        $pdf->Ln();

        $pdf->SetXY(18,61);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(40, 4, utf8_decode('ESTADO CIVIL'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(47, 4, utf8_decode(self::$data['estado_civil']), 1, 'l');
        $pdf->SetXY(105,61);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(42, 4, utf8_decode('DIRECCIÓN DE RESIDENCIA'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(46, 4, utf8_decode(self::$data['Direccion_Residencia']), 1, 'l');
        $pdf->Ln();

        $pdf->SetXY(18,65);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(40, 4, utf8_decode('TELÉFONO DEL DOMICILIO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(47, 4, utf8_decode(self::$data['Telefono']), 1, 'l');;
        $pdf->SetXY(105,65);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(42, 4, utf8_decode('CORREO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(46, 4, utf8_decode(self::$data['Correo1']), 1, 'l');

        $pdf->SetXY(18,69);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(40, 4, utf8_decode('PROFESIÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(47, 4, utf8_decode(self::$data['Nivelestudio']), 1, 'l');;
        $pdf->SetXY(105,69);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(42, 4, utf8_decode('CARGO A DESEMPEÑAR'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(46, 4, utf8_decode(self::$data['cargo_laboral']), 1, 'l');

        // $pdf->SetXY(18,73);
        // $pdf->SetFont('Arial', 'B', 7);
        // $pdf->Cell(40, 4, utf8_decode('A.R.L. ANTERIOR'), 1, 0, 'l');
        // $pdf->SetFont('Arial', '', 7);
        // $pdf->MultiCell(47, 4, utf8_decode(''), 1, 'l');;
        // $pdf->SetXY(105,73);
        // $pdf->SetFont('Arial', 'B', 7);
        // $pdf->Cell(42, 4, utf8_decode('E.P.S. ANTERIOR'), 1, 0, 'l');
        // $pdf->SetFont('Arial', '', 7);
        // $pdf->MultiCell(46, 4, utf8_decode(''), 1, 'l');
        if (self::$data['salud_ocupacional'] === 'Consulta Medica') {
            $pdf->SetXY(18,79);
            $pdf->SetXY(18,79);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(175, 4, utf8_decode('ANTECEDENTES OCUPACIONALES'), 1, 0, 'C',1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);


            $pdf->SetXY(18,83);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(37, 8, utf8_decode('EMPRESA'), 1, 0, 'C',1);
            $pdf->Cell(37, 8, utf8_decode('CARGO'), 1, 0,'C',1);
            $pdf->Cell(47, 4, utf8_decode('FACTORES DE RIESGO'), 1, 0,'C',1);
            $pdf->Cell(34, 8, utf8_decode('TIEMPO'), 1, 0,'C',1);
            $pdf->Cell(20, 8, utf8_decode('USO E.P.P.'), 1, 0,'C',1);
            $pdf->Ln();

            $pdf->SetXY(92,87);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(4, 4, utf8_decode('F'), 1, 0,'C',1);
            $pdf->Cell(4, 4, utf8_decode('Q'), 1, 0,'C',1);
            $pdf->Cell(4, 4, utf8_decode('B'), 1, 0,'C',1);
            $pdf->Cell(7, 4, utf8_decode('ERG'), 1, 0,'C',1);
            $pdf->Cell(7, 4, utf8_decode('PSIC'), 1, 0,'C',1);
            $pdf->Cell(7, 4, utf8_decode('MEC'), 1, 0,'C',1);
            $pdf->Cell(7, 4, utf8_decode('ELEC'), 1, 0,'C',1);
            $pdf->Cell(7, 4, utf8_decode('OTRO'), 1, 0,'C',1);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', 8);

            foreach(self::$antecedentes as $antecedente){
                if($antecedente['antecedente_empresa']){
                    $pdf->SetX(18);
                    $pdf->Cell(37, 4, utf8_decode($antecedente['antecedente_empresa']),   1, 0, 'C');
                    $pdf->SetFont('Arial', '', 4);
                    $pdf->Cell(37, 4, utf8_decode($antecedente['antecedente_cargo']),   1, 0, 'C');
                    $pdf->SetFont('Arial', '', 8);
                    $pdf->Cell(4, 4, utf8_decode($antecedente['f']),   1, 0, 'C');
                    $pdf->Cell(4, 4, utf8_decode($antecedente['q']),   1, 0, 'C');
                    $pdf->Cell(4, 4, utf8_decode($antecedente['b']),   1, 0, 'C');
                    $pdf->Cell(7, 4, utf8_decode($antecedente['erg']),   1, 0, 'C');
                    $pdf->Cell(7, 4, utf8_decode($antecedente['psic']),   1, 0, 'C');
                    $pdf->Cell(7, 4, utf8_decode($antecedente['mec']),   1, 0, 'C');
                    $pdf->Cell(7, 4, utf8_decode($antecedente['elec']),   1, 0, 'C');
                    $pdf->Cell(7, 4, utf8_decode($antecedente['otro']),   1, 0, 'C');
                    $pdf->Cell(34, 4, utf8_decode($antecedente['tiempo']),  1, 0, 'C');
                    $pdf->Cell(20, 4, utf8_decode($antecedente['uso_e_p_p']),  1, 0, 'C');
                    $pdf->Ln();
                }
            }
                $pdf->Ln();

            $pdf->SetX(18);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(62, 4, utf8_decode('¿HA SUFRIDO ACCIDENTES DE TRABAJO?'), 0, 0, 'l');
            $pdf->SetX(92);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(4, 4, utf8_decode('X'), 1, 0,'C');
            $pdf->Cell(6, 4, utf8_decode('SI'), 0, 0,'C');
            $pdf->SetX(102);
            $pdf->Ln();
            $pdf->Ln();

            $pdf->SetX(18);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(17, 8, utf8_decode('FECHA'), 1, 0, 'C',1);
            $pdf->Cell(27, 8, utf8_decode('EMPRESA'), 1, 0,'C',1);
            $pdf->Cell(37, 8, utf8_decode('FACTOR  RIESGO'),1, 0,'C',1);
            $pdf->Cell(27, 8, utf8_decode('TIPO LESIÓN'), 1, 0,'C',1);
            $pdf->Cell(28, 8, utf8_decode('PARTE AFECTADA'), 1, 0,'C',1);
            $pdf->Cell(18, 8, utf8_decode('DÍAS INCAP'), 1, 0,'C',1);
            $pdf->Cell(21, 8, utf8_decode('SECUELAS'), 1, 0,'C',1);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', 8);
            foreach(self::$antecedentes as $antecedente){
                if($antecedente['empresa_accidentes']){
                    $pdf->SetX(18);
                    $pdf->Cell(17, 4, utf8_decode($antecedente['fecha_accidentes']), 1, 0, 'C');
                    $pdf->Cell(27, 4, utf8_decode($antecedente['empresa_accidentes']), 1, 0, 'C');
                    $pdf->Cell(37, 4, utf8_decode($antecedente['causa']), 1, 0, 'C');
                    $pdf->Cell(27, 4, utf8_decode($antecedente['tipo_lesion']), 1, 0, 'C');
                    $pdf->Cell(28, 4, utf8_decode($antecedente['parte_afectada']), 1, 0, 'C');
                    $pdf->Cell(18, 4, utf8_decode($antecedente['dias_incap']), 1, 0, 'C');
                    $pdf->Cell(21, 4, utf8_decode($antecedente['secuelas']), 1, 0, 'C');
                    $pdf->Ln();
                }

            }
            $pdf->Ln();
            $pdf->SetX(18);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(62, 4, utf8_decode('¿HA SUFRIDO ENFERMEDADES PROFESIONALES?'), 0, 0, 'l');
            $pdf->SetX(92);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(4, 4, utf8_decode('X'), 1, 0,'C');
            $pdf->Cell(6, 4, utf8_decode('SI'), 0, 0,'C');
            $pdf->SetX(102);
            $pdf->Ln();
            $pdf->Ln();

            $pdf->SetX(18);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(17, 4, utf8_decode('FECHA'), 1, 0, 'C',1);
            $pdf->Cell(37, 4, utf8_decode('EMPRESA'), 1, 0,'C',1);
            $pdf->Cell(37, 4, utf8_decode('DIAGNÓSTICO'), 1, 0,'C',1);
            $pdf->Cell(42, 4, utf8_decode('REUBICACIÓN'), 1, 0,'C',1);
            $pdf->Cell(42, 4, utf8_decode('INDEMNIZACIÓN'), 1, 0,'C',1);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', 8);

            foreach(self::$antecedentes as $antecedente){
                if($antecedente['diagnostico']){
                    $pdf->SetX(18);
                    $pdf->Cell(17, 4, utf8_decode($antecedente['fecha']),  1, 0, 'C');
                    $pdf->Cell(37, 4, utf8_decode($antecedente['empresa']),  1, 0, 'C');
                    $pdf->Cell(37, 4, utf8_decode($antecedente['diagnostico']),  1, 0, 'C');
                    $pdf->Cell(42, 4, utf8_decode($antecedente['reubicacion']),  1, 0, 'C');
                    $pdf->Cell(42, 4, utf8_decode($antecedente['indemnizacion']),  1, 0, 'C');
                    $pdf->Ln();
                }
            }
            $pdf->Ln();
            $pdf->SetX(18);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(62, 4, utf8_decode('ANTECEDENTES PATOLÓGICOS: '), 0, 0, 'l');
            $pdf->Ln();
            $pdf->Ln();
            $pdf->SetX(18);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(12, 4, utf8_decode('Sexo:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(163, 4, utf8_decode(self::$data['Sexo']),  0, 0, 'l');
            $pdf->Ln();
            $pdf->SetX(18,141);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(21, 4, utf8_decode('Estado Civil:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(154, 4, utf8_decode(self::$data['estado_civil']), 0, 'l');

            $pdf->SetX(18,146);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(34, 4, utf8_decode('Nivel De Escolaridad:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(141, 4, utf8_decode(self::$data['Nivelestudio']), 0, 'l');

            $pdf->SetX(18,151);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(25, 4, utf8_decode('Cargo Laboral:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(150, 4, utf8_decode(self::$data['cargo_laboral']), 0, 'l');

            $pdf->SetX(18,156);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(36, 4, utf8_decode('Composicion Familiar:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(139, 4, utf8_decode(self::$data['composicion_familiar']), 0, 'l');

            $pdf->SetX(18,161);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(16, 4, utf8_decode('Vivienda:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(159, 4, utf8_decode(self::$data['vivienda']), 0, 'l');

            $pdf->SetX(18,166);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(48, 4, utf8_decode('Numero De Personas A Cargo:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(127, 4, utf8_decode(self::$data['personas_a_cargo']), 0, 'l');

            $pdf->SetX(18,171);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(36, 4, utf8_decode('Tipo De Contratacíon:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(139, 4, utf8_decode(self::$data['tipo_contratacion']), 0, 'l');

            $pdf->SetX(18,176);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(44, 4, utf8_decode('Antigüedad En La Empresa:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(131, 4, utf8_decode(self::$data['antiguedad_en_empresa']), 0, 'l');

            $pdf->SetX(18,181);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(41, 4, utf8_decode('Antigüedad Cargo Actual:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(134, 4, utf8_decode(self::$data['antiguedad_cargo_actual']), 0, 'l');

            $pdf->SetX(18,186);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(50, 4, utf8_decode('Numero De Cursos A Su Cargo:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(125, 4, utf8_decode(self::$data['numero_cursos_a_cargo']), 0, 'l');

            $pdf->SetX(18,191);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(41, 4, utf8_decode('Consumo De Cigarrillo:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(134, 4, utf8_decode(self::$data['fuma']), 0, 'l');

            $pdf->SetX(18,196);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(31, 4, utf8_decode('Actividad Fisica:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(144, 4, utf8_decode(self::$data['practica_deporte']), 0, 'l');

            $pdf->SetX(18,201);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(55, 4, utf8_decode('Consumo De Bebidas Alcoholicas:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(120, 4, utf8_decode(self::$data['alcohol']), 0, 'l');

            $pdf->SetX(18,206);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(23, 4, utf8_decode('Tipo Examen:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(152, 4, utf8_decode(self::$data['tipoExamen']), 0, 'l');

            $pdf->SetX(18,211);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(77, 4, utf8_decode('Exposición A Factores De Riesgo Ocupacionales:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(98, 4, utf8_decode(self::$data['factoresRiesgo']), 0, 'l');

            $pdf->SetX(18,216);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(48, 4, utf8_decode('Antecedentes De Enfermedad:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(127, 4, utf8_decode(self::$data['antecedentesenfermedad']), 0, 'l');

            $pdf->SetX(18,221);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(43, 4, utf8_decode('Origen De Enfermedades:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(132, 4, utf8_decode(self::$data['origenEnfermedades']), 0, 'l');

            $pdf->SetX(18,226);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(65, 4, utf8_decode('Antecedentes De Accidentes De Trabajo:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(110, 4, utf8_decode(self::$data['antecedentedetrabajo']), 0, 'l');

            $pdf->SetX(18,231);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(60, 4, utf8_decode('Antecedentes De Enfermedad Laboral:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(115, 4, utf8_decode(self::$data['antecedenteenfermedadlaboral']), 0, 'l');

            $pdf->SetX(18,236);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(43, 4, utf8_decode('Enfermedad Común Previa:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(132, 4, utf8_decode(self::$data['enfermedadComun']), 0, 'l');

            $pdf->SetX(18,241);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(40, 4, utf8_decode('Antecedentes Familiares:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(135, 4, utf8_decode(self::$data['antecedentesfamiliares']), 0, 'l');

            $pdf->SetX(18,246);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(42, 4, utf8_decode('Patología De Antecedente:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(133, 4, utf8_decode(self::$data['patologiaAntecedente']), 0, 'l');

            $pdf->SetX(18,251);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(41, 4, utf8_decode('Indice De Masa Corporal:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(134, 4, utf8_decode(self::$data['masaCorporal']), 0, 'l');

            $pdf->SetX(18,256);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(42, 4, utf8_decode('Patplogía Osteomuscular:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(133, 4, utf8_decode(self::$data['patologiaOsteomuscular']), 0, 'l');

            $pdf->SetX(18,261);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(42, 4, utf8_decode('Patología Cardiovascular:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(133, 4, utf8_decode(self::$data['patologiaCardiovascular']), 0, 'l');

            $pdf->SetX(18,266);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(37, 4, utf8_decode('Patología Metabolica:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(138, 4, utf8_decode(self::$data['patologiaMetabolica']), 0, 'l');

            $pdf->SetX(18,271);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(58, 4, utf8_decode('Diagnóstico Infeccioso o Parasitaria:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(117, 4, utf8_decode(self::$data['infecciososParasitaria']), 0, 'l');


            $pdf->Line(18, 79, 18, 276);
            $pdf->Line(193, 79, 193, 276);
            $pdf->Line(18, 276, 193, 276);

            $pdf->SetX(18,274);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(78, 4, utf8_decode('Patología De Sangre Y Organos Hematopoyeticos:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(97, 4, utf8_decode(self::$data['patologiaSangre']), 0, 'l');

            #MARGENES HOJA 2
            $pdf->Line(18, 8, 18, 276);
            $pdf->Line(193, 8, 193, 276);
            $pdf->Line(18, 276, 193, 276);
            $pdf->Line(18, 8, 193, 8);

            $pdf->SetX(18,15);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(68, 4, utf8_decode('Trastornos Mentales Y Del Comportamento:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(107, 4, utf8_decode(self::$data['trastronosMentales']), 0, 'l');

            $pdf->SetX(18,20);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(50, 4, utf8_decode('Patologías Del Sistema Nerviso:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(125, 4, utf8_decode(self::$data['patologiaNervioso']), 0, 'l');

            $pdf->SetX(18,25);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(52, 4, utf8_decode('Patología De Ojos Y Sus Anexos:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(123, 4, utf8_decode(self::$data['patologiaOjo']), 0, 'l');

            $pdf->SetX(18,30);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(70, 4, utf8_decode('Patología De Oidos Y La Apofisis Mastoides:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(105, 4, utf8_decode(self::$data['patologiaOido']), 0, 'l');

            $pdf->SetX(18,35);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(57, 4, utf8_decode('Patologías Del Sistema Respiratorio:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(118, 4, utf8_decode(self::$data['patologiaRespiratorio']), 0, 'l');

            $pdf->SetX(18,40);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(52, 4, utf8_decode('Patología Del Aparato Digestivo:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(123, 4, utf8_decode(self::$data['patologiaDigestivo']), 0, 'l');

            $pdf->SetX(18,45);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(67, 4, utf8_decode('Patología De La Piel o Tegido Subcutaneo:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(108, 4, utf8_decode(self::$data['patologiaPiel']), 0, 'l');

            $pdf->SetX(18,50);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(51, 4, utf8_decode('Patologías Del Aparato Urinario:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(124, 4, utf8_decode(self::$data['patologiaUrinario']), 0, 'l');

            $pdf->SetX(18,55);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(48, 4, utf8_decode('Malformación Congenitas:'), 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(108, 4, utf8_decode(self::$data['malformacionCongenitas']), 0, 'l');
            $pdf->Ln();
            $pdf->SetX(18,68);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(62, 4, utf8_decode('OBSERVACIONES DE ANTECEDENTES:'), 0, 0, 'l');
            $pdf->SetX(18, 72);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(175, 3, utf8_decode(self::$data['condiciones_generales']), 0, 'C');
            $pdf->Ln();
            $pdf->SetXY(18,75);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(175, 4, utf8_decode('VACUNAS'), 1, 0, 'C', 1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);
            #CUADRO
            $pdf->Line(18, 79, 18, 88);#LINEA SUPERIOR HORIZONTAL
            $pdf->Line(73, 79, 73, 88); #LINEA VERTICAL 1
            $pdf->Line(18, 88, 193, 88); #LINEA INFERIOR HORIZONTAL
            $pdf->Line(118, 79, 118, 88);
            $pdf->Line(193, 79, 193, 88);
            $pdf->Line(18, 79, 193, 79);

            $pdf->SetXY(18,79);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(55, 4, utf8_decode('Hepatitis B'), 1, 0, 'l');

            $pdf->SetXY(18,84);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(10, 4, utf8_decode('Dosis: '), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(10, 4, utf8_decode(self::$data['dosis_v']), 0, 'C');

            $pdf->SetXY(40,84);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(20, 4, utf8_decode('Última Dosis:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(10, 4, utf8_decode(self::$data['ultima_dosis']), 0, 'C');

            $pdf->SetXY(75,79);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(10, 4, utf8_decode('T.T.'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(10, 4, utf8_decode(self::$data['t_t']), 0, 'C');

            $pdf->SetXY(75,84);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(10, 4, utf8_decode('Dosis: '), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(10, 4, utf8_decode(self::$data['dosi']), 0, 'C');

            $pdf->SetXY(95,79);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(10, 4, utf8_decode('T.D.'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(10, 4, utf8_decode(self::$data['t_d']), 0, 'C');

            $pdf->SetXY(120,79);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(35, 4, utf8_decode('Otras (incluida Varicela): '), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(30, 4, utf8_decode(self::$data['otras']), 0, 'C');

            $pdf->SetXY(120,84);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(14, 4, utf8_decode('Fechas:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(20, 4, utf8_decode(self::$data['Fechas']), 0, 'C');

            #ANTECEDENTE GINECOSTETRICO
            $pdf->SetXY(18,88);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(175, 4, utf8_decode('ANTECEDENTES GINECO OBSTÉTRICOS'), 1, 0, 'C', 1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetXY(18, 92);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(175, 3, utf8_decode(  self::$data['ginecoobstetricos']), 0, 'C');

            $pdf->SetXY(18,97);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(16, 4, utf8_decode('Menarquia:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(15, 4, utf8_decode(self::$data['menarquia']), 0, 'C');

            $pdf->SetXY(50,97);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(11, 4, utf8_decode('Ciclos:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(15, 4, utf8_decode(self::$data['ciclos']), 0, 'C');

            $pdf->SetXY(77,97);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(11, 4, utf8_decode('FUM:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(15, 4, utf8_decode(self::$data['fum']), 0, 'C');

            $pdf->SetXY(18,103);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(7, 4, utf8_decode('G:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(10, 4, utf8_decode(self::$data['g']), 0, 'C');

            $pdf->SetXY(37,103);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(7, 4, utf8_decode('P:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(10, 4, utf8_decode(self::$data['p']), 0, 'C');

            $pdf->SetXY(55,103);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(7, 4, utf8_decode('C:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(10, 4, utf8_decode(self::$data['c']), 0, 'C');

            $pdf->SetXY(73,103);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(7, 4, utf8_decode('A:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(10, 4, utf8_decode(self::$data['a']), 0, 'C');

            $pdf->SetXY(91,103);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(7, 4, utf8_decode('V:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(10, 4, utf8_decode(self::$data['v']), 0, 'C');

            $pdf->SetXY(18,108);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(20, 4, utf8_decode('Planificación:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(155, 4, utf8_decode(self::$data['planificacion']), 0, 'C');

            $pdf->SetXY(18,113);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(20, 4, utf8_decode('Método:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(155, 4, utf8_decode(self::$data['metodo']), 0, 'C');

            $pdf->SetXY(18,118);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(40, 4, utf8_decode('FECHA ÚLTIMA CITOLOGÍA:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(15, 4, utf8_decode(self::$data['ultima_citologia']), 0, 'C');

            $pdf->SetXY(75,118);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(18, 4, utf8_decode('Resultado:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(75, 4, utf8_decode(self::$data['resultado']), 0, 'C');

            $pdf->SetXY(170,118);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(13, 4, utf8_decode('Tratada:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(10, 4, utf8_decode(self::$data['tratada']), 0, 'C');

            $pdf->SetXY(18,123);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(175, 4, utf8_decode('HÁBITOS'), 1, 0, 'C',1 );
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);

            #TABLA DE ACTIVIDAD FISICA
            $pdf->SetXY(18,127);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(175, 4, utf8_decode('¿REALIZA ACTIVIDAD FÍSICA?'), 1, 0, 'C', 1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);

            $pdf->SetXY(18,131);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(65, 4, utf8_decode('¿Practica Deporte o ejercicio?'), 1, 0, 'C');
            $pdf->SetXY(18,135);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(65, 4, utf8_decode(self::$data['practica_deporte']), 1, 'l');

            $pdf->SetXY(83,131);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(50, 4, utf8_decode('Cuál'), 1, 0, 'C');
            $pdf->SetXY(83,135);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['cual']), 1, 'l');

            $pdf->SetXY(133,131);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(60, 4, utf8_decode('Regularidad'), 1, 0, 'C');
            $pdf->SetXY(133,135);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(60, 4, utf8_decode(self::$data['regularidad']), 1, 'l');

            #TABLA EXPOSICION AL TABACO
            $pdf->SetXY(18,139);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(175, 4, utf8_decode('¿TIENE EXPOSICIÓN AL TABACO?'), 1, 0, 'C', 1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);

            $pdf->SetXY(18,143);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(24, 4, utf8_decode('¿Fuma?'), 1, 0, 'C');
            $pdf->SetXY(18,147);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(24, 4, utf8_decode(self::$data['fuma']), 1, 'l');

            $pdf->SetXY(42,143);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(25, 4, utf8_decode('¿Fumó?'), 1, 0, 'C');
            $pdf->SetXY(42,147);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(25, 4, utf8_decode(self::$data['fumo']), 1, 'l');

            $pdf->SetXY(67,143);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(40, 4, utf8_decode('Años de Fumador'), 1, 0, 'C');
            $pdf->SetXY(67,147);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(40, 4, utf8_decode(self::$data['anos_fumador']), 1, 'l');

            $pdf->SetXY(107,143);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(43, 4, utf8_decode('No. Cigarrillos al día'), 1, 0, 'C');
            $pdf->SetXY(107,147);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(43, 4, utf8_decode(self::$data['cigarrillos_al_dia']), 1, 'l');

            $pdf->SetXY(150,143);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(43, 4, utf8_decode('¿Hace cuánto no fuma?'), 1, 0, 'C');
            $pdf->SetXY(150,147);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(43, 4, utf8_decode(self::$data['hace_cuanto_no_fuma']), 1, 'l');

            #TABLA DE CONSUMO SPA
            $pdf->SetXY(18,151);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(175, 4, utf8_decode('¿CONSUME SPA?'), 1, 0, 'C', 1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);

            $pdf->SetXY(18,155);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(90, 4, utf8_decode('Consumo de Sustancias Psico Activas'), 1, 0, 'C');
            $pdf->SetXY(18,159);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(90, 4, utf8_decode(self::$data['sustancias_psico_activas']), 1, 'l');

            $pdf->SetXY(108,155);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(85, 4, utf8_decode('Cuál (es)'), 1, 0, 'C');
            $pdf->SetXY(108,159);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(85, 4, utf8_decode(self::$data['cuales']), 1, 'l');

            #TABLA DE CONSUMO DE LICOR
            $pdf->SetXY(18,163);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(175, 4, utf8_decode('ALCOHOL'), 1, 0, 'C', 1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);

            $pdf->SetXY(18,167);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(40, 4, utf8_decode('¿Consume Licor?'), 1, 0, 'C');
            $pdf->SetXY(18,171);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(40, 4, utf8_decode(self::$data['alcohol']), 1, 'l');

            $pdf->SetXY(58,167);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(50, 4, utf8_decode('FRECUENCIA'), 1, 0, 'C');
            $pdf->SetXY(58,171);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['frecuencia']), 1, 'l');

            $pdf->SetXY(108,167);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(85, 4, utf8_decode('Tipo'), 1, 0, 'C');
            $pdf->SetXY(108,171);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(85, 4, utf8_decode(self::$data['tipo']), 1, 'l');

            #REVISION POR SISTEMAS
            $pdf->SetXY(18,175);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(175, 4, utf8_decode('REVISIÓN POR SISTEMAS'), 1, 0, 'C', 1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);

            #Si esta en Null debe aparecer NORMAL
            $pdf->SetXY(18,180);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(38, 4, utf8_decode('Cabeza y ORL:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(10, 4, utf8_decode(self::$data['cabeza_y_orl']), 0, 'l');

            $pdf->SetXY(18,185);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(38, 4, utf8_decode('Sistema Neurológico:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(10, 4, utf8_decode(self::$data['sistema_neurologico']), 0, 'l');

            $pdf->SetXY(18,190);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(38, 4, utf8_decode('Sistema Cardiopulmonar:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(10, 4, utf8_decode(self::$data['sistema_cardiopulmonar']), 0, 'l');

            $pdf->SetXY(18,195);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(38, 4, utf8_decode('Sistema Digestivo:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(10, 4, utf8_decode(self::$data['sistema_digestivo']), 0, 'l');

            $pdf->SetXY(18,200);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(45, 4, utf8_decode('Sistema Musculo esquelético:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(130, 4, utf8_decode(self::$data['sistema_musculo_esqueletico']),0, 'l');

            $pdf->SetXY(18,205);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(45, 4, utf8_decode('Sistema Genitourinario:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(130, 4, utf8_decode(self::$data['sistema_genitourinario']), 0, 'l');

            $pdf->SetXY(18,210);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(45, 4, utf8_decode('Piel y Faneras:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(130, 4, utf8_decode(self::$data['piel_y_faneras']), 0, 'l');
            $pdf->SetFont('Arial', 'B', 8);

            $pdf->SetXY(18,215);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(15, 4, utf8_decode('OTROS:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(155, 4, utf8_decode(self::$data['otros']), 0, 'l');

            #SIGNOS VITALES
            $pdf->SetXY(18,220);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(175, 4, utf8_decode('EXAMEN FÍSICO'), 1, 0, 'C', 1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);

            $pdf->SetXY(18, 225);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(162, 4, utf8_decode('Condiciones generales:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetXY(18, 230);
            $pdf->MultiCell(175, 4, utf8_decode(self::$data['condiciones_generales']), 1, 'L');

            $pdf->SetXY(18,235);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(31, 4, utf8_decode('Frecuencia Cardíaca:'), 0, 0, 'l');
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(10, 4, utf8_decode(self::$data['f_c']), 0, 'l');

            $pdf->SetXY(70,235);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(33, 4, utf8_decode('Frecuencia Respiratoria:'), 0, 0, 'l');
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(10, 4, utf8_decode(self::$data['f_r']), 0, 'l');

            $pdf->SetXY(151,235);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(20, 4, utf8_decode('Temperatura:'), 0, 0, 'l');
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(9, 4, utf8_decode(self::$data['t']), 0, 'l');
            $pdf->SetXY(181,235);
            $pdf->Cell(28, 4, utf8_decode('°C'), 0, 0, 'l');

            $pdf->SetXY(120,235);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(15, 4, utf8_decode('Sat. O2:'), 0, 0, 'l');
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(10, 4, utf8_decode(self::$data['sato2']), 0, 'l');

            $pdf->SetXY(18,240);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(27, 4, utf8_decode('Dominancia Motora:'), 0, 0, 'l');
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['dominancia_motora']), 0, 'l');

            $pdf->SetXY(18,245);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(18, 4, utf8_decode('Peso (Kg):'), 0, 0, 'l');
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(10, 4, utf8_decode(self::$data['peso']), 0, 'l');

            $pdf->SetXY(48,245);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(33, 4, utf8_decode('Talla (Centimetros):'), 0, 0, 'l');
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(10, 4, utf8_decode(self::$data['talla']), 0, 'l');

            $pdf->SetXY(93,245);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(9, 4, utf8_decode('IMC:'), 0, 0, 'l');
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(10, 4, utf8_decode(self::$data['imc']), 0, 'l');

            $pdf->SetXY(115,245);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(28, 4, utf8_decode('Clasificación:'), 0, 0, 'l');
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(30, 4, utf8_decode(self::$data['c_']), 0, 'l');

            $pdf->SetXY(18,274);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(27, 4, utf8_decode('1. Cabeza'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['cabeza']), 0, 'l');
            $pdf->Ln();

            $pdf->SetXY(18,15);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(27, 4, utf8_decode('2. Ojos'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['ojos']), 0, 'l');
            $pdf->Ln();

            $pdf->SetXY(18,20);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(27, 4, utf8_decode('3. Fondo de Ojos'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['fondo_de_ojos']), 0 ,'l');
            $pdf->Ln();

            $pdf->SetXY(18,25);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(27, 4, utf8_decode('4. Oídos'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['oidos']), 0, 'l');
            $pdf->Ln();

            $pdf->SetXY(18,30);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(27, 4, utf8_decode('5. Otoscopia'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['otoscopia']), 0, 'l');
            $pdf->Ln();

            $pdf->SetXY(18,35);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(27, 4, utf8_decode('6. Nariz'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['nariz']), 0, 'l');


            $pdf->SetXY(18,40);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(27, 4, utf8_decode('9. Boca'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['boca']), 0, 'l');
            $pdf->Ln();

            $pdf->SetXY(18,45);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(27, 4, utf8_decode('10. Dentadura'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['dentadura']), 0, 'l');
            $pdf->Ln();

            $pdf->SetXY(18,50);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(27, 4, utf8_decode('11. Faringe'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['faringe']), 0, 'l');
            $pdf->Ln();

            $pdf->SetXY(18,55);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(27, 4, utf8_decode('12. Cuello'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['cuello']), 0, 'l');
            $pdf->Ln();

            $pdf->SetXY(18,60);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(27, 4, utf8_decode('13. Mamas'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['mamas']), 0, 'l');
            $pdf->Ln();

            $pdf->SetXY(18,65);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(27, 4, utf8_decode('14. Tórax '), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['torax']), 0, 'l');
            $pdf->Ln();

            $pdf->SetXY(18,70);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(27, 4, utf8_decode('15. Corazón'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['corazon']), 0, 'l');
            $pdf->Ln();

            $pdf->SetXY(106,10);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(35, 4, utf8_decode('16. Pulmones'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['pulmones']), 0, 'l');
            $pdf->Ln();

            $pdf->SetXY(106,15);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(35, 4, utf8_decode('17. Columna'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['columna']), 0, 'l');
            $pdf->Ln();

            $pdf->SetXY(106,20);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(35, 4, utf8_decode('18. Abdomen'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['abdomen']), 0, 'l');
            $pdf->Ln();

            $pdf->SetXY(106,25);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(35, 4, utf8_decode('19. Genitales Externos'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['genitales_externos']), 0, 'l');
            $pdf->Ln();

            $pdf->SetXY(106,30);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(35, 4, utf8_decode('20. Miembros Sup.'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['miembros_sup']), 0, 'l');
            $pdf->Ln();

            $pdf->SetXY(106,35);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(35, 4, utf8_decode('21. Miembros Inf.'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['miembros_inf']), 0, 'l');
            $pdf->Ln();

            $pdf->SetXY(106,40);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(35, 4, utf8_decode('22. Reflejos '), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['reflejos']), 0, 'l');
            $pdf->Ln();

            $pdf->SetXY(106,45);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(35, 4, utf8_decode('23. Motilidad'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['motilidad']), 0, 'l');
            $pdf->Ln();

            $pdf->SetXY(106,50);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(35, 4, utf8_decode('24. Fuerza Muscular'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['fuerza_muscular']), 0, 'l');
            $pdf->Ln();

            $pdf->SetXY(106,55);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(35, 4, utf8_decode('25. Marcha'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['marcha']), 0, 'l');
            $pdf->Ln();

            $pdf->SetXY(106,60);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(35, 4, utf8_decode('26. Piel y Faneras'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(50, 4, utf8_decode(self::$data['piel_faneras']), 0, 'l');
            $pdf->Ln();

            $pdf->SetXY(18,75);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(27, 4, utf8_decode('DESCRIPCIÓN AMPLIACIÓN HALLAZGOS:'), 0, 0, 'l');
            $pdf->SetXY(18,80);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(175, 4, utf8_decode(self::$data['ampliacion_hallazgos']), 0, 'l');
            $pdf->Ln();

            }

        #LOGO FINAL HOJA 2
        if (self::$data['salud_ocupacional'] === 'Voz') {

            $pdf->SetXY(18,115);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(175, 4, utf8_decode('RIESGO COVID'), 1, 0, 'C',1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);

            $pdf->SetXY(18,119);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(175, 4, utf8_decode('ANTECEDENTES DE RIESGO Y EXPOSICIÓN'), 1, 0, 'C',1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);

            $pdf->SetXY(18,125);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(62, 4, utf8_decode('Desplazamientos en los últimos 14 días:'), 0, 0, 'l');

            $pdf->SetXY(92, 125);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(95, 125);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(102, 125);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(105, 125);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(18,130);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(8, 4, utf8_decode('País:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(20, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(50,130);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(12, 4, utf8_decode('Ciudad:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(20, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(87,130);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(34, 4, utf8_decode('Periodo de estadía: del '), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(15, 4, utf8_decode(''), 0, 'C');
            $pdf->SetXY(137,130);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(6, 4, utf8_decode('al'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(15, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,135);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(62, 4, utf8_decode('¿Tuvo contacto estrecho con un caso confirmado o probable de infección por COVID-19 en los últimos 14 días?:'), 0, 0, 'l');

            $pdf->SetXY(173, 135);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(176, 135);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(183, 135);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(186, 135);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(18,140);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(140, 4, utf8_decode('¿Es usted trabajador de la salud u otro personal del ámbito hospitalario que haya tenido contacto cercano con un caso comprobado o probable de infección por COVID-19 en los últimos 14 días?:'), 0, 'l');

            $pdf->SetXY(163, 143);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(166, 143);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(173, 143);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(176, 143);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(18,149);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(175, 4, utf8_decode('ANTECEDENTES CLÍNICOS Y DE HOSPITALIZACIÓN'), 1, 0, 'C',1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);

            $pdf->SetXY(18,154);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(140, 4, utf8_decode('¿Ha sido diagnosticado por COVID-19?'), 0, 'l');

            $pdf->SetXY(92, 154);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(95, 154);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(102, 154);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(105, 154);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(18,159);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(31, 4, utf8_decode('Fecha De Consulta'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(15, 4, utf8_decode(''), 0, 'C');
            $pdf->SetXY(70,159);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('Institución de salud:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(30, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,164);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(31, 4, utf8_decode('Fecha de alta por EPS'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(15, 4, utf8_decode(''), 0, 'C');
            $pdf->SetXY(70,164);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('Institución de salud:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(30, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,169);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(140, 4, utf8_decode('¿Está consumiendo medicamentos antinflamatorios o acetaminofén?:'), 0, 'l');

            $pdf->SetXY(120, 169);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(124, 169);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(130, 169);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(134, 169);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(18,174);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(175, 4, utf8_decode('MOTIVO DE CONSULTA'), 1, 0, 'C',1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetXY(18, 178);
            $pdf->MultiCell(175, 4, utf8_decode(self::$data['Motivoconsulta']), 1, 'L');

            $pdf->SetXY(18,182);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(175, 4, utf8_decode('FICHA EVALUACIÓN VOZ OCUPACIONAL'), 1, 0, 'C',1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);

            $pdf->SetXY(18,187);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(33, 4, utf8_decode('Tiempo en la empresa:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(15, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(80,187);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(35, 4, utf8_decode('Horas de trabajo diarias:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(15, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,192);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(140, 4, utf8_decode('Trabaja en otra institución'), 0, 'l');

            $pdf->SetXY(92, 192);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(95, 192);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(102, 192);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(105, 192);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(119,192);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(20, 4, utf8_decode('Obs:'), 0, 'l');
            $pdf->SetXY(129,192);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(64, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,197);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(140, 4, utf8_decode('Horas de trabajo'), 0, 'l');

            $pdf->SetXY(92, 197);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(95, 197);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(102, 197);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(105, 197);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(119,197);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(20, 4, utf8_decode('Obs:'), 0, 'l');
            $pdf->SetXY(129,90);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(64, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,202);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(70, 4, utf8_decode('Exposición a cambios drásticos de temperatura (calor – frio)'), 0, 'l');

            $pdf->SetXY(92, 203);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(95, 203);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(102, 203);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(105, 203);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(119,203);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(20, 4, utf8_decode('Obs:'), 0, 'l');
            $pdf->SetXY(129,203);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(64, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,210);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(70, 4, utf8_decode('Exposición a químicos'), 0, 'l');

            $pdf->SetXY(92, 210);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(95, 210);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(102, 210);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(105, 210);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(119,210);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(20, 4, utf8_decode('Obs:'), 0, 'l');
            $pdf->SetXY(129,210);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(64, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,215);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(70, 4, utf8_decode('Grita con frecuencia'), 0, 'l');

            $pdf->SetXY(92, 215);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(95, 215);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(102, 215);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(105, 215);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(119,215);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(20, 4, utf8_decode('Obs:'), 0, 'l');
            $pdf->SetXY(129,215);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(64, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,220);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(70, 4, utf8_decode('Debe usar su voz frecuentemente en el trabajo'), 0, 'l');

            $pdf->SetXY(92, 220);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(95, 220);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(102, 220);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(105, 220);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(119,220);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(20, 4, utf8_decode('Obs:'), 0, 'l');
            $pdf->SetXY(129,220);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(64, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,225);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(70, 4, utf8_decode('Alergias'), 0, 'l');

            $pdf->SetXY(92, 225);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(95, 225);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(102, 225);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(105, 225);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(119,225);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(20, 4, utf8_decode('Obs:'), 0, 'l');
            $pdf->SetXY(129,225);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(64, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,230);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(70, 4, utf8_decode('Toma algún medicamento'), 0, 'l');

            $pdf->SetXY(92, 230);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(95, 230);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(102, 230);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(105, 230);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(119,230);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(20, 4, utf8_decode('Obs:'), 0, 'l');
            $pdf->SetXY(129,230);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(64, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,235);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(70, 4, utf8_decode('Sufre alguna Enfermedad'), 0, 'l');

            $pdf->SetXY(92, 235);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(95, 235);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(102, 235);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(105, 235);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(119,235);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(20, 4, utf8_decode('Obs:'), 0, 'l');
            $pdf->SetXY(129,235);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(64, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,240);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(70, 4, utf8_decode('Consume alcohol'), 0, 'l');

            $pdf->SetXY(92, 240);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(95, 240);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(102, 240);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(105, 240);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(119,240);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(20, 4, utf8_decode('Obs:'), 0, 'l');
            $pdf->SetXY(129,240);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(64, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,245);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(70, 4, utf8_decode('Fuma'), 0, 'l');

            $pdf->SetXY(92, 245);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(95, 245);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(102, 245);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(105, 245);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(119,245);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(20, 4, utf8_decode('Obs:'), 0, 'l');
            $pdf->SetXY(129,245);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(64, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,250);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(70, 4, utf8_decode('Presenta Disfonías frecuente (disminución voz)'), 0, 'l');

            $pdf->SetXY(92, 250);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(95, 250);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(102, 250);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(105, 250);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(119,250);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(20, 4, utf8_decode('Obs:'), 0, 'l');
            $pdf->SetXY(129,250);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(64, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,255);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(70, 4, utf8_decode('Presenta afonía frecuente (pérdida total de la voz)'), 0, 'l');

            $pdf->SetXY(92, 255);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(95, 255);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(102, 255);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(105, 255);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(119,255);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(20, 4, utf8_decode('Obs:'), 0, 'l');
            $pdf->SetXY(129,255);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(64, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,260);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(70, 4, utf8_decode('Consume alimentos picantes o ácidos'), 0, 'l');

            $pdf->SetXY(92, 260);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(95, 260);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(102, 260);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(105, 260);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(119,260);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(20, 4, utf8_decode('Obs:'), 0, 'l');
            $pdf->SetXY(129,260);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(64, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,265);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(70, 4, utf8_decode('Consume bebidas muy calientes o muy frías'), 0, 'l');

            $pdf->SetXY(92, 265);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(95, 265);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(102, 265);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(105, 265);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(119,265);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(20, 4, utf8_decode('Obs:'), 0, 'l');
            $pdf->SetXY(129,265);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(64, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,274);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(70, 4, utf8_decode('Descansa su voz'), 0, 'l');

            $pdf->SetXY(92, 10);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(95, 10);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(102, 10);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(105, 10);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(119,10);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(20, 4, utf8_decode('Obs:'), 0, 'l');
            $pdf->SetXY(129,10);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(64, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,15);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(70, 4, utf8_decode('Hidrata durante la jornada laboral'), 0, 'l');

            $pdf->SetXY(92, 15);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(95, 15);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(102, 15);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(105, 15);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(119,15);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(20, 4, utf8_decode('Obs:'), 0, 'l');
            $pdf->SetXY(129,15);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(64, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,20);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(70, 4, utf8_decode('Dolor frecuente en la garganta'), 0, 'l');

            $pdf->SetXY(92, 20);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(95, 20);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(102, 20);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(105, 20);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(119,20);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(20, 4, utf8_decode('Obs:'), 0, 'l');
            $pdf->SetXY(129,20);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(64, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,25);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(70, 4, utf8_decode('Tos'), 0, 'l');

            $pdf->SetXY(92, 25);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(95, 25);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(102, 25);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(105, 25);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(119,25);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(20, 4, utf8_decode('Obs:'), 0, 'l');
            $pdf->SetXY(129,25);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(64, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,30);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(70, 4, utf8_decode('Sequedad'), 0, 'l');

            $pdf->SetXY(92, 30);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(95, 30);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(102, 30);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(105, 30);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(119,30);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(20, 4, utf8_decode('Obs:'), 0, 'l');
            $pdf->SetXY(129,30);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(64, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18,36);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->MultiCell(70, 4, utf8_decode('Tipo respiratorio'), 0, 'l');

            $pdf->SetXY(52, 36);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(58, 36);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(30, 4, utf8_decode('Nasal'), 0, 0, 'l');

            $pdf->SetXY(105, 36);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(110, 36);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(30, 4, utf8_decode('Boca'), 0, 0, 'l');

            $pdf->SetXY(148, 36);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(152, 36);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(30, 4, utf8_decode('Mixta'), 0, 0, 'l');

            $pdf->SetXY(18,41);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->MultiCell(70, 4, utf8_decode('Glatzel'), 0, 'l');

            $pdf->SetXY(51,41);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->MultiCell(70, 4, utf8_decode('Permeable'), 0, 'l');

            $pdf->SetXY(77, 41);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(81, 41);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(88, 41);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(92, 41);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(105,41);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->MultiCell(70, 4, utf8_decode('Simetría'), 0, 'l');

            $pdf->SetXY(123, 41);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(126, 41);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(132, 41);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(136, 41);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(146,41);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->MultiCell(70, 4, utf8_decode('Obstrucción'), 0, 'l');

            $pdf->SetXY(172, 41);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(176, 41);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('SI'), 0, 0, 'l');

            $pdf->SetXY(182, 41);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(186, 41);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(30, 4, utf8_decode('NO'), 0, 0, 'l');

            $pdf->SetXY(18,48);
            $pdf->SetFont('Arial', 'B', 9.5);
            $pdf->MultiCell(70, 4, utf8_decode('Simetría Asimetría'), 0, 'l');

            $pdf->SetXY(51, 46);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(12, 4, utf8_decode('Boca'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(20, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(105, 46);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(20, 4, utf8_decode('Mandíbula'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(20, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(146, 46);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(15, 4, utf8_decode('Dientes'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(20, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(51, 51);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(15, 4, utf8_decode('Labios'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(20, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(105, 51);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(15, 4, utf8_decode('Mejillas'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(20, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(146, 51);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(15, 4, utf8_decode('Lengua'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(20, 4, utf8_decode(''), 0, 'C');

            $pdf->SetXY(18, 56);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(15, 4, utf8_decode('Fluidez'), 0, 0, 'l');
            $pdf->SetXY(51, 56);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(130, 4, utf8_decode(''), 0, 'C');

            $pdf->Line(18, 34, 18, 61);#LINEA SUPERIOR HORIZONTAL
            $pdf->Line(102, 34, 102, 55); #LINEA VERTICAL 1
            $pdf->Line(18, 61, 193, 61); #LINEA INFERIOR HORIZONTAL
            $pdf->Line(18, 34, 193, 34); #LINEA INGERIOR HORIZONTAL 2
            $pdf->Line(50, 34, 50, 61); #LINEA 1 VERTICAL
            $pdf->Line(146, 34, 146, 55);
            $pdf->Line(193, 34, 193, 61);
            $pdf->Line(18, 55, 193, 55);
            $pdf->Line(18, 40, 193, 40);
            $pdf->Line(18, 45, 193, 45);

            #MARGENES
            $pdf->Line(18, 9, 193, 9);
            $pdf->Line(18, 9, 17, 272);
            $pdf->Line(193, 9, 193, 272);
            $pdf->Line(18, 272, 193, 272);
        }
        if (self::$data['salud_ocupacional'] === 'Psicologia') {
            $pdf->Ln();
            $pdf->SetXY(18,73);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(175, 4, utf8_decode('ANTECEDENTES PERSONALES  TRASTORNO  PSICOLÓGICA   PSIQUIÁTRICA  O MENTAL DIAGNOSTICADO'), 1, 0, 'C',1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);

            $pdf->SetXY(18,77);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(24, 4, utf8_decode('DIAGNÓSTICO:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(150, 4, utf8_decode(''), 0, 'L');

            $pdf->SetXY(18,81);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(51, 4, utf8_decode('PROFESIONAL QUE DIAGNOSTICA:'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(120, 4, utf8_decode(''), 0, 'L');

            $pdf->SetX(18,85);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(20, 4, utf8_decode('OBSERVACIONES:'), 0, 0, 'l');
            $pdf->SetXY(18,81);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(175, 4, utf8_decode(''), 1, 'L');

            $pdf->SetXY(18,89);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(175, 4, utf8_decode('SINTOMATOLOGÍA ACTUAL'), 1, 0, 'C',1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetXY(18,93);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(175, 4, utf8_decode('También es una composición de caracteres imprimibles (con grafema) generados por un algoritmo de cifrado que, aunque no tienen sentido para cualquier persona, sí puede ser descifrado por su destinatario original. En otras palabras, un texto es un entramado de signos con una intención comunicativa que adquiere sentido en determinado contexto.'), 0, 'J');

            $pdf->Ln();
            $pdf->SetX(18,100);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(175, 4, utf8_decode('ÁREA COGNITIVIA'), 1, 0, 'C',1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);
            $cognitivos=' cong ';
            $memoria= self::$data['memoria'];
            $atencion= self::$data['atencion'];
            $Lenguaje= self::$data['lenguaje'];
            $ubicado= self::$data['ubicacion'];
            $Tiempo= self::$data['tiempo_espacio'];
            $Funcionamiento= self::$data['estado_mental'];
            $presentación= self::$data['presentacion_personal'];
            $exaltación= self::$data['pensamiento_logico'];
            $introspección= self::$data['introspeccion'];
            $prospección= self::$data['prospeccion'];
            $pdf->SetXY(18,114);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->MultiCell(175, 4, utf8_decode(
            'procesos cognitivos  superiores, percepcion:'. '   '.$cognitivos.';
            Memoria:'. '   '.$memoria.';
            atencion:'. '   '.$atencion.';
            Lenguaje:'. '   ' .$Lenguaje.';
            ubicado en:'. '   '.$ubicado.';
            Tiempo:'. '   '.$Tiempo.';
            Funcionamiento de su estado mental:'. '   ' .$Funcionamiento.';
            presentación personal:'. '   '.$presentación.';
            exaltación del afecto, pensamiento lógico de Curso y contenido:'. '   ' .$exaltación.';
            introspección:'. '   ' .$introspección.';
            prospección:'. '   ' .$prospección.''), 0, 'J');

            $pdf->SetXY(18,274);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(175, 4, utf8_decode('ANALISIS'), 1, 0, 'C',1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);

            $pdf->SetXY(18,14);
            $pdf->SetFont('Arial', '', 8);

            #MARGENES HOJA 2
            $pdf->Line(18, 10, 18, 272);
            $pdf->Line(193, 10, 193, 272);
            $pdf->Line(18, 272, 193, 272);
        }
         #visiometria
        if (self::$data['salud_ocupacional'] === 'Visiometria'){
            $pdf->SetXY(18,18);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(175, 4, utf8_decode('AGUDEZA VISUAL'), 1, 0, 'C', 1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);

            $pdf->SetXY(18,22);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(50, 4, utf8_decode('AVCC OJO DERECHO'), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(37, 4, utf8_decode(self::$data['avcc_ojoderecho']), 1, 'l');

            $pdf->SetXY(18,26);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(50, 4, utf8_decode('AVSC OJO DERECHO'), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(37, 4, utf8_decode(self::$data['avsc_ojoderecho']), 1, 'l');

            $pdf->SetXY(18,30);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(50, 4, utf8_decode('AVSC AV CERCA OJO DERECHO'), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(37, 4, utf8_decode(self::$data['avscav_ojoderecho']), 1, 'l');

            $pdf->SetXY(18,34);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(50, 4, utf8_decode('AV CERCA OJO DERECHO'), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(37, 4, utf8_decode(self::$data['avcerca_ojoderecho']), 1, 'l');

            $pdf->SetXY(105,22);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(50, 4, utf8_decode('AVCC OJO IZQUIERDO'), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(38, 4, utf8_decode(self::$data['avcc_ojoizquierdo']), 1, 'l');

            $pdf->SetXY(105,26);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(50, 4, utf8_decode('AVSC OJO IZQUIERDO'), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(38, 4, utf8_decode(self::$data['avsc_ojoizquierdo']), 1, 'l');

            $pdf->SetXY(105,30);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(50, 4, utf8_decode('AVSC AV CERCA OJO IZQUIERDO'), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(38, 4, utf8_decode(self::$data['avscav_ojoizquierdo']), 1, 'l');

            $pdf->SetXY(105,34);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(50, 4, utf8_decode('AV CERCA OJO IZQUIERDO'), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(38, 4, utf8_decode(self::$data['avcerca_ojoizquierdo']), 1, 'l');

            $pdf->SetXY(18,38);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(175, 4, utf8_decode('MODALIDAD OCULAR'), 1, 0, 'C', 1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);

            $pdf->SetXY(18,42);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(87, 4, utf8_decode('OJO DERECHO'), 1, 0, 'C',1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetXY(18,46);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(87, 4, utf8_decode(self::$data['motilidad_ojoderecho']), 1, 'l');

            $pdf->SetXY(18,50);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(87, 4, utf8_decode('OJO IZQUIERDO'), 1, 0, 'C',1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetXY(18,54);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(87, 4, utf8_decode(self::$data['motilidad_ojoizquierdo']), 1, 'l');

            $pdf->SetXY(105,42);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(88, 4, utf8_decode('COVER TEST DERECHO'), 1, 0, 'C',1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetXY(105,46);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(88, 4, utf8_decode(self::$data['covert_ojoderecho']), 1, 'l');

            $pdf->SetXY(105,50);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(88, 4, utf8_decode('COVER TEST IZQUIERDO'), 1, 0, 'C',1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetXY(105,54);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(88, 4, utf8_decode(self::$data['covert_ojoizquierdo']), 1, 'l');

            $pdf->SetXY(18,58);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(175, 4, utf8_decode('BIOMICROSCOPIA'), 1, 0, 'C', 1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);

            $pdf->SetXY(18,62);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(87, 4, utf8_decode('OJO DERECHO'), 1, 0, 'C',1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetXY(18,66);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(87, 4, utf8_decode(self::$data['presionintra_ojoderecho']), 1, 'l');

            $pdf->SetXY(105,62);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(88, 4, utf8_decode('OJO IZQUIERDO'), 1, 0, 'C',1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetXY(105,66);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(88, 4, utf8_decode(self::$data['presionintra_ojoizquierdo']), 1, 'l');

            $pdf->SetXY(18,70);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(175, 4, utf8_decode('FONDO DE OJOS'), 1, 0, 'C', 1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);

            $pdf->SetXY(18,74);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(87, 4, utf8_decode('OJO DERECHO'), 1, 0, 'C',1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetXY(18,78);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(87, 4, utf8_decode(self::$data['fondo_ojoderecho']), 1, 'l');

            $pdf->SetXY(105,74);
            $pdf->SetFont('Arial', 'B', 8);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(88, 4, utf8_decode('OJO IZQUIERDO'), 1, 0, 'C',1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetXY(105,78);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(88, 4, utf8_decode(self::$data['fondo_ojoizquierdo']), 1, 'l');
        }

         #CONDUCTA
         $pdf->Ln();
         $pdf->SetXY(18,84);
         $pdf->SetFont('Arial', 'B', 8);
         $pdf->SetDrawColor(0,0,0);
         $pdf->SetFillColor(214, 214, 214);
         $pdf->SetTextColor(0,0,0);
         $pdf->Cell(175, 4, utf8_decode('CONDUCTA'), 1, 0, 'C', 1);
         $pdf->SetTextColor(0,0,0);
         $pdf->SetDrawColor(0,0,0);

         $pdf->SetXY(18,89);
         $pdf->SetFont('Arial', 'B', 8);
         $pdf->Cell(45, 4, utf8_decode('PLAN DE MANEJO:'), 0, 0, 'l'); #es lo mismo que la conducta
         $pdf->SetXY(18,94);
         $pdf->SetFont('Arial', '', 8);
         $pdf->MultiCell(175, 4, utf8_decode(self::$data['Planmanejo']), 0, 'l');
         $pdf->Ln();

         $pdf->SetXY(18,99);
         $pdf->SetFont('Arial', 'B', 8);
         $pdf->Cell(45, 4, utf8_decode('RECOMENDACIONES:'), 0, 0, 'l');
         $pdf->SetXY(18,104);
         $pdf->SetFont('Arial', '', 8);
         $pdf->MultiCell(175, 4, utf8_decode(self::$data['Recomendaciones']), 0, 'l');
         $pdf->Ln();

         $pdf->SetXY(18,110);
         $pdf->SetFont('Arial', 'B', 8);
         $pdf->Cell(65, 4, utf8_decode('SISTEMAS DE VIGILANCIA EPIDEMIOLÓGICO:'), 0, 0, 'l');
         $pdf->SetFont('Arial', '', 8);
         $pdf->MultiCell(110, 4, utf8_decode(self::$data['vigilancia_epidemiologico']), 0, 'l');
         $pdf->Ln();
         #MARGENES HOJA 2
         $pdf->Line(18, 9, 18, 272);
         $pdf->Line(193, 9, 193, 272);
         $pdf->Line(18, 272, 193, 272);
         $pdf->Line(18, 9, 193, 9);

        $pdf->SetXY(18,274);
        $pdf->Cell(175, 4, utf8_decode(''), 1, 0, 'C',1);


         $pdf->SetXY(18,82);
         $pdf->SetFont('Arial', 'B', 8);
         $pdf->Cell(45, 4, utf8_decode('DESTINO DEL PACIENTE:'), 0, 0, 'l');
         $pdf->SetFont('Arial', '', 8);
         $pdf->MultiCell(130, 4, utf8_decode(''), 0, 'l');
         $pdf->Ln();

         $pdf->SetXY(18,86);
         $pdf->SetFont('Arial', 'B', 8);
         $pdf->Cell(45, 4, utf8_decode('FINALIDAD:'), 0, 0, 'l');
         $pdf->SetFont('Arial', '', 8);
         $pdf->MultiCell(130, 4, utf8_decode(''), 0, 'l');
         #DIAGNOSTICOS
         $pdf->SetXY(18,92);
         $pdf->SetFont('Arial', 'B', 8);
         $pdf->SetDrawColor(0,0,0);
         $pdf->SetFillColor(214, 214, 214);
         $pdf->SetTextColor(0,0,0);
         $pdf->Cell(175, 4, utf8_decode('DIAGNÓSTICOS'), 1, 0, 'C', 1);
         $pdf->SetTextColor(0,0,0);
         $pdf->SetDrawColor(0,0,0);

         #DX PRINCIPAL
         $pdf->SetXY(18,96);
         $pdf->SetFont('Arial', 'B', 8);
         $pdf->SetDrawColor(0,0,0);
         $pdf->SetFillColor(214, 214, 214);
         $pdf->SetTextColor(0,0,0);
         $pdf->Cell(175, 4, utf8_decode('DIAGNÓSTICO PRINCIPAL'), 1, 0, 'C', 1);
         $pdf->SetTextColor(0,0,0);
         $pdf->SetDrawColor(0,0,0);

         $pdf->SetXY(18,100);
         $pdf->SetFont('Arial', 'B', 8);
         $pdf->Cell(30, 4, utf8_decode('CÓDIGO CIE10'), 1, 0, 'C');
         $pdf->SetXY(18,104);
         $pdf->SetFont('Arial', '', 8);
         $pdf->MultiCell(30, 4, utf8_decode(''), 1, 'l');

         $pdf->SetXY(48,100);
         $pdf->SetFont('Arial', 'B', 8);
         $pdf->Cell(85, 4, utf8_decode('DESCRIPCION DEL DIAGNÓSTICO'), 1, 0, 'C');
         $pdf->SetXY(48,104);
         $pdf->SetFont('Arial', '', 8);
         $pdf->MultiCell(85, 4, utf8_decode(''), 1, 'l');

         $pdf->SetXY(133,100);
         $pdf->SetFont('Arial', 'B', 8);
         $pdf->Cell(60, 4, utf8_decode('TIPO DEL DIAGNÓSTICO'), 1, 0, 'C');
         $pdf->SetXY(133,104);
         $pdf->SetFont('Arial', '', 8);
         $pdf->MultiCell(60, 4, utf8_decode(''), 1, 'l');

         #DX SEGUNDARIO
         $pdf->SetXY(18,108);
         $pdf->SetFont('Arial', 'B', 8);
         $pdf->SetDrawColor(0,0,0);
         $pdf->SetFillColor(214, 214, 214);
         $pdf->SetTextColor(0,0,0);
         $pdf->Cell(175, 4, utf8_decode('DIAGNÓSTICOS SEGUNDARIOS'), 1, 0, 'C', 1);
         $pdf->SetTextColor(0,0,0);
         $pdf->SetDrawColor(0,0,0);

         $pdf->SetXY(18,112);
         $pdf->SetFont('Arial', 'B', 8);
         $pdf->Cell(30, 4, utf8_decode('CÓDIGO CIE10'), 1, 0, 'C');
         $pdf->SetXY(18,116);
         $pdf->SetFont('Arial', '', 8);
         $pdf->MultiCell(30, 4, utf8_decode(''), 1, 'l');

         $pdf->SetXY(48,112);
         $pdf->SetFont('Arial', 'B', 8);
         $pdf->Cell(85, 4, utf8_decode('DESCRIPCION DEL DIAGNÓSTICO'), 1, 0, 'C');
         $pdf->SetXY(48,116);
         $pdf->SetFont('Arial', '', 8);
         $pdf->MultiCell(85, 4, utf8_decode(''), 1, 'l');

         $pdf->SetXY(133,112);
         $pdf->SetFont('Arial', 'B', 8);
         $pdf->Cell(59, 4, utf8_decode('TIPO DEL DIAGNÓSTICO'), 1, 0, 'C');
         $pdf->SetXY(133,116);
         $pdf->SetFont('Arial', '', 8);
         $pdf->MultiCell(59, 4, utf8_decode(''), 1, 'l');

         $pdf->SetXY(18,120);
         $pdf->SetFont('Arial', 'B', 8);
         $pdf->Cell(64, 4, utf8_decode('CONCEPTO MÉDICO DE APTITUD LABORAL:'), 0, 0, 'l');
         $pdf->SetFont('Arial', '', 8);
         if (self::$data['salud_ocupacional'] === 'Consulta Medica') {
            $pdf->MultiCell(105, 4, utf8_decode(self::$data['aptitud_laboral_medico']), 0, 'l');
            $pdf->Ln();
         }elseif (self::$data['salud_ocupacional'] === 'Visiometria') {
            $pdf->MultiCell(105, 4, utf8_decode(self::$data['aptitud_laboral_visiomretria']), 0, 'l');
            $pdf->Ln();
         }elseif (self::$data['salud_ocupacional'] === 'Voz') {
            $pdf->MultiCell(105, 4, utf8_decode(self::$data['aptitud_laboral_voz']), 0, 'l');
            $pdf->Ln();
         }elseif (self::$data['salud_ocupacional'] === 'Psicologia') {
            $pdf->MultiCell(105, 4, utf8_decode(self::$data['aptitud_laboral_psicologia']), 0, 'l');
            $pdf->Ln();
         }
         $pdf->MultiCell(105, 4, utf8_decode(''), 0, 'l');
         $pdf->Ln();

        $pdf->Ln();
        #FIRMAS
        $pdf->Line(20, 251, 90, 251);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(23, 252);
        $pdf->Cell(60, 4, utf8_decode(self::$data['firma_medico_examinador']), 0, 0, 'l'); #NOMBRE COMPLETO DEL MEDICO
        $pdf->SetXY(23, 257);
        $pdf->Cell(60, 4, utf8_decode(''), 0, 0, 'l'); #ESPECIALIDAD DEL MEDICO
        $pdf->SetXY(23, 262);
        $pdf->Cell(32, 4, utf8_decode('REGISTRO Y LIC S.O:'), 0, 'l'); #REGISTRO MEDICO
        $pdf->MultiCell(30, 4, utf8_decode(''), 0, 'l');
        #FIRMAS
        if(self::$data['firma_trabajador']){
            $pdf->Image(public_path().'/storage/saludOcupacional/firma/'.self::$data['firma_trabajador'], 130, 258, 56, 11);
        }
        $pdf->Line(110, 251, 174, 251);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(115, 252);

        $pdf->Cell(50, 4, utf8_decode(self::$data['Primer_Nom']. ' '. self::$data['SegundoNom']. ' '. self::$data['Primer_Ape']. ' '. self::$data['Segundo_Ape']), 0, 0, 'l'); #NOMBRE COMPLETO DEL PACIENTE
        $pdf->SetXY(115, 257);
        $pdf->Cell(8, 4, utf8_decode('CC'), 0, 'l'); #CEDULA PACIENTE
        $pdf->MultiCell(36, 4, utf8_decode(self::$data['Num_Doc']), 0, 'l');
    }
}
