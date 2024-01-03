<template>
    <v-container pa-1>
        <v-container pt-3 pb-1>
            <v-layout row>
                <v-dialog v-model="dialog" persistent max-width="600px">
                <v-card>
                    <v-card-title class="headline success" style="color:white">
                    <span v-if="save" class="headline">Crear Cargo</span>
                    <span v-else class="headline">Editar Cargo</span>
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
                        <pre>{{ data }}</pre>
                        </v-layout>
                    </v-container>
                    </v-card-text>
                    <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="dialog = false">Cancelar</v-btn>
                    <v-btn v-if="save" color="blue darken-1" flat @click="saveCargo()">Guardar</v-btn>
                    <v-btn v-else color="blue darken-1" flat @click="updateCargo()">Actualizar</v-btn>
                    </v-card-actions>
                </v-card>
                </v-dialog>
            </v-layout>
        </v-container>
        <v-container pt-3 pb-1>
            <v-layout row>
                <v-dialog v-model="subDialog" persistent max-width="600px">
                <v-card>
                    <v-card-title class="headline success" style="color:white">
                    <span v-if="save" class="headline">Crear Actividad</span>
                    <span v-else class="headline">Editar Actividad</span>
                    </v-card-title>
                    <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                        <v-flex xs12 sm6 md6>
                            <v-text-field label="Nombre*" v-model="subData.Nombre" required></v-text-field>
                        </v-flex>
                        <pre>{{subData}}</pre>
                        </v-layout>
                    </v-container>
                    </v-card-text>
                    <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="subDialog = false">Cancelar</v-btn>
                    <v-btn v-if="subSave" color="blue darken-1" flat @click="saveActividad()">Guardar</v-btn>
                    <v-btn v-else color="blue darken-1" flat @click="updateActividad()">Actualizar</v-btn>
                    </v-card-actions>
                </v-card>
                </v-dialog>
            </v-layout>
        </v-container>
        <v-card-title>
            <v-btn v-if="can('cargo.create')" round @click="createCargo()"  color="primary" dark><v-icon left>person_add</v-icon>Nueva Cargo</v-btn>
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
            :items="cargos"
            :search="search"
            rowsPerPageText="Filas por página"
            >
            <template v-slot:items="props">
            <tr @click="expandTable(props)">
                <td>{{ props.item.id }}</td>
                <td class="text-xs-right">{{ props.item.Nombre }}</td>
                <td class="text-xs-right">{{ props.item.Descripcion }}</td>
                <td class="text-xs-right">
                    <v-btn v-if="can('cargo.edit')" fab outline color="warning" small @click="editCargo(props.item)"><v-icon>edit</v-icon></v-btn>
                    <v-btn v-if="can('actividad-cargo.create')" fab outline color="success" small @click="createActividad(props)" ><v-icon>add</v-icon></v-btn>
                </td>
            </tr>
            </template>
            <template v-slot:expand="props">
                <v-data-table
                 :headers="subHeaders"
                 :items="actividades"
                 :hide-actions="true"
                 >
                    <template v-slot:items="props">
                        <td>{{ props.item.id }}</td>
                        <td class="text-xs-right">{{ props.item.Nombre }}</td>
                        <td class="text-xs-right">
                            <v-btn v-if="can('actividad-cargo.edit')" fab color="warning" small @click="editActividad(props.item)"><v-icon>edit</v-icon></v-btn>
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
</template>

<script>
    import { mapGetters } from 'vuex';
    export default {
        name:'TableCargo',
        data() {
            return {
                expand: false,
                cargos: [],
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
                subHeaders: [
                     {  text: 'id', align: 'left', value: 'id' },
                     {  text: 'Nombre', align: 'right', value: 'Nombre' },
                     {  text: '', align: 'right', value: '' },
                ],
                save: true,
                subSave: true,
                dialog: false,
                subDialog: false,
                data:{
                    Nombre: '',
                    Descripcion: '',
                },
                subData:{
                    Cargo_id: '',
                    Nombre: '',
                },
                actividades: []
            }
        },
        computed:{
            ...mapGetters(['can'])
        },
        mounted () {
            this.fetchCargos();
        },
        methods: {
            fetchCargos() {
                axios.get('/api/cargo/all')
                    .then(res => this.cargos = res.data);
            },
            createCargo(){
                this.emptyData();
                this.save = true;
                this.dialog = true;
            },
            async createActividad(Cargo){
                await this.emptySubData();
                this.subData.Cargo_id = Cargo.item.id;
                this.subSave = true;
                this.subDialog = true;
            },
            showError(message){
                swal({
                    title: "¡No puede ser!",
                    text: `${message}`,
                    icon: "warning",
                });
            },
            async expandTable(props){
                await this.getActividades(props.item.id);
                props.expanded = !props.expanded;
                
            },
            getActividades(CargoId){
                axios.get(`/api/cargo/${CargoId}/actividadcargo/all`)
                .then(res =>  this.actividades = res.data )
                .catch(err => this.showError(err.response.data))
            },
            emptyData(){
                this.data = {
                    Nombre: '',
                    Descripcion: '',
                }
            },
            emptySubData(){
                this.subData = {
                    Cargo_id: '',
                    Nombre: '',
                }
            },
            saveCargo(){
                axios.post('/api/cargo/create',this.data)
                .then(res => {
                    this.emptyData();
                    this.dialog = false;
                    this.fetchCargos();
                    swal({
                        title: "¡Cargo Creado!",
                        text: " ",
                        timer: 2000,
                        icon: "success",
                        buttons: false
                    });
                })
                .catch(err => this.showError(err.response.data))
            },
            saveActividad(){
               // console.log('this.subData.Cargo_id :', this.subData.Cargo_id);
                axios.post(`/api/cargo/${this.subData.Cargo_id}/actividadcargo/create`,this.subData)
                .then(res => {
                    this.subDialog = false;
                    this.getActividades(this.subData.Cargo_id);
                    this.emptySubData();
                    swal({
                        title: "¡Actividad Creado!",
                        text: " ",
                        timer: 2000,
                        icon: "success",
                        buttons: false
                    });
                })
                .catch(err => this.showError(err.response.data))
            },
            editCargo(role){
                this.data = role;
                this.save = false;
                this.dialog = true;
            },
            editActividad(Actividad){
                this.subData = Actividad;
                this.subSave = false;
                this.subDialog = true;
            },
            updateCargo() {
                axios.put(`/api/cargo/edit/${this.data.id}`,this.data)
                    .then(res => {   
                        this.emptyData();
                        this.dialog = false;
                        this.fetchCargos();   
                        swal({
                            title: "¡Cargo Actualizado!",
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch(err => console.log(err.response.data));
            },
            updateActividad(){
                axios.put(`/api/cargo/${this.subData.Cargo_id}/actividadcargo/edit/${this.subData.id}`,this.subData)
                    .then(res => {   
                        this.emptySubData();
                        this.subDialog = false;
                        swal({
                            title: "¡Actividad Actualizado!",
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