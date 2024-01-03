<template>
    <v-card>
        <template>
            <div class="text-center">
                <v-dialog v-model="preload" persistent width="300">
                    <v-card color="primary" dark>
                        <v-card-text>
                            Tranquilo procesamos tu solicitud !
                            <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                        </v-card-text>
                    </v-card>
                </v-dialog>
            </div>
        </template>
        <v-container fluid grid-list-xl>
            <v-container pt-3 pb-1>
                <v-layout row>
                    <v-dialog v-model="dialogFacturas" width="1200" persistent>
                        <v-card>
                            <!--                            <v-card-title class="headline grey lighten-2" primary-title>Facturas-->
                            <!--                            </v-card-title>-->
                            <!--                            <v-card-text>-->
                            <!--                                <v-container grid-list-md>-->
                            <!--                                    <v-layout wrap>-->
                            <!--                                        <v-flex xs12>-->
                            <!--                                            <v-autocomplete label="Bodegas" :items="listBodegasByRole" item-text="Nombre"-->
                            <!--                                                            item-value="id" v-model="formFiltro.bodega" required>-->
                            <!--                                            </v-autocomplete>-->
                            <!--                                        </v-flex>-->
                            <!--                                        <v-flex xs3>-->
                            <!--                                            <v-text-field type="date" v-model="formFiltro.fechaDesde" label="Desde"></v-text-field>-->
                            <!--                                        </v-flex>-->
                            <!--                                        <v-flex xs3>-->
                            <!--                                            <v-text-field type="date" v-model="formFiltro.fechaHasta" label="Hasta"></v-text-field>-->
                            <!--                                        </v-flex>-->
                            <!--                                        <v-flex xs6>-->
                            <!--                                            <v-autocomplete v-model="formFiltro.codesumi" label="Medicamento" item-value="id" item-text="Nombre" :items="codigoSumisPendientes"></v-autocomplete>-->
                            <!--                                        </v-flex>-->
                            <!--                                        <v-flex xs2 offset-xs10>-->
                            <!--                                            <v-btn @click="getAllpendingTraslates" color="success">Filtrar</v-btn>-->
                            <!--                                        </v-flex>-->
                            <!--                                    </v-layout>-->
                            <!--                                </v-container>-->


                            <!--                                <v-container grid-list-md>-->
                            <!--                                    <v-layout wrap>-->
                            <!--                                        &lt;!&ndash;                            <v-flex xs6>&ndash;&gt;-->
                            <!--                                        &lt;!&ndash;                                <v-text-field v-model="buscarPendiente" label="Buscar..."></v-text-field>&ndash;&gt;-->
                            <!--                                        &lt;!&ndash;                            </v-flex>&ndash;&gt;-->
                            <!--                                        <v-flex xs12>-->
                            <!--                                            &lt;!&ndash;                                            <v-data-table :headers="headerTrasladoPendiente" :items="allPendingTraslates" :search="buscarPendiente" class="elevation-1">&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                                                <template v-slot:items="props">&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                                                    <td class="text-xs-center">{{ props.item.id}}</td>&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                                                    <td class="text-xs-center">{{ props.item.created_at}}</td>&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                                                    <td class="text-xs-center">{{ props.item.Bodega_Nombre}}</td>&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                                                    <td class="text-xs-center">{{ props.item.Bodega_Nombre_destino}}</td>&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                                                    <td class="text-xs-center">{{ props.item.descripcion}}</td>&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                                                    <td class="text-xs-center">{{ props.item.Medicamento}}</td>&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                                                    <td class="text-xs-center">{{ props.item.CantidadtotalFactura }}</td>&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                                                    <td class="text-xs-center">{{ props.item.Numlote}}</td>&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                                                    <td class="text-xs-center">&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                                                        <v-tooltip top>&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                                                            <template v-slot:activator="{ on }">&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                                                                <v-btn fab outline color="error" small v-on="on"&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                                                                       @click="cancelTransfer(props.item)">&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                                                                    <v-icon>close</v-icon>&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                                                                </v-btn>&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                                                            </template>&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                                                            <span>Cancelar</span>&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                                                        </v-tooltip>&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                                                    </td>&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                                                </template>&ndash;&gt;-->
                            <!--                                            &lt;!&ndash;                                            </v-data-table>&ndash;&gt;-->
                            <!--                                        </v-flex>-->
                            <!--                                    </v-layout>-->
                            <!--                                </v-container>-->

                            <!--                            </v-card-text>-->
                            <!--                            <v-divider></v-divider>-->

                            <!--                            <v-card-actions>-->
                            <!--                                <v-spacer></v-spacer>-->
                            <!--                                <v-btn color="primary"  @click="dialogFacturas = false">Cerrar-->
                            <!--                                </v-btn>-->
                            <!--                            </v-card-actions>-->
                        </v-card>
                    </v-dialog>


                    <v-dialog v-model="dialogExport" persistent width="300">
                        <v-card color="primary" dark>
                            <v-card-text>
                                Exportando, espere por favor.
                                <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                    <v-dialog v-model="dialogUploadFact" max-width="600px">
                        <v-card>
                            <v-card-title>
                                <span v-if="save" class="headline">Agregar Medicamento</span>
                                <span v-else class="headline">Editar Inventario</span>
                            </v-card-title>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs12 sm6 md6>
                                            <input type="file" ref="uploadFact">
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" flat @click="dialogUploadFact = !dialogUploadFact">Cancelar
                                </v-btn>
                                <v-btn v-if="save" color="blue darken-1" flat @click="saveFactura">Guardar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <v-dialog v-model="edit" persistent max-width="600px">
                        <v-card>
                            <v-card-title>
                                <span class="headline">Modificar Cantidad Medicamento</span>
                            </v-card-title>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex md12>
                                            <v-text-field :type="'number'" label="Cantidad" v-model="newCant" required>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex md12>
                                            <v-textarea name="input-7-1" label="Observacion" v-model="observaciones">
                                            </v-textarea>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                            <v-divider></v-divider>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" flat @click="closeEditDialog()">Cancelar</v-btn>
                                <v-btn v-if="save" color="blue darken-1" flat @click="updateCant()">Guardar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <v-dialog v-model="showOthersStock" persistent max-width="600px">
                        <v-card>
                            <v-card-title>
                                <span class="headline">Cantidad total en las bodegas</span>
                            </v-card-title>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex md12>
                                        <span class="subheading">{{nameMedicamentoStocks}}</span>
                                        <v-data-table :headers="headersOtherStock" :items="otherStocks" hide-actions>
                                            <template v-slot:items="props">
                                                <td class="text-xs-right">{{ props.item.Nombre }}</td>
                                                <td class="text-xs-right">{{ props.item.Stock }}</td>
                                            </template>
                                        </v-data-table>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                            <v-divider></v-divider>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" flat @click="showOthersStock = !showOthersStock">Cancelar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <v-dialog v-model="showFacturas" max-width="1000px" persistent>
                        <v-card>
                            <v-toolbar color="primary" dark>
                                <v-toolbar-title>Facturas</v-toolbar-title>
                            </v-toolbar>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex md12>
                                        <v-card>
                                            <v-card-title>
                                                <v-spacer></v-spacer>
                                                <v-text-field v-model="searchFactura" append-icon="search"
                                                    label="Buscar" single-line hide-details></v-text-field>
                                            </v-card-title>
                                            <v-data-table :headers="headersFactura" :items="facturas"
                                                :search="searchFactura">
                                                <template v-slot:items="props">
                                                    <td class="text-xs-right">{{ props.item.numfactura }}</td>
                                                    <td class="text-xs-right">{{ props.item.proveedor }}</td>
                                                    <td class="text-xs-right">{{ props.item.created_at.split('.')[0] }}
                                                    </td>
                                                    <td class="text-xs-right">
                                                        <v-tooltip top>
                                                            <template v-slot:activator="{ on }">
                                                                <v-btn text icon color="deep-orange" dark v-on="on"
                                                                    :readonly="preload" @click="imprimir(props.item)">
                                                                    <v-icon>file_copy</v-icon>
                                                                </v-btn>
                                                            </template>
                                                            <span>ACTA DE RECEPCIÓN</span>
                                                        </v-tooltip>
                                                    </td>
                                                </template>
                                                <template v-slot:no-results>
                                                    <v-alert :value="true" color="error" icon="warning">
                                                        Your search for "{{ searchFactura }}" found no results.
                                                    </v-alert>
                                                </template>
                                            </v-data-table>
                                        </v-card>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="error" @click="closeFactura()">Salir</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-layout>
            </v-container>
            <v-card-title>
                <v-flex xs12 my-3>
                    <v-autocomplete v-model="data.Bodega_id" append-icon="search" :items="bodegas" item-text="Nombre"
                        item-value="id" label="Buscar Bodega..." single-line hide-details @input="inventarioselect">
                    </v-autocomplete>
                </v-flex>
                <v-flex xs5 v-show="data.Bodega_id || data.Bodega_id === 0">
                    <v-layout row wrap>
                        <v-flex xs12 v-if="can('bodegaPendiente.export')">
                            <v-layout row wrap>
                                <v-flex xs4>
                                    <v-menu ref="menu" v-model="menu" :close-on-content-click="false" :nudge-right="40"
                                        :return-value.sync="startDate" lazy transition="scale-transition" offset-y
                                        full-width min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <v-text-field v-model="startDate" label="Fecha inicio" prepend-icon="event"
                                                readonly v-on="on"></v-text-field>
                                        </template>
                                        <v-date-picker v-model="startDate" color="primary" locale="es" no-title
                                            scrollable>
                                            <v-spacer></v-spacer>
                                            <v-btn flat color="primary" @click="menu = false">Cancelar</v-btn>
                                            <v-btn flat color="primary" @click="$refs.menu.save(startDate)">OK</v-btn>
                                        </v-date-picker>
                                    </v-menu>
                                </v-flex>
                                <v-flex xs4>
                                    <v-menu ref="menu2" v-model="menu2" :close-on-content-click="false"
                                        :nudge-right="40" :return-value.sync="finishDate" lazy
                                        transition="scale-transition" offset-y full-width min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <v-text-field v-model="finishDate" label="Fecha final" prepend-icon="event"
                                                readonly v-on="on"></v-text-field>
                                        </template>
                                        <v-date-picker v-model="finishDate" color="primary" locale="es" no-title
                                            scrollable>
                                            <v-spacer></v-spacer>
                                            <v-btn flat color="primary" @click="menu2 = false">Cancelar</v-btn>
                                            <v-btn flat color="primary" @click="$refs.menu2.save(finishDate)">OK</v-btn>
                                        </v-date-picker>
                                    </v-menu>
                                </v-flex>

                            </v-layout>
                        </v-flex>
                        <v-flex xs12>
                            <v-btn v-if="can('facturaInventario.upload')" round @click="createMedicamento()"
                                color="primary" dark inabled>
                                <v-icon left>cloud_upload</v-icon>Cargar Factura
                            </v-btn>
                            <v-btn round @click="exportSaldos()" color="success" dark inabled>
                                <v-icon left>cloud_download</v-icon>Exportar saldos
                            </v-btn>

                            <v-btn round color="warning" dark inabled @click="openFactura()">
                                <v-icon left>list</v-icon>Facturas
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </v-flex>
                <v-flex v-show="data.Bodega_id || data.Bodega_id === 0" xs7 class="text-xs-right">
                    <label>Valor Estimado Bodega:</label> <strong>{{this.valorBodega | pesoFormat }}</strong>
                </v-flex>
                <v-flex v-show="data.Bodega_id || data.Bodega_id === 0" xs7>
                    <v-text-field v-model="search" append-icon="search" label="Buscar..." single-line hide-details>
                    </v-text-field>
                </v-flex>
            </v-card-title>
            <v-data-table :headers="headers" :items="invetario" :search="search">
                <template v-slot:headers="props">
                    <tr>
                        <!--                        <th v-for="header in props.headers" :key="header.value">-->
                        <!--                            <template v-show="header.text != 'Bodega'">{{header.text }}</template>-->
                        <!--                        </th>-->
                        <th key="Codigo">Codigo</th>
                        <th key="Bodega" v-if="data.Bodega_id === 0">Bodega</th>
                        <th key="nombreSumi">Descripción</th>
                        <th key="Producto">Descripción Comercial</th>
                        <th key="Titular">Titular</th>
                        <th key="CUM_completo">Cum</th>
                        <th key="Numlote">Lote</th>
                        <th key="Cantidadisponible">Exitencia</th>
                        <th key="">Fecha vencimiento</th>
                        <th key="">Estado Molécula</th>
                        <th></th>

                    </tr>
                </template>
                <template v-slot:items="props">
                    <td class="text-xs-right">{{ props.item.codigoDetalle }}</td>
                    <td class="text-xs-right" v-if="data.Bodega_id === 0">{{ props.item.Bodega }}</td>
                    <td class="text-xs-right">{{ props.item.nombreSumi }}</td>
                    <td class="text-xs-right">{{ props.item.Producto }}</td>
                    <td class="text-xs-right">{{ props.item.Titular }}</td>
                    <td class="text-xs-right">{{ props.item.CUM_completo }}</td>
                    <td class="text-xs-right">{{ props.item.Numlote }}</td>
                    <td class="text-xs-right">{{ props.item.Cantidadisponible }}</td>
                    <td class="text-xs-right">
                        <v-chip :class="colorVencido(props.item.Fvence)">{{props.item.Fvence }}</v-chip>
                    </td>
                    <td class="text-xs-right" v-if="can('inventario.edit')">
                        <v-edit-dialog :return-value.sync="props.item.Fvence" large lazy persistent
                            cancel-text="Cancelar" save-text="Cambiar"
                            @save="editaFechaVence(props.item.Lote_id,props.item.Fvence)">
                            <div>{{ props.item.Fvence }}<v-icon right>edit</v-icon>
                            </div>
                            <template v-slot:input>
                                <div class="mt-3 title">Update Número Meses</div>
                            </template>
                            <template v-slot:input>
                                <v-text-field v-model="props.item.Fvence" label="Edit" single-line type="date" counter
                                    autofocus></v-text-field>
                            </template>
                        </v-edit-dialog>
                    </td>
                    <td class="text-xs-right">{{ props.item.Estado_id | estado }}</td>
                    <td class="text-xs-right">
                        <v-btn v-if="can('inventario.show')" fab outline color="info" small
                            @click="showCantMedicamento(props.item)">
                            <v-icon>search</v-icon>
                        </v-btn>
                    </td>
                </template>
                <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                    Your search for "{{ search }}" found no results.
                </v-alert>
            </v-data-table>
        </v-container>
    </v-card>
</template>

<script>
    import {
        mapGetters
    } from 'vuex';
    import moment, {
        locale
    } from 'moment';
    moment.locale('es');
    export default {
        name: 'MedicamentosInventario',
        data() {
            return {
                listBodegasByRole: [],
                dialogFacturas: false,
                preload: false,
                valorBodega: 0,
                menu: false,
                menu2: false,
                startDate: moment().format('YYYY-MM-DD'),
                finishDate: moment().format('YYYY-MM-DD'),
                dialogUploadFact: false,
                invetario: [],
                expand: false,
                bodegas: [],
                medicamentos: [],
                grupos: [],
                subGrupos: [],
                medicamentosBodega: [],
                detalleMedicamento: [],
                Medicamento_id: null,
                newCant: 0,
                medicamentoAux: {},
                search: '',
                dialogExport: false,
                headersOtherStock: [{
                        text: 'Bodega',
                        align: 'right',
                        sortable: false,
                    },
                    {
                        text: 'Cantidad total',
                        align: 'right',
                        sortable: false,
                    },
                ],
                headers: [{
                        text: 'Codigo',
                        align: 'right',
                        sortable: false,
                        value: 'codigoDetalle'
                    },
                    {
                        text: 'Bodega',
                        align: 'right',
                        sortable: false,
                        value: 'Codigo'
                    },
                    {
                        text: 'Descripción',
                        align: 'right',
                        sortable: false,
                        value: 'Producto'
                    },
                    {
                        text: 'Titular',
                        align: 'right',
                        sortable: false,
                        value: 'Titular'
                    },
                    {
                        text: 'Cum',
                        align: 'right',
                        sortable: false,
                        value: 'CUM_completo'
                    },
                    {
                        text: 'Lote',
                        align: 'right',
                        sortable: false,
                        value: 'Numlote'
                    },
                    {
                        text: 'Exitencia',
                        align: 'right',
                        sortable: false,
                        value: 'Cantidadisponible'
                    },
                    {
                        text: 'Fecha vencimiento',
                        align: 'left',
                        sortable: false,
                        value: ''
                    },
                    {
                        text: 'Estado Molécula',
                        align: 'right',
                        sortable: false,
                        value: ''
                    },
                    {
                        text: '',
                        align: 'right',
                        sortable: false,
                        value: ''
                    }
                ],
                headersFactura: [{
                        text: 'Factura',
                        align: 'right',
                        sortable: false,
                        value: 'numfactura'
                    },
                    {
                        text: 'Proveedor',
                        align: 'right',
                        sortable: false,
                        value: 'proveedor'
                    },
                    {
                        text: 'Fecha',
                        align: 'center',
                        sortable: false,
                        value: 'created_at'
                    },
                    {
                        text: 'Acciones',
                        align: 'right',
                        sortable: false,
                        value: ''
                    }
                ],
                save: true,
                subSave: true,
                dialog: false,
                subDialog: false,
                edit: false,
                data: {
                    Bodega_id: '',
                    Detallearticulo_id: '',
                    Stock: '',
                    Cantidadmaxima: '',
                    Cantidadminima: '',
                },
                titular_selected: '',
                forma_farmaceutica_selected: '',
                umedida_selected: '',
                observaciones: '',
                otherStocks: [],
                showOthersStock: false,
                nameMedicamentoStocks: '',
                showFacturas: false,
                facturas: [],
                searchFactura: '',
            }
        },
        computed: {
            ...mapGetters(['can']),
            titularArticulo() {
                return this.medicamentos.filter((medicamento) => medicamento.Principio_Activo === this.principio_activo)
            },
            presentacionArticulo() {
                return this.medicamentos.filter((medicamento) => {
                    if (medicamento.Principio_Activo === this.principio_activo && medicamento.Titular === this
                        .titular_selected) {
                        return medicamento;
                    }
                })
            },
            UmedidaArticulo() {
                return this.medicamentos.filter((medicamento) => {
                    if (medicamento.Principio_Activo === this.principio_activo && medicamento.Titular === this
                        .titular_selected && medicamento.Forma_Farmaceutica && this.forma_farmaceutica_selected
                    ) {
                        return medicamento;
                    }
                })
            },
            CantidadArticulo() {
                return this.medicamentos.filter((medicamento) => {
                    if (medicamento.Principio_Activo === this.principio_activo && medicamento.Titular === this
                        .titular_selected && medicamento.Forma_Farmaceutica == this
                        .forma_farmaceutica_selected && medicamento.Unidad_Medida === this.umedida_selected) {
                        return medicamento;
                    }
                })
            },
            principio_activo: function () {
                const medicamento = this.medicamentos.filter((medicamento) => medicamento.Principio_Activo === this
                    .principio_activo)
                //revisar esta linea no entiendo mucho el core del negocio, pero no estaba guardando el detalle articulo y quiero saber si el ID es este
                this.data.Detallearticulo_id = medicamento[0]['id'];
            }
        },
        watch: {
            search: function () {
                if (this.search && this.search.length == 3) {
                    this.getArticulosBodega();
                }
            }
        },
        filters: {
            estado(estado) {
                return (estado == 1) ? 'Activo' : 'Inactivo'
            },
            pesoFormat: (valor) => `$${new Intl.NumberFormat().format(valor) || 0}`
        },
        mounted() {
            this.fetchBodegas()
            // this.fetchMedicamentos();
            this.fetchSubgrupos();
        },
        methods: {
            colorVencido(fecha) {
                let fechaActual = moment();
                let fechaAnterior = moment(fecha);
                let diferencia = fechaAnterior.diff(fechaActual, 'days')
                if (diferencia <= 180) {
                    return 'red white--text';
                } else {
                    return 'green white--text';
                }
            },
            inventarioselect() {
                this.valorBodega = 0;
                this.preload = true;
                this.data.Bodega_id
                axios.post('/api/bodega/inventariobodega', {
                        Bodega_id: this.data.Bodega_id
                    }).then(res => {
                        res.data.forEach(s => {
                            if (s.precio_unidad) {
                                this.valorBodega += parseInt(s.precio_unidad) * parseInt(s.Cantidadisponible)
                            }
                        })
                        this.invetario = res.data
                        this.preload = false;
                    })
                    .catch(error => {
                        this.preload = false;
                        console.log(error.data)
                    });
            },
            fetchBodegas() {
                axios.get('/api/bodega/all')
                    .then(res => {
                        this.listBodegasByRole = res.data
                        this.bodegas.push({
                            id: 0,
                            Nombre: 'SELECCIONAR TODAS'
                        })
                        res.data.forEach(s => {
                            this.bodegas.push(s)
                        })
                    })
            },
            // fetchMedicamentos() {
            //     axios.get('/api/detallearticulo/all')
            //         .then(res => this.medicamentos = res.data);
            // },
            fetchSubgrupos() {
                axios.get('/api/subgrupo/enabled')
                    .then(res => this.subGrupos = res.data);
            },
            getArticulosBodega() {
                axios.post(`/api/bodega/${this.data.Bodega_id}/articulo/allArticulos`, {
                        nombre: this.search
                    })
                    .then(res => {
                        this.medicamentosBodega = res.data
                    })
            },
            createMedicamento() {
                this.emptyData();
                this.save = true;
                this.dialogUploadFact = true;
            },
            async createDetalle(Medicamento) {
                await this.emptySubData();
                // console.log('Medicamento', Medicamento)
                this.subData.Articulo_id = Medicamento.item.id;
                this.subSave = true;
                this.subDialog = true;
            },
            showError(message) {
                swal({
                    title: "¡No puede ser!",
                    text: `${message.name}`,
                    icon: "warning",
                });
            },
            async expandTable(props) {
                await this.getDetalle(props.item.id);
                props.expanded = !props.expanded;

            },
            emptyData() {
                this.data = {
                    Bodega_id: this.data.Bodega_id,
                    Detallearticulo_id: '',
                    Stock: '',
                    Cantidadmaxima: '',
                    Cantidadminima: '',
                }
                this.principio_activo = ''
                this.titular_selected = ''
                this.forma_farmaceutica_selected = ''
                this.umedida_selected = ''
            },
            saveMedicamento() {

                axios.post(`/api/bodega/${this.data.Bodega_id}/articulo/create`, this.data)
                    .then(res => {
                        this.emptyData();
                        this.dialog = false;
                        this.getArticulosBodega();
                        swal({
                            title: "¡Medicamento Agregado!",
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch(err => this.showError(err.response.data))
            },
            saveDetalle() {
                axios.post(`/api/detallearticulo/create`, this.subData)
                    .then(res => {
                        this.subDialog = false;
                        this.getDetalle(this.subData.Grupo_id);
                        this.emptySubData();
                        swal({
                            title: "¡Detalle Agregado!",
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch(err => this.showError(err.response.data))
            },
            setFact() {
                this.dialogUploadFact = true;
                this.$refs.uploadFact.value = '';
            },
            editMedicamento(medicamento) {
                this.data = {
                    Bodega_id: medicamento.Bodega_id,
                    Detallearticulo_id: medicamento.id,
                    Stock: medicamento.Stock,
                    Cantidadmaxima: medicamento.Cantidadmaxima,
                    Cantidadminima: medicamento.Cantidadminima,
                }
                this.principio_activo = medicamento.Principio_Activo
                this.titular_selected = medicamento.Titular
                this.forma_farmaceutica_selected = medicamento.Forma_Farmaceutica
                this.umedida_selected = medicamento.Unidad_Medida
                this.save = false;
                this.dialog = true;
            },
            editCantMedicamento(medicamento) {
                this.medicamentoAux = medicamento;
                this.edit = true;
            },
            closeEditDialog() {
                this.edit = false;
                this.medicamentoAux = {},
                    this.newCant = 0;
                this.observaciones = '';
            },
            updateCant() {

                if (this.newCant < 0) {
                    swal({
                        title: "Debe diligenciar una cantidad igual o superior a 0",
                        icon: "warning"
                    });
                    return;
                }

                if (this.observaciones == "") {
                    swal({
                        title: "Debe llenar la Observacion",
                        icon: "warning"
                    });
                    return;
                }
                this.medicamentoAux.observaciones = this.observaciones;
                this.medicamentoAux.Cantidadisponible = this.newCant;
                axios.post('/api/bodega/updateCant', this.medicamentoAux)
                    .then(res => {
                        swal({
                            title: "¡Existencias actualizada     con exito!",
                            text: ` `,
                            timer: 3000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch(error => console.log(error.data));
                this.closeEditDialog();
                this.inventarioselect();
            },
            editDetalle(detalle) {
                this.subData = detalle;
                this.subSave = false;
                this.subDialog = true;
            },
            async saveFactura() {

                let formData = new FormData();
                formData.append('data', this.$refs.uploadFact.files[0]);
                console.log('formData', formData)

                await axios.post('/api/file/facturas/import', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(res => {
                        this.dialogUploadFact = false;
                        swal({
                            title: res.data.message,
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch(err => console.log(err.response))
                this.dialogUploadFact = false;
                await swal({
                    title: "Factura",
                    text: "Carga exitosa",
                    timer: 2000,
                    icon: "success",
                    buttons: false
                });
            },
            showCantMedicamento(articulo) {
                this.showOthersStock = true;
                this.nameMedicamentoStocks = articulo.Producto;
                this.otherStocks = [];
                axios.post(`/api/bodega/inventarioOtherStock`, articulo)
                    .then(res => {
                        this.otherStocks = res.data;
                    })
                    .catch(err => console.log(err.response.data));
            },
            closeDialog() {
                this.emptyData()
                this.dialog = false
            },

            exportSaldos() {
                axios({
                    method: 'get',
                    url: `/api/bodega/${this.data.Bodega_id}/saldos`,
                    responseType: 'blob'
                }).then(res => {
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                    });
                    let url = window.URL.createObjectURL(blob);
                    window.location.href = url;
                    let a = document.createElement("a")
                    a.download = 'hola'
                    // a.href = url
                    document.body.appendChild(a)
                    a.click()
                    document.body.removeChild(a)
                }).catch(err => {
                    console.log(err)
                })
            },
            editaFechaVence(id, fecha) {
                this.preload = true;
                const data = {
                    Fvence: fecha
                };
                axios.post('/api/bodega/lote/editar/' + id, data).then(res => {
                    this.preload = false;
                    this.$alerSuccess('Fecha Actualizada')
                })
            },
            openFactura() {
                this.showFacturas = true
                let data = {
                    dateStart: this.startDate,
                    dateEnd: this.finishDate
                }
                axios.post(`/api/bodega/${this.data.Bodega_id}/solicitudCompraFacturas`, data).then(res => {
                    this.facturas = res.data
                })
            },
            closeFactura() {
                this.facturas = []
                this.showFacturas = false
            },
            imprimir(item) {
                this.preload = true
                let pdf = {
                    type: "actaRecepcion",
                    numfactura: item.numfactura,
                    proveedor_id: item.proveedor_id
                }
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
                        this.preload = false
                    }).catch(err => {
                        this.preload = false
                    })
            },
        },
    }

</script>

<style lang="scss" scoped>

</style>
