<template>
    <div>
        <v-form>
            <v-container grid-list-md fluid class="pa-0">
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Lensometria</span>
                </v-card-title>
                <v-container grid-list-xs>
                    <v-layout row wrap>
                        <v-container fluid grid-list-xl>
                            <v-layout wrap align-center>
                                <v-flex xs2>
                                    <v-text-field v-model="optometria.lateralidad_od" label="OD">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field v-model="optometria.esf_od" label="ESF"></v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field v-model="optometria.cyl_od" label="CYL"></v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field v-model="optometria.eje_od" label="EJE"></v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field v-model="optometria.add_od" label="ADD"></v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field v-model="optometria.lateralidad_oi" label="OI">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field v-model="optometria.esf_oi" label="ESF"></v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field v-model="optometria.cyl_oi" label="CYL"></v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field v-model="optometria.eje_oi" label="EJE"></v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field v-model="optometria.add_oi" label="ADD"></v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-layout>
                </v-container>
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Agudeza Visual</span>
                </v-card-title>
                <v-layout wrap align-center>
                    <v-flex xs12 sm4>
                    </v-flex>
                    <v-flex xs12 sm2>
                        <v-checkbox color="success" v-model="optometria.checkboxSC" value="1" label="SC"></v-checkbox>
                    </v-flex>
                    <v-flex xs12 sm2>
                        <v-checkbox color="success" v-model="optometria.checkboxCC" value="1" label="CC"></v-checkbox>
                    </v-flex>
                    <v-flex xs12>
                        <h3><span class="title layout justify-center font-weight-light">VL</span></h3>
                    </v-flex>
                    <v-flex xs2 sm6>
                        <v-text-field v-model="optometria.agudeza_od" label="OD"></v-text-field>
                    </v-flex>
                    <v-flex xs2 sm6>
                        <v-text-field v-model="optometria.agudezavp_od" label="VP"></v-text-field>
                    </v-flex>

                    <v-flex xs2 sm6>
                        <v-text-field v-model="optometria.agudeza_oi" label="OI"></v-text-field>
                    </v-flex>
                    <v-flex xs2 sm6>
                        <v-text-field v-model="optometria.agudezavp_oi" label="VP"></v-text-field>
                    </v-flex>
                </v-layout>
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Motilidad Ocular</span>
                </v-card-title>
                <v-layout wrap align-center>
                    <v-flex xs2>
                        <h3>COVER TEST</h3>
                    </v-flex>
                    <v-flex xs3>
                        <v-text-field v-model="optometria.ocular_vl" label="VL"></v-text-field>
                    </v-flex>
                    <v-flex xs3>
                        <v-text-field v-model="optometria.ocular_vp" label="VP"></v-text-field>
                    </v-flex>
                    <v-flex xs3>
                        <v-text-field type="number" v-model="optometria.ocular_ppc" label="PPC"></v-text-field>
                    </v-flex>
                </v-layout>
                <v-btn color="success" round @click="saveOptometria()">Guardar y seguir</v-btn>
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
            this.fetchOptometria();
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
            saveOptometria() {
                this.preloadHistoria = true
                this.optometria.citapaciente_id = this.datosCita.cita_paciente_id
                axios.post('/api/historia/saveOptometria', this.optometria)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.$emit('respuestaComponente')
                        this.preloadHistoria = false
                    })
            },
            fetchOptometria() {
                this.preloadHistoria = true
                axios.get(`/api/historia/fetchOptometria/${this.datosCita.cita_paciente_id}`)
                    .then(res => {
                        this.optometria = res.data;
                        this.preloadHistoria = false
                    });
            }
        }
    }

</script>
