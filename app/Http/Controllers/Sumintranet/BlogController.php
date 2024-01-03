<?php

namespace App\Http\Controllers\Sumintranet;

use App\Http\Controllers\Controller;
use App\Modelos\blog;
use App\Modelos\conteonoticia;
use App\Modelos\Megustanoticia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{

    public function blog()
    {
        $blog = blog::select(
            'blogs.id',
            'blogs.subtexto',
            'blogs.titulo',
            'blogs.texto',
            'blogs.estado_id',
            'estados.Nombre as Nombre_estado',
            'estados.id as idestado',
            'blogs.created_at',
            'blogs.imagen',
            'blogs.fecha_inicio'
        )
            ->withCount('detalle')->join('estados', 'blogs.estado_id', 'estados.id')->orderBy('blogs.estado_id', 'asc')->get();
        return response()->json($blog);
    }

    public function blogs()
    {
        // $fechas = Carbon::now();
        // $fecha = $fechas->format('Y-m-d');
        $datos = blog::select(DB::raw('*', 'created_at as dia'))
            ->withCount(['relacion' => function ($query) {
                $query->where('like', true);
            }])
            ->withCount(['relacions' => function ($query) {
                $query->where('like', false);
            }])
            ->with(['userlike' => function ($query) {
                $query->select(['*'])->get();
            }])
            ->where('estado_id', 1)
            ->orderby('fecha_inicio', 'desc')->paginate(4);
        return [
            'pagination' => [
                'total' => $datos->total(),
                'current_page' => $datos->currentPage(),
                'per_page' => $datos->perPage(),
                'last_page' => $datos->lastPage(),
                'from' => $datos->firstItem(),
                'to' => $datos->lastPage(),
            ],
            'datos' => $datos
        ];
    }

    public function detalle($id)
    {
        $detalle = blog::select(
            'blogs.id',
            'blogs.subtexto',
            'blogs.titulo',
            'blogs.texto',
            'blogs.estado_id',
            'blogs.created_at',
            'blogs.imagen',
            'blogs.fecha_inicio'
        )->where('id', $id)->first();

        conteonoticia::create([
            'visto' => 'true',
            'user_id' => Auth::user()->id,
            'noticia_id' => $id
        ]);
        return response()->json($detalle, 200);
    }

    public function store(Request $request)
    {
        $fechas = Carbon::now();
        $fecha = $fechas->format('Y-m-d');
        $validar = Validator::make($request->all(), [
            'imagen' => 'required',
            'subtexto' => 'required',
            'titulo' => 'required',
            'texto' => 'required',
            'fecha_inicio' => 'required',
        ]);
        if ($validar->fails()) {
            return response()->json(["errors" => $validar->getMessageBag()], 422);
        }
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $name = time() . $file->getClientOriginalName();
            $file->move(public_path() . '/storage/imagenesintranet', $name);

            if ($request->fecha_inicio > $fecha) {
                $estado = 2;
            }
            if ($request->fecha_inicio == $fecha) {
                $estado = 1;
            }
            blog::create([
                'imagen' => $name,
                'subtexto' => $request->subtexto,
                'titulo' => $request->titulo,
                'texto' => $request->texto,
                'fecha_inicio' => $request->fecha_inicio,
                'estado_id' => $estado,
            ]);
        }
        return response()->json(['message' => 'Creado con exito']);
    }

    public function estado(Request $request, $id)
    {
        if ($request->Nombre_estado == '1') {
            blog::where('id', $id)->update([
                'estado_id' => 2,
                'updated_at' => Carbon::now()
            ]);
        } else if ($request->Nombre_estado == '2') {
            blog::where('id', $id)->update([
                'estado_id' => 1,
                'updated_at' => Carbon::now()
            ]);
        }


        return response()->json(['message' => 'hola']);
    }

    public function update(Request $request)
    {
        $fechas = Carbon::now();
        $fecha = $fechas->format('Y-m-d');
        $validar = Validator::make($request->all(), [
            'subtexto' => 'required',
            'fecha_inicio' => 'required',
            'titulo' => 'required',
            'texto' => 'required',
        ]);
        if ($validar->fails()) {
            return response()->json(["errors" => $validar->getMessageBag()], 422);
        }
        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $Nombre_Imagen = time() . $archivo->getClientOriginalName();
            $archivo->move(public_path() . '/storage/imagenesintranet', $Nombre_Imagen);
        } else {
            $Nombre_Imagen = $request->imagen;
        }
        if ($request->fecha_inicio > $fecha) {
            blog::where('id', $request->id)->update([
                'estado_id' => 2,
            ]);
        }
        if ($request->fecha_inicio == $fecha) {
            blog::where('id', $request->id)->update([
                'estado_id' => 1,
            ]);
        }
        blog::where('id', $request->id)->update([
            'fecha_inicio' => $request->fecha_inicio,
            'subtexto' => $request->subtexto,
            'titulo' => $request->titulo,
            'texto' => $request->texto,
            'imagen' => $Nombre_Imagen,
            'updated_at' => Carbon::now()
        ]);
        return response()->json([
            'message' => 'Usuario actualizado con exito !'
        ], 200);
    }

    public function opcion(Request $request, $id)
    {
        if ($request->megusta == true) {
            $megusta = Megustanoticia::where('blog_id', $id)->where('user_id', Auth::user()->id)->where('like',true)->first();
            if(!$megusta){
                Megustanoticia::create([
                    'like' => $request->megusta,
                    'blog_id' => $id,
                    'user_id' => Auth::user()->id,
                ]);
            }
        } else if ($request->nomegusta == false) {
            $noMegusta = Megustanoticia::where('blog_id', $id)->where('user_id', Auth::user()->id)->where('like',false)->first();
            if(!$noMegusta){
                Megustanoticia::create([
                    'like' => $request->nomegusta,
                    'blog_id' => $id,
                    'user_id' => Auth::user()->id,
                ]);
            }
        }

        return response()->json(['message' => 'Creado con exito', 200]);
    }

    public function verusuarios($id)
    {
        $ver = conteonoticia::select(
            DB::raw("(CASE WHEN conteonoticias.visto = '1' THEN 'Visto' ELSE 'No Visto' END) AS estado"),
            DB::raw("concat(users.name,' ',users.apellido) as nombre"),
            DB::raw("count(users.id) as usuario")
        )
            ->join('users', 'users.id', 'conteonoticias.user_id')
            ->where('conteonoticias.noticia_id', $id)
            ->groupby(
                'conteonoticias.visto',
                'users.name',
                'users.apellido',
                'conteonoticias.noticia_id'
            )
            ->get();
        return response()->json($ver, 200);
    }
}
