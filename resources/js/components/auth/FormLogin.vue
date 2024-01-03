<template>

    <div>

        <form @submit.prevent="submit">
            <v-flex xs9>
                <v-text-field v-model="data.email" v-validate="'required|email'"
                    :error-messages="errors.collect('email')" label="Correo Electrónico" data-vv-name="email" required
                    dark></v-text-field>
            </v-flex>
            <v-flex xs9>
                <v-text-field v-model="data.password" :append-icon="show1 ? 'visibility' : 'visibility_off'"
                    :type="show1 ? 'text' : 'password'" label="Contraseña" @click:append="show1 = !show1" required dark>
                </v-text-field>
            </v-flex>
            <v-layout justify-center>
                <v-flex xs5>
                    <v-btn dark flat @click="dialog = true, email_recuperar = ''">Olvide mi contraseña</v-btn>
                </v-flex>
                <v-flex xs5>
                    <v-btn color="primary" type="submit">Ingresar</v-btn>
                </v-flex>
            </v-layout>
        </form>

        <v-dialog v-model="dialog" max-width="500px">
            <v-layout align-center justify-center>
                <v-flex xs12>
                    <v-card>
                        <v-toolbar dark color="primary">
                            <v-toolbar-title>Recuperar contraseña</v-toolbar-title>
                            <v-spacer></v-spacer>
                        </v-toolbar>
                        <v-card-text>
                            <v-form>
                                <v-text-field prepend-icon="email" v-model="email_recuperar" label="Correo Electrónico"
                                    type="email">
                                </v-text-field>
                            </v-form>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" @click="recuperarPassword()">Enviar</v-btn>
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

    </div>
</template>
<script>
    import Vue from 'vue';
    import {
        mapMutations
    } from 'vuex';
    import VeeValidate from 'vee-validate'
    import swal from 'sweetalert';

    Vue.use(VeeValidate)

    export default {
        name: 'FormLogin',
        $_veeValidate: {
            validator: 'new'
        },

        data: () => ({
            show1: false,
            data: {
                email: '',
                password: '',
                remember_me: true
            },
            dictionary: {
                attributes: {
                    email: 'E-mail Address',
                    password: 'password',
                    name
                    // custom attributes
                },
                custom: {
                    email: {
                        required: () => 'Correo electrónico no puede estar vacio',
                    },
                    password: {
                        required: () => 'Contraseña no puede estar vacia',
                    }
                }
            },
            dialog: false,
            email_recuperar: '',
            preload: false

        }),

        mounted() {
            this.$validator.localize('es', this.dictionary)
        },

        methods: {
            submit() {
                this.$validator.validateAll()
                    .then((res) => this.login())
            },
            goHome() {
                this.$router.push('/')
            },
            login() {
                axios.post('/api/auth/login', this.data)
                    .then((res) => {
                        localStorage.setItem('_token', res.data.access_token);
                        axios.defaults.headers.common['Authorization'] =
                            `${res.data.token_type} ${res.data.access_token}`;
                        this.$store.commit('setUser', res.data.user);
                        swal({
                            title: "¡Bienvenido!",
                            text: " ",
                            type: "success",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                        this.goHome()
                    })
                    .catch((err) => this.showError(err.response.data.message));

            },
            showError: (message) => {
                swal({
                    title: "¡No puede ser!",
                    text: `${message}`,
                    icon: "warning",
                });
            },
            recuperarPassword() {
                if (this.email_recuperar == '') return;
                this.preload = true
                axios.post('/api/password/create', {
                    email: this.email_recuperar
                }).then((res) => {
                    swal({
                        title: "¡Codigo de recuperación enviado al correo con exito!",
                        text: " ",
                        type: "success",
                        timer: 2000,
                        icon: "success",
                        buttons: false
                    });
                    this.preload = false
                    this.dialog = false
                    this.email_recuperar = ''
                }).catch(err => {
                    this.preload = false
                    swal({
                        title: "¡No podemos encontrar un usuario con esa dirección de correo electrónico!",
                        text: " ",
                        type: "error",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                });

            },
        }
    }

</script>
