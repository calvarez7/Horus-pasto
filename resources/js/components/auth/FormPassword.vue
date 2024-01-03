<template>
    <div>

        <v-content>
            <v-container fluid fill-height mt-5>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm8 md4>
                        <v-card class="elevation-12">
                            <v-toolbar dark color="primary">
                                <v-toolbar-title>Recuperar contraseña</v-toolbar-title>
                                <v-spacer></v-spacer>
                            </v-toolbar>
                            <v-card-text>
                                <v-form>
                                    <v-text-field prepend-icon="email" label="Email" v-model="email" type="email">
                                    </v-text-field>
                                    <v-text-field prepend-icon="lock" label="Password" v-model="password"
                                        type="password">
                                    </v-text-field>
                                    <v-text-field prepend-icon="lock" label="Confirmar password"
                                        v-model="password_confirmation" type="password">
                                    </v-text-field>
                                </v-form>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="primary" @click="submit()">Enviar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>

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
    import swal from 'sweetalert';
    export default {
        name: 'FormPassword',
        data: () => ({
            email: '',
            password: '',
            password_confirmation: '',
            preload: false
        }),
        methods: {
            submit() {
                let url = window.location.href;
                let token = url.split("password/");
                let data = {
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                    token: token[1]
                }
                this.preload = true
                axios.post('/api/password/reset', data).then((res) => {
                    this.preload = false
                    this.$alerSuccess("Contraseña cambiada con exito!");
                    this.$router.push("/login");
                }).catch(err => {
                    this.preload = false
                    this.$alerError("Error en diligenciamiento de datos");
                });
            }
        }
    }

</script>
