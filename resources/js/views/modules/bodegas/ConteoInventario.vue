<template>
    <v-layout>
        <v-flex xs12 sm12>
            <v-card>
                <v-card-title primary-title>
                    <div>
                        <h3 class="headline mb-0">Conteo de Inventario</h3>
                    </div>
                </v-card-title>
                <v-card-actions>
                    <v-container fluid grid-list-xl>
                        <v-layout wrap align-center>
                            <v-flex xs12 sm6 d-flex>
                                <v-autocomplete :items="bodegas" item-text="Nombre" append-icon="search" item-value="id"
                                    v-model="data.Bodega_id" label="Selecciona la bodega a contar"></v-autocomplete>
                            </v-flex>
                            <v-flex xs3 sm1 d-flex>
                                <v-btn color="primary" @click="generarInventario()">Generar</v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-actions>
                <v-container fluid grid-list-xl2>

                    <v-data-table :headers="headers" :items="listaInventarios">
                        <template v-slot:headers>
                            <tr>
                                <td>Bodega</td>
                                <td>Fecha creacion</td>
                                <td>Estado</td>
                                <td></td>
                            </tr>
                        </template>
                        <template v-slot:items="props">
                            <td>{{ props.item.bodega }}</td>
                            <td>{{ props.item.created_at }}</td>
                            <td>
                                <v-chip color="green" text-color="white">{{ props.item.estado }}</v-chip>
                            </td>
                            <td>
                                <v-btn color="warning"
                                    @click="idInventario = props.item.id; reanudarInventarios(props.item.bodega_id)"
                                    :disabled="!can('reanudar.conteo')">Reanudar</v-btn>
                            </td>

                        </template>
                        <template v-slot:no-results>
                            <v-alert :value="true" color="error" icon="warning">
                                Your search for "{{ search }}" found no results.
                            </v-alert>
                        </template>
                    </v-data-table>
                </v-container>
            </v-card>

            <v-dialog v-model="dialogEditar" width="500">
                <v-card>
                    <v-card-title class="headline grey lighten-2" primary-title>
                        Editar Fecha vencimiento
                    </v-card-title>
                    <v-card-text>
                        <v-flex xs12 sm6 md4>
                            <v-menu v-model="menu2" :close-on-content-click="false" :nudge-right="40" lazy
                                transition="scale-transition" offset-y full-width min-width="290px">
                                <template v-slot:activator="{ on }">
                                    <v-text-field v-model="date" label="Fecha vence" prepend-icon="event" readonly
                                        v-on="on"></v-text-field>
                                </template>
                                <v-date-picker locale="es" v-model="date" @input="menu2 = false"></v-date-picker>
                            </v-menu>
                        </v-flex>
                    </v-card-text>
                    <v-divider></v-divider>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" flat @click="dialogEditar = false">
                            Cancelar
                        </v-btn>
                        <v-btn color="success" flat @click="editaFechaVence()">
                            Actualizar
                        </v-btn>
                    </v-card-actions>
                </v-card>

            </v-dialog>
            <v-dialog v-model="dialogInventario" fullscreen hide-overlay transition="dialog-bottom-transition">
                <template>
                    <div class="text-center">
                        <v-dialog v-model="preload" persistent width="300">
                            <v-card color="primary" dark>
                                <v-card-text>
                                    Tranquilo procesamos tu solicitud !
                                    <v-progress-linear indeterminate color="white" class="mb-0">
                                    </v-progress-linear>
                                </v-card-text>
                            </v-card>
                        </v-dialog>
                    </div>
                    <div class="text-center">
                        <v-dialog v-model="buscarLote" width="1200" persistent>
                            <v-card>
                                <v-card-title>
                                    <span class="headline">Buscar Lote</span>
                                </v-card-title>
                                <v-card-text>
                                    <v-container grid-list-md>
                                        <v-layout wrap>
                                            <v-flex xs6 sm3 md2>
                                                <v-text-field label="NumLote" v-model="numeroLote" required>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm8 md6>
                                                <v-text-field label="Medicamento" v-model="nombreMedicamento">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm6 md4>
                                                <v-btn color="success" @click="buscarloteEnCero">Buscar</v-btn>
                                            </v-flex>
                                            <v-flex xs12 sm12 md12>
                                                <v-data-table :items="listLotesCero" key="id">
                                                    <template v-slot:headers>
                                                        <tr>
                                                            <th>LoteID</th>
                                                            <th>NumLote</th>
                                                            <th>Codigo</th>
                                                            <th>Producto</th>
                                                            <th>Titular</th>
                                                            <th></th>
                                                        </tr>
                                                    </template>
                                                    <template v-slot:items="props">
                                                        <td class="text-xs-center">{{ props.item.id }}</td>
                                                        <td class="text-xs-center">{{ props.item.Numlote }}</td>
                                                        <td class="text-xs-center">{{ props.item.codigo }}</td>
                                                        <td class="text-xs-center">{{ props.item.Nombre }}</td>
                                                        <td class="text-xs-center">{{ props.item.titular }}</td>
                                                        <td>
                                                            <v-btn color="info"
                                                                @click="agregarLoteEnCero(props.item,props.index)"
                                                                :disabled="agregarCero">Agregar</v-btn>
                                                        </td>
                                                    </template>
                                                    <template v-slot:no-results>
                                                        <v-alert :value="true" color="error" icon="warning">
                                                            Your search for "{{ search }}" found no results.
                                                        </v-alert>
                                                    </template>
                                                </v-data-table>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="error"
                                        @click="numeroLote = '',nombreMedicamento = '',listLotesCero = [],buscarLote = false">
                                        Cerrar</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </div>
                </template>
                <v-card>
                    <v-toolbar dark color="primary">
                        <v-btn icon dark @click="dialogInventario = false,listarInventariosPendientes()">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Conteos</v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <template>
                        <div class="text-xs-center">
                            <v-progress-circular :rotate="-90" :size="100" :width="2" :value="0" color="teal">
                                <label>Conteo 1</label>
                                <div class="red--text">
                                    <label>Pendiente</label>
                                    {{falta1}}
                                </div>
                            </v-progress-circular>

                            <v-progress-circular :rotate="-90" :size="100" :width="2" :value="0" color="warning">
                                <label>Conteo 2</label>
                                <div class="red--text">
                                    <label>Pendiente</label>
                                    {{falta2}}
                                </div>
                            </v-progress-circular>

                            <v-progress-circular :rotate="-90" :size="100" :width="2" :value="0" color="red">
                                <label>Conteo 3</label>
                                <div class="red--text">
                                    <label>Pendiente</label>
                                    {{falta3}}
                                </div>
                            </v-progress-circular>
                        </div>
                    </template>
                    <v-divider></v-divider>
                    <v-container fluid grid-list-xl>
                        <v-card-title>
                            <v-btn v-if="can('conteo.1')" color="success" class="white--text" :disabled="conteo1"
                                v-on:click="cierreConteo1()">
                                Cerrar Conteo 1
                                <v-icon right dark>mdi-content-save-all</v-icon>
                            </v-btn>
                            <v-btn v-if="can('conteo.2')" color="warning" class="white--text" :disabled="conteo2"
                                v-on:click="cierreConteo2()">
                                Cerrar Conteo 2
                                <v-icon right dark>mdi-content-save-all</v-icon>
                            </v-btn>
                            <v-btn v-if="can('conteo.3')" color="error" class="white--text" :disabled="conteo3"
                                v-on:click="cierreConteo3()">
                                Cerrar Conteo 3
                                <v-icon right dark>mdi-content-save-all</v-icon>
                            </v-btn>
                            <v-btn v-if="can('cerrarConteos')" color="primary" class="white--text" :disabled="cierre"
                                v-on:click="updateEstado()">
                                Cerrar Conteos
                                <v-icon right dark>mdi-content-save-all</v-icon>
                            </v-btn>
                            <template>
                                <v-layout row justify-center>
                                    <v-dialog v-model="dialog" persistent max-width="600px">
                                        <template>
                                            <v-btn color="primary" dark @click="proximosConteos">Próximo Conteos</v-btn>
                                        </template>
                                    </v-dialog>
                                </v-layout>
                            </template>
                            <v-text-field v-model="search" append-icon="search" label="Buscar Molécula" single-line
                                hide-details>
                            </v-text-field>
                        </v-card-title>
                        <v-data-table :headers="headers" :items="invetario" :search="search" key="idLote">
                            <template>
                                <tr>
                                    <th>LoteID</th>
                                    <th>Codigo</th>
                                    <th>Producto</th>
                                    <th>Titular</th>
                                    <th>Numlote</th>
                                    <th>Fvence</th>
                                    <th v-if="can('conteo.saldo')">Saldo</th>
                                    <th v-if="can('conteo.1')">Conteo1</th>
                                    <th v-if="can('conteo.2')">Conteo2</th>
                                    <th v-if="can('conteo.3')">Conteo3</th>
                                    <th v-if="can('value.1')">Resultado conteo</th>
                                </tr>
                            </template>
                            <template v-slot:items="props">
                                <td>{{ props.item.idLote }}</td>
                                <td>{{ props.item.Codigo }}</td>
                                <td>{{ props.item.Producto }}</td>
                                <td>{{ props.item.Titular }}</td>
                                <td>{{ props.item.Numlote }}</td>
                                <td>
                                    <v-edit-dialog :return-value.sync="props.item.Fvence" large lazy persistent
                                        cancel-text="Cancelar" save-text="Cambiar"
                                        @save="editaFechaVence(props.item.idLote,props.item.Fvence)">
                                        <div>{{ props.item.Fvence }}</div>
                                        <template v-slot:input>
                                            <div class="mt-3 title">Update Número Meses</div>
                                        </template>
                                        <template v-slot:input>
                                            <v-text-field v-model="props.item.Fvence" label="Edit" single-line counter
                                                autofocus></v-text-field>
                                        </template>
                                    </v-edit-dialog>
                                </td>
                                <td v-if="can('conteo.saldo')">{{ props.item.Cantidadisponible }}</td>
                                <td v-if="can('conteo.1')">
                                    <v-text-field :readonly="props.item.Estado_id == 1?false:true"
                                        :type="props.item.Estado_id == 1 || props.item.Estado_id == 32?'text':'password'"
                                        v-model="props.item.Conteo1" label="conteo 1"
                                        @input="faltaconteo1($event,props.item)">
                                    </v-text-field>
                                </td>
                                <td v-if="can('conteo.2')">
                                    <v-text-field :readonly="props.item.Estado_id == 30?false:true"
                                        :type="props.item.Estado_id == 30 || props.item.Estado_id == 32?'text':'password'"
                                        v-show="(props.item.Estado_id == 30 || props.item.Estado_id == 32 || props.item.Estado_id == 31) && props.item.Conteo1 !== props.item.saldo_inicial"
                                        v-model="props.item.Conteo2" label="conteo 2"
                                        @input="faltaconteo2($event,props.item)">
                                    </v-text-field>
                                </td>
                                <td v-if="can('conteo.3')">
                                    <v-text-field :readonly="props.item.Estado_id != 31"
                                        v-show="(props.item.Estado_id == 31 ||props.item.Estado_id == 32) && props.item.Conteo1 !== props.item.Conteo2 && props.item.Conteo2 !== props.item.saldo_inicial  && props.item.Conteo2"
                                        v-model="props.item.Conteo3" @input="faltaconteo3($event,props.item)">
                                    </v-text-field>
                                </td>
                                <td v-if="can('value.1')">
                                    <strong>
                                        <h4>{{props.item.Value1}}</h4>
                                    </strong>
                                </td>
                            </template>
                            <template v-slot:no-results>
                                <v-alert :value="true" color="error" icon="warning">
                                    Your search for "{{ search }}" found no results.
                                </v-alert>
                            </template>
                        </v-data-table>
                        <div>
                            <v-btn color="info" @click="guardarProgreso" dark>Guardar progreso</v-btn>
                            <v-btn color="primary" @click="buscarLote = true" :disabled="conteo1" dark>Agregar Lote
                            </v-btn>
                            <v-btn color="warning" @click="addArticulo = true" :disabled="conteo1" dark>Agregar
                                DetalleArticulo</v-btn>
                        </div>
                    </v-container>
                </v-card>
            </v-dialog>

            <v-dialog v-model="addArticulo" width="1200" persistent>
                <v-card>
                    <v-card-title>
                        <span class="headline">Buscar DetalleArticulo</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm8 md6>
                                    <v-text-field label="Codigo" v-model="codigoDetalle"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-btn color="success" @click="buscarDetalle">Buscar</v-btn>
                                </v-flex>
                                <v-flex xs12 sm12 md12>
                                    <v-data-table :items="detalles" class="elevation-1" hide-actions>
                                        <template v-slot:headers>
                                            <tr>
                                                <th><strong>Codigo</strong></th>
                                                <th><strong>Producto</strong></th>
                                                <th><strong>Titular</strong></th>
                                                <th><strong>NumLote</strong></th>
                                                <th><strong>Fecha Vence</strong></th>
                                                <th></th>
                                            </tr>
                                        </template>
                                        <template v-slot:items="props">
                                            <tr>
                                                <td class="text-xs-center">{{ props.item.codigo}}</td>
                                                <td class="text-xs-center">{{ props.item.Producto}}</td>
                                                <td class="text-xs-center">{{ props.item.titular}}</td>
                                                <td>
                                                    <v-text-field v-model="loteArticulo">
                                                    </v-text-field>
                                                </td>
                                                <td>
                                                    <v-text-field type="date" v-model="fechaArticulo"></v-text-field>
                                                </td>
                                                <td>
                                                    <v-btn color="info" small @click="addetalle(props.item)">Agregar
                                                    </v-btn>
                                                </td>
                                            </tr>
                                        </template>
                                    </v-data-table>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" @click="closeDetalle">Cerrar
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-flex>
    </v-layout>
</template>

<script>
    import Swal from 'sweetalert2';
    import moment from 'moment';
    moment.locale('es');
    import {
        mapGetters
    } from 'vuex';
    export default {
        data() {
            return {
                listLotesCero: [],
                numeroLote: "",
                nombreMedicamento: "",
                buscarLote: false,
                agregarCero: false,
                interval: {},
                actualizados: [],
                bodegas: [],
                falta1: 0,
                falta2: 0,
                falta3: 0,
                invetario: [],
                search: '',
                dialog: false,
                menu1: false,
                preload: false,
                disableGenerate: true,
                dialogInventario: false,
                listaInventarios: [],
                idInventario: null,
                dialogEditar: false,
                codigoLoteFechaVence: null,
                date: new Date().toISOString().substr(0, 10),
                menu2: false,
                idIndexArray: null,
                pagination: {
                    sortBy: 'name'
                },
                fechaInventario: moment().format('YYYY-MM-DD'),
                data: {
                    Bodega_id: '',
                    Bodearticulo_id: '',
                },
                headers: [{

                        text: 'LoteID',
                        align: 'left',
                        sortable: false,
                        value: 'Id'
                    },
                    {
                        text: 'Codigo',
                        align: 'left',
                        sortable: false,
                        value: 'Codigo'
                    },
                    {
                        text: 'Producto',
                        value: 'Producto'
                    },
                    {
                        text: 'Titular',
                        value: 'Titular'
                    },
                    {
                        text: 'Numlote',
                        value: 'Numlote'
                    },
                    {
                        text: 'Fvence',
                        value: 'Fvence'
                    },
                    {
                        text: 'Cantidadisponible',
                        value: 'Cantidadisponible'
                    },
                    {
                        text: 'Conteo 1',
                        value: ''
                    },
                    {
                        text: 'Conteo 2',
                        value: ''
                    },
                    {
                        text: 'Conteo 3',
                        value: ''
                    },
                    {
                        text: 'Resultado conteo',
                        value: ''

                    }
                ],
                conteo1: false,
                conteo2: false,
                conteo3: false,
                cierre: false,
                addArticulo: false,
                codigoDetalle: '',
                detalles: [],
                loteArticulo: '',
                fechaArticulo: '',

            }
        },
        beforeDestroy() {
            clearInterval(this.interval)
        },
        mounted() {
            this.fetchBodegas()
            this.listarInventariosPendientes()
        },
        computed: {
            ...mapGetters(['can']),
        },
        methods: {
            cierreConteo1() {
                if (this.falta1 !== 0) {
                    this.$alerError("Hay valores pendiente en el primer conteo");
                    return;
                }
                if (this.actualizados.length > 0) {
                    this.$alerError("Hay valores sin guardar en el primer conteo");
                    return;
                }
                this.preload = true;
                axios.post('/api/conteo/conteo1/' + this.idInventario, this.invetario)
                    .then(res => {
                        this.reanudarInventarios();
                        this.preload = false;
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'bottom',
                            background: 'success',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            onOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })

                        Toast.fire({
                            icon: 'success',
                            title: 'Conteo guardado con exito!'
                        });
                    })
            },
            cierreConteo2() {
                if (this.falta2 !== 0) {
                    this.$alerError("Hay valores pendiente en el segundo conteo")
                    return;
                }
                if (this.actualizados.length > 0) {
                    this.$alerError("Hay valores sin guardar en el segundo conteo")
                    return;
                }
                this.preload = true;
                axios.post('/api/conteo/conteo2/' + this.idInventario, this.invetario)
                    .then(res => {
                        this.reanudarInventarios();
                        this.preload = false;
                        swal({
                            title: "¡Conteo 2 cerrado con exito!",
                            text: ` `,
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
            },
            cierreConteo3() {
                if (this.falta3 !== 0) {
                    this.$alerError("Hay valores pendiente en el tercer conteo")
                    return;
                }
                if (this.actualizados.length > 0) {
                    this.$alerError("Hay valores sin guardar en el tercer conteo")
                    return;
                }
                this.preload = true;

                axios.post('/api/conteo/conteo3/' + this.idInventario, this.invetario)
                    .then(res => {
                        this.reanudarInventarios();
                        this.preload = false;
                        swal({
                            title: "¡Conteo 3 cerrado con exito!",
                            text: ` `,
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
            },
            updateEstado() {
                this.preload = true;
                axios.post('/api/conteo/update/' + this.idInventario, this.invetario)
                    .then(res => {
                        this.idInventario = null;
                        this.dialogInventario = false;
                        this.preload = false;
                        this.listarInventariosPendientes();
                        swal({
                            title: "¡Conteo cerrado con exito!",
                            text: ` `,
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
            },
            faltaconteo1(e, item) {
                this.preload = true;

                let objIndex = this.actualizados.findIndex((obj => obj.lote === item.Numlote));

                if (objIndex === -1) {
                    if (Number.isInteger(parseInt(item.Conteo1)) && parseInt(item.Conteo1) >= 0) {
                        this.actualizados.push({
                            lote: item.Numlote,
                            cantidad: item.Conteo1,
                            id: item.idConteo
                        });
                        if (this.falta1 > 0) {
                            this.falta1--
                        }
                    }
                } else {
                    if (!item.Conteo1) {
                        this.falta1++;
                        this.actualizados.splice(objIndex, 1)
                    } else {
                        if (Number.isInteger(parseInt(item.Conteo1)) && parseInt(item.Conteo1) >= 0) {
                            this.actualizados[objIndex].cantidad = item.Conteo1;
                        }
                    }
                }
                this.preload = false;
            },
            faltaconteo2(e, item) {
                this.preload = true;

                let objIndex = this.actualizados.findIndex((obj => obj.lote === item.Numlote));

                if (objIndex === -1) {
                    if (Number.isInteger(parseInt(item.Conteo2)) && parseInt(item.Conteo2) >= 0) {
                        this.actualizados.push({
                            lote: item.Numlote,
                            cantidad: item.Conteo2,
                            id: item.idConteo
                        });
                        if (this.falta2 > 0) {
                            this.falta2--
                        }
                    }
                } else {
                    if (!item.Conteo2) {
                        this.falta2++;
                        this.actualizados.splice(objIndex, 1)
                    } else {
                        if (Number.isInteger(parseInt(item.Conteo2)) && parseInt(item.Conteo2) >= 0) {
                            this.actualizados[objIndex].cantidad = item.Conteo2;
                        }
                    }
                }
                this.preload = false;
            },
            faltaconteo3(e, item) {
                this.preload = true;

                let objIndex = this.actualizados.findIndex((obj => obj.lote === item.Numlote));

                if (objIndex === -1) {
                    if (Number.isInteger(parseInt(item.Conteo3)) && parseInt(item.Conteo3) >= 0) {
                        this.actualizados.push({
                            lote: item.Numlote,
                            cantidad: item.Conteo3,
                            id: item.idConteo
                        });
                        if (this.falta3 > 0) {
                            this.falta3--
                        }
                    }
                } else {
                    if (!item.Conteo3) {
                        this.falta3++;
                        this.actualizados.splice(objIndex, 1)
                    } else {
                        if (Number.isInteger(parseInt(item.Conteo3)) && parseInt(item.Conteo3) >= 0) {
                            this.actualizados[objIndex].cantidad = item.Conteo3;
                        }
                    }
                }
                this.preload = false;
            },
            fetchBodegas() {
                axios.get('/api/bodega/all')
                    .then(res => this.bodegas = res.data)
            },
            saveconteo(invetario) {
                axios.post('/api/conteo/create', {
                        Lote_id: invetario.idLote,
                        Conteo1: invetario.Conteo1,
                        Conteo2: invetario.Conteo2,
                        Conteo3: invetario.Conteo3,
                        Conteo4: invetario.Conteo4,
                        Bodegaarticulo_id: invetario.Bodegaarticulo_id
                    })
                    .then(res => {
                        this.inventarioselect();
                        swal({
                            title: "¡Se guardo con exito!",
                            text: ` `,
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
            },
            checkInputs(data) {
                if ((!!data.Conteo1 != data.est1) || (!!data.Conteo2 != data.est2) || (!!data.Conteo3 != data.est3) || (
                        !!data.Conteo4 != data.est4)) {
                    return false;
                }

                return true;
            },
            exportConteo() {
                axios({
                    method: 'post',
                    params: {
                        fechaInventario: this.fechaInventario
                    },
                    url: '/api/conteo/export',
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

                    this.dialog = false
                }).catch(err => {
                    console.log(err)
                })
            },
            totalconteo1() {
                this.invetario.forEach(inv => {
                    let total = 0
                    if (inv.Conteo1 == null) {
                        total += 1;
                    }
                });
            },
            generarInventario() {
                if (!this.data.Bodega_id) {
                    this.$alerError("Debe seleccionar un bodega");
                    return;
                }
                let objIndex = this.listaInventarios.findIndex((obj => parseInt(obj.bodega_id) === this.data
                    .Bodega_id));
                if (objIndex > -1) {
                    this.$alerError("La bodega seleccionada tiene un inventario pendiente");
                    return;
                }
                this.preload = true;
                swal({
                    title: '¿Está segur@ de iniciar el inventario?',
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.get('/api/bodega/inventario/generar/' + this.data.Bodega_id).then(res => {
                            if (res.status === 200) {
                                this.idInventario = res.data.codigoInventario;
                                this.reanudarInventarios();
                            }
                        })
                    } else {
                        this.preload = false;
                    }
                });
            },
            reanudarInventarios(bodega) {
                this.preload = true;
                if(bodega != undefined){
                    this.bodegaId = bodega
                }
                axios.get('/api/bodega/inventario/consulta/conteos/' + this.idInventario).then(res => {
                    if (res.data.lista.length > 0) {
                        this.falta1 = parseInt(res.data.faltantes1);
                        this.falta2 = parseInt(res.data.faltantes2);
                        this.falta3 = parseInt(res.data.faltantes3);
                        switch (parseInt(res.data.estadoInventario)) {
                            case 1:
                                this.conteo1 = false;
                                this.conteo2 = true;
                                this.conteo3 = true;
                                this.cierre = true;
                                break;
                            case 30:
                                this.conteo1 = true;
                                this.conteo2 = false;
                                this.conteo3 = true;
                                this.cierre = true;

                                break;
                            case 31:
                                this.conteo1 = true;
                                this.conteo2 = true;
                                this.conteo3 = true;
                                this.cierre = true;
                                if (parseInt(res.data.faltantes3) > 0) {
                                    this.conteo3 = false;
                                }
                                if (parseInt(res.data.faltantes3) === 0) {
                                    this.cierre = false;
                                }
                                break;
                            case 32:
                                this.conteo1 = true;
                                this.conteo2 = true;
                                this.conteo3 = true;
                                this.cierre = false;
                                break;
                        }
                        this.invetario = res.data.lista;
                        if (this.dialogInventario === false) {
                            this.dialogInventario = true;
                            this.preload = false;
                        }

                    } else {
                        if (parseInt(res.data.estadoInventario) === 31 && parseInt(res.data.faltantes3) === 0) {
                            if (this.dialogInventario === false) {
                                this.dialogInventario = true;
                            }
                            this.cierreConteo3();
                        }
                    }
                    this.preload = false;
                    this.actualizados = [];
                })
            },
            guardarProgreso() {
                this.preload = true;
                axios.post('/api/conteo/actualizar', this.actualizados).then(res => {
                    if (res.status === 200) {
                        this.actualizados = [];
                        this.reanudarInventarios();
                        this.preload = false;
                    }
                })
            },
            listarInventariosPendientes() {
                this.preload = true;

                axios.get('/api/bodega/inventario/consulta').then(res => {
                    this.listaInventarios = res.data;
                    this.preload = false;
                })
            },
            changeSort(column) {
                if (this.pagination.sortBy === column) {
                    this.pagination.descending = !this.pagination.descending
                } else {
                    this.pagination.sortBy = column;
                    this.pagination.descending = false
                }
            },
            cargarFechaVence(id, fecha, idIndex) {
                this.date = fecha;
                this.codigoLoteFechaVence = id;
                this.idIndexArray = idIndex;
                this.dialogEditar = true;
            },
            editaFechaVence(id, fecha) {
                this.preload = true;
                const data = {
                    Fvence: fecha
                };
                axios.post('/api/bodega/lote/editar/' + id, data).then(res => {
                    this.preload = false;
                })
            },
            buscarloteEnCero() {
                this.preload = true;
                const data = {
                    numeroLote: this.numeroLote,
                    nombreMedicamento: this.nombreMedicamento
                };
                if (this.numeroLote === "" && this.nombreMedicamento === "") {
                    this.$alerError("Debe ingresar un valor");
                    return;
                }
                axios.post('/api/bodega/buscar/lote/' + this.idInventario, data).then(res => {
                    if (res.data.length > 0) {
                        this.listLotesCero = res.data;
                    }
                    this.preload = false;
                })
            },
            agregarLoteEnCero(item, index) {
                let codigo = this.invetario.find(i => i.Codigo == item.codigo && i.Numlote == item.Numlote);

                if (codigo == undefined) {
                    this.agregarCero = true;
                    axios.get('/api/bodega/inventario/agregar/' + this.idInventario + '/' + item.id).then(res => {
                        this.listLotesCero.splice(index, 1);
                        this.reanudarInventarios();
                        this.preload = false;
                        this.agregarCero = false;
                    })
                } else {
                    this.$alerError("El Codigo y NumLote ya existe!");
                    return;
                }

            },
            proximosConteos() {
                axios({
                    method: 'get',
                    url: '/api/conteo/proximos/' + this.idInventario,
                    responseType: 'blob'
                }).then(res => {
                    let blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download = `InformeInventario.xlsx`;
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);

                    this.dialog = false
                }).catch(err => {
                    console.log(err)
                })
            },
            buscarDetalle() {
                if (this.codigoDetalle == "") return;
                axios.post('/api/bodega/buscarDetalle', {
                    codigo: this.codigoDetalle
                }).then(res => {
                    if (res.data.length <= 0) {
                        this.$alerError("No existe el codigo!");
                    } else {
                        this.detalles = res.data
                    }
                })
            },
            addetalle(item) {
                if (this.loteArticulo == '' || this.loteArticulo == null || this.fechaArticulo == '' |
                    this.fechaArticulo == null) {
                    this.$alerError("Debe ingresar NumLote y Fecha Vence");
                    return;
                }
                let codigo = this.invetario.find(i => i.Codigo == item.codigo && i.Numlote == this.loteArticulo);
                if (codigo == undefined) {
                    let data = {
                        detalle_id: item.id,
                        lote: this.loteArticulo,
                        fecha: this.fechaArticulo,
                        bodega_id: this.bodegaId
                    }
                    this.agregarCero = true;
                    axios.post('/api/bodega/inventario/agregarDetalle/' + this.idInventario, data).then(res => {
                        this.$alerSuccess('Detalle Articulo agregado con exito!');
                        this.reanudarInventarios();
                        this.closeDetalle();
                    })
                } else {
                    this.$alerError("El Codigo y NumLote ya existe!");
                    return;
                }
            },
            closeDetalle() {
                this.codigoDetalle = ''
                this.detalles = []
                this.addArticulo = false
                this.loteArticulo = ''
                this.fechaArticulo = ''
            }
        }

    }

</script>

<style lang="stylus" scoped>

</style>
