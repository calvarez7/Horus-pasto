<template>
    <v-layout row wrap>
        <v-flex xs12>
            <template>
                <div class="text-center">
                    <v-dialog v-model="preload_DesPatologia" persistent width="300">
                        <v-card color="primary" dark>
                            <v-card-text>
                                Tranquilo procesamos tu solicitud !
                                <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                </div>
            </template>
            <v-card color="lighten-1" class="mb-5" height="auto">
                <v-container grid-list-xs>
                    <v-card-title class="headline" style="color:white;background-color:#0074a6">
                        <span class="title layout justify-center font-weight-light">Descripción de la Patología</span>
                    </v-card-title>
                    <v-layout row wrap>

                        <v-flex xs12 sm6 md12>
                            <v-textarea label="Descripción Patología Cáncer Actual"
                                v-model="datos.Patologiacancelactual"></v-textarea>
                        </v-flex>

                        <v-flex xs12 sm6 md4>
                            <v-text-field label="Fecha recolección muestra Dx cáncer actua"
                                v-model="datos.fdxcanceractual" type="date"></v-text-field>
                        </v-flex>

                        <v-flex xs12 sm6 md3>
                            <v-text-field label="fecha reporte laboratorio patología"
                                v-model="datos.flaboratoriopatologia" type="date"></v-text-field>
                        </v-flex>

                        <v-flex xs12 sm6 md5>
                            <v-select :items="tumor" label="Diferenciación tumor sólido maligno según biopsia"
                                v-model="datos.tumorsegunbiopsia"></v-select>
                        </v-flex>

                    </v-layout>
                </v-container>
            </v-card>
            <v-card color="lighten-1" class="mb-5" height="auto">
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Localizacion Cancer</span>
                </v-card-title>
                <v-container grid-list-xs>
                    <v-layout row wrap>
                        <v-flex xs12 sm6 md3>
                            <v-select :items="localizacion" label="Localización del cáncer"
                                v-model="datos.localizacioncancer"></v-select>
                        </v-flex>

                        <v-flex xs12 sm6 md3 v-if="datos.localizacioncancer === 'Colorrectal'">
                            <v-text-field value="Dukes" label="clasificación según tipo de cáncer" readonly>
                            </v-text-field>
                        </v-flex>

                        <v-flex xs12 sm6 md3 v-if="datos.localizacioncancer === 'Colorrectal'">
                            <v-select :items="valores" label="Valores de la lista" v-model="datos.Dukes">
                            </v-select>
                        </v-flex>

                        <v-flex xs12 sm6 md3 v-if="datos.localizacioncancer === 'Próstata'">
                            <v-text-field value="Gleason" label="clasificación según tipo de cáncer" readonly>
                            </v-text-field>
                        </v-flex>

                        <v-flex xs12 sm6 md2 v-if="datos.localizacioncancer === 'Próstata'">
                            <v-text-field label="Valores de la lista" v-model="datos.gleason"></v-text-field>
                        </v-flex>

                        <v-flex xs12 sm6 md3 v-if="datos.localizacioncancer === 'Mama'">
                            <v-text-field value="Her 2" label="clasificación según tipo de cáncer" readonly>
                            </v-text-field>
                        </v-flex>

                        <v-flex xs12 sm6 md3 v-if="datos.localizacioncancer === 'Mama'">
                            <v-select :items="Her2" label="Valores de la lista" v-model="datos.Her2"></v-select>
                        </v-flex>

                        <v-flex xs12 sm6 md3 v-if="datos.localizacioncancer === 'Linfomas Hodgkin/ no Hoodgkin'">
                            <v-text-field value="Estadificación clínica" label="clasificación según tipo de cáncer"
                                readonly></v-text-field>
                        </v-flex>

                        <v-flex xs12 sm6 md3 v-if="datos.localizacioncancer === 'Linfomas Hodgkin/ no Hoodgkin'">
                            <v-select :items="Estadificaciónclinica" label="Valores de la lista"
                                v-model="datos.Estadificaciónclinica"></v-select>
                        </v-flex>

                    </v-layout>
                </v-container>
            </v-card>
            <v-card color="lighten-1" class="mb-5" height="auto">
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Estadificación</span>
                </v-card-title>
                <v-container grid-list-xs>
                    <v-textarea name="input-7-1" label="Estadificación inicial por TNM"
                        v-model="datos.estadificacioninicial">
                    </v-textarea>
                    <v-text-field label="Fecha de esta Estadificación " v-model="datos.fechaestadificacion" type="date">
                    </v-text-field>
                </v-container>
                <v-btn color="primary" round @click="guardarPatologiaConsulta()">Guardar y seguir</v-btn>
            </v-card>
        </v-flex>
    </v-layout>
</template>
<script>
    export default {
        name: "MotivoConsulta",
        props: {
            datosCita: Object
        },
        created() {
            this.fetchPatologia();
        },
        data() {
            return {
                preload_DesPatologia: false,
                datos: {
                    Patologiacancelactual: '',
                    fdxcanceractual: '',
                    flaboratoriopatologia: '',
                    tumorsegunbiopsia: '',
                    localizacioncancer: '',
                    Dukes: '',
                    gleason: '',
                    Her2: '',
                    Estadificaciónclinica: '',
                    estadificacioninicial: '',
                    fechaestadificacion: '',
                },
                tumor: ['Bien diferenciado (grado 1)', 'Moderadamente diferenciado (grado 2)',
                    'Mal diferenciado (grado 3)', 'Anaplásico o indiferenciado (grado 4)', 'No aplica'
                ],
                localizacion: ['Colorrectal', 'Próstata', 'Mama', 'Linfomas Hodgkin/ no Hoodgkin', 'No aplica'],
                valores: ['A', 'B', 'C', 'D'],
                Her2: ['+++ positivo', '++ equívoco o indeterminado', '+ negativo', 'cero o negativo'],
                Estadificaciónclinica: ['Estado (etapa) I', 'Estado (etapa) II', 'Estado (etapa) III',
                    'Estado (etapa) IV'
                ]

            }
        },
        methods: {

            fetchPatologia() {
                axios.get('/api/patologia/' + this.datosCita.cita_paciente_id + '/datospatologia')
                    .then(res => {
                        this.datos = res.data;
                    });
            },

            guardarPatologiaConsulta() {
                if (!this.datos.Patologiacancelactual) {
                    swal("Descripción Patología Cáncer Actual es REQUERIDO y debe ser mayor a 5 caracteres")
                    return;
                } else if (this.datos.Patologiacancelactual.length < 5) {
                    swal("Descripción Patología Cáncer Actual debe ser mayor de 5 caracteres")
                    return;
                }
                this.preload_DesPatologia = true;
                axios.post('/api/patologia/' + this.datosCita.cita_paciente_id + '/create', this.datos).then((res) => {
                    this.preload_DesPatologia = false
                    this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                    this.$emit('respuestaComponente')
                }).catch(err => {
                    this.setError(err.response.data)
                })

            },

        }
    }

</script>
<style scoped>

</style>
