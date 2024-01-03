<?php namespace App\Formats;

use Codedge\Fpdf\Fpdf\Fpdf;
use App\Modelos\Analisis_evento;
use App\Modelos\Eventos_adverso;
use Illuminate\Support\Facades\DB;

class AnalisisCasos extends FPDF {
    public static $eventos;
    public static $acciones_seguras;
    public static $acciones_mejoras;

    public function generar($data) {

        self::$eventos=Eventos_adverso::select('eventos_adversos.id', 'e.nombre as nombreEvento', 'e.id as eventoId', 'p.Num_Doc',
            DB::raw("CONCAT(p.Primer_Nom,' ',p.SegundoNom, ' ', p.Segundo_Ape,' ',p.Primer_Ape) as nombre_completo"),
            'eventos_adversos.clasificacion', 'eventos_adversos.created_at', 'eventos_adversos.paciente_id',
            'eventos_adversos.relacion', 'p.Edad_Cumplida', 'p.sexo', 'sed.Nombre as sede_ocurrencia', 'sed.id as sedeocurrencia_id',
            'sede.Nombre as sede_reportante', 'eventos_adversos.fecha_ocurrencia', 'd.Producto as medicamento',
            'eventos_adversos.fecha_venci_medicamento', 'eventos_adversos.lote_medicamento',
            'eventos_adversos.invima_medicamento', 'eventos_adversos.referencia', 'eventos_adversos.modelo',
            'eventos_adversos.serial', 'eventos_adversos.invima_dispositivo', 'de.Producto as dispositivo',
            'eventos_adversos.descripcion_hechos', 'eventos_adversos.accion_tomada', 'u.name',
            'u.apellido', 'eventos_adversos.clasificacion_invima', 'eventos_adversos.dosis_medicamento',
            'eventos_adversos.medida_medicamento', 'eventos_adversos.via_medicamento',
            'eventos_adversos.frecuencia_medicamento', 'eventos_adversos.infusion_medicamento',
            'cl.nombre as nombreClasificacion', 'cl.id as clasificacionId', 'te.nombre as nombreTipoevento',
            'te.id as tipoeventoId', 'u.email', 'eventos_adversos.nombre_equipo', 'eventos_adversos.marca_equipo',
            'eventos_adversos.modelo_equipo', 'eventos_adversos.serie_equipo', 'eventos_adversos.placa_equipo', 'ent.nombre as entidad',
            'es.Nombre as estado', 'es.id as estado_id', 'eventos_adversos.lote_dispositivo', 'eventos_adversos.otro_evento',
            'eventos_adversos.servicio_ocurrencia', 'eventos_adversos.servicio_reportante', 'eventos_adversos.nivel_priorizacion',
            'eventos_adversos.priorizacion', 'analisis.fecha_analisis',
            'analisis.cronologia', 'analisis.acciones_inseguras', 'analisis.clasificacion', 'analisis.metodologia',
            'analisis.que_fallo', 'analisis.porque_fallo', 'analisis.que_causo', 'analisis.accion_implemento',
            'analisis.despues_adminmedicamento', 'analisis.factores_explicarelevento', 'analisis.desaparecio_suspendermedicamento',
            'analisis.reaccion_medicamentosospechoso', 'analisis.ampliar_informacionpaciente', 'analisis.evaluacion_causalidad',
            'analisis.clasificacion_invima', 'analisis.seriedad', 'analisis.fecha_muerte', 'analisis.desenlace_evento', 'analisis.causas_esavi',
            'analisis.asociacion_esavi', 'analisis.ventana_mayoriesgo', 'analisis.evidencia_asociacioncausal', 'analisis.factores_esavi',
            'analisis.farmaco_cinetica', 'analisis.condiciones_farmacocinetica', 'analisis.prescribio_manerainadecuada',
            'analisis.medicamento_entrenamiento', 'analisis.potenciales_interacciones', 'analisis.notificacion_refieremedicamento',
            'analisis.problema_biofarmaceutico', 'analisis.deficiencias_sistemas', 'analisis.factores_asociados', 'analisis.medicamento_manerainadecuada',
            'analisis.acciones_inseguras', 'analisis.elemento_funcion', 'analisis.modo_fallo', 'analisis.efecto', 'analisis.npr',
            'analisis.acciones_propuestas', 'analisis.id as analisisevento_id', 'analisis.descripcion_consecuencias', 'eventos_adversos.profesional') ->join('eventos as e', 'eventos_adversos.evento_id', 'e.id') ->leftjoin('pacientes as p', 'eventos_adversos.paciente_id', 'p.id') ->leftjoin('entidades as ent', 'p.entidad_id', 'ent.id') ->join('sedeproveedores as sed', 'eventos_adversos.sede_ocurrencia', 'sed.id') ->join('users as u', 'eventos_adversos.user_id', 'u.id') ->join('sedeproveedores as sede', 'eventos_adversos.sede_reportante', 'sede.id') ->join('estados as es', 'eventos_adversos.estado_id', 'es.id') ->leftjoin('clasificacion_eventos as cl', 'eventos_adversos.clasificacionevento_id', 'cl.id') ->leftjoin('tipo_eventos as te', 'eventos_adversos.tipoevento_id', 'te.id') ->leftjoin('detallearticulos as d', 'eventos_adversos.detallearticulo_id', 'd.id') ->leftjoin('detallearticulos as de', 'eventos_adversos.dispositivo', 'de.id') ->leftjoin('analisis_eventos as analisis', 'analisis.eventosadverso_id', 'eventos_adversos.id') ->with(['pre_analisis'=> function ($query) {
                $query->select('analisis_eventos.eventosadverso_id', 'analisis_eventos.id') ->with(['accion_inseguras'=> function ($query) {
                        $query->select('acciones_inseguras.analisisevento_id', 'acciones_inseguras.id', 'acciones_inseguras.name',
                            'acciones_inseguras.condiciones_paciente', 'acciones_inseguras.metodos', 'acciones_inseguras.colaborador',
                            'acciones_inseguras.equipo_trabajo', 'acciones_inseguras.ambiente', 'acciones_inseguras.recursos',
                            'acciones_inseguras.contexto') ->get();
                    }

                    ]) ->with(['accion_mejoras'=> function ($query) {
                        $query->select('acciones_mejoras.analisisevento_id', 'acciones_mejoras.id', 'acciones_mejoras.nombre',
                            'acciones_mejoras.responsables', 'acciones_mejoras.fecha_cumplimiento', 'acciones_mejoras.fecha_seguimiento',
                            'acciones_mejoras.estado') ->get();
                    }

                    ]) ->get();
            }

            ]) ->with(['asignado_evento'=> function ($query) {
                $query->select('asignado_eventos.eventosadverso_id', 'asignado_eventos.id', 'asignado_eventos.user_id',
                    DB::raw("CONCAT(users.name,' ',users.apellido) as nombreAsignado")) ->join('users', 'asignado_eventos.user_id', 'users.id') ->where('asignado_eventos.estado_id', 1) ->get();
            }

            ]) ->where('eventos_adversos.tipo_id', 12) ->where('eventos_adversos.id', $data) ->first();

        $pdf=new AnalisisCasos('P', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $this->body($pdf);
        $pdf->Output();
    }

    public function header() {
        $this->SetDrawColor(140, 190, 56);
        $this->Rect(5, 5, 200, 287);
        $this->SetDrawColor(0, 0, 0);

        $this->Line(12, 12, 198, 12);
        $this->Line(12, 12, 12, 35);
        $this->Line(12, 35, 198, 35);
        $this->Line(198, 12, 198, 35);
        $this->Line(159, 12, 159, 35);
        $this->Line(50, 12, 50, 35);
        $this->Line(159, 19, 198, 19);
        $this->Line(159, 25, 198, 25);


        $this->SetFont('Arial', 'B', 12);
        $this->SetXY(50, 22);
        $this->MultiCell(109, 4, utf8_decode("INFORME DE ANÁLISIS DE SUCESOS CLÍNICOS"), 0, 'C');
        $logo=public_path() . "/images/logo.png";
        $this->Image($logo, 16, 14, -320);

        $this->SetXY(160, 14);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(15, 4, utf8_decode("Código:"));
        $this->SetFont('Arial', '', 10);
        $this->Cell(20, 4, " FO-GC-049");

        $this->SetXY(160, 20);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(15, 4, utf8_decode("Versión:"));
        $this->SetFont('Arial', '', 10);
        $this->Cell(10, 4, "02");

        $this->SetXY(160, 26);
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(20, 4, utf8_decode("Fecha de aprobación:"));
        $this->SetXY(160, 30);
        $this->SetFont('Arial', '', 10);
        $this->Cell(20, 4, "18/04/2022");
        $this->ln();
        $this->ln();
        $this->ln();

    }

    public function footer() {
        $this->SetXY(190, 287);
        $this->Cell(10, 4, utf8_decode('Página ').$this->PageNo().' de {nb}', 0, 0, 'C');
    }

    public function body($pdf) {

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetX(12);
        $pdf->SetFillColor(106, 193, 34);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(186, 6, utf8_decode('INFORMACIÓN GENERAL'), 1, 1, 'C', 1);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(31, 4, utf8_decode('NOMBRE COMPLETO:'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(55, 4, utf8_decode(self::$eventos->nombre_completo), 1, 0, 'l');
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(31, 4, utf8_decode('IDENTIFICACIÓN'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(31, 4, utf8_decode(self::$eventos->Num_Doc), 1, 0, 'l');
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(20, 4, utf8_decode('EDAD'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(18, 4, utf8_decode(self::$eventos->Edad_Cumplida.' Años'), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(31, 4, utf8_decode('SEDE'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(80, 4, utf8_decode(self::$eventos->sede_ocurrencia), 1, 0, 'l');
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(25, 4, utf8_decode('SERVICIO'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(50, 4, utf8_decode(self::$eventos->servicio_ocurrencia), 1, 0, 'l');
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(31, 4, utf8_decode('FECHA DE REPORTE'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(31, 4, utf8_decode(self::$eventos->created_at), 1, 0, 'l');
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(34, 4, utf8_decode('FECHA DE OCURRENCIA'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(28, 4, utf8_decode(self::$eventos->fecha_ocurrencia), 1, 0, 'l');
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(31, 4, utf8_decode('FECHA DE ANÁLISIS'), 1, 0, 'l');
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(31, 4, utf8_decode(self::$eventos->fecha_analisis), 1, 0, 'l');
        $pdf->Ln();
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetX(12);
        $pdf->SetFillColor(106, 193, 34);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(186, 6, utf8_decode('DESCRIPCIÓN DE LOS HECHOS'), 1, 1, 'C', 1);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$eventos->descripcion_hechos), 1, 'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetX(12);
        $pdf->SetFillColor(106, 193, 34);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(186, 6, utf8_decode('ANÁLISIS'), 1, 1, 'C', 1);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$eventos->cronologia), 1, 'J');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetX(12);
        $pdf->SetFillColor(106, 193, 34);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(186, 6, utf8_decode('ANÁLISIS CAUSAL DEL CASO'), 1, 1, 'C', 1);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(186, 4, utf8_decode('Metodología de análisis: '. self::$eventos->metodologia), 1, 'L');
        $pdf->Ln(); 
        $y=$pdf->GetY();
        if($y > 220) {
            $pdf->AddPage();
            $y=15;
        }

        if(self::$eventos->metodologia=='Protocolo de Londres') {
            self::$acciones_seguras=Analisis_evento::select('a.*') ->join('acciones_inseguras as a', 'a.analisisevento_id', 'analisis_eventos.id') ->where('a.analisisevento_id', self::$eventos->analisisevento_id)->get();
            $n=0;

            foreach (self::$acciones_seguras as $acciones_segura) {
                $n+=1;

                $y=$pdf->GetY();
                #TITULO $pdf->SetX(12);
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->MultiCell(73, 4, utf8_decode('Acción Insegura '. $n .': '. $acciones_segura->name), 0, 'L');
                #TITULO 2 $pdf->SetFont('Arial', 'B', 9);
                $pdf->SetXY(85, $y);
                $pdf->SetFillColor(106, 193, 34);
                $pdf->SetTextColor(255, 255, 255);
                $pdf->Cell(113, 6, utf8_decode('Factores Contributivos'), 1, 1, 'C', 1);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->SetTextColor(0, 0, 0);
                $y7=$pdf->GetY();

                $pdf->SetFont('Arial', '', 9);
                $pdf->SetXY(85, $y+8);
                $pdf->MultiCell(30, 4, utf8_decode('Condiciones del Paciente: '), 0, 'L');
                $y9=$pdf->GetY();
                $acciones_segura['condiciones_paciente']=str_replace(array('[', ']', '"'), array('', '', ''), $acciones_segura['condiciones_paciente']);
                $pdf->SetFont('Arial', '', 9);
                $pdf->SetXY(120, $y+8);
                $pdf->MultiCell(70, 4, utf8_decode($acciones_segura['condiciones_paciente']==null?'No Aplica':$acciones_segura['condiciones_paciente']), 0, 'L');
                $y8=$pdf->GetY();
                $linea1=max($y8, $y9);

                $pdf->SetXY(85, $linea1+4);
                $pdf->MultiCell(70, 4, utf8_decode('Métodos / Procesos: '), 0, 'L');
                $acciones_segura['metodos']=str_replace(array('[', ']', '"'), array('', '', ''), $acciones_segura['metodos']);
                $pdf->SetXY(120, $linea1+4);
                $pdf->MultiCell(70, 4, utf8_decode($acciones_segura['metodos']==null?'No Aplica':$acciones_segura['metodos']), 0, 'L');
                $y1=$pdf->GetY();

                $pdf->SetXY(85, $y1+4);
                $pdf->MultiCell(25, 4, utf8_decode('Colaborador / Individuo: '), 0, 'L');
                $y10=$pdf->GetY();
                $acciones_segura['colaborador']=str_replace(array('[', ']', '"'), array('', '', ''), $acciones_segura['colaborador']);
                $pdf->SetXY(120, $y1+4);
                $pdf->MultiCell(70, 4, utf8_decode($acciones_segura['colaborador']==null?'No Aplica':$acciones_segura['colaborador']), 0, 'L');
                $y2=$pdf->GetY();
                $linea2=max($y10, $y2);

                $pdf->SetXY(85, $linea2+4);
                $pdf->MultiCell(70, 4, utf8_decode('Equipo de trabajo: '), 0, 'L');
                $acciones_segura['equipo_trabajo']=str_replace(array('[', ']', '"'), array('', '', ''), $acciones_segura['equipo_trabajo']);
                $pdf->SetXY(120, $linea2+4);
                $pdf->MultiCell(70, 4, utf8_decode($acciones_segura['equipo_trabajo']==null?'No Aplica':$acciones_segura['equipo_trabajo']), 0, 'L');
                $y3=$pdf->GetY();

                $pdf->SetXY(85, $y3+4);
                $pdf->MultiCell(70, 4, utf8_decode('Ambiente: '), 0, 'L');
                $acciones_segura['ambiente']=str_replace(array('[', ']', '"'), array('', '', ''), $acciones_segura['ambiente']);
                $pdf->SetXY(120, $y3+4);
                $pdf->MultiCell(70, 4, utf8_decode($acciones_segura['ambiente']==null?'No Aplica':$acciones_segura['ambiente']), 0, 'L');
                $y4=$pdf->GetY();

                $pdf->SetXY(85, $y4+4);
                $pdf->MultiCell(30, 4, utf8_decode('Recursos / Materiales / Insumos: '), 0, 'L');
                $y12=$pdf->GetY();
                $acciones_segura['recursos']=str_replace(array('[', ']', '"'), array('', '', ''), $acciones_segura['recursos']);
                $pdf->SetXY(120, $y4+4);
                $pdf->MultiCell(70, 4, utf8_decode($acciones_segura['recursos']==null?'No Aplica':$acciones_segura['recursos']), 0, 'L');
                $y5=$pdf->GetY();
                $linea3=max($y5, $y12);

                $pdf->SetXY(85, $linea3+4);
                $pdf->MultiCell(70, 4, utf8_decode('Contexto institucional: '), 0, 'L');
                $acciones_segura['contexto']=str_replace(array('[', ']', '"'), array('', '', ''), $acciones_segura['contexto']);
                $pdf->SetXY(120, $linea3+4);
                $pdf->MultiCell(70, 4, utf8_decode($acciones_segura['contexto']==null?'No Aplica':$acciones_segura['contexto']), 0, 'L');
                $y6=$pdf->GetY();
                $conteoY=max($y1, $y2, $y3, $y4, $y5, $linea3, $y6);
                $pdf->Ln();
                #cuadrado $pdf->Line(12, $y, 198, $y);
                $pdf->Line(12, $conteoY, 12, $y);
                $pdf->Line(198, $conteoY, 198, $y);
                $pdf->Line(12, $conteoY, 198, $conteoY);
                $pdf->Line(85, $y, 85, $conteoY);
                $pdf->Line(118, $y7, 118, $conteoY);

                #lineas Verticales $pdf->Line(85, $linea1+2, 198, $linea1+2);
                $pdf->Line(85, $y1+2, 198, $y1+2);
                $pdf->Line(85, $linea2+2, 198, $linea2+2);
                $pdf->Line(85, $y3+2, 198, $y3+2);
                $pdf->Line(85, $y4+2, 198, $y4+2);
                $pdf->Line(85, $linea3+2, 198, $linea3+2);

                if($conteoY > 220) {
                    $pdf->AddPage();
                    $y=15;
                }
            }
        }

        if(self::$eventos->metodologia=='Respuesta Inmediata') {
            self::$acciones_seguras=Analisis_evento::where('id', self::$eventos->analisisevento_id)->first();

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetX(12);
            $pdf->SetFillColor(106, 193, 34);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell(186, 6, utf8_decode('RESPUESTA INMEDIATA'), 1, 1, 'C', 1);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetTextColor(0, 0, 0);
            $y=$pdf->GetY();

            if($y > 220) {
                $pdf->AddPage();
                $y=15;
            }

            $pdf->SetFont('Arial', '', 9);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Qué fallo: '.self::$eventos->que_fallo), 1, 'L');

            if($y > 220) {
                $pdf->AddPage();
                $y=15;
            }

            $pdf->SetFont('Arial', '', 9);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Cómo / por qué falló: '.self::$eventos->porque_fallo), 1, 'L');

            if($y > 220) {
                $pdf->AddPage();
                $y=15;
            }

            $pdf->SetFont('Arial', '', 9);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Qué causó: '.self::$eventos->que_causo), 1, 'L');

            if($y > 220) {
                $pdf->AddPage();
                $y=15;
            }

            $pdf->SetFont('Arial', '', 9);
            $pdf->SetX(12);
            $pdf->MultiCell(186, 4, utf8_decode('Qué plan de acción se implementó: '.self::$eventos->accion_implemento), 1, 'L');
            $pdf->Ln();

            if($y > 220) {
                $pdf->AddPage();
                $y=15;
            }
        }

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetX(12);
        $pdf->SetFillColor(106, 193, 34);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(186, 6, utf8_decode('DESENLACE Y CONCLUSIONES'), 1, 1, 'C', 1);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetX(12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 4, utf8_decode(self::$eventos->descripcion_consecuencias), 1, 'J');
        $pdf->Ln();

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetX(12);
        $pdf->SetFillColor(106, 193, 34);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(35, 4, utf8_decode('CLASIFICACIÓN:'), 1, 0, 'L', 1);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetX(47);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(151, 4, utf8_decode(self::$eventos->clasificacion), 1, 'L');
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetX(12);
        $pdf->SetFillColor(106, 193, 34);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(186, 4, utf8_decode('PLAN DE MEJORA'), 1, 0, 'C', 1);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln();

        $pdf->SetX(12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(106, 193, 34);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(62, 4, utf8_decode('ACTIVIDAD'), 1, 0, 'C', 1);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(62, 4, utf8_decode('RESPONSABLE'), 1, 0, 'C', 1);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(62, 4, utf8_decode('FECHA'), 1, 0, 'C', 1);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Ln();
        $inicio=$pdf->GetY();
        self::$acciones_mejoras = Analisis_evento::select('a.*') 
        ->join('acciones_mejoras as a', 'a.analisisevento_id', 'analisis_eventos.id') 
        ->where('a.analisisevento_id', self::$eventos->analisisevento_id)
        ->get();

        $y=$inicio;

        if(count(self::$acciones_mejoras) > 0) {
            foreach (self::$acciones_mejoras as $acciones_mejora) {
                $pdf->SetXY(12, $y);
                $pdf->SetFont('Arial', '', 8);
                $pdf->MultiCell(60, 4, utf8_decode($acciones_mejora->nombre), 0, 'l');
                $y1=$pdf->GetY();
                $pdf->SetXY(80, $y);
                $acciones_mejora['responsables']=str_replace(array('[', ']', '"'), array('', '', ''), $acciones_mejora['responsables']);
                $pdf->MultiCell(50, 4, utf8_decode($acciones_mejora['responsables']), 0, 'C');
                $y2=$pdf->GetY();
                $pdf->SetXY(152, $y);
                $pdf->MultiCell(30, 4, utf8_decode($acciones_mejora->fecha_cumplimiento), 0, 'C');
                $y3=$pdf->GetY();
                $conteoY=max($y, $y1, $y2, $y3);
                $pdf->Ln();
                #cuadrado $pdf->Line(12, $y, 198, $y);
                $pdf->Line(12, $conteoY, 12, $y);
                $pdf->Line(198, $conteoY, 198, $y);
                $pdf->Line(12, $conteoY, 198, $conteoY);
                $pdf->Line(74, $y, 74, $conteoY);
                $pdf->Line(136, $y, 136, $conteoY);
                $y=$conteoY;

                if($conteoY > 220) {
                    $pdf->AddPage();
                    $y=15;
                }
            }
        }else {
            $pdf->SetXY(12, $y);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(60, 4, utf8_decode('No Aplica'), 0, 'C');
            $y1=$pdf->GetY();
            $pdf->SetXY(80, $y);
            $pdf->MultiCell(50, 4, utf8_decode('No Aplica'), 0, 'C');
            $y2=$pdf->GetY();
            $pdf->SetXY(152, $y);
            $pdf->MultiCell(30, 4, utf8_decode('No Aplica'), 0, 'C');
            $y3=$pdf->GetY();
            $conteoY=max($y, $y1, $y2, $y3);
            $pdf->Ln();
            #cuadrado $pdf->Line(12, $y, 198, $y);
            $pdf->Line(12, $conteoY, 12, $y);
            $pdf->Line(198, $conteoY, 198, $y);
            $pdf->Line(12, $conteoY, 198, $conteoY);
            $pdf->Line(74, $y, 74, $conteoY);
            $pdf->Line(136, $y, 136, $conteoY);
            $y=$conteoY;

            if($conteoY > 220) {
                $pdf->AddPage();
                $y=15;
            }

        }

        $pdf->Ln();
        $pdf->Ln();

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetX(12);
        $pdf->Cell(35, 4, utf8_decode('Responsable análisis: Comité de seguridad del paciente'), 0, 0, 'L');
        $pdf->Ln();
        $pdf->Ln();
    }
}
