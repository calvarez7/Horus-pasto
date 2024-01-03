<template>
    <v-layout row wrap>
        <template>
            <div class="text-center">
                <v-dialog v-model="dialogErrorInventario" width="500">
                    <v-card>
                        <v-card-title class="headline grey lighten-2" primary-title>
                            Error en el archivo
                        </v-card-title>


                        <v-card-text>
                            <div v-for="errorsInventarios in errorsInventario" :key="errorsInventarios">
                                <v-alert :value="true" type="error">
                                    {{ errorsInventarios }}
                                </v-alert>
                            </div>

                        </v-card-text>

                        <v-divider></v-divider>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" flat @click="closeModal">
                                ACEPTAR
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
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
        <v-flex xs4>
            <v-select :items="bodegas" label="Seleccionar Bodega" v-model="form.bodega"></v-select>
        </v-flex>
        <v-flex xs4>
            <v-btn color="success" @click="filtrarBodega">Filtrar</v-btn>
        </v-flex>
        <v-toolbar flat color="white">
            <v-toolbar-title>INGREDIENTES</v-toolbar-title>
            <v-divider class="mx-2" inset vertical></v-divider>
            <v-spacer></v-spacer>

            <!--            <v-dialog max-width="900px" persistent v-model="dialogTraslado">-->
            <!--                <template v-slot:activator="{ on }">-->
            <!--                    <v-btn color="success" dark v-on="on">Traslado Bodega</v-btn>-->
            <!--                </template>-->
            <!--                <v-card>-->
            <!--                    <v-form ref="form">-->
            <!--                        <v-card-title>Descargue por Consumo</v-card-title>-->
            <!--                        <v-card-text>-->

            <!--                            <v-container>-->
            <!--                                <v-layout>-->
            <!--                                    <v-flex xs12 md6>-->
            <!--                                        <v-autocomplete v-model="form.bodegaOrigen" outline label="Bodega Origen" :items="['Restaurante','Cafeteria']" @change="bodegasTraslado">-->
            <!--                                        </v-autocomplete>-->
            <!--                                    </v-flex>-->
            <!--                                    <v-flex xs12 md6>-->
            <!--                                        <v-autocomplete v-model="form.bodegaDestino" outline label="Bodega Destino" :items="['Restaurante','Cafeteria']" disabled>-->
            <!--                                        </v-autocomplete>-->
            <!--                                    </v-flex>-->
            <!--                                </v-layout>-->
            <!--                            </v-container>-->
            <!--                            <v-divider></v-divider>-->

            <!--                            <v-container>-->
            <!--                                <v-layout>-->
            <!--                                    <v-flex xs12 md4>-->
            <!--                                        <v-autocomplete :items="form.ingredienteBodega" v-model="form.ingredienteTraslado" item-text="nombre"  label="Ingredientes" >-->
            <!--                                        </v-autocomplete>-->
            <!--                                    </v-flex>-->
            <!--                                    <v-flex xs12 md4>-->
            <!--                                        <v-text-field label="Cantidad Disponible"></v-text-field>-->
            <!--                                    </v-flex>-->
            <!--                                </v-layout>-->
            <!--                            </v-container>-->

            <!--                        </v-card-text>-->
            <!--                        <v-card-actions>-->
            <!--                            <VSpacer />-->
            <!--                            <v-btn color="primary" flat>-->
            <!--                                Cerrar-->
            <!--                            </v-btn>-->
            <!--                            <v-btn color="info">-->
            <!--                                Cargar-->
            <!--                                <template v-slot:loader>-->
            <!--                            <span class="custom-loader">-->
            <!--                                <v-icon light>cached</v-icon>-->
            <!--                            </span>-->
            <!--                                </template>-->
            <!--                            </v-btn>-->
            <!--                        </v-card-actions>-->
            <!--                    </v-form>-->
            <!--                </v-card>-->
            <!--            </v-dialog>-->

            <v-dialog max-width="900px" persistent v-model="dialogDescargue">
                <template v-slot:activator="{ on }">
                    <v-btn color="warning" dark v-on="on">Descargue por Consumo</v-btn>
                </template>
                <v-card>
                    <v-form ref="form">
                        <v-card-title>Descargue por Consumo</v-card-title>
                        <v-card-text>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-img height="300" width="650" max-height="60"
                                        src="/images/ejemplo_descargue_vagout.PNG"></v-img>
                                </v-flex>
                                <v-flex xs12>
                                    <br>
                                    <v-divider></v-divider>
                                    <br>
                                </v-flex>
                                <v-flex xs3>
                                    <v-btn color="primary" dark outline round @click="uploadFilesDescargue">
                                        <v-icon left>backup</v-icon>
                                        Subir archivos
                                    </v-btn>
                                </v-flex>
                                <v-flex xs9 px-2>
                                    <input hidden name="fileDescargue" ref="filesDescargue" type="file"
                                        @change="setNameDescargue" />
                                    <VTextField disabled name="name"
                                        :rules="[v => !!v || 'Se necesitan cargar archivos para validar']" single-line
                                        v-model="namefile" @click="uploadFilesDescargue" />
                                </v-flex>
                            </v-layout>
                        </v-card-text>
                        <v-card-actions>
                            <VSpacer />
                            <v-btn color="primary" flat @click="closeModal">
                                Cerrar
                            </v-btn>
                            <v-btn color="info" @click="cargarDescargue">
                                Cargar
                                <template v-slot:loader>
                                    <span class="custom-loader">
                                        <v-icon light>cached</v-icon>
                                    </span>
                                </template>
                            </v-btn>
                        </v-card-actions>
                    </v-form>

                </v-card>
            </v-dialog>
            <v-dialog max-width="900px" persistent v-model="dialogEntrada">
                <template v-slot:activator="{ on }">
                    <v-btn color="primary" dark v-on="on">Cargue de Compras</v-btn>
                </template>
                <v-card>
                    <v-form ref="form">
                        <v-card-title>Cargue de Compras</v-card-title>
                        <v-card-text>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-img height="300" width="650" max-height="60"
                                        src="/images/ejemplo_ingresos_vagout.PNG"></v-img>
                                </v-flex>
                                <v-flex xs12>
                                    <br>
                                    <v-divider></v-divider>
                                    <br>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-select :items="listBodegas" item-value="id" item-text="nombre"
                                        v-model="bodegaCarga" label="Bodega" autocomplete></v-select>
                                </v-flex>
                                <v-flex xs3>
                                    <v-btn color="primary" dark outline round @click="uploadFiles">
                                        <v-icon left>backup</v-icon>
                                        Subir archivos
                                    </v-btn>
                                </v-flex>
                                <v-flex xs9 px-2>
                                    <input hidden name="file" ref="files" type="file" @change="setName" />
                                    <VTextField disabled name="name"
                                        :rules="[v => !!v || 'Se necesitan cargar archivos para validar']" single-line
                                        v-model="namefile" @click="uploadFiles" />
                                </v-flex>
                            </v-layout>
                        </v-card-text>
                        <v-card-actions>
                            <VSpacer />
                            <v-btn color="primary" flat @click="closeModal">
                                Cerrar
                            </v-btn>
                            <v-btn color="info" @click="cargar">
                                Cargar
                                <template v-slot:loader>
                                    <span class="custom-loader">
                                        <v-icon light>cached</v-icon>
                                    </span>
                                </template>
                            </v-btn>
                        </v-card-actions>
                    </v-form>
                </v-card>
            </v-dialog>

            <!-- CARGUE INVENTARIO  -->


            <v-dialog max-width="900px" persistent v-model="dialogoInventario">
                <template v-slot:activator="{ on }">
                    <v-btn color="red" dark v-on="on">CARGUE INVENTARIO</v-btn>
                </template>
                <v-card>
                    <v-form ref="form">
                        <v-card-title>Cargue inventario</v-card-title>
                        <v-card-text>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-img height="200" width="550" max-height="60" src="/images/inventariocargue.png">
                                    </v-img>
                                </v-flex>
                                <v-flex xs12>
                                    <br>
                                    <v-divider></v-divider>
                                    <br>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-select :items="listBodegas" item-value="id" item-text="nombre"
                                        v-model="bodegaCarga" label="Bodega" autocomplete></v-select>
                                </v-flex>
                                <v-flex xs3>
                                    <v-btn color="primary" v-model="botonsubir" dark outline round
                                        @click="uploadFilesinventario">
                                        <v-icon left>backup</v-icon>
                                        Subir archivos
                                    </v-btn>
                                </v-flex>
                                <v-flex xs9 px-2>
                                    <input hidden name="fileInventario" ref="filesInventario" type="file"
                                        @change="setNameInventario" />
                                    <VTextField disabled name="name"
                                        :rules="[v => !!v || 'Se necesitan cargar archivos para validar']" single-line
                                        v-model="namefile" @click="uploadFilesinventario" />
                                </v-flex>
                            </v-layout>
                        </v-card-text>
                        <v-card-actions>
                            <VSpacer />
                            <v-btn color="primary" flat @click="closeModal">
                                Cerrar
                            </v-btn>
                            <v-btn color="info" @click="cargarInventario">
                                Cargar
                                <template v-slot:loader>
                                    <span class="custom-loader">
                                        <v-icon light>cached</v-icon>
                                    </span>
                                </template>
                            </v-btn>

                        </v-card-actions>
                    </v-form>
                </v-card>
            </v-dialog>

            <v-dialog v-model="dialogError" width="1200px">
                <v-card>
                    <v-card-title>
                        <v-alert :value="true" type="error"><strong>Error en el descargue</strong>: Los
                            siguientes
                            productos no tienen saldo en bodega</v-alert>
                    </v-card-title>
                    <v-card-text>
                        <template>
                            <v-data-table :items="itemsErrors" class="elevation-1" hide-actions>
                                <template v-slot:headers="props">
                                    <tr>
                                        <th><strong>CICLO DE MENÚ</strong></th>
                                        <th><strong>TIPO DE PLATO</strong></th>
                                        <th><strong>NOMBRE</strong></th>
                                        <th><strong>INGREDIENTES</strong></th>
                                        <th><strong>Cantidad</strong></th>
                                        <th><strong>Unidad de medida</strong></th>
                                        <th><strong>Fecha</strong></th>


                                    </tr>
                                </template>
                                <template v-slot:items="props">
                                    <td>{{ props.item['CICLO DE MENÚ'] }}</td>
                                    <td>{{ props.item['TIPO DE PLATO'] }}</td>
                                    <td>{{ props.item['NOMBRE'] }}</td>
                                    <td>{{ props.item['INGREDIENTES'] }}</td>
                                    <td>{{ props.item['Cantidad'] }}</td>
                                    <td>{{ props.item['Unidad de medida'] }}</td>
                                    <td>{{ props.item.Fecha.date }}</td>

                                </template>
                            </v-data-table>
                        </template>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" dark @click="dialogError = false;itemsErrors = []">Cerrar
                        </v-btn>
                        <!--                        <v-btn color="green darken-1" flat="flat" @click="dialog = false">Agree</v-btn>-->
                    </v-card-actions>
                </v-card>
            </v-dialog>



<!--                        <v-dialog v-model="dialogEntrada" fullscreen hide-overlay transition="dialog-bottom-transition">-->
<!--                            <template v-slot:activator="{ on }">-->
<!--                                <v-btn color="primary" dark v-on="on">Nueva Entrada</v-btn>-->
<!--                            </template>-->
<!--                            <v-card>-->
<!--                                <v-toolbar dark color="primary">-->
<!--                                    <v-btn icon dark @click="close">-->
<!--                                        <v-icon>close</v-icon>-->
<!--                                    </v-btn>-->
<!--                                    <v-toolbar-title>Entrada Inventario</v-toolbar-title>-->
<!--                                    <v-spacer></v-spacer>-->
<!--                                </v-toolbar>-->
<!--                                <v-card-text>-->
<!--                                    <v-form ref="form">-->
<!--                                        <v-container fluid pa-0>-->
<!--                                            <v-layout row wrap>-->
<!--                                                <v-flex xs4>-->
<!--                                                    <v-btn color="primary" dark outline round @click="uploadFiles">-->
<!--                                                        <v-icon left>backup</v-icon>-->
<!--                                                        Subir archivos-->
<!--                                                    </v-btn>-->
<!--                                                    <input hidden multiple name="file" ref="files" type="file" @change="setName"/>-->
<!--                                                    <VTextField disabled name="name"-->
<!--                                                                :rules="[v => !!v || 'Se necesitan cargar archivos para validar']" single-line-->
<!--                                                                v-model="namefile" @click="uploadFiles"/>-->
<!--                                                    <v-btn color="success" @click="cargar">Cargar</v-btn>-->
<!--                                                </v-flex>-->
<!--                                                <v-flex xs8 md2>-->
<!--                                                    <v-container fluid pa-0>-->
<!--                                                        <v-layout>-->
<!--                                                            <v-flex xs12>-->
<!--                                                                <v-img  height="420" width="1200" max-height="260"-->

<!--                                                                        src="/images/ejemplo_ingresos_vagout.PNG"></v-img>-->
<!--                                                            </v-flex>-->
<!--                                                        </v-layout>-->
<!--                                                    </v-container>-->

<!--                                                    </v-flex>-->
<!--                                            </v-layout>-->
<!--                                        </v-container>-->

<!--                                        <template v-for="error in noRegistra">-->
<!--                                                <v-flex xs12 px-12>-->
<!--                                                <v-alert-->
<!--                                                    :value="true"-->
<!--                                                    type="error"-->
<!--                                                >-->
<!--                                                    El ingrediente "{{error.Nombre}}" no registra en inventario.-->
<!--                                                </v-alert>-->
<!--                                                </v-flex>-->
<!--                                        </template>-->



<!--                                    </v-form>-->
<!--                                </v-card-text>-->
<!--                            </v-card>-->
<!--                        </v-dialog>-->

            <v-dialog v-model="dialog" max-width="500px" persistent>
                <template v-slot:activator="{ on }">
                    <!--                    <v-btn color="success" dark class="mb-2" v-on="on">Nuevo Ingrediente</v-btn>-->
                </template>
                <v-card>
                    <form @submit.prevent="save">
                        <v-card-title>
                            <span class="headline">Nuevo Ingrediente</span>
                        </v-card-title>
                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs12>
                                        <v-text-field v-model="ingrediente.nombre" label="Nombre" required>
                                        </v-text-field>
                                    </v-flex>

                                    <v-flex xs12>
                                        <v-select :items="unidades" label="Unidad" v-model="ingrediente.unidad_medida"
                                            required></v-select>
                                    </v-flex>

                                    <v-flex xs6>
                                        <v-text-field type="number" v-model="ingrediente.stock_actual"
                                            :label="'Cantidad en ('+(ingrediente.unidad_medida?ingrediente.unidad_medida:'')+')'"
                                            required></v-text-field>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-text-field type="number" v-model="ingrediente.total_inventario"
                                            label="Precio" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-autocomplete label="Bodega" v-model="ingrediente.vagout_bodega_id"
                                            :items="listBodegas" item-text="nombre" item-value="id"></v-autocomplete>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="error" persistent @click="close">Cerrar</v-btn>
                            <v-btn color="success" type="submit">Guardar</v-btn>
                        </v-card-actions>
                    </form>
                </v-card>
            </v-dialog>
        </v-toolbar>
        <v-spacer></v-spacer>

        <template>
            <v-layout wrap>
                <v-flex>
                    <v-card>
                        <v-card-title>
                            <v-text-field v-model="search" append-icon="search" label="Buscar ..." single-line
                                hide-details></v-text-field>
                        </v-card-title>
                        <v-data-table :headers="headers" :items="listaIngredientes" class="elevation-1"
                            :search="search">
                            <template v-slot:items="props">
                                <td>{{ props.item.nombre }}</td>
                                <td> {{ props.item.stock_actual }} {{props.item.unidad_medida}}</td>
                                <td>{{ props.item.precio_promedio | pesoFormat }}</td>
                                <td>{{ props.item.total_inventario | pesoFormat }}</td>
                                <td>{{ props.item.bodega }}</td>
                                <td class="text-xs">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn text icon color="warning" dark v-on="on">
                                                <v-icon @click="abrirAjuste(props.item)">edit
                                                </v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Editar</span>
                                    </v-tooltip>
                                </td>
                            </template>
                            <template v-slot:no-data>
                                <v-btn color="primary" @click="listarIngredientes">Reset</v-btn>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-flex>
                <v-toolbar flat color="white">
                    <v-dialog persistent v-model="InventarioModal" max-width="600px">
                        <v-toolbar flat dark color="primary">
                            <v-toolbar-title>Ajuste de producto de inventario</v-toolbar-title>
                            <v-spacer></v-spacer>
                        </v-toolbar>
                        <v-card>
                            <v-card-text>
                                <form>
                                    <v-container grid-list-md>
                                        <v-layout wrap>
                                            <v-flex xs12 sm6 md6>
                                                <v-select :items="tipoInventario" label="Selecciona tipo ajuste"
                                                    v-model="tipoInvetarios"></v-select>
                                            </v-flex>
                                            <v-flex xs12 sm6 md6>
                                                <v-text-field v-model="cantAjuste" label="Cantidad" type="number">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm12 md12>
                                                <v-textarea v-model="descripcionAjuste" label="Descripcion"></v-textarea>
                                            </v-flex>
                                            <!-- <v-flex xs4>
                                                <v-btn color="success" @click="filtrarBodega">Filtrar</v-btn>
                                            </v-flex>
                                            <v-flex xs12 sm6 md4>
                                                <v-text-field v-model="inventario.nombre" label="Nombre" readonly>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm6 md4>
                                                <v-text-field v-model="inventario.stock_actual" label="Cantidad"
                                                    type="number"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm6 md4>
                                                <v-text-field v-model="inventario.precio_promedio"
                                                    label="Precio Promedio" type="number"></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm6 md4>
                                                <v-text-field v-model="inventario.total_inventario" label="Valor Total"
                                                    type="number"></v-text-field>
                                            </v-flex> -->
                                        </v-layout>
                                    </v-container>
                                </form>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="red dark" dark @click="closeModal">Cancelar</v-btn>
                                <v-btn color="success" dark @click="ajusteInventario">Confirmar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
            </v-layout>
        </template>
    </v-layout>
</template>

<style>
    .custom-loader {
        animation: loader 1s infinite;
        display: flex;
    }

    @-moz-keyframes loader {
        from {
            transform: rotate(0);
        }

        to {
            transform: rotate(360deg);
        }
    }

    @-webkit-keyframes loader {
        from {
            transform: rotate(0);
        }

        to {
            transform: rotate(360deg);
        }
    }

    @-o-keyframes loader {
        from {
            transform: rotate(0);
        }

        to {
            transform: rotate(360deg);
        }
    }

    @keyframes loader {
        from {
            transform: rotate(0);
        }

        to {
            transform: rotate(360deg);
        }
    }

</style>
<script>
    export default {
        data: () => ({
            descripcionAjuste:null,
            errorsInventario: [],
            dialogErrorInventario: false,
            itemsErrors: [],
            form: {
                bodega: 'Todas',
                bodegaOrigen: null,
                bodegaDestino: null,
                ingredienteBodega: [],
                ingredienteTraslado: null,
                cantidadTraslado: null
            },
            bodegas: ['Todas', 'Restaurante', 'Cafeteria'],
            tipoInventario: ['Ajuste Entrada', 'Ajuste Salida'],
            dialogDescargue: false,
            dialogTraslado: false,
            dialogError: false,
            loading: false,
            bodegaCarga: null,
            listBodegas: [],
            allcategory: [],
            search: '',
            botonsubir: '',
            cantidad: '',
            cantAjuste: '',
            tipoInvetarios: '',
            tiposInventario: [],
            idInventario: '',
            dialog: false,
            InventarioModal: false,
            dialogEntrada: false,
            dialogoInventario: false,
            noRegistra: [],
            preload: false,
            listaIngredientes: [],
            ingredientes: [],
            namefile: 'Seleccionar archivos',
            unidades: ['Gramos', 'Mililitros', 'Unidad'],
            headers: [{
                    text: 'Nombre',
                    value: 'nombre'
                },
                {
                    text: 'Cantidad',
                    value: 'stock_actual'
                },
                {
                    text: 'Precio Promedio',
                    value: 'precio_promedio'
                },
                {
                    text: 'Valor Total',
                    value: 'total_inventario'
                },
                {
                    text: 'Bodega',
                    value: ''
                },
                {
                    text: 'Ajustes',
                    value: ''
                },
            ],
            ingrediente: {
                nombre: null,
                precio_compra: null,
                stock_actual: null,
                stock_minimo: null,
                total_inventario: null,
                unidad_medida: null,
                vagout_bodega_id: null
            },
            inventario: {
                stock_actual: '',
                nombre: '',
                precio_promedio: '',
            },
            entradaId: '',
            entrada: {
                stock_actual: null,
                precio_venta: null,
                precio_compra: null,
                total_inventario: null
            }
        }),
        filters: {
            pesoFormat: (valor) => `$${new Intl.NumberFormat().format(valor) || 0}`
        },
        computed: {},
        watch: {},
        methods: {

            close() {
                for (const prop of Object.getOwnPropertyNames(this.ingrediente)) {
                    this.ingrediente[prop] = null;
                }
                for (const prop of Object.getOwnPropertyNames(this.entrada)) {
                    this.entrada[prop] = null;
                }
                this.dialogEntrada = false;
                this.dialogoInventario = false;
                this.dialog = false;
                this.namefile = 'Seleccionar archivos';
                this.errorsInventario = false;
            },
            save() {
                if (!this.ingrediente.unidad_medida) {
                    this.$alerError("La unidad de medida es requerida");
                    return;
                }
                const precio = (parseInt(this.ingrediente.total_inventario) / parseInt(this.ingrediente.stock_actual));
                this.ingrediente.precio_compra = parseFloat(precio).toFixed(2);
                axios.post('/api/inventario/ingredientes', this.ingrediente).then(res => {
                    if (res.status === 200) {
                        this.$alerSuccess("Registro Exitoso");
                        this.close();
                        this.listarIngredientes()
                    }
                }).catch(e => {
                    console.log(e)
                })
            },
            update() {
                axios.put('/api/inventario/ingredientes/' + this.entradaId, this.entrada).then(res => {
                    if (res.status === 200) {
                        this.$alerSuccess("Registro Exitoso");
                        this.close();
                        this.listarIngredientes()
                    }
                }).catch(e => {
                    console.log(e)
                })
            },
            listarIngredientes() {
                axios.get('/api/inventario/ingredientes').then(res => {
                    this.ingredientes = res.data;
                    this.listaIngredientes = res.data;
                })
            },
            cargarDatos(item) {
                this.entradaId = item.id;
                this.entrada.precio_compra = item.precio_compra;
                this.entrada.precio_venta = item.precio_venta;
                this.dialogEntrada = true;
            },
            async cargar() {
                this.preload = true;
                this.loading = true;
                if (this.$refs.files.files.length === 0) {
                    this.$alerError("El archivo de carga es requerido");
                    this.loading = false;
                    return;
                }
                let formData = new FormData();
                formData.append('data', this.$refs.files.files[0]);
                formData.append('bodega', this.bodegaCarga);
                //this.preload = true;
                const data = await axios.post('/api/inventario/validar', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                this.preload = false;
                this.listarIngredientes();
                if (data.data.length > 0) {
                    this.dialogEntrada = false;
                    this.noRegistra = data.data;
                    this.namefile = 'Seleccionar archivos';
                    this.$refs.files.files = [];
                    this.loading = false;
                    this.showMessage();

                } else {
                    this.noRegistra = data.data;
                    this.namefile = 'Seleccionar archivos';
                    this.dialogEntrada = false;
                    this.listarIngredientes();
                    this.loading = false;
                }
            },

            async cargarInventario() {
                this.preload = true;
                if (this.$refs.filesInventario.files.length === 0) {
                    this.$alerError("El archivo de carga es requerido");
                    this.preload = false;
                    return;
                }
                let formData = new FormData();
                formData.append('data', this.$refs.filesInventario.files[0]);
                formData.append('bodega', this.bodegaCarga);
                try {
                    const data = await axios.post('/api/inventario/cargueInventario', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });
                    this.listarIngredientes();
                    this.dialogoInventario = data.data.error;
                    this.showMessage();
                    this.clear();
                } catch (err) {
                    (
                        this.clear(),
                        this.errorsInventario = err.response.data,
                        this.preload = false,
                        this.dialogErrorInventario = true
                    )
                }
                this.preload = false;
            },

            async cargarDescargue() {
                this.preload = true;
                if (this.$refs.filesDescargue.files.length === 0) {
                    this.$alerError("El archivo de carga es requerido");
                    this.loading = false;
                    return;
                }

                let formData = new FormData();
                formData.append('data', this.$refs.filesDescargue.files[0]);
                const data = await axios.post('/api/inventario/descargue', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                this.listarIngredientes();
                if (parseInt(data.data.state) === 1) {
                    this.$alerError(data.data.message)
                } else if (parseInt(data.data.state) === 2) {
                    this.itemsErrors = data.data.sinCupo;
                    this.dialogError = true;
                } else {
                    this.$alerSuccess(data.data.message)

                }
                this.preload = false;
                this.namefile = 'Seleccionar archivos';
                this.$refs.filesDescargue.value = null;
                this.dialogDescargue = false;
                this.$refs.filesInventario.value = null;
                this.preload = false;
            },
            uploadFiles() {
                this.$refs.files.click()
            },
            uploadFilesDescargue() {
                this.$refs.filesDescargue.click()
            },
            uploadFilesinventario() {
                this.$refs.filesInventario.click()
            },
            setNameInventario() {
                if (this.$refs.filesInventario.files.length === 0) return this.namefile = 'Seleccionar archivos';
                return this.namefile = (this.$refs.filesInventario.files.length === 1) ? this.$refs.filesInventario
                    .files[0].name :
                    `${this.$refs.filesInventario.files.length} archivos para cargar`
            },
            setName() {
                if (this.$refs.files.files.length === 0) return this.namefile = 'Seleccionar archivos';
                return this.namefile = (this.$refs.files.files.length === 1) ? this.$refs.files.files[0].name :
                    `${this.$refs.files.files.length} archivos para cargar`
            },
            setNameDescargue() {
                if (this.$refs.filesDescargue.files.length === 0) return this.namefile = 'Seleccionar archivos';
                return this.namefile = (this.$refs.filesDescargue.files.length === 1) ? this.$refs.filesDescargue.files[
                        0].name :
                    `${this.$refs.filesDescargue.files.length} archivos para cargar`
            },
            getBodegasVagout() {
                axios.get('/api/inventario/getBodegasVagout').then(res => {
                    this.listBodegas = res.data;
                })
            },
            closeModal() {
                this.namefile = 'Seleccionar archivos';
                this.$refs.files.value = null;
                this.$refs.filesDescargue.value = null;
                this.dialogEntrada = false;
                this.dialogDescargue = false;
                this.dialogoInventario = false;
                this.$refs.filesInventario.value = null;
                this.dialogErrorInventario = false;
                this.InventarioModal = false;
            },
            filtrarBodega() {
                this.preload = true;
                if (this.form.bodega === 'Restaurante') {
                    this.listaIngredientes = this.ingredientes.filter(ingrediente => ingrediente.bodega ===
                        'Restaurante')
                } else if (this.form.bodega === 'Cafeteria') {
                    this.listaIngredientes = this.ingredientes.filter(ingrediente => ingrediente.bodega === 'Cafeteria')
                } else {
                    this.listaIngredientes = this.ingredientes;
                }
                this.preload = false;
            },
            bodegasTraslado() {
                console.log(this.ingredientes);
                if (this.form.bodegaOrigen === 'Restaurante') {
                    this.form.bodegaDestino = 'Cafeteria';
                    this.form.ingredienteBodega = this.ingredientes.filter(ingrediente => ingrediente.bodega ===
                        'Restaurante')
                } else if (this.form.bodegaOrigen === 'Cafeteria') {
                    this.form.bodegaDestino = 'Restaurante';
                    this.form.ingredienteBodega = this.ingredientes.filter(ingrediente => ingrediente.bodega ===
                        'Cafeteria')
                } else {
                    this.form.bodegaDestino = null;
                    this.form.ingredienteBodega = [];
                }
            },
            abrirAjuste(item) {
                this.InventarioModal = true
                this.entradaId = item
            },
            ajusteInventario() {

                if (!this.cantAjuste || !this.tipoInvetarios || !this.descripcionAjuste) {
                    this.$alerError('Todos los campos son requeridos')
                    return false
                }
                console.log(this.entradaId.stock_actual,this.cantAjuste);
                if (this.tipoInvetarios === 'Ajuste Salida' && (parseInt(this.entradaId.stock_actual) < parseInt(this.cantAjuste))) {
                    this.$alerError('La cantidad ingresada es mayor a la que se encuentra en el inventario')
                    this.clear()
                    return false
                }

                axios({
                    method: 'post',
                    data: {
                        cantidad: this.cantAjuste,
                        tiposInventario: this.tipoInvetarios,
                        descripcion: this.descripcionAjuste
                    },
                    url: '/api/inventario/getInventario/' + this.entradaId.id
                })

                this.listarIngredientes();
                this.$alerSuccess('Ajuste realizado correctamente')
                this.InventarioModal = false
                this.clear()

            },
            showMessage() {
                swal({
                    title: "¡Su archivo se ha subido correctamente!",
                    // text: message,
                    icon: "success",
                    showConfirmButton: false,
                    timer: 3000,
                    position: 'top-end'
                });
            },
            clear() {
                this.dialogoInventario = '';
                    this.cantAjuste = '';
                    this.tipoInvetarios = '';
                    this.descripcionAjuste = '';
            },

        },
        beforeMount() {
            this.getBodegasVagout();
            this.listarIngredientes();
        },


    }

</script>
