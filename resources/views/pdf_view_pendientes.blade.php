<?php
use \Milon\Barcode\DNS1D;
use \Milon\Barcode\DNS2D;

?>
<html>

<head>
    <style>
        @page {
            margin: 180px 0px;
            font-size: 11px;
            margin-top: 150px;
            size: 8.5in 11in;
        }

        #header {
            position: fixed;
            left: -10px;
            top: -150px;
            right: -90px;
            height: 140px;
            background-color: #84c44c;
            text-align: center;
            background-image: url('/storage/images/1.PNG');
            background-repeat: no-repeat;
            border-bottom: dotted;
        }

        #footer {
            position: relative;
            left: 0px;
            right: 0px;
            height: 10%;
            text-align: center;
        }

        #footer .page:after {
            content: counter(page, upper-roman);
        }

        #content {
            max-height: 20%;
        }

        .tg {
            border-collapse: collapse;
            border-spacing: 0;
            margin-left: 15px;
            margin-right: 20px !important
        }

    </style>

<body>
    <div id="header">
        <div class="card" style="margin-top: 40px; margin-left: 50px">
            {{-- <img src="img_avatar.png" alt="Avatar" style="width:100%"> --}}
            <div class="container" style="">
                <label for="IPS"><i>Punto de Atención:</i> {{$ips}}</label><br>
                <label for="Fecha"><i>Impreso por:</i> <b>{{auth()->user()->name}} {{auth()->user()->apellido}}</b></label><br>
                <label for="IPS"><i>Número de Autorización:</i> <b>{{$order_id}}</b></label><br>
                <label for="Consulta"><i>Fecha Solicitud:</i> <b>{{$fecha_auditoria}}</b></label><br>
                <label for="Consulta"><i>Fecha Impresión:</i> <b>{{$Fecha_actual}}</b></label><br>
                <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($identificacion, 'C39')}}" alt="barcode" /><br>
            </div>
        </div>
    </div>
    <div id="content">
        <style type="text/css">
            .tg {
                border-collapse: collapse;
                border-spacing: 0;
                margin-right: 20px !important
            }

            .tg td {
                font-family: Arial, sans-serif;
                font-size: 11px;
                padding: 0px 10px;
                border-style: solid;
                border-width: 1px;
                overflow: hidden;
                word-break: normal;
                border-color: black;
            }

            .tg th {
                font-family: Arial, sans-serif;
                font-size: 11px;
                font-weight: normal;
                padding: 0px 10px;
                border-style: solid;
                border-width: 1px;
                overflow: hidden;
                word-break: normal;
                border-color: black;
            }

            .tg .tg-x6od {
                border-color: #2e3092;
                text-align: left;
                vertical-align: top
            }

            .tg .tg-h8g6 {
                font-weight: bold;
                background-color: #efefef;
                border-color: #2e3092;
                text-align: center;
                vertical-align: top
            }

            .tg .tg-baqh {
                text-align: center;
                vertical-align: top
            }

            .tg .tg-ybnj {
                border-color: #2e3092;
                text-align: center;
                vertical-align: top
            }

            .tg .tg-37mw {
                font-size: 11px;
                border-color: #2e3092;
                text-align: center;
                vertical-align: top
            }

            @media screen and (max-width: 767px) {
                .tg {
                    width: auto !important;
                }

                .tg col {
                    width: auto !important;
                }

                .tg-wrap {
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                }
            }

        </style>
        <div class="tg-wrap">
            <table class="tg">
                <tr>
                    <th class="tg-h8g6">Identificación</th>
                    <th class="tg-h8g6">Nombre</th>
                    <th class="tg-h8g6">Edad</th>
                    <th class="tg-h8g6">Sexo</th>
                    <th class="tg-h8g6">Dx.</th>
                    <th class="tg-h8g6">Teléfono</th>
                    <th class="tg-h8g6">Celular</th>
                    <th class="tg-h8g6">Correo</th>
                </tr>
                <tr>
                    <td class="tg-x6od">{{$identificacion}}</td>
                    <td class="tg-x6od">{{$nombre}}</td>
                    <td class="tg-x6od">{{$edad}}</td>
                    <td class="tg-x6od">{{$sexo}}</td>
                    <td class="tg-x6od">{{$dx_principal}}</td>
                    <td class="tg-x6od">{{$telefono}}</td>
                    <td class="tg-x6od">{{$celular}}</td>
                    <td class="tg-x6od">{{$correo}}</td>
                </tr>
            </table>
            <br>
            <table class="tg">
                <tr>
                    <td class="tg-h8g6" style="width: 2%">Código</td>
                    <td class="tg-h8g6">Nombre</td>
                    <td class="tg-h8g6" style="width: 2%">Presentación</td>
                    <td class="tg-h8g6">vía de <br>admón</td>
                    <td class="tg-h8g6" style="width: 2%">Días</td>
                    <td class="tg-h8g6" style="width: 2%">Cant</td>
                    <td class="tg-h8g6">Reclamar en</td>
                    <td class="tg-h8g6">Observación</td>
                </tr>
                @foreach($medicamentos as $key=>$medicamento)
                <tr>
                    <td class="tg-x6od">{{$medicamento['id']}}</td>
                    <td class="tg-x6od">{{$medicamento['nombre']}}</td>
                    <td class="tg-x6od">{{$medicamento['Unidadmedida']}}</td>
                    <td class="tg-x6od">{{$medicamento['PosViaAdmin']}}</td>
                    <td class="tg-x6od">{{$medicamento['Duracion']}}</td>
                    <td class="tg-x6od">{{$medicamento['Cantidadpormedico']}}</td>
                    <td class="tg-x6od">{{$ips}}</td>
                    <td class="tg-x6od">{{$medicamento['Observacion']}}</td>                   
                </tr>
                @endforeach
                <tr>
                    <td class="tg-ybnj" colspan="4">
                            @if(!empty($medico_tratante))
                            {{$medico_tratante}}@endif<br>
                            <label for="firma_digital">Medico Tratante: </label>
                        <br></td>
                    <td class="tg-baqh" colspan="4">
                            <label for="firma_digital"><p style="color:red">MÉDICAMENTOS PENDIENTES</p></label>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    </div>
    {{-- <div id="footer">
        <p style="widht: 80% text-align: justify;">IMPORTANTE: ESTE DOCUMENTO ES VÁLIDO SOLAMENTE HASTA EL {{$Fecha_estimada}},
    UNA VEZ CUMPLIDO DICHO PLAZO NO HAY RESPONSABILIDAD DE LA EPS Y MEDICINA PREPAGADA SUMIMEDICAL - RED VITAL.</p>
    <table class="tg" style="width: 100%">
        <tr>
            <td class="tg-xn46">
                @if(!empty($Firma))
                <img src="../{{$Firma}}" alt="" style="width: 208px; height:58px">@endif<br>
                <label for="firma_digital">Firma Digital: </label>
            </td>
            <td class="tg-xn46">
                @if(!empty($Firma_Auditor))
                <img src="../{{$Firma_Auditor}}" alt="" style="width: 208px; height:58px"><br>
                <label for="firma_digital">Firma Auditor: </label>
                @endif
            </td>
        </tr>
    </table>
    </div> --}}
</body>

</html>
