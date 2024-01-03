<template>
    <div>

        <v-container fluid grid-list-xl>
            <v-layout row wrap>
                <v-flex xs12>
                    <v-card>
                        <v-toolbar color="primary" dark flat>
                            <v-flex xs12 text-xs-center>
                                <v-toolbar-title>Filtros</v-toolbar-title>
                            </v-flex>
                        </v-toolbar>
                        <v-card-text>
                            <v-container class="pa-0">
                                <v-layout row wrap>
                                    <v-flex xs3>
                                        <v-menu ref="menu1" v-model="menu1" :close-on-content-click="false"
                                            :nudge-right="40" lazy transition="scale-transition" offset-y full-width
                                            min-width="290px">
                                            <template v-slot:activator="{ on }">
                                                <v-text-field v-model="dateDesde" label="Fecha Desde"
                                                    prepend-icon="event" readonly v-on="on"></v-text-field>
                                            </template>
                                            <v-date-picker color="primary" locale="es" v-model="dateDesde" no-title
                                                @input="menu1 = false">
                                            </v-date-picker>
                                        </v-menu>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-menu ref="menu2" v-model="menu2" :close-on-content-click="false"
                                            :nudge-right="40" lazy transition="scale-transition" offset-y full-width
                                            min-width="290px">
                                            <template v-slot:activator="{ on }">
                                                <v-text-field v-model="dateHasta" label="Fecha Hasta"
                                                    prepend-icon="event" readonly v-on="on"></v-text-field>
                                            </template>
                                            <v-date-picker color="primary" locale="es" v-model="dateHasta" no-title
                                                @input="menu2 = false">
                                            </v-date-picker>
                                        </v-menu>
                                    </v-flex>

                                    <!-- <v-flex xs4 sm4>
                                        <v-text-field v-model="documento" label="Documento" />
                                    </v-flex>  -->

                                    <v-flex xs5 sm5 class="text-xs-right">
                                        <VAutocomplete :items="alleventos" v-model="evento" label="Área"
                                            item-text="nombre" single-line hide-details />
                                    </v-flex>

                                    <!-- <v-flex xs5 sm5>
                                        <VAutocomplete :items="allsedes" v-model="sede_ocurrencia" item-value="id"
                                            item-text="Nombre" label="Sede ocurrencia" />
                                    </v-flex>

                                    <v-flex xs3>
                                        <VAutocomplete :items="['Plan Mejora', 'Protocolo de Londres', 'Respuesta Inmediata']"
                                            v-model="tipo" label="Tipo de Informe" />
                                    </v-flex> -->

                                    <v-flex xs1 sm1>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn text icon color="error" dark v-on="on">
                                                    <v-icon @click="clearFields()">clear</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Limpiar</span>
                                        </v-tooltip>
                                    </v-flex>

                                    <v-flex xs1 sm1 class="text-xs-right">
                                        <v-btn color="success" @click="generarInforme()">Generar</v-btn>
                                    </v-flex>

                                </v-layout>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>

        <v-dialog v-model="preload" persistent width="300">
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
    import moment from 'moment';
    export default {
        name: 'Eventoinforme',
        data: () => ({
            dateDesde: moment().format('YYYY-MM-DD'),
            dateHasta: moment().format('YYYY-MM-DD'),
            menu1: false,
            menu2: false,
            alleventos: [],
            evento: '',
            preload: false,
            allsedes: [],
            sede_ocurrencia: '',
            documento: '',
            tipo: ''

        }),
        mounted() {
            this.geteventos();
            this.sedes();
            moment.locale('es');
        },
        methods: {
            sedes() {
                axios.get('/api/sedeproveedore/getSedePrestador').then((res) => {
                    this.allsedes = res.data
                })
            },
            geteventos() {
                axios.get('/api/evento/allsumimedical').then(res => {
                    this.alleventos = res.data
                });
            },
            clearFields() {
                this.evento = ''
                this.dateDesde = moment().format('YYYY-MM-DD')
                this.dateHasta = moment().format('YYYY-MM-DD')
                this.sede_ocurrencia = ''
                this.documento = ''
                this.tipo = ''
            },
            generarInforme() {
                this.preload = true
                axios({
                    method: 'post',
                    params: {
                        fechaDesde: this.dateDesde,
                        fechaHasta: this.dateHasta,
                        area: this.evento,
                    },
                    url: '/api/evento/informe',
                    responseType: 'blob'
                }).then(res => {
                    this.preload = false
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');
                    a.download = `InformeEventos.xlsx`;
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                }).catch(err => {
                    this.preload = false
                    console.log(err)
                })
            }
        }

    }

</script>
