<template>
    <div>
        <v-dialog v-model="dialogDemanda">
            <component :is="comp" :paciente="paciente" modulo="demandaInducida" :valor="demandaInducina.id"
                @cerrarAgendamiento="cerrarAgendaminto()"></component>
        </v-dialog>
        <v-card v-if="can('reporte.demandaInducida')">
            <v-card-title class="headline success" style="color:white">
                <span class="justify-center title layout font-weight-light">Reporte demanda inducida</span>
            </v-card-title>
            <v-form>
                <v-container>
                    <v-layout row wrap>
                        <v-flex xs12 sm6 md3>
                            <v-text-field type="date" v-model="fecha.fecha_inicio" label="Fecha inicial">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md3>
                            <v-text-field type="date" v-model="fecha.fecha_fin" label="Fecha Final">
                            </v-text-field>
                        </v-flex>
                        <v-btn round color="primary" dark @click="reporte_demanda_inducida()">Descargar</v-btn>
                    </v-layout>
                </v-container>
            </v-form>
        </v-card>

        <v-container pa-0 fluid grid-list-md>
            <v-layout row>
                <v-flex xs12>
                    <v-card max-height="100%" class="mb-3">
                        <v-card-title class="headline success" style="color:white">
                            <span class="justify-center title layout font-weight-light">Administracion demanda
                                inducida</span>
                        </v-card-title>
                        <v-container>
                            <v-flex xs5>
                                <v-text-field append-icon="search" hide-details label="Search" single-line
                                    v-model="search" />
                            </v-flex>
                        </v-container>
                        <v-divider></v-divider>
                        <v-data-table :headers="headers" :search="search" :items="demandasInducidas" :loading="false"
                            class="elevation-1">
                            <v-progress-linear color="blue" indeterminate></v-progress-linear>
                            <template v-slot:items="props">
                                <td>{{ props.item.id }}</td>
                                <td class="text-xs-left">{{ props.item.Tipo_Doc }}</td>
                                <td class="text-xs-left">{{ props.item.Num_Doc }}</td>
                                <td class="text-xs-left">{{ props.item.Paciente }}</td>
                                <td class="text-xs-left">{{ props.item.IPS }}</td>
                                <td class="text-xs-left">{{ props.item.tipoDemandaInducida }}</td>
                                <td class="text-xs-left">{{ props.item.programaremitido }}</td>
                                <td class="text-xs-left" v-if="props.item.demanda_inducida_efectiva == '1'">
                                    <v-chip color="green" text-color="white">Si</v-chip>
                                </td>
                                <td class="text-xs-left" v-if="props.item.demanda_inducida_efectiva == '0'">
                                    <v-chip color="red" text-color="white">No</v-chip>
                                </td>
                                <td class="text-xs-center">
                                    <div
                                        v-if="props.item.demanda_inducida_efectiva == '1' && !tipoDemandaInducidaNoAsistidas.find(demanda => demanda === props.item.programaremitido)">
                                        <v-chip v-if="!props.item.Cita_Paciente_1_id && !props.item.estado1">Sin Asignar
                                        </v-chip>
                                        <v-chip
                                            v-else-if="props.item.Cita_Paciente_1_id && parseInt(props.item.estado1) === 4"
                                            color="warning" text-color="white">Por Confirmar</v-chip>
                                        <v-chip
                                            v-else-if="props.item.Cita_Paciente_1_id && (parseInt(props.item.estado1) === 8 || parseInt(props.item.estado1) === 9)"
                                            color="success" text-color="white">Atendida</v-chip>
                                        <v-chip
                                            v-else-if="props.item.Cita_Paciente_1_id && parseInt(props.item.estado1) === 6"
                                            color="error" text-color="white">Cancelada</v-chip>
                                        <v-chip
                                            v-else-if="props.item.Cita_Paciente_1_id && parseInt(props.item.estado1) === 12"
                                            color="error" text-color="white">Inasistencia</v-chip>
                                    </div>
                                </td>
                                <td class="text-xs-center">
                                    <div
                                        v-if="props.item.demanda_inducida_efectiva == '1' && !tipoDemandaInducidaNoAsistidas.find(demanda => demanda === props.item.programaremitido)">
                                        <v-chip v-if="!props.item.Cita_Paciente_2_id && !props.item.estado2">Sin Asignar
                                        </v-chip>
                                        <v-chip
                                            v-else-if="props.item.Cita_Paciente_2_id && parseInt(props.item.estado2) === 4"
                                            color="warning" text-color="white">Por Confirmar</v-chip>
                                        <v-chip
                                            v-else-if="props.item.Cita_Paciente_2_id && (parseInt(props.item.estado2) === 8 || parseInt(props.item.estado2) === 9)"
                                            color="success" text-color="white">Atendida</v-chip>
                                        <v-chip
                                            v-else-if="props.item.Cita_Paciente_2_id && parseInt(props.item.estado2) === 6"
                                            color="error" text-color="white">Cancelada</v-chip>
                                        <v-chip
                                            v-else-if="props.item.Cita_Paciente_2_id && parseInt(props.item.estado2) === 12"
                                            color="error" text-color="white">Inasistencia</v-chip>
                                    </div>
                                </td>
                                <td class="text-xs-center">
                                    <div
                                        v-if="props.item.demanda_inducida_efectiva == '1' && !tipoDemandaInducidaNoAsistidas.find(demanda => demanda === props.item.programaremitido)">
                                        <v-chip v-if="!props.item.Cita_Paciente_3_id && !props.item.estado3">Sin Asignar
                                        </v-chip>
                                        <v-chip
                                            v-else-if="props.item.Cita_Paciente_3_id && parseInt(props.item.estado3) === 4"
                                            color="warning" text-color="white">Por Confirmar</v-chip>
                                        <v-chip
                                            v-else-if="props.item.Cita_Paciente_3_id && (parseInt(props.item.estado3) === 8 || parseInt(props.item.estado3) === 9)"
                                            color="success" text-color="white">Atendida</v-chip>
                                        <v-chip
                                            v-else-if="props.item.Cita_Paciente_3_id && parseInt(props.item.estado3) === 6"
                                            color="error" text-color="white">Cancelada</v-chip>
                                        <v-chip
                                            v-else-if="props.item.Cita_Paciente_3_id && parseInt(props.item.estado3) === 12"
                                            color="error" text-color="white">Inasistencia</v-chip>
                                    </div>
                                </td>
                                <td class="text-xs-center">
                                    <v-btn v-if="habilitarAgenda(props.item)" color="primary" fab small dark
                                        @click="abrirAgenda(props.item)">
                                        <v-icon>event</v-icon>
                                    </v-btn>
                                </td>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>

<script>
    import {
        mapGetters
    } from 'vuex';
    import moment from "moment";
    moment.locale("es");
    import agendamientoDinamico from "../../../components/agendamiento/agendamientoDinamico";
import axios from 'axios';
    export default {
        name: 'HorusHealthDemandaInducida',
        components: {
            agendamientoDinamico
        },
        data() {
            return {
                search: '',
                fecha: {
                    fecha_inicio: '',
                    fecha_fin: ''
                },
                search: '',
                demandaInducina: {},
                dialogDemanda: false,
                paciente: {},
                comp: '',
                headers: [{
                        text: 'Id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Tipo Doc',
                        align: 'left',
                        value: 'Tipo_Doc'
                    },
                    {
                        text: 'Documento',
                        align: 'left',
                        value: 'Num_Doc'
                    },
                    {
                        text: 'Paciente',
                        align: 'left',
                        value: 'Paciente'
                    },
                    {
                        text: 'IPS',
                        align: 'left',
                        value: 'IPS'
                    },
                    {
                        text: 'Tipo',
                        align: 'left',
                        value: 'tipoDemandaInducida'
                    },
                    {
                        text: 'Programa',
                        align: 'left',
                        value: 'programaremitido'
                    },
                    {
                        text: 'Efectiva',
                        align: 'left',
                        value: 'demanda_inducida_efectiva'
                    },
                    {
                        text: 'Consulta 1',
                        align: 'center',
                    },
                    {
                        text: 'Consulta 2',
                        align: 'center',
                    },
                    {
                        text: 'Consulta 3',
                        align: 'center',
                    },
                    {
                        text: 'Acciones',
                        align: 'left',
                    }
                ],
                demandasInducidas: [],
                tipoDemandaInducidaNoAsistidas: ['VACUNACION', 'SALUD ORAL', 'TALLERES EDUCATIVOS', 'CITOLOGIA',
                    'MAMOGRAFIA', 'TAMIZAJE PROSTATA'
                ]
            };
        },

        created() {
            this.consultarDemandaInducida();
        },
        computed: {
            ...mapGetters(['can']),
        },

        methods: {
            consultarDemandaInducida() {
                axios.get("/api/historia/consultarDemandaInducida").then(res => {
                    this.demandasInducidas = res.data
                }).catch(err => {
                    this.preload = false;
                    console.log('datos', err);
                });
            },
            abrirAgenda(item) {
                this.demandaInducina = item;
                this.paciente = {
                    Num_Doc: item.Num_Doc,
                    Tipo_Doc: item.Tipo_Doc,
                    Primer_Nom: item.Primer_Nom,
                    SegundoNom: item.SegundoNom,
                    Primer_Ape: item.Primer_Ape,
                    Segundo_Ape: item.Segundo_Ape,
                    id: item.paciente_id
                };
                this.dialogDemanda = true;
                this.comp = 'agendamientoDinamico';
            },
            cerrarAgendaminto() {
                this.dialogDemanda = false;
                this.consultarDemandaInducida();
            },
            habilitarAgenda(item) {
                const filtroDemandaInducida = this.tipoDemandaInducidaNoAsistidas.find(demanda => demanda === item
                    .programaremitido)
                if (!filtroDemandaInducida) {
                    if (item.demanda_inducida_efectiva === true) {
                        if (!item.Cita_Paciente_1_id && !item.estado1) {
                            return true;
                        } else {
                            if (item.Cita_Paciente_1_id && (parseInt(item.estado1) === 6 || parseInt(item.estado1) ===
                                    12)) {
                                if (!item.Cita_Paciente_2_id && !item.estado2) {
                                    return true;
                                } else {
                                    if (item.Cita_Paciente_2_id && (parseInt(item.estado2) === 6 || parseInt(item
                                            .estado2) === 12)) {
                                        if (!item.Cita_Paciente_3_id && !item.estado3) {
                                            return true;
                                        } else {
                                            false
                                        }

                                    } else {
                                        return false;
                                    }
                                }
                            } else {
                                return false;
                            }
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }

            },
            reporte_demanda_inducida() {
                if (!this.fecha.fecha_inicio) {
                    this.$alerError("Debe seleccionar la fecha inicio!");
                    return
                }if (!this.fecha.fecha_fin) {
                    this.$alerError("Debe seleccionar la fecha final!");
                    return
                }
                axios({
                    method: 'post',
                    params: {
                        f_inicial: this.fecha.fecha_inicio,
                        f_final: this.fecha.fecha_fin,
                    },
                    url: '/api/historia/reporteDemandaInducida',
                    responseType: 'blob'
                }).then(res => {
                        this.preload = false;
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download = `demandaInducida${this.fecha.fecha_inicio}_${this.fecha.fecha_fin}.xlsx`;
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    this.loading = false;

                }).catch(err => {
                    this.loading = false;
                    this.showMessage('No hay referencias para descargar')
                })
            }
        }
    };

</script>

<style lang="scss" scoped>

</style>
