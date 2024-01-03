<?php

namespace App\Http\Controllers;

use Mike42\Escpos\Printer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class ImpresoraController extends Controller
{
    //
    public function imprimir(){
        try {
            $nombre_impresora = "POS"; 
            $conection = new WindowsPrintConnector($nombre_impresora);
            $printer = new Printer($conection);
            $printer->text("Hola sumi" . "\n");
            $printer->text("Hola sumi" . "\n");
            $printer->text("Hola sumi" . "\n");
            $printer->text("Hola sumi" . "\n");
            $printer->text("Hola sumi" . "\n");
            $printer->text("Hola sumi" . "\n");
            $printer->text("Hola sumi" . "\n");
            $printer->cut();
            $printer->close();
        } catch (\Throwable $th) {
            return $th->getMessage(); //throw $th;
        }
    }

}
