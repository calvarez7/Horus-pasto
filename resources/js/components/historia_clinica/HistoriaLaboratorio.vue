<template>
    <div>
        <v-card>
            <v-card-title class="headline success" style="color:white">
                <span class="title layout justify-center font-weight-light">Historico de laboratorios</span>
                <v-btn color="warning" round @click="cerrar()">Cerrar</v-btn>
            </v-card-title>
            <v-data-table :headers="headersServicios" :items="historialpaciente">
                <template v-slot:items="props">
                    <tr>
                        <td class="text-xs-center" @click="buscarResultado(props.item)"
                            v-if="props.item.estado_resultado === '1'">
                            <v-chip label color="success" text-color="white">Con Resultado</v-chip>
                        </td>
                        <td class="text-xs-center" v-else-if="props.item.estado_resultado === '0'" @click="buscarResultado(props.item)">
                            <v-chip label color="error" text-color="white">Sin Resultado</v-chip>
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
                    </tr>
                </template>
            </v-data-table>
            <v-card-title class="headline success" style="color:white">
                <span class="title layout justify-center font-weight-light">Resultados</span>
            </v-card-title>
            <v-data-table v-model="dialogoResultados" :headers="headersResultado" :items="resultados">
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
            this.buscarDatosExam();
        },
        data() {
            return {
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
                this.preload = true;
                axios.post('/api/paciente/historialLabortario', this.datosCita).then(res => {
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
            cerrar() {
                this.$emit('respuestaComponente');
            }
        }
    }

</script>
