<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-stepper v-model="e6" vertical>
                <v-stepper-step :complete="e6 > 1" editable :edit-icon="$vuetify.icons.complete" step="1">
                    Descripción de la Patología
                    <small>Patología</small>
                </v-stepper-step>

                <v-stepper-content step="1">
                    <!-- Notificacion para Guardar Descripción de la Patología -->
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
                    <!-- Notificacion para Guardar Descripción de la Patología -->
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
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
                    <v-btn color="primary" round @click="guardarPatologiaConsulta()">Guardar y seguir</v-btn>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 2" editable :edit-icon="$vuetify.icons.complete" step="2">Tipo Tumor
                </v-stepper-step>

                <v-stepper-content step="2">
                    <v-card color="lighten-1" class="mb-5" height="auto">

                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-flex xs12 sm6 md3>
                                    <v-select :items="localizacion" label="Localización del cáncer"
                                        v-model="datos.localizacioncancer" :bind="datos.localizacioncancer"></v-select>
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

                                <v-flex xs12 sm6 md3
                                    v-if="datos.localizacioncancer === 'Linfomas Hodgkin/ no Hoodgkin'">
                                    <v-text-field value="Estadificación clínica"
                                        label="clasificación según tipo de cáncer" readonly></v-text-field>
                                </v-flex>

                                <v-flex xs12 sm6 md3
                                    v-if="datos.localizacioncancer === 'Linfomas Hodgkin/ no Hoodgkin'">
                                    <v-select :items="Estadificaciónclinica" label="Valores de la lista"
                                        v-model="datos.Estadificaciónclinica"></v-select>
                                </v-flex>

                            </v-layout>
                        </v-container>
                        <v-btn color="primary" round @click="guardarTipotumor()">Guardar</v-btn>
                    </v-card>
                </v-stepper-content>

                <v-stepper-step :complete="e6 > 3" editable :edit-icon="$vuetify.icons.complete" step="3">
                    Estadificación del tumor</v-stepper-step>

                <v-stepper-content step="3">
                    <v-card color="lighten-1" class="mb-5" height="auto">

                        <v-container grid-list-xs>
                            <v-textarea name="input-7-1" label="Estadificación inicial por TNM"
                                v-model="datos.estadificacioninicial">
                            </v-textarea>

                            <v-text-field label="Fecha de esta Estadificación " v-model="datos.fechaestadificacion"
                                type="date">
                            </v-text-field>

                        </v-container>
                        <v-btn color="primary" round @click="guardarEstadificacion()">Guardar</v-btn>
                    </v-card>
                </v-stepper-content>
            </v-stepper>
        </v-flex>
    </v-layout>
</template>
<script>
import Swal from 'sweetalert2';
    import {EventBus} from '../../../../event-bus.js';

    export default {
        created() {
            // this.removeLocalStorage();
            this.citaPaciente = this.$route.query.cita_paciente;
            // this.getLocalStorage();
        },
        data() {
            return {
                e6: 1,
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
                citaPaciente: 0,
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

        mounted() {

            this.fetchPatologia();
        },

        methods: {

            fetchPatologia() {
                axios.get('/api/patologia/' + this.citaPaciente + '/datospatologia')
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
                axios.post('/api/patologia/' + this.citaPaciente + '/create', this.datos).then((res) => {
                    this.preload_DesPatologia = false
                    this.e6 = 2;
                    const Toast = Swal.mixin({
                            toast: true,
                            position: 'center-end',
                            background: '#4caf50',
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: false,
                            onOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })

                        Toast.fire({
                            icon: 'success',
                            title: '<span style="color:#fff">Motivo Guardado con exito<span>'
                        });
                    localStorage.setItem("Patologias", JSON.stringify(this.datos));
                    EventBus.$emit('change_disabled_list_history', 'DESCRIPCIÓN PATOLOGIA')
                });

            },

            guardarTipotumor() {
                this.preload_DesPatologia = true;
                axios.post('/api/patologia/' + this.citaPaciente + '/create', this.datos).then((res) => {
                    this.preload_DesPatologia = false;
                    this.e6 = 3;
                    const Toast = Swal.mixin({
                            toast: true,
                            position: 'center-end',
                            background: '#4caf50',
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: false,
                            onOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })

                        Toast.fire({
                            icon: 'success',
                            title: '<span style="color:#fff">Motivo Guardado con exito<span>'
                        });
                    localStorage.setItem("Patologias", JSON.stringify(this.datos));
                });

            },

            guardarEstadificacion() {
                this.preload_DesPatologia = true;
                axios.post('/api/patologia/' + this.citaPaciente + '/create', this.datos).then((res) => {
                    this.preload_DesPatologia = false;
                    const Toast = Swal.mixin({
                            toast: true,
                            position: 'center-end',
                            background: '#4caf50',
                            showConfirmButton: false,
                            timer: 1000,
                            timerProgressBar: false,
                            onOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })

                        Toast.fire({
                            icon: 'success',
                            title: '<span style="color:#fff">Motivo Guardado con exito<span>'
                        });
                    localStorage.setItem("Patologias", JSON.stringify(this.datos));
                    this.$router.push('/historias/historia_oncologica/patologias?cita_paciente=' + this.citaPaciente);
                });

            },
            getLocalStorage() {
                let patologia = JSON.parse(localStorage.getItem("Patologias"));
                if (patologia) {
                    this.datos = patologia;
                } else {
                    this.fetchPatologia();
                }
            },
            // removeLocalStorage() {
            //     localStorage.removeItem("Gineco");
            //     localStorage.removeItem("Diagnostico");
            //     localStorage.removeItem("ExamenSistema");
            //     localStorage.removeItem("MotivoConsulta");
            //     localStorage.removeItem("AntecedentesPersonales");
            //     localStorage.removeItem("AntecedentesFamiliares");
            //     localStorage.removeItem("rcvm");
            //     localStorage.removeItem("EstiloVida");
            //     localStorage.removeItem("Conducta");
            // }
        }
    }

</script>
<style scoped>

</style>
