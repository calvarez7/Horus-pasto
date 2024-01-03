<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-card-title class="headline" style="color:white;background-color:#0074a6">
                <span class="title layout justify-center font-weight-light">Familiograma</span>
            </v-card-title>
            <v-layout row wrap>
                <v-flex xs12>
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container fluid grid-list-xl>
                            <v-layout wrap align-center>
                                <v-flex xs12 sm6 md3>
                                    <v-select v-model="familiograma.vinculos" :items="vinculo" label="Vínculos">
                                    </v-select>
                                </v-flex>

                                <v-flex xs12 sm6 md3>
                                    <v-select v-model="familiograma.relacion_afectiva" :items="afectiva"
                                        label="Relación afectiva">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md4>
                                    <v-select label="Problemas salud/enfermedad" :items="sino"
                                        v-model="familiograma.problemas_salud">
                                    </v-select>
                                </v-flex>
                                <v-flex xs2>
                                    <v-btn fab small dark color="success" @click="saveFamiliograma()">
                                        <v-icon dark>add</v-icon>
                                    </v-btn>
                                </v-flex>
                                <v-flex xs12 sm6 md6 v-if="familiograma.problemas_salud=='Si'">
                                    <v-textarea outlined name="input-7-4" label="CUAL"
                                        v-model="familiograma.cual_familiograma">
                                    </v-textarea>
                                </v-flex>
                                <v-flex xs12 sm6 md6 v-if="familiograma.problemas_salud=='Si'">
                                    <v-textarea outlined name="input-7-4" label="OBSERVACIONES"
                                        v-model="familiograma.observaciones_familiograma">
                                    </v-textarea>
                                </v-flex>
                            </v-layout>
                        </v-container>
                        <v-card>
                            <v-card-title primary-title>
                                <v-flex xs12 sm12 md12>
                                    <v-data-table :items="getFamiliograma" :headers="headerFamiliograma" hide-actions
                                        class="elevation-1">
                                        <template v-slot:items="props">
                                            <td class="text-xs">{{ props.item.id }}</td>
                                            <td class="text-xs">{{ props.item.vinculo }}</td>
                                            <td class="text-xs">{{ props.item.relacion_afectiva }}</td>
                                            <td class="text-xs">{{ props.item.problemas_salud }}</td>
                                            <td class="text-xs">{{ props.item.cual_familiograma }}</td>
                                            <td class="text-xs">{{ props.item.observaciones_familiograma }}</td>
                                            <td class="text-xs">{{ props.item.name }}</td>
                                        </template>
                                    </v-data-table>
                                </v-flex>
                            </v-card-title>
                        </v-card>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
        <v-btn color="success" round @click="guardarSeguir()">Guardar y seguir</v-btn>
    </v-form>
</template>
<script>
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        created() {
            this.fetchfamiliograma();
        },
        data() {
            return {
                vinculo: ['Noviasgo', 'Esposos', 'Union Libre', 'Divorciados', 'Separados', 'Viudo'],
                afectiva: ['Conflictiva', 'Dominante', 'Repulsiva', 'Distante', 'Estresante', 'Satisfactoria'],
                sino: ['Si', 'No'],
                familiograma: {
                    vinculos: '',
                    relacion_afectiva: '',
                    problemas_salud: '',
                    cual_familiograma: '',
                    observaciones_familiograma: '',
                },
                getFamiliograma: [],

                headerFamiliograma: [{
                        text: 'id',
                    },
                    {
                        text: 'Vinculos',
                    },
                    {
                        text: 'Relación afectiva',
                    },
                    {
                        text: 'Problemas salud',
                    },
                    {
                        text: 'Cual familiograma',
                    },
                    {
                        text: 'Observación',
                    },
                    {
                        text: 'Registrado por',
                    },
                ]
            }
        },
        methods: {
            saveFamiliograma() {
                if (this.familiograma.problemas_salud == 'Si') {
                    if (!this.familiograma.cual_familiograma) {
                        this.$alerError("El campo cual es requerido!");
                        return
                    } else {
                        this.familiograma.paciente_id = this.datosCita.paciente_id;
                        axios.post('/api/historia/saveFamiliograma', this.familiograma)
                            .then(res => {
                                this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                                this.fetchfamiliograma();
                                this.clear();
                            })
                    }
                } else {
                    this.familiograma.paciente_id = this.datosCita.paciente_id;
                    axios.post('/api/historia/saveFamiliograma', this.familiograma)
                        .then(res => {
                            this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                            this.fetchfamiliograma();
                            this.clear();
                        })
                }
            },
            fetchfamiliograma() {
                axios.get(`/api/historia/fetchFamiliograma/${this.datosCita.paciente_id}`)
                    .then(res => {
                        this.getFamiliograma = res.data;
                    });
            },
            guardarSeguir() {
                this.$emit('respuestaComponente');
            },
            clear() {
                for (const key in this.familiograma) {
                    this.familiograma[key] = ''
                }
            }
        }

    }

</script>
