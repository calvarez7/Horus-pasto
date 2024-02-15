<template>
    <v-content id="imglogin">
        <v-container fluid mt-5>
            <v-layout align-center justify-center>
                <v-flex xs12 sm8 md4>
                    <v-card class="elevation-12">
                        <v-toolbar dark color="primary">
                            <v-toolbar-title>Crear contraseña</v-toolbar-title>
                            <v-spacer></v-spacer>
                        </v-toolbar>
                        <v-card-text>
                            <v-form>
                                <v-text-field prepend-icon="phone" maxlength="10" label="Celular" v-model="celular"
                                    type="number"
                                    oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                    onkeypress="return (event.charCode >= 48 && event.charCode <= 57)">
                                </v-text-field>
                                <v-text-field prepend-icon="lock" label="Contraseña" v-model="password" type="password">
                                </v-text-field>
                                <v-text-field prepend-icon="lock" label="Confirmar contraseña"
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
    import Vue from 'vue';
    import swal from 'sweetalert';
    export default {
        name: 'GenerarPassword',
        data: () => ({
            celular: '',
            password: '',
            password_confirmation: '',
            preload: false
        }),
        methods: {
            submit() {
                if (this.password == '' | this.password_confirmation == '' | this.celular == '') {
                    this.$alerError("Todos los campos son obligatorios!");
                    return;
                }
                if (this.password.length < 8) {
                    this.$alerError("La contraseña debe ser mínimo de 8 caracteres!");
                    return;
                }
                let url = window.location.href;
                let tokenPaciente = url.split("generar/password/");
                let token = tokenPaciente[1].split("/");
                let data = {
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                    celular: this.celular,
                    paciente_id: token[0],
                    token: token[1]
                }
                this.preload = true
                axios.post('/api/redvital/createPassword', data).then((res) => {
                    this.preload = false
                    this.$alerSuccess(res.data.message);
                    this.$router.push("/gestion");
                }).catch(err => {
                    this.preload = false
                    let msg = '';
                    for (const pro in err.response.data) {
                        if (msg) msg += `${err.response.data[pro]}`
                        else msg = err.response.data[pro]
                    }
                    if (msg != '') {
                        this.$alerError(msg);
                    } else {
                        this.$alerError(err.response.data.message);
                    }
                });
            }
        }
    }

</script>
<style>
    #imglogin {
        background-image: url('/storage/images/dia-doctor-lindo-joven-apuesto-bata-laboratorio-gafas-sonriendo-sosteniendo-libro.jpg');
        background-size: cover
    }

</style>
