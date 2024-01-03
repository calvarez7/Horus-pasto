<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout row wrap>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="rutina.duerme_bien" value="1"
                        label="Duerme bien"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="rutina.cuantas_horas_duerme" value="1"
                        label="Cuántas horas duerme?:"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="rutina.tiempo_minutos_dia_juego" value="1"
                        label="Tiempo en minutos suma al día de juego"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="rutina.practica_actividad_fisica" value="1"
                        label="¿Usted practica al menos 150 minutos semanales (aproximadamente dos horas) de actividad física moderada o al menos 75 minutos semanales de actividad física intensa? "></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="rutina.cuantas_veces_bania_dia" value="1"
                        label="Cuantas veces se baña en el día "></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="rutina.cuidado_bucal_adecuado" value="1"
                        label="Cuidado bucal adecuado"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="rutina.control_esfinter_vesical" value="1"
                        label="Control esfínter vesical"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="rutina.frecuencia_caracteristicas_orina" value="1"
                        label="Frecuencia y características de la orina:?"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="rutina.control_esfinter_rectal" value="1"
                        label="Control esfínter Rectal"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="rutina.frecuencia_caracteristicas_deposiciones" value="1"
                        label="Frecuencia y características deposiciones:?"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="rutina.exposicion_tV_videojuegos_internet" value="1"
                        label="Exposición a TV, videojuegos e internet"></v-checkbox>
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
        data() {
            return {
                rutina: {}
            }
        },
        methods: {
            saveRutinaHabito() {
                this.rutina.cita_paciente_id = this.datosCita.cita_paciente_id;
                axios.post('/api/historia/saveRutinaHabito', this.rutina)
                    .then(res => {
                        this.$alertHistoria(res.data.message);
                    })
            },
            fetchRutinaHabito() {
                axios.get(`/api/historia/fetchRutinaHabito/${this.datosCita.cita_paciente_id}`)
                    .then(res => {
                        this.rutina = res.data;
                    });
            },
        }
    }

</script>
