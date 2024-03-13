<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RipsTransaccion extends Model
{
    use HasFactory;

    protected $table = "rips_transacciones";
    protected $guarded = [];
    protected $with = ['estado'];

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }
}
