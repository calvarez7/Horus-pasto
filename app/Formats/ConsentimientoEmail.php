<?php
namespace App\Formats;

use App\Modelos\citapaciente;
use App\Modelos\Paciente;
use Codedge\Fpdf\Fpdf\Fpdf;

class ConsentimientoEmail extends FPDF
{
    public static $id;
    public static $firma;
    public static $paciente;

    public function generar($data)
    {

        self::$firma = citapaciente::where('id',$data['id'])->first();
        self::$paciente = Paciente::where('id',self::$firma->Paciente_id)->first();
        $pdf= new ConsentimientoEmail('p', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $this->body($pdf);
        $pdf->Output();

    }

    public function Header(){
        $this->SetFont('Arial', 'B', 9);
        $logo = public_path() . "/images/logo.png";
        $this->Image($logo, 13, 7, 28);

        #lineas horizontales
        $this->Line(5, 5, 205, 5);
        $this->Line(5, 30, 205, 30);
        $this->Line(5, 5, 205, 5);
        $this->Line(163, 13, 205, 13);
        $this->Line(163, 20, 205, 20);

        #lineas verticales
        $this->Line(5, 5, 5, 30);
        $this->Line(205, 5, 205, 30);
        $this->Line(50, 5, 50, 30);
        $this->Line(163, 5, 163, 30);

        $this->SetXY(12, 10);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(192, 6, utf8_decode('CONSENTIMIENTO INFORMADO PARA'), 0, 0, 'C');

        $this->SetXY(12, 15);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(192, 6, utf8_decode('CONSULTA EXTERNA BAJO'), 0, 0, 'C');

        $this->SetXY(12, 20);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(192, 6, utf8_decode('LA MODALIDAD DE TELEMEDICINA'), 0, 0, 'C');

        $this->SetXY(163, 7);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(192, 6, utf8_decode('Código: FO-CE-031'), 0, 0, 'L');

        $this->SetXY(163, 14);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(192, 6, utf8_decode('Versión: 02'), 0, 0, 'L');

        $this->SetXY(163, 20);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(192, 6, utf8_decode('Fecha de aprobación:'), 0, 0, 'L');

        $this->SetXY(163, 24);
        $this->SetFont('Arial', '', 10);
        $this->Cell(192, 6, utf8_decode('25/05/2023'), 0, 0, 'L');

        $this->SetXY(15, 30);
        $this->Cell(15, 5, utf8_decode(' '), 0, 0, 'L');
        $this->ln();
    }


    public function body($pdf){
        $y=40;
        $pdf->SetXY(15,$y+2);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(185,4,utf8_decode('Fecha de diligenciamiento: '.self::$firma['firma_consentimiento_time'] ),0,0,'J',0);


        $y = $pdf->GetY();
        $pdf->SetXY(35,$y+4);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(197, 218, 174);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(165, 15 ,utf8_decode(''), 1, 0, 'L');
        $y1 = $pdf->GetY();

        $y2 = $pdf->GetY();
        $pdf->Line(65, $y1, 65, $y2+15); #LINA VERTICAL IZQUIERDA
        $pdf->Line(98, $y1, 98, $y2+15); #LINEA VERTICAL DERECHA
        $pdf->Line(155, $y1, 155, $y2+15); #LINEA VERTICAL DERECHA
        $pdf->Line(185, $y1, 185, $y2+15); #vertical
        $pdf->Line(13, $y1, 13, $y2+15); #LINEA VERTICAL DERECHA
        $pdf->Line(13, $y1, 35, $y2); #LINEA horizontal DERECHA
        $pdf->Line(13, $y1+15, 35, $y2+15); #LINEA horizontal DERECHA
        $pdf->Line(35, $y1+8, 200, $y2+8); #LINEA horizontal DERECHA

        $pdf->SetXY(14,$y+10);
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(30,4,utf8_decode('Paciente'),0,0,'L',0);

        $pdf->SetXY(35,$y+8);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,4,utf8_decode(self::$paciente['Primer_Ape']),0,0,'L',0);

        $pdf->SetXY(66,$y+8);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,4,utf8_decode(self::$paciente['Segundo_Ape']),0,0,'L',0);

        $pdf->SetXY(105,$y+8);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,4,utf8_decode(self::$paciente['SegundoNom'] == null ? self::$paciente['Primer_Nom']: self::$paciente['Primer_Nom'] .' '. self::$paciente['SegundoNom']),0,0,'L',0);

        $pdf->SetXY(157,$y+8);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,4,utf8_decode(self::$paciente['Num_Doc']),0,0,'L',0);

        $pdf->SetXY(185,$y+8);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,4,utf8_decode(self::$paciente['Edad_Cumplida']),0,0,'L',0);

        $pdf->SetXY(35,$y+14);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,4,utf8_decode('Primer apellido'),0,0,'L',0);

        $pdf->SetXY(66,$y+14);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,4,utf8_decode('Segundo apellido'),0,0,'L',0);

        $pdf->SetXY(105,$y+14);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,4,utf8_decode('Nombres completos'),0,0,'L',0);

        $pdf->SetXY(157,$y+14);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,4,utf8_decode('Documento'),0,0,'L',0);

        $pdf->SetXY(185,$y+14);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(30,4,utf8_decode('Edad'),0,0,'L',0);

        $pdf->SetXY(13,$y+23);
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(160,4,utf8_decode('DESCRIPCIÓN:'),0,0,'J',0);

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','',9);
        $pdf->MultiCell(185,4, utf8_decode('La telemedicina involucra el uso de tecnología de telecomunicaciones y medios electrónicos para interactuar con usted, revisar su información médica para propósitos de diagnóstico, terapia, seguimiento y/o educación.'),0,'J');

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(185,4,utf8_decode('BENEFICIOS DEL PROCEDIMIENTO:'),0,0,'J',0);

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','',9);
        $pdf->MultiCell(185,4, utf8_decode('Los beneficios esperados de la atención bajo la modalidad de Telemedicina Interactiva son: Mayor acceso a la consulta médica por ser remota evitando desplazamiento. Protección frente a exposición a posibles contagios de enfermedades infecciosas por otras personas al recibir la atención mediante la modalidad de telemedicina en un sitio diferente a una Institución prestadora de servicios de salud, tal como mi vivienda. Disminuye los costos y riesgos de desplazamiento fuera de su entorno.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial','B',11);
        $pdf->SetX(13);
        $pdf->Cell(185,4,utf8_decode('POSIBLES COMPLICACIONES:'),0,0,'L',0);

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','',9);
        $pdf->MultiCell(185,4, utf8_decode('La consulta médica a través de la modalidad de Telemedicina Interactiva conlleva a la ausencia de examen físico, lo que puede complicar o dificultar una impresión diagnóstica. Es posible que de acuerdo con los resultados de la valoración médica deba acudir de manera presencial para ser valorado por profesionales de Sumimedical S.A.S. o dirigirse a la red de prestadores de su asegurador. Es deber del paciente seguir y acatar las recomendaciones médicas. En casos excepcionales, la información transmitida puede no ser suficiente (p. ej. Baja resolución de las imágenes) para permitir una toma apropiada de decisiones médicas. Posibles demoras en la evaluación/tratamiento médico debido a deficiencias o fallos en el equipo electrónico en todos los casos, Sumimedical S.A.S. hace todos los esfuerzos para la reducción de los riesgos y dispone de los medios para el manejo de las complicaciones que lleguen a presentarse.'),0,'J');

        $y=$pdf->GetY();
        $pdf->SetFont('Arial','B',11);
        $pdf->SetXY(13,$y+5);
        $pdf->Cell(185,4,'ALTERNATIVAS DISPONIBLES A LA CONSULTA:',0,0,'L',0);

        $pdf->Ln();
        $pdf->SetFont('Arial','',9);
        $pdf->SetX(13);
        $pdf->MultiCell(185,4, utf8_decode('Si usted no acepta esta consulta puede solicitarla en modalidad presencial o dirigirse por sus propios medios al servicio de consulta prioritaria de Sumimedical S.A.S. o en la red de prestadores de su asegurador.'),0,'J');

        $pdf->Ln();
        $pdf->SetFont('Arial','B',11);
        $pdf->SetX(13);
        $pdf->Cell(185,4,'RIESGO DE NO ACEPTAR LA CONSULTA:',0,0,'L',0);

        $pdf->Ln();
        $pdf->SetFont('Arial','',9);
        $pdf->SetX(13);
        $pdf->MultiCell(185,4, utf8_decode('En caso de negarse probablemente continuará con los síntomas que ahora padece y se puede agravar su estado de salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(185,4,utf8_decode('¿QUÉ HACER SI NECESITA MÁS INFORMACIÓN?'),0,0,'L',0);

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(185,4,utf8_decode('En caso de requerir más información al respecto se puede dirigir a la sede donde se encuentre inscrito.'),0,'J');

        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(185,4,utf8_decode('RECOMENDACIONES:'),0,0,'L',0);

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','',9);
        $pdf->MultiCell(185,4, utf8_decode('Es necesario que informe al personal médico cualquier alteración propia de su salud, la existencia de comorbilidades, tales como hipertensión, diabetes, alergias, entre otros; esto ayudará a conocer mejor sus condiciones de salud. El paciente debe advertir de las posibles alergias medicamentosas, alteraciones de la coagulación, enfermedades cardiopulmonares, existencia de prótesis marca pasos, medicaciones que esté recibiendo o cualquier otra circunstancia que afecte a su salud.'),0,'J');

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(185,4,utf8_decode('DECLARACIÓN DEL PACIENTE / TUTOR / REPRESENTANTE LEGAL'),0,0,'L',0);

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','',9);
        $pdf->MultiCell(185,4,utf8_decode('Declaro en pleno uso de mis facultades mentales que he recibido una explicación y descripción clara y en lenguaje sencillo sobre el servicio de Consulta Externa bajo la modalidad de Telemedicina que voy a recibir, mediante la cual un Profesional de la Salud, haciendo uso de una herramienta de videollamada, hará una valoración de mi estado de salud, con miras a obtener un concepto médico y a ordenar los medicamentos y/o tecnologías en salud que considere necesarios para mi tratamiento.'),0,'J');

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','',9);
        $pdf->MultiCell(185,4,utf8_decode('Se me han aclarado todas las dudas y se me han dicho los beneficios, posibles riesgos y complicaciones, así, como las otras alternativas de tratamiento al servicio que voy a recibir, y la posibilidad de que los profesionales de la Salud determinen la necesidad de cambio de modalidad en la atención, según la valoración que hagan de mi estado de salud o por fallas tecnológicas en el proceso de atención que impidan el cabal desarrollo de esta. Igualmente declaro que se me ha informado que para efectos de brindarme atención médica, autorizo expresamente a Sumimedical S.A.S para recolectar, clasificar, almacenar, utilizar, archivar y cualquier otra modalidad de tratamiento a mis Datos Personales, incluyendo mis datos sensibles, conforme a las finalidades establecidas en la Política de Tratamiento de Datos Personales disponible para su consulta en la página web https://sumimedical.com/politica-de-privacidad/ la cual puede ser modificada por la institución sin previo aviso. Estas finalidades incluyen soportar la atención medico asistencial, remitir información a su entidad aseguradora, gestionar procesos de cobro, realizar encuestas de satisfacción; enviar por cualquier canal (correo electrónico, SMS, físico) resultados de exámenes diagnósticos.'),0,'J');
            $y=$pdf->GetY();
            if ($y  > 250) {
                $y = 40;
                $pdf->AddPage();
            }

        $pdf->Ln();
        $pdf->SetXY(13,$y);
        $pdf->MultiCell(185,4, utf8_decode('Por lo anteriormente dicho, y en pleno uso de mis facultades, autorizo al equipo de salud de la Sumimedical S.A.S. el servicio de consulta externa bajo la modalidad de Telemedicina Interactiva y que el servicio requiere de una conexión a internet estable, lo que puede implicar un consumo de datos celulares o de ancho de banda para el paciente. Entiendo y asumo los riesgos relacionados con la realización de este y autorizo a que se tomen las medidas necesarias ante cualquier complicación derivada de la consulta. SI_x_ NO___ (marque con una X).'),0,'J');

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','',9);
        $pdf->MultiCell(185,4,utf8_decode('Autorizo el tratamiento de mis datos personales, incluyendo mis datos sensibles, para los fines previamente señalados, así como la posibilidad de grabación y almacenamiento en la nube hacia servidores nacionales o internacionales SI_x_ NO___ (marque con una X). Nota: como paciente, usted tiene derecho a retractarse de este consentimiento informado en cualquier momento antes o durante la realización de la intervención. Consulta, y que, en caso de no aceptar esta modalidad de tratamiento, puedo continuar recibiendo atención médica.'),0,'J');

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(185,4,utf8_decode('MENORES DE EDAD E INCAPACES:'),0,0,'L',0);

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','',9);
        $pdf->MultiCell(185,4,utf8_decode('En caso de que el paciente sea menor de 18 años, y/o incapaz, este consentimiento será suscrito por su representante legal. Sé que el paciente es incapaz de tomar por sí mismo la decisión de aceptar o rechazar el tratamiento descrito arriba. Por ende, en mi condición de Representante Legal del menor y/o incapaz ___________________________________, el médico tratante me ha explicado de forma satisfactoria en qué consiste, cuáles son los objetivos del procedimiento y me han informado los riesgos y los procedimientos alternativos. He comprendido todo lo anterior, y por ende Yó _____________________________________________ con cedula de ciudadanía número: _____________ Otorgo mi Consentimiento en nombre del paciente menor de edad y/o incapaz, para que se le preste el servicio de Consulta Externa bajo la modalidad de Telemedicina Interactiva. SI_x_ NO___ (marque con una X).'),0,'J');

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(185,4,utf8_decode('MANIFESTACIÓN DE VOLUNTAD:'),0,0,'L',0);

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','',9);
        $pdf->MultiCell(185,4,utf8_decode('En consideración a lo anterior, de manera libre y voluntaria doy mi consentimiento para ser atendido bajo la modalidad de Telemedicina Interactiva; de tal forma que el consentimiento que emito se encuentra exento de cualquier error. En cualquier caso, deseo que se me respeten las siguientes condiciones (si no hay condiciones escríbase (ninguna) y sí no se aceptan algunos de los otros puntos hágase constar):'),0,'J');

        $pdf->Ln();
        $pdf->SetX(13);
        $pdf->SetFont('Arial','',9);
        $dataURI    = self::$firma['firma_consentimiento'];
        $dataPieces = explode(',',$dataURI);
        $encodedImg = $dataPieces[1];
        $decodedImg = base64_decode($encodedImg);

        // Compruebe si la imagen se decodificó correctamente
        if( $decodedImg!==false ){
            //  Guardar imagen en una ubicación temporal
            if( file_put_contents('firma_consentimiento.png',$decodedImg)!==false ){
                $pdf->Image('firma_consentimiento.png',130, 228, 56, 11,'PNG');
            }
        }


        $pdf->Line(120, 245, 195, 245);
        $pdf->SetXY(120, 248);
        $pdf->Cell(85, 4, utf8_decode('Firma paciente'), 0, 'l');

        $pdf->SetXY(120, 253);
        $pdf->Cell(85, 4, utf8_decode('Fecha: '.self::$firma['firma_consentimiento_time']), 0, 'l');
    }

    }
