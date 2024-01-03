<template>
    <v-card min-height="100%">
        <v-container pa-1>


            <v-dialog v-model="dialogCreate" persistent max-width="600px">
                <v-card>
                    <form @submit.prevent="guardar">
                    <v-card-title>
                        <span class="headline">Nueva Entidad</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm12 md12>
                                    <v-text-field label="Nombre" v-model="form.nombre" outline required></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-switch
                                        v-model="form.agendar_paciente"
                                        label="Agendar Pacientes"
                                        color="indigo"
                                        hide-details
                                    ></v-switch>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-switch
                                        v-model="form.entrega_medicamentos"
                                        label="Entrega Medicamentos"
                                        color="indigo"
                                        hide-details
                                    ></v-switch>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-switch
                                        v-model="form.atender_paciente"
                                        label="Atender Paciente"
                                        color="indigo"
                                        hide-details
                                    ></v-switch>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-switch
                                        v-model="form.autorizar_ordenes"
                                        label="Autorizar Ordenamiento"
                                        color="indigo"
                                        hide-details
                                    ></v-switch>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-switch
                                        v-model="form.consutar_historico"
                                        label="Consultar Historia"
                                        color="indigo"
                                        hide-details
                                    ></v-switch>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-switch
                                        v-model="form.generar_ordenes"
                                        label="Generar Ordenamiento"
                                        color="indigo"
                                        hide-details
                                    ></v-switch>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error"  @click="cerrarModal()">Cerrar</v-btn>
                        <v-btn color="success" type="submit">Guardar</v-btn>
                    </v-card-actions>
                    </form>
                </v-card>
            </v-dialog>

            <v-dialog v-model="dialog" persistent max-width="600px">
                <v-card>
                    <v-card-title>
                        <span class="headline">Permisos en Aplicativo</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm6 md4>
                                    <v-switch
                                        v-model="permisos.agendar_paciente"
                                        label="Agendar Pacientes"
                                        color="indigo"
                                        hide-details
                                    ></v-switch>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-switch
                                        v-model="permisos.entrega_medicamentos"
                                        label="Entrega Medicamentos"
                                        color="indigo"
                                        hide-details
                                    ></v-switch>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-switch
                                        v-model="permisos.atender_paciente"
                                        label="Atender Paciente"
                                        color="indigo"
                                        hide-details
                                    ></v-switch>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-switch
                                        v-model="permisos.autorizar_ordenes"
                                        label="Autorizar Ordenamiento"
                                        color="indigo"
                                        hide-details
                                    ></v-switch>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-switch
                                        v-model="permisos.consutar_historico"
                                        label="Consultar Historia"
                                        color="indigo"
                                        hide-details
                                    ></v-switch>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-switch
                                        v-model="permisos.generar_ordenes"
                                        label="Generar Ordenamiento"
                                        color="indigo"
                                        hide-details
                                    ></v-switch>
                                </v-flex>

                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error"  @click="cerrarModal()">Cerrar</v-btn>
                        <v-btn color="success" @click="actualizar">Guardar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
            <v-card-title>
                <v-btn round color="primary" @click="dialogCreate = true" dark>
                    <v-icon left>person_add</v-icon>Nueva Entidad
                </v-btn>
                <v-spacer></v-spacer>
                <v-flex xs12 sm5>
                    <v-text-field v-model="search" append-icon="search" label="Buscar..." single-line hide-details>
                    </v-text-field>
                </v-flex>
            </v-card-title>

            <v-data-table class="elevation-1" :headers="headers" :items="listEntidades" :search="search">
                <template v-slot:items="props">
                    <td>{{ props.item.id }}</td>
                    <td class="">{{ props.item.nombre }}</td>
                    <td class="text-xs-center" v-if="props.item.estado_id == '1'"><v-chip color="success" text-color="white">Activo</v-chip></td>
                    <td class="text-xs-center" v-if="props.item.estado_id == '2'"><v-chip color="error" text-color="white">Inactivo</v-chip></td>
                    <td class="">
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                        <v-btn fab dark small v-on="on" color="success" v-show="props.item.estado_id == '2'" @click="actualizarEstado(1,props.item.id)">
                        <v-icon dark>check</v-icon>
                    </v-btn>
                            </template>
                            <span>Activar</span>
                        </v-tooltip>
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                        <v-btn fab dark small color="error" v-on="on" v-show="props.item.estado_id == '1'" @click="actualizarEstado(2,props.item.id)">
                            <v-icon dark>close</v-icon>
                        </v-btn>
                </template>
                <span>Inactivar</span>
                </v-tooltip>
                </td>
                    <td>
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <v-btn fab dark small color="info" v-on="on" @click="listarPermisos(props.item)">
                                <v-icon dark>list</v-icon>
                            </v-btn>
                        </template>
                        <span>Permisos</span>
                    </v-tooltip>
                    </td>

                </template>
                <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                    Your search for "{{ search }}" found no results.
                </v-alert>
            </v-data-table>
        </v-container>
    </v-card>
</template>


<script>
    import {
        mapGetters
    } from 'vuex';
    export default {
        name: 'Entidades',
        data() {
            return {
                dialogCreate:false,
                search:"",
                dialog: false,
                listEntidades: [],
                idEntidad: null,
                permisos:{
                    agendar_paciente:null,
                    entrega_medicamentos:null,
                    atender_paciente:null,
                    autorizar_ordenes:null,
                    consutar_historico:null,
                    generar_ordenes:null
                },
                form:{
                    nombre:null,
                    agendar_paciente:null,
                    entrega_medicamentos:null,
                    atender_paciente:null,
                    autorizar_ordenes:null,
                    consutar_historico:null,
                    generar_ordenes:null,
                    estado_id:1
                },
                headers:[
                    {
                        text: 'id',
                        align: 'left',
                        sortable:false
                    },
                    {
                        text: 'Nombre',
                        align: 'left',
                        value: 'nombre'
                    },
                    {
                        text: 'Estado',
                        align: 'center',
                        sortable:false

                    },
                    {
                        text: 'Acciones',
                        align: '',
                        sortable:false

                    },
                    {
                        text: 'Permisos',
                        align: '',
                        sortable:false

                    },
                ]
            }
        },
        methods:{
            getEntidades(){
                axios.get('/api/entidades/getEntidades').then(res => {
                    this.listEntidades = res.data;
                });
            },
            cerrarModal(){
                this.clearData();
                this.dialog = false;
                this.dialogCreate = false;

            },
            clearData(){
                this.idEntidad = null;
                for (const prop of Object.getOwnPropertyNames(this.permisos)) {
                    this.permisos[prop] = "";
                }
                for (const prop of Object.getOwnPropertyNames(this.form)) {
                    this.form[prop] = "";
                }
            },
            actualizar(){
                axios.post('/api/entidades/guardarPermisos/'+this.idEntidad,this.permisos).then(res => {
                    this.$alerSuccess(res.data);
                    this.clearData();
                    this.dialog = false;
                    this.getEntidades();
                }).catch((err) => this.$alerError(err.response.data.message));
            },
            listarPermisos(item){
                console.log(item);
                this.permisos.agendar_paciente = parseInt(item.agendar_paciente);
                this.permisos.entrega_medicamentos = parseInt(item.entrega_medicamentos);
                this.permisos.atender_paciente = parseInt(item.atender_paciente);
                this.permisos.autorizar_ordenes = parseInt(item.autorizar_ordenes);
                this.permisos.consutar_historico = parseInt(item.consutar_historico);
                this.permisos.generar_ordenes = parseInt(item.generar_ordenes);
                this.dialog = true;
                this.idEntidad = item.id;
            },
            actualizarEstado(tipo,entidad){
                const data = {
                    estado_id: tipo
                };
                axios.post('/api/entidades/guardarPermisos/'+entidad,data).then(res => {
                    this.$alerSuccess(res.data);
                    this.getEntidades();
                }).catch((err) => this.$alerError(err.response.data.message));
            },
            guardar(){
                axios.post('/api/entidades/guardar',this.form).then(res => {
                    this.$alerSuccess(res.data);
                    this.dialogCreate = false;
                    this.clearData();
                    this.getEntidades();
                }).catch((err) => this.$alerError(err.response.data.message));
            }
        },
        mounted() {
            this.getEntidades();
        }
    }
</script>
