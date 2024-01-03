<template>
    <v-card min-height="100%">
        <v-container pa-1>
            <v-container pt-3 pb-1>
                <v-layout row>
                    <v-dialog v-model="dialog" persistent max-width="600px">
                    <v-card>
                        <v-card-title class="headline success" style="color:white">
                        <span v-if="save" class="headline">Crear Familia</span>
                        <span v-else class="headline">Editar Familia</span>
                        </v-card-title>
                        <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                            <v-flex xs12 sm6 md6>
                                <v-autocomplete
                                    label="Tipo Familia"
                                    :items="tipoFamilia"
                                    item-text="Nombre"
                                    item-value="id"
                                    v-model="data.Tipofamilia_id"
                                ></v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-text-field v-model="data.Nombre" label="Nombre"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-text-field v-model="data.Descripcion" label="Descripción"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-text-field v-model="data.Responsable" label="Responsable"></v-text-field>
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
                        <span class="headline layout justify-center">Familias</span>
                    </v-flex>
                    <v-flex xs4>
                        <v-btn v-if="can('familia.create')" round @click="createTipoFamilia()" color="primary" dark><v-icon left>exposure_plus_1</v-icon>Nuevo Familia</v-btn>
                    </v-flex>
                    <v-spacer></v-spacer>
                    <v-flex
                    sm5 
                    xs5>
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
                :items="Familias"
                :search="search"
                >
                <template v-slot:items="props">
                    <td>{{ props.item.id }}</td>
                    <td class="text-xs-right">{{ props.item.Nombre }}</td>
                    <td class="text-xs-right">{{ props.item.Descripcion }}</td>
                    <td class="text-xs-right">{{ props.item.Responsable }} </td>
                    <td class="text-xs-right">{{ props.item.Nombretipofamilia }} </td>
                    <td class="text-xs-right">
                        <v-btn v-if="can('familia.edit')" fab outline color="warning" small @click="editTipoFamilia(props.item)"><v-icon>edit</v-icon></v-btn>
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
        name:'TableFamilia',
        data() {
            return {
                Familias: [],
                tipoFamilia: [],
                unidadfuncional: [],
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
                        value: 'Descripcion'
                    },
                    {
                        text: 'Responsable',
                        align: 'right',
                        sortable: false,
                        value: 'Responsable'
                    },
                    {
                        text: 'Tipo',
                        align: 'right',
                        sortable: false,
                        value: 'Nombretipofamilia'
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
                nombres: [
                    'Laboratorios',
                    'Urgencias'
                ],
                data:{
                    Nombre: '',
                    Descripcion: '',
                    Responsable: '',
                    Tipofamilia_id: '',
                },
            }
        },
        computed:{
            ...mapGetters(['can'])
        },
        mounted () {
            this.fetchFamilia()
            this.fetchTipoFamilia();
        },
        methods: {
            fetchFamilia() {
                axios.get('/api/familia/all')
                    .then(res => this.Familias = res.data);
            },
            fetchTipoFamilia() {
                axios.get('/api/tipofamilia/all')
                    .then(res => this.tipoFamilia = res.data);
            },
            createTipoFamilia(){
                this.fetchTipoFamilia();
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
                    Responsable: '',
                    Tipofamilia_id: '',
                }
            },
            saveTipoFamilia(){
                axios.post(`/api/familia/create`,this.data)
                .then(res => {
                    this.emptyData();
                    this.dialog = false;
                    this.fetchFamilia();
                    swal({
                        title: "¡Familia Creado!",
                        text: " ",
                        timer: 2000,
                        icon: "success",
                        buttons: false
                    });
                })
                .catch(err => this.showError(err.response.data))
            },
            editTipoFamilia(Familia){
                this.data = Familia;
                this.fetchTipoFamilia();
                this.save = false;
                this.dialog = true;
            },
            updateTipoFamilia() {
                axios.put(`/api/Familia/edit/${this.data.id}`,this.data)
                    .then(res => {   
                        this.emptyData();
                        this.dialog = false;
                        this.fetchFamilia();   
                        swal({
                            title: "¡Familia Actualizado!",
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