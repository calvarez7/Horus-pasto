<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-card-title class="headline" style="color:white;background-color:#0074a6">
                <span class="title layout justify-center font-weight-light">Redes de apoyo</span>
            </v-card-title>
            <v-layout row wrap>
                <v-flex xs12>
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container fluid grid-list-xl>
                            <v-layout wrap align-center>
                                <v-flex xs12 sm6 md5>
                                    <v-select v-model="redesApoyo.redes_apoyo" :items="redes" label="Patologias">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md2>
                                    <v-select v-model="redesApoyo.sino" :items="sino" label="Si / No">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12 sm6 md1>
                                    <v-btn fab dark color="success" @click="guardarRedesApoyo()" small>
                                        <v-icon dark>add</v-icon>
                                    </v-btn>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-textarea name="input-7-1" v-model="redesApoyo.cual_club"
                                        v-if="redesApoyo.redes_apoyo =='Pertenece a algun Club Social o Cultural' & redesApoyo.sino == 'Si'"
                                        label="Cual">
                                    </v-textarea>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-textarea name="input-7-1" v-model="redesApoyo.observacion_club"
                                        v-if="redesApoyo.redes_apoyo =='Pertenece a algun Club Social o Cultural' & redesApoyo.sino == 'Si'"
                                        label="Observacion">
                                    </v-textarea>
                                </v-flex>
                            </v-layout>
                        </v-container>
                        <v-card>
                            <v-card-title primary-title>
                                <v-flex xs12 sm12 md12>
                                    <v-data-table :items="redesAP" :headers="headerredesApoyo" hide-actions
                                        class="elevation-1">
                                        <template v-slot:items="props">
                                            <td class="text-xs">{{ props.item.id }}</td>
                                            <td class="text-xs">{{ props.item.redes_apoyo }}</td>
                                            <td class="text-xs">{{ props.item.sino }}</td>
                                            <td class="text-xs">{{ props.item.cual_club }}</td>
                                            <td class="text-xs">{{ props.item.observacion_club }}</td>
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
            this.fetchRedesApoyo();
        },
        data() {
            return {
                redesApoyo: {
                    redes_apoyo: '',
                    sino: '',
                    cual_club: '',
                    observacion_club: ''
                },
                redes: ['Trabaja', 'Asiste a la Iglesia', 'Pertenece a algun Club Deportivo', 'Comparte con sus Amigos',
                    'Asiste al Colegio', 'Comparte con sus Vecinos', 'Pertenece a algun Club Social o Cultural',
                ],
                headerredesApoyo: [{
                        text: 'id',
                    },
                    {
                        text: 'Patologia',
                    },
                    {
                        text: 'Si/No',
                    },
                    {
                        text: 'Cual',
                    },
                    {
                        text: 'Observacion',
                    },
                    {
                        text: 'Registrado por',
                    }
                ],
                redesAP: [],
                sino: ['Si', 'No']
            }
        },
        methods: {
            guardarRedesApoyo() {
                if (this.redesApoyo.redes_apoyo == 'Pertenece a algun Club Social o Cultural' && this.redesApoyo.sino == 'Si') {
                    if (!this.redesApoyo.cual_club) {
                        this.$alerError("El campo cual es requerido!");
                        return
                    } else {
                        this.redesApoyo.citapaciente_id = this.datosCita.cita_paciente_id;
                        axios.post('/api/historia/saveRedesApoyo', this.redesApoyo)
                        .then(res => {
                            this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                            this.fetchRedesApoyo();
                            this.clear();
                        })
                    }
                } else {
                    this.redesApoyo.citapaciente_id = this.datosCita.cita_paciente_id;
                    axios.post('/api/historia/saveRedesApoyo', this.redesApoyo)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.fetchRedesApoyo();
                        this.clear();
                    })
                }
            },
            fetchRedesApoyo() {
                axios.get(`/api/historia/fetchRedesApoyo/${this.datosCita.cita_paciente_id}`)
                    .then(res => {
                        this.redesAP = res.data;
                    });
            },
            guardarSeguir() {
                this.$emit('respuestaComponente')
            },

            clear() {
                for (const key in this.redesApoyo) {
                    this.redesApoyo[key] = ''
                }
            }
        }
    }

</script>
