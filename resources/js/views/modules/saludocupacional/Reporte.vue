<template>
  <v-layout row wrap>
        <v-flex xs12>
            <v-card>
                <v-card-text class="headline success" style="color:white">
                    <h4 style="color:white">Reporte salud ocupacional</h4>
                </v-card-text>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs4>
                            <v-menu v-model="menu1" :close-on-content-click="false" :nudge-right="40" lazy
                                transition="scale-transition" offset-y full-width min-width="290px">
                                <template v-slot:activator="{ on }">
                                    <VTextField v-model="startDate" label="Fecha inicial" prepend-icon="event" readonly
                                        v-on="on" />
                                </template>
                                <VDatePicker color="primary" :max="finishDate" v-model="startDate"
                                    @input="menu1 = false" />
                            </v-menu>
                        </v-flex>
                        <v-flex xs3>
                            <v-menu v-model="menu2" :close-on-content-click="false" :nudge-right="40" lazy
                                transition="scale-transition" offset-y full-width min-width="290px">
                                <template v-slot:activator="{ on }">
                                    <VTextField v-model="finishDate" label="Fecha final" prepend-icon="event" readonly
                                        v-on="on" />
                                </template>
                                <VDatePicker color="primary" :min="startDate" :max="today" v-model="finishDate"
                                    @input="menu2 = false" />
                            </v-menu>
                        </v-flex>
                        <v-flex xs1>
                            <v-btn color="warning" :loading="loading" round @click="consolidatedReport()">Descargar
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-flex>
  </v-layout>
</template>

<script>
import moment from 'moment';
import Widget from '../../../components/referencia/Widget.vue'
moment.locale('es');
export default {
     name: 'RipsReportes',
        components: {
            Widget,
        },
        data: () => {
            return {
                startDate: moment().format('YYYY-MM-DD'),
                finishDate: moment().format('YYYY-MM-DD'),
                today: moment().format('YYYY-MM-DD'),
            }
        },
        mounted() {
            this.counter();
        },
        methods: {
            consolidatedReport() {
                this.loading = true;
                axios({
                    method: 'post',
                    params: {
                        startDate: this.startDate,
                        finishDate: this.finishDate,
                    },
                    url: '/api/pdf/reporteOcupacional',
                    responseType: 'blob'
                }).then(res => {
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download = `saludOcupacional${this.startDate}_${this.finishDate}.xlsx`;
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    this.loading = false;

                }).catch(err => {
                    this.loading = false;
                    this.showMessage('No hay referencias para descargar')
                })
            },
            showMessage(message) {
                swal({
                    title: "¡Aviso!",
                    text: message,
                    icon: "warning"
                });
            },
            ripsHorus() {
                this.loading = true;
                axios({
                    method: 'post',
                    params: {
                        startDate1: this.startDate1,
                        finishDate1: this.finishDate1,
                        archivo12: this.archivo12,
                        PrestadorTipo: this.PrestadorTipo,
                    },
                    url: '/api/rips/ripsHorus',
                    responseType: 'blob'
                }).then(res => {
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download = `RipsOncologia${this.startDate}_${this.finishDate}.xlsx`;
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    this.loading = false;

                }).catch(err => {
                    this.loading = false;
                    this.showMessage('No hay referencias para descargar')
                })
            },
            showMessage(message) {
                swal({
                    title: "¡Aviso!",
                    text: message,
                    icon: "warning"
                });
            },
            consolidatedRips() {
                this.loading = true;
                axios({
                    method: 'post',
                    params: {
                        fechainicio: this.fechainicio,
                        fechafin: this.fechafin,
                        consolidado: this.consolidado,
                        archivos1: this.archivos1,
                    },
                    url: '/api/rips/consolidadoRips',
                    responseType: 'blob'
                }).then(res => {
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download = `ConsolidadoRips${this.fechainicio}_${this.fechafin}.xlsx`;
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    this.loading = false;

                }).catch(err => {
                    this.loading = false;
                    this.showMessage('No hay ConsolidadoRips para descargar')
                })
            },
            showMessage(message) {
                swal({
                    title: "¡Aviso!",
                    text: message,
                    icon: "warning"
                });
            },
        }
}
</script>

<style>

</style>
