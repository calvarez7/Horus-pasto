<template>
    <v-container fluid pa-1 grid-list-md>
        <v-layout row wrap>

            <v-flex xs12 md12>
                <v-card-title class="headline success" style="color:white">
                    <span class="title layout justify-center font-weight-light">Registro de seguimiento covid-19</span>
                </v-card-title>
                <v-divider></v-divider>
                <v-card>
                    <v-form @submit.prevent="search_paciente('seguimiento')">
                        <v-container fluid pa-4>
                            <v-layout>
                                <v-flex xs12 md4>
                                    <v-text-field v-model="cedula_paciente" label="Número de Documento">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 md4>
                                    <v-btn @click="search_paciente('seguimiento')" @keyup.enter="search_paciente('seguimiento')"
                                           v-if="!paciente.id" fab outline small color="success">
                                        <v-icon>search</v-icon>
                                    </v-btn>
                                    <v-btn @click="clearFields()" v-if="paciente.id" fab outline small color="error">
                                        <v-icon>clear</v-icon>
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-form>
                </v-card>
            </v-flex>
        </v-layout>
        <v-layout grow pa-0 >
            <!-- v-show="paciente.id"  -->
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
                            <!-- <v-flex xs1 md2>
                                    <v-select v-model="paciente.Tipo_Afiliado" label="Plan Salud">
                                    </v-select>
                                </v-flex> -->
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
                             <v-btn v-show="tipoRegistro === 'seguimiento'" color="warning" @click="actualizarDatosPersonales()" round>Actualizar
                                </v-btn>
                        </v-layout>
                    </v-card-text>
                    <!-- <v-form v-model="valid" v-show="tipoRegistro === 'archivo'">
                        <v-container>
                            <v-layout>
                                <v-flex xs12 md3>
                                    <v-btn color="primary" class="white--text" @click="importPrestadores()">
                                        Selecciona el archivo
                                        <v-icon right dark>cloud_upload</v-icon>
                                    </v-btn>
                                </v-flex>

                                <v-flex xs12 md3>
                                    <input type="file" v-show="false" @change="onFilePicked" ref="file">
                                    <v-text-field v-model="nameFile" name="name" readonly label="Nombre archivo"
                                        id="id">
                                    </v-text-field>
                                </v-flex>

                                <v-flex xs12 md4>
                                    <v-autocomplete label="Institución Remite" :items="proveedore" item-text="Nombre"
                                        item-value="id" v-model="entidad">
                                    </v-autocomplete>
                                </v-flex>
                            </v-layout>
                            <div class="text-xs-center">
                                <v-btn round color="success" dark @click="guardar()">Guardar</v-btn>
                            </div>
                        </v-container>
                    </v-form> -->
                </v-card>

<!--                v-show="tipoRegistro === 'seguimiento'"-->
                <v-flex xs12>
                    <v-card max-height="100%" class="mb-3">
                        <v-card-title class="headline success" style="color:white">
                            <span class="title layout justify-center font-weight-light">Solicitud de ingreso</span>
                        </v-card-title>
                        <v-card-text>
                            <form @submit.prevent="guardarSeguimiento">
                            <v-layout row wrap>
                                <v-flex xs12 sm4 md4>
                                    <v-text-field label="Nacionalidad" v-model="formSeguimiento.nacionalidad" required></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-autocomplete label="Municipio Ocurrencia" :items="municipios" item-value="id" :item-text="municipios => municipios.municipio+'-'+municipios.departamento" v-model="formSeguimiento.municipio_ocurrencia_id" required></v-autocomplete>
                                </v-flex>
<!--                                <v-flex xs12 sm4 md4>-->
<!--                                <v-text-field label="Dirección Actual" v-model="formSeguimiento.direccionActual" required></v-text-field>-->
<!--                            </v-flex>-->
                                <v-flex xs12 sm4 md4>
                                    <v-label><strong>¿Ha realizado los últimos 15 días viajes Internacionales?</strong></v-label>
                                    <v-radio-group v-model="formSeguimiento.afirmacion_viaje_internacional">
                                        <v-radio
                                            v-for="n in afirmacion"
                                            :key="n.id"
                                            :label="`${n.nombre}`"
                                            :value="n.id"
                                            color="info"
                                            required
                                        ></v-radio>
                                    </v-radio-group>
                                    <v-textarea label="Descripción" v-show="parseInt(formSeguimiento.afirmacion_viaje_internacional) === 1" v-model="formSeguimiento.descripcion_viaje_internacional"></v-textarea>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-label><strong>¿Ha realizado los últimos 15 días viajes Nacionales?</strong></v-label>
                                    <v-radio-group v-model="formSeguimiento.afirmacion_viaje_nacional">
                                        <v-radio
                                            v-for="n in afirmacion"
                                            :key="n.id"
                                            :label="`${n.nombre}`"
                                            :value="n.id"
                                            color="info"
                                            required
                                        ></v-radio>
                                    </v-radio-group>
                                    <v-textarea label="Descripción" v-show="parseInt(formSeguimiento.afirmacion_viaje_nacional) === 1" v-model="formSeguimiento.descripcion_viaje_nacional"></v-textarea>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-label><strong>Tipo de Contacto</strong></v-label>
                                    <v-checkbox v-model="formSeguimiento.tipo_contatos" color="info" label="Sociales" value="Sociales"></v-checkbox>
                                    <v-checkbox v-model="formSeguimiento.tipo_contatos" color="info" label="Familiares" value="Familiares"></v-checkbox>
                                    <v-checkbox v-model="formSeguimiento.tipo_contatos" color="info" label="Personal de Salud." value="personal_salud"></v-checkbox>
                                    <v-checkbox v-model="formSeguimiento.tipo_contatos" color="info" label="De vuelo" value="vuelo"></v-checkbox>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-label><strong>Tipo de Vivienda</strong></v-label>
                                    <v-radio-group v-model="formSeguimiento.tipo_vivienda">
                                        <v-radio key="casa" label="Casa" value="casa" color="info" required></v-radio>
                                        <v-radio key="apartamento" label="Apartamento" value="apartamento" color="info" required></v-radio>
                                    </v-radio-group>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-label><strong>Habitación individual</strong></v-label>
                                    <v-radio-group v-model="formSeguimiento.habitacion_individual">
                                        <v-radio
                                            v-for="n in afirmacion"
                                            :key="n.id"
                                            :label="`${n.nombre}`"
                                            :value="n.id"
                                            color="info"
                                            required
                                        ></v-radio>
                                    </v-radio-group>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-label><strong>Clasificación del Caso</strong></v-label>
                                    <v-radio-group v-model="formSeguimiento.clasificacion_caso">
                                        <v-radio key="probable" label="Probable" value="probable" color="info" required></v-radio>
                                        <v-radio key="descartado" label="Descartado" value="descartado" color="info" required></v-radio>
                                        <v-radio key="confirmado" label="Confirmado" value="confirmado" color="info" required></v-radio>
                                    </v-radio-group>
                                </v-flex>
                                <v-flex xs12 sm4 md4>
                                    <v-label><strong>Docente asignado a dictar clases</strong></v-label>
                                    <v-radio-group v-model="formSeguimiento.docenteClase">
                                        <v-radio
                                            v-for="n in docenteClase"
                                            :key="n.id"
                                            :label="`${n.nombre}`"
                                            :value="n.id"
                                            color="info"
                                            required
                                        ></v-radio>
                                    </v-radio-group>
                                </v-flex>
                                <v-flex xs12 sm12 md12>
                                    <v-textarea label="Observaciones" v-model="formSeguimiento.observaciones" required></v-textarea>
                                </v-flex>
                                <v-btn  color="success" type="submit" >Validar</v-btn>
                            </v-layout>
                            </form>
                        </v-card-text>
                    </v-card>
                </v-flex>




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
                afirmacion: [{id:1,nombre:'SI'}, {id:0,nombre:'NO'}],
                docenteClase: [{id:1,nombre:'SI'}, {id:0,nombre:'NO'}],
                radioGroup:1,
                tipoRegistro:'',
                nameFile: '',
                file: '',
                cedula_paciente: 'null',
                paciente: '',
                entidad: '',
                proveedore: [],
                municipios: [],
                formSeguimiento:{
                    docenteClase: '',
                    paciente_id: null,
                    nacionalidad:"",
                    municipio_ocurrencia_id:null,
                    afirmacion_viaje_internacional:null,
                    descripcion_viaje_internacional:"",
                    afirmacion_viaje_nacional:null,
                    descripcion_viaje_nacional:"",
                    tipo_contatos:[],
                    tipo_vivienda:null,
                    habitacion_individual:null,
                    clasificacion_caso:null,
                    observaciones:"",
                }
            }
        },
        computed: {
            ...mapGetters(['can']),
        },
        mounted() {
            moment.locale('es');
            this.proveedores();
            this.fetchMunicipios();
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
            search_paciente(tipo) {
                this.tipoRegistro = tipo;
                if (!this.cedula_paciente) return;

                axios.get(`/api/paciente/showEnabled/${this.cedula_paciente}`)
                    .then((res) => {
                        if (res.data.paciente) {
                            this.paciente = res.data.paciente;
                            this.sede_selected = this.paciente.IPS;
                            this.paciente = res.data.paciente;
                            this.formSeguimiento.paciente_id = res.data.paciente.id;
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
                    this.tipoRegistro = '',
                    this.cedula_paciente = '',
                    this.paciente = {
                        Primer_Nom: '',
                        Primer_Ape: '',
                        Tipo_Doc: '',
                        Num_Doc: '',
                        Edad_Cumplida: ''
                    }
                for (const prop of Object.getOwnPropertyNames(this.formSeguimiento)) {
                    this.formSeguimiento[prop] = null;
                }
                this.formSeguimiento.tipo_contatos = [];
            },
            fetchMunicipios() {
                axios.get('/api/municipio/lista')
                    .then(res => {
                        console.log(res.data);
                        this.municipios = res.data
                    })
            },
            guardarSeguimiento(){
            axios.post('/api/seguimiento/create',this.formSeguimiento).then(res => {
                if(res.status === 200){
                    this.$alerSuccess(res.data.message);
                    this.clearFields();
                }
            })
            .catch(e => {
                this.$alerError('El paciente ya cuenta con un proceso')
            })
            }
        }
    }

</script>

<style>

</style>
