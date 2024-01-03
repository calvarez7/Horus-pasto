<template>
    <div>
        <v-layout>
            <v-flex shrink xs12 mr-1>
                <v-card max-height="100%" class="mb-3">
                    <v-card-text>
                        <v-container grid-list-xl text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs3>
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
                                <v-flex xs3>
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
                                <v-flex xs6>
                                    <VAutocomplete clear-icon label="Buscar médico" :items="listMedicos"
                                        item-text="nombre" item-value="id" v-model="filtros.medico_id" />
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

        <v-dialog v-model="preload" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Tranquilo procesamos tu solicitud !
                    <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import moment from 'moment';
    export default {
        name: 'reporteAgendas',
        data: () => ({
            preload: false,
            menu1: false,
            menu2: false,
            filtros: {
                fechaDesde: moment().format('YYYY-MM-DD'),
                fechaHasta: moment().format('YYYY-MM-DD'),
            },
            medicos: []
        }),
        mounted() {
            this.fetchMedicos();
        },
        computed: {
            listMedicos() {
                return this.medicos.map(medico => {
                    return {
                        id: medico.id,
                        nombre: `${medico.cedula} - ${medico.name} ${medico.apellido}`
                    }
                })
            },
        },
        methods: {
            fetchMedicos() {
                axios.get('/api/user/medicos')
                    .then(res => this.medicos = res.data);
            },
            generarInforme() {
                this.preload = true;
                axios({
                    method: 'post',
                    url: '/api/agenda/exportAuditoria',
                    responseType: 'blob',
                    params: this.filtros
                }).then(res => {
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');
                    a.download = 'InformeAuditoriaAgendas';
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    this.preload = false
                }).catch(err => {
                    this.preload = false
                })
            },
        },
    }

</script>

