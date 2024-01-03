<template>
    <div>
        <v-card>
            <v-form @submit.prevent="find()">
                <v-layout row wrap>
                    <v-flex xs12 md12 lg12>
                        <v-card>
                            <v-card-title class="headline success" style="color:white">
                                <span class="title layout justify-center font-weight-light">Historico
                                    Laboratorios</span>
                            </v-card-title>
                            <v-container grid-list-xl>
                                <v-layout>
                                    <v-flex xs3 sm>
                                        <v-text-field v-model="cedula" label="Cédula"></v-text-field>
                                    </v-flex>
                                    <v-flex xs6 sm>
                                        <v-btn color="primary" round @click="buscarDatosExam()">Buscar</v-btn>
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
            <v-data-table :headers="headersServicios" :items="historialpaciente">
                <template v-slot:items="props">
                    <tr>
                        <td class="text-xs-center" @click="dialogoResultados = true,buscarResultado(props.item)"
                            v-if="props.item.estado_resultado === '1' && parseInt(props.item.estado_cargado) !== 2">
                            <v-chip label color="success" text-color="white">Con Resultado</v-chip>
                        </td>
                        <td class="text-xs-center" v-else-if="props.item.estado_resultado === '0' && parseInt(props.item.estado_cargado) !== 2">
                            <v-chip label color="error" text-color="white">Sin Resultado</v-chip>
                        </td>
                        <td class="text-xs-center" v-else-if="parseInt(props.item.estado_resultado) === 1 && parseInt(props.item.estado_cargado) === 2">
                            <template v-if="props.item.adjunto">
                                <v-chip color="orange" text-color="white" @click="descargarAdjunto(props.item.adjunto)">Descargar</v-chip>
                            </template>
                            <template v-else>
                                {{props.item.resultado_lab}}
                            </template>
                        </td>
                        <td v-else></td>
                        <td>{{props.item.num_orden}}</td>
                        <td>{{props.item.fecha}}</td>
                        <td>{{props.item.nom_examen}}</td>
                        <td>{{props.item.nom_medico}}</td>
                        <td class="text-xs-center" v-if="props.item.estado_cargado == '1'">
                            <v-chip color="green" text-color="white">Procesado en {{props.item.Nombre}}</v-chip>
                        </td>
                        <td class="text-xs-center" v-else-if="props.item.estado_cargado == '0'">
                            <v-chip color="info" text-color="white">Pendiente por Procesar en {{props.item.Nombre}}
                            </v-chip>
                        </td>
                        <td class="text-xs-center" v-else>
                            <v-chip color="orange" text-color="white">Registrado en SUMIMEDICAL</v-chip>
                        </td>
                    </tr>
                </template>
            </v-data-table>
            <v-dialog v-model="dialogoResultados" width="5000" persistent>
                <v-card-title class="headline success" style="color:white">
                    <span class="title layout justify-center font-weight-light">Resultados</span>
                    <v-btn color="warning" round @click="dialogoResultados=false,limpiar()">Cerrar</v-btn>
                </v-card-title>
                <v-data-table :headers="headersResultado" :items="resultados">
                    <template v-slot:items="props">
                        <tr>
                            <td class="text-xs-center">{{props.item.fecha_resultado}}</td>
                            <td class="text-xs-center">{{props.item.nom_analito}}</td>
                            <td class="text-xs-center">{{props.item.resultado}}</td>
                            <td class="text-xs-center">{{props.item.unidades}}</td>
                            <td class="text-xs-center">{{props.item.usu_valida}}</td>
                            <td class="text-xs-center">
                                <v-chip color="green" text-color="white">{{props.item.vr_minimo}}</v-chip>
                            </td>
                            <td class="text-xs-center">
                                <v-chip color="error" text-color="white">{{props.item.vr_maximo}}
                                </v-chip>
                            </td>
                        </tr>
                    </template>
                </v-data-table>
            </v-dialog>
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
                headersServicios: [{
                        text: "Resultado",
                        align: 'center',
                        sortable: false,
                        value: ""
                    },
                    {
                        text: "# Orden",
                        align: 'center',
                        value: "orden"
                    }, {
                        text: "Fecha orden",
                        align: 'center',
                        sortable: false,
                        value: "FechaOrdenamiento"
                    }, {
                        text: "Examen",
                        align: 'center',
                        sortable: false,
                        value: "Fechaorden"
                    }, {
                        text: "Medico Envia Orden",
                        align: 'center',
                        value: "Codigo"
                    }, {
                        text: "Estado del laboratorio",
                        align: 'center',
                        value: "Nombre"
                    }
                ],
                headersResultado: [{
                        text: "Fecha de resultado",
                        align: 'center',
                        sortable: false,
                        value: ""
                    },
                    {
                        text: "Analito",
                        align: 'center',
                        value: "orden"
                    }, {
                        text: "Resultado",
                        align: 'center',
                        sortable: false,
                        value: "FechaOrdenamiento"
                    }, {
                        text: "Unidad Medida",
                        align: 'center',
                        sortable: false,
                        value: "Fechaorden"
                    }, {
                        text: "Usuario del Laboratorio",
                        align: 'center',
                        value: "Codigo"
                    }, {
                        text: "Valor Minimo",
                        align: 'center',
                        value: "Nombre"
                    }, {
                        text: "Valor Maximo",
                        align: 'center',
                        value: "Nombre"
                    }
                ]
            }
        },
        methods: {

            buscarDatosExam() {
                if (!this.cedula) {
                    swal({
                        title: "Debe ingresar un cédula",
                        icon: "warning"
                    });
                    return;
                }
                this.preload = true;
                axios.post('/api/paciente/buscarDatosExam', {
                    Num_Doc: this.cedula
                }).then(res => {
                    this.historialpaciente = res.data;
                    this.preload = false;
                }).catch(err => {
                    this.preload = false;
                    console.log(err);
                })
            },

            buscarResultado(item) {
                const datos = item;
                this.preload = true;
                axios.post('/api/paciente/buscarResultado', datos).then(res => {
                    this.resultados = res.data;
                    this.preload = false;
                }).catch(err => {
                    this.preload = false;
                    console.log(err);
                })
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
            async descargarAdjunto(ruta){
                this.search_adjunto = true
                try {
                    let adj = await AdjuntoService.get(ruta);
                    let blob = new Blob([adj[1]], {
                        type: adj[0]
                    });
                    let link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    window.open(link.href, "_blank");
                    // this.search_adjunto = false
                } catch (error) {
                    // this.search_adjunto = false
                }
            }

        }
    }

</script>
