<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<body>
<style type="text/css">
    .tg {
        border-collapse: collapse;
        border-spacing: 0;
    }

    .tg td {
        border-color: black;
        border-style: solid;
        border-width: 1px;
        font-family: Arial, sans-serif;
        font-size: 10px;
        overflow: hidden;
        padding: 5px 3px;
        word-break: normal;
    }

    .tg th {
        border-color: black;
        border-style: solid;
        border-width: 1px;
        font-family: Arial, sans-serif;
        font-size: 10px;
        font-weight: normal;
        overflow: hidden;
        padding: 10px 5px;
        word-break: normal;
    }

    .tg .tg-c3ow {
        border-color: inherit;
        text-align: center;
        vertical-align: top
    }

    .tg .tg-0pky {
        border-color: inherit;
        text-align: left;
        vertical-align: top
    }

    .tg .tg-7btt {
        border-color: inherit;
        font-weight: bold;
        text-align: center;
        vertical-align: top
    }
</style>
<table class="tg">
    <tr>
        <th class="tg-c3ow" colspan="45"><span style="font-weight:bold">ANEXO TÉCNICO No. 3</span><br><span
                style="font-weight:bold">SOLICITUD DE AUTORIZACION DE SERVICIOS DE SALUDE</span></th>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="7" rowspan="3">
            <img src="images/logo_colombia.png"
                 style="width: 100px;height: 100px;margin-left: 18px;margin-right: -18px;margin-top: 8px;margin-bottom: -8;">
        </td>
        <td class="tg-7btt" colspan="38">MINISTERIO DE LA PROTECCION SOCIAL</td>
    </tr>
    <tr>
        <td class="tg-c3ow" colspan="38"><span
                style="font-weight:bold">SOLICITUD DE AUTORIZACION DE SERVICIOS DE SALUD</span></td>
    </tr>
    <tr>
        <td class="tg-c3ow" colspan="11"><span style="font-weight:bold">NUMERO DE SOLICITUD</span></td>
        <td class="tg-0pky" colspan="10">{{$ordenId}}</td>
        <td class="tg-c3ow" colspan="3"><span style="font-weight:bold">FECHA</span></td>
        <td class="tg-0pky" colspan="6">{{date('Y-m-d')}}</td>
        <td class="tg-c3ow" colspan="3"><span style="font-weight:bold">HORA</span></td>
        <td class="tg-0pky" colspan="5">{{date('h:i a')}}</td>
    </tr>
    <tr>
        <td class="tg-c3ow" colspan="45"><span style="font-weight:bold">INFORMACION DEL PRESTADOR (solicitante)</span>
        </td>
    </tr>
    <tr>
        <td class="tg-7btt" colspan="5">NOMBRE</td>
        <td class="tg-0pky" colspan="26">SUMIMEDICAL S.A.S</td>
        <td class="tg-c3ow" colspan="2"><span style="font-weight:bold">NIT</span></td>
        <td class="tg-0pky" colspan="12">900033371</td>
    </tr>
    <tr>
        <td class="tg-c3ow" colspan="5"><span style="font-weight:bold">Código</span></td>
        <td class="tg-0pky" colspan="8">004</td>
        <td class="tg-c3ow" colspan="12"><span style="font-weight:bold">Dirección prestador</span></td>
        <td class="tg-0pky" colspan="20">Carrera 80 C Numero 32EE-65</td>
    </tr>
    <tr>
        <td class="tg-c3ow" colspan="5"><span style="font-weight:bold">Télefono</span></td>
        <td class="tg-0pky" colspan="8">5201040</td>
        <td class="tg-c3ow" colspan="12"><span style="font-weight:bold">Departamento</span></td>
        <td class="tg-0pky" colspan="6">Antioquia</td>
        <td class="tg-c3ow" colspan="8"><span style="font-weight:bold">Municipio</span></td>
        <td class="tg-0pky" colspan="6">Medellin</td>
    </tr>
    <tr>
        <td class="tg-c3ow" colspan="45"><span style="font-weight:bold">ENTIDAD A LA QUE SE LE SOLICITA (PAGADOR)</span>
        </td>
    </tr>
    <tr>
        <td class="tg-7btt" colspan="45">DATOS DEL PACIENTE</td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="11">{{$paciente->Primer_Ape}}</td>
        <td class="tg-0pky" colspan="11">{{$paciente->Segundo_Ape}}</td>
        <td class="tg-0pky" colspan="11">{{$paciente->Primer_Nom}}</td>
        <td class="tg-0pky" colspan="12">{{$paciente->SegundoNom}}</td>
    </tr>
    <tr>
        <td class="tg-c3ow" colspan="11"><span style="font-weight:bold">Tipo Documento de Identificación</span></td>
        <td class="tg-0pky" colspan="34"></td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="2"></td>
        <td class="tg-7btt" colspan="9">Registro Civil</td>
        <td class="tg-0pky" colspan="2"></td>
        <td class="tg-7btt" colspan="9">Pasaporte</td>
        <td class="tg-7btt" colspan="12">Número documento de identificación</td>
        <td class="tg-0pky" colspan="11">{{$paciente->Num_Doc}}</td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="2"></td>
        <td class="tg-7btt" colspan="9">Tarjeta de identidad</td>
        <td class="tg-0pky" colspan="2"></td>
        <td class="tg-7btt" colspan="9">Adulto sin identificación</td>
        <td class="tg-7btt" colspan="12">Fecha de Nacimiento</td>
        <td class="tg-0pky" colspan="11">{{$paciente->Fecha_Naci}}</td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="2">{{$paciente->Tipo_Doc == 'CC'?'X':''}}</td>
        <td class="tg-7btt" colspan="9">Cédula de ciudadanía</td>
        <td class="tg-0pky" colspan="2"></td>
        <td class="tg-7btt" colspan="9">Menor sin identificación</td>
        <td class="tg-7btt" colspan="9">Departamento</td>
        <td class="tg-0pky" colspan="3">{{$paciente->Dane_Dpto}}</td>
        <td class="tg-7btt" colspan="8">Municipio</td>
        <td class="tg-0pky" colspan="3">{{$paciente->Dane_Mpio}}</td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="2"></td>
        <td class="tg-7btt" colspan="9">Cédula de extranjería</td>
        <td class="tg-c3ow" colspan="11"><span style="font-weight:bold">Dirección de Residencia Habitual</span></td>
        <td class="tg-0pky" colspan="13">{{$paciente->Direccion_Residencia}}</td>
        <td class="tg-7btt" colspan="4">Teléfono</td>
        <td class="tg-0pky" colspan="6">{{$paciente->Telefono}}</td>
    </tr>
    <tr>
        <td class="tg-c3ow" colspan="11"><span style="font-weight:bold">Teléfono celular</span></td>
        <td class="tg-0pky" colspan="11">{{$paciente->Celular1}}</td>
        <td class="tg-c3ow" colspan="8"><span style="font-weight:bold">Correo electrónico</span></td>
        <td class="tg-0pky" colspan="15">{{$paciente->Correo1}}</td>
    </tr>
    <tr>
        <td class="tg-7btt" colspan="45">Cobertura en salud</td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="2">{{$paciente->tipo_categoria == 'CONTRIBUTIVO'?'X':''}}</td>
        <td class="tg-7btt" colspan="9">Regimen Contributivo</td>
        <td class="tg-0pky" colspan="2"></td>
        <td class="tg-7btt" colspan="9">Regimen Subsidiado - parcial</td>
        <td class="tg-0pky" colspan="2"></td>
        <td class="tg-7btt" colspan="11">Población Pobre no asegurada sin SISBEN</td>
        <td class="tg-0pky" colspan="2"></td>
        <td class="tg-7btt" colspan="8">Plan adicional de salud</td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="2">{{$paciente->tipo_categoria == 'SUBSIDIADO'?'X':''}}</td>
        <td class="tg-7btt" colspan="9">Regimen Subsidiado - total</td>
        <td class="tg-0pky" colspan="2"></td>
        <td class="tg-7btt" colspan="9">Población pobre No asegurada con SISBEN</td>
        <td class="tg-0pky" colspan="2"></td>
        <td class="tg-7btt" colspan="11">Desplazado</td>
        <td class="tg-0pky" colspan="2"></td>
        <td class="tg-7btt" colspan="8">Otro</td>
    </tr>
    <tr>
        <td class="tg-7btt" colspan="45">FORMACION DE LA ATENCION Y SERVICIOS SOLICITADOS</td>
    </tr>
    <tr>
        <td class="tg-7btt" colspan="18">Origen de la atención</td>
        <td class="tg-7btt" colspan="13">Tipo de servicios solicitados</td>
        <td class="tg-7btt" colspan="14">Prioridad de la atención</td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="2">{{$citaPaciente->Finalidad == "Enfermedad General"?"X":""}}</td>
        <td class="tg-7btt" colspan="7">Enfermedad General</td>
        <td class="tg-0pky" colspan="2">{{$citaPaciente->Finalidad == "Accidente de Trabajo"?"X":""}}</td>
        <td class="tg-7btt" colspan="7">Accidente de trabajo</td>
        <td class="tg-0pky" colspan="2"></td>
        <td class="tg-7btt" colspan="11">Posterior a la atención inicial de urgencias</td>
        <td class="tg-0pky" colspan="2"></td>
        <td class="tg-7btt" colspan="12">Prioritaria</td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="2">{{$citaPaciente->Finalidad == "Enfermedad Profesionall"?"X":""}}</td>
        <td class="tg-c3ow" colspan="7"><span style="font-weight:bold">Enfermedad Profesional</span></td>
        <td class="tg-0pky" colspan="2"></td>
        <td class="tg-7btt" colspan="7">Accidente de tránsito</td>
        <td class="tg-0pky" colspan="2"></td>
        <td class="tg-7btt" colspan="11">Servicios electivos</td>
        <td class="tg-0pky" colspan="2"></td>
        <td class="tg-7btt" colspan="12">No prioritaria</td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="2"></td>
        <td class="tg-c3ow" colspan="9"><span style="font-weight:bold">Evento Catastrófico</span></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
    </tr>
    <tr>
        <td class="tg-7btt" colspan="45">Ubicación del Paciente al momento de la solicitud de autorizacion:</td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="2"></td>
        <td class="tg-7btt" colspan="6">Consulta Externa</td>
        <td class="tg-0pky" colspan="2"></td>
        <td class="tg-7btt" colspan="6">Hospitalización</td>
        <td class="tg-7btt" colspan="4">Servicio</td>
        <td class="tg-0pky" colspan="25"></td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="2"></td>
        <td class="tg-7btt" colspan="6">Urgencias</td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-0pky"></td>
        <td class="tg-7btt" colspan="4">Cama</td>
        <td class="tg-0pky" colspan="25"></td>
    </tr>
    <tr>
        <td class="tg-7btt" colspan="16">Manejo integral según Guía de:</td>
        <td class="tg-0pky" colspan="29"></td>
    </tr>
    <tr>
        <td class="tg-7btt" colspan="6">Código CUPS</td>
        <td class="tg-7btt" colspan="5">Cantidad</td>
        <td class="tg-7btt" colspan="34">Descripción</td>
    </tr>
    @foreach($servicios as $servicio)
        <tr>
            <td class="tg-0pky" colspan="6">{{$servicio['codigo']}}</td>
            <td class="tg-0pky" colspan="5">{{$servicio['cantidad']}}</td>
            @if(isset($servicio['descripcion']))
                <td class="tg-0pky" colspan="34">{{$servicio['descripcion']}}</td>
            @elseif (isset($servicio["nombre"]))
                <td class="tg-0pky" colspan="34">{{$servicio['nombre']}}</td>
            @endif
        </tr>
    @endforeach
    <tr>
        <td class="tg-7btt" colspan="45">Justificación Clínica:<br></td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="45">
            @foreach($servicios as $servicio)
            - {{$servicio['observacion']}}<br>
            @endforeach

        </td>
    </tr>
    <tr>
        <td class="tg-7btt" colspan="45">Impresión Diagnóstica:</td>
    </tr>
    <tr>
        <td class="tg-7btt" colspan="8">Diagnóstico principal</td>
        <td class="tg-7btt" colspan="6">Codigo CIE10</td>
        <td class="tg-7btt" colspan="31">Descripción</td>
    </tr>
    @foreach($cie10s as $cie10)
        @if($cie10["primario"])
            <tr>
                <td class="tg-0pky" colspan="8"></td>
                <td class="tg-0pky" colspan="6">{{$cie10["codigo"]}}</td>
                <td class="tg-0pky" colspan="31">{{$cie10["Descripcion"]}}</td>
            </tr>
            @break
        @endif
    @endforeach
    <tr>
        <td class="tg-7btt" colspan="8">Diagnóstico relacionado</td>
        <td class="tg-7btt" colspan="6"><span style="font-weight:700;font-style:normal">Codigo CIE10</span><br></td>
        <td class="tg-7btt" colspan="31"><span style="font-weight:700;font-style:normal">Descripción</span><br></td>
    </tr>
    @foreach($cie10s as $cie10)
        @if(!$cie10["primario"])
            <tr>
                <td class="tg-0pky" colspan="8"></td>
                <td class="tg-0pky" colspan="6">{{$cie10["codigo"]}}</td>
                <td class="tg-0pky" colspan="31">{{$cie10["Descripcion"]}}</td>
            </tr>
        @endif
    @endforeach
    <tr>
        <td class="tg-7btt" colspan="45">INFORMACION DE LA PERSONA QUE SOLICITA</td>
    </tr>
    <tr>
        <td class="tg-7btt" colspan="8">Nombre de que solicita</td>
        <td class="tg-0pky" colspan="22"></td>
        <td class="tg-7btt" colspan="5">Teléfono</td>
        <td class="tg-0pky" colspan="10"></td>
    </tr>
    <tr>
        <td class="tg-7btt" colspan="8">Cargo o actividad</td>
        <td class="tg-0pky" colspan="22"></td>
        <td class="tg-7btt" colspan="5">Teléfono Celular</td>
        <td class="tg-0pky" colspan="10"></td>
    </tr>
    <tr>
        <td class="tg-0pky" colspan="45"><span
                style="font-weight:700;font-style:italic">MPS-SAS V5.0 2008-07-11</span><br></td>
    </tr>
</table>
</body>

</html>
