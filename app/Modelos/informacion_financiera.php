<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class informacion_financiera extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $table = 'informacion_financieras';
    protected $guarded = [];
}
