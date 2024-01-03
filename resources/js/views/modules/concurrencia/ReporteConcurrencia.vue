<template>
    <div>
        <v-layout>
            <v-flex shrink xs12 mr-1>
                <v-card max-height="100%" class="mb-3">
                    <v-dialog v-model="preload" persistent width="300">
                        <v-card color="primary" dark>
                            <v-card-text>
                                Tranquilo procesamos tu solicitud !
                                <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                    <v-divider></v-divider>
                    <v-card-text>
                        <v-container grid-list-xl text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-autocomplete v-model="filtros.informe" item-value="id" item-text="nombre"
                                        :items="listInformes" label="Selecciona Informe">
                                    </v-autocomplete>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>
                                <v-flex xs4 v-if="filtros.informe == 2">
                                    <v-menu v-model="menu1" :close-on-content-click="false" :nudge-right="40" lazy
                                        transition="scale-transition" offset-y full-width min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <VTextField v-model="filtros.fechaDesde" label="Fecha Desde"
                                                prepend-icon="event" readonly v-on="on" />
                                        </template>
                                        <VDatePicker color="primary" v-model="filtros.fechaDesde"
                                            @input="menu1 = false" />
                                    </v-menu>
                                </v-flex>
                                <v-flex xs4 v-if="filtros.informe == 2">
                                    <v-menu v-model="menu2" :close-on-content-click="false" :nudge-right="40" lazy
                                        transition="scale-transition" offset-y full-width min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <VTextField v-model="filtros.fechaHasta" label="Fecha Hasta"
                                                prepend-icon="event" readonly v-on="on" />
                                        </template>
                                        <VDatePicker color="primary" v-model="filtros.fechaHasta"
                                            @input="menu2 = false" />
                                    </v-menu>
                                </v-flex>
                                <v-flex xs1 offset-xs11>
                                    <v-btn color="success" @click="generarInforme">Descargar</v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
    import {
        mapGetters
    } from "vuex";
    import moment from 'moment';

    export default {
        name: 'reporteConcurrencia',

        data: () => ({
            preload: false,
            menu1: false,
            menu2: false,
            hoy: moment().format('YYYY-MM-DD'),
            listInformes: [{
                id: 1,
                nombre: 'Informe Concurrencia Ingreso',
                can: 'reporte.concurrencia',
                fileName: 'informe_Concurrencia_Ingresos'
            }, {
                id: 2,
                nombre: 'Informe Concurrencia Egreso',
                can: 'reporte.concurrencia',
                fileName: 'informe_Concurrencia_Egresos'
            },{
                id: 3,
                nombre: 'Censo Hospitalario',
                can: 'reporte.censo',
                fileName: 'censo_hospitalario'
            }],
            filtros: {
                fechaDesde: null,
                fechaHasta: null,
                informe: null,
            }
        }),
        computed: {
            ...mapGetters(['can']),
        },
        methods: {
            generarInforme() {
                this.preload = true;
                const name = this.listInformes.filter(obj => obj.id === this.filtros.informe)
                axios({
                    method: 'post',
                    url: '/api/referencia/generarInforme',
                    responseType: 'blob',
                    params: this.filtros
                }).then(res => {
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download = `ConsolidadoIngreso.xlsx`;
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    this.preload = false,
                        this.dialog = false
                    this.clear();
                }).catch(err => {
                    this.preload = false,
                        console.log(err)
                })
            },
            clear() {
                for (const prop of Object.getOwnPropertyNames(this.filtros)) {
                    this.filtros[prop] = null;
                }
            }
        },
    }

</script>

<style>

</style>
