<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-card color="lighten-1" class="mb-5" height="auto">
                <v-container grid-list-xs>
                    <v-layout row wrap>
                        <v-container fluid grid-list-xl>
                            <v-layout wrap align-center>
                                <v-flex xs12 sm4 d-flex>
                                    <v-autocomplete v-model="antecedent.cie10_id" append-icon="search"
                                        :items="cieConcat" item-disabled="customDisabled" item-text="nombre"
                                        item-value="id" label="Diagnóstico">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm6 d-flex>
                                    <v-textarea solo name="input-1-1" label="Escribir Descripción Antecedente"
                                        v-model="antecedent.descripcion">
                                    </v-textarea>
                                </v-flex>
                                <v-flex xs12 sm1 d-flex>
                                    <v-btn fab dark color="success" @click="guardarAntecedente()">
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
                            <v-data-table :items="antecedentePaci" :headers="antecedenteP" hide-actions
                                class="elevation-1">
                                <template v-slot:items="props">
                                    <td>{{ props.item.created_at }}</td>
                                    <td class="text-xs">{{ props.item.name }}</td>
                                    <td class="text-xs">{{ props.item.cie }}</td>
                                    <td class="text-xs">{{ props.item.Descripcion }}</td>
                                </template>
                            </v-data-table>
                        </v-flex>
                    </v-card-title>
                </v-card>
            </v-card>
            <v-btn color="primary" round @click="nextParentesco()">Seguir</v-btn>
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
            this.fetchAntecedentes();
            this.fetchAntecedentePersonal();
        },
        data() {
            return {
                antecedent: {
                    cie10_id: '',
                    descripcion: '',
                    citapaciente_id: this.datosCita.cita_paciente_id
                },
                citaPaciente: 0,
                antecedentes: [],
                antecedentePaci: [],
                antecedenteP: [{
                        text: 'Fecha'
                    },
                    {
                        text: 'Médico'
                    },
                    {
                        text: 'Antecedente'
                    },
                    {
                        text: 'Descripción'
                    }
                ],
                parentesco: [],
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

            fetchCie10s() {
                axios.get("/api/cie10/all").then(res => {
                    this.cie10s = res.data;
                });
            },

            addDiagnostico() {
                if (this.Cie10_id) {
                    this.cie10Array.find(cie10 => {
                        if (cie10.id == this.Cie10_id) {
                            this.Codigo = cie10.codigo;
                        }
                    });
                }
                if (
                    this.Cie10_id &&
                    this.Tipodiagnostico != "Impresión diagnóstica" &&
                    this.Marcapaciente.length > 0
                ) {
                    this.Diagnostico.push({
                        Cie10_id: this.Cie10_id,
                        Tipodiagnostico: this.Tipodiagnostico,
                        Marcapaciente: this.Marcapaciente,
                        Esprimario: false,
                        Codigo: this.Codigo
                    });
                    this.disable(this.Cie10_id);
                    this.clearDataArticulo();
                } else if (
                    this.Cie10_id &&
                    this.Tipodiagnostico == "Impresión diagnóstica" &&
                    this.Marcapaciente.length == 0
                ) {
                    this.Diagnostico.push({
                        Cie10_id: this.Cie10_id,
                        Tipodiagnostico: this.Tipodiagnostico,
                        Esprimario: false,
                        Codigo: this.Codigo
                    });
                    this.disable(this.Cie10_id);
                    this.clearDataArticulo();
                }
            },

            fetchAntecedentes() {
                axios.get("/api/antecedente/all").then(res => (this.antecedentes = res.data));
            },

            guardarAntecedente() {
                axios.post('/api/pacienteantecedente/saveAntecedentesOcupacional', this.antecedent)
                    .then(res => {

                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.clearAntecedente();
                        this.fetchAntecedentePersonal();
                    })
            },

            fetchAntecedentePersonal() {
                axios.get('/api/pacienteantecedente/antecedentesOcupacional/' + this.datosCita.cita_paciente_id )
                    .then(res => {
                        this.antecedentePaci = res.data
                    });
            },

            clearAntecedente() {
                this.antecedent.antecedente = ''
                this.antecedent.descripcion = ''
                this.antecedent.cie10_id = ''
            },

            fetchParentesco() {
                axios.get("/api/parentesco/all").then(res => (this.parentesco = res.data));
            },
            nextParentesco() {
                this.$emit('respuestaComponente')

            },

        }
    }

</script>
