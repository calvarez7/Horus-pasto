<template>
    <v-card min-height="100%">
        <v-container pa-1>
            <v-container pt-3 pb-1>
                <v-layout row>
                    <v-dialog v-model="dialog" persistent max-width="600px">
                    <v-card>
                        <v-card-title class="headline success" style="color:white">
                        <span v-if="save" class="headline">Crear Cup Servicio</span>
                        <span v-else class="headline">Editar Cup Servicio</span>
                        </v-card-title>
                        <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm6 md6>
                                    <v-autocomplete label="Cup*" :items="cups" item-text="Nombre" item-value="id" v-model="data.Cup_id" required></v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-autocomplete label="Tipo Ingreso*" v-model="tiposervicio" :items="getTipoIngreso" required></v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-autocomplete label="Unidad funcional*" v-model="unidadfuncional" :items="getUnidadFuncional" item-text="unidadfuncional" required></v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-autocomplete label="Servicio*" v-model="data.Servicio_id" :items="getServicio" item-text="Nombre" item-value="id" required></v-autocomplete>
                                </v-flex>
                            </v-layout>
                        </v-container>
                        </v-card-text>
                        <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" flat @click="dialog = false">Cancelar</v-btn>
                        <v-btn v-if="save" color="blue darken-1" flat @click="saveCupServicio()">Guardar</v-btn>
                        <v-btn v-else color="blue darken-1" flat @click="updateCupServicio()">Actualizar</v-btn>
                        </v-card-actions>
                    </v-card>
                    </v-dialog>
                </v-layout>
            </v-container>
            <v-card-title>
                <v-flex xs12>
                    <span class="headline layout justify-center">Cups Servicios</span>
                </v-flex>
                    <v-flex xs4>                        
                        <v-btn round @click="createCupServicio()" color="primary" dark><v-icon left>exposure_plus_1</v-icon>Nuevo Cup Servicio</v-btn>
                    </v-flex>
                    <v-spacer></v-spacer>
                    <v-flex
                        xs6
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
                :items="cupServicios"
                :search="search"
                >
                <template v-slot:items="props">
                    <td>{{ props.item.id }}</td>
                    <td class="text-xs-right">{{ props.item.cup }}</td>
                    <td class="text-xs-right">{{ props.item.tiposervicio }}</td>
                    <td class="text-xs-right">{{ props.item.unidadfuncional }}</td>
                    <td class="text-xs-right">{{ props.item.servicio }}</td>
                    <td class="text-xs-right">
                        <v-btn fab outline color="warning" small @click="editCupServicio(props.item)"><v-icon>edit</v-icon></v-btn>
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
    export default {
        name:'TableCupServicio',
        data() {
            return {
                cupServicios: [],
                cups: [],
                search: '',
                headers: [
                    {
                        text: 'id',
                        align: 'left',
                        value: 'id'
                    },
                    {
                        text: 'Cup',
                        align: 'right',
                        sortable: false,
                        value: 'cup'
                    },
                    {
                        text: 'Tipo servicio',
                        align: 'right',
                        sortable: false,
                        value: 'tiposervicio'
                    },
                    {
                        text: 'Unidad funcional',
                        align: 'right',
                        sortable: false,
                        value: 'unidadfuncional'
                    },
                    {
                        text: 'Servicio',
                        align: 'right',
                        sortable: false,
                        value: 'servicio'
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
                servicios: [],
                tiposervicio: '',
                unidadfuncional: '',
                data:{
                    Cup_id: '',
                    Servicio_id: ''
                },
            }
        },
        computed: {
            getTipoIngreso(){
                return this.servicios.map((servicio) => servicio.tiposervicio)
            },
            getUnidadFuncional(){
                return this.servicios.filter((servicio) => servicio.tiposervicio == this.tiposervicio )
            },
            getServicio(){
                return this.servicios.filter((servicio) => (servicio.tiposervicio == this.tiposervicio && servicio.unidadfuncional == this.unidadfuncional ))
            }
        },
        mounted () {
            this.fetchCupServicios();
            this.fetchServicio();
            this.fetchCups();
        },
        methods: {
            fetchCups() {
                axios.get('/api/cups/all')
                    .then(res => this.cups = res.data.map((cup) => { return { id: cup.id, Nombre: `${cup.Codigo} - ${cup.Nombre}` }}));
            },
            fetchServicio(){
                axios.get('/api/servicio/all')
                    .then(res => this.servicios = res.data);
            },
            fetchCupServicios() {
                axios.get('/api/cupservicio/all')
                    .then(res => this.cupServicios = res.data);
            },
            createCupServicio(){
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
                    Cup_id: '',
                    Servicio_id: ''
                }
                this.tiposervicio = '';
                this.unidadfuncional = ''
            },
            saveCupServicio(){
                axios.post('/api/cupservicio/create',this.data)
                .then(res => {
                    this.emptyData();
                    this.dialog = false;
                    this.fetchCupServicios();
                    swal({
                        title: "¡Cup Servicio Creado!",
                        text: " ",
                        timer: 2000,
                        icon: "success",
                        buttons: false
                    });
                })
                .catch(err => this.showError(err.response.data))
            },
            editCupServicio(CupServicio){
                this.data.id = CupServicio.id;
                this.data.Cup_id = CupServicio.Cup_id;
                this.data.Servicio_id = CupServicio.Servicio_id;
                this.tiposervicio = CupServicio.tiposervicio;
                this.unidadfuncional = CupServicio.unidadfuncional;
                this.save = false;
                this.dialog = true;
            },
            updateCupServicio() {
                axios.put(`/api/cupservicio/edit/${this.data.id}`,this.data)
                    .then(res => {   
                        this.emptyData();
                        this.dialog = false;
                        this.fetchCupServicios();   
                        swal({
                            title: "¡Cup Servicio Actualizado!",
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