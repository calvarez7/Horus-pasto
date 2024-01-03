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
            text-align: left;
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
        div.container {
            display: inline-block;
        }

    </style>

<body>
    <div id="header">
        <div class="card" style="margin-top: 20px; margin-left: 20px">
            <span style="margin-top: 0; margin-left: 580px; font-size:15px">{{ $page }}/{{ $total }}</span>
            {{-- <img src="img_avatar.png" alt="Avatar" style="width:100%"> --}}
            <div class="container" style="margin-right: -550px; margin-top: 10px">
                <label for="IPS" ><i></i><b>SUMIMEDICAL S.A.S</b></label> <br>
                <label for="Nit" ><i><b>NIT:</b></i> 900033371 <i><b>Res:</b> </i>004</label> <br>
                <label for="direccion" ><i></i><b>Carrera 80 c Nùmero 32EE-65</b></label> <br>
                <label for="telefono" ><i><b>Telefono:</b></i> 5201040</label> <br>
                <label for="departamento" ><b>Antioquia, Medellin</b></label> <br>
            </div>

                <div class="container" style="margin-top: 10px">
                <label for="Fecha"><i>Impreso por:</i> <b>{{auth()->user()->name}} {{auth()->user()->apellido}}</b></label><br>
                <label for="IPS"><i>Número de Autorización:</i> <b>{{$order_id}}</b></label><br>
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
                border-color: #3eb39e;
                text-align: left;
                vertical-align: top
            }

            .tg .tg-h8g6 {
                font-weight: bold;
                background-color: #efefef;
                border-color: #3eb39e;
                text-align: center;
                vertical-align: top
            }

            .tg .tg-baqh {
                text-align: center;
                vertical-align: top
            }

            .tg .tg-ybnj {
                border-color: #3eb39e;
                text-align: center;
                vertical-align: top
            }

            .tg .tg-37mw {
                font-size: 11px;
                border-color: #3eb39e;
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
        <div class="tg">
            <table class="tg">
                <tr>
                    <th class="tg-h8g6">Nombre Paciente</th>
                    <th class="tg-h8g6">Identificación</th>
                    <th class="tg-h8g6">Edad</th>
                    <th class="tg-h8g6">Sexo</th>
                    <th class="tg-h8g6">Dx.</th>
                    <th class="tg-h8g6">Teléfono</th>
                    <th class="tg-h8g6">Celular</th>
                    <th class="tg-h8g6">Correo</th>
                </tr>
                <tr>
                    <td class="tg-x6od">{{$nombre}}</td>
                    <td class="tg-x6od">{{$identificacion}}</td>
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
                
                    <div style="font-size: 12px; font-family: Tahoma, Geneva, sans-serif !important;
                        background-color: #ffffff; border: 1.5px solid #3eb39e;vertical-align: top">
                        <h4 style="text-align: center"><b>Recomendaciónes</b></h4>
                        <pre
                            style="white-space: pre-wrap; margin-left: 4px; margin-right: 4px; font-size: 14px; font-family: Tahoma, Geneva, sans-serif !important">
                                {{$observaciones}}
                            </pre>
                    </div>
                </div>
            </table>
            <div style="margin-left: -40px; margin-right: -40px;">
                <div style="margin-left: -40px; margin-right: -40px;">
                    <div style="font-size: 12px; font-family: Tahoma, Geneva, sans-serif !important;
                            vertical-align: top; text-align: center;">
                        @if(!empty($Firma))
                        <img src="../{{$Firma}}" alt="" style="width: 208px; height:58px">@endif<br>
                        <label for="firma_digital">Firma digital: </label>
                    </div>
                </div>
        </div>
    </div>
    </div>
</body>

</html>
