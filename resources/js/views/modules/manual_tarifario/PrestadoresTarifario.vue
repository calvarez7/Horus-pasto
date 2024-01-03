<template>
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
                            <v-flex xs4 sm4 md4>
                                <v-btn 
                                color="success"
                                @click="importPrestadores()"
                                >
                                    adjuntar
                                    <v-icon>attachment</v-icon>
                                </v-btn>
                            </v-flex>
                            <input type="file" v-show="false" @change="onFilePicked" ref="file">           
                            <v-flex xs8 sm8 md8>
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
                        <v-card-title class="headline success" style="color:white">
                        <span v-if="save" class="headline">Crear Prestador</span>
                        <span v-else class="headline">Editar Prestador</span>
                        </v-card-title>
                        <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm6 md6>
                                    <v-autocomplete 
                                        label="Tipo de prestador*" 
                                        v-model="data.Tipoprestador_id"
                                        :items="tipoprestadores"
                                        item-text="Nombre"
                                        item-value="id"
                                        required>                                
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex  xs12 sm6 md6 v-for="field in inputs" :key="field.model">
                                    <v-text-field :label="field.label" v-model="data[field.model]" required></v-text-field>
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
            <v-container pt-3 pb-1>
                <v-layout row>
                    <v-dialog v-model="subDialog" persistent max-width="600px">
                    <v-card>
                        <v-card-title>
                        <span v-if="save" class="headline">Crear Sede Prestador</span>
                        <span v-else class="headline">Editar Sede Prestador</span>
                        </v-card-title>
                        <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm6 md6>
                                    <v-autocomplete 
                                        label="Municipio*" 
                                        v-model="subData.Municipio_id"
                                        :items="municipios"
                                        item-text="Nombre"
                                        item-value="id"
                                        required>                                
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-text-field 
                                        label="Código habilitación*" 
                                        v-model="subData.Codigo_habilitacion"
                                        required>                                
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-text-field 
                                        label="Nombre*" 
                                        v-model="subData.Nombre"
                                        required>                                
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-text-field
                                        v-model="subData.Nivelatencion"
                                        label="Nivel de atención*"
                                        required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-text-field
                                        v-model="subData.Direccion"
                                        label="Dirección*"
                                        required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-text-field
                                        v-model="subData.Correo1"
                                        label="Correo electrónico*"
                                        required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-text-field
                                        v-model="subData.Correo2"
                                        label="Correo electrónico alternativo*"
                                        required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-text-field
                                        v-model="subData.Telefono1"
                                        label="Telefono*"
                                        required>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-text-field
                                        v-model="subData.Telefono2"
                                        label="telefono alternativo*"
                                        required>
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-container>
                        </v-card-text>
                        <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" flat @click="subDialog = false">Cancelar</v-btn>
                        <v-btn v-if="subSave" color="blue darken-1" flat @click="saveSedePrestador()">Guardar</v-btn>
                        <v-btn v-else color="blue darken-1" flat @click="updateSedePrestador()">Actualizar</v-btn>
                        </v-card-actions>
                    </v-card>
                    </v-dialog>
                </v-layout>
            </v-container>
            <v-card-title>
                <v-btn round @click="createPrestador()" color="primary" dark ><v-icon left>person_add</v-icon>Crear Prestador</v-btn>
                <v-spacer></v-spacer>
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
            </v-card-title>
            <v-data-table
                :headers="headers"
                :items="prestadores"
                :search="search"
                >
                <template v-slot:items="props">
                    <tr @click="expandTable(props)">
                        <td>{{ props.item.id }}</td>
                        <td class="text-xs-right">{{ props.item.Nombre }}</td>
                        <td class="text-xs-right">{{ props.item.Nit }}</td>
                        <td class="text-xs-right">{{ props.item.Direccion }}</td>
                        <td class="text-xs-right">{{ props.item.Correo1 }}</td>
                        <td class="text-xs-right">{{ props.item.Telefono1 }}</td>
                        <td class="text-xs-right">{{ props.item.Codigo_prestador }}</td>
                        <td class="text-xs-right">
                            <v-btn outline color="warning" small icon @click="editPrestador(props.item)"><v-icon>edit</v-icon></v-btn>
                            <v-btn outline color="success" small icon @click="createSedePrestador(props)" ><v-icon>add</v-icon></v-btn>
                            <v-btn outline color="error" small icon @click="disablePrestador(props.item)" ><v-icon>clear</v-icon></v-btn>
                        </td>
                    </tr>
                </template>
                <template v-slot:expand="props">
                    <v-data-table
                     :headers="subHeaders"
                     :items="sedePrestadores"
                     :hide-actions="true"
                     >
                        <template v-slot:items="props">
                            <td>{{ props.item.id }}</td>
                            <td class="text-xs-left">{{ props.item.Municipio }}</td>
                            <td class="text-xs-left">{{ props.item.Nombre }}</td>
                            <td class="text-xs-left">{{ props.item.Codigo_habilitacion }}</td>
                            <td class="text-xs-left">{{ props.item.Correo1 }}</td>
                            <td class="text-xs-left">
                                <v-btn fab color="warning" small @click="editSedePrestador(props.item)"><v-icon>edit</v-icon></v-btn>
                                <v-btn fab color="error" small @click="disableSedePrestador(props.item)" ><v-icon>clear</v-icon></v-btn>
                            </td>
                        </template>

                        <v-card flat>
                        <v-card-text>{{ props.item.Nombre }}</v-card-text>
                        </v-card>
                    </v-data-table>
                </template>
                <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                    Your search for "{{ search }}" found no results.
                </v-alert>
            </v-data-table>
        </v-container>
    </v-card>
</template>

<script>
 
    export default {
        name: 'PrestadoresTarifario',
        components: {
        },
        data(){
            return {
                importFile: false,
                permisos: [],
                search: '',
                headers: [
                    {
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Nombre Comercial',
                        align: 'right',
                        sortable: false,
                        value: 'Nombre'
                    },
                    {
                        text: 'NIT',
                        align: 'right',
                        sortable: false,
                        value: 'Nit'
                    },
                    {
                        text: 'Dirección',
                        align: 'right',
                        sortable: false,
                        value: 'Direccion'
                    },
                    {
                        text: 'Correo',
                        align: 'right',
                        sortable: false,
                        value: 'Correo1'
                    },
                    {
                        text: 'Teléfono',
                        align: 'right',
                        sortable: false,
                        value: 'Telefono1'
                    },
                    {
                        text: 'Código prestador',
                        align: 'right',
                        sortable: false,
                        value: 'Codigo_prestador'
                    },                    
                    {
                        text: '',
                        align: 'right',
                        sortable: false,
                        value: ''
                    },
                ],
                subHeaders: [
                    { text: 'id', align: 'left', value: 'id' },
                    { text: 'Municipio', align: 'left', value: 'Municipio' },
                    { text: 'Nombre', align: 'left', value: 'Nombre' },
                    { text: 'Código Habilitación', align: 'left', value: 'Codigo_habilitacion' },
                    { text: 'Correo Electrónico', align: 'left', value: 'Correo1'},
                    { text: '', align: 'left', value: '' },
                ],
                inputs:[
                    { label: 'Nombre Comercial', model: 'Nombre'},
                    { label: 'Nit', model: 'Nit'},
                    { label: 'Dirección', model: 'Direccion'},
                    { label: 'Correo', model: 'Correo1'},
                    { label: 'Correo alterno (opcional)', model: 'Correo2'},
                    { label: 'Teléfono', model: 'Telefono1'},
                    { label: 'Teléfono alterno (opcional)', model: 'Telefono2'},
                    { label: 'Código prestador', model: 'Codigo_prestador'},
                ],
                save: true,
                dialog: false,
                subSave: true,
                subDialog: false,
                data:{
                    Tipoprestador_id: '',
                    Nombre: '',
                    Nit: '',
                    Direccion: '',
                    Correo1: '',
                    Correo2: '',
                    Telefono1: '',
                    Telefono2: '',
                    Codigo_prestador: '',
                },
                subData:{
                    Municipio_id: '',
                    Prestador_id: '',
                    Codigo_habilitacion: '',
                    Nombre: '',
                    Nivelatencion: '',
                    Direccion: '',
                    Correo1: '',
                    Correo2: '',
                    Telefono1: '',
                    Telefono2: '',
                },
                municipios: [],
                prestadores: [],
                nameFile: '',
                file:'',
                sedePrestadores: [],
                tipoprestadores: [],
            }
        },
        mounted(){
            this.fetchTipoPrestador();
            this.fetchPrestadores();
            this.fetchMunicipios();
        },
        methods: {
            fetchTipoPrestador(){
                axios.get('/api/tipoprestador/all')
                        .then((res) => {
                            this.tipoprestadores = res.data
                        })
                        .catch((err) => console.log('err.response', err.response));
            },
            fetchMunicipios(){
                axios.get('/api/municipio/all')
                        .then((res) => this.municipios = res.data);
            },
            fetchPrestadores(){
                axios.get('/api/prestador/all')
                        .then((res) => {
                            this.prestadores = res.data
                            })
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
            getSedePrestador(Prestador_id){
                axios.post('/api/sedeproveedore/all',{ Prestador_id })
                        .then((res) => {
                            this.sedePrestadores = res.data
                        })
                        .catch((err) => console.log(err));
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
                    Codigo_prestador: prestador.Codigo_prestador,
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
                    Tipoprestador_id: '',
                    Nombre: '',
                    Nit: '',
                    Direccion: '',
                    Correo1: '',
                    Correo2: '',
                    Telefono1: '',
                    Telefono2: '',
                    Codigo_prestador: '',
                }
            },
            clearSubFields(){
                this.subData = {
                    Municipio_id: '',
                    Prestador_id: '',
                    Codigo_habilitacion: '',
                    Nombre: '',
                    Nivelatencion: '',
                    Direccion: '',
                    Correo1: '',
                    Correo2: '',
                    Telefono1: '',
                    Telefono2: '',
                }
            },
            async createSedePrestador(sede){
                await this.clearSubFields();
                this.subData.Prestador_id = sede.item.id;
                this.subSave = true;
                this.subDialog = true;
            },
            saveSedePrestador(){
                axios.post('/api/sedeproveedore/create', this.subData)
                        .then((res) => {
                            this.subDialog = false;
                            this.getSedePrestador(this.subData.Prestador_id);
                            this.clearSubFields();
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
            editSedePrestador(sede){
                this.subData = sede;
                this.subSave = false;
                this.subDialog = true;
            },
            updateSedePrestador(){
                axios.put(`/api/sedeproveedore/edit/${this.subData.id}`, this.subData)
                        .then((res) => {
                            this.getSedePrestador(this.subData.Prestador_id);
                            this.clearSubFields();
                            this.subDialog = false;
                            swal({
                                title: `¡${res.data.message}!`,
                                text: " ",
                                timer: 2000,
                                icon: "success",
                                buttons: false
                            });
                        })
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
            },
            async expandTable(props){
                await this.getSedePrestador(props.item.id);
                props.expanded = !props.expanded;
                
            },
            disablePrestador(prestador){
                swal({
                    title: 'Deshabilitar prestador',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((confirm) => {
                        if(confirm){
                            axios.put(`/api/prestador/disable/${prestador.id}`)
                                .then((res) => {
                                    this.fetchPrestadores();
                                    swal({
                                        title: `¡${res.data.message}!`,
                                        text: " ",
                                        timer: 2000,
                                        icon: "success",
                                        buttons: false
                                    });
                                })
                                .catch((err) => console.log(err.response))
                        }
                        
                    });
            },
            disableSedePrestador(sedePrestador){
                swal({
                    title: 'Deshabilitar sede del prestador',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((confirm) => {
                        if(confirm){
                            axios.put(`/api/sedeproveedore/disable/${sedePrestador.id}`)
                                .then((res) => {
                                    this.getSedePrestador(sedePrestador.Prestador_id);
                                    swal({
                                        title: `¡${res.data.message}!`,
                                        text: " ",
                                        timer: 2000,
                                        icon: "success",
                                        buttons: false
                                    });
                                })
                                .catch((err) => console.log(err.response))
                        }
                        
                    });
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>