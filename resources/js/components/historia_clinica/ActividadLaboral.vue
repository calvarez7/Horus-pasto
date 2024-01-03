<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout row wrap>
                <v-flex xs12 sm6 md3>
                    <v-text-field label="Edad de inicio de su actividad laboral" v-model="actividadLaboral.inicio_actividad_laboral"></v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md9>
                    <v-text-field label="Alteraciones temporales, permanentes o agravadas del estado de salud del joven, ocasionadas por la labor o por la exposición al medio ambiente de trabajo" v-model="actividadLaboral.alteraciones_temporales"></v-text-field>
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
                actividadLaboral: {}
            }
        },
        methods: {
            saveActividadLaboral() {
                this.actividadLaboral.paciente_id = this.datosCita.paciente_id
                axios.post('/api/historia/saveActividadLaboral', this.actividadLaboral)
                    .then(res => {
                        this.$alertHistoria(res.data.message);
                    })
            },
            fetchActividadLaboral() {
                axios.get(`/api/historia/fetchActividadLaboral/${this.datosCita.paciente_id}`)
                    .then(res => {
                        this.actividadLaboral = res.data;
                    });
            },
        }
    }

</script>
