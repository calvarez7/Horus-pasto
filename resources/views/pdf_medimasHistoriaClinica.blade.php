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
@foreach($historias as $historia)
    <body>
    @if ($historia['Tipocita_id'] == 7)
        <div id="headerOncologia">
            <div class="card" style="margin-top: 30px; margin-left: 90px">
                <div class="container" style="">
                    <label for="IPS">Punto de Atención: {{$historia['IPS']}}</label>
                    <br>
                    <label for="Consulta">Consulta: {{$historia['Especialidad']}}</label>
                    <br>
                    <label for="Consulta">Tipo: {{$historia['Tipocita']}}</label>
                    <br>
                    <label for="Fecha">Fecha Solicitud: {{$historia['Datetimeingreso']}}</label>
                </div>
            </div>
        </div>
    @elseif($historia['Tipocita_id'] == 6)
        <div id="headerImagenologia">

        </div>
    @else
        <div id="header">
            <div class="card" style="margin-top: 30px; margin-left: 90px">
                <div class="container" style="">
                    <label for="IPS">Punto de Atención: {{$historia['IPS']}}</label>
                    <br> @if ($historia['Tipocita_id'] != 1 && $historia['Tipocita_id'] != 3 && $historia['Tipocita_id'] != 4 && $historia['Tipocita_id'] != 5 &&
                $historia['Tipocita_id'] != 7)
                        <label for="Consulta">Consulta: {{$historia['Especialidad']}}</label>
                        <br> @endif
                    <label for="Consulta">Tipo: {{$historia['Tipocita']}}</label>
                    <br>
                    <label for="Fecha">Fecha Solicitud: {{$historia['Datetimeingreso']}}</label>
                </div>
            </div>
        </div>
    @endif @if ($historia['Tipocita_id'] == 7)
        <div id="footerOncologia">
        </div>
    @else
    @endif @if ($historia['Tipocita_id'] == 6)
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
                <td class="tg-zp25" colspan="10">{{$historia['Nombre']}}</td>
                <td class="tg-j7to" colspan="3"><span style="font-weight:700">Identificación</span></td>
                <td class="tg-9zda" colspan="7">{{$historia['Cédula']}}</td>
                <td class="tg-j7to" colspan="4"><span style="font-weight:bold">Fecha nacimiento</span></td>
                <td class="tg-9zda" colspan="5">{{$historia['Fecha_Nacimiento']}}</td>
            </tr>
            <tr>
                <td class="tg-t2c5" colspan="4"><span style="font-weight:bold">Edad</span></td>
                                <td class="tg-qgl5" colspan="3">{{$historia['Edad']}}</td>
                <td class="tg-t2c5" colspan="3"><span style="font-weight:bold">Genero</span></td>
                <td class="tg-qgl5" colspan="4">{{$historia['Sexo']}}</td>
                <td class="tg-t2c5" colspan="4"><span style="font-weight:700">Tipo afiliación</span></td>
                <td class="tg-acmg" colspan="7">{{$historia['Tipo_Afiliado']}}</td>
                <td class="tg-t2c5" colspan="4"><span style="font-weight:bold">Departamento</span></td>
                <td class="tg-qgl5" colspan="5">{{$historia['Departamento']}}</td>
            </tr>
            <tr>
                <td class="tg-t2c5" colspan="4"><span style="font-weight:bold">Teléfono</span></td>
                <td class="tg-qgl5" colspan="5">{{$historia['Telefono']}}</td>
                <td class="tg-t2c5" colspan="5"><span style="font-weight:bold">Municipio</span></td>
                <td class="tg-qgl5" colspan="10">{{$historia['Municipio_Afiliado']}}</td>
                <td class="tg-t2c5" colspan="5"><span style="font-weight:700">Estado civil</span></td>
                <td class="tg-qgl5" colspan="5"></td>
            </tr>
            @if(!empty($historia['Ocupacion']))
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
    @if ($historia['Tipocita_id'] == 1)
        <div style="margin-top: 150px; margin-left: -40px; margin-right: -40px;">
            <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
                <h4 style="text-align: center"><b style="background-color: #efefef">Observaciones de Transcrición</b></h4>
                <pre
                    style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                    @if(!empty($historia['Observaciones'])){{$historia['Observaciones']}}@endif
                </pre>
            </div>
        </div>
        <div style="margin-left: -40px; margin-right: -40px;">
            <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
                <h4 style="text-align: center"><b style="background-color: #efefef">Información del profesional</b></h4>
                <pre
                    style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                <p><b>Medico que Ordeno:</b> @if(!empty($historia['Medicoordeno'])){{$historia['Medicoordeno']}}@endif</p>
                <p><b>Diagnóstico Principal:</b> @if(!empty($historia['Diagnostico_Principal'])){{$historia['Diagnostico_Principal']}}@endif</p>
                <p><b>Finalidad:</b> @if(!empty($historia['finalidadTrans'])){{$historia['finalidadTrans']}}@endif</p>
                <p><b>Entidad que emite:</b> @if(!empty($historia['Entidademite'])){{$historia['Entidademite']}}@endif</p>
                </pre>
            </div>
        </div>
    @endif
    <!-- inicio -->
    @if ($historia['Tipocita_id'] == 6)
        <table class="tg" style="margin-top: 150px; margin-left: -40px; margin-right: -40px; width:100%;">
            <tr>
                <td class="tg-7ghi" ><span style="font-weight:bold">ESTUDIO</span>
                </td>
            </tr>
            <tr>
                <td class="tg-zp25">@if(!empty($historia['Cups'])){{$historia['Cups']}}@endif</td>
            </tr>
        </table>
        <div style="margin-left: -40px; margin-right: -40px;">
            <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
                <h4 style="text-align: LEFT; margin-left: 8px;"><b style="background-color: #efefef">INDICACION</b></h4>
                <P
                    style="margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif; text-justify: inter-word; text-align: justify !important">
                    @if(!empty($historia['Indicacion'])){{$historia['Indicacion']}}@endif
                </P>
            </div>
        </div>

        <div style="margin-left: -40px; margin-right: -40px;">
            <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
                <h4 style="text-align: LEFT; margin-left: 8px;"><b style="background-color: #efefef">HALLAZGOS</b></h4>
                <p
                    style="margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif; text-justify: inter-word; text-align: justify !important">
                    @if(!empty($historia['Hallazgos'])){{$historia['Hallazgos']}}@endif
                </p>
            </div>
        </div>
        <div style="margin-left: -40px; margin-right: -40px;">
            <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
                <h4 style="text-align: LEFT; margin-left: 8px;"><b style="background-color: #efefef">CONCLUSION</b></h4>
                <p
                    style="margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif; text-justify: inter-word; text-align: justify !important">
                    @if(!empty($historia['Conclusion'])){{$historia['Conclusion']}}@endif
                </p>
            </div>
        </div>
    @endif
    <!-- inicio -->
    @if ($historia['Tipocita_id'] == 6)
        <div style="margin-left: -40px; margin-right: -40px;">
            <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
                <h4 style="text-align: LEFT; margin-left: 8px;"><b style="background-color: #efefef">NOTACLARATORIA</b></h4>
                <p
                    style="margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif; text-justify: inter-word; text-align: justify !important">
                    @if(!empty($historia['Notaclaratoria'])){{$historia['Notaclaratoria']}}@endif
                </p>
            </div>
        </div>
    @endif
    <!-- inicio -->
    @if ($historia['Tipocita_id'] != 1 && $historia['Tipocita_id'] != 3 && $historia['Tipocita_id'] != 4 && $historia['Tipocita_id'] != 5 && $historia['Tipocita_id'] != 6)
        <div style="margin-top: 150px; margin-left: -40px; margin-right: -40px;">
            <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
                <h4 style="text-align: center"><b style="background-color: #efefef">Motivo de Consulta</b></h4>
                <pre
                    style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif; text-transform: capitalize !important">
                     @if(!empty($historia['Motivoconsulta'])){{$historia['Motivoconsulta']}}@endif
                </pre>
            </div>
        </div>
        <div style="margin-left: -40px; margin-right: -40px;">
            <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
                <h4 style="text-align: center"><b style="background-color: #efefef">Enfermedad Actual</b></h4>
                <pre
                    style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                     @if(!empty($historia['Enfermedadactual'])){{$historia['Enfermedadactual']}}@endif
                </pre>
            </div>
        </div>
    @endif @if ($historia['Tipocita_id'] == 7 && $historia['Tipocita_id'] != 1 && $historia['Tipocita_id'] != 3 && $historia['Tipocita_id'] != 4 && $historia['Tipocita_id'] != 5 &&
    $historia['Tipocita_id'] != 6)
        <div style="margin-left: -40px; margin-right: -40px;">
            <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
                <h4 style="text-align: center"><b style="background-color: #efefef">Tratamiento Oncologico</b></h4>
                <pre
                    style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                    @if(!empty($historia['tratamientoncologico'])){{$historia['tratamientoncologico']}}@endif
                </pre>
            </div>
        </div>
    @endif @if ($historia['Tipocita_id'] == 7 && $historia['Tipocita_id'] != 1 && $historia['Tipocita_id'] != 3 && $historia['Tipocita_id'] != 4 && $historia['Tipocita_id'] != 5 &&
    $historia['Tipocita_id'] != 6)
        <div style="margin-left: -40px; margin-right: -40px;">
            <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
                <h4 style="text-align: center"><b style="background-color: #efefef">Cirugia Oncologica</b></h4>
                <table class="egt">
                    <tr>
                        <td>
                            @if(!empty($historia['$recibioradioterapia']))
                            <ul>
                                @if ($historia['$recibioradioterapia'] == 0 or $historia['$recibioradioterapia'] == 1)
                                    <li>
                                        <label for="Neurologico"><b>Recibio Radioterapia:</b></label>
                                    </li>
                                    <li>
                                        <label for="Neurologico"><b>Numero Cirugias:</b> @if(!empty($historia['inicioradioterapia'])){{$historia['inicioradioterapia']}}@endif</label>
                                    </li>
                                    <li>
                                        <label for="Neurologico"><b>Fecha Inicio:</b> @if(!empty($historia['finradioterapia'])){{$historia['finradioterapia']}}@endif</label>
                                    </li>
                                    <li>
                                        <label for="Neurologico"><b>Fecha Final:</b> @if(!empty($historia['nsesiones'])){{$historia['nsesiones']}}@endif</label>
                                    </li>
                                @endif
                            </ul>
                            @endif
                        </td>

                        <td>
                            @if(!empty($historia['cirujiaoncologica']))
                            <ul>
                                @if ($historia['cirujiaoncologica'] == 0 or $historia['cirujiaoncologica'] == 1)
                                    <li>
                                        <label for="Neurologico"><b>Tiene Cirugias:</b> </label>
                                    </li>
                                    <li>
                                        <label for="Neurologico"><b>Numero Cirugias:</b> @if(!empty($historia['ncirujias'])){{$historia['ncirujias']}}@endif</label>
                                    </li>
                                    <li>
                                        <label for="Neurologico"><b>Fecha Inicio:</b> @if(!empty($historia['iniciocirujia'])){{$historia['iniciocirujia']}}@endif</label>
                                    </li>
                                    <li>
                                        <label for="Neurologico"><b>Fecha Final:</b> @if(!empty($historia['fincirujia'])){{$historia['fincirujia']}}@endif</label>
                                    </li>
                                    @endif
                                    </ul>
                            @endif
                        </td>
                        <td>
                            <ul>
                                <label for="Neurologico"><b>Intencion del Tratamiento Oncologico:</b> @if(!empty($historia['intencion'])){{$historia['intencion']}}@endif
                                </label>
                                <br>
                            </ul>
                        </td>

                    </tr>

                </table>
            </div>
        </div>
    @endif @if ($historia['Tipocita_id'] == 7 && $historia['Tipocita_id'] != 1 && $historia['Tipocita_id'] != 3 && $historia['Tipocita_id'] != 4 && $historia['Tipocita_id'] != 5 &&
    $historia['Tipocita_id'] != 6)
        <div style="margin-left: -40px; margin-right: -40px;">
            <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
                <h4 style="text-align: center"><b style="background-color: #efefef">Descripcióm Patología Actual</b></h4>
                <pre
                    style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                    @if(!empty($historia['Patologiacancelactual'])){{$historia['Patologiacancelactual']}}@endif
                </pre>
                <br>
                <br>
                <table class="egt">
                    <tr>
                        <td>
                            <ul>
                                <li>
                                    <label for="Neurologico"><b>fecha de ingreso al laboratorio de patología:</b>
                                        @if(!empty($historia['fdxcanceractual'])){{$historia['fdxcanceractual']}}@endif
                                    </label>
                                </li>
                                <li>
                                    <label for="Neurologico"><b>fecha de reporte del laboratorio de
                                            patología:</b>@if(!empty($historia['flaboratoriopatologia'])){{$historia['flaboratoriopatologia']}}@endif</label>
                                </li>
                                </li>
                            </ul>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endif @if ($historia['Tipocita_id'] == 7 && !empty($historia['localizacioncancer']) && $historia['Tipocita_id'] != 1 && $historia['Tipocita_id'] != 3 &&
    $historia['Tipocita_id'] != 4 && $historia['Tipocita_id'] != 5 && $historia['Tipocita_id'] != 6)
        <div style="margin-left: -40px; margin-right: -40px;">
            <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
                <h4 style="text-align: center"><b style="background-color: #efefef">Tipo de Tumor</b></h4>
                <table class="egt">
                    <tr>
                        <td>
                            <ul>
                                <li>
                                    <label for="Neurologico"><b>Localización Cancer:</b>@if(!empty($historia['localizacioncancer'])) {{$historia['localizacioncancer']}}@endif</label>
                                </li>
                                </label>
                                </li>
                                @if (!empty($historia['Dukes']))
                                    <li>
                                        <label for="Neurologico"><b>Dukes:</b> {{$historia['Dukes']}}</label>
                                    </li>
                                    @endif
                                    </label>
                                    </li>
                                    @if (!empty($historia['gleason']))
                                        <li>
                                            <label for="Neurologico"><b>Gleason:</b> {{$historia['gleason']}}</label>
                                        </li>
                                        @endif
                                        </label>
                                        </li>
                                        @if (!empty($historia['Her2']))
                                            <li>
                                                <label for="Neurologico"><b>Her2:</b> {{$historia['Her2']}}</label>
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
    @if ($historia['Tipocita_id'] == 7 && $historia['Tipocita_id'] != 1 && $historia['Tipocita_id'] != 3 && $historia['Tipocita_id'] != 4 && $historia['Tipocita_id'] != 5 &&
    $historia['Tipocita_id'] != 6)
        <div style="margin-left: -40px; margin-right: -40px;">
            <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
                <h4 style="text-align: center"><b style="background-color: #efefef">Estadificación del Tumor</b></h4>
                <pre
                    style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                    @if(!empty($historia['estadificacioninicial']))
                    {{$historia['estadificacioninicial']}}@endif
                </pre>
                <br>
                <br>
                <table class="egt">
                    <tr>
                        <td>
                            <ul>
                                <li>
                                    <label for="Neurologico"><b>F. Estadificación:</b>@if(!empty($historia['fechaestadificacion'])) {{$historia['fechaestadificacion']}}@endif</label>
                                </li>
                                <li>
                                    <label for="Neurologico"><b>F. Reporte Laboratorio:</b>@if(!empty($historia['flaboratoriopatologia'])) {{$historia['flaboratoriopatologia']}}@endif
                                    </label>
                                </li>
                                <li>
                                    <label for="Neurologico"><b>Diferenciación Tumor:</b>@if(!empty($historia['tumorsegunbiopsia'])) {{$historia['tumorsegunbiopsia']}}@endif</label>
                                </li>
                            </ul>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    @endif
    @if ($historia['marcapaciente'] != Null && $historia['Tipocita_id'] != 1 && $historia['Tipocita_id'] != 3 && $historia['Tipocita_id'] != 4 && $historia['Tipocita_id'] != 5 &&
    $historia['Tipocita_id'] != 7  && $historia['Tipocita_id'] != 6)
        <div style="margin-left: -40px; margin-right: -40px;">
            <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
                <h4 style="text-align: center"><b style="background-color: #efefef">Resultados de Ayudas Diagnosticas</b>
                </h4>
                <pre
                    style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                    @if(!empty($historia['Resultayudadiagnostica']))
                    {{$historia['Resultayudadiagnostica']}}
                    @endif
                </pre>
            </div>
        </div>
    @endif
    @if ($historia['marcapaciente'] != Null && $historia['Tipocita_id'] != 1 && $historia['Tipocita_id'] != 3 && $historia['Tipocita_id'] != 4 && $historia['Tipocita_id'] != 5 &&
    $historia['Tipocita_id'] != 7  && $historia['Tipocita_id'] != 6)
        <div style="margin-left: -40px; margin-right: -40px;">
            <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
                <h4 style="text-align: center"><b style="background-color: #efefef">Marcas del Paciente</b>
                </h4>
                <pre
                    style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                    @if(!empty($historia['Antecedentes']))
                    {{$historia['Antecedentes']}}
                    @endif
                </pre>
            </div>
        </div>
    @endif @if ($historia['Tipocita_id'] != 1 && $historia['Tipocita_id'] != 3 && $historia['Tipocita_id'] != 4 && $historia['Tipocita_id'] != 5 && $historia['Tipocita_id'] != 7  && $historia['Tipocita_id'] != 6)
        <div style="margin-left: -40px; margin-right: -40px;">
            <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
                <h4 style="text-align: center"><b style="background-color: #efefef">Antecedentes Personales</b></h4>
                <ul>
                    @if(!empty($historia['Antecedentes']))
                        <li>{{$historia['Antecedentes']}}</li>
                    @endif
                </ul>
            </div>
        </div>
    @endif @if ($historia['Sexo']  == 'F' && $historia['Tipocita_id'] != 1 && $historia['Tipocita_id'] != 3 && $historia['Tipocita_id'] != 4 && $historia['Tipocita_id']
    != 5 && $historia['Tipocita_id'] != 7  && $historia['Tipocita_id'] != 6)
        <div style="margin-left: -40px; margin-right: -40px;">
            <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
                <h4 style="text-align: center"><b style="background-color: #efefef">Antecedentes Gineco Obstétricos</b></h4>
                <ul>
                    <li>
                        @if(!empty($historia['Fechaultimamenstruacion']))
                            <label for="FUM"><b>FUM:</b>{{$historia['Fechaultimamenstruacion']}}</label>
                            <br> @else()
                            <label for="FUM"><b>FUM:</b>No registra</label>
                            <br> @endif
                    </li>
                    <li>
                        @if(!empty($historia['Gestaciones']))
                            <label for="Gestaciones"><b>Gestaciones:</b>{{$historia['Gestaciones']}}</label>
                            <br> @else()
                            <label for="Gestaciones"><b>Gestaciones:</b>No registra</label>
                            <br> @endif
                    </li>
                    <li>
                        @if(!empty($historia['Partos']))
                            <label for="Partos"><b>Partos:</b>{{$historia['Partos']}}</label>
                            <br> @else()
                            <label for="Partos"><b>Partos:</b>No registra</label>
                            <br> @endif
                    </li>
                    <li>
                        @if(!empty($historia['Abortoprovocado']))
                            <label for="Abortoprovocado"><b>Abortoprovocado:</b>{{$historia['Abortoprovocado']}}</label>
                            <br> @else()
                            <label for="Abortoprovocado"><b>Abortoprovocado:</b>No registra</label>
                            <br> @endif
                    </li>
                    <li>
                        @if(!empty($historia['Abortoespontaneo']))
                            <label for="Abortoespontaneo"><b>Abortoespontaneo:</b>{{$historia['Abortoespontaneo']}}</label>
                            <br> @else()
                            <label for="Abortoespontaneo"><b>Abortoespontaneo:</b>No registra</label>
                            <br> @endif
                    </li>
                    <li>
                        @if(!empty($historia['Mortinato']))
                            <label for="Mortinato"><b>Mortinato:</b>{{$historia['Mortinato']}}</label>
                            <br> @else()
                            <label for="Mortinato"><b>Mortinato:</b>No registra</label>
                            <br> @endif
                    </li>
                    <li>
                        @if(!empty($historia['Gestantefechaparto']))
                            <label for="Gestantefechaparto"><b>Gestantefechaparto:</b>{{$historia['Gestantefechaparto']}}</label>
                            <br> @else()
                            <label for="Gestantefechaparto"><b>Gestantefechaparto: </b>No registra</label>
                            <br> @endif
                    </li>
                    <li>
                        @if(!empty($historia['Gestantenumeroctrlprenatal']))
                            <label for="Gestantenumeroctrlprenatal"><b>Gestantenumeroctrlprenatal:
                                </b>{{$historia['Gestantenumeroctrlprenatal']}}</label>
                            <br> @else()
                            <label for="Gestantenumeroctrlprenatal"><b>Gestantenumeroctrlprenatal: </b>No registra</label>
                            <br> @endif
                    </li>
                    <li>
                        @if(!empty($historia['Eutoxico']))
                            <label for="Eutoxico"><b>Eutoxico:</b>{{$historia['Eutoxico']}}</label>
                        @else()
                            <label for="Eutoxico"><b>Eutoxico:</b>No registra</label>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Cesaria']))
                            <label for="Cesaria"><b>Cesaria:</b>{{$historia['Cesaria']}}</label>
                        @else()
                            <label for="Cesaria"><b>Cesaria:</b>No registra</label>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Cancercuellouterino']))
                            <label for="Cancercuellouterino"><b>Cancercuellouterino:</b>{{$historia['Cancercuellouterino']}}</label>
                        @else()
                            <label for="Cancercuellouterino"><b>Cancercuellouterino:</b>No registra</label>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Ultimacitologia']))
                            <label for="Ultimacitologia"><b>Ultimacitologia:</b>{{$historia['Ultimacitologia']}}</label>
                        @else()
                            <label for="Ultimacitologia"><b>Ultimacitologia:</b>No registra</label>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Resultado']))
                            <label for="Resultado"><b>Resultado:</b>{{$historia['Resultado']}}</label>
                        @else()
                            <label for="Resultado"><b>Resultado:</b>No registra</label>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Menarquia']))
                            <label for="Menarquia"><b>Menarquia:</b>{{$historia['Menarquia']}}</label>
                        @else()
                            <label for="Menarquia"><b>Menarquia:</b>No registra</label>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Ciclos']))
                            <label for="Ciclos"><b>Ciclos:</b>{{$historia['Ciclos']}}</label>
                        @else()
                            <label for="Ciclos"><b>Ciclos:</b>No registra</label>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Regulares']))
                            <label for="Regulares"><b>Regulares:</b>{{$historia['Regulares']}}</label>
                        @else()
                            <label for="Regulares"><b>Regulares:</b>No registra</label>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Observaciongineco']))
                            <label for="Observaciongineco"><b>Observaciongineco:</b>{{$historia['Observaciongineco']}}</label>
                        @else()
                            <label for="Observaciongineco"><b>Observaciongineco:</b>No registra</label>
                        @endif
                    </li>
                </ul>
            </div>
        </div>

    @endif
    @if ($historia['Tipocita_id'] != 1 && $historia['Tipocita_id'] != 3 && $historia['Tipocita_id'] != 4 && $historia['Tipocita_id'] != 5 && $historia['Tipocita_id'] != 7  && $historia['Tipocita_id'] != 6)
        <div style="margin-left: -40px; margin-right: -40px;">
            <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
                <h4 style="text-align: center"><b style="background-color: #efefef">Revisión por Sistemas</b></h4>
                <ul>
                    <li>
                        @if(!empty($historia['Oftalmologico']))
                            <label for="Oftalmologico"><b>Oftalmologico:</b>{{$historia['Oftalmologico']}}</label>
                            <br> @else(!empty(NULL))
                            <label for="Oftalmologico"><b>Oftalmologico:</b>Normal</label>
                            <br> @endif
                    </li>
                    <li>
                        @if(!empty($historia['Genitourinario']))
                            <label for="Genitourinario"><b>Genitourinario:</b>{{$historia['Genitourinario']}}</label>
                            <br> @else(!empty(NULL))
                            <label for="Genitourinario"><b>Genitourinario:</b>Normal</label>
                            <br> @endif
                    </li>
                    <li>
                        @if(!empty($historia['Otorrinoralingologico']))
                            <label for="OtorrinoLalingologico"><b>Otorrinoralingologico:</b>{{$historia['Otorrinoralingologico']}}</label>
                            <br> @else(!empty(NULL))
                            <label for="OtorrinoLalingologico"><b>Otorrinoralingologico:</b>Normal</label>
                            <br> @endif
                    </li>
                    <li>
                        @if(!empty($historia['Linfatico']))
                            <label for="Linfatico"><b>Linfatico:</b>{{$historia['Linfatico']}}</label>
                            <br> @else(!empty(NULL))
                            <label for="Linfatico"><b>Linfatico:</b>Normal</label>
                            <br> @endif
                    </li>
                    <li>
                        @if(!empty($historia['Osteomioarticular']))
                            <label for="Osteomioarticular"><b>Osteomioarticular:</b>{{$historia['Osteomioarticular']}}</label>
                            <br> @else(!empty(NULL))
                            <label for="Osteomioarticular"><b>Osteomioarticular:</b>Normal</label>
                            <br> @endif
                    </li>
                    <li>
                        @if(!empty($historia['Neurologico']))
                            <label for="Neurologico"><b>Neurologico:</b>{{$historia['Neurologico']}}</label>
                            <br> @else(!empty(NULL))
                            <label for="Neurologico"><b>Neurologico:</b>Normal</label>
                            <br> @endif
                    </li>
                    <li>
                        @if(!empty($historia['Cardiovascular']))
                            <label for="Cardiovascular"><b>Cardiovascular:</b>{{$historia['Cardiovascular']}}</label>
                            <br> @else(!empty(NULL))
                            <label for="Cardiovascular"><b>Cardiovascular: Normal</label><br>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Tegumentario']))
                            <label for="Tegumentario"><b>Tegumentario: </b>{{$historia['Tegumentario']}}</label>
                            <br> @else(!empty(NULL))
                            <label for="Tegumentario"><b>Tegumentario: </b>Normal</label>
                            <br> @endif
                    </li>
                    <li>
                        @if(!empty($historia['Respiratorio']))
                            <label for="Respiratorio"><b>Respiratorio:</b>{{$historia['Respiratorio']}}</label>
                        @else(!empty(NULL))
                            <label for="Respiratorio"><b>Respiratorio:</b>Normal</label>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Endocrinologico']))
                            <label for="Endocrinologico"><b>Endocrinologico:</b>{{$historia['Endocrinologico']}}</label>
                            <br> @endif
                    </li>
                    <li>
                        @if(!empty($historia['Gastrointestinal']))
                            <label for="Gastrointestinal"><b>Gastrointestinal:</b>{{$historia['Gastrointestinal']}}</label>
                        @else(!empty(NULL))
                            <label for="Gastrointestinal"><b>Gastrointestinal:</b>Normal</label>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Norefiere']))
                            <label for="Otro"><b>Otro:</b>{{$historia['Norefiere']}}</label>
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
                        @if(!empty($historia['Dietasaludable']))
                            <label for="Dieta Saludable"><b>¿Tiene dieta saludable?:</b> {{$historia['Dietasaludable']}}</label>
                            <br> @ELSE(!empty(NULL))
                            <label for="Dieta Saludable"><b>¿Tiene dieta saludable?:</b> No Refiere</label>
                            <br> @endif
                    </li>
                    <li>
                        @if(!empty($historia['Suenoreparador']))
                            <label for="Sueño Reparador"><b>¿Tiene sueño reparador?:</b> {{$historia['Suenoreparador']}}</label>
                            <br> @else(!empty(NULL))
                            <label for="Sueño Reparador"><b>¿Tiene sueño reparador?:</b>No Refiere</label>
                            <br> @endif
                    </li>
                    <li>
                        @if(!empty($historia['Duermemenoseishoras']))
                            <label for="Duerme Menos Seis Horas"><b>¿Duerme menos de seis horas?:</b> {{$historia['Duermemenoseishoras']}}
                            </label>
                            <br> @else(!empty(NULL))
                            <label for="Duerme Menos Seis Horas"><b>¿Duerme menos de seis horas?:</b>No Refiere</label>
                            <br> @endif
                    </li>
                    <li>
                        @if(!empty($historia['Altonivelestres']))
                            <label for="Alto Nivel Estres"><b>¿Alto nivel estres?:</b> {{$historia['Altonivelestres']}}</label>
                            <br> @else(!empty(NULL))
                            <label for="Alto Nivel Estres"><b>¿Alto nivel estres?:</b>No Refiere</label>
                            <br> @endif
                    </li>
                    <li>
                        @if(!empty($historia['Actividadfisica']))
                            <label for="Actividad Fisica"><b>Actividad fisica:</b> {{$historia['Actividadfisica']}}<span>
                        </span>
                                <label for="Frecuencia Semanal"><b>Frecuencia semanal:</b> {{$historia['Frecuenciasemana']}}</label>
                                <span><label for="Duración"><b>Duracion:</b> {{$historia['Duracion']}}</label></span></label>
                            <br> @else(!empty(NULL))
                            <label for="Actividad Fisica"><b>Actividad fisica:</b> No Refiere<span>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Fumacantidad']))
                            <label for="Fuma Cantidad"><b>Cantidad que fuma:</b> {{$historia['Fumacantidad']}}</label> <br>
                        @else(!empty(NULL))
                            <label for="Fuma Cantidad"><b>Cantidad que fuma:</b> No Refiere</label> <br>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Fumainicio']))
                            <label for="Fuma Inicio"><b>Inicio a fumar:</b> {{$historia['Fumainicio']}}</label> <br>
                        @else(!empty(NULL))
                            <label for="Fuma Inicio"><b>Inicio a fumar:</b> No Refiere</label> <br>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Fumadorpasivo']))
                            <label for="Fumador Pasivo"><b>Es fumador pasivo:</b> {{$historia['Fumadorpasivo']}}</label> <br>
                        @else(!empty(NULL))
                            <label for="Fumador Pasivo"><b>Es fumador pasivo:</b>No Refiere</label> <br>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Cantidadlicor']))
                            <label for="Cantidad licor"><b>Cantidad de licor:</b> {{$historia['Cantidadlicor']}}</label> <br>
                        @else(!empty(NULL))
                            <label for="Cantidad licor"><b>Cantidad de licor:</b>No Refiere</label> <br>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Licorfrecuencia']))
                            <label for="Frecuencia Licor"><b>Frecuencia del licor:</b> {{$historia['Licorfrecuencia']}}</label> <br>
                        @else(!empty(NULL))
                            <label for="Frecuencia Licor"><b>Frecuencia del licor:</b>No Refiere</label> <br>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Consumopsicoactivo']))
                            <label for="Consumo Psicoactivo"><b>Consumo de sustancias psicoactivo:</b>
                                {{$historia['Consumopsicoactivo']}}</label> <br>
                        @else(!empty(NULL))
                            <label for="Consumo Psicoactivo"><b>Consumo de sustancias psicoactivo:</b>No Refiere</label> <br>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Psicoactivocantidad']))
                            <label for="Psicoactivo Cantidad"><b>Cantidad de sustancias Psicoactivas:</b>
                                {{$historia['Psicoactivocantidad']}}</label> <br>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Estilovidaobservaciones']))
                            <label for="Estilovida Observaciones"><b>Estilo vida observaciones:</b>
                                {{$historia['Estilovidaobservaciones']}}</label> <br>
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
                @if(!empty($historia['Antropometricas']))
                <li>{{$historia['Antropometricas']}}</li>
                @endif
            </ul>
        </div>
    </div>
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Signos Vitales</b></h4>
            <ul>
                @if(!empty($historia['Signos_Vitales']))
                <li>{{$historia['Signos_Vitales']}}</li>
                @endif
            </ul>
        </div>
    </div>
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Otros signos vitales</b></h4>
            <ul>
                @if(!empty($historia['Otros_Signos_Vitales']))
                    <li>{{$historia['Otros_Signos_Vitales']}}</li>
                @endif
            </ul>
        </div>
    </div>
    @if ($historia['Tipocita_id'] != 1 && $historia['Tipocita_id'] != 3 && $historia['Tipocita_id'] != 4 && $historia['Tipocita_id'] != 5 && $historia['Tipocita_id'] != 7  && $historia['Tipocita_id'] != 6)
        <div style="margin-left: -40px; margin-right: -40px;">
            <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
                <h4 style="text-align: center"><b style="background-color: #efefef">Examen Físico</b></h4>
                <ul>
                    <li>
                        @if(!empty($historia['Cabezacuello']))
                            <label for="Dieta Saludable"><b>Cabeza cuello:</b>{{$historia['Cabezacuello']}}</label> <br>
                        @else
                            <label for="Dieta Saludable"><b>Cabeza cuello:</b> Normal</label> <br>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Suenoreparador']))
                            <label for="Sueño Reparador"><b>Ojos y Fondo de Ojos:</b>{{$historia['Suenoreparador']}}</label> <br>
                        @else
                            <label for="Sueño Reparador"><b>Ojos y Fondo de Ojos:</b>Normal</label> <br>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Cardiopulmonar']))
                            <label for="Duerme Menos Seis Horas"><b>Cardiopulmonar:</b>{{$historia['Cardiopulmonar']}}</label> <br>
                        @else
                            <label for="Duerme Menos Seis Horas"><b>Cardiopulmonar:</b>Normal</label> <br>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Abdomen']))
                            <label for="Alto Nivel Estres"><b>Abdomen:</b>{{$historia['Abdomen']}}</label> <br>
                        @else
                            <label for="Alto Nivel Estres"><b>Abdomen:</b>Normal</label> <br>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Extremidades']))
                            <label for="Fuma Cantidad"><b>Extremidades:</b>{{$historia['Extremidades']}}</label> <br>
                        @else
                            <label for="Fuma Cantidad"><b>Extremidades:</b> Normal</label> <br>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Pulsosperifericos']))
                            <label for="Fuma Inicio"><b>Pulsos Perifericos:</b>{{$historia['Pulsosperifericos']}}</label> <br>
                        @else
                            <label for="Fuma Inicio"><b>Pulsos Perifericos:</b> Normal</label> <br>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Neurologico']))
                            <label for="Fumador Pasivo"><b>Neurologicos:</b>{{$historia['Neurologico']}}</label> <br>
                        @else
                            <label for="Fumador Pasivo"><b>Neurologicos:</b>Normal</label> <br>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Reflejososteotendinos']))
                            <label for="Cantidad licor"><b>Reflejos Osteotendinosos:</b>{{$historia['Reflejososteotendinos']}}</label> <br>
                        @else
                            <label for="Cantidad licor"><b>Reflejos Osteotendinosos:</b>Normal</label> <br>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Pielfraneras']))
                            <label for="Frecuencia Licor"><b>Piel y Faneras:</b>{{$historia['Pielfraneras']}}</label> <br>
                        @else
                            <label for="Frecuencia Licor"><b>Piel y Faneras:</b>Normal</label> <br>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Genitourinario']))
                            <label for="Consumo Psicoactivo"><b>Genitourinario:</b>{{$historia['Genitourinario']}}</label> <br>
                        @else
                            <label for="Consumo Psicoactivo"><b>Genitourinario:</b>Normal</label> <br>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Examenmama']))
                            <label for="Estilovida Observaciones"><b>Examen de Mama:</b>{{$historia['Examenmama']}}</label> <br>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Tactoretal']))
                            <label for="Estilovida Observaciones"><b>Tacto Rectal:</b>{{$historia['Tactoretal']}}</label> <br>
                        @endif
                    </li>
                    <li>
                        @if(!empty($historia['Examenmental']))
                            <label for="Consumo Psicoactivo"><b>Examen Mental:</b>{{$historia['Examenmental']}}</label> <br>
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
                <li>@if(!empty($historia['Presión_Arterial'])){{$historia['Presión_Arterial']}}@endif</li>
            </ul>
        </div>
    </div>
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Estado general del paciente</b></h4>
            <pre
                style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                @if(!empty($historia['Condiciongeneral'])){{$historia['Condiciongeneral']}}@endif
            </pre>
        </div>
    </div>
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Análisis y plan</b></h4>
            <pre
                style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                 @if(!empty($historia['Planmanejo'])){{$historia['Planmanejo']}}@endif
                </pre>
        </div>
    </div>
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Diagnósticos del paciente</b></h4>
            <ul>
                <li><b>Diagnóstico principal: </b> <span>@if(!empty($historia['Diagnostico_Principal'])){{$historia['Diagnostico_Principal']}}@endif</span></li>
                <li><b>Otros diagnósticos: </b>
                    @if(!empty($historia['Diagnostico_Secundario']))<pre>{{$historia['Diagnostico_Secundario']}}</pre>@endif
                </li>
            </ul>
        </div>
    </div>
    @if($historia['Recomendaciones'] !=  null )
        <div style="margin-left: -40px; margin-right: -40px;">
            <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
                <h4 style="text-align: center"><b style="background-color: #efefef">Recomendaciones</b></h4>
                <pre
                    style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                    {{$historia['Recomendaciones']}}
                </pre>
            </div>
        </div>
    @endif


    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: center"><b style="background-color: #efefef">Causa externa y finalidad</b></h4>
            <p style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
            <p style="font-weight:bold"><b>Causa externa: </b> <span>@if(!empty($historia['Destinopaciente'])){{$historia['Destinopaciente']}}@endif</span></p>
            <p style="font-weight:bold"><b>Finalidad: </b> <span>@if(!empty($historia['Finalidad'])){{$historia['Finalidad']}}@endif</span></p>
            </p>
        </div>
    </div>
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
            background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
            <h4 style="text-align: LEFT; margin-left: 8px;"><b style="background-color: #efefef">INFORMACIÓN DEL PROFESIONAL</b></h4>
            <pre
                style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important">
                @if ($historia['Tipocita_id'] == 6)
                    <p>
                    @if(!empty($historia['Firmaradiologo']))
                            <img src="../{{$historia['Firmaradiologo']}}" alt="" style="width: 208px; height:58px; text-align: LEFT">@endif
                </p>
                    <p><b>Medico:</b> @if(!empty($historia['Radiologo'])){{$historia['Radiologo']}}@endif</p>
                    <p><b>Registromedico:</b> @if(!empty($historia['Regradiologo'])){{$historia['Regradiologo']}}@endif</p>
                @endif
                <p><b>Especialidad:</b> @if(!empty($historia['Especialidad'])){{$historia['Especialidad']}}@endif</p>
                <p><b>Registromedico:</b> @if(!empty($historia['Registromedico'])){{$historia['Registromedico']}}@endif</p>
                </pre>
        </div>
    </div>
    <div style="margin-left: -40px; margin-right: -40px;">
        @if ($historia['Tipocita_id'] != 6)
            <div style="font-size: 10px; font-family: Tahoma, Geneva, sans-serif !important;
                vertical-align: top; text-align: center;">
                @if(!empty($historia['Firma']))
                    <img src="../{{$historia['Firma']}}" alt="" style="width: 208px; height:58px">@endif
                <br>
                <label for="firma_digital">Firma digital: </label>
            </div>
        @endif
    </div>
    </body>
@endforeach
</html>
