<template>
    <div>
        <v-layout>
            <v-flex xs12 sm12 md12>
                <v-card>

                    <v-card>
                        <v-bottom-nav :value="true" color="transparent">
                            <v-btn color="primary" flat
                                @click="notificaciones = true, showclasificacion = false, getNotificaciones(), getUser()">
                                <span>Notificación</span>
                                <v-icon>circle_notifications</v-icon>
                            </v-btn>
                            <v-btn color="primary" flat @click="showclasificacion = true, notificaciones = false">
                                <span>Clasificaciónes</span>
                                <v-icon>mdi-file-chart</v-icon>
                            </v-btn>
                        </v-bottom-nav>
                    </v-card>

                    <v-card v-show="notificaciones">
                        <v-card-title>
                            <v-tooltip top>
                                <template v-slot:activator="{ on }">
                                    <v-btn fab color="primary" small v-on="on"
                                        @click="dialogCreateNotificacion = true, formNotificacion = {}, saveNotificacion = true">
                                        <v-icon>add</v-icon>
                                    </v-btn>
                                </template>
                                <span>Agregar</span>
                            </v-tooltip>
                            <v-spacer></v-spacer>
                            <v-text-field v-model="searchNotificacion" append-icon="search" label="Buscar..."
                                single-line hide-details>
                            </v-text-field>
                        </v-card-title>
                        <template>
                            <v-data-table :headers="headersNotificacion" :items="allusernotificaciones"
                                :search="searchNotificacion" class="elevation-1" color="primary">
                                <template v-slot:items="props">
                                    <td>{{ props.item.id }}</td>
                                    <td>{{ props.item.cedula }}</td>
                                    <td>{{ props.item.name }} {{ props.item.apellido }}</td>
                                    <td>{{ props.item.tipo }}</td>
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn fab color="primary" small v-on="on" @click="edit(props.item)">
                                                <v-icon>question_answer</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Ver</span>
                                    </v-tooltip>
                                </template>
                            </v-data-table>
                        </template>

                        <v-dialog v-model="dialogCreateNotificacion" v-if="dialogCreateNotificacion" persistent
                            max-width="800px">
                            <v-card>
                                <v-toolbar dark color="primary">
                                    <v-flex xs12 text-xs-center flat dark>
                                        <v-toolbar-title v-if="saveNotificacion">Crear Usuario Notificación
                                        </v-toolbar-title>
                                        <v-toolbar-title v-else>Editar Usuario Notificación</v-toolbar-title>
                                    </v-flex>
                                </v-toolbar>
                                <v-card-text>
                                    <v-container grid-list-md>
                                        <v-layout wrap>
                                            <v-flex xs12 md4 sm4>
                                                <v-select :items="['TODAS MENOS', 'TODAS', 'SOLO']"
                                                    v-model="formNotificacion.tipo" label="Tipo">
                                                </v-select>
                                            </v-flex>
                                            <v-flex xs12 md12 sm12 v-show="formNotificacion.tipo != 'TODAS'">
                                                <v-autocomplete v-model="formNotificacion.sede_ocurrencia"
                                                    :items="sedesCompletas" item-text="nombre" item-value="id" chips
                                                    label="Sede ocurrencia" multiple>
                                                    <template v-slot:selection="data">
                                                        <v-chip :selected="data.selected" close
                                                            class="chip--select-multi"
                                                            @input="remove_sede(data.item.id)">
                                                            {{ data.item.nombre }}
                                                        </v-chip>
                                                    </template>
                                                    <template v-slot:item="data">
                                                        <template>
                                                            <v-list-tile-content>
                                                                <v-list-tile-title v-html="data.item.nombre">
                                                                </v-list-tile-title>
                                                            </v-list-tile-content>
                                                        </template>
                                                    </template>
                                                </v-autocomplete>
                                            </v-flex>
                                            <v-flex xs12 md8 sm8>
                                                <v-autocomplete :readonly="!saveNotificacion" :items="user_activos"
                                                    label="Usuario" item-text="nombre" item-value="id"
                                                    v-model="formNotificacion.user" />
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="error" @click="dialogCreateNotificacion = false">Cerrar
                                    </v-btn>
                                    <v-btn v-if="saveNotificacion" @click="saveCreateNotificacion" color="success">
                                        Guardar
                                    </v-btn>
                                    <v-btn v-else @click="updateNotificacion" color="warning">Actualizar</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-card>

                    <v-card v-show="showclasificacion">
                        <v-container grid-list-md fluid>
                            <v-layout wrap>
                                <v-flex xs12>
                                    <v-card-text class="px-0">
                                        <v-card>
                                            <v-card-title>
                                                <div class="text-xs-center">
                                                    <v-dialog v-model="dialogSuceso" persistent max-width="900px">
                                                        <template v-slot:activator="{ on }">
                                                            <v-btn color="primary" dark v-on="on">
                                                                Nuevo Suceso
                                                            </v-btn>
                                                        </template>
                                                        <v-card>
                                                            <v-card-title class="primary white--text">
                                                                <span class="headline">Crear Nuevo Suceso</span>
                                                            </v-card-title>
                                                            <v-card-text>
                                                                <v-container grid-list-md>
                                                                    <v-layout wrap>
                                                                        <v-flex xs12>
                                                                            <v-text-field v-model="nombreSuceso"
                                                                                label="Nombre" required
                                                                                hint="Este campo es requerido">
                                                                            </v-text-field>
                                                                        </v-flex>
                                                                    </v-layout>
                                                                </v-container>
                                                            </v-card-text>
                                                            <v-card-actions>
                                                                <v-spacer></v-spacer>
                                                                <v-btn color="error" dark @click="closeNewSuceso">
                                                                    Cancelar
                                                                </v-btn>
                                                                <v-btn color="success" dark @click="saveSuceso">Guardar
                                                                </v-btn>
                                                            </v-card-actions>
                                                        </v-card>
                                                    </v-dialog>
                                                </div>
                                                <v-spacer></v-spacer>
                                                <v-text-field v-model="search1" append-icon="search" label="Buscar"
                                                    single-line hide-details></v-text-field>
                                            </v-card-title>
                                            <v-data-table :headers="sucesos" :items="allSucesos" :search="search1">
                                                <template v-slot:items="props">
                                                    <td>{{ props.item.id }}</td>
                                                    <td>{{ props.item.nombre }}</td>
                                                    <td>{{ props.item.estado }}</td>
                                                    <td>
                                                        <v-tooltip top>
                                                            <template v-slot:activator="{ on }">
                                                                <v-btn fab dark small v-on="on" color="success"
                                                                    v-show="props.item.estadoId == '2'"
                                                                    @click="actualizarEstado(1,props.item.id)">
                                                                    <v-icon dark>check</v-icon>
                                                                </v-btn>
                                                            </template>
                                                            <span>Activar</span>
                                                        </v-tooltip>
                                                        <v-tooltip top>
                                                            <template v-slot:activator="{ on }">
                                                                <v-btn fab dark small color="error" v-on="on"
                                                                    v-show="props.item.estadoId == '1'"
                                                                    @click="actualizarEstado(2,props.item.id)">
                                                                    <v-icon dark>close</v-icon>
                                                                </v-btn>
                                                            </template>
                                                            <span>Inactivar</span>
                                                        </v-tooltip>
                                                    </td>
                                                </template>
                                                <template v-slot:no-results>
                                                    <v-alert :value="true" color="error" icon="warning">
                                                        Your search for "{{ search1 }}" found no results.
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
                                                    <v-dialog v-model="dialogClasificacion" persistent
                                                        max-width="900px">
                                                        <template v-slot:activator="{ on }">
                                                            <v-btn color="primary" dark v-on="on">
                                                                Nueva Clasificación Área
                                                            </v-btn>
                                                        </template>
                                                        <v-card>
                                                            <v-card-title class="primary white--text">
                                                                <span class="headline">Crear Nueva Clasificación
                                                                    Área</span>
                                                            </v-card-title>
                                                            <v-card-text>
                                                                <v-container grid-list-md>
                                                                    <v-layout wrap>
                                                                        <v-flex xs12>
                                                                            <v-text-field v-model="nombreClasificacion"
                                                                                label="Nombre" required
                                                                                hint="Este campo es requerido">
                                                                            </v-text-field>
                                                                        </v-flex>
                                                                        <v-flex xs12>
                                                                            <v-autocomplete v-model="sucesoId"
                                                                                :items="allSucesos" item-text="nombre"
                                                                                item-value="id" label="Suceso"
                                                                                hint="Este campo es requerido">
                                                                            </v-autocomplete>
                                                                        </v-flex>
                                                                    </v-layout>
                                                                </v-container>
                                                            </v-card-text>
                                                            <v-card-actions>
                                                                <v-spacer></v-spacer>
                                                                <v-btn color="error" dark
                                                                    @click="closeNewClasificacion">Cancelar
                                                                </v-btn>
                                                                <v-btn color="success" dark @click="saveClasificacion">
                                                                    Guardar
                                                                </v-btn>
                                                            </v-card-actions>
                                                        </v-card>
                                                    </v-dialog>
                                                </div>
                                                <v-spacer></v-spacer>
                                                <v-text-field v-model="search2" append-icon="search" label="Buscar"
                                                    single-line hide-details></v-text-field>
                                            </v-card-title>
                                            <v-data-table :headers="clasificaciones" :items="allClasificaciones"
                                                :search="search2">
                                                <template v-slot:items="props">
                                                    <td>{{ props.item.id }}</td>
                                                    <td>{{ props.item.suceso }}</td>
                                                    <td>{{ props.item.nombre }}</td>
                                                </template>
                                                <template v-slot:no-results>
                                                    <v-alert :value="true" color="error" icon="warning">
                                                        Your search for "{{ search2 }}" found no results.
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
                                                    <v-dialog v-model="dialogTipoEvento" persistent max-width="900px">
                                                        <template v-slot:activator="{ on }">
                                                            <v-btn color="primary" dark v-on="on">
                                                                Nuevo Tipo de Evento
                                                            </v-btn>
                                                        </template>
                                                        <v-card>
                                                            <v-card-title class="primary white--text">
                                                                <span class="headline">Crear Nuevo Tipo de Evento</span>
                                                            </v-card-title>
                                                            <v-card-text>
                                                                <v-container grid-list-md>
                                                                    <v-layout wrap>
                                                                        <v-flex xs12>
                                                                            <v-text-field v-model="tipoEvento"
                                                                                label="Nombre" required
                                                                                hint="Este campo es requerido">
                                                                            </v-text-field>
                                                                        </v-flex>
                                                                        <v-flex xs12>
                                                                            <v-autocomplete v-model="clasificacion"
                                                                                :items="allClasificaciones"
                                                                                item-text="nombre" item-value="id"
                                                                                label="Clasificación Área"
                                                                                hint="Este campo es requerido">
                                                                            </v-autocomplete>
                                                                        </v-flex>
                                                                    </v-layout>
                                                                </v-container>
                                                            </v-card-text>
                                                            <v-card-actions>
                                                                <v-spacer></v-spacer>
                                                                <v-btn color="error" dark @click="closeNewTipoEvento">
                                                                    Cancelar
                                                                </v-btn>
                                                                <v-btn color="success" dark @click="saveTipoEvento">
                                                                    Guardar
                                                                </v-btn>
                                                            </v-card-actions>
                                                        </v-card>
                                                    </v-dialog>
                                                </div>
                                                <v-spacer></v-spacer>
                                                <v-text-field v-model="search3" append-icon="search" label="Buscar"
                                                    single-line hide-details></v-text-field>
                                            </v-card-title>
                                            <v-data-table :headers="tipoactividades" :items="allTipoactividades"
                                                :search="search3">
                                                <template v-slot:items="props">
                                                    <td>{{ props.item.id }}</td>
                                                    <td>{{ props.item.clasificacion }}</td>
                                                    <td>{{ props.item.nombre }}</td>
                                                </template>
                                                <template v-slot:no-results>
                                                    <v-alert :value="true" color="error" icon="warning">
                                                        Your search for "{{ search3 }}" found no results.
                                                    </v-alert>
                                                </template>
                                            </v-data-table>
                                        </v-card>
                                    </v-card-text>
                                </v-flex>

                            </v-layout>
                        </v-container>

                    </v-card>

                </v-card>
            </v-flex>
        </v-layout>

        <v-dialog v-model="preload" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Tranquilo procesamos tu solicitud !
                    <v-progress-linear indeterminate color="white" class="mb-0">
                    </v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                notificaciones: false,
                searchNotificacion: '',
                allusernotificaciones: [],
                headersNotificacion: [{
                        text: 'User_id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Documento',
                        align: 'left',
                        value: 'cedula'
                    },
                    {
                        text: 'Usuario',
                        align: 'left',
                        value: 'name'
                    },
                    {
                        text: 'Tipo',
                        align: 'left',
                        value: 'tipo'
                    },
                    {
                        text: 'Acción',
                        align: 'left',
                        value: ''
                    }
                ],
                dialogCreateNotificacion: false,
                saveNotificacion: true,
                users: [],
                formNotificacion: {
                    tipo: '',
                    sede_ocurrencia: [],
                },
                sedesUser: [],
                preload: false,
                showclasificacion: false,
                dialogSuceso: false,
                dialogClasificacion: false,
                dialogTipoEvento: false,
                search1: '',
                search2: '',
                search3: '',
                nombreSuceso: '',
                sucesos: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Nombre',
                        align: 'left',
                        value: 'nombre'
                    },
                    {
                        text: 'Estado',
                        align: 'left',
                        value: 'estado'
                    },
                    {
                        text: 'Acción',
                        align: 'left',
                        value: ''
                    }
                ],
                allSucesos: [],
                clasificaciones: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Suceso',
                        align: 'left',
                        value: 'suceso'
                    },
                    {
                        text: 'Nombre',
                        align: 'left',
                        value: 'nombre'
                    }
                ],
                allClasificaciones: [],
                tipoactividades: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Clasificación',
                        align: 'left',
                        value: 'clasificacion'
                    },
                    {
                        text: 'Nombre',
                        align: 'left',
                        value: 'nombre'
                    }
                ],
                allTipoactividades: [],
                nombreClasificacion: '',
                sucesoId: '',
                tipoEvento: '',
                clasificacion: '',

            }
        },
        mounted() {
            this.sedes();
            this.getSucesos();
            this.getClasificaciones();
            this.getTipoActividades();
        },
        computed: {
            user_activos() {
                let finded = [];
                this.users.forEach(u => {
                    if (u.estado == 1) {
                        finded.push(u)
                    }
                });
                return finded;
            },
            sedesCompletas() {
                return this.allsedes.map(sede => {
                    return {
                        id: sede.id,
                        nombre: `${sede.Nombre} - ${sede.Direccion}`
                    }
                })
            },
        },
        methods: {
            getNotificaciones() {
                axios.get('/api/evento/getUserNotification').then(res => {
                    this.allusernotificaciones = res.data
                });
            },
            getUser() {
                axios.get('/api/user/all')
                    .then(res => this.users = res.data.map((us) => {
                        return {
                            id: us.id,
                            nombre: `${us.cedula} - ${us.name} ${us.apellido}`,
                            estado: us.estado_user
                        }
                    }));
            },
            sedes() {
                axios.get('/api/sedeproveedore/getSedePrestador').then((res) => {
                    this.allsedes = res.data
                })
            },
            saveCreateNotificacion() {
                if (this.formNotificacion.tipo !== 'TODAS') {
                    if (this.formNotificacion.sede_ocurrencia == undefined) {
                        this.$alerError("Debe seleccionar una sede de ocurrencia almenos!");
                        return;
                    }
                    if (this.formNotificacion.sede_ocurrencia.length <= 0) {
                        this.$alerError("Debe seleccionar una sede de ocurrencia almenos!");
                        return;
                    }
                }
                this.preload = true
                let formData = new FormData();
                formData.append('tipo', this.formNotificacion.tipo);
                formData.append('user_id', this.formNotificacion.user);
                if (this.formNotificacion.tipo !== 'TODAS') {
                    for (let i = 0; i < this.formNotificacion.sede_ocurrencia.length; i++) {
                        formData.append(`sedeproveedore_id[]`, this.formNotificacion.sede_ocurrencia[i]);
                    }
                }
                axios.post(`/api/evento/createUserNotification`, formData)
                    .then(res => {
                        this.getNotificaciones()
                        this.dialogCreateNotificacion = false
                        this.preload = false
                        this.$alerSuccess(res.data.message)
                    }).catch(err => {
                        this.preload = false
                    })
            },
            updateNotificacion() {
                if (this.formNotificacion.tipo !== 'TODAS') {
                    if (this.formNotificacion.sede_ocurrencia == undefined) {
                        this.$alerError("Debe seleccionar una sede de ocurrencia almenos!");
                        return;
                    }
                    if (this.formNotificacion.sede_ocurrencia.length <= 0) {
                        this.$alerError("Debe seleccionar una sede de ocurrencia almenos!");
                        return;
                    }
                }
                this.preload = true
                let formData = new FormData();
                formData.append('tipo', this.formNotificacion.tipo);
                formData.append('user_id', this.formNotificacion.user);
                if (this.formNotificacion.tipo !== 'TODAS') {
                    for (let i = 0; i < this.formNotificacion.sede_ocurrencia.length; i++) {
                        formData.append(`sedeproveedore_id[]`, this.formNotificacion.sede_ocurrencia[i]);
                    }
                }
                axios.post(`/api/evento/updateUserNotification`, formData)
                    .then(res => {
                        this.getNotificaciones()
                        this.dialogCreateNotificacion = false
                        this.preload = false
                        this.$alerSuccess(res.data.message)
                    }).catch(err => {
                        this.preload = false
                    })
            },
            remove_sede(item) {
                this.formNotificacion.sede_ocurrencia.splice(this.formNotificacion.sede_ocurrencia.indexOf(item), 1)
                this.formNotificacion.sede_ocurrencia = [...this.formNotificacion.sede_ocurrencia]
            },
            edit(data) {
                this.preload = true
                this.formNotificacion.user = data.id
                this.formNotificacion.tipo = data.tipo
                axios.get(`/api/evento/sedesUserNotification/` + data.id)
                    .then(res => {
                        this.formNotificacion.sede_ocurrencia = res.data
                        this.dialogCreateNotificacion = true
                        this.saveNotificacion = false
                        this.preload = false
                    }).catch(err => {
                        this.preload = false
                    })
            },

            getSucesos() {
                axios.get('/api/evento/allsuceso')
                    .then(res => {
                        this.allSucesos = res.data;
                    });
            },

            getClasificaciones() {
                axios.get('/api/evento/allclasificaciones')
                    .then(res => {
                        this.allClasificaciones = res.data;
                    });
            },

            getTipoActividades() {
                axios.get('/api/evento/alltipoactividades')
                    .then(res => {
                        this.allTipoactividades = res.data;
                    });
            },

            closeNewSuceso() {
                this.dialogSuceso = false
                this.nombreSuceso = ''
            },

            closeNewClasificacion() {
                this.dialogClasificacion = false
                this.nombreClasificacion = ''
                this.sucesoId = ''
            },

            closeNewTipoEvento() {
                this.dialogTipoEvento = false
                this.clasificacion = ''
                this.tipoEvento = ''
            },

            saveSuceso() {
                if (this.nombreSuceso == '') {
                    this.$alerError("Debe ingresar un nombre!");
                    return;
                }
                this.preload = true
                axios.post(`/api/evento/saveSuceso`, {
                        suceso: this.nombreSuceso
                    })
                    .then(res => {
                        this.getSucesos();
                        this.closeNewSuceso()
                        this.preload = false
                        this.$alerSuccess('Suceso creado con exito!')
                    }).catch(err => {
                        this.preload = false
                    })
            },

            actualizarEstado(estadoId, id) {
                this.preload = true
                axios.post(`/api/evento/updateEstadoSuceso/` + id, {
                        estado_id: estadoId
                    })
                    .then(res => {
                        this.getSucesos();
                        this.preload = false
                        this.$alerSuccess('Suceso actualizado con exito!')
                    }).catch(err => {
                        this.preload = false
                    })
            },

            saveClasificacion() {
                if (this.nombreClasificacion == '') {
                    this.$alerError("Debe ingresar un nombre!");
                    return;
                }
                if (this.sucesoId == '') {
                    this.$alerError("Debe ingresar un suceso!");
                    return;
                }
                this.preload = true
                axios.post(`/api/evento/saveClasificacion`, {
                        evento_id: this.sucesoId,
                        nombre: this.nombreClasificacion
                    })
                    .then(res => {
                        this.getClasificaciones();
                        this.closeNewClasificacion()
                        this.preload = false
                        this.$alerSuccess('Clasificación de área creada con exito!')
                    }).catch(err => {
                        this.preload = false
                    })
            },

            saveTipoEvento() {
                if (this.tipoEvento == '') {
                    this.$alerError("Debe ingresar un nombre!");
                    return;
                }
                if (this.clasificacion == '') {
                    this.$alerError("Debe ingresar una clasificación de área!");
                    return;
                }
                this.preload = true
                axios.post(`/api/evento/saveTipoactividad`, {
                        clasificacionevento_id: this.clasificacion,
                        nombre: this.tipoEvento
                    })
                    .then(res => {
                        this.getTipoActividades();
                        this.closeNewTipoEvento()
                        this.preload = false
                        this.$alerSuccess('Tipo de evento creado con exito!')
                    }).catch(err => {
                        this.preload = false
                    })
            }

        },
    }

</script>
