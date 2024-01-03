<template>
    <v-layout>
        <v-layout row>
            <v-dialog v-model="dialog" max-width="1000px">
                <v-card>
                    <v-toolbar flat color="primary" dark>
                        <v-toolbar-title>Ordenes</v-toolbar-title>
                    </v-toolbar>
                    <v-data-table class="fb-table-elem" :headers="headerDetalle" :items="detalleOrdenes">
                        <template v-slot:items="props">
                            <td class="text-xs-center"><a href="javascript:void(0)" @click="dialogDetails = true,listaDetalleOrden = props.item.detaarticulordens">{{ props.item.id }}</a></td>
                            <td class="text-xs-center">{{ props.item.day }}</td>
                            <td class="text-xs-center">{{ props.item.paginacion }}</td>

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

            <v-dialog v-model="dialogDetails" max-width="1200px">
                <v-card>
                    <v-toolbar flat color="primary" dark>
                        <v-toolbar-title>Detalles Orden</v-toolbar-title>
                    </v-toolbar>
                    <v-data-table class="fb-table-elem" :headers="headerDetails" :items="listaDetalleOrden">
                        <template v-slot:items="props">
                            <td class="text-xs-center">{{ props.item.id }}</td>
                            <td class="text-xs-center">{{ props.item.Codigo }}</td>
                            <td class="text-xs-center">{{ props.item.Nombre }}</td>
                            <td class="text-xs-center">{{ props.item.Via }}</td>
                            <td class="text-xs-center">{{ Math.round(props.item.Cantidad_Medico) }} {{ props.item.Unidad_Medida }}</td>
                            <td class="text-xs-center">{{ props.item.Frecuencia }} / {{ props.item.Unidad_Tiempo }}</td>
                            <td class="text-xs-center">{{ props.item.Observacion }}</td>
                        </template>
                    </v-data-table>
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
                                    <td>
                                        <v-btn color="info" @click="dialogObservacion = true,selected= props.item.id">Aprobar</v-btn>
                                    </td>
                                    <!-- <td class="text-xs-center">{{ props.item.Tipocita_id }}</td> -->
                                    <td class="text-xs-center" v-if="parseInt(props.item.Tipocita_id) === 7"><v-chip color="green" text-color="white">Oncologia</v-chip></td>
                                    <td class="text-xs-center" v-else><v-chip color="green" text-color="white">Transcripción</v-chip></td>
                                    <td class="text-xs-center">{{ props.item.Num_Doc }}</td>
                                    <td class="text-xs-center">{{ props.item.paciente }}</td>
                                    <td class="text-xs-center"><a href="javascript:void(0)" @click="viewDetails(props.item.ordens)">{{ props.item.scheme_name }}</a></td>
                                    <td class="text-xs-center">{{ props.item.Fechaorden | date }}</td>
                                    <td class="text-xs-center">{{ props.item.create_at | date }}</td>
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
    import moment from "moment";
    moment.locale("es");
    export default {
        name: 'enfermera',
        data() {
            return {
                search:"",
                dialog: false,
                dialogObservacion: false,
                dialogDetails: false,
                listaOncologiaDispensado: [],
                listaDetalleOrden: [],
                detalleOrdenes: [],
                codigoDetalleOrden: null,
                observacion: '',
                selected: null,
                disableButton: true,
                headers: [{
                    text: "",
                    sortable: false,
                    align: "center",
                    value: ""
                },
                {
                    text: "Tipo",
                    sortable: false,
                    align: "center",
                    value: "Tipocita_id"
                },
                {
                    text: "N.Documento",
                    sortable: false,
                    align: "center",
                    value: "Num_Doc"
                },
                    {
                        text: "Paciente",
                        sortable: false,
                        align: "center",
                        value: "paciente"
                    },{
                        text: "Esquema",
                        sortable: false,
                        align: "center",
                        value: ""
                    },
                    {
                        text: "Fecha Autorización",
                        sortable: false,
                        align: "center",
                        value: ""
                    },
                    {
                        text: "Fecha Creación",
                        sortable: false,
                        align: "center",
                        value: "create_at"
                    }
                ],
                headerDetalle: [{
                    text: "Orden",
                    sortable: false,
                    align: "center",
                    value: ""
                },{
                    text: "Dias Aplicacion",
                    sortable: false,
                    align: "center",
                    value: ""
                },{
                    text: "Ciclos",
                    sortable: false,
                    align: "center",
                    value: ""
                }],
                headerDetails: [{
                    text: "Id",
                    sortable: false,
                    align: "center",
                    value: ""
                },{
                    text: "CodeSumi",
                    sortable: false,
                    align: "center",
                    value: ""
                },{
                    text: "Medicamento",
                    sortable: false,
                    align: "center",
                    value: ""
                },{
                    text: "Via",
                    sortable: false,
                    align: "center",
                    value: ""
                },{
                    text: "Cantidad",
                    sortable: false,
                    align: "center",
                    value: ""
                },{
                    text: "Frecuencia",
                    sortable: false,
                    align: "center",
                    value: ""
                },{
                    text: "Observación",
                    sortable: false,
                    align: "center",
                    value: ""
                }],

            }
        },
        filters: {
            date: Fechaorden => {
                return moment(Fechaorden).format("LL");
            },
        },
            mounted() {
            this.find();
        },
        watch:{
            selected: function () {
                this.disableButton = true;

                if(this.selected.length > 0){
                    this.disableButton = false;
                }
            }
        },
        methods:{
            find: async function () {
                try {
                    await axios.get('/api/orden/getHistoryPendingOncology')
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
            saveObservation(){
                if (this.observacion == "") {
                    this.$alerError('Debe llenar la Observacion');
                    return;
                }
                let data = {
                    observacion: this.observacion,
                    citaPaciente: this.selected
                };
                try {
                    axios.post('/api/orden/aprovacionFarmacia',data)
                        .then(res => {
                            if(res.status === 201){
                                this.$alerSuccess('Esquema Autorizado')
                                this.observacion = "";
                                this.codigoOrden = null;
                                this.dialogObservacion = false;
                                this.dialog = false;
                                this.selected = false;
                                this.find();
                            }
                        })
                }catch (e) {
                    console.log(e)
                }
                this.find();
            }
        }
    }
    </script>
