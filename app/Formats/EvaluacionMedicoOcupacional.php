<?php
namespace App\Formats;

use App\Modelos\Saludocupacional;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\User;


class EvaluacionMedicoOcupacional extends FPDF
{

 public function generar($datos){
     $datos = Saludocupacional::select([
         'cp.Datetimeingreso',
         'saludocupacionals.tipoExamen',
         'p.Primer_Nom',
         'p.SegundoNom',
         'p.Primer_Ape',
         'p.Segundo_Ape',
         'p.Num_Doc',
         'Sexo',
         'p.Edad_Cumplida',
         'p.Fecha_Naci',
         'p.estado_civil',
         'p.Telefono',
         'p.Celular1',
         'p.Direccion_Residencia',
         'p.Aseguradora',
         'saludocupacionals.nombre_de_la_empresa',
         'saludocupacionals.cargo',
         'saludocupacionals.antiguedad',
         'saludocupacionals.factoresRiesgo',
         'saludocupacionals.aptitud_laboral_medico',
         'c.Recomendaciones',
         'saludocupacionals.vigilancia_epidemiologico',
         'cp.user_medico_atiende',
         'cp.firma_trabajador',
         'cp.salud_ocupacional',
         'saludocupacionals.aptitud_laboral_visiomretria',
         'saludocupacionals.aptitud_laboral_psicologia'
     ])->join('cita_paciente as cp','saludocupacionals.cita_paciente_id','cp.id')
         ->join('pacientes as p','cp.Paciente_id','p.id')
         ->join('conductas as c','cp.id','c.Citapaciente_id')
         ->where('saludocupacionals.id',$datos['data']['idCodigoOcupacional'])->first();

 $pdf = new EvaluacionMedicoOcupacional('P','mm','A4');
 $pdf->AliasNbPages();
 $pdf->AddPage();
 $Y = 40;
 $pdf->SetDrawColor(0,0,0);
 $pdf->Rect(5, 5, 200, 287);
 $pdf->SetDrawColor(0,0,0);
 #IMAGEN Y DATOS EMPRESARIALES
 $pdf->SetFont('Arial', 'B', 9);
 $logo = public_path() . "/images/logo.png";
 $pdf->Image($logo, 23, 20, -350);
 $pdf->SetXY(5, 5);
 $pdf->SetDrawColor(0,0,0);
 $pdf->SetFillColor(220, 220, 220);
 $pdf->SetTextColor(0,0,128);
 #TITULO 1
 $pdf->SetFont('Arial', 'B', 15);
 $pdf->Cell(200, 7, utf8_decode('CERTIFICADO MÉDICO DE APTITUD LABORAL'),1, 0,'C',1);
 $pdf->SetTextColor(0,0,0);
 $pdf->SetDrawColor(0,0,0);
 #NIT
 $pdf->SetFont('Arial', '', 7);
 $pdf->SetXY(8, 42);
 $pdf->Cell(60, 3, utf8_decode('NIT:900033371-4 Res: 004 '), 0, 0, 'C');

 #TITULO SEGUNDARIO
 $pdf->SetXY(70, 25);
 $pdf->SetFont('Arial', 'B', 15);
 $pdf->Cell(284, 4, utf8_decode('EVALUACION MEDICO OCUPACIONAL'), 0, 'C');
 $pdf->SetXY(80, 35);
 #SUBTITULO
 $pdf->SetFont('Arial', '', 12);
 $pdf->Cell(284, 4, utf8_decode('CERTIFICADO DE APTITUD LABORAL'), 0, 'C');

 #TITULO DE INFORMACION
 $pdf->SetXY(5, 55);
 $pdf->SetTextColor(0,0,128);
 $pdf->SetFont('Arial', 'B', 12);
 $pdf->Cell(200, 6, utf8_decode('INFORMACION GENERAL'),1, 1, 'C',1);
 $pdf->SetTextColor(0,0,0);

 #DATOS DE ATENCION
 $pdf->SetXY(5, 61);
 $pdf->SetFont('Arial', 'B', 9);
 $pdf->MultiCell(30, 4, utf8_decode('Fecha (Y,M,D)'), 0, 'l');
 $pdf->SetXY(35, 61);
 $pdf->SetFont('Arial', '', 7);
 $pdf->MultiCell(40, 4, substr(substr($datos->Datetimeingreso,0,10),0,12), 0, 'l');
 $pdf->SetXY(75, 61);
 $pdf->SetFont('Arial', 'B', 9);
 $pdf->MultiCell(30, 4, utf8_decode('Tipo de Examen:'), 0, 'l');
 $pdf->SetXY(105, 61);
 $pdf->SetFont('Arial', '', 7);
 $pdf->MultiCell(100, 4, utf8_decode($datos->tipoExamen), 0, 'l');
 $pdf->SetXY(5, 65);
 $pdf->SetFont('Arial', 'B', 9);
 $pdf->MultiCell(30, 4, utf8_decode('Lugar:'), 0, 'l');
 $pdf->SetXY(35, 65);
 $pdf->SetFont('Arial', '', 7);
 $pdf->MultiCell(170, 4, utf8_decode('Medellín (ANT)'), 0, 'l');
 $pdf->Ln();
 $pdf->Line(5, 71, 205, 71);

 #DATOS PERSONALES
 $pdf->SetXY(5, 72);
 $pdf->SetFont('Arial', 'B', 9);
 $pdf->MultiCell(40, 4, utf8_decode('Nombres y Apellidos:'), 0, 'l');
 $pdf->SetXY(45, 72);
 $pdf->SetFont('Arial', '', 7);
 $pdf->MultiCell(60, 4, utf8_decode($datos->Primer_Nom. ' '. $datos->SegundoNom. ' '. $datos->Primer_Ape. ' '. $datos->Segundo_Ape ), 0, 'l');
 $pdf->SetXY(105, 72);
 $pdf->SetFont('Arial', 'B', 9);
 $pdf->MultiCell(30, 4, utf8_decode('Cedula:'), 0, 'l');
 $pdf->SetXY(135, 72);
 $pdf->SetFont('Arial', '', 7);
 $pdf->MultiCell(30, 4, utf8_decode($datos->Num_Doc), 0, 'l');
//  $pdf->SetXY(165, 72);
//  $pdf->SetFont('Arial', 'B', 9);
//  $pdf->MultiCell(10, 4, utf8_decode('De:'), 0, 'l');
//  $pdf->SetXY(175, 72);
//  $pdf->SetFont('Arial', '', 7);
//  $pdf->MultiCell(30, 4, utf8_decode(''), 0, 'l');

 $pdf->SetXY(5, 78);
 $pdf->SetFont('Arial', 'B', 9);
 $pdf->MultiCell(30, 4, utf8_decode('Sexo (Según CC):'), 0, 'l');
 $pdf->SetXY(35, 78);
 $pdf->SetFont('Arial', '', 7);
 $pdf->MultiCell(40, 4, utf8_decode($datos->Sexo), 0, 'l');
 $pdf->SetXY(75, 78);
 $pdf->SetFont('Arial', 'B', 9);
 $pdf->MultiCell(30, 4, utf8_decode('Edad (en años):'), 0, 'l');
 $pdf->SetXY(105, 78);
 $pdf->SetFont('Arial', '', 7);
 $pdf->MultiCell(20, 4, utf8_decode($datos->Edad_Cumplida), 0, 'l');
 $pdf->SetXY(125, 78);
 $pdf->SetFont('Arial', 'B', 9);
 $pdf->MultiCell(40, 4, utf8_decode('Fecha (YYYY,mm,dd)'), 0, 'l');
 $pdf->SetXY(165, 78);
 $pdf->SetFont('Arial', '', 7);
 $pdf->MultiCell(40, 4, substr(substr($datos->Fecha_Naci,0,10),0,11), 0, 'l');

 $pdf->SetXY(5, 87);
 $pdf->SetFont('Arial', 'B', 9);
 $pdf->MultiCell(25, 4, utf8_decode('Estado Civil:'), 0, 'l');
 $pdf->SetXY(30, 87);
 $pdf->SetFont('Arial', '', 7);
 $pdf->MultiCell(40, 4, utf8_decode($datos->estado_civil), 0, 'l');
 $pdf->SetXY(70, 87);
 $pdf->SetFont('Arial', 'B', 9);
 $pdf->MultiCell(20, 4, utf8_decode('Telefono:'), 0, 'l');
 $pdf->SetXY(90, 87);
 $pdf->SetFont('Arial', '', 7);
 $pdf->MultiCell(35, 4, utf8_decode($datos->Telefono), 0, 'l');
 $pdf->SetXY(125, 87);
 $pdf->SetFont('Arial', 'B', 9);
 $pdf->MultiCell(40, 4, utf8_decode('Movil:'), 0, 'l');
 $pdf->SetXY(165, 87);
 $pdf->SetFont('Arial', '', 7);
 $pdf->MultiCell(40, 4, utf8_decode($datos->Celular1), 0, 'l');
 $pdf->SetXY(5, 92);
 $pdf->SetFont('Arial', 'B', 9);
 $pdf->MultiCell(20, 4, utf8_decode('Dirección:'), 0, 'l');
 $pdf->SetXY(25, 92);
 $pdf->SetFont('Arial', '', 7);
 $pdf->MultiCell(70, 4, utf8_decode($datos->Direccion_Residencia), 0, 'l');
 $pdf->SetXY(105, 92);
 $pdf->SetFont('Arial', 'B', 9);
 $pdf->MultiCell(15, 4, utf8_decode('E.P.S:'), 0, 'l');
 $pdf->SetXY(120, 92);
 $pdf->SetFont('Arial', '', 7);
 $pdf->MultiCell(70, 4, utf8_decode($datos->Aseguradora), 0, 'l');
 $pdf->Line(5, 97, 205, 97);

 #DATOS DE LA EMPRESA A INGRESAR
 $pdf->SetXY(5, 99);
 $pdf->SetFont('Arial', 'B', 9);
 $pdf->MultiCell(30, 4, utf8_decode('Nombre de la Empresa:'),0 , 'l');
 $pdf->SetXY(35, 99);
 $pdf->SetFont('Arial', '', 7);
 $pdf->MultiCell(70, 8, utf8_decode($datos->nombre_de_la_empresa), 0, 'l');
 $pdf->SetXY(105, 99);
 $pdf->SetFont('Arial', 'B', 9);
 $pdf->MultiCell(15, 4, utf8_decode('Cargo:'), 0, 'l');
 $pdf->SetXY(120, 99);
 $pdf->SetFont('Arial', '', 7);
 $pdf->MultiCell(80, 4, utf8_decode($datos->cargo), 0, 'l');
 $pdf->SetXY(105, 103);
 $pdf->SetFont('Arial', 'B', 9);
 $pdf->MultiCell(25, 4, utf8_decode('Antigüedad:'), 0, 'l');
 $pdf->SetXY(130, 103);
 $pdf->SetFont('Arial', '', 7);
 $pdf->MultiCell(70, 4, utf8_decode($datos->antiguedad), 0, 'l');
 $pdf->Line(5, 108, 205, 108);

 #RIESGOS LABORALES
 $pdf->SetXY(5, 109);
 $pdf->SetFont('Arial', 'B', 9);
 $pdf->Cell(30, 4, utf8_decode('Riesgos Laborales:'),0 , 'l');
 $pdf->SetXY(5, 113);
 $pdf->SetFont('Arial', '', 7);
 $pdf->MultiCell(90, 4, utf8_decode($datos->factoresRiesgo), 0, 'l');

 #SEGUNDO TITULO
 $pdf->SetXY(5, 120);
 $pdf->SetTextColor(0,0,128);
 $pdf->SetFont('Arial', 'B', 12);
 $pdf->Cell(200, 6, utf8_decode('EXÁMENES DE DIAGNÓSTICO OCUPACIONAL REALIZADOS'),1, 0, 'C',1);
 $pdf->SetTextColor(0,0,0);
 $pdf->Ln();

 #TEXTO DEL SEGUNDO TITULO
 $pdf->SetX(5);
 $pdf->SetFont('Arial', 'B', 9);
 $pdf->Cell(31, 4, utf8_decode('Tipo de Valoración:'),0 , 'l');
 $pdf->SetFont('Arial', '', 7);
 $pdf->Cell(90, 4, utf8_decode($datos->tipoExamen), 0, 'l');
 $pdf->Ln();

 #TERCER TITULO
 $pdf->SetX(5);
 $pdf->SetTextColor(0,0,128);
 $pdf->SetFont('Arial', 'B', 12);
 $pdf->Cell(200, 6, utf8_decode('CONCEPTO DE APTITUD OCUPACIONAL'),1, 0, 'C',1);
 $pdf->SetTextColor(0,0,0);
 $pdf->Ln();

// dd($datos->salud_ocupacional);
 #TEXTO DEL TERCER TITULO
 $pdf->SetX(5);
 $pdf->SetFont('Arial', 'B', 9);
 $pdf->Cell(25, 4, utf8_decode('Observaciones:'),0 , 'l');
 $pdf->SetFont('Arial', '', 7);
 if($datos->salud_ocupacional == 'Consulta Medica'){
     $pdf->Cell(190, 4, utf8_decode($datos->aptitud_laboral_medico), 0, 'l');
 }elseif($datos->salud_ocupacional == 'Visiometria'){
     $pdf->Cell(190, 4, utf8_decode($datos->aptitud_laboral_visiomretria), 0, 'l');
 }elseif($datos->salud_ocupacional == 'Psicologia'){
     $pdf->Cell(190, 4, utf8_decode($datos->aptitud_laboral_psicologia), 0, 'l');
 }
 //$pdf->MultiCell(190, 4, utf8_decode(), 0, 'l');
 $pdf->Ln();
 #CUARTO TITULO
 $pdf->SetX(5);
 $pdf->SetTextColor(0,0,128);
 $pdf->SetFont('Arial', 'B', 11);
 $pdf->Cell(200, 6, utf8_decode('RECOMENDACIONES'),1, 0, 'C',1);
 $pdf->Ln();

 #TEXTO DEL CUARTO TITULO
 $pdf->SetX(5);
 $pdf->SetFont('Arial', '', 7);
 $pdf->MultiCell(198, 4, utf8_decode($datos->Recomendaciones), 0, 'l');

 #QUINTO TITULO
 $pdf->SetX(5);
 $pdf->SetTextColor(0,0,128);
 $pdf->SetFont('Arial', 'B', 10);
 $pdf->Cell(200, 4, utf8_decode('INGRESAR AL PROGRAMA DE VIGILANCIA EPIDEMIOLÓGICA O PROGRAMA DE PROMOCIÓN Y PREVENCIÓN'),1, 0, 'C',1);
 $pdf->Ln();

 #TEXTO DEL QUINTO TITULO
 $pdf->SetX(5);
 $pdf->SetTextColor(0,0,0);
 $pdf->SetFont('Arial', '', 7);
 $pdf->Cell(200, 4, utf8_decode($datos->vigilancia_epidemiologico),1, 0, 'L',0);
 $pdf->Ln();

 #SEPTIMO TITULO
 $pdf->SetX(5);
 $pdf->SetTextColor(0,0,128);
 $pdf->SetFont('Arial', 'B', 10);
 $pdf->Cell(200, 6, utf8_decode(strtoupper('Consentimiento Informado del Aspirante o Trabajador')),1, 0, 'C',1);
 $pdf->SetTextColor(0,0,0);
 $pdf->SetDrawColor(0,0,0);
 $pdf->Ln();

 #TEXTO DEL SEPTIMO TITULO
 $pdf->SetX(6);
 $pdf->SetFont('Arial', '', 7);
 $pdf->MultiCell(198, 4, utf8_decode('Autorizo al doctor(a) abajo mencionado(a) a realizar en mi examen médico y/o paraclínicos ocupacionales registrados en este documento. El (la) doctor(a) abajo mencionado(a) me ha explicado la naturaleza y el propósito del examen médico y/o paraclínicos. He comprendido y he tenido la oportunidad de analizar el propósito, los beneficios, la interpretación, las limitaciones y riesgos del examen médico y/o paraclínicos ocupacionales, a partir de la asesoría brindada antes de la respectiva toma de la prueba. Entiendo que la realización de estas pruebas es voluntaria y que tuve la oportunidad de retirar mi consentimiento en cualquier momento antes de que se realizara el o los exámenes. Fui informado de las medidas que tomara la compañía PROFESALUD para proteger la confidencialidad de mis resultados. Recibí copia de la valoración médica ocupacional. Las respuestas dadas por mí en este examen están completas y son verídicas. Autorizo a la compañía PROFESALUD para que suministre a las personas o entidades contempladas en la legislación vigente, la información registrada en este documento para el bien cumplimiento del programa de salud ocupacional y para las situaciones contempladas en la misma legislación, igualmente para que remita la historia clínica a la EPS a la cual me encuentra actualmente afiliado. Finalmente manifiesto que he leído y comprendido perfectamente lo anterior y que todos los espacios en blanco han sido completados ante mi firma y que me encuentro en capacidad de expresar mi consentimiento:'), 0, 'J');

 #FIRMAS


 $pdf->SetX(120);
 $pdf->MultiCell(55, 4, utf8_decode(''), 0, 'l');
 if($datos->firma_trabajador){
    if (file_exists(public_path().'/storage/saludOcupacional/firma/'.$datos->firma_trabajador)) {
        $pdf->Image(public_path().'/storage/saludOcupacional/firma/'.$datos->firma_trabajador, 130, 258, 56, 11);
    }
 }else{
    $pdf->SetXY(120, 266);
    $pdf->Cell(85, 4, utf8_decode($datos->Primer_Nom. ' '. $datos->SegundoNom. ' '. $datos->Primer_Ape. ' '. $datos->Segundo_Ape ), 0, 'l');
 }
 $pdf->Line(120, 270, 195, 270);
 $pdf->SetXY(120, 272);
 $pdf->Cell(85, 4, utf8_decode('Firma Empleado'), 0, 'l');
 $pdf->SetXY(170, 272);


 $pdf->Line(10, 270, 90, 270);
 $pdf->SetX(10);
 $pdf->Cell(85, 4, utf8_decode('Médico Especialista en Salud Ocupacional'), 0, 'l');
 $pdf->SetX(70);
 $pdf->Cell(45, 4, utf8_decode('Registro'), 0, 'l');
 $pdf->Ln();



 $medico = User::find($datos->user_medico_atiende);
 $pdf->SetFont('Arial','B',6);
 $pdf->Text(11,280,utf8_decode($medico->name." ".$medico->apellido." R.M:".$medico->cedula." (".$medico->especialidad_medico.")"));
 if($medico->Firma){
 if (file_exists(storage_path(substr($medico->Firma, 9)))) {
 $pdf->Image(storage_path(substr($medico->Firma, 9)), 10, 257, 56, 11);
 }
 }

 $pdf->Output();
 }
}
