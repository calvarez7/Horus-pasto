<template>
    <div>
        <v-form>
            <v-container grid-list-md fluid class="pa-0">
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Biomicroscopia</span>
                </v-card-title>
                <v-layout wrap align-center>
                    <v-flex xs12>
                        <v-textarea name="input-7-1" v-model="optometria.biomicroscopiaOd" label="OD"></v-textarea>
                    </v-flex>
                    <v-flex xs12>
                        <v-textarea name="input-7-1" v-model="optometria.biomicroscopiaOi" label="OI"></v-textarea>
                    </v-flex>
                </v-layout>
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">PIO</span>
                </v-card-title>
                <v-layout wrap align-center>
                    <v-flex xs6>
                        <v-text-field v-model="optometria.pioOd" label="OD"></v-text-field>
                    </v-flex>
                    <v-flex xs6>
                        <v-text-field v-model="optometria.pioOi" label="OI"></v-text-field>
                    </v-flex>
                </v-layout>
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Oftalmoscopia</span>
                </v-card-title>
                <v-layout wrap align-center>
                    <v-flex xs12>
                        <v-textarea name="input-7-1" v-model="optometria.oftalmoscopiaOd" label="OD"></v-textarea>
                    </v-flex>
                    <v-flex xs12>
                        <v-textarea name="input-7-1" v-model="optometria.oftalmoscopiaOi" label="OI"></v-textarea>
                    </v-flex>
                </v-layout>
                <v-btn color="success" round @click="savebiooftalmoscopia()">Guardar y seguir</v-btn>
            </v-container>
        </v-form>
        <v-dialog v-model="preloadHistoria" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Estamos procesando su información
                    <v-progress-linear indeterminate color="white" class="mb-0">
                    </v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>
<script>
    export default {
        name: "",
        props: {
            datosCita: Object
        },
        created() {
            this.fetchsbiooftalmoscopia();
        },
        data() {
            return {
                preloadHistoria: false,
                optometria: {},
                lensometria: ['OD', 'OI'],
                optometriaItems: [],
                headerOptometria: [{
                        text: 'Lateralidad',
                    },
                    {
                        text: 'ESF',
                    },
                    {
                        text: 'CYL',
                    },
                    {
                        text: 'EJE',
                    },
                    {
                        text: 'ADD',
                    }
                ]
            }
        },
        methods: {
            savebiooftalmoscopia() {
                this.preloadHistoria = true
                this.optometria.citapaciente_id = this.datosCita.cita_paciente_id
                axios.post('/api/historia/savebiooftalmoscopia', this.optometria)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.$emit('respuestaComponente')
                        this.preloadHistoria = false
                    })
            },
            fetchsbiooftalmoscopia() {
                this.preloadHistoria = true
                axios.get(`/api/historia/fetchsbiooftalmoscopia/${this.datosCita.cita_paciente_id}`)
                    .then(res => {
                        this.optometria = res.data;
                        this.preloadHistoria = false
                    });
            }
        }
    }

</script>
