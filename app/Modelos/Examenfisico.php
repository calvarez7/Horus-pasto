<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Examenfisico extends Model
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    protected $fillable = ['Citapaciente_id', 'Peso', 'Talla', 'Imc', 'ISC', 'Clasificacion', 'Perimetroabdominal',
        'Perimetrocefalico', 'Frecucardiaca', 'Pulsos', 'Frecurespiratoria', 'Temperatura',
        'Saturacionoxigeno', 'Posicion', 'Lateralidad', 'Presionsistolica', 'Presiondiastolica',
        'Presionarterialmedia', 'Condiciongeneral', 'Cabezacuello', 'Ojosfondojos', 'Agudezavisual',
        'Cardiopulmonar', 'Abdomen', 'Osteomuscular', 'Extremidades', 'Pulsosperifericos', 'Neurologico',
        'Reflejososteotendinos', 'Pielfraneras', 'Genitourinario', 'Examenmama', 'Tactoretal', 'Examenmental',
        'user_id', 'nota', 'tipo', 'tasa_filtracion','paciente_id','checkboxCcc','cabeza','Cara','Ojos','Selccc',
        'AgudezavisualDer','AgudezavisualIzq','Conjuntiva','Esclera','OjosfondojosAnt','OjosfondojosPost','Nariz',
        'Tabique', 'Cornetes','Oidos','AuricularDer','AuricularIzq','ConductoDer','MembranaTim','integra','perforacion',
        'TubosVen','Maxilar','LabiosComisura','MejillaCarrillo','CavidadOral','ArticulaciónTemporo', 'EstructurasDentales',
        'checkboxTorax','Torax','checkboxDesTorax','Mamas','Pectorales','RejaCostal','checkboxDesToraxPos','RejaCostalPos',
        'DesvCol','checkboxCardioPulmonar','Pulmones','Cardiacos','checkboxAbdomen','AlturaUterina','ActividadUterina',
        'FrecuenciaCardiacaFetal','movimientosFetales','RuidosPlacentarios','checkboxManiobra','PresentacionFetal',
        'NumFetos', 'PosUtero','Tacto','checkboxGenitoUrinario','Maculino','Testiculos','Escroto','Prepucio','Cordon',
        'TactoRectalHom','Femenino','Especuloscopia','TactoVag','Involucion','SangradoUter','ExpulTapon','Dilatacioncuello',
        'Borramiento','Estacion','loquios','Tactorecfem','TemTono','checkboxPerine','DesgarroPerine', 'Episiorragia',
        'checkboxExtremidades','checkboxSistemaNervioso','SistemaNervioso','ParesCraneales','EvaluacionMarcha',
        'EvaluacionTonoMuscular','EvaluacionFuerza','EvaluacionEsfera','checkboxPielFaneras', 'PielFaneras',
        'checkboxSistemaOsteo','SistemaOsteo','Cuello','FiO','created_at','PesoGanancia','Imcbebe','gananciaesperada','cuidado','escalaDesarrollo', 'autismo',
        'resultadoVale', 'actividades', 'comportamientos',  'autoeficacia','independencia','DorsoFetal',
        'actividadesProposito', 'controlInterno', 'funcionesEjecutivas','Identidad','valoracionIdentidad',
        'Autonomia','valoracionAutonomia','visualnino','problemaOido','escuchaBien','factoresOido',
        'riesgosMentalesNinos','lesionesAutoinflingidas', 'alteracionesGenitales',
        'tannerMasculino','alteracionesGenitalesExternos','tannerFemenino', 'tannerVello','motricidadGruesa',
        'motricidadFina','audicionLenguaje', 'personalSocial','funciones','desempeñocomunicativo', 'violencia_mental',
        'violencia_conflicto', 'violencia_sexual', 'rendimiento_escolar','test_figura_humana','columna_vertebral','circunferencia_brazo',
        'circunferencia_pantorrilla','peso_talla', 'clasificacion_peso_talla', 'talla_edad', 'clasificacion_talla_edad', 'cefalico_edad',
        'clasificacion_cefalico_edad', 'imc_edad','clasificacion_imc_edad',
        'peso_edad','clasificacion_peso_edad', 'examen_mental'
    ];

    public function citapaciente()
    {
        return $this->belongsTo('App\Modelos\citapaciente');
    }
}
