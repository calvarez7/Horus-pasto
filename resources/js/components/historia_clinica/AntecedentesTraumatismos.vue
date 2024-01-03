<template>
    <v-form>
        <v-container grid-list-md fluid class="pa-0">
            <v-layout row wrap>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="traumatismo.traumatismos" value="1"
                        label="Traumatismos"></v-checkbox>
                </v-flex>
                <v-flex xs12 sm6 md3>
                    <v-checkbox color="success" v-model="traumatismo.accidentes" value="1"
                        label="Accidentes"></v-checkbox>
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
                traumatismo: {}
            }
        },
        methods: {
            saveAnteTraumatismo() {
                this.traumatismo.cita_paciente_id = this.datosCita.cita_paciente_id;
                axios.post('/api/historia/saveAnteTraumatismo', this.traumatismo)
                    .then(res => {
                        this.$alertHistoria(res.data.message);
                    })
            },
            fetchAnteTraumatismo() {
                axios.get(`/api/historia/fetchAnteTraumatismo/${this.datosCita.cita_paciente_id}`)
                    .then(res => {
                        this.traumatismo = res.data;
                    });
            },
        }
    }

</script>
