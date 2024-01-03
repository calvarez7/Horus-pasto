<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-stepper v-model="e6" vertical>
                <v-stepper-step :complete="e6 > 1" editable :edit-icon="$vuetify.icons.complete" step="1">
                    MC Y EA
                    <small>Motivo de consulta y enfermedad actual ENFERMERIA</small>
                </v-stepper-step>

                <v-stepper-content step="1">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-switch color="primary" v-model="motivo.ingesta" label="Ingesta adecuada">
                                    </v-switch>
                                    <v-flex md12>
                                        <v-textarea v-show="motivo.ingesta" name="input-7-1" label="Ingresa Observación" value=""
                                            v-model="motivo.ingestaAdecuada"></v-textarea>
                                    </v-flex>
                                </v-flex>
                                <v-flex xs12>
                                    <v-switch color="primary" v-model="motivo.diuresis"
                                        label="Diuresis adecuada"></v-switch>
                                    <v-flex md12>
                                        <v-textarea v-show="motivo.diuresis" name="input-7-1" label="Ingresa Observación" value=""
                                            v-model="motivo.Diuresis"></v-textarea>
                                    </v-flex>
                                </v-flex>
                                <v-flex xs12>
                                    <v-switch color="primary" v-model="motivo.deposicionAdecuada"
                                        label="Deposición adecuada"></v-switch>
                                    <v-flex md12>
                                        <v-textarea v-show="motivo.deposicionAdecuada" name="input-7-1" label="Ingresa Observación" value=""
                                            v-model="motivo.deposicion"></v-textarea>
                                    </v-flex>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card>
                    <v-btn color="primary" round @click="guardarMotivoConsulta()">Guardar y seguir</v-btn>
                </v-stepper-content>
                <template>
                    <div class="text-center">
                        <v-dialog v-model="preload_motivoOncologico" persistent width="300">
                            <v-card color="primary" dark>
                                <v-card-text>
                                    Tranquilo procesamos tu solicitud !
                                    <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                                </v-card-text>
                            </v-card>
                        </v-dialog>
                    </div>
                </template>
            </v-stepper>
        </v-flex>
    </v-layout>
</template>
<script>
    import Swal from 'sweetalert2';
    import {
        EventBus
    } from '../../../../event-bus.js';

    export default {
        created() {
            this.removeLocalStorage();
            this.citaPaciente = this.$route.query.cita_paciente;
            this.getLocalStorage();
        },
        data() {
            return {
                preload_motivoOncologico: false,
                e6: 1,
                motivo: {
                    Tipocita_id: 10,
                    Motivoconsulta: '',
                    Enfermedadactual: '',
                    ingestaAdecuada: '',
                    Diuresis: '',
                    deposicion: '',
                },
                citaPaciente: 0,

            }
        },

        mounted() {

            // this.fetchAnamnesis();
        },

        methods: {

            fetchAnamnesis() {
                axios.get('/api/cita/' + this.citaPaciente + '/motivo')
                    .then(res => {
                        console.log('anamnesis', res.data);
                        this.motivo = {
                            ...res.data,
                            Tipocita_id: 10
                        };
                    });
            },

            guardarMotivoConsulta() {
                this.preload_motivoOncologico = true;
                axios.post('/api/cita/' + this.citaPaciente + '/motivo', this.motivo).then((res) => {
                    this.preload_motivoOncologico = false;
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
                    this.e6 = 2;
                    localStorage.setItem("MotivoConsulta", JSON.stringify(this.motivo));
                    this.$router.push('/historias/historia_enfermeria/patologias?cita_paciente=' + this.citaPaciente);
                    //EventBus.$emit('change_disabled_list_history','ANTECEDENTES')
                });

            },
            getLocalStorage() {
                let motivo = JSON.parse(localStorage.getItem("MotivoConsulta"));
                if (motivo) {
                    this.motivo = motivo;
                } else {
                    this.fetchAnamnesis();
                }
            },
            removeLocalStorage() {
                localStorage.removeItem("Gineco");
                localStorage.removeItem("Diagnostico");
                localStorage.removeItem("ExamenSistema");
                localStorage.removeItem("MotivoConsulta");
                localStorage.removeItem("AntecedentesPersonales");
                localStorage.removeItem("AntecedentesFamiliares");
                localStorage.removeItem("rcvm");
                localStorage.removeItem("EstiloVida");
                localStorage.removeItem("Conducta");
            }
        }
    }

</script>
<style scoped>

</style>
