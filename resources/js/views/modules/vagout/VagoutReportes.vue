<template>
    <v-layout justify-center>
        <v-container fluid grid-list-lg>
            <v-layout row wrap>
                <v-flex xs4>
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
                                            <VTextField v-model="startDate" label="Fecha inicial" prepend-icon="event"
                                                readonly v-on="on" />
                                        </template>
                                        <VDatePicker color="primary" :max="finishDate" v-model="startDate"
                                            @input="menu3 = false" />
                                    </v-menu>
                                </v-flex>
                                <v-flex xs3>
                                    <v-menu v-model="menu4" :close-on-content-click="false" :nudge-right="40" lazy
                                        transition="scale-transition" offset-y full-width min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <VTextField v-model="finishDate" label="Fecha final" prepend-icon="event"
                                                readonly v-on="on" />
                                        </template>
                                        <VDatePicker color="primary" :min="startDate" :max="today" v-model="finishDate"
                                            @input="menu4 = false" />
                                    </v-menu>
                                </v-flex>
                                <v-flex xs1>
                                    <v-btn color="info" :loading="loading" round @click="registroInventario(1)">
                                        Descargar
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-card-title>
                    </v-card>
                </v-flex>
                <!-- <v-flex xs4>
                    <v-card>
                        <v-card-text class="headline success" style="color:white">
                            <h4 style="color:white">Archivo plano de compras Vagout</h4>
                        </v-card-text>
                        <v-card-title primary-title>
                            <v-layout row wrap>
                                <v-flex xs4>
                                    <v-menu v-model="menu5" :close-on-content-click="false" :nudge-right="40" lazy
                                        transition="scale-transition" offset-y full-width min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <VTextField v-model="startDate" label="Fecha inicial" prepend-icon="event"
                                                readonly v-on="on" />
                                        </template>
                                        <VDatePicker color="primary" :max="finishDate" v-model="startDate"
                                            @input="menu5 = false" />
                                    </v-menu>
                                </v-flex>
                                <v-flex xs3>
                                    <v-menu v-model="menu6" :close-on-content-click="false" :nudge-right="40" lazy
                                        transition="scale-transition" offset-y full-width min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <VTextField v-model="finishDate" label="Fecha final" prepend-icon="event"
                                                readonly v-on="on" />
                                        </template>
                                        <VDatePicker color="primary" :min="startDate" :max="today" v-model="finishDate"
                                            @input="menu6 = false" />
                                    </v-menu>
                                </v-flex>
                                <v-flex xs1>
                                    <v-btn color="info" :loading="loading" round @click="registroInventario(2)">
                                        Descargar
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-card-title>
                    </v-card>
                </v-flex> -->
                <v-flex xs4>
                    <v-card>
                        <v-card-text class="headline info" style="color:white">
                            <h4 style="color:white">Reporte ventas Vagout</h4>
                        </v-card-text>
                        <v-card-title primary-title>
                            <v-layout row wrap>
                                <v-flex xs4>
                                    <v-menu v-model="menu7" :close-on-content-click="false" :nudge-right="40" lazy
                                        transition="scale-transition" offset-y full-width min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <VTextField v-model="startDate" label="Fecha inicial" prepend-icon="event"
                                                readonly v-on="on" />
                                        </template>
                                        <VDatePicker color="primary" :max="finishDate" v-model="startDate"
                                            @input="menu7 = false" />
                                    </v-menu>
                                </v-flex>
                                <v-flex xs3>
                                    <v-menu v-model="menu8" :close-on-content-click="false" :nudge-right="40" lazy
                                        transition="scale-transition" offset-y full-width min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <VTextField v-model="finishDate" label="Fecha final" prepend-icon="event"
                                                readonly v-on="on" />
                                        </template>
                                        <VDatePicker color="primary" :min="startDate" :max="today" v-model="finishDate"
                                            @input="menu8 = false" />
                                    </v-menu>
                                </v-flex>
                                <v-flex xs1>
                                    <v-btn color="info" :loading="loading" round @click="registroInventario(3)">
                                        Descargar
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-card-title>
                    </v-card>
                </v-flex>
                <v-flex xs4>
                    <v-card>
                        <v-card-text class="headline warning" style="color:white">
                            <h4 style="color:white">Reporte consumo inventario Vagout</h4>
                        </v-card-text>
                        <v-card-title primary-title>
                            <v-layout row wrap>
                                <v-flex xs4>
                                    <v-menu v-model="menu9" :close-on-content-click="false" :nudge-right="40" lazy
                                        transition="scale-transition" offset-y full-width min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <VTextField v-model="startDate" label="Fecha inicial" prepend-icon="event"
                                                readonly v-on="on" />
                                        </template>
                                        <VDatePicker color="primary" :max="finishDate" v-model="startDate"
                                            @input="menu9 = false" />
                                    </v-menu>
                                </v-flex>
                                <v-flex xs3>
                                    <v-menu v-model="menu10" :close-on-content-click="false" :nudge-right="40" lazy
                                        transition="scale-transition" offset-y full-width min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <VTextField v-model="finishDate" label="Fecha final" prepend-icon="event"
                                                readonly v-on="on" />
                                        </template>
                                        <VDatePicker color="primary" :min="startDate" :max="today" v-model="finishDate"
                                            @input="menu10 = false" />
                                    </v-menu>
                                </v-flex>
                                <v-flex xs1>
                                    <v-btn color="info" :loading="loading" round @click="registroInventario(4)">
                                        Descargar
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-card-title>
                    </v-card>
                </v-flex>
                <v-flex xs4>
                    <v-card>
                        <v-card-text class="headline warning" style="color:white">
                            <h4 style="color:white">Reporte consumo inventario Vagout</h4>
                        </v-card-text>
                        <v-card-title primary-title>
                            <v-layout row wrap>
                                <v-flex xs4>
                                    <v-menu v-model="menu9" :close-on-content-click="false" :nudge-right="40" lazy
                                        transition="scale-transition" offset-y full-width min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <VTextField v-model="startDate" label="Fecha inicial" prepend-icon="event"
                                                readonly v-on="on" />
                                        </template>
                                        <VDatePicker color="primary" :max="finishDate" v-model="startDate"
                                            @input="menu9 = false" />
                                    </v-menu>
                                </v-flex>
                                <v-flex xs3>
                                    <v-menu v-model="menu10" :close-on-content-click="false" :nudge-right="40" lazy
                                        transition="scale-transition" offset-y full-width min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <VTextField v-model="finishDate" label="Fecha final" prepend-icon="event"
                                                readonly v-on="on" />
                                        </template>
                                        <VDatePicker color="primary" :min="startDate" :max="today" v-model="finishDate"
                                            @input="menu10 = false" />
                                    </v-menu>
                                </v-flex>
                                <v-flex xs1>
                                    <v-btn color="info" :loading="loading" round @click="registroInventario(4)">
                                        Descargar
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-card-title>
                    </v-card>
                </v-flex>
                <v-flex xs4 v-if="can('deduccion.nomina')">
                    <v-card>
                        <v-card-text class="headline red" style="color:white">
                            <h4 style="color:white">Reporte dedución nomina</h4>
                        </v-card-text>
                        <v-card-title primary-title>
                            <v-layout row wrap>
                                <v-flex xs4>
                                    <v-menu v-model="menu9" :close-on-content-click="false" :nudge-right="40" lazy
                                        transition="scale-transition" offset-y full-width min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <VTextField v-model="startDate" label="Fecha inicial" prepend-icon="event"
                                                readonly v-on="on" />
                                        </template>
                                        <VDatePicker color="primary" :max="finishDate" v-model="startDate"
                                            @input="menu9 = false" />
                                    </v-menu>
                                </v-flex>
                                <v-flex xs3>
                                    <v-menu v-model="menu10" :close-on-content-click="false" :nudge-right="40" lazy
                                        transition="scale-transition" offset-y full-width min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <VTextField v-model="finishDate" label="Fecha final" prepend-icon="event"
                                                readonly v-on="on" />
                                        </template>
                                        <VDatePicker color="primary" :min="startDate" :max="today" v-model="finishDate"
                                            @input="menu10 = false" />
                                    </v-menu>
                                </v-flex>
                                <v-flex xs1>
                                    <v-btn color="info" :loading="loading" round @click="registroInventario(7)">
                                        Descargar
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-card-title>
                    </v-card>
                </v-flex>
                <!-- <v-flex xs4>
                    <v-card>
                        <v-card-text class="headline success" style="color:white">
                            <h4 style="color:white">Reporte inventario final</h4>
                        </v-card-text>
                        <v-card-title primary-title>
                            <v-layout row wrap>
                                <v-flex xs1>
                                    <v-btn color="info" :loading="loading" round @click="registroInventario(5)">
                                        Descargar
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-card-title>
                    </v-card>
                </v-flex> -->
                <v-flex xs4>
                    <v-card>
                        <v-card-text class="headline success" style="color:white">
                            <h4 style="color:white">Registro Ciclos</h4>
                        </v-card-text>
                        <v-card-title primary-title>
                            <v-layout row wrap>
                                <v-flex xs4>
                                    <v-menu v-model="menu13" :close-on-content-click="false" :nudge-right="40" lazy
                                        transition="scale-transition" offset-y full-width min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <VTextField v-model="startDate" label="Fecha Ingredientes"
                                                prepend-icon="event" readonly v-on="on" />
                                        </template>
                                        <VDatePicker color="primary" v-model="startDate" @input="menu13 = false" />
                                    </v-menu>
                                </v-flex>
                                <!--                                <v-flex xs4>-->
                                <!--                                    <v-text-field type="number" label="Personas" v-model="personas"></v-text-field>-->
                                <!--                                </v-flex>-->

                                <v-flex xs1>
                                    <v-btn color="info" :loading="loading" round @click="registroInventario(6)">
                                        Descargar
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-card-title>
                    </v-card>
                </v-flex>
                <v-flex xs4>
                    <v-card>
                        <v-card-text class="headline success">
                            <h4 style="color:white">Reporte corte</h4>
                        </v-card-text>
                        <v-card-title primary-title>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <!-- <v-menu v-model="menu3" :close-on-content-click="false" :nudge-right="40" lazy
                                        transition="scale-transition" offset-y full-width min-width="290px">
                                    </v-menu> -->
                                    <v-layout row wrap>
                                        <v-flex xs8>
                                            <v-select v-model="listacortes" :items="listcortes" item-text="fecha_corte"
                                                label="Seleccione fecha de corte">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs1>
                                            <v-btn color="info" :loading="loading" round @click="descargarCorteFecha">
                                                Descargar
                                            </v-btn>
                                        </v-flex>
                                    </v-layout>
                                </v-flex>
                            </v-layout>
                        </v-card-title>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </v-layout>
</template>
<script>
    import moment from 'moment';
    import Widget from '../../../components/referencia/Widget.vue';
    import {
        mapGetters
    } from "vuex";

    moment.locale('es');
    export default {
        name: 'vagoutDomicilios',
        components: {
            Widget,
        },
        data: () => {
            return {
                finishDate: moment().format('YYYY-MM-DD'),
                loading: false,
                menu1: false,
                menu2: false,
                menu3: false,
                menu4: false,
                menu5: false,
                menu6: false,
                menu7: false,
                menu8: false,
                menu9: false,
                menu10: false,
                menu11: false,
                menu12: false,
                menu13: false,
                listacortes: '',
                startDate: moment().format('YYYY-MM-DD'),
                fechainicio: moment().format('YYYY-MM-DD'),
                fechafin: moment().format('YYYY-MM-DD'),
                prueba: moment().format('YYYY-MM-DD'),
                personas: 0,
                today: moment().format('YYYY-MM-DD'),
                listcortes: [],
            }
        },
        mounted() {
            this.seleccionarFechaCorte();
        },

        computed: {
            ...mapGetters(['can'])
        },
        methods: {
            registroInventario(consulta) {
                // if (parseInt(consulta) === 6 && this.personas <= 0) {
                //     this.$alerError('La cantidad de personas debe ser mayor a 0');
                //     this.personas = 1;
                //     return;
                // }
                this.loading = true;
                axios({
                    method: 'post',
                    params: {
                        fechainicio: this.startDate,
                        fechafin: this.finishDate,
                        id: consulta,
                        personas: this.personas
                    },
                    url: '/api/facturas/registroInventario',
                    responseType: 'blob'
                }).then(res => {
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download = `ReporteVagout${this.fechainicio}_${this.fechafin}.xlsx`;
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    this.loading = false;

                }).catch(err => {
                    this.loading = false;
                    this.showMessage('No hay ReporteVagout para descargar')
                })
            },

            seleccionarFechaCorte() {
                axios.get('/api/facturas/seleccionarFechaCorte').then(res => {
                    this.listcortes = res.data;
                })
            },

            descargarCorteFecha() {
                axios({
                    method: 'get',
                    params: {
                        prueba: this.listacortes,
                    },
                    url: '/api/facturas/descargarCorteFecha',
                    responseType: 'blob'
                }).then(res => {

                    if (this.listacortes) {
                        let blob = new Blob([res.data], {
                            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                        });
                        let url = window.URL.createObjectURL(blob);
                        let a = document.createElement('a');

                        a.download = `ReporteCorte${this.listacortes}.xlsx`;
                        a.href = url;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        this.preload = false;
                        this.clear();

                    } else [
                        this.showMessage('Seleccione una fecha')
                    ]

                })
            },

            showMessage(message) {
                swal({
                    title: "¡Aviso!",
                    text: message,
                    icon: "warning"
                });
            },

            clear() {
                this.listacortes = ''
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
