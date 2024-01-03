<template>
    <v-layout row wrap>
        <template>
            <div class="text-center">
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
        <v-dialog v-model="dialogPendingTraslates" width="1200" persistent>
            <v-card>
                <v-card-title class="headline grey lighten-2" primary-title>Traslados Pendientes
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-autocomplete label="Bodegas" :items="listBodegasByRole" item-text="Nombre"
                                    item-value="id" v-model="formFiltro.bodega" required>
                                </v-autocomplete>
                            </v-flex>
                            <v-flex xs3>
                                <v-text-field type="date" v-model="formFiltro.fechaDesde" label="Desde"></v-text-field>
                            </v-flex>
                            <v-flex xs3>
                                <v-text-field type="date" v-model="formFiltro.fechaHasta" label="Hasta"></v-text-field>
                            </v-flex>
                            <v-flex xs6>
                                <v-autocomplete v-model="formFiltro.codesumi" label="Medicamento" item-value="id"
                                    item-text="Nombre" :items="codigoSumisPendientes"></v-autocomplete>
                            </v-flex>
                            <v-flex xs2 offset-xs10>
                                <v-btn @click="getAllpendingTraslates" color="success">Filtrar</v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>


                    <v-container grid-list-md>
                        <v-layout wrap>
                            <!--                            <v-flex xs6>-->
                            <!--                                <v-text-field v-model="buscarPendiente" label="Buscar..."></v-text-field>-->
                            <!--                            </v-flex>-->
                            <v-flex xs12>
                                <v-data-table :headers="headerTrasladoPendiente" :items="allPendingTraslates"
                                    :search="buscarPendiente" class="elevation-1">
                                    <template v-slot:items="props">
                                        <td class="text-xs-center">{{ props.item.id}}</td>
                                        <td class="text-xs-center">{{ props.item.created_at}}</td>
                                        <td class="text-xs-center">{{ props.item.Bodega_Nombre}}</td>
                                        <td class="text-xs-center">{{ props.item.Bodega_Nombre_destino}}</td>
                                        <td class="text-xs-center">{{ props.item.descripcion}}</td>
                                        <td class="text-xs-center">{{ props.item.Medicamento}}</td>
                                        <td class="text-xs-center">{{ props.item.CantidadtotalFactura }}</td>
                                        <td class="text-xs-center">{{ props.item.Numlote}}</td>
                                        <td class="text-xs-center">
                                            <v-tooltip top>
                                                <template v-slot:activator="{ on }">
                                                    <v-btn fab outline color="error" small v-on="on"
                                                        @click="cancelTransfer(props.item)">
                                                        <v-icon>close</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Cancelar</span>
                                            </v-tooltip>
                                        </td>
                                    </template>
                                </v-data-table>
                            </v-flex>
                        </v-layout>
                    </v-container>

                </v-card-text>
                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="dialogPendingTraslates = false">Cerrar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>



        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>

                <v-toolbar dark color="primary">
                    <v-toolbar-title>NOVEDAD A {{ordenNovedad.medicamento}}</v-toolbar-title>
                    <v-spacer></v-spacer>
                </v-toolbar>

                <v-card-text>
                    <v-form>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12>
                                    <v-select @change="validarNoSolicitado"
                                        :items="listNovedad"
                                        label="Tipo Novedad" v-model="formNovedad.tipoNovedad" required></v-select>
                                </v-flex>
                                <v-flex xs12 v-if="formNovedad.tipoNovedad === 'Producto No Solicitado'">
                                    <v-autocomplete :items="listMedicamentosNuevoNovedad" item-text="medicamento"
                                        item-value="id" v-model="formNovedad.nuevoMedicamento" label="Nuevo Producto">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs12
                                    v-if="formNovedad.tipoNovedad === 'Sobrante' || formNovedad.tipoNovedad === 'Faltante'|| formNovedad.tipoNovedad === 'Averias'">
                                    <v-text-field v-model="formNovedad.cantidadRecibida"
                                        :label="formNovedad.tipoNovedad === 'Averias'?'Cantidades Funcionales':'Cantidad Total Recibida'"
                                        type="number"></v-text-field>
                                </v-flex>
                                <v-flex xs12
                                    v-if="formNovedad.tipoNovedad === 'Nuevo Precio' || formNovedad.tipoNovedad === 'Producto No Solicitado'">
                                    <v-text-field v-model="formNovedad.nuevoPrecio" label="Precio Unidad" type="number">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 v-if="formNovedad.tipoNovedad">
                                    <v-textarea outline v-model="formNovedad.observacion" label="Observacion">
                                    </v-textarea>
                                </v-flex>
                                <v-flex xs12 v-if="formNovedad.tipoNovedad">
                                    <v-checkbox v-model="formNovedad.devolucion" color="primary" label="Devolver"
                                        :disabled="formNovedad.tipoNovedad === 'Producto No Conforme'"></v-checkbox>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="dialog = false">Cerrar</v-btn>
                    <v-btn color="blue darken-1" flat @click="guardarNovedad">Guardar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-flex xs12>
            <v-card>
                <v-container>
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
                    <DialogDetalleMovimiento :dialogOpen="dialogDetalleOpen" :idMovimientoDetalle="idMovimientoDetalle"
                        :status_movimiento="statusMovimiento">
                    </DialogDetalleMovimiento>
                    <v-layout row wrap>
                        <v-flex xs4>
                            <v-autocomplete label="Tipo transación*" :items="filterlistaTiposTransaccion"
                                item-text="TransacionNombre" @change="clearPresBodRep()" item-value="id"
                                v-model="data.Tipotransacion_id" required>
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs4 pl-2>
                            <v-autocomplete label="Bodega Solicitante*" :items="listBodegasByRole" item-text="Nombre"
                                item-value="id" @change="getPendingMovement()" v-model="data.BodegaOrigen_id" required>
                            </v-autocomplete>
                        </v-flex>

                        <v-flex xs12 v-if="data.Tipotransacion_id == 2">
                            <v-btn color="warning" @click="getAllpendingTraslates">Traslados Pendientes</v-btn>
                        </v-flex>
                        <!-- <v-container v-if="data.Tipotransacion_id == 4 && orden.bodegarticulos.length > 0">
                            <v-layout row wrap>
                                <v-flex xs2 px-1>
                                    <v-text-field label="Número factura*" v-model="orden.Numfactura" required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs2 px-1>
                                    <v-text-field label="Número factura zeus*" v-model="orden.Numfacturazeus" required>
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-container> -->
                    </v-layout>
                </v-container>
                <v-container v-show="data.Tipotransacion_id == 2">
                    <v-layout>
                        <v-flex xs6>
                            <v-text-field v-model="searchTraslado" append-icon="search" label="Buscar" single-line
                                hide-details></v-text-field>
                        </v-flex>
                    </v-layout>
                </v-container>
                <!-- <v-container v-if="data.Tipotransacion_id == 4 && orden.bodegarticulos.length > 0">
                    <v-layout>
                        <v-flex xs6>
                            <v-text-field
                                v-model="searchFactura"
                                append-icon="search"
                                label="Buscar"
                                single-line
                                hide-details
                            ></v-text-field>
                        </v-flex>
                    </v-layout>
                </v-container> -->
                <v-data-table v-show="data.Tipotransacion_id == 2" :headers="headerTraslado" :items="listTraslados"
                    :search="searchTraslado">
                    <template v-slot:items="props">
                        <td class="text-xs-center">{{ props.item.id}}</td>
                        <td class="text-xs-center">{{ props.item.created_at}}</td>
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
                <v-card-title primary-title v-if="data.Tipotransacion_id == 4">
                    <v-layout row wrap>
                        <v-flex xs6>
                            <v-layout row wrap>
                                <VSpacer />
                                <v-flex sm12 xs6>
                                    <v-text-field v-model="searchFactura" append-icon="search" label="Buscar"
                                        single-line hide-details></v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                    </v-layout>
                </v-card-title>
                <v-data-table :headers="headerArticulosCompra" :items="orden.bodegarticulos"
                    v-model="data.articuloSelected" v-if="data.Tipotransacion_id == 4" :expand="expand" item-key="id"
                    :search="searchFactura" ref="dTable">
                    <template v-slot:headers="props">
                        <tr>

                            <th v-for="header in props.headers" :key="header.text" :class="`text-xs-${header.align}`">
                                {{ header.text }}
                            </th>
                        </tr>
                    </template>
                    <template v-slot:items="props">
                        <tr>
                            <td class="text-xs-left">
                                <!--                                                        <v-btn fab dark small color="primary"-->
                                <!--                                                            @click="imprimir({type:'ordenCompra',id:props.item.id})">-->
                                <!--                                                            <v-icon dark>file_download</v-icon>-->
                                <!--                                                        </v-btn>-->

                                <v-chip label small color="red" text-color="white"
                                    @click="imprimir({type:'ordenCompra',id:props.item.id})">PDF</v-chip>
                                <v-chip label small color="green" text-color="white"
                                    @click="imprimirExcel(props.item.id)">EXCEL</v-chip>
                                <!-- <v-chip label small color="warning" text-color="white"
                                    @click="cargarMedicamentos(props.item.id)">CARGA FACTURA</v-chip> -->
                            </td>
                            <td class="text-xs-center">{{ props.item.id }}</td>
                            <td class="text-xs-left">{{ props.item.usuario}}</td>
                            <td class="text-xs-left">{{ props.item.created_at }}</td>
                            <td class="text-xs-center">{{ props.item.proveedor }}</td>
                            <td class="text-xs-right">
                                <v-btn outline color="error" round @click="anularOrdenCompra(props.item.id)">Anular
                                </v-btn>
                                <v-btn icon color="primary" @click="cargarMedicamentos(props,props.item.orden_compra)">
                                    <v-icon>visibility</v-icon>
                                </v-btn>
                            </td>
                        </tr>

                    </template>
                    <template v-slot:expand="props">
                        <v-container fluid grid-list-xl>
                            <v-layout row wrap>
                                <v-flex xs3>
                                    <v-text-field rows="10" v-model="codigoFactura" cols="10" label="Codigo Factura">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-btn outline color="success" round @click="confirmarMovimiento">Confirmar</v-btn>
                                </v-flex>
                                <v-flex xs2>
                                    <h3>Valor Factura <strong>{{sumaCalculada | pesoFormat}}</strong></h3>
                                </v-flex>
                                <v-flex xs5>
                                    <v-text-field v-model="searchMedicamento" append-icon="search" label="Buscar"
                                        single-line hide-details></v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-container>
                        <v-card flat>
                            <v-data-table :headers="headersMedicamentos" :items="solicitudDetalles" item-key="id"
                                :search="searchMedicamento" v-model="selectedFactura" :pagination.sync="pagination"
                                select-all>
                                <template v-slot:headers="props">
                                    <tr>
                                        <th>
                                            <v-checkbox :input-value="props.all" :indeterminate="props.indeterminate"
                                                color="primary" hide-details @click.stop="toggleAllFactura">
                                            </v-checkbox>
                                        </th>
                                        <th v-for="header in props.headers" :key="header.text"
                                            :class="['column sortable', pagination.descending ? 'desc' : 'asc', header.value === pagination.sortBy ? 'active' : '']"
                                            @click="changeSort(header.value)">
                                            <v-icon small>arrow_upward</v-icon>
                                            {{ header.text }}
                                        </th>
                                    </tr>
                                </template>
                                <template class="text-xs-right" v-slot:items="props">
                                    <td>
                                        <v-checkbox color="primary" v-model="props.selected" primary hide-details>
                                        </v-checkbox>
                                    </td>
                                    <td class="text-xs-right"
                                        v-if="((!props.item.lote || !props.item.fecha_vencimiento) && (!props.item.tipoNovedad))">
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn flat icon color="error" small v-on="on">
                                                    <v-icon>error</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Datos Incompletos</span>
                                        </v-tooltip>
                                    </td>
                                    <td class="text-xs-right"
                                        v-else-if="!props.item.lote && !props.item.fecha_vencimiento && props.item.tipoNovedad">
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn flat icon color="warning" small v-on="on">
                                                    <v-icon>done</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Novedad Agregada</span>
                                        </v-tooltip>
                                    </td>
                                    <td class="text-xs-right" v-else>
                                        <v-btn flat icon color="green">
                                            <v-icon>done</v-icon>
                                        </v-btn>
                                    </td>
                                    <td class="text-xs-right">{{ props.item.medicamento}}</td>
                                    <td class="text-xs-right">{{ props.item.titular}}</td>
                                    <td class="text-xs-right">{{ props.item.unidad_compra}}</td>
                                    <td class="text-xs-right">{{ props.item.precio_unidad}}</td>
                                    <td class="text-xs-right">{{ props.item.precio_total }}</td>
                                    <td class="text-xs-right">{{ props.item.saldo_cantidad }}</td>

                                    <td class="text-xs-right">
                                        <v-layout row wrap justify-center>
                                            <v-flex xs12>
                                                <v-text-field rows="10" cols="10" v-model="props.item.CantidadLote"
                                                    @input="validarSaldoLote(props.item)"></v-text-field>
                                            </v-flex>
                                        </v-layout>
                                    </td>
                                    <td class="text-xs-right">
                                        <v-layout row wrap justify-center>
                                            <v-flex xs12>
                                                <v-text-field rows="10" cols="10" v-model="props.item.lote">
                                                </v-text-field>
                                            </v-flex>
                                        </v-layout>
                                    </td>
                                    <td class="text-xs-right">
                                        <v-layout row wrap justify-center>
                                            <v-flex xs12>
                                                <v-text-field rows="10" cols="10" type="date"
                                                    v-model="props.item.fecha_vencimiento"></v-text-field>
                                            </v-flex>
                                        </v-layout>
                                    </td>
                                    <td class="text-xs-right" v-if="!props.item.tipoNovedad">
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab outline color="warning" small v-on="on"
                                                    @click="agregarNovedad(props.item)">
                                                    <v-icon>notification_important</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Novedad</span>
                                        </v-tooltip>
                                    </td>
                                    <td class="text-xs-right" v-else>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab outline color="warning" small v-on="on"
                                                    @click="props.item.tipoNovedad=null,props.item.devolucion=null,props.item.observacion=null,props.item.cantidadRecibida=null,props.item.nuevoPrecio=null">
                                                    <v-icon>close</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Eliminar Novedad</span>
                                        </v-tooltip>
                                    </td>
                                </template>
                            </v-data-table>
                        </v-card>
                    </template>
                </v-data-table>

                <v-flex xs12 pb-3>
                    <v-layout row wrap>
                        <v-spacer></v-spacer>
                        <v-btn :disabled="loading" color="success" round v-if="articuloAjusteSelected.length > 0"
                            @click="createAjusteMovement()">
                            Guardar ajuste
                        </v-btn>
                        <v-btn color="dark" round @click="saveTranslate()"
                            v-if="data.Tipotransacion_id == 2 && listArticulos.length > 0">
                            Generar</v-btn>
                        <v-btn color="success" @click="saveArticuloMovimiento()"
                            v-if="data.Tipotransacion_id == 4 && orden.selected.length > 0" :disabled="entrada" outline
                            round>Guardar
                            entrada</v-btn>
                        <v-spacer></v-spacer>
                    </v-layout>
                </v-flex>
            </v-card>
        </v-flex>
        <v-flex xs12 my-2>
            <v-card>
                <v-data-table :headers="articulosAjusteHeader" item-key="detalle_id" :items="articuloAjuste" select-all
                    v-model="articuloAjusteSelected"
                    v-show="data.Tipotransacion_id == 6 || data.Tipotransacion_id == 7">
                    <template v-slot:headers="props">
                        <tr>
                            <th>
                                <v-checkbox color="primary" :input-value="props.all"
                                    :indeterminate="props.indeterminate" primary hide-details @click.stop="toggleAll" />
                            </th>
                            <template v-for="header in props.headers">
                                <th v-show="checkHeader(header.text)" :key="header.text"
                                    :class="`text-xs-${header.align}`">
                                    {{ header.text }}
                                </th>
                            </template>
                        </tr>
                    </template>
                    <template v-slot:items="props">
                        <td :active="props.selected" @click="props.selected = !props.selected">
                            <v-checkbox color="primary" :input-value="props.selected" primary hide-details></v-checkbox>
                        </td>
                        <td class="text-xs-center">{{ props.item.id }}</td>
                        <td class="text-xs-center">{{ props.item.Motivo }}</td>
                        <td class="text-xs-center">{{ props.item.Producto }}</td>
                        <td class="text-xs-center">{{ props.item.Lote }}</td>
                        <td class="text-xs-center">
                            <v-edit-dialog cancel-text="Cancelar" large lazy persistent save-text="Cambiar"
                                :return-value.sync="props.item.Cantidad">
                                <div>
                                    <v-icon size="20" color="warning">edit</v-icon>
                                    <span> {{ props.item.Cantidad }} </span>
                                </div>
                                <template v-slot:input>
                                    <v-text-field autofocus counter label="Precio" v-model="props.item.Cantidad" />
                                </template>
                            </v-edit-dialog>
                        </td>
                        <td class="text-xs-center">{{ props.item.Fvence }}</td>
                    </template>
                </v-data-table>

            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    import {
        mapGetters
    } from 'vuex';

    import {
        Movimiento
    } from '../../../models/Movimiento'
    import DialogDetalleMovimiento from './DialogDetalleMovimiento';
    import {
        EventBus
    } from '../../../event-bus.js';
    import moment from 'moment';

    export default {
        name: 'BodegaMovimientos',
        components: {
            DialogDetalleMovimiento,
        },
        data() {
            return {
                listNovedad:[],
                preload: false,
                codigoSumisPendientes: [],
                formFiltro: {
                    bodega: null,
                    fechaDesde: null,
                    fechaHasta: null,
                    codesumi: null
                },
                buscarPendiente: '',
                allPendingTraslates: [],
                dialogPendingTraslates: false,
                valorFactura: 0,
                pagination: {
                    sortBy: 'name'
                },
                listMedicamentosNuevoNovedad: [],
                codigoFactura: "",
                ordenNovedad: [],
                formNovedad: {
                    tipoNovedad: '',
                    devolucion: false,
                    observacion: '',
                    cantidadRecibida: null,
                    nuevoPrecio: null,
                    nuevoMedicamento: null
                },
                active: 0,
                novedadNombre: '',
                dialog: false,
                selectedFactura: [],
                solicitudDetalles: [],
                searchTraslado: '',
                searchFactura: '',
                searchMedicamento: '',
                searchNovedad: '',
                listTraslados: [],
                entrada: false,
                listaTiposTransaccion: [],
                listaBodegas: [],
                listBodegasByRole: [],
                listaBodegaArticulos: [],
                listArticulos: [],
                listaTitular: [],
                listarNovedades: [],
                dialogDetalleOpen: false,
                idMovimientoDetalle: 0,
                statusMovimiento: 15,
                nombre: '',
                loading: false,
                menuDate: false,
                update: false,
                observaciones: '',
                today: moment().format('YYYY-MM-DD'),
                articulo: {
                    bodegaArticulo: {},
                    Cantidadtotal: 0,
                    Titular: {},
                    // Cum: ''
                },
                articuloAjuste: [],
                articuloAjusteSelected: [],
                articulosAjusteHeader: [{
                        text: 'id',
                        value: 'id',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Motivo',
                        value: 'Motivo',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Producto',
                        value: 'Producto',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Lote',
                        value: 'Lote',
                        sortable: false,
                        align: 'left'
                    },
                    {
                        text: 'Cantidad',
                        value: 'Cantidad',
                        sortable: false,
                        align: 'left'
                    },
                    {
                        text: 'Fecha vencimiento',
                        value: 'Fvence',
                        sortable: false,
                        align: 'left'
                    },
                ],
                headerTrasladoPendiente: [{
                        text: 'Identificación',
                        align: 'center',
                        value: 'id'
                    },
                    {
                        text: 'Fecha Solicitud',
                        align: 'center',
                        value: 'created_at'
                    },
                    {
                        text: 'Bodega Origen',
                        sortable: false,
                        align: 'center',
                        value: 'Bodega_Nombre'
                    },
                    {
                        text: 'Bodega Destino',
                        sortable: false,
                        align: 'center',
                        value: 'Bodega_Nombre_destino'
                    },
                    {
                        text: 'Descripción',
                        align: 'center',
                        value: 'descripcion',
                    },
                    {
                        text: 'Descripción Comercial',
                        align: 'center',
                        value: 'Medicamento',
                    },
                    {
                        text: 'Cantidad trasladada',
                        sortable: false,
                        align: 'center',
                        value: 'CantidadtotalFactura'
                    },
                    {
                        text: 'Lote',
                        align: 'center',
                        value: 'Numlote'
                    },
                    {
                        text: '',
                        sortable: false,
                        align: 'center',
                        value: ''
                    },
                ],
                headerTraslado: [{
                        text: 'Identificación',
                        align: 'center',
                        value: 'id'
                    },
                    {
                        text: 'Fecha Solicitud',
                        align: 'center',
                        value: 'created_at'
                    },
                    {
                        text: 'Bodega Origen',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Medicamento',
                        align: 'center',
                        value: 'Medicamento'
                    },
                    {
                        text: 'Cantidad trasladada',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'CUM',
                        align: 'center',
                        value: 'CUM_completo'
                    },
                    {
                        text: 'Lote',
                        align: 'center',
                        value: 'Numlote'
                    },
                    {
                        text: '',
                        sortable: false,
                        align: 'center'
                    },
                ],
                headerArticulosCompra: [{
                        text: '',
                        align: 'right',
                        sortable: false
                    },
                    {
                        text: '# solicitud',
                        align: 'center',
                        sortable: false,
                        value: 'id'
                    },
                    {
                        text: 'Autorizo',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Fecha Creacion',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Proveedor',
                        align: 'right',
                        sortable: false
                    },
                    {
                        text: ' ',
                        align: 'left',
                        sortable: false
                    }
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
                headersMedicamentos: [{
                        text: ' ',
                        align: 'center'
                    },
                    {
                        text: 'Medicamento',
                        align: 'center',
                        value: 'medicamento'
                    },
                    {
                        text: 'Titular',
                        align: 'center',
                        value: 'titular'
                    },
                    {
                        text: 'Presentacion',
                        align: 'center',
                        value: 'unidad_compra'
                    },
                    {
                        text: 'Precio Unidad',
                        align: 'center',
                        value: 'precio_unidad'
                    },
                    {
                        text: 'Precio Total',
                        align: 'center',
                        value: 'precio_total'
                    },
                    {
                        text: 'Cantidad',
                        align: 'center',
                        value: 'Cantidad'
                    },
                    {
                        text: 'Cantidad Lote',
                        align: 'center',
                        value: 'Cantidad'
                    },
                    {
                        text: 'Lote',
                        value: "",
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Fecha Vencimiento',
                        sortable: true,
                        value: "Cantidad",
                        align: 'center'
                    },
                    {
                        text: '',
                        sortable: false,
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
                expand: false,
            }
        },
        created() {
            EventBus.$on('close-dialog-detalle-movimiento', () => {
                this.dialogDetalleOpen = false;
                this.idMovimientoDetalle = 0;
            })
        },
        mounted() {
            const novedades = [
                {nombre:'Averias',permiso:'novedad.bodega.averia'},
                {nombre:'Sobrante',permiso:'novedad.bodega.sobrante'},
                {nombre:'Faltante',permiso:'novedad.bodega.faltante'},
                {nombre:'Producto No Conforme',permiso:'novedad.bodega.noconforme'},
                {nombre:'Producto No Solicitado',permiso:'novedad.bodega.nosolicitado'},
                {nombre:'Nuevo Precio', permiso:'novedad.bodega.nuevoprecio'},
            ];
            novedades.forEach(s => {
                if(this.can(s.permiso)){
                    this.listNovedad.push(s.nombre)
                }
            })
            this.fetchTiposTransaccion()
            this.fetchBodegaByRole()
            this.fetchBodegas()
        },
        computed: {
            ...mapGetters(['can']),
            filterlistaTiposTransaccion() {
                return this.listaTiposTransaccion.filter(tipo => {
                    if (tipo.id == 6 || tipo.id == 7) {
                        return (this.can('bodega.ajuste.auditar')) ? true : false
                    }
                    return true
                })
            },
            filteredBodega() {
                return this.listaBodegas.filter(bodega => bodega.id != this.data.BodegaOrigen_id)
            },
            sumaCalculada: function () {
                let total = 0;
                if (this.selectedFactura.length > 0) {
                    this.selectedFactura.forEach(s => {
                        total = total + parseInt(s.precio_total);
                    })
                }
                return total
            }
        },
        filters: {
            pesoFormat: (valor) => `$${new Intl.NumberFormat().format(valor) || 0}`
        },
        methods: {
            checkHeader(text) {
                if (text === 'Fvence') {
                    return data.Tipotransacion_id == 6 ? true : false
                }

                return true
            },
            clearFieldsArticulo() {
                this.articulo = {
                    bodegaArticulo: {},
                    Cantidadtotal: 0,
                    Titular: {},
                    // Cum: ''
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
                this.data.Reps_id = '';
                this.articuloAjuste = [];
                this.articuloAjusteSelected = [];
            },
            createAjusteMovement() {
                this.loading = true;
                axios.post('/api/bodega/' + this.data.BodegaOrigen_id + '/movimiento/ajuste', {
                        articulos: this.articuloAjusteSelected,
                        tipotransacion_id: this.data.Tipotransacion_id
                    })
                    .then(res => {
                        this.getAjusteEntradaByBodega();
                        this.articuloAjusteSelected = [];
                        swal({
                            title: res.data.message,
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                        this.loading = false;
                    })
            },
            fetchBodegas() {
                axios.get('/api/bodega/all')
                    .then(res => this.listaBodegas = res.data)
            },
            fetchBodegaArticulos() {
                this.loading = true;
                axios.post(`/api/bodega/${this.data.BodegaOrigen_id}/articulo/all`, {
                        nombre: this.nombre
                    })
                    .then(res => {
                        this.listaBodegaArticulos = res.data;
                        this.loading = false;
                    })
            },
            fetchBodegaByRole() {
                axios.get('/api/bodega/getBodegaByRole')
                    .then(res => {
                        if (res.data.length > 0) {
                            this.listBodegasByRole = res.data
                        }
                    })
            },
            fetchTiposTransaccion() {
                axios.get('/api/tipotransacion/movimiento')
                    .then(res => this.listaTiposTransaccion = res.data)
            },
            getAjusteEntradaByBodega() {
                axios.post(`/api/bodega/${this.data.BodegaOrigen_id}/solicitud/ajuste`, this.data)
                    .then(res => this.articuloAjuste = res.data)
            },
            getTraslados() {
                this.preload = true;
                axios.get(`/api/movimiento/${this.data.BodegaOrigen_id}/getTraslado`).then(res => {
                    this.listTraslados = res.data;
                    this.preload = false;
                }).catch(err => {
                    this.showError(err.response.data.message)
                    this.preload = false;

                });
            },
            acceptTransfer(traslado) {

                swal({
                    title: 'Está segur@ de aceptar el traslado',
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        this.preload = true;
                        axios.post(`/api/movimiento/acceptTransfer`, traslado).then(res => {
                            swal("Traslado aceptado de manera exitosa!", {
                                timer: 2000,
                                icon: "success",
                                buttons: false
                            });
                            this.getTraslados();
                            this.preload = false;
                        }).catch(err => {
                            this.preload = false;
                            this.showError(err.response.data.message)
                        });
                    }
                });
            },
            updateCant(traslado) {
                this.traslado = traslado;
                this.update = true;
            },
            getPendingMovement() {
                this.articuloAjusteSelected = [];
                switch (this.data.Tipotransacion_id) {
                    case 2:
                        this.fetchBodegas();
                        this.getTraslados();
                        break;
                    case 4:
                        this.getSolicitudesAprovadas();
                        this.getNovedadesFactura();
                        break;
                    case 6:
                    case 7:
                        this.getAjusteEntradaByBodega()
                        break;
                }
            },
            getSolicitudesAprovadas() {
                axios.post(`/api/bodega/${this.data.BodegaOrigen_id}/ordencompra/getSolicitudesEntradas`)
                    .then(res => {
                        this.orden.bodegarticulos = res.data
                    })
            },
            removeArticulo(index) {
                this.data.bodegarticulos.splice(index, 1);
            },
            removeArticuloToTranslate(index) {
                this.listArticulos.splice(index, 1);
            },
            save(index, item) {
                this.listArticulos.splice(index, 1, item);
            },
            async saveArticuloMovimiento() {
                if (!this.data.BodegaOrigen_id) return
                if (!this.orden.Numfactura) {
                    return swal({
                        title: 'Ingresa el número de factura',
                        text: " ",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    })
                }

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
                        axios.post(`/api/bodega/solicitud/create`, {
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
            toggleAll() {
                if (this.articuloAjusteSelected.length) this.articuloAjusteSelected = []
                else this.articuloAjusteSelected = this.articuloAjuste.slice()
            },
            validateMovimiento() {
                let success = true;
                this.orden.selected.forEach(sel => {
                    if (!sel.CantidadtotalFactura || !sel.Precio || !sel.Lote || !sel.Fvence) success = false;
                })

                return success

            },
            submitUpdate() {
                if (this.observaciones == "") {
                    swal({
                        title: "Debe llenar la Observacion",
                        icon: "warning"
                    });
                    return;
                }

                axios.post(`/api/movimiento/updateTransfer/${this.traslado.id}`, {
                    observaciones: this.observaciones,
                    ...this.traslado
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
            cancelDialog() {
                this.traslado = {};
                this.update = false;
                this.observaciones = '';
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
            cargarMedicamentos(pro, item) {
                this.solicitudDetalles = [];
                this.selectedFactura = [];
                pro.expanded = !pro.expanded
                this.solicitudDetalles = item.map(s => {
                    return {
                        id: s.id,
                        solicitudcompra_id: s.solicitudcompra_id,
                        medicamento: s.medicamento,
                        titular: s.titular,
                        Cantidad: s.Cantidad,
                        state: s.state,
                        unidad_compra: s.unidad_compra,
                        cantidad_inicial: s.cantidad_inicial,
                        precio_unidad: s.precio_unidad,
                        precio_total: (parseFloat(s.precio_unidad) * parseInt(s.Cantidad)).toFixed(2),
                        detallearticulo_id: s.detallearticulo_id,
                        lote: null,
                        fecha_vencimiento: null,
                        tipoNovedad: null,
                        devolucion: null,
                        observacion: null,
                        cantidadRecibida: null,
                        nuevoPrecio: null,
                        codesumi: s.codesumi,
                        saldo_cantidad: s.saldo_cantidad,
                        proveedor_id: pro.item.proveedor_id
                    }
                })
            },
            toggleAllFactura() {
                if (this.selectedFactura.length) this.selectedFactura = []
                else this.selectedFactura = this.solicitudDetalles.slice()
            },
            agregarNovedad(item) {
                this.formNovedad.tipoNovedad = '';
                this.formNovedad.devolucion = false;
                this.formNovedad.observacion = '';
                this.formNovedad.cantidadRecibida = null;
                this.formNovedad.nuevoPrecio = null;
                this.formNovedad.nuevoMedicamento = null
                this.ordenNovedad = item
                this.dialog = true;
            },
            guardarNovedad() {
                let objIndex = this.solicitudDetalles.findIndex((obj => obj.id === this.ordenNovedad.id));
                for (const prop of Object.getOwnPropertyNames(this.formNovedad)) {
                    this.solicitudDetalles[objIndex][prop] = this.formNovedad[prop];
                }
                this.dialog = false;
            },
            confirmarMovimiento() {
                if (this.selectedFactura.length > 0) {
                    const filtro = this.selectedFactura.filter(obj => (!obj.lote && !obj.fecha_vencimiento))
                    if (filtro.length === 0) {
                        if (this.codigoFactura) {
                            this.preload = true;
                            axios.post('/api/movimiento/entradaOrdenComprar/' + this.codigoFactura, this
                                .selectedFactura).then(res => {
                                this.$alerSuccess(res.data.message, " ", false)
                                for (const prop of Object.getOwnPropertyNames(this.$refs.dTable.expanded)) {
                                    this.$set(this.$refs.dTable.expanded, prop, false);
                                }
                                this.preload = false;
                                this.imprimir({
                                    type: "actaRecepcion",
                                    id: this.selectedFactura[0].solicitudcompra_id,
                                    numfactura: this.codigoFactura,
                                    proveedor_id: this.selectedFactura[0].proveedor_id
                                })
                                this.imprimir({
                                    type: "NovedadesOrden",
                                    id: this.selectedFactura[0].solicitudcompra_id,
                                    numfactura: this.codigoFactura
                                })
                                this.selectedFactura = [];
                                this.codigoFactura = "";
                                this.getSolicitudesAprovadas();
                            }).catch(err => {
                                this.preload = false;
                            })
                        } else {
                            this.$alerError('Codigo factura requerido', '', false)
                        }
                    } else {
                        this.$alerError('Columnas incompletas', '', false)
                    }
                } else {
                    this.$alerWarning("Debe seleccionar minimo un articulo", '', false)
                }
            },
            generarActa(solicitud) {
                axios({
                        method: 'post',
                        url: '/api/movimiento/actaResolucion/' + solicitud,
                        responseType: 'blob',
                    })
                    .then(res => {
                        let blob = new Blob([res.data], {
                            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                        });
                        let url = window.URL.createObjectURL(blob);
                        let a = document.createElement('a');
                        a.download = "ActaRecepcion";
                        a.href = url;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                    }).catch(err => {
                        // this.preload = false;
                    })
            },
            getNovedadesFactura() {
                axios.get('/api/movimiento/getNovedades/' + this.data.BodegaOrigen_id).then(res => {
                    this.listarNovedades = res.data;
                })
            },
            imprimirExcel(id) {
                axios({
                        method: 'post',
                        url: '/api/movimiento/detallesSolicitudCompra/' + id,
                        responseType: 'blob',
                    })
                    .then(res => {
                        let blob = new Blob([res.data], {
                            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                        });
                        let url = window.URL.createObjectURL(blob);
                        let a = document.createElement('a');
                        a.download = "OrdenCompra#" + id;
                        a.href = url;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                    }).catch(err => {
                        // this.preload = false;
                    })
            },
            validarNoSolicitado() {
                if (this.formNovedad.tipoNovedad === 'Producto No Solicitado') {
                    axios.get('/api/bodega/getDetallesDisponibles/' + this.ordenNovedad.codesumi + '/' + this
                        .ordenNovedad.solicitudcompra_id).then(res => {
                        this.listMedicamentosNuevoNovedad = res.data
                    })
                }
                if (this.formNovedad.tipoNovedad === 'Producto No Conforme') {
                    this.formNovedad.devolucion = 1;
                } else {
                    this.formNovedad.devolucion = 0;
                }
            },
            changeSort(column) {
                if (this.pagination.sortBy === column) {
                    this.pagination.descending = !this.pagination.descending
                } else {
                    this.pagination.sortBy = column
                    this.pagination.descending = false
                }
            },
            validarSaldoLote(item) {
                if (parseInt(item.CantidadLote) > parseInt(item.saldo_cantidad)) {
                    this.$alerError("La cantidad ingresada no debe ser superior a la cantidad esperada")
                    item.CantidadLote = 1;
                    return;
                }
                if (parseInt(item.CantidadLote) <= 0) {
                    this.$alerError("La cantidad ingresada no debe ser inferior o igual a 0")
                    item.CantidadLote = 1;
                    return;
                }
            },
            getAllpendingTraslates() {
                this.preload = true;
                axios.post('/api/movimiento/getAllpendingTraslates', this.formFiltro).then(res => {
                    this.allPendingTraslates = res.data.solicitudes;
                    this.codigoSumisPendientes = res.data.codigosSumi;
                    this.dialogPendingTraslates = true;
                    this.preload = false;
                }).catch(e => {
                    console.log(e);
                    this.preload = false;
                })
            },
            cancelTransfer(traslado) {
                swal({
                    title: 'Está segur@ de cancelar el traslado',
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.post(`/api/movimiento/cancelTransfer`, traslado).then(res => {
                            swal("Traslado cancelado de manera exitosa!", {
                                timer: 2000,
                                icon: "success",
                                buttons: false
                            });
                            this.getTraslados();
                            this.getAllpendingTraslates()
                        }).catch(err => this.showError(err.response.data.message));
                    }
                });
            },
            anularOrdenCompra(item) {
                swal({
                    title: 'Está segur@ de anular la orden de compra?',
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.post('/api/movimiento/anularOrdenCompra', {
                            solicitudcompra_id: item
                        }).then(res => {
                            this.getSolicitudesAprovadas();
                            this.$alerSuccess(res.data.message);
                        }).catch(err => {
                            this.$alerError(err.response.data.message)
                        })
                    }
                });
            }
        }
    }

</script>

<style scoped>
    .centered-input input {
        text-align: center
    }

</style>
