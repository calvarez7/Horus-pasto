<template>
    <div>
        <v-layout>
            <v-flex xs12 sm12 md12>
                <v-card>

                    <v-card>
                        <v-bottom-nav :value="true" color="transparent">
                            <v-btn color="primary" flat @click="parametrizacion = true, email = false">
                                <span>Parametrización</span>
                                <v-icon>list_alt</v-icon>
                            </v-btn>
                            <v-btn color="success" flat @click="email = true, parametrizacion = false">
                                <span>Email Prestador</span>
                                <v-icon>email</v-icon>
                            </v-btn>
                        </v-bottom-nav>
                    </v-card>

                    <v-card v-show="parametrizacion">
                        <v-layout row wrap>
                            <v-flex xs12 px-1>
                                <v-card>
                                    <v-container pa-1>

                                        <v-card-title>
                                            <v-layout row wrap justify-center>
                                                <h4 class="headline">Parametrización</h4>
                                            </v-layout>
                                            <v-layout row wrap>
                                                <v-spacer></v-spacer>
                                                <v-tooltip top>
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn round color="primary" dark small v-on="on"
                                                            @click="dialogStore = true, clearParametrizacion(), fetchTipos()">
                                                            <v-icon>exposure_plus_1</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Crear parametrización</span>
                                                </v-tooltip>
                                                <v-flex sm12 xs12>
                                                    <v-text-field v-model="search" append-icon="search"
                                                        label="Buscar" single-line hide-details></v-text-field>
                                                </v-flex>
                                            </v-layout>
                                        </v-card-title>

                                        <v-data-table :headers="headers" :items="codigoglosas" :search="search">
                                            <template v-slot:items="props">
                                                <td>{{ props.item.id }}</td>
                                                <td>{{ props.item.descripcion }}</td>
                                                <td>{{ props.item.Nombre }}</td>
                                                <td>{{ props.item.codigo }}</td>
                                                <td>
                                                    <v-tooltip top>
                                                        <template v-slot:activator="{ on }">
                                                            <v-btn fab outline color="error" small v-on="on"
                                                                @click="updateParametrizacion(props.item)">
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

                                        <v-dialog v-model="dialogStore" max-width="800px">
                                            <v-card>
                                                <v-toolbar dark color="primary">
                                                    <v-flex xs12 text-xs-center flat dark>
                                                        <v-toolbar-title>Crear Parametrización</v-toolbar-title>
                                                    </v-flex>
                                                </v-toolbar>
                                                <v-card-text>
                                                    <v-container grid-list-md>
                                                        <v-layout wrap>
                                                            <v-flex xs12 sm12 md12>
                                                                <v-text-field label="Descripcion"
                                                                    v-model="datos.descripcion"></v-text-field>
                                                            </v-flex>
                                                            <v-flex xs12 sm6 md6>
                                                                <v-select label="Tipo" :items="tipos"
                                                                    v-model="datos.tipo_id" autocomplete
                                                                    item-text="Nombre" item-value="id"></v-select>
                                                            </v-flex>
                                                            <v-flex xs12 sm4 md4>
                                                                <v-text-field label="Codigo" v-model="datos.codigo">
                                                                </v-text-field>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-container>
                                                </v-card-text>
                                                <v-card-actions>
                                                    <v-spacer></v-spacer>
                                                    <v-btn color="error" @click="dialogStore = false">Cancelar</v-btn>
                                                    <v-btn color="success" @click="saveParametrizacion()">
                                                        Guardar
                                                    </v-btn>
                                                </v-card-actions>
                                            </v-card>
                                        </v-dialog>

                                    </v-container>
                                </v-card>
                            </v-flex>
                        </v-layout>
                    </v-card>

                    <v-card v-show="email">
                        <v-layout row wrap>
                            <v-flex xs12 px-1>
                                <v-card>
                                    <v-container pa-1>

                                        <v-card-title>
                                            <v-layout row wrap justify-center>
                                                <h4 class="headline">Email Prestador</h4>
                                            </v-layout>
                                            <v-layout row wrap>
                                                <v-spacer></v-spacer>
                                                <v-tooltip top>
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn round color="primary" dark small v-on="on"
                                                            @click="dialogStoreEmail = true, clearEmail(), fetchProveedores()">
                                                            <v-icon>exposure_plus_1</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Crear Email Prestador</span>
                                                </v-tooltip>
                                                <v-flex sm12 xs12>
                                                    <v-text-field v-model="searchEmail" append-icon="search"
                                                        label="Buscar" single-line hide-details></v-text-field>
                                                </v-flex>
                                            </v-layout>
                                        </v-card-title>

                                        <v-data-table :headers="headersEmail" :items="fullnameprestadorEmail"
                                            :search="searchEmail">
                                            <template v-slot:items="props">
                                                <td>{{ props.item.id }}</td>
                                                <td>{{ props.item.prestador }}</td>
                                                <td class="text-xs-right">
                                                    <v-edit-dialog large lazy :return-value.sync="props.item.email"
                                                        cancel-text="Cancelar" save-text="Cambiar"
                                                        @save="editEmail(props.item)">
                                                        <div>{{ props.item.email }} <v-icon color="warning" small>edit
                                                            </v-icon>
                                                        </div>
                                                        <template v-slot:input>
                                                            <v-text-field type="email" v-model="props.item.email"
                                                                label="Editar Email">
                                                            </v-text-field>
                                                        </template>
                                                    </v-edit-dialog>
                                                </td>
                                            </template>
                                            <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                                                Your search for "{{ searchEmail }}" found no results.
                                            </v-alert>
                                        </v-data-table>

                                        <v-dialog v-model="dialogStoreEmail" max-width="800px">
                                            <v-card>
                                                <v-toolbar dark color="primary">
                                                    <v-flex xs12 text-xs-center flat dark>
                                                        <v-toolbar-title>Crear Email Prestador</v-toolbar-title>
                                                    </v-flex>
                                                </v-toolbar>
                                                <v-card-text>
                                                    <v-container grid-list-md>
                                                        <v-layout wrap>
                                                            <v-flex xs12 sm12 md12>
                                                                <v-autocomplete v-model="emailprestador.prestador_id"
                                                                    :items="allProveedores" item-value="id"
                                                                    item-text="name" label="Nit-Prestador" required>
                                                                </v-autocomplete>
                                                            </v-flex>
                                                            <v-flex xs12 sm12 md12>
                                                                <v-text-field label="Email" type="email"
                                                                    prepend-icon="mail" v-model="emailprestador.email">
                                                                </v-text-field>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-container>
                                                </v-card-text>
                                                <v-card-actions>
                                                    <v-spacer></v-spacer>
                                                    <v-btn color="error" @click="dialogStoreEmail = false">Cancelar
                                                    </v-btn>
                                                    <v-btn color="success" @click="savePrestadorEmail()">
                                                        Guardar
                                                    </v-btn>
                                                </v-card-actions>
                                            </v-card>
                                        </v-dialog>

                                    </v-container>
                                </v-card>
                            </v-flex>
                        </v-layout>
                    </v-card>

                </v-card>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
    import {
        PrestadorService
    } from '../../../services';
    export default {
        name: 'MedicalBillsAdmin',
        data() {
            return {
                parametrizacion: false,
                search: '',
                headers: [{
                        text: 'id',
                        align: 'left',
                        sortable: false,
                        value: 'id'
                    },
                    {
                        text: 'Descripción',
                        align: 'left',
                        sortable: false,
                        value: 'descripcion'
                    },
                    {
                        text: 'Tipo',
                        align: 'left',
                        sortable: false,
                        value: 'tipo'
                    },
                    {
                        text: 'Código',
                        align: 'left',
                        sortable: false,
                        value: 'codigo'
                    },
                    {
                        text: '',
                        align: 'left',
                        sortable: false,
                        value: ''
                    }
                ],
                headersEmail: [{
                        text: 'id',
                        align: 'left',
                        sortable: false,
                        value: 'id'
                    },
                    {
                        text: 'Prestador',
                        align: 'left',
                        sortable: false,
                        value: 'prestador'
                    },
                    {
                        text: 'Email',
                        align: 'left',
                        sortable: false,
                        value: 'email'
                    },
                ],
                dialogStore: false,
                datos: {},
                tipos: [],
                codigoglosas: [],
                email: false,
                dialogStoreEmail: false,
                emailprestador: {},
                listProveedores: [],
                searchEmail: '',
                allemailPrestador: []
            }
        },
        mounted() {
            this.fetchCodigoglosas();
            this.fetchEmailPrestador();
        },
        computed: {
            allProveedores() {
                return this.listProveedores.map((proveedor) => {
                    return {
                        id: proveedor.Prestador_id,
                        name: `${proveedor.nit} - ${proveedor.Nombre}`
                    }
                })
            },
            fullnameprestadorEmail() {
                return this.allemailPrestador.map((eprestador) => {
                    return {
                        id: eprestador.id,
                        prestador: `${eprestador.Nit} - ${eprestador.Nombre}`,
                        email: eprestador.email
                    }
                })
            },
        },
        methods: {
            fetchTipos() {
                axios.get('/api/codigoglosa/tipos')
                    .then(res => this.tipos = res.data);
            },
            fetchCodigoglosas() {
                axios.get('/api/codigoglosa/all')
                    .then(res => this.codigoglosas = res.data);
            },
            saveParametrizacion() {
                axios.post('/api/codigoglosa/create', this.datos)
                    .then((res) => {
                        this.dialogStore = false;
                        this.clearParametrizacion();
                        this.fetchCodigoglosas();
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
                        this.$alerError('Debe llenar los campos obligatorios o el codigo ya existe');
                    })
            },
            clearParametrizacion() {
                for (const prop of Object.getOwnPropertyNames(this.datos)) {
                    this.datos[prop] = "";
                };
            },
            updateParametrizacion(item) {
                axios.put(`/api/codigoglosa/edit/${item.id}`)
                    .then((res) => {
                        this.fetchCodigoglosas();
                        swal({
                            title: `${res.data.message}`,
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch((err) => console.log(err));
            },
            async fetchProveedores() {
                try {
                    this.listProveedores = await PrestadorService.getPrestadores();
                } catch (error) {
                    console.log(error)
                }
            },
            clearEmail() {
                for (const prop of Object.getOwnPropertyNames(this.emailprestador)) {
                    this.emailprestador[prop] = "";
                };
            },
            savePrestadorEmail() {
                if (this.emailprestador.email) {
                    var regex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
                    if (!regex.test(this.emailprestador.email)) {
                        this.$alerError("Debe ingresar un email valido")
                        return;
                    }
                }
                axios.post('/api/emailcmedica/create', this.emailprestador)
                    .then((res) => {
                        this.dialogStoreEmail = false;
                        this.clearEmail();
                        this.fetchEmailPrestador();
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
                        let msg = '';
                        for (const pro in err.response.data) {
                            if (msg) msg += `${err.response.data[pro]}`
                            else msg = err.response.data[pro]
                        }
                        swal({
                            title: msg,
                            text: " ",
                            type: "error",
                            icon: "error",
                        });
                    })
            },
            fetchEmailPrestador() {
                axios.get('/api/emailcmedica/all')
                    .then(res => this.allemailPrestador = res.data);
            },
            editEmail(emailPrestador) {
                if (emailPrestador.email == '') {
                    return;
                }
                if (emailPrestador.email) {
                    var regex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
                    if (!regex.test(emailPrestador.email)) {
                        this.$alerError("Debe ingresar un email valido")
                        return;
                    }
                }
                axios.put(`/api/emailcmedica/edit/${emailPrestador.id}`, emailPrestador)
                    .then((res) => {
                        this.fetchEmailPrestador();
                        swal({
                            title: `${res.data.message}`,
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch((err) => console.log(err));
            }
        }
    }

</script>
