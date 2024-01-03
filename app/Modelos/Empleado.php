<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\EmpleadoRepository;

class Empleado extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'Estado_id', 'Documento', 'Nombre', 'Area', 'correo', 'celular', 'eps_id', 'area_id',
        'tipo_documento', 'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido',
         'fecha_nacimiento', 'lugar_nacimineto','edad','lugar_exp_documento','fecha_exp_documento',
         'nombre_documento','ruta_documento','genero','grupo_sanguineo','etnia','discapacidad',
         'tipo_discapacidad','cabeza_familia','estado_civil','estatura','peso','telefono',
         'celular2','correo_corporativo','direccion_residencia','barrio','tipo_vivienda',
         'estrato','municipio_id','contacto_emergencia','tel_contacto_emergencia','parentesco_contacto','nombre_foto','ruta_foto','salario'
    ];

    public static function getRepository(){
        return  new EmpleadoRepository();
    }

    public function familia()
    {
        return $this->hasMany(empleado_familia::class, 'empleado_id', 'id');
    }
    public function felicitacion(){
        return $this->hasMany(Felicitacione::class, 'empleado_id', 'id');
    }

    /** Scopes */
    public function scopeWhereEstado($query, $estado){
        if($estado){
            return $query->where('empleados.Estado_id', $estado);
        }
    }
}
