<template>
    <div>
        <v-card>
            <v-form @submit.prevent="find()">
                <v-layout row wrap>
                    <v-flex xs12 md12 lg12>
                        <v-card>
                            <v-card-title class="headline success" style="color:white">
                                <span class="title layout justify-center font-weight-light">Historico
                                    Consentimiento</span>
                            </v-card-title>
                            <v-container grid-list-xl>
                                <v-layout>
                                    <v-flex xs3 sm>
                                        <v-text-field v-model="cedula" label="Cédula"></v-text-field>
                                    </v-flex>
                                    <v-flex xs6 sm>
                                        <v-btn color="primary" round @click="buscarDatosConsentimientos()">Buscar</v-btn>
                                        <v-btn @click="clearFields()" v-if="cedula" fab outline small color="error">
                                            <v-icon>clear</v-icon>
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-form>
            <v-data-table :headers="headers" :items="historialpaciente">
                <template v-slot:items="props">
                    <tr>
                        <td>{{props.item.citaPaciente_id}}</td>
                        <td>{{props.item.paciente_nombre}}</td>
                        <td>{{props.item.Hora_Inicio.split('.')[0]}}</td>
                        <td>{{props.item.actividad}}</td>
                        <td class="text-xs-center">
                            <v-chip color="info" text-color="white">{{props.item.modalidad}}
                            </v-chip>
                        </td>
                        <td>{{props.item.medico}}</td>
                        <td>
                            <v-btn color="info" drak @click="imprmirConsentimiento(props.item.citaPaciente_id)">Imprimir</v-btn>
                        </td>
                    </tr>
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
    import {AdjuntoService} from "../../../services";

    export default {
        name: "",
        props: {
            datosCita: Object
        },
        created() {},
        data() {
            return {
                cedula: '',
                dialogoResultados: false,
                historialpaciente: [],
                resultados: [],
                preload: false,
                headers: [{
                        text: "Id",
                        align: 'center',
                        sortable: false,
                        value: ""
                    },
                    {
                        text: "Nombre",
                        align: 'center',
                        value: "orden"
                    }, {
                        text: "Fecha cita",
                        align: 'center',
                        sortable: false,
                        value: "FechaOrdenamiento"
                    }, {
                        text: "Actividad",
                        align: 'center',
                        sortable: false,
                        value: "Fechaorden"
                    }, {
                        text: "Modalidad",
                        align: 'center',
                        value: "Codigo"
                    }, {
                        text: "Medico",
                        align: 'center',
                        value: "Nombre"
                    },{
                        text: "Imprimir",
                        align: 'center',
                        value: "Nombre"
                    }
                ],
            }
        },
        methods: {

            buscarDatosConsentimientos() {
                if (!this.cedula) {
                    swal({
                        title: "Debe ingresar un cédula",
                        icon: "warning"
                    });
                    return;
                }
                this.preload = true;
                axios.post('/api/paciente/buscarDatosConsentimientos', {
                    Num_Doc: this.cedula
                }).then(res => {
                    this.historialpaciente = res.data;
                    this.preload = false;
                }).catch(err => {
                    this.preload = false;
                    console.log(err);
                })
            },

            imprmirConsentimiento(id){
                    const pdf = {
                        type: 'consentimiento',
                        id: id,
                    }
                    axios.post("/api/pdf/print-pdf", pdf, {
                            responseType: "arraybuffer"
                        })
                        .then(res => {
                            let blob = new Blob([res.data], {
                                type: "application/pdf"
                            });
                            let link = document.createElement("a");
                            link.href = window.URL.createObjectURL(blob);
                            window.open(link.href, "_blank");
                        })
                        .catch(err => console.log(err.response));
                },

            limpiar() {
                for (const key in this.resultados) {
                    this.resultados[key] = ''
                }
            },

            clearFields() {
                    this.cedula = '';
                    this.historialpaciente = [];
                    this.limpiar()
                },

        }
    }

</script>
