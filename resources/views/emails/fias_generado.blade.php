<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fias Generado</title>
</head>
<body>
    <h1>¡Tu fias esta listo!</h1>
    <p>Generaste el fias {{ $data['tipo_fias'] }} de la fecha {{ $data['mes'] }}/{{ $data['anio'] }}</p>
    <p>El archivo se envio como adjunto.</p>
</body>
</html>