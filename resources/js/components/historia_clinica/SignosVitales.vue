<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout row wrap>
                <v-flex xs12 sm6 md3>
                    <v-text-field type="number" v-model="signosVitales.saturacion_oxigeno" label="Saturación de Oxígeno pulsioximetría">
                    </v-text-field>
                </v-flex>

                <v-flex xs12 sm6 md3>
                    <v-text-field type="number" v-model="signosVitales.tension_arterial" label="Tensión Arterial"></v-text-field>
                </v-flex>

                <v-flex xs12 sm6 md3>
                    <v-text-field type="number" v-model="signosVitales.frecuencia_respiratoria" label="Frecuencia Respiratoria"></v-text-field>
                </v-flex>

                <v-flex xs12 sm6 md3>
                    <v-text-field type="number" v-model="signosVitales.frecuencia_cardiaca" label="Frecuencia Cardiaca"></v-text-field>
                </v-flex>

                <v-flex xs12 sm6 md3>
                    <v-text-field type="number" v-model="signosVitales.pulsos" label="Pulsos"></v-text-field>
                </v-flex>
            </v-layout>
        </v-container>
    </v-form>
</template>
<script>
    export default {
        name: "",
        data() {
            return {
                signosVitales: {}
            }
        },
    }

</script>
