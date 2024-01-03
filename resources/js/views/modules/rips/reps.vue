<template>
    <div>
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


        <v-dialog v-model="dialogCrearPrestador" width="1000">
            <v-card>
                <v-card-title class="headline grey lighten-2" primary-title>Nuevo Prestador</v-card-title>
                <form @submit.prevent="GuardarPrestador">
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12 sm6 md12>
                                <v-text-field label="Nombre" v-model="formPrestador.nombre" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-text-field label="Nit" type="number" v-model="formPrestador.nit" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-text-field label="Codigo Prestador" type="number" v-model="formPrestador.codigoPrestador" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md12>
                                <v-text-field label="Direccion" v-model="formPrestador.direccion" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-text-field v-model="formPrestador.email1" label="Email 1" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-text-field v-model="formPrestador.email2" label="Email 2"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-text-field v-model="formPrestador.telefono1" label="Telefono 1" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-text-field v-model="formPrestador.telefono2" label="Telefono 2"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-checkbox color="primary" label="Capitado" v-model="formPrestador.capitado"></v-checkbox>
                            </v-flex>
                        </v-layout>
                    </v-container>

                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" small @click="dialogCrearPrestador = false">Cerrar</v-btn>
                    <v-btn color="success" small type="submit">Guardar</v-btn>
                </v-card-actions>
                </form>
            </v-card>
        </v-dialog>

        <v-container grid-list-md>
            <v-layout row wrap>
                <v-flex xs12 md6>
                    <v-text-field v-model="filtro.nit" label="Nit" type="number"></v-text-field>
                </v-flex>
                <v-flex xs12 md6>
                    <v-text-field v-model="filtro.habilitacion" label="Codigo Habilitacion"
                                  type="number"></v-text-field>
                </v-flex>
                <v-flex xs12 md6>
                    <v-text-field v-model="filtro.nombre" label="Nombre"></v-text-field>
                </v-flex>
                <v-flex xs12 md6>
                    <div>
                        <v-btn color="success" small @click="sedesPrestadores()">Filtrar</v-btn>
                        <v-btn color="error" small @click="limpiarFiltros">Limpiar</v-btn>
                    </div>
                </v-flex>
            </v-layout>
        </v-container>
        <v-card>
            <v-toolbar flat color="white">
                <v-spacer></v-spacer>
                <v-dialog v-model="dialogCrear" max-width="1200px">
                    <template v-slot:activator="{ on }">
                        <v-btn color="success" dark class="mb-2" v-on="on">Agregar<v-icon right dark>add</v-icon></v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            <span class="headline">Nueva Sede</span>
                        </v-card-title>
                        <form @submit.prevent="GuardarSede">
                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs12 sm10 md10>
                                        <v-autocomplete v-model="formSede.prestador_id" label="Prestador" :items="prestadoresList" item-value="id" :item-text="p => p.Nit+'-'+p.Nombre" required></v-autocomplete>
                                    </v-flex>
                                    <v-flex xs12 sm10 md2>
                                        <v-btn fab dark small color="success" @click="dialogCrearPrestador = true">
                                            <v-icon dark>add</v-icon>
                                        </v-btn>
                                    </v-flex>
                                    <v-flex xs12 sm6 md12>
                                        <v-text-field label="Nombre" v-model="formSede.nombre" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <v-text-field label="Codigo Habilitación" type="number" v-model="formSede.codigoHabilitacion" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm1 md1>
                                        <v-text-field label="Digito" type="number" v-model="formSede.digito" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm1 md1>
                                        <v-text-field label="Nivel" type="number" v-model="formSede.nivel" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md7>
                                        <v-autocomplete v-model="formSede.municipio_id" :items="municipiosList" label="Municipio" item-value="id" :item-text="m => m.departamento+'-'+m.municipio" required></v-autocomplete>
                                    </v-flex>
                                    <v-flex xs12 sm6 md12>
                                        <v-text-field label="Direccion" v-model="formSede.direccion" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md6>
                                        <v-text-field v-model="formSede.email1" label="Email 1" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md6>
                                        <v-text-field v-model="formSede.email2" label="Email 2"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md6>
                                        <v-text-field v-model="formSede.telefono1" label="Telefono 1" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6 md6>
                                        <v-text-field v-model="formSede.telefono2" label="Telefono 2"></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="error" small @click="close">Cancel</v-btn>
                            <v-btn v-if="!formularioEditar" color="success" small type="submit">Guardar</v-btn>
                            <v-btn v-else color="warning" small type="submit">Actualizar</v-btn>

                        </v-card-actions>
                        </form>

                    </v-card>
                </v-dialog>
            </v-toolbar>
            <v-data-table :headers="headers" :items="sedePrestadorList" class="elevation-1" hide-actions>
                <template v-slot:items="props">
                    <td class="text-xs-center">{{ props.item.Codigo_habilitacion }}</td>
                    <td class="text-xs-center">{{ props.item.Nombre }}</td>
                    <td class="text-xs-center">{{ props.item.nit }}</td>
                    <td class="text-xs-center">{{ props.item.prestador }}</td>
                    <td class="text-xs-center">{{ props.item.iron }}</td>
                    <td class="text-xs-center">
                        <v-btn flat icon color="orange" @click="cargarDetalle(props.item)">
                            <v-icon>edit</v-icon>
                        </v-btn>
                    </td>

                </template>
            </v-data-table>
        </v-card>
        <v-card>
        <v-container grid-list-md>
            <v-layout row wrap>
                <v-flex xs12 md10>
                        <div class="text-xs-center">
                            <v-pagination v-model="page" :length="pages" total-visible="10" @input="sedesPrestadores()"></v-pagination>
                        </div>
                </v-flex>
                <v-flex xs12 md2>
                    <div class="text-xs-center mt-0">
                        <v-select v-model="items" :items="[5,10,20,50,100]" @change="sedesPrestadores()"></v-select>
                    </div>
                </v-flex>
            </v-layout>
        </v-container>
        </v-card>
    </div>
</template>
<script>
export default {
    name: 'prestadoresRips',
    data: () => ({
        formularioEditar:false,
        formPrestador:{
            nombre:null,
            nit:null,
            direccion:null,
            email1:null,
            email2:null,
            telefono1:null,
            telefono2:null,
            codigoPrestador:null,
            capitado:null
        },
        formSede:{
            id:null,
            prestador_id:null,
            nombre:null,
            codigoHabilitacion:null,
            digito:null,
            nivel:null,
            municipio_id:null,
            direccion:null,
            email1:null,
            email2:null,
            telefono1:null,
            telefono2:null
        },
        dialogCrearPrestador:false,
        dialogCrear:false,
        preload:false,
        pages: 0,
        page: 1,
        items:5,
        filtro: {
            nit: null,
            habilitacion: null,
            nombre: null
        },
        headers: [
            {text: 'Codigo Habilitacion', value: 'Codigo_habilitacion', align: 'center'},
            {text: 'Nombre', value: 'Nombre', align: 'center'},
            {text: 'Nit', value: 'nit', align: 'center'},
            {text: 'Prestador', value: 'prestador', align: 'center'},
            {text: '', value: 'acciones'},


        ],
        sedePrestadorList: [],
        prestadoresList:[],
        municipiosList:[]
    }),
    methods: {
        municipios(){
          axios.get('/api/municipio/lista').then(res => {
              this.municipiosList = res.data;
          })
        },
        sedesPrestadores() {
            this.preload = true;
            axios.post('/api/sedeproveedore/prestadoresRips?page=' + this.page+'&items='+this.items,this.filtro).then(res => {
                this.sedePrestadorList = res.data.data
                this.preload = false;
                this.pages = res.data.last_page;
            }).catch(e => {
                this.preload = false;
            })
        },
        prestadores(){
            this.preload = true;
            axios.get('/api/sedeproveedore/prestadores').then(res =>{
                this.prestadoresList = res.data
                this.preload = false;
            })
        },
        limpiarFiltros(){
            this.preload = true;
            this.filtro.nit = null;
            this.filtro.habilitacion = null;
            this.filtro.nombre = null;
            this.sedesPrestadores();
        },
        close(){
            for (const prop of Object.getOwnPropertyNames(this.formSede)) {
                this.formSede[prop] = null;
            }
            this.formularioEditar = false;
            this.dialogCrear = false;
        },
        closePrestador(){
            for (const prop of Object.getOwnPropertyNames(this.formPrestador)) {
                this.formPrestador[prop] = null;
            }
            this.dialogCrearPrestador = false;
        },
        GuardarSede(){
            this.preload = true;
            axios.post('/api/sedeproveedore/guardarRepsRips',this.formSede).then(res => {
                this.$alerSuccess(res.data.mensaje);
                this.preload = false;
                this.dialogCrear = false;
                this.sedesPrestadores();
                this.close();
            })
        },
        GuardarPrestador(){
            this.preload = true;
            axios.post('/api/sedeproveedore/guardarPrestadorRips',this.formPrestador).then(res => {
                this.$alerSuccess(res.data.mensaje);
                this.preload = false;
                this.prestadores();
                this.closePrestador();
            })
        },
        cargarDetalle(item){
            const codigoDigito = item.Codigo_habilitacion.split('-')
            this.formSede.id = parseInt(item.id)
            this.formSede.prestador_id = parseInt(item.Prestador_id);
            this.formSede.nombre = item.Nombre;
            this.formSede.codigoHabilitacion = codigoDigito[0];
            this.formSede.digito = codigoDigito[1];
            this.formSede.nivel = item.Nivelatencion;
            this.formSede.municipio_id = parseInt(item.Municipio_id);
            this.formSede.direccion = item.Direccion;
            this.formSede.email1 = item.Correo1;
            this.formSede.email2 = item.Correo2;
            this.formSede.telefono1 = item.Telefono1;
            this.formSede.telefono2 = item.Telefono2;
            this.formularioEditar = true;
            this.dialogCrear = true;
        }
    },
    mounted() {
        this.municipios();
        this.prestadores();
        this.sedesPrestadores();
    }
}
</script>
