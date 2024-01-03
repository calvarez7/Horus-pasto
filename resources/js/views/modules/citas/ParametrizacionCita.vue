<template>
    <div>
        <v-toolbar flat color="white">
            <v-toolbar-title>Especialidad Cups</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-dialog v-model="dialog" max-width="800px">
                <template v-slot:activator="{ on }">
                    <v-btn color="primary" dark class="mb-2" v-on="on">Crear</v-btn>
                </template>
                <v-card>
                    <v-card-title>
                        <span class="headline">Nueva Especialidad Cups</span>
                    </v-card-title>

                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm12 md12>
                                    <v-autocomplete :items="allEspelidades" item-text="Nombre" item-value="id"
                                        label="Especialidad" v-model="especialidad"></v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm12 md12>
                                    <v-autocomplete :items="cups" item-text="nombre" item-value="id" label="Cups"
                                        v-model="cup" multiple></v-autocomplete>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" @click="close">Salir</v-btn>
                        <v-btn color="success" @click="save">Guardar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-toolbar>

        <v-card>
            <v-card-title>
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details>
                </v-text-field>
            </v-card-title>
            <v-data-table :headers="headers" :items="parametrizaciones" :search="search" class="elevation-1">
                <template v-slot:items="props">
                    <td>{{ props.item.id }}</td>
                    <td>{{ props.item.especialidad }}</td>
                    <td>{{ props.item.cup }}</td>
                    <td class="justify-center layout px-0">
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-btn fab outline color="error" small v-on="on" @click="update(props.item)">
                                    <v-icon>delete</v-icon>
                                </v-btn>
                            </template>
                            <span>Inhabilitar</span>
                        </v-tooltip>
                    </td>
                </template>
            </v-data-table>
        </v-card>

    </div>
</template>

<script>
    export default {
        data: () => ({
            search: '',
            dialog: false,
            headers: [{
                    text: 'id',
                    align: 'left',
                    sortable: false,
                    value: 'id'
                },
                {
                    text: 'Especialidad',
                    value: 'especialidad'
                },
                {
                    text: 'Cup',
                    value: 'cup'
                },
                {
                    text: 'Acción',
                    sortable: false
                }
            ],
            parametrizaciones: [],
            cups: [],
            cup: [],
            allEspelidades: [],
            especialidad: ''
        }),

        mounted() {
            this.fetchCups()
            this.fetchEspecialidades()
            this.fetchEspecialidadCups()
        },

        methods: {

            fetchCups() {
                axios.get('/api/cups/all')
                    .then(res => this.cups = res.data.map((cup) => {
                        return {
                            id: cup.id,
                            nombre: `${cup.Codigo} - ${cup.Nombre}`
                        }
                    }));
            },

            fetchEspecialidades() {
                axios.get('/api/especialidad/all').
                then(res => {
                    this.allEspelidades = res.data;
                });
            },

            fetchEspecialidadCups() {
                axios.get('/api/especialidad/allespecialidadcups').then(res => {
                    this.parametrizaciones = res.data.map((pa => {
                        return {
                            id: pa.id,
                            cup: `${pa.Codigo} - ${pa.Nombre}`,
                            especialidad: pa.especialidad
                        }
                    }));
                });
            },

            update(item) {
                swal({
                    title: 'Esta seguro de inhabilitar Especialidad Cup?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        axios.put(`/api/especialidad/updatespecialidadcups/${item.id}`).then(res => {
                            this.fetchEspecialidadCups();
                            this.$alerSuccess('Especialidad Cups inhabilitada con exito!')
                        })
                    }
                });
            },

            close() {
                this.dialog = false
                this.especialidad = ''
                this.cup = []
            },

            save() {
                if (this.especialidad == '') return;
                if (this.cup.length == 0) return;
                let data = {
                    especialidad: this.especialidad,
                    cups: this.cup
                }
                axios.post('/api/especialidad/storespecialidadcups', data).then(res => {
                    this.fetchEspecialidadCups();
                    this.close();
                    this.$alerSuccess('Especialidad Cups creado con exito!')
                })
            }
        }
    }

</script>
