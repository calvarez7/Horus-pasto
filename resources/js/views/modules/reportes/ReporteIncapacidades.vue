<template>
    <v-container grid-list-md fluid class="pa-0">
        <v-layout>
            <v-flex xs12>
                <v-card>
                    <v-card-text class="headline success">
                        <h4 style="color:white">Reporte compras y proveedores Vagout</h4>
                    </v-card-text>
                    <v-card-title primary-title>
                        <v-layout row wrap>
                            <v-flex xs4>
                                <v-menu v-model="menu3" :close-on-content-click="false" :nudge-right="40" lazy
                                    transition="scale-transition" offset-y full-width min-width="290px">
                                    <template v-slot:activator="{ on }">
                                        <VTextField v-model="f_inicial" label="Fecha inicial" prepend-icon="event"
                                            readonly v-on="on" />
                                    </template>
                                    <VDatePicker color="primary" :max="f_final" v-model="f_inicial"
                                        @input="menu3 = false" />
                                </v-menu>
                            </v-flex>
                            <v-flex xs3>
                                <v-menu v-model="menu4" :close-on-content-click="false" :nudge-right="40" lazy
                                    transition="scale-transition" offset-y full-width min-width="290px">
                                    <template v-slot:activator="{ on }">
                                        <VTextField v-model="f_final" label="Fecha final" prepend-icon="event"
                                            readonly v-on="on" />
                                    </template>
                                    <VDatePicker color="primary" :min="f_inicial" :max="today"
                                        v-model="f_final" @input="menu4 = false" />
                                </v-menu>
                            </v-flex>
                            <v-flex xs3>
                                <v-select v-model="tipo_informe" :items="tipoInforme" label="Tipo de Informe"
                                    required></v-select>
                            </v-flex>
                            <v-flex xs1>
                                <v-btn color="info" :loading="loading" round @click="reporteIncapacidades()">
                                    Descargar
                                </v-btn>
                            </v-flex>
                        </v-layout>
                    </v-card-title>
                </v-card>
            </v-flex>
        </v-layout>
        <v-dialog v-model="preload" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Tranquilo procesamos tu solicitud !
                    <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-container>
</template>
<script>
    import moment from 'moment';
    import {
        mapGetters
    } from "vuex";
    moment.locale('es');
    export default {
        data() {
            return {
                menu3: false,
                menu4: false,
                preload: false,
                tipoInforme: ['Informe por fecha creación', 'Informe por fecha iniciación'],
                today: moment().format('YYYY-MM-DD'),
                loading: false,
                f_inicial: moment().format('YYYY-MM-DD'),
                f_final: moment().format('YYYY-MM-DD'),
                tipo_informe: ''
            }
        },
        computed: {
            ...mapGetters(['can'])
        },
        methods: {
            reporteIncapacidades() {
                if (this.tipo_informe == '') {
                    this.$alerError("Tipo de informe es obligatorio");
                    return;
                }
                this.preload = true;
                axios({
                    method: 'post',
                    params: {
                        f_inicial: this.f_inicial,
                        f_final: this.f_final,
                        tipo_informe: this.tipo_informe,
                    },
                    url: '/api/incapacidad/reporteIncapacidad',
                    responseType: 'blob'
                }).then(res => {
                        this.preload = false;
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download = `Incapacidades${this.f_inicial}_${this.f_final}.xlsx`;
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
    }

</script>
