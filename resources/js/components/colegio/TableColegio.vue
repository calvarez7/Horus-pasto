<template>
    <v-card min-height="100%">
        <v-container pa-1>
            <v-container pt-3 pb-1>
                <v-layout row>
                    <v-dialog v-model="dialog" persistent max-width="600px">
                    <v-card>
                        <v-card-title class="headline success" style="color:white">
                        <span v-if="save" class="headline">Crear Colegio</span>
                        <span v-else class="headline">Editar Colegio</span>
                        </v-card-title>
                        <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                            <v-flex xs12 sm6 md6>
                                <v-autocomplete
                                    label="Municipios"
                                    :items="Municipios"
                                    item-text="Nombre"
                                    item-value="id"
                                    v-model="data.Municipio_id"
                                ></v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-text-field v-model="data.Nombre" label="Nombre"></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md6>
                                <v-text-field v-model="data.Codigodanecolegio" label="Codigó Dane"></v-text-field>
                            </v-flex>
                            </v-layout>
                        </v-container>
                        </v-card-text>
                        <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" flat @click="dialog = false">Cancelar</v-btn>
                        <v-btn v-if="save" color="blue darken-1" flat @click="saveMunicipios()">Guardar</v-btn>
                        <v-btn v-else color="blue darken-1" flat @click="updateMunicipios()">Actualizar</v-btn>
                        </v-card-actions>
                    </v-card>
                    </v-dialog>
                </v-layout>
            </v-container>
            <v-card-title>
                    <v-flex xs12>
                        <span class="headline layout justify-center">Colegios</span>
                    </v-flex>
                    <v-flex xs4>
                        <v-btn v-if="can('colegio.create')" round @click="createColegio()" color="primary" dark><v-icon left>exposure_plus_1</v-icon>Nuevo Colegio</v-btn>
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
                :items="Colegios"
                :search="search"
                >
                <template v-slot:items="props">
                    <td>{{ props.item.id }}</td>
                    <td class="text-xs-right">{{ props.item.NomColegio }}</td>
                    <td class="text-xs-right">{{ props.item.CodeDane }}</td>
                    <td class="text-xs-right">{{ props.item.NomMunicipio }} </td>
                    <td class="text-xs-right">
                        <v-btn v-if="can('colegio.edit')" fab outline color="warning" small @click="editMunicipios(props.item)"><v-icon>edit</v-icon></v-btn>
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
        name:'TableColegio',
        data() {
            return {
                Colegios: [],
                Municipios: [],
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
                        value: 'NomColegio'
                    },
                    {
                        text: 'Código Dane',
                        align: 'right',
                        sortable: false,
                        value: 'CodeDane'
                    },
                    {
                        text: 'Municipio',
                        align: 'right',
                        sortable: false,
                        value: 'NomMunicipio'
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
                    Codigodanecolegio: '',
                    Municipio_id: '',
                },
            }
        },
        computed:{
            ...mapGetters(['can'])
        },
        mounted () {
            this.fetchColegio()
            this.fetchMunicipios();
        },
        methods: {
            fetchColegio() {
                axios.get('/api/colegio/all')
                    .then(res => this.Colegios = res.data);
            },
            fetchMunicipios() {
                axios.get('/api/municipio/all')
                    .then(res => this.Municipios = res.data);
            },
            createColegio(){
                this.fetchMunicipios();
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
                    Codigodanecolegio: '',
                    Municipio_id: '',
                }
            },
            saveMunicipios(){
                axios.post(`/api/colegio/create`,this.data)
                .then(res => {
                    this.emptyData();
                    this.dialog = false;
                    this.fetchColegio();
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
            editMunicipios(Familia){
                this.data = Familia;
                this.fetchMunicipios();
                this.save = false;
                this.dialog = true;
            },
            updateMunicipios() {
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