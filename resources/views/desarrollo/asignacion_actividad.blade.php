<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Actividad</title>
</head>
<body>

    <div class="card mx-auto">
        <div class="card-header">
            Nueva actividad
        </div>
        <div class="card-body">
            <h5 class="card-title">{{ $actividad->titulo }}</h5>
            <p class="card-text">{{ $actividad->detalle }}</p>
            <p>Inicia {{ $actividad->tiempo_inicio }}</p>
            <p>Fecha entrega {{ $actividad->tiempo_fin }}</p>
            <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
    </div>

</body>
</html>
