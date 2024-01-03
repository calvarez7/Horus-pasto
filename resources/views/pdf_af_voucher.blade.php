<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de validación</title>
    <style>
        * {
            font-family: Arial;
        }
        header{
            width: 100%;
        }
        .dateExp {
            margin-left: 500px;
            font-size: 10;
        }
        .name {
            font-size: 34px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .otherInfo {
            width: 100%;
        }
        .code{
            margin-bottom: 20px;
        }
        .acum{
            margin-bottom: 10px;
        }
        .main-title > .content {
            font-size: 20px;
            font-weight: bold;
            margin: 10px 0 10px 300px;
        }
        table {
            width: 100%;
        }

    </style>
</head>
<body>
    <header>
        <div class="rep">
            <div class="dateExp">Fecha de validación: {{ date('d/m/Y', strtotime($paqTemp['created_at'])) }}</div>
            <div class="name">
                <span>{{ $paqTemp['repName'] }}</span>
            </div>
            <div class="acum">
                @if($paqTemp['afTemps'][0]['entityCode'] == 'RES004')
                    <span><b>CONVENIO MAGISTERIO ({{$paqTemp['afTemps'][0]['entityCode']}})</b></span>
                @elseif($paqTemp['afTemps'][0]['entityCode'] == 'EAS027')
                    <span><b>CONVENIO FERROCARRILES({{$paqTemp['afTemps'][0]['entityCode']}})</b></span>
                @else
                    <span><b>CONVENIO MEDIMAS ({{$paqTemp['afTemps'][0]['entityCode']}})</b></span>
                @endif
            </div>
            <div class="otherInfo">
                <div class="code">
                    <em>Cod. Hab. {{ $paqTemp['afTemps'][0]['code'] }} - {{ $paqTemp['afTemps'][0]['documentType'] }} {{ $paqTemp['afTemps'][0]['documentNumber'] }}</em>
                </div>
            </div>
            <div class="acum">
                <span><b>Cantidad de facturas:</b> {{ $paqTemp['totalBills'] }}</span><br />
                <span><b>Total radicado:</b> {{ number_format($paqTemp['totalValueAf']) }}</span>
            </div>
        </div>
        <div class="main-title">
            <div class="content">AF{{ $paqTemp['namePaq'] }}</div>
        </div>
    </header>
    <div id="container">
        <table>
            <tr>
                <th>#</th>
                <th>Número de factura</th>
                <th>Fecha Expedición</th>
                <th>Valor neto</th>
            </tr>
            $number = 1;
            @foreach($paqTemp['afTemps'] as $afTemps)
            <tr>
                <td>{{ $number++ }}</td>
                <td>{{ $afTemps['billNumber'] }}</td>
                <td>{{ $afTemps['billDate'] }}</td>
                <td>{{ number_format($afTemps['netoValue']) }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</body>
</html>
