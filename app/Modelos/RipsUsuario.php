<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RipsUsuario extends Model
{
    use HasFactory;

    protected $table = "rips_usuarios";
    protected $guarded = [];
}
