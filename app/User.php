<?php

namespace App;

use App\Repositories\UserRepository;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    use HasApiTokens, Notifiable, HasRoles;

    protected $guard_name = 'api';

    protected $fillable = [
        'name', 'apellido', 'direccion', 'departamento_id', 'celular', 'fecha_naci', 'cedula', 'nit', 'telefono', 'email', 'password', 'avatar',
        'Firma', 'Registromedico', 'estado_user', 'avatar_url', 'especialidad_medico','prestador_id'
    ];

    protected $hidden = [
        'password', 'created_at', 'updated_at', 'email_verified_at', 'remember_token',
    ];

    protected $appends = ['avatar_url'];

    public function getAvatarUrlAttribute()
    {
        return Storage::url('avatars/' . $this->id . '/' . $this->avatar);
    }

    public function estados()
    {
        return $this->belongsToMany('App\Modelos\Estado')->withPivot('User_id', 'Estado_id', 'Actualizado_por')->withTimestamps();
    }

    public function agendas()
    {
        return $this->hasMany('App\Modelos\Agenda');
    }

    public function messages()
    {
        return $this->hasMany('App\Modelos\Message');
    }

    public static function getRepository()
    {
        return new UserRepository();
    }

    public function responsables()
    {
        return $this->belongsToMany('App\Modelos\ActividadDesarrollo','resposables_desarrollos', 'user_id',  'actividad_desarrollo_id');
    }

}
