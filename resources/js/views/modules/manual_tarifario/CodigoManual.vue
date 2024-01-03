<template>
    <v-flex xs12 px-1>
        <v-card>
            <v-container pa-1>
                <v-container pa-0>
                    <v-dialog v-model="dialogUpAnio" max-width="500px" persistent>
                        <v-card>
                            <v-toolbar dark color="primary">
                                <v-flex xs12 text-xs-center flat dark>
                                    <v-toolbar-title>Actualizar codigo manual(Año)</v-toolbar-title>
                                </v-flex>
                            </v-toolbar>

                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap align-center>
                                        <v-flex xs6 sm6 md6>
                                            <v-autocomplete :items="items.listanio" v-model="anioBase" label="Año base">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs6 sm6 md6>
                                            <v-autocomplete :items="items.listanio" v-model="anioCrear"
                                                label="Año crear">
                                            </v-autocomplete>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="error" @click="clearUpdateCodigo()">Salir</v-btn>
                                <v-btn color="success" @click="saveUpdateCodigo()">Guardar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <v-dialog v-model="importFile" max-width="500px">
                        <v-card>
                            <v-card-title>
                                <span class="headline">Importar Archivo</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap align-center>
                                        <v-flex xs3 sm3 md3>
                                            <v-btn color="success" @click="importCodigoManuales()">
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
                                <v-card-title>
                                    <span v-if="save" class="headline">Crear Código Manual</span>
                                    <span v-else class="headline">Editar Código Manual</span>
                                </v-card-title>
                                <v-card-text>
                                    <v-container grid-list-md>
                                        <v-layout wrap>
                                            <v-flex xs12 sm6 md6 v-for="field in inputs" :key="field.model">
                                                <v-autocomplete v-if="field.items" :items="items[field.items]"
                                                    item-text="Nombre" item-value="id" :label="field.label"
                                                    v-model="data[field.model]" required></v-autocomplete>
                                                <v-text-field v-else :label="field.label" v-model="data[field.model]"
                                                    required></v-text-field>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="error" @click="dialog = false">Cancelar</v-btn>
                                    <v-btn v-if="save" color="success" @click="saveCodigoManual()">Guardar
                                    </v-btn>
                                    <v-btn v-else color="warning" @click="updateCodigoManual()">Actualizar
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-layout>
                </v-container>
                <v-card-title>
                    <v-layout row wrap justify-center>
                        <h4 class="headline">Codigo Manual</h4>
                    </v-layout>
                    <v-layout row wrap>
                        <!-- <v-btn round small dark color="orange" @click="importFile = !importFile">
                            <v-icon left>cloud_upload</v-icon>
                        </v-btn> -->
                        <v-spacer></v-spacer>
                        <v-btn round small @click="createCodigoManual()" color="primary" dark>
                            <v-icon left>exposure_plus_1</v-icon>
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-btn fab color="info" dark small v-on="on" @click="updateCodigoall()">
                                    <v-icon>update</v-icon>
                                </v-btn>
                            </template>
                            <span>Actualizar codigos manual</span>
                        </v-tooltip>
                        <v-flex sm12 xs12>
                            <v-text-field v-model="search" append-icon="search" label="Buscar..." single-line
                                hide-details></v-text-field>
                        </v-flex>
                    </v-layout>
                </v-card-title>
                <v-data-table :loading="loading" :headers="headers" :items="codigoManuales" :search="search">
                    <template v-slot:items="props">
                        <td class="text-xs-right">{{ props.item.cup }}</td>
                        <td class="text-xs-right">{{ props.item.tipomanual }}</td>
                        <td class="text-xs-right">{{ props.item.Codigo }}</td>
                        <td class="text-xs-center">
                            <v-edit-dialog large lazy :return-value.sync="props.item.Descripcion" cancel-text="Cancelar"
                                save-text="Cambiar" @save="updateCodigoManual(props.item)">
                                <div>{{ props.item.Descripcion }} <v-icon color="warning" small>edit</v-icon>
                                </div>
                                <template v-slot:input>
                                    <v-text-field v-model="props.item.Descripcion" label="Editar" single-line>
                                    </v-text-field>
                                </template>
                            </v-edit-dialog>
                        </td>
                        <td class="text-xs-right">{{ props.item.Valor }}</td>
                        <td class="text-xs-right">{{ props.item.anio }}</td>
                        <td class="text-xs-right">
                            <v-tooltip top>
                                <template v-slot:activator="{ on }">
                                    <v-btn fab outline color="error" small v-on="on"
                                        @click="updateCodigoManual(props.item, true)">
                                        <v-icon>delete</v-icon>
                                    </v-btn>
                                </template>
                                <span>Inhabilitar</span>
                            </v-tooltip>
                        </td>
                    </template>
                    <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                        Your search for "{{ search }}" found no results.
                    </v-alert>
                </v-data-table>

                <v-dialog v-model="preload" persistent width="300">
                    <v-card color="primary" dark>
                        <v-card-text>
                            Estamos procesando su información
                            <v-progress-linear indeterminate color="white" class="mb-0">
                            </v-progress-linear>
                        </v-card-text>
                    </v-card>
                </v-dialog>
            </v-container>
        </v-card>
    </v-flex>
</template>

<script>
    import moment from "moment";
    moment.locale("es");
    export default {
        name: 'CodigoManual',
        components: {},
        data() {
            return {
                importFile: false,
                permisos: [],
                search: '',
                headers: [{
                        text: 'Cup',
                        align: 'right',
                        sortable: false,
                        value: 'cup'
                    },
                    {
                        text: 'Tipo Manual',
                        align: 'right',
                        sortable: false,
                        value: 'tipomanual'
                    },
                    {
                        text: 'Código',
                        align: 'right',
                        sortable: false,
                        value: 'Codigo'
                    },
                    {
                        text: 'Descripción',
                        align: 'left',
                        sortable: false,
                        value: 'Descripcion'
                    },
                    {
                        text: 'Valor',
                        align: 'right',
                        sortable: false,
                        value: 'Valor'
                    },
                    {
                        text: 'Año',
                        align: 'right',
                        sortable: true,
                        value: 'anio'
                    },
                    {
                        text: '',
                        align: 'right',
                        sortable: false,
                        value: ''
                    },
                ],
                inputs: [{
                        label: 'Tipo Manual',
                        model: 'Tipomanual_id',
                        items: 'tipoManuales'
                    },
                    {
                        label: 'Cup',
                        model: 'Cup_id',
                        items: 'cups'
                    },
                    {
                        label: 'Código',
                        model: 'Codigo'
                    },
                    {
                        label: 'Valor',
                        model: 'Valor'
                    },
                    {
                        label: 'Año',
                        model: 'anio',
                        items: 'listanio'
                    },
                ],
                save: true,
                dialog: false,
                data: {
                    Tipomanual_id: '',
                    Codigo: '',
                    Descripcion: '',
                    Valor: '',
                    Cup_id: ''
                },
                items: {
                    tipoManuales: [],
                    cups: [],
                    listanio: []
                },
                codigoManuales: [],
                nameFile: '',
                file: '',
                dialogUpAnio: false,
                anioBase: '',
                anioCrear: '',
                preload: false,
                loading: false
            }
        },
        mounted() {
            this.fetchCodigoManuales();
            this.fetchCups()
            this.fetchTipoManuales();
        },
        methods: {
            fetchCodigoManuales() {
                this.loading = true
                axios.get('/api/codigomanual/all')
                    .then(res => {
                        this.codigoManuales = res.data
                        this.loading = false
                    })
                    .catch(err => {
                        this.loading = false
                    });
            },
            fetchTipoManuales() {
                axios.get('/api/tipomanuales/all')
                    .then((res) => this.items.tipoManuales = res.data)
                    .catch((err) => console.log(err));
            },
            fetchCups() {
                axios.get('/api/cups/all')
                    .then((res) => this.items.cups = res.data.map((cup) => {
                        return {
                            id: cup.id,
                            Nombre: `${cup.Codigo} - ${cup.Nombre}`
                        }
                    }))
                    .catch((err) => console.log(err));
            },
            createCodigoManual() {
                this.arrayAnios();
                this.save = true;
                this.dialog = true;
                this.clearFields();
            },
            saveCodigoManual() {
                axios.post('/api/codigomanual/create', this.data)
                    .then((res) => {
                        this.dialog = false;
                        this.fetchCodigoManuales();
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
                        if (err.response.data.message) {
                            this.$alerError(err.response.data.message);
                        } else {
                            this.$alerError('Todos los campos son obligatorios');
                        }
                    })
            },
            editCodigoManual(codigoManual) {
                this.save = false;
                this.dialog = true;
                this.data = {
                    id: codigoManual.id,
                    Tipomanual_id: codigoManual.Tipomanual_id,
                    Codigo: codigoManual.Codigo,
                    Descripcion: codigoManual.Descripcion,
                    Valor: codigoManual.Valor,
                    Cup_id: codigoManual.Cup_id,
                };
            },
            updateCodigoManual(codigo, disabled) {
                let data = {
                    id: codigo.id,
                    descripcion: codigo.Descripcion,
                    disabled: disabled,
                }
                axios.put(`/api/codigomanual/edit/${data.id}`, data)
                    .then((res) => {
                        this.fetchCodigoManuales();
                        this.clearFields();
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
                    Tipomanual_id: '',
                    Codigo: '',
                    Descripcion: '',
                    Valor: '',
                    Cup_id: ''
                }
            },
            importCodigoManuales() {
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

                axios.post('/api/Codigo Manual/import', formData, {
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
            },
            arrayAnios() {
                let n = (new Date()).getFullYear()
                for (let i = n; i >= 2018; i--) {
                    this.items.listanio.push(i)
                }
            },
            updateCodigoall() {
                this.dialogUpAnio = true
                this.arrayAnios();
            },
            saveUpdateCodigo() {
                if (this.anioBase >= this.anioCrear || this.anioBase == '' || this.anioCrear == '') {
                    swal({
                        title: 'Error',
                        text: 'No puede crear un año menor o igual a el año base, o hay campos vacios.',
                        type: "error",
                        timer: 3000,
                        icon: "error",
                        buttons: false
                    });
                    return;
                }
                this.preload = true
                axios.post(`/api/codigomanual/updateAnio`, {
                    aniobase: this.anioBase,
                    aniocrear: this.anioCrear
                }).then((res) => {
                    this.fetchCodigoManuales();
                    this.clearUpdateCodigo();
                    this.preload = false
                    this.$alerSuccess('Codigos manual creados con exito!');
                }).catch((err => {
                    this.preload = false
                    this.$alerError(err.response.data.message);
                }))
            },
            clearUpdateCodigo() {
                this.anioBase = ''
                this.anioCrear = ''
                this.dialogUpAnio = false
            }
        }
    }

</script>

<style lang="scss" scoped>

</style>
