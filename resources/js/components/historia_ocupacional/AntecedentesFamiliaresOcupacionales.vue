<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-card color="lighten-1" class="mb-5" height="auto">
                <v-container grid-list-xs>
                    <v-layout row wrap>
                        <v-container fluid grid-list-xl>
                            <v-layout wrap align-center>
                                <v-flex xs12 sm3 d-flex>
                                    <v-autocomplete v-model="antecedent.cie10_id" append-icon="search"
                                        :items="cieConcat" item-disabled="customDisabled" item-text="nombre"
                                        item-value="id" label="Diagnóstico">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm3 d-flex>
                                    <v-autocomplete label="Selecciona Familiar" :items="parentesco" item-text="Nombre"
                                        item-value="id" v-model="antecedent.familiar">
                                    </v-autocomplete>
                                </v-flex>

                                <v-flex xs12 sm5 d-flex>
                                    <v-textarea solo name="input-1-1" label="Escribir Descripción Antecedente"
                                        v-model="antecedent.descripcion">
                                    </v-textarea>
                                </v-flex>

                                <v-flex xs12 sm1 d-flex>
                                    <v-btn fab dark color="success" @click="guardarAntecedentefamiliar()">
                                        <v-icon dark>add</v-icon>
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-layout>
                </v-container>
                <v-card>
                    <v-card-title primary-title>
                        <v-flex xs12 sm12 d-flex>
                            <v-data-table :items="antecedenteFami" :headers="familiare" hide-actions
                                class="elevation-1">
                                <template v-slot:items="props">
                                    <td>{{ props.item.created_at }}</td>
                                    <td class="text-xs">{{ props.item.name }}</td>
                                    <td class="text-xs">{{ props.item.cie }}</td>
                                    <td class="text-xs">{{ props.item.familiar }}</td>
                                    <td class="text-xs">{{ props.item.Descripcion }}</td>
                                </template>
                            </v-data-table>
                        </v-flex>
                    </v-card-title>
                </v-card>
            </v-card>
            <v-btn color="primary" round @click="nextConigtiva()">Seguir</v-btn>
        </v-flex>
    </v-layout>
</template>
<script>
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        created() {
            this.fetchCie10s();
            this.fetchParentesco();
            this.fetchAntecedenteFamiliar();
        },
        data() {
            return {
                antecedent: {
                    antecedente: '',
                    cie10_id: '',
                    descripcion: '',
                    cita_paciente: this.datosCita.cita_paciente_id,
                    familiar: ''
                },
                parentesco: [],
                antecedenteFami: [],
                familiare: [{
                        text: 'Fecha'
                    },
                    {
                        text: 'Médico'
                    },
                    {
                        text: 'Antecedente'
                    },
                    {
                        text: 'Parentesco'
                    },
                    {
                        text: 'Descripción'
                    }
                ],
                Cie10_id: '',
                cie10Array: [],
                cie10s: [],
            }
        },

        computed: {
            cieConcat() {
                if (this.cie10s !== undefined) {
                    return this.cie10Array = this.cie10s.map(cie => {
                        return {
                            id: cie.id,
                            codigo: cie.Codigo_CIE10,
                            nombre: `${cie.Codigo_CIE10} - ${cie.Descripcion}`,
                            customDisabled: false
                        };
                    });
                }
            }
        },
        methods: {
            fetchParentesco() {
                axios.get("/api/parentesco/all").then(res => (this.parentesco = res.data));
            },

            fetchCie10s() {
                axios.get("/api/cie10/all").then(res => {
                    this.cie10s = res.data;
                });
            },

            guardarAntecedentefamiliar() {
                axios.post('/api/parentescoantecedente/create', this.antecedent)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.clearAntecedente();
                        this.fetchAntecedenteFamiliar();
                    })
            },

            fetchAntecedenteFamiliar() {
                axios.get('/api/parentescoantecedente/familiares/' + this.datosCita.cita_paciente_id)
                    .then(res => {
                        this.antecedenteFami = res.data
                    });
            },
            clearAntecedente() {

                this.antecedent.cie10_id = ''
                this.antecedent.descripcion = ''
                this.antecedent.familiar = ''
            },
            nextConigtiva() {
                this.$emit('respuestaComponente')
            }

        }
    }

</script>
