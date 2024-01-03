<template>
    <div>
        <v-card>
            <v-card-title class="headline success" style="color:white">
                <span class="title layout justify-center font-weight-light">Clasificación</span>
            </v-card-title>

            <v-toolbar flat color="white">
                <v-toolbar-title>Clasificaciones del Paciente</v-toolbar-title>
                <v-divider class="mx-2" inset vertical></v-divider>
                <v-spacer></v-spacer>
                <v-btn color="primary" dark class="mb-2" @click="dialogClasificacion = true">Crear</v-btn>
            </v-toolbar>
            <v-data-table :headers="headersClasificacion" :items="clasificacion" class="elevation-1">
                <template v-slot:items="props">
                    <td class="text-xs-center">{{ props.item.clasificacion_id }}</td>
                    <td class="text-xs-center">{{ props.item.clasificacion_nombre }}</td>
                    <td class="text-xs-center">{{ props.item.descripcion }}</td>
                    <td>
                        <v-chip v-if="props.item.estado == 1" color="success" dark>
                            Activo</v-chip>
                        <v-chip v-else-if="props.item.estado == 0" color="error" dark>
                            Inactivo</v-chip>
                    </td>
                    <td class="text-xs-left">{{ props.item.creado_por }}</td>
                    <td class="justify-left layout px-0">
                        <v-icon small color="warning" class="mr-2" @click="editItemClasificacion(props.item)">
                            edit
                        </v-icon>
                        <v-icon small color="error" @click="deleteItem(props.item)">
                            delete
                        </v-icon>
                    </td>
                </template>
                <template v-slot:no-data>
                    No hay información para mostrar
                </template>
            </v-data-table>

            <!-- dialogo de crear o editar -->
            <v-dialog v-model="dialogClasificacion" max-width="500px" persisten>
                <v-card>
                    <v-card-title class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">{{ formTitle }}</span>
                    </v-card-title>

                    <v-container>
                        <v-text-field v-model="editedItem.clasificacion_nombre" label="Nombre" outlined>
                        </v-text-field>
                        <v-text-field v-model="editedItem.descripcion" label="Descripción" outlined>
                        </v-text-field>
                    </v-container>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" dark @click="closeClasificacion()">
                            Cancel
                        </v-btn>
                        <v-btn color="success" dark @click="guardarClasificacion()">
                            Guardar
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <!-- inactivar la clasificacion -->
            <v-dialog v-model="dialogDelete" max-width="500px">
                <v-card>
                    <v-card-title class="text-h5"><span class="title layout justify-center font-weight-light">
                            ¿Deseas {{editedItem.estado == 1 ? 'Inactivar':'Activar'}} este tipo de
                            novedad?</span>
                    </v-card-title>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" dark @click="closeDelete()">Cancel</v-btn>
                        <v-btn color="success" dark
                            @click="editedItem.estado == 1 ? inactivarItem(editedItem):activarItem(editedItem)">
                            {{editedItem.estado == 1? 'Inactivar':'Activar'}}</v-btn>
                        <v-spacer></v-spacer>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <template>
                <div class="text-center">
                    <v-dialog v-model="loading" persistent width="300">
                        <v-card color="primary" dark>
                            <v-card-text>
                                Tranquilo procesamos tu solicitud !
                                <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                </div>
            </template>

        </v-card>
    </div>
</template>

<script>
    //clasificacion

    export default {

        data() {
            return {
                dialogDelete: false,
                dialogClasificacion: false,
                loading: false,
                clasificacion: [],
                headersClasificacion: [{
                        text: 'Id',
                        align: 'start',
                        sortable: true,
                        value: 'id',
                    },
                    {
                        text: 'Nombre',
                        align: 'start',
                        sortable: false,
                        value: 'nombre',
                    },
                    {
                        text: 'Descripción',
                        align: 'start',
                        sortable: false,
                        value: 'descripcion',
                    },
                    {
                        text: 'Estado',
                        sortable: false,
                        value: 'estado'
                    },
                    {
                        text: 'Usuario Crea',
                        sortable: false,
                        value: 'creado_por'
                    },
                    {
                        text: 'Actions',
                        value: 'actions',
                        sortable: false
                    }
                ],
                paginacion: {
                    page: 1,
                    total: 0
                },
                editedIndex: -1,
                editedItem: {
                    clasificacion_nombre: null,
                    descripcion:  null
                },
                defaultItem: {
                    clasificacion_nombre: null,
                    descripcion: null
                },
            };
        },

        computed: {
            formTitle() {
                return this.editedIndex === -1 ? 'Crear Tipo Marcación' : 'Editar Tipo Marcación'
            },
        },

        created() {
            this.listarClasificacion()
        },

        methods: {
            //funciones para clasificacion del paciente

            /**
             * Funcion listar clasificacion
             * @return objetc marcacion
             * @author kobatime
             */
            listarClasificacion() {
                this.loading = true
                axios.get('/api/clasificaciones/getClasificacion')
                    .then(res => {
                        this.loading = false
                        this.clasificacion = res.data;
                    })
            },

            /**
             * Guardar y actualizar clasificacion
             * @return objetc editedItem
             * @author kobatime
             */
            guardarClasificacion() {
                if(this.editedItem.clasificacion_nombre == null){
                  return  this.$alerError('Debe ingresar un nombre de la clasificacion')
                }
                if(this.editedItem.descripcion == null){
                  return  this.$alerError('Debe ingresar una descripción de la clasificacion')
                }
                this.loading = true
                if (this.editedIndex > -1) {
                    axios.post('/api/clasificaciones/actualizacionClasificacion/' + this.editedItem.clasificacion_id,
                        this.editedItem).then(res => {
                        this.loading = false
                        this.$alerSuccess(res.data.message)
                    }).catch(error => {
                        this.loading = false
                        console.log(error)
                    })
                } else {
                    axios.post('/api/clasificaciones/guardarClasificacion', this.editedItem).then(res => {
                        this.loading = false
                        this.$alerSuccess(res.data.message)
                    }).catch(error => {
                        this.loading = false
                        console.log(error)
                    })
                }
                this.closeClasificacion()
                this.listarClasificacion()

            },

            /**
             * Funcion para abrir modal de editar 
             * @param item object
             * @return object
             * @author kobatime
             */
            editItemClasificacion(item) {
                this.editedIndex = this.clasificacion.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialogClasificacion = true
            },

            /**
             * Cerrar dialogo 
             * @return string
             * @author kobatime
             */
            closeClasificacion() {
                this.dialogClasificacion = false
                this.listarClasificacion()
                this.$nextTick(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                })
            },

            /**
             * Cerrar dialogo de inactivar
             * @return string
             * @author kobatime
             */
            closeDelete() {
                this.dialogDelete = false
                this.listarClasificacion()
                this.$nextTick(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                })
            },

            /**
             * Funcion para abrir modal de inactivar 
             * @param item object
             * @return object
             * @author kobatime
             */
            deleteItem(item) {
                this.editedIndex = this.clasificacion.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialogDelete = true
            },

            /**
             * Funcion para inactivar la clasificacion
             * @param item object
             * @return boolean
             * @author kobatime
             */
            async inactivarItem(item) {
                this.loading = true
                axios.post('/api/clasificaciones/actualizacionClasificacionEstado/' + item.clasificacion_id).then(res => {
                    this.loading = false
                    this.closeDelete()
                }).catch(err => {
                    this.loading = false
                    console.log(err)
                })
            },

            /**
             * Funcion para activar la clasificacion
             * @param item object
             * @return boolean
             * @author kobatime
             */
            async activarItem(item) {
                this.loading = true
                axios.post('/api/clasificaciones/actualizacionClasificacionEstado/' + item.clasificacion_id).then(res => {
                    this.loading = false
                    this.closeDelete()
                }).catch(err => {
                    this.loading = false
                    console.log(err)
                })
            },

        },
    };

</script>
