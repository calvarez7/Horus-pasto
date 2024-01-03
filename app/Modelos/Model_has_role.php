<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Model_has_role extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    public $timestamps = false;

    protected $fillable = [
        'role_id', 'model_type', 'model_id',
    ];

    protected $table = 'model_has_roles';

}
