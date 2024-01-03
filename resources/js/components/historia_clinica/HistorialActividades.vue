<template>
    <div>
        <v-card>
            <v-card-title class="headline success" style="color:white">
                <span class="title layout justify-center font-weight-light">Mi Historial Clinico {{usuario}}</span>
                <v-btn color="warning" round @click="cerrar()">Cerrar</v-btn>
            </v-card-title>
            <v-data-table :headers="header" :items="historiapaciente" item-key="index">
                <template v-slot:items="props">
                    <td class="text-xs-center">{{ props.item.Datetimeingreso}}</td>
                    <td class="text-xs-center">{{props.item.Tipocita}}</td>
                    <td class="text-xs-center">{{props.item.Atividad}}</td>
                    <td class="text-xs-center">
                        <v-btn v-if="props.item.Datetimeingreso < '2022-04-20 00:00:00.000'" color="red" small fat dark
                            @click="printhc(props.item)">
                            <v-icon>assignment</v-icon>
                        </v-btn>
                        <v-btn v-else color="blue" small fat dark @click="imprimirhc(props.item)">
                            <v-icon>assignment</v-icon>
                        </v-btn>
                    </td>
                    <td>
                        <v-btn color="warning" small fab dark @click="precargar(props.item)">
                            <v-icon>touch_app</v-icon>
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
                usuario: [],
                preload: false,
                header: [{
                        text: 'F. Atención',
                        align: 'center',
                        value: 'Datetimeingreso',
                        sortable: false
                    },
                    {
                        text: 'Tipo de Historia',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Actividad',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Ver Historia',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Precargar Historia',
                    },
                ],

            }
        },
        methods: {
            buscarDatos() {
                this.preload = true;
                axios.post('/api/paciente/historialAtencion', this.datosCita).then(res => {
                    this.historiapaciente = res.data;
                    this.usuario = res.data[0].usuario;
                    console.log(this.historiapaciente)
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
                pdf.id = imprimir.cita_paciente_id;
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
            precargar(carga){
                
            }
        }
    }

</script>
