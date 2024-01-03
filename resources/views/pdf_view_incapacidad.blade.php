<html>

<head>
    <style>
        @page {
            margin: 170px -20px;
            font-size: 11px;
            margin-top: 170px;
            size: 8.5in 11in;
            font-family:Arial, sans-serif;
        }

        #header {
            overflow-x:auto;
            position: fixed;
            margin-left: 30px;
            margin-right: 30px;
            top: -150px;
            right: 0px;
            height: 20%;
            text-align: center;
        }

        #footer {
            position: fixed;
            left: 0px;
            bottom: 400px;
            right: 0px;
            height: 10%;
            text-align: center;
        }

        #footer .page:after {
            content: counter(page, upper-roman);
        }

        #content {
            max-height: 20%;
            margin-left: 30px;
        }
        .tg  {border-collapse:collapse;border-spacing:0;}
        .tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:10px;
            overflow:hidden;padding:2px 5px;word-break:normal;}
        .tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:10px;
            font-weight:normal;overflow:hidden;padding:2px 5px;word-break:normal;}
        .tg .tg-baqh{text-align:center;vertical-align:top}
        .tg .tg-lqy6{text-align:right;vertical-align:top}
        .tg .tg-amwm{font-weight:bold;text-align:center;vertical-align:top}
        .tg .tg-l2oz{font-weight:bold;text-align:right;vertical-align:top}
        .tg .tg-0lax{text-align:left;vertical-align:top}
        .tg .tg-jpc1{font-size:10px;text-align:left;vertical-align:top}

    </style>

<body>
    <div id="header">
        <table class="tg" style="table-layout: fixed; width: 100%">
            <thead>
            <tr>
                <th class="tg-amwm" colspan="32">&nbsp;&nbsp;&nbsp;CERTIFICADO DE INCAPACIDAD MÉDICA</th>
                @if ($Ut == 'MEDIMAS')
                    <th class="tg-baqh" colspan="11" rowspan="3"><img src="images/logo.png" width="90" height="50"></th>
                @endif
                @if ($Ut == 'REDVITAL UT')
                    <th class="tg-baqh" colspan="11" rowspan="3"><img src="images/logo-redvital-1.png" width="125" height="50"></th>
                @endif
            </tr>
            <tr>
                <td class="tg-l2oz" colspan="3"><b>Número:</b></td>
                <td class="tg-0lax" colspan="7">{{$order_id}}</td>
                <td class="tg-l2oz" colspan="4"><b>Fecha:</b></td>
                <td class="tg-0lax" colspan="7">{{$Fechacreaincapacidad}}</td>
                <td class="tg-l2oz" colspan="4"><b>Convenio:</b></td>
            <td class="tg-0lax" colspan="7" style="color: red">{{$Ut}}</td>
            </tr>
            <tr>
                <td class="tg-amwm" colspan="32" style="text-align: center"><b>INFORMACIÓN DEL PACIENTE</b></td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="tg-l2oz" colspan="3"><b>Nombre:</b></td>
                <td class="tg-0lax" colspan="14">{{$Nombre}}</td>
                <td class="tg-l2oz" colspan="5"><b>Tipo Documento:</b></td>
                <td class="tg-0lax" colspan="3">{{$TTipod}}</td>
                <td class="tg-l2oz" colspan="4"><b>Número:</b></td>
                <td class="tg-0lax" colspan="5">{{$identificacion}}</td>
                <td class="tg-l2oz" colspan="4"><b>Tipo Afiliado:</b></td>
                <td class="tg-0lax" colspan="5" style="color: red">{{$afilia}}</td>
            </tr>
            <tr>
                <td class="tg-l2oz" colspan="4"><b>Tipo Empleado:</b></td>
                <td class="tg-0lax" colspan="6"></td>
               
                <td class="tg-l2oz" colspan="5"><b>Tipo de Plan:</b></td>
                 @if ($Ut == 'MEDIMAS')
                <td class="tg-0lax" colspan="6">Contributivo</td>
                @endif
                 @if ($Ut == 'REDVITAL UT')
                <td class="tg-0lax" colspan="6">Magisterio</td>
                @endif
                @if ($Ut == 'REDVITAL UT')
                <td class="tg-l2oz" colspan="4"><b>Labora en:</b></td>
                <td class="tg-0lax" colspan="18" style="font-size:7px">{{$Laboraen}}</td>
                @endif
            </tr>
            <tr>
                @if ($Ut == 'MEDIMAS')
                    <td class="tg-l2oz" colspan="4"><b>Nivel Salarial:</b></td>
                    <td class="tg-0lax" colspan="6"></td>
                @endif                
                <td class="tg-l2oz" colspan="3"><b>Teléfono:</b></td>
                <td class="tg-0lax" colspan="5">{{$tel}}</td>
                <td class="tg-l2oz" colspan="5"><b>Edad:</b></td>
                <td class="tg-0lax" colspan="5">{{$edad}}</td>
                @if ($Ut == 'REDVITAL UT')
                    <td class="tg-l2oz" colspan="3"><b>IPS Primaria</b></td>
                    <td class="tg-jpc1" colspan="12">{{$ips1}}</td>
                @endif
                @if ($Ut == 'MEDIMAS')
                    <td class="tg-l2oz" colspan="3"><b>IPS Primaria</b></td>
                    <td class="tg-jpc1" colspan="12">{{$ma}}</td>
                @endif
                
            </tr>

            <tr>
                <td class="tg-l2oz" colspan="43" style="text-align: center"><b>DETALLE DE LA INCAPACIDAD</b></td>
            </tr>
            <tr>
                @if ($Ut == 'MEDIMAS1')
                <td class="tg-l2oz" colspan="3"><b>Tipo:</b></td>
                @endif
                <td class="tg-0lax" colspan="5"></td>
                
                @if ($Ut == 'MEDIMAS1')
                    <td class="tg-l2oz" colspan="9" style="font-size: 12px"><b>IPS que generó la incapacidad:</b></td>
                    <td class="tg-0lax" colspan="16" style="font-size: 10px">{{$Proveedor}}</td> 
                @endif
                @if ($Ut == 'MEDIMAS1') 
                <td class="tg-l2oz" colspan="6"><b>N° incapacidad anterior:</b></td>
                 @endif
                <td class="tg-0lax" colspan="4"></td>

            </tr>
            <tr>
                <td class="tg-l2oz" colspan="5"><b>Tipo de generación:</b></td>
                <td class="tg-0lax" colspan="6"></td>
                <td class="tg-l2oz" colspan="4"><b>Prórroga:</b></td>
                <td class="tg-0lax" colspan="3">{{$Prorroga}}</td>
                <td class="tg-l2oz" colspan="4"><b>Fecha Inicio:</b></td>
                <td class="tg-0lax" colspan="6">{{$FechaInicio}}</td>
                <td class="tg-l2oz" colspan="3"><b>Fecha final:</b></td>
                <td class="tg-0lax" colspan="5">{{$FechaFinal}}</td>
                <td class="tg-l2oz" colspan="3"><b>Total días:</b></td>
                <td class="tg-0lax" colspan="4">{{$CantidadDias}}</td>
            </tr>
            <tr>
                <td class="tg-l2oz" colspan="3"><b>Concepto:</b></td>
                <td class="tg-0lax" colspan="7">{{$Contingencia}}</td>
                <td class="tg-l2oz" colspan="6"><b>Diagnóstico principal:</b></td>
                <td class="tg-0lax" colspan="4">{{$TextCie10}}</td>
                <td class="tg-0lax" colspan="23"></td>
            </tr>
            <tr>
                <td class="tg-amwm" colspan="43" style="text-align: center"><b>OBSERVACIONES</b></td>
            </tr>
            <tr>
                <td class="tg-0lax" colspan="43">{{$Descripcion}}</td>
            </tr>
            <tr>
                <td class="tg-baqh" colspan="10" rowspan="3">
                    @if ($hj == 1)
                        <p>{{$Medicoordeno}}</p>
                    @else
                        @if(!empty($Firma))
                        <img src="../{{$Firma}}" width="100" height="30">
                        @endif
                    @endif
                    
                <td class="tg-0lax" colspan="33" rowspan="5"></td>
            </tr>
            <tr>
            </tr>
            <tr>
            </tr>
            <tr>
                <td class="tg-l2oz" colspan="4"><b>Profesional:</b></td>
                <td class="tg-0lax" colspan="6">{{$medico_ordena}}</td>
            </tr>
            <tr>
                <td class="tg-l2oz" colspan="4"><b>Registro Medico:</b></td>
                <td class="tg-0lax" colspan="6">{{$Rmedico}}</td>
            </tr>
            <tr>
                <td class="tg-l2oz" colspan="4"><b>Especialidad:</b></td>
                <td class="tg-0lax" colspan="6">{{$especialidad_medico}}</td>
                <td class="tg-lqy6" colspan="5"><span style="font-weight:bold;font-style:italic">Impreso Por:</span></td>
                <td class="tg-0lax" colspan="15"><i>{{auth()->user()->name}} {{auth()->user()->apellido}}</i></td>
                <td class="tg-0lax" colspan="4"><span style="font-weight:bold;font-style:italic">Fecha Impresión:</span></td>
                <td class="tg-0lax" colspan="9"><?php echo date("Y-m-d h:i:s") ?></td>
            </tr>
            </tbody>
        </table>
    </div>
</body>

</html>