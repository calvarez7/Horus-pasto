<!DOCTYPE html>
<html lang="en">
<head>
  <title>Reporte Teleconcepto</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:12px;
  overflow:hidden;padding:5px 2px;word-break:normal;}
.tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:12px;
  font-weight:normal;overflow:hidden;padding:5px 2px;word-break:normal;}
.tg .tg-c3ow{border-color:inherit;text-align:center;vertical-align:top}
.tg .tg-7btt{border-color:inherit;font-weight:bold;text-align:center;vertical-align:top}
.tg .tg-0pky{border-color:inherit;text-align:left;vertical-align:top}
</style>
</head>
<body>

<div class="container">
<table class="tg" style="undefined;table-layout: fixed; width: 720px; margin-left:-30; margin-right:-12">
<colgroup>
<col style="width: 25px">
<col style="width: 25px">
<col style="width: 25px">
<col style="width: 25px">
<col style="width: 25px">
<col style="width: 25px">
<col style="width: 25px">
<col style="width: 25px">
<col style="width: 25px">
<col style="width: 25px">
<col style="width: 28px">
<col style="width: 25px">
<col style="width: 26px">
<col style="width: 26px">
<col style="width: 26px">
<col style="width: 26px">
<col style="width: 26px">
<col style="width: 26px">
<col style="width: 26px">
<col style="width: 26px">
<col style="width: 26px">
<col style="width: 26px">
<col style="width: 26px">
<col style="width: 26px">
<col style="width: 26px">
<col style="width: 26px">
<col style="width: 26px">
<col style="width: 26px">
<col style="width: 30px">
<col style="width: 29px">
<col style="width: 27px">
<col style="width: 20px">
<col style="width: 20px">
<col style="width: 20px">
<col style="width: 20px">
<col style="width: 20px">
</colgroup>
<thead>
  <tr>
    <th class="tg-c3ow" colspan="36" style="background:green"><span style="font-weight:bold; color:white">DATOS DEL PACIENTE</span></th>
  </tr>
</thead>
<tbody>
  <tr>
    <td class="tg-7btt" colspan="4">Nombre:</td>
    <td class="tg-0pky" colspan="6">{{$paciente['Primer_Nom']}}</td>
    <td class="tg-7btt" colspan="4">Apellido:</td>
    <td class="tg-0pky" colspan="7">{{$paciente['Primer_Ape']}}</td>
    <td class="tg-7btt" colspan="2">Tipo:</td>
    <td class="tg-0pky" colspan="2">{{$paciente['Tipo_Doc']}}</td>
    <td class="tg-7btt" colspan="4">Documento:</td>
    <td class="tg-0pky" colspan="7">{{$paciente['Num_Doc']}}</td>
  </tr>
  <tr>
    <td class="tg-7btt" colspan="2">Edad:</td>
    <td class="tg-0pky" colspan="2">{{$paciente['Edad_Cumplida']}}</td>
    <td class="tg-7btt" colspan="6">Regimen:</td>
    <td class="tg-0pky" colspan="4">{{$paciente['Primer_Ape']}}</td>
    <td class="tg-7btt" colspan="7">Tipo Afiliado:</td>
    <td class="tg-0pky" colspan="6"></td>
    <td class="tg-7btt" colspan="3">Teléfono: </td>
    <td class="tg-0pky" colspan="6">{{$paciente['Celular2']}}</td>
  </tr>
  <tr>
    <td class="tg-7btt" colspan="4">Celular:</td>
    <td class="tg-0pky" colspan="6">{{$paciente['Celular1']}}</td>
    <td class="tg-7btt" colspan="4">Correo:</td>
    <td class="tg-0pky" colspan="11">{{$paciente['Correo1']}}</td>
    <td class="tg-7btt" colspan="3">EPS:</td>
    <td class="tg-0pky" colspan="9" style="color:red">{{$Ut}}</td>
  </tr>
  <tr>
    <td class="tg-c3ow" colspan="36" style="background:green"><span style="font-weight:bold; color:white">TELE-MEDICINA</span></td>
  </tr>
  <tr>
    <td class="tg-7btt" colspan="5">Motivo:</td>
    <td class="tg-0pky" colspan="31" style="text-align:justify">{{$motivo}}</td>
  </tr>
  <tr>
    <td class="tg-c3ow" colspan="36" style="background:green"><span style="font-weight:bold; color:white">RESUMEN HISTORIA CLINICA</span></td>
  </tr>
  <tr>
    <td class="tg-0pky" colspan="36" style="text-align:justify">{{$ResumenHc}}</td>
  </tr>
  <tr>
    <td class="tg-c3ow" colspan="36" style="background:green"><span style="font-weight:bold; color:white">RESPUESTA FAMILIARISTA</span></td>
  </tr>
  <tr>
    <td class="tg-0pky" colspan="36" style="text-align:justify">{{$Respuesta}}</td>
  </tr>
  <tr>
    <td class="tg-0pky" colspan="12">
    <img src="../{{$Firma}}" width="100" height="30"><br><span>Firma</span>
    </td>
    <td class="tg-0pky" colspan="12"><br>{{$Registromedico}}<br><span>Registo Médico</span></td>
    <td class="tg-0pky" colspan="12"><br>{{$especialidad_medico}}<br><span>Especialidad Médico</span></td>
  </tr>
</tbody>
</table>
</div>

</body>
</html>