<template>
    <v-card>
        <v-container grid-list-md fluid>
            <v-layout wrap>
                <v-flex xs12>
                    <v-card-text class="px-0">
                        <v-card>
                            <v-card-title>
                                <div class="text-xs-center">
                                    <v-dialog v-model="dialogArea" persistent max-width="900px">
                                        <template v-slot:activator="{ on }">
                                            <v-btn color="primary" dark v-on="on">
                                                Nueva área
                                            </v-btn>
                                        </template>
                                        <v-card>
                                            <v-card-title class="primary white--text">
                                                <span class="headline">Crear Nueva Área</span>
                                            </v-card-title>
                                            <v-card-text>
                                                <v-container grid-list-md>
                                                    <v-layout wrap>
                                                        <v-flex xs12>
                                                            <v-text-field label="NOMBRE*" required
                                                                hint="Este campo es requerido" v-model="nombrearea">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-text-field label="DESCRIPCIÓN*"
                                                                hint="Este campo es requerido"
                                                                v-model="descripcionarea">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-autocomplete v-model="permisoarea" :items="allPermisos"
                                                                item-text="name" item-value="id" label="PERMISO*"
                                                                hint="Este campo es requerido">
                                                            </v-autocomplete>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card-text>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="red darken-3" flat @click="closeNewArea()">Cancelar
                                                </v-btn>
                                                <v-btn color="green darken-3" flat @click="createArea()">Guardar
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </div>
                                <v-spacer></v-spacer>
                                <v-text-field v-model="search1" append-icon="search" label="Search" single-line
                                    hide-details></v-text-field>
                            </v-card-title>
                            <v-data-table :headers="areas" :items="allAreas" :search="search1">
                                <template v-slot:items="props">
                                    <td>{{ props.item.id }}</td>
                                    <td>{{ props.item.NombrePermiso }}</td>
                                    <td>{{ props.item.Nombre }}</td>
                                    <td>{{ props.item.Descripcion }}</td>
                                    <td>{{ props.item.Estado }}</td>
                                    <td>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab outline color="warning" small v-on="on"
                                                    @click="editArea(props.item)">
                                                    <v-icon>edit</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Editar</span>
                                        </v-tooltip>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab outline color="error" small v-on="on"
                                                    @click="disableArea(props.item)">
                                                    <v-icon>person_add_disabled</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Inactivar</span>
                                        </v-tooltip>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab outline color="success" v-on="on" small
                                                    @click="enableArea(props.item)">
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

                <v-dialog v-model="dialogEdit" persistent width="800">
                    <v-card>
                        <v-toolbar flat color="primary" dark>
                            <v-toolbar-title>Editar Área</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs6>
                                        <v-text-field v-model="area.Nombre" label="NOMBRE">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-text-field v-model="area.Descripcion" label="DESCRIPCIÓN">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-autocomplete v-model="permiso" :items="allPermisos" item-text="name"
                                            item-value="id" label="PERMISO">
                                        </v-autocomplete>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-divider></v-divider>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" flat @click="closeArea()">
                                Cerrar
                            </v-btn>
                            <v-btn color="primary" flat @click="updateArea()">
                                Guardar
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <v-flex xs6>
                    <v-card-text class="px-0">
                        <v-card>
                            <v-card-title>
                                <div class="text-xs-center">
                                    <v-dialog v-model="dialogCategoria" persistent max-width="900px">
                                        <template v-slot:activator="{ on }">
                                            <v-btn color="primary" dark v-on="on">
                                                Nueva Categoria
                                            </v-btn>
                                        </template>
                                        <v-card>
                                            <v-card-title class="primary white--text">
                                                <span class="headline">Crear Nueva Categoria</span>
                                            </v-card-title>
                                            <v-card-text>
                                                <v-container grid-list-md>
                                                    <v-layout wrap>
                                                        <v-flex xs12>
                                                            <v-text-field label="NOMBRE*" required
                                                                hint="Este campo es requerido"
                                                                v-model="nombrecategoria">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-text-field label="DESCRIPCIÓN*"
                                                                hint="Este campo es requerido"
                                                                v-model="descripcioncategoria">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-autocomplete v-model="areacategoria" :items="allAreas"
                                                                item-text="Nombre" item-value="id" label="ÁREA*"
                                                                hint="Este campo es requerido"></v-autocomplete>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card-text>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="red darken-3" flat @click="closeNewCategoria()">Cancelar
                                                </v-btn>
                                                <v-btn color="green darken-3" flat @click="createNewCategoria()">Guardar
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </div>
                                <v-spacer></v-spacer>
                                <v-text-field v-model="search2" append-icon="search" label="Search" single-line
                                    hide-details></v-text-field>
                            </v-card-title>
                            <v-data-table :headers="categorias" :items="allCategorias" :search="search2">
                                <template v-slot:items="props">
                                    <td>{{ props.item.id }}</td>
                                    <td>{{ props.item.Area }}</td>
                                    <td>{{ props.item.Nombre }}</td>
                                    <td>{{ props.item.Descripcion }}</td>
                                    <td>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab outline color="success" v-on="on" small
                                                    @click="crearAlerta(props.item)">
                                                    <v-icon>add</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Alerta</span>
                                        </v-tooltip>
                                    </td>
                                </template>
                                <template v-slot:no-results>
                                    <v-alert :value="true" color="error" icon="warning">
                                        Your search for "{{ search }}" found no results.
                                    </v-alert>
                                </template>
                            </v-data-table>
                            <v-dialog v-model="dialogCrearAlerta" persistent width="800">
                                <v-card>
                                    <v-toolbar flat color="primary" dark>
                                        <v-toolbar-title>Alerta o Recomendación</v-toolbar-title>
                                    </v-toolbar>
                                    <v-card-text>
                                        <v-container grid-list-md>
                                            <v-layout wrap>
                                                <v-flex xs12>
                                                    <v-textarea v-model="categoria.alerta">
                                                    </v-textarea>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </v-card-text>
                                    <v-divider></v-divider>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="primary" flat @click="closeCategoria()">
                                            Cerrar
                                        </v-btn>
                                        <v-btn color="primary" flat @click="guardarAlerta()">
                                            Guardar
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>

                        </v-card>
                    </v-card-text>
                </v-flex>

                <v-flex xs6>
                    <v-card-text class="px-0">
                        <v-card>
                            <v-card-title>
                                <div class="text-xs-center">
                                    <v-dialog v-model="dialogActividad" persistent max-width="900px">
                                        <template v-slot:activator="{ on }">
                                            <v-btn color="primary" dark v-on="on">
                                                Nueva Actividad
                                            </v-btn>
                                        </template>
                                        <v-card>
                                            <v-card-title class="primary white--text">
                                                <span class="headline">Crear Nueva Actividad</span>
                                            </v-card-title>
                                            <v-card-text>
                                                <v-container grid-list-md>
                                                    <v-layout wrap>
                                                        <v-flex xs12>
                                                            <v-text-field label="NOMBRE*" required
                                                                hint="Este campo es requerido"
                                                                v-model="nombreactividad">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-text-field label="DESCRIPCIÓN*"
                                                                hint="Este campo es requerido"
                                                                v-model="descripcionactividad">
                                                            </v-text-field>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-autocomplete v-model="categoria" :items="allCategorias"
                                                                item-text="Nombre" item-value="id" label="CATEGORIA*"
                                                                hint="Este campo es requerido"></v-autocomplete>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card-text>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="red darken-3" flat @click="closeNewActividad()">Cancelar
                                                </v-btn>
                                                <v-btn color="green darken-3" flat @click="createNewActividad()">Guardar
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </div>
                                <v-spacer></v-spacer>
                                <v-text-field v-model="search3" append-icon="search" label="Search" single-line
                                    hide-details></v-text-field>
                            </v-card-title>
                            <v-data-table :headers="actividades" :items="allActividades" :search="search3">
                                <template v-slot:items="props">
                                    <td>{{ props.item.id }}</td>
                                    <td>{{ props.item.Categoria }}</td>
                                    <td>{{ props.item.Nombre }}</td>
                                    <td>{{ props.item.Descripcion }}</td>
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
                dialogArea: false,
                search1: '',
                nombrearea: "",
                descripcionarea: "",
                allAreas: [],
                permisoarea: "",
                allPermisos: [],
                areas: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Permiso',
                        value: 'NombrePermiso',
                        align: 'left'
                    },
                    {
                        text: 'Nombre',
                        value: 'Nombre',
                        align: 'left'
                    },
                    {
                        text: 'Descripción',
                        value: 'Descripcion',
                        align: 'left'
                    },
                    {
                        text: 'Estado',
                        value: 'Estado',
                        align: 'left'
                    },
                    {
                        text: '',
                        align: 'left',
                        sortable: false,
                        value: 'actions'
                    }
                ],
                area: [],
                dialogEdit: false,
                permiso: "",
                dialogCategoria: false,
                categoria: [],
                search2: '',
                allCategorias: [],
                dialogCrearAlerta: false,
                nombrecategoria: "",
                descripcioncategoria: "",
                categorias: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Área',
                        value: 'Area',
                        align: 'left'
                    },
                    {
                        text: 'Nombre',
                        value: 'Nombre',
                        align: 'left'
                    },
                    {
                        text: 'Descripción',
                        value: 'Descripcion',
                        align: 'left'
                    },
                    {
                        text: 'Alerta',
                        align: 'left'
                    }
                ],
                areacategoria: "",
                dialogActividad: false,
                allActividades: [],
                actividades: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Categoria',
                        value: 'Categoria',
                        align: 'left'
                    },
                    {
                        text: 'Nombre',
                        value: 'Nombre',
                        align: 'left'
                    },
                    {
                        text: 'Descripción',
                        value: 'Descripcion',
                        align: 'left'
                    }
                ],
                nombreactividad: "",
                descripcionactividad: "",
                search3: '',
                categoria: ""
            }
        },
        mounted() {
            this.getAreas();
            this.getPermisos();
            this.getCategorias();
            this.getActividades();
        },
        methods: {
            getPermisos() {
                axios.get('/api/permiso/all')
                    .then(res => {
                        this.allPermisos = res.data;
                    });
            },
            getAreas() {
                axios.get('/api/helpdesk/getarea')
                    .then(res => {
                        this.allAreas = res.data;
                    });
            },
            createArea() {
                let formData = new FormData();
                formData.append('Nombre', this.nombrearea)
                formData.append('Descripcion', this.descripcionarea)
                formData.append('Permission_id', this.permisoarea)
                formData.append('Estado_id', 1)
                axios.post(`/api/helpdesk/storearea`, formData)
                    .then(res => {
                        this.getAreas()
                        this.closeNewArea()
                        swal({
                            title: "¡Nueva área creada con exito!",
                            text: ` `,
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
                            type: "error",
                            icon: "error",
                        });
                    })
            },
            closeNewArea() {
                this.dialogArea = false
                this.nombrearea = ""
                this.descripcionarea = "",
                    this.permisoarea = ""
            },
            editArea(item) {
                this.dialogEdit = true
                this.area = item
            },
            crearAlerta(item) {
                this.dialogCrearAlerta = true
                this.categoria = item
            },
            closeCategoria() {
                this.getCategorias();
                this.dialogCrearAlerta = false
                this.categoria.alerta = ""
            },
            closeArea() {
                this.getAreas();
                this.dialogEdit = false
                this.permiso = ""
            },
            guardarAlerta() {
                axios.post(`/api/helpdesk/crearAlerta/${this.categoria.id}`, this.categoria)
                    .then(res => {
                        this.getCategorias()
                        this.closeCategoria()
                        swal({
                            title: "¡Alerta creada con exito!",
                            text: ` `,
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
                            type: "error",
                            icon: "error",
                        });
                    })
            },
            updateArea() {
                let formData = new FormData();
                formData.append('Nombre', this.area.Nombre)
                formData.append('Descripcion', this.area.Descripcion)
                formData.append('Permission_id', this.permiso)
                axios.post(`/api/helpdesk/updatearea/${this.area.id}`, formData)
                    .then(res => {
                        this.getAreas();
                        this.closeArea();
                        swal({
                            title: "¡Actualizo área con exito!",
                            text: ` `,
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
                            type: "error",
                            icon: "error",
                        });
                    });
            },
            enableArea(area) {
                this.data = area
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Una vez el área esté habilitado, saldra en el listado de generar Helpdesk!",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.get(`/api/helpdesk/enable/${this.data.id}`)
                            .then(res => {
                                this.getAreas();
                                swal("¡Área Habilitada!", {
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                            })
                            .catch(err => err.response.data);
                    }
                })
            },
            disableArea(area) {
                this.data = area
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Una vez la área esté deshabilitada, no saldra en el listado de generar Helpdesk!",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.get(`/api/helpdesk/disable/${this.data.id}`)
                            .then(res => {
                                this.getAreas();
                                swal("¡Área Deshabilitada!", {
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                            })
                            .catch(err => err.response.data);
                    }
                })
            },
            getCategorias() {
                axios.get('/api/helpdesk/allcategoria')
                    .then(res => {
                        this.allCategorias = res.data;
                    });
            },
            closeNewCategoria() {
                this.dialogCategoria = false
                this.nombrecategoria = ""
                this.descripcioncategoria = ""
                this.areacategoria = ""
            },
            createNewCategoria() {
                let formData = new FormData();
                formData.append('Nombre', this.nombrecategoria)
                formData.append('Descripcion', this.descripcioncategoria)
                formData.append('Area_id', this.areacategoria)
                axios.post(`/api/helpdesk/storecategoria`, formData)
                    .then(res => {
                        this.getCategorias()
                        this.closeNewCategoria()
                        swal({
                            title: "¡Nueva categoria creada con exito!",
                            text: ` `,
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
                            type: "error",
                            icon: "error",
                        });
                    })
            },
            getActividades() {
                axios.get('/api/helpdesk/allactividad')
                    .then(res => {
                        this.allActividades = res.data;
                    });
            },
            closeNewActividad() {
                this.dialogActividad = false
                this.nombreactividad = ""
                this.descripcionactividad = ""
                this.categoria = ""
            },
            createNewActividad() {
                let formData = new FormData();
                formData.append('Nombre', this.nombreactividad)
                formData.append('Descripcion', this.descripcionactividad)
                formData.append('Categoria_id', this.categoria)
                axios.post(`/api/helpdesk/storeactividad`, formData)
                    .then(res => {
                        this.getActividades()
                        this.closeNewActividad()
                        swal({
                            title: "¡Nueva actividad creada con exito!",
                            text: ` `,
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
                            type: "error",
                            icon: "error",
                        });
                    })
            },

        }
    }

</script>
