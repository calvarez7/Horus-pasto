<template>
    <v-container fluid pa-1>
        <v-form @submit.prevent="search_paciente()">
            <v-layout row>
                <v-flex xs12>
                    <v-card-title class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">Registro de pruebas carga covid-19</span>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-card>
                        <v-card-text>
                            <v-container grid-list-md fluid class="pa-1">
                                <v-form @submit.prevent="search_paciente()">
                                    <v-layout row wrap>
                                        <v-flex xs5>
                                            <v-autocomplete label="Institución que procesa la muestra" :items="proveedore"
                                                item-text="Nombre" item-value="id" v-model="entidad">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs5>
                                            <v-text-field v-model="cedula_paciente" label="Número de Documento">
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
                    </v-card>
                </v-flex>
            </v-layout>
        </v-form>
        <v-layout grow pa-0 v-show="paciente.id">
            <v-flex xs12>
                <v-card max-height="100%" class="mb-3">
                    <v-card-title class="headline success" style="color:white">
                        <span class="title layout justify-center font-weight-light">Datos del Paciente</span>
                    </v-card-title>
                    <v-card-text>
                        <v-layout row wrap>
                            <v-flex xs1>
                                <v-text-field v-model="paciente.Tipo_Doc" readonly label="Tipo Doc">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs1>
                                <v-text-field v-model="paciente.Num_Doc" readonly label="N° Doc">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs1>
                                <v-text-field v-model="paciente.Primer_Nom" readonly label="1 Nombre">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs1>
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
                            <v-flex xs1>
                                <v-text-field v-model="paciente.Sexo" label="Sexo">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs1>
                                <v-text-field label="Estado Civil">
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
                            <v-flex xs1>
                                <v-text-field v-model="paciente.Celular1" label="Celular" type="number" maxlength="10"
                                oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                min="1">
                                </v-text-field>
                            </v-flex>
                            <v-flex xs3>
                                <v-text-field v-model="paciente.Direccion_Residencia" label="Direccion_Residencia">
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-card-text>
                    <v-layout row wrap>
                        <v-flex xs2>
                            <v-btn color="primary" class="white--text" @click="importPrestadores()">
                                Selecciona el archivo
                                <v-icon right dark>cloud_upload</v-icon>
                            </v-btn>
                        </v-flex>
                        <v-flex xs2>
                            <input type="file" v-show="false" @change="onFilePicked" ref="file">
                            <v-text-field v-model="nameFile" name="name" readonly label="Nombre archivo" id="id">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs3>
                            <v-btn color="success" @click="guardar()" dark>Guardar</v-btn>
                        </v-flex>
                    </v-layout>
                </v-card>
            </v-flex>
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
                nameFile: '',
                file: '',
                cedula_paciente: '',
                paciente: '',
                entidad: '',
                proveedore: [],
            }
        },
        computed: {
            ...mapGetters(['can']),
        },
        mounted() {
            moment.locale('es');
            this.proveedores();
        },
        methods: {
            importPrestadores() {
                this.$refs.file.click()
            },
            onFilePicked(e) {
                const files = e.target.files
                this.nameFile = files[0].name
                this.file = files[0]
            },
            guardar() {
                if (this.$refs.file.value == '') {
                    this.$alerError("El anexo es requerido");
                    return;
                }
                let formData = new FormData();
                formData.append('adjunto', this.file);
                formData.append('entidad', this.entidad);
                formData.append('paciente_id', this.paciente.id)
                formData.append('cedula', this.paciente.Num_Doc)
                axios.post('/api/covid/create', formData, {})
                    .then((res) => {

                        this.importFile = false;
                        this.nameFile = '';
                        this.file = null;
                        this.clearFields()
                        this.$alerSuccess("Anexo generado con exito!");
                    })
                    .catch(() => console.log('Error'));
            },
            proveedores() {
                axios.get('/api/sedeproveedore/sedeproveedores')
                    .then((res) => {
                        this.proveedore = res.data
                    })
                    .catch((err) => console.log(err));
            },
            search_paciente() {
                if (!this.cedula_paciente) return;

                axios.get(`/api/paciente/showEnabled/${this.cedula_paciente}`)
                    .then((res) => {
                        if (res.data.paciente) {
                            this.paciente = res.data.paciente;
                            this.sede_selected = this.paciente.IPS;
                            this.paciente = res.data.paciente;
                        }
                        if (res.data.message) this.showMessage(res.data.message);
                    });
            },
            showMessage(message) {
                this.preload_cita = false
                swal({
                    title: `${message}`,
                    icon: "warning",
                });
            },
            clearFields() {
                this.entidad = '',
                    this.cedula_paciente = '',
                    this.paciente = {
                        Primer_Nom: '',
                        Primer_Ape: '',
                        Tipo_Doc: '',
                        Num_Doc: '',
                        Edad_Cumplida: ''
                    }
            },
        }
    }

</script>

<style>

</style>
