<template>
    <v-container pa-0 fluid grid-list-md>
        <v-layout row>
            <v-flex xs12>
                <v-card max-height="100%" class="mb-3">
                    <v-card-title class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">ALERTAS</span>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-container grid-list-md text-xs-center>
                        <v-layout row wrap>

                            <v-flex xs2 md2 sm2 px-2>
                                <v-text-field label="Documento" v-model="filters.documento">
                                </v-text-field>
                            </v-flex>

                            <v-flex xs2 md2 sm2 px-2>
                                <v-autocomplete label="Departamento" :items="departamentos" item-text="Nombre"
                                    item-value="id" v-model="filters.departamento">
                                </v-autocomplete>
                            </v-flex>

                            <v-flex xs2 md2 sm2 px-2>
                                <v-autocomplete label="Municipio" :items="municipios" item-text="Nombre" item-value="id"
                                    v-model="filters.municipio">
                                </v-autocomplete>
                            </v-flex>

                            <v-flex xs2 md2 sm2 px-2>
                                <v-autocomplete label="IPS" :items="prestadores" item-text="Nombre" item-value="id"
                                    v-model="filters.ips">
                                </v-autocomplete>
                            </v-flex>

                            <v-flex xs2 md2 sm2 px-2>
                                <v-select :items="alert" label="Alertas" v-model="filters.alertas">
                                </v-select>
                            </v-flex>

                            <v-flex xs2 md2 sm2 px-2>
                                <v-btn color="success" @click="fetctalertas()">Buscar</v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card>
                <v-card max-height="100%" class="mb-3">
                    <v-card-title>
                        <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                        </v-text-field>
                    </v-card-title>
                    <div>
                        <v-data-table :headers="headers" :items="pacientesAlertas" :search="search" hide-actions
                            :pagination.sync="pagination">
                            <template v-slot:items="props">
                                <td>{{ props.item.citapaciente_id }}</td>
                                <td>{{ props.item.Tipo_Doc }}</td>
                                <td>{{ props.item.Cedula }}</td>
                                <td>{{ props.item.Nombre}}</td>
                                <td v-if="props.item.Sexo == 'M'">Masculino</td>
                                <td v-if="props.item.Sexo == 'F'">Femenino</td>
                                <td>{{props.item.Presionsistolica}}/{{props.item.Presiondiastolica}}</td>
                                <td>{{props.item.Ut}}</td>
                                <td>
                                    <v-chip :class="semaforoDias(props.item)">{{ `${props.item.Dias} DÍA(S)` }}</v-chip>
                                </td>
                                <td>
                                    <div class="text-xs-center">
                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab icon small color="success" v-on="on" @click="asignarCita()">
                                                    <v-icon>calendar_today</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Cita</span>
                                        </v-tooltip>
                                        <v-tooltip bottom>
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab dark small color="red" v-on="on">
                                                    <v-icon dark>assignment_turned_in</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Historia Clinica</span>
                                        </v-tooltip>
                                    </div>
                                </td>
                            </template>
                        </v-data-table>
                        <div class="text-xs-center pt-2">
                            <v-pagination v-model="pagination.page" :length="pages" circle></v-pagination>
                        </div>
                    </div>
                    <component :is='comp' :datosCita="datosCita" />
                </v-card>
            </v-flex>
        </v-layout>
        <v-dialog v-model="dialogcitas" persistent width="1500">
            <component :is='comp' :datosCita="datosCita" @respuestaComponente="dialogcitas=false" />
        </v-dialog>
        <v-dialog v-model="preload" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Estamos procesando su información
                    <v-progress-linear indeterminate color="white" class="mb-0">
                    </v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-container>

</template>
<script>
    import moment from "moment";
    import {
        mapGetters
    } from 'vuex';
    import AsingarCitaComponent from '../../../components/gestion_riesgo/AsingarCita.vue'

    export default {
        name: "Paciente",
        props: {
            datosCita: Object,
            pacientesAlertas: Array,
            inPendingPage: Boolean,
        },
        components: {
            AsingarCitaComponent,
        },
        data() {
            return {
                search: '',
                preload: false,
                comp: null,
                dialogcitas: false,
                alert: ['Presion Arterial'],
                filters: {
                    documento: null,
                    departamento: null,
                    municipio: null,
                    alertas: null,
                },
                pagination: {
                    rowsPerPage: 10,
                },
                selected: [],
                // pacientesAlertas: [],
                dateDesde: moment().format('YYYY-MM-DD'),
                dateHasta: moment().format('YYYY-MM-DD'),
                departamentos: [],
                municipios: [],
                prestadores: [],
                headers: [{
                        text: 'Id Alerta',
                        align: 'left'
                    },
                    {
                        text: 'Tipo Documento',
                        value: 'Tipo_Doc',
                        align: 'left',
                    },
                    {
                        text: 'Documento',
                        value: 'Cedula',
                        align: 'left',
                    },
                    {
                        text: 'Nombre',
                        value: 'Nombre',
                        align: 'left',
                    },
                    {
                        text: 'Sexo',
                        value: 'Sexo',
                        align: 'left',
                    },
                    {
                        text: 'Alerta',
                        align: 'left',
                    },
                    {
                        text: 'UT',
                        value: 'Ut',
                        align: 'left',
                    },
                    {
                        text: 'Semaforo',
                        value: 'Fecha',
                        align: 'left',
                    },
                    {
                        text: 'Aciones',
                        value: 'Aciones',
                        align: 'rigth',
                    }
                ],

            }
        },
        created() {
            this.fetchDepartamentos()
            this.fetchMunicipios()
            this.fetchprestador()

        },
        computed: {
            pages() {
                if (this.pagination.rowsPerPage == null ||
                    this.pagination.totalItems == null
                ) return 0

                return Math.ceil(this.pagination.totalItems / this.pagination.rowsPerPage)
            }
        },
        watch: {
            pacientesAlertas() {
                this.pagination.totalItems = this.pacientesAlertas.length;
            }
        },

        methods: {
            fetctalertas() {
                this.preload = true;
                axios.post('/api/paciente/alertas', this.filters).then(res => {
                    this.pacientesAlertas = res.data;
                    this.preload = false;
                    this.clearFilter();
                }).catch((err) => {
                    console.log(err);
                    this.preload = false;
                });
            },
            asignarCita() {
                this.dialogcitas = true;
                this.comp = 'AsingarCitaComponent';
            },
            fetchDepartamentos() {
                axios.get('/api/departamento/all')
                    .then((res) => {
                        this.departamentos = res.data
                    })
            },
            fetchMunicipios() {
                axios.get('/api/municipio/all')
                    .then(res => {
                        this.municipios = res.data
                    })
            },
            fetchprestador() {
                axios.get('/api/sedeproveedore/sedeproveedores').then(res => {
                    this.prestadores = res.data;
                });
            },
            loadData(items) {
                this.datosCita = {
                    nombre_paciente: items.Primer_Nom + items.Primer_Ape,
                    cita_paciente_id: items.cita_paciente_id,
                    paciente_id: items.paciente_id,
                    paciente_cedula: items.Cedula,
                    Edad_Cumplida: items.Edad_Cumplida,
                    Especialidad: items.Especialidad,
                    Tipo_agenda: items.Tipo_agenda,
                    salud_ocupacional: items.salud_ocupacional,
                    sexo: items.Sexo,
                };

            },
            clearFilter() {
                for (const prop of Object.getOwnPropertyNames(this.filters)) {
                    this.filters[prop] = null;
                }
            },
            semaforoDias(asignadas) {
                if (asignadas.Dias >= 5) {
                    return 'error white--text';
                }
                if (asignadas.Dias <= 2) {
                    return 'success white--text';
                }
                if (asignadas.Dias == 3 || 4) {
                    return 'yellow white--text';
                }
            },
        }
    }

</script>
