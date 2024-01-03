<template>
    <v-card>
        <v-container pa-1>
                <v-container pt-3 pb-1>
                    <v-layout row>
                        <v-dialog v-model="dialog" persistent max-width="600px">
                        <v-card>
                            <v-card-title class="headline success" style="color:white">
                            <span v-if="save" class="headline">Crear Permiso</span>
                            <span v-else class="headline">Editar Permiso</span>
                            </v-card-title>
                            <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                <v-flex xs12 sm7 md7>
                                    <v-text-field label="Nombre*" v-model="data.name" required></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm5 md5>
                                    <v-autocomplete
                                    :items="tipos"
                                    item-text="Nombre" 
                                    item-value="id"
                                    label="Tipo"
                                    v-model="data.Tipo_id"
                                    ></v-autocomplete>
                                </v-flex>
                                </v-layout>
                            </v-container>
                            </v-card-text>
                            <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" flat @click="dialog = false">Cancelar</v-btn>
                            <v-btn v-if="save" color="blue darken-1" flat @click="savePermiso()">Guardar</v-btn>
                            <v-btn v-else color="blue darken-1" flat @click="updatePermiso()">Actualizar</v-btn>
                            </v-card-actions>
                        </v-card>
                        </v-dialog>
                    </v-layout>
                </v-container>
            <v-card-title>
            <v-btn v-if="can('permiso.create')" round @click="createPermiso()" color="primary" dark ><v-icon left>person_add</v-icon>Crear Permiso</v-btn>
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
                :items="permisos"
                :search="search"
                >
                <template v-slot:items="props">
                    <td>{{ props.item.id }}</td>
                    <td>{{ props.item.name }}</td>
                    <td>{{ props.item.Nombre }}</td>
                    <td class="text-xs-right">
                        <v-btn v-if="can('permiso.edit')" fab outline color="warning" small @click="editPermiso(props.item)"><v-icon>edit</v-icon></v-btn>
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
    import { mapGetters } from 'vuex';
    export default {
        name:'TableRole',
        data() {
            return {
                permisos: [],
                search: '',
                headers: [
                    {
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
                        text: 'Tipo',
                        align: 'left',
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
                    name: "admin",
                    Tipo_id: ""
                },
                tipos: []
            }
        },
        computed:{
            ...mapGetters(['can'])
        },
        mounted () {
            this.fetchPermissions()
            this.allTipos()
        },
        methods: {
            fetchPermissions() {
                axios.get('/api/permiso/allWithTipos')
                    .then(res => this.permisos = res.data)
            },
            createPermiso(){
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
                    name: ''
                }
            },
            savePermiso(){
                axios.post('/api/permiso/create',this.data)
                .then(res => {
                    this.emptyData();
                    this.dialog = false;
                    this.fetchPermissions();
                    swal({
                        title: "¡Permiso Creado!",
                        text: " ",
                        type: "success",
                        timer: 2000,
                        icon: "success",
                        buttons: false
                    });
                })
                .catch(err => this.showError(err.response.data))
            },
            editPermiso(role){
                this.data = {
                    id: role.id,
                    name: role.name
                };
                this.save = false;
                this.dialog = true;
            },
            updatePermiso() {
                axios.put(`/api/permiso/edit/${this.data.id}`,this.data)
                    .then(res => {   
                        this.emptyData();
                        this.dialog = false;
                        this.fetchPermissions();
                        swal({
                            title: "¡Permiso Actualizado!",
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch(err => console.log(err.response.data));
            },
            allTipos(){
                axios.get('/api/tipo/all')
                    .then(res => this.tipos = res.data)
            }
        },
    }
</script>
<style lang="scss" scoped>

</style>