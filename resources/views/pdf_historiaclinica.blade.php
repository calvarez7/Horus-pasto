<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @page {
            margin: 180px 50px;
        }

        #header {
            position: fixed;
            left: -80px;
            top: -180px;
            right: -50px;
            height: 130px;
            background-color: #84c44c;
            text-align: center;
            background-image: url('/storage/images/1.PNG');
            background-repeat: no-repeat;
            border-bottom: dotted;
        }

#headerOncologia {
    position: fixed;
    left: -80px;
    top: -180px;
    right: -50px;
    height: 130px;
    background-color: purple;
    text-align: center;
    background-image: url('/storage/images/1.PNG');
    background-repeat: no-repeat;
    border-bottom: dotted;
}

#headerImagenologia {
    position: fixed;
    left: -80px;
    top: -180px;
    right: -50px;
    height: 130px;
    background-color: purple;
    text-align: center;
    background-image: url('/storage/images/logoradium.png');
    background-repeat: no-repeat;
    border-bottom: dotted;
}

        #footer {
            position: fixed;
            left: -50px;
            bottom: -180px;
            right: -50px;
            height: 30px;
            background-color: lightblue;
            background-image: url('/storage/images/piesumi.PNG')
        }

        #footerOncologia {
            position: fixed;
            left: -50px;
            bottom: -180px;
            right: -50px;
            height: 30px;
            background-color: lightblue;
            background-image: url('/storage/images/piesumi.PNG')
        }

        #footerImagenologia {
            position: fixed;
            left: -50px;
            bottom: -180px;
            right: -50px;
            height: 30px;
            background-color: lightblue;
            background-image: url('/storage/images/radiumhc.png')
        }

        #footer .page:after {
            content: counter(page, upper-roman);
        }

    </style>

<body>
    @if ($Tipocita_id == 7)
    <div id="headerOncologia">
        <div class="card" style="margin-top: 30px; margin-left: 90px">
            {{-- <img src="img_avatar.png" alt="Avatar" style="width:100%"> --}}
            <div class="container" style="">
                <label for="IPS">Punto de Atención: {{$ips}}</label>
                <br>
                <label for="Consulta">Consulta: {{$Especialidad}}</label>
                <br>
                <label for="Consulta">Tipo: {{$Tipocita}}</label>
                <br>
                <label for="Fecha">Fecha Solicitud: {{$Datetimeingreso}}</label>
            </div>
        </div>
    </div>
    @elseif($Tipocita_id == 6)
    <div id="headerImagenologia">

    </div>
    @else
    <div id="header">
        <div class="card" style="margin-top: 30px; margin-left: 90px">
            {{-- <img src="img_avatar.png" alt="Avatar" style="width:100%"> --}}
            <div class="container" style="">
                <label for="IPS">Punto de Atención: {{$ips}}</label>
                <br> @if ($Tipocita_id != 1 && $Tipocita_id != 3 && $Tipocita_id != 4 && $Tipocita_id != 5 &&
                $Tipocita_id != 7)
                <label for="Consulta">Consulta: {{$Especialidad}}</label>
                <br> @endif
                <label for="Consulta">Tipo: {{$Tipocita}}</label>
                <br>
                <label for="Fecha">Fecha Solicitud: {{$Datetimeingreso}}</label>
            </div>
        </div>
    </div>
    @endif @if ($Tipocita_id == 7)
    <div id="footerOncologia">
    </div>
    @else
    @endif @if ($Tipocita_id == 6)
    <div id="footerImagenologia">
    </div>
    @else
    <div id="footer">
    </div>
    @endif

    <div id="content" style="width:100%; float:left; height:auto; border:1px">
        <style type="text/css">
            .tg {
                border-collapse: collapse;
                border-spacing: 0;
                margin-left: -30px;
                margin-right: -50px
            }

            .tg td {
                font-family: Arial, sans-serif;
                font-size: 10px;
                padding: 8px 10px;
                border-style: solid;
                border-width: 1px;
                overflow: hidden;
                word-break: normal;
                border-color: black;
            }

            .tg th {
                font-family: Arial, sans-serif;
                font-size: 10px;
                font-weight: normal;
                padding: 8px 10px;
                border-style: solid;
                border-width: 1px;
                overflow: hidden;
                word-break: normal;
                border-color: black;
            }

            .tg .tg-t2c5 {
                font-size: 10px;
                font-family: Tahoma, Geneva, sans-serif !important;
                ;
                background-color: #efefef;
                color: #343434;
                border-color: #3eb39e;
                text-align: center;
                vertical-align: top
            }

            .tg .tg-qgl5 {
                font-size: 10px;
                font-family: Tahoma, Geneva, sans-serif !important;
                ;
                background-color: #ffffff;
                border-color: #3eb39e;
                text-align: left;
                vertical-align: top
            }

            .tg .tg-acmg {
                font-size: 10px;
                font-family: Tahoma, Geneva, sans-serif !important;
                ;
                border-color: #3eb39e;
                text-align: left;
                vertical-align: top
            }

            .tg .tg-j7to {
                font-size: 10px;
                font-family: Tahoma, Geneva, sans-serif !important;
                ;
                border-color: #3eb39e;
                text-align: center;
                vertical-align: bottom
            }

            .tg .tg-k5cz {
                font-size: 10px;
                font-family: Tahoma, Geneva, sans-serif !important;
                ;
                background-color: #efefef;
                color: #343434;
                border-color: #3eb39e;
                text-align: left;
                vertical-align: top
            }

            .tg .tg-9zda {
                font-size: 10px;
                font-family: Tahoma, Geneva, sans-serif !important;
                ;
                border-color: #3eb39e;
                text-align: left;
                vertical-align: bottom
            }

            .tg .tg-zp25 {
                font-size: 10px;
                font-family: Tahoma, Geneva, sans-serif !important;
                ;
                background-color: #ffffff;
                border-color: #3eb39e;
                text-align: left;
                vertical-align: bottom
            }

            .tg .tg-yks0 {
                font-weight: bold;
                font-size: 10px;
                font-family: Tahoma, Geneva, sans-serif !important;
                ;
                background-color: #efefef;
                color: #343434;
                border-color: #3eb39e;
                text-align: center;
                vertical-align: bottom
            }

            .tg .tg-7ghi {
                font-size: 10px;
                font-family: Tahoma, Geneva, sans-serif !important;
                ;
                background-color: #efefef;
                color: #343434;
                border-color: #3eb39e;
                text-align: center;
                vertical-align: middle
            }

            .tg .tg-j7p5 {
                border-color: #3eb39e;
                text-align: left;
                vertical-align: top
            }

        </style>
        <table class="tg" style="margin-top: -11px; margin-left: -40px; margin-right: -40px;">
            <tr>
                <th class="tg-7ghi" colspan="34"><span style="font-weight:bold">Información básica del paciente</span>
                </th>
            </tr>
            <tr>
                <td class="tg-yks0" colspan="5"><span style="font-weight:bold">Nombre Completo</span></td>
                <td class="tg-zp25" colspan="10">{{$Nombre}}</td>
                <td class="tg-j7to" colspan="3"><span style="font-weight:700">Identificación</span></td>
                <td class="tg-9zda" colspan="7">{{$Cédula}}</td>
                <td class="tg-j7to" colspan="4"><span style="font-weight:bold">Fecha nacimiento</span></td>
                <td class="tg-9zda" colspan="5">{{$Fecha_Nacimiento}}</td>
            </tr>
            <tr>
                <td class="tg-t2c5" colspan="4"><span style="font-weight:bold">Edad</span></td>
                <td class="tg-qgl5" colspan="3">{{$edad}}</td>
                <td class="tg-t2c5" colspan="3"><span style="font-weight:bold">Genero</span></td>
                <td class="tg-qgl5" colspan="4">{{$sexo}}</td>
                <td class="tg-t2c5" colspan="4"><span style="font-weight:700">Tipo afiliación</span></td>
                <td class="tg-acmg" colspan="7">{{$Tipo_Afiliado}}</td>
                <td class="tg-t2c5" colspan="4"><span style="font-weight:bold">Departamento</span></td>
                <td class="tg-qgl5" colspan="5">{{$Departamento}}</td>
            </tr>
            <tr>
                <td class="tg-t2c5" colspan="4"><span style="font-weight:bold">Teléfono</span></td>
                <td class="tg-qgl5" colspan="5">{{$Telefono}}</td>
                <td class="tg-t2c5" colspan="5"><span style="font-weight:bold">Municipio</span></td>
                <td class="tg-qgl5" colspan="10">{{$Municipio_Afiliado}}</td>
                <td class="tg-t2c5" colspan="5"><span style="font-weight:700">Estado civil</span></td>
                <td class="tg-qgl5" colspan="5"></td>
            </tr>
            @if(!empty($Ocupacion))
            <tr>
                <td class="tg-t2c5" colspan="5"><span style="font-weight:bold">Ocupación</span></td>
                <td class="tg-qgl5" colspan="12"></td>
                <td class="tg-t2c5" colspan="7"><span style="font-weight:bold">Nombre Acompañante</span></td>
                <td class="tg-qgl5" colspan="10"></td>
            </tr>
            <tr>
                <td class="tg-k5cz" colspan="5"><span style="font-weight:700">Teléfono Acompañante</span></td>
                <td class="tg-qgl5" colspan="6"></td>
                <td class="tg-k5cz" colspan="6"><span style="font-weight:700">Nombre Responsable</span></td>
                <td class="tg-qgl5" colspan="9"></td>
                <td class="tg-k5cz" colspan="4"><span style="font-weight:700">Teléfono Responsable</span></td>
                <td class="tg-qgl5" colspan="4"></td>
            </tr>
            @endif
        </table>
    </div>
    <!-- prueba -->
    @if ($Tipocita_id == 1)
    <div style="margin-top: 150px; margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Observaciones de Transcrición</b></h4>
            <pre
                style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                    {{$Observaciones}}
                </pre>
        </div>
    </div>
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Información del profesional</b></h4>
            <pre
                style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                <p><b>Medico que Ordeno:</b> {{$Medicoordeno}}</p>
                <p><b>Diagnóstico Principal:</b> {{$Diagnostico_Principal}}</p>
                <p><b>Finalidad:</b> {{$finalidadTrans}}</p>
                <p><b>Entidad que emite:</b> {{$Entidademite}}</p>
                </pre>
        </div>
    </div>
    @endif
    <!-- inicio -->
    @if ($Tipocita_id == 6)
        <table class="tg" style="margin-top: 150px; margin-left: -40px; margin-right: -40px; width:100%;">
            <tr>
                <td class="tg-7ghi" ><span style="font-weight:bold">ESTUDIO</span>
                </td>
            </tr>
            <tr>
                <td class="tg-zp25">{{$Cups}}</td>
            </tr>
        </table>
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: LEFT; margin-left: 8px;"><b style="background-color: #efefef">INDICACION</b></h4>
            <P
                style="margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif; text-justify: inter-word; text-align: justify !important">
                    {{$Indicacion}}
            </P>
        </div>
    </div>
    {{-- <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: LEFT; margin-left: 8px;"><b style="background-color: #efefef">TECNICA</b></h4>
            <P
                style="margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif; text-justify: inter-word; text-align: justify !important">
                    {{$Tecnica}}
            </P>
        </div>
    </div> --}}
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: LEFT; margin-left: 8px;"><b style="background-color: #efefef">HALLAZGOS</b></h4>
            <p
                style="margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif; text-justify: inter-word; text-align: justify !important">
                    {{$Hallazgos}}
                </p>
        </div>
    </div>
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: LEFT; margin-left: 8px;"><b style="background-color: #efefef">CONCLUSION</b></h4>
            <p
                style="margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif; text-justify: inter-word; text-align: justify !important">
                    {{$Conclusion}}
                </p>
        </div>
    </div>
    @endif
    <!-- inicio -->
    @if ($Tipocita_id == 6)
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: LEFT; margin-left: 8px;"><b style="background-color: #efefef">NOTACLARATORIA</b></h4>
            <p
                style="margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif; text-justify: inter-word; text-align: justify !important">
                    {{$Notaclaratoria}}
                </p>
        </div>
    </div>
    @endif
    <!-- inicio -->
    @if ($Tipocita_id != 1 && $Tipocita_id != 3 && $Tipocita_id != 4 && $Tipocita_id != 5 && $Tipocita_id != 6)
    <div style="margin-top: 150px; margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Motivo de Consulta</b></h4>
            <pre
                style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif; text-transform: capitalize !important">
                    {{$Motivoconsulta}}
                </pre>
        </div>
    </div>
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Enfermedad Actual</b></h4>
            <pre
                style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                    {{$Enfermedadactual}}
                </pre>
        </div>
    </div>
    @endif @if ($Tipocita_id == 7 && $Tipocita_id != 1 && $Tipocita_id != 3 && $Tipocita_id != 4 && $Tipocita_id != 5 &&
    $Tipocita_id != 6)
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Tratamiento Oncologico</b></h4>
            <pre
                style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                    {{$tratamientoncologico}}
                </pre>
        </div>
    </div>
    @endif @if ($Tipocita_id == 7 && $Tipocita_id != 1 && $Tipocita_id != 3 && $Tipocita_id != 4 && $Tipocita_id != 5 &&
    $Tipocita_id != 6)
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Cirugia Oncologica</b></h4>
            <table class="egt">
                <tr>
                    <td>
                        <ul>
                            @if ($recibioradioterapia == 0 or $recibioradioterapia == 1)
                            <li>
                                <label for="Neurologico"><b>Recibio Radioterapia:</b></label>
                            </li>
                            <li>
                                <label for="Neurologico"><b>Numero Cirugias:</b> {{$inicioradioterapia}}</label>
                            </li>
                            <li>
                                <label for="Neurologico"><b>Fecha Inicio:</b> {{$finradioterapia}}</label>
                            </li>
                            <li>
                                <label for="Neurologico"><b>Fecha Final:</b> {{$nsesiones}}</label>
                            </li>
                            @endif
                        </ul>
                    </td>

                    <td>
                        <ul>
                            @if ($cirujiaoncologica == 0 or $cirujiaoncologica == 1)
                            <li>
                                <label for="Neurologico"><b>Tiene Cirugias:</b> </label>
                            </li>
                            <li>
                                <label for="Neurologico"><b>Numero Cirugias:</b> {{$ncirujias}}</label>
                            </li>
                            <li>
                                <label for="Neurologico"><b>Fecha Inicio:</b> {{$iniciocirujia}}</label>
                            </li>
                            <li>
                                <label for="Neurologico"><b>Fecha Final:</b> {{$fincirujia}}</label>
                            </li>
                            @endif
                            </u>
                    </td>
                    <td>
                        <ul>
                            <label for="Neurologico"><b>Intencion del Tratamiento Oncologico:</b> {{$intencion}}
                            </label>
                            <br>
                        </ul>
                    </td>

                </tr>

            </table>
        </div>
    </div>
    @endif @if ($Tipocita_id == 7 && $Tipocita_id != 1 && $Tipocita_id != 3 && $Tipocita_id != 4 && $Tipocita_id != 5 &&
    $Tipocita_id != 6)
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Descripcióm Patología Actual</b></h4>
            <pre
                style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                    {{$Patologiacancelactual}}
                </pre>
            <br>
            <br>
            <table class="egt">
                <tr>
                    <td>
                        <ul>
                            <li>
                                <label for="Neurologico"><b>fecha de ingreso al laboratorio de patología:</b>
                                    {{$fdxcanceractual}}
                                </label>
                            </li>
                            <li>
                                <label for="Neurologico"><b>fecha de reporte del laboratorio de
                                        patología:</b>{{$flaboratoriopatologia}}</label>
                            </li>
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    @endif @if ($Tipocita_id == 7 && !empty($localizacioncancer) && $Tipocita_id != 1 && $Tipocita_id != 3 &&
    $Tipocita_id != 4 && $Tipocita_id != 5 && $Tipocita_id != 6)
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Tipo de Tumor</b></h4>
            <table class="egt">
                <tr>
                    <td>
                        <ul>
                            <li>
                                <label for="Neurologico"><b>Localización Cancer:</b> {{$localizacioncancer}}</label>
                            </li>
                            </label>
                            </li>
                            @if (!empty($Dukes))
                            <li>
                                <label for="Neurologico"><b>Dukes:</b> {{$Dukes}}</label>
                            </li>
                            @endif
                            </label>
                            </li>
                            @if (!empty($gleason))
                            <li>
                                <label for="Neurologico"><b>Gleason:</b> {{$gleason}}</label>
                            </li>
                            @endif
                            </label>
                            </li>
                            @if (!empty($Her2))
                            <li>
                                <label for="Neurologico"><b>Her2:</b> {{$Her2}}</label>
                            </li>
                            @endif
                            </label>
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    @endif
    @if ($Tipocita_id == 7 && $Tipocita_id != 1 && $Tipocita_id != 3 && $Tipocita_id != 4 && $Tipocita_id != 5 &&
    $Tipocita_id != 6)
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Estadificación del Tumor</b></h4>
            <pre
                style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                    {{$estadificacioninicial}}
                </pre>
            <br>
            <br>
            <table class="egt">
                <tr>
                    <td>
                        <ul>
                            <li>
                                <label for="Neurologico"><b>F. Estadificación:</b> {{$fechaestadificacion}}</label>
                            </li>

                                <li>
                                    <label for="Neurologico"><b>F. Reporte Laboratorio:</b> {{$flaboratoriopatologia}}
                            </label>
                            </li>
                            <li>
                                <label for="Neurologico"><b>Diferenciación Tumor:</b> {{$tumorsegunbiopsia}}</label>
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    @endif
    @if ($marcapaciente != Null && $Tipocita_id != 1 && $Tipocita_id != 3 && $Tipocita_id != 4 && $Tipocita_id != 5 &&
    $Tipocita_id != 7  && $Tipocita_id != 6)
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Resultados de Ayudas Diagnosticas</b>
            </h4>
            <pre
                style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                    {{$Resultayudadiagnostica}}
                </pre>
        </div>
    </div>
    @endif
    @if ($marcapaciente != Null && $Tipocita_id != 1 && $Tipocita_id != 3 && $Tipocita_id != 4 && $Tipocita_id != 5 &&
    $Tipocita_id != 7  && $Tipocita_id != 6)
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Marcas del Paciente</b>
            </h4>
            <pre
                style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                    {{$marcapaciente}}
                </pre>
        </div>
    </div>
    @endif @if ($Tipocita_id != 1 && $Tipocita_id != 3 && $Tipocita_id != 4 && $Tipocita_id != 5 && $Tipocita_id != 7  && $Tipocita_id != 6)
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Antecedentes Personales</b></h4>
            <ul>
                <li>{{$Antecedentes}}</li>
            </ul>
        </div>
    </div>

    {{--
        <div style="margin-left: -40px; margin-right: -40px;">
            <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
                <h4 style="text-align: center"><b style="background-color: #efefef">Antecedentes Familiares</b></h4>
                <ul>
                    <li>{{$Antecedentes_Parentesco}}</li>
    </ul>
    </div>
    </div> --}}
    @endif @if ($Sexo == 'F' && $Tipocita_id != 1 && $Tipocita_id != 3 && $Tipocita_id != 4 && $Tipocita_id
    != 5 && $Tipocita_id != 7  && $Tipocita_id != 6)
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Antecedentes Gineco Obstétricos</b></h4>
            <ul>
                <li>
                    @if(!empty($Fechaultimamenstruacion))
                    <label for="FUM"><b>FUM:</b>{{$Fechaultimamenstruacion}}</label>
                    <br> @else()
                    <label for="FUM"><b>FUM:</b>No registra</label>
                    <br> @endif
                </li>
                <li>
                    @if(!empty($Gestaciones))
                    <label for="Gestaciones"><b>Gestaciones:</b>{{$Gestaciones}}</label>
                    <br> @else()
                    <label for="Gestaciones"><b>Gestaciones:</b>No registra</label>
                    <br> @endif
                </li>
                <li>
                    @if(!empty($Partos))
                    <label for="Partos"><b>Partos:</b>{{$Partos}}</label>
                    <br> @else()
                    <label for="Partos"><b>Partos:</b>No registra</label>
                    <br> @endif
                </li>
                <li>
                    @if(!empty($Abortoprovocado))
                    <label for="Abortoprovocado"><b>Abortoprovocado:</b>{{$Abortoprovocado}}</label>
                    <br> @else()
                    <label for="Abortoprovocado"><b>Abortoprovocado:</b>No registra</label>
                    <br> @endif
                </li>
                <li>
                    @if(!empty($Abortoespontaneo))
                    <label for="Abortoespontaneo"><b>Abortoespontaneo:</b>{{$Abortoespontaneo}}</label>
                    <br> @else()
                    <label for="Abortoespontaneo"><b>Abortoespontaneo:</b>No registra</label>
                    <br> @endif
                </li>
                <li>
                    @if(!empty($Mortinato))
                    <label for="Mortinato"><b>Mortinato:</b>{{$Mortinato}}</label>
                    <br> @else()
                    <label for="Mortinato"><b>Mortinato:</b>No registra</label>
                    <br> @endif
                </li>
                <li>
                    @if(!empty($Gestantefechaparto))
                    <label for="Gestantefechaparto"><b>Gestantefechaparto:</b>{{$Gestantefechaparto}}</label>
                    <br> @else()
                    <label for="Gestantefechaparto"><b>Gestantefechaparto: </b>No registra</label>
                    <br> @endif
                </li>
                <li>
                    @if(!empty($Gestantenumeroctrlprenatal))
                    <label for="Gestantenumeroctrlprenatal"><b>Gestantenumeroctrlprenatal:
                        </b>{{$Gestantenumeroctrlprenatal}}</label>
                    <br> @else()
                    <label for="Gestantenumeroctrlprenatal"><b>Gestantenumeroctrlprenatal: </b>No registra</label>
                    <br> @endif
                </li>
                <li>
                    @if(!empty($Eutoxico))
                    <label for="Eutoxico"><b>Eutoxico:</b>{{$Eutoxico}}</label>
                    @else()
                    <label for="Eutoxico"><b>Eutoxico:</b>No registra</label>
                    @endif
                </li>
                <li>
                    @if(!empty($Cesaria))
                    <label for="Cesaria"><b>Cesaria:</b>{{$Cesaria}}</label>
                    @else()
                    <label for="Cesaria"><b>Cesaria:</b>No registra</label>
                    @endif
                </li>
                <li>
                    @if(!empty($Cancercuellouterino))
                    <label for="Cancercuellouterino"><b>Cancercuellouterino:</b>{{$Cancercuellouterino}}</label>
                    @else()
                    <label for="Cancercuellouterino"><b>Cancercuellouterino:</b>No registra</label>
                    @endif
                </li>
                <li>
                    @if(!empty($Ultimacitologia))
                    <label for="Ultimacitologia"><b>Ultimacitologia:</b>{{$Ultimacitologia}}</label>
                    @else()
                    <label for="Ultimacitologia"><b>Ultimacitologia:</b>No registra</label>
                    @endif
                </li>
                <li>
                    @if(!empty($Resultado))
                    <label for="Resultado"><b>Resultado:</b>{{$Resultado}}</label>
                    @else()
                    <label for="Resultado"><b>Resultado:</b>No registra</label>
                    @endif
                </li>
                <li>
                    @if(!empty($Menarquia))
                    <label for="Menarquia"><b>Menarquia:</b>{{$Menarquia}}</label>
                    @else()
                    <label for="Menarquia"><b>Menarquia:</b>No registra</label>
                    @endif
                </li>
                <li>
                    @if(!empty($Ciclos))
                    <label for="Ciclos"><b>Ciclos:</b>{{$Ciclos}}</label>
                    @else()
                    <label for="Ciclos"><b>Ciclos:</b>No registra</label>
                    @endif
                </li>
                <li>
                    @if(!empty($Regulares))
                    <label for="Regulares"><b>Regulares:</b>{{$Regulares}}</label>
                    @else()
                    <label for="Regulares"><b>Regulares:</b>No registra</label>
                    @endif
                </li>
                <li>
                    @if(!empty($Observaciongineco))
                    <label for="Observaciongineco"><b>Observaciongineco:</b>{{$Observaciongineco}}</label>
                    @else()
                    <label for="Observaciongineco"><b>Observaciongineco:</b>No registra</label>
                    @endif
                </li>
            </ul>
        </div>
    </div>

    @endif
    @if ($Tipocita_id != 1 && $Tipocita_id != 3 && $Tipocita_id != 4 && $Tipocita_id != 5 && $Tipocita_id != 7  && $Tipocita_id != 6)
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Revisión por Sistemas</b></h4>
            <ul>
                <li>
                    @if(!empty($Oftalmologico))
                    <label for="Oftalmologico"><b>Oftalmologico:</b>{{$Oftalmologico}}</label>
                    <br> @else(!empty(NULL))
                    <label for="Oftalmologico"><b>Oftalmologico:</b>Normal</label>
                    <br> @endif
                </li>
                <li>
                    @if(!empty($Genitourinario))
                    <label for="Genitourinario"><b>Genitourinario:</b>{{$Genitourinario}}</label>
                    <br> @else(!empty(NULL))
                    <label for="Genitourinario"><b>Genitourinario:</b>Normal</label>
                    <br> @endif
                </li>
                <li>
                    @if(!empty($Otorrinoralingologico))
                    <label for="OtorrinoLalingologico"><b>Otorrinoralingologico:</b>{{$Otorrinoralingologico}}</label>
                    <br> @else(!empty(NULL))
                    <label for="OtorrinoLalingologico"><b>Otorrinoralingologico:</b>Normal</label>
                    <br> @endif
                </li>
                <li>
                    @if(!empty($Linfatico))
                    <label for="Linfatico"><b>Linfatico:</b>{{$Linfatico}}</label>
                    <br> @else(!empty(NULL))
                    <label for="Linfatico"><b>Linfatico:</b>Normal</label>
                    <br> @endif
                </li>
                <li>
                    @if(!empty($Osteomioarticular))
                    <label for="Osteomioarticular"><b>Osteomioarticular:</b>{{$Osteomioarticular}}</label>
                    <br> @else(!empty(NULL))
                    <label for="Osteomioarticular"><b>Osteomioarticular:</b>Normal</label>
                    <br> @endif
                </li>
                <li>
                    @if(!empty($Neurologico))
                    <label for="Neurologico"><b>Neurologico:</b>{{$Neurologico}}</label>
                    <br> @else(!empty(NULL))
                    <label for="Neurologico"><b>Neurologico:</b>Normal</label>
                    <br> @endif
                </li>
                <li>
                    @if(!empty($Cardiovascular))
                    <label for="Cardiovascular"><b>Cardiovascular:</b>{{$Cardiovascular}}</label>
                    <br> @else(!empty(NULL))
                    <label for="Cardiovascular"><b>Cardiovascular: Normal</label><br>
                    @endif
                </li>
                <li>
                    @if(!empty($Tegumentario))
                    <label for="Tegumentario"><b>Tegumentario: </b>{{$Tegumentario}}</label>
                    <br> @else(!empty(NULL))
                    <label for="Tegumentario"><b>Tegumentario: </b>Normal</label>
                    <br> @endif
                </li>
                <li>
                    @if(!empty($Respiratorio))
                    <label for="Respiratorio"><b>Respiratorio:</b>{{$Respiratorio}}</label>
                    @else(!empty(NULL))
                    <label for="Respiratorio"><b>Respiratorio:</b>Normal</label>
                    @endif
                </li>
                <li>
                    @if(!empty($Endocrinologico))
                    <label for="Endocrinologico"><b>Endocrinologico:</b>{{$Endocrinologico}}</label>
                    <br> @endif
                </li>
                <li>
                    @if(!empty($Gastrointestinal))
                    <label for="Gastrointestinal"><b>Gastrointestinal:</b>{{$Gastrointestinal}}</label>
                    @else(!empty(NULL))
                    <label for="Gastrointestinal"><b>Gastrointestinal:</b>Normal</label>
                    @endif
                </li>
                <li>
                    @if(!empty($Norefiere))
                    <label for="Otro"><b>Otro:</b>{{$Norefiere}}</label>
                    @else(!empty(Null))
                    <label for="Otro"><b>Otro:</b>No Refiere</label>
                    @endif
                </li>
            </ul>
        </div>
    </div>
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Estilo de Vida</b></h4>
            <ul>
                <li>
                    @if(!empty($Dietasaludable))
                    <label for="Dieta Saludable"><b>¿Tiene dieta saludable?:</b> {{$Dietasaludable}}</label>
                    <br> @ELSE(!empty(NULL))
                    <label for="Dieta Saludable"><b>¿Tiene dieta saludable?:</b> No Refiere</label>
                    <br> @endif
                </li>
                <li>
                    @if(!empty($Suenoreparador))
                    <label for="Sueño Reparador"><b>¿Tiene sueño reparador?:</b> {{$Suenoreparador}}</label>
                    <br> @else(!empty(NULL))
                    <label for="Sueño Reparador"><b>¿Tiene sueño reparador?:</b>No Refiere</label>
                    <br> @endif
                </li>
                <li>
                    @if(!empty($Duermemenoseishoras))
                    <label for="Duerme Menos Seis Horas"><b>¿Duerme menos de seis horas?:</b> {{$Duermemenoseishoras}}
                    </label>
                    <br> @else(!empty(NULL))
                    <label for="Duerme Menos Seis Horas"><b>¿Duerme menos de seis horas?:</b>No Refiere</label>
                    <br> @endif
                </li>
                <li>
                    @if(!empty($Altonivelestres))
                    <label for="Alto Nivel Estres"><b>¿Alto nivel estres?:</b> {{$Altonivelestres}}</label>
                    <br> @else(!empty(NULL))
                    <label for="Alto Nivel Estres"><b>¿Alto nivel estres?:</b>No Refiere</label>
                    <br> @endif
                </li>
                <li>
                    @if(!empty($Actividadfisica))
                    <label for="Actividad Fisica"><b>Actividad fisica:</b> {{$Actividadfisica}}<span>
                        </span>
                        <label for="Frecuencia Semanal"><b>Frecuencia semanal:</b> {{$Frecuenciasemana}}</label>
                        <span><label for="Duración"><b>Duracion:</b> {{$Duracion}}</label></span></label>
                    <br> @else(!empty(NULL))
                    <label for="Actividad Fisica"><b>Actividad fisica:</b> No Refiere<span>
                            @endif
                </li>
                <li>
                    @if(!empty($Fumacantidad))
                    <label for="Fuma Cantidad"><b>Cantidad que fuma:</b> {{$Fumacantidad}}</label> <br>
                    @else(!empty(NULL))
                    <label for="Fuma Cantidad"><b>Cantidad que fuma:</b> No Refiere</label> <br>
                    @endif
                </li>
                <li>
                    @if(!empty($Fumainicio))
                    <label for="Fuma Inicio"><b>Inicio a fumar:</b> {{$Fumainicio}}</label> <br>
                    @else(!empty(NULL))
                    <label for="Fuma Inicio"><b>Inicio a fumar:</b> No Refiere</label> <br>
                    @endif
                </li>
                <li>
                    @if(!empty($Fumadorpasivo))
                    <label for="Fumador Pasivo"><b>Es fumador pasivo:</b> {{$Fumadorpasivo}}</label> <br>
                    @else(!empty(NULL))
                    <label for="Fumador Pasivo"><b>Es fumador pasivo:</b>No Refiere</label> <br>
                    @endif
                </li>
                <li>
                    @if(!empty($Cantidadlicor))
                    <label for="Cantidad licor"><b>Cantidad de licor:</b> {{$Cantidadlicor}}</label> <br>
                    @else(!empty(NULL))
                    <label for="Cantidad licor"><b>Cantidad de licor:</b>No Refiere</label> <br>
                    @endif
                </li>
                <li>
                    @if(!empty($Licorfrecuencia))
                    <label for="Frecuencia Licor"><b>Frecuencia del licor:</b> {{$Licorfrecuencia}}</label> <br>
                    @else(!empty(NULL))
                    <label for="Frecuencia Licor"><b>Frecuencia del licor:</b>No Refiere</label> <br>
                    @endif
                </li>
                <li>
                    @if(!empty($Consumopsicoactivo))
                    <label for="Consumo Psicoactivo"><b>Consumo de sustancias psicoactivo:</b>
                        {{$Consumopsicoactivo}}</label> <br>
                    @else(!empty(NULL))
                    <label for="Consumo Psicoactivo"><b>Consumo de sustancias psicoactivo:</b>No Refiere</label> <br>
                    @endif
                </li>
                <li>
                    @if(!empty($Psicoactivocantidad))
                    <label for="Psicoactivo Cantidad"><b>Cantidad de sustancias Psicoactivas:</b>
                        {{$Psicoactivocantidad}}</label> <br>
                    @endif
                </li>
                <li>
                    @if(!empty($Estilovidaobservaciones))
                    <label for="Estilovida Observaciones"><b>Estilo vida observaciones:</b>
                        {{$Estilovidaobservaciones}}</label> <br>
                    @else(!empty(NULL))
                    <label for="Estilovida Observaciones"><b>Estilo vida observaciones:</b> No Refiere</label> <br>
                    @endif
                </li>
            </ul>
        </div>
    </div>
    @endif
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Medidas Antropometricas</b></h4>
            <ul>
                <li>{{$Antropometricas}}</li>
            </ul>
        </div>
    </div>
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Signos Vitales</b></h4>
            <ul>
                <li>{{$Signos_Vitales}}</li>
            </ul>
        </div>
    </div>
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Otros signos vitales</b></h4>
            <ul>
                <li>{{$Otros_Signos_Vitales}}</li>
            </ul>
        </div>
    </div>
    @if ($Tipocita_id != 1 && $Tipocita_id != 3 && $Tipocita_id != 4 && $Tipocita_id != 5 && $Tipocita_id != 7  && $Tipocita_id != 6)
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Examen Físico</b></h4>
            <ul>
                <li>
                    @if(!empty($Cabezacuello))
                    <label for="Dieta Saludable"><b>Cabeza cuello:</b>{{$Cabezacuello}}</label> <br>
                    @else
                    <label for="Dieta Saludable"><b>Cabeza cuello:</b> Normal</label> <br>
                    @endif
                </li>
                <li>
                    @if(!empty($suenoreparador))
                    <label for="Sueño Reparador"><b>Ojos y Fondo de Ojos:</b>{{$suenoreparador}}</label> <br>
                    @else
                    <label for="Sueño Reparador"><b>Ojos y Fondo de Ojos:</b>Normal</label> <br>
                    @endif
                </li>
                <li>
                    @if(!empty($Cardiopulmonar))
                    <label for="Duerme Menos Seis Horas"><b>Cardiopulmonar:</b>{{$Cardiopulmonar}}</label> <br>
                    @else
                    <label for="Duerme Menos Seis Horas"><b>Cardiopulmonar:</b>Normal</label> <br>
                    @endif
                </li>
                <li>
                    @if(!empty($Abdomen))
                    <label for="Alto Nivel Estres"><b>Abdomen:</b>{{$Abdomen}}</label> <br>
                    @else
                    <label for="Alto Nivel Estres"><b>Abdomen:</b>Normal</label> <br>
                    @endif
                </li>
                <li>
                    @if(!empty($Extremidades))
                    <label for="Fuma Cantidad"><b>Extremidades:</b>{{$Extremidades}}</label> <br>
                    @else
                    <label for="Fuma Cantidad"><b>Extremidades:</b> Normal</label> <br>
                    @endif
                </li>
                <li>
                    @if(!empty($Pulsosperifericos))
                    <label for="Fuma Inicio"><b>Pulsos Perifericos:</b>{{$Pulsosperifericos}}</label> <br>
                    @else
                    <label for="Fuma Inicio"><b>Pulsos Perifericos:</b> Normal</label> <br>
                    @endif
                </li>
                <li>
                    @if(!empty($Neurologico))
                    <label for="Fumador Pasivo"><b>Neurologicos:</b>{{$Neurologico}}</label> <br>
                    @else
                    <label for="Fumador Pasivo"><b>Neurologicos:</b>Normal</label> <br>
                    @endif
                </li>
                <li>
                    @if(!empty($Reflejososteotendinos))
                    <label for="Cantidad licor"><b>Reflejos Osteotendinosos:</b>{{$Reflejososteotendinos}}</label> <br>
                    @else
                    <label for="Cantidad licor"><b>Reflejos Osteotendinosos:</b>Normal</label> <br>
                    @endif
                </li>
                <li>
                    @if(!empty($Pielfraneras))
                    <label for="Frecuencia Licor"><b>Piel y Faneras:</b>{{$Pielfraneras}}</label> <br>
                    @else
                    <label for="Frecuencia Licor"><b>Piel y Faneras:</b>Normal</label> <br>
                    @endif
                </li>
                <li>
                    @if(!empty($Genitourinario))
                    <label for="Consumo Psicoactivo"><b>Genitourinario:</b>{{$Genitourinario}}</label> <br>
                    @else
                    <label for="Consumo Psicoactivo"><b>Genitourinario:</b>Normal</label> <br>
                    @endif
                </li>
                <li>
                    @if(!empty($Examenmama))
                    <label for="Estilovida Observaciones"><b>Examen de Mama:</b>{{$Examenmama}}</label> <br>
                    @endif
                </li>
                <li>
                    @if(!empty($Tactoretal))
                    <label for="Estilovida Observaciones"><b>Tacto Rectal:</b>{{$Tactoretal}}</label> <br>
                    @endif
                </li>
                <li>
                    @if(!empty($Examenmental))
                    <label for="Consumo Psicoactivo"><b>Examen Mental:</b>{{$Examenmental}}</label> <br>
                    @else
                    <label for="Consumo Psicoactivo"><b>Examen Mental:</b>Normal</label> <br>
                    @endif
                </li>
            </ul>
        </div>
    </div>
    @endif
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Presión Arterial</b></h4>
            <ul>
                <li>{{$Presión_Arterial}}</li>
            </ul>
        </div>
    </div>
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Estado general del paciente</b></h4>
            <pre
                style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                {{$Condiciongeneral}}
            </pre>
        </div>
    </div>
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Análisis y plan</b></h4>
            <pre
                style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                {{$Planmanejo}}
                </pre>
        </div>
    </div>
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Diagnósticos del paciente</b></h4>
            <ul>
                <li><b>Diagnóstico principal: </b> <span>{{$Diagnostico_Principal}}</span></li>
                <li><b>Otros diagnósticos: </b>
                    <pre>{{$Diagnostico_Secundario}}</pre>
                </li>
            </ul>
        </div>
    </div>
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Recomendaciones</b></h4>
            <pre
                style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                    {{$Recomendaciones}}
                </pre>
        </div>
    </div>

    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Causa externa y finalidad</b></h4>
            <p
                style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                <p style="font-weight:bold"><b>Causa externa: </b> <span>{{$Destinopaciente}}</span></p>
                <p style="font-weight:bold"><b>Finalidad: </b> <span>{{$Finalidad}}</span></p>
            </p>
        </div>
    </div>
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: LEFT; margin-left: 8px;"><b style="background-color: #efefef">INFORMACIÓN DEL PROFESIONAL</b></h4>
            <pre
                style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                @if ($Tipocita_id == 6)
                <p>
                    @if(!empty($Firmaradiologo))
                    <img src="../{{$Firmaradiologo}}" alt="" style="width: 208px; height:58px; text-align: LEFT">@endif
                </p>
                <p><b>Medico:</b> {{$Radiologo}}</p>
                <p><b>Registromedico:</b> {{$Regradiologo}}</p>
                @endif
                <p><b>Medico:</b> {{$Atendido_Por}}</p>
                <p><b>Especialidad:</b> {{$Especialidad}}</p>
                <p><b>Registromedico:</b> {{$Registromedico}}</p>
                </pre>
        </div>
    </div>
    <div style="margin-left: -40px; margin-right: -40px;">
        @if ($Tipocita_id != 6)
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
                vertical-align: top; text-align: center;">
            @if(!empty($Firma))
            <img src="../{{$Firma}}" alt="" style="width: 208px; height:58px">@endif
            <br>
            <label for="firma_digital">Firma digital: </label>
        </div>
        @endif
    </div>
</body>

</html>
