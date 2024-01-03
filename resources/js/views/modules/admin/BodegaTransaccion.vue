<template>
    <v-layout row wrap>
        <BodegaTipoTransaccion />
        <BodegaListTransaccion />  
        <v-flex xs12 py-2>
            <v-card>
                <v-container pa-1>
                    <v-container pt-3 pb-1>
                        <v-layout row>
                            <v-dialog v-model="dialog" max-width="600px">
                            <v-card>
                                <v-card-title>
                                <span v-if="save" class="headline">Agregar Movimiento</span>
                                <span v-else class="headline">Editar Movimiento</span>
                                </v-card-title>
                                <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                    <v-flex xs12 sm6 md6>
                                        <v-autocomplete label="Tipo*" :items="tipos" item-text="Nombre" item-value="id" v-model="data.Tipo_id" required></v-autocomplete>
                                    </v-flex>
                                    <v-flex xs12 sm6 md6>
                                        <v-autocomplete label="Transacción*" :items="transaccions" item-text="Nombre" item-value="id" v-model="data.Transacion_id" required></v-autocomplete>
                                    </v-flex>
                                    </v-layout>
                                </v-container>
                                </v-card-text>
                                <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" flat @click="dialog = false">Cancelar</v-btn>
                                <v-btn v-if="save" color="blue darken-1" flat @click="saveTransaccion()">Guardar</v-btn>
                                <v-btn v-else color="blue darken-1" flat @click="updateTransaccion()">Actualizar</v-btn>
                                </v-card-actions>
                            </v-card>
                            </v-dialog>
                        </v-layout>
                    </v-container>
                    <v-card-title>
                        <v-btn v-if="can('movimiento.create')" round @click="createTransaccion()" color="primary" dark ><v-icon left>person_add</v-icon>Agregar Movimiento</v-btn>
                        <v-spacer></v-spacer>
                        <v-flex 
                            sm5
                            xs12
                            >
                            <v-text-field
                                v-model="search"
                                append-icon="search"
                                label="Buscar..."
                                single-line
                                hide-details
                            ></v-text-field>
                        </v-flex>  
                    </v-card-title>
                    <v-data-table
                        :headers="headers"
                        :items="tipoTransaccions"
                        :search="search"
                        >
                        <template v-slot:items="props">
                            <td>{{ props.item.id }}</td>
                            <td class="text-xs-right">{{ props.item.TipoNombre }}</td>
                            <td class="text-xs-right">{{ props.item.TransacionNombre }}</td>
                            <td class="text-xs-right">
                                <v-btn v-if="can('movimiento.edit')" fab outline color="warning" small @click="editTransaccion(props.item)"><v-icon>edit</v-icon></v-btn>
                            </td>
                        </template>
                        <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                            Your search for "{{ search }}" found no results.
                        </v-alert>
                    </v-data-table>
                </v-container>
            </v-card>
        </v-flex>

    </v-layout>
</template>

<script>
    import { mapGetters } from 'vuex' 
    import BodegaTipoTransaccion from './BodegaTipoTransaccion.vue'
    import BodegaListTransaccion from './BodegaListTransaccion.vue'

    export default {
        name:'TipoTransaccion',
        components:{
            BodegaTipoTransaccion,
            BodegaListTransaccion
        },
        data() {
            return {
                transaccions: [],
                tipos: [],
                tipoTransaccions: [],
                search: '',
                headers: [
                    {
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Tipo',
                        align: 'right',
                        sortable: false,
                        value: 'TipoNombre'
                    },
                    {
                        text: 'Transacción',
                        align: 'right',
                        sortable: false,
                        value: 'TransacionNombre'
                    },
                    {
                        text: '',
                        align: 'right',
                        sortable: false,
                        value: ''
                    }
                ],
                save: true,
                dialog: false,
                data:{
                    Transacion_id: '',
                    Tipo_id:''
                },
            }
        },
        computed:{
            ...mapGetters(['can'])
        },
        mounted () {
            this.fetchTipoTransaccions();
            this.fetchTransaccions();
            this.fetchTipos();
        },
        methods: {
            fetchTipoTransaccions() {
                axios.get('/api/tipotransacion/all')
                    .then(res => this.tipoTransaccions = res.data)
            },
            fetchTipos() {
                axios.get('/api/tipo/all')
                    .then(res => this.tipos = res.data)
            },
            fetchTransaccions() {
                axios.get('/api/transacion/all')
                    .then(res => this.transaccions = res.data)
            },
            createTransaccion(){
                this.fetchTransaccions();
                this.fetchTipos();
                this.emptyData();
                this.save = true;
                this.dialog = true;
            },
            showError(message){
                swal({
                    title: "¡No puede ser!",
                    text: `${message.name}`,
                    icon: "warning",
                });
            },
            emptyData(){
                this.data = {
                    Transacion_id: '',
                    Tipo_id:''
                }
            },
            saveTransaccion(){
                axios.post('/api/tipotransacion/create',this.data)
                .then(res => {
                    this.emptyData();
                    this.dialog = false;
                    this.fetchTipoTransaccions();
                    swal({
                        title: "¡Tipo Transacción Creado!",
                        text: " ",
                        timer: 2000,
                        icon: "success",
                        buttons: false
                    });
                })
                .catch(err => this.showError(err.response.data))
            },
            editTransaccion(transaccion){
                this.data = {
                    id: transaccion.id,
                    Transacion_id: transaccion.Transacion_id,
                    Tipo_id: transaccion.Tipo_id,
                };
                this.save = false;
                this.dialog = true;
            },
            updateTransaccion() {
                axios.put(`/api/tipotransacion/edit/${this.data.id}`,this.data)
                    .then(res => {   
                        this.emptyData();
                        this.dialog = false;
                        this.fetchTipoTransaccions();
                        swal({
                            title: "¡Transacción Actualizado!",
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch(err => console.log(err.response.data));
            },
        },
    }
</script>
<style lang="scss" scoped>

</style>