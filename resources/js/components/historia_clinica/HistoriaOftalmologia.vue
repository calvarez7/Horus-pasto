<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-container grid-list-md fluid class="pa-0">
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Agudeza Visual</span>
                </v-card-title>
                <v-card color="lighten-1" class="mb-5" height="auto">
                    <v-layout row wrap>
                        <v-flex xs6 sm6 class="px-5">
                            <v-autocomplete :items="agudezas" v-model="oftamologia.avcc_ojoderecho"
                                label="AVCC OJO DERECHO">
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs6 sm6 class="px-5">
                            <v-autocomplete :items="agudezas" v-model="oftamologia.avcc_ojoizquierdo"
                                label="AVCC OJO IZQUIERDO">
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs6 sm6 class="px-5">
                            <v-autocomplete :items="agudezas" v-model="oftamologia.avsc_ojoderecho"
                                label="AVSC OJO DERECHO">
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs6 sm6 class="px-5">
                            <v-autocomplete :items="agudezas" v-model="oftamologia.avsc_ojoizquierdo"
                                label="AVSC OJO IZQUIERDO">
                            </v-autocomplete>
                        </v-flex>
                        <v-flex xs6 sm6 class="px-5">
                            <v-text-field v-model="oftamologia.ph_ojoderecho" label="PH OJO DERECHO">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs6 sm6 class="px-5">
                            <v-text-field v-model="oftamologia.ph_ojoizquierdo" label="PH OJO IZQUIERDO">
                            </v-text-field>
                        </v-flex>
                    </v-layout>
                </v-card>
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Motilidad Ocular</span>
                </v-card-title>
                <v-card color="lighten-1" class="mb-5" height="auto">
                    <v-layout row wrap>
                        <v-flex xs6 sm6 class="px-5">
                            <v-textarea v-model="oftamologia.motilidad_ojoderecho" label="OJO DERECHO"></v-textarea>
                        </v-flex>
                        <v-flex xs6 sm6 class="px-5">
                            <v-textarea v-model="oftamologia.covert_ojoderecho" label="COVER TEST DERECHO">
                            </v-textarea>
                        </v-flex>
                        <v-flex xs6 sm6 class="px-5">
                            <v-textarea v-model="oftamologia.motilidad_ojoizquierdo" label="OJO IZQUIERDO">
                            </v-textarea>
                        </v-flex>
                        <v-flex xs6 sm6 class="px-5">
                            <v-textarea v-model="oftamologia.covert_ojoizquierdo" label="COVER TEST IZQUIERDO">
                            </v-textarea>
                        </v-flex>
                    </v-layout>
                </v-card>
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">BIOMICROSCOPIA</span>
                </v-card-title>
                <v-card color="lighten-1" class="mb-5" height="auto">
                    <v-layout row wrap>
                        <v-flex xs6 sm6 class="px-5">
                            <v-textarea v-model="oftamologia.biomicros_ojoderecho" label="OJO DERECHO">
                            </v-textarea>
                        </v-flex>
                        <v-flex xs6 sm6 class="px-5">
                            <v-textarea v-model="oftamologia.biomicros_ojoizquierdo" label="OJO IZQUIERDO">
                            </v-textarea>
                        </v-flex>
                    </v-layout>
                </v-card>
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">PRESION INTRAOCULAR</span>
                </v-card-title>
                <v-card color="lighten-1" class="mb-5" height="auto">
                    <v-layout row wrap>
                        <v-flex xs6 sm6 class="px-5">
                            <v-textarea v-model="oftamologia.presionintra_ojoderecho" label="OJO DERECHO">
                            </v-textarea>
                        </v-flex>
                        <v-flex xs6 sm6 class="px-5">
                            <v-textarea v-model="oftamologia.presionintra_ojoizquierdo" label="OJO IZQUIERDO">
                            </v-textarea>
                        </v-flex>
                    </v-layout>
                </v-card>
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">GIONIOSCOPIA</span>
                </v-card-title>
                <v-card color="lighten-1" class="mb-5" height="auto">
                    <v-layout row wrap>
                        <v-flex xs6 sm6 class="px-5">
                            <v-textarea v-model="oftamologia.gionios_ojoderecho" label="OJO DERECHO">
                            </v-textarea>
                        </v-flex>
                        <v-flex xs6 sm6 class="px-5">
                            <v-textarea v-model="oftamologia.gionios_ojoizquierdo" label="OJO IZQUIERDO">
                            </v-textarea>
                        </v-flex>
                    </v-layout>
                </v-card>
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">FONDO DE OJOS</span>
                </v-card-title>
                <v-card color="lighten-1" class="mb-5" height="auto">
                    <v-layout row wrap>
                        <v-flex xs6 sm6 class="px-5">
                            <v-textarea v-model="oftamologia.fondo_ojoderecho" label="OJO DERECHO">
                            </v-textarea>
                        </v-flex>
                        <v-flex xs6 sm6 class="px-5">
                            <v-textarea v-model="oftamologia.fondo_ojoizquierdo" label="OJO IZQUIERDO">
                            </v-textarea>
                        </v-flex>
                    </v-layout>
                </v-card>
                <v-btn color="success" round @click="saveOftamologia()">Guardar y seguir</v-btn>
            </v-container>
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
            this.fetchOftamologia();
        },
        data() {
            return {
                agudezas: ['20/20', '20/30', '20/40', '20/50', '20/60', '20/70', '20/80', '20/100', '20/200', '20/400',
                    'PL', 'NPL'
                ],

                oftamologia: {
                    avcc_ojoderecho: '',
                    avcc_ojoizquierdo: '',
                    avsc_ojoderecho: '',
                    avsc_ojoizquierdo: '',
                    ph_ojoderecho: '',
                    ph_ojoizquierdo: '',
                    motilidad_ojoderecho: '',
                    covert_ojoderecho: '',
                    motilidad_ojoizquierdo: '',
                    covert_ojoizquierdo: '',
                    biomicros_ojoderecho: '',
                    biomicros_ojoizquierdo: '',
                    presionintra_ojoderecho: '',
                    presionintra_ojoizquierdo: '',
                    gionios_ojoderecho: '',
                    gionios_ojoizquierdo: '',
                    fondo_ojoderecho: '',
                    fondo_ojoizquierdo: '',
                }
            }
        },

        methods: {
            saveOftamologia() {
                axios.post('/api/cita/saveOftamologia/' + this.datosCita.cita_paciente_id, this.oftamologia).then((
                    res) => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.$emit('respuestaComponente');
                    })
            },

            fetchOftamologia() {
                axios.post('/api/cita/getoftamologia/' + this.datosCita.cita_paciente_id)
                    .then(res => {
                        if (res.data) {
                            this.oftamologia = res.data
                        }
                    })
                    .catch(err => console.log(err.response));
            },

        }
    }

</script>
