<template>
    <div>
        <v-card>
            <v-card-title class="headline success" style="color:white">
                <span class="title layout justify-center font-weight-light">Historico Clinico</span>
                <v-btn color="warning" round @click="cerrar()">Cerrar</v-btn>
            </v-card-title>
            <v-data-table :headers="header" :items="historiapaciente" item-key="index">
                <template v-slot:items="props">
                    <td class="text-xs-center">{{ props.item.Datetimeingreso}}</td>
                    <td class="text-xs-center" v-if="props.item.Destinopaciente == 'Hospitalización (Remision)'">
                        <v-chip color="red" text-color="white">{{ props.item.Destinopaciente}}</v-chip>
                    </td>
                    <td class="text-xs-center" v-if="props.item.Destinopaciente != 'Hospitalización (Remision)'">
                        <v-chip color="info" text-color="white">{{ props.item.Destinopaciente}}</v-chip>
                    </td>
                    <td class="text-xs-center">{{ props.item.Atendido_Por}}</td>
                    <td class="text-xs-center">{{ props.item.Especialidad}}</td>
                    <td class="text-xs-center">{{props.item.Tipocita}}</td>
                    <td class="text-xs-center">
                    <v-btn v-if="props.item.Datetimeingreso < '2022-04-20 00:00:00.000'" color="red" small fat dark
                        @click="printhc(props.item)">
                        <v-icon>assignment</v-icon>
                    </v-btn>
                    <v-btn v-else color="blue" small fat dark @click="imprimirhc(props.item)">
                        <v-icon>assignment</v-icon>
                    </v-btn>
                    </td>
                </template>
            </v-data-table>
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
            this.buscarDatos();
        },
        data() {
            return {
                dialogHistorico: false,
                historiapaciente: [],
                preload: false,
                header: [{
                        text: 'F. Atención',
                        align: 'center',
                        value: 'Datetimeingreso',
                        sortable: false
                    },
                    {
                        text: 'Destino del Paciente',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Atendido por',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Especialidad',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Tipo',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Ver Historia',
                        sortable: false,
                        align: 'center'
                    },
                ],

            }
        },
        methods: {
            buscarDatos() {
                this.preload = true;
                axios.post('/api/paciente/historialClinico', this.datosCita).then(res => {
                    this.historiapaciente = res.data;
                    this.preload = false;
                }).catch(err => {
                    this.preload = false;
                    console.log(err);
                })
            },

            cerrar() {
                this.$emit('respuestaComponente');
            },

            printhc(imprimir) {
                    let pdf = [];
                        pdf = imprimir;
                        pdf.id=imprimir.cita_paciente_id;
                        pdf.type = "historia";
                    axios
                        .post("/api/pdf/print-pdf", pdf, {
                            responseType: "arraybuffer"
                        })
                        .then(res => {
                            let blob = new Blob([res.data], {
                                type: "application/pdf"
                            });
                            let link = document.createElement("a");
                            link.href = window.URL.createObjectURL(blob);
                            window.open(link.href, "_blank");
                        });
                },

            imprimirhc(imprimir) {
                let pdf = [];
                pdf = imprimir;
                pdf.type = "historiaintegral";
                axios
                    .post("/api/historia/imprimirhc", pdf, {
                        responseType: "arraybuffer"
                    })
                    .then(res => {
                        let blob = new Blob([res.data], {
                            type: "application/pdf"
                        });
                        let link = document.createElement("a");
                        link.href = window.URL.createObjectURL(blob);
                        window.open(link.href, "_blank");
                    });

            },
        }
    }

</script>
