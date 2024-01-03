<template>
    <v-layout row wrap>
        <v-flex xs12>
            <v-stepper v-model="e6" vertical>
                <v-stepper-step :complete="e6 > 1" editable :edit-icon="$vuetify.icons.complete" step="1">
                    MC Y EA
                    <small>Motivo de consulta y enfermedad actual</small>
                </v-stepper-step>

                <v-stepper-content step="1">
                    <v-card color="lighten-1" class="mb-5" height="auto">
                        <v-container grid-list-xs>
                            <v-textarea name="input-7-1" label="Motivo de Consulta" value=""
                                v-model="motivo.Motivoconsulta"></v-textarea>
                            <v-textarea name="input-7-1" label="Enfermedad Actual" value=""
                                v-model="motivo.Enfermedadactual"></v-textarea>
                            <v-textarea name="input-7-1" label="Tratamiento Oncologico" value=""
                                v-model="motivo.tratamientoncologico"></v-textarea>
                            <v-layout row wrap>
                                <v-flex xs12 sm4 md4>
                                    <v-switch color="primary" v-model="motivo.cirujia" label="Cirugía oncológica"></v-switch>
                                    <v-flex md3>
                                        <v-text-field v-show="motivo.cirujia" v-model="motivo.ncirujias" label="cuantas: ">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex md4>
                                        <v-text-field v-show="motivo.cirujia" v-model="motivo.iniciocirujia"
                                            label="Fecha de inicio: " type="date"></v-text-field>
                                    </v-flex>
                                    <v-flex md4>
                                        <v-text-field v-show="motivo.cirujia" v-model="motivo.fincirujia"
                                            label="Fecha de finalizacion: " type="date"></v-text-field>
                                    </v-flex>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-switch color="primary" v-model="motivo.radioterapia" label="Recibio radioterapia"></v-switch>
                                    <v-flex md4>
                                        <v-text-field v-show="motivo.radioterapia" v-model="motivo.nsesiones"
                                            label="Número Sesiones: ">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex md4>
                                        <v-text-field v-show="motivo.radioterapia" v-model="motivo.inicioradioterapia"
                                            label="Fecha de inicio: " type="date"></v-text-field>
                                    </v-flex>
                                    <v-flex md4>
                                        <v-text-field v-show="motivo.radioterapia" v-model="motivo.finradioterapia"
                                            label="Fecha de finalizacion: " type="date"></v-text-field>
                                    </v-flex>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-select :items="intencion" v-model="motivo.intencion" label="Intencion del tratamiento oncológico inicial"></v-select>
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
                <v-stepper-step :complete="e6 > 2" editable :edit-icon="$vuetify.icons.complete" step="2">Revisión por
                    sistema</v-stepper-step>

                <v-stepper-content step="2">
                    <v-card color="lighten-1" class="mb-5" height="auto">

                        <v-container fluid>
                            <v-layout row>
                                <v-flex grow pa-1>
                                    <v-card color="lighten-1" class="mb-5" height="auto">
                                        <v-card-text>
                                            <v-flex xs12>
                                                <v-layout row wrap>
                                                    <v-switch color="primary" v-model="motivo.a" label="Oftalmológico">
                                                    </v-switch>
                                                </v-layout>
                                                <v-text-field label="Oftalmológico" v-show="motivo.a"
                                                    v-model="motivo.Oftalmologico">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-switch color="primary" v-model="motivo.b" label="Genitourinario"
                                                    :value="motivo.b"></v-switch>
                                                <v-text-field label="Genitourinario" v-show="motivo.b"
                                                    v-model="motivo.Genitourinario">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-switch color="primary" v-model="motivo.c"
                                                    label="Otorrinolaringológico"></v-switch>
                                                <v-text-field label="Otorrinolaringológico" v-show="motivo.c"
                                                    v-model="motivo.Otorrinoralingologico">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-switch color="primary" v-model="motivo.d" label="Linfático"></v-switch>
                                                <v-text-field label="Linfático" v-show="motivo.d"
                                                    v-model="motivo.Linfatico">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-switch color="primary" v-model="motivo.f" label="Osteomioarticular">
                                                </v-switch>
                                                <v-text-field label="Osteomioarticular" v-show="motivo.f"
                                                    v-model="motivo.Osteomioarticular">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-switch color="primary" v-model="motivo.g" label="Neurológico">
                                                </v-switch>
                                                <v-text-field label="Neurológico" v-show="motivo.g"
                                                    v-model="motivo.Neurologico">
                                                </v-text-field>
                                            </v-flex>
                                        </v-card-text>
                                    </v-card>
                                </v-flex>
                                <v-flex grow pa-1>
                                    <v-card color="lighten-1" class="mb-5" height="auto">
                                        <v-card-text>
                                            <v-flex xs12>
                                                <span></span>
                                                <v-switch color="primary" v-model="motivo.h" label="Cardiovascular:">
                                                </v-switch>
                                                <v-text-field label="Cardiovascular" v-show="motivo.h"
                                                    v-model="motivo.Cardiovascular">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-switch color="primary" v-model="motivo.i" label="Tegumentario">
                                                </v-switch>
                                                <v-text-field label="Tegumentario" v-show="motivo.i"
                                                    v-model="motivo.Tegumentario">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-switch color="primary" v-model="motivo.j" label="Respiratorio">
                                                </v-switch>
                                                <v-text-field label="Respiratorio" v-show="motivo.j"
                                                    v-model="motivo.Respiratorio">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-switch color="primary" v-model="motivo.k" label="Endocrinológico">
                                                </v-switch>
                                                <v-text-field label="Endocrinológico" v-show="motivo.k"
                                                    v-model="motivo.Endocrinologico">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-switch color="primary" v-model="motivo.l" label="Gastrointestinal:">
                                                </v-switch>
                                                <v-text-field label="Gastrointestinal" v-show="motivo.l"
                                                    v-model="motivo.Gastrointestinal">
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12>
                                                <v-switch color="primary" v-model="motivo.m" label="Otros"></v-switch>
                                                <v-text-field label="Otros" v-show="motivo.m" v-model="motivo.Norefiere">
                                                </v-text-field>
                                            </v-flex>
                                        </v-card-text>
                                    </v-card>
                                </v-flex>
                            </v-layout>
                        </v-container>
                        <v-btn color="primary" round @click="guardarRevisionsisitema()">Guardar</v-btn>
                    </v-card>
                </v-stepper-content>
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
                    Tipocita_id: 7,
                    Motivoconsulta: '',
                    Enfermedadactual: '',
                    tratamientoncologico: '',
                    ncirujias: '',
                    iniciocirujia: '',
                    fincirujia: '',
                    inicioradioterapia: '',
                    finradioterapia: '',
                    nsesiones: '',
                    intencion: '',
                    Oftalmologico: '',
                    Genitourinario: '',
                    Otorrinoralingologico: '',
                    Linfatico: '',
                    Osteomioarticular: '',
                    Neurologico: '',
                    Cardiovascular: '',
                    Tegumentario: '',
                    Respiratorio: '',
                    Endocrinologico: '',
                    Gastrointestinal: '',
                    Norefiere: '',
                },
                citaPaciente: 0,
                intencion: ['Curación', 'Paliación']

            }
        },

        mounted() {

            // this.fetchAnamnesis();
        },

        methods: {

            fetchAnamnesis() {
                axios.get('/api/cita/' + this.citaPaciente + '/motivo')
                    .then(res => {
                        this.motivo = {
                            ...res.data,
                            Tipocita_id: 7
                        } ;
                    });
            },

            guardarMotivoConsulta() {
                // console.log(this.data)
                if (!this.motivo.Motivoconsulta) {
                    swal("Motivo consulta es REQUERIDO y debe ser mayor a 5 caracteres")
                    return;
                } else if (this.motivo.Motivoconsulta.length < 5) {
                    swal("Motivo consulta debe ser mayor a 5 caracteres")
                    return;
                }

                if (!this.motivo.Enfermedadactual) {
                    swal("Enfermedad actual es REQUERIDA y debe ser mayor a 20 caracteres")
                    return;
                } else if (this.motivo.Enfermedadactual.length < 20) {
                    swal("Enfermedad actual debe ser mayor a 20 caracteres")
                    return;
                }
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
                    //EventBus.$emit('change_disabled_list_history','ANTECEDENTES')
                });

            },
            guardarRevisionsisitema() {
                this.preload_motivoOncologico = true;
                axios.post('/api/cita/' + this.citaPaciente + '/revisionsistema', this.motivo).then((res) => {
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
                    this.e6 = 3;
                    localStorage.setItem("MotivoConsulta", JSON.stringify(this.motivo));
                    this.$router.push('/historias/historia_oncologica/descripcionPatologia?cita_paciente=' + this.citaPaciente);

                    // EventBus.$emit('change_disabled_list_history','ANTECEDENTES')
                })
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
