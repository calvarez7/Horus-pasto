<html>

<head>
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
            background-color: orange;
            text-align: center;
            background-image: url('/storage/images/logoradium.png');
            background-repeat: no-repeat;
            border-bottom: dotted
        }

        #footer {
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

        .page-break {
            page-break-after: always;
        }

    </style>

<body>
    <div id="header">
    </div>
    <div id="footer">
    </div>
    <div id="content" style="width:100%; float:left; height:auto; border:1px">
        <style type="text/css">
            .tg {
                border-collapse: collapse;
                border-spacing: 0;
            }

            .tg td {
                font-family: Arial, sans-serif;
                font-size: 14px;
                padding: 8px 10px;
                border-style: solid;
                border-width: 1px;
                overflow: hidden;
                word-break: normal;
                border-color: black;
            }

            .tg th {
                font-family: Arial, sans-serif;
                font-size: 14px;
                font-weight: normal;
                padding: 8px 10px;
                border-style: solid;
                border-width: 1px;
                overflow: hidden;
                word-break: normal;
                border-color: black;
            }

            .tg .tg-t2c5 {
                font-size: 12px;
                font-family: Tahoma, Geneva, sans-serif !important;
                ;
                background-color: #efefef;
                color: #343434;
                border-color: #3eb39e;
                text-align: center;
                vertical-align: top
            }

            .tg .tg-qgl5 {
                font-size: 12px;
                font-family: Tahoma, Geneva, sans-serif !important;
                ;
                background-color: #ffffff;
                border-color: #3eb39e;
                text-align: left;
                vertical-align: top
            }

            .tg .tg-acmg {
                font-size: 12px;
                font-family: Tahoma, Geneva, sans-serif !important;
                ;
                border-color: #3eb39e;
                text-align: left;
                vertical-align: top
            }

            .tg .tg-j7to {
                font-size: 12px;
                font-family: Tahoma, Geneva, sans-serif !important;
                ;
                border-color: #3eb39e;
                text-align: center;
                vertical-align: bottom
            }

            .tg .tg-k5cz {
                font-size: 12px;
                font-family: Tahoma, Geneva, sans-serif !important;
                ;
                background-color: #efefef;
                color: #343434;
                border-color: #3eb39e;
                text-align: left;
                vertical-align: top
            }

            .tg .tg-9zda {
                font-size: 12px;
                font-family: Tahoma, Geneva, sans-serif !important;
                ;
                border-color: #3eb39e;
                text-align: left;
                vertical-align: bottom
            }

            .tg .tg-zp25 {
                font-size: 12px;
                font-family: Tahoma, Geneva, sans-serif !important;
                ;
                background-color: #ffffff;
                border-color: #3eb39e;
                text-align: left;
                vertical-align: bottom
            }

            .tg .tg-yks0 {
                font-weight: bold;
                font-size: 12px;
                font-family: Tahoma, Geneva, sans-serif !important;
                ;
                background-color: #efefef;
                color: #343434;
                border-color: #3eb39e;
                text-align: center;
                vertical-align: bottom
            }

            .tg .tg-7ghi {
                font-size: 13px;
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

            table.minimalistBlack {
                border: 3px solid #3EB39E;
                background-color: #FFFFFF;
                width: 100%;
                text-align: left;
                border-collapse: collapse;
            }

            table.minimalistBlack td,
            table.minimalistBlack th {
                border: 2px solid #3EB39E;
                padding: 5px 4px;
            }

            table.minimalistBlack tbody td {
                font-size: 13px;
                color: #000000;
            }

            table.minimalistBlack thead {
                background: #EFEFEF;
                background: -moz-linear-gradient(top, #f3f3f3 0%, #f0f0f0 66%, #EFEFEF 100%);
                background: -webkit-linear-gradient(top, #f3f3f3 0%, #f0f0f0 66%, #EFEFEF 100%);
                background: linear-gradient(to bottom, #f3f3f3 0%, #f0f0f0 66%, #EFEFEF 100%);
                border-bottom: 3px solid #3EB39E;
            }

            table.minimalistBlack thead th {
                font-size: 15px;
                font-weight: bold;
                color: #000000;
                text-align: center;
            }

            table.minimalistBlack tfoot td {
                font-size: 14px;
            }

        </style>
        <table class="tg">
            <tr>
                <th class="tg-7ghi" colspan="34"><span style="font-weight:bold">Información básica del paciente</span>
                </th>
            </tr>
            <tr>
                <td class="tg-yks0" colspan="5"><span style="font-weight:bold">Nombre Completo</span></td>
                <td class="tg-zp25" colspan="10">{{$Nombrepaciente}}</td>
                <td class="tg-j7to" colspan="3"><span style="font-weight:700">Identificación</span></td>
                <td class="tg-9zda" colspan="7">{{$Num_Doc}}</td>
                <td class="tg-j7to" colspan="4"><span style="font-weight:bold">Fecha nacimiento</span></td>
                <td class="tg-9zda" colspan="5">{{$Fecha_Naci}}</td>
            </tr>
            <tr>
                <td class="tg-t2c5" colspan="4"><span style="font-weight:bold">Edad</span></td>
                <td class="tg-qgl5" colspan="3">{{$Edad_Cumplida}}</td>
                <td class="tg-t2c5" colspan="3"><span style="font-weight:bold">Sexo</span></td>
                <td class="tg-qgl5" colspan="4">{{$Sexo}}</td>
                <td class="tg-t2c5" colspan="4"><span style="font-weight:700">Tipo afiliación</span></td>
                <td class="tg-acmg" colspan="7">{{$Tipo_Afiliado}}</td>
                <td class="tg-t2c5" colspan="4"><span style="font-weight:bold">Departamento</span></td>
                <td class="tg-qgl5" colspan="5">{{$Departamento}}</td>
            </tr>
            <tr>
                <td class="tg-t2c5" colspan="4"><span style="font-weight:bold">Teléfono</span></td>
                <td class="tg-qgl5" colspan="5">{{$Telefono}}</td>
                <td class="tg-t2c5" colspan="5"><span style="font-weight:bold">Municipio</span></td>
                <td class="tg-qgl5" colspan="10">{{$Mpio_Afiliado}}</td>
                <td class="tg-t2c5" colspan="5"><span style="font-weight:700">Estado civil</span></td>
                <td class="tg-qgl5" colspan="5">{{$Vivecon}}</td>
            </tr>
            <tr>
                <td class="tg-t2c5" colspan="5"><span style="font-weight:bold">Ocupación</span></td>
                <td class="tg-qgl5" colspan="12">{{$Ocupacion}}</td>
                <td class="tg-t2c5" colspan="7"><span style="font-weight:bold">Nombre Acompañante</span></td>
                <td class="tg-qgl5" colspan="10">{{$Nombreacompanante}}</td>
            </tr>
            <tr>
                <td class="tg-k5cz" colspan="5"><span style="font-weight:700">Teléfono Acompañante</span></td>
                <td class="tg-qgl5" colspan="6">{{$Telefonoacompanante}}</td>
                <td class="tg-k5cz" colspan="6"><span style="font-weight:700">Nombre Responsable</span></td>
                <td class="tg-qgl5" colspan="9">{{$Nombreresponsable}}</td>
                <td class="tg-k5cz" colspan="4"><span style="font-weight:700">Teléfono Responsable</span></td>
                <td class="tg-qgl5" colspan="4">{{$Telefonoresponsable}}</td>
            </tr>
            <tr>
                <td class="tg-k5cz" colspan="5"><span style="font-weight:700">Parentesco Responsable</span></td>
                <td class="tg-qgl5" colspan="9">{{$Parentesco}}</td>
                <td class="tg-t2c5" colspan="6"><span style="font-weight:700">Aseguradora</span></td>
                <td class="tg-qgl5" colspan="6">{{$Aseguradora}}</td>
                <td class="tg-t2c5" colspan="4"><span style="font-weight:700">Tipo Vinculación</span></td>
                <td class="tg-qgl5" colspan="4">{{$Tipovinculacion}}</td>
            </tr>
            {{-- <tr>
                <td class="tg-k5cz" colspan="17"><span style="font-weight:700">Parentesco Responsable</span></td>
                <td class="tg-qgl5" colspan="17">{{$Parentesco}}</td>
            </tr> --}}
        </table>
    </div>


    @empty(!$Indicacion)

    <p style="border-style: double; border-color: #3eb39e;"><b style="text-align:center">Indicación</b>
        <br><span>{{$Indicacion}}</span></p>
    </p>
    @if(!empty($Tecnica))
    <p style="border-style: double; border-color: #3eb39e"><b class="text-center">Tecnica</b>
        <br><span>{{$Tecnica}}</span>
    </p>
    @endif
    <p style="border-style: double; border-color: #3eb39e"><b class="text-center">Hallazgos</b>
        <br><span>{{$Hallazgos}}</span>
    </p>
    <p style="border-style: double; border-color: #3eb39e"><b class="text-center">Conclusión</b>
        <br><span>{{$Conclusion}}</span>
    </p>

    @empty(!$Notaclaratorias)
    <div style="page-break-before: always;"> </div>
    @foreach ($Notaclaratorias as $key => $itemnota)
    <p>
        <h3 style="color:#3eb39e" align="center">Nota aclaratoria #{{$key+1}}</h3>
        <p style="border-style: double; border-color: #3eb39e"><b class="text-center">Nota: </b>
            <br><span>{{ $itemnota['nota']}}</span>
        </p>
        <span><strong>Nombre:</strong> {{ $itemnota['name'] }} {{ $itemnota['apellido'] }}
            <strong>Especialidad:</strong>
            {{ $itemnota['especialidad_medico']}}
            <strong>Fecha:</strong> {{ explode('.',$itemnota['created_at'])[0] }}</span>
        <br />
    </p>
    <br />
    @endforeach
    @endempty

    @empty(!$Insumos)
    <div style="page-break-before: always;"> </div>
    <table class="minimalistBlack">
        <caption>
            <h3 style="color:#3eb39e" align="center">Insumos</h3>
        </caption>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        @foreach ($Insumos as $item)
        <tbody>
            <tr>
                <td>{{ $item['nombre']}}</td>
                <td>{{ $item['cantidad']}}</td>
            </tr>
        </tbody>
        @endforeach
        </tr>
    </table>
    @endempty
    @endempty

    @empty(!$Estudio)
    <p>
        <h3 style="color:#3eb39e" align="center">Estudio</h3>
        <p style="border-style: double; border-color: #3eb39e">
            <strong>Cantidad: </strong><label class="padding: 3px">{{ $Estudio['cantidad']}} |</label>
            <strong>Via: </strong><label>{{ $Estudio['via']}} |</label>
            <strong>Peso: </strong><label>{{$Estudio['peso']}} |</label>
            <strong>Tiempo: </strong><label>{{ $Estudio['tiempo']}} |</label>
            <strong>Exposición: </strong><label>{{ $Estudio['exposicion']}} |</label>
            <strong>mAs: </strong><label>{{ $Estudio['mas']}} |</label>
            <strong>Kv: </strong><label>{{$Estudio['kv']}}</label>
            <strong>Distancia: </strong><label>{{$Estudio['distancia']}}</label>
            <strong>Foco: </strong><label>{{$Estudio['foco']}}</label>
            <strong>Total dosis: </strong><label>{{$Estudio['total_dosis']}}</label>
        </p>
        <p style="border-style: double; border-color: #3eb39e"><b class="text-center">Observación: </b>
            <br><span>{{ $Estudio['observacion']}}</span>
        </p>
        <span><strong>Nombre:</strong> {{ $Estudio['name'] }} {{ $Estudio['apellido'] }} <strong>Especialidad:</strong>
            {{ $Estudio['especialidad_medico']}}
            <strong>Fecha:</strong> {{ explode('.',$Estudio['created_at'])[0] }}</span>
        <br />
    </p>
    <br />
    @endempty

    @empty(!$Notas)
    @foreach ($Notas as $key => $item)
    <p>
        <h3 style="color:#3eb39e" align="center">Nota #{{$key+1}}</h3>
        <p style="border-style: double; border-color: #3eb39e">
            <strong>Peso: </strong><label class="padding: 3px">{{ $item['peso']}} |</label>
            <strong>Frecuencia cardiaca: </strong><label>{{ $item['Frecucardiaca']}} |</label>
            <strong>Frecuencia respiratoria: </strong><label>{{ $item['Frecurespiratoria']}} |</label>
            <strong>Temperatura: </strong><label>{{ $item['Temperatura']}} |</label>
            <strong>Saturación de oxigeno: </strong><label>{{ $item['Saturacionoxigeno']}} |</label>
            <strong>Presión arterial: </strong><label>{{ $item['Presionarterialmedia']}} |</label>
            <strong>Tasa filtración: </strong><label>{{ $item['tasa_filtracion']}}</label>
        </p>
        <p style="border-style: double; border-color: #3eb39e"><b class="text-center">Observación: </b>
            <br><span>{{ $item['nota']}}</span>
        </p>
        <span><strong>Nombre:</strong> {{ $item['name'] }} {{ $item['apellido'] }} <strong>Especialidad:</strong>
            {{ $item['especialidad_medico']}}
            <strong>Fecha:</strong> {{ explode('.',$item['created_at'])[0] }}</span>
    </p>
    @endforeach
    @endempty

    @empty($Notas || $Estudio)
    <div style="margin-left: -40px; margin-right: -40px;">
        <div style="font-size: 12px; font-family: Tahoma, Geneva, sans-serif !important;
                vertical-align: top; text-align: center;">
            @if(!empty($Firma))
            <img src="../{{$Firma}}" alt="" style="width: 208px; height:58px">@endif<br>
            <label for="firma_digital">Firma digital: </label>
        </div>
    </div>
    @endempty


</body>

</html>
