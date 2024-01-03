<template>
    <v-flex xs12 px-1>
        <v-card>
            <v-container pa-1>
                <v-container pa-0>
                    <v-dialog v-model="importFile" max-width="500px">
                        <v-card>
                            <v-card-title>
                                <span class="headline">Importar Archivo</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap align-center>
                                        <v-flex xs3 sm3 md3>
                                            <v-btn color="success" @click="importTipoManuales()">
                                                <v-icon>attachment</v-icon>
                                            </v-btn>
                                        </v-flex>
                                        <input type="file" v-show="false" @change="onFilePicked" ref="file">
                                        <v-flex xs9 sm9 md9>
                                            <v-text-field v-model="nameFile" name="name" readonly label="nombre"
                                                id="id"></v-text-field>
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
                                <v-toolbar dark color="primary">
                                    <v-flex xs12 text-xs-center flat dark>
                                        <v-toolbar-title v-if="save">Crear Tipo Manual</v-toolbar-title>
                                        <v-toolbar-title v-else>Editar Tipo Manual</v-toolbar-title>
                                    </v-flex>
                                </v-toolbar>
                                <v-card-text>
                                    <v-container grid-list-md>
                                        <v-layout wrap>
                                            <v-flex xs12 sm6 md6 v-for="field in inputs" :key="field.model">
                                                <v-text-field :label="field.label" v-model="data[field.model]" required>
                                                </v-text-field>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>

                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="error" @click="dialog = false">Cancelar</v-btn>
                                    <v-btn v-if="save" color="success" @click="saveTiposManual()">Guardar</v-btn>
                                    <v-btn v-else color="warning" @click="updateTiposManual()">Actualizar</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-layout>
                </v-container>
                <v-card-title>
                    <v-layout row wrap justify-center>
                        <h4 class="headline">Tipos Manual</h4>
                    </v-layout>
                    <v-layout row wrap>
                        <!-- <v-btn 
                         round
                         small
                         dark
                         color="orange"
                         @click="importFile = !importFile"
                         >
                            <v-icon >cloud_upload</v-icon>
                        </v-btn> -->
                        <v-spacer></v-spacer>
                        <v-btn round small @click="createTiposManual()" color="primary" dark>
                            <v-icon>exposure_plus_1</v-icon>
                        </v-btn>
                        <v-flex sm12 xs12>
                            <v-text-field v-model="search" append-icon="search" label="Buscar..." single-line
                                hide-details></v-text-field>
                        </v-flex>
                    </v-layout>
                </v-card-title>
                <v-data-table :headers="headers" :items="tipoManuales" :search="search">
                    <template v-slot:items="props">
                        <td class="text-xs-right">{{ props.item.Nombre }}</td>
                        <td class="text-xs-right">{{ props.item.Descripcion }}</td>
                        <td class="text-xs-right">
                            <v-btn fab outline color="warning" small @click="editTiposManual(props.item)">
                                <v-icon>edit</v-icon>
                            </v-btn>
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
        name: 'Iss2000Tarifarios',
        components: {},
        data() {
            return {
                importFile: false,
                search: '',
                headers: [{
                        text: 'Nombre',
                        align: 'right',
                        sortable: false,
                        value: 'Nombre'
                    },
                    {
                        text: 'Descripción',
                        align: 'right',
                        sortable: false,
                        value: 'Descripcion'
                    },
                    {
                        text: '',
                        align: 'right',
                        sortable: false,
                        value: ''
                    },
                ],
                inputs: [{
                        label: 'Nombre',
                        model: 'Nombre'
                    },
                    {
                        label: 'Descripción',
                        model: 'Descripcion'
                    },
                ],
                save: true,
                dialog: false,
                data: {
                    Nombre: '',
                    Descripcion: '',
                },
                tipoManuales: [],
                nameFile: '',
                file: ''
            }
        },
        mounted() {
            this.fetchTipoManuales();
        },
        methods: {
            fetchTipoManuales() {
                axios.get('/api/tipomanuales/all')
                    .then((res) => this.tipoManuales = res.data)
                    .catch((err) => console.log(err));
            },
            createTiposManual() {
                this.save = true;
                this.dialog = true;
                this.clearFields();
            },
            saveTiposManual() {
                axios.post('/api/tipomanuales/create', this.data)
                    .then((res) => {
                        this.dialog = false;
                        this.fetchTipoManuales();
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
                    .catch((err) => {
                        this.$alerError('Todos los campos son obligatorios');
                    })
            },
            editTiposManual(prestador) {
                this.save = false;
                this.dialog = true;
                this.data = {
                    id: prestador.id,
                    Nombre: prestador.Nombre,
                    Descripcion: prestador.Descripcion,
                };
            },
            updateTiposManual() {
                axios.put(`/api/tipomanuales/edit/${this.data.id}`, this.data)
                    .then((res) => {
                        this.fetchTipoManuales();
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
            clearFields() {
                this.data = {
                    Nombre: '',
                    Descripcion: '',
                }
            },
            importTipoManuales() {
                this.$refs.file.click()
            },
            onFilePicked(e) {
                const files = e.target.files
                this.nameFile = files[0].name
                this.file = files[0] // this is an image file that can be sent to server...
            },
            saveFile() {
                let formData = new FormData();
                formData.append('data', this.file);

                axios.post('/api/prestador/import', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
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
