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
                <label for="SUMIMEDICAL"><b>SUMIMEDICAL</b></label><br>
                <label for="Nit: "><b>NIT: </b><span><i>900033371-4</i></span></label><br>
                <label style="font-size: 18px"><b>{{$tipo_cita}}</b></label><br>
                <label for="Direccion_Sede "><b>Direccion:</b><i>{{$Direccion_Sede}}</i></label><br>
                <label for="Telefono: "><b>Telefono: </b><i>520-10-40</i></label><br>
                <label for="Dirección: "><i>Impreso Por: </i><b><i>{{auth()->user()->name}}
                            {{auth()->user()->apellido}}</i></b></label><br>
            </div>
        </div>
    </div>
    <div id="content">
        <style type="text/css">
            .tg {
                border-collapse: collapse;
                border-spacing: 0;
                margin-right: 40px !important
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
                    {{-- <th class="tg-h8g6">IPS Básica</th> --}}
                    <th class="tg-h8g6">Dirección</th>
                    <th class="tg-h8g6">Correo</th>
                    <th class="tg-h8g6">Teléfono</th>
                    <th class="tg-h8g6">Tipo Afiliado</th>
                </tr>
                <tr>
                    <td class="tg-x6od">{{$identificacion}}</td>
                    <td class="tg-x6od">{{$nombre}}</td>
                    <td class="tg-x6od">{{$edad}}</td>
                    <td class="tg-x6od">{{$sexo}}</td>
                    {{-- <td class="tg-x6od">{{$ips}}</td> --}}
                    <td class="tg-x6od">{{$direccion}}</td>
                    <td class="tg-x6od">{{$correo}}</td>
                    <td class="tg-x6od">{{$telefono}}</td>
                    <td class="tg-x6od">{{$Tipo_Afiliado}}</td>
                </tr>
            </table>
            <br>
            <table class="tg">
                <tr>
                    <td class="tg-h8g6">Fecha Autorización</td>
                    {{-- <td class="tg-h8g6">Médico Tratante</td>
                    <td class="tg-h8g6">Médico Ordena</td> --}}
                    <td class="tg-h8g6">observación</td>
                </tr>
                {{-- {{-- @foreach($servicios as $key=>$servicio) --}}
                <tr>
                    <td class="tg-x6od">{{$Fecha_actual}}</td>
                    {{-- <td class="tg-x6od">{{$medico_tratante}}</td>
                    <td class="tg-x6od">{{$medico_ordena}}</td> --}}
                    <td class="tg-x6od">{{$holi}}</td>
                </tr>
                {{-- @endforeach --}}
                <tr>
                    <td class="tg-ybnj" colspan="2">
                        @if(!empty($firma_digital))
                        {{$firma_digital}}@endif<br>
                        <label for="firma_digital">Firma Digital: </label>
                        <br></td>
                </tr>
            </table>
        </div>
    </div>
    </div>

</body>

</html>
