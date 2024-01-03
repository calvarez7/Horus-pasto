<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-card>
                <v-dialog v-model="update" persistent max-width="400px">
                    <v-card>
                        <v-card-title class="headline grey lighten-2" primary-title>
                            ¿Está segur@ de cambiar la cantidad de Traslado?
                        </v-card-title>
                        <v-card-text>
                            <v-textarea name="input-7-1" label="Observacion" v-model="observaciones">
                            </v-textarea>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" text @click="submitUpdate()">
                                Aceptar
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
                                item-value="id" @change="getTraslados()" v-model="Bodega_id" required>
                            </v-autocomplete>
                        </v-flex>
                    </v-layout>
                </v-container>
                <v-data-table v-if="listTraslados.length > 0" :headers="header" :items="listTraslados">
                    <template v-slot:items="props">
                        <td class="text-xs-center">{{ props.item.id}}</td>
                        <td class="text-xs-center">{{ props.item.Bodega_Nombre}}</td>
                        <td class="text-xs-center">{{ props.item.Medicamento}}</td>
                        <td class="text-xs-center">
                            <v-edit-dialog :return-value.sync="props.item.CantidadtotalFactura" large lazy persistent
                                cancel-text="Cancelar" save-text="Cambiar" @save="updateCant(props.item)">
                                <div>{{ props.item.CantidadtotalFactura }}</div>
                                <template v-slot:input>
                                    <div class="mt-3 title">Update Número Meses</div>
                                </template>
                                <template v-slot:input>
                                    <v-text-field v-model="props.item.CantidadtotalFactura" label="Edit" single-line
                                        counter autofocus></v-text-field>
                                </template>
                            </v-edit-dialog>
                        </td>
                        <td class="text-xs-center">{{ props.item.CUM_completo}}</td>
                        <td class="text-xs-center">{{ props.item.Numlote}}</td>
                        <td class="text-xs-center">
                            <v-tooltip top>
                                <template v-slot:activator="{ on }">
                                    <v-btn fab outline color="green" small v-on="on"
                                        @click="acceptTransfer(props.item)">
                                        <v-icon>edit</v-icon>
                                    </v-btn>
                                </template>
                                <span>Aceptar</span>
                            </v-tooltip>
                        </td>
                    </template>
                </v-data-table>
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
        name: 'GestionTraslados',
        data() {
            return {
                listBodegas: [],
                listTraslados: [],
                Traslado: {},
                Bodega_id: '',
                observaciones: '',
                update: false,
                header: [
                    {
                        text: 'Identificación',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Bodega Origen',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Medicamento',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Cantidad trasladada',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'CUM',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Lote',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: '',
                        sortable: false,
                        align: 'center'
                    },
                ],  
            }
        },
        mounted() {
            this.fetchBodegas();
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
            getTraslados() {
                axios.get(`/api/movimiento/${this.Bodega_id}/getTraslado`).then(res => { 
                    this.listTraslados = res.data
                }).catch(err => this.showError(err.response.data.message));
            },
            updateCant(traslado) {
                this.Traslado = traslado;
                this.update = true;
            },
            acceptTransfer(traslado) {

                swal({
                    title: 'Está segur@ de aceptar el traslado',
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.post(`/api/movimiento/acceptTransfer`, traslado).then(res => {
                            swal("Traslado aceptado de manera exitosa!", {
                                timer: 2000,
                                icon: "success",
                                buttons: false
                            });
                            this.getTraslados();
                        }).catch(err => this.showError(err.response.data.message));
                    }
                });
            },
            cancelDialog() {
                this.Traslado = {};
                this.update = false;
                this.observaciones = '';
            },
            submitUpdate() {
                if (this.observaciones == "") {
                    swal({
                        title: "Debe llenar la Observacion",
                        icon: "warning"
                    });
                    return;
                }

                console.log("Traslado", this.Traslado);
                axios.post(`/api/movimiento/updateTransfer/${this.Traslado.id}`, {
                    observaciones: this.observaciones,
                    ...this.Traslado
                }).then(res => {
                    swal("Traslado modificado de manera exitosa!", {
                        timer: 2000,
                        icon: "success",
                        buttons: false
                    });
                    this.cancelDialog();
                    this.getTraslados();
                }).catch(err => this.showError(err.response.data.message));

            },
            showError(message) {
                swal({
                    title: "¡No puede ser!",
                    text: `${message}`,
                    icon: "warning",
                });
            },
        }
    }

</script>