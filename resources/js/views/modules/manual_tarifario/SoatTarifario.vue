<template>
    <v-flex xs3 px-1>
        <v-card>
            <v-container pa-1>
                <v-container pa-0>
                    <v-dialog v-model="importFile" max-width="500px">
                        <v-card>
                        <v-card-title>
                            <span class="headline">Importar Archivo</span>
                        </v-card-title>

                        <v-card-text>
                            <v-container  grid-list-md>
                            <v-layout wrap align-center>
                                <v-flex xs3 sm3 md3>
                                    <v-btn 
                                    color="success"
                                    @click="importPrestadores()"
                                    >
                                        <v-icon>attachment</v-icon>
                                    </v-btn>
                                </v-flex>
                                <input type="file" v-show="false" @change="onFilePicked" ref="file">           
                                <v-flex xs9 sm9 md9>
                                <v-text-field
                                    v-model="nameFile"
                                    name="name"
                                    readonly
                                    label="nombre"
                                    id="id"
                                    ></v-text-field> 
                                </v-flex>
                            </v-layout>
                            </v-container>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" flat @click="importFile = !importFile">Cancel</v-btn>
                            <v-btn color="blue darken-1" flat @click="saveFile()">Save</v-btn>
                        </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <v-layout row>
                        <v-dialog v-model="dialog" max-width="600px">
                        <v-card>
                            <v-card-title>
                            <span v-if="save" class="headline">Crear Prestador</span>
                            <span v-else class="headline">Editar Prestador</span>
                            </v-card-title>
                            <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex  xs12 sm6 md6 v-for="field in inputs" :key="field.model">
                                        <v-autocomplete v-if="field.items" :items="municipios" item-text="Nombre" item-value="id" :label="field.label"  v-model="data[field.model]" required></v-autocomplete>
                                        <v-text-field v-else :label="field.label" v-model="data[field.model]" required></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                            </v-card-text>
                            <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" flat @click="dialog = false">Cancelar</v-btn>
                            <v-btn v-if="save" color="blue darken-1" flat @click="savePrestador()">Guardar</v-btn>
                            <v-btn v-else color="blue darken-1" flat @click="updatePrestador()">Actualizar</v-btn>
                            </v-card-actions>
                        </v-card>
                        </v-dialog>
                    </v-layout>
                </v-container>
                <v-card-title>
                    <v-layout row wrap justify-center>
                        <h4 class="headline">SOAT</h4>
                    </v-layout>
                    <v-layout row wrap>
                        <v-btn 
                        round
                        small
                        dark
                        color="orange"
                        @click="importFile = !importFile"
                        >
                            <v-icon>cloud_upload</v-icon>
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-btn round small @click="createPrestador()" color="primary" dark >
                            <v-icon>exposure_plus_1</v-icon>
                        </v-btn>
                        <v-flex 
                        sm12
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
                <v-data-table
                    :headers="headers"
                    :items="prestadores"
                    :search="search"
                    >
                    <template v-slot:items="props">
                        <td class="text-xs-right">{{ props.item.Nombre }}</td>
                        <td class="text-xs-right">{{ props.item.Nit }}</td>
                        <td class="text-xs-right">
                            <v-btn fab outline color="warning" small @click="editPrestador(props.item)"><v-icon>edit</v-icon></v-btn>
                        </td>
                    </template>
                    <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                        Your search for "{{ search }}" found no results.
                    </v-alert>
                </v-data-table>
            </v-container>
        </v-card>   
    </v-flex>
</template>

<script>
 
    export default {
        name: 'SoatTarifarios',
        components: {
        },
        data(){
            return {
                importFile: false,
                permisos: [],
                search: '',
                headers: [
                    {
                        text: 'Nombre',
                        align: 'right',
                        sortable: false,
                        value: 'Nombre'
                    },
                    {
                        text: 'Valor',
                        align: 'right',
                        sortable: false,
                        value: 'Nit'
                    },
                    {
                        text: '',
                        align: 'right',
                        sortable: false,
                        value: 'Nit'
                    },
                ],
                inputs:[
                    { label: 'Nombre', model: 'Nombre'},
                    { label: 'Nit', model: 'Nit'},
                    { label: 'Dirección', model: 'Direccion'},
                    { label: 'Correo', model: 'Correo1'},
                    { label: 'Correo (opcional)', model: 'Correo2'},
                    { label: 'Teléfono', model: 'Telefono1'},
                    { label: 'Teléfono (opcional)', model: 'Telefono2'},
                    { label: 'Municipio', model: 'Municipio_id', items: 'municipios'},
                    { label: 'Código habilitacion', model: 'Codigo_habilitacion'},
                ],
                save: true,
                dialog: false,
                data:{
                    Nombre: '',
                    Nit: '',
                    Direccion: '',
                    Correo1: '',
                    Correo2: '',
                    Telefono1: '',
                    Telefono2: '',
                    Municipio_id: '',
                    Codigo_habilitacion: '',
                },
                municipios: [],
                prestadores: [],
                nameFile: '',
                file:''
            }
        },
        mounted(){
            this.fetchPrestadores();
            this.fetchMunicipios();
        },
        methods: {
            fetchMunicipios(){
                axios.get('/api/municipio/all')
                        .then((res) => this.municipios = res.data);
            },
            fetchPrestadores(){
                axios.get('/api/prestador/prestadores')
                        .then((res) => this.prestadores = res.data)
                        .catch((err) => console.log(err));
            },
            createPrestador(){
                this.save = true;
                this.dialog = true;
                this.clearFields();
            },
            savePrestador(){
                axios.post('/api/prestador/create', this.data)
                        .then((res) => {
                            this.dialog = false;
                            this.fetchPrestadores();
                            this.clearFields();
                            swal({
                                title: `${res.data.message}`,
                                text: ' ',
                                type: "success",
                                timer: 3000,
                                icon: "success",
                                buttons: false
                            });
                        })
                        .catch((err) => console.log(err))
            },
            editPrestador(prestador){
                this.save = false;
                this.dialog = true;
                this.data = {
                    id: prestador.id,
                    Nombre: prestador.Nombre,
                    Nit: prestador.Nit,
                    Direccion: prestador.Direccion,
                    Correo1: prestador.Correo1,
                    Correo2: prestador.Correo2,
                    Telefono1: prestador.Telefono1,
                    Telefono2: prestador.Telefono2,
                    Municipio_id: prestador.Municipio_id,
                    Codigo_habilitacion: prestador.Codigo_habilitacion,
                };
            },
            updatePrestador(){
                axios.put(`/api/prestador/edit/${this.data.id}`, this.data)
                        .then((res) => {
                            this.fetchPrestadores();
                            this.clearFields();
                            this.dialog = false;
                            swal({
                                title: `¡${res.data.message}!`,
                                text: " ",
                                timer: 2000,
                                icon: "success",
                                buttons: false
                            });
                        })
            },
            clearFields(){
                this.data = {
                    Nombre: '',
                    Nit: '',
                    Direccion: '',
                    Correo1: '',
                    Correo2: '',
                    Telefono1: '',
                    Telefono2: '',
                    Municipio_id: '',
                    Codigo_habilitacion: '',
                }
            },
            importPrestadores(){
                this.$refs.file.click()
            },
            onFilePicked(e){
                const files = e.target.files
                this.nameFile = files[0].name
                this.file = files[0] // this is an image file that can be sent to server...
            },
            saveFile(){
               let formData = new FormData();
               formData.append('data', this.file);

               axios.post( '/api/prestador/import',formData, { headers: {'Content-Type': 'multipart/form-data'}})
                    .then((res) => {
                        this.importFile = false;
                        this.nameFile = '';
                        this.file = null;
                        swal({
                            title: `${res.data.message}`,
                            text: ' ',
                            type: "success",
                            timer: 3000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch(() => console.log('FAILURE!!'));
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>