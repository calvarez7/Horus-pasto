<template>
    <v-layout>
        <v-flex xs12>
            <v-card>
                <v-card-title primary-title>
                    <v-flex xs12 sm6 md3>
                        <v-text-field label="Buscar Paciente" v-model="cc" type="number" min="0">
                        </v-text-field>
                    </v-flex>
                    <v-btn color="success" @click="getNotas()">Buscar</v-btn>
                </v-card-title>
            </v-card>
            <v-divider></v-divider>
            <v-card>
                <v-card-title>
                    Nutrition
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                    </v-text-field>
                </v-card-title>
                <v-data-table :headers="headers" :items="notas" :search="search">
                    <template v-slot:items="props">
                        <td>{{ props.item.id }}</td>
                        <td>{{ props.item.Num_Doc }}</td>
                        <td>{{ props.item.Primer_Nom }} {{ props.item.SegundoNom }} {{ props.item.Primer_Ape }}
                            {{ props.item.Segundo_Ape }}</td>
                        <td>{{ props.item.created_at }}</td>
                        <td>
                            <v-btn fab dark small color="warning">
                                <v-icon dark @click="miNota(props.item.id)">mdi-eye</v-icon>
                            </v-btn>
                        </td>
                    </template>
                    <template v-slot:no-results>
                        <v-alert :value="true" color="error" icon="warning">
                            Your search for "{{ search }}" found no results.
                        </v-alert>
                    </template>
                </v-data-table>
            </v-card>
            <div class="text-xs-center">
                <v-dialog v-model="dialog" width="800">
                    <v-card>
                        <v-card-title class="headline success" style="color:white">
                            Nota de enfermeria
                        </v-card-title>

                        <v-card-text>
                            <h4>Nota</h4>
                            <p>{{descripcion.nota}}</p>
                            <v-divider></v-divider>
                            <h4>Signos de alarma</h4>
                            <p>{{descripcion.signos_alarma}}</p>
                            <v-divider></v-divider>
                            <h4>Nota</h4>
                            <p>{{descripcion.signos_alarma}}</p>
                            <v-divider></v-divider>
                            <h4>Cuidados en casa</h4>
                            <p>{{descripcion.cuidados_casa}}</p>
                            <v-divider></v-divider>
                            <h4>Caso de urgencias</h4>
                            <p>{{descripcion.caso_urgencia}}</p>
                            <v-divider></v-divider>
                            <h4>Alimentación</h4>
                            <p>{{descripcion.alimentacion}}</p>
                            <v-divider></v-divider>
                            <h4>Efectos secundarios</h4>
                            <p>{{descripcion.efectos_secundarios}}</p>
                            <v-divider></v-divider>
                            <h4>Habito higiene</h4>
                            <p>{{descripcion.habito_higiene}}</p>
                            <v-divider></v-divider>
                            <h4>Derechos deberes</h4>
                            <p>{{descripcion.derechos_deberes}}</p>
                            <v-divider></v-divider>
                            <h4>Normas sala quimioterapía</h4>
                            <p>{{descripcion.normas_sala_quimioterapia}}</p>
                        </v-card-text>

                        <v-divider></v-divider>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="red" dark @click="dialog=false">
                                Cerrar
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </div>
        </v-flex>
    </v-layout>
</template>
<script>
    import moment from "moment";
    import {
        mapGetters
    } from "vuex";

    moment.locale("es");
    export default {
        name: 'enfermera',
        data() {
            return {
                descripcion: '',
                dialog: false,
                search: '',
                cc: '',
                notas: [],
                headers: [{
                    text: "id",
                    sortable: false,
                    value: ""
                }, {
                    text: "Cédula",
                    sortable: false,
                    value: ""
                }, {
                    text: "Nombre",
                    sortable: false,
                    value: ""
                }, {
                    text: "Fecha de Nota",
                    sortable: false,
                    value: "Num_Doc"
                }, {
                    text: "Ver",
                    sortable: false,
                    value: "paciente"
                }],
            }
        },
        computed: {
            ...mapGetters(['can'])
        },
        methods: {
            getNotas() {
                const data = {
                    'cc': this.cc
                };
                try {
                    axios.post('/api/esquemas/notas/', data)
                        .then(res => {
                            this.notas = res.data
                            console.log('notas', res.data);
                        })
                } catch (e) {
                    console.log(e);
                }
            },
            miNota(id){
                try {
                    axios.get('/api/esquemas/miNota/'+ id, )
                    .then(res => {
                            this.descripcion = res.data;
                            this.dialog= true
                        })
                } catch (e) {
                    console.log(e);
                }
            }
        },
        mounted() {
            this.getNotas();
        }
    }

</script>
