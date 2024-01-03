<template>
    <div>

        <v-container fluid fill-height v-if="validation">
            <v-layout align-center justify-center>
                <v-flex xs12 sm8 md4>
                    <form @submit.prevent="submit">
                        <v-card class="elevation-12">
                            <v-toolbar dark color="primary">
                                <v-toolbar-title>Horus - PQRSF</v-toolbar-title>
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
                    <v-card>
                        <v-toolbar flat color="primary" dark>
                            <v-flex xs12 text-xs-center flat dark>
                                <v-toolbar-title>Peticiones - Quejas - Reclamos - Sugerencias - Felicitaciones (PQRS-F)
                                </v-toolbar-title>
                            </v-flex>
                        </v-toolbar>
                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-flex xs2>
                                        <v-text-field readonly v-model="paciente.Num_Doc" label="Cedula" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-text-field readonly v-model="paciente.Primer_Nom" label="Nombre" required>
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-text-field readonly v-model="paciente.Primer_Ape" label="Primer Apellido"
                                            required></v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-text-field readonly v-model="paciente.Segundo_Ape" label="Segundo Apellido"
                                            required></v-text-field>
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
                                    <v-flex xs4>
                                        <v-text-field label="Email" v-model="email" required type="email">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs4>
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
                                           'Información', 'Solicitud']" label="Tipo de Solicitud"
                                            v-model="tipoSolicitud" @change="infoTipoSolicitud">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-select :items="['Buzón', 'Presencial', 'Escrito', 'Veeduría', 'Telefónico',
                                           'Fiduprevisora', 'Comite regional', 'Procuraduria', 'Personeria',
                                           'Contraloria', 'Supersalud', 'Secretaria de educacion',
                                           'Derecho de Petición', 'Eps Medimas', 'Correo electronico','Redes Sociales',
                                           'Encuestas de Satisfacion', 'DSSA', 'Secretaría de Salud', 'FPSF','Jorge te Ayuda']"
                                            label="Canal" v-model="canal">
                                        </v-select>
                                    </v-flex>
                                    <v-flex xs4 v-show="canal == 'Supersalud'">
                                        <v-text-field label="Radicado Supersalud" v-model="supersalud">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-textarea label="Descripción del caso" v-model="descripcion"
                                            :rules="maxDescripcion" :counter="240">
                                        </v-textarea>
                                    </v-flex>
                                    <v-flex xs6>
                                        <input id="adjuntos" multiple ref="adjuntos" type="file" />
                                        <span>(máximo 3 archivos y 5 MB, formatos permitidos jpg, jpeg, png, pdf)</span>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-checkbox v-model="checkbox" color="primary"
                                            label="Con el diligenciamiento de este formato autorizo expresamente el uso de mis datos personales según Ley 1581 de 2012">
                                        </v-checkbox>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" dark @click="enviarFormulario()">Enviar</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-layout>
            </v-container>
        </v-flex>

        <v-dialog v-model="dialogHistory" width="700" persistent>
            <v-card>

                <v-card-title class="headline primary" style="color:white" primary-title>
                    Historial
                </v-card-title>

                <v-card-text>
                    <v-layout>
                        <v-expansion-panel>
                            <v-expansion-panel-content v-for="(item, index) in history" :key="index">
                                <template v-slot:header>
                                    <span><b># Radicado:</b> {{item.id}}</span>
                                    <span><b>Estado:</b>
                                        <v-chip :class="ColorTd(item.estado)">{{item.estado}}</v-chip>
                                    </span>
                                    <span><b>Fecha:</b> {{item.created_at.split('.')[0]}}</span>
                                </template>
                                <v-card>
                                    <v-container grid-list-md>
                                        <v-layout row wrap>
                                            <v-flex xs6 md6>
                                                <v-text-field label="Tipo de Solicitud" v-model="item.Tiposolicitud"
                                                    readonly></v-text-field>
                                            </v-flex>
                                            <v-flex xs6 md6>
                                                <v-text-field label="Canal" v-model="item.Canal" readonly>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs12 md12>
                                                <v-textarea readonly outlined label="Descripción"
                                                    :value="item.Descripcion">
                                                </v-textarea>
                                            </v-flex>
                                            <v-flex xs12 md12 v-if="item.gestion_pqrsfs.length > 0">
                                                <span> <strong>Nombre:</strong> {{ item.gestion_pqrsfs[0]['name'] }}
                                                    {{ item.gestion_pqrsfs[0]['apellido'] }}</span>
                                            </v-flex>
                                            <v-flex xs12 md12 v-else>
                                                <span> <strong>Paciente:</strong> {{ paciente.Primer_Nom }}
                                                    {{ paciente.Primer_Ape }}</span>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                    </v-layout>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success" flat @click="dialogHistory = false, validation = false, infoAviso()">
                        ok
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </div>

</template>
<script>
    import Vue from 'vue';
    import Swal from 'sweetalert2';
    export default {
        name: 'PqrsfFormulario',
        data() {
            return {
                data: {
                    cedula: ''
                },
                paciente: {},
                quienGenero: '',
                tipoSolicitud: '',
                descripcion: '',
                maxDescripcion: [
                    v => v.length <= 240 || 'Maximo de caracteres 240',
                ],
                checkbox: false,
                email: '',
                supersalud: '',
                cedulaGenero: '',
                nombreGenero: '',
                adjuntos: [],
                canal: '',
                validation: true,
                history: [],
                dialogHistory: false,
                preload: false
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
                        axios.post('/api/pqrsf/history', {
                                paciente_id: this.paciente.id
                            })
                            .then((res1) => {
                                this.history = res1.data
                                this.preload = false;
                                if (this.history.length > 0) {
                                    this.dialogHistory = true
                                } else {
                                    this.infoAviso();
                                    this.validation = false
                                }
                            })
                    })
                    .catch((err) => {
                        this.preload = false;
                        this.showError(err.response.data.message)
                    });
            },
            infoAviso() {
                Swal.fire({
                    title: 'Aviso',
                    text: 'Señor usuario recuerde que para recibir respuesta a su solicitud debe ingresar un correo electrónico valido de lo contrario deberá realizar su tramite a través de los canales presenciales. Se recuerda la importancia que de manera continua actualice sus datos personales en su sede de atención primaria.',
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
                formData.append('supersalud', this.supersalud)
                formData.append('quiengenero', this.quienGenero)
                formData.append('cedulagenero', this.cedulaGenero)
                formData.append('nombregenero', this.nombreGenero)
                formData.append('tiposolicitud', this.tipoSolicitud)
                formData.append('descripcion', this.descripcion)
                formData.append('canal', this.canal)
                for (let i = 0; i < this.adjuntos.length; i++) {
                    formData.append(`adjuntos[]`, this.adjuntos[i]);
                }
                axios.post(`/api/pqrsf/storeuser`, formData)
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
                this.supersalud = ''
                this.quienGenero = ''
                this.cedulaGenero = ''
                this.nombreGenero = ''
                this.tipoSolicitud = ''
                this.descripcion = ''
                this.$refs.adjuntos.value = ''
                this.canal = ''
                this.checkbox = false
            },
            ColorTd(estado) {
                if (estado == 'Cerrado') {
                    return 'error white--text';
                } else {
                    return 'success white--text';
                }
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
                } else if (this.tipoSolicitud == 'Información') {
                    msg =
                        'Solicitud de información de procesos, horarios, sedes, entre otros'
                } else if (this.tipoSolicitud == 'Solicitud') {
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
