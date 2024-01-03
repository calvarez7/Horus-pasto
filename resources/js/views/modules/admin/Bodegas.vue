<template>
    <v-layout row wrap>
<!--        <BodegasTipos />-->
        <v-flex xs12 px-1>
            <v-card>
                <v-container pa-1>
                        <v-container pt-3 pb-1>
                            <v-layout row>
                                <v-dialog v-model="dialog" persistent max-width="600px">
                                <v-card>
                                    <v-card-title class="headline success" style="color:white">
                                    <span v-if="save" class="headline">Crear Bodega</span>
                                    <span v-else class="headline">Editar Bodega</span>
                                    </v-card-title>
                                    <v-card-text>
                                    <v-container grid-list-md>
                                        <v-layout wrap>
                                        <v-flex xs12 sm6 md6>
                                            <v-autocomplete label="Tipo*" :items="tipos" item-value="id" item-text="Nombre" v-model="data.Tipobodega_id" required></v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-autocomplete label="Municipio*" :items="municipios" item-value="id" item-text="Nombre" v-model="data.Municipio_id" required></v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-text-field label="Nombre*" v-model="data.Nombre" required></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-text-field label="Telefono*" v-model="data.Telefono" required></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-text-field label="Direccion*" v-model="data.Direccion" required></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-text-field label="Horainicio*" v-model="data.Horainicio" required></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md6>
                                            <v-text-field label="Horafin*" v-model="data.Horafin" required></v-text-field>
                                        </v-flex>
                                            <v-flex xs12 sm12 md12>
                                                <v-label>Variables Min/Max</v-label>
                                                <v-divider></v-divider>
                                            </v-flex>
                                            <v-flex xs12 sm6 md6>
                                                <v-text-field label="Stock de seguridad (dias)" type="number" v-model="data.stock_seguridad" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm6 md6>
                                                <v-text-field label="Tiempo Reposicion (dias)" type="number" v-model="data.tiempo_reposicion" required></v-text-field>
                                            </v-flex>
                                            <v-flex xs12 sm6 md6>
                                                <v-text-field label="Cobertura (dias)" type="number" v-model="data.cobertura" required></v-text-field>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                    </v-card-text>
                                    <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="blue darken-1" flat @click="dialog = false">Cancelar</v-btn>
                                    <v-btn v-if="save" color="blue darken-1" flat @click="saveBodega()">Guardar</v-btn>
                                    <v-btn v-else color="blue darken-1" flat @click="updateBodega()">Actualizar</v-btn>
                                    </v-card-actions>
                                </v-card>
                                </v-dialog>
                            </v-layout>
                        </v-container>
                    <v-card-title>
                    <v-btn round v-if="can('bodega.create')" @click="createBodega()" color="primary" dark ><v-icon left>person_add</v-icon>Crear bodega</v-btn>
                    <v-spacer></v-spacer>
                    <v-flex sm5 xs12>
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
                        :items="bodegas"
                        :search="search"
                        >
                        <template v-slot:items="props">
                            <td>{{ props.item.id }}</td>
                            <td class="text-xs-right">{{ props.item.TipobodegaNombre }}</td>
                            <td class="text-xs-right">{{ props.item.Nombre }}</td>
                            <td class="text-xs-right">{{ props.item.Telefono }}</td>
                            <td class="text-xs-right">{{ props.item.NombreMunicipio }}</td>
                            <td class="text-xs-right">{{ props.item.Direccion }}</td>
                            <td class="text-xs-right">
                                <v-btn v-if="can('bodega.edit')" fab outline color="warning" small @click="editBodega(props.item)"><v-icon>edit</v-icon></v-btn>
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
    import { mapGetters } from 'vuex';
    import BodegasTipos from './BodegasTipos.vue'
    export default {
        name:'Bodegas',
        components:{
            BodegasTipos
        },
        data() {
            return {
                bodegas: [],
                municipios: [],
                tipos: [],
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
                        value: 'Tipo'
                    },
                    {
                        text: 'Nombre',
                        align: 'right',
                        sortable: false,
                        value: 'Nombre'
                    },
                    {
                        text: 'Teléfono',
                        align: 'right',
                        sortable: false,
                        value: 'Telefono'
                    },
                    {
                        text: 'Municipio',
                        align: 'right',
                        sortable: false,
                        value: 'NombreMunicipio'
                    },
                    {
                        text: 'Direccion',
                        align: 'right',
                        sortable: false,
                        value: 'Direccion'
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
                    Municipio_id: null,
                    Tipobodega_id: null,
                    Nombre: '',
                    Telefono: '',
                    Direccion: '',
                    Horainicio: '',
                    Horafin: '',
                    stock_seguridad: '',
                    tiempo_reposicion: '',
                    cobertura: '',
                },
            }
        },
        computed:{
            ...mapGetters(['can'])
        },
        mounted () {
            this.fetchBodegas();
            this.fetchMunicipios();
            this.fetchTipos();
        },
        methods: {
            fetchBodegas() {
                axios.get('/api/bodega/all')
                    .then(res => this.bodegas = res.data)
            },
            fetchMunicipios() {
                axios.get('/api/municipio/all')
                    .then(res => this.municipios = res.data)
            },
            fetchTipos() {
                axios.get('/api/tipobodega/all')
                    .then(res => this.tipos = res.data)
            },
            createBodega(){
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
                    Municipio_id: null,
                    Tipobodega_id: null,
                    Nombre: '',
                    Telefono: '',
                    Direccion: '',
                    Horainicio: '',
                    Horafin: '',
                    stock_seguridad: '',
                    tiempo_reposicion: '',
                    cobertura: '',
                }
            },
            saveBodega(){
                axios.post('/api/bodega/create',this.data)
                .then(res => {
                    this.emptyData();
                    this.dialog = false;
                    this.fetchBodegas();
                    swal({
                        title: "¡bodega Creado!",
                        text: " ",
                        type: "success",
                        timer: 2000,
                        icon: "success",
                        buttons: false
                    });
                })
                .catch(err => this.showError(err.response.data))
            },
            editBodega(bodega){
                this.data = {
                    id: bodega.id,
                    Municipio_id: bodega.Municipio_id,
                    Tipobodega_id: bodega.Tipobodega_id,
                    Nombre: bodega.Nombre,
                    Telefono: bodega.Telefono,
                    Direccion: bodega.Direccion,
                    Horainicio: bodega.Horainicio,
                    Horafin: bodega.Horafin,
                    stock_seguridad: bodega.stock_seguridad,
                    tiempo_reposicion: bodega.tiempo_reposicion,
                    cobertura: bodega.cobertura,
                };
                this.save = false;
                this.dialog = true;
            },
            updateBodega() {
                axios.put(`/api/bodega/edit/${this.data.id}`,this.data)
                    .then(res => {
                        this.emptyData();
                        this.dialog = false;
                        this.fetchBodegas();
                        swal({
                            title: "¡bodega Actualizado!",
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
