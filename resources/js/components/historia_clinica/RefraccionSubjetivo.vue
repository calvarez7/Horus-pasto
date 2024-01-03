<template>
    <div>
        <v-form>
            <v-container grid-list-md fluid class="pa-0">
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Queratometria </span>
                </v-card-title>
                <v-layout wrap align-center>
                    <v-flex xs2 sm6>
                        <v-text-field v-model="optometria.queratometria_od" label="OD"></v-text-field>
                    </v-flex>
                    <v-flex xs2 sm6>
                        <v-text-field v-model="optometria.queratometria_oi" label="OI"></v-text-field>
                    </v-flex>
                </v-layout>
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Refraccion</span>
                </v-card-title>
                <v-layout wrap align-center>
                    <v-flex xs2 sm6>
                        <v-text-field v-model="optometria.refraccion_od" label="OD"></v-text-field>
                    </v-flex>
                    <v-flex xs2 sm6>
                        <v-text-field v-model="optometria.refraccionAv_od" label="AV"></v-text-field>
                    </v-flex>

                    <v-flex xs2 sm6>
                        <v-text-field v-model="optometria.refraccion_oi" label="OI"></v-text-field>
                    </v-flex>
                    <v-flex xs2 sm6>
                        <v-text-field v-model="optometria.refraccionAv_oi" label="AV"></v-text-field>
                    </v-flex>
                </v-layout>
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Subjetivo</span>
                </v-card-title>
                <v-layout wrap align-center>
                    <v-flex xs2 sm6>
                        <v-text-field v-model="optometria.subjetivo_od" label="OD"></v-text-field>
                    </v-flex>
                    <v-flex xs2 sm6>
                        <v-text-field v-model="optometria.subjetivoAv_od" label="AV"></v-text-field>
                    </v-flex>

                    <v-flex xs2 sm6>
                        <v-text-field v-model="optometria.subjetivo_oi" label="OI"></v-text-field>
                    </v-flex>
                    <v-flex xs2 sm6>
                        <v-text-field v-model="optometria.subjetivoAv_oi" label="AV"></v-text-field>
                    </v-flex>
                </v-layout>
                <v-btn color="success" round @click="saveRefraccion()">Guardar y seguir</v-btn>
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
            this.fetchsRefraccion();
        },
        data() {
            return {
                preloadHistoria : false,
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
            saveRefraccion() {
                this.preloadHistoria = true
                this.optometria.citapaciente_id = this.datosCita.cita_paciente_id
                axios.post('/api/historia/saveRefraccion', this.optometria)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.$emit('respuestaComponente')
                        this.preloadHistoria = false
                    })
            },
            fetchsRefraccion() {
                this.preloadHistoria = true
                axios.get(`/api/historia/fetchsRefraccion/${this.datosCita.cita_paciente_id}`)
                    .then(res => {
                        this.optometria = res.data;
                        this.preloadHistoria = false 
                    });
            }
        }
    }

</script>
