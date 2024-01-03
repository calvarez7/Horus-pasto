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
                                        :items="listResoluciones" label="Selecciona resolucion">
                                    </v-autocomplete>
                                </v-flex>
                            </v-layout>
                            <v-layout row wrap>
                                <v-flex xs12 v-if="filtros.resolucion === 1604 || filtros.resolucion === 1">
                                    <VAutocomplete :items="listBodegasByRole" item-text="Nombre" item-value="id"
                                        label="Bodega" required v-model="filtros.bodega" />
                                </v-flex>
                                <v-flex xs4 v-if="filtros.resolucion === 1604 || filtros.resolucion === 1 || filtros.resolucion === 9">
                                    <v-autocomplete v-model="filtros.entidad" item-value="id" item-text="nombre"
                                        :items="listEntidades" label="Selecciona Entidad">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs12 v-if="filtros.resolucion === 5 || filtros.resolucion === 6">
                                </v-flex>
                                <v-flex xs4>
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
                                <v-flex xs4>
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
                                    <v-btn color="success" @click="generarExcel">Descargar</v-btn>
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
    } from 'vuex';
    export default {
        name: 'reporteResoluciones',
        data: () => ({
            preload: false,
            menu1: false,
            menu2: false,
            listEntidades: [],
            listBodegasByRole: [],
            resoluciones: [{
                id: 256,
                nombre: '256 (Autorización Servicios de Salud)',
                can: 'resolucion.256',
                fileName: 'AutorizaciónServiciosSalud'
            }, {
                id: 1604,
                nombre: '1604 (Gestion de Pendiente)',
                can: 'resolucion.1604',
                fileName: 'GestionPendientes'
            }, {
                id: 1,
                nombre: 'Reporte Dispensados',
                can: 'any',
                fileName: 'ReporteDispensados'
            }, {
                id: 5,
                nombre: 'Reporte Circular 005',
                can: 'circular.005',
                fileName: 'ReporteCircular'
            },{
                id: 6,
                nombre: 'INFORME DE RECAUDO DE CUOTAS MODERADORAS Y COPAGOS REPORTE DE MEDICAMENTOS',
                can: 'recaudos',
                fileName: 'RecaudoMedicamentos'
            },{
                id: 7,
                nombre: 'INFORME DE RECAUDO DE CUOTAS MODERADORAS Y COPAGOS REPORTE DE CONSULTAS',
                can: 'recaudos',
                fileName: 'RecaudoConsultas'
            },{
                id: 8,
                nombre: 'INFORME DE RECAUDO DE CUOTAS MODERADORAS Y COPAGOS REPORTE DE PROCEDIMIENTOS',
                can: 'recaudos',
                fileName: 'RecaudoProcedimientos'
            },{
                id: 9,
                nombre: 'INADECUACIONES',
                can: 'inadecuaciones.ordenes',
                fileName: 'inadecuaciones'
            }
            ],
            resolucion: null,
            filtros: {
                entidad: null,
                fechaDesde: null,
                fechaHasta: null,
                resolucion: null,
                bodega: 0
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
            getEntidades() {
                axios.get('/api/entidades/getEntidades').then(res => {
                    this.listEntidades = res.data;
                });
            },
            generarExcel() {
                this.preload = true;
                const name = this.resoluciones.filter(obj => obj.id === this.filtros.resolucion)
                axios({
                        method: 'post',
                        url: '/api/orden/resolucion',
                        responseType: 'blob',
                        params: this.filtros
                    })
                    .then(res => {
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
                    }).catch(err => {
                        this.preload = false,
                    this.$alerError('No se puede generar, Solo los dias domingos');
                    })
            },
            fetchBodega() {
                axios.get('/api/bodega/all')
                    .then(res => {
                        this.listBodegasByRole = [{
                            Nombre: 'SELECCIONAR TODAS',
                            id: 0
                        }];
                        res.data.forEach(s => {
                            this.listBodegasByRole.push(s);
                        })
                    })
            },
        },
        mounted() {
            this.getEntidades();
            this.fetchBodega();
        }
    };

</script>
