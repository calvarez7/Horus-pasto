<template>
    <v-container fluid grid-list-xl>
        <v-dialog v-model="preload" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Estamos procesando su información
                    <v-progress-linear indeterminate color="white" class="mb-0">
                    </v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-dialog
            v-model="dialogMenu.dialog" width="1200" persistent>
            <v-card>
                <v-card-title
                    class="headline grey lighten-2"
                    primary-title
                >
                    <!--                    {{dialogMenu.nameMenu.toUpperCase()}}-->
                    <!--                    <v-text-field class="float:right" type="number"></v-text-field>-->
                    <v-layout row wrap>
                        <v-flex xs4>
                            {{ dialogMenu.nameMenu.toUpperCase() }}
                        </v-flex>
                        <v-flex xs4></v-flex>
                        <v-flex xs4>
                            <v-text-field label="VALOR MENU" v-model="compuestoMenu.valor" class="float:right"
                                          type="number"></v-text-field>
                        </v-flex>
                    </v-layout>
                </v-card-title>

                <v-card-text>
                    <v-container grid-list-md>
                        <form>
                            <v-layout wrap>
                                <v-flex xs10>
                                    <VAutocomplete v-model="productoMenu" :items="productos" label="Agregar producto"
                                                   item-value="id"
                                                   item-text="nombre"/>
                                </v-flex>
                                <v-flex xs2>
                                    <v-btn color="success" @click="agregarProducto('existente')">Agregar</v-btn>
                                </v-flex>
                                <v-flex xs3>
                                    <v-text-field label="Nombre (Componente)"
                                                  v-model="nuevoProducto.nombre"></v-text-field>
                                </v-flex>
                                <v-flex xs3>
                                    <v-text-field label="Presentacion (Componente)"
                                                  v-model="nuevoProducto.presentacion"></v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field type="number" label="Codigo Barras"
                                                  v-model="nuevoProducto.codigoBarras"></v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field type="number" label="Valor"
                                                  v-model="nuevoProducto.valor"></v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-btn color="success" @click="agregarProducto('nuevo')">Crear</v-btn>
                                </v-flex>
                            </v-layout>
                        </form>
                    </v-container>
                    <v-container grid-list-md v-for="(producto,index) in compuestoMenu.productos" :key="index">
                        <v-layout wrap>
                            <v-flex xs12>
                                <div>
                                    <v-toolbar flat color="white">
                                        <v-toolbar-title>{{ producto.nombre }}
                                            <v-btn fab small color="error">
                                                <v-icon dark @click="eliminarProductoMenu(index)">remove</v-icon>
                                            </v-btn>
                                        </v-toolbar-title>
                                        <v-divider
                                            class="mx-2"
                                            inset
                                            vertical
                                        ></v-divider>
                                        <v-spacer></v-spacer>
                                        <VAutocomplete label="Agregar Ingrediente" :items="listaIngredientes"
                                                       v-model="ingredienteProducto[index]" item-value="id"
                                                       item-text="nombre"/>
                                        <v-btn color="primary" dark class="mb-2"
                                               @click="agregarIngrediente(producto.id,index)">Agregar
                                        </v-btn>
                                    </v-toolbar>
                                    <v-data-table :headers="headers"
                                                  :items="i.filter((obj => obj.producto === producto.id))"
                                                  class="elevation-1">
                                        <template v-slot:headers="props">
                                            <td>Nombre</td>
                                            <td class="text-xs-center">Cantidad
                                                Existencias(gramos/mililitros/unidades)
                                            </td>
                                            <td class="text-xs-center">Precio por Medida (gramos/mililitros/unidades)
                                            </td>
                                            <td>Cantidad Requridad</td>
                                            <td></td>
                                        </template>
                                        <template v-slot:items="props">
                                            <td>{{ props.item.nombre }}</td>
                                            <td>{{ props.item.existencia }} ({{ props.item.unidad }})</td>
                                            <td>${{ props.item.precioUnidad }} (x {{ props.item.unidad }})</td>
                                            <td>
                                                <v-text-field type="number" v-model="props.item.requerida"
                                                              @input="validar($event,props.item.existencia,props.item.id)"></v-text-field>
                                                {{ props.item.unidad }}
                                            </td>
                                            <td>
                                                <v-btn fab small color="error"
                                                       @click="eliminarIngredienteMenu(props.item.nombre,index)">
                                                    <v-icon dark>mdi-window-close</v-icon>
                                                </v-btn>
                                            </td>

                                        </template>
                                    </v-data-table>
                                </div>
                            </v-flex>
                        </v-layout>
                    </v-container>


                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" @click="cerrarMenu">Cerrar</v-btn>
                    <v-btn color="success" @click="guardarMenu">Guardar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-layout row wrap>
            <v-flex xs12>
                <v-select outline label="Seleccionar Bodega..." :items="listBodegas" item-text="nombre" item-value="id"
                          @input="loadProductos($event)"></v-select>
            </v-flex>
            <v-flex xs7 md7>
<!--                <v-card>-->
<!--                    <v-card-text>-->
<!--                        <v-container class="pa-0">-->
<!--                            <v-layout>-->
<!--                                <v-chip v-for="menu in menus" row wrap :key="menu.nombre"-->
<!--                                        color="teal"-->
<!--                                        @click="agregarMenuCarrito(menu)"-->
<!--                                        label-->
<!--                                        text-color="white"-->
<!--                                >{{ menu.nombre }}-->
<!--                                </v-chip>-->
<!--                            </v-layout>-->
<!--                        </v-container>-->
<!--                    </v-card-text>-->
<!--                </v-card>-->
                <v-card>
                    <v-card-text>
                        <v-container class="pa-0">
                            <v-layout row wrap>
                                <v-flex xs11 sm11 d-flex>
                                    <v-text-field
                                        label="Lector"
                                        outline v-model="codigoBarras"
                                        @focus="estadoLector= false"
                                        @blur="estadoLector = true"
                                        autofocus
                                        :disabled="deshabilitarCodigo"
                                    ></v-text-field>
                                </v-flex>
                                <v-flex xs1 sm1>
                                    <v-btn flat icon color="green" :disabled="estadoLector">
                                        <v-icon>mdi-circle</v-icon>
                                    </v-btn>
                                </v-flex>
                                <v-flex xs12 sm12 d-flex>
                                    <v-data-table
                                        :headers="headersFacturas"
                                        :items="carrito"
                                        hide-actions
                                        class="elevation-1"
                                    >
                                        <template v-slot:items="props">
                                            <td>{{ props.item.id }}</td>
                                            <td>{{ props.item.nombre }}</td>
                                            <td>
                                                <v-btn v-show="props.item.cantidad > 1" class="mx-2" fab dark small
                                                       color="warning" @click="restarCantidad(props.item.id)">
                                                    <v-icon dark>mdi-minus</v-icon>
                                                </v-btn>
                                                <strong>{{ props.item.cantidad }}</strong></td>
                                            <td>${{ props.item.precio }}</td>
                                            <td>
                                                <v-btn class="mx-2" fab dark small color="error"
                                                       @click="quitarProducto(props.item.id)">
                                                    <v-icon dark>mdi-window-close</v-icon>
                                                </v-btn>
                                            </td>
                                        </template>
                                    </v-data-table>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex xs5 md5>
                <v-card>
                    <v-card-text>
                        <v-container class="pa-0">
                            <v-layout row wrap>
                                <v-flex xs1 md1>
                                    <strong>FECHA: </strong>
                                </v-flex>
                                <v-flex xs3 md3>
                                    <p>{{ fechaActual }}</p>
                                </v-flex>
                                <v-flex xs2 md2>
                                    <strong>CAJER@: </strong>
                                </v-flex>
                                <v-flex xs4 md4>
                                    <p>{{ nameUser }}</p>
                                </v-flex>
                                <v-flex xs6 sm6 d-flex>
                                    <v-text-field
                                        label="Identificacion Usuario"
                                        outline v-model="identificacion"
                                    ></v-text-field>
                                </v-flex>
                                <v-flex xs4 sm4 d-flex>
                                    <v-btn color="info" @click="getUser" dark>
                                        Buscar
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
                <v-card v-show="usuario.nombre !== ''">
                    <v-card-text>
                        <v-container class="pa-0">
                            <v-layout row wrap>
                                <v-flex xs12 sm12 d-flex>
                                    <h4>{{ usuario.nombre }} </h4>
                                    <p>({{ usuario.tipo }})</p><br>
                                    <h4>CC {{ usuario.numero }}</h4>
                                </v-flex>
                                <v-flex xs12 sm12 d-flex>
                                    <v-select
                                        :items="tipoEntrega"
                                        label="Tipo Entrega"
                                        v-model="entrega"
                                        outline
                                    ></v-select>
                                </v-flex>

                                <v-flex xs12 sm12 d-flex>
                                    <v-select
                                        :items="formaPago"
                                        label="Forma de pago"
                                        v-model="pago"
                                        outline
                                    ></v-select>
                                </v-flex>
                                <v-flex xs6 sm6 d-flex>
                                    <v-text-field v-show='pago === "Efectivo"'
                                                  label="Total Efectivo" type="number"
                                                  outline v-model="totalEfectivo"
                                    ></v-text-field>
                                </v-flex>

                                <v-flex xs6 sm6 d-flex>
                                    <v-text-field v-show='entrega === "Domicilio"'
                                                  label="Valor Domicilio" type="number"
                                                  outline v-model="totalDomicilio"
                                    ></v-text-field>
                                </v-flex>
                                <v-flex xs6 sm6 d-flex>
                                    <v-text-field v-show='cantidadEmpaques > 0 && entrega === "Domicilio"'
                                                  label="Valor Empaque" type="number"
                                                  outline v-model="totalEmpaque"
                                    ></v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>

                <v-card>
                    <v-card-text>
                        <v-container class="pa-0">
                            <v-layout row wrap>
                                <v-flex xs6 sm6 d-flex>
                                    <strong><h2>TOTAL PRODUCTOS</h2></strong>
                                </v-flex>
                                <v-flex xs6 sm6 d-flex class="text-xs-right" shrink>
                                    <h2>$ {{ totalProductos }}</h2>
                                </v-flex>

                                <v-flex xs6 sm6 d-flex>
                                    <strong v-show='cantidadEmpaques > 0 && entrega === "Domicilio"'><h2>TOTAL
                                        EMPAQUES</h2></strong>
                                </v-flex>
                                <v-flex xs6 sm6 d-flex class="text-xs-right" shrink>
                                    <h2 v-show='cantidadEmpaques > 0 && entrega === "Domicilio"'>$
                                        {{ (totalEmpaque === "" ? 0 : parseInt(totalEmpaque)) * parseInt(cantidadEmpaques) }}
                                        (x{{ cantidadEmpaques }})</h2>
                                </v-flex>

                                <v-flex xs6 sm6 d-flex>
                                    <strong v-show='entrega === "Domicilio"'><h2>TOTAL DOMICILIO</h2></strong>
                                </v-flex>
                                <v-flex xs6 sm6 d-flex class="text-xs-right" shrink>
                                    <h2 v-show='entrega === "Domicilio"'>$
                                        {{ (totalDomicilio === "" ? 0 : totalDomicilio) }}</h2>
                                </v-flex>
                                <v-flex xs6 sm6 d-flex>
                                    <strong><h2>TOTAL A PAGAR</h2></strong>
                                </v-flex>
                                <v-flex xs6 sm6 d-flex class="text-xs-right" shrink>
                                    <strong><h2>$
                                        {{ parseInt(totalProductos) + (totalDomicilio ? parseInt(totalDomicilio) : 0) + (totalEmpaque === "" ? 0 : parseInt(totalEmpaque)) * parseInt(cantidadEmpaques) }}</h2>
                                    </strong>
                                </v-flex>
                                <v-flex xs6 sm6 d-flex v-if="pago === 'Efectivo'">
                                    <strong><h2>CAMBIO</h2></strong>
                                </v-flex>
                                <v-flex xs6 sm6 d-flex class="text-xs-right" v-if="pago === 'Efectivo'" shrink>
                                    <strong><h2>
                                        {{ Number.isInteger(totalCambio) ? '$ ' + totalCambio : totalCambio }}</h2>
                                    </strong>
                                </v-flex>
                                <v-flex xs6 sm6 d-flex>
                                    <v-btn color="success" @click="generarFactura">Generar</v-btn>
                                </v-flex>
                                <v-flex xs6 sm6 d-flex>
                                    <v-btn color="error" @click="clearData">Cancelar</v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import moment from 'moment';
import Widget from '../../../components/referencia/Widget.vue';
import {
    UserService
} from '../../../services';
import {mapGetters, mapState} from "vuex";

moment.locale('es');

export default {
    name: 'ReferenciaReporte',
    components: {},
    data: vm => ({
        deshabilitarCodigo: true,
        preload:false,
        listBodegas: [],
        dialogMenu: {
            dialog: false,
            idMenu: false,
            nameMenu: ""
        },
        productoMenu: null,
        i: [],
        ingredienteProducto: [],
        compuestoMenu: {id: null, valor: null, productos: []},
        listaIngredientes: [],
        totalProductos: 0,
        cantidadEmpaques: 0,
        totalEmpaque: "",
        totalEfectivo: 0,
        totalDomicilio: "",
        time: null,
        codigoBarras: "",
        productos: [],
        carrito: [],
        menus: [],
        estadoLector: true,
        usuario: {
            nombre: "",
            numero: "",
            tipo: ""
        },
        fechaActual: "",
        identificacion: "",
        tipoEntrega: ['Autoservicio', 'Domicilio'],
        formaPago: ["Descuento Nomina", "Efectivo","QR"],
        entrega: "Autoservicio",
        pago: "Descuento Nomina",
        nuevoProducto: {
            nombre: null,
            presentacion: null,
            codigoBarras: null,
            valor: null
        },
        headers: [{
            text: 'Codigo',
            align: 'left',
            sortable: false,
            value: 'name'
        },
            {
                text: 'Nombre',
                align: 'left',
                value: 'calories'
            },
            {
                text: 'C.Disponibles',
                value: 'fat'
            },
            {
                text: 'precio',
                value: 'fat'
            },
            {
                text: '',
                value: ''
            }
        ],
        headersFacturas: [{
            text: 'Codigo',
            align: 'left',

        },
            {
                text: 'Nombre',
                value: 'calories'
            },
            {
                text: 'Cantidad',
                value: ''
            },
            {
                text: 'Precio',
                value: ''
            },
            {
                text: '',
                value: ''
            }
        ],
        facturas: [{
            name: 'Frozen Yogurt',
            calories: 159
        },
            {
                name: 'Ice cream sandwich',
                calories: 237
            }
        ],
        itemss: [
            {header: 'Today'},
            {
                avatar: 'https://cdn.vuetifyjs.com/images/lists/1.jpg',
                title: 'CC. 1035877094',
                subtitle: "Cristian Alvarez"
            },
            {divider: true, inset: true},
        ],
        meses: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"]
    }),

    computed: {
        ...mapState(['user']),
        ...mapGetters(['fullName', 'can', 'avatar', 'UserEmail']),
        nameUser() {
            return this.fullName.substring(0, 20);
        },
        totalCambio: function () {
            let cambio = 0;
            let valorTotal = parseInt(this.totalProductos) + (this.totalDomicilio ? parseInt(this.totalDomicilio) : 0) + (this.totalEmpaque === "" ? 0 : parseInt(this.totalEmpaque)) * parseInt(this.cantidadEmpaques);
            let totalEfectivo = parseInt(this.totalEfectivo) ? parseInt(this.totalEfectivo) : 0;
            if (totalEfectivo >= valorTotal) {
                cambio = totalEfectivo - valorTotal;
            } else {
                cambio = 'Error'
            }
            return cambio;
        }
    },
    watch: {
        codigoBarras: function () {
            if (this.codigoBarras.length > 0) {
                if (this.time) {
                    clearTimeout(this.time);
                }
                this.time = setTimeout(() => this.buscarProducto(this.codigoBarras), 1000);
            }
        },
        entrega: function () {
            if (this.entrega === "Autoservicio") {
                this.totalDomicilio = "";
                this.totalEmpaque = "";
            }
        }
    },
    mounted() {
        const date = new Date();
        this.fechaActual = date.getDate() + " de " + this.meses[date.getMonth()] + " de " + date.getFullYear()
        this.getBodegasVagout();
    },
    methods: {
        getProducts(bodega) {
            axios.get('/api/productos/getProductos/'+bodega).then(res => {
                this.productos = res.data;
                this.deshabilitarCodigo = false;
                this.preload = false;
            })
        },
        buscarProducto(codigo) {
            let objIndex = this.productos.findIndex((obj => obj.codigo === codigo.toString()));
            if (objIndex > -1) {
                this.addCarrito(this.productos[objIndex]);
                this.codigoBarras = "";
            }
        },
        addCarrito(producto) {
            console.log(producto);
            this.totalProductos = parseInt(this.totalProductos) + parseInt(producto.precio_venta);
            let objIndex = this.carrito.findIndex((obj => obj.id === producto.codigo));
            if (objIndex === -1) {
                const art = {
                    id: producto.codigo,
                    nombre: producto.nombre,
                    cantidad: 1,
                    precio: producto.precio_venta,
                    idInterno: producto.id,
                    empaque: producto.requiere_empaque,
                    tipo: producto.tipo,
                    inventario: producto.inventario_id,
                    bodega: producto.vagout_bodega_id
                };
                if (producto.requiere_empaque) {
                    this.cantidadEmpaques++;
                }
                this.carrito.push(art);
            } else {
                this.carrito[objIndex].cantidad++;
                if (producto.requiere_empaque) {
                    this.cantidadEmpaques++;
                }
            }
        },
        restarCantidad(id) {
            let objIndex = this.carrito.findIndex((obj => obj.id === id));
            this.totalProductos = parseInt(this.totalProductos) - parseInt(this.carrito[objIndex].precio);
            if (this.carrito[objIndex].empaque) {
                this.cantidadEmpaques--;
            }
            this.carrito[objIndex].cantidad--;
        },
        quitarProducto(id) {
            let objIndex = this.carrito.findIndex((obj => obj.id === id));
            this.totalProductos = (parseInt(this.totalProductos) - (parseInt(this.carrito[objIndex].precio) * parseInt(this.carrito[objIndex].cantidad)));
            if (this.carrito[objIndex].empaque) {
                this.cantidadEmpaques = parseInt(this.cantidadEmpaques) - parseInt(this.carrito[objIndex].cantidad);
            }
            this.carrito.splice(objIndex, 1);
        },
        getUser() {
            if (this.identificacion == "") {
                swal({
                    title: "Debe ingresar el numero de identificación del usuario",
                    icon: "warning"
                });
                return;
            }

            axios.get('/api/user/getFindUserForDatabase/' + this.identificacion).then(res => {
                if (res.data === false) {
                    this.$alerError("El numero de documento no se encuentra en el sistema")
                    this.identificacion = "";
                } else {
                    this.pago = null;
                    this.usuario.nombre = res.data.nombre;
                    this.usuario.numero = res.data.numero;
                    this.usuario.tipo = res.data.tipo;
                    if(this.identificacion === '0000'){
                        this.formaPago = ["Efectivo","QR"];
                    }else{
                        this.formaPago = ["Descuento Nomina"];
                        this.pago = "Descuento Nomina";
                    }
                }
            })
        },
        generarFactura() {
            if(!this.pago){
                swal({
                    title: "Debe ingresar forma de pago",
                    icon: "warning"
                });
                return;
            }
            if (this.carrito.length === 0) {
                swal({
                    title: "Debe ingresar productos",
                    icon: "warning"
                });
                return;
            }

            if (this.entrega === "Domicilio" && (this.totalDomicilio === "" || parseInt(this.totalDomicilio) < 100)) {
                swal({
                    title: "El valor del domicilio debe ser mayor a 100",
                    icon: "warning"
                });
                return;
            }

            if (this.entrega === "Domicilio" && this.totalEmpaque === "") {
                swal({
                    title: "Ingrese el valor del empaque",
                    icon: "warning"
                });
                return;
            }

            if (this.usuario.numero === "") {
                swal({
                    title: "Debe ingresar un usuario",
                    icon: "warning"
                });
                return;
            }
            if (this.pago === 'Efectivo') {
                if (!Number.isInteger(this.totalCambio)) {
                    swal({
                        title: "Valor efectivo invalido",
                        icon: "warning"
                    });
                    return;
                }
            }

            swal({
                title: "Esta seguro de generar la factura?",
                icon: "warning",
                buttons: ["Cancelar", "Confirmar"],
            }).then(async validate => {
                if (validate) {
                    const data = {
                        productos: this.carrito,
                        cliente: this.usuario.numero,
                        tipoCliente: this.usuario.tipo,
                        valor: this.totalProductos,
                        valorDomicilio: this.totalDomicilio,
                        tipoEntrega: this.entrega,
                        valorEmpaque: this.totalEmpaque,
                        cantidadEmpaques: this.cantidadEmpaques,
                        formaPago: this.pago,
                        efectivo: this.totalEfectivo
                    };
                    axios.post("/api/facturas/setSaveTypeSale", data).then(res => {
                        if (res.data.type === 'error') {
                            this.$alerError(res.data.message);
                        } else {
                            this.$alerSuccess(res.data.message);
                            this.imprimir({type: 'tirillaVagout', id: res.data.factura});
                            this.clearData();
                        }
                    }).catch(e => {
                        this.$alerSuccess(res.data.message);
                        this.clearData();
                    })
                }
            })
        },
        clearData() {
            this.carrito = [];
            this.identificacion = "";
            this.usuario.nombre = "";
            this.usuario.numero = "";
            this.usuario.tipo = "";
            this.entrega = "Autoservicio";
            this.totalDomicilio = "";
            this.totalProductos = 0;
            this.totalEmpaque = "";
            this.cantidadEmpaques = 0;
            this.pago = null;
            this.totalEfectivo = null;
        },
        getMenus() {
            axios.get('/api/productos/menus').then(res => {
                this.menus = res.data;
                this.deshabilitarCodigo = false;
                this.preload = false;
            })
        },
        agregarProducto(tipo) {


            if (tipo === 'existente') {
                let objIndex = this.productos.findIndex((obj => obj.id === this.productoMenu));
                let objIndex2 = this.compuestoMenu.productos.findIndex((obj => obj.id === this.productoMenu));
                if (objIndex2 === -1) {
                    this.compuestoMenu.productos.push(this.productos[objIndex]);
                }
            } else {
                let objIndex3 = this.compuestoMenu.productos.findIndex((obj => obj.id === this.nuevoProducto.nombre));
                if (objIndex3 === -1) {
                    const cantidad = this.productos.filter((obj => obj.codigo === String(this.nuevoProducto.codigoBarras)));
                    if (cantidad.length === 0) {
                        this.compuestoMenu.productos.push({
                            id: this.nuevoProducto.nombre,
                            codigo: this.nuevoProducto.codigoBarras,
                            nombre: this.nuevoProducto.nombre,
                            presentacion: this.nuevoProducto.presentacion,
                            precio_venta: this.nuevoProducto.valor
                        });
                        this.nuevoProducto = {
                            nombre: null,
                            presentacion: null,
                            codigoBarras: null,
                            valor: null
                        }
                    } else {
                        this.$alerError('El codigo de barras ya existe')
                    }
                }
            }
        },
        getIngredientes() {
            axios.get('/api/inventario/ingredientes').then(res => {
                this.listaIngredientes = res.data;
            })
        },
        agregarIngrediente(id, index) {
            let objIndex = this.listaIngredientes.findIndex((obj => obj.id === this.ingredienteProducto[index]));
            let objIndex2 = this.i.findIndex((obj => obj.id === this.listaIngredientes[objIndex].id && obj.producto === id));
            if (objIndex2 === -1) {
                this.i.push({
                    id: this.listaIngredientes[objIndex].id,
                    nombre: this.listaIngredientes[objIndex].nombre,
                    existencia: this.listaIngredientes[objIndex].stock_actual,
                    precioUnidad: this.listaIngredientes[objIndex].precio_compra,
                    unidad: this.listaIngredientes[objIndex].unidad_medida,
                    requerida: 0,
                    producto: id
                });
                this.ingredienteProducto[index] = null;
            }
        },
        validar(value, existencias, id) {
            let objIndex = this.i.findIndex((obj => obj.id === id));
            if (parseInt(value) > parseInt(existencias)) {
                this.$alerError('La cantidad requerida debe ser menor a las existencias');
                this.i[objIndex].requerida = 0;
            }
        },
        eliminarProductoMenu(index) {
            for (var p = this.i.length - 1; p >= 0; p--) {
                if (this.i[p].producto === this.compuestoMenu.productos[index].id) {
                    this.i.splice(p, 1);
                }
            }
            this.compuestoMenu.productos.splice(index, 1);
        },
        eliminarIngredienteMenu(nombre, index) {
            for (var p = this.i.length - 1; p >= 0; p--) {
                if (this.i[p].producto === this.compuestoMenu.productos[index].id && this.i[p].nombre === nombre) {
                    this.i.splice(p, 1);
                }
            }
        },
        abrirmenu(menu) {
            axios.get('/api/productos/menu/detalle/' + menu.id).then(res => {
                this.compuestoMenu = {
                    id: res.data.menu.id,
                    valor: res.data.menu.valor,
                    productos: []
                };
                res.data.productos.forEach(s => {
                    this.compuestoMenu.productos.push(s);
                });
                res.data.ingrediente.forEach(s => {
                    this.i.push({
                        id: s.id,
                        nombre: s.nombre,
                        existencia: s.stock_actual,
                        precioUnidad: s.precio_compra,
                        unidad: s.unidad_medida,
                        requerida: parseInt(s.cantidad),
                        producto: parseInt(s.producto_id)
                    });
                });

                this.compuestoMenu.id = menu.id;
                this.compuestoMenu.valor = menu.valor;
                this.dialogMenu.idMenu = menu.id;
                this.dialogMenu.nameMenu = menu.nombre;
                this.dialogMenu.dialog = true;
            })
        },
        guardarMenu() {
            const data = {menu: this.compuestoMenu, ingredientes: this.i};
            axios.post('/api/productos/guardarMenu', data).then(res => {
                this.$alerSuccess(res.data.message);
                this.cerrarMenu();
            });
        },
        cerrarMenu() {
            this.dialogMenu = {dialog: false, idMenu: false, nameMenu: ""};
            this.compuestoMenu = {id: null, valor: null, productos: []};
            this.i = [];
        },
        agregarMenuCarrito(menu) {
            const data = {
                categoriaproducto_id: null,
                codigo: menu.id,
                id: menu.id,
                nombre: menu.nombre,
                precio_venta: String(menu.valor),
                requiere_empaque: "0",
                tipo: 'menu',
                inventario_id: null,
                vagout_bodega_id: null
            };
            this.addCarrito(data)
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
                    const w = window.open(link.href, "_blank");
                    w.print();
                    setTimeout(function () {
                        w.close();
                    }, 2000);
                })
                .catch(err => console.log(err.response));
            return;
        },
        getBodegasVagout() {
            axios.get('/api/inventario/getBodegasVagout').then(res => {
                this.listBodegas = res.data;
            })
        },
        loadProductos(e) {
            this.preload = true;
            switch (e) {
                case 1:
                    this.getProducts(e);
                    this.getMenus();
                    break;
                case 2:
                    this.getProducts(e);
                    this.menus = [];
                    break
            }

        }
    }
}
</script>
