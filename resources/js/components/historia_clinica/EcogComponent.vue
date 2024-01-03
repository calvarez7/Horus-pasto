<template>
    <div>
        <v-form>
            <v-container grid-list-md fluid class="pa-0">
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="justify-center title layout font-weight-light">ESCALA DE VALORACIÓN FUNCIONAL ECOG: <b>{{ecog}}</b></span>
                </v-card-title>
                <v-layout row wrap>
                    <v-flex sm12 md12 sd12>
                    <v-radio-group v-model="ecog" color="primary">
                        <v-radio color="primary" v-for="opcion in opciones" :key="opcion.valor" :label="opcion.nombre" :value="opcion.valor"></v-radio>
                    </v-radio-group>
                    </v-flex>
                    <v-btn color="success" round @click="saveEcog()">Guardar y seguir</v-btn>
                </v-layout>
            </v-container>
        </v-form>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        name: 'HorusHealthKarnosfskiComponent',
        props: {
            datosCita: Object
        },
        data() {
            return {
                opciones: [
                    {
                        nombre:'El paciente se encuentra totalmente asintomático y es capaz de realizar un trabajo y actividades normales de la vida diaria.',
                        valor: 0
                    },
                    {
                        nombre:'El paciente presenta síntomas que le impiden realizar trabajos arduos, aunque se desempeña normalmente en sus actividades cotidianas y en trabajos ligeros. El paciente sólo permanece en la cama durante las horas de sueño nocturno.',
                        valor: 1
                    },
                    {
                        nombre:'El paciente no es capaz de desempeñar ningún trabajo, se encuentra con síntomas que le obligan a permanecer en la cama durante varias horas al día, además de las de la noche, pero que no superan el 50% del día. El individuo satisface la mayoría de sus necesidades personales solo.',
                        valor: 2
                    },
                    {
                        nombre:'El paciente necesita estar encamado más de la mitad del día por la presencia de síntomas. Necesita ayuda para la mayoría de las actividades de la vida diaria como por ejemplo el vestirse.',
                        valor: 3
                    },
                    {
                        nombre:'El paciente permanece encamado el 100% del día y necesita ayuda para todas las actividades de la vida diaria, como por ejemplo la higiene corporal, la movilización en la cama e incluso la alimentación.',
                        valor: 4
                    },
                    {
                        nombre:'Fallecido.',
                        valor: 5
                    },
                ],
                ecog: null
            };
        },
        created(){
            this.fetchEcog()
        },

        methods: {
            saveEcog() {
                const data = {
                    citapaciente_id:this.datosCita.cita_paciente_id,
                    valor_scala_ecog:this.ecog
                };
                axios.post('/api/historia/saveEcog', data).then(res => {
                    this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                    this.$emit('respuestaComponente')
                })
            },

            fetchEcog() {
                axios.get(`/api/historia/fetchEcog/${this.datosCita.cita_paciente_id}`).then(res => {
                    this.ecog = res.data.valor_scala_ecog
                })
            }
        },
    };

</script>

<style lang="scss" scoped>

</style>
