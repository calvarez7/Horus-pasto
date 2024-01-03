<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Model_has_permission extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public $timestamps = false;

    protected $fillable = [
        'permission_id', 'model_type', 'model_id',
    ];

    protected $table = 'model_has_permissions';

}
