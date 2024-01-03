<template>
    <v-card>
        <v-container pa-1>
            <v-container pt-3 pb-1>
                <v-layout row>
                    <v-dialog v-model="dialog" max-width="600px">
                    <v-card>
                        <v-card-title class="headline success" style="color:white">
                        <span v-if="save" class="headline">Crear Grupo</span>
                        <span v-else class="headline">Editar Grupo</span>
                        </v-card-title>
                        <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                            <v-flex xs12 sm6 md6>
                                <v-text-field label="Nombre*" v-model="data.Nombre" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-text-field label="Código*" v-model="data.Codigo" required></v-text-field>
                            </v-flex>
                            </v-layout>
                        </v-container>
                        </v-card-text>
                        <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" flat @click="dialog = false">Cancelar</v-btn>
                        <v-btn v-if="save" color="blue darken-1" flat @click="saveGrupo()">Guardar</v-btn>
                        <v-btn v-else color="blue darken-1" flat @click="updateGrupo()">Actualizar</v-btn>
                        </v-card-actions>
                    </v-card>
                    </v-dialog>
                </v-layout>
            </v-container>
            <v-container pt-3 pb-1>
                <v-layout row>
                    <v-dialog v-model="subDialog" persistent max-width="600px">
                    <v-card>
                        <v-card-title>
                        <span v-if="save" class="headline">Crear SubGrupo</span>
                        <span v-else class="headline">Editar SubGrupo</span>
                        </v-card-title>
                        <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                            <v-flex xs12 sm6 md6>
                                <v-text-field label="Nombre*" v-model="subData.Nombre" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-text-field label="Descripción*" v-model="subData.Descripcion" required></v-text-field>
                            </v-flex>
                            <pre>{{subData}}</pre>
                            </v-layout>
                        </v-container>
                        </v-card-text>
                        <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" flat @click="subDialog = false">Cancelar</v-btn>
                        <v-btn v-if="subSave" color="blue darken-1" flat @click="saveSubGrupo()">Guardar</v-btn>
                        <v-btn v-else color="blue darken-1" flat @click="updateSubGrupo()">Actualizar</v-btn>
                        </v-card-actions>
                    </v-card>
                    </v-dialog>
                </v-layout>
            </v-container>
            <v-card-title>
                <v-btn v-if="can('grupo.create')" round @click="createGrupo()"  color="primary" dark><v-icon left>person_add</v-icon>Nuevo Grupo</v-btn>
            <v-spacer></v-spacer>
            <v-flex 
            xs1
            sm5
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
                :items="grupos"
                :search="search"
                rowsPerPageText="Filas por página"
                >
                <template v-slot:items="props">
                <tr @click="expandTable(props)">
                    <td>{{ props.item.id }}</td>
                    <td class="text-xs-right">{{ props.item.Nombre }}</td>
                    <td class="text-xs-right">{{ props.item.Codigo }}</td>
                    <td class="text-xs-right">
                        <v-btn v-if="can('grupo.edit')" fab outline color="warning" small @click="editGrupo(props.item)"><v-icon>edit</v-icon></v-btn>
                        <v-btn v-if="can('subgrupo.create')" fab outline color="success" small @click="createSubGrupo(props)" ><v-icon>add</v-icon></v-btn>
                    </td>
                </tr>
                </template>
                <template v-slot:expand="props">
                    <v-data-table
                    :headers="subHeaders"
                    :items="subGrupos"
                    :hide-actions="true"
                    >
                        <template v-slot:items="props">
                            <td>{{ props.item.id }}</td>
                            <td class="text-xs-right">{{ props.item.Nombre }}</td>
                            <td class="text-xs-right">{{ props.item.Descripcion }}</td>
                            <td class="text-xs-right">
                                <v-btn v-if="can('subgrupo.edit')" fab color="warning" small @click="editSubGrupo(props.item)"><v-icon>edit</v-icon></v-btn>
                            </td>
                        </template>

                        <v-card flat>
                        <v-card-text>{{ props.item.Nombre }}</v-card-text>
                        </v-card>
                    </v-data-table>
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
        name: 'GrupoMedicamentos',
        data() {
            return {
                expand: false,
                grupos: [],
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
                        text: 'Código',
                        align: 'right',
                        sortable: false,
                        value: 'Codigo'
                    },
                    {
                        text: '',
                        align: 'right',
                        sortable: false,
                        value: ''
                    }
                ],
                subHeaders: [
                     {  text: 'id', align: 'left', value: 'id' },
                     {  text: 'Nombre', align: 'right', value: 'Nombre' },
                     {  text: 'Descripción', align: 'right', value: 'Descripcion' },
                     {  text: '', align: 'right', value: '' },
                ],
                save: true,
                subSave: true,
                dialog: false,
                subDialog: false,
                data:{
                    Nombre:'',
                    Codigo:'',
                },
                subData:{
                    Grupo_id: '',
                    Nombre: '',
                    Descripcion: ''
                },
                subGrupos: []
            }
        },
        computed:{
            ...mapGetters(['can'])
        },
        mounted () {
            this.fetchGrupos();
        },
        methods: {
            fetchGrupos() {
                axios.get('/api/grupo/all')
                    .then(res => this.grupos = res.data);
            },
            createGrupo(){
                this.emptyData();
                this.save = true;
                this.dialog = true;
            },
            async createSubGrupo(grupo){
                await this.emptySubData();
                this.subData.Grupo_id = grupo.item.id;
                this.subSave = true;
                this.subDialog = true;
            },
            showError(message){
                swal({
                    title: "¡No puede ser!",
                    text: `${message.name}`,
                    icon: "warning",
                });
            },
            async expandTable(props){
                await this.getSubGrupos(props.item.id);
                props.expanded = !props.expanded;
                
            },
            getSubGrupos(Grupo_id){
                axios.post(`/api/subgrupo/all`,{ Grupo_id })
                .then(res =>  this.subGrupos = res.data )
                .catch(err => this.showError(err.response.data))
            },
            emptyData(){
                this.data = {
                    Nombre: '',
                    Codigo: ''
                }
            },
            emptySubData(){
                this.subData = {
                    Grupo_id: '',
                    Nombre: '',
                    Descripcion: ''
                }
            },
            saveGrupo(){
                axios.post('/api/grupo/create',this.data)
                .then(res => {
                    this.emptyData();
                    this.dialog = false;
                    this.fetchGrupos();
                    swal({
                        title: "¡Grupo Creada!",
                        text: " ",
                        timer: 2000,
                        icon: "success",
                        buttons: false
                    });
                })
                .catch(err => this.showError(err.response.data))
            },
            saveSubGrupo(){
                axios.post(`/api/subgrupo/create`,this.subData)
                .then(res => {
                    this.subDialog = false;
                    this.getSubGrupos(this.subData.Grupo_id);
                    this.emptySubData();
                    swal({
                        title: "¡Subgrupo Creado!",
                        text: " ",
                        timer: 2000,
                        icon: "success",
                        buttons: false
                    });
                })
                .catch(err => this.showError(err.response.data))
            },
            editGrupo(role){
                this.data = role;
                this.save = false;
                this.dialog = true;
            },
            editSubGrupo(SubGrupo){
                this.subData = SubGrupo;
                this.subSave = false;
                this.subDialog = true;
            },
            updateGrupo() {
                axios.put(`/api/grupo/edit/${this.data.id}`,this.data)
                    .then(res => {   
                        this.emptyData();
                        this.dialog = false;
                        this.fetchGrupos();   
                        swal({
                            title: "¡Grupo Actualizado!",
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch(err => console.log(err.response.data));
            },
            updateSubGrupo(){
                axios.put(`/api/subgrupo/edit/${this.subData.id}`,this.subData)
                    .then(res => {   
                        this.emptySubData();
                        this.subDialog = false;
                        swal({
                            title: "¡SubGrupo Actualizado!",
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch(err => console.log(err.response.data));
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>