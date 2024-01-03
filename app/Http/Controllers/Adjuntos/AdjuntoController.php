<?php

namespace App\Http\Controllers\Adjuntos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AdjuntoController extends Controller
{
    public function get(Request $request)
    {
        return Storage::disk(config('filesystems.disksUse'))->get($request->ruta);
    }

    public function getType(Request $request){

        $path = pathinfo(Storage::disk(config('filesystems.disksUse'))->path($request->ruta));

        switch ($path['extension']){
            case 'pdf':

                $extension = "application/pdf";
            break;
            case 'csv':
            case 'xls':
                $extension = "application/vnd.ms-excel";
            break;
            case 'jpeg':
            case 'jpg':
                $extension = "image/jpeg";
            break;
            case 'doc':
            case 'docx':
                $extension = "application/msword";
            break;
            case 'png':
            case 'PNG':
            $extension = "image/png";
            break;
            case 'xlsx':
                $extension = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8";
            break;
            case 'txt':
            case 'TXT':
                $extension = "text/plain";
            break;
        }

        return response()->json($extension, 200);
    }
}
