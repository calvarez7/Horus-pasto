<template>
    <div>
        <v-layout row wrap>
            <v-flex xs12>
                <v-card>
                    <v-container>
                        <v-layout row wrap>
                            <v-flex xs5>
                                <v-text-field label="Numero factura" prepend-icon="mdi-barcode" v-model="datoskardex.codigoFactura"></v-text-field>
                            </v-flex>
                            <v-flex xs7>
                            </v-flex>
                            <v-flex xs12>
                                <v-divider class="mb-4"></v-divider>
                            </v-flex>
                            <v-flex xs4>
                                <v-menu ref="menu1" v-model="menu1" :close-on-content-click="false" :nudge-right="40"
                                    lazy transition="scale-transition" offset-y full-width min-width="290px">
                                    <template v-slot:activator="{ on }">
                                        <v-text-field v-model="datoskardex.startDate" label="Fecha inicio"
                                            prepend-icon="event" readonly v-on="on"></v-text-field>
                                    </template>
                                    <v-date-picker color="primary" locale="es" :max="datoskardex.finishDate" no-title
                                        v-model="datoskardex.startDate" @input="menu1 = false" />
                                </v-menu>
                            </v-flex>
                            <v-flex xs4 pl-2>
                                <v-menu ref="menu2" v-model="menu2" :close-on-content-click="false" :nudge-right="40"
                                    lazy transition="scale-transition" offset-y full-width min-width="290px">
                                    <template v-slot:activator="{ on }">
                                        <v-text-field v-model="datoskardex.finishDate" label="Fecha final"
                                            prepend-icon="event" readonly v-on="on"></v-text-field>
                                    </template>
                                    <v-date-picker color="primary" locale="es" :min="datoskardex.startDate" :max="today"
                                        no-title v-model="datoskardex.finishDate" @input="menu2 = false" />
                                </v-menu>
                            </v-flex>
                            <v-flex xs6>
                                <vAutocomplete :items="listaBodegas" v-model="datoskardex.bodega_id" label="Bodega"
                                    item-value="id" prepend-icon="mdi-city" item-text="Nombre" />
                            </v-flex>
                            <v-flex xs6>
                                <vAutocomplete :items="codesumis" v-model="datoskardex.codigos" label="Molecula"
                                    item-value="Codigo" prepend-icon="mdi-pill" item-text="Nombre" />
                            </v-flex>
                            <v-btn color="success" @click="kardex()">Consultar</v-btn>
                        </v-layout>
                    </v-container>
                </v-card>
            </v-flex>
            <v-flex xs12 mt-2>
                <v-card>
                    <v-container fluid>
                        <v-layout row wrap>
                            <v-flex xs12>
                                <v-card>
                                    <v-card-title>
                                        {{title}}
                                        <v-spacer></v-spacer>
                                        <v-text-field v-model="search" append-icon="search" label="Search" single-line
                                            hide-details></v-text-field>
                                    </v-card-title>
                                    <v-data-table :headers="headers" :items="listKardex" :search="search" hide-actions>
                                        <template v-slot:items="props">
                                            <td>{{ props.item.Transación }}</td>
                                            <td>{{ props.item.Numfactura }}</td>
                                            <td>{{ props.item.orden }}</td>
                                            <td>{{ props.item.Creacion_del_movimiento }}</td>
                                            <td>{{ props.item.CantidadtotalFactura }}</td>
                                            <td>{{ props.item.Numlote }}</td>
                                            <td>{{ props.item.medicamento }}</td>
                                            <td>{{ props.item.responsable }}</td>
                                            <td v-show="props.item.Transación == 'Traslado'">{{ props.item.Bodega_origen }}</td>
                                            <td v-show="props.item.Transación == 'Traslado'">{{ props.item.Bodega_destino }}</td>
                                        </template>
                                        <template v-slot:no-results>
                                            <v-alert :value="true" color="error" icon="warning">
                                                Your search for "{{ search }}" found no results.
                                            </v-alert>
                                        </template>
                                    </v-data-table>
                                </v-card>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card>
            </v-flex>
        </v-layout>
    </div>
</template>

<script>
    import moment from 'moment';
    moment.locale('es');
    export default {
        name: 'vistaKardex',
        data() {
            return {
                search: '',
        headers: [
                { text: 'Tipo Transación', value: 'Transación', sortable:true},
                { text: 'Número Factura', value: 'Numfactura', sortable:true},
                { text: 'Número Orden', value: 'orden', sortable:true},
                { text: 'F_Movimineto', value: 'Creacion_del_movimiento', sortable:true},
                { text: 'Total Movimiento', value: 'CantidadtotalFactura', sortable:false },
                { text: '# Lote', value: 'Numlote', sortable:false },
                { text: 'Medicamento', value: 'nombre', sortable:false},
                { text: 'Usuario', value: 'resposable', sortable:false },
                { text: 'Bodega origen', value: 'Bodega_origen', sortable:false},
                { text: 'Bodega destino', value: 'Bodega_destino', sortable:false},
                ],
                datoskardex: {
                    codigos: '',
                    bodega_id: null,
                    startDate: moment().format('YYYY-MM-DD'),
                    finishDate: moment().format('YYYY-MM-DD'),
                    codigoFactura:"",
                },
                headerArticulos: [{
                        text: '#',
                        sortable: false
                    },
                    {
                        text: 'CUM',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Medicamento',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Titular',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: 'Cantidad',
                        sortable: false,
                        align: 'center'
                    },
                    {
                        text: '',
                        sortable: false,
                        align: 'center'
                    },
                ],
                today: moment().format('YYYY-MM-DD'),
                menu1: false,
                menu2: false,
                listaBodegas: [],
                codesumis: [],
                listKardex: [],
                title: ""

            }
        },
        mounted() {
            this.fetchBodegas();
            this.fetchCodesumis();
        },
        methods: {
            fetchCodesumis() {
                axios.get('/api/codesumi/all')
                    .then(res => {
                        this.codesumis = res.data;
                    })
            },
            fetchBodegas() {
                axios.get('/api/bodega/getBodegaByRole')
                    .then(res => {
                        this.listaBodegas = res.data
                    })
            },
            kardex() {
                axios.post('/api/codesumi/kardex', this.datoskardex)
                    .then(res => {
                        if(res.data.kardex.length > 0){
                            this.title = res.data.kardex[0].Bodega;
                        }
                        this.listKardex = res.data.kardex;
                            res.data.inventario.forEach(s => {
                                this.listKardex.push(s);
                            })
                    })
            },
        }
    }

</script>

<style lang="scss" scoped>
    .buttom-nav-anexo {
        width: 30% !important;
        margin: 0 35% !important;
        border-radius: 40px;
        /* border-radius: 25px 25px 0 0; */
    }

</style>
