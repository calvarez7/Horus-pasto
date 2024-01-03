<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout row wrap>
                <v-flex xs12>
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs4 sm3 class="px-5">
                                <v-radio-group v-model="voz.respiracion_modo" label="MODO">
                                    <v-radio v-for="n in modo" :key="n" :label="`${n}`" :value="n" color="primary">
                                    </v-radio>
                                </v-radio-group>
                            </v-flex>
                            <v-flex xs4 sm3 class="px-5">
                                <v-radio-group v-model="voz.respiracion_tipo" label="TIPO">
                                    <v-radio v-for="n in tipo" :key="n" :label="`${n}`" :value="n" color="primary">
                                    </v-radio>
                                </v-radio-group>
                            </v-flex>
                            <v-flex xs4 sm3 class="px-5">
                                <v-radio-group v-model="voz.respiracion_fonorespiratoria"
                                    label="COORD. FONORESPIRATORIA">
                                    <v-radio v-for="n in fonorespiratoria" :key="n" :label="`${n}`" :value="n"
                                        color="primary"></v-radio>
                                </v-radio-group>
                            </v-flex>
                            <v-flex xs12 md3 class="px-2">
                                <v-radio-group v-model="voz.perimetros_biaxilar" label="PRUEBA GLATZER">
                                    <v-radio v-for="n in glatzer" :key="n" :label="`${n}`" :value="n" color="primary">
                                    </v-radio>
                                </v-radio-group>
                            </v-flex>
                        </v-layout>
                        <hr>
                        <h2 class="text-md-center">PERIMETROS</h2>
                        <v-layout row wrap>
                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.perimetros_xifoideo" label="biaxilar" required>
                                </v-text-field>
                            </v-flex>

                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.perimetros_abdominal" label="xifoideo" required>
                                </v-text-field>
                            </v-flex>

                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.respiracion_prueba_glatzer" label="abdominal" required>
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                        <hr>
                        <h2 class="text-md-center">TIEMPOS DE RESPIRACION</h2>
                        <v-layout row wrap>
                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.tiempos_respiracion_inspiracion" label="inspiración"
                                    required></v-text-field>
                            </v-flex>

                            <v-flex xs12 md4 class="px-2">
                                <v-text-field v-model="voz.tiempos_respiracion_espiracion" label="espiración" required>
                                </v-text-field>
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
                glatzer: ['adecuada', 'inadecuada'],
                fonorespiratoria: ['Normal', 'Discontinua', 'Fuera de fase'],
                tipo: ['Costal Superior', 'Mixto', 'Abdominal'],
                modo: ['Nasal', 'Bucal', 'Mixto'],
                voz: {
                    respiracion_modo: '',
                    respiracion_tipo: '',
                    respiracion_fonorespiratoria: '',
                    perimetros_biaxilar: '',
                    perimetros_xifoideo: '',
                    perimetros_abdominal: '',
                    respiracion_prueba_glatzer: '',
                    tiempos_respiracion_inspiracion: '',
                    tiempos_respiracion_espiracion: ''
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
