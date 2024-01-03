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
                            <v-textarea name="input-7-1" label="Motivo de Consulta" value=""
                                v-model="data.Motivoconsulta"></v-textarea>
                            <v-textarea name="input-7-1" label="Enfermedad Actual" value=""
                                v-model="data.Enfermedadactual"></v-textarea>
                        </v-container>
                    </v-card>
                    <v-btn color="primary" round @click="guardarMotivoConsulta()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step @click="fetchAntecedentePersonal(), fetchAntecedentes()" :complete="e6 > 2" editable
                    :edit-icon="$vuetify.icons.complete" step="2">ANTECEDENTES
                    PERSONALES</v-stepper-step>
                <v-stepper-content step="2">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-container fluid grid-list-xl>
                                    <v-layout wrap align-center>
                                        <v-flex xs12 sm4 d-flex>
                                            <v-autocomplete label="Selecciona Antecedentes" :items="antecedentes"
                                                item-text="Nombre" item-value="id" v-model="antecedent.antecedente">
                                            </v-autocomplete>
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
                                            <td class="text-xs">{{ props.item.Nombre }}</td>
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
                    FAMILIARES</v-stepper-step>
                <v-stepper-content step="3">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-container fluid grid-list-xl>
                                    <v-layout wrap align-center>
                                        <v-flex xs12 sm3 d-flex>
                                            <v-autocomplete label="Selecciona Antecedentes" :items="antecedentes"
                                                item-text="Nombre" item-value="id" v-model="antecedent.antecedente">
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
                                            <td class="text-xs">{{ props.item.Nombre }}</td>
                                            <td class="text-xs">{{ props.item.familiar }}</td>
                                            <td class="text-xs">{{ props.item.Descripcion }}</td>
                                        </template>
                                    </v-data-table>
                                </v-flex>
                            </v-card-title>
                        </v-card>
                    </v-card>
                    <v-btn color="primary" round @click="nextAgudeza()">Seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 4" editable :edit-icon="$vuetify.icons.complete" step="4">
                    AGUDEZA VISUAL
                </v-stepper-step>
                <v-stepper-content step="4">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs6 sm6 class="px-5">
                                <v-autocomplete :items="agudezas" v-model="oftamologia.avcc_ojoderecho"
                                    label="AVCC OJO DERECHO">
                                </v-autocomplete>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-autocomplete :items="agudezas" v-model="oftamologia.avcc_ojoizquierdo"
                                    label="AVCC OJO IZQUIERDO">
                                </v-autocomplete>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-autocomplete :items="agudezas" v-model="oftamologia.avsc_ojoderecho"
                                    label="AVSC OJO DERECHO">
                                </v-autocomplete>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-autocomplete :items="agudezas" v-model="oftamologia.avsc_ojoizquierdo"
                                    label="AVSC OJO IZQUIERDO">
                                </v-autocomplete>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-text-field v-model="oftamologia.ph_ojoderecho" label="PH OJO DERECHO">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-text-field v-model="oftamologia.ph_ojoizquierdo" label="PH OJO IZQUIERDO">
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="saveAgudeza()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 5" editable :edit-icon="$vuetify.icons.complete" step="5">
                    MOTILIDAD OCULAR
                </v-stepper-step>
                <v-stepper-content step="5">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="oftamologia.motilidad_ojoderecho" label="OJO DERECHO"></v-textarea>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="oftamologia.covert_ojoderecho" label="COVER TEST DERECHO">
                                </v-textarea>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="oftamologia.motilidad_ojoizquierdo" label="OJO IZQUIERDO">
                                </v-textarea>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="oftamologia.covert_ojoizquierdo" label="COVER TEST IZQUIERDO">
                                </v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="saveMotilidad()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 6" editable :edit-icon="$vuetify.icons.complete" step="6">
                    BIOMICROSCOPIA
                </v-stepper-step>
                <v-stepper-content step="6">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="oftamologia.biomicros_ojoderecho" label="OJO DERECHO"></v-textarea>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="oftamologia.biomicros_ojoizquierdo" label="OJO IZQUIERDO">
                                </v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="saveBiomicroscopia()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 7" editable :edit-icon="$vuetify.icons.complete" step="7">
                    PRESION INTRAOCULAR
                </v-stepper-step>
                <v-stepper-content step="7">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="oftamologia.presionintra_ojoderecho" label="OJO DERECHO">
                                </v-textarea>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="oftamologia.presionintra_ojoizquierdo" label="OJO IZQUIERDO">
                                </v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="savePresion()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 8" editable :edit-icon="$vuetify.icons.complete" step="8">
                    GIONIOSCOPIA
                </v-stepper-step>
                <v-stepper-content step="8">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="oftamologia.gionios_ojoderecho" label="OJO DERECHO"></v-textarea>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="oftamologia.gionios_ojoizquierdo" label="OJO IZQUIERDO">
                                </v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="saveGionioscopia()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 9" editable :edit-icon="$vuetify.icons.complete" step="9">
                    FONDO DE OJO
                </v-stepper-step>
                <v-stepper-content step="9">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="oftamologia.fondo_ojoderecho" label="OJO DERECHO"></v-textarea>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="oftamologia.fondo_ojoizquierdo" label="OJO IZQUIERDO"></v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="saveFondojo()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 10" @click="fetchCie10s()" editable :edit-icon="$vuetify.icons.complete"
                    step="10">
                    DIAGNÓSTICOS
                </v-stepper-step>
                <v-stepper-content step="10">
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
                            <v-btn color="success" round dark v-on:click="submitDiagnostico()">Guardar</v-btn>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="nextConducta()">Seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 11" editable :edit-icon="$vuetify.icons.complete" step="11">
                    CONDUCTAS
                </v-stepper-step>
                <v-stepper-content step="11">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 sm6 class="px-2">
                                <v-textarea label="Plan de manejo" v-model="conducta.Planmanejo">
                                </v-textarea>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-textarea label="Recomendaciones" v-model="conducta.Recomendaciones"></v-textarea>
                            </v-flex>
                            <v-flex xs12 sm4 class="px-2">
                                <v-autocomplete :items="[
									'RIA Primera Infancia','RIA Infancia','RIA Adolescencia','RIA Juventud', 'RIA Adulto',
									'RIA Adulto Mayor','RIA Materno Perinatal', 'Control', 'Interconsulta', 'Domiciliaria',
									'Urgencias', 'Hospitalización'
								]" label="Destino del paciente" append-icon="search" v-model="conducta.Destinopaciente"></v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm5>
                                <v-autocomplete :items="[
									'Atención del parto, del embarazo y posparto', 'Atención del recién nacido',
									'Atención planificación familiar', 'Atención crecimiento y desarrollo',
									'Atención joven Sano', 'Detención alteraciones del embarazo',
									'Detención alteraciones del adulto', 'Detención alteraciones agudeza Visual',
									'Enfermedad Profesional', 'Teleconsulta', 'No aplica'
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
                preload: false,
                e6: 1,
                data: {
                    Motivoconsulta: '',
                    Enfermedadactual: ''
                },
                antecedent: {
                    antecedente: '',
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
                agudezas: ['20/20', '20/30', '20/40', '20/50', '20/60', '20/70', '20/80', '20/100', '20/200', '20/400',
                    'PL', 'NPL'
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
                oftamologia: {
                    avcc_ojoderecho: '',
                    avcc_ojoizquierdo: '',
                    avsc_ojoderecho: '',
                    avsc_ojoizquierdo: '',
                    ph_ojoderecho: '',
                    ph_ojoizquierdo: '',
                    motilidad_ojoderecho: '',
                    covert_ojoderecho: '',
                    motilidad_ojoizquierdo: '',
                    covert_ojoizquierdo: '',
                    biomicros_ojoderecho: '',
                    biomicros_ojoizquierdo: '',
                    presionintra_ojoderecho: '',
                    presionintra_ojoizquierdo: '',
                    gionios_ojoderecho: '',
                    gionios_ojoizquierdo: '',
                    fondo_ojoderecho: '',
                    fondo_ojoizquierdo: '',
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
                if (!this.data.Enfermedadactual) {
                    swal("Enfermedad actual es REQUERIDA y debe ser mayor a 20 caracteres")
                    return;
                } else if (this.data.Enfermedadactual.length < 20) {
                    swal("Enfermedad actual debe ser mayor a 20 caracteres")
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

            nextAgudeza() {
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

                let oftamologia = JSON.parse(localStorage.getItem("oftamologia"));
                if (oftamologia) {
                    this.oftamologia = oftamologia;
                } else {
                    this.fetchOftamologia();
                }
            },

            removeLocalStorage() {
                localStorage.removeItem("Atencion");
                localStorage.removeItem("Diagnostico");
                localStorage.removeItem("Conducta");
                localStorage.removeItem("oftamologia");
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
                        this.e6 = 12;
                        localStorage.setItem("Conducta", JSON.stringify(this.conducta));
                        swal({
                            title: res.data.message,
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                        EventBus.$emit("call-order");
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

            saveOftamologia() {
                axios.post('/api/cita/saveOftamologia/' + this.citaPaciente, this.oftamologia).then((res) => {
                    localStorage.setItem("oftamologia", JSON.stringify(this.oftamologia));
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
                axios.post('/api/cita/getoftamologia/' + this.citaPaciente)
                    .then(res => {
                        if (res.data) {
                            this.oftamologia = res.data
                        }
                    })
                    .catch(err => console.log(err.response));
            },

            saveAgudeza() {
                this.saveOftamologia();
                this.e6 = 5
            },

            saveMotilidad() {
                this.saveOftamologia();
                this.e6 = 6
            },

            saveBiomicroscopia() {
                this.saveOftamologia();
                this.e6 = 7
            },

            savePresion() {
                this.saveOftamologia();
                this.e6 = 8
            },

            saveGionioscopia() {
                this.saveOftamologia();
                this.e6 = 9
            },

            saveFondojo() {
                this.saveOftamologia();
                this.fetchCie10s();
                this.e6 = 10
            },

            nextConducta() {
                this.e6 = 11
            }

        }
    }

</script>
