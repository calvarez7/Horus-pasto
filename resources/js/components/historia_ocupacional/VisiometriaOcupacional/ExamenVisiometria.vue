<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout row wrap>
                <v-flex xs12>
                    <h2 class="text-md-center"> AGUDEZA VISUAL</h2>
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs6 sm6 class="px-5">
                                <v-text-field v-model="saludVisiometriaOcupacional.avcc_ojoderecho"
                                    label="AVCC OJO DERECHO">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-text-field v-model="saludVisiometriaOcupacional.avcc_ojoizquierdo"
                                    label="AVCC OJO IZQUIERDO">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-text-field v-model="saludVisiometriaOcupacional.avsc_ojoderecho"
                                    label="AVSC OJO DERECHO">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-text-field v-model="saludVisiometriaOcupacional.avsc_ojoizquierdo"
                                    label="AVSC OJO IZQUIERDO">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-text-field v-model="saludVisiometriaOcupacional.avscav_ojoizquierdo"
                                    label="AVSC Av Cerca OJO IZQUIERDO">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-text-field v-model="saludVisiometriaOcupacional.avscav_ojoderecho"
                                    label="AVSC Av Cerca OJO DERECHO">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-text-field v-model="saludVisiometriaOcupacional.avcerca_ojoderecho"
                                    label="Av Cerca OJO DERECHO">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-text-field v-model="saludVisiometriaOcupacional.avcerca_ojoizquierdo"
                                    label="Av Cerca IZQUIERDO">
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-card>

                    <h2 class="text-md-center"> MOTILIDAD OCULAR</h2>
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 sm12 class="px-5">
                                <v-textarea v-model="saludVisiometriaOcupacional.motilidad_ocular"
                                    label="Motilidad ocular"></v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <h2 class="text-md-center"> TEST DE ESTEREOPSIS</h2>

                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 sm12 class="px-5">
                                <v-textarea v-model="saludVisiometriaOcupacional.estereopsis" label="ESTEREOPSIS">
                                </v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <h2 class="text-md-center"> TEST DE VISIÓN AL COLOR</h2>

                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs12 sm12 class="px-5">
                                <v-textarea v-model="saludVisiometriaOcupacional.vision_color" label="VISIÓN AL COLOR">
                                </v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <h2 class="text-md-center"> RETINOSCOPIA</h2>
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="saludVisiometriaOcupacional.retinoscopia_ojoderecho"
                                    label="OJO DERECHO"></v-textarea>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="saludVisiometriaOcupacional.retinoscopia_ojoizquierdo"
                                    label="OJO IZQUIERDO">
                                </v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-card>

                    <h2 class="text-md-center"> BIOMICROSCOPIA</h2>

                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="saludVisiometriaOcupacional.gionios_ojoderecho"
                                    label="OJO DERECHO"></v-textarea>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="saludVisiometriaOcupacional.gionios_ojoizquierdo"
                                    label="OJO IZQUIERDO">
                                </v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-card>

                    <h2 class="text-md-center"> FONDO DE OJO</h2>

                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-layout row wrap>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="saludVisiometriaOcupacional.fondo_ojoderecho" label="OJO DERECHO">
                                </v-textarea>
                            </v-flex>
                            <v-flex xs6 sm6 class="px-5">
                                <v-textarea v-model="saludVisiometriaOcupacional.fondo_ojoizquierdo"
                                    label="OJO IZQUIERDO"></v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-btn color="primary" round @click="saveVisiometria()">Guardar y seguir</v-btn>
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
            this.fetchVisiometria();
        },
        data() {
            return {

                agudezas: ['20/20', '20/30', '20/40', '20/50', '20/60', '20/70', '20/80', '20/100', '20/200', '20/400',
                    'PL', 'NPL', 'N/A'
                ],
                saludVisiometriaOcupacional: {
                    avcc_ojoderecho: '',
                    avcc_ojoizquierdo: '',
                    avsc_ojoderecho: '',
                    avsc_ojoizquierdo: '',
                    avscav_ojoizquierdo: '',
                    avscav_ojoderecho: '',
                    avcerca_ojoderecho: '',
                    avcerca_ojoizquierdo: '',
                    motilidad_ocular: '',
                    estereopsis: '',
                    vision_color: '',
                    retinoscopia_ojoderecho: '',
                    retinoscopia_ojoizquierdo: '',
                    gionios_ojoderecho: '',
                    gionios_ojoizquierdo: '',
                    fondo_ojoderecho: '',
                    fondo_ojoizquierdo: '',
                    aptitud_laboral_visiomretria: '',
                }
            }
        },

        methods: {
            saveVisiometria() {
                axios.post('/api/cita/saveSaludocupacional/' + this.datosCita.cita_paciente_id, this
                        .saludVisiometriaOcupacional)
                    .then((res) => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.$emit('respuestaComponente')
                    })
            },

            fetchVisiometria() {
                axios.post('/api/cita/getSaludocupacional/' + this.datosCita.cita_paciente_id)
                    .then(res => {
                        if (res.data) {
                            this.saludVisiometriaOcupacional = res.data
                        }
                    })
            }

        }
    }

</script>
