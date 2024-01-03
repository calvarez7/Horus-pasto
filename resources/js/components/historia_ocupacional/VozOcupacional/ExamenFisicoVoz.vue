<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout row wrap>
                <v-flex xs12>
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <h2 class="text-md-center">MUSCULATURA LARINGEA</h2>
                        <v-layout row wrap>
                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.musculatura_laringea_normal" label="normal" required>
                                </v-text-field>
                            </v-flex>

                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.musculatura_laringea_irritada" label="irritada" required>
                                </v-text-field>
                            </v-flex>

                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.musculatura_laringea_inflamada" label="inflamada" required>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.musculatura_laringea_placas" label="placas" required>
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                        <h2 class="text-md-center">MUSCULATURA EXTRALARINGEA</h2>
                        <v-layout row wrap>
                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.musculatura_extralaringea_dolor_palpar"
                                    label="dolor al palpar" required></v-text-field>
                            </v-flex>

                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.musculatura_extralaringea_dolor_deglutir"
                                    label="dolor al deglutir" required></v-text-field>
                            </v-flex>

                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.musculatura_extralaringea_tono_normal" label="tono normal"
                                    required>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.musculatura_extralaringea_tono_aumentado"
                                    label="tono aumentado" required></v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="saveRespiracion()">Guardar y seguir</v-btn>
                </v-flex>
            </v-layout>
        </v-container>
    </v-form>
</template>
<script>
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        created() {
            this.fetchVoz();
        },
        data() {
            return {
                 voz: {
                    musculatura_laringea_normal: '',
                    musculatura_laringea_irritada: '',
                    musculatura_laringea_inflamada: '',
                    musculatura_laringea_placas: '',
                    musculatura_extralaringea_dolor_palpar: '',
                    musculatura_extralaringea_dolor_deglutir: '',
                    musculatura_extralaringea_tono_normal: '',
                    musculatura_extralaringea_tono_aumentado: ''
                },
            }
        },

        methods: {
            saveRespiracion() {
                axios.post('/api/cita/saveSaludocupacional/' + this.datosCita.cita_paciente_id, this.voz).then((
                    res) => {
                    this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                    this.$emit('respuestaComponente')
                    this.fetchVoz()
                })
            },

            fetchVoz() {
                axios.post('/api/cita/getSaludocupacional/' + this.datosCita.cita_paciente_id)
                    .then(res => {
                        if (res.data) {
                            this.voz = res.data
                        }
                    })
            }


        }
    }

</script>

