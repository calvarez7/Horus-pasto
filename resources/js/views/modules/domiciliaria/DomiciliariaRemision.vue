<template>
    <v-container fluid pa-0>
        <v-form @submit.prevent="search_paciente()">
            <v-layout row>
                <v-flex xs5 offset-md8>
                    <v-card>
                        <v-card-text>
                            <v-form @submit.prevent="search_paciente()">
                                <v-layout row wrap>
                                    <v-flex xs10>
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
                        </v-card-text>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-form>
        <v-layout grow pa-1>
            <v-flex xs12>
                <v-card>
                    <v-card-title>
                        <span class="title layout justify-center font-weight-light">Datos del Paciente</span>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-card-title primary-title>
                        <v-container grid-list-md fluid class="pa-1">
                            <v-layout row wrap>
                                <v-flex xs1>
                                    <v-select :items="Tipo_Doc" v-model="paciente.Tipo_Doc" label="Tipo Doc">
                                    </v-select>
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
                                <v-btn color="warning" @click="actualizarDatosPersonales()" round>Actualizar
                                </v-btn>
                            </v-layout>
                        </v-container>
                    </v-card-title>
                </v-card>
            </v-flex>
        </v-layout>
        <v-layout grow pa-0>
            <v-flex xs12>
                <v-card>
                    <v-card-title>
                        <span class="title layout justify-center font-weight-light">Formulario de Remision</span>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-card-title primary-title>
                        <v-container grid-list-md fluid class="pa-1">
                            <v-layout wrap align-center>
                                <v-flex xs4>
                                    <v-autocomplete label="Institución Remite" :items="proveedore" item-text="Nombre"
                                        item-value="id" v-model="formRemision.insRemite">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field v-model="formRemision.telIPS" label="Teléfono IPS">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs3>
                                    <v-select :items="medic" v-model="formRemision.medRemite"
                                        @change="formRemision.medicoSumi = ''; formRemision.otroMedico = ''"
                                        label="Tipo de Medico">
                                    </v-select>
                                </v-flex>
                                <v-layout v-if="formRemision.medRemite === 'Medico SUMIMEDICAL'">
                                    <v-flex xs12>
                                        <v-autocomplete label="Medico Remite" :items="medicoSumi" item-text="name"
                                            item-value="id" v-model="formRemision.medicoSumi">
                                        </v-autocomplete>
                                    </v-flex>
                                </v-layout>
                                <v-layout v-if="formRemision.medRemite === 'Otro'">
                                    <v-flex xs12>
                                        <v-text-field label="Medico Remite" v-model="formRemision.otroMedico">
                                        </v-text-field>
                                    </v-flex>
                                </v-layout>
                                <v-flex xs2>
                                    <v-autocomplete v-model="formRemision.departamento" label="Departamento"
                                        :items="departamento" item-text="departamento">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs2>
                                    <v-autocomplete v-model="formRemision.ciudad" label="Ciudad Principal"
                                        :items="departamento" item-text="municipio">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs1>
                                    <v-text-field v-model="formRemision.barrio" label="Barrio">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs1>
                                    <v-select v-model="formRemision.zona" label="Zona" :items="zona">
                                    </v-select>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field v-model="formRemision.direcAtencion" label="Dirección Atención">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field v-model="formRemision.nomCuidador" label="Nombre Cuidador">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs1>
                                    <v-text-field v-model="formRemision.celCuidador" label="Celular Cuidador">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs2>
                                    <v-text-field v-model="formRemision.nomResponsable" label="Nombre Responsable">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs1>
                                    <v-text-field v-model="formRemision.calResponsable" label="Cel. Responsable">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs1>
                                    <v-select v-model="formRemision.area" label="Area" :items="area">
                                    </v-select>
                                </v-flex>
                                <v-flex xs5>
                                    <v-autocomplete v-model="formRemision.dx" append-icon="search" chips multiple
                                        :items="cieConcat" item-disabled="customDisabled" item-text="nombre"
                                        item-value="id" label="Diagnóstico">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs3>
                                    <v-select :items="programa" v-model="formRemision.programa" @change="formRemision.agudo = '';
                                     formRemision.tfg = ''; formRemision.covid = ''" label="Programa Remite">
                                    </v-select>
                                </v-flex>
                                <v-btn color="success" round v-show="formRemision.programa === 'Cuidados Paliativos'
                                        || formRemision.programa === 'Crónicos en Casa'
                                        || formRemision.programa === 'Clínica de Heridas'
                                        || formRemision.programa === 'Programa Respira'
                                        " @change="formRemision.agudo = ''" @click="dialogBarthe=true">Índice Barthel
                                </v-btn>
                                <v-flex xs2 v-show="formRemision.programa === 'Agudos'">
                                    <v-select :items="agudo" v-model="formRemision.agudo" @change="formRemision.tfg = '';
                                    formRemision.Confusion= ''; formRemision.Fresuencia = ''; formRemision.sistolica = '';
                                    formRemision.diastolica = ''; formRemision.edad = ''" label="En caso Agudo">
                                    </v-select>
                                </v-flex>
                                <v-flex xs1 v-show="formRemision.agudo === 'antibioticoterapia TFG'">
                                    <v-text-field label="Valor TFG" v-model="formRemision.tfg" type="number">
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs2 v-show="formRemision.programa === 'Estrategia Covid'">
                                    <v-select :items="covid" v-model="formRemision.covid" label="Estrategia Covid">
                                    </v-select>
                                </v-flex>
                                <v-flex xs2>
                                    <v-select v-show="formRemision.agudo === 'Infecciones Respiratorias Bajas'"
                                        :items="check" v-model="formRemision.Confusion" label="Presencia Confusion">
                                    </v-select>
                                </v-flex>
                                <v-flex xs2>
                                    <v-select v-show="formRemision.agudo === 'Infecciones Respiratorias Bajas'"
                                        :items="check" v-model="formRemision.Fresuencia" label="Fresuencia Res > 30">
                                    </v-select>
                                </v-flex>
                                <v-flex xs2>
                                    <v-select v-show="formRemision.agudo === 'Infecciones Respiratorias Bajas'"
                                        :items="check" v-model="formRemision.sistolica" label="Presion sistolica < 90">
                                    </v-select>
                                </v-flex>
                                <v-flex xs2>
                                    <v-select v-show="formRemision.agudo === 'Infecciones Respiratorias Bajas'"
                                        :items="check" v-model="formRemision.diastolica"
                                        label="Presion diastolica <= 60">
                                    </v-select>
                                </v-flex>
                                <v-flex xs2>
                                    <v-select v-show="formRemision.agudo === 'Infecciones Respiratorias Bajas'"
                                        :items="check" v-model="formRemision.edad" label="edad > 65">
                                    </v-select>
                                </v-flex>
                                <v-flex xs12>
                                    <label>El paciente es alérgico a algún medicamento?</label>
                                    <v-textarea solo name="input-7-4">
                                    </v-textarea>
                                </v-flex>
                                <v-flex xs2>
                                    <!-- <label>Anexo</label> -->
                                    <input type="file" />
                                </v-flex>
                                <v-btn color="success" @click="generarRemision()" round>Generar Remision
                                </v-btn>
                            </v-layout>
                            <v-dialog v-model="dialogBarthe" persistent max-width="600px">
                                <v-card>
                                    <v-card-title class="headline purple" style="color:white">
                                        <span class="title layout justify-center font-weight-light">Indice Barthel</span>
                                    </v-card-title>
                                    <v-card-text>
                                        <v-container grid-list-md>
                                            <form @submit.prevent="barthel()">
                                                <v-layout wrap>
                                                    <v-flex xs12 sm6 md4>
                                                        <v-text-field label="Legal first name*" required></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 sm6 md4>
                                                        <v-text-field label="Legal middle name"
                                                            hint="example of helper text only on focus"></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 sm6 md4>
                                                        <v-text-field label="Legal last name*"
                                                            hint="example of persistent helper text" persistent-hint
                                                            required></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 sm6>
                                                        <v-autocomplete
                                                            :items="['Skiing', 'Ice hockey', 'Soccer', 'Basketball', 'Hockey', 'Reading', 'Writing', 'Coding', 'Basejump']"
                                                            label="Interests" multiple></v-autocomplete>
                                                    </v-flex>
                                                </v-layout>
                                            </form>
                                        </v-container>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="blue darken-1" flat @click="dialogBarthe = false">Close</v-btn>
                                        <v-btn color="blue darken-1" flat @click="dialogBarthe = false">Save</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </v-container>
                    </v-card-title>
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
        created() {
            this.citaPaciente = this.$route.query.cita_paciente;
        },
        data() {
            return {
                dialogBarthe: false,
                departamento: [],
                medic: ['Medico SUMIMEDICAL', 'Otro'],
                cie10s: [],
                zona: ['Norte', 'Centro', 'Sur', 'Oriente', 'Occidente'],
                area: ['Rural', 'Urbana'],
                depart: ['Antioquia', 'Choco'],
                Tipo_Doc: ['CC', 'CE', 'PA', 'RC', 'TI'],
                cedula_paciente: '1000083383',
                paciente: '',
                cc_paciente: '',
                agudo: ['N/A', 'ambas', 'antibioticoterapia TFG', 'Infecciones Respiratorias Bajas'],
                check: ['Si', 'no'],
                programa: ['Agudos', 'Clínica de Heridas', 'Crónicos en Casa', 'Cuidados Paliativos',
                    'Estrategia Covid', 'Programa Respira'
                ],
                covid: ['Crónicos', 'Gestantes', 'Sospechosos Covid', 'Agudos', 'Aplicación de Medicamentos',
                    'Procedimientos'
                ],
                formRemision: {
                    agudo: '',
                    programa: '',
                    covid: '',
                    Confusion: '',
                    Fresuencia: '',
                    sistolica: '',
                    diastolica: '',
                    edad: '',
                    insRemite: '',
                    telIPS: '',
                    medRemite: '',
                    medicoSumi: '',
                    otroMedico: '',
                    tfg: '',
                    departamento: '',
                    ciudad: '',
                    barrio: '',
                    zona: '',
                    direcAtencion: '',
                    nomCuidador: '',
                    celCuidador: '',
                    nomResponsable: '',
                    calResponsable: '',
                    area: '',
                    dx: '',
                },
                proveedore: [],
                medicoSumi: []

            }
        },
        computed: {
            ...mapGetters(['can']),
            cieConcat() {
                return this.cie10Array = this.cie10s.map(cie => {
                    return {
                        id: cie.id,
                        codigo: cie.Codigo_CIE10,
                        nombre: `${cie.Codigo_CIE10} - ${cie.Descripcion}`,
                        customDisabled: false
                    };
                });
            },
        },
        mounted() {
            moment.locale('es');
            this.fetchCie10s();
            this.proveedores();
            this.fechMedicos();
            this.fetchDepartamentos();
        },
        methods: {
            barthel() {
                this.dialogBarthe = false;
            },
            fetchDepartamentos() {
                axios.get('/api/domiciliaria/departamento')
                    .then((res) => {
                        console.log(res.data);
                        this.departamento = res.data
                    })
                    .catch((err) => console.log(err));
            },
            fechMedicos() {
                axios.get('/api/user/medicoSumi')
                    .then((res) => {
                        console.log('Medicos', res.data);
                        this.medicoSumi = res.data
                    })
                    .catch((err) => console.log(err));
            },
            proveedores() {
                axios.get('/api/sedeproveedore/sedeproveedores')
                    .then((res) => {
                        this.proveedore = res.data
                    })
                    .catch((err) => console.log(err));
            },
            fetchCie10s() {
                axios.get("/api/cie10/all").then(res => {
                    this.cie10s = res.data;
                });
            },
            generarRemision() {
                axios.post(`/api/domiciliaria/create/${this.paciente.id}`, this.formRemision)
                    .then((res) => {
                        console.log('Remision', res.data);
                        // this.especialidades = res.data
                    });
            },
            actualizarDatosPersonales() {
                axios.put(`/api/paciente/edit_ubicacion_data/${this.paciente.id}`, this.paciente)
                    .then((res) => {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'bottom-end',
                            background: '#0064ff',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            onOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })

                        Toast.fire({
                            icon: 'success',
                            title: '<span style="color:#fff">Datos Actualizados con Exito! !<span>'
                        });
                    })

            },
            fetchEspecialidades() {
                axios.get(`/api/agenda/agendaDisponible/especialidades`)
                    .then((res) => {
                        this.especialidades = res.data
                    });
            },
            search_paciente() {
                if (!this.cedula_paciente) return;

                this.fetchEspecialidades();
                axios.get(`/api/paciente/showEnabled/${this.cedula_paciente}`)
                    .then((res) => {
                        console.log('datos_paci', res.data);
                        if (res.data.paciente) {
                            this.paciente = res.data.paciente;
                            this.sede_selected = this.paciente.IPS;
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
