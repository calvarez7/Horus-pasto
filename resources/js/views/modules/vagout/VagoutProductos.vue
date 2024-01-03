<template>
    <v-container fluid grid-list-md class="pa-0">
        <v-layout row wrap>
            <v-flex x12 >
                <v-card>
                    <v-toolbar flat color="white">
                        <v-toolbar-title>Productos</v-toolbar-title>
                        <v-divider class="mx-2" inset vertical></v-divider>
                        <v-spacer></v-spacer>
                        <v-btn color="success" dark class="mb-2" @click="dialogProduc=true">Nuevo Producto</v-btn>
                        <v-dialog v-model="dialogProduc" max-width="800px" persistent>
                            <v-card>
                                <form @submit.prevent="createProducto">
                                    <v-toolbar flat dark color="primary">
                                        <v-toolbar-title>Nuevo Productos</v-toolbar-title>
                                        <v-spacer></v-spacer>
                                    </v-toolbar>
                                    <v-container grid-list-md>
                                        <v-layout wrap>
                                            <v-flex xs12 sm12 md12>
                                                <v-text-field v-model="produc.codigo_barras" label="Codigo Barras"
                                                              v-if="dialogProduc" required autofocus></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm6 md4>
                                                <v-autocomplete label="Categoria*" :items="allcategory" item-text="nombre"
                                                                item-value="id" v-model="produc.categoriaproducto_id" required>
                                                </v-autocomplete>
                                            </v-flex>
                                            <v-flex xs12 sm6 md4>
                                                <v-text-field v-model="produc.nombre" label="nombre" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm6 md4>
                                                <v-text-field v-model="produc.descripcion" label="descripcion" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm6 md4>
                                                <v-text-field  v-model="produc.presentacion" label="presentacion" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm6 md4>
                                                <v-autocomplete label="Bodega" :items="listBodegas" item-text="nombre"
                                                                item-value="id" v-model="produc.vagout_bodega_id" required>
                                                </v-autocomplete>
                                            </v-flex>
                                            <v-flex xs12 sm6 md4>
                                                <v-switch v-model="produc.requiere_empaque" color="success"
                                                          label="Requiere Empaque"></v-switch>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-divider></v-divider>
                                            </v-flex>
<!--                                            <v-flex xs6>-->
<!--                                                <v-text-field readonly type="number" v-model="valorPreparacion" box label="Valor ingredientes" required></v-text-field>-->
<!--                                            </v-flex>-->
                                            <v-flex xs12>
                                                <v-text-field type="number" v-model="produc.valor" outline label="Valor" required></v-text-field>
                                            </v-flex>
<!--                                            <v-flex xs6>-->
<!--                                                <v-text-field type="number" v-model="produc.adicional" outline label="Valor Adicional" @input="calcularProducto" required></v-text-field>-->
<!--                                            </v-flex>-->

<!--                                            <v-flex xs12>-->
<!--                                                <v-text-field readonly type="number" v-model="produc.valor" outline label="precio venta" required></v-text-field>-->
<!--                                            </v-flex>-->

<!--                                            <v-flex xs12 sm6 md6>-->
<!--                                                <v-autocomplete v-model="ingrediente" label="Ingrediente" :items="ingredientes" item-text="nombre" return-object></v-autocomplete>-->
<!--                                            </v-flex>-->
<!--                                            <v-flex xs12 sm4 md4>-->
<!--                                                <v-text-field type="number" v-model="cantidad" :label="'Cantidad ('+(ingrediente?ingrediente.unidad_medida:'')+')'"></v-text-field>-->
<!--                                            </v-flex>-->
<!--                                            <v-flex xs12 sm2 md2>-->
<!--                                                <v-btn color="success" @click="agregarIngrediente" round>Agregar</v-btn>-->
<!--                                            </v-flex>-->
<!--                                            <v-flex xs12 >-->
<!--                                                <v-data-table :headers="headerIngredientes" :items="ingredientesProductos" hide-actions-->
<!--                                                              class="elevation-1" pagination.sync="pagination" item-key="id" loading="true">-->
<!--                                                    <template v-slot:items="props">-->
<!--                                                        <td class="text-xs-left">{{ props.item.nombre }}</td>-->
<!--                                                        <td class="text-xs-left">{{ props.item.cantidad }}</td>-->
<!--                                                        <td class="text-xs-center">$ {{ props.item.precio_compra }}</td>-->
<!--                                                        <td class="text-xs-center">-->
<!--                                                            <v-btn fab dark small color="error">-->
<!--                                                            <v-icon dark @click="eliminarIngrediente(props.item.id)">remove</v-icon>-->
<!--                                                        </v-btn>-->
<!--                                                        </td>-->

<!--                                                        <td></td>-->

<!--                                                    </template>-->
<!--                                                    <v-alert v-slot:no-data :value="true" color="error" icon="warning">No hay datos.-->
<!--                                                    </v-alert>-->
<!--                                                </v-data-table>-->
<!--                                            </v-flex>-->

                                        </v-layout>
                                    </v-container>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="error" @click="clearFormNote">Cancelar</v-btn>
                                        <v-btn color="success" type="submit">Guardar</v-btn>
                                    </v-card-actions>
                                </form>
                            </v-card>
                        </v-dialog>
                    </v-toolbar>
                    <v-card-title>
                        <v-flex xs4>
                            <v-select
                                :items="bodegas"
                                label="Seleccionar Bodega"
                                v-model="form.bodega"
                            ></v-select>
                        </v-flex>
                        <v-flex xs4>
                            <v-btn color="success" @click="filtrarBodega">Filtrar</v-btn>
                        </v-flex>
                        <v-spacer></v-spacer>
                        <v-text-field
                            v-model="search"
                            append-icon="search"
                            label="Search"
                            single-line
                            hide-details
                        ></v-text-field>
                        </v-card-title>
                        <v-data-table
                        :headers="products"
                        :items="productos"
                        :search="search"
                        >
                        <template v-slot:items="props">
                            <td>{{ props.item.id }}</td>
                            <td>{{ props.item.codigo_barras }}</td>
                            <td>{{ props.item.nombre }}</td>
                            <td>{{ props.item.presentacion }}</td>
                            <td>{{ props.item.nombrecategoria }}</td>
                            <td>{{ props.item.bodega }}</td>
                            <td ><v-chip v-if="props.item.requiere_empaque == 1" color="info" text-color="white">SI</v-chip><v-chip v-else color="error" text-color="white">No</v-chip></td>
                            <td><strong>$ {{ props.item.valor}}</strong></td>
                            <td class="text-xs">
                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-btn text icon color="warning" dark v-on="on">
                                            <v-icon @click="getproducto(props.item)">edit
                                            </v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Editar</span>
                                </v-tooltip>
                            </td>
                        </template>
                        <template v-slot:no-results>
                            <v-alert :value="true" color="error" icon="warning">
                            Your search for "{{ search }}" found no results.
                            </v-alert>
                        </template>
                        </v-data-table>
                </v-card>
            </v-flex>
            <v-toolbar flat color="white">
            <v-dialog persistent v-model="productoModal" max-width="600px">
                <v-toolbar flat dark color="primary">
                    <v-toolbar-title>Editar Producto</v-toolbar-title>
                    <v-spacer></v-spacer>
                </v-toolbar>
                <v-card>
                    <v-card-text>
                        <form>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12>
                                    <v-text-field v-model="produc.codigo_barras" label="Codigo Barras"
                                                  ></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-autocomplete label="Categoria*" :items="allcategory" item-text="nombre"
                                                    item-value="id" v-model="produc.categoriaproducto_id" required>
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-text-field v-model="produc.nombre" label="nombre"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-text-field v-model="produc.descripcion" label="descripcion"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-text-field  v-model="produc.presentacion" label="presentacion"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-switch v-model="produc.requiere_empaque" color="success"
                                              label="Requiere Empaque"></v-switch>
                                </v-flex>
<!--                                <v-flex xs6>-->
<!--                                    <v-text-field readonly type="number" v-model="valorPreparacion" box label="Valor ingredientes" required></v-text-field>-->
<!--                                </v-flex>-->
                                <v-flex xs12>
                                    <v-text-field type="number" v-model="produc.valor" outline label="Valor" required></v-text-field>
                                </v-flex>

<!--                                <v-flex xs6>-->
<!--                                    <v-text-field type="number" v-model="produc.adicional" outline label="Valor Adicional" @input="calcularProducto" required></v-text-field>-->
<!--                                </v-flex>-->
<!--                                <v-flex xs12>-->
<!--                                    <v-text-field readonly type="number" v-model="produc.valor" outline label="precio venta" required></v-text-field>-->
<!--                                </v-flex>-->
<!--                                <v-flex xs6>-->
<!--                                    <v-autocomplete v-model="ingrediente" label="Ingrediente" :items="ingredientes" item-text="nombre" return-object></v-autocomplete>-->
<!--                                </v-flex>-->
<!--                                <v-flex xs4>-->
<!--                                    <v-text-field type="number" v-model="cantidad" :label="'Cantidad ('+(ingrediente?ingrediente.unidad_medida:'')+')'"></v-text-field>-->
<!--                                </v-flex>-->
<!--                                <v-flex xs12 sm2 md2>-->
<!--                                    <v-btn color="success" @click="agregarIngrediente" round>Agregar</v-btn>-->
<!--                                </v-flex>-->
<!--                                <v-flex xs12 >-->
<!--                                    <v-data-table :headers="headerIngredientes" :items="ingredientesProductos" hide-actions-->
<!--                                                  class="elevation-1" pagination.sync="pagination" item-key="id" loading="true">-->
<!--                                        <template v-slot:items="props">-->
<!--                                            <td class="text-xs-left">{{ props.item.nombre }}</td>-->
<!--                                            <td class="text-xs-left">{{ props.item.cantidad }}</td>-->
<!--                                            <td class="text-xs-center">$ {{ props.item.precio_compra }}</td>-->
<!--                                            <td class="text-xs-center">-->
<!--                                                <v-btn fab dark small color="error">-->
<!--                                                    <v-icon dark @click="eliminarIngrediente(props.item.id)">remove</v-icon>-->
<!--                                                </v-btn>-->
<!--                                            </td>-->

<!--                                            <td></td>-->

<!--                                        </template>-->
<!--                                        <v-alert v-slot:no-data :value="true" color="error" icon="warning">No hay datos.-->
<!--                                        </v-alert>-->
<!--                                    </v-data-table>-->
<!--                                </v-flex>-->
                            </v-layout>
                        </v-container>
                        </form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="red dark" dark  @click="clearFormNote">Cancelar</v-btn>
                        <v-btn color="success" dark @click="updateProducto()">Actualizar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            </v-toolbar>
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        name: 'vagoutProductos',
        components: {},
        data() {
            return {
                form: {
                    bodega: 'Todas',
                },
                bodegas:['Todas','Restaurante','Cafeteria'],
                listBodegas:[],
                search: '',
                save:'',
                productoModal: false,
                categoriaModal: false,
                productos: [],
                ingredientes: [],
                allcategory: [],
                dialogProduc: false,
                dialogCategory: false,
                ingrediente: null,
                ingredientesProductos:[],
                ingredientesProductosEdit:[],
                cantidad: null,
                headerIngredientes: [{
                        text: 'Nombre',
                        value: 'nombre'
                    },
                    {
                        text: 'Cantidad',
                        value: 'descripcion'
                    },
                    {
                        text: 'Valor',
                        value: 'descripcion'
                    },{
                        text: '',
                        value: 'descripcion'
                    }],
                categorias: [{
                        text: 'id',
                        value: 'id'
                    },
                    {
                        text: 'nombre',
                        value: 'nombre'
                    },
                    {
                        text: 'descripcion',
                        value: 'descripcion'
                    },
                    {
                        text: 'Acción'
                    }
                ],
                products: [
                    {
                        text: 'id',
                        value: 'id'
                    },
                    {
                        text: 'codigo Barras',
                        value: 'codigo_barras'
                    },
                    {
                        text: 'nombre',
                        value: 'nombre'
                    },
                    {
                        text: 'presentacion',
                        value: 'presentacion'
                    },
                    {
                        text: 'Tipo Categoria',
                        value: 'nombrecategoria'
                    },
                    {
                        text: 'Bodega',
                        value: 'bodega'
                    },
                    {
                        text: 'empaque',
                        value: 'requiere_empaque'
                    },
                    {
                        text: 'Precio Venta',
                        value: ''
                    },
                    {
                        text: '',
                        value: ''
                    },
                ],
                productoId: '',
                categoryId: '',
                category: {
                    nombre: '',
                    descripcion: '',
                },
                lisProducto:[],
                produc: {
                    codigo_barras:'',
                    categoriaproducto_id: '',
                    nombre: '',
                    descripcion: '',
                    presentacion: "",
                    imagen: '',
                    requiere_empaque: false,
                    adicional: 0,
                    valor:0,
                    vagout_bodega_id:null
                },
                defaultItem: {
                    categoriaproducto_id: '',
                    nombre: '',
                    descripcion: '',
                    presentacion: '',
                    fecha_vencimiento: '',
                    imagen: ''
                },
                valorPreparacion: 0,
            }

        },
        mounted() {
            this.allCategory();
            this.allProduct();
            // this.allIngredientes();
            this.getBodegasVagout();
        },
        methods: {
            updateProducto(){
                if(parseInt(this.produc.valor) <= 0){
                    this.$alerError("Debe ingresar un valor superior a 0");
                    return;
                }
                const data = {
                    p: this.produc,
                };
                axios.put('/api/productos/edit/'+ this.productoId, data).then(res => {
                    this.allProduct();
                    this.clearFormNote();
                    this.productoModal=false;
                    swal({
                        title: "¡Producto Actualizado con exito!",
                        text: " ",
                        timer: 2000,
                        icon: "success",
                        buttons: false
                    });
                });
            },
            updateCategory(){
                axios.put('/api/categoriaprodutos/edit/'+ this.categoryId,this.category).then(res => {
                    this.allCategory();
                    this.categoriaModal=false;
                    swal({
                        title: "¡Categoria Actualizada con exito!",
                        text: " ",
                        timer: 2000,
                        icon: "success",
                        buttons: false
                    });
                });
            },
            getcategory(item){
                this.categoryId = item.id;
                this.category.nombre = item.nombre;
                this.category.descripcion = item.descripcion;
                this.categoriaModal=true;
            },
            async getproducto(item){
                const ingredientes = await axios.get('/api/productos/ingredientes/'+item.id);
                console.log(item);
                let valor = 0;
                this.ingredientesProductos = ingredientes.data;
                 this.ingredientesProductos.forEach(s => {
                     valor = valor+(parseFloat(s.precio_compra)*parseInt(s.cantidad))
                 });
                this.valorPreparacion = valor;
                this.productoId = item.id;
                this.produc.codigo_barras = item.codigo_barras;
                this.produc.categoriaproducto_id = parseInt(item.categoriaproducto_id);
                 this.produc.nombre = item.nombre;
                 this.produc.descripcion = item.descripcion;
                 this.produc.presentacion = item.presentacion;
                this.produc.requiere_empaque = parseInt(item.requiere_empaque);
                this.produc.valor = parseInt(item.valor);
                this.productoModal=true;
                // this.calcularProducto()
            },
            createCateory() {
                axios.post('/api/categoriaprodutos/create', this.category)
                    .then(res => {
                        this.dialogCategory = false;
                        this.allCategory();
                        swal({
                        title: "¡Categoria Creada con exito!",
                        text: " ",
                        timer: 2000,
                        icon: "success",
                        buttons: false
                    });
                    }).catch(err => console.log(err.response.data))
            },
            createProducto() {
                if(parseInt(this.produc.valor) <= 0){
                    this.$alerError("Debe ingresar un valor superior a 0");
                    return;
                }
                const data = {
                    p: this.produc,
                };
                axios.post('/api/productos/create', data)
                    .then(res => {
                        this.dialogProduc = false;
                        this.allProduct();
                        this.clearFormNote();
                        swal({
                        title: "¡Producto Creado con exito!",
                        text: " ",
                        timer: 2000,
                        icon: "success",
                        buttons: false
                    });
                    }).catch(err => {
                        this.$alerError(err.response.data.message)
                })
            },
            allCategory() {
                axios.get('/api/categoriaprodutos/allCategory')
                    .then(res => {
                        this.allcategory = res.data;
                    }).catch(err => console.log(err.response.data))
            },
            allProduct() {
                axios.get('/api/productos/allProduct')
                    .then(res => {
                        console.log(res.data);
                        this.productos = res.data;
                        this.lisProductos = res.data;
                    }).catch(err => console.log(err.response.data))
            },
            clearFormNote(){
                for (const prop of Object.getOwnPropertyNames(this.produc)) {
                    this.produc[prop] = "";
                };
                for (const prop of Object.getOwnPropertyNames(this.category)) {
                    this.category[prop] = "";
                }
                this.ingredientesProductos = [];
                this.dialogProduc = false;
                this.productoModal = false;
                this.valorPreparacion = 0;
                this.produc.valor = 0;
                this.produc.adicional = 0;

            },
            allIngredientes(){
                    axios.get('/api/inventario/ingredientes').then(res => {
                        this.ingredientes = res.data;
                        console.log('ingredientes',this.ingredientes);
                    })
            },
            agregarIngrediente(){
                if(!this.ingrediente){
                    this.$alerError('Debe selecciona un ingrediente');
                    return;
                }
                if(!this.cantidad){
                    this.$alerError('Debe ingresa una cantidad');
                    return;
                }
                this.ingrediente.cantidad = this.cantidad;
                this.ingredientesProductos.push(this.ingrediente);
                const valor =  (parseFloat(this.valorPreparacion)+(parseFloat(this.ingrediente.precio_compra)*parseInt(this.cantidad)));
                this.valorPreparacion = valor.toFixed(2);
                this.ingrediente = null;
                this.cantidad = null;
                this.calcularProducto()
            },
            async eliminarIngrediente(id){
                let objIndex = this.ingredientesProductos.findIndex((obj => obj.id === id));
                const valor = parseFloat( this.valorPreparacion) - (parseFloat(this.ingredientesProductos[objIndex].precio_compra)*parseInt(this.ingredientesProductos[objIndex].cantidad));
                this.valorPreparacion = valor.toFixed(2);
                this.ingredientesProductos.splice(objIndex, 1);
                this.calcularProducto();

            },
            calcularProducto(){
                const adicional = (this.produc.adicional?parseInt(this.produc.adicional):0);
                const valorPreparacion = parseInt(this.valorPreparacion);
                this.produc.valor = adicional+valorPreparacion;
            },
            getBodegasVagout(){
                axios.get('/api/inventario/getBodegasVagout').then(res => {
                    this.listBodegas = res.data;
                })
            },
            filtrarBodega(){
                this.preload = true;
                if(this.form.bodega === 'Restaurante'){
                    this.productos = this.lisProductos.filter(ingrediente => ingrediente.bodega === 'Restaurante')
                }else if(this.form.bodega === 'Cafeteria'){
                    this.productos = this.lisProductos.filter(ingrediente => ingrediente.bodega === 'Cafeteria')
                }else{
                    this.productos = this.lisProductos;
                }
                this.preload = false;
            },
        }
    }

</script>
