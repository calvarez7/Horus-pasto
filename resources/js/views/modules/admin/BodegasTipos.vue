<template>
    <v-flex xs6 px-1>
        <v-card>
            <v-container pa-1>
                    <v-container pt-3 pb-1>
                        <v-layout row>
                            <v-dialog v-model="dialog" persistent max-width="600px">
                            <v-card>
                                <v-card-title class="headline success" style="color:white">
                                <span v-if="save" class="headline">Crear Tipo</span>
                                <span v-else class="headline">Editar Tipo</span>
                                </v-card-title>
                                <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                    <v-flex xs12 sm6 md6>
                                        <v-text-field label="Nombre*" v-model="data.Nombre" required></v-text-field>
                                    </v-flex>
                                    <pre>{{ data }}</pre>
                                    </v-layout>
                                </v-container>
                                </v-card-text>
                                <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" flat @click="dialog = false">Cancelar</v-btn>
                                <v-btn v-if="save" color="blue darken-1" flat @click="saveTipo()">Guardar</v-btn>
                                <v-btn v-else color="blue darken-1" flat @click="updateTipo()">Actualizar</v-btn>
                                </v-card-actions>
                            </v-card>
                            </v-dialog>
                        </v-layout>
                    </v-container>
                <v-card-title>
                <v-btn round v-if="can('tipo-bodega.create')" @click="createTipo()" color="primary" dark ><v-icon left>person_add</v-icon>Crear Tipo</v-btn>
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
                    :items="tipos"
                    :search="search"
                    >
                    <template v-slot:items="props">
                        <td>{{ props.item.id }}</td>
                        <td class="text-xs-right">{{ props.item.Nombre }}</td>
                        <td class="text-xs-right">
                            <v-btn v-if="can('tipo-bodega.edit')" fab outline color="warning" small @click="editTipo(props.item)"><v-icon>edit</v-icon></v-btn>
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
    import { mapGetters } from 'vuex';

    export default {
        name:'BodegasTipos',
        data() {
            return {
                tipos: [],
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
                        text: '',
                        align: 'right',
                        sortable: false,
                        value: ''
                    }
                ],
                save: true,
                dialog: false,
                data:{
                    Nombre: "admin",
                },
            }
        },
        computed:{
            ...mapGetters(['can'])
        },
        mounted () {
            this.fetchTipos()
        },
        methods: {
            fetchTipos() {
                axios.get('/api/tipobodega/all')
                    .then(res => this.tipos = res.data)
            },
            createTipo(){
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
                    Nombre: ''
                }
            },
            saveTipo(){
                axios.post('/api/tipobodega/create',this.data)
                .then(res => {
                    this.emptyData();
                    this.dialog = false;
                    this.fetchTipos();
                    swal({
                        title: "¡Tipo Creado!",
                        text: " ",
                        type: "success",
                        timer: 2000,
                        icon: "success",
                        buttons: false
                    });
                })
                .catch(err => this.showError(err.response.data))
            },
            editTipo(role){
                this.data = {
                    id: role.id,
                    Nombre: role.Nombre
                };
                this.save = false;
                this.dialog = true;
            },
            updateTipo() {
                axios.put(`/api/tipobodega/edit/${this.data.id}`,this.data)
                    .then(res => {   
                        this.emptyData();
                        this.dialog = false;
                        this.fetchTipos();
                        swal({
                            title: "¡Tipo Actualizado!",
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