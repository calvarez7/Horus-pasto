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
                                    <v-autocomplete v-model="filtros.resolucion" item-value="id" item-text="nombre"
                                                    :items="listResoluciones" label="Selecciona PQRSFS">
                                    </v-autocomplete>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>
                                <v-flex xs4 v-if="filtros.resolucion === 4">
                                    <v-autocomplete v-model="filtros.entidad" item-value="id" item-text="nombre"
                                                    :items="listTipo" label="Seleccione Tipo">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs4 v-if="filtros.resolucion === 5">
                                    <v-TextField v-model="filtros.CedulaPacientes"  label="Cedula del paciente">
                                    </v-TextField>
                                </v-flex>
                                <v-flex xs4 v-if="filtros.resolucion != 1">
                                    <v-menu v-model="menu1" :close-on-content-click="false" :nudge-right="40" lazy
                                            transition="scale-transition" offset-y full-width min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <VTextField v-model="filtros.fechaDesde" label="Fecha Desde"
                                                        prepend-icon="event" readonly v-on="on"/>
                                        </template>
                                        <VDatePicker color="primary" v-model="filtros.fechaDesde"
                                                     @input="menu1 = false" />
                                    </v-menu>
                                </v-flex>
                                <v-flex xs4 v-if="filtros.resolucion != 1">
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
    import {mapGetters} from "vuex";
    import moment from 'moment';

    export default {
        name: 'reportePqrsfs',

        data: () => ({
            preload: false,
            menu1: false,
            menu2: false,
            hoy: moment().format('YYYY-MM-DD'),
            listTipo: [{
                id: 1,
                nombre: 'Medicamentos'
            },{
                id: 2,
                nombre: 'Servicio'
            },{
                id: 3,
                nombre: 'Area'
            },{
                id: 4,
                nombre: 'Empleado'
            }],
            resoluciones: [{
                id: 1,
                nombre: 'Pendientes PQRSF',
                can: 'reporte.pqrsf',
                fileName: 'PendientePqrsf'
            }, {
                id: 2,
                nombre: 'Informe Global de PQRSF',
                can: 'reporte.pqrsf',
                fileName: 'GlobalPqrsf'
            }, {
                id: 3,
                nombre: 'Reporte de Oportunidad PQRSF',
                can: 'reporte.pqrsf',
                fileName: 'OportunidadPqrsf'
            }, {
                id: 4,
                nombre: 'Reporte de conteo PQRSF',
                can: 'reporte.pqrsf',
                fileName: 'ConteoPqrsf'
            },{
                id: 5,
                nombre: 'Reporte de PQRSF por Paciente',
                can: 'reporte_paciente.pqrsf',
                fileName: 'Historico Paciente Pqrsf'
            },{
                id: 6,
                nombre: 'Reporte de PQRSF por Usuario',
                can: 'reporte_usuario.pqrsf',
                fileName: 'Historico Usuario Pqrsf'
            },{
                id: 7,
                nombre: 'Reporte de PQRSF SuperSalud',
                can: 'reporte.pqrsf',
                fileName: 'Informe Supersalud'
            }],
            resolucion: null,
            filtros: {
                fechaDesde: null,
                fechaHasta: null,
                resolucion: null,
                listTipo: null,
                CedulaPacientes: null,
            }
        }),
        computed: {
            ...mapGetters(['can']),
            listResoluciones: function () {
                let data = [];
                this.resoluciones.forEach(s => {
                    if (this.can(s.can)) {
                        data.push(s)
                    }
                })
                return data
            },
        },
        methods: {
            generarInforme() {
                this.preload = true;
                const name = this.resoluciones.filter(obj => obj.id === this.filtros.resolucion)
                axios({
                    method: 'post',
                    url: '/api/pqrsf/generarInforme',
                    responseType: 'blob',
                    params: this.filtros
                }).then(res => {
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download = name[0].fileName;
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
