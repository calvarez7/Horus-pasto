<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-stepper v-model="e6" vertical>
                <v-stepper-step :complete="e6 > 1" editable :edit-icon="$vuetify.icons.complete" step="1">
                    ATENCIÓN
                </v-stepper-step>
                <v-stepper-content step="1">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-select
                                :items="motivos"
                                v-model="data.Motivoconsulta"
                                label="Selecciona el tipo de consulta">
                            </v-select>
                            <!-- <v-textarea name="input-7-1" label="Motivo de Consulta" value=""
                                v-model="data.Motivoconsulta"></v-textarea> -->
                            <v-textarea name="input-7-1" label="Enfermedad Actual" value=""
                                v-model="data.Enfermedadactual">
                            </v-textarea>
                        </v-container>
                    </v-card>
                    <v-btn color="primary" round @click="guardarMotivoConsulta()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step @click="fetchAntecedentePersonal(), fetchAntecedentes()" :complete="e6 > 2" editable
                    :edit-icon="$vuetify.icons.complete" step="2">ANTECEDENTES
                    PERSONALES DE ENFERMEDAD MENTAL</v-stepper-step>
                <v-stepper-content step="2">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-container fluid grid-list-xl>
                                    <v-layout wrap align-center>
                                        <v-flex xs12 sm4 d-flex>
                                            <v-autocomplete v-model="antecedent.cie10_id" append-icon="search" :items="cieConcat"
                                                item-disabled="customDisabled" item-text="nombre" item-value="id"
                                                label="Diagnóstico">
                                            </v-autocomplete>
                                            <!-- <v-autocomplete label="Selecciona Antecedentes" :items="antecedentes"
                                                item-text="Nombre" item-value="id" v-model="antecedent.antecedente">
                                            </v-autocomplete> -->
                                        </v-flex>

                                        <v-flex xs12 sm6 d-flex>
                                            <v-textarea solo name="input-1-1" label="Escribir Descripción Antecedente"
                                                v-model="antecedent.descripcion">
                                            </v-textarea>
                                        </v-flex>

                                        <v-flex xs12 sm1 d-flex>
                                            <v-btn fab dark color="success" @click="guardarAntecedente()">
                                                <v-icon dark>add</v-icon>
                                            </v-btn>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-layout>
                        </v-container>
                        <v-card>
                            <v-card-title primary-title>
                                <v-flex xs12 sm12 d-flex>
                                    <v-data-table :items="antecedentePaci" :headers="antecedenteP" hide-actions
                                        class="elevation-1">
                                        <template v-slot:items="props">
                                            <td>{{ props.item.created_at }}</td>
                                            <td class="text-xs">{{ props.item.name }}</td>
                                            <!-- <td class="text-xs">{{ props.item.Nombre }}</td> -->
                                            <td class="text-xs">{{ props.item.cie }}</td>
                                            <td class="text-xs">{{ props.item.Descripcion }}</td>
                                        </template>
                                    </v-data-table>
                                </v-flex>
                            </v-card-title>
                        </v-card>
                    </v-card>
                    <v-btn color="primary" round @click="nextParentesco()">Seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step @click="fetchAntecedenteFamiliar(), fetchAntecedentes(), fetchParentesco()"
                    :complete="e6 > 3" editable :edit-icon="$vuetify.icons.complete" step="3">ANTECEDENTES
                    FAMILIARES DE ENFERMEDAD MENTAL</v-stepper-step>
                <v-stepper-content step="3">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-container fluid grid-list-xl>
                                    <v-layout wrap align-center>
                                        <v-flex xs12 sm3 d-flex>
                                            <v-autocomplete v-model="antecedent.cie10_id" append-icon="search" :items="cieConcat"
                                                item-disabled="customDisabled" item-text="nombre" item-value="id"
                                                label="Diagnóstico">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 sm3 d-flex>
                                            <v-autocomplete label="Selecciona Familiar" :items="parentesco"
                                                item-text="Nombre" item-value="id" v-model="antecedent.familiar">
                                            </v-autocomplete>
                                        </v-flex>

                                        <v-flex xs12 sm5 d-flex>
                                            <v-textarea solo name="input-1-1" label="Escribir Descripción Antecedente"
                                                v-model="antecedent.descripcion">
                                            </v-textarea>
                                        </v-flex>

                                        <v-flex xs12 sm1 d-flex>
                                            <v-btn fab dark color="success" @click="guardarAntecedentefamiliar()">
                                                <v-icon dark>add</v-icon>
                                            </v-btn>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-layout>
                        </v-container>
                        <v-card>
                            <v-card-title primary-title>
                                <v-flex xs12 sm12 d-flex>
                                    <v-data-table :items="antecedenteFami" :headers="familiare" hide-actions
                                        class="elevation-1">
                                        <template v-slot:items="props">
                                            <td>{{ props.item.created_at }}</td>
                                            <td class="text-xs">{{ props.item.name }}</td>
                                            <td class="text-xs">{{ props.item.cie }}</td>
                                            <td class="text-xs">{{ props.item.familiar }}</td>
                                            <td class="text-xs">{{ props.item.Descripcion }}</td>
                                        </template>
                                    </v-data-table>
                                </v-flex>
                            </v-card-title>
                        </v-card>
                    </v-card>
                    <v-btn color="primary" round @click="nextConigtiva()">Seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 4" editable :edit-icon="$vuetify.icons.complete" step="4">
                    AREA COGNITIVA - PROCESOS COGNITIVOS SUPERIORES
                </v-stepper-step>
                <v-stepper-content step="4">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="psicologiaOcupacional.proceso_cognitivo" label="percepcion"></v-textarea>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="psicologiaOcupacional.memoria" label="Memoria"></v-textarea>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="psicologiaOcupacional.atencion" label="Atencion"></v-textarea>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="psicologiaOcupacional.lenguaje" label="Lenguaje"></v-textarea>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="psicologiaOcupacional.ubicacion" label="Ubicado en"></v-textarea>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="psicologiaOcupacional.estado_mental" label="Estado Mental"></v-textarea>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="psicologiaOcupacional.presentacion_personal" label="Presentación personal"></v-textarea>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="psicologiaOcupacional.introspeccion" label="Introspección "></v-textarea>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="psicologiaOcupacional.prospeccion" label="Prospección "></v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="saveOcupacional()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 5" editable :edit-icon="$vuetify.icons.complete" step="5">
                    AREA SOCIAL
                </v-stepper-step>
                <v-stepper-content step="5">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12>
                                <v-textarea v-model="psicologiaOcupacional.social" label="social"></v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="saveSocial()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 6" editable :edit-icon="$vuetify.icons.complete" step="6">
                    AREA FAMILIAR
                </v-stepper-step>
                <v-stepper-content step="6">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12>
                                <v-textarea v-model="psicologiaOcupacional.familiar" label="familiar"></v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="saveFamiliar()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 7" editable :edit-icon="$vuetify.icons.complete" step="7">
                    AREA LABORAL
                </v-stepper-step>
                <v-stepper-content step="7">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12>
                                <v-textarea v-model="psicologiaOcupacional.laboral" label="Laboral"></v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="saveLaboral()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 8" editable :edit-icon="$vuetify.icons.complete" step="8">
                    AREA ACADEMICA
                </v-stepper-step>
                <v-stepper-content step="8">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12>
                                <v-textarea v-model="psicologiaOcupacional.academica" label="Academica"></v-textarea>
                            </v-flex>

                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="saveAcademica()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 9" editable :edit-icon="$vuetify.icons.complete" step="9">
                    ANALISIS DE DIAGNOSTICO
                </v-stepper-step>
                <v-stepper-content step="9">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12>
                                <v-textarea v-model="psicologiaOcupacional.analisis_diagnostico" label="Diagnostico"></v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="saveAnadiagnostico()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 10" editable :edit-icon="$vuetify.icons.complete" step="10">
                    TECNICA DE RECOLECCIÓN DE DATOS
                </v-stepper-step>
                <v-stepper-content step="10">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12>
                                <v-textarea v-model="psicologiaOcupacional.recoleccion_datos" label="Recolección de datos"></v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="saveRecoleccion()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 11" @click="fetchCie10s()" editable :edit-icon="$vuetify.icons.complete"
                    step="11">
                    DIAGNÓSTICOS
                </v-stepper-step>
                <v-stepper-content step="11">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 sm6 class="px-2">
                                <v-autocomplete v-model="Cie10_id" append-icon="search" :items="cieConcat"
                                    item-disabled="customDisabled" item-text="nombre" item-value="id"
                                    label="Diagnóstico">
                                </v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-select :items="['Impresión diagnóstica', 'Confirmado nuevo','Confirmado repetido']"
                                    label="Tipo Diagnóstico" v-model="Tipodiagnostico" chips></v-select>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-autocomplete v-if="Tipodiagnostico != 'Impresión diagnóstica' && Tipodiagnostico"
                                    :items="['Oncológicos','Anticoagulados','Salud Mental','Nefroprotección ','Respiratorios',
                                    'Reumatologicos','Tramisibles Crónicas','Gestantes','Enfermedades Huerfanas','Trasplantados',
                                    'Polimedicados','Pacientes Tutela','Paciente Alto Costo','Domiciliario','Primera Infancia','Infancia ',
                                    'Adolescencia ','Juventud ','Riesgo Cardiovascular','Grupal Curso Psicoprofilactico',
                                    'Apoyo Lactancia Materna ','Detección Temprana Cáncer Cuello Uterino ','Riesgo Cardiovascular',
                                    'Planificación Familiar','Atención Preconcepcional','Promocion De Alimentacion Y Nutricion','Prenatal',
                                    'Atención Del Posparto','Atención Del Recien Nacido','Detección Temprana Cancer De Mama', 'Adultez', 'Vejez'
						            ]" label="Marcar paciente" v-model="Marcapaciente" attach multiple chips></v-autocomplete>
                            </v-flex>
                            <v-btn color="primary" round dark v-on:click="addDiagnostico()">Ingresar Diagnóstico</v-btn>
                            <v-flex xs12 sm12>
                                <v-data-table :headers="headersDiagnostico" :items="Diagnostico" :search="search">
                                    <template v-slot:items="props">
                                        <tr :active="props.selected" @click="props.selected = !props.selected">
                                            <td>
                                                <v-checkbox color="primary" v-model="selectedItems" hide-details
                                                    :value="props.item">
                                                </v-checkbox>
                                            </td>
                                            <td>{{ props.item.Codigo }}</td>
                                            <td class="text-xs-center">{{ props.item.Tipodiagnostico }}</td>
                                            <td class="text-xs-center">
                                                <v-select :items="props.item.Marcapaciente" label="Marcar paciente"
                                                    v-model="props.item.Marcapaciente" attach multiple chips></v-select>
                                            </td>
                                            <td class="text-xs-center">
                                                <v-btn fab utline color="error" small @click="removeDiagnostico(props)">
                                                    <v-icon>clear</v-icon>
                                                </v-btn>
                                            </td>
                                        </tr>
                                    </template>
                                    <v-alert v-slot:no-results :value="true" color="error" icon="warning">Your search
                                        for
                                        "{{ search }}"
                                        found no results.</v-alert>
                                </v-data-table>
                            </v-flex>
                            <v-btn color="primary" round dark v-on:click="submitDiagnostico()">Guardar y seguir</v-btn>
                        </v-layout>
                    </v-card>
                </v-stepper-content>
                <v-stepper-step :complete="e6 > 12" editable :edit-icon="$vuetify.icons.complete" step="12">
                    CONCEPTO DE APTITUD LABORAL – PSICOLOGIA
                </v-stepper-step>
                <v-stepper-content step="12">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 md8 class="px-2">
                                <v-autocomplete :items="aptitud" v-model="psicologiaOcupacional.aptitud_laboral_psicologia"
                                    label="CONCEPTO DE APTITUD LABORAL – PSICOLOGIA">
                                </v-autocomplete>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="nextFin()">Guardar y terminar</v-btn>
                </v-stepper-content>
                <v-stepper-step :complete="e6 > 13" editable :edit-icon="$vuetify.icons.complete" step="13">
                    CONDUCTAS
                </v-stepper-step>
                <v-stepper-content step="13">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 sm6 class="px-2">
                                <v-textarea label="Recomendaciones Personales" v-model="conducta.Planmanejo">
                                </v-textarea>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-textarea label="Recomendaciones laborales" v-model="conducta.Recomendaciones"></v-textarea>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-autocomplete :items="[
                                'Control', 'Interconsulta', 'Urgencias', 'Consulta externa prioritaria', 'No aplica'
								]" label="Destino del paciente" append-icon="search" v-model="conducta.Destinopaciente"></v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm5>
                                <v-autocomplete :items="[
									'Consulta SST', 'No aplica'
								]" label="Finalidad" append-icon="search" v-model="conducta.Finalidad"></v-autocomplete>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="guardarConducta()">Guardar y terminar</v-btn>
                </v-stepper-content>

            </v-stepper>

            <template>
                <div class="text-center">
                    <v-dialog v-model="preload" persistent width="300">
                        <v-card color="primary" dark>
                            <v-card-text>
                                Tranquilo procesamos tu solicitud !
                                <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                </div>
            </template>
        </v-flex>
    </v-layout>
</template>
<script>
    import Swal from 'sweetalert2';
    import {
        EventBus
    } from '../../../../event-bus.js';
    export default {
        created() {
            this.citaPaciente = this.$route.query.cita_paciente;
            this.antecedent.cita_paciente = this.$route.query.cita_paciente;
            this.removeLocalStorage();
            this.getLocalStorage();
        },
        data() {
            return {
                aptitud: ['Sin Recomendaciones Laborales', 'Con Recomendaciones  Laborales', 'Sin Restricciones  Laborales', 'Con Restricciones Laborales'],
                motivos: ['Examenes ocupacionales periódicos', 'Examenes ocupacionales ingreso', 'Examenes ocupacionales egreso', 'Examenes ocupacionales post incapacidad', 'Examenes ocupacionales reubicación'],
                preload: false,
                e6: 1,
                data: {
                    Motivoconsulta: '',
                    Enfermedadactual: ''
                },
                antecedent: {
                    antecedente: '',
                    cie10_id: '',
                    descripcion: '',
                    cita_paciente: '',
                    familiar: ''
                },
                citaPaciente: 0,
                antecedentes: [],
                antecedentePaci: [],
                antecedenteP: [{
                        text: 'Fecha'
                    },
                    {
                        text: 'Médico'
                    },
                    {
                        text: 'Antecedente'
                    },
                    {
                        text: 'Descripción'
                    }
                ],
                parentesco: [],
                antecedenteFami: [],
                familiare: [{
                        text: 'Fecha'
                    },
                    {
                        text: 'Médico'
                    },
                    {
                        text: 'Antecedente'
                    },
                    {
                        text: 'Parentesco'
                    },
                    {
                        text: 'Descripción'
                    }
                ],
                Cie10_id: '',
                cie10Array: [],
                cie10s: [],
                Tipodiagnostico: '',
                headersDiagnostico: [{
                        text: "Marcar Principal",
                        align: "center",
                        value: "marcar"
                    },
                    {
                        text: "Diagnóstico",
                        align: "center",
                        value: "diagnostico"
                    },
                    {
                        text: "Tipo Diagnóstico",
                        align: "center",
                        value: "tipo_diagnostico"
                    },
                    {
                        text: "Marca",
                        align: "center",
                        value: "marca"
                    },
                    {
                        text: "",
                        align: "center",
                        value: ""
                    }
                ],
                Diagnostico: [],
                search: '',
                Marcapaciente: [],
                selectedItems: {},
                DiagnosticoSaved: [],
                conducta: {
                    Planmanejo: '',
                    Recomendaciones: '',
                    Destinopaciente: '',
                    Finalidad: ''
                },
                psicologiaOcupacional: {
                    proceso_cognitivo: '',
                    memoria: '',
                    atencion: '',
                    lenguaje: '',
                    ubicacion: '',
                    estado_mental: '',
                    presentacion_personal: '',
                    pensamiento_logico: '',
                    introspeccion: '',
                    prospeccion: '',
                    area_familiar: '',
                    area_mental: '',
                    antecedentes_mentales: '',
                    aptitud_laboral_psicologia: '',
                    academica: '',
                    laboral: '',
                    social: '',
                    familiar: '',
                    analisis_diagnostico: '',
                    recoleccion_datos: '',
                }
            }
        },

        computed: {
            cieConcat() {
                if (this.cie10s !== undefined) {
                    return this.cie10Array = this.cie10s.map(cie => {
                        return {
                            id: cie.id,
                            codigo: cie.Codigo_CIE10,
                            nombre: `${cie.Codigo_CIE10} - ${cie.Descripcion}`,
                            customDisabled: false
                        };
                    });
                }
            }
        },
        mounted() {
            this.fetchCie10s();
        },
        methods: {

            fetchCie10s() {
                axios.get("/api/cie10/all").then(res => {
                    this.cie10s = res.data;
                });
            },

            addDiagnostico() {
                if (this.Cie10_id) {
                    this.cie10Array.find(cie10 => {
                        if (cie10.id == this.Cie10_id) {
                            this.Codigo = cie10.codigo;
                        }
                    });
                }
                if (
                    this.Cie10_id &&
                    this.Tipodiagnostico != "Impresión diagnóstica" &&
                    this.Marcapaciente.length > 0
                ) {
                    this.Diagnostico.push({
                        Cie10_id: this.Cie10_id,
                        Tipodiagnostico: this.Tipodiagnostico,
                        Marcapaciente: this.Marcapaciente,
                        Esprimario: false,
                        Codigo: this.Codigo
                    });
                    this.disable(this.Cie10_id);
                    this.clearDataArticulo();
                } else if (
                    this.Cie10_id &&
                    this.Tipodiagnostico == "Impresión diagnóstica" &&
                    this.Marcapaciente.length == 0
                ) {
                    this.Diagnostico.push({
                        Cie10_id: this.Cie10_id,
                        Tipodiagnostico: this.Tipodiagnostico,
                        Esprimario: false,
                        Codigo: this.Codigo
                    });
                    this.disable(this.Cie10_id);
                    this.clearDataArticulo();
                }
            },

            removeDiagnostico(diagnostico) {
                this.Diagnostico.splice(diagnostico.index, 1);
                if (diagnostico.item.id != null) {
                    axios
                        .get(`/api/cie10/deleteDiagnostic/${diagnostico.item.id}`)
                        .then(res => localStorage.removeItem("Diagnostico"));
                }
                this.cie10Array.map(cie10 => {
                    if (cie10.id == diagnostico.item.Cie10_id) {
                        cie10.customDisabled = false
                    }
                });
            },

            fetchDiagnostico() {
                axios.get(`/api/cie10/diagnostico/${this.citaPaciente}`).then(res => {
                    this.DiagnosticoSaved = res.data.cie10;
                    this.Diagnostico = this.DiagnosticoSaved;
                });
            },

            clearDataArticulo() {
                this.Cie10_id = '';
                this.Tipodiagnostico = '';
                this.Marcapaciente = '';
                this.Codigo = '';
            },

            disable(id) {
                this.cie10Array.map(cie10 => {
                    if (cie10.id == id) {
                        cie10.customDisabled = true
                    }
                });
            },

            fetchAtencion() {
                axios.get('/api/cita/' + this.citaPaciente + '/motivo')
                    .then(res => {
                        this.data = res.data;
                    });
            },

            guardarMotivoConsulta() {
                if (!this.data.Motivoconsulta) {
                    swal("Motivo consulta es REQUERIDO y debe ser mayor a 5 caracteres")
                    return;
                } else if (this.data.Motivoconsulta.length < 5) {
                    swal("Motivo consulta debe ser mayor a 5 caracteres")
                    return;
                }
                this.preload = true;
                axios.post('/api/cita/' + this.citaPaciente + '/motivo', this.data).then((res) => {
                    this.preload = false;
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'center-end',
                        background: '#4caf50',
                        showConfirmButton: false,
                        timer: 1000,
                        timerProgressBar: false,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: '<span style="color:#fff">Motivo Guardado con exito<span>'
                    });
                    this.e6 = 2;
                    localStorage.setItem("Atencion", JSON.stringify(this.data));
                    this.fetchAntecedentes();
                    this.fetchAntecedentePersonal();
                });

            },

            fetchAntecedentes() {
                axios.get("/api/antecedente/all").then(res => (this.antecedentes = res.data));
            },

            guardarAntecedente() {
                this.preload = true;
                axios.post("/api/pacienteantecedente/create", this.antecedent)
                    .then(res => {
                        this.preload = false;
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'center-end',
                            background: '#4caf50',
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: false,
                            onOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })
                        Toast.fire({
                            icon: 'success',
                            title: '<span style="color:#fff">Antecedentes Agregado con exito<span>'
                        });
                        this.clearAntecedente();
                        this.fetchAntecedentePersonal();
                    })
                    .catch(err => console.log(err.response.data));
            },

            fetchAntecedentePersonal() {
                let data = {
                    citaPaciente_id: this.citaPaciente
                }
                axios.post("/api/pacienteantecedente/antecedentes1", data)
                    .then(res => {
                        this.antecedentePaci = res.data
                    });
            },

            nextParentesco() {
                this.e6 = 3;
                this.fetchParentesco();
                this.fetchAntecedenteFamiliar();
            },

            clearAntecedente() {
                this.antecedent.antecedente = ''
                this.antecedent.descripcion = ''
                this.antecedent.familiar = ''
            },

            fetchParentesco() {
                axios.get("/api/parentesco/all").then(res => (this.parentesco = res.data));
            },

            guardarAntecedentefamiliar() {
                this.preload = true;
                axios.post("/api/parentescoantecedente/create", this.antecedent)
                    .then(res => {
                        this.preload = false;
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'center-end',
                            background: '#4caf50',
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: false,
                            onOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })
                        Toast.fire({
                            icon: 'success',
                            title: '<span style="color:#fff">Antecedentes Agregado con exito<span>'
                        });
                        this.clearAntecedente();
                        this.fetchAntecedenteFamiliar();
                    })
                    .catch(err => console.log(err.response.data));
            },

            fetchAntecedenteFamiliar() {
                let data = {
                    citaPaciente_id: this.citaPaciente
                }
                axios.post("/api/parentescoantecedente/familiares", data)
                    .then(res => {
                        this.antecedenteFami = res.data
                    });
            },

            nextConigtiva() {
                this.e6 = 4;
            },

            getLocalStorage() {
                let Diagnostico = JSON.parse(localStorage.getItem("Diagnostico"));
                if (Diagnostico) {
                    this.Diagnostico = Diagnostico;
                    this.Diagnostico.forEach(diag => {
                        this.disable(diag.Cie10_id);
                    });
                } else {
                    this.fetchDiagnostico();
                }

                let motivo = JSON.parse(localStorage.getItem("Atencion"));
                if (motivo) {
                    this.data = motivo;
                } else {
                    this.fetchAtencion();
                }

                let conducta = JSON.parse(localStorage.getItem("Conducta"));
                if (conducta) {
                    this.conducta = conducta;
                } else {
                    this.fetchConducta();
                }

                let saludocupacional = JSON.parse(localStorage.getItem("saludocupacional"));
                if (saludocupacional) {
                    this.saludocupacional = saludocupacional;
                } else {
                    this.fetchOftamologia();
                }
            },

            removeLocalStorage() {
                localStorage.removeItem("Atencion");
                localStorage.removeItem("Diagnostico");
                localStorage.removeItem("Conducta");
                localStorage.removeItem("saludocupacional");
            },

            submitDiagnostico() {
                if (this.Diagnostico.length == 0 || !this.selectedItems) {
                    swal({
                        title: "Debe asignar un diagnostico principal",
                        icon: "warning"
                    });
                    return;
                }
                var diag = [];
                this.Diagnostico.forEach(diagnostico => {
                    if (diagnostico.Cie10_id == this.selectedItems.Cie10_id) {
                        diagnostico.Esprimario = true;
                    }
                    if (diagnostico.id == null) {
                        diag.push(diagnostico);
                    }
                });
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "El diagnóstico será almacenado",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios
                            .post(`/api/cie10/create/${this.citaPaciente}`, diag)
                            .then(res => {
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'center-end',
                                    background: '#4caf50',
                                    showConfirmButton: false,
                                    timer: 1000,
                                    timerProgressBar: false,
                                    onOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                })
                                Toast.fire({
                                    icon: 'success',
                                    title: '<span style="color:#fff">Diagnostico guardado con exito<span>'
                                });
                                localStorage.setItem(
                                    "Diagnostico",
                                    JSON.stringify(this.Diagnostico)
                                );
                                this.actitudlaboral();
                            })
                            .catch(err => console.log(err.response.data));
                    }
                });
            },

            guardarConducta() {
                if (this.conducta.Planmanejo == undefined) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#000">Plan de manejo debe ser minimo de 5 caracteres !<span>'
                    })
                    return;
                } else if (this.conducta.Recomendaciones == undefined) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#000">Recomendaciones de consulta debe ser minimo de 5 caracteres!<span>'
                    })
                    return;
                } else if (!this.conducta.Destinopaciente) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#000">Destino del paciente es requerido!<span>'
                    })
                    return;
                } else if (!this.conducta.Finalidad) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#000">La FINALIDAD es requerida!<span>'
                    })
                    return;
                }
                this.preload = true;
                axios
                    .post("/api/conducta/" + this.citaPaciente + "/final", this.conducta)
                    .then(res => {
                        this.preload = false;
                        this.e6 = 14;
                        localStorage.setItem("Conducta", JSON.stringify(this.conducta));
                        swal({
                            title: res.data.message,
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                        // EventBus.$emit("call-order");
                    });
            },

            fetchConducta() {
                axios.post('/api/conducta/' + this.citaPaciente + '/getConductaByCita')
                    .then(res => {
                        if (res.data) {
                            this.conducta = res.data
                        }
                    })
                    .catch(err => console.log(err.response));
            },

            saveSaludocupacional() {
                axios.post('/api/cita/saveSaludocupacional/' + this.citaPaciente, this.psicologiaOcupacional).then((res) => {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'center-end',
                        background: '#4caf50',
                        showConfirmButton: false,
                        timer: 1000,
                        timerProgressBar: false,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: '<span style="color:#fff">Guardado con exito!<span>'
                    });
                })
            },

            fetchOftamologia() {
                axios.post('/api/cita/getSaludocupacional/' + this.citaPaciente)
                    .then(res => {
                        if (res.data) {
                            this.saludocupacional = res.data
                        }
                    })
                    .catch(err => console.log(err.response));
            },

            saveOcupacional() {
                this.saveSaludocupacional();
                this.e6 = 5
            },
            saveSocial(){
                this.saveSaludocupacional();
                this.e6 = 6
            },
            saveFamiliar(){
                this.saveSaludocupacional();
                this.e6 = 7
            },
            saveAcademica(){
                this.saveSaludocupacional();
                this.e6 = 9
            },
            saveLaboral(){
                this.saveSaludocupacional();
                this.e6 = 8
            },
            saveAnadiagnostico(){
                this.saveSaludocupacional();
                this.e6 = 10
            },
            saveRecoleccion(){
                this.saveSaludocupacional();
                this.e6 = 11
            },
            actitudlaboral(){
                this.e6 = 12
            },
            nextFin() {
                this.e6 = 13
                this.saveSaludocupacional();
            },
            nextConducta() {
                this.e6 = 14
            }

        }
    }

</script>
