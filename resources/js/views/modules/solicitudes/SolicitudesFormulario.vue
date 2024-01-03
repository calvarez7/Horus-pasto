<template>
    <div>

        <v-container fluid fill-height v-if="validation">
            <v-layout align-center justify-center>
                <v-flex xs12 sm8 md4>
                    <form @submit.prevent="submit">
                        <v-card class="elevation-12">
                            <v-toolbar dark color="primary">
                                <v-toolbar-title>Horus - Radicación</v-toolbar-title>
                                <v-spacer></v-spacer>
                            </v-toolbar>
                            <v-card-text>
                                <v-text-field color="primary" prepend-icon="account_box" v-model="data.cedula"
                                    label="Cédula del afiliado" type="number">
                                </v-text-field>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="primary" dark type="submit" :disabled="preload">Ingresar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </form>
                </v-flex>
            </v-layout>
        </v-container>

        <v-flex xs10 offset-xs1 class="mt-2 mb-2" v-else>
            <v-container fluid grid-list-xl>
                <v-layout row wrap>
                    <v-card class="elevation-12">
                        <v-toolbar dark color="redvitalVerde">
                            <v-flex class="text-xs-center" flat dark>
                                <v-toolbar-title>Radicación
                                </v-toolbar-title>
                            </v-flex>
                        </v-toolbar>
                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs6 sm2 md2>
                                        <v-text-field readonly v-model="paciente.Num_Doc" label="Documento" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs6 sm3 md3>
                                        <v-text-field readonly v-model="paciente.Primer_Nom" label="Nombre" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs6 sm3 md3>
                                        <v-text-field readonly v-model="paciente.Primer_Ape" label="Primer Apellido"
                                            required></v-text-field>
                                    </v-flex>
                                    <v-flex xs6 sm4 md4>
                                        <v-text-field readonly v-model="paciente.Segundo_Ape" label="Segundo Apellido"
                                            required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm8 md8>
                                        <v-text-field readonly v-model="paciente.IPS" label="Sede" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm4 md4>
                                        <v-text-field v-model="paciente.Telefono" label="Telefono" required
                                            type="number"
                                            onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                            oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="7" min="1">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm2 md2>
                                        <v-text-field v-model="paciente.celular" label="Celular" type="number"
                                            onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                            oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="10" min="1">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm2 md2>
                                        <v-text-field v-model="paciente.celular2" label="Celular 2" type="number"
                                            onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                            oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            maxlength="10" min="1">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm4 md4>
                                        <v-text-field label="Email" v-model="paciente.correo" required type="email">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm4 md4>
                                        <v-text-field label="Dirección" v-model="paciente.Direccion_Residencia" required
                                            type="email">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm12 md12>
                                        <v-autocomplete :items="tipoSolicitudes" @click="getTipos" return-object
                                            item-text="nombre" label="Tipo de Solicitud" v-model="tipoSolicitud"
                                            multiple @input="cantidadAdjuntos(), infoTipoSolicitud()">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs12 sm12 md12 v-for="(item, index) in cantidadAdjunto" :key="index">
                                        <v-textarea :label="'Observación ' + item.nombre" v-model="item.observacion"
                                            :rules="maxObservacion" :counter="300" outline>
                                        </v-textarea>
                                        <input :id="item.id" multiple ref="adjuntos" type="file" />
                                        <span><strong>adjunte {{ item.nombre }}</strong></span>
                                        <v-spacer class="mt-2"></v-spacer>
                                    </v-flex>
                                    <v-flex xs12 sm12 md12>
                                        <v-checkbox v-model="checkbox" color="redvitalAzul"
                                            label="Con el diligenciamiento de este formato autorizo expresamente el uso de mis datos personales según Ley 1581 de 2012">
                                        </v-checkbox>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="redvitalVerde" dark @click="enviarFormulario()">Enviar</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-layout>
            </v-container>
        </v-flex>

        <v-dialog v-model="dialogInfo" width="500" persistent>
            <v-card>

                <v-card-title class="headline redvitalAzul" style="color:white" primary-title>
                    Información!
                </v-card-title>

                <v-card-text>
                    <p>
                        Sr(a) {{ this.paciente.Primer_Nom }} debe tener en cuenta que para su
                        solicitud debe facilitar los siguientes archivos.
                    </p>
                    <p v-for="(item, index) in tipoSolicitud" :key="index">
                        <v-chip label color="warning" text-color="white">
                            <v-icon left>label</v-icon>{{ item.nombre }}
                        </v-chip>
                        <span>{{ item.descripcion }}</span>
                    </p>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success" flat @click="dialogInfo = false">
                        ok
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogRadicado" width="500" persistent>
            <v-card>

                <v-card-title class="headline redvitalVerde" style="color:white" primary-title>
                    Exito!
                </v-card-title>

                <v-card-text>
                    <p>
                        Sr(a) {{ this.paciente.Primer_Nom }} su solicitud fue radicada con
                        éxito, la respuesta será enviada a su correo electrónico dando cumplimiento a
                        los tiempos de oportunidad.
                    </p>
                    <p v-for="(item, index) in radicados" :key="index">
                        <v-chip label color="redvitalAzul" text-color="white">
                            <v-icon left>label</v-icon>Radicado # {{ item.radicado }}
                        </v-chip>
                        <span>Solicitud {{ item.solicitud }}</span>
                    </p>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success" flat @click="dialogRadicado = false, goHome()">
                        ok
                    </v-btn>
                </v-card-actions>
            </v-card>
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
    import Swal from 'sweetalert2';
    export default {
        name: 'SolicitudFormulario',
        data() {
            return {
                data: {
                    cedula: ''
                },
                preload: false,
                validation: true,
                tipoSolicitudes: [],
                tipoSolicitud: [],
                cantidadAdjunto: [],
                checkbox: false,
                maxObservacion: [
                    v => v.length <= 300 || 'Maximo de caracteres 300',
                ],
                dialogInfo: false,
                paciente: {},
                adjuntos: [],
                radicados: [],
                dialogRadicado: false,

            }
        },
        methods: {
            submit() {
                this.validacionPaciente();
            },
            validacionPaciente() {
                if (this.data.cedula == "") {
                    Swal.fire({
                        icon: 'error',
                        title: "¡No puede ser!",
                        text: 'Debe llenar los campos obligatorios!',
                    })
                    return
                }
                this.preload = true;
                axios.post('/api/redvital/validacionPacienteAuth', this.data)
                    .then((res) => {
                        this.paciente = res.data
                        if (this.paciente.Estado_Afiliado == 1) {
                            this.validation = false
                            this.infoAviso()
                        } else {
                            this.showError('El paciente no se encuentra activo.')
                        }
                        this.preload = false;
                    })
                    .catch((err) => {
                        this.preload = false;
                        this.showError(err.response.data.message)
                    });
            },
            infoAviso() {
                Swal.fire({
                    title: 'Aviso',
                    text: 'Señor usuario recuerde que para recibir respuesta a su solicitud debe actualizar sus datos de contacto actuales.',
                    icon: 'info',
                    allowOutsideClick: false
                })
            },
            showError: (message) => {
                Swal.fire({
                    icon: 'warning',
                    title: "¡No puede ser!",
                    text: `${message}`,
                })
            },
            getTipos() {
                axios.get('/api/redvital/getTipoSolicitud')
                    .then(res => {
                        this.tipoSolicitudes = res.data.map(ts => {
                            return {
                                id: ts.id,
                                nombre: ts.nombre,
                                descripcion: ts.descripcion,
                                observacion: ''
                            }
                        })
                    });
            },
            cantidadAdjuntos() {
                this.cantidadAdjunto = this.tipoSolicitud
            },
            infoTipoSolicitud() {
                if (this.tipoSolicitud.length > 0) {
                    this.dialogInfo = true;
                }
            },
            async enviarFormulario() {
                if (this.checkbox == false) {
                    swal({
                        title: "¡Debe aceptar el uso de sus datos personales según Ley 1581 de 2012!",
                        text: ` `,
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                    return
                }
                if (this.paciente.correo == '' |
                    this.paciente.correo == undefined |
                    this.paciente.celular == '' |
                    this.paciente.celular == undefined |
                    this.paciente.Direccion_Residencia == '' |
                    this.paciente.Direccion_Residencia == undefined) {
                    this.$alerError(
                        "No actualizo los datos obligatorios de contacto, revise celular, email o dirección")
                    return;
                }
                var regex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
                if (!regex.test(this.paciente.correo)) {
                    swal({
                        title: "Debe ingresar un email valido",
                        icon: "warning"
                    });
                    return;
                }
                if (this.tipoSolicitud.length == 0) {
                    this.$alerError("Debe seleccionar un tipo de solicitud!")
                    return;
                }
                this.adjuntos = this.$refs.adjuntos
                let validationAdjunto = false;
                for (let index = 0; index < this.adjuntos.length; index++) {
                    let filesize = 0;
                    for (let i = 0; i < this.adjuntos[index].files.length; i++) {
                        filesize += this.adjuntos[index].files[i].size
                        if (filesize > 8000000) {
                            let solicitud = this.tipoSolicitud.find(s => s.id == this.adjuntos[index].id);
                            Swal.fire({
                                title: 'Error',
                                text: `Los adjuntos de la solicitud ${solicitud.nombre} superan el peso de 8MB.`,
                                icon: 'error',
                                allowOutsideClick: false
                            })
                            validationAdjunto = true
                            return;
                        }
                    }
                }
                if (validationAdjunto == false) {
                    this.preload = true
                    let error = false;
                    for (let index = 0; index < this.adjuntos.length; index++) {
                        let solicitud = this.tipoSolicitud.find(s => s.id == this.adjuntos[index].id);
                        let formData = new FormData();
                        formData.append('paciente_id', this.paciente.id);
                        formData.append('documento', this.paciente.Num_Doc);
                        formData.append('descripcion', solicitud.observacion);
                        formData.append('solicitud_id', solicitud.id);
                        formData.append('solicitud_nombre', solicitud.nombre);
                        for (let i = 0; i < this.adjuntos[index].files.length; i++) {
                            if (this.radicados.length == 0) {
                                formData.append(this.adjuntos[index].id + '[]',
                                    this.adjuntos[index].files[i]);
                            } else {
                                let find = this.radicados.find(r => r.solicitud_id == this.adjuntos[index].id)
                                if (!find) {
                                    formData.append(this.adjuntos[index].id + '[]',
                                        this.adjuntos[index].files[i]);
                                }
                            }
                        }
                        await axios.post('/api/redvital/radicacionInterna', formData).then((res) => {
                            this.radicados.push(res.data);
                        }).catch((err) => {
                            error = true;
                        })
                    }
                    if (error == true) {
                        this.preload = false
                        Swal.fire({
                            icon: 'warning',
                            title: "¡No puede ser!",
                            text: `Error de radicación, comunicate con el área de atención al usuario!`,
                        })
                    } else if (error == false) {
                        this.paciente.Correo1 = this.paciente.correo
                        this.paciente.Celular1 = this.paciente.celular
                        this.paciente.Celular2 = this.paciente.celular2
                        axios.put(`/api/redvital/updatepaciente/${this.paciente.id}`, this.paciente)
                            .then((res) => {
                                if (res.data.message) {
                                    this.preload = false
                                    this.dialogRadicado = true
                                    this.paciente.correo = ''
                                    this.paciente.celular = ''
                                    this.paciente.celular2 = ''
                                }
                            }).catch((err) => {
                                this.preload = false
                                Swal.fire({
                                    icon: 'warning',
                                    title: "¡No puede ser!",
                                    text: `Error de radicación, comunicate con el área de atención al usuario!`,
                                })
                            })
                    }
                }
            },
            goHome() {
                this.$router.push("/solicitudes")
            },
        }
    }

</script>
