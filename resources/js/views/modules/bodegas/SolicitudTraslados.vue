<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-card>
                <v-dialog v-model="dialog" persistent max-width="800px">
                    <v-card>
                        <v-card-title>
                            <span class="headline">Asignar Cantidad</span>
                        </v-card-title>
                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs12 sm8 md8>
                                        <v-text-field label="Medicamento*" v-model="data.Medicamento"
                                            required readonly></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm4 md4>
                                        <v-autocomplete label="Cum*" :items="listCum" item-text="CUM_completo"
                                            item-value="CUM_completo" v-model="data.Cum" :search-input.sync="findLote" required></v-autocomplete>
                                    </v-flex>
                                    <v-flex xs12 sm6 md6>
                                        <v-autocomplete label="Lote*" :items="listLote"
                                            item-text="Numlote" item-value="id"
                                            v-model="data.Lote" :search-input.sync="findBodegaArticulo_id" required></v-autocomplete>
                                    </v-flex>
                                    <v-flex xs12 sm6 md6>
                                        <v-text-field type="number"
                                        min="1"
                                        onkeypress="return event.charCode >= 48"
                                        oncopy="return false" onpaste="return false" label="Cantidad*" v-model="data.Cantidad"
                                            required></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" flat @click="closeDialog()">Cancelar</v-btn>
                            <v-btn color="blue darken-1" flat @click="saveTraslado(data)">Guardar</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="cancel" persistent max-width="400px">
                    <v-card>
                        <v-card-title class="headline grey lighten-2" primary-title>
                            Cancelar Solicitud de Traslado
                        </v-card-title>
                        <v-card-text>
                            <v-textarea name="input-7-1" label="Observacion" v-model="observaciones">
                            </v-textarea>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" text @click="submitDelete()">
                                Anular
                            </v-btn>
                            <v-btn color="red" text @click="cancelDialog()">
                                Cerrar
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-container>
                    <v-layout row wrap>
                        <v-flex xs4 pl-2>
                            <v-autocomplete label="Solicitudes Bodega*" :items="listBodegas" item-text="Nombre"
                                item-value="id" @change="getSolicitudTraslado()" v-model="Bodega_id" required>
                            </v-autocomplete>
                        </v-flex>
                    </v-layout>
                </v-container>
                <v-data-table v-if="solicitudTraslados.length > 0" :headers="header" :items="solicitudTraslados">
                    <template v-slot:items="props">
                        <td class="text-xs-center">{{ props.item.id}}</td>
                        <td class="text-xs-center">{{ props.item.Nombre_BodegaOrigen}}</td>
                        <td class="text-xs-center">{{ props.item.Medicamento}}</td>
                        <td class="text-xs-center">{{ props.item.Cantidad}}</td>
                        <td class="text-xs-center">
                            <v-tooltip top>
                                <template v-slot:activator="{ on }">
                                    <v-btn fab outline color="warning" small v-on="on"
                                        @click="assignTransfer(props.item)">
                                        <v-icon>edit</v-icon>
                                    </v-btn>
                                </template>
                                <span>Editar</span>
                            </v-tooltip>
                            <v-tooltip top>
                                <template v-slot:activator="{ on }">
                                    <v-btn fab outline color="danger" small v-on="on"
                                        @click="anularSolicitud(props.item)">
                                        <v-icon>clear</v-icon>
                                    </v-btn>
                                </template>
                                <span>Anular</span>
                            </v-tooltip>
                        </td>
                    </template>
                </v-data-table>
            </v-card>
        </v-flex>
        <v-flex pt-2>
            <v-card>
                <v-container grid-list-xs>
                    <v-layout row wrap>
                        <v-flex xs12>
                            <v-data-table  v-if="Traslados.length > 0" :headers="headersTraslados" :items="Traslados" hide-actions
                                class="elevation-1" pagination.sync="pagination" item-key="id">
                                <template v-slot:items="props">
                                    <td class="text-xs-center">{{ props.item.Solicitud_id }}</td>
                                    <td class="text-xs-center">{{ props.item.Medicamento }}</td>
                                    <td class="text-xs-center">{{ props.item.Cum }}</td>
                                    <td class="text-xs-center">{{ props.item.Lote }}</td>
                                    <td class="text-xs-center">{{ props.item.Cantidad }}</td>
                                    <td class="text-xs-center">
                                        <v-btn color="error" @click="removeTraslado(props)" icon>
                                            <v-icon>clear</v-icon>
                                        </v-btn>
                                    </td>
                                </template>
                                <v-alert v-slot:no-data :value="true" color="error" icon="warning">No hay datos.
                                </v-alert>
                            </v-data-table>
                        </v-flex>
                        <v-layout row wrap>
                            <v-spacer></v-spacer>
                            <v-btn v-if="Traslados.length > 0" color="dark" round @click="generateTraslate()">Generar Traslado</v-btn>
                        </v-layout>
                    </v-layout>
                </v-container>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    import {
        EventBus
    } from '../../../event-bus.js';
    import moment from 'moment';

    export default {
        name: 'SolicitudTraslados',
        data() {
            return {
                listBodegas: [],
                Traslados: [],
                solicitudTraslados: [],
                listCum: [],
                listLote: [],
                solicitudA: {},
                Bodega_id: '',
                findLote: '',
                cancel: '',
                findBodegaArticulo_id: '',
                BodegaArticulo_id: '',
                observaciones: '',
                dialog: false,
                header: [
                    {
                        text: 'Identificación de la solicitud',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Bodega Solicitante',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Medicamento',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Cantidad',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: '',
                        sortable: false,
                        align: 'center'
                    },
                ],
                headersTraslados: [{
                        text: 'Identificación de la solicitud',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Medicamento',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Cum',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Lote',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Cantidad',
                        sortable: false,
                        align: 'center'
                    },
                ],
                Traslado: {
                    Solicitud_id: '',
                    Bodega_id: '',
                    Medicamento: '',
                    Cum: '',
                    Lote: '',
                    Cantidad: ''
                },
                data: {
                    Solicitud_id: '',
                    Medicamento: '',
                    Cum: '',
                    Lote: '',
                    Cantidad: ''
                }
            }
        },
        mounted() {
            this.fetchBodegas();
        },
        watch: {
            findLote: function () {
                if (this.data.Cum) {
                    axios
                    .post("/api/orden/getLote", {
                        Codesumi_id: this.data.Codesumi_id,
                        CUM_completo: this.data.Cum,
                        Bodega_id: this.Bodega_id
                    })
                    .then(res => {
                        this.listLote = res.data;
                    })
                    .catch(err => console.log(err.response.data));
                }
            },
            findBodegaArticulo_id: function () {
                let Lote = this.listLote.find(lote => lote.id == this.data.Lote);
                if (Lote) {
                    this.data.BodegaArticulo_id = Lote.Bodegarticulo_id;
                }
            }
        },
        computed: {
        },
        methods: {
            fetchBodegas() {
                axios.get('/api/bodega/getBodegaByRole')
                    .then(res => {
                        if (res.data.length > 0) {
                            this.listBodegas = res.data
                        }
                    })
            },
            fetchBodegaArticulos(medicamento) {
                axios.post(`/api/bodega/${this.Bodega_id}/articulo/all`, {
                        nombre: medicamento
                    })
                    .then(res => {
                        this.listaBodegaArticulos = res.data;
                        this.listaBodegaArticulos.forEach(articulo => {
                            articulo.detallearticulos.forEach(each => {
                                this.listCum.push(each)
                            })
                        })
                    })
            },
            getSolicitudTraslado() {
                axios.get(`/api/bodega/${this.Bodega_id}/solicitud/getSolicitudByBodegaDestino`)
                    .then(res => this.solicitudTraslados = res.data);
            },
            assignTransfer(solicitud) {
                this.data = {
                    Solicitud_id: solicitud.id,
                    Codesumi_id: solicitud.Codesumi_id,
                    Medicamento: solicitud.Medicamento,
                    CantidadTotal: solicitud.Cantidad,
                    BodegaOrigen_id: this.Bodega_id, //La bodega destino de la solicitud pasa a ser la bodega origen del traslado
                    BodegaDestino_id: solicitud.BodegaOrigen_id //La bodega origen de la solicitud pasa a ser la bodega destino del traslado
                }
                this.fetchBodegaArticulos(solicitud.Medicamento);
                this.dialog = true;
            },
            saveTraslado(data) {
                if (!data.Cum) {
                    swal({
                		title: "Traslado",
                		text: "Ingrese un cum!",
                		timer: 2000,
                		icon: "error",
                		buttons: false
                    });
                    return;
                }

                if (!data.Lote) {
                    swal({
                		title: "Traslado",
                		text: "Ingrese un Lote!",
                		timer: 2000,
                		icon: "error",
                		buttons: false
                    });
                    return;
                }

                if (!data.Cantidad) {
                    swal({
                		title: "Traslado",
                		text: "Ingrese una cantidad!",
                		timer: 2000,
                		icon: "error",
                		buttons: false
                    });
                    return;
                }

                let validate = this.validateLote(data);
                console.log("validate", validate);
                if (validate.val) {
                    const codesumi = ({ Solicitud_id }) => Solicitud_id == this.data.Solicitud_id
                    let belongsToTraslados = this.Traslados.filter(codesumi);
                    var traslado = this.getCount(belongsToTraslados);
                    if (traslado) {
                        this.pushTraslado(traslado.Cantidad);
                    } else {
                        this.pushTraslado();
                    }
                } else {
                    let msg = `La cantidad máxima de traslado por el Lote seleccionado es de: ${validate.Cantidadisponible}`;
                    swal({
                        title: "Traslado",
                        text: msg,
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                }
            },
            getCount(traslados) {
                var traslado = {};
                traslado = traslados.slice(0);
                if (traslado) {
                    const reducer = (acum, {Cantidad}) => acum + parseInt(Cantidad);
                    traslado.Cantidad = traslados.reduce(reducer, 0);
                }
                return traslado;
            },
            pushTraslado(cantidad = 0) {
                if (parseInt(this.data.CantidadTotal) >=
                    (parseInt(this.data.Cantidad) + parseInt(cantidad))
                ) {
                    this.Traslados.push({
                        ...this.data
                    });
                    this.clearPush();
                } else {
                    swal({
                        title: "Cantidad seleccionada no permitida",
                        text: "La cantidad supera la cantidad solicitada",
                        icon: "error"
                    });
                    return;
                }
            },
            removeTraslado(traslado) {
                this.Traslados.splice(traslado.index, 1);
            },
            clearPush() {
                this.data = {};
                this.listCum = [];
                this.listLote = [];
                this.BodegaArticulo_id = '';
                this.dialog = false;
            },
            generateTraslate() {
                var BodegaDestino_idAux = this.Traslados[0].BodegaDestino_id;
                const val = ({ BodegaDestino_id }) => BodegaDestino_id != BodegaDestino_idAux;
                let validate = this.Traslados.filter(val);
                if (validate.length != 0) {
                    swal({
                        title: "Bodega solicitante",
                        text: "Se tiene más de una bodega solicitante en la generación de traslado!",
                        icon: "error"
                    });
                    return;
                } else {
                    swal({
                        title: "¿Está Seguro(a)?",
                        text: "Se realizará el traslado de mercancia!",
                        type: "success",
                        icon: "info",
                        buttons: ["Cancelar", "Confirmar"],
                        dangerMode: true
                    }).then(willDelete => {
                        if (willDelete) {
                            axios.post(`/api/movimiento/generateTraslado`, {
                                BodegaDestino_id: BodegaDestino_idAux,
                                BodegaOrigen_id: this.Bodega_id,
                                Traslados: this.Traslados
                            }).then(res => {
                                swal("Traslado creado de manera exitosa!", {
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                                this.clearTraslado();
                            }).catch(err => this.showError(err.response.data.message));
                        }
                    });
                }
            },
            showError(message) {
                swal({
                    title: "¡No puede ser!",
                    text: `${message}`,
                    icon: "warning",
                });
            },
            clearTraslado() {
                this.Traslados = [];
                this.clearPush();
                this.cancelDialog();
                this.getSolicitudTraslado();
            },
            anularSolicitud(solicitud) {
                this.solicitudA = solicitud;
                this.cancel = true;
            },
            submitDelete() {
                if (this.observaciones == "") {
                    swal({
                        title: "Debe llenar la Observacion",
                        icon: "warning"
                    });
                    return;
                }

                swal({
                    title: 'Está segur@ de anular la solicitud de traslado',
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        console.log("solicitud", this.solicitudA);
                        axios.post(`/api/bodega/solicitud/cancelTransfer/${this.solicitudA.id}`, {
                            observaciones: this.observaciones
                        }).then(res => {
                            swal("Traslado cancelado de manera exitosa!", {
                                timer: 2000,
                                icon: "success",
                                buttons: false
                            });
                            this.clearTraslado();
                        }).catch(err => this.showError(err.response.data.message))
                    }
                });
            },
            cancelDialog() {
                this.cancel = false;
                this.observaciones = '';
            },
            validateLote(data) {

                console.log("date", data);

                var Lote = this.listLote.find(lote => lote.id == data.Lote);
                console.log("Lote", Lote);
                if (parseInt(Lote.Cantidadisponible) < parseInt(data.Cantidad)) {
                    return {
                        ...Lote,
                        val: false
                    };
                } else {
                    return {
                        ...Lote,
                        val: true
                    };
                }
            },
            closeDialog() {
                this.listCum = [];
                this.dialog = false;
            }
        }
    }

</script>

<style lang="scss" scoped>

</style>
