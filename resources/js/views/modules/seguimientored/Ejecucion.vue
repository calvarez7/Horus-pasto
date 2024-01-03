<template>
    <div>
        <v-layout row wrap>
            <v-flex xs12>
                <v-card>
                    <v-container pa-1>
                        <v-card-title>
                            <v-flex sm5>
                                <v-autocomplete
                                    append-icon="search"
                                    label="Buscar Municipio..."
                                    :items="municipios"
                                    item-text="Nombre"
                                    item-value="id"
                                    hide-details
                                ></v-autocomplete>
                            </v-flex>
                            <v-flex sm4>
                            </v-flex>
                            <v-flex sm3>
                                <v-text-field
                                    label="Valor Ejecutado" type="number"
                                ></v-text-field>
                            </v-flex>
                            <v-flex sm5>
                                <v-autocomplete
                                    append-icon="search"
                                    label="Buscar prestador..."
                                    :items="prestadores"
                                    item-text="Nombre"
                                    item-value="id"
                                    hide-details
                                ></v-autocomplete>
                            </v-flex>
                            <v-flex sm4>
                            </v-flex>
                            <v-flex sm3>
                                <v-text-field
                                    label="Metas de Salud"
                                 type="number"></v-text-field>
                            </v-flex>

                            <v-flex sm1>
                                <v-btn color="success">Buscar</v-btn>
                            </v-flex>
                        </v-card-title>
                    </v-container>
                </v-card>
            </v-flex>
            <v-flex xs12 pt-3>
                <v-card>
                    <v-container pa-1>
                        <v-card-title>
                            <v-spacer></v-spacer>
                            <v-flex xs2>
                                <v-menu ref="inicialdate" v-model="menu2" :close-on-content-click="false"
                                        :nudge-right="40" :return-value.sync="inicialdate" lazy
                                        transition="scale-transition" offset-y full-width min-width="290px">
                                    <template v-slot:activator="{ on }">
                                        <v-text-field v-model="inicialdate" label="Fecha inicial"
                                                      prepend-icon="event" readonly v-on="on"></v-text-field>
                                    </template>
                                    <v-date-picker v-model="inicialdate" color="primary" locale="es" no-title
                                                   scrollable>
                                        <v-spacer></v-spacer>
                                        <v-btn flat color="primary" @click="menu2 = false">Cancelar</v-btn>
                                        <v-btn flat color="primary" @click="saveInicialDate()">OK</v-btn>
                                    </v-date-picker>
                                </v-menu>
                            </v-flex>
                            <v-flex xs2>
                                <v-menu ref="finishdate" v-model="menu3" :close-on-content-click="false"
                                        :nudge-right="40" :return-value.sync="finishdate" lazy
                                        transition="scale-transition" offset-y full-width min-width="290px">
                                    <template v-slot:activator="{ on }">
                                        <v-text-field v-model="finishdate" label="Fecha final"
                                                      prepend-icon="event" readonly v-on="on" />
                                    </template>
                                    <v-date-picker v-model="finishdate" color="primary" locale="es" no-title
                                                   scrollable>
                                        <v-spacer></v-spacer>
                                        <v-btn flat color="primary" @click="menu3 = false">Cancelar</v-btn>
                                        <v-btn flat color="primary" @click="saveFinishDate()">OK</v-btn>
                                    </v-date-picker>
                                </v-menu>
                            </v-flex>
                        </v-card-title>
                        <v-data-table
                            :headers="headers"
                            :items="tarifas"
                            :search="search"
                        >
                            <template v-slot:items="props">
                                <td>{{ props.item.id }}</td>
                                <td class="text-xs-center">{{ props.item.Codigo }}</td>
                                <td class="text-xs-center">{{ props.item.Cup }}</td>
                                <td class="text-xs-center">{{ props.item.Manual }}</td>
                                <td class="text-xs-center">{{ props.item.Tarifa != 0? `${props.item.Tarifa}%` : 'pleno' }}</td>
                                <td class="text-xs-center">{{ props.item.Ambito }}</td>
                                <td class="text-xs-right">
                                    <v-edit-dialog
                                        :return-value.sync="props.item.Valor"
                                        large
                                        lazy
                                        persistent
                                        cancel-text="Cancelar"
                                        save-text="Cambiar"
                                        @save="save(props.item)"
                                    >
                                        <div>{{ props.item.Valor }}</div>
                                        <template v-slot:input>
                                            <div class="mt-3 title">Update Valor</div>
                                        </template>
                                        <template v-slot:input>
                                            <v-text-field
                                                v-model="props.item.Valor"
                                                label="Edit"
                                                single-line
                                                counter
                                                autofocus
                                            ></v-text-field>
                                        </template>
                                    </v-edit-dialog>
                                </td>
                                <td>
                                    <v-btn color="error" icon @click="disableCupTarifa(props.item)">
                                        <v-icon>clear</v-icon>
                                    </v-btn>
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
    </div>
</template>
<script>
    export default {
        data: () => {
            return {
                municipios:[],
                prestadores:[],
                prestador:[{nombre:"Antioquia",id:"05"}],
                headers:[
                    {text: 'id',
                        align: 'left',
                        value: 'id',
                    },

                    {text: 'Codigo CUP',
                        align: 'left',
                        value: 'id',
                        sortable:false},
                    {text: 'Descripción CUP',
                        align: 'left',
                        value: 'id',
                        sortable:false},
                    {text: 'Manual',
                        align: 'left',
                        value: 'id',
                        sortable:false},
                    {text: 'Tarifa',
                        align: 'left',
                        value: 'id',
                        sortable:false},
                    {text: 'Ambito',
                        align: 'left',
                        value: 'id',
                        sortable:false},
                    {text: 'Oportunidad',
                        align: 'left',
                        value: 'id',
                        sortable:false},
                ]
            }
        },
        methods:{
            fetchMunicipios() {
                axios.get('/api/municipio/all')
                    .then(res => this.municipios = res.data)
            },
            fetchPrestadores(){
                axios.get('/api/prestador/all')
                    .then((res) => {this.prestadores = res.data})
                    .catch((err) => console.log(err));
            },
        },
        mounted() {
            this.fetchMunicipios();
            this.fetchPrestadores();
        },
    }

</script>

