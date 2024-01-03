<?php

namespace App\Http\Controllers\Sumintranet;

use App\Events\FelicitacioneEvent;
use App\Http\Controllers\Controller;
use App\Modelos\Empleado;
use App\Modelos\Felicitacione;
use App\Modelos\Notification as ModelosNotification;
use App\Notifications\FelicitacioneNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification as NotificationsNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class CumpleañosController extends Controller
{

    public function fecha(Request $request)
    {
        $fechas = Carbon::now();
        $mes = $fechas->format('m');
        $dia = $fechas->format('d');
        $fecha = Empleado::select(
            'empleados.Nombre',
            'empleados.id',
            'empleados.genero',
            'areas.Nombre as nombreArea',
            'empleados.correo',
            'empleados.celular',
            'empleados.id',
            DB::raw("DATEPART(DD,fecha_nacimiento) as dia")
        )->withCount(['felicitacion' => function ($query) {
            $query->where('felcitacion', true);
        }])
            ->leftjoin('areas', 'areas.id', 'empleados.area_id')
            ->wheremonth('fecha_nacimiento', $mes)
            ->whereday('fecha_nacimiento', $dia)
            ->orderby('dia', 'asc')
            ->get();
        $dato = [];
        foreach ($fecha as $key => $reporte) {
            $dato[$key] = $reporte;
            setlocale(LC_TIME, "spanish");
            $reporte["dia"] = strftime("%d %B");
        }
        return response()->json($dato, 200);
    }

    public function store(Request $request, $id)
    {
        $post = Felicitacione::create([
            'user_id' => Auth::user()->id,
            'felcitacion' => $request->felcitacion,
            'empleado_id' => $id,
            'created_at' => Carbon::now()
        ]);
        $empledo = Empleado::find($id)->toArray();
        User::where('cedula', $empledo['Documento'])->each(function (User $user) use ($post) {
            Notification::send($user, new FelicitacioneNotification($post));
        });
        return response()->json(['message' => 'Enviada con exito !', 200]);
    }

    public function traer()
    {
        $vars = count(Auth()->user()->unreadNotifications);
        $leidos = Auth()->user()->readNotifications;
        $sin_leer = Auth()->user()->unreadNotifications;
        return response()->json([
            'count' => $vars,
            'leidos' => $leidos,
            'no_leidos' => $sin_leer,
            200
        ]);
    }

    public function todos(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function update(Request $request, $id)
    {
        ModelosNotification::where('id', $id)->update(['read_at' => Carbon::now()]);
    }

    public function destroy($id)
    {
        //
    }
}
