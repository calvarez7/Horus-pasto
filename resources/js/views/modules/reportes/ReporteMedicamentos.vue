<template>
    <div>
        <v-layout>
            <v-flex shrink xs12 mr-1>
                <v-card max-height="100%" class="mb-3">
                    <v-card-title class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">Reportes del servicio
                            farmaceutico</span>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-card-text>
                        <v-container grid-list-xl text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs5 offset-xs3>
                                    <v-autocomplete v-model="tipoReport" item-value="name" :items="tipoReporte"
                                        label="Selecciona tipo de reporte">
                                        <template v-slot:selection="{item}">
                                            {{ item.name }}
                                        </template>
                                        <template v-slot:item="{item}">
                                            <v-list-tile-content v-if="can(item.can)" v-text="item.name">
                                            </v-list-tile-content>
                                        </template>
                                    </v-autocomplete>
                                </v-flex>
                            </v-layout>

                            <v-layout row wrap >
                                <v-flex xs12 sm6 md2>
                                    <v-text-field type="date" v-model="fecha.fecha_inicio" label="Fecha inicial">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md2>
                                    <v-text-field type="date" v-model="fecha.fecha_fin" label="Fecha Final">
                                    </v-text-field>
                                </v-flex>
                                <v-btn round color="primary" dark @click="exportReportMedicamentos()">Descargar</v-btn>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>

        <v-dialog v-model="preload_datos" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Estamos procesando su información
                    <v-progress-linear indeterminate color="white" class="mb-0">
                    </v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
    import {
        mapGetters
    } from 'vuex';
    export default {
        name: 'reporteMedicamentos',
        data: () => ({
            preload_datos: false,
            tipoReport: '',
            tipoReporte: [{
                    name: 'Entradas por facturas',
                    can: 'entardasFacturas.enter'
                }, {
                    name: 'Entrega medicamentos',
                    can: 'reportes.medicamentos'
                },
                {
                    name: 'Existencias',
                    can: 'kardex2.enter'
                },
                {
                    name: 'prueba',
                    can: 'kardex4.enter'
                },
            ],
            fecha: {
                fecha_inicio: '',
                fecha_fin: '',
            },

        }),
        computed: {
            ...mapGetters(['can']),
        },
        methods: {
            exportReportMedicamentos() {
                if (this.tipoReport == 'Entradas por facturas') {
                    this.preload_datos = true
                    axios.post('/api/detallearticulo/exportVademecum', this.fecha, {
                        responseType: "arraybuffer"
                    }).then(res => {
                        let blob = new Blob([res.data], {
                            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                        });
                        let url = window.URL.createObjectURL(blob);
                        let a = document.createElement('a');

                        a.download = `entradaPorFactura_${this.fecha.fecha_inicio}_${this.fecha.fecha_fin}.xlsx`;
                        a.href = url;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        this.preload_datos = false;
                        this.clearFilter();
                    });
                } else if (this.tipoReport == 'Entrega medicamentos') {
                    this.preload = true;
                    axios({
                        method: 'post',
                        url: '/api/reporteMedicamentos/reporteEntregaMedicamentos',
                        responseType: 'blob',
                        params: this.fecha
                    }).then(res => {
                        let blob = new Blob([res.data], {
                            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                        });
                        let url = window.URL.createObjectURL(blob);
                        let a = document.createElement('a');

                        a.download = `entregaMedicamentos_${this.fecha.fecha_inicio}_${this.fecha.fecha_fin}.xlsx`;
                        a.href = url;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        this.preload = false
                    }).catch(err => {
                        this.preload = false,
                            console.log(err)
                    })
                }
            },
        }
    };

</script>

<style lang="scss" scoped>
</style>
