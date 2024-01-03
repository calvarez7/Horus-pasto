<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-flex xs12>
                <v-card-title class="headline success" style="color:white">
                    <span class="title layout justify-center font-weight-light">CONCEPTO DE APTITUD LABORAL</span>
                </v-card-title>
            </v-flex>
            <v-card color="lighten-1" class="mb-5" height="auto">
                <v-layout row wrap>
                    <v-flex xs12 class="px-2">
                        <v-autocomplete :items="aptitud" v-model="conducta.aptitud_laboral_psicologia"
                            label="CONCEPTO DE APTITUD LABORAL">
                        </v-autocomplete>
                    </v-flex>
                </v-layout>
            </v-card>
            <!-- <v-btn color="primary" round @click="nextFin()">Guardar y terminar</v-btn> -->
            <v-flex xs12>
                <v-card-title class="headline success" style="color:white">
                    <span class="title layout justify-center font-weight-light">CONDUCTAS</span>
                </v-card-title>
            </v-flex>

            <v-card color="lighten-1" class="mb-5" height="auto">
                <v-layout row wrap>
                    <v-flex xs12 sm6 class="px-2">
                        <v-textarea label="Recomendaciones Personales" v-model="conducta.Planmanejo">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs12 sm6>
                        <v-textarea label="Recomendaciones laborales" v-model="conducta.Recomendaciones"></v-textarea>
                    </v-flex>
                    <v-flex xs6 sm6 class="px-2">
                        <v-autocomplete :items="[
                                'Control', 'Interconsulta', 'Urgencias', 'Consulta externa prioritaria', 'No aplica'
								]" label="Destino del paciente" append-icon="search" v-model="conducta.Destinopaciente"></v-autocomplete>
                    </v-flex>
                    <v-flex xs6 sm6>
                        <v-autocomplete :items="[
									'Consulta SST', 'No aplica'
								]" label="Finalidad" append-icon="search" v-model="conducta.Finalidad"></v-autocomplete>
                    </v-flex>
                </v-layout>
            </v-card>
            <v-flex xs2>
                <v-btn v-if="!conducta.Planmanejo" color="primary" round @click="guardarConducta()">Guardar</v-btn>
                <v-btn v-if="conducta.Planmanejo" color="warning" round @click="guardarConducta()">Actualizar</v-btn>
            </v-flex>
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
            this.fetchConducta();
        },
        data() {
            return {
                aptitud: ['Sin Recomendaciones Laborales', 'Con Recomendaciones  Laborales',
                    'Sin Restricciones  Laborales', 'Con Restricciones Laborales'
                ],
                conducta: {
                    Planmanejo: '',
                    Recomendaciones: '',
                    Destinopaciente: '',
                    Finalidad: '',
                    aptitud_laboral_psicologia: ''
                },
            }
        },

        methods: {

            guardarConducta() {
                if (this.conducta.Planmanejo == undefined) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#000">Plan de manejo debe ser minimo de 5 caracteres !<span>'
                    })
                    return;
                } else if (this.conducta.Recomendaciones == undefined) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#000">Recomendaciones de consulta debe ser minimo de 5 caracteres!<span>'
                    })
                    return;
                } else if (!this.conducta.Destinopaciente) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#000">Destino del paciente es requerido!<span>'
                    })
                    return;
                } else if (!this.conducta.Finalidad) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#000">La FINALIDAD es requerida!<span>'
                    })
                    return;
                }

                axios
                    .post("/api/conducta/" + this.datosCita.cita_paciente_id + "/final", this.conducta)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.$emit('respuestaComponente')
                    });
            },

            fetchConducta() {
                axios.post('/api/conducta/' + this.datosCita.cita_paciente_id + '/getConductaByCita')
                    .then(res => {
                        if (res.data) {
                            this.conducta = res.data
                        }
                    })

            }

        }
    }

</script>
