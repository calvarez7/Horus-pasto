<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-card>
                <v-card-text class="headline success" style="color:white">
                    <h4 style="color:white">Reporte financiero RIPS</h4>
                </v-card-text>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs4 px-2>
                            <v-select :items="entidad" v-model="financiero" label="Selecciona la Entidad..."></v-select>
                        </v-flex>
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
        <hr>
        <br><v-flex xs12>
            <v-card>
                <v-card-text class="headline info" style="color:white">
                    <h4 style="color:white">Reporte RIPS Oncología</h4>
                </v-card-text>
                <v-card-text>
                    <v-layout row wrap>
                        <!-- <v-flex xs2 px-2>
                            <v-select :items="Prestadors" item-text="nombre" item-value="valor" v-model="PrestadorTipo" label="Selecciona Prestador..."></v-select>
                        </v-flex> -->
                        <v-flex xs2 px-2>
                            <v-select :items="archivosOnco" v-model="archivo12" label="Selecciona Archivo..."></v-select>
                        </v-flex>
                        <v-flex xs4>
                            <v-menu v-model="menu5" :close-on-content-click="false" :nudge-right="40" lazy
                                transition="scale-transition" offset-y full-width min-width="290px">
                                <template v-slot:activator="{ on }">
                                    <VTextField v-model="startDate1" label="Fecha inicial" prepend-icon="event" readonly
                                        v-on="on" />
                                </template>
                                <VDatePicker color="primary" :max="finishDate1" v-model="startDate1"
                                    @input="menu5 = false" />
                            </v-menu>
                        </v-flex>
                        <v-flex xs3>
                            <v-menu v-model="menu6" :close-on-content-click="false" :nudge-right="40" lazy
                                transition="scale-transition" offset-y full-width min-width="290px">
                                <template v-slot:activator="{ on }">
                                    <VTextField v-model="finishDate1" label="Fecha final" prepend-icon="event" readonly
                                        v-on="on" />
                                </template>
                                <VDatePicker color="primary" :min="startDate1" :max="today" v-model="finishDate1"
                                    @input="menu6 = false" />
                            </v-menu>
                        </v-flex>
                        <v-flex xs1>
                            <v-btn color="warning" :loading="loading" round @click="ripsHorus()">Descargar
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-flex>
        <hr>
        <br>
        <v-flex xs12>
            <v-card>
                <v-card-text class="headline warning" style="color:white">
                    <h4 style="color:white">Reporte archivo consolidado RIPS</h4>
                </v-card-text>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs2 px-2>
                            <v-select :items="entidad" v-model="consolidado" label="Selecciona Archivo..."></v-select>
                        </v-flex>
                        <v-flex xs2 px-2>
                            <v-select :items="archivos" v-model="archivos1" label="Selecciona Archivo..."></v-select>
                        </v-flex>
                        <v-flex xs2>
                            <v-menu v-model="menu3" :close-on-content-click="false" :nudge-right="40" lazy
                                transition="scale-transition" offset-y full-width min-width="290px">
                                <template v-slot:activator="{ on }">
                                    <VTextField v-model="fechainicio" label="Fecha inicial" prepend-icon="event"
                                        readonly v-on="on" />
                                </template>
                                <VDatePicker color="primary" :max="fechafin" v-model="fechainicio"
                                    @input="menu3 = false" />
                            </v-menu>
                        </v-flex>
                        <v-flex xs2>
                            <v-menu v-model="menu4" :close-on-content-click="false" :nudge-right="40" lazy
                                transition="scale-transition" offset-y full-width min-width="290px">
                                <template v-slot:activator="{ on }">
                                    <VTextField v-model="fechafin" label="Fecha final" prepend-icon="event" readonly
                                        v-on="on" />
                                </template>
                                <VDatePicker color="primary" :min="fechainicio" :max="today" v-model="fechafin"
                                    @input="menu4 = false" />
                            </v-menu>
                        </v-flex>
                        <v-flex xs1 px-2>
                            <v-btn color="warning" :loading="loading" round @click="consolidatedRips()">Descargar
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-flex>
        <hr>
        <br>
        <v-flex xs12>
            <v-card>
                <v-card-text class="headline cyan" style="color:white">
                    <h4 style="color:white">Reporte archivo consolidado RIPS (JSON)</h4>
                </v-card-text>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs2 px-2>
                            <v-select :items="entidad" v-model="consolidadoJson" label="Selecciona Archivo..."></v-select>
                        </v-flex>
                        <v-flex xs2 px-2>
                            <v-select :items="archivosJson" v-model="archivosJson1" label="Selecciona Archivo..."></v-select>
                        </v-flex>
                        <v-flex xs2>
                            <v-menu v-model="menu7" :close-on-content-click="false" :nudge-right="40" lazy
                                    transition="scale-transition" offset-y full-width min-width="290px">
                                <template v-slot:activator="{ on }">
                                    <VTextField v-model="fechainicioJson" label="Fecha inicial" prepend-icon="event"
                                                readonly v-on="on" />
                                </template>
                                <VDatePicker color="primary" :max="fechafinJson" v-model="fechainicioJson"
                                             @input="menu7 = false" />
                            </v-menu>
                        </v-flex>
                        <v-flex xs2>
                            <v-menu v-model="menu8" :close-on-content-click="false" :nudge-right="40" lazy
                                    transition="scale-transition" offset-y full-width min-width="290px">
                                <template v-slot:activator="{ on }">
                                    <VTextField v-model="fechafinJson" label="Fecha final" prepend-icon="event" readonly
                                                v-on="on" />
                                </template>
                                <VDatePicker color="primary" :min="fechainicioJson" :max="today" v-model="fechafinJson"
                                             @input="menu8 = false" />
                            </v-menu>
                        </v-flex>
                        <v-flex xs1 px-2>
                            <v-btn color="warning" :loading="loading" round @click="consolidatedRipsJson()">Descargar
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-flex>
    </v-layout>

    <!-- </v-layout>
    </v-container> -->
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
                archivosJson1:'',
                consolidadoJson:'',
                consolidado: '',
                financiero: '',
                archivos1: '',
                archivo12: '',
                entidad: ['RES004', 'EAS027','EAS027-1'],
                PrestadorTipo: '',
                Prestadors: [{
                    nombre: 'Oncologia',
                    valor: 7
                }],
                archivos: ['AF', 'AT', 'AC', 'AP', 'AU', 'AN', 'AM', 'AH', 'US','CT'],
                archivosJson:['Consultas','Procedimientos','Urgencias','Hospitalizacion','RecienNacidos','Medicamentos','OtrosServicios'],
                archivosOnco: ['AC','AM'],
                count: {
                    _a2: 0,
                    _a3: 0,
                    _a9: 0
                },
                loading: false,
                menu1: false,
                menu2: false,
                menu3: false,
                menu4: false,
                menu5: false,
                menu6: false,
                menu7: false,
                menu8: false,
                referencias: [{
                        color: '#00b297',
                        icon: 'history',
                        title: '0',
                        subTitle: 'Total ingresados',
                        supTitle: `Hasta la fecha`,
                    }, {
                        color: '#f80',
                        icon: 'watch_later',
                        title: '0',
                        subTitle: 'En proceso',
                        supTitle: `Gestión`,
                    },
                    {
                        color: '#00c851',
                        icon: 'archive',
                        title: '0',
                        subTitle: 'Proceso interno',
                        supTitle: `En concurrencia`,
                    },
                    {
                        color: '#dc3545',
                        icon: 'notification_important',
                        title: '0',
                        subTitle: 'Pendiente',
                        supTitle: `Pendiente`,
                    }
                ],
                startDate: moment().format('YYYY-MM-DD'),
                finishDate: moment().format('YYYY-MM-DD'),
                startDate1: moment().format('YYYY-MM-DD'),
                finishDate1: moment().format('YYYY-MM-DD'),
                fechainicio: moment().format('YYYY-MM-DD'),
                fechafin: moment().format('YYYY-MM-DD'),
                fechafinJson: moment().format('YYYY-MM-DD'),
                fechainicioJson: moment().format('YYYY-MM-DD'),
                today: moment().format('YYYY-MM-DD'),
            }
        },
        mounted() {
            this.counter();
        },
        methods: {
            counter(anexo) {
                axios.get('/api/referencia/counter')
                    .then(res => {
                        this.referencias[0].title = `${res.data.total}`;
                        this.referencias[1].title = `${res.data.gestion}`;
                        this.referencias[2].title = `${res.data.concurrencia}`;
                        this.referencias[3].title = `${res.data.pendientes}`;
                    })
            },
            consolidatedReport() {
                this.loading = true;
                axios({
                    method: 'post',
                    params: {
                        startDate: this.startDate,
                        finishDate: this.finishDate,
                        consolidado: this.consolidado,
                        financiero: this.financiero,
                    },
                    url: '/api/rips/reporteRips',
                    responseType: 'blob'
                }).then(res => {
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download = `TeleorientacionConsolidado${this.startDate}_${this.finishDate}.xlsx`;
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
                        archivos1: this.archivos1
                    },
                    url: '/api/rips/consolidadoRips',
                    responseType: 'blob'
                }).then(res => {
                    if(this.archivos1 === 'CT'){
                        console.log("hola",this.archivos1);
                        const blob = new Blob([res.data], {
                            type: "text/plain"
                        });
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        const regex = /-/gi;
                        a.download = "CT.TXT"
                        a.href = url;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        this.loading = false;
                    }else {


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

                    }


                }).catch(err => {
                    this.loading = false;
                    this.showMessage('No hay ConsolidadoRips para descargar')
                })
            },
            consolidatedRipsJson(){
                this.loading = true;
                axios({
                    method: 'post',
                    params: {
                        fechainicio: this.fechainicioJson,
                        fechafin: this.fechafinJson,
                        consolidado: this.consolidadoJson,
                        archivos1: this.archivosJson1
                    },
                    url: '/api/rips/consolidadoRipsJson',
                    responseType: 'blob'
                }).then(res => {
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download = `ConsolidadoRipsJSON${this.archivosJson1}${this.fechainicio}_${this.fechafin}.xlsx`;
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    this.loading = false;

                }).catch(err => {
                    this.loading = false;
                    this.showMessage('No hay ConsolidadoRips para descargar')
                })
            }
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
