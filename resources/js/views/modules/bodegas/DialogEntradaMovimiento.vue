<template>
    <div>
        <v-layout justify-center wrap>

            <v-dialog v-model="dialogOpen" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable>
                <v-card tile>
                    <v-toolbar flat dark color="primary">
                        <v-btn icon dark @click="closeDialog()">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Entrada Movimiento</v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-card-text>
                        <v-data-table :headers="headers" :items="listaMovimientoArticulos">

                            <template v-slot:items="props">
                                <td class="text-xs-center">{{ props.item.Principio_Activo}}</td>
                                <td class="text-xs-center">{{ props.item.Cantidadtotal}}</td>
                                <td class="text-xs-center">
                                    <v-text-field @input="changeCantidadFactura($event, props.item.id)"
                                        label="Cantidad Factura"></v-text-field>
                                    {{props.item.CantidadtotalFactura}}
                                </td>
                                <td class="text-xs-center">
                                    <v-text-field @input="changeNumLote($event, props.item.id)" label="Num Lote">
                                    </v-text-field>
                                    {{props.item.Numlote}}
                                </td>
                                <td class="text-xs-center">{{ props.item.Precio}}</td>
                            </template>
                            <template v-slot:no-data>
                                <v-alert :value="true" color="error" icon="warning">
                                    No hay movimientos en este Estado.
                                </v-alert>
                            </template>

                        </v-data-table>
                        <v-flex xs12>
                            <v-layout row wrap>
                                <v-spacer></v-spacer>
                                <v-btn @click="guardarEntrada()">Guardar Entrada Movimiento</v-btn>
                                <v-spacer></v-spacer>
                            </v-layout>
                        </v-flex>

                    </v-card-text>
                </v-card>

            </v-dialog>


        </v-layout>
    </div>
</template>

<script>
    import {
        EventBus
    } from '../../../event-bus.js';

    export default {
        name: 'DialogDetalleMovimiento',
        props: {
            dialogOpen: {
                type: Boolean,
                default: false
            },
            idMovimientoDetalle: {
                type: Number,
                default: 0
            },
        },

        data() {
            return {
                listaMovimientoArticulos: [],
                headers: [{
                        text: 'Principio_Activo',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Cantidadtotal',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'CantidadtotalFactura',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Lote',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Precio',
                        sortable: false,
                        align: 'center'
                    },
                ],
            }

        },
        watch: {
            idMovimientoDetalle: function () {
                if (this.dialogOpen) {
                    this.fetchMovimientoArticulos();
                }
            }
        },
        methods: {
            closeDialog() {
                EventBus.$emit('close-dialog-detalle-movimiento')
            },
            fetchMovimientoArticulos() {
                axios.get(`/api/movimiento/articulos/${this.idMovimientoDetalle}/`)
                    .then(res => this.listaMovimientoArticulos = res.data)
            },
            getClassDiferencia(diferencia) {
                if (diferencia > 0) {
                    return 'info'
                } else if (diferencia < 0) {
                    return 'warning'
                } else {
                    return ''
                }
            },
            changeCantidadFactura(value, id) {
                let index = this.listaMovimientoArticulos.findIndex(i => i.id === id);
                this.listaMovimientoArticulos[index].CantidadtotalFactura = value;
            },
            changeNumLote(value, id) {
                let index = this.listaMovimientoArticulos.findIndex(i => i.id === id);
                this.listaMovimientoArticulos[index].Numlote = value;
            },
            guardarEntrada() {
                const msg = 'Esta Seguro de guardar los cambios ?';
                swal({
                    title: msg,
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((confirm) => {
                    if (confirm) {
                        let data = {
                            bodega_transaccions: this.listaMovimientoArticulos
                        }
                        axios.post('/api/movimiento/' + this.idMovimientoDetalle + '/entrace', data)
                            .then(res => {

                            })
                    }

                });
            }
        }
    }

</script>

<style scoped>
    span.diferencia {
        padding: 10px;
        border-radius: 30px;
    }

</style>
