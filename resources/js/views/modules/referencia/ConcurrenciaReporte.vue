<template>
    <v-container fluid grid-list-xl>
        <v-layout row wrap>
            <!-- <v-flex v-for="ref in referencias" :key="ref.color" d-flex lg3 sm6 xs12>
                <Widget
                    :color="ref.color"
                    :icon="ref.icon"
                    :title="ref.title"
                    :subTitle="ref.subTitle"
                    :supTitle="ref.supTitle" />
            </v-flex> -->
            <v-flex xs6>
                <v-card>
                    <v-card-text>
                        <v-container class="pa-0">
                            <v-layout row wrap>
                                <v-flex xs4>
                                    <v-menu
                                        v-model="menu1"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        lazy
                                        transition="scale-transition"
                                        offset-y
                                        full-width
                                        min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <VTextField
                                                v-model="startDate"
                                                label="Fecha inicial"
                                                prepend-icon="event"
                                                readonly
                                                v-on="on" />
                                        </template>
                                        <VDatePicker
                                            color="primary"
                                            :max="finishDate"
                                            v-model="startDate"
                                            @input="menu1 = false" />
                                    </v-menu>
                                </v-flex>
                                <v-flex xs4>
                                    <v-menu
                                        v-model="menu2"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        lazy
                                        transition="scale-transition"
                                        offset-y
                                        full-width
                                        min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <VTextField
                                                v-model="finishDate"
                                                label="Fecha final"
                                                prepend-icon="event"
                                                readonly
                                                v-on="on" />
                                        </template>
                                        <VDatePicker
                                            color="primary"
                                            :min="startDate"
                                            :max="today"
                                            v-model="finishDate"
                                            @input="menu2 = false" />
                                    </v-menu>
                                </v-flex>
                                <v-flex xs4>
                                    <v-btn
                                        color="warning"
                                        :loading="loading"
                                        round
                                        @click="consolidatedReport()">Descargar</v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import moment from 'moment';
    import Widget from '../../../components/referencia/Widget.vue'

    moment.locale('es');

    export default {
        name: 'ReferenciaReporte',
        components:{
            Widget,
        },
        data: () => {
            return {
                count: {
                    _a2: 0,
                    _a3: 0,
                    _a9: 0
                },
                finishDate: moment().format('YYYY-MM-DD'),
                loading: false,
                menu1: false,
                menu2: false,
                referencias: [
                    {
                        color: '#00b297',
                        icon: 'history',
                        title: '0',
                        subTitle:  'Total ingresados',
                        supTitle: `Hasta la fecha`,
                    },{
                        color: '#f80',
                        icon: 'watch_later',
                        title: '0',
                        subTitle:  'En proceso',
                        supTitle: `Gestión`,
                    },
                    {
                        color: '#00c851',
                        icon: 'archive',
                        title: '0',
                        subTitle:  'Proceso interno',
                        supTitle: `En concurrencia`,
                    },
                    {
                        color: '#dc3545',
                        icon: 'notification_important',
                        title: '0',
                        subTitle:  'Pendiente',
                        supTitle: `Pendiente`,
                    }
                ],
                startDate: moment().format('YYYY-MM-DD'),
                today: moment().format('YYYY-MM-DD'),
            }
        },
        mounted(){
            this.counter();
        },
        methods:{
            counter(anexo){
                axios.get('/api/referencia/counter')
                    .then(res => {
                        this.referencias[0].title = `${res.data.total}`;
                        this.referencias[1].title = `${res.data.gestion}`;
                        this.referencias[2].title = `${res.data.concurrencia}`;
                        this.referencias[3].title = `${res.data.pendientes}`;
                    })
            },
            consolidatedReport(){
                this.loading = true;
                axios({
                    method: 'get',
                    params: {
                        startDate: this.startDate,
                        finishDate: this.finishDate,
                    },
                    url: '/api/referencia/concurrenciaReporte',
                    responseType: 'blob'
                }).then(res => {
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download = `reporte_Concurrencia${this.startDate}_${this.finishDate}.xlsx`;
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

        }
    }
</script>

<style lang="scss" scoped>
    .buttom-nav-anexo {
        width: 30% !important;
        margin: 0 35% !important;
        border-radius: 40px;
        /* border-radius: 25px 25px 0 0; */
    }
</style>
