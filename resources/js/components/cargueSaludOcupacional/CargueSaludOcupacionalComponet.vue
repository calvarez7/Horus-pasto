<template>
    <v-container fluid pa-0>
            <v-layout row>
                <v-flex xs12>
                    <v-card-title class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">Cargue historias clinicas
                            manuales</span>
                    </v-card-title>
                    <v-divider></v-divider>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" rounded @click="abrirModal()">
                                Tipo anexo
                            </v-btn>
                        </v-card-actions>
                        <v-card-text>
                            <v-container grid-list-md fluid class="pa-1">
                                <v-form @submit.prevent="search_paciente()">
                                    <v-layout row wrap>
                                        <v-flex xs4>
                                            <v-text-field v-model="cedula_paciente" label="Número de Documento">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs3>
                                            <v-autocomplete v-model="listaAnexos" label="Tipo anexo" :items="tipoAnexos" item-text="nombre" item-value="id">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs3>
                                            <v-text-field v-model="fecha_proceso" label="Fecha del proceso" type=date>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs2>
                                            <v-btn @click="search_paciente()" @keyup.enter="search_paciente()"
                                                v-if="!paciente.id" fab outline small color="success">
                                                <v-icon>search</v-icon>
                                            </v-btn>
                                            <v-btn @click="clearFields()" v-if="paciente.id" fab outline small
                                                color="error">
                                                <v-icon>clear</v-icon>
                                            </v-btn>
                                        </v-flex>
                                    </v-layout>
                                </v-form>
                            </v-container>
                        </v-card-text>

                </v-flex>
            </v-layout>
        <v-layout grow pa-0 v-show="paciente.id">
            <v-flex xs12>
                <v-card max-height="100%" class="mb-3">
                    <v-card-title class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">Datos del Paciente</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container grid-list-md fluid class="pa-0">
                            <v-layout row wrap>
                                <v-flex xs12 sm6 md2>
                                    <v-text-field v-model="paciente.Tipo_Doc" readonly label="Tipo Doc">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field v-model="paciente.Num_Doc" readonly label="N° Doc">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field v-model="paciente.Primer_Nom" readonly label="1 Nombre">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field v-model="paciente.SegundoNom" readonly label="2 nombre">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs1>
                                    <v-text-field v-model="paciente.Primer_Ape" readonly label="1 Apellido">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs1>
                                    <v-text-field v-model="paciente.Segundo_Ape" readonly label="2 Apellido">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field v-model="paciente.Fecha_Naci" readonly label="F. Nacimiento">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs1>
                                    <v-text-field v-model="paciente.Edad_Cumplida" label="Edad">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field v-model="paciente.Sexo" label="Sexo">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs1 md3>
                                    <v-text-field v-model="paciente.Nivelestudio" label="Ocupación">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs1 md3>
                                    <v-text-field v-model="paciente.Correo1" label="Correo">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs3 md3>
                                    <v-text-field v-model="paciente.NombreIPS" label="Punto Atencion">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs1 md2>
                                    <v-text-field v-model="paciente.Tipo_Afiliado" label="Tipo Afiliado">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field v-model="paciente.Telefono" label="Telefono">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field v-model="paciente.Celular1" label="Celular" type="number" readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs3>
                                    <v-text-field v-model="paciente.Direccion_Residencia" label="Direccion_Residencia">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12>
                                <input id="adjuntos"  ref="adjuntos" type="file" />
                                <span>(máximo 1 archivo y 5 MB, formatos permitidos jpg, jpeg, png, pdf)</span>
                            </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                        <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="success" rounded @click="guardar()">
                            Guardar
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>

        <v-layout row>
            <v-dialog v-model="dialog" max-width="800px">
                <v-card>
                    <v-toolbar flat color="primary" dark>
                        <v-toolbar-title>Tipo archivo</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-container grid-list-md fluid class="pa-1">
                            <v-layout row>
                                <v-flex xs6>
                                    <v-text-field v-model="tipo.nombre" label="Nombre" :error-messages="errores.nombre">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs6>
                                    <v-text-field v-model="tipo.descripcion" label="Descripción" :error-messages="errores.descripcion">
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-container>

                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" rounded @click="limpiarCampos()">
                            Cerrar
                        </v-btn>
                        <v-btn color="success" rounded @click="guardarTipoAnexo()">
                            Guardar
                        </v-btn>
                    </v-card-actions>
                </v-card>

            </v-dialog>
        </v-layout>
    </v-container>

</template>

<script>
    import {
        mapGetters
    } from 'vuex';
    import Swal from 'sweetalert2';
    import moment from 'moment';
    export default {
        data() {
            return {
                adjuntos:[],
                cedula_paciente: '',
                paciente: '',
                listaAnexos: '',
                fecha_proceso:'',
                dialog: false,
                tipo: {
                    nombre: '',
                    descripcion: ''
                },
                tipoAnexos: [],
                errores:{
                    nombre:'',
                    descripcion:''
                }
            }
        },
        computed: {
            ...mapGetters(['can']),
        },
        mounted() {
            moment.locale('es');
            this.listarTipoAnexo()
        },
        methods: {
            guardar() {
                if (this.$refs.adjuntos.files == '') {
                    this.$alerError("El anexo es requerido");
                    return;
                }
                this.adjuntos = this.$refs.adjuntos.files
                if (this.adjuntos.length > 1) {
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
                formData.append('tipo_anexo_id', this.listaAnexos)
                formData.append('cedula_paciente', this.paciente.Num_Doc)
                formData.append('fecha_proceso', this.fecha_proceso)
                for (let i = 0; i < this.adjuntos.length; i++) {
                    formData.append(`adjuntos[]`, this.adjuntos[i]);
                }
                axios.post('/api/adjuntoAnexo_saludOcupacional/guardar', formData)
                    .then(res => {
                        this.clearFields()
                        this.$alerSuccess("Anexo generado con exito!");
                    })
                    .catch(() => console.log('Error'));
            },

            search_paciente() {
                if (!this.cedula_paciente){
                    this.$alerError("El número de documento es requerido");
                    return;
                }else if(this.listaAnexos == ''){
                    this.$alerError("El tipo anexo es requerido");
                    return;
                }else if (this.fecha_proceso == ''){
                    this.$alerError("La fecha del proceso es requerida");
                    return;
                }
                axios.get(`/api/paciente/consultarPacientes/${this.cedula_paciente}`)
                    .then((res) => {
                        if (res.data.data) {
                            this.paciente = res.data.data;
                        }
                    });
            },

            clearFields() {
                this.cedula_paciente = '',
                this.listaAnexos = '',
                this.fecha_proceso = '',
                    this.paciente = {
                        Primer_Nom: '',
                        Primer_Ape: '',
                        Tipo_Doc: '',
                        Num_Doc: '',
                        Edad_Cumplida: ''
                    }
            },

            abrirModal() {
                this.dialog = true
            },

            guardarTipoAnexo() {
                this.limpiarError()
                axios.post('/api/tipoAnexo_saludOcupacional/guardar', this.tipo)
                    .then(res => {
                        this.$alerSuccess(res.data.mensaje);
                        this.listarTipoAnexo()
                        this.limpiarCampos()
                    }).catch(err => {
                        this.setError(err.response.data.errors)
                    })
            },

            listarTipoAnexo() {
                axios.get('/api/tipoAnexo_saludOcupacional/listar')
                    .then((res) => {
                        this.tipoAnexos = res.data.data
                    }).catch((err) => console.log(err));
            },

            limpiarCampos() {
                this.tipo.nombre = '',
                this.tipo.descripcion = '',
                this.dialog = false
                this.limpiarError()
            },

            setError(errors) {
                for (const key in this.errores) {
                    if (key in errors) {
                        this.errores[key] = errors[key].join(',')
                    }
                }
            },

            limpiarError() {
                for (const key in this.errores) {
                    this.errores[key] = ''
                }
            },
        }
    }

</script>
