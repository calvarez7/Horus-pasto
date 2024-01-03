<template>
    <div>
        <v-card>
            <v-card-title class="headline success" style="color:white">
                <span class="title layout justify-center font-weight-light">Historico Alertas</span>
                <v-btn color="warning" round @click="cerrar()">Cerrar</v-btn>
            </v-card-title>
            <v-expansion-panel>
                <v-expansion-panel-content class="headline" style="color:black;background-color:#f0f2f2">
                    <template v-slot:actions>
                        <v-icon color="error">$vuetify.icons.error</v-icon>
                    </template>
                    <template v-slot:header>
                        <div>Medicamentos Alergicos</div>
                    </template>
                    <v-card>
                        <v-data-table :headers="headersAlertas" :items="historialpaciente.Medicamentos" class="elevation-1">
                            <template v-slot:items="props">
                                <td>{{ props.item.Producto }}</td>
                                <td class="text-xs-left">{{ props.item.observacionAlergia }}</td>
                                <td class="text-xs-left">{{ props.item.usuario }}</td>
                                <td class="text-xs-left">{{ props.item.fecha }}</td>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-expansion-panel-content>
                <v-expansion-panel-content class="headline" style="color:black;background-color:#f0f2f2">
                    <template v-slot:actions>
                        <v-icon color="error">$vuetify.icons.error</v-icon>
                    </template>
                    <template v-slot:header>
                        <div>Alergias Alimentarias</div>
                    </template>
                    <v-card>
                        <v-data-table :headers="headersAlertas" :items="historialpaciente.Alimentos" class="elevation-1">
                            <template v-slot:items="props">
                                <td>{{ props.item.Alimneto }}</td>
                                <td class="text-xs-left">{{ props.item.observacionalimneto }}</td>
                                <td class="text-xs-left">{{ props.item.usuario }}</td>
                                <td class="text-xs-left">{{ props.item.fecha }}</td>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-expansion-panel-content>
                <v-expansion-panel-content class="headline" style="color:black;background-color:#f0f2f2">
                    <template v-slot:actions>
                        <v-icon color="error">$vuetify.icons.error</v-icon>
                    </template>
                    <template v-slot:header>
                        <div>Alergicas Ambientales</div>
                    </template>
                    <v-card>
                         <v-data-table :headers="headersAlertas" :items="historialpaciente.Ambientales" class="elevation-1">
                            <template v-slot:items="props">
                                <td>{{ props.item.Ambientales }}</td>
                                <td class="text-xs-left">{{ props.item.observacionambiental }}</td>
                                <td class="text-xs-left">{{ props.item.usuario }}</td>
                                <td class="text-xs-left">{{ props.item.fecha }}</td>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-expansion-panel-content>
                <v-expansion-panel-content class="headline" style="color:black;background-color:#f0f2f2">
                    <template v-slot:actions>
                        <v-icon color="error">$vuetify.icons.error</v-icon>
                    </template>
                    <template v-slot:header>
                        <div>Otras Alergias</div>
                    </template>
                    <v-card>
                         <v-data-table :headers="headersAlertas" :items="historialpaciente.Otros" class="elevation-1">
                            <template v-slot:items="props">
                                <td>{{ props.item.OtrasAlergias }}</td>
                                <td class="text-xs-left">{{ props.item.observacionotras }}</td>
                                <td class="text-xs-left">{{ props.item.usuario }}</td>
                                <td class="text-xs-left">{{ props.item.fecha }}</td>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-card>
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
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        created() {
            this.buscarDatosAlert();
        },
        data() {
            return {
                preload: false,
                headersAlertas: [{
                        text: 'Alergia',
                        align: 'left',
                    },
                    {
                        text: 'Observaciones',
                        align: 'left',
                    },
                    {
                        text: 'Usuario Registro',
                        align: 'left',
                    },
                    {
                        text: 'Fecha Registro',
                        align: 'left',
                    }
                ],
                historialpaciente: [],
            }

        },
        methods: {
            buscarDatosAlert() {
                this.preload = true;
                axios.post('/api/paciente/buscarDatosAlert', this.datosCita).then(res => {
                    this.historialpaciente = res.data;
                    this.preload = false;
                }).catch(err => {
                    this.preload = false;
                })
            },

            cerrar() {
                this.$emit('respuestaComponente');
            }
        }
    }

</script>
