<template>
    <div>
        <v-form>
            <v-container grid-list-md fluid class="pa-0">
                <v-layout row wrap>
                    <v-flex xs12>
                        <h2 class="text-md-center"> CONCEPTO MÉDICO DE APTITUD LABORAL – MEDICO OCUPACIONAL</h2>
                        <v-card color="lighten-1" class="mb-5" height="auto">
                            <v-layout row wrap>
                                <v-flex xs12 class="px-2">
                                    <v-autocomplete :items="aptitud" v-model="conducta.aptitud_laboral_medico"
                                        label="CONCEPTO MÉDICO DE APTITUD LABORAL – MEDICO OCUPACIONAL">
                                    </v-autocomplete>
                                </v-flex>
                            </v-layout>
                        </v-card>
                        <h2 class="text-md-center"> SISTEMAS DE VIGILANCIA EPIDEMIOLÓGICO</h2>
                        <v-card color="lighten-1" class="mb-5" height="auto">
                            <v-layout row wrap>
                                <v-flex xs12 class="px-2">
                                    <v-autocomplete :items="epidemiologico" v-model="conducta.vigilancia_epidemiologico"
                                        label="SISTEMAS DE VIGILANCIA EPIDEMIOLÓGICO">
                                    </v-autocomplete>
                                </v-flex>
                            </v-layout>
                        </v-card>
                        <h2 class="text-md-center"> CONDUCTA</h2>
                        <v-card color="lighten-1" class="mb-5" height="auto">
                            <v-layout row wrap>
                                <v-flex xs12 sm6 class="px-2">
                                    <v-textarea label="Plan de manejo" v-model="conducta.Planmanejo">
                                    </v-textarea>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-textarea label="RECOMENDACIONES Y/O RESTRICCIONES LABORALES"
                                        v-model="conducta.Recomendaciones">
                                    </v-textarea>
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
                        <v-btn color="primary" round @click="guardarConducta()">Guardar y terminar</v-btn>
                        <v-dialog v-model="firmas" width="500" persistent>
                            <template v-slot:activator="{ on }">
                                <v-btn color="red lighten-2" dark v-on="on" @click="firma()">
                                    Firma
                                </v-btn>
                            </template>

                            <v-card>
                                <v-card-title class="headline grey lighten-2" primary-title>
                                    FIRMA DEL PACIENTE
                                </v-card-title>

                                <v-flex xs12 sm12 onmouseover>
                                    <VueSignaturePad id="signature" width="100%" height="450px" ref="signaturePad" />
                                </v-flex>
                                <v-divider></v-divider>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="warning" large @click="firmas = false, undo()">Cerrar Sin Guardar</v-btn>
                                    <v-btn color="error" large @click="undo">Borrar</v-btn>
                                    <v-btn color="success" large @click="saveFirma()">Guardar</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-form>
    </div>
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
                firmas: false,
                aptitud: ['Sin Recomendaciones Laborales', 'Con Recomendaciones  Laborales',
                    'Sin Restricciones  Laborales', 'Con Restricciones Laborales'
                ],
                epidemiologico: ['sistema de vigilancia epidemiológico osteomuscular',
                    'sistema de vigilancia epidemiológico voz', 'sistema de vigilancia epidemiológico psicosocial',
                    'no aplica'
                ],
                conducta: {
                    Planmanejo: '',
                    Recomendaciones: '',
                    Destinopaciente: '',
                    Finalidad: '',
                    aptitud_laboral_medico: '',
                    vigilancia_epidemiologico: ''
                }
            }
        },

        methods: {

            undo() {
                this.$refs.signaturePad.clearSignature();
            },
            firma() {
                this.firmas = true;
                this.$nextTick(() => {
                    this.$refs.signaturePad.resizeCanvas();
                })
            },
            saveFirma() {
                this.preload = true;
                const {
                    isEmpty,
                    data
                } = this.$refs.signaturePad.saveSignature();
                if (!isEmpty) {
                    let formData = new FormData();
                    formData.append("file", data);
                    let citaPaciente = this.datosCita.cita_paciente_id
                    axios.post('/api/cita/firmaPaciente/' + citaPaciente, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(res => {
                        this.imprimir();
                        this.$alerSuccess(res.data);
                        this.$refs.signaturePad.clearSignature();
                    }).catch(err => {
                        this.preload = false;
                        console.log(err.response)
                    })
                } else {
                    this.$alerError('Debe ingresar una firma');
                    this.preload = false;
                }
                this.firmas = false
            },

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
                    .catch(err => console.log(err.response));
            }

        }
    }

</script>
<style>
    #signature {
        border: double 3px transparent;
        border-radius: 5px;
        background-image: linear-gradient(white, white),
            radial-gradient(circle at top left, #4bc5e8, #9f6274);
        background-origin: border-box;
        background-clip: content-box, border-box;
    }

</style>
