<template>
    <v-layout row wrap>
        <v-dialog v-model="preload" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Tranquilo procesamos tu solicitud !
                    <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-dialog v-model="dialog" persistent max-width="800px">
            <v-card>
                <v-card-title>
                    <span class="headline">Asignar Cantidad</span>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-text-field label="Medicamento*" v-model="dataTraslado.Medicamento"
                                    required readonly></v-text-field>
                            </v-flex>
                            <v-flex xs3>
                                <v-text-field label="Cantidad Solicitada" v-model="cantidadSolicitada" outline placeholder="*" readonly></v-text-field>
                            </v-flex>
                            <v-flex xs3>
                                <v-text-field label="Cantidad Traslado" v-model="cantidadtraslado" outline placeholder="*" readonly></v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-data-table :items="listLote" class="elevation-1" hide-actions>
                                    <template v-slot:headers>
                                        <tr>
                                            <th><strong>COD</strong></th>
                                            <th><strong>LOTE</strong></th>
                                            <th><strong>DISPONIBLE</strong></th>
                                            <th><strong>TRASLADO</strong></th>
                                        </tr>
                                    </template>
                                    <template v-slot:items="props">
                                        <tr>
                                            <td class="text-xs-center">{{ props.item.id}}</td>
                                            <td class="text-xs-center">{{ props.item.Numlote}}</td>
                                            <td class="text-xs-center">{{ props.item.Cantidadisponible}}</td>
                                            <td class="text-xs-center">
                                                <form>
                                                <v-layout row wrap justify-center>
                                                    <v-flex xs7>
                                            <v-text-field row type="number" @input="validarCantidadLote($event)" v-model="props.item.cantidadTraslado" min="0"
                                                          onkeypress="return (event.charCode >= 48 && event.charCode <= 57 || event.charCode === 44)"></v-text-field>
                                                    </v-flex>
                                                </v-layout>
                                                </form>
                                            </td>
                                        </tr>
                                    </template>
                                </v-data-table>

                            </v-flex>

                            <!--                            <v-flex xs12 sm6 md6>-->
<!--                                <v-autocomplete label="Lote*" :items="listLote"-->
<!--                                    item-text="Numlote" item-value="id"-->
<!--                                    v-model="dataTraslado.Lote" :search-input.sync="findBodegaArticulo_id" required></v-autocomplete>-->
<!--                            </v-flex>-->
<!--                            <v-flex xs4 sm2 md2>-->
<!--                                <v-text-field label="Disponible" v-model="cantidadDisponible" outline placeholder="*" readonly></v-text-field>-->
<!--                            </v-flex>-->

<!--                            <v-flex xs4 sm2 md2>-->
<!--                                <v-text-field label="Solicitada" v-model="cantidadSolicitada" outline placeholder="*" readonly></v-text-field>-->
<!--                            </v-flex>-->

<!--                            <v-flex xs4 sm2 md2>-->
<!--                                <v-text-field type="number"-->
<!--                                        min="1"-->
<!--                                        onkeypress="return event.charCode >= 48"-->
<!--                                        oncopy="return false" onpaste="return false" label="Cantidad*" v-model="dataTraslado.Cantidad"-->
<!--                                    required></v-text-field>-->
<!--                            </v-flex>-->
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="closeDialogTraslado()">Cancelar</v-btn>
                    <v-btn color="blue darken-1" flat @click="saveTraslado()">Guardar</v-btn>
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
        <v-flex xs12>
            <v-card>
                <v-container>
                    <v-layout row wrap>
                        <v-flex xs4>
                            <VAutocomplete
                                :items="listaTiposTransaccionByRole"
                                item-text="TransacionNombre"
                                item-value="id"
                                label="Tipo transación*"
                                required
                                v-model="data.Tipotransacion_id"
                                @change="clearPresBodRep()" />
                        </v-flex>
                        <VSpacer />
                        <v-flex xs6 pl-2>
                            <VAutocomplete
                                :items="listBodegasByRole"
                                item-text="Nombre"
                                item-value="id"
                                :label="data.Tipotransacion_id == 1? 'Bodega Solicitante*':'Solicitudes Bodega*'"
                                required
                                v-model="data.BodegaOrigen_id"
                                @change="getBodegaArticulo()" />
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card>
        </v-flex>
        <v-flex xs12 my-2>
            <v-card>
                <v-card-title primary-title>
                    <v-layout row wrap>
                        <v-flex xs6>
                            <v-layout row wrap>
                                <v-flex xs8 pl-2 v-show="data.Tipotransacion_id == 2">
                                    <VAutocomplete
                                        :items="filteredBodega"
                                        item-text="Nombre"
                                        item-value="id"
                                        label="Bodega solicitante*"
                                        required
                                        clearable
                                        v-model="data.BodegaDestino_id" />
                                </v-flex>
                                <v-flex xs12>
                                    <v-btn
                                        color="primary"
                                        outline
                                        round
                                        v-show="data.articuloSelected.length > 0"
                                        @click="requestAcepted()"
                                    >Aceptar Solicitud</v-btn>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                        <VSpacer />
                        <v-flex
                            sm5
                            xs12
                            >
                            <v-text-field
                                v-model="search"
                                append-icon="search"
                                label="Buscar..."
                                single-line
                                hide-details
                            ></v-text-field>
                        </v-flex>
                    </v-layout>
                </v-card-title>
                <v-card-text>
                    <v-data-table
                        :headers="headers"
                        :items="solicitudes"
                        v-model="data.articuloSelected"
                        v-show="data.Tipotransacion_id == 1"
                        :expand="expand"
                        item-key="id"
                        :search="search"
                    >
                        <template v-slot:headers="props">
                            <tr>

                                <th
                                    v-for="header in props.headers"
                                    :key="header.text"
                                    :class="`text-xs-${header.align}`">
                                    {{ header.text }}
                                </th>
                            </tr>
                        </template>
                        <template v-slot:items="props">
                            <tr>
                                <td class="text-xs-left">
                                        <v-btn fab dark small color="primary" @click="imprimir({type:'ordenCompra',id:props.item.id})">
                                            <v-icon dark>file_download</v-icon>
                                        </v-btn>
                                    <v-chip label small color="green" text-color="white" @click="imprimirExcel(props.item.id)">EXCEL</v-chip>
                                </td>
                                <td class="text-xs-center">{{ props.item.id }}</td>
                                <td class="text-xs-left">{{ props.item.usuario}}</td>
                                <td class="text-xs-left">{{ props.item.created_at }}</td>
                                <td class="text-xs-center">
                                    <v-layout row wrap justify-center>
                                    <v-flex xs8>
                                        <v-autocomplete
                                            :items="sedesproveedores"
                                            item-text="Nombre"
                                            item-value="id"
                                            v-model="props.item.sedeproveedore_id"
                                            label="Proveedor"
                                        @change="fetchSolicitudDetalles(props.item.id,props.item.sedeproveedore_id,props)"
                                        />
                                    </v-flex>
                                    </v-layout>
                                </td>
                                <td class="text-xs-right">
                                    <v-btn outline color="success" v-show="validarMedicamentos(props.item.sedeproveedore_id)" @click="generarAutorizacion(props.item,7,props.item.sedeproveedore_id,props)" round>Autorizar</v-btn>
                                    <v-btn outline color="error" v-show="props.item.sedeproveedore_id"  @click="generarAutorizacion(props.item,26,props.item.sedeproveedore_id,props)" round>Anular</v-btn>
                                </td>
                            </tr>

                        </template>
                        <template v-slot:expand="props">
                            <v-card flat>
                                <v-alert :value="true" v-if="data.Tipotransacion_id == 1" type="info">
                                    Esta a punto de autorizar o anular una solicitud de compra por un valor total de <strong>
                                    $ {{ precioTotalFactura() }} </strong>
                                </v-alert>
                                <v-data-table :headers="headersMedicamentos" :items="solicitudDetalles" item-key="id" hide-actions>
                                    <template class="text-xs-center" v-slot:items="props">
                                        <td v-if="props.item.state === null || props.item.state === 2">
                                            <v-btn flat icon color="gray">
                                            <v-icon>remove</v-icon>
                                            </v-btn>
                                        </td>
                                        <td class="text-xs-center" v-else-if="parseInt(props.item.state) === 1 && parseFloat(props.item.precio_unidad) > 0 &&  parseInt(props.item.Cantidad) > 0">
                                            <v-btn flat icon color="green">
                                                <v-icon>done</v-icon>
                                            </v-btn>
                                        </td>
                                        <td class="text-xs-center" v-else-if="parseInt(props.item.state) === 3 && parseInt(props.item.Cantidad) > 0">
                                            <v-btn flat icon color="orange">
                                                <v-icon>done</v-icon>
                                            </v-btn>
                                        </td>
                                        <td class="text-xs-center" v-else-if="parseInt(props.item.state) === 0">
                                            <v-btn flat icon color="red">
                                                <v-icon>clear</v-icon>
                                            </v-btn>
                                        </td>
                                        <td class="text-xs-center" v-else-if="parseInt(props.item.state) === 1 && !props.item.Cantidad || parseInt(props.item.state) === 1 && parseInt(props.item.Cantidad) <= 0">
                                            <v-tooltip top>
                                                <template v-slot:activator="{ on }">
                                                    <v-btn flat icon color="error" small v-on="on">
                                                        <v-icon>error</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Cantidad Invalida</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-center" v-else-if="parseInt(props.item.state) === 1 && !props.item.precio_unidad || parseInt(props.item.state) === 1 && parseFloat(props.item.precio_unidad) <= 0">
                                            <v-tooltip top>
                                                <template v-slot:activator="{ on }">
                                                    <v-btn flat icon color="error" small v-on="on">
                                                        <v-icon>error</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Precio Invalida</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-center">{{ props.item.id}}</td>
                                        <td class="text-xs-center">{{ props.item.medicamento}}</td>
                                        <td class="text-xs-center">{{ props.item.titular}}</td>
                                        <td class="text-xs-center">{{ props.item.unidad_compra}}</td>
                                        <td class="text-xs-center">

                                            <v-layout row wrap justify-center>
                                                <v-flex xs12>
                                                    <v-text-field :disabled="props.item.state === 0" rows="10" cols="10" type="number" min="1" step="any" onkeypress="return (event.charCode >= 48 && event.charCode <= 57 || event.charCode === 44)" v-model="props.item.precio_unidad"></v-text-field>
                                                </v-flex>
                                            </v-layout>

                                        </td>
                                        <td class="text-xs-center">
                                            <v-layout row wrap justify-center>
                                                <v-flex xs8>
                                            <v-text-field :disabled="props.item.state === 0" rows="10" cols="10" type="number" min="1" :rules="numberRules" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)" v-model="props.item.Cantidad"></v-text-field>
                                                </v-flex>
                                            </v-layout>
                                        </td>
                                        <td class="text-xs-center">$ {{  props.item.valorTotal = ((!props.item.precio_unidad?0:parseFloat(props.item.precio_unidad))*(!props.item.Cantidad?0:parseInt(props.item.Cantidad))).toFixed(2)}}</td>
                                        <td class="text-xs-center">
                                            <v-tooltip top v-if="parseInt(props.item.state) === 1 || parseInt(props.item.state) === 3">
                                                <template v-slot:activator="{ on }">
                                                    <v-btn fab outline color="error" small v-on="on" @click="props.item.state = 0,props.item.Cantidad = props.item.cantidad_inicial,props.item.precio_unidad = props.item.precio_inicial">
                                                        <v-icon>delete</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Eliminar</span>
                                            </v-tooltip>

                                            <v-tooltip top v-if="parseInt(props.item.state) === 0 || parseInt(props.item.state) === 3 || parseInt(props.item.state) === 2">
                                                <template v-slot:activator="{ on }">
                                                    <v-btn fab outline color="primary" small v-on="on" @click="props.item.state = 1,props.item.Cantidad = props.item.cantidad_inicial,props.item.precio_unidad = props.item.precio_inicial">
                                                        <v-icon>restore</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Deshacer</span>
                                            </v-tooltip>
                                            <v-tooltip top v-if="parseInt(props.item.state) === 2">
                                                <template v-slot:activator="{ on }">
                                                    <v-btn fab outline color="warning" small v-on="on" @click="props.item.state = 3">
                                                        <v-icon>done</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Confirmar</span>
                                            </v-tooltip>
                                        </td>


                                    </template>
                                </v-data-table>
                            </v-card>
                        </template>
<!--                        <v-alert v-slot:no-results :value="true" color="error" icon="warning">-->
<!--                            Your search for "{{ search }}" found no results.-->
<!--                        </v-alert>-->
                    </v-data-table>
                    <v-data-table
                        :headers="trasladoHeader"
                        :items="solicitudTrasladosFiltered"
                        v-show="data.Tipotransacion_id == 2"
                        :search="search" >
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
                </v-card-text>
            </v-card>
        </v-flex>
        <v-flex pt-2>
            <v-card>
                <v-data-table  v-if="traslados.length > 0" :headers="headersTraslados" :items="traslados" hide-actions
                    class="elevation-1" pagination.sync="pagination" item-key="id">
                    <template v-slot:items="props">
                        <td class="text-xs-center">{{ props.item.Solicitud_id }}</td>
                        <td class="text-xs-center">{{ props.item.Medicamento }}</td>
                        <td class="text-xs-center">
                            <template v-for="lote in props.item.Lote">
                                <strong >{{lote.Numlote}}</strong><br>
                            </template>
                        </td>
                        <td class="text-xs-center">{{ props.item.Cantidad }}</td>
                        <td class="text-xs-center">
                            <!-- <v-btn color="error" @click="removeTraslado(props)" icon>
                                <v-icon>clear</v-icon>
                            </v-btn> -->
                        </td>
                    </template>
                    <v-alert v-slot:no-data :value="true" color="error" icon="warning">No hay datos.
                    </v-alert>
                </v-data-table>
                <v-layout row wrap>
                    <v-spacer></v-spacer>

                    <v-flex my-2>
                        <v-btn v-if="traslados.length > 0" color="success" round @click="generateTraslate()">Generar Traslado</v-btn>
                    </v-flex>
                </v-layout>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    import { mapGetters } from 'vuex';
    import {
        Movimiento
    } from '../../../models/Movimiento'

    import {
        EventBus
    } from '../../../event-bus.js';
    import moment from 'moment';

    export default {
        name: 'BodegaAuditoria',
        data() {
            return {
                preload:false,
                cantidadtraslado:0,
                cantidadSolicitada:null,
                cantidadDisponible:null,
                solicitudDetalles: [],
                numberRules: [
                    v => v.length > 0 || "La cantidad es requerido",
                    v => v > 0 || "La cantida debe ser mayor a 0"
                ],
                expand: false,
                traslados: [],
                entrada: false,
                listaTiposTransaccion: [
                    {
                        id: 1,
                        TransacionNombre: 'Solicitud Compra'
                    },
                    // {
                    //     id: 2,
                    //     TransacionNombre: 'Traslado'
                    // },
                ],
                dialog: false,
                listaBodegas: [],
                listBodegasByRole: [],
                listaBodegaArticulos: [],
                listCum: [],
                listArticulos: [],
                listaTitular: [],
                nombre: '',
                observaciones: '',
                article: '',
                loading: false,
                today: moment().format('YYYY-MM-DD'),
                articulo: {
                    bodegaArticulo: {},
                    Cantidadtotal: 0,
                    Titular: {},
                    lote: '',
                    // Cum: ''
                },
                trasladoHeader:[
                    {
                        text: 'Identificación de la solicitud',
                        value: 'id',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Bodega Solicitante',
                        value: 'Nombre_BodegaOrigen',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Medicamento',
                        value:'Medicamento',
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
                headerArticulosCompra: [{
                        text: 'CUM / Medicamento',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Titular',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Cantidad',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Cantidad factura',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Precio unidad',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Lote',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Fecha Vencimiento',
                        sortable: false,
                        align: 'center'
                    },
                ],
                headerArticulos: [{
                        text: '#',
                        sortable: false
                    },
                    {
                        text: 'CUM',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Medicamento',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Titular',
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
                    {
                        text: '',
                        sortable: false,
                        align: 'center'
                    },
                ],
                headers: [
                    {
                        text: '',
                        align: 'right',
                        sortable: false,
                        value: ''
                    },
                    {
                        text: '# solicitud',
                        align: 'center',
                        sortable: false,
                        value: 'id'
                    },
                    {
                        text: 'Solicitante',
                        align: 'left',
                        sortable: false,
                        value: ''
                    },
                    {
                        text: 'Fecha Creacion',
                        align: 'left',
                        sortable: false,
                        value: ''
                    },
                    {
                        text: 'Proveedor',
                        align: 'center',
                        sortable: false,
                        value: ''
                    },
                    {
                        text: ' ',
                        align: 'left',
                        sortable: false,
                        value: ''
                    }
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
                        text: 'Lote',
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
                headersMedicamentos:[
                    {
                        text: '',
                        sortable: true,
                        align: 'center'
                    },
                    {
                        text: '# OrdenCompra',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Medicamento',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Titular',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Presentacion',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Precio Unidad',
                        value: "precio_unidad",
                        sortable: true,
                        align: 'center'
                    },
                    {
                        text: 'Cantidad',
                        sortable: true,
                        value: "Cantidad",
                        align: 'center'
                    }
                    ,
                    {
                        text: 'Valor Total',
                        sortable: true,
                        value: "valorTotal",
                        align: 'center'
                    }
                    ,
                    {
                        text: '',
                        align: 'center'
                    }
                ],
                headerArticulostraslado: [{
                        text: '#',
                        value: "",
                        align: 'center'
                    },
                    // {
                    //     text: 'Cum',
                    //     value: "Edad_Cumplida",
                    //     align: 'center'
                    // },
                    {
                        text: 'Nombre',
                        value: "",
                        align: 'center'
                    },
                    {
                        text: 'Codigo',
                        value: "",
                        align: 'center'
                    },
                    {
                        text: 'Cantidad',
                        value: "",
                        align: 'center'
                    }
                ],
                orden: {
                    Numfacturazeus: '',
                    Numfactura: '',
                    bodegarticulos: [],
                    selected: [],
                },
                data: {
                    Tipotransacion_id: '',
                    Proveedor_id: '',
                    BodegaOrigen_id: '',
                    BodegaDestino_id: '',
                    bodegarticulos: [],
                    articuloSelected: [],
                    sedeselected: 0,
                },
                dataProducto: {
                    id: '',
                    Bodegarticulo_id: '',
                    Fvence: '',
                    Cantidadtotal: '',
                    Precio: '',
                    Lote: '',
                },
                search: '',
                sedesproveedores: [],
                solicitudes: [],
                solicitudTraslados: [],
                listLote: [],
                findBodegaArticulo_id: '',
                dataTraslado: {},
                cancel: false,
            }
        },
        watch: {
            findBodegaArticulo_id: function () {
                let Lote = this.listLote.find(lote => lote.id == this.dataTraslado.Lote);
                if (Lote) {
                    this.cantidadDisponible = Lote. Cantidadisponible;
                    this.dataTraslado.BodegaArticulo_id = Lote.Bodegarticulo_id;
                }
            }
        },
        mounted() {
            this.fetchBodegaByRole()
            this.fetchBodegas()
        },
        computed: {
            ...mapGetters(['can']),
            filteredBodega() {
                return this.listaBodegas.filter(bodega => {
                    if (this.data.BodegaOrigen_id != bodega.id) return bodega;
                });
            },
            listaTiposTransaccionByRole(){
                let tras = []
                if(this.can('bodega.compra.auditar')) tras.push(this.listaTiposTransaccion[0])
                // tras.push(this.listaTiposTransaccion[1])
                return tras;
            },
            solicitudTrasladosFiltered(){
                if(!this.data.BodegaDestino_id) return this.solicitudTraslados;
                return this.solicitudTraslados.filter(soli => soli.Bodegaorigen_id == this.data.BodegaDestino_id)
            }
        },
        methods: {
            assignTransfer(solicitud) {
                this.cantidadSolicitada = null;
                this.cantidadDisponible = null;
                this.listLote = [];
                this.dataTraslado.Cantidad = null;
                this.dataTraslado = {
                    Solicitud_id: solicitud.id,
                    Codesumi_id: solicitud.Codesumi_id,
                    Medicamento: solicitud.Medicamento,
                    CantidadTotal: solicitud.Cantidad,
                    detalle: solicitud.detalle_id,
                    Lote: [],
                    BodegaOrigen_id: this.data.BodegaOrigen_id, //La bodega destino de la solicitud pasa a ser la bodega origen del traslado
                    BodegaDestino_id: solicitud.Bodegaorigen_id //La bodega origen de la solicitud pasa a ser la bodega destino del traslado
                }
                this.cantidadSolicitada = solicitud.Cantidad;
                // this.fetchBodegaArticulos(solicitud.Medicamento);
                this.getLotesDetalleArticulo(solicitud.detallearticulo_id);
                this.dialog = true;
            },
            fetchBodegas(){
                axios.get('/api/bodega/all')
                    .then(res => this.listaBodegas = res.data)
            },
            fetchBodegaByRole() {
                axios.get('/api/bodega/getBodegaByRole')
                    .then(res => {
                        if (res.data.length > 0) {
                            this.listBodegasByRole = res.data
                        }
                    })
            },
            fetchBodegaArticulos(medicamento) {
                console.log(medicamento)
                // axios.post(`/api/bodega/${this.data.BodegaOrigen_id}/articulo/all`, {
                //         nombre: medicamento
                //     })
                //     .then(res => {
                //         this.listaBodegaArticulos = res.data;
                //
                //         this.listaBodegaArticulos.forEach(articulo => {
                //             articulo.detallearticulos.forEach(each => {
                //                 this.listCum.push(each)
                //             })
                //         })
                //     })
            },
            fetchSolicitudTraslado() {
                axios.get(`/api/bodega/${this.data.BodegaOrigen_id}/solicitud/getSolicitudByBodegaDestino`)
                    .then(res => this.solicitudTraslados = res.data);
            },
            fetchRequest(){
                axios.post(`/api/bodega/${this.data.BodegaOrigen_id}/${this.data.sedeselected}/ordencompra/getSolicitudesPendientes`)
                    .then(res => {
                        this.solicitudes = res.data
                        })
            },
            getBodegaArticulo() {
                switch (this.data.Tipotransacion_id) {
                    case 1:
                        this.fetchProveedores();
                        this.fetchRequest();
                        break;
                    case 2:
                        this.fetchSolicitudTraslado();
                        break;
                    default:
                        break;
                }
            },
            clearForm() {
                this.data = {
                    id: '',
                    Tipotransacion_id: '',
                    prestador_id: '',
                    BodegaOrigen_id: '',
                    BodegaDestino_id: '',
                    Orden_id: '',
                    Reps_id: '',
                    bodegarticulos: [],
                    listArticulos: []
                }
            },
            clearPresBodRep() {
                this.data.prestador_id = ''
                this.data.BodegaOrigen_id = ''
                this.data.BodegaDestino_id = ''
                this.data.Reps_id = ''
                this.data.Motivo = ''
                this.data.bodegarticulos = []
                this.data.articuloSelected = []
                this.solicitudes = []
                this.articulo = {
                    bodegaArticulo: {},
                    Cantidadtotal: 0,
                    Titular: {},
                    lote: '',
                    // Cum: ''
                }
            },
            addBodegaTransaccion(){
                this.data.bodegarticulos.push(Object.assign({}, this.dataProducto))
            },
            deleteTransaccion(index){
                this.data.bodegarticulos.splice(index, 1)
            },
            fetchTitulares(){
                if (this.articulo.bodegaArticulo) {
                    this.listaTitular = this.articulo.bodegaArticulo.detallearticulos;
                } else {
                    this.listaTitular = [];
                }
            },
            clearFieldsArticulo(){
                this.articulo = {
                    bodegaArticulo: {},
                    Cantidadtotal: 0,
                    Titular: {},
                    // Cum: ''
                }
            },
            addArticulo(){
                if (this.articulo.bodegaArticulo.hasOwnProperty('id') && this.articulo.Titular.hasOwnProperty('id') &&
                    this.articulo.Cantidadtotal > 0) {
                    this.data.bodegarticulos.push(this.articulo);
                    this.clearFieldsArticulo();
                }
            },
            removeArticulo(index){
                this.data.bodegarticulos.splice(index, 1);
            },
            saveArticuloRequest(){
                const msg = 'Recuerde que si el movimiento tiene un error debe eliminarlo y volver a crearlo';
                swal({
                    title: msg,
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((confirm) => {
                    if (confirm) {
                        if(this.data.Tipotransacion_id == 1) return this.sendOrdenCompra();
                        if(this.data.Tipotransacion_id == 6 || this.data.Tipotransacion_id == 7) return this.sendSolicitudAjuste();

                    }

                });
            },
            getSolicitudesAprovadas(){
                axios.post('/api/bodega/' + this.data.BodegaOrigen_id + '/ordencompra/allacepted')
                    .then(res => this.orden.bodegarticulos = res.data.map(articulo => {
                        return {
                            id: articulo.id,
                            Nombre: articulo.Nombre,
                            Cantidad: articulo.Cantidad,
                            CUM_completo: articulo.CUM_completo,
                            Titular: articulo.Titular,
                            bodegaarticulo_id: articulo.bodegaarticulo_id,
                            Lote: '',
                            CantidadtotalFactura: articulo.Cantidad,
                            Precio: '',
                            Fvence: ''
                        }
                    }))
            },
            validateMovimiento(){
                let success = true;
                this.orden.selected.forEach(sel => {
                    if(!sel.CantidadtotalFactura || !sel.Precio || !sel.Lote || !sel.Fvence) success = false;
                })
                return success
            },
            async saveArticuloMovimiento(){
                if(!this.data.BodegaOrigen_id) return

                if(await !this.validateMovimiento()){
                    return swal({
                            title: 'La información no está completa',
                            text: " ",
                            timer: 2000,
                            icon: "error",
                            buttons: false
                        })
                }
                this.entrada = false;
                axios.post('/api/movimiento/' + this.data.BodegaOrigen_id + '/entradafactura', this.orden)
                    .then(res => {
                        swal({
                            title: res.data.message,
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        })
                        this.orden.selected = [];
                        this.entrada = false;
                        this.getSolicitudesAprovadas()
                    })
            },
            toggleAll () {
                if (this.data.articuloSelected.length) this.data.articuloSelected = []
                else this.data.articuloSelected = this.solicitudes.slice()
            },
            addArticuloToTranslate() {
                if (
                    this.articulo.bodegaArticulo.hasOwnProperty('Codigo') &&
                    // this.articulo.Cum.hasOwnProperty('CUM_completo') &&
                    this.articulo.Cantidadtotal > 0
                ) {
                    this.listArticulos.push({
                        Bodegaorigen_id: this.data.BodegaOrigen_id,
                        Bodegadestino_id: this.data.BodegaDestino_id,
                        Codesumi_id: this.articulo.bodegaArticulo.id,
                        Nombre: this.articulo.bodegaArticulo.Nombre,
                        Codigo: this.articulo.bodegaArticulo.Codigo,
                        Cantidad: this.articulo.Cantidadtotal,
                    });
                    this.clearFieldsArticulo();
                } else {
                    swal({
                        title: "Traslado",
                        text: "Es necesario elegir un medicamento y cantidad mayor a 0 para agregar al traslado.",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                }
            },
            removeArticuloToTranslate(index){
                this.listArticulos.splice(index, 1);
            },
            save(index,item){
                this.listArticulos.splice(index, 1, item);
            },
            saveTranslate(){
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Se realizará la generación de la solicitud de traslado!",
                    type: "success",
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.post(`/api/bodega/solicitud/create`,{
                            solicitudes: this.listArticulos
                        }).then(async res => {
                            await swal("¡Solicitud creada de manera exitosa!", {
                                timer: 2000,
                                icon: "success",
                                buttons: false
                            });

                            this.clearForm();
                        });
                    }
                });
            },
            fetchProveedores(){
                axios.get('/api/sedeproveedore/allproveedores')
                .then(res => this.sedesproveedores = res.data)
            },
            checkHeaderTable(text){
                if(text === 'Lote'){
                    return (this.data.Tipotransacion_id === 6 || this.data.Tipotransacion_id === 7)? true : false;
                }
                return true;
            },
            checkTipoSolicitud(){
                return (this.data.Tipotransacion_id == 1 || this.data.Tipotransacion_id == 6 || this.data.Tipotransacion_id == 7)? true : false;

            },
            sendOrdenCompra(){
                axios.post('/api/bodega/' + this.data.BodegaOrigen_id + '/ordencompra/create', this.data)
                    .then(res => {
                        swal({
                            title: `¡${res.data.message}!`,
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                        this.data.bodegarticulos = [];
                        // this.fetchMovimientos()
                        // this.clearForm()
                    })
            },
            sendSolicitudAjuste(){
                axios.post('/api/bodega/' + this.data.BodegaOrigen_id + '/solicitud/ajuste/create', this.data)
                    .then(res => {
                        swal({
                            title: `¡${res.data.message}!`,
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                        this.data.bodegarticulos = [];
                        // this.fetchMovimientos()
                        // this.clearForm()
                    })
            },
            closeDialogTraslado(){
                this.listCum = [];
                this.dialog = false;
            },
            saveTraslado() {
                if(!this.cantidadtraslado){
                    swal({
                		title: "Traslado",
                		text: "Ingrese una cantidad!",
                		timer: 2000,
                		icon: "error",
                		buttons: false
                    });
                    return;
                }

                if(this.cantidadtraslado > this.cantidadSolicitada){
                    swal({
                        title: "Traslado",
                        text: "La cantidad del traslado no puede ser superior a la solicitada",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                    return;
                }

                this.dataTraslado.Cantidad = this.cantidadtraslado;
                    this.traslados.push({
                        ...this.dataTraslado
                    });
                    this.clearPush();
            },
            validateLote() {
                var Lote = this.listLote.find(lote => lote.id == this.dataTraslado.Lote);
                if (parseInt(Lote.Cantidadisponible) < parseInt(this.dataTraslado.Cantidad)) {
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
                if (parseInt(this.dataTraslado.CantidadTotal) >=
                    (parseInt(this.dataTraslado.Cantidad) + parseInt(cantidad))
                ) {
                    let objIndex = this.traslados.findIndex((obj => obj.detalle === this.dataTraslado.detalle));
                    if(objIndex < 0){
                        this.traslados.push({
                            ...this.dataTraslado
                        });
                        this.clearPush();
                    }else{
                        swal({
                            title: "Solicitud en tramite",
                            text: "La Solicitud ya se encuentra en tramite",
                            icon: "error"
                        });
                        this.clearPush();
                    }
                } else {
                    swal({
                        title: "Cantidad seleccionada no permitida",
                        text: "La cantidad supera la cantidad solicitada",
                        icon: "error"
                    });
                    return;
                }
            },
            clearPush() {
                this.dataTraslado = {};
                this.listCum = [];
                this.listLote = [];
                this.BodegaArticulo_id = '';
                this.dialog = false;
            },
            generateTraslate(){
                var BodegaDestino_idAux = this.traslados[0].BodegaDestino_id;
                if(!BodegaDestino_idAux) return console.log('this.traslados',this.traslados);
                const val = ({ BodegaDestino_id }) => BodegaDestino_id != BodegaDestino_idAux;
                let validate = this.traslados.filter(val);
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
                            this.preload = true;
                            axios.post(`/api/movimiento/generateTrasladoSalida`, {
                                BodegaDestino_id: BodegaDestino_idAux,
                                BodegaOrigen_id: this.data.BodegaOrigen_id,
                                Traslados: this.traslados
                            }).then(res => {
                                swal("Traslado creado de manera exitosa!", {
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                                this.preload = false;
                                this.clearTraslado();
                            }).catch(err => {
                                this.preload = false;
                                this.showError(err.response.data.message)
                            });
                        }
                    });
                }
            },
            clearTraslado() {
                this.traslados = [];
                this.clearPush();
                this.cancelDialog();
                this.fetchSolicitudTraslado();
            },
            cancelDialog() {
                this.cancel = false;
                this.observaciones = '';
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
                        axios.post(`/api/bodega/solicitud/cancelTransfer/${this.solicitudA.id}`, {
                            observaciones: this.observaciones,
                            detalle: this.solicitudA.detalle_id,
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
            requestAcepted(){
                axios.put(`/api/bodega/${this.data.BodegaOrigen_id}/ordencompra/acceptRequest`, this.data)
                    .then((res) => {
                        swal({
                            title: res.data.message,
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                        this.fetchRequest();
                    })
                    .catch((err) => console.log(err));
      },
            validarMedicamentos(proveedor){
                let show = false;
                if(proveedor){
                    let pendientes = this.solicitudDetalles.filter(s => ((!s.precio_unidad ||  parseFloat(s.precio_unidad) <= 0 || !s.Cantidad ||  parseInt(s.Cantidad) <= 0)) && parseInt(s.state) === 1)
                    if(pendientes.length === 0){
                         show = true;
                    }
                    let anulados = this.solicitudDetalles.filter(s => (parseInt(s.state) === 0));
                    if(anulados.length === this.solicitudDetalles.length){
                        show = false;
                    }
                }
                return show;
            },
            generarAutorizacion(solicitud,estado,proveedor,item){
                axios.post('/api/bodega/autorizacion/'+estado+'/'+proveedor,this.solicitudDetalles).then(res => {
                    swal({
                        title: res.data.message,
                        text: " ",
                        timer: 2000,
                        icon: res.data.type,
                        buttons: false
                    });
                    if(estado === 7 && res.data.type === 'success'){
                        this.imprimir({type:'ordenCompra',id:solicitud.id})
                    }
                    this.getBodegaArticulo();
                    item.expanded = false;
                })
            },
            imprimir(pdf) {
                axios
                    .post("/api/pdf/print-pdf", pdf, {
                        responseType: "arraybuffer"
                    })
                    .then(res => {
                        let blob = new Blob([res.data], {
                            type: "application/pdf"
                        });
                        let link = document.createElement("a");
                        link.href = window.URL.createObjectURL(blob);
                        window.open(link.href, "_blank");
                    });
            },
            clearProveedor(){
                this.data.sedeselected = 0;
                this.getBodegaArticulo();
            },
            fetchSolicitudDetalles(solicitud,proveedor,item) {
                item.expanded = false
                if(!proveedor){
                    this.$alerWarning("El proveedor es requerido");
                    return;
                }
                axios.get(`/api/bodega/${solicitud}/${proveedor}/ordencompra/getSolicitudesPendientesDetalles`)
                    .then(res =>  {
                        this.solicitudDetalles = res.data;
                        item.expanded = true
                        });
            },
            precioTotalFactura(){
                let total = 0;
                this.solicitudDetalles.forEach(s => {
                    if(parseInt(s.state) === 1){
                        total += (!s.precio_unidad?0:parseFloat(s.precio_unidad))   *   (!s.Cantidad?0:parseInt(s.Cantidad) );
                    }
                })

                return total.toFixed();
            },
            imprimirExcel(id){
                axios({
                    method: 'post',
                    url: '/api/movimiento/detallesSolicitudCompra/'+id,
                    responseType: 'blob',
                })
                    .then(res => {
                        let blob = new Blob([res.data], {
                            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                        });
                        let url = window.URL.createObjectURL(blob);
                        let a = document.createElement('a');
                        a.download = "OrdenCompra#"+id;
                        a.href = url;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                    }).catch(err => {
                    // this.preload = false;
                })
            },
            findLote: function () {
                    axios.post("/api/orden/getLote", {
                            Codesumi_id: this.dataTraslado.Codesumi_id,
                            CUM_completo: this.dataTraslado.Cum,
                            Bodega_id: this.data.BodegaOrigen_id
                        })
                        .then(res => {
                            this.listLote = res.data;
                        })
                        .catch(err => console.log(err.response.data));
            },
            getLotesDetalleArticulo(id){
                axios.get('/api/bodega/'+this.data.BodegaOrigen_id+'/'+id+'/getLotesDetalleArticulo').then(res => {
                    this.listLote = res.data.map(s => {
                        return {
                            id:s.id,
                            Numlote:s.Numlote,
                            Cantidadisponible:s.Cantidadisponible,
                            cantidadTraslado:null,
                            bodegaArticulo:s.Bodegarticulo_id
                        }
                    });
                })
            },
            validarCantidadLote(){
                let total = 0;
                this.dataTraslado.Lote = [];
                this.listLote.forEach(s => {
                    const cantidad = parseInt(s.cantidadTraslado)?parseInt(s.cantidadTraslado):0;
                    total += parseInt(s.cantidadTraslado)?parseInt(s.cantidadTraslado):0;
                    if(cantidad > 0){
                        this.dataTraslado.Lote.push(s)
                    }
                })
                this.cantidadtraslado = total
            }
        }
    }

</script>

<style lang="scss" scoped>

</style>
