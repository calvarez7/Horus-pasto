<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface FiasInterface {

    public function consultar($data);
    //public function estructurar(Collection $collection) : String;

}