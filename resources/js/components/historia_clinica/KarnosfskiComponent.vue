<template>
    <div>
        <v-form>
            <v-container grid-list-md fluid class="pa-0">
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="justify-center title layout font-weight-light">ESCALA DE VALORACIÓN FUNCIONAL: ÍNDICE
                        DE KARNOFSKI: <span><b>{{valorKarnofski}}</b></span></span>
                </v-card-title>
                <v-layout row wrap>
                    <v-flex sm12 md12 sd12>
                    <v-radio-group v-model="valorKarnofski" color="primary">
                        <v-radio color="primary" v-for="opcion in opciones" :key="opcion.valor" :label="opcion.nombre" :value="opcion.valor"></v-radio>
                    </v-radio-group>
                    </v-flex>
                    <v-btn color="success" round @click="saveKarnofski()">Guardar y seguir</v-btn>
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
                opciones: [{
                        nombre: "Normal",
                        valor: 100
                    },
                    {
                        nombre: "Actividades normales con síntomas leves de la enfermedad",
                        valor: 90
                    },
                    {
                        nombre: "Actividad normal con esfuerzo, con algunos signos y síntomas de la enfermedad",
                        valor: 80
                    },
                    {
                        nombre: "Capaz de cuidarse, pero incapaz de llevar a término actividades normales o trabajo activo",
                        valor: 70
                    },
                    {
                        nombre: "Requiere atención ocasional, pero puede cuidarse a sí mismo",
                        valor: 60
                    },
                    {
                        nombre: "Requiere gran atención incluso de tipo médico. En cama menos del 50% del día",
                        valor: 50
                    },
                    {
                        nombre: "Inválido, incapacitado, necesita cuidados y atenciones especiales. En cama más del 50% del día",
                        valor: 40
                    },
                    {
                        nombre: "Inválido grave, severamente incapacitado, tratamiento de soporte activo",
                        valor: 30
                    },
                    {
                        nombre: "Todo el tiempo en cama, paciente muy grave, necesita hospitalización y tratamiento activo",
                        valor: 20
                    },
                    {
                        nombre: "En agonía",
                        valor: 10
                    },
                    {
                        nombre: "Fallecido",
                        valor: 0
                    },

                ],
                valorKarnofski: null

            };
        },

        created() {
            this.fetchKarnofski();
        },

        methods: {

            saveKarnofski() {
                const data = {
                    citapaciente_id:this.datosCita.cita_paciente_id,
                    valor_scala_karnofski:this.valorKarnofski
                };
                axios.post('/api/historia/saveKarnofski', data).then(res => {
                    this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                    this.$emit('respuestaComponente')
                })
            },

            fetchKarnofski() {
                axios.get(`/api/historia/fetchKarnofski/${this.datosCita.cita_paciente_id}`).then(res => {
                    this.valorKarnofski = res.data.valor_scala_karnofski
                })
            }
        },
    };

</script>

<style lang="scss" scoped>

</style>
