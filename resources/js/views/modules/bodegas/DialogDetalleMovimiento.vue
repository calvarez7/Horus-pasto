<template>
    <div>
        <v-layout
            justify-center
            wrap>
            <v-dialog
                v-model="dialogOpen"
                fullscreen
                hide-overlay
                transition="dialog-bottom-transition" 
                scrollable >
                <v-card tile>
                    <v-toolbar
                        flat
                        dark
                        color="primary">
                        <v-btn
                            dark
                            icon
                            @click="closeDialog()">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Detalle Movimiento</v-toolbar-title>
                        <VSpacer />
                    </v-toolbar>
                    <v-card-text>
                        <v-data-table
                            :headers="headers"
                            :items="listaMovimientoArticulos"
                            >
                            <template v-slot:items="props">
                                <td class="text-xs-center">{{ props.item.Principio_Activo}}</td>
                                <td class="text-xs-center">{{ props.item.Cantidadtotal}}</td>
                                <td class="text-xs-center">{{ props.item.CantidadtotalFactura}}</td>
                                <td class="text-xs-center">{{ props.item.Precio}}</td>
                                <td class="text-xs-center">{{ props.item.Numlote}}</td>
                                <td v-if="status_movimiento == 3" class="text-xs-center"><span class="diferencia" :class="getClassDiferencia(props.item.Cantidadtotal - props.item.CantidadtotalFactura)">{{ props.item.Cantidadtotal - props.item.CantidadtotalFactura}}</span></td>
                            </template>
                            <template v-slot:no-data>
                                <v-alert :value="true"  color="error" icon="warning">
                                    No hay movimientos en este Estado.
                                </v-alert>
                            </template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </v-layout>
    </div>
</template>

<script>

    import {EventBus} from '../../../event-bus.js';

    export default {
        name: 'DialogDetalleMovimiento',
        props:{
            dialogOpen: {
                type: Boolean,
                default: false
            },
            idMovimientoDetalle: {
                type: Number,
                default: 0
            },
            status_movimiento: {
                type: Number,
                default: 15
            },
        },

        data() {
            return {
                listaMovimientoArticulos:[],
                headers: [
                    { text:'Principio_Activo', sortable: false, align: 'center' },
                    { text:'Cantidadtotal', sortable: false, align: 'center' },
                    { text:'CantidadtotalFactura', sortable: false, align: 'center' },
                    { text:'Precio', sortable: false, align: 'center' },
                    { text:'Lote', sortable: false, align: 'center' },
                    { text:'', sortable: false, align: 'center' },


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