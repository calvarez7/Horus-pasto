<template>
    <v-card min-height="100%">
        <v-container pa-1>
            <v-container pt-3 pb-1>
                <v-layout row>
                    <v-dialog v-model="dialog" persistent max-width="600px">
                    <v-card>
                        <v-card-title class="headline success" style="color:white">
                        <span v-if="save" class="headline">Crear Tipo Familia</span>
                        <span v-else class="headline">Editar Tipo Familia</span>
                        </v-card-title>
                        <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                            <v-flex xs12 sm6 md6>
                                <v-text-field label="Nombre *" v-model="data.Nombre" required></v-text-field>
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
                        <v-btn v-if="save" color="blue darken-1" flat @click="saveTipoFamilia()">Guardar</v-btn>
                        <v-btn v-else color="blue darken-1" flat @click="updateTipoFamilia()">Actualizar</v-btn>
                        </v-card-actions>
                    </v-card>
                    </v-dialog>
                </v-layout>
            </v-container>
            <v-card-title>
                <v-flex xs12>
                    <span class="headline layout justify-center">Tipos Familia</span>
                </v-flex>
                    <v-flex xs4>                        
                        <v-btn v-if="can('tipo-familia.create')" round @click="createTipoFamilia()" color="primary" dark><v-icon left>exposure_plus_1</v-icon>Nuevo Tipo Familia</v-btn>
                    </v-flex>
                    <v-spacer></v-spacer>
                    <v-flex
                    xs6>
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
                :items="tipoFamilias"
                :search="search"
                >
                <template v-slot:items="props">
                    <td>{{ props.item.id }}</td>
                    <td class="text-xs-right">{{ props.item.Nombre }}</td>
                    <td class="text-xs-right">{{ props.item.Descripcion }}</td>
                    <td class="text-xs-right">
                        <v-btn v-if="can('tipo-familia.edit')" fab outline color="warning" small @click="editTipoFamilia(props.item)"><v-icon>edit</v-icon></v-btn>
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
        name:'TableTipoFamilia',
        data() {
            return {
                tipoFamilias: [],
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
                    Descripcion: '',
                },
            }
        },
        computed:{
            ...mapGetters(['can'])
        },
        mounted () {
            this.fetchTipoFamilias();
        },
        methods: {
            fetchTipoFamilias() {
                axios.get('/api/tipofamilia/all')
                    .then(res => this.tipoFamilias = res.data);
            },
            createTipoFamilia(){
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
                    Descripcion: '',
                }
            },
            saveTipoFamilia(){
                axios.post('/api/tipofamilia/create',this.data)
                .then(res => {
                    this.emptyData();
                    this.dialog = false;
                    this.fetchTipoFamilias();
                    swal({
                        title: "¡Tipo Familia Creado!",
                        text: " ",
                        timer: 2000,
                        icon: "success",
                        buttons: false
                    });
                })
                .catch(err => this.showError(err.response.data))
            },
            editTipoFamilia(tipoFamilia){
                this.data.id = tipoFamilia.id;
                this.data.Nombre = tipoFamilia.Nombre;
                this.data.Descripcion = tipoFamilia.Descripcion;
                this.save = false;
                this.dialog = true;
            },
            updateTipoFamilia() {
                axios.put(`/api/tipofamilia/edit/${this.data.id}`,this.data)
                    .then(res => {   
                        this.emptyData();
                        this.dialog = false;
                        this.fetchTipoFamilias();   
                        swal({
                            title: "¡Tipo Familia Actualizado!",
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