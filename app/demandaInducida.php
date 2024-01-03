<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class demandaInducida extends Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    protected $casts = [
        'fecha_dx_riesgocardiovascular' => 'date',
        'demanda_inducida_efectiva' => 'boolean'
    ];

    protected $guarded = [];
}
