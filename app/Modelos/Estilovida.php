<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Estilovida extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['Citapaciente_id', 'Dietasaludable', 'Suenoreparador',
        'Duermemenoseishoras', 'Altonivelestres', 'Actividadfisica', 'Frecuenciasemana',
        'Duracion', 'Fumacantidad', 'Fumainicio', 'Fumadorpasivo', 'Cantidadlicor',
        'Licorfrecuencia', 'Consumopsicoactivo', 'Psicoactivocantidad', 'Estilovidaobservaciones',
        'checkboxDietasaludable','checkboxSuenoreparador','checkboxDuermemenoseishoras',
        'checkboxAltonivelestres','checkboxActividadfisica','checkboxFuma','checkboxTomalicor','frecuenciaActividad','checkboxtv',
        'Exposicion','juego','TipoSueno','DietaFrecuencia','DuracionSueno','Fumanos','tabaquico','riesgoEpoc','Bano', 'Bucal',
        'Posiciones','Esfinter','CaracteristicasOrina', 'esfinterRectal','checkboxesfinterRectal','leche','alimento','introduccionAlimentos','checkboxjuego','checkboxbano',
        'checkboxbucal','checkboxPosiciones','checkboxesfinter','checkboxOrina','checkboxConsumopsicoactivo',
        'checkboxexposicionhumo', 'expuestohumo',
        'expuesttopsicoactivos', 'anosexpuesto', 'checkboxexposicionpsicoactivos', 'anosexpuesto_sustancias','cuantas_comidas',
        'dieta_balanceada'

    ];

    public function citapaciente()
    {
        return $this->belongsTo('App\Modelos\citapaciente');
    }
}
