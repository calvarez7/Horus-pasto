<template>
    <v-layout>
        <v-layout row>
            <v-dialog v-model="dialog" max-width="1000px">
                <v-card>
                    <v-toolbar flat color="primary" dark>
                        <v-toolbar-title>Detalles Ordenes</v-toolbar-title>
                    </v-toolbar>
                    <v-data-table class="fb-table-elem" :headers="headerOrders" :items="detalleOrdenes">
                        <template v-slot:items="props">
                            <td class="text-xs-center">{{ props.item.codigoOrden }}</td>
                            <td class="text-xs-center">{{ props.item.Primer_Ape }} {{ props.item.Segundo_Ape }} {{
                                props.item.Primer_Nom }} {{ props.item.SegundoNom }}
                            </td>
                            <td class="text-xs-center">{{ props.item.Num_Doc }}</td>
                            <td class="text-xs-center">{{ props.item.Descripcion }}</td>
                            <td class="text-xs-center">{{ props.item.page }}/{{props.item.total_pages}}</td>
                            <td class="text-xs-center">{{ props.item.day }}</td>
                            <td class="text-xs-center" v-if="props.item.Estado_id == 7"><v-chip color="success" dark>Aprobado</v-chip></td>
                            <td class="text-xs-center" v-else-if="props.item.Estado_id == 35"><v-chip color="primary" dark>Aprobado Quimico</v-chip></td>
                            <td class="text-xs-center" v-else><v-chip color="warning" dark>Por Auditar</v-chip></td>
                        </template>
                    </v-data-table>
                </v-card>
            </v-dialog>
            <v-dialog v-model="dialogObservacion" persistent max-width="600px">
                <v-card>
                    <v-card-title>
                        <span class="headline">Observación Farmacia</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <v-layout row wrap>
                                <v-flex >
                                    <v-text-field
                                        v-model="observacion"
                                        label="Observación"
                                        persistent-hint
                                        required
                                    ></v-text-field>
                                </v-flex >
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" @click="dialogObservacion = false,observacion= ''">Cancelar</v-btn>
                        <v-btn color="success" @click="saveObservation">Guardar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-layout>
        <v-flex shrink xs12 ml-1>
            <template>
                <v-layout wrap>
                    <v-flex>
                        <v-container>
                            <v-layout row wrap>
                                <v-flex xs12 md12 lg12>
                                    <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details>
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-container>
                        <v-card>

                            <v-data-table :search="search" :headers="headers" :items="listaOncologiaDispensado" item-key="index">
                                <template v-slot:items="props">
                                    <td class="text-xs-center"><a href="javascript:void(0)" @click="viewDetails(props.item.detaarticulordens)">{{ props.item.Codigo }}</a></td>
                                    <td class="text-xs-left">{{ props.item.medicamento }}</td>
                                    <td class="text-xs-center">{{ props.item.cantidad }}</td>
                                    <td class="text-xs-center">{{ Math.ceil(props.item.cantidad / props.item.concentracion) }}</td>
                                    <td class="text-xs-center">{{ props.item.stock }}</td>
                                </template>
                                <template v-slot:no-data>
                                    <v-alert :value="true" color="error" icon="warning">No hay movimientos en este Estado.</v-alert>
                                </template>
                            </v-data-table>
                        </v-card>
                    </v-flex>
                </v-layout>
            </template>

        </v-flex>
    </v-layout>

</template>
<script>
    export default {
        name: 'esquemas',
        data() {
            return {
                search:"",
                dialog: false,
                dialogObservacion: false,
                listaOncologiaDispensado: [],
                detalleOrdenes: [],
                codigoDetalleOrden: null,
                observacion: '',
                headers: [{
                    text: "Codigo",
                    sortable: false,
                    align: "center",
                    value: ""
                },
                    {
                        text: "Medicamento",
                        sortable: false,
                        align: "center",
                        value: "medicamento"
                    },
                    {
                        text: "Ordenado",
                        sortable: false,
                        align: "center",
                        value: ""
                    },
                    {
                        text: "Unidades",
                        sortable: false,
                        align: "center",
                        value: ""
                    },
                    {
                        text: "Stock",
                        sortable: false,
                        align: "center",
                        value: "stock"
                    }
                ],
                headerOrders: [{
                    text: "Id",
                    sortable: false,
                    align: "center",
                    value: ""
                }, {
                    text: "Nombre Paciente",
                    sortable: false,
                    align: "center",
                    value: ""
                }, {
                    text: "N.Documento",
                    sortable: false,
                    align: "center",
                    value: ""
                }, {
                    text: "Diagnostico",
                    sortable: false,
                    align: "center",
                    value: ""
                },{
                    text: "Ciclos",
                    sortable: false,
                    align: "center",
                    value: ""
                },{
                    text: "Dias",
                    sortable: false,
                    align: "center",
                    value: ""
                },{
                    text: "Estado",
                    sortable: true,
                    align: "center",
                    value: "Estado_id"
                }
                ]
            }
        },
        mounted() {
            this.find();
        },
        methods: {
            find: async function () {
                try {
                    await axios.get('/api/esquemas/getOrdersOncologyDispensed')
                        .then(res => {
                            this.listaOncologiaDispensado = res.data;
                        })
                } catch (e) {
                    console.log(e)
                }
            },
            viewDetails: async function (ordenes) {
                this.detalleOrdenes = ordenes;
                this.dialog = true;
                console.log(ordenes)
            },
            observacionForm(orden){
                this.codigoDetalleOrden = orden;
                this.dialogObservacion = true;
                console.log(this.codigoOrden)
            },
            saveObservation(){
                if (this.observacion == "") {
                    this.$alerError('Debe llenar la Observacion');
                    return;
                }
                const data = {
                    observacion: this.observacion,
                    codigoOrden: this.codigoDetalleOrden,
                };
                try {
                    axios.post('/api/orden/aprovacionFarmacia',data)
                    .then(res => {
                        if(res.status === 201){
                            this.observacion = "";
                            this.codigoOrden = null;
                            this.dialogObservacion = false;
                            this.dialog = false;
                            this.find();
                            }
                        })
                }catch (e) {
                    console.log(e)
                }
            }
        }
    }
</script>
