<template>
    <v-flex xs6 px-1>
        <v-card>
            <v-container pa-1>
                <v-container pt-3 pb-1>
                    <v-layout row>
                        <v-dialog v-model="dialog" max-width="600px">
                        <v-card>
                            <v-card-title class="headline success" style="color:white">
                            <span v-if="save" class="headline">Crear Transacción</span>
                            <span v-else class="headline">Editar Transacción</span>
                            </v-card-title>
                            <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                <v-flex xs12 sm6 md6>
                                    <v-text-field label="Nombre*" v-model="data.Nombre" required></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-text-field label="Descripción*" v-model="data.Descripcion" required></v-text-field>
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
                    <v-btn v-if="can('transaccion.create')" round @click="createTransaccion()" color="primary" dark ><v-icon left>person_add</v-icon>Crear Transaccion</v-btn>
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
                    :items="transaccions"
                    :search="search"
                    >
                    <template v-slot:items="props">
                        <td>{{ props.item.id }}</td>
                        <td class="text-xs-right">{{ props.item.Nombre }}</td>
                        <td class="text-xs-right">{{ props.item.Descripcion }}</td>
                        <td class="text-xs-right">
                            <v-btn v-if="can('transaccion.edit')" fab outline color="warning" small @click="editTransaccion(props.item)"><v-icon>edit</v-icon></v-btn>
                        </td>
                    </template>
                    <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                        Your search for "{{ search }}" found no results.
                    </v-alert>
                </v-data-table>
            </v-container>
        </v-card>
    </v-flex>
</template>

<script>
    import { mapGetters } from 'vuex' 
    export default {
        name:'BodegaListTransaccion',
        data() {
            return {
                transaccions: [],
                search: '',
                headers: [
                    {
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Nombre',
                        align: 'right',
                        sortable: false,
                        value: 'Nombre'
                    },
                    {
                        text: 'Descripción',
                        align: 'right',
                        sortable: false,
                        value: 'Descripcion'
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
                    Nombre: '',
                    Descripcion:''
                },
            }
        },
        computed:{
            ...mapGetters(['can'])
        },
        mounted () {
            this.fetchTransaccions()
        },
        methods: {
            fetchTransaccions() {
                axios.get('/api/transacion/all')
                    .then(res => this.transaccions = res.data)
            },
            createTransaccion(){
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
                    Nombre: '',
                    Descripcion: ''
                }
            },
            saveTransaccion(){
                axios.post('/api/transacion/create',this.data)
                .then(res => {
                    this.emptyData();
                    this.dialog = false;
                    this.fetchTransaccions();
                    swal({
                        title: "¡Transaccion Creado!",
                        text: " ",
                        type: "success",
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
                    Nombre: transaccion.Nombre,
                    Descripcion: transaccion.Descripcion,
                };
                this.save = false;
                this.dialog = true;
            },
            updateTransaccion() {
                axios.put(`/api/transacion/edit/${this.data.id}`,this.data)
                    .then(res => {   
                        this.emptyData();
                        this.dialog = false;
                        this.fetchTransaccions();
                        swal({
                            title: "¡Transaccion Actualizado!",
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