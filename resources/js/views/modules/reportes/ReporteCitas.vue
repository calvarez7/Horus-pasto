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
                            <v-card-title class="headline success" style="color:white">
                                <span class="title layout justify-center font-weight-light">Reporte citas
                                </span>
                            </v-card-title>
                            <v-layout row wrap>
                                <v-flex xs6>
                                    <v-text-field v-model="fecha.fechaInicio" label="Fecha inicio" type="date">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs6>
                                    <v-text-field v-model="fecha.fechaFin" label="Fecha Fin" type="date">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs1>
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
        name: 'reporteCitas',

        data: () => ({
            preload: false,
            fecha: {
                fechaInicio: '',
                fechaFin: '',
            }
        }),
        computed: {
            ...mapGetters(['can']),

        },
        methods: {
            generarInforme() {
                this.preload = true;
                axios({
                    method: 'post',
                    url: '/api/reporteCita/reporteCitas',
                    responseType: 'blob',
                     params: this.fecha
                }).then(res => {
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download = `Reporte.xlsx`;
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

</script>

<style>

</style>
