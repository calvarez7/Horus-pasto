<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout row wrap>
                <v-flex xs12>
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.lugar_cabeza" label="Cabeza" required></v-text-field>
                            </v-flex>

                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.lugar_frente" label="frente" required></v-text-field>
                            </v-flex>

                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.lugar_nasales" label="nasales" required>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.lugar_mejillas" label="mejillas" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.lugar_cuello" label="cuello" required>
                                </v-text-field>
                            </v-flex>
                            <v-flex xs12>
                                <v-textarea outline name="input-7-4" v-model="voz.voz_observaciones"
                                    label="OBSERVACIONES">
                                </v-textarea>
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
                    lugar_cabeza: '',
                    lugar_frente: '',
                    lugar_nasales: '',
                    lugar_mejillas: '',
                    lugar_cuello: '',
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
