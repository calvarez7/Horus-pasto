<?php
namespace App\Repositories;

use Illuminate\Http\Request;

class UserRepository
{
    public function getQueryLevelItemsOrder()
    {
        $levels = $this->getUserOrdenLevelByRoles();
        return $levels['allLevel'];
    }

    public function getOrderingLevelItemsOrder()
    {
        $levels = $this->getUserOrdenLevelByRoles();
        return $levels['maxLevel'];
    }

    private function getUserOrdenLevelByRoles()
    {
        if(auth()->user()->hasPermissionTo('order.audit-all-orders'))
        {
            return array(
                'maxLevel' => -1,
                'allLevel' => [0,1,2,3,4],
            );
        }

        $rolesUser = auth()->user()->getRoleNames()->toArray();

        $nivel0 = [
            'Auxiliar de Enfermeria',
            'Auxiliar Sede Tipo D',
            'Nutricion',
            'Psicologia',
            'Fisioterapia',
            'Optometria',
            'Terapia Respiratoria',
            'Fonoaudiologia',
            'Neuropsicologia',
            'Trabajo Social',
            'Linea de frente',
        ];
        $nivel1 = [
            'Medicina General',
            'Medicina Alternativa',
            'Odontologia',
            'Enfermeria',
            'Enfermeria Sede',
            'Medicina Laboral'
        ];
        $nivel2 = [
            'Medico Experto RCCVM',
            'Medico Experto Salud Mental',
            'Medico Experto Reumatologia',
            'Medico Experto Anticoagulados',
            'Medico Experto Nefroproteccion',
            'Medico Experto Respiratorios',
            'Medico Experto Trasmisibles Cronicas',
            'Psiquiatria',
            'Ginecologia',
            'Obstetricia',
            'Medicina Interna',
            'Otorrinolaringologia',
            'Oftalmologia',
            'Ortopedia y Traumatologia',
            'Cirugia General',
            'Pediatria',
            'Dermatologia',
            'Medicina del Deporte',
            'Toxicologia',
            'Fisiatria',
            'Urologia',
            'Especialidades de Odontologia',
            'Lider de Sede',
            'Cirugía Vascular'
        ];
        $nivel3 = [
            'Neurologia',
            'Cardiologia',
            'Anestesiologia',
            'Medicina Familiar',
            'Hematologia',
            'Nefrologia',
            'Endocrinologia',
            'Cirugia Coloproctologica',
            'Alergologia',
            'Mastologia',
            'Neumologia',
            'Medicina del Dolor',
            'Oncologia',
            'Neurocirugia',
            'Infectologia',
            'Reumatologia',
            'Electrofisiologia',
            'Gestion de Solicitudes',
            'Gestion de Solicitudes Odontologia',
            'Centro Regulador'
        ];
        $nivel4 = [
            'Tutelas',
            'Coordinacion Gestion de Solicitudes'
        ];

        foreach ($nivel4 as $nivel) {
            if (in_array($nivel, $rolesUser)) {
                return array(
                    'maxLevel' => 4,
                    'allLevel' => [0,1,2,3,4],
                );
            }
        }

        foreach ($nivel3 as $nivel) {
            if (in_array($nivel, $rolesUser)) {
                return array(
                    'maxLevel' => 3,
                    'allLevel' => [0,1,2,3],
                );
            }
        }

        foreach ($nivel2 as $nivel) {
            if (in_array($nivel, $rolesUser)) {
                return array(
                    'maxLevel' => 2,
                    'allLevel' => [0,1,2,3],
                );
            }
        }

        foreach ($nivel1 as $nivel) {
            if (in_array($nivel, $rolesUser)) {
                return array(
                    'maxLevel' => 1,
                    'allLevel' => [0,1,2,3],
                );
            }
        }

        foreach ($nivel0 as $nivel) {
            if (in_array($nivel, $rolesUser)) {
                return array(
                    'maxLevel' => 0,
                    'allLevel' => [0,1,2,3],
                );
            }
        }

        return array(
            'maxLevel' => -1,
            'allLevel' => [0],
        );
    }

}
