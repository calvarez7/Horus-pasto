<template>
    <div>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout>
                <v-flex xs12 sm12 md12>
                    <v-card>
                        <v-card-text class="headline success">
                            <h4 style="color:white">Transplantes</h4>
                        </v-card-text>
                        <v-card-title primary-title>
                            <v-layout row wrap>
                                <v-flex xs12 sm5 md3>
                                    <v-select v-model="consultaFiltrada.entidad" :items="items" item-value="id"
                                        label="Entidad">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm5 md4>
                                    <v-autocomplete v-model="consultaFiltrada.sede_id" append-icon="search"
                                        :items="sedesIPS" item-text="Nombre" item-value="id" label="sedes">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm1 md1>
                                    <v-btn outline fab color="teal" @click="consultar()">
                                        <v-icon>mdi-search-web</v-icon>
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-card-title>
                    </v-card>
                </v-flex>
            </v-layout>
            <v-layout>
                <v-flex v-for="ref in referencias" :key="ref.color" d-flex lg3 sm6 xs12>
                    <Widget :color="ref.color" :icon="ref.icon" :title="ref.title" :subTitle="ref.subTitle"
                        :supTitle="ref.supTitle" />
                </v-flex>
            </v-layout>
            <v-layout>
                <v-flex xs12 sm12 md12>
                    <v-card>
                        <v-card-title primary-title>
                            <v-layout row wrap>
                                <v-flex xs12 sm12 md12>
                                    <v-card-title>
                                        <v-btn v-if="can('fiasF4.report')" color="purple" dark @click="estadistica()">
                                            Estadistica
                                            <v-icon dark right>mdi-chart-line</v-icon>
                                        </v-btn>
                                    </v-card-title>
                                    <v-card>
                                        <v-card-title>
                                            <v-spacer></v-spacer>
                                            <v-text-field v-model="search" append-icon="search" label="Search"
                                                single-line hide-details></v-text-field>
                                        </v-card-title>
                                        <v-data-table :headers="headers" :items="transplantesData" :search="search">
                                            <template v-slot:items="props">
                                                <td>{{props.item.Num_Doc}}</td>
                                                <td>{{props.item.Nombre}}</td>
                                                <td>{{props.item.descripcion_del_cie_10}}</td>
                                                <td>{{props.item.el_usuario_tiene_mas_de_1_trasplante}}</td>
                                                <td>{{props.item.fecha_de_solicitud_de_trasplante}}</td>
                                                <td>{{props.item.fecha_de_trasplante}}</td>
                                                <td>{{props.item.fecha_ùltimo_control}}
                                                </td>
                                                <td>
                                                    <v-tooltip top>
                                                        <template v-slot:activator="{ on }">
                                                            <v-btn small text icon color="warning" dark v-on="on"
                                                                disabled>
                                                                <v-icon @click="modalFias(props.item)">
                                                                    mdi-message-draw
                                                                </v-icon>
                                                            </v-btn>
                                                        </template>
                                                        <span>Ver paciente</span>
                                                    </v-tooltip>
                                                </td>
                                            </template>
                                            <template v-slot:no-results>
                                                <v-alert :value="true" color="error" icon="warning">
                                                    Your search for "{{ search }}" found no results.
                                                </v-alert>
                                            </template>
                                        </v-data-table>
                                    </v-card>
                                </v-flex>
                            </v-layout>
                        </v-card-title>
                    </v-card>
                </v-flex>
            </v-layout>
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
        </v-container>
        <!-- MODAL DE GRILLA PROGRAMAS ESPECIALES -->
        <v-dialog v-model="dialogEstadistica" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable>
            <v-card tile>
                <v-toolbar card dark color="primary">
                    <v-btn icon dark @click="dialogEstadistica = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Estadistica Programas especiales</v-toolbar-title>
                    <v-spacer></v-spacer>
                </v-toolbar>
                <v-card-text>
                    <v-flex xs12 sm12 md12>
                        <iframe title="13 Sistemas de información - Sistemas información" width="1324px" height="612"
                            src="https://app.powerbi.com/view?r=eyJrIjoiNGJhOGE0MWYtNGQ0NC00MGFmLWIyOGUtNDVkNGQ3M2ZlYjAxIiwidCI6IjQ4NzRlMWJhLTU2ZGItNDc5Mi1iMDUyLTRlMmUyOWJlMjk5MyJ9"
                            frameborder="0" allowFullScreen="true"></iframe>
                    </v-flex>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
    import moment from 'moment';
    import Widget from '../../../components/referencia/Widget.vue'
    import {
        mapGetters
    } from "vuex";
    moment.locale('es');
    export default {
        components: {
            Widget,
        },
        data() {
            return {
                items: ['REDVITAL UT', 'FERROCARRILES NACIONALES'],
                dialog: false,
                preload: false,
                modalHistoricoSignos: false,
                currentPage: 1,
                pages: 1,
                search: '',
                pagination: {},
                selected: [],
                headers: [{
                        text: 'CC - Paciente',
                        value: 'Num_Doc'
                    },
                    {
                        text: 'IPS'
                    },
                    {
                        text: 'Cie_10'
                    },
                    {
                        text: 'Mas de 1 trasplante'
                    },
                    {
                        text: 'f.solicitud trasplante'
                    },
                    {
                        text: 'F.trasplante'
                    },
                    {
                        text: 'F.ùltimo control'
                    },
                ],
                transplantesData: [],
                referencias: [{
                    color: '#00b297',
                    icon: 'mdi-account-convert',
                    title: '0',
                    subTitle: 'Transplantes'
                }, ],
                dialogEstadistica: false,
                consultaFiltrada: {
                    entidad: '',
                    f_inicio: '',
                    f_final: '',
                    sede_id: ''
                },
                sedesIPS: [],
                historicoSignos: {
                    cedula: ''
                },
                allSignosVitales: [],
                datosPacienteHistorico: []
            }
        },
        mounted() {
            this.sedes();
        },
        computed: {
            ...mapGetters(['can']),
        },
        methods: {
            fiasTransplantados() {
                this.preload = true;
                axios.post('/api/fias/allTransplantes', this.consultaFiltrada)
                    .then(res => {
                        console.log('c r', res.data)
                        this.transplantesData = res.data;
                        this.preload = false;
                    }).catch(e => {
                        console.log(e);
                        this.preload = false;
                    })
            },
            estadistica() {
                this.dialogEstadistica = true
            },
            consultar() {
                axios.post('/api/fias/consultTransplantes', this.consultaFiltrada).then(res => {
                    this.referencias[0].title = `${res.data.transplantes}`;
                });
                this.fiasTransplantados()
            },
            sedes() {
                axios.get('/api/fias/sedes').then(res => {
                    this.sedesIPS = res.data
                })
            }
        }
    }

</script>
