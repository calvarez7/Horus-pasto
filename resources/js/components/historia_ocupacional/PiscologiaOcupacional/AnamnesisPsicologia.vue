<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout row wrap>
                <v-flex xs12>
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-container fluid grid-list-xl>
                                    <v-layout wrap align-center>
                                        <v-flex xs12>
                                            <v-select :items="motivos" v-model="data.Motivoconsulta"
                                                label="Selecciona el tipo de consulta">
                                            </v-select>
                                            <v-textarea name="input-7-1" label="Enfermedad Actual" value=""
                                                v-model="data.Enfermedadactual">
                                            </v-textarea>
                                            <v-btn color="primary" round @click="guardarMotivoConsulta()">Guardar y
                                                seguir</v-btn>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-layout>
                        </v-container>
                    </v-card>
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

        },
        data() {
            return {
                motivos: ['Examenes ocupacionales periódicos', 'Examenes ocupacionales ingreso',
                    'Examenes ocupacionales egreso', 'Examenes ocupacionales post incapacidad',
                    'Examenes ocupacionales reubicación', 'Exámenes ocupacionales para Evento deportivos',
                    'Exámenes ocupacionales para evento Folclórico'
                ],
                data: {
                    Motivoconsulta: '',
                    Enfermedadactual: ''
                },


            }
        },
        mounted() {
            this.fetchAtencion();
        },
        methods: {

            fetchAtencion() {
                axios.get('/api/cita/' + this.datosCita.cita_paciente_id + '/motivo')
                    .then(res => {
                        this.data = res.data;
                    });
            },

            guardarMotivoConsulta() {
                if (!this.data.Motivoconsulta) {
                    swal("Motivo consulta es REQUERIDO y debe ser mayor a 5 caracteres")
                    return;
                } else if (this.data.Motivoconsulta.length < 5) {
                    swal("Motivo consulta debe ser mayor a 5 caracteres")
                    return;
                }
                axios.post('/api/cita/' + this.datosCita.cita_paciente_id + '/motivo', this.data)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.$emit('respuestaComponente')
                    });

            },


        }
    }

</script>
