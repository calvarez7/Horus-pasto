<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout row wrap>
                <v-flex xs12 sm6 md3>
                    <v-text-field v-model="desarrollo.rendimiento_escolar" label="Rendimiento escolar ">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-text-field v-model="desarrollo.actitud_aprendizaje" label="Aptitud de aprendizaje">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-text-field v-model="desarrollo.actitud_aula" label="Actitud en el aula">
                    </v-text-field>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-text-field v-model="desarrollo.relacionamiento_companeros"
                        label="Relacionamiento con compañeros y docentes"></v-text-field>
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
                desarrollo: {}
            }
        },
        methods: {
            saveDesarrolloAprendisaje() {
                this.desarrollo.cita_paciente_id = this.datosCita.cita_paciente_id
                axios.post('/api/historia/saveDesarrolloAprendisaje', this.desarrollo)
                    .then(res => {
                        this.$alertHistoria(res.data.message);
                    })
            },
            fetchDesarrolloAprendisaje() {
                axios.get(`/api/historia/fetchDesarrolloAprendisaje/${this.datosCita.cita_paciente_id}`)
                    .then(res => {
                        this.desarrollo = res.data;
                    });
            },
        }
    }

</script>
