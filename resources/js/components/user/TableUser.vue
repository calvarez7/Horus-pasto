<template>
    <v-card min-height="100%">
        <v-container pa-1>
            <v-container pt-3 pb-1>
                <v-layout row>
                    <v-dialog v-model="dialog" persistent max-width="976px">
                        <v-card>
                            <v-card-title class="headline success" style="color:white">
                                <span v-if="save" class="headline">Crear Usuario</span>
                                <span v-else class="headline">Editar Usuario</span>
                            </v-card-title>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs12 sm6 md6>
                                            <v-text-field label="Nombre*" v-model="data.name" required></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-text-field label="Apellido" v-model="data.apellido"></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-select :items="nit" label="Tipo de Documento" v-model="data.nit"
                                                required></v-select>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-text-field label="Número de Cédula*" v-model="data.cedula" required>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-text-field label="Correo Electrónico*" v-model="data.email" required>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-text-field label="Registro Medico" v-model="data.Registromedico"
                                                required>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm12 md12>
                                            <v-autocomplete v-model="data.prestador_id" :items="prestadores" item-value="id" item-text="Nombre" label="Prestador"></v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-checkbox v-model="contrasena" color="red" label="Cambiar Contraseña">
                                            </v-checkbox>
                                        </v-flex>
                                        <v-flex xs12 v-if="contrasena == true">
                                            <v-text-field label="Contraseña*" v-model="data.password" type="password"
                                                required></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 v-if="contrasena == true">
                                            <v-text-field label="Confirmar contraseña*"
                                                v-model="data.password_confirmation" type="password" required>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-autocomplete :items="allCargos" item-text="nombre" label="Especialidad Medica*"
                                                v-model="data.especialidad_medico" required>
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 sm12>
                                            <input type="file" id="file" ref="mysignature" class="custom-file-input"
                                                v-on:change="onFilePicked" />
                                            <v-btn color="blue darken-1" flat @click="updatefirma()">Actualizar firma
                                            </v-btn>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="error" @click="dialog = false">Cancelar</v-btn>
                                <v-btn v-if="save" color="success" @click="registerUser()">Guardar</v-btn>
                                <v-btn v-else color="warning" @click="updateUser()">Actualizar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <v-dialog v-model="dialogPermission" persistent max-width="976px">
                        <v-card>
                            <v-card-title class="headline success" style="color:white">
                                <span class="headline">Permisos</span>
                            </v-card-title>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs12 sm6 md6>
                                            <v-text-field label="Nombre" readonly v-model="dato.name"></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-text-field label="Correo Electrónico" readonly v-model="dato.email">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-layout row wrap>
                                                <v-flex xs12>
                                                    <span>Seleccione permiso</span>
                                                </v-flex>
                                                <v-flex xs11>
                                                    <v-autocomplete v-model="permiso" :items="allPermissions"
                                                        item-text="name" item-value="id" chips multiple>
                                                        <template v-slot:selection="data">
                                                            <v-chip :selected="data.selected" close
                                                                class="chip--select-multi"
                                                                @input="remove_permission(data.item.id)">
                                                                {{ data.item.name }}
                                                            </v-chip>
                                                        </template>
                                                        <template v-slot:item="data">
                                                            <template v-if="typeof data.item.name !== 'object'">
                                                                <v-list-tile-content v-text="data.item.name">
                                                                </v-list-tile-content>
                                                            </template>
                                                            <template v-else>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title v-html="data.item">
                                                                    </v-list-tile-title>
                                                                    <v-list-tile-sub-title v-html="data.item">
                                                                    </v-list-tile-sub-title>
                                                                </v-list-tile-content>
                                                            </template>
                                                        </template>
                                                    </v-autocomplete>
                                                </v-flex>
                                                <v-flex xs1>
                                                    <v-tooltip bottom>
                                                        <template v-slot:activator="{ on }">
                                                            <v-btn v-on="on" color="success" fab small dark @click="savePermission(permiso)">
                                                                <v-icon>add</v-icon>
                                                            </v-btn>
                                                        </template>
                                                        <span>Agregar</span>
                                                    </v-tooltip>
                                                </v-flex>
                                            </v-layout>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-card>
                                                <v-card-title>
                                                    <v-tooltip bottom>
                                                        <template v-slot:activator="{ on }">
                                                            <v-btn v-on="on" color="error" fab small dark
                                                                @click="deletePermission(selected)">
                                                                <v-icon>remove</v-icon>
                                                            </v-btn>
                                                        </template>
                                                        <span>Eliminar</span>
                                                    </v-tooltip>
                                                    <v-spacer></v-spacer>
                                                    <v-text-field v-model="searchPermission" append-icon="search"
                                                        label="Search" single-line hide-details></v-text-field>
                                                </v-card-title>
                                                <v-data-table class="elevation-1" v-model="selected" select-all dense
                                                    :headers="headersPermission" :items="userPermissions"
                                                    :search="searchPermission">
                                                    <template v-slot:items="props">
                                                        <tr :active="props.selected"
                                                            @click="props.selected = !props.selected">
                                                            <td>
                                                                <v-checkbox primary hide-details color="primary"
                                                                    :input-value="props.selected">
                                                                </v-checkbox>
                                                            </td>
                                                            <td>{{ props.item.id }}</td>
                                                            <td>{{ props.item.name }}</td>
                                                            <td>{{ props.item.Tipo }}</td>
                                                        </tr>
                                                    </template>
                                                    <v-alert v-slot:no-results :value="true" color="error"
                                                        icon="warning">
                                                        Your search for "{{ search }}" found no results.
                                                    </v-alert>
                                                </v-data-table>
                                            </v-card>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" flat @click="closePermission()">Cancelar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>

                    <v-dialog v-model="dialogRoles" persistent max-width="976px">
                        <v-card>
                            <v-card-title class="headline success" style="color:white">
                                <span class="headline">Roles</span>
                            </v-card-title>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs12 sm6 md6>
                                            <v-text-field label="Nombre" readonly v-model="data.name"></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-text-field label="Correo Electrónico" readonly v-model="data.email">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-layout row wrap>
                                                <v-flex xs12>
                                                    <span>Seleccione rol</span>
                                                </v-flex>
                                                <v-flex xs11 v-if="roles">
                                                    <v-autocomplete v-model="role" :items="roles" item-text="name"
                                                        item-value="id" chips multiple>
                                                        <template v-slot:selection="data">
                                                            <v-chip :selected="data.selected" close
                                                                class="chip--select-multi"
                                                                @input="remove_roles(data.item.id)">
                                                                {{ data.item.name }}
                                                            </v-chip>
                                                        </template>
                                                        <template v-slot:item="data">
                                                            <template v-if="typeof data.item.name !== 'object'">
                                                                <v-list-tile-content v-text="data.item.name">
                                                                </v-list-tile-content>
                                                            </template>
                                                            <template v-else>
                                                                <v-list-tile-content>
                                                                    <v-list-tile-title v-html="data.item">
                                                                    </v-list-tile-title>
                                                                    <v-list-tile-sub-title v-html="data.item">
                                                                    </v-list-tile-sub-title>
                                                                </v-list-tile-content>
                                                            </template>
                                                        </template>
                                                    </v-autocomplete>
                                                </v-flex>
                                                <v-flex xs1>
                                                    <v-tooltip bottom>
                                                        <template v-slot:activator="{ on }">
                                                            <v-btn v-on="on" color="success" fab small dark
                                                                @click="saveRoles()">
                                                                <v-icon>add</v-icon>
                                                            </v-btn>
                                                        </template>
                                                        <span>Agregar</span>
                                                    </v-tooltip>
                                                </v-flex>
                                            </v-layout>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-card>
                                                <v-card-title>
                                                    <v-tooltip bottom>
                                                        <template v-slot:activator="{ on }">
                                                            <v-btn v-on="on" color="error" fab small dark
                                                                @click="deleteRol(selected)">
                                                                <v-icon>remove</v-icon>
                                                            </v-btn>
                                                        </template>
                                                        <span>Eliminar</span>
                                                    </v-tooltip>
                                                    <v-spacer></v-spacer>
                                                    <v-text-field v-model="searchRol" append-icon="search"
                                                        label="Search" single-line hide-details></v-text-field>
                                                </v-card-title>
                                                <v-data-table class="elevation-1" v-model="selected" select-all
                                                    :headers="headersRoles" :items="userRoles" :search="searchRol">
                                                    <template v-slot:items="props">
                                                        <tr :active="props.selected"
                                                            @click="props.selected = !props.selected">
                                                            <td>
                                                                <v-checkbox primary hide-details color="primary"
                                                                    :input-value="props.selected">
                                                                </v-checkbox>
                                                            </td>
                                                            <td>{{ props.item.id }}</td>
                                                            <td>{{ props.item.name }}</td>
                                                        </tr>
                                                    </template>
                                                    <v-alert v-slot:no-results :value="true" color="error"
                                                        icon="warning">
                                                        Your search for "{{ search }}" found no results.
                                                    </v-alert>
                                                </v-data-table>
                                            </v-card>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" flat @click="closeRoles()">Cancelar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-layout>
            </v-container>
            <v-card-title>
                <v-btn v-if="can('usuarios.crear')" round color="primary" @click="createUser()" dark>
                    <v-icon left>person_add</v-icon>Crear usuario
                </v-btn>
                <v-btn v-if="can('usuarios.crear')" round color="warning" @click="especialidadCargo = true" dark>
                    <v-icon left>person_add</v-icon>Crear Especialidad (cargo)
                </v-btn>
                <v-spacer></v-spacer>
                <v-flex xs12 sm5>
                    <v-text-field v-model="search" append-icon="search" label="Buscar..." single-line hide-details>
                    </v-text-field>
                </v-flex>
            </v-card-title>
            <v-data-table class="mx-2" :headers="headers" :items="users" :search="search">
                <template v-slot:items="props">
                    <td>{{ props.item.id }}</td>
                    <td class="text-xs-right">{{ props.item.name }}</td>
                    <td class="text-xs-right">{{ props.item.apellido }}</td>
                    <td class="text-xs-right">{{ props.item.cedula }}</td>
                    <td class="text-xs-right">{{ props.item.email }}</td>
                    <td class="text-xs-right">{{ props.item.especialidad_medico }}</td>
                    <td class="text-xs-right">{{ props.item.estado_user | estado_u }}</td>
                    <td class="text-xs-right">
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-btn v-if="can('usuarios.rol')" fab outline color="primary" small v-on="on"
                                    @click="openRoles(props.item)">
                                    <v-icon>group_add</v-icon>
                                </v-btn>
                            </template>
                            <span>Roles</span>
                        </v-tooltip>
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-btn v-if="can('usuarios.permiso')" fab outline color="green" small v-on="on"
                                    @click="permission(props.item)">
                                    <v-icon>lock_open</v-icon>
                                </v-btn>
                            </template>
                            <span>Permisos</span>
                        </v-tooltip>
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-btn v-if="can('usuarios.editar')" fab outline color="warning" small v-on="on"
                                    @click="editUser(props.item)">
                                    <v-icon>edit</v-icon>
                                </v-btn>
                            </template>
                            <span>Editar</span>
                        </v-tooltip>
                        <v-tooltip top v-if="props.item.estado_user == 1">
                            <template v-slot:activator="{ on }">
                                <v-btn v-if="can('usuarios.inactivar')" fab outline color="error" small v-on="on"
                                    @click="disableUser(props.item)">
                                    <v-icon>person_add_disabled</v-icon>
                                </v-btn>
                            </template>
                            <span>Inactivar</span>
                        </v-tooltip>
                        <v-tooltip top v-if="props.item.estado_user == 2">
                            <template v-slot:activator="{ on }">
                                <v-btn v-if="can('usuarios.activar')" fab outline color="success" small v-on="on"
                                    @click="activarUser(props.item)">
                                    <v-icon>person_add_disabled</v-icon>
                                </v-btn>
                            </template>
                            <span>Activar</span>
                        </v-tooltip>
                    </td>
                </template>
                <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                    Your search for "{{ search }}" found no results.
                </v-alert>
            </v-data-table>
            <v-dialog v-model="especialidadCargo" persistent max-width="976px">
                <v-card>
                    <v-card-title class="headline success" style="color:white">
                        <span class="headline">Crear nueva especialidad o cargo</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm6 md6>
                                    <v-text-field label="Nombre" v-model="especialidad.nombre" :error-messages="perro">
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="success" @click="crearEspecialidad()">Guardar</v-btn>
                        <v-btn color="red" dark @click="cerrarModal()">Cancelar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-container>
    </v-card>

</template>

<script>
    import Swal from 'sweetalert2';
    import {
        UserService,
        RoleService
    } from './../../services';
    import {
        mapGetters
    } from "vuex";
    export default {
        data() {
            return {
                prestadores:[],
                especialidad: {
                    nombre: ''
                },
                especialidadCargo: false,
                contrasena: false,
                users: [],
                search: '',
                searchPermission: '',
                searchRol: '',
                headers: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Nombre',
                        align: 'left',
                        sortable: false,
                        value: 'name'
                    },
                    {
                        text: 'Apellido',
                        align: 'right',
                        sortable: false,
                        value: 'apellido'
                    },
                    {
                        text: 'Cedula',
                        align: 'right',
                        sortable: false,
                        value: 'cedula'
                    },
                    {
                        text: 'Correo Electrónico',
                        align: 'left',
                        sortable: false,
                        value: 'email'
                    },
                    {
                        text: 'Especialidad',
                        align: 'left',
                        sortable: false,
                        value: 'especialidad_medico'
                    },
                    {
                        text: 'Estado',
                        align: 'right',
                        sortable: false,
                        value: 'estado_user'
                    },
                    {
                        text: '',
                        align: 'right',
                        sortable: false,
                        value: 'actions'
                    },
                ],
                headersPermission: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Nombre',
                        align: 'left',
                        value: 'name'
                    },
                    {
                        text: 'Tipo',
                        align: 'left',
                        value: 'Tipo'
                    }
                ],
                dialog: false,
                save: true,
                data: {
                    name: '',
                    apellido: '',
                    nit: '',
                    cedula: '',
                    Registromedico: '',
                    especialidad_medico: '',
                    email: '',
                    estado_user: '',
                    password: '',
                    password_confirmation: '',
                    roles: null,
                    prestador_id: null
                },
                roles: null,
                nit: ['CC', 'CE', 'NIT', 'PA'],
                especialidad_medico: [],
                dialogPermission: false,
                dato: {},
                allPermissions: [],
                permiso: [],
                userPermissions: [],
                selected: [],
                dialogRoles: false,
                headersRoles: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Nombre',
                        align: 'left',
                        value: 'name'
                    }
                ],
                userRoles: [],
                role: [],
                perro: [],
                allCargos: []
            }
        },
        filters: {
            estado_u: function (value) {
                if (value == 1) {
                    return 'Activo'
                } else if (value !== 2) {
                    return 'Inactivo';
                }
            }
        },
        computed: {
            ...mapGetters(['can'])
        },
        mounted() {
            this.fetchUsers();
            this.fetchRoles();
            this.allEspecialidades()
        },
        methods: {
            onFilePicked() {
                this.data.firma = this.$refs.mysignature.files[0];
            },
            fetchUsers: async function () {
                try {
                    this.users = await UserService.all();
                } catch (err) {
                    console.log(err)
                }
            },
            createUser() {
                this.emptyData();
                this.dialog = true;
                this.save = true;
            },
            registerUser: async function () {
                try {
                    await UserService.create(this.data);
                    this.emptyData();
                    this.dialog = false;
                    this.fetchUsers();

                    swal({
                        title: "¡Usuario Creado!",
                        text: " ",
                        type: "success",
                        timer: 2000,
                        icon: "success",
                        buttons: false
                    });

                } catch (error) {
                    console.log(error)
                }
            },
            showError(message) {
                swal({
                    title: "¡No puede ser!",
                    text: `${message.email}`,
                    icon: "warning",
                });
            },
            emptyData() {
                this.data = {
                    name: '',
                    apellido: '',
                    cedula: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    contrasena: false,
                    roles: null
                }
            },
            fetchRoles: async function () {
                try {
                    this.roles = await RoleService.all();
                } catch (error) {
                    console.log(error)
                }
            },
            editUser(row) {
                this.fetchPrestadores();
                this.data = {
                    id: row.id,
                    name: row.name,
                    apellido: row.apellido,
                    nit: row.nit,
                    Registromedico: row.Registromedico,
                    especialidad_medico: row.especialidad_medico,
                    cedula: row.cedula,
                    email: row.email,
                    roles: row.roles.map(rol => rol.name),
                    prestador_id: parseInt(row.prestador_id)
                }
                this.dialog = true;
                this.save = false;
            },
            updateUser: async function () {
                try {
                    await UserService.update(this.data.id, this.data);
                    this.emptyData();
                    this.dialog = false;
                    this.fetchUsers();
                    swal({
                        title: "¡Usuario Actualizado!",
                        text: " ",
                        type: "success",
                        timer: 2000,
                        icon: "success",
                        buttons: false
                    });
                } catch (error) {
                    console.log(error)
                }
            },
            updatefirma() {
                let formData = new FormData();
                formData.append("file", this.data.firma);
                axios.post(`/api/user/edit/${this.data.id}`, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(res => {
                        this.emptyData();
                        this.dialog = false;
                        this.fetchUsers();
                        swal({
                            title: "¡Usuario Actualizado!",
                            text: " ",
                            type: "success",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch(err => console.log('err.response.data', err.response.data))
            },
            disableUser(user) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Una vez el usuario esté deshabilitado, ya no podrá ingresar al sistema!",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.put(`/api/user/available/${user.id}`, {
                                estado_user: 2
                            })
                            .then(res => {
                                this.fetchUsers();
                                swal("¡Usuario Deshabilitado!", {
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                            })
                            .catch(err => console.log(err.response.data));
                    }
                })

            },
            activarUser(user) {
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Se activara el usuario en la plataforma!",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.put(`/api/user/available/${user.id}`, {
                                estado_user: 1
                            })
                            .then(res => {
                                swal("¡Usuario Activado!", {
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                                this.fetchUsers();
                            })
                            .catch(err => console.log(err.response.data));
                    }
                })

            },
            toggleAll() {
                if (this.selected.length) this.selected = []
                else this.selected = this.userPermissions.slice()
            },
            permissionsAll() {
                axios.get('/api/permiso/all').then(res => {
                    this.allPermissions = res.data;
                });
            },
            remove_permission(item) {
                this.permiso.splice(this.permiso.indexOf(item), 1)
                this.permiso = [...this.permiso]
            },
            permissionUser(user) {
                axios.post(`/api/permiso/permissionuser/${user.id}`).then(res => {
                    this.userPermissions = res.data;
                });
            },
            permission(user) {
                this.dato = user
                this.permissionsAll();
                this.permissionUser(user);
                this.dialogPermission = true
            },
            closePermission() {
                this.dialogPermission = false
                this.permiso = ''
                this.searchPermission = ''
                this.selected = []
            },
            savePermission(permiso) {
                if (permiso == '') return
                var formData = new FormData();
                for (let i = 0; i < permiso.length; i++) {
                    formData.append(`permiso[]`, permiso[i]);
                }
                axios.post(`/api/user/addPermissions/${this.dato.id}`, formData).then(res => {
                        this.$alerSuccess('Permiso asignado con Exito!');
                        this.permissionUser(this.dato);
                        this.permiso = ''
                        this.searchPermission = ''
                        this.fetchUsers();
                    })
                    .catch(err => {
                        let msg = '';
                        for (const pro in err.response.data) {
                            if (msg) msg += `${err.response.data[pro]}`
                            else msg = err.response.data[pro]
                        }
                        this.$alerError(msg);
                    });
            },
            deletePermission() {
                if (this.selected == '') return
                var formData = new FormData();
                for (let i = 0; i < this.selected.length; i++) {
                    formData.append(`permiso[]`, this.selected[i].id);
                }
                axios.post(`/api/user/deletePermission/${this.dato.id}`, formData).then(res => {
                        this.$alerSuccess('Permiso eliminado con Exito!');
                        this.permissionUser(this.dato);
                        this.permiso = ''
                        this.searchPermission = ''
                        this.selected = []
                        this.fetchUsers();
                    })
                    .catch(err => {
                        let msg = '';
                        for (const pro in err.response.data) {
                            if (msg) msg += `${err.response.data[pro]}`
                            else msg = err.response.data[pro]
                        }
                        this.$alerError(msg);
                    });
            },
            openRoles(row) {
                this.data = {
                    id: row.id,
                    name: row.name,
                    email: row.email,
                    roles: row.roles.map(rol => rol.id)
                }
                this.dialogRoles = true;
                this.roleUser(row);
            },
            closeRoles() {
                this.dialogRoles = false
                this.data.roles = ''
                this.searchRol = ''
                this.selected = []
            },
            remove_roles(item) {
                this.role.splice(this.role.indexOf(item), 1)
                this.role = [...this.role]
            },
            saveRoles() {
                if (this.role == '') return
                var formData = new FormData();
                for (let i = 0; i < this.role.length; i++) {
                    formData.append(`role[]`, this.role[i]);
                }
                axios.post(`/api/user/addRol/${this.data.id}`, formData).then(res => {
                        this.$alerSuccess('Rol asignado con Exito!');
                        this.roleUser(this.data)
                        this.role = ''
                        this.searchRol = ''
                        this.fetchUsers();
                    })
                    .catch(err => {
                        let msg = '';
                        for (const pro in err.response.data) {
                            if (msg) msg += `${err.response.data[pro]}`
                            else msg = err.response.data[pro]
                        }
                        this.$alerError(msg);
                    });
            },
            roleUser(user) {
                axios.post(`/api/role/roleuser/${user.id}`).then(res => {
                    this.userRoles = res.data;
                });
            },
            deleteRol() {
                if (this.selected == '') return
                var formData = new FormData();
                for (let i = 0; i < this.selected.length; i++) {
                    formData.append(`rol[]`, this.selected[i].id);
                }
                axios.post(`/api/user/deleteUser/${this.data.id}`, formData).then(res => {
                        this.$alerSuccess('Rol eliminado con Exito!');
                        this.roleUser(this.data);
                        this.fetchUsers();
                        this.role = ''
                        this.searchRol = ''
                        this.selected = []
                    })
                    .catch(err => {
                        let msg = '';
                        for (const pro in err.response.data) {
                            if (msg) msg += `${err.response.data[pro]}`
                            else msg = err.response.data[pro]
                        }
                        this.$alerError(msg);
                    });
            },
            async crearEspecialidad() {
                axios.post('/api/user/guardarEspecialidad', this.especialidad).then(res => {
                    this.clearEspecialidad();
                    this.especialidadCargo = false
                    this.clearErrorInputs()
                }).catch(res => {
                    this.setErrorInputs(res.response.data)
                });
                this.allEspecialidades();
            },
            clearEspecialidad() {
                this.especialidad.nombre = ''
            },

            setErrorInputs(errors) {
                for (const error in errors) {
                    this.perro = errors[error]
                }
            },

            cerrarModal(){
                this.clearError()
                this.especialidadCargo = false
            },

            clearError() {
                this.perro = ''
            },

            allEspecialidades() {
                axios.get('/api/user/allEspecialidades').then(res => {
                    this.allCargos = res.data
                })
            },
            fetchPrestadores() {
                axios.get('/api/prestador/all')
                    .then((res) => {
                        this.prestadores = res.data
                    })
                    .catch((err) => console.log(err));
            },
        },
    }

</script>
<style scoped>
    table.v-table tbody td,
    table.v-table tbody th {
        height: 19px;
    }

</style>
