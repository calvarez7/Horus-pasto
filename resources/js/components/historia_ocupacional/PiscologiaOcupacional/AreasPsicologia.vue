<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-card color="lighten-1" class="mb-5" height="auto">
                <v-layout row wrap>
                    <v-flex xs12>
                        <v-card-title class="headline success" style="color:white">
                            <span class="title layout justify-center font-weight-light">Procesos Cognitivos Superiores -
                                Area Cognitiva</span>
                        </v-card-title>
                    </v-flex>
                    <v-flex xs6 sm6 class="px-5">
                        <v-textarea v-model="psicologiaOcupacional.proceso_cognitivo" label="Percepcion"></v-textarea>
                    </v-flex>
                    <v-flex xs6 sm6 class="px-5">
                        <v-textarea v-model="psicologiaOcupacional.memoria" label="Memoria"></v-textarea>
                    </v-flex>
                    <v-flex xs6 sm6 class="px-5">
                        <v-textarea v-model="psicologiaOcupacional.atencion" label="Atencion"></v-textarea>
                    </v-flex>
                    <v-flex xs6 sm6 class="px-5">
                        <v-textarea v-model="psicologiaOcupacional.lenguaje" label="Lenguaje"></v-textarea>
                    </v-flex>
                    <v-flex xs6 sm6 class="px-5">
                        <v-textarea v-model="psicologiaOcupacional.ubicacion" label="Ubicado en"></v-textarea>
                    </v-flex>
                    <v-flex xs6 sm6 class="px-5">
                        <v-textarea v-model="psicologiaOcupacional.estado_mental" label="Estado Mental"></v-textarea>
                    </v-flex>
                    <v-flex xs6 sm6 class="px-5">
                        <v-textarea v-model="psicologiaOcupacional.presentacion_personal" label="Presentación Personal">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs6 sm6 class="px-5">
                        <v-textarea v-model="psicologiaOcupacional.introspeccion" label="Introspección "></v-textarea>
                    </v-flex>
                    <v-flex xs6 sm6 class="px-5">
                        <v-textarea v-model="psicologiaOcupacional.prospeccion" label="Prospección "></v-textarea>
                    </v-flex>
                    <v-flex xs6 sm6 class="px-5">
                        <v-textarea v-model="psicologiaOcupacional.social" label="Area Social"></v-textarea>
                    </v-flex>
                    <v-flex xs6 sm6 class="px-5">
                        <v-textarea v-model="psicologiaOcupacional.familiar" label="Area Familiar"></v-textarea>
                    </v-flex>
                    <v-flex xs6 sm6 class="px-5">
                        <v-textarea v-model="psicologiaOcupacional.laboral" label="Area Laboral"></v-textarea>
                    </v-flex>
                    <v-flex xs6 sm6 class="px-5">
                        <v-textarea v-model="psicologiaOcupacional.academica" label="Area Academica"></v-textarea>
                    </v-flex>
                    <v-flex xs6 sm6 class="px-5">
                        <v-textarea v-model="psicologiaOcupacional.analisis_diagnostico" label=" Analisis Diagnostico">
                        </v-textarea>
                    </v-flex>
                    <v-flex xs6 sm6 class="px-5">
                        <v-textarea v-model="psicologiaOcupacional.recoleccion_datos"
                            label="Tecnica de Recolección de datos">
                        </v-textarea>
                    </v-flex>
                </v-layout>
                <v-btn color="primary" round @click="saveSaludocupacional()">Guardar y seguir</v-btn>
            </v-card>
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
            this.fetchOcupacional();
        },
        data() {
            return {
                psicologiaOcupacional: {
                    proceso_cognitivo: '',
                    memoria: '',
                    atencion: '',
                    lenguaje: '',
                    ubicacion: '',
                    estado_mental: '',
                    presentacion_personal: '',
                    introspeccion: '',
                    prospeccion: '',
                    academica: '',
                    laboral: '',
                    social: '',
                    familiar: '',
                    analisis_diagnostico: '',
                    recoleccion_datos: '',
                },
            }
        },

        methods: {
            saveSaludocupacional() {
                axios.post('/api/cita/saveSaludocupacional/'+ this.datosCita.cita_paciente_id, this.psicologiaOcupacional).then((
                    res) => {
                    this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                    this.$emit('respuestaComponente')
                })
            },
            fetchOcupacional() {
                axios.post('/api/cita/getSaludocupacional/' + this.datosCita.cita_paciente_id)
                    .then(res => {
                        if (res.data) {
                            this.psicologiaOcupacional = res.data
                        }
                    })
            },


        }
    }

</script>
