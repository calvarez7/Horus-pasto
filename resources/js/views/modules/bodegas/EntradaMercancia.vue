<template>
    <v-card>
        <DialogEntradaMovimiento :dialogOpen="dialogDetalleOpen" :idMovimientoDetalle="idMovimientoDetalle">
        </DialogEntradaMovimiento>
<!--        <v-form @submit.prevent="saveMovimiento()">
            <v-container>
                <DialogDetalleMovimiento :dialogOpen="dialogDetalleOpen" :idMovimientoDetalle="idMovimientoDetalle">
                </DialogDetalleMovimiento>


                <v-layout row wrap>
                    &lt;!&ndash;v-flex xs4>
                        <v-autocomplete
                            label="Transacción*"
                            :items="filteredTransacciones"
                            item-text="TransacionNombre" @change="clearPresBodRep()"
                            item-value="id" v-model="data.Tipotransacion_id"
                            required>
                        </v-autocomplete>
                    </v-flex&ndash;&gt;
                    <v-flex xs6>
                        <v-autocomplete label="Bodega origen*" :items="listaBodegas" item-text="Nombre" item-value="id"
                            @change="getBodegaArticulo()" v-model="data.BodegaOrigen_id" required>
                        </v-autocomplete>
                    </v-flex>
                    <v-flex xs6>
                        <v-autocomplete label="Proveedor*" :items="listaPrestadores" item-text="Nombre" item-value="id"
                            v-model="data.prestador_id" required>
                        </v-autocomplete>
                    </v-flex>
                    <v-flex xs4>
                        <v-text-field name="input-7-1" label="Numero de Orden Zeus" value=""
                            v-model="data.Numfacturazeus"></v-text-field>
                    </v-flex>
                    <v-flex xs4>
                        <v-text-field name="input-7-1" label="Num Factura" value="" v-model="data.Numfactura">
                        </v-text-field>
                    </v-flex>
                    <v-flex xs4 v-if="tipoNombreSelected != ''">
                        <v-btn color="success" type="button" @click="addBodegaTransaccion()">Agregar articulo</v-btn>
                    </v-flex>
                    <v-flex xs12 v-for="(bodegaArticulo, index) in data.bodegarticulos" v-bind:key="index">
                        <v-container>
                            <v-layout row wrap>
                                <v-flex xs5 px-1>
                                    <v-autocomplete label="Medicamento*" :items="CUMSconcat"
                                        item-text="Principio_Activo" item-value="id" v-model="bodegaArticulo.Lote_id"
                                        required>
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs1 px-1>
                                    <v-text-field :label="'Cantidad*'" v-model="bodegaArticulo.Cantidadtotal" required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs1 px-1>
                                    <v-text-field :label="'Cantidad en Factura*'"
                                        v-model="bodegaArticulo.CantidadtotalFactura" required>
                                    </v-text-field>
                                </v-flex>

                                <v-flex xs1 px-1>
                                    <v-text-field label="$ Precio*" v-model="bodegaArticulo.Precio" required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs1 px-1>
                                    <v-text-field label="Lote*" v-model="bodegaArticulo.Lote" required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs1 px-1>
                                    <v-btn color="error" type="button" @click="deleteTransaccion(index)">Eliminar
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-flex>
                    <v-flex xs12>
                        <v-layout row wrap>
                            <v-spacer></v-spacer>
                            <v-btn color="success" type="submit">Guardar Movimiento</v-btn>
                            <v-spacer></v-spacer>
                        </v-layout>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-form>-->
        <v-data-table :headers="headers" :items="listaMovimientosShow">

            <template v-slot:items="props">
                <td>{{ props.item.id}}</td>
                <td class="text-xs-center">{{ props.item.NomEstado}}</td>
                <td class="text-xs-center">{{ props.item.NombreTransacion}}</td>
                <td class="text-xs-center">{{ props.item.BodegaOrigen}}</td>
                <td class="text-xs-center" v-if="props.item.Tipotransacion_id == typeTransactions['compra']">
                    {{ props.item.NomPrestador}}</td>
                <td class="text-xs-center" v-if="props.item.Tipotransacion_id == typeTransactions['traslado']">
                    {{ props.item.BodegaDestino}}</td>
                <td class="text-xs-center">{{ props.item.NomReps}}</td>
                <td class="text-xs-center">{{ props.item.NomusuarioCrea}}</td>
                <td class="text-xs-center">
                    <v-btn color="warning" dark v-on:click="verDetalle(props.item.id)">Recibir Mercancia</v-btn>
                </td>

            </template>
            <template v-slot:no-data>
                <v-alert :value="true" color="error" icon="warning">
                    No hay movimientos en este Estado.
                </v-alert>
            </template>

        </v-data-table>
    </v-card>
</template>

<script>
    import {
        Transaction
    } from '../../../models/Transaction'
    import {
        Movimiento
    } from '../../../models/Movimiento'
    import {
        EventBus
    } from '../../../event-bus.js';
    import DialogEntradaMovimiento from './DialogEntradaMovimiento';


    export default {
        name: 'BodegaMovimientos',
        components: {
            DialogEntradaMovimiento,
        },
        data() {
            return {

                listaMovimientos: [],
                listaMovimientosShow: [],
                headers: [{
                        text: 'Id',
                        sortable: false
                    },
                    {
                        text: 'Estado',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Transaccion',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Bodega origen',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Destino / Prestador',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Resp',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Usuario',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Detalle',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Confirmar',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Eliminar',
                        sortable: false,
                        align: 'center'
                    },
                ],
                data: {
                    Numfacturazeus: '',
                    Numfactura: '',
                    Tipotransacion_id: '1',
                    BodegaOrigen_id: '',
                    Reps_id: '',
                    BodegaDestino_id: '',
                    bodegarticulos: [],

                },
                dataProducto: {
                    id: '',
                    Lote_id: '',
                    Fvence: '',
                    Cantidadtotal: '',
                    Precio: '',
                    Lote: '',
                    CantidadtotalFactura: ''
                },
                typeTransactions: Transaction.typeTransaction,
                estadosMovimiento: Movimiento.estados,
                idMovimientoDetalle: 0,
                dialogDetalleOpen: false
            }
        },
        created() {
            EventBus.$on('close-dialog-detalle-movimiento', () => {
                this.dialogDetalleOpen = false;
                this.idMovimientoDetalle = 0;
            })
        },
        mounted() {

            this.fetchMovimientos()
        },
        computed: {
            filteredTransacciones() {
                return this.listaTiposTransaccion.filter((tipoTransaccion) => tipoTransaccion.TipoNombre === this
                    .tipoNombreSelected)
            },
            CUMSconcat() {
                return this.listaBodegaArticulos.map(cums => {
                    return {
                        Principio_Activo: `${cums.CUM_completo} - ${cums.Principio_Activo}`
                    };
                });
            }
        },
        watch: {
            tipoNombreSelected: function () {
                const tipoTransaccion = this.listaTiposTransaccion.filter(tipoTransaccion => {
                    return tipoTransaccion['id'] == this.tipoNombreSelected;
                })
                this.data.Tipotransacion_id = tipoTransaccion[0]['id']
            }
        },
        methods: {
            filterEstado(value) {

                // console.log(value);
                this.listaMovimientosShow = this.listaMovimientos.filter(mov => {
                    // console.log(value == mov.Estado_id)
                    return value == mov.Estado_id;
                })
            },
            fetchMovimientos() {
                axios.get('/api/movimiento/all')
                    .then((res) => {
                        this.listaMovimientos = res.data;
                        // llamaos el filtro de estado con estado 4 (por confirmar)
                        this.filterEstado(7);
                    })
            },

            saveMovimiento() {
                const msg = 'Recuerde que si el movimiento tiene un error debe eliminarlo y volver a crearlo';
                swal({
                    title: msg,
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((confirm) => {
                    if (confirm) {
                        axios.post('/api/movimiento/' + this.data.BodegaOrigen_id + '/create', this.data)
                            .then(res => {
                                this.fetchMovimientos()
                                this.clearForm()
                            })
                    }

                });

            },

            verDetalle(idMovimiento) {
                this.dialogDetalleOpen = true;
                this.idMovimientoDetalle = idMovimiento
            },

        }
    }

</script>

<style lang="scss" scoped>

</style>
