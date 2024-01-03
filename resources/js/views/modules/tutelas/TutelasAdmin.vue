<template>
    <v-card>
        <v-container grid-list-md fluid>
            <v-layout wrap>
                <v-flex xs12>
                    <v-card-text class="px-0">
                        <v-card>
                            <v-card-title>
                                <div class="text-xs-center">
                                    <v-dialog v-model="dialogResponsable" persistent max-width="900px">
                                        <template v-slot:activator="{ on }">
                                            <v-btn color="primary" dark v-on="on">
                                                Nuevo Responsable
                                            </v-btn>
                                        </template>
                                        <v-card>
                                            <v-card-title class="primary white--text">
                                                <span class="headline">Agregar nuevo Responsable</span>
                                            </v-card-title>
                                            <v-card-text>
                                                <v-container grid-list-md>
                                                    <v-layout wrap>
                                                        <v-flex xs12>
                                                            <v-autocomplete :items="Roles" item-text="name"
                                                                item-value="id" label="RESPONSABLE" v-model="Roles.id">
                                                            </v-autocomplete>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card-text>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="red darken-3" flat @click="clearResponsable()">Cancelar
                                                </v-btn>
                                                <v-btn color="green darken-3" flat @click="saveResponsable()">Guardar
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </div>
                                <v-spacer></v-spacer>
                                <v-text-field v-model="search" append-icon="search" label="Search" single-line
                                    hide-details></v-text-field>
                            </v-card-title>
                            <v-data-table :headers="responsable_he" :items="responsables" :search="search">
                                <template v-slot:items="props">
                                    <td>{{ props.item.id }}</td>
                                    <td>{{ props.item.NombreRol }}</td>
                                    <td>{{ props.item.NombreEstado }}</td>
                                    <td>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab outline color="error" small v-on="on"
                                                    @click="disableResponsable(props.item)">
                                                    <v-icon>person_add_disabled</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Inactivar</span>
                                        </v-tooltip>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab outline color="success" v-on="on" small
                                                    @click="enableResponsable(props.item)">
                                                    <v-icon>add</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Activar</span>
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
                    </v-card-text>
                </v-flex>
                <v-flex xs6>
                    <v-card-text class="px-0">
                        <v-card>
                            <v-card-title>
                                <div class="text-xs-center">
                                    <v-dialog v-model="dialogRequerimiento" persistent max-width="900px">
                                        <template v-slot:activator="{ on }">
                                            <v-btn color="primary" dark v-on="on">
                                                Nuevo Tipo de Requerimiento
                                            </v-btn>
                                        </template>
                                        <v-card>
                                            <v-card-title class="primary white--text">
                                                <span class="headline">Crear Nuevo Tipo de Requerimiento</span>
                                            </v-card-title>
                                            <v-card-text>
                                                <v-container grid-list-md>
                                                    <v-layout wrap>
                                                        <v-flex xs12 sm6>
                                                            <v-text-field label="Nombre*" required
                                                                v-model="datostiporequerido.Nombre"
                                                                hint="Este campo es requerido">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs12 sm6 md4>
                                                            <v-text-field label="Dias*" hint="Este campo es requerido"
                                                                v-model="datostiporequerido.Dias">
                                                            </v-text-field>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card-text>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="red darken-3" flat @click="clearRequerimiento()">Cancelar
                                                </v-btn>
                                                <v-btn color="green darken-3" flat @click="saveRequerimiento()">Guardar
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </div>
                                <v-spacer></v-spacer>
                                <v-text-field v-model="search1" append-icon="search" label="Search" single-line
                                    hide-details></v-text-field>
                            </v-card-title>
                            <v-data-table :headers="tipo_req" :items="Tiporequerimiento" :search="search1">
                                <template v-slot:items="props">
                                    <td>{{ props.item.id }}</td>
                                    <td>{{ props.item.Nombre }}</td>
                                    <td>{{ props.item.Dias }}</td>
                                </template>
                                <template v-slot:no-results>
                                    <v-alert :value="true" color="error" icon="warning">
                                        Your search for "{{ search }}" found no results.
                                    </v-alert>
                                </template>
                            </v-data-table>
                        </v-card>
                    </v-card-text>
                </v-flex>
                <v-flex xs6>
                    <v-card-text class="px-0">
                        <v-card>
                            <v-card-title>
                                <div class="text-xs-center">
                                    <v-dialog v-model="dialogJuzgados" persistent max-width="900px">
                                        <template v-slot:activator="{ on }">
                                            <v-btn color="primary" dark v-on="on">
                                                Nuevo Juzgado
                                            </v-btn>
                                        </template>
                                        <v-card>
                                            <v-card-title class="primary white--text">
                                                <span class="headline">Crear Nuevo Juzgado</span>
                                            </v-card-title>
                                            <v-card-text>
                                                <v-container grid-list-md>
                                                    <v-layout wrap>
                                                        <v-flex xs12>
                                                            <v-text-field label="Nombre*" required
                                                                hint="Este campo es requerido"
                                                                v-model="datosjuzgados.Nombre">
                                                            </v-text-field>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card-text>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="red darken-3" flat @click="cleardataJuzgados()">Cancelar
                                                </v-btn>
                                                <v-btn color="green darken-3" flat @click="saveJusgado()">Guardar
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </div>
                                <v-spacer></v-spacer>
                                <v-text-field v-model="search2" append-icon="search" label="Search" single-line
                                    hide-details></v-text-field>
                            </v-card-title>
                            <v-data-table :headers="jusgados" :items="Jusgados" :search="search2">
                                <template v-slot:items="props">
                                    <td>{{ props.item.id }}</td>
                                    <td>{{ props.item.Nombre }}</td>
                                </template>
                                <template v-slot:no-results>
                                    <v-alert :value="true" color="error" icon="warning">
                                        Your search for "{{ search }}" found no results.
                                    </v-alert>
                                </template>
                            </v-data-table>
                        </v-card>
                    </v-card-text>
                </v-flex>
            </v-layout>
        </v-container>
    </v-card>
</template>

<script>
    import swal from 'sweetalert';
    export default {
        data() {
            return {
                search: '',
                search1: '',
                search2: '',
                dialog: false,
                dialogJuzgados: false,
                dialogRequerimiento: false,
                dialogResponsable: false,
                datosjuzgados: {
                    Nombre: ''
                },
                datostiporequerido: {
                    Nombre: '',
                    Dias: ''
                },
                jusgados: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Nombre',
                        value: 'Nombre',

                        sortable: false,
                        align: 'left'
                    }
                ],
                tipo_req: [{
                        text: 'id',
                        align: 'left',
                        sortable: false,
                        value: 'id'
                    },
                    {
                        text: 'Nombre',
                        align: 'left',
                        value: 'Nombre'
                    },
                    {
                        text: 'Dias',
                        align: 'left',
                        value: 'Dias'
                    }
                ],
                responsable_he: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Nombre',
                        align: 'left',
                        value: 'NombreRol'
                    },
                    {
                        text: 'Estado',
                        align: 'left',
                        value: 'NombreEstado'
                    },
                    {
                        text: '',
                        align: 'left',
                        sortable: false,
                        value: 'actions'
                    }
                ],
                Jusgados: [],
                Tiporequerimiento: [],
                Roles: [],
                addresponsable: "",
                responsables: []
            }
        },
        mounted() {
            this.getJuzgados();
            this.getTiporequerimientos();
            this.getRoles();
            this.getResponsables();
        },
        methods: {
            getJuzgados() {
                axios.get('/api/juzgado/all')
                    .then(res => {
                        this.Jusgados = res.data
                    });
            },

            getTiporequerimientos() {
                axios.get('/api/tiporequerimiento/all')
                    .then(res => {
                        this.Tiporequerimiento = res.data
                    });
            },

            getRoles() {
                axios.get('/api/role/all')
                    .then(res => {
                        this.Roles = res.data
                    });
            },

            getResponsables() {
                axios.get('/api/tutelas/allresponsables')
                    .then(res => {
                        this.responsables = res.data
                    });
            },

            saveJusgado() {
                axios.post('/api/juzgado/create', this.datosjuzgados)
                    .then(res => {
                        this.cleardataJuzgados();
                        this.getJuzgados();
                        swal({
                            title: res.data.message,
                            text: " ",
                            type: "success",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    }).catch(err => {
                        swal({
                            title: err.response.data.Nombre,
                            type: "warning",
                            timer: 2000,
                            icon: "warning"
                        });
                    });
            },

            saveRequerimiento() {
                axios.post('/api/tiporequerimiento/create', this.datostiporequerido)
                    .then(res => {
                        this.clearRequerimiento();
                        this.getTiporequerimientos();
                        swal({
                            title: res.data.message,
                            text: " ",
                            type: "success",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    }).catch(err => {
                        let msg = '';
                        for (const pro in err.response.data) {
                            if (msg) msg += `${err.response.data[pro]}`
                            else msg = err.response.data[pro]
                        }
                        swal({
                            title: msg,
                            text: " ",
                            type: "warning",
                            icon: "warning",
                        });
                    })
            },

            saveResponsable() {
                let formData = new FormData();
                formData.append('Rol_id', this.Roles.id)
                axios.post(`/api/tutelas/listresponsable`, formData, {}).then(res => {
                        this.getResponsables();
                        this.clearResponsable();
                        swal({
                            title: res.data.message,
                            text: " ",
                            type: "success",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch(err => {
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

            disableResponsable(responsable) {
                this.data = responsable
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Una vez el responsable esté deshabilitado, ya no saldra en el listado de tutelas!",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.get(`/api/tutelas/estadoresponsable/${this.data.id}`)
                            .then(res => {
                                this.getResponsables();
                                swal("¡Responsable Deshabilitado!", {
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                            })
                            .catch(err => err.response.data);
                    }
                })
            },

            enableResponsable(responsable) {
                this.data = responsable
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Una vez el responsable esté habilitado, saldra en el listado de tutelas!",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.get(`/api/tutelas/enableresponsable/${this.data.id}`)
                            .then(res => {
                                this.getResponsables();
                                swal("¡Responsable Habilitado!", {
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                            })
                            .catch(err => err.response.data);
                    }
                })
            },

            clearResponsable() {
                this.dialogResponsable = false
                this.Roles.id = []
            },

            cleardataJuzgados() {
                this.dialogJuzgados = false
                this.datosjuzgados = {}
            },

            clearRequerimiento() {
                this.dialogRequerimiento = false
                this.datostiporequerido = {}
            }
        }
    }

</script>

<style>

</style>
