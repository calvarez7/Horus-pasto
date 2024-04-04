<?php
// Our Controller
namespace App\Http\Controllers\PDF;

use App\Formats\CertificadoRips;
use App\Modelos\Cuporden;
use App\Modelos\Sedeproveedore;
use PDF;
use App\User;
use Carbon\Carbon;
use App\Modelos\Orden;
use App\Formats\Anexos;
use App\Modelos\PaqTemp;
use App\Formats\Servicio;
use App\Modelos\hcmedima;
use App\Formats\Oncologia;
use App\Formats\FichaCovid;
use Codedge\Fpdf\Fpdf\Fpdf;
use App\Formats\Medicamento;
use Illuminate\Http\Request;
use App\Formats\TeleConcepto;
use App\Formats\ActaRecepcion;
use App\Formats\AnalisisCasos;
use App\Formats\FacturaVagout;
use App\Formats\Incapacidades;
use App\Formats\TirillaVagout;
use App\Formats\FormatoSarlaft;
use App\Formats\FormatoNegacion;
use App\Formats\ConsentimientoEmail;
use App\Formats\HistoriaClinica;
use App\Formats\CertificadoCitas;
use App\Formats\FormualOptometria;
use App\Formats\FormulaControl;
use Illuminate\Support\Facades\DB;
use App\Formats\HistoriaClinicaSST;
use App\Formats\MedicamentoFirmado;
use App\Formats\CertificadoAfiliado;
use App\Formats\HistoriaOcupacional;
use App\Formats\Recomendaciones;
use App\Formats\RecomendacionesCups;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Formats\CertificadoRetirados;
use App\Modelos\NovedadesOrdenCompra;
use App\Formats\HistoriaClinicaMasivo;
use App\Formats\OrdenCompraMedicamentos;
use App\Formats\HistoriaClinicaIntengral;
use App\Formats\HistoriaClinicaCompleta;
use App\Formats\IntructivoValidacion1552;
use App\Formats\ConcentimientosInformados;
use App\Formats\CertificadoEmpleadosActivos;
use App\Formats\EvaluacionMedicoOcupacional;
use App\Modelos\gestion_humana_certificados;
use App\Formats\CertificadoRetencionIngresos;
// This is important to add here.
use App\Formats\CertificadoPrestacionServicios;
use App\Formats\CertificadoTerminacionPractica;
use App\Formats\FichaEpidemiologa;
use App\Formats\NovedadesOrdenCompraMedicamentos;
use App\Http\Controllers\Historia\OrdenController;
use App\Http\Controllers\Historia\ResumenhistoriaController;

class PDFController extends Controller
{
    function print(Request $request)
    {
        if (!empty($request->fecha_solicitud)) {
            if ($request->type == 'otros') {
                $fecha_estimada = date("Y-m-d", strtotime("+" . 1 . " months", strtotime($request->fecha_solicitud)));
            } elseif ($request->type == 'formula') {
                $orden = Orden::find($request->orden_id);
                if ($orden) {
                    if ($orden->Estado_id == 18) {
                        $fecha_estimada = date("Y-m-d", strtotime("+" . 2 . " months", strtotime($orden->Fechaorden)));
                    } else {
                        $fecha_estimada = date("Y-m-d", strtotime("+" . 28 . " days", strtotime($orden->Fechaorden)));
                    }
                }
            } else {
                if (empty($request->numero)) {
                    $request->numero = 1;
                }
                $fecha_estimada = date("Y-m-d", strtotime("+" . strval(intval($request->numero) * 28) . " day"));
            }
        } else {
            if ($request->type == 'otros') {
                $fecha_estimada = date("Y-m-d", strtotime("+" . 1 . " months"));
            } elseif ($request->type == 'formula') {
                $orden = Orden::find($request->orden_id);
                if ($orden) {
                    if ($orden->Estado_id == 18) {
                        $fecha_estimada = date("Y-m-d", strtotime("+" . 2 . " months", strtotime($orden->Fechaorden)));
                    } else {
                        $fecha_estimada = date("Y-m-d", strtotime("+" . 28 . " days", strtotime($orden->Fechaorden)));
                    }
                }
            } else {
                if (empty($request->numero)) {
                    $request->numero = 1;
                }
                $fecha_estimada = date("Y-m-d", strtotime("+" . strval(intval($request->numero) * 28) . " day"));
            }
        }

        $fecha_inicio = date("Y-m-d", strtotime("+" . strval((intval($request->numero) * 28) - 27) . " day"));

        $data = [
            //Radium HC
            'Indicacion'              => $request->Indicacion,
            'Hallazgos'               => $request->Hallazgos,
            'Conclusion'              => $request->Conclusion,
            'Notaclaratoria'          => $request->Notaclaratoria,
            'Tecnica'                 => $request->Tecnica,
            'Cups'                    => $request->Cups,
            'Radiologo'               => $request->Radiologo,
            'Regradiologo'            => $request->Regradiologo,
            'Firmaradiologo'          => $request->Firmaradiologo,
            'Dx'                      => $request->Dx,
            'Sexo'                    => $request->Sexo,
            'Edad_Cumplida'           => $request->Edad_Cumplida,
            'Nombrepaciente'          => $request->Nombrepaciente,
            'Num_Doc'                 => $request->Num_Doc,
            'Fecha_Naci'              => $request->Fecha_Naci,
            'Departamento'            => $request->Departamento,
            'Telefono'                => $request->Telefono,
            'Mpio_Afiliado'           => $request->Mpio_Afiliado,
            'Ocupacion'               => $request->Ocupacion,
            'Nombreacompanante'       => $request->Nombreacompanante,
            'Telefonoacompanante'     => $request->Telefonoacompanante,
            'Nombreresponsable'       => $request->Nombreresponsable,
            'Telefonoresponsable'     => $request->Telefonoresponsable,
            'Parentesco'              => $request->Parentesco,
            'Aseguradora'             => $request->Aseguradora,
            'Tipovinculacion'         => $request->Tipovinculacion,
            'Vivecon'                 => $request->Vivecon,
            'Insumos'                 => $request->Insumos,
            'Notas'                   => $request->Notas,
            'Estudio'                 => $request->Estudio,
            'Notaclaratorias'         => $request->Notaclaratorias,

            //Horus HC
            'Provincapacidad'         => $request->Provincapacidad,
            'Oftalmologico'           => $request->Oftalmologico,
            'Genitourinario'          => $request->Genitourinario,
            'Otorrinoralingologico'   => $request->Otorrinoralingologico,
            'Linfatico'               => $request->Linfatico,
            'Osteomioarticular'       => $request->Osteomioarticular,
            'Neurologico'             => $request->Neurologico,
            'Cardiovascular'          => $request->Cardiovascular,
            'Tegumentario'            => $request->Tegumentario,
            'Respiratorio'            => $request->Respiratorio,
            'Endocrinologico'         => $request->Endocrinologico,
            'Gastrointestinal'        => $request->Gastrointestinal,
            'Norefiere'               => $request->Norefiere,
            'NombreAntecedente'       => $request->NombreAntecedente,
            'logo'                    => $request->logo,
            'identificacion'          => $request->Num_Doc,
            'Cédula'                  => $request->Cédula,
            'Fecha_Nacimiento'        => $request->Fecha_Nacimiento,
            'Departamento'            => $request->Departamento,
            'Municipio_Afiliado'      => $request->Municipio_Afiliado,
            'Direccion_Residencia'    => $request->Direccion_Residencia,
            'Laboraen'                => $request->Laboraen,
            'Motivoconsulta'          => $request->Motivoconsulta,
            'Enfermedadactual'        => $request->Enfermedadactual,
            'Resultayudadiagnostica'  => $request->Resultayudadiagnostica,
            'tratamientoncologico'    => $request->tratamientoncologico,
            'Fechacreaincapacidad'    => $request->Fechacreaincapacidad,
            'tel'                     => $request->tel,
            'hj'                      => $request->hj,
            'Proveedor'               => $request->Proveedor,
            'Tipo_Afiliado'           => $request->Tipo_Afiliado,
            'afilia'                  => $request->afilia,
            'especialidad_medico'     => $request->especialidad_medico,
            'Rmedico'                 => $request->Rmedico,
            'Ut'                      => $request->Ut,
            'ips1'                    => $request->ips1,
            'ma'                      => $request->ma,
            'TTipod'                  => $request->TTipod,
            'Tipocita_id'             => $request->Tipocita_id,
            'cirujiaoncologica'       => $request->cirujiaoncologica,
            'ncirujias'               => $request->ncirujias,
            'iniciocirujia'           => $request->iniciocirujia,
            'fincirujia'              => $request->fincirujia,
            'recibioradioterapia'     => $request->recibioradioterapia,
            'inicioradioterapia'      => $request->inicioradioterapia,
            'finradioterapia'         => $request->finradioterapia,
            'nsesiones'               => $request->nsesiones,
            'intencion'               => $request->intencion,
            'Antropometricas'         => $request->Antropometricas,
            'Signos_Vitales'          => $request->Signos_Vitales,
            'Otros_Signos_Vitales'    => $request->Otros_Signos_Vitales,
            'Presión_Arterial'        => $request->Presión_Arterial,
            'Condiciongeneral'        => $request->Condiciongeneral,
            'Planmanejo'              => $request->Planmanejo,
            'Diagnostico_Principal'   => $request->Diagnostico_Principal,
            'Diagnostico_Secundario'  => $request->Diagnostico_Secundario,
            'Antecedentes'            => $request->Antecedentes,
            'Antecedentes_Parentesco' => $request->Antecedentes_Parentesco,
            // ONCOLOGIA
            'Patologiacancelactual'           => $request->Patologiacancelactual,
            'fdxcanceractual'                 => $request->fdxcanceractual,
            'flaboratoriopatologia'           => $request->flaboratoriopatologia,
            'tumorsegunbiopsia'               => $request->tumorsegunbiopsia,
            'localizacioncancer'              => $request->localizacioncancer,
            'Dukes'                           => $request->Dukes,
            'gleason'                         => $request->gleason,
            'Her2'                            => $request->Her2,
            'estadificacioninicial'           => $request->estadificacioninicial,
            'fechaestadificacion'             => $request->fechaestadificacion,
            'cirujiaoncologica'               => $request->cirujiaoncologica,
            'recibioradioterapia'             => $request->recibioradioterapia,
            // FIN ONCOLOGIA
            'Abdomen'                 => $request->Abdomen,
            'Agudezavisual'           => $request->Agudezavisual,
            'Cabezacuello'            => $request->Cabezacuello,
            'Cardiopulmonar'          => $request->Cardiopulmonar,
            'Examenmama'              => $request->Examenmama,
            'Examenmental'            => $request->Examenmental,
            'Extremidades'            => $request->Extremidades,
            'Frecucardiaca'           => $request->Frecucardiaca,
            'Frecurespiratoria'       => $request->Frecurespiratoria,
            'Genitourinario'          => $request->Genitourinario,
            'Neurologico'             => $request->Neurologico,
            'Ojosfondojos'            => $request->Ojosfondojos,
            'Osteomuscular'           => $request->Osteomuscular,
            'Pielfraneras'            => $request->Pielfraneras,
            'Reflejososteotendinos'   => $request->Reflejososteotendinos,
            'Tactoretal'              => $request->Tactoretal,
            'Dietasaludable'          => $request->Dietasaludable,
            'Suenoreparador'          => $request->Suenoreparador,
            'Duermemenoseishoras'     => $request->Duermemenoseishoras,
            'Altonivelestres'         => $request->Altonivelestres,
            'Actividadfisica'         => $request->Actividadfisica,
            'Frecuenciasemana'        => $request->Frecuenciasemana,
            'Duracion'                => $request->Duracion,
            'Fumacantidad'            => $request->Fumacantidad,
            'Fumainicio'              => $request->Fumainicio,
            'Fumadorpasivo'           => $request->Fumadorpasivo,
            'Cantidadlicor'           => $request->Cantidadlicor,
            'Licorfrecuencia'         => $request->Licorfrecuencia,
            'Consumopsicoactivo'      => $request->Consumopsicoactivo,
            'Psicoactivocantidad'     => $request->Psicoactivocantidad,
            'Estilovidaobservaciones' => $request->Estilovidaobservaciones,
            'Tipodiagnostico'         => $request->Tipodiagnostico,
            'Recomendaciones'         => $request->Recomendaciones,
            'Finalidad'               => $request->Finalidad,
            'finalidadTrans'          => $request->finalidadTrans,
            'Entidademite'            => $request->Entidademite,
            'Tipocita'                => $request->Tipocita,
            'Destinopaciente'         => $request->Destinopaciente,
            'Atendido_Por'            => $request->Atendido_Por,
            'Cedulamedico'            => $request->Cedulamedico,
            'Registromedico'          => $request->Registromedico,
            'nombre'                  => $request->Primer_Nom . ' ' . $request->Segundo_Nom . ' ' . $request->Primer_Ape . ' ' . $request->Segundo_Ape,
            'Nombre'                  => $request->Nombre,
            'Especialidad'            => $request->Especialidad,
            'Firma'                   => $request->Firma,
            //GINECOLOGICOS
            'Fechaultimamenstruacion' => $request->Fechaultimamenstruacion,
            'Gestaciones'             => $request->Gestaciones,
            'Partos'                  => $request->Partos,
            'Abortoprovocado'         => $request->Abortoprovocado,
            'Abortoespontaneo'        => $request->Abortoespontaneo,
            'Mortinato'               => $request->Mortinato,
            'Gestantefechaparto'      => $request->Gestantefechaparto,
            'Gestantenumeroctrlprenatal'  => $request->Gestantenumeroctrlprenatal,
            'Eutoxico'                => $request->Eutoxico,
            'Cesaria'                 => $request->Cesaria,
            'Cancercuellouterino'     => $request->Cancercuellouterino,
            'Ultimacitologia'         => $request->Ultimacitologia,
            'Resultado'               => $request->Resultado,
            'Menarquia'               => $request->Menarquia,
            'Ciclos'                  => $request->Ciclos,
            'Regulares'               => $request->Regulares,
            'Observaciongineco'       => $request->Observaciongineco,

            'marcapaciente'           => $request->marcapaciente,
            'Firma_Auditor'           => $request->Firma_Auditor,
            'edad'                    => $request->Edad_Cumplida,
            'sexo'                    => $request->Sexo,
            'ips'                     => $request->IPS,
            'direccion'               => $request->Direccion_Residencia,
            'aseguradora'             => $request->aseguradora,
            'correo'                  => $request->Correo,
            'telefono'                => $request->Telefono,
            'celular'                 => $request->Celular,
            'estado_afiliado'         => $request->Estado_Afiliado,
            'fecha_solicitud'         => $request->fecha_solicitud,
            'fecha_auditoria'         => $request->fecha_auditoria,
            'dx_principal'            => $request->dx_principal,
            'grupo_farmaceutico'      => $request->grupo_farmaceutico,
            'medicamento'             => $request->medicamento,
            'cantidad_dosis'          => $request->cantidad_dosis,
            'via'                     => $request->via,
            'frecuencia'              => $request->frecuencia,
            'unidad_tiempo'           => $request->unidad_tiempo,
            'duracion'                => $request->duracion,
            'cantidad_mensual'        => $request->cantidad_mensual,
            'renovable'               => $request->renovable,
            'Observaciones'           => $request->Observaciones,
            'Medicoordeno'            => $request->Medicoordeno,
            'sede'                    => $request->sede,
            'Direccion_Sede'          => $request->Direccion_Sede,
            'holi'                    => $request->holi,
            'prestador'               => $request->prestador,
            'servicio'                => $request->servicio,
            'medico_tratante'         => $request->medico_tratante,
            'medico_ordena'           => $request->medico_ordena,
            'autorizado'              => $request->autorizado,
            'firma_digital'           => $request->firma_digital,
            'fecha_formula'           => $request->fecha_formula,
            'autorizacion'            => $request->autorizacion,
            'medicamentos'            => $request->medicamentos,
            'servicios'               => $request->servicios,
            'FechaInicio'             => $request->FechaInicio,
            'CantidadDias'            => $request->CantidadDias,
            'FechaFinal'              => $request->FechaFinal,
            'Prorroga'                => $request->Prorroga,
            'Cie10'                   => $request->Cie10,
            'Contingencia'            => $request->Contingencia,
            'Descripcion'             => $request->Descripcion,
            'Firma'                   => $request->Firma,
            'TextCie10'               => $request->TextCie10,
            'order_id'                => $request->orden_id,
            'tipo_cita'               => $request->Tipo_cita,
            'Especialidad'            => $request->Especialidad,
            'Fecha_actual'            => date("Y-m-d H:i:s"),
            'Fecha_estimada'          => $fecha_estimada,
            'Fecha_inicio'            => $fecha_inicio,
            'total'                   => $request->total,
            'Datetimeingreso'         => $request->Datetimeingreso,
            'numero'                  => $request->numero,
            'page'                    => $request->page,
            'mensaje'                 => $request->mensaje,

            //Prestador
            'nombrePrestador'         => $request->nombrePrestador,
            'direccionPrestador'      => $request->direccionPrestador,
            'nitPrestador'            => $request->nitPrestador,
            'telefono1Prestador'      => $request->telefono1Prestador,
            'telefono2Prestador'      => $request->telefono2Prestador,
            'codigoHabilitacion'      => $request->codigoHabilitacion,
            'municipioPrestador'      => $request->municipioPrestador,
            'departamentoPrestador'   => $request->departamentoPrestador,

        ];

        if ($request->type == 'formula') {
            $pdf = new Medicamento();
            $pdf->generar($data, $request->SendEmail, "F O R M U L A  M E D I C A");
            exit();
        } elseif ($request->type == 'pendiente') {
            $pdf = new Medicamento();
            $pdf->generar($data, $request->SendEmail, "M E D.  P E N D I E N T E S");
            exit();
            //$pdf = PDF::loadView('pdf_view_pendientes', $data);
        } elseif ($request->type == 'ordenamiento') {
            $pdf = PDF::loadView('pdf_view_ordenamiento', $data);
        } elseif ($request->type == 'teleconcepto') {
            $pdf = new TeleConcepto();
            $pdf->generar($request->all());
            exit();
            //            $pdf = PDF::loadView('pdf_view_teleconcepto', $request->all());
        } elseif ($request->type == 'autorizacion') {
            $pdf = PDF::loadView('pdf_view_autorizacion', $data);
        } elseif ($request->type == 'medicamentos') {
            $pdf = PDF::loadView('pdf_view_medicamentos', $data);
        } elseif ($request->type == 'envio') {
            $pdf = PDF::loadView('pdf_view_envio', $data);
        } elseif ($request->type == 'observacion') {
            $pdf = PDF::loadView('pdf_view_observacion', $data);
        } elseif ($request->type == 'incapacidad') {
            $pdf = new Incapacidades();
            $pdf->generar($request->orden_id);
            exit();
        } elseif ($request->type == 'otros') {
            $ubicaciones = [];
            foreach ($request->servicios as $servicio) {
                $cupOrden = Cuporden::join('cups as c', 'c.id', 'cupordens.cup_id')
                    ->leftjoin('autorizacions as a', 'a.Cuporden_id', 'cupordens.id')
                    ->where('cupordens.id', $servicio['identificador'])->first();
                $orden = Orden::find($cupOrden->Orden_id);
                $fechaOrden = (isset($cupOrden->fecha_orden) ? $cupOrden->fecha_orden : $orden->Fechaorden);
                if ($cupOrden->Ubicacion_id) {
                    $sede = Sedeproveedore::find($cupOrden->Ubicacion_id);
                    $ubicaciones[$fechaOrden][$sede->id]["servicios"][] = $cupOrden;
                    $ubicaciones[$fechaOrden][$sede->id]["datos"] = $sede;
                } else {
                    $ubicaciones[$fechaOrden]['sin_dato']['servicios'][] = $cupOrden;
                    $ubicaciones[$fechaOrden]['sin_dato']["datos"] = [];
                }
            }
            foreach ($ubicaciones as $fecha) {
                foreach ($fecha as $ubicacion) {
                    $pdf = new Servicio();
                    $pdf->generar($ubicacion, $request->Correo);
                }
            }
            exit();
        } elseif ($request->type == 'cita') {
            $pdf = new CertificadoCitas();
            $pdf->generar($request->all());
            exit();
            //$pdf = PDF::loadView('pdf_view_cita', $data);
        } elseif ($request->type == 'test') {
            $historia = DB::select("exec dbo.GeneraHC" . "'$request->id'");
            $pdf = new HistoriaClinica();
            $pdf->generar((array) $historia[0]);
            exit();
            //            $pdf = PDF::loadView('pdf_historiaclinica', $data);
        } elseif ($request->type == 'radium') {
            $pdf = PDF::loadView('pdf_historiaradium', $data);
        } elseif ($request->type == 'vagout') {
            $pdf = new FacturaVagout();
            $pdf->generar($data);
            exit();
        } elseif ($request->type == 'oncologia') {
            $pdf = new Oncologia();
            $orden = Orden::find($request->orden);
            $pdf->generar($orden);
            exit();
        } elseif ($request->type == 'MedicamentoFirmado') {
            $pdf = new MedicamentoFirmado();
            $pdf->generar($data, $request->SendEmail, "F O R M U L A  M E D I C A");
            exit();
        } elseif ($request->type == 'historia') {
            $pdf = new HistoriaClinica();
            $pdf->generar($request->all());
            exit();
        } elseif ($request->type == 'historiaintegral') {
            $pdf = new HistoriaClinicaIntengral();
            $pdf->generar($request->cita_paciente_id);
            exit();
        } elseif ($request->type == 'historiaintegralCompleta') {
            $pdf = new HistoriaClinicaCompleta();
            $pdf->generar($request->cita_paciente_id);
            exit();
        } elseif ($request->type == 'Analisis') {
            $pdf = new AnalisisCasos();
            $pdf->generar($request->id);
            exit();
        } elseif ($request->type == 'formulaMedicamentos') {
            $pdf = new FormulaControl();
            $pdf->generar($request->id);
            exit();
        } elseif ($request->type == 'evaluacionOcupacional') {
            $pdf = new EvaluacionMedicoOcupacional();
            $pdf->generar($request->all());
            exit();
        } elseif ($request->type == 'historiaOcupacional') {
            $pdf = new HistoriaOcupacional();
            $pdf->generar($request->data);
            // $pdf = PDF::loadView('pdf_salud', $request->data);
            exit();
        } elseif ($request->type == 'covid') {
            $pdf = new FichaCovid();
            $pdf->generar($request->all());
            exit();
        } elseif ($request->type == 'Negacion') {
            $pdf = new FormatoNegacion();
            $pdf->generar($request->all());
            exit();
        } elseif ($request->type == 'consentimiento') {
            $pdf = new ConsentimientoEmail();
            $pdf->generar($request->all());
            exit();
        } elseif ($request->type == 'Anexo') {
            $pdf = new Anexos();
            $pdf->generar($request->all());
            exit();
        } elseif ($request->type == 'optometriaOrden') {
            $pdf = new FormualOptometria();
            $pdf->generar($request->id);
            exit();
        } elseif ($request->type == 'recomendacionOrden') {
            $pdf = new Recomendaciones();
            $pdf->generar($request->id);
            exit();
        } elseif ($request->type == 'recomendacionOrdenCup') {
            $pdf = new RecomendacionesCups();
            $pdf->generar($request->all());
            exit();
        } elseif ($request->type == 'concentimiento') {
            $pdf = new ConcentimientosInformados();
            $pdf->generar($request->all());
            exit();
        } elseif ($request->type == 'fichasEpidemiologa') {
            $pdf = new FichaEpidemiologa();
            $pdf->generar($request->all());
            exit();
        } elseif ($request->type == 'historiasst') {
            $pdf = new HistoriaClinicaSST();
            $pdf->generar($request->id);
            exit();
        } elseif ($request->type == 'validador1552') {
            $pdf = new IntructivoValidacion1552();
            $pdf->generar($request->all());
            exit();
        } elseif ($request->type == 'historiaMasivo') {
            //            dd($request->all());/
            //----NO BORRAR LO COMENTADO------
            //              $cedulas = ['8470129','170163'];
            //              foreach ($cedulas as $cedula){
            //              $historia = DB::select("exec dbo.historialHCMasivo" . "'$cedula'");
            $pdf = new HistoriaClinicaMasivo();
            $pdf->generar($request->historias);

            //                $pdf->generar($historia);
            //            }
            exit();
        } elseif ($request->type == 'actaRecepcion') {
            //            $novedades = NovedadesOrdenCompra::join('ordencompras as o','o.id','novedades_orden_compras.ordencompras_id')
            //                ->where('o.solicitudcompra_id',$request->id)
            //                ->get();
            //            if(count($novedades)>0){
            //            dd($request->all());
            $pdf = new ActaRecepcion();
            $pdf->generar($request->all());
            //            }
            exit();
        } elseif ($request->type == 'NovedadesOrden') {
            $pdf = new NovedadesOrdenCompraMedicamentos();
            $pdf->generar($request->all());
            exit();
        } elseif ($request->type == 'ordenCompra') {
            $pdf = new OrdenCompraMedicamentos();
            $pdf->generar($request->id);
            exit();
        } elseif ($request->type == 'Sarlafts') {
            $pdf = new FormatoSarlaft();
            $pdf->generar($request->id);
            exit();
        } elseif ($request->type == 'certificadoAfiliado') {
            $pdf = new CertificadoAfiliado();
            $pdf->generar($request->id);
            exit();
        } elseif ($request->type == 'Analisis') {
            $pdf = new AnalisisCasos();
            $pdf->generar($request->id);
            exit();
        } elseif ($request->type == 'tirillaVagout') {
            $pdf = new TirillaVagout();
            $pdf->generar($request->id);
            exit();
        } elseif ($request->type == 'certificadoRetirado') {
            $empleado = gestion_humana_certificados::select('*')->where('documento', $request->cedula)
                ->first();
            if ($empleado->estado_empleado == 'ACTIVO') {
                return response()->json([], 421);
            } else if ($request->cedula != $empleado->documento) {
                return response()->json([], 422);
            } else {
                $pdf = new CertificadoRetirados();
                $pdf->generar($request->cedula);
            }
            exit();
        } elseif ($request->type == 'FinPracticas') {

            $empleado = gestion_humana_certificados::select('*')->where('documento', $request->numero)
                ->first();
            if ($empleado->estado_empleado == 'ACTIVO') {
                return response()->json([], 421);
            } else if ($request->numero != $empleado->documento) {
                return response()->json([], 422);
            } else {
                $pdf = new CertificadoTerminacionPractica();
                $pdf->generar($request->numero);
            }
            exit();
        } elseif ($request->type == 'CertificadoActivo') {

            if ($request->cedula1) {
                $pdf = new CertificadoEmpleadosActivos();
                $pdf->generar($request->cedula1);
            } else {
                $user = User::select('*')->where('id', $request->id)->get()->toArray();

                $cedula1 = [];
                foreach ($user as $key) {
                    $cedula1 = $key['cedula'];
                }
                $pdf = new CertificadoEmpleadosActivos();
                $pdf->generar($cedula1);
            }
            exit();
        } elseif ($request->type == 'CertificadoPrestacionServicios') {
            $pdf = new CertificadoPrestacionServicios();
            $pdf->generar($request->cedula2);
            exit();
        } elseif ($request->type == 'CertificadoRetencionIngresos') {
            // dd($request->all());
            if ($request->cedula3) {
                $empleado = gestion_humana_certificados::select('*')->where('documento', $request->cedula3)
                    ->first();

                $valida = DB::select("exec SP_CERTIFICADO_DIAN  ?,?,?,?", [$request->cedula3, $request->fechaI, $request->fechaF, $request->tipo]);

                if ($empleado->documento != $request->cedula3) {
                    return response()->json([], 422);
                } else if ($request->fechaI < $empleado->fecha_ingreso) {
                    return response()->json([], 421);
                } else if ($valida == null) {
                    return response()->json([], 421);
                } else {
                    $pdf = new CertificadoRetencionIngresos();
                    $pdf->generar($request->cedula3, $request->tipo, $request->fechaI, $request->fechaF);
                }
            } else {
                $user = User::select('*')->where('id', $request->id)->get()->toArray();

                $cedula3 = [];
                foreach ($user as $key) {
                    $cedula3 = $key['cedula'];
                }
                $empleado = gestion_humana_certificados::select('*')->where('documento', $cedula3)
                    ->first();

                $valida = DB::select("exec SP_CERTIFICADO_DIAN  ?,?,?,?", [$cedula3, $request->fechaI, $request->fechaF, "0"]);

                if ($request->fechaI < $empleado->fecha_ingreso) {
                    return response()->json([], 421);
                } else if ($valida == null) {
                    return response()->json([], 421);
                } else if ($empleado->reporte_dian == true) {
                    return response()->json(['message' => 'no'], 422);
                } else {
                    $pdf = new CertificadoRetencionIngresos();
                    $pdf->generar($cedula3, $request->tipo, $request->fechaI, $request->fechaF);
                }
            }
            exit();
        } elseif ($request->type == 'certificadoRips') {
            $pdf = new CertificadoRips();
            $pdf->generate($request->id, $request->tipo);
            exit();
        }

        $customPaper = array(0, 0, 792, 612);
        $pdf->setPaper('a4', 'portrait');

        if ($request->SendEmail) {

            $nombre    = $data['nombre'];
            $documento = $data['Num_Doc'];
            $data      = array(
                'email' => $request->Correo,
            );

            $email = Mail::send('send_mail', $data, function ($message) use ($data, $pdf, $nombre, $documento) {
                $message->to($data['email']);
                $message->subject($documento . " " . $nombre);
                $message->attachData($pdf->output(), 'Orden Sumimedical' . ' ' . $documento . ' ' . $nombre . '.pdf');
            });
        }

        return $pdf->download();
    }

    public function enviarHC(Request $request)
    {
        $SendEmail = $request->Correo1;
        $pdf = new HistoriaClinicaIntengral();
        $pdf->generar($request->cita_paciente_id, $SendEmail);

        return response()->json([
            'message' => 'se envio correctamente sapo!'
        ], 200);
    }

    function printRipVoucher(Request $request)
    {

        $paqTemp = PaqTemp::where('id', $request->id)->with('afTemps')->first();

        $data = [
            'paqTemp' => $paqTemp,
            'number' => 1
        ];

        $pdf = PDF::loadView('pdf_af_voucher', $data);

        return $pdf->download();
    }


    public function reporteOcupacional(Request $request)
    {
        $appointments = Collect(DB::select("exec dbo.Reporte_sst ?,?", [$request->startDate, $request->finishDate]));
        $array = json_decode($appointments, true);
        return (new FastExcel($array))->download('file.xls');
    }


    public function reporteCaracterizacion(Request $request)
    {
        $appointments = DB::select("exec dbo.Caracterizacion ?,?", [$request->startDate, $request->finishDate]);

        $combinedData = array_merge($appointments);
        if (empty($combinedData)) {
            return response()->json(['error'=>"No hay registros para generar el archivo"],404);
        }

        return (new FastExcel($combinedData))->download('file.xls');
    }
}

