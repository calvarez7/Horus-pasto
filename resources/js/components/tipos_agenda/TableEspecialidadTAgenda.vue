<template>
    <v-card min-height="100%">
        <v-container pa-1>
            <v-container pt-3 pb-1>
                <v-layout row>
                    <v-dialog v-model="dialog" persistent max-width="1200px">
                        <v-card>
                            <v-card-title v-if="can('tiposAgenda.create')" class="headline success" style="color:white">
                                <span v-if="save" class="headline">Crear Tipo Agenda</span>
                                <!-- <span v-else class="headline">Editar Tipo Agenda</span> -->
                            </v-card-title>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs12 sm6 md6>
                                            <v-autocomplete label="Especialidad" :items="especialidades"
                                                item-text="Nombre" item-value="id" v-model="data.especialidad">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-autocomplete label="Actividad" :items="actividades"
                                                v-model="data.Tipoagenda_id" item-text="name" item-value="id">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-autocomplete label="Cup- Primera vez *" :items="cups" item-text="Nombre"
                                                item-value="id" v-model="data.Primeravez_id" required>
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-autocomplete label="Cup- Control *" :items="cups" item-text="Nombre"
                                                item-value="id" v-model="data.Control_id" required>
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-text-field label="Duración(minutos)*" type="number"
                                                v-model="data.Duracion" required>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-autocomplete label="Tipo cita *" :items="tipoCita" item-text="Nombre"
                                                item-value="id" v-model="data.tipocita_id" required>
                                            </v-autocomplete>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" flat @click="dialog = false">Cancelar</v-btn>
                                <v-btn v-if="save" color="blue darken-1" flat @click="saveTipoAgenda()">Guardar</v-btn>
                                <!-- <v-btn v-else color="blue darken-1" flat @click="updateTipoAgenda()">Actualizar</v-btn> -->
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-layout>
            </v-container>
            <v-card-title>
                <v-flex xs12>
                    <span class="headline layout justify-center">Tipo Agenda </span>
                </v-flex>
                <v-flex xs4>
                    <v-btn v-if="can('tipo-agenda.create')" round @click="createTipoAgenda()" color="primary" dark>
                        <v-icon left>exposure_plus_1</v-icon>Nuevo Tipo Agenda
                    </v-btn>
                </v-flex>
                <v-spacer></v-spacer>
                <v-flex sm5 xs5>
                    <v-text-field v-model="search" append-icon="search" label="Buscar..." single-line hide-details>
                    </v-text-field>
                </v-flex>
            </v-card-title>
            <v-data-table :headers="headers" :items="tAgendas" :search="search">
                <template v-slot:items="props">
                    <td>{{ props.item.id }}</td>
                    <td class="text-xs-right">{{ props.item.nombreEspecilidad }}</td>
                    <td class="text-xs-right">{{ props.item.nombreActividad }}</td>
                    <td class="text-xs-right">{{ props.item.Primeravez_id }}</td>
                    <td class="text-xs-right">{{ props.item.Control_id }}</td>
                    <td class="text-xs-right">{{ props.item.Duracion }} minutos</td>
                    <td class="text-xs-right">{{ props.item.tipocita }} </td>
                    <td class="text-xs-right" v-if="props.item.estado_id === '1'">
                        <v-chip color="success" text-color="white">Activo</v-chip>
                    </td>
                    <td class="text-xs-right" v-else>
                        <v-chip color="error" text-color="white">Inactivo</v-chip>
                    </td>
                    <td class="text-xs-right">
                        <v-btn v-if="can('tipo-agenda.edit') && props.item.estado_id === '1'" fab outline color="error" small
                            @click="cambiarEstado(props.item.id)">
                            <v-icon>mdi-close-circle</v-icon>
                        </v-btn>
                        <v-btn v-if="can('tipo-agenda.edit') && props.item.estado_id === '2'" fab outline color="warning" small
                            @click="cambiarEstado(props.item.id)">
                            <v-icon>mdi-check-circle</v-icon>
                        </v-btn>
                    </td>
                </template>
                <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                    Your search for "{{ search }}" found no results.
                </v-alert>
            </v-data-table>
        </v-container>
         <div class="text-center">
         <v-dialog v-model="preload" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Estamos procesando su información
                    <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
         </div>
    </v-card>
</template>

<script>
    import {
        mapGetters
    } from 'vuex';
    export default {
        name: 'TableEspecialidadTAgenda',
        data() {
            return {
                preload: false,
                cups: [],
                tipoCita: [],
                tAgendas: [],
                especialidades: [],
                actividades: [],
                search: '',
                headers: [{
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Especialidad',
                        align: 'right',
                        sortable: false,
                        value: 'nombreEspecilidad'
                    },
                    {
                        text: 'Actividad',
                        align: 'right',
                        sortable: false,
                        value: 'nombreActividad'
                    },
                    {
                        text: 'Cip primera vez',
                        align: 'right',
                        sortable: false,
                        value: 'nombreActividad'
                    },
                    {
                        text: 'Control',
                        align: 'right',
                        sortable: false,
                        value: 'nombreActividad'
                    },
                    {
                        text: 'Duración',
                        align: 'right',
                        sortable: false,
                        value: 'Duracion'
                    },
                    {
                        text: 'Tipo cita',
                        align: 'right',
                        sortable: false,
                        value: ''
                    },
                    {
                        text: 'Estado',
                        align: 'right',
                        sortable: false,
                        value: ''
                    }

                ],
                save: true,
                dialog: false,
                data: {
                    especialidad: '',
                    Tipoagenda_id: '',
                    Primeravez_id: '',
                    Control_id: '',
                    Duracion: 0,
                    tipocita_id: ''
                },
            }
        },
        mounted() {
            this.fetchTipoAgenda();
            this.fetchTipoActividad();
            this.fetchEspecialidad();
            this.fetchCups();
            this.fetchtipoCita();
        },
        computed: {
            ...mapGetters(['can'])
        },
        methods: {
            fetchCups() {
                axios.get('/api/cups/all')
                    .then(res => this.cups = res.data.map((cup) => {
                        return {
                            id: cup.id,
                            Nombre: `${cup.Codigo} - ${cup.Nombre}`
                        }
                    }));
            },
            fetchTipoAgenda() {
                axios.get('/api/especialidad/Especialidadtipoagenda/allEspecialidades')
                    .then(res => {
                        this.tAgendas = res.data
                    });
            },
            fetchtipoCita(){
                axios.get('/api/tipocita/all')
                .then(res => {
                    this.tipoCita = res.data;
                })
            },
            fetchTipoActividad() {
                axios.get('/api/tipoagenda/all')
                    .then(res => this.actividades = res.data);
            },
            fetchEspecialidad() {
                axios.get('/api/especialidad/all')
                    .then(res => this.especialidades = res.data);
            },
            createTipoAgenda() {
                this.fetchTipoActividad();
                this.fetchEspecialidad();
                this.emptyData();
                this.save = true;
                this.dialog = true;
            },
            showError(message) {
                swal({
                    title: "¡No puede ser!",
                    text: `${message.name}`,
                    icon: "warning",
                });
            },
            emptyData() {
                this.data = {
                    especialidad: '',
                    Tipoagenda_id: '',
                    Primeravez_id: '',
                    Control_id: '',
                    Duracion: 0,
                }
            },
            saveTipoAgenda() {
                axios.post(`/api/especialidad/${this.data.especialidad}/Especialidadtipoagenda/create`, this.data)
                    .then(res => {
                        this.emptyData();
                        this.dialog = false;
                        this.fetchTipoAgenda();
                        swal({
                            title: "¡Tipo Agenda Creado!",
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch(err => this.showError(err.response.data))
            },
            editTipoAgenda(tAgenda) {
                this.data = tAgenda;
                this.data.especialidad = parseInt(tAgenda.Especialidad_id);
                this.data.Tipoagenda_id = parseInt(tAgenda.Tipoagenda_id);
                this.data.Primeravez_id = parseInt(tAgenda.Primeravez_id);
                this.data.Control_id = parseInt(tAgenda.Control_id);
                this.save = false;
                this.dialog = true;
            },
            updateTipoAgenda() {
                axios.put(`/api/tipoagenda/edit/${this.data.id}`, this.data)
                    .then(res => {
                        this.emptyData();
                        this.dialog = false;
                        this.fetchTipoAgenda();
                        swal({
                            title: "¡Tipo Agenda Actualizado!",
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch(err => console.log(err.response.data));
            },

            cambiarEstado(id){
                 swal({
                    title: "¿Está Seguro(a)?",
                    text: "Se cambiara el estado de la actividad",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        this.preload = true
                        axios.put('/api/especialidad/Especialidadtipoagenda/cambioEstado/' + id).then(res =>{
                            this.fetchTipoAgenda();
                            this.preload = false;
                            }).catch(err => {
                                console.log(err.response.data);
                                 this.preload = false;
                                });
                    }else{
                        this.preload = false;
                    }
                    });
            }
        },
    }

</script>
<style lang="scss" scoped>

</style>
