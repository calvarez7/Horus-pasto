<template>
    <v-container fluid grid-list-xl>
        <v-layout row wrap>
            <v-flex xs12>
                <v-card>
                    <template>
                        <div>
                            <v-toolbar flat color="white">
                                <v-toolbar-title>Pacientes Pendientes por Gestionar</v-toolbar-title>
                                <v-divider class="mx-2" inset vertical></v-divider>
                                <v-spacer></v-spacer>
                                <v-dialog v-model="dialog" max-width="500px">
                                    <v-card>
    x                                    <v-card-title>
                                            <span class="headline">asd</span>
                                        </v-card-title>

                                        <v-card-text>
                                            <!-- <v-container grid-list-md>
                                                <v-layout wrap>
                                                    <v-flex xs12 sm6 md4>
                                                        <v-text-field v-model="editedItem.name" label="Dessert name">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 sm6 md4>
                                                        <v-text-field v-model="editedItem.calories" label="Calories">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 sm6 md4>
                                                        <v-text-field v-model="editedItem.fat" label="Fat (g)">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 sm6 md4>
                                                        <v-text-field v-model="editedItem.carbs" label="Carbs (g)">
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 sm6 md4>
                                                        <v-text-field v-model="editedItem.protein" label="Protein (g)">
                                                        </v-text-field>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container> -->
                                        </v-card-text>

                                        <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <!-- <v-btn color="blue darken-1" flat @click="close">Cancel</v-btn>
                                            <v-btn color="blue darken-1" flat @click="save">Save</v-btn> -->
                                        </v-card-actions>
                                    </v-card>
                                </v-dialog>
                            </v-toolbar>
                            <v-data-table :headers="headers" :items="remisiones" class="elevation-1">
                                <template v-slot:items="props">
                                    <td>{{ props.item.citaPaciente_id }}</td>
                                    <td class="text-xs-right">{{ props.item.paciente }}</td>
                                    <td class="text-xs-right">{{ props.item.remite }}</td>
                                    <td class="text-xs-right">{{ props.item.telIPSremite }}</td>
                                    <td class="text-xs-right">{{ props.item.emailmedico }}</td>
                                    <!-- <td class="justify-center layout px-0">
                                        <v-icon small class="mr-2" @click="editItem(props.item)">
                                            edit
                                        </v-icon>
                                        <v-icon small @click="deleteItem(props.item)">
                                            delete
                                        </v-icon>
                                    </td> -->
                                </template>
                                <template v-slot:no-data>
                                    <!-- <v-btn color="primary" @click="initialize">Reset</v-btn> -->
                                </template>
                            </v-data-table>
                        </div>
                    </template>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import {
        mapGetters
    } from 'vuex';
    import Swal from 'sweetalert2';
    import moment from 'moment';
    export default {
        data: () => ({
            search: '',
            dialog: false,
            remisiones: [],
            headers: [{
                    text: 'Dessert (100g serving)',
                    align: 'left',
                    sortable: false,
                    value: 'name'
                },
                {
                    text: 'Calories',
                    value: 'calories'
                },
                {
                    text: 'Fat (g)',
                    value: 'fat'
                },
                {
                    text: 'Carbs (g)',
                    value: 'carbs'
                },
                {
                    text: 'Protein (g)',
                    value: 'protein'
                },
                {
                    text: 'Actions',
                    value: 'name',
                    sortable: false
                }
            ],
        }),

        computed: {
            ...mapGetters(['can']),
        },
        mounted() {
            moment.locale('es');
            this.fetchRemisiones();
        },
        methods: {
            fetchRemisiones() {
                axios.get("/api/domiciliaria/remisionesall").then(res => {
                    this.remisiones = res.data;
                });
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
