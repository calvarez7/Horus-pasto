<template>
    <div>
        <v-dialog v-model="preload" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Estamos procesando su información
                    <v-progress-linear indeterminate color="white" class="mb-0">
                    </v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-layout row wrap>
            <v-flex xs12>
                <v-card>
                    <v-container grid-list-md text-xs-center>
                        <v-layout row wrap>
                            <v-flex sm6>
                                <v-autocomplete
                                    append-icon="search"
                                    label="Buscar Departamento..."
                                    :items="departamento"
                                    item-text="Nombre"
                                    item-value="codigo_Dane"
                                    hide-details
                                    v-model="filters.departamento"
                                ></v-autocomplete>
                            </v-flex>
                            <v-flex sm6>
                                <v-autocomplete
                                    append-icon="search"
                                    label="Buscar Municipio..."
                                    :items="municipios"
                                    item-value="codigo_Dane"
                                    item-text="Nombre"
                                    hide-details
                                    v-model="filters.municio"
                                ></v-autocomplete>
                            </v-flex>
                            <v-flex sm6>
                                <v-autocomplete
                                    append-icon="search"
                                    label="IPS"
                                    :items="ips"
                                    item-text="NombreIPS"
                                    item-value="id"
                                    hide-details
                                    v-model="filters.ips"
                                ></v-autocomplete>
                            </v-flex>
                            <v-flex sm6>
                                <v-autocomplete
                                    append-icon="search"
                                    label="Región"
                                    :items="sedePrestadores"
                                    item-text="Nombre"
                                    hide-details
                                ></v-autocomplete>
                            </v-flex>
                            <v-flex xs12>
                                <v-autocomplete v-model="filters.cups" :items="cups" item-text="Nombre" item-value="id"
                                                label="CUPS" multiple>
                                    <template v-slot:selection="data">
                                        <v-chip
                                            :selected="data.selected"
                                            close
                                            class="chip--select-multi"
                                            @input="remove(data.item)"
                                        >
                                            {{ data.item.Codigo }}
                                        </v-chip>
                                    </template>

                                </v-autocomplete>
                            </v-flex>
                            <v-flex sm1 offset-sm11>
                                <v-btn color="success" @click="consultarInformacion">Buscar</v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card>
            </v-flex>
            <v-flex xs12 pt-3>
                <v-card>
                    <v-tabs centered>
                        <v-tabs-slider color="primary"></v-tabs-slider>
                        <v-tab href="#tab-1">
                            Servicio
                        </v-tab>
                        <v-tab href="#tab-2">
                            Auditoria
                        </v-tab>
                        <v-tab-item value="tab-1">
                    <v-container pa-1>
                        <v-card-title>
                            <v-spacer></v-spacer>
                            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                            </v-text-field>
                        </v-card-title>
                        <v-data-table
                            :headers="headers"
                            :items="tarifas"
                            :search="search"
                        >
                            <template v-slot:items="props">
                                <td>{{ props.index+1 }}</td>
                                <td class="text-xs-center">{{ props.item.Codigo }}</td>
                                <td class="text-xs-center">{{ props.item.Nombre }}</td>
                                <td class="text-xs-center">{{ props.item.prestador }}</td>

                                <td class="text-xs-center">{{ props.item.Manual }}</td>
                                <td class="text-xs-center">{{ props.item.Tarifa }}</td>
                                <td class="text-xs-center">{{ props.item.Ambito }}</td>
                                <td v-if=" props.item.oportunidad > 3" class="text-xs-center" style="color:red">{{ props.item.oportunidad }}</td>
                                <td v-else class="text-xs-center" style="color:green">{{ props.item.oportunidad }}</td>


                            </template>
                            <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                                Your search for "{{ search }}" found no results.
                            </v-alert>
                        </v-data-table>
                    </v-container>
                        </v-tab-item>
                        <v-tab-item value="tab-2">

                            <v-container pa-1>
                                <v-card-title>
                                    <v-spacer></v-spacer>
                                    <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                                    </v-text-field>
                                </v-card-title>
                                <v-data-table
                                    :headers="headers"
                                    :items="tarifasAuditoria"
                                    :search="search2"
                                >
                                    <template v-slot:items="props">
                                        <td>{{ props.index+1 }}</td>
                                        <td class="text-xs-center">{{ props.item.Codigo }}</td>
                                        <td class="text-xs-center">{{ props.item.Nombre }}</td>
                                        <td class="text-xs-center">{{ props.item.prestador }}</td>

                                        <td class="text-xs-center">{{ props.item.Manual }}</td>
                                        <td class="text-xs-center">{{ props.item.Tarifa }}</td>
                                        <td class="text-xs-center">{{ props.item.Ambito }}</td>
                                        <td v-if=" props.item.oportunidad > 3" class="text-xs-center" style="color:red">{{ props.item.oportunidad }}</td>
                                        <td v-else class="text-xs-center" style="color:green">{{ props.item.oportunidad }}</td>


                                    </template>
                                    <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                                        Your search for "{{ search2 }}" found no results.
                                    </v-alert>
                                </v-data-table>
                            </v-container>

                        </v-tab-item>
                    </v-tabs>
                </v-card>
            </v-flex>
        </v-layout>
    </div>
</template>
<script>
    export default {
        data: () => {
            return {
                preload:false,
                search:"",
                search2:"",
                idPrestador: null,
                departamento: [],
                municipios: [],
                prestadores: [],
                sedePrestadores: [],
                ips:[],
                cups:[],
                filters:{
                    ips:null,
                    departamento:null,
                    municio:null,
                    cups:[]
                },
                headers:[
                    {text: 'id',
                    align: 'left',
                    value: 'id',
                   },{
                        text: 'Codigo CUP',
                        align: 'center',
                        value: 'Codigo',
                    },{
                    text: 'Descripción CUP',
                        align: 'center',
                        value: 'Nombre',
                    },{
                        text: 'Prestador',
                        align: 'center',
                        value: 'prestador',
                    },{
                        text: 'Manual',
                        align: 'center',
                        value: 'Manual',
                        },
                    {text: 'Tarifa',
                        align: 'center',
                        value: 'Tarifa',
                        },
                    {text: 'Ambito',
                        align: 'center',
                        value: 'Ambito',
                        },
                    {text: 'Oportunidad',
                        value: 'oportunidad',
                        align: 'center',
                        sortable:true},
                ],
                tarifas:[],
                tarifasAuditoria:[]

            }
        },
        methods:{
            fetchDepartamentos() {
                axios.get('/api/departamento/all')
                    .then((res) => {
                        console.log(res.data);
                        this.departamento = res.data
                    })
                    .catch((err) => console.log(err));
            },
            fetchMunicipios() {
                axios.get('/api/municipio/all')
                    .then(res => {this.municipios = res.data
                    console.log(res.data)})
            },
            getIPS(){
                axios.get('/api/prestador/ips/pacientes/3')
                    .then((res) => {
                        this.ips = res.data;
                    })
                    .catch((err) => console.log(err));
            },
            getOportunidades(){
                this.preload = true;
                axios.post('/api/red/getOportunidad',this.filters).then(res => {
                    this.tarifas = res.data;
                    this.preload = false;
                }).catch((err) => {console.log(err);this.preload = false;});
            },
            getOportunidadesAuditoria(){
                this.preload = true;
                axios.post('/api/red/getOportunidadAuditoria',this.filters).then(res => {
                    this.tarifasAuditoria = res.data;
                    this.preload = false;
                }).catch((err) => {console.log(err);this.preload = false;});
            },
            getCups(){
                this.preload = true;
                axios.get('/api/cups/getCupsEntidades/3').then(res => {
                    this.cups = res.data;
                    this.preload = false
                }).catch((err) => {console.log(err);this.preload = false;});
            },
            remove (item) {
                const index = this.filters.cups.indexOf(item.id);
                if (index >= 0) this.filters.cups.splice(index, 1)
            },
            consultarInformacion(){
                this.getOportunidades();
                this.getOportunidadesAuditoria();
            }
        },
        mounted() {
            this.getCups();
            this.fetchDepartamentos();
            this.fetchMunicipios();
            this.getIPS();
            this.getOportunidades();
        },
    }

</script>

