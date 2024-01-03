<template>
    <div>
        <v-container grid-list-md fluid class="mt-2 pa-0">
            <v-layout row wrap>
                <v-flex xs2 style="border: solid #6495ed54">
                    <v-stepper v-if="e6" v-model="e6" :vertical="true">
                        <template v-for="(e, index) in elementos">
                            <v-stepper-step :key="`${e.id}-step`" :complete="e6 > e.id" :step="e.id" :editable="true"
                                @click="head(e.id,index)">
                                {{ e.nombre }}
                            </v-stepper-step>
                        </template>
                    </v-stepper>
                </v-flex>
                <v-flex xs10 style="border: solid #6495ed54">
                    <v-stepper v-if="e6" v-model="e6">
                        <template>
                            <v-stepper-items>
                                <v-stepper-content :key="`${step}-content`" :step="step">
                                    <component :is="comp" :datosCita="datosCita"
                                        @respuestaComponente="continuar(step)" />
                                </v-stepper-content>
                            </v-stepper-items>
                        </template>
                    </v-stepper>
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>

<script>
    import PacienteComponent from '../../components/paciente/Paciente.vue';
    import MotivoComponent from '../../components/historia_clinica/MotivoConsulta.vue';
    import RevisionSistemasComponent from '../../components/historia_clinica/RevisionSistemas.vue';
    import AntecedentesPersonalesComponent from '../../components/historia_clinica/AntecedentesPersonales.vue';
    // new componentes
    import AntecedentesTransfusiones from '../../components/historia_clinica/AntecedentesTransfusiones.vue';
    import AntecedentesQuirurgicosComponent from '../../components/historia_clinica/AntecedentesQuirurgicos.vue';
    import EstiloVidaComponent from '../../components/historia_clinica/EstiloVida.vue';
    import AntecedentesGinecologicosComponent from '../../components/historia_clinica/AntecedentesGinecologicos.vue';
    import EsquemaVacunacionComponent from '../../components/historia_clinica/EsquemaVacunacion.vue';
    import AntecedentesFamiliaresComponent from '../../components/historia_clinica/AntecedentesFamiliares.vue';
    import AntecedentesHospitalizacionComponent from '../../components/historia_clinica/AntecedentesHospitalizacion.vue';
    import RedesApoyoComponent from '../../components/historia_clinica/RedesApoyo.vue';
    import ApgarFamiliarComponent from '../../components/historia_clinica/ApgarFamiliar.vue';
    import FamiliogramaComponent from '../../components/historia_clinica/Familiograma.vue';
    import AntecedentesBiopsicosocialesComponent from '../../components/historia_clinica/AntecedentesBiopsicosociales.vue';
    import HabitosToxicosComponent from '../../components/historia_clinica/HabitosToxicos.vue';
    import ResultadosLaboratorioComponent from '../../components/historia_clinica/ResultadosLaboratorio.vue';
    import ResultadosAyudasDxComponent from '../../components/historia_clinica/ResultadosAyudasDx.vue';
    import DescripcionPatologicaComponent from '../../components/historia_clinica/DescripcionPatologica.vue';
    import BiomiOftalmoscopiaComponent from '../../components/historia_clinica/BiomiOftalmoscopia.vue';
    import RefraSubjetivoComponent from '../../components/historia_clinica/RefraccionSubjetivo.vue';
    import AntecedentesFarmacologicosComponent from '../../components/historia_clinica/AntecedentesFarmacologicos.vue';
    import AntecedenteQuimicoComponent from '../../components/historia_clinica/AntecedenteQuimico.vue';
    import ExamenFisicoComponent from '../../components/historia_clinica/ExamenFisico.vue';
    import ImpresionDxComponent from '../../components/historia_clinica/ImpresionDx.vue';
    import PlanCuidadoComponent from '../../components/historia_clinica/PlanCuidado.vue';
    import PlanManejoComponent from '../../components/historia_clinica/PlanManejo.vue';
    import AntecedentesFarmacologicoComponent from '../../components/historia_clinica/AntecedentesFarmacoterapeuticos.vue';
    import AntecedentesTraumaticosComponent from '../../components/historia_clinica/AntecedentesTraumaticos.vue'
    import EstadificacionRCVComponent from '../../components/historia_clinica/EstadificacionRCV.vue';
    import KarnofskiComponent from '../../components/historia_clinica/KarnosfskiComponent.vue';
    import EcogComponent from '../../components/historia_clinica/EcogComponent.vue';
    import EsasComponent from '../../components/historia_clinica/EsasComponent.vue';
    import IndiceBarthelComponent from '../../components/historia_clinica/IndiceBarthelComponent.vue';

    // INICIO DE COMPONENTES DE SALUD OCUPACIONAL
    import AnamnesisPsicologiaOcupacionalComponent from '../historia_ocupacional/PiscologiaOcupacional/AnamnesisPsicologia.vue';
    import AreasPsicologiaOcupacionalComponent from '../../components/historia_ocupacional/PiscologiaOcupacional/AreasPsicologia.vue';
    import DiagnosticosOcupacionalesComponent from '../historia_ocupacional/DiagnosticosOcupacionales.vue';
    import ConductaOcupacionalComponent from '../historia_ocupacional/ConductaOcupacional.vue';
    import AnamnesisVozOcupacionalComponent from '../../components/historia_ocupacional/VozOcupacional/AnamnesisVoz.vue';
    import AntecedentesPersonalesOcupacionalesComponent from '../historia_ocupacional/AntecedentesPersonalesOcupacionales.vue';
    import AntecedentesFamiliaresOcupacionalesComponent from '../historia_ocupacional/AntecedentesFamiliaresOcupacionales.vue';
    import RespiracionVozComponent from '../../components/historia_ocupacional/VozOcupacional/RespiracionVoz.vue';
    import CualidadesVozComponent from '../../components/historia_ocupacional/VozOcupacional/CualidadesVoz.vue';
    import ManejoPersonalVozComponent from '../../components/historia_ocupacional/VozOcupacional/ManejoPersonalVoz.vue';
    import ExamenFisicoVozComponent from '../../components/historia_ocupacional/VozOcupacional/ExamenFisicoVoz.vue';
    import AnamnesisVisiometriaComponent from '../../components/historia_ocupacional/VisiometriaOcupacional/AnamnesisVisiometria.vue';
    import ExamenVisiometriaComponent from '../../components/historia_ocupacional/VisiometriaOcupacional/ExamenVisiometria.vue';
    import AnamensisMedicaComponent from '../../components/historia_ocupacional/ConsulaMedicaOcupacional/AnamensisMedica.vue';
    import AntecedentesOcupacionalesMedicosComponent from '../../components/historia_ocupacional/ConsulaMedicaOcupacional/AntecedentesOcupacionalesMedicos.vue';
    import VacunasMedicasOcupacionalesComponent from '../../components/historia_ocupacional/ConsulaMedicaOcupacional/VacunasMedicasOcupacionales.vue';
    import GinecoobstetricosMedicaComponent from '../../components/historia_ocupacional/ConsulaMedicaOcupacional/GinecoobstetricosMedica.vue';
    import HabitosMedicaComponent from '../../components/historia_ocupacional/ConsulaMedicaOcupacional/HabitosMedica.vue';
    import RevisionSistemasMedicaComponent from '../../components/historia_ocupacional/ConsulaMedicaOcupacional/RevisionSistemasMedica.vue';
    import CondicionesSaludMedicaComponent from '../../components/historia_ocupacional/ConsulaMedicaOcupacional/CondicionesSaludMedica.vue';
    import ExamenFisicoMedicaComponent from '../../components/historia_ocupacional/ConsulaMedicaOcupacional/ExamenFisicoMedica.vue';
    import ConceptoLaboralMedicaComponent from '../../components/historia_ocupacional/ConsulaMedicaOcupacional/ConceptoLaboralMedica.vue';
    import PacientesSstComponent from '../../components/paciente/PacienteSst.vue'

    //INICIO DE COMPONENTES DE OPTOMETRIA
    import OptometriaComponent from '../historia_clinica/Optometria.vue';
    //INICIO DE COMPONENTES HISTORIAS GRUPALES
    //import OptometriaComponent from '../historia_grupales';
    //INICIO DE COMPONENTES DE OFTALMOLOGIA
    import OftalmologiaComponent from '../../components/historia_clinica/HistoriaOftalmologia.vue';
    //Inicio de componentes quimicos farmaceuticos
    import AntecedentesQuimicoFarmacologicoComponent from '../historia_clinica/AntecedentesQuimicoFarmacologico.vue';
    //procedimientos menores
    import ProcedimientosMenoresComponent from '../historia_clinica/ProcedimientosMenores.vue';
    //imagenologia
    import imagenologiaComponent from '../historia_clinica/Imagenologia.vue';

    export default {
        name: "Paciente",
        props: {
            datosCita: Object
        },
        components: {
            PacienteComponent,
            MotivoComponent,
            RevisionSistemasComponent,
            AntecedentesPersonalesComponent,
            AntecedentesTransfusiones,
            AntecedentesQuirurgicosComponent,
            AntecedentesGinecologicosComponent,
            EsquemaVacunacionComponent,
            AntecedentesFamiliaresComponent,
            AntecedentesHospitalizacionComponent,
            RedesApoyoComponent,
            ApgarFamiliarComponent,
            FamiliogramaComponent,
            AntecedentesBiopsicosocialesComponent,
            HabitosToxicosComponent,
            ResultadosLaboratorioComponent,
            ResultadosAyudasDxComponent,
            AntecedentesFarmacologicosComponent,
            ExamenFisicoComponent,
            AntecedenteQuimicoComponent,
            AntecedentesQuimicoFarmacologicoComponent,
            ImpresionDxComponent,
            PlanCuidadoComponent,
            PlanManejoComponent,
            AntecedentesFarmacologicoComponent,
            AntecedentesTraumaticosComponent,
            EstadificacionRCVComponent,
            KarnofskiComponent,
            EcogComponent,
            EsasComponent,
            IndiceBarthelComponent,
            // SALUD OCUPACIONAL
            AnamnesisPsicologiaOcupacionalComponent,
            AreasPsicologiaOcupacionalComponent,
            DiagnosticosOcupacionalesComponent,
            ConductaOcupacionalComponent,
            AnamnesisVozOcupacionalComponent,
            AntecedentesPersonalesOcupacionalesComponent,
            AntecedentesFamiliaresOcupacionalesComponent,
            RespiracionVozComponent,
            CualidadesVozComponent,
            ManejoPersonalVozComponent,
            ExamenFisicoVozComponent,
            AnamnesisVisiometriaComponent,
            ExamenVisiometriaComponent,
            AnamensisMedicaComponent,
            AntecedentesOcupacionalesMedicosComponent,
            VacunasMedicasOcupacionalesComponent,
            GinecoobstetricosMedicaComponent,
            HabitosMedicaComponent,
            RevisionSistemasMedicaComponent,
            CondicionesSaludMedicaComponent,
            ExamenFisicoMedicaComponent,
            ConceptoLaboralMedicaComponent,
            OptometriaComponent,
            DescripcionPatologicaComponent,
            BiomiOftalmoscopiaComponent,
            RefraSubjetivoComponent,
            EstiloVidaComponent,
            OftalmologiaComponent,
            ProcedimientosMenoresComponent,
            PacientesSstComponent,
            //imagenologia
            imagenologiaComponent
        },
        created() {
            switch (this.datosCita.Especialidad) {
                case "Examenes ocupacionales periódicos":
                case "Examenes ocupacionales post incapacidad":
                case "Examenes ocupacionales egreso":
                case "Examenes ocupacionales ingreso":
                case "Examenes ocupacionales reubicación":
                case "Examenes ocupacionales para participar en eventos deportivos":
                case "Examenes ocupacionales para participar en eventos folcloricos":
                    if (this.datosCita.salud_ocupacional == 'Psicologia') {
                        this.elementos = [{
                                id: 1,
                                nombre: 'Datos Paciente',
                                componente: 'PacientesSstComponent'
                            },
                            {
                                id: 2,
                                nombre: 'Atención',
                                componente: 'AnamnesisPsicologiaOcupacionalComponent'
                            },
                            {
                                id: 3,
                                nombre: 'Antecedentes Personales De Enfermedad Mental',
                                componente: 'AntecedentesPersonalesOcupacionalesComponent'
                            },
                            {
                                id: 4,
                                nombre: 'Antecedentes Familiares De Enfermedad Mental',
                                componente: 'AntecedentesFamiliaresOcupacionalesComponent'
                            },
                            {
                                id: 5,
                                nombre: 'Area Cognitiva - Procesos Cognitivos Superiores',
                                componente: 'AreasPsicologiaOcupacionalComponent'
                            },
                            {
                                id: 6,
                                nombre: 'Diagnósticos',
                                componente: 'ImpresionDxComponent'
                            },
                            {
                                id: 7,
                                nombre: 'Conducta Final',
                                componente: 'ConductaOcupacionalComponent'
                            }
                        ];
                        this.comp = 'PacientesSstComponent';
                        break;
                    }
                    if (this.datosCita.salud_ocupacional == 'Voz') {
                        this.elementos = [{
                                id: 1,
                                nombre: 'Datos Paciente',
                                componente: 'PacientesSstComponent'
                            },
                            {
                                id: 2,
                                nombre: 'Atencion',
                                componente: 'AnamnesisVozOcupacionalComponent'
                            },
                            {
                                id: 3,
                                nombre: 'Antecedentes Personales',
                                componente: 'AntecedentesPersonalesOcupacionalesComponent'
                            },
                            {
                                id: 4,
                                nombre: 'Antecedentes Familiares',
                                componente: 'AntecedentesFamiliaresOcupacionalesComponent'
                            },
                            {
                                id: 5,
                                nombre: 'Respiracion',
                                componente: 'RespiracionVozComponent'
                            },
                            {
                                id: 6,
                                nombre: 'Cualidades de la Voz',
                                componente: 'CualidadesVozComponent'
                            },
                            {
                                id: 7,
                                nombre: 'Manejo de Resonadores',
                                componente: 'ManejoPersonalVozComponent'
                            },
                            {
                                id: 8,
                                nombre: 'Manejo de Resonadores',
                                componente: 'ExamenFisicoVozComponent'
                            },
                            {
                                id: 9,
                                nombre: 'Diagnosticos',
                                componente: 'ImpresionDxComponent'
                            },
                            {
                                id: 10,
                                nombre: 'Conducta Final',
                                componente: 'ConductaOcupacionalComponent'
                            },
                        ];
                        this.comp = 'PacientesSstComponent';
                        break;
                    }
                    if (this.datosCita.salud_ocupacional == 'Visiometria') {
                        this.elementos = [{
                                id: 1,
                                nombre: 'Datos Paciente',
                                componente: 'PacientesSstComponent'
                            },
                            {
                                id: 2,
                                nombre: 'Atencion',
                                componente: 'AnamnesisVisiometriaComponent'
                            },
                            {
                                id: 3,
                                nombre: 'Antecedentes Personales',
                                componente: 'AntecedentesPersonalesOcupacionalesComponent'
                            },
                            {
                                id: 4,
                                nombre: 'Antecedentes Familiares',
                                componente: 'AntecedentesFamiliaresOcupacionalesComponent'
                            },
                            {
                                id: 5,
                                nombre: 'Examen Visiometria',
                                componente: 'ExamenVisiometriaComponent'
                            },
                            {
                                id: 6,
                                nombre: 'Diagnosticos',
                                componente: 'ImpresionDxComponent'
                            },
                            {
                                id: 7,
                                nombre: 'Conducta Final',
                                componente: 'ConductaOcupacionalComponent'
                            },
                        ];
                        this.comp = 'PacientesSstComponent';
                        break;
                    }
                    if (this.datosCita.salud_ocupacional == 'Consulta Medica') {
                        this.elementos = [{
                                id: 1,
                                nombre: 'Datos Paciente',
                                componente: 'PacientesSstComponent'
                            }, {
                                id: 2,
                                nombre: 'Atencion',
                                componente: 'AnamensisMedicaComponent'
                            },
                            {
                                id: 3,
                                nombre: 'Antecedentes Personales',
                                componente: 'AntecedentesPersonalesOcupacionalesComponent'
                            },
                            {
                                id: 4,
                                nombre: 'Antecedentes Familiares',
                                componente: 'AntecedentesFamiliaresOcupacionalesComponent'
                            }, {
                                id: 5,
                                nombre: 'Antecedentes Ocupacionales',
                                componente: 'AntecedentesOcupacionalesMedicosComponent'
                            },
                            {
                                id: 6,
                                nombre: 'Antecedentes Vacunas',
                                componente: 'VacunasMedicasOcupacionalesComponent'
                            },
                            {
                                id: 7,
                                nombre: 'Antecedentes Ginecostetricos',
                                componente: 'GinecoobstetricosMedicaComponent'
                            },
                            {
                                id: 8,
                                nombre: 'Habitos',
                                componente: 'HabitosMedicaComponent'
                            },
                            {
                                id: 9,
                                nombre: 'Revision Por Sistemas',
                                componente: 'RevisionSistemasMedicaComponent'
                            },
                            {
                                id: 10,
                                nombre: 'Condiciones de Salud',
                                componente: 'CondicionesSaludMedicaComponent'
                            },
                            {
                                id: 11,
                                nombre: 'Examen Fisico',
                                componente: 'ExamenFisicoMedicaComponent'
                            },
                            {
                                id: 12,
                                nombre: 'Diagnosticos',
                                componente: 'ImpresionDxComponent'
                            },
                            {
                                id: 13,
                                nombre: 'Conducta Final - Medica',
                                componente: 'ConceptoLaboralMedicaComponent'
                            },
                        ];
                        this.comp = 'PacientesSstComponent';
                        break;
                    }
                    case "Oftalmologia":
                        this.elementos = [{
                                id: 1,
                                nombre: 'Datos Paciente',
                                componente: 'PacienteComponent'
                            },
                            {
                                id: 2,
                                nombre: 'Motivo Consulta',
                                componente: 'MotivoComponent'
                            },
                            {
                                id: 3,
                                nombre: 'Antecedentes Personales',
                                componente: 'AntecedentesPersonalesOcupacionalesComponent'
                            },
                            {
                                id: 4,
                                nombre: 'Antecedentes Familiares',
                                componente: 'AntecedentesFamiliaresOcupacionalesComponent'
                            },
                            {
                                id: 5,
                                nombre: 'Oftalmologia',
                                componente: 'OftalmologiaComponent'
                            },
                            {
                                id: 6,
                                nombre: 'Diagnósticos',
                                componente: 'ImpresionDxComponent'
                            },
                            {
                                id: 7,
                                nombre: 'Plan de Manejo',
                                componente: 'PlanManejoComponent'
                            }
                        ]
                        this.comp = 'PacienteComponent';
                        break;
                    case "Optometria":
                        this.elementos = [{
                                id: 1,
                                nombre: 'Datos Paciente',
                                componente: 'PacienteComponent'
                            },
                            {
                                id: 2,
                                nombre: 'Motivo Consulta',
                                componente: 'MotivoComponent'
                            },
                            {
                                id: 3,
                                nombre: 'Antecedentes Personales',
                                componente: 'AntecedentesPersonalesComponent'
                            },
                            {
                                id: 4,
                                nombre: 'Antecedentes Familiares',
                                componente: 'AntecedentesFamiliaresComponent'
                            },
                            {
                                id: 5,
                                nombre: 'Lensometria - Agudeza Visual',
                                componente: 'OptometriaComponent'
                            },
                            {
                                id: 6,
                                nombre: 'Biomicroscopia - Oftalmoscopia',
                                componente: 'BiomiOftalmoscopiaComponent'
                            },
                            {
                                id: 7,
                                nombre: 'Refraccion - Subjetivo',
                                componente: 'RefraSubjetivoComponent'
                            },
                            {
                                id: 8,
                                nombre: 'Diagnósticos',
                                componente: 'ImpresionDxComponent'
                            },
                            {
                                id: 9,
                                nombre: 'Plan de Manejo',
                                componente: 'PlanManejoComponent'
                            }
                        ]
                        this.comp = 'PacienteComponent';
                        break;
                    case "Oncologia":
                        if (this.datosCita.sexo == "F") {
                            this.elementos = [{
                                    id: 1,
                                    nombre: 'Datos Paciente',
                                    componente: 'PacienteComponent'
                                },
                                {
                                    id: 2,
                                    nombre: 'Motivo Consulta',
                                    componente: 'MotivoComponent'
                                },
                                {
                                    id: 3,
                                    nombre: 'Revisión Sistemas',
                                    componente: 'RevisionSistemasComponent'
                                },
                                {
                                    id: 4,
                                    nombre: 'Antecedentes Personales',
                                    componente: 'AntecedentesPersonalesComponent'
                                },
                                {
                                    id: 5,
                                    nombre: 'Antecedentes Familiares',
                                    componente: 'AntecedentesFamiliaresComponent'
                                },
                                {
                                    id: 6,
                                    nombre: 'Antecedentes Ginecostetricos',
                                    componente: 'AntecedentesGinecologicosComponent'
                                },
                                {
                                    id: 7,
                                    nombre: 'Examen Fisicos',
                                    componente: 'ExamenFisicoComponent'
                                },
                                {
                                    id: 8,
                                    nombre: 'Descripcion Patologicas',
                                    componente: 'DescripcionPatologicaComponent'

                                },
                                {
                                    id: 9,
                                    nombre: 'Diagnósticos',
                                    componente: 'ImpresionDxComponent'
                                },
                                {
                                    id: 10,
                                    nombre: 'Plan de Manejo',
                                    componente: 'PlanManejoComponent'
                                }
                            ];
                            this.comp = 'PacienteComponent';
                            break;
                        } else if (this.datosCita.sexo == "M") {
                            this.elementos = [{
                                    id: 1,
                                    nombre: 'Datos Paciente',
                                    componente: 'PacienteComponent'
                                },
                                {
                                    id: 2,
                                    nombre: 'Motivo Consulta',
                                    componente: 'MotivoComponent'
                                },
                                {
                                    id: 3,
                                    nombre: 'Revisión Sistemas',
                                    componente: 'RevisionSistemasComponent'
                                },
                                {
                                    id: 4,
                                    nombre: 'Antecedentes Personales',
                                    componente: 'AntecedentesPersonalesComponent'
                                },
                                {
                                    id: 5,
                                    nombre: 'Antecedentes Familiares',
                                    componente: 'AntecedentesFamiliaresComponent'
                                },
                                {
                                    id: 6,
                                    nombre: 'Examen Fisicos',
                                    componente: 'ExamenFisicoComponent'
                                },
                                {
                                    id: 7,
                                    nombre: 'Descripcion Patologicas',
                                    componente: 'DescripcionPatologicaComponent'

                                },
                                {
                                    id: 8,
                                    nombre: 'Diagnósticos',
                                    componente: 'ImpresionDxComponent'
                                },
                                {
                                    id: 9,
                                    nombre: 'Plan de Manejo',
                                    componente: 'PlanManejoComponent'
                                }
                            ];
                            this.comp = 'PacienteComponent';
                            break;
                        }
                        case "Imagenologia": {
                            this.elementos = [{
                                    id: 1,
                                    nombre: 'Datos Paciente',
                                    componente: 'PacienteComponent'
                                },
                                {
                                    id: 2,
                                    nombre: 'Imagenologia',
                                    componente: 'imagenologiaComponent'
                                }
                            ]
                            this.comp = 'PacienteComponent';
                            break;
                        }
                        case "Medicina General":
                        case "Enfermeria":
                        case "Enfermeria Sede":
                        case "Auxiliar de Enfermeria":
                            if (this.datosCita.Tipo_agenda == '451') {
                                this.elementos = [{
                                        id: 1,
                                        nombre: 'Datos Paciente',
                                        componente: 'PacienteComponent'
                                    },
                                    {
                                        id: 2,
                                        nombre: 'Motivo Consulta',
                                        componente: 'MotivoComponent'
                                    },

                                    {
                                        id: 3,
                                        nombre: 'Impresión Diagnostica',
                                        componente: 'ImpresionDxComponent'
                                    },
                                    {
                                        id: 4,
                                        nombre: 'Plan de Manejo',
                                        componente: 'PlanManejoComponent'
                                    },
                                ]

                                this.comp = 'PacienteComponent';
                                break;
                            } else if (this.datosCita.Tipo_agenda == '23') {
                                this.elementos = [{
                                        id: 1,
                                        nombre: 'Datos Paciente',
                                        componente: 'PacienteComponent'
                                    },
                                    {
                                        id: 2,
                                        nombre: 'Motivo Consulta',
                                        componente: 'MotivoComponent'
                                    },
                                    {
                                        id: 3,
                                        nombre: 'Revisión Sistemas',
                                        componente: 'RevisionSistemasComponent'
                                    },
                                    {
                                        id: 4,
                                        nombre: 'Antecedentes Personales',
                                        componente: 'AntecedentesPersonalesComponent'
                                    },
                                    {
                                        id: 5,
                                        nombre: 'Antecedentes Familiares',
                                        componente: 'AntecedentesFamiliaresComponent'
                                    },
                                    {
                                        id: 6,
                                        nombre: 'Antecedentes Transfusionales',
                                        componente: 'AntecedentesTransfusiones'
                                    },
                                    {
                                        id: 7,
                                        nombre: 'Vacunacion',
                                        componente: 'EsquemaVacunacionComponent'
                                    },
                                    {
                                        id: 8,
                                        nombre: 'Antecedentes Quirugicos',
                                        componente: 'AntecedentesQuirurgicosComponent'
                                    },
                                    {
                                        id: 9,
                                        nombre: 'Antecedentes Alergicos',
                                        componente: 'AntecedentesFarmacologicosComponent'
                                    },
                                    {
                                        id: 10,
                                        nombre: 'Antecedentes Hospitalarios',
                                        componente: 'AntecedentesHospitalizacionComponent'
                                    },
                                    {
                                        id: 11,
                                        nombre: 'Antecedentes Ginecostetricos',
                                        componente: 'AntecedentesGinecologicosComponent'

                                    },
                                    {
                                        id: 12,
                                        nombre: 'Estilo de vida',
                                        componente: 'EstiloVidaComponent'
                                    },
                                    {
                                        id: 13,
                                        nombre: 'Antecedentes Biopsicosiciales',
                                        componente: 'AntecedentesBiopsicosocialesComponent'
                                    },
                                    {
                                        id: 14,
                                        nombre: 'Resultados de Laboratorios',
                                        componente: 'ResultadosLaboratorioComponent'
                                    },
                                    {
                                        id: 15,
                                        nombre: 'Examen fisico',
                                        componente: 'ExamenFisicoComponent'
                                    },
                                    {
                                        id: 16,
                                        nombre: 'Diagnostico de la Consulta',
                                        componente: 'ImpresionDxComponent'
                                    },
                                    {
                                        id: 17,
                                        nombre: 'Plan de Cuidado',
                                        componente: 'PlanCuidadoComponent'
                                    },
                                    {
                                        id: 18,
                                        nombre: 'Plan de Manejo',
                                        componente: 'PlanManejoComponent'
                                    }
                                ]

                                this.comp = 'PacienteComponent';
                                break;

                            } else if (this.datosCita.Tipo_agenda == '13' & this.datosCita.sexo == "F") {
                                this.elementos = [

                                    {
                                        id: 1,
                                        nombre: 'Datos Paciente',
                                        componente: 'PacienteComponent'
                                    },
                                    {
                                        id: 2,
                                        nombre: 'Motivo Consulta',
                                        componente: 'MotivoComponent'
                                    },
                                    {
                                        id: 3,
                                        nombre: 'Revisión Sistemas',
                                        componente: 'RevisionSistemasComponent'
                                    },
                                    {
                                        id: 4,
                                        nombre: 'Antecedentes Personales',
                                        componente: 'AntecedentesPersonalesComponent'
                                    },
                                    {
                                        id: 5,
                                        nombre: 'Antecedentes Familiares',
                                        componente: 'AntecedentesFamiliaresComponent'
                                    },
                                    {
                                        id: 6,
                                        nombre: 'Antecedentes Transfusionales',
                                        componente: 'AntecedentesTransfusiones'
                                    },
                                    {
                                        id: 7,
                                        nombre: 'Vacunacion',
                                        componente: 'EsquemaVacunacionComponent'
                                    },
                                    {
                                        id: 8,
                                        nombre: 'Antecedentes Quirugicos',
                                        componente: 'AntecedentesQuirurgicosComponent'
                                    },
                                    {
                                        id: 9,
                                        nombre: 'Antecedentes Alergicos',
                                        componente: 'AntecedentesFarmacologicosComponent'
                                    },
                                    {
                                        id: 10,
                                        nombre: 'Antecedentes Hospitalarios',
                                        componente: 'AntecedentesHospitalizacionComponent'
                                    },
                                    {
                                        id: 11,
                                        nombre: 'Antecedentes Ginecostetricos',
                                        componente: 'AntecedentesGinecologicosComponent'
                                    },
                                    {
                                        id: 12,
                                        nombre: 'Estilo de vida',
                                        componente: 'EstiloVidaComponent'
                                    },
                                    {
                                        id: 13,
                                        nombre: 'Antecedentes Biopsicosiciales',
                                        componente: 'AntecedentesBiopsicosocialesComponent'
                                    },
                                    {
                                        id: 14,
                                        nombre: 'Resultados de laboratorio',
                                        componente: 'resultadosLaboratorioComponent'
                                    },
                                    {
                                        id: 15,
                                        nombre: 'Examen fisico',
                                        componente: 'ExamenFisicoComponent'
                                    },
                                    {
                                        id: 16,
                                        nombre: 'Diagnostico de la Consulta',
                                        componente: 'ImpresionDxComponent'
                                    },
                                    {
                                        id: 17,
                                        nombre: 'Plan de Cuidado',
                                        componente: 'PlanCuidadoComponent'
                                    },
                                    {
                                        id: 18,
                                        nombre: 'Plan de Manejo',
                                        componente: 'PlanManejoComponent'
                                    },

                                ]

                                this.comp = 'PacienteComponent';
                                break;

                            } else if (this.datosCita.Tipo_agenda == '13' & this.datosCita.sexo == "M") {
                                this.elementos = [

                                    {
                                        id: 1,
                                        nombre: 'Datos Paciente',
                                        componente: 'PacienteComponent'
                                    },
                                    {
                                        id: 2,
                                        nombre: 'Motivo Consulta',
                                        componente: 'MotivoComponent'
                                    },
                                    {
                                        id: 3,
                                        nombre: 'Revisión Sistemas',
                                        componente: 'RevisionSistemasComponent'
                                    },
                                    {
                                        id: 4,
                                        nombre: 'Antecedentes Personales',
                                        componente: 'AntecedentesPersonalesComponent'
                                    },
                                    {
                                        id: 5,
                                        nombre: 'Antecedentes Familiares',
                                        componente: 'AntecedentesFamiliaresComponent'
                                    },
                                    {
                                        id: 6,
                                        nombre: 'Antecedentes Transfusionales',
                                        componente: 'AntecedentesTransfusiones'
                                    },
                                    {
                                        id: 7,
                                        nombre: 'Vacunacion',
                                        componente: 'EsquemaVacunacionComponent'
                                    },
                                    {
                                        id: 8,
                                        nombre: 'Antecedentes Quirugicos',
                                        componente: 'AntecedentesQuirurgicosComponent'
                                    },
                                    {
                                        id: 9,
                                        nombre: 'Antecedentes Alergicos',
                                        componente: 'AntecedentesFarmacologicosComponent'
                                    },
                                    {
                                        id: 10,
                                        nombre: 'Antecedentes Hospitalarios',
                                        componente: 'AntecedentesHospitalizacionComponent'
                                    },
                                    {
                                        id: 11,
                                        nombre: 'Estilo de vida',
                                        componente: 'EstiloVidaComponent'
                                    },
                                    {
                                        id: 12,
                                        nombre: 'Antecedentes Biopsicosiciales',
                                        componente: 'AntecedentesBiopsicosocialesComponent'
                                    },
                                    {
                                        id: 13,
                                        nombre: 'Resultados de laboratorio',
                                        componente: 'resultadosLaboratorioComponent'
                                    },
                                    {
                                        id: 14,
                                        nombre: 'Examen fisico',
                                        componente: 'ExamenFisicoComponent'
                                    },
                                    {
                                        id: 15,
                                        nombre: 'Diagnostico de la Consulta',
                                        componente: 'ImpresionDxComponent'
                                    },
                                    {
                                        id: 16,
                                        nombre: 'Plan de Cuidado',
                                        componente: 'PlanCuidadoComponent'
                                    },
                                    {
                                        id: 17,
                                        nombre: 'Plan de Manejo',
                                        componente: 'PlanManejoComponent'
                                    },
                                ]

                                this.comp = 'PacienteComponent';
                                break;

                            }
                            //PRIMERA INFANCIA
                            else if (this.datosCita.Tipo_agenda == '63' & this.datosCita.sexo == "F") {
                                this.elementos = [

                                    {
                                        id: 1,
                                        nombre: 'Datos Paciente',
                                        componente: 'PacienteComponent'
                                    },
                                    {
                                        id: 2,
                                        nombre: 'Motivo Consulta',
                                        componente: 'MotivoComponent'
                                    },
                                    {
                                        id: 3,
                                        nombre: 'Revisión Sistemas',
                                        componente: 'RevisionSistemasComponent'
                                    },
                                    {
                                        id: 4,
                                        nombre: 'Antecedentes Personales',
                                        componente: 'AntecedentesPersonalesComponent'
                                    },
                                    {
                                        id: 5,
                                        nombre: 'Antecedentes Familiares',
                                        componente: 'AntecedentesFamiliaresComponent'
                                    },
                                    {
                                        id: 6,
                                        nombre: 'Antecedentes Transfusionales',
                                        componente: 'AntecedentesTransfusiones'
                                    },
                                    {
                                        id: 7,
                                        nombre: 'Vacunacion',
                                        componente: 'EsquemaVacunacionComponent'
                                    },
                                    {
                                        id: 8,
                                        nombre: 'Antecedentes Quirugicos',
                                        componente: 'AntecedentesQuirurgicosComponent'
                                    },
                                    {
                                        id: 9,
                                        nombre: 'Antecedentes Alergicos',
                                        componente: 'AntecedentesFarmacologicosComponent'
                                    },
                                    {
                                        id: 10,
                                        nombre: 'Antecedentes Hospitalarios',
                                        componente: 'AntecedentesHospitalizacionComponent'
                                    },
                                    {
                                        id: 11,
                                        nombre: 'Estilo de vida',
                                        componente: 'EstiloVidaComponent'
                                    },
                                    {
                                        id: 12,
                                        nombre: 'Antecedentes Biopsicosiciales',
                                        componente: 'AntecedentesBiopsicosocialesComponent'
                                    },
                                    {
                                        id: 13,
                                        nombre: 'Resultados de laboratorio',
                                        componente: 'resultadosLaboratorioComponent'
                                    },
                                    {
                                        id: 14,
                                        nombre: 'Examen fisico',
                                        componente: 'ExamenFisicoComponent'
                                    },
                                    {
                                        id: 15,
                                        nombre: 'Diagnostico de la Consulta',
                                        componente: 'ImpresionDxComponent'
                                    },
                                    {
                                        id: 16,
                                        nombre: 'Plan de Cuidado',
                                        componente: 'PlanCuidadoComponent'
                                    },
                                    {
                                        id: 17,
                                        nombre: 'Plan de Manejo',
                                        componente: 'PlanManejoComponent'
                                    },
                                ]

                                this.comp = 'PacienteComponent';
                                break;

                            } else if (this.datosCita.Tipo_agenda == '63' & this.datosCita.sexo == "M") {
                                this.elementos = [

                                    {
                                        id: 1,
                                        nombre: 'Datos Paciente',
                                        componente: 'PacienteComponent'
                                    },
                                    {
                                        id: 2,
                                        nombre: 'Motivo Consulta',
                                        componente: 'MotivoComponent'
                                    },
                                    {
                                        id: 3,
                                        nombre: 'Revisión Sistemas',
                                        componente: 'RevisionSistemasComponent'
                                    },
                                    {
                                        id: 4,
                                        nombre: 'Antecedentes Personales',
                                        componente: 'AntecedentesPersonalesComponent'
                                    },
                                    {
                                        id: 5,
                                        nombre: 'Antecedentes Familiares',
                                        componente: 'AntecedentesFamiliaresComponent'
                                    },
                                    {
                                        id: 6,
                                        nombre: 'Antecedentes Transfusionales',
                                        componente: 'AntecedentesTransfusiones'
                                    },
                                    {
                                        id: 7,
                                        nombre: 'Vacunacion',
                                        componente: 'EsquemaVacunacionComponent'
                                    },
                                    {
                                        id: 8,
                                        nombre: 'Antecedentes Quirugicos',
                                        componente: 'AntecedentesQuirurgicosComponent'
                                    },
                                    {
                                        id: 9,
                                        nombre: 'Antecedentes Alergicos',
                                        componente: 'AntecedentesFarmacologicosComponent'
                                    },
                                    {
                                        id: 10,
                                        nombre: 'Antecedentes Hospitalarios',
                                        componente: 'AntecedentesHospitalizacionComponent'
                                    },
                                    {
                                        id: 11,
                                        nombre: 'Estilo de vida',
                                        componente: 'EstiloVidaComponent'
                                    },
                                    {
                                        id: 12,
                                        nombre: 'Antecedentes Biopsicosiciales',
                                        componente: 'AntecedentesBiopsicosocialesComponent'
                                    },
                                    {
                                        id: 13,
                                        nombre: 'Resultados de laboratorio',
                                        componente: 'resultadosLaboratorioComponent'
                                    },
                                    {
                                        id: 14,
                                        nombre: 'Examen fisico',
                                        componente: 'ExamenFisicoComponent'
                                    },
                                    {
                                        id: 15,
                                        nombre: 'Diagnostico de la Consulta',
                                        componente: 'ImpresionDxComponent'
                                    },
                                    {
                                        id: 16,
                                        nombre: 'Plan de Cuidado',
                                        componente: 'PlanCuidadoComponent'
                                    },
                                    {
                                        id: 17,
                                        nombre: 'Plan de Manejo',
                                        componente: 'PlanManejoComponent'
                                    },
                                ]

                                this.comp = 'PacienteComponent';
                                break;

                            }
                            //INFANCIA
                            else if (this.datosCita.Tipo_agenda == '26' & this.datosCita.sexo == "F") {
                                this.elementos = [

                                    {
                                        id: 1,
                                        nombre: 'Datos Paciente',
                                        componente: 'PacienteComponent'
                                    },
                                    {
                                        id: 2,
                                        nombre: 'Motivo Consulta',
                                        componente: 'MotivoComponent'
                                    },
                                    {
                                        id: 3,
                                        nombre: 'Revisión Sistemas',
                                        componente: 'RevisionSistemasComponent'
                                    },
                                    {
                                        id: 4,
                                        nombre: 'Antecedentes Personales',
                                        componente: 'AntecedentesPersonalesComponent'
                                    },
                                    {
                                        id: 5,
                                        nombre: 'Antecedentes Familiares',
                                        componente: 'AntecedentesFamiliaresComponent'
                                    },
                                    {
                                        id: 6,
                                        nombre: 'Antecedentes Transfusionales',
                                        componente: 'AntecedentesTransfusiones'
                                    },
                                    {
                                        id: 7,
                                        nombre: 'Vacunacion',
                                        componente: 'EsquemaVacunacionComponent'
                                    },
                                    {
                                        id: 8,
                                        nombre: 'Antecedentes Quirugicos',
                                        componente: 'AntecedentesQuirurgicosComponent'
                                    },
                                    {
                                        id: 9,
                                        nombre: 'Antecedentes Alergicos',
                                        componente: 'AntecedentesFarmacologicosComponent'
                                    },
                                    {
                                        id: 10,
                                        nombre: 'Antecedentes Hospitalarios',
                                        componente: 'AntecedentesHospitalizacionComponent'
                                    },
                                    {
                                        id: 11,
                                        nombre: 'Antecedentes Ginecostetricos',
                                        componente: 'AntecedentesGinecologicosComponent'
                                    },
                                    {
                                        id: 12,
                                        nombre: 'Estilo de vida',
                                        componente: 'EstiloVidaComponent'
                                    },
                                    {
                                        id: 13,
                                        nombre: 'Antecedentes Biopsicosiciales',
                                        componente: 'AntecedentesBiopsicosocialesComponent'
                                    },
                                    {
                                        id: 14,
                                        nombre: 'Resultados de laboratorio',
                                        componente: 'resultadosLaboratorioComponent'
                                    },
                                    {
                                        id: 15,
                                        nombre: 'Examen fisico',
                                        componente: 'ExamenFisicoComponent'
                                    },
                                    {
                                        id: 16,
                                        nombre: 'Diagnostico de la Consulta',
                                        componente: 'ImpresionDxComponent'
                                    },
                                    {
                                        id: 17,
                                        nombre: 'Plan de Cuidado',
                                        componente: 'PlanCuidadoComponent'
                                    },
                                    {
                                        id: 18,
                                        nombre: 'Plan de Manejo',
                                        componente: 'PlanManejoComponent'
                                    },
                                ]

                                this.comp = 'PacienteComponent';
                                break;

                            } else if (this.datosCita.Tipo_agenda == '26' & this.datosCita.sexo == "M") {
                                this.elementos = [

                                    {
                                        id: 1,
                                        nombre: 'Datos Paciente',
                                        componente: 'PacienteComponent'
                                    },
                                    {
                                        id: 2,
                                        nombre: 'Motivo Consulta',
                                        componente: 'MotivoComponent'
                                    },
                                    {
                                        id: 3,
                                        nombre: 'Revisión Sistemas',
                                        componente: 'RevisionSistemasComponent'
                                    },
                                    {
                                        id: 4,
                                        nombre: 'Antecedentes Personales',
                                        componente: 'AntecedentesPersonalesComponent'
                                    },
                                    {
                                        id: 5,
                                        nombre: 'Antecedentes Familiares',
                                        componente: 'AntecedentesFamiliaresComponent'
                                    },
                                    {
                                        id: 6,
                                        nombre: 'Antecedentes Transfusionales',
                                        componente: 'AntecedentesTransfusiones'
                                    },
                                    {
                                        id: 7,
                                        nombre: 'Vacunacion',
                                        componente: 'EsquemaVacunacionComponent'
                                    },
                                    {
                                        id: 8,
                                        nombre: 'Antecedentes Quirugicos',
                                        componente: 'AntecedentesQuirurgicosComponent'
                                    },
                                    {
                                        id: 9,
                                        nombre: 'Antecedentes Alergicos',
                                        componente: 'AntecedentesFarmacologicosComponent'
                                    },
                                    {
                                        id: 10,
                                        nombre: 'Antecedentes Hospitalarios',
                                        componente: 'AntecedentesHospitalizacionComponent'
                                    },
                                    {
                                        id: 11,
                                        nombre: 'Estilo de vida',
                                        componente: 'EstiloVidaComponent'
                                    },
                                    {
                                        id: 12,
                                        nombre: 'Antecedentes Biopsicosiciales',
                                        componente: 'AntecedentesBiopsicosocialesComponent'
                                    },
                                    {
                                        id: 13,
                                        nombre: 'Resultados de laboratorio',
                                        componente: 'resultadosLaboratorioComponent'
                                    },
                                    {
                                        id: 14,
                                        nombre: 'Examen fisico',
                                        componente: 'ExamenFisicoComponent'
                                    },
                                    {
                                        id: 15,
                                        nombre: 'Diagnostico de la Consulta',
                                        componente: 'ImpresionDxComponent'
                                    },
                                    {
                                        id: 16,
                                        nombre: 'Plan de Cuidado',
                                        componente: 'PlanCuidadoComponent'
                                    },
                                    {
                                        id: 17,
                                        nombre: 'Plan de Manejo',
                                        componente: 'PlanManejoComponent'
                                    },
                                ]

                                this.comp = 'PacienteComponent';
                                break;

                            }
                            //JUVENTUD
                            else if (this.datosCita.Tipo_agenda == '35' & this.datosCita.sexo == "F") {
                                this.elementos = [

                                    {
                                        id: 1,
                                        nombre: 'Datos Paciente',
                                        componente: 'PacienteComponent'
                                    },
                                    {
                                        id: 2,
                                        nombre: 'Motivo Consulta',
                                        componente: 'MotivoComponent'
                                    },
                                    {
                                        id: 3,
                                        nombre: 'Revisión Sistemas',
                                        componente: 'RevisionSistemasComponent'
                                    },
                                    {
                                        id: 4,
                                        nombre: 'Antecedentes Personales',
                                        componente: 'AntecedentesPersonalesComponent'
                                    },
                                    {
                                        id: 5,
                                        nombre: 'Antecedentes Familiares',
                                        componente: 'AntecedentesFamiliaresComponent'
                                    },
                                    {
                                        id: 6,
                                        nombre: 'Antecedentes Transfusionales',
                                        componente: 'AntecedentesTransfusiones'
                                    },
                                    {
                                        id: 7,
                                        nombre: 'Vacunacion',
                                        componente: 'EsquemaVacunacionComponent'
                                    },
                                    {
                                        id: 8,
                                        nombre: 'Antecedentes Quirugicos',
                                        componente: 'AntecedentesQuirurgicosComponent'
                                    },
                                    {
                                        id: 9,
                                        nombre: 'Antecedentes Alergicos',
                                        componente: 'AntecedentesFarmacologicosComponent'
                                    },
                                    {
                                        id: 10,
                                        nombre: 'Antecedentes Hospitalarios',
                                        componente: 'AntecedentesHospitalizacionComponent'
                                    },
                                    {
                                        id: 11,
                                        nombre: 'Antecedentes Ginecostetricos',
                                        componente: 'AntecedentesGinecologicosComponent'
                                    },
                                    {
                                        id: 12,
                                        nombre: 'Estilo de vida',
                                        componente: 'EstiloVidaComponent'
                                    },
                                    {
                                        id: 13,
                                        nombre: 'Antecedentes Biopsicosiciales',
                                        componente: 'AntecedentesBiopsicosocialesComponent'
                                    },
                                    {
                                        id: 14,
                                        nombre: 'Resultados de laboratorio',
                                        componente: 'resultadosLaboratorioComponent'
                                    },
                                    {
                                        id: 15,
                                        nombre: 'Examen fisico',
                                        componente: 'ExamenFisicoComponent'
                                    },
                                    {
                                        id: 16,
                                        nombre: 'Diagnostico de la Consulta',
                                        componente: 'ImpresionDxComponent'
                                    },
                                    {
                                        id: 17,
                                        nombre: 'Plan de Cuidado',
                                        componente: 'PlanCuidadoComponent'
                                    },
                                    {
                                        id: 18,
                                        nombre: 'Plan de Manejo',
                                        componente: 'PlanManejoComponent'
                                    },
                                ]

                                this.comp = 'PacienteComponent';
                                break;

                            } else if (this.datosCita.Tipo_agenda == '35' & this.datosCita.sexo == "M") {
                                this.elementos = [

                                    {
                                        id: 1,
                                        nombre: 'Datos Paciente',
                                        componente: 'PacienteComponent'
                                    },
                                    {
                                        id: 2,
                                        nombre: 'Motivo Consulta',
                                        componente: 'MotivoComponent'
                                    },
                                    {
                                        id: 3,
                                        nombre: 'Revisión Sistemas',
                                        componente: 'RevisionSistemasComponent'
                                    },
                                    {
                                        id: 4,
                                        nombre: 'Antecedentes Personales',
                                        componente: 'AntecedentesPersonalesComponent'
                                    },
                                    {
                                        id: 5,
                                        nombre: 'Antecedentes Familiares',
                                        componente: 'AntecedentesFamiliaresComponent'
                                    },
                                    {
                                        id: 6,
                                        nombre: 'Antecedentes Transfusionales',
                                        componente: 'AntecedentesTransfusiones'
                                    },
                                    {
                                        id: 7,
                                        nombre: 'Vacunacion',
                                        componente: 'EsquemaVacunacionComponent'
                                    },
                                    {
                                        id: 8,
                                        nombre: 'Antecedentes Quirugicos',
                                        componente: 'AntecedentesQuirurgicosComponent'
                                    },
                                    {
                                        id: 9,
                                        nombre: 'Antecedentes Alergicos',
                                        componente: 'AntecedentesFarmacologicosComponent'
                                    },
                                    {
                                        id: 10,
                                        nombre: 'Antecedentes Hospitalarios',
                                        componente: 'AntecedentesHospitalizacionComponent'
                                    },
                                    {
                                        id: 11,
                                        nombre: 'Estilo de vida',
                                        componente: 'EstiloVidaComponent'
                                    },
                                    {
                                        id: 12,
                                        nombre: 'Antecedentes Biopsicosiciales',
                                        componente: 'AntecedentesBiopsicosocialesComponent'
                                    },
                                    {
                                        id: 13,
                                        nombre: 'Resultados de laboratorio',
                                        componente: 'resultadosLaboratorioComponent'
                                    },
                                    {
                                        id: 14,
                                        nombre: 'Examen fisico',
                                        componente: 'ExamenFisicoComponent'
                                    },
                                    {
                                        id: 15,
                                        nombre: 'Diagnostico de la Consulta',
                                        componente: 'ImpresionDxComponent'
                                    },
                                    {
                                        id: 16,
                                        nombre: 'Plan de Cuidado',
                                        componente: 'PlanCuidadoComponent'
                                    },
                                    {
                                        id: 17,
                                        nombre: 'Plan de Manejo',
                                        componente: 'PlanManejoComponent'
                                    },
                                ]

                                this.comp = 'PacienteComponent';
                                break;

                            }
                            // ADULTO
                            else if (this.datosCita.sexo == 'F' & this.datosCita.Tipo_agenda == '9' || this.datosCita
                                .Tipo_agenda == '501') {
                                this.elementos = [

                                    {
                                        id: 1,
                                        nombre: 'Datos Paciente',
                                        componente: 'PacienteComponent'
                                    },
                                    {
                                        id: 2,
                                        nombre: 'Motivo Consulta',
                                        componente: 'MotivoComponent'
                                    },
                                    {
                                        id: 3,
                                        nombre: 'Revisión Sistemas',
                                        componente: 'RevisionSistemasComponent'
                                    },
                                    {
                                        id: 4,
                                        nombre: 'Antecedentes Personales',
                                        componente: 'AntecedentesPersonalesComponent'
                                    },
                                    {
                                        id: 5,
                                        nombre: 'Antecedentes Familiares',
                                        componente: 'AntecedentesFamiliaresComponent'
                                    },
                                    {
                                        id: 6,
                                        nombre: 'Antecedentes Transfusionales',
                                        componente: 'AntecedentesTransfusiones'
                                    },
                                    {
                                        id: 7,
                                        nombre: 'Vacunacion',
                                        componente: 'EsquemaVacunacionComponent'
                                    },
                                    {
                                        id: 8,
                                        nombre: 'Antecedentes Quirugicos',
                                        componente: 'AntecedentesQuirurgicosComponent'
                                    },
                                    {
                                        id: 9,
                                        nombre: 'Antecedentes Alergicos',
                                        componente: 'AntecedentesFarmacologicosComponent'
                                    },
                                    {
                                        id: 10,
                                        nombre: 'Antecedentes Hospitalarios',
                                        componente: 'AntecedentesHospitalizacionComponent'
                                    },
                                    {
                                        id: 11,
                                        nombre: 'Antecedentes Ginecostetricos',
                                        componente: 'AntecedentesGinecologicosComponent'
                                    },
                                    {
                                        id: 12,
                                        nombre: 'Estilo de vida',
                                        componente: 'EstiloVidaComponent'
                                    },
                                    {
                                        id: 13,
                                        nombre: 'Antecedentes Biopsicosiciales',
                                        componente: 'AntecedentesBiopsicosocialesComponent'
                                    },
                                    {
                                        id: 14,
                                        nombre: 'Resultados de laboratorio',
                                        componente: 'resultadosLaboratorioComponent'
                                    },
                                    {
                                        id: 15,
                                        nombre: 'Examen fisico',
                                        componente: 'ExamenFisicoComponent'
                                    },
                                    {
                                        id: 16,
                                        nombre: 'Diagnostico de la Consulta',
                                        componente: 'ImpresionDxComponent'
                                    },
                                    {
                                        id: 17,
                                        nombre: 'Plan de Cuidado',
                                        componente: 'PlanCuidadoComponent'
                                    },
                                    {
                                        id: 18,
                                        nombre: 'Plan de Manejo',
                                        componente: 'PlanManejoComponent'
                                    },
                                ]

                                this.comp = 'PacienteComponent';
                                break;

                            } else if (this.datosCita.sexo == 'M' & this.datosCita.Tipo_agenda == '9' || this.datosCita
                                .Tipo_agenda == '501') {
                                this.elementos = [

                                    {
                                        id: 1,
                                        nombre: 'Datos Paciente',
                                        componente: 'PacienteComponent'
                                    },
                                    {
                                        id: 2,
                                        nombre: 'Motivo Consulta',
                                        componente: 'MotivoComponent'
                                    },
                                    {
                                        id: 3,
                                        nombre: 'Revisión Sistemas',
                                        componente: 'RevisionSistemasComponent'
                                    },
                                    {
                                        id: 4,
                                        nombre: 'Antecedentes Personales',
                                        componente: 'AntecedentesPersonalesComponent'
                                    },
                                    {
                                        id: 5,
                                        nombre: 'Antecedentes Familiares',
                                        componente: 'AntecedentesFamiliaresComponent'
                                    },
                                    {
                                        id: 6,
                                        nombre: 'Antecedentes Transfusionales',
                                        componente: 'AntecedentesTransfusiones'
                                    },
                                    {
                                        id: 7,
                                        nombre: 'Vacunacion',
                                        componente: 'EsquemaVacunacionComponent'
                                    },
                                    {
                                        id: 8,
                                        nombre: 'Antecedentes Quirugicos',
                                        componente: 'AntecedentesQuirurgicosComponent'
                                    },
                                    {
                                        id: 9,
                                        nombre: 'Antecedentes Alergicos',
                                        componente: 'AntecedentesFarmacologicosComponent'
                                    },
                                    {
                                        id: 10,
                                        nombre: 'Antecedentes Hospitalarios',
                                        componente: 'AntecedentesHospitalizacionComponent'
                                    },
                                    {
                                        id: 11,
                                        nombre: 'Estilo de vida',
                                        componente: 'EstiloVidaComponent'
                                    },
                                    {
                                        id: 12,
                                        nombre: 'Antecedentes Biopsicosiciales',
                                        componente: 'AntecedentesBiopsicosocialesComponent'
                                    },
                                    {
                                        id: 13,
                                        nombre: 'Resultados de laboratorio',
                                        componente: 'resultadosLaboratorioComponent'
                                    },
                                    {
                                        id: 14,
                                        nombre: 'Examen fisico',
                                        componente: 'ExamenFisicoComponent'
                                    },
                                    {
                                        id: 15,
                                        nombre: 'Diagnostico de la Consulta',
                                        componente: 'ImpresionDxComponent'
                                    },
                                    {
                                        id: 16,
                                        nombre: 'Plan de Cuidado',
                                        componente: 'PlanCuidadoComponent'
                                    },
                                    {
                                        id: 17,
                                        nombre: 'Plan de Manejo',
                                        componente: 'PlanManejoComponent'
                                    },
                                ]

                                this.comp = 'PacienteComponent';
                                break;

                            }

                            //VEJEZ
                            else if (this.datosCita.sexo == 'F' & this.datosCita.Tipo_agenda == '526' || this.datosCita
                                .Tipo_agenda == '16') {
                                this.elementos = [

                                    {
                                        id: 1,
                                        nombre: 'Datos Paciente',
                                        componente: 'PacienteComponent'
                                    },
                                    {
                                        id: 2,
                                        nombre: 'Motivo Consulta',
                                        componente: 'MotivoComponent'
                                    },
                                    {
                                        id: 3,
                                        nombre: 'Revisión Sistemas',
                                        componente: 'RevisionSistemasComponent'
                                    },
                                    {
                                        id: 4,
                                        nombre: 'Antecedentes Personales',
                                        componente: 'AntecedentesPersonalesComponent'
                                    },
                                    {
                                        id: 5,
                                        nombre: 'Antecedentes Familiares',
                                        componente: 'AntecedentesFamiliaresComponent'
                                    },
                                    {
                                        id: 6,
                                        nombre: 'Antecedentes Transfusionales',
                                        componente: 'AntecedentesTransfusiones'
                                    },
                                    {
                                        id: 7,
                                        nombre: 'Vacunacion',
                                        componente: 'EsquemaVacunacionComponent'
                                    },
                                    {
                                        id: 8,
                                        nombre: 'Antecedentes Quirugicos',
                                        componente: 'AntecedentesQuirurgicosComponent'
                                    },
                                    {
                                        id: 9,
                                        nombre: 'Antecedentes Alergicos',
                                        componente: 'AntecedentesFarmacologicosComponent'
                                    },
                                    {
                                        id: 10,
                                        nombre: 'Antecedentes Hospitalarios',
                                        componente: 'AntecedentesHospitalizacionComponent'
                                    },
                                    {
                                        id: 11,
                                        nombre: 'Antecedentes Ginecostetricos',
                                        componente: 'AntecedentesGinecologicosComponent'
                                    },
                                    {
                                        id: 12,
                                        nombre: 'Estilo de vida',
                                        componente: 'EstiloVidaComponent'
                                    },
                                    {
                                        id: 13,
                                        nombre: 'Antecedentes Biopsicosiciales',
                                        componente: 'AntecedentesBiopsicosocialesComponent'
                                    },
                                    {
                                        id: 14,
                                        nombre: 'Resultados de laboratorio',
                                        componente: 'resultadosLaboratorioComponent'
                                    },
                                    {
                                        id: 15,
                                        nombre: 'Examen fisico',
                                        componente: 'ExamenFisicoComponent'
                                    },
                                    {
                                        id: 16,
                                        nombre: 'Diagnostico de la Consulta',
                                        componente: 'ImpresionDxComponent'
                                    },
                                    {
                                        id: 17,
                                        nombre: 'Plan de Cuidado',
                                        componente: 'PlanCuidadoComponent'
                                    },
                                    {
                                        id: 18,
                                        nombre: 'Plan de Manejo',
                                        componente: 'PlanManejoComponent'
                                    },
                                ]

                                this.comp = 'PacienteComponent';
                                break;

                            } else if (this.datosCita.sexo == 'F' & this.datosCita.Tipo_agenda == '526' || this
                                .datosCita.Tipo_agenda == '16') {
                                this.elementos = [

                                    {
                                        id: 1,
                                        nombre: 'Datos Paciente',
                                        componente: 'PacienteComponent'
                                    },
                                    {
                                        id: 2,
                                        nombre: 'Motivo Consulta',
                                        componente: 'MotivoComponent'
                                    },
                                    {
                                        id: 3,
                                        nombre: 'Revisión Sistemas',
                                        componente: 'RevisionSistemasComponent'
                                    },
                                    {
                                        id: 4,
                                        nombre: 'Antecedentes Personales',
                                        componente: 'AntecedentesPersonalesComponent'
                                    },
                                    {
                                        id: 5,
                                        nombre: 'Antecedentes Familiares',
                                        componente: 'AntecedentesFamiliaresComponent'
                                    },
                                    {
                                        id: 6,
                                        nombre: 'Antecedentes Transfusionales',
                                        componente: 'AntecedentesTransfusiones'
                                    },
                                    {
                                        id: 7,
                                        nombre: 'Vacunacion',
                                        componente: 'EsquemaVacunacionComponent'
                                    },
                                    {
                                        id: 8,
                                        nombre: 'Antecedentes Quirugicos',
                                        componente: 'AntecedentesQuirurgicosComponent'
                                    },
                                    {
                                        id: 9,
                                        nombre: 'Antecedentes Alergicos',
                                        componente: 'AntecedentesFarmacologicosComponent'
                                    },
                                    {
                                        id: 10,
                                        nombre: 'Antecedentes Hospitalarios',
                                        componente: 'AntecedentesHospitalizacionComponent'
                                    },
                                    {
                                        id: 11,
                                        nombre: 'Estilo de vida',
                                        componente: 'EstiloVidaComponent'
                                    },
                                    {
                                        id: 12,
                                        nombre: 'Antecedentes Biopsicosiciales',
                                        componente: 'AntecedentesBiopsicosocialesComponent'
                                    },
                                    {
                                        id: 13,
                                        nombre: 'Resultados de laboratorio',
                                        componente: 'resultadosLaboratorioComponent'
                                    },
                                    {
                                        id: 14,
                                        nombre: 'Examen fisico',
                                        componente: 'ExamenFisicoComponent'
                                    },
                                    {
                                        id: 15,
                                        nombre: 'Diagnostico de la Consulta',
                                        componente: 'ImpresionDxComponent'
                                    },
                                    {
                                        id: 16,
                                        nombre: 'Plan de Cuidado',
                                        componente: 'PlanCuidadoComponent'
                                    },
                                    {
                                        id: 17,
                                        nombre: 'Plan de Manejo',
                                        componente: 'PlanManejoComponent'
                                    },
                                ]

                                this.comp = 'PacienteComponent';
                                break;

                            } else if (this.datosCita.Tipo_agenda == '16' || this.datosCita.Tipo_agenda == '526' & this
                                .datosCita.sexo == "M") {
                                this.elementos = [

                                    {
                                        id: 1,
                                        nombre: 'Datos Paciente',
                                        componente: 'PacienteComponent'
                                    },
                                    {
                                        id: 2,
                                        nombre: 'Motivo Consulta',
                                        componente: 'MotivoComponent'
                                    },
                                    {
                                        id: 3,
                                        nombre: 'Revisión Sistemas',
                                        componente: 'RevisionSistemasComponent'
                                    },
                                    {
                                        id: 4,
                                        nombre: 'Antecedentes Personales',
                                        componente: 'AntecedentesPersonalesComponent'
                                    },
                                    {
                                        id: 5,
                                        nombre: 'Antecedentes Familiares',
                                        componente: 'AntecedentesFamiliaresComponent'
                                    },
                                    {
                                        id: 6,
                                        nombre: 'Antecedentes Transfusionales',
                                        componente: 'AntecedentesTransfusiones'
                                    },
                                    {
                                        id: 7,
                                        nombre: 'Vacunacion',
                                        componente: 'EsquemaVacunacionComponent'
                                    },
                                    {
                                        id: 8,
                                        nombre: 'Antecedentes Quirugicos',
                                        componente: 'AntecedentesQuirurgicosComponent'
                                    },
                                    {
                                        id: 9,
                                        nombre: 'Antecedentes Alergicos',
                                        componente: 'AntecedentesFarmacologicosComponent'
                                    },
                                    {
                                        id: 10,
                                        nombre: 'Antecedentes Hospitalarios',
                                        componente: 'AntecedentesHospitalizacionComponent'
                                    },
                                    {
                                        id: 11,
                                        nombre: 'Estilo de vida',
                                        componente: 'EstiloVidaComponent'
                                    },
                                    {
                                        id: 12,
                                        nombre: 'Antecedentes Biopsicosiciales',
                                        componente: 'AntecedentesBiopsicosocialesComponent'
                                    },
                                    {
                                        id: 13,
                                        nombre: 'Resultados de laboratorio',
                                        componente: 'resultadosLaboratorioComponent'
                                    },
                                    {
                                        id: 14,
                                        nombre: 'Examen fisico',
                                        componente: 'ExamenFisicoComponent'
                                    },
                                    {
                                        id: 15,
                                        nombre: 'Diagnostico de la Consulta',
                                        componente: 'ImpresionDxComponent'
                                    },
                                    {
                                        id: 16,
                                        nombre: 'Plan de Cuidado',
                                        componente: 'PlanCuidadoComponent'
                                    },
                                    {
                                        id: 17,
                                        nombre: 'Plan de Manejo',
                                        componente: 'PlanManejoComponent'
                                    },
                                ]

                                this.comp = 'PacienteComponent';
                                break;

                            } else if (this.datosCita.sexo == 'F' && this.datosCita.Tipo_agenda == '181') {
                                this.elementos = [{
                                        id: 1,
                                        nombre: 'Datos Paciente',
                                        componente: 'PacienteComponent'
                                    },
                                    {
                                        id: 2,
                                        nombre: 'Motivo Consulta',
                                        componente: 'MotivoComponent'
                                    },
                                    {
                                        id: 3,
                                        nombre: 'Revisión Sistemas',
                                        componente: 'RevisionSistemasComponent'
                                    },
                                    {
                                        id: 4,
                                        nombre: 'Antecedentes Personales',
                                        componente: 'AntecedentesPersonalesComponent'
                                    },
                                    {
                                        id: 5,
                                        nombre: 'Antecedentes Hospitalarios',
                                        componente: 'AntecedentesHospitalizacionComponent'
                                    },
                                    {
                                        id: 6,
                                        nombre: 'Vacunacion',
                                        componente: 'EsquemaVacunacionComponent'
                                    },
                                    {
                                        id: 7,
                                        nombre: 'Antecedentes Quirugicos',
                                        componente: 'AntecedentesQuirurgicosComponent'
                                    },
                                    {
                                        id: 8,
                                        nombre: 'Antecedentes Familiares',
                                        componente: 'AntecedentesFamiliaresComponent'
                                    },
                                    {
                                        id: 9,
                                        nombre: 'Habitos',
                                        componente: 'EstiloVidaComponent'
                                    },
                                    {
                                        id: 10,
                                        nombre: 'Examen Fisico',
                                        componente: 'ExamenFisicoComponent'
                                    },
                                    {
                                        id: 11,
                                        nombre: 'Antecedentes Ginecostetricos',
                                        componente: 'AntecedentesGinecologicosComponent'
                                    },
                                    {
                                        id: 12,
                                        nombre: 'Estadificacion Riesgo Cardiovascular',
                                        componente: 'EstadificacionRCVComponent'
                                    },
                                    {
                                        id: 13,
                                        nombre: 'Impresión Diagnostica',
                                        componente: 'ImpresionDxComponent'
                                    },
                                    {
                                        id: 14,
                                        nombre: 'Plan y Manejo',
                                        componente: 'PlanManejoComponent'
                                    }
                                ]
                                this.comp = 'PacienteComponent';
                                break;
                            } else if (this.datosCita.sexo == 'M' && this.datosCita.Tipo_agenda == '181') {
                                this.elementos = [{
                                        id: 1,
                                        nombre: 'Datos Paciente',
                                        componente: 'PacienteComponent'
                                    },
                                    {
                                        id: 2,
                                        nombre: 'Motivo Consulta',
                                        componente: 'MotivoComponent'
                                    },
                                    {
                                        id: 3,
                                        nombre: 'Revisión Sistemas',
                                        componente: 'RevisionSistemasComponent'
                                    },
                                    {
                                        id: 4,
                                        nombre: 'Antecedentes Personales',
                                        componente: 'AntecedentesPersonalesComponent'
                                    },
                                    {
                                        id: 5,
                                        nombre: 'Antecedentes Hospitalarios',
                                        componente: 'AntecedentesHospitalizacionComponent'
                                    },
                                    {
                                        id: 6,
                                        nombre: 'Vacunacion',
                                        componente: 'EsquemaVacunacionComponent'
                                    },
                                    {
                                        id: 7,
                                        nombre: 'Antecedentes Quirugicos',
                                        componente: 'AntecedentesQuirurgicosComponent'
                                    },
                                    {
                                        id: 8,
                                        nombre: 'Antecedentes Familiares',
                                        componente: 'AntecedentesFamiliaresComponent'
                                    },
                                    {
                                        id: 9,
                                        nombre: 'Habitos',
                                        componente: 'EstiloVidaComponent'
                                    },
                                    {
                                        id: 10,
                                        nombre: 'Examen Fisico',
                                        componente: 'ExamenFisicoComponent'
                                    },
                                    {
                                        id: 11,
                                        nombre: 'Estadificacion Riesgo Cardiovascular',
                                        componente: 'EstadificacionRCVComponent'
                                    },
                                    {
                                        id: 12,
                                        nombre: 'Impresión Diagnostica',
                                        componente: 'ImpresionDxComponent'
                                    },
                                    {
                                        id: 13,
                                        nombre: 'Plan y Manejo',
                                        componente: 'PlanManejoComponent'
                                    }
                                ]
                                this.comp = 'PacienteComponent';
                                break;
                            } else if (this.datosCita.sexo == 'F' && this.datosCita.Tipo_agenda == '4') {
                                this.elementos = [{
                                        id: 1,
                                        nombre: 'Datos Paciente',
                                        componente: 'PacienteComponent'
                                    },
                                    {
                                        id: 2,
                                        nombre: 'Motivo Consulta',
                                        componente: 'MotivoComponent'
                                    },
                                    {
                                        id: 3,
                                        nombre: 'Revisión Sistemas',
                                        componente: 'RevisionSistemasComponent'
                                    },
                                    {
                                        id: 4,
                                        nombre: 'Antecedentes Personales',
                                        componente: 'AntecedentesPersonalesComponent'
                                    },
                                    {
                                        id: 5,
                                        nombre: 'Antecedentes Hospitalarios',
                                        componente: 'AntecedentesHospitalizacionComponent'
                                    },
                                    {
                                        id: 6,
                                        nombre: 'Vacunacion',
                                        componente: 'EsquemaVacunacionComponent'
                                    },
                                    {
                                        id: 7,
                                        nombre: 'Antecedentes Quirugicos',
                                        componente: 'AntecedentesQuirurgicosComponent'
                                    },
                                    {
                                        id: 8,
                                        nombre: 'Antecedentes Familiares',
                                        componente: 'AntecedentesFamiliaresComponent'
                                    },
                                    {
                                        id: 9,
                                        nombre: 'Habitos',
                                        componente: 'EstiloVidaComponent'
                                    },
                                    {
                                        id: 10,
                                        nombre: 'Examen Fisico',
                                        componente: 'ExamenFisicoComponent'
                                    },
                                    {
                                        id: 11,
                                        nombre: 'Antecedentes Ginecostetricos',
                                        componente: 'AntecedentesGinecologicosComponent'
                                    },
                                    {
                                        id: 12,
                                        nombre: 'Estadificacion Riesgo Cardiovascular',
                                        componente: 'EstadificacionRCVComponent'
                                    },
                                    {
                                        id: 13,
                                        nombre: 'Impresión Diagnostica',
                                        componente: 'ImpresionDxComponent'
                                    },
                                    {
                                        id: 14,
                                        nombre: 'Plan y Manejo',
                                        componente: 'PlanManejoComponent'
                                    }
                                ]
                                this.comp = 'PacienteComponent';
                                break;
                            } else if (this.datosCita.sexo == 'M' && this.datosCita.Tipo_agenda == '4') {
                                this.elementos = [{
                                        id: 1,
                                        nombre: 'Datos Paciente',
                                        componente: 'PacienteComponent'
                                    },
                                    {
                                        id: 2,
                                        nombre: 'Motivo Consulta',
                                        componente: 'MotivoComponent'
                                    },
                                    {
                                        id: 3,
                                        nombre: 'Revisión Sistemas',
                                        componente: 'RevisionSistemasComponent'
                                    },
                                    {
                                        id: 4,
                                        nombre: 'Antecedentes Personales',
                                        componente: 'AntecedentesPersonalesComponent'
                                    },
                                    {
                                        id: 5,
                                        nombre: 'Antecedentes Hospitalarios',
                                        componente: 'AntecedentesHospitalizacionComponent'
                                    },
                                    {
                                        id: 6,
                                        nombre: 'Vacunacion',
                                        componente: 'EsquemaVacunacionComponent'
                                    },
                                    {
                                        id: 7,
                                        nombre: 'Antecedentes Quirugicos',
                                        componente: 'AntecedentesQuirurgicosComponent'
                                    },
                                    {
                                        id: 8,
                                        nombre: 'Antecedentes Familiares',
                                        componente: 'AntecedentesFamiliaresComponent'
                                    },
                                    {
                                        id: 9,
                                        nombre: 'Habitos',
                                        componente: 'EstiloVidaComponent'
                                    },
                                    {
                                        id: 10,
                                        nombre: 'Examen Fisico',
                                        componente: 'ExamenFisicoComponent'
                                    },
                                    {
                                        id: 11,
                                        nombre: 'Estadificacion Riesgo Cardiovascular',
                                        componente: 'EstadificacionRCVComponent'
                                    },
                                    {
                                        id: 12,
                                        nombre: 'Impresión Diagnostica',
                                        componente: 'ImpresionDxComponent'
                                    },
                                    {
                                        id: 13,
                                        nombre: 'Plan y Manejo',
                                        componente: 'PlanManejoComponent'
                                    }
                                ]
                                this.comp = 'PacienteComponent';
                                break;
                            } else if (this.datosCita.sexo == 'F' && (this.datosCita.Tipo_agenda == '536' ||
                                    this.datosCita.Tipo_agenda == '596' || this.datosCita.Tipo_agenda == '612' ||
                                    this.datosCita.Tipo_agenda == '613')) {
                                this.elementos = [{
                                        id: 1,
                                        nombre: 'Datos Paciente',
                                        componente: 'PacienteComponent'
                                    },
                                    {
                                        id: 2,
                                        nombre: 'Motivo Consulta',
                                        componente: 'MotivoComponent'
                                    },
                                    {
                                        id: 3,
                                        nombre: 'Revisión Sistemas',
                                        componente: 'RevisionSistemasComponent'
                                    },
                                    {
                                        id: 4,
                                        nombre: 'Antecedentes Personales',
                                        componente: 'AntecedentesPersonalesComponent'
                                    },
                                    {
                                        id: 5,
                                        nombre: 'Antecedentes Hospitalarios',
                                        componente: 'AntecedentesHospitalizacionComponent'
                                    },
                                    {
                                        id: 6,
                                        nombre: 'Vacunacion',
                                        componente: 'EsquemaVacunacionComponent'
                                    },
                                    {
                                        id: 7,
                                        nombre: 'Antecedentes Quirugicos',
                                        componente: 'AntecedentesQuirurgicosComponent'
                                    },
                                    {
                                        id: 8,
                                        nombre: 'Antecedentes Familiares',
                                        componente: 'AntecedentesFamiliaresComponent'
                                    },
                                    {
                                        id: 9,
                                        nombre: 'Habitos',
                                        componente: 'EstiloVidaComponent'
                                    },
                                    {
                                        id: 10,
                                        nombre: 'Examen Fisico',
                                        componente: 'ExamenFisicoComponent'
                                    },
                                    {
                                        id: 11,
                                        nombre: 'Antecedentes Ginecostetricos',
                                        componente: 'AntecedentesGinecologicosComponent'
                                    },
                                    {
                                        id: 12,
                                        nombre: 'Estadificacion Riesgo Cardiovascular',
                                        componente: 'EstadificacionRCVComponent'
                                    },
                                    {
                                        id: 13,
                                        nombre: 'Impresión Diagnostica',
                                        componente: 'ImpresionDxComponent'
                                    },
                                    {
                                        id: 14,
                                        nombre: 'Plan y Manejo',
                                        componente: 'PlanManejoComponent'
                                    }
                                ]
                                this.comp = 'PacienteComponent';
                                break;
                            } else if (this.datosCita.sexo == 'M' && (this.datosCita.Tipo_agenda == '536' || this
                                    .datosCita.Tipo_agenda == '596' || this.datosCita.Tipo_agenda == '612' || this
                                    .datosCita.Tipo_agenda == '613')) {
                                this.elementos = [{
                                        id: 1,
                                        nombre: 'Datos Paciente',
                                        componente: 'PacienteComponent'
                                    },
                                    {
                                        id: 2,
                                        nombre: 'Motivo Consulta',
                                        componente: 'MotivoComponent'
                                    },
                                    {
                                        id: 3,
                                        nombre: 'Revisión Sistemas',
                                        componente: 'RevisionSistemasComponent'
                                    },
                                    {
                                        id: 4,
                                        nombre: 'Antecedentes Personales',
                                        componente: 'AntecedentesPersonalesComponent'
                                    },
                                    {
                                        id: 5,
                                        nombre: 'Antecedentes Hospitalarios',
                                        componente: 'AntecedentesHospitalizacionComponent'
                                    },
                                    {
                                        id: 6,
                                        nombre: 'Vacunacion',
                                        componente: 'EsquemaVacunacionComponent'
                                    },
                                    {
                                        id: 7,
                                        nombre: 'Antecedentes Quirugicos',
                                        componente: 'AntecedentesQuirurgicosComponent'
                                    },
                                    {
                                        id: 8,
                                        nombre: 'Antecedentes Familiares',
                                        componente: 'AntecedentesFamiliaresComponent'
                                    },
                                    {
                                        id: 9,
                                        nombre: 'Habitos',
                                        componente: 'EstiloVidaComponent'
                                    },
                                    {
                                        id: 10,
                                        nombre: 'Examen Fisico',
                                        componente: 'ExamenFisicoComponent'
                                    },
                                    {
                                        id: 11,
                                        nombre: 'Estadificacion Riesgo Cardiovascular',
                                        componente: 'EstadificacionRCVComponent'
                                    },
                                    {
                                        id: 12,
                                        nombre: 'Impresión Diagnostica',
                                        componente: 'ImpresionDxComponent'
                                    },
                                    {
                                        id: 13,
                                        nombre: 'Plan y Manejo',
                                        componente: 'PlanManejoComponent'
                                    }
                                ]
                                this.comp = 'PacienteComponent';
                                break;
                            } else if (this.datosCita.Tipo_agenda == '5' || this.datosCita.Tipo_agenda == '27' || this
                                .datosCita.Tipo_agenda == '28' || this.datosCita.Tipo_agenda == '39' ||
                                this.datosCita.Tipo_agenda == '42' || this.datosCita.Tipo_agenda == '53' || this
                                .datosCita.Tipo_agenda == '133' || this.datosCita.Tipo_agenda == '429') {
                                this.elementos = [{
                                        id: 1,
                                        nombre: 'Datos Paciente',
                                        componente: 'PacienteComponent'
                                    },
                                    {
                                        id: 2,
                                        nombre: 'Procedimiento Menor',
                                        componente: 'ProcedimientosMenoresComponent'
                                    },
                                    {
                                        id: 3,
                                        nombre: 'Examen fisico',
                                        componente: 'ExamenFisicoComponent'
                                    },
                                    {
                                        id: 4,
                                        nombre: 'Impresión Diagnostica',
                                        componente: 'ImpresionDxComponent'
                                    },
                                ]
                                this.comp = 'PacienteComponent';
                                break;
                            } else {

                                if (this.datosCita.sexo == "F") {
                                    this.elementos = [{
                                            id: 1,
                                            nombre: 'Datos Paciente',
                                            componente: 'PacienteComponent'
                                        },
                                        {
                                            id: 2,
                                            nombre: 'Motivo Consulta',
                                            componente: 'MotivoComponent'
                                        },
                                        {
                                            id: 3,
                                            nombre: 'Revisión Sistemas',
                                            componente: 'RevisionSistemasComponent'
                                        },
                                        {
                                            id: 4,
                                            nombre: 'Antecedentes Personales',
                                            componente: 'AntecedentesPersonalesComponent'
                                        },
                                        {
                                            id: 5,
                                            nombre: 'Antecedentes farmacològicos',
                                            componente: 'AntecedentesFarmacologicoComponent'
                                        },
                                        {
                                            id: 6,
                                            nombre: 'Antecedentes traumaticos',
                                            componente: 'AntecedentesTraumaticosComponent'
                                        },

                                        {
                                            id: 7,
                                            nombre: 'Antecedentes Familiares',
                                            componente: 'AntecedentesFamiliaresComponent'
                                        },
                                        {
                                            id: 8,
                                            nombre: 'Antecedentes Transfusionales',
                                            componente: 'AntecedentesTransfusiones'
                                        },
                                        {
                                            id: 9,
                                            nombre: 'Vacunacion',
                                            componente: 'EsquemaVacunacionComponent'
                                        },
                                        {
                                            id: 10,
                                            nombre: 'Antecedentes Quirurgicos',
                                            componente: 'AntecedentesQuirurgicosComponent'

                                        },
                                        {
                                            id: 11,
                                            nombre: 'Antecedentes Alergicos',
                                            componente: 'AntecedentesFarmacologicosComponent'
                                        },
                                        {
                                            id: 12,
                                            nombre: 'Antecedentes Hospitalarios',
                                            componente: 'AntecedentesHospitalizacionComponent'
                                        },
                                        {
                                            id: 13,
                                            nombre: 'Antecedentes Ginecostetricos',
                                            componente: 'AntecedentesGinecologicosComponent'

                                        },
                                        {
                                            id: 14,
                                            nombre: 'Indice de Karnofski',
                                            componente: 'KarnofskiComponent'
                                        },
                                        {
                                            id: 15,
                                            nombre: 'Valoracion funcional Ecog',
                                            componente: 'EcogComponent'
                                        },
                                        {
                                            id: 16,
                                            nombre: 'Evaluación de sintomas ESAS',
                                            componente: 'EsasComponent'
                                        },
                                        {
                                            id: 17,
                                            nombre: 'Estilos de Vida',
                                            componente: 'EstiloVidaComponent'
                                        },
                                        {
                                            id: 18,
                                            nombre: 'Antecedentes Biopsicosiciales',
                                            componente: 'AntecedentesBiopsicosocialesComponent'
                                        },
                                        {
                                            id: 19,
                                            nombre: 'Resultados de laboratorio',
                                            componente: 'resultadosLaboratorioComponent'
                                        },
                                        {
                                            id: 20,
                                            nombre: 'Examen fisico',
                                            componente: 'ExamenFisicoComponent'
                                        },
                                        {
                                            id: 21,
                                            nombre: 'Diagnostico de la Consulta',
                                            componente: 'ImpresionDxComponent'
                                        },
                                        {
                                            id: 22,
                                            nombre: 'Plan de Cuidado',
                                            componente: 'PlanCuidadoComponent'
                                        },
                                        {
                                            id: 23,
                                            nombre: 'Indice de Barthel',
                                            componente: 'IndiceBarthelComponent'
                                        },
                                        {
                                            id: 24,
                                            nombre: 'Plan de Manejo',
                                            componente: 'PlanManejoComponent'
                                        },

                                    ];
                                    this.comp = 'PacienteComponent';
                                    break;

                                } else if (this.datosCita.sexo == "M") {
                                    this.elementos = [{
                                            id: 1,
                                            nombre: 'Datos Paciente',
                                            componente: 'PacienteComponent'
                                        },
                                        {
                                            id: 2,
                                            nombre: 'Motivo Consulta',
                                            componente: 'MotivoComponent'
                                        },
                                        {
                                            id: 3,
                                            nombre: 'Revisión Sistemas',
                                            componente: 'RevisionSistemasComponent'
                                        },
                                        {
                                            id: 4,
                                            nombre: 'Antecedentes Personales',
                                            componente: 'AntecedentesPersonalesComponent'
                                        },
                                        {
                                            id: 5,
                                            nombre: 'Antecedentes farmacològicos',
                                            componente: 'AntecedentesFarmacologicoComponent'
                                        },
                                        {
                                            id: 6,
                                            nombre: 'Antecedentes traumaticos',
                                            componente: 'AntecedentesTraumaticosComponent'
                                        },
                                        {
                                            id: 7,
                                            nombre: 'Antecedentes Familiares',
                                            componente: 'AntecedentesFamiliaresComponent'
                                        },
                                        {
                                            id: 8,
                                            nombre: 'Antecedentes Transfusionales',
                                            componente: 'AntecedentesTransfusiones'
                                        },
                                        {
                                            id: 9,
                                            nombre: 'Vacunacion',
                                            componente: 'EsquemaVacunacionComponent'
                                        },
                                        {
                                            id: 10,
                                            nombre: 'Antecedentes Quirugicos',
                                            componente: 'AntecedentesQuirurgicosComponent'
                                        },
                                        {
                                            id: 11,
                                            nombre: 'Antecedentes Alergicos',
                                            componente: 'AntecedentesFarmacologicosComponent'
                                        },
                                        {
                                            id: 12,
                                            nombre: 'Antecedentes Hospitalarios',
                                            componente: 'AntecedentesHospitalizacionComponent'
                                        },
                                        {
                                            id: 13,
                                            nombre: 'Indice de Karnofski',
                                            componente: 'KarnofskiComponent'
                                        },
                                        {
                                            id: 14,
                                            nombre: 'Valoracion funcional Ecog',
                                            componente: 'EcogComponent'
                                        },
                                        {
                                            id: 15,
                                            nombre: 'Evaluación de sintomas ESAS',
                                            componente: 'EsasComponent'
                                        },
                                        {
                                            id: 16,
                                            nombre: 'Indice de Barthel',
                                            componente: 'IndiceBarthelComponent'
                                        },
                                        {
                                            id: 17,
                                            nombre: 'Estilo de vida',
                                            componente: 'EstiloVidaComponent'
                                        },
                                        {
                                            id: 18,
                                            nombre: 'Antecedentes Biopsicosiciales',
                                            componente: 'AntecedentesBiopsicosocialesComponent'
                                        },
                                        {
                                            id: 19,
                                            nombre: 'Resultados de laboratorio',
                                            componente: 'resultadosLaboratorioComponent'
                                        },
                                        {
                                            id: 20,
                                            nombre: 'Examen fisico',
                                            componente: 'ExamenFisicoComponent'
                                        },
                                        {
                                            id: 21,
                                            nombre: 'Diagnostico de la Consulta',
                                            componente: 'ImpresionDxComponent'
                                        },
                                        {
                                            id: 22,
                                            nombre: 'Plan de Cuidado',
                                            componente: 'PlanCuidadoComponent'
                                        },
                                        {
                                            id: 23,
                                            nombre: 'Plan de Manejo',
                                            componente: 'PlanManejoComponent'
                                        },
                                    ];
                                    this.comp = 'PacienteComponent';
                                    break;
                                }
                            }
                            case "Quimica Farmacologica":
                                this.elementos = [{
                                        id: 1,
                                        nombre: 'Datos Paciente',
                                        componente: 'PacienteComponent'
                                    },
                                    {
                                        id: 2,
                                        nombre: 'Motivo Consulta',
                                        componente: 'MotivoComponent'
                                    },
                                    {
                                        id: 3,
                                        nombre: 'Antecedentes Alergicos',
                                        componente: 'AntecedentesFarmacologicosComponent'
                                    },
                                    {
                                        id: 4,
                                        nombre: 'Constantes Vitales',
                                        componente: 'ExamenFisicoComponent'
                                    },
                                    {
                                        id: 5,
                                        nombre: 'Perfil Farmacoterapeutico',
                                        componente: 'AntecedentesQuimicoFarmacologicoComponent'
                                    },
                                    {
                                        id: 6,
                                        nombre: 'Impresión Diagnostica',
                                        componente: 'ImpresionDxComponent'
                                    },
                                    {
                                        id: 7,
                                        nombre: 'Plan de Manejo',
                                        componente: 'PlanManejoComponent'
                                    }

                                ]
                                this.comp = 'PacienteComponent';
                                break;
                            default:
                                if (this.datosCita.sexo == "F") {
                                    this.elementos = [{
                                            id: 1,
                                            nombre: 'Datos Paciente',
                                            componente: 'PacienteComponent'
                                        },
                                        {
                                            id: 2,
                                            nombre: 'Motivo Consulta',
                                            componente: 'MotivoComponent'
                                        },
                                        {
                                            id: 3,
                                            nombre: 'Revisión Sistemas',
                                            componente: 'RevisionSistemasComponent'
                                        },
                                        {
                                            id: 4,
                                            nombre: 'Antecedentes Personales',
                                            componente: 'AntecedentesPersonalesComponent'
                                        },
                                        {
                                            id: 5,
                                            nombre: 'Antecedentes Ginecostetricos',
                                            componente: 'AntecedentesGinecologicosComponent'

                                        },
                                        {
                                            id: 6,
                                            nombre: 'Antecedentes Quirurgicos',
                                            componente: 'AntecedentesQuirurgicosComponent'

                                        },
                                        {
                                            id: 7,
                                            nombre: 'Antecedentes Alergicos',
                                            componente: 'AntecedentesFarmacologicosComponent'
                                        },
                                        {
                                            id: 8,
                                            nombre: 'Antecedentes Familiares',
                                            componente: 'AntecedentesFamiliaresComponent'
                                        },
                                        {
                                            id: 9,
                                            nombre: 'Estilos de Vida',
                                            componente: 'EstiloVidaComponent'
                                        },
                                        {
                                            id: 10,
                                            nombre: 'Resultados de Laboratorios',
                                            componente: 'ResultadosLaboratorioComponent'
                                        },
                                        {
                                            id: 11,
                                            nombre: 'Examen Fisicos',
                                            componente: 'ExamenFisicoComponent'
                                        },
                                        {
                                            id: 12,
                                            nombre: 'Impresión Diagnostica',
                                            componente: 'ImpresionDxComponent'
                                        },
                                        {
                                            id: 13,
                                            nombre: 'Indice de Karnofski',
                                            componente: 'KarnofskiComponent'
                                        },
                                        {
                                            id: 14,
                                            nombre: 'Valoracion funcional Ecog',
                                            componente: 'EcogComponent'
                                        },
                                        {
                                            id: 15,
                                            nombre: 'Evaluación de sintomas ESAS',
                                            componente: 'EsasComponent'
                                        },
                                        {
                                            id: 16,
                                            nombre: 'Indice de Barthel',
                                            componente: 'IndiceBarthelComponent'
                                        },
                                        {
                                            id: 17,
                                            nombre: 'Plan de Manejo',
                                            componente: 'PlanManejoComponent'
                                        },
                                    ];
                                    this.comp = 'PacienteComponent';
                                    break;

                                } else if (this.datosCita.sexo == "M") {
                                    this.elementos = [{
                                            id: 1,
                                            nombre: 'Datos Paciente',
                                            componente: 'PacienteComponent'
                                        },
                                        {
                                            id: 2,
                                            nombre: 'Motivo Consulta',
                                            componente: 'MotivoComponent'
                                        },
                                        {
                                            id: 3,
                                            nombre: 'Revisión Sistemas',
                                            componente: 'RevisionSistemasComponent'
                                        },
                                        {
                                            id: 4,
                                            nombre: 'Antecedentes Personales',
                                            componente: 'AntecedentesPersonalesComponent'
                                        },
                                        {
                                            id: 5,
                                            nombre: 'Antecedentes Quirurgicos',
                                            componente: 'AntecedentesQuirurgicosComponent'

                                        },
                                        {
                                            id: 6,
                                            nombre: 'Antecedentes Alergicos',
                                            componente: 'AntecedentesFarmacologicosComponent'
                                        },
                                        {
                                            id: 7,
                                            nombre: 'Antecedentes Familiares',
                                            componente: 'AntecedentesFamiliaresComponent'
                                        },
                                        {
                                            id: 8,
                                            nombre: 'Estilos de Vida',
                                            componente: 'EstiloVidaComponent'
                                        },
                                        {
                                            id: 9,
                                            nombre: 'Examen Fisicos',
                                            componente: 'ExamenFisicoComponent'
                                        },
                                        {
                                            id: 10,
                                            nombre: 'Resultados de Laboratorios',
                                            componente: 'ResultadosLaboratorioComponent'
                                        },
                                        {
                                            id: 11,
                                            nombre: 'Impresión Diagnostica',
                                            componente: 'ImpresionDxComponent'
                                        },
                                        {
                                            id: 12,
                                            nombre: 'Indice de Karnofski',
                                            componente: 'KarnofskiComponent'
                                        },
                                        {
                                            id: 13,
                                            nombre: 'Valoracion funcional Ecog',
                                            componente: 'EcogComponent'
                                        },
                                        {
                                            id: 14,
                                            nombre: 'Evaluación de sintomas ESAS',
                                            componente: 'EsasComponent'
                                        },
                                        {
                                            id: 15,
                                            nombre: 'Indice de Barthel',
                                            componente: 'IndiceBarthelComponent'
                                        },
                                        {
                                            id: 16,
                                            nombre: 'Plan de Manejo',
                                            componente: 'PlanManejoComponent'
                                        },
                                    ];
                                    this.comp = 'PacienteComponent';
                                    break;
                                }
            }
        },
        data() {
            return {
                step: 1,
                e6: 1,
                comp: null,
                elementos: [],
            }
        },
        methods: {
            continuar(value) {
                this.$emit("porcentaje", this.elementos, this.comp);
                const next = value + 1
                this.e6 = next;
                this.step = next;
                this.comp = this.elementos[parseInt(value)].componente;
            },

            head(id, key) {
                this.e6 = id;
                this.step = id;
                this.comp = this.elementos[parseInt(key)].componente;
            },
        }
    }

</script>

<style>

</style>
