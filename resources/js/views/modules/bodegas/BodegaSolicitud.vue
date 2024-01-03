<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-dialog v-model="preload" persistent width="300">
                <v-card color="primary" dark>
                    <v-card-text>
                        Tranquilo procesamos tu solicitud !
                        <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                    </v-card-text>
                </v-card>
            </v-dialog>

            <v-dialog max-width="800px" persistent v-model="dialog">
                <v-card>
                    <v-form ref="form">
                        <v-card-title>Cargar solicitud</v-card-title>
                        <v-card-text>
                            <v-layout row wrap>
                                <v-flex xs3>
                                    <v-btn color="primary" dark outline round @click="uploadFiles">
                                        <v-icon left>backup</v-icon>
                                        Subir archivos
                                    </v-btn>
                                </v-flex>
                                <v-flex xs9 px-2>
                                    <input hidden multiple name="file" ref="files" type="file" @change="setName" />
                                    <VTextField disabled name="name"
                                                :rules="[v => !!v || 'Se necesitan cargar archivos para validar']" single-line
                                                v-model="namefile" @click="uploadFiles" />
                                </v-flex>
                            </v-layout>
                        </v-card-text>
                        <v-card-actions>
                            <VSpacer />
                            <v-btn color="primary" flat @click="dialog = !dialog">
                                Cerrar
                            </v-btn>
                            <v-btn color="primary" flat @click="validarArticulos">
                                Validar
                            </v-btn>
                        </v-card-actions>
                    </v-form>
                </v-card>
            </v-dialog>



            <v-card>
                <v-container>
                    <DialogDetalleMovimiento :dialogOpen="dialogDetalleOpen" :idMovimientoDetalle="idMovimientoDetalle"
                        :status_movimiento="statusMovimiento" />
                    <v-layout row wrap>
                        <v-flex xs4>
                            <VAutocomplete :items="listaTiposTransaccion" item-text="TransacionNombre" item-value="id"
                                label="Tipo transación*" required v-model="data.Tipotransacion_id"
                                @change="clearPresBodRep()" />
                        </v-flex>
                        <v-flex xs4 px-2>
                            <VAutocomplete :items="listBodegasByRole" item-text="Nombre" item-value="id"
                                label="Bodega Solicitante*" required v-model="data.BodegaOrigen_id"
                                @change="getBodegaArticulo()" />
                        </v-flex>
                        <!-- <v-flex xs4 v-show="data.Tipotransacion_id == 1">
                            <VAutocomplete :items="sedesproveedores" item-text="Nombre" item-value="id"
                                v-model="data.sedeselected" label="Proveedor*" />
                        </v-flex> -->
                        <v-flex xs4 pl-2 v-if="data.Tipotransacion_id == 2">
                            <VAutocomplete :items="filteredBodega" item-text="Nombre" item-value="id"
                                label="Bodega Destinatario*" required v-model="data.BodegaDestino_id"
                                @change="getBodegaArticulo()" />
                        </v-flex>
                        <v-flex xs4 px-2
                            v-show="data.Tipotransacion_id == 6 || data.Tipotransacion_id == 7 || data.Tipotransacion_id == 9">
                            <VAutocomplete :items="listMotivoAjuste" label="Motivo Ajuste*" required
                                v-model="data.Motivo" />
                        </v-flex>


                        <v-container class="px-0" v-show="data.Tipotransacion_id == 6 || data.Tipotransacion_id == 7 || data.Tipotransacion_id == 9">
                            <v-layout row wrap>
                                <v-flex xs6>
                                    <VAutocomplete :items="listArticuloWithout" item-text="Nombre"
                                                   :label="'Articulo'" :loading="loading" return-object required
                                                   :search-input.sync="ajustes" v-if="data.Tipotransacion_id"
                                                   v-model="articulo.bodegaArticulo" />
                                </v-flex>

                                <v-flex xs2 v-show="data.Tipotransacion_id == 6">
                                    <v-text-field label="Vencimiento*" type="date" required v-model="articulo.fVence" />
                                </v-flex>
                                <v-flex xs3 px-1>
                                    <VTextField label="Lote*" required v-model="articulo.lote" />
                                </v-flex>
                                <v-flex xs2 px-1>
                                    <VTextField label="Cantidad*" required type="number" min="1"
                                                onkeypress="return event.charCode >= 48" oncopy="return false"
                                                onpaste="return false" v-model="articulo.Cantidadtotal" />
                                </v-flex>
                                <v-flex xs1>
                                    <v-btn color="primary" outline round type="button" @click="findloteAjuste()">
                                        agregar
                                    </v-btn>
                                </v-flex>

                            </v-layout>
                        </v-container>

                        <v-container class="px-0" v-show="data.Tipotransacion_id == 1">
                            <v-layout row wrap>
                                <v-flex xs5>
                                    <VAutocomplete :items="listaBodegaArticulos" :item-text="data.Tipotransacion_id == 1?'lista':'fullname'"
                                        :label="data.Tipotransacion_id == 1?'Articulo':'Principio activo*'" :loading="loading" return-object required
                                        :search-input.sync="nombre" v-if="data.Tipotransacion_id"
                                        v-model="articulo.bodegaArticulo" @change="fetchTitulares()" />
                                </v-flex>
<!--                                <v-flex xs3 px-1 v-show="data.Tipotransacion_id != 1">-->
<!--                                    <VAutocomplete :items="listaTitular" item-text="Titular" label="Titular*" required-->
<!--                                        return-object v-model="articulo.Titular" />-->
<!--                                </v-flex>-->

                                <v-flex xs2 px-1>
                                    <VTextField label="Cantidad*" required type="number" min="1"
                                        onkeypress="return event.charCode >= 48" oncopy="return false"
                                        onpaste="return false" v-model="articulo.Cantidadtotal" />
                                </v-flex>
                                <v-flex xs1>
                                    <v-btn color="primary" outline round type="button" @click="addArticulo()">
                                        agregar
                                    </v-btn>
                                </v-flex>
                                <v-flex xs1 px-2 v-show="data.Tipotransacion_id == 1">
                                    <v-btn color="success" outline round type="button" @click="dialog = true">Cargar Solicitud</v-btn>
                                </v-flex>
                                <v-flex v-for="(error,index) in errorCarga" :key="index" xs12 v-show="data.Tipotransacion_id == 1 && errorCarga.length > 0">
                                    <v-alert :value="true" dismissible type="error">El codigo "{{error}}" no se encuentra registrado en los articulos</v-alert>
                                </v-flex>
                            </v-layout>
                        </v-container>
                        <v-container v-if="data.Tipotransacion_id == 2">
                            <v-layout row wrap>
                                <v-flex xs6>
                                    <VAutocomplete :items="listArticuloWithout" item-text="Nombre"
                                        label="Principio activo*" :loading="loading" required return-object
                                        :search-input.sync="article" v-if="data.Tipotransacion_id"
                                        v-model="articulo.bodegaArticulo" @input="getStock($event)" outline />
                                </v-flex>
                                <v-flex xs2 px-2>
                                    <v-text-field label="Cantidad Disponible"  placeholder="*" outline required type="number" min="1" v-model="totalDisponible" readonly/>
                                </v-flex>
                                <v-flex xs2 px-2>
                                    <VTextField label="Cantidad*" required type="number" min="1"
                                        onkeypress="return event.charCode >= 48" oncopy="return false"
                                        onpaste="return false" v-model="articulo.Cantidadtotal" />
                                </v-flex>
                                <v-flex xs1 px-1>
                                    <v-btn color="primary" outline round type="button"
                                        @click="addArticuloToTranslate()">agregar
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                        <v-container v-if="data.Tipotransacion_id == 4 && orden.bodegarticulos.length > 0">
                            <v-layout row wrap>
                                <v-flex xs2 px-1>
                                    <VTextField label="Número factura*" required v-model="orden.Numfactura" />
                                </v-flex>
                                <v-flex xs2 px-1>
                                    <VTextField label="Número factura zeus*" required v-model="orden.Numfacturazeus" />
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-layout>
                </v-container>
                <v-data-table :headers="headerArticulos" :items="arrAjustes" v-if="data.Tipotransacion_id == 6 || data.Tipotransacion_id == 7 || data.Tipotransacion_id == 9">
                    <template v-slot:headers="props">
                        <tr>
                            <template v-for="(header,index) in props.headers">
                                <th :key="index" v-if="checkHeaderTable(header.text)">{{ header.text }}</th>
                            </template>
                        </tr>
                    </template>
                    <template v-slot:items="props">
                        <td class="text-xs-center">{{ props.item.id}}</td>
                        <td class="text-xs-center">{{ props.item.Codigo }}</td>
                        <td class="text-xs-center">{{ props.item.Nombre }}</td>
                        <td class="text-xs-center">{{ props.item.presentacion }}</td>

                        <!--                        <td class="text-xs-center">{{ props.item.bodegaArticulo.unidad_compra }}</td>-->
                        <!-- <td class="text-xs-center">{{ parseFloat(props.item.Titular.precio_unidad).toFixed(2) }}</td> -->
                        <td class="text-xs-right">{{ props.item.lote }}</td>
                        <td class="text-xs-center">
                            <v-edit-dialog large lazy cancel-text="Cancelar" save-text="Guardar"
                                @save="updateCantidad(props.item)">
                                <div>{{ props.item.Cantidadtotal }} <v-icon color="warning" small>edit</v-icon>
                                </div>
                                <template v-slot:input>
                                    <v-text-field v-model="props.item.Cantidadtotal" label="Editar" min="1"
                                        type="number" single-line>
                                    </v-text-field>
                                </template>
                            </v-edit-dialog>
                        </td>
                        <!-- <td class="text-xs-center">
                            {{ parseFloat(props.item.Titular.precio_unidad * props.item.Cantidadtotal).toFixed(2) }}
                        </td> -->
                        <td class="text-xs-center">
                            <v-btn icon small @click="removeArticulo(props.index, props.item.Titular.precio_total)">
                                <v-icon color="error">clear</v-icon>
                            </v-btn>
                        </td>
                    </template>
                </v-data-table>

                <v-data-table :headers="headerArticulos" :items="data.bodegarticulos" v-if="data.Tipotransacion_id == 1">
                    <template v-slot:headers="props">
                        <tr>
                            <template v-for="(header,index) in props.headers">
                                <th :key="index" v-if="checkHeaderTable(header.text)">{{ header.text }}</th>
                            </template>
                        </tr>
                    </template>
                    <template v-slot:items="props">
                        <td class="text-xs-center">{{ props.item.bodegaArticulo.id}}</td>
                        <td class="text-xs-center">{{ props.item.bodegaArticulo.Codigo }}</td>
                        <td class="text-xs-center">{{ props.item.bodegaArticulo.Nombre }}</td>
                        <td class="text-xs-center">{{ props.item.bodegaArticulo.Titular }}</td>
                        <td class="text-xs-center">{{ props.item.bodegaArticulo.unidad_compra }}</td>
                        <td class="text-xs-center">
                            <v-edit-dialog large lazy cancel-text="Cancelar" save-text="Guardar"
                                           @save="updateCantidad(props.item)">
                                <div>{{ props.item.Cantidadtotal }} <v-icon color="warning" small>edit</v-icon>
                                </div>
                                <template v-slot:input>
                                    <v-text-field v-model="props.item.Cantidadtotal" label="Editar" min="1"
                                                  type="number" single-line>
                                    </v-text-field>
                                </template>
                            </v-edit-dialog>
                        </td>
                        <!-- <td class="text-xs-center">
                            {{ parseFloat(props.item.Titular.precio_unidad * props.item.Cantidadtotal).toFixed(2) }}
                        </td> -->
                        <td class="text-xs-center">
                            <v-btn icon small @click="removeArticulo(props.index, props.item.Titular.precio_total)">
                                <v-icon color="error">clear</v-icon>
                            </v-btn>
                        </td>
                    </template>
                </v-data-table>



                <!-- <v-alert :value="true" v-if="data.Tipotransacion_id == 1" type="info">
                    Esta a punto de hacer una solicitud de compra por un valor total de <strong>
                        {{ parseFloat(precioTotalFactura).toFixed(2) }} </strong>
                </v-alert> -->
                <v-data-table :headers="headerArticulostraslado" :items="listArticulos" v-if="data.Tipotransacion_id == 2">
                    <template v-slot:items="props">
                        <td>{{ props.index + 1}}</td>
                        <td class="text-xs-center">{{ props.item.Nombre}}</td>
                        <td class="text-xs-center">{{ props.item.Codigo}}</td>
                        <td class="text-xs-center">
                            <v-edit-dialog :return-value.sync="props.item.Cantidad" large lazy persistent
                                cancel-text="Cancelar" save-text="Cambiar" @save="save(props.index, props.item)">
                                <div>{{ props.item.Cantidad }}</div>
                                <template v-slot:input>
                                    <div class="mt-3 title">Update Cantidad</div>
                                </template>
                                <template v-slot:input>
                                    <v-text-field type="number" min="1" onkeypress="return event.charCode >= 48"
                                        oncopy="return false" onpaste="return false" v-model="props.item.Cantidad"
                                        label="Edit" single-line counter autofocus></v-text-field>
                                </template>
                            </v-edit-dialog>
                        </td>
                        <td class="text-xs-center">
                            <v-btn icon small @click="removeArticuloToTranslate(props.index)">
                                <v-icon color="error">clear</v-icon>
                            </v-btn>
                        </td>
                    </template>
                </v-data-table>
                <v-data-table :headers="headerArticulosCompra" :items="orden.bodegarticulos" select-all v-if="data.Tipotransacion_id == 4" v-model="orden.selected">
                    <template v-slot:headers="props">
                        <tr>
                            <th>
                                <VCheckbox color="primary" hide-details :indeterminate="props.indeterminate"
                                    :input-value="props.all" @click.stop="toggleAll" />
                            </th>
                            <th v-for="header in props.headers" :key="header.text">
                                {{ header.text }}
                            </th>
                        </tr>
                    </template>
                    <template v-slot:items="props">
                        <td>
                            <VCheckbox color="primary" hide-details primary v-model="props.selected" />
                        </td>
                        <td class="text-xs-center">{{ props.item.CUM_completo}} {{ props.item.Nombre}}</td>
                        <td class="text-xs-center">{{ props.item.Titular}}</td>
                        <td class="text-xs-center">{{ props.item.Cantidad}}</td>
                        <td class="text-xs-center">
                            <v-edit-dialog cancel-text="Cancelar" large lazy persistent save-text="Cambiar"
                                :return-value.sync="props.item.CantidadtotalFactura">
                                <div>{{ props.item.CantidadtotalFactura }}</div>
                                <template v-slot:input>
                                    <div class="mt-3 title">Update Valor</div>
                                </template>
                                <template v-slot:input>
                                    <VTextField autofocus counter v-model="props.item.CantidadtotalFactura"
                                        label="Editar" single-line />
                                </template>
                            </v-edit-dialog>
                        </td>
                        <td class="text-xs-center">
                            <v-edit-dialog cancel-text="Cancelar" large lazy persistent
                                :return-value.sync="props.item.Precio" save-text="Cambiar">
                                <div>{{ props.item.Precio }}</div>
                                <template v-slot:input>
                                    <div class="mt-3 title">Update Valor</div>
                                </template>
                                <template v-slot:input>
                                    <v-text-field autofocus counter label="Precio" v-model="props.item.Precio" />
                                </template>
                            </v-edit-dialog>
                        </td>
                        <td class="text-xs-center">
                            <v-edit-dialog cancel-text="Cancelar" large lazy persistent
                                :return-value.sync="props.item.Lote" save-text="Cambiar">
                                <div>{{ props.item.Lote }}</div>
                                <template v-slot:input>
                                    <div class="mt-3 title">Update Valor</div>
                                </template>
                                <template v-slot:input>
                                    <VTextField autofocus counter label="Lote" v-model="props.item.Lote" />
                                </template>
                            </v-edit-dialog>
                        </td>
                        <td class="text-xs-center">
                            <v-edit-dialog cancel-text="Cancelar" large lazy persistent
                                :return-value.sync="props.item.Fvence" save-text="Cambiar">
                                <div>{{ props.item.Fvence }}</div>
                                <template v-slot:input>
                                    <div class="mt-3 title">Update Valor</div>
                                </template>
                                <template v-slot:input>
                                    <v-menu :close-on-content-click="false" full-width :key="props.item.id" lazy
                                        min-width="290px" :nudge-right="40" offset-y ref="menuDate"
                                        :return-value.sync="props.item.Fvence" transition="scale-transition"
                                        v-model="props.item.menuDate">
                                        <template v-slot:activator="{ on }">
                                            <VTextField label="Fecha vencimiento" readonly v-model="props.item.Fvence"
                                                v-on="on" />
                                        </template>
                                        <v-date-picker color="primary" locale="es" :min="today" no-title scrollable
                                            v-model="props.item.Fvence">
                                            <VSpacer />
                                            <v-btn color="primary" flat @click="props.item.menuDate = false">
                                                Cancelar
                                            </v-btn>
                                            <v-btn color="primary" flat @click="$refs.menuDate.save(props.item.Fvence)">
                                                OK
                                            </v-btn>
                                        </v-date-picker>
                                    </v-menu>
                                </template>
                            </v-edit-dialog>
                        </td>
                    </template>
                </v-data-table>
                <v-flex xs12 pb-3>
                    <v-layout row wrap>
                        <VSpacer />
                        <v-btn color="success" @click="saveArticuloRequest()"
                            v-show="checkTipoSolicitud() && (data.bodegarticulos.length > 0 || arrAjustes.length > 0)" outline round>
                            Guardar Solicitud</v-btn>
                        <v-btn color="dark" round @click="saveTranslate()"
                            v-if="data.Tipotransacion_id == 2 && listArticulos.length > 0">
                            Generar</v-btn>
                        <v-btn color="success" @click="saveArticuloMovimiento()"
                            v-if="data.Tipotransacion_id == 4 && orden.selected.length > 0" :disabled="entrada" outline
                            round>Guardar
                            entrada</v-btn>
                        <VSpacer />
                    </v-layout>
                </v-flex>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    import {
        Transaction
    } from '../../../models/Transaction'
    import {
        Movimiento
    } from '../../../models/Movimiento'

    import DialogDetalleMovimiento from './DialogDetalleMovimiento';
    import {
        EventBus
    } from '../../../event-bus.js';
    import moment from 'moment';

    export default {
        name: 'BodegaSolicitud',
        components: {
            DialogDetalleMovimiento,
        },
        data: (vm) => ({
            preload:false,
            arrAjustes:[],
            errorCarga: [],
            namefile: 'Seleccionar archivos',
            dialog:false,
            totalDisponible:null,
            entrada: false,
            listaTiposTransaccion: [],
            listaPrestadores: [],
            listaBodegas: [],
            listBodegasByRole: [],
            listaReps: [],
            listaBodegaArticulos: [],
            listArticuloWithout: [],
            listArticulos: [],
            listaTitular: [],
            listMotivoAjuste: [
                'Aprovechamiento',
                'Avería',
                'Cambio de Código',
                'Conteo',
                'ConsumoQuimioterapia',
                'Devolución Paciente',
                'Devolución Servicio',
                'Error de Dispensación',
                'Error ingreso en Factura',
                'Promociones y muestras',
                'Traslado Sumimedica',
                'Traslado Victoriana',
                'Vencimiento',
                'Salida Colaborador'
            ],
            // listaCum: [],
            estadosMovimientoFormat: [],
            dialogDetalleOpen: false,
            idMovimientoDetalle: 0,
            statusMovimiento: 15,
            nombre: '',
            article: '',
            ajustes: '',
            loading: false,
            menuDate: false,
            menu1: false,
            solicitudBodega: false,
            today: moment().format('YYYY-MM-DD'),
            articulo: {
                bodegaArticulo: {},
                Cantidadtotal: '',
                Titular: {},
                lote: '',
                fVence: null
                // Cum: ''
            },
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
                    sortable: false,
                    align: 'right'
                },
                {
                    text: 'Código',
                    sortable: false,
                    align: 'right'
                },
                {
                    text: 'Medicamento',
                    sortable: false,
                    align: 'right'
                },
                // {
                //     text: 'Titular',
                //     sortable: false,
                //     align: 'right'
                // },
                {
                    text: 'Presentación',
                    sortable: false,
                    align: 'center'
                },
                // {
                //     text: 'Precio unidad',
                //     sortable: false,
                //     align: 'left'
                // },
                {
                    text: 'Lote',
                    sortable: false,
                    align: 'right'
                },
                // {
                //     text: 'Fecha vencimiento',
                //     sortable: false,
                //     align: 'right'
                // },
                {
                    text: 'Cantidad',
                    sortable: false,
                    align: 'center'
                },
                // {
                //     text: 'Precio total',
                //     sortable: false,
                //     align: 'center'
                // },
                {
                    text: '',
                    sortable: false,
                    align: 'right'
                },
            ],
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
                Orden_id: '',
                Reps_id: '',
                bodegarticulos: [],
            },
            dataProducto: {
                id: '',
                Bodegarticulo_id: '',
                Fvence: '',
                Cantidadtotal: '',
                Precio: '',
                Lote: '',
            },
            typeTransactions: Transaction.typeTransaction,
            estadosMovimiento: Movimiento.estados,
            date: new Date().toISOString().substr(0, 10),
            dateFormatted: vm.formatDate(new Date().toISOString().substr(0, 10)),
            precioTotalFactura: 0,
            sedesproveedores: []

        }),
        created() {
            EventBus.$on('close-dialog-detalle-movimiento', () => {
                this.dialogDetalleOpen = false;
                this.idMovimientoDetalle = 0;
            })
        },
        mounted() {
            this.fetchTiposTransaccion()
            this.fetchBodegaByRole()
            this.fetchBodegas()
            this.fetchProveedores()
        },
        watch: {
            nombre: function () {
                if (this.nombre && this.nombre.length == 3) {
                    this.fetchBodegaArticulos();
                }
            },
            article: function () {
                if (this.article && this.article.length == 3) {
                    this.fetchArticulosWithout();
                }
            },
             ajustes: function (){
                if (this.ajustes && this.ajustes.length == 3) {
                    this.fetchArticulosWithoutAjustes();
                }
            },
            date(val) {
                this.dateFormatted = this.formatDate(this.date)
            }
        },
        computed: {
            filteredBodega() {
                return this.listaBodegas.filter(bodega => {
                    if (this.data.BodegaOrigen_id != bodega.id) return bodega;
                });
            },
            computedDateFormatted() {
                return this.formatDate(this.date)
            }
        },
        methods: {
            fetchProveedores() {
                axios.get('/api/sedeproveedore/allproveedores')
                    .then(res => this.sedesproveedores = res.data)
            },
            fetchBodegas() {
                axios.get('/api/bodega/all')
                    .then(res => this.listaBodegas = res.data)
            },
            fetchTiposTransaccion() {
                axios.get('/api/tipotransacion/solicitud')
                    .then(res => this.listaTiposTransaccion = res.data)
            },
            fetchBodegaByRole() {
                axios.get('/api/bodega/getBodegaByRole')
                    .then(res => {
                        if (res.data.length > 0) {
                            this.listBodegasByRole = res.data
                        }
                    })
            },
            fetchBodegaArticulos() {
                this.loading = true;
                let bodega = this.data.BodegaOrigen_id;
                if (this.data.Tipotransacion_id == 2) {
                    bodega = this.data.BodegaDestino_id;
                }
                if (this.data.Tipotransacion_id == 1 || this.data.Tipotransacion_id == 6) {
                    axios.post(`/api/bodega/articulo/getDetallesArticulos`, {
                            nombre: this.nombre
                        })
                        .then(res => {
                            this.listaBodegaArticulos = res.data.map(data => {
                                return {
                                    ...data,
                                    fullname: data.Codigo,
                                    Codigo:data.codigo,
                                    Nombre:data.Producto,
                                    Titular:data.Titular,
                                    unidad_compra:data.unidad_compra,
                                    lista: data.codigo+' ('+data.Producto+' - '+data.Titular+')'
                                }
                            });
                            this.loading = false;
                        })
                } else {
                    console.log(bodega);
                    axios.post(`/api/bodega/${bodega}/articulo/all`, {
                            nombre: this.nombre
                        })
                        .then(res => {
                            this.listaBodegaArticulos = res.data.map(data => {
                                return {
                                    ...data,
                                    fullname: `${data.Codigo} - ${data.Nombre}`
                                }
                            });
                            this.loading = false;
                        })
                }
            },
            fetchArticulosWithout() {
                this.loading = true;
                console.log(this.data.BodegaDestino_id)
                axios.post(`/api/bodega/articulo/allWithoutBodega`, {
                        article: this.article,
                        bodega: this.data.BodegaDestino_id
                    })
                    .then(res => {
                        this.listArticuloWithout = res.data;
                        this.loading = false;
                    });
            },

            fetchArticulosWithoutAjustes() {
                this.loading = true;
                console.log(this.data)
                axios.post(`/api/bodega/articulo/allWithoutBodega`, {
                    article: this.article,
                    bodega: this.data.BodegaOrigen_id
                })
                    .then(res => {
                        this.listArticuloWithout = res.data;
                        this.loading = false;
                    });
            },
            getBodegaArticulo() {
                if (this.data.Tipotransacion_id == 1) this.listaBodegaArticulos = [];
                else this.getSolicitudesAprovadas();
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
                this.listaBodegaArticulos = [];
                this.data.prestador_id = ''
                this.data.BodegaOrigen_id = ''
                this.data.BodegaDestino_id = ''
                this.data.Reps_id = ''
                this.data.Motivo = ''
                this.data.bodegarticulos = []
                this.articulo = {
                    bodegaArticulo: {},
                    Cantidadtotal: '',
                    Titular: {},
                    lote: ''
                };
                if (this.data.Tipotransacion_id === 9) {
                    this.listBodegasByRole.forEach(s => {
                        this.listMotivoAjuste.push(s.Nombre);
                    });
                }
            },
            addBodegaTransaccion() {
                this.data.bodegarticulos.push(Object.assign({}, this.dataProducto))
            },
            deleteTransaccion(index) {
                this.data.bodegarticulos.splice(index, 1)
            },
            fetchTitulares() {
                if (this.articulo.bodegaArticulo) {
                    this.listaTitular = this.articulo.bodegaArticulo.detallearticulos;
                } else {
                    this.listaTitular = [];
                }
            },
            clearFieldsArticulo() {
                this.articulo = {
                    bodegaArticulo: {},
                    Cantidadtotal: '',
                    Titular: {}
                }
                this.listaTitular = [];
                this.totalDisponible = null;
                this.date = new Date().toISOString().substr(0, 10)
            },
            addArticulo() {
                if (parseInt(this.articulo.Cantidadtotal) < 0 || parseInt(this.articulo.Cantidadtotal) === 0 ||
                    this.articulo.Cantidadtotal == '') {
                    this.$alerError("La cantidad no puede ser inferior o igual a 0");
                    this.articulo.Cantidadtotal = 1;
                    return;
                }
                this.articulo.Cantidadtotal = Math.abs(this.articulo.Cantidadtotal);

                if (this.data.Tipotransacion_id === 1){
                    let index = this.data.bodegarticulos.findIndex(i => i.bodegaArticulo.id === this.articulo.bodegaArticulo.id);
                    if (index >= 0) {
                        this.$alerError("El articulo ya se encuentra agregado");
                        return;
                    }
                    this.data.bodegarticulos.push({
                        ...this.articulo,
                        date: this.date,
                    });
                    this.clearFieldsArticulo();
                } else if(this.data.Tipotransacion_id === 6 || this.data.Tipotransacion_id === 7){
                    console.log(this.articulo.bodegaArticulo);

                }else{
                    let index = this.data.bodegarticulos.findIndex(i => i.Titular.id === this.articulo.Titular.id &&
                        i.Titular.Titular === this.articulo.Titular.Titular);

                    if (index >= 0) {
                        this.$alerError("El principio activo y el titular ya esta agregado");
                        return;
                    }
                    if (this.articulo.bodegaArticulo.hasOwnProperty('id') && this.articulo.Titular.hasOwnProperty('id') &&
                        this.articulo.Cantidadtotal > 0) {
                        if (this.articulo.Titular.precio_unidad) {
                            this.articulo.Titular.precio_total = parseFloat(this.articulo.Titular.precio_unidad) *
                                parseFloat(this.articulo.Cantidadtotal)
                        }
                        this.precioTotalFactura += this.articulo.Titular.precio_total
                        this.data.bodegarticulos.push({
                            ...this.articulo,
                            date: this.date,
                        });
                        this.clearFieldsArticulo();
                    }
                }



            },
            removeArticulo(index, precioTotal) {
                this.data.bodegarticulos.splice(index, 1);
                this.precioTotalFactura -= parseFloat(precioTotal)
            },
            saveArticuloRequest() {
                console.log(this.data.Tipotransacion_id);
                const msg = 'Recuerde que si el movimiento tiene un error debe eliminarlo y volver a crearlo';
                swal({
                    title: msg,
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((confirm) => {
                    if (confirm) {
                        if (this.data.Tipotransacion_id == 1) return this.sendOrdenCompra();
                        if (this.data.Tipotransacion_id == 6 || this.data.Tipotransacion_id == 7) return this.sendSolicitudAjuste();

                    }

                });
            },
            updateCantidad(item) {
                let index = this.data.bodegarticulos.findIndex(i => i.Titular.id === item.Titular.id);
                if (parseInt(item.Cantidadtotal) < 0 || parseInt(item.Cantidadtotal) === 0 ||
                    item.Cantidadtotal == '') {
                    this.$alerError("La cantidad no puede ser inferior o igual a 0, sera reemplazada por 1");
                    this.data.bodegarticulos[index].Cantidadtotal = 1;
                }
                this.data.bodegarticulos[index].Cantidadtotal = Math.abs(item.Cantidadtotal)
                this.data.bodegarticulos[index].Titular.precio_total =
                    parseFloat(this.data.bodegarticulos[index].Titular.precio_unidad) *
                    parseFloat(this.data.bodegarticulos[index].Cantidadtotal)

                this.precioTotalFactura = 0;
                this.data.bodegarticulos.forEach(data => {
                    this.precioTotalFactura += data.Titular.precio_total
                })
            },
            getSolicitudesAprovadas() {
                axios.post('/api/bodega/' + this.data.BodegaOrigen_id + '/ordencompra/allacepted')
                    .then(res => this.orden.bodegarticulos = res.data.map(articulo => {
                        return {
                            id: articulo.id,
                            Nombre: 'holaaa',
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
            validateMovimiento() {
                let success = true;
                this.orden.selected.forEach(sel => {
                    if (!sel.CantidadtotalFactura || !sel.Precio || !sel.Lote || !sel.Fvence) success = false;
                })

                return success

            },
            async saveArticuloMovimiento() {
                if (!this.data.BodegaOrigen_id) return

                if (await !this.validateMovimiento()) {
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
            toggleAll() {
                if (this.orden.selected.length) this.orden.selected = []
                else this.orden.selected = this.orden.bodegarticulos.slice()
            },
            addArticuloToTranslate() {
                console.log(this.articulo);
                if (
                    this.articulo.bodegaArticulo.hasOwnProperty('Codigo') &&
                    (this.articulo.Cantidadtotal > 0 || this.articulo.Cantidadtotal != '')
                ) {
                    if(parseInt(this.articulo.Cantidadtotal) <= parseInt(this.totalDisponible)){
                        this.listArticulos.push({
                            Bodegaorigen_id: this.data.BodegaOrigen_id,
                            Bodegadestino_id: this.data.BodegaDestino_id,
                            Codesumi_id: this.articulo.bodegaArticulo.Codesumi_id,
                            detalleArticulo: this.articulo.bodegaArticulo.id,
                            Nombre: this.articulo.bodegaArticulo.Nombre,
                            Codigo: this.articulo.bodegaArticulo.Codigo,
                            Cantidad: this.articulo.Cantidadtotal,
                        });
                        this.clearFieldsArticulo();
                    }else{
                        swal({
                            title: "Traslado",
                            text: "La cantidad ingresada es superior a la cantidad disponible",
                            timer: 2000,
                            icon: "error",
                            buttons: false
                        });
                        this.articulo.Cantidadtotal = 0;
                    }
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
            removeArticuloToTranslate(index) {
                this.listArticulos.splice(index, 1);
            },
            save(index, item) {
                this.listArticulos.splice(index, 1, item);
            },
            saveTranslate() {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Se realizará la generación de la solicitud de traslado!",
                    type: "success",
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        this.preload = true;
                        axios.post(`/api/bodega/solicitud/create`, {
                            solicitudes: this.listArticulos
                        }).then(async res => {
                            await swal({
                                title: "¡Solicitud creada de manera exitosa!",
                                text: `número de la solicitud es: ${res.data.solicitud}`,
                                icon: "info",
                                buttons: {
                                    text: "ok!",
                                },
                            });
                            this.preload = false;
                            this.clearForm();
                        }).catch(e => {this.preload = false})
                    }
                });
            },
            checkHeaderTable(text) {
                if (text === 'Lote') {
                    return (this.data.Tipotransacion_id === 6 || this.data.Tipotransacion_id === 7) ? true : false;
                } else if (text === 'Fecha vencimiento') {
                    return (this.data.Tipotransacion_id === 6) ? true : false;
                }
                return true;
            },
            checkTipoSolicitud() {
                return (this.data.Tipotransacion_id == 1 || this.data.Tipotransacion_id == 6 || this.data
                    .Tipotransacion_id == 7 || this.data.Tipotransacion_id == 9) ? true : false;

            },
            sendOrdenCompra() {
                if (!this.data.BodegaOrigen_id) {
                    this.$alerError("No ha seleccionado la bodega");
                    return;
                }
                this.preload = true
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
                        this.precioTotalFactura = 0
                        let pdf = {
                            type: "ordenCompra",
                            id: res.data.solicitud
                        };
                        this.imprimir(pdf);
                        this.preload = false
                    }).catch(e =>{
                        this.preload = false
                    })
            },
            sendSolicitudAjuste() {
                this.solicitudBodega = true;
                const data = {
                    motivo: this.data.Motivo,
                    ajustes: this.arrAjustes,
                    Tipotransacion_id: this.data.Tipotransacion_id
                }
                this.preload = true
                axios.post('/api/bodega/' + this.data.BodegaOrigen_id + '/solicitud/ajuste/create',data)
                    .then(async res => {

                        await swal({
                            title: "¡Solicitud creada de manera exitosa!",
                            text: `número de la solicitud es: ${res.data.solicitud}`,
                            icon: "info",
                            buttons: {
                                text: "ok!",
                            },
                            dangerMode: true,
                        }).then(async mess => {
                            if (mess && (this.data.Tipotransacion_id == 7 || this.data
                                    .Tipotransacion_id == 9) && res.data.noExist) {
                                await swal(`el lote ${res.data.noExist}`, {
                                    icon: "info",
                                    buttons: ["OK"]
                                });
                            }
                        })
                        this.preload = false;
                        this.data.bodegarticulos = [];
                        this.arrAjustes = [];
                        this.solicitudBodega = false;
                    }).catch(e => {this.preload = false})
            },
            parseDate(date) {
                if (!date) return null

                const [month, day, year] = date.split('/')
                return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
            },
            formatDate(date) {
                if (!date) return null

                const [year, month, day] = date.split('-')
                return `${month}/${day}/${year}`
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
            getStock(e){
                console.log(e);
                if(e){
                    this.totalDisponible = parseInt(e.Stock);
                }else{
                    this.totalDisponible = null;
                }
            },
            uploadFiles() {
                this.$refs.files.click()
            },
            setName() {
                if (this.$refs.files.files.length === 0) return this.namefile = 'Seleccionar archivos';
                return this.namefile = (this.$refs.files.files.length === 1) ? this.$refs.files.files[0].name :
                    `${this.$refs.files.files.length} archivos para cargar`
            },
            validarArticulos(){
                this.errorCarga = [];
                this.data.bodegarticulos = [];
                this.preload = true;
                let formData = new FormData();
                formData.append('data', this.$refs.files.files[0])
                axios.post('/api/bodega/cargaMasiva',formData).then(res => {
                    this.data.bodegarticulos = res.data.medicamentos;
                    this.errorCarga = res.data.errores;
                    this.dialog = false;
                    this.preload = false;
                }).catch(err => {
                    this.preload = false;
                });
            },
            findloteAjuste(){
                this.preload = true;
                const data = {
                    tipoSolicitud: this.data.Tipotransacion_id,
                    cantidad:this.articulo.Cantidadtotal,
                    bodega: this.data.BodegaOrigen_id,
                    lote: this.articulo.lote,
                    fVence: this.articulo.fVence,
                    ...this.articulo.bodegaArticulo
                }
                axios.post('/api/bodega/findloteAjuste',data).then(res => {
                    if(res.data.message === undefined){
                        console.log("hola",res.data);
                        this.arrAjustes.push({
                            id: res.data.id,
                            Codigo: res.data.Codigo,
                            Nombre: res.data.Nombre,
                            lote: res.data.Numlote,
                            Cantidadtotal: res.data.cantidad,
                            presentacion: res.data.presentacion_comercial,
                            bodegaArticulo:res.data.bodegaArticulo,
                            Fvence:res.data.Fvence
                        });
                        this.listArticuloWithout = []
                        this.articulo.bodegaArticulo = null;
                        this.articulo.lote = null;
                        this.articulo.Cantidadtotal = null;
                        this.articulo.fVence = null;
                    }else{
                        this.$alerError(res.data.message)
                    }
                    this.preload = false
                }).catch(e => {this.preload = false})
            }
        }
    }

</script>

<style lang="scss" scoped>

</style>
