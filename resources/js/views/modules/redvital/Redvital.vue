<template>
    <v-content  id="right">
        <v-container fluid fill-height v-if="!paciente.hasOwnProperty('id')">
            <v-layout align-center justify-left>
                <v-flex xs12 sm8 md5>
                        <v-img src="/images/logoGestion.png" aspect-ratio="2.75" background-size: contain></v-img>
                        <hr style="border: 1px solid #3CB0D1;">
                        <form @submit.prevent="submit">
                            <v-card-text>
                                <v-text-field color="#3CB0D1" prepend-icon="account_box" v-model="data.cedula"
                                    label="Cédula del afiliado" type="number">
                                </v-text-field>
                                <v-text-field prepend-icon="security" v-model="data.password" color="#3CB0D1"
                                    :append-icon="show1 ? 'visibility' : 'visibility_off'"
                                    :type="show1 ? 'text' : 'password'" label="Contraseña"
                                    @click:append="show1 = !show1">
                                </v-text-field>
                                <v-btn flat small color="error" @click="dialog = true">No tengo contraseña o la
                                    olvide
                                </v-btn>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="#3CB0D1" dark type="submit">Ingresar</v-btn>
                            </v-card-actions>
                        </form>
                </v-flex>
            </v-layout>
        </v-container>

        <RedvitalLayout v-else :paciente="paciente">
        </RedvitalLayout>

        <v-dialog v-model="dialog" max-width="500px">
            <v-layout align-center justify-center>
                <v-flex xs12 sm12 md12>
                    <v-card>
                        <v-toolbar dark color="redvitalAzul">
                            <v-toolbar-title>Generar contraseña</v-toolbar-title>
                            <v-spacer></v-spacer>
                        </v-toolbar>
                        <v-card-text>
                            <v-text-field prepend-icon="email" v-model="generarPassword.email_generar"
                                label="Correo electrónico" type="email">
                            </v-text-field>
                            <v-text-field prepend-icon="account_box" v-model="generarPassword.cedula_generar"
                                label="Cédula del afiliado" type="email">
                            </v-text-field>
                            <v-text-field prepend-icon="calendar_today"
                                v-model="generarPassword.fechaNacimiento_generar" label="Fecha de nacimiento"
                                type="date" color="redvitalAzul">
                            </v-text-field>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="redvitalAzul" dark @click="passwordGenerate()">Enviar</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-dialog>

        <v-dialog v-model="preload" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Estamos procesando su información
                    <v-progress-linear indeterminate color="white" class="mb-0">
                    </v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>

    </v-content>
</template>
<script>
    import RedvitalLayout from '../redvital/RedvitalLayout';
    import Vue from 'vue';
    import Swal from 'sweetalert2';
    export default {
        name: 'FormValidate',
        components: {
            RedvitalLayout
        },
        data() {
            return {
                data: {
                    cedula: '',
                    password: ''
                },
                paciente: {},
                show1: false,
                dialog: false,
                preload: false,
                generarPassword: {
                    email_generar: '',
                    cedula_generar: '',
                    fechaNacimiento_generar: ''
                }
            }
        },
        methods: {
            submit() {
                this.validacionPaciente();
            },
            validacionPaciente() {
                if ((this.data.cedula == "") || (this.data.password == "")) {
                    Swal.fire({
                        icon: 'error',
                        title: "¡No puede ser!",
                        text: 'Debe llenar los campos obligatorios!',
                    })
                    return
                }

                axios.post('/api/redvital/validacionPaciente', this.data)
                    .then((res) => {
                        this.paciente = res.data
                    })
                    .catch((err) => this.showError(err.response.data.message));
            },
            showError: (message) => {
                Swal.fire({
                    icon: 'warning',
                    title: "¡No puede ser!",
                    text: `${message}`,
                })
            },
            passwordGenerate() {
                if (this.generarPassword.fechaNacimiento_generar == '' || this.generarPassword.email_generar == '' ||
                    this.generarPassword.cedula_generar == '') {
                    Swal.fire({
                        icon: 'error',
                        title: "¡No puede ser!",
                        text: `Todos los campos son obligatorios`,
                    })
                    return
                }
                this.preload = true
                axios.post('/api/redvital/generarPassword', this.generarPassword).then((res) => {
                    for (const prop of Object.getOwnPropertyNames(this.generarPassword)) {
                        this.generarPassword[prop] = '';
                    }
                    this.preload = false
                    this.dialog = false
                    Swal.fire({
                        icon: 'success',
                        title: "¡Exito!",
                        text: `${res.data.message}`,
                    })
                }).catch((err) => {
                    this.showError(err.response.data.message)
                    this.preload = false
                })
            }

        }
    }

</script>
<style>
    #img {
        background-image: url('/storage/images/imglogin.png');
        background-size: cover;
        background-repeat: round;
    }

    #right:before {
        content: '';
        position: fixed;
        width: 100vw;
        height: 100vh;
        top: 0;
        background-image: url('/images/prueba.jpg');
        background-size: cover
    }

    html {
        overflow-y: auto !important;
        overflow-x: auto !important;
    }

    .sombra {
        box-shadow: 10px
    }

</style>
