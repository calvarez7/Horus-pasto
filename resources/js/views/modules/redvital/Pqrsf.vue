<template>
    <v-content>

        <v-layout v-show="formulario" class="mt-5 my-5">
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm12 md10>
                        <v-card class="elevation-12">
                            <v-toolbar dark color="redvitalVerde">
                                <v-flex class="text-xs-center" flat dark>
                                    <v-toolbar-title>Peticiones - Quejas - Reclamos - Sugerencias - Felicitaciones
                                        (PQRS-F)
                                    </v-toolbar-title>
                                </v-flex>
                                <v-spacer></v-spacer>
                            </v-toolbar>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-flex xs2>
                                            <v-text-field readonly v-model="paciente.Num_Doc" label="Cedula" required>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs3>
                                            <v-text-field readonly v-model="paciente.Primer_Nom" label="Nombre"
                                                required>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs3>
                                            <v-text-field readonly v-model="paciente.Primer_Ape" label="Primer Apellido"
                                                required></v-text-field>
                                        </v-flex>
                                        <v-flex xs4>
                                            <v-text-field readonly v-model="paciente.Segundo_Ape"
                                                label="Segundo Apellido" required></v-text-field>
                                        </v-flex>
                                        <v-flex xs8>
                                            <v-text-field readonly v-model="paciente.IPS" label="Sede" required>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs4>
                                            <v-text-field v-model="paciente.Telefono" label="Telefono/Celular" required
                                                type="number">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field label="Email" v-model="email" required type="email">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-select :items="['Usuario', 'Otro']" label="Quien genera el Pqrsf"
                                                v-model="quienGenero"></v-select>
                                        </v-flex>
                                        <v-flex xs6 v-show="quienGenero == 'Otro'">
                                            <v-text-field label="Cedula" v-model="cedulaGenero" type="number">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6 v-show="quienGenero == 'Otro'">
                                            <v-text-field label="Nombre" v-model="nombreGenero"></v-text-field>
                                        </v-flex>
                                        <v-flex xs4>
                                            <v-select :items="['Petición', 'Queja', 'Reclamo', 'Sugerencia', 'Felicitación',
                                            'Información', 'Solicitud']" label="Tipo de Solicitud" v-model="tipoSolicitud"
                                                @change="infoTipoSolicitud">
                                            </v-select>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-textarea label="Descripción del caso" v-model="descripcion"
                                                :rules="maxDescripcion" :counter="240">
                                            </v-textarea>
                                        </v-flex>
                                        <v-flex xs6>
                                            <input id="adjuntos" multiple ref="adjuntos" type="file" />
                                            <span>(máximo 3 archivos y 5 MB, formatos permitidos jpg, jpeg, png,
                                                pdf)</span>
                                        </v-flex>
                                        <v-flex xs6>
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
                    </v-flex>
                </v-layout>
            </v-container>
        </v-layout>

        <v-layout v-show="consultarEstado" class="mt-5">
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm12 md4>
                        <v-card class="elevation-12">
                            <v-toolbar dark color="redvitalVerde">
                                <v-toolbar-title>Consultar Estado</v-toolbar-title>
                                <v-spacer></v-spacer>
                            </v-toolbar>
                            <v-form @submit.prevent="checkState()">
                                <v-card-text>
                                    <v-text-field color="redvitalVerde" v-model="radicado" prepend-icon="account_box"
                                        label="Numero de radicado" type="number">
                                    </v-text-field>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="redvitalVerde" dark type="submit">Consultar</v-btn>
                                </v-card-actions>
                            </v-form>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-layout>

        <v-layout v-show="resultadoEstado" class="mt-5 my-5">
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm12 md6>
                        <v-card class="elevation-12">
                            <v-toolbar dark color="redvitalVerde">
                                <v-toolbar-title>Estado</v-toolbar-title>
                                <v-spacer></v-spacer>
                            </v-toolbar>
                            <v-card-text>
                                <v-container grid-list-md>
                                    <v-layout wrap v-for="item in estado" :key="item.id">
                                        <v-flex xs12
                                            v-show="(item.Estado !== 'Anulado') && (item.Estado !== 'Cerrado')">
                                            <span>
                                                Señor usuario en la actualidad nos encontrados dando tramite a su
                                                requerimiento, por lo tanto REDVITAL UT estará enviando respuesta al
                                                correo electrónico registrado.
                                            </span>
                                            <v-divider class="my-2"></v-divider>
                                        </v-flex>
                                        <v-flex xs12 v-show="item.Estado == 'Anulado'">
                                            <span>
                                                Señor usuario su requerimiento fue anulado, comunicate a nuestra
                                                linea para más información (57)4 411 44 88.
                                            </span>
                                            <v-divider class="my-2"></v-divider>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field readonly v-model="item.id" label="RADICADO">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field readonly v-model="item.Estado" label="ESTADO">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field readonly v-model="item.NumDoc" label="DOCUMENTO">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field readonly :value="`${item.PrimerN} ${item.PrimerA}`"
                                                label="NOMBRE">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 v-for="(GesPqr, index) in item.gestion_pqrsfs"
                                            :key="`ad-${index}`">
                                            <v-flex v-if="GesPqr.Tipo_id == 9">
                                                <v-flex xs12>
                                                    <v-textarea v-model="GesPqr.Motivo" readonly>
                                                        <template v-slot:label>
                                                            <div>
                                                                SOLUCIÓN
                                                            </div>
                                                        </template>
                                                    </v-textarea>
                                                    <span><strong>Fecha:</strong>
                                                        {{ GesPqr.created_at.split('.')[0] }}</span>
                                                </v-flex>
                                                <v-btn v-for="(data, index) in GesPqr.adjuntos_pqrsf"
                                                    :key="`aj1-${index}`">
                                                    <a :href="`${data.Ruta}`" target="_blank"
                                                        style="text-decoration:none">
                                                        <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                                                        {{index+1}}
                                                    </a>
                                                </v-btn>
                                            </v-flex>
                                            <span class="red--text">
                                                Señor usuario esta respuesta se envio a su correo electrónico registrado
                                                donde
                                                podra verla más completa.
                                            </span>
                                            <v-divider class="my-2" v-if="index < 1"></v-divider>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card-text>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-layout>


        <v-layout row justify-center>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm12 md6>
                        <v-dialog v-model="mensajechat" persistent max-width="500px">
                            <v-card>
                                <v-toolbar color="redvitalVerde" dark class="mb-3">
                                    <v-toolbar-title>Importante</v-toolbar-title>
                                </v-toolbar>
                                <v-card-text>
                                    <v-container grid-list-md>
                                        <v-layout wrap>
                                            <strong class="subheading">
                                                <p>
                                                    Señor(a) {{ paciente.Primer_Nom }} {{ paciente.Primer_Ape }}
                                                    {{ paciente.Segundo_Ape }}
                                                    <p>
                                                        Para Redvitalut es importante conocer las observaciones del
                                                        servicio,
                                                        pues nos permite implementar las acciones de mejoras para cada
                                                        día
                                                        fortalecer
                                                        la atención a nuestros usuarios. En este modulo usted puede
                                                        consultar el
                                                        estado de
                                                        su requerimiento o ir al formulario para realizar la radicación.
                                                    </p>
                                                    Que desea hacer ?
                                            </strong>
                                        </v-layout>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions class="text-center">
                                    <v-spacer></v-spacer>
                                    <v-btn color="info" dark @click="openEstado()">Estado</v-btn>
                                    <!-- <v-btn color="redvitalVerde" dark @click="chat()">Ir al Chat</v-btn> -->
                                    <v-btn color="red" dark @click="openFormulario()">Formulario</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-layout>

    </v-content>
</template>
<script>
    import Vue from 'vue';
    import Swal from 'sweetalert2';
    export default {
        name: "Pqrsf",
        props: {
            paciente: Object
        },
        data() {
            return {
                dialogoChat: true,
                formulario: false,
                consultarEstado: false,
                mensajechat: true,
                quienGenero: '',
                tipoSolicitud: '',
                descripcion: '',
                maxDescripcion: [
                    v => v.length <= 240 || 'Maximo de caracteres 240',
                ],
                checkbox: false,
                email: '',
                cedulaGenero: '',
                nombreGenero: '',
                adjuntos: [],
                radicado: '',
                resultadoEstado: false,
                estado: {}
            }
        },
        methods: {
            chat() {
                this.mensajechat = false
                this.$emit("dialogoChat", this.dialogoChat)
            },
            openEstado() {
                this.mensajechat = false
                this.consultarEstado = true
            },
            checkState() {
                axios.post('/api/pqrsf/checkStatus', {
                        radicado: this.radicado,
                        paciente_id: this.paciente.id

                    })
                    .then(res => {
                        this.estado = res.data
                        this.consultarEstado = false
                        this.resultadoEstado = true
                    }).catch(err => {
                        let msg = '';
                        for (const pro in err.response.data) {
                            if (msg) msg += `${err.response.data[pro]}`
                            else msg = err.response.data[pro]
                        }
                        swal({
                            title: msg,
                            text: " ",
                            type: "error",
                            icon: "error",
                        });
                    })
            },
            openFormulario() {
                this.mensajechat = false
                Swal.fire({
                    title: 'Aviso',
                    text: 'Señor usuario recuerde que para recibir respuesta a su solicitud debe ingresar un correo electrónico valido de lo contrario deberá realizar su tramite a través de los canales presenciales. Se recuerda la importancia que de manera continua actualice sus datos personales en su sede de atención primaria.',
                    icon: 'info',
                    allowOutsideClick: false
                })
                this.formulario = true
            },
            enviarFormulario() {
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
                if (this.quienGenero == 'Otro') {
                    if ((this.cedulaGenero == '') || (this.nombreGenero == '')) {
                        swal({
                            title: "¡Debe ingresar los campos obligatorios!",
                            text: ` `,
                            timer: 2000,
                            icon: "error",
                            buttons: false
                        });
                        return
                    }
                }
                this.adjuntos = this.$refs.adjuntos.files
                if (this.adjuntos.length > 3) {
                    swal({
                        title: "¡No puede adjuntar más de 3 archivos!",
                        text: ` `,
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                    return
                }
                let formData = new FormData();
                formData.append('paciente_id', this.paciente.id)
                formData.append('documento', this.paciente.Num_Doc)
                formData.append('telefono', this.paciente.Telefono)
                formData.append('email', this.email)
                formData.append('quiengenero', this.quienGenero)
                formData.append('cedulagenero', this.cedulaGenero)
                formData.append('nombregenero', this.nombreGenero)
                formData.append('tiposolicitud', this.tipoSolicitud)
                formData.append('descripcion', this.descripcion)
                for (let i = 0; i < this.adjuntos.length; i++) {
                    formData.append(`adjuntos[]`, this.adjuntos[i]);
                }
                axios.post(`/api/pqrsf/store`, formData)
                    .then(res => {
                        this.data = res.data
                        this.limpiarFormulario()
                        Swal.fire({
                            title: 'Exito!',
                            text: `Sr(a) ${this.paciente.Primer_Nom} su solicitud fue radicada con éxito N° de radicado ${this.data.pqrsf}
                            La respuesta será enviada a su correo electrónico dando cumplimiento a los tiempos de oportunidad establecidos
                            por Fiduprevisora SA`,
                            icon: 'success',
                            allowOutsideClick: false
                        })
                    }).catch(err => {
                        let msg = '';
                        for (const pro in err.response.data) {
                            if (msg) msg += `${err.response.data[pro]}`
                            else msg = err.response.data[pro]
                        }
                        swal({
                            title: msg,
                            text: " ",
                            type: "error",
                            icon: "error",
                        });
                    })
            },
            limpiarFormulario() {
                this.email = ''
                this.quienGenero = ''
                this.cedulaGenero = ''
                this.nombreGenero = ''
                this.tipoSolicitud = ''
                this.descripcion = ''
                this.$refs.adjuntos.value = ''
                this.checkbox = false
            },
            infoTipoSolicitud() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    onOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })

                let msg = ''
                if (this.tipoSolicitud == 'Petición') {
                    msg =
                        'Es una solicitud verbal o escrita, que tiene como propósito requerir la intervención de la entidad en un asunto concreto'
                } else if (this.tipoSolicitud == 'Queja') {
                    msg =
                        'Es la manifestación de inconformidad o descontento expresada por un proveedor, relacionada con el comportamiento o la atención prestada por parte de un funcionario o colaborador de la entidad'
                } else if (this.tipoSolicitud == 'Reclamo') {
                    msg =
                        'Es la exigencia de atención presentada por un proveedor, ocasionada y relacionada con la ausencia y deficiencia de un servicio o producto'
                } else if (this.tipoSolicitud == 'Sugerencia') {
                    msg =
                        'Es la propuesta de adecuación o mejora en la prestación de un servicio'
                } else if (this.tipoSolicitud == 'Felicitación') {
                    msg =
                        'Manifestación del usuario para expresar su satisfacción frente al servicio recibido por la Institución'
                } else if (this.tipoSolicitud == 'Información'){
                    msg =
                        'Solicitud de información de procesos, horarios, sedes, entre otros'
                } else if (this.tipoSolicitud == 'Solicitud'){
                    msg =
                        'Solicitudes'
                }

                Toast.fire({
                    icon: 'info',
                    title: msg
                })

            }
        }
    }

</script>
