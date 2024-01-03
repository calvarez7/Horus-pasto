<template>
    <v-layout>
        <v-flex xs12 sm12>
            <v-card>
                <v-card-title primary-title>
                    <div>
                        <h3 class="headline mb-0">Conteo de Inventario</h3>
                    </div>
                </v-card-title>
                <v-card-actions>
                    <v-container fluid grid-list-xl>
                        <v-layout wrap align-center>
                            <v-flex xs12 sm6 d-flex>
                                <v-autocomplete :items="bodegas" item-text="Nombre" append-icon="search" item-value="id"
                                    v-model="data.Bodega_id" label="Selecciona la bodega a contar"
                                    @input="inventarioselect"></v-autocomplete>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-actions>
                <v-container fluid grid-list-xl>
                    <v-card-title>

                        <v-spacer></v-spacer>
                        <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                        </v-text-field>
                    </v-card-title>
                    <v-data-table :headers="headers" :items="invetario" :search="search">
                        <template v-slot:items="props">
                            <td>{{ props.item.Codigo }}</td>
                            <td>{{ props.item.Producto }}</td>
                            <td>{{ props.item.Titular }}</td>
                            <td>{{ props.item.Numlote }}</td>
                            <td>{{ props.item.Fvence }}</td>
                            <td>{{ props.item.Cantidadisponible }}</td>
                            <td><v-text-field v-model="data.Conteo1" label="conteo 1"></v-text-field></td>
                            <td><v-text-field v-model="data.Conteo2" label="conteo 2"></v-text-field></td>
                            <td><v-text-field v-model="data.Conteo3" label="conteo 3"></v-text-field></td>
                            <td><v-text-field v-model="data.Conteo4" label="conteo 4"></v-text-field></td>
                            <td><v-text-field v-model="data.Conteo5" label="conteo 5"></v-text-field></td>
                        </template>
                        <template v-slot:no-results>
                            <v-alert :value="true" color="error" icon="warning">
                                Your search for "{{ search }}" found no results.
                            </v-alert>
                        </template>
                    </v-data-table>
                </v-container>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        data() {
            return {
                bodegas: [],
                invetario: [],
                search: '',
                data: {
                    Bodega_id: '',
                    saldo: '',
                    Lote: '',
                    Fvencimiento: '',
                    Conteo1: '',
                    Conteo2: '',
                    Calculado: '',
                    Conteo3: '',
                    Conteo4: ''
                },
                invetario: [],

                headers: [{
                        text: 'Codigo',
                        align: 'left',
                        sortable: false,
                        value: 'Codigo'
                    },
                    {
                        text: 'Producto',
                        value: 'Producto'
                    },
                    {
                        text: 'Titular',
                        value: 'Titular'
                    },
                    {
                        text: 'Numlote',
                        value: 'Numlote'
                    },
                    {
                        text: 'Fvence',
                        value: 'Fvence'
                    },
                    {
                        text: 'Cantidadisponible',
                        value: 'Cantidadisponible'
                    },
                    {
                        text: 'Conteo 1',
                        value: 'Conteo1'
                    },
                    {
                        text: 'Conteo 2',
                        value: 'Conteo2'
                    },
                    {
                        text: 'Conteo 3',
                        value: 'Conteo3'
                    },
                    {
                        text: 'Conteo 4',
                        value: 'Conteo4'

                    },
                    {
                        text: 'Conteo 5',
                        value: 'Conteo5'
                    }
                ]
            }
        },
        mounted() {
            this.fetchBodegas()
        },
        methods: {
            inventarioselect() {
                this.data.Bodega_id
                axios.post('/api/bodega/loteporBodega', {
                        Bodega_id: this.data.Bodega_id
                    }).then(res => {
                        this.invetario = res.data
                        console.log('inventario', res.data)
                    })
                    .catch(error => console.log(error.data));
            },
            fetchBodegas() {
                axios.get('/api/bodega/all')
                    .then(res => this.bodegas = res.data)
            }
        }

    }

</script>

<style>

</style>
