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

                                    <v-flex xs6 sm6 class="text-xs-right">
                                        <VAutocomplete :items="tipoSolicitudes" v-model="solicitud"
                                            label="Tipo Solicitud" item-text="nombre" item-value="nombre" />
                                    </v-flex>

                                    <v-flex xs6 sm6>
                                        <VAutocomplete :items="departamentos" v-model="departamento" item-value="Nombre"
                                            item-text="Nombre" label="Departamento" />
                                    </v-flex>

                                    <v-flex xs6 sm6>
                                        <VAutocomplete :items="municipios" v-model="municipio" item-value="Nombre"
                                            item-text="Nombre" label="Municipio" />
                                    </v-flex>

                                    <v-flex xs4 sm4>
                                        <v-text-field v-model="documento" label="Documento" />
                                    </v-flex>

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

                                    <v-flex xs12 sm12 class="text-xs-right">
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
        name: 'Radicacioninforme',
        data: () => ({
            dateDesde: moment().format('YYYY-MM-DD'),
            dateHasta: moment().format('YYYY-MM-DD'),
            menu1: false,
            menu2: false,
            preload: false,
            documento: '',
            departamentos: [],
            municipios: [],
            tipoSolicitudes: [],
            solicitud: '',
            departamento: '',
            municipio: '',

        }),
        mounted() {
            this.fetchDepartamentos()
            this.fetchMunicipios()
            this.getTipos()
            moment.locale('es');
        },
        methods: {
            fetchDepartamentos() {
                axios.get('/api/departamento/all')
                    .then((res) => {
                        this.departamentos = res.data
                    })
            },
            fetchMunicipios() {
                axios.get('/api/municipio/all')
                    .then(res => {
                        this.municipios = res.data
                    })
            },
            getTipos() {
                axios.get('/api/redvital/getTipoSolicitud')
                    .then(res => {
                        this.tipoSolicitudes = res.data
                    });
            },
            clearFields() {
                this.dateDesde = moment().format('YYYY-MM-DD')
                this.dateHasta = moment().format('YYYY-MM-DD')
                this.documento = ''
                this.solicitud = ''
                this.departamento = ''
                this.municipio = ''
            },
            generarInforme() {
                this.preload = true
                axios({
                    method: 'post',
                    params: {
                        solicitud: this.solicitud,
                        fechaDesde: this.dateDesde,
                        fechaHasta: this.dateHasta,
                        departamento: this.departamento,
                        documento: this.documento,
                        municipio: this.municipio
                    },
                    url: '/api/solicitud/informe',
                    responseType: 'blob'
                }).then(res => {
                    this.preload = false
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');
                    a.download = `InformeSolicitudes.xlsx`;
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
