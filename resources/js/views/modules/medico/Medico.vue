<template>
    <v-container pa-0 fluid grid-list-md>
        <v-layout row wrap>
            <v-dialog v-model="dialog" persistent max-width="1000px">
                <v-card>
                    <v-flex>
                        <v-card flat>
                            <v-card-title class="headline primary" style="color:white">Atención de Paciente no
                                Programado</v-card-title>
                            <v-form>
                                <v-container>
                                    <v-layout wrap align-center>
                                        <v-flex xs2>
                                            <v-text-field v-model="paciente.Ut" readonly label="Entidad"></v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field v-model="paciente.NombreIPS" readonly label="IPS">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs4>
                                            <v-text-field v-model="paciente.Medicofamilia" readonly
                                                label="Medico Familia">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs4>
                                            <v-text-field v-model="paciente.Primer_Nom" readonly label="Nombre">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs3>
                                            <v-text-field v-model="paciente.Primer_Ape" readonly label="Apellido">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs1>
                                            <v-text-field v-model="paciente.Tipo_Doc" readonly label="Tipo Documento">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs3>
                                            <v-text-field v-model="paciente.Num_Doc" readonly label="Número Documento">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs1>
                                            <v-text-field v-model="paciente.Edad_Cumplida" readonly label="Edad">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs4>
                                            <v-text-field v-model="paciente.Telefono" label="Telefono"></v-text-field>
                                        </v-flex>
                                        <v-flex xs4>
                                            <v-text-field v-model="paciente.Celular1" label="Celular" type="number"
                                                maxlength="10"
                                                oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                                min="1"></v-text-field>
                                        </v-flex>
                                        <v-flex xs4>
                                            <v-text-field v-model="paciente.Celular2" label="Celular 2 (opcional)">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field v-model="paciente.Correo1" label="Correo"></v-text-field>
                                        </v-flex>
                                        <v-flex xs6>
                                            <v-text-field v-model="paciente.Correo2" label="Correo 2 (opcional)">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6 md8>
                                            <v-autocomplete label="Cup*" :items="cups" item-text="Nombre"
                                                item-value="id" v-model="data.Cup_id" required></v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-autocomplete :items="[
                                            'Atención del parto (puerperio)', 'Atención del recién nacido', 'Atención en planificación familiar', 'Detección de alteraciones De crecimiento y  desarrollo Del menor de diez años', 'Detección de alteración del desarrollo joven', 'Detección de alteraciones del embarazo', 'Detección de alteraciones del adulto', 'Detección de alteraciones de agudeza visual', 'Detección de enfermedad profesional','Teleconsulta','No aplica',
                                            ]" label="Finalidad" append-icon="search" v-model="data.Finalidad">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-flex xs12 sm6 md4>
                                            <v-autocomplete :items="sedes" label="Sede atención" append-icon="search"
                                                item-text="nombre" item-value="id" v-model="data.lugar_atencion">
                                            </v-autocomplete>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-form>
                            <v-container>
                                <v-btn color="success" class="ma-2 white--text" @click="generate()">
                                    Atender Paciente No Programado
                                    <v-icon right dark>send</v-icon>
                                </v-btn>
                            </v-container>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" flat @click="cerrarModal()">Cerrar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-flex>
                </v-card>
            </v-dialog>
            <v-flex xs9>
                <template>
                    <div>
                        <v-toolbar flat color="white">
                            <v-toolbar-title>Mi agenda</v-toolbar-title>
                            <v-divider class="mx-2" inset vertical></v-divider>
                            <v-toolbar-title>Fecha</v-toolbar-title>
                            <v-divider class="mx-2" inset vertical></v-divider>
                            <v-spacer></v-spacer>
                        </v-toolbar>
                        <v-data-table :headers="headers" :items="citas" item-key="index" class="elevation-1"
                            :rows-per-page-items="[{'value':-1}]">
                            <template v-slot:items="props">
                                <td>{{ props.item.Hora_Inicio | time }}</td>
                                <td class="text-xs-right">
                                    {{ props.item.Primer_Nom }} {{ props.item.SegundoNom }}
                                    {{ props.item.Primer_Ape }} {{ props.item.Segundo_Ape }}
                                </td>
                                <td class="text-xs-right">{{ props.item.Num_Doc }}</td>
                                <td class="text-xs-right">{{ props.item.Edad_Cumplida }}</td>
                                <td class="text-xs-right">{{ props.item.Tipo_agenda }}</td>
                                <td class="text-xs-right">{{ props.item.Estado }}</td>
                                <td class="text-xs-right">{{ props.item.salud_ocupacional }}</td>
                                <td class="justify-center layout px-0">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn text v-if="props.item.CP_Estado_id == 4"
                                                :disabled="disable(props.item)" icon color="green" dark v-on="on">
                                                <v-icon @click="atenderPaciente(props.item)">how_to_reg</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Atender Paciente</span>
                                    </v-tooltip>
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn text v-if="props.item.CP_Estado_id == 8"
                                                :disabled="disable(props.item)" icon color="orange" dark v-on="on">
                                                <v-icon @click="reditectToAtender(props.item)">how_to_reg</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Regresar</span>
                                    </v-tooltip>
                                    <!-- <v-tooltip top>
                                        <template v-slot:activator="{ on }" v-if="props.item.CP_Estado_id == 9">
                                            <v-btn text icon color="blue" dark v-on="on">
                                                <v-icon @click="printhc(props.item)">assignment_turned_in</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Historial</span>
                                    </v-tooltip> -->
                                    <v-tooltip top v-if="can('print.sst')">
                                        <template v-slot:activator="{ on }" v-if="props.item.CP_Estado_id == 9">
                                            <v-btn text icon color="blue" dark v-on="on">
                                                <v-icon @click="imprimirSST(props.item.cita_paciente_id)">
                                                    assignment_turned_in</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Historial SST</span>
                                    </v-tooltip>
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn text v-if="props.item.CP_Estado_id == 4"
                                                :disabled="disable(props.item)" icon color="red" dark v-on="on">
                                                <v-icon @click="inasistioPaciente(props.item)">person_add_disabled
                                                </v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Inasistida</span>
                                    </v-tooltip>
                                </td>
                            </template>
                            <template v-slot:no-data>
                                <!-- <v-btn color="primary" @click="initialize">Reset</v-btn> -->
                            </template>
                        </v-data-table>
                    </div>
                </template>
                <template>
                    <div class="text-center">
                        <v-dialog v-model="preloadmedico" persistent width="300">
                            <v-card color="primary" dark>
                                <v-card-text>
                                    Tranquilo procesamos tu solicitud !
                                    <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                                </v-card-text>
                            </v-card>
                        </v-dialog>
                    </div>
                </template>
                <template v-if="notProgramed.length > 0">
                    <div>
                        <v-toolbar flat color="white">
                            <v-toolbar-title>Citas no programadas</v-toolbar-title>
                            <v-divider class="mx-2" inset vertical></v-divider>
                            <v-spacer></v-spacer>
                        </v-toolbar>
                        <v-data-table :headers="headersNotProgramed" :items="notProgramed" class="elevation-1"
                            item-key="index" :rows-per-page-items="[{'value':-1}]">
                            <template v-slot:items="props">
                                <td>{{ props.item.Fecha }}</td>
                                <td>{{ props.item.Tipo_cita }}</td>
                                <td class="text-xs-right">
                                    {{ props.item.Primer_Nom }} {{ props.item.SegundoNom }}
                                    {{ props.item.Primer_Ape }} {{ props.item.Segundo_Ape }}
                                </td>
                                <td class="text-xs-right">{{ props.item.Num_Doc }}</td>
                                <td class="text-xs-right">{{ props.item.Edad_Cumplida }}</td>
                                <td class="text-xs-right">{{ props.item.Estado }}</td>
                                <td class="justify-center layout px-0">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn text v-if="props.item.CP_Estado_id == 8"
                                                :disabled="disable(props.item)" icon color="orange" dark v-on="on">
                                                <v-icon @click="reditectToAtender(props.item)">how_to_reg</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Regresar</span>
                                    </v-tooltip>
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn v-if="props.item.CP_Estado_id == 9" text icon color="blue" dark
                                                v-on="on">
                                                <v-icon @click="printhc(props.item)">assignment_turned_in</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Historial</span>
                                    </v-tooltip>
                                </td>
                            </template>
                            <template v-slot:no-data>
                                <!-- <v-btn color="primary" @click="initialize">Reset</v-btn> -->
                            </template>
                        </v-data-table>
                    </div>
                </template>
            </v-flex>
            <v-flex xs3>
                <v-card max-height="100%" class="mb-3" v-if="can('medico.no-programada.enter')">
                    <v-card-title>
                        <span class="title layout justify-center font-weight-light">Buscar Paciente cita no
                            programada</span>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-card-text>
                        <v-container grid-list-md fluid class="pa-0">
                            <v-layout wrap align-center justify-center>
                                <v-flex xs12>
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
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>
<script>
    // import { EventBus } from "../../event-bus.js";
    import moment from "moment";
    import Swal from 'sweetalert2';
    import {
        mapGetters
    } from 'vuex';
    export default {
        name: "Medico",
        data() {
            return {
                tipoEspecialidad: 0,
                cups: [],
                preloadmedico: false,
                citas: [],
                notProgramed: [],
                cita: "",
                citaPaciente: {},
                type: {
                    type: "Ingreso"
                },
                citaPacienteId: "0",
                cita_paciente_id: '',
                cedula_paciente: '',
                actividades: [],
                paciente: {},
                dialog: false,
                data: {
                    entidademite: "",
                    Finalidad: "",
                    Actividad: '',
                    Cup_id: '',
                    Tipocita_id: 2,
                    lugar_atencion: ''
                },
                headersNotProgramed: [{
                        text: "Fecha",
                        value: "Fecha",
                        align: "center",
                        sortable: true
                    },
                    {
                        text: "Tipo Cita",
                        //value: "Fecha",
                        align: "center",
                        sortable: true
                    },
                    {
                        text: "Paciente",
                        value: "Primer_Nom",
                        align: "center"
                    },
                    {
                        text: "Cédula",
                        value: "Num_Doc",
                        align: "center"
                    },
                    {
                        text: "Edad",
                        value: "Edad_Cumplida",
                        align: "center"
                    },
                    {
                        text: "Estado",
                        value: "CP_Estado_id",
                        align: "center"
                    },
                    {
                        text: "Acciones",
                        value: "",
                        align: "center"
                    }
                ],
                headers: [{
                        text: "Hora",
                        value: "Hora_Inicio",
                        align: "center",
                        sortable: true
                    },
                    {
                        text: "Paciente",
                        value: "Primer_Nom",
                        align: "center"
                    },
                    {
                        text: "Cédula",
                        value: "Num_Doc",
                        align: "center"
                    },
                    {
                        text: "Edad",
                        value: "Edad_Cumplida",
                        align: "center"
                    },
                    {
                        text: "Tipo Cita",
                        value: "Tipo_Cita",
                        align: "center"
                    },
                    {
                        text: "Estado",
                        value: "CP_Estado_id",
                        align: "center"
                    },
                    {
                        text: "Tipo",
                        align: "center"
                    },
                    {
                        text: "Acciones",
                        value: "",
                        align: "center"
                    }
                ],
                dataFilter: {
                    fecha_inicio: moment().format("YYYY-MM-DD"),
                    documento: '',
                    tipocita: ''
                },
                tipocitas: [],
                sedes: [],
                filter: false,
                servicios: ['Ecografia', 'Tomografia', 'Radiografia ', 'Mamografia', 'Ginecologica',
                    'Doppler', 'Intervencionista', 'Obstetrica', 'Biopsias', 'Drenajes', 'Ecocardiografia'
                ]

            };
        },
        filters: {
            time: time => {
                moment.locale("es");
                return moment(time).format("HH:mm:ss");
            }
        },
        mounted() {
            this.cronogramacitashoy();
            this.getNotProgramed();
            this.fetchCups();
            this.tipoCita();
            this.sedesSumimedical();
        },
        computed: {
            ...mapGetters(['can'])
        },
        methods: {
            fetchCups() {
                axios.get('/api/cups/all')
                    .then(res => this.cups = res.data.map((cup) => {
                        return {
                            id: cup.id,
                            Nombre: `${cup.Codigo} - ${cup.Nombre}`
                        }
                    }));
            },
            tipoCita() {
                axios.get('/api/imagenologia/tipoCita').then(res => {
                    this.tipocitas = res.data
                });
            },
            async isImagenologia(cita) {
                return await axios.post('/api/cita/checkEspecialidad', {
                        cita_paciente: cita.cita_paciente_id
                    })
                    .then(res => {
                        if (res.data.Nombre == 'Imagenologia') {
                            this.isImgRadium = true;
                            return true
                        }
                        return false
                    })
                    .catch(err => console.log(err.response))
            },

            async printhc(cita) {
                var pdf = {};
                if (await this.isImagenologia(cita)) {
                    axios.post("/api/historiapaciente/gethistoriaradium", {
                        Num_Doc: cita.Num_Doc
                    }).then(res => {
                        this.historiapaciente = res.data.find(h => h.Citapaciente_id == cita
                            .cita_paciente_id);
                        if (!this.historiapaciente) {
                            swal({
                                title: "Historial",
                                text: "El historial no puede ser mostrado, ingrese al modulo historico de imagenologia.",
                                timer: 4000,
                                icon: "error",
                                buttons: false
                            });
                        } else {

                            let especialidad = '';
                            let hora = '';

                            (cita.Especialidad) ? especialidad = cita.Especialidad: especialidad = this.data
                                .Actividad;
                            (cita.Hora_Inicio) ? hora = cita.Especialidad: hora = cita.Fecha;

                            pdf = {
                                type: "radium",
                                FechaInicio: hora,
                                Nombrepaciente: this.historiapaciente.Nombrepaciente,
                                Hallazgos: this.historiapaciente.Hallazgos,
                                Conclusion: this.historiapaciente.Conclusion,
                                Indicacion: this.historiapaciente.Indicacion,
                                Notaclaratoria: this.historiapaciente.Notaclaratoria,
                                Num_Doc: this.historiapaciente.Num_Doc,
                                Fecha_Naci: this.historiapaciente.Fecha_Naci,
                                Tipo_Afiliado: this.historiapaciente.Tipo_Afiliado,
                                Departamento: this.historiapaciente.Departamento,
                                Telefono: this.historiapaciente.Telefono,
                                Mpio_Afiliado: this.historiapaciente.Mpio_Afiliado,
                                Ocupacion: this.historiapaciente.Ocupacion,
                                Nombreacompanante: this.historiapaciente.Nombreacompanante,
                                Telefonoacompanante: this.historiapaciente.Telefonoacompanante,
                                Nombreresponsable: this.historiapaciente.Nombreresponsable,
                                Telefonoresponsable: this.historiapaciente.Telefonoresponsable,
                                Parentesco: this.historiapaciente.Parentesco,
                                Aseguradora: this.historiapaciente.Aseguradora,
                                Tipovinculacion: this.historiapaciente.Tipovinculacion,
                                Vivecon: this.historiapaciente.Vivecon,

                                Edad_Cumplida: this.historiapaciente.Edad_Cumplida,
                                Sexo: this.historiapaciente.Sexo,
                                Dx: this.historiapaciente.Dx,
                                id: this.historiapaciente.id,
                                Firma: this.historiapaciente.Firma
                            };

                            this.printPDF(pdf);
                        }
                    });
                } else {
                    this.preloadmedico = true
                    axios.post("/api/historiapaciente/gethistoria", {
                        Num_Doc: cita.Num_Doc
                    }).then(res => {
                        this.preloadmedico = false;
                        this.historiapaciente = res.data.find(h => h.id == cita.cita_paciente_id);
                        if (!this.historiapaciente) {
                            swal({
                                title: "Historial",
                                text: "El historial no puede ser mostrado",
                                timer: 2000,
                                icon: "error",
                                buttons: false
                            });
                        } else {

                            let especialidad = '';
                            let hora = '';

                            (cita.Especialidad) ? especialidad = cita.Especialidad: especialidad = this.data
                                .Actividad;
                            (cita.Hora_Inicio) ? hora = cita.Especialidad: hora = cita.Fecha;

                            pdf = {
                                type: "test",
                                Abdomen: this.historiapaciente.Abdomen,
                                Actividadfisica: this.historiapaciente.Actividadfisica,
                                Agudezavisual: this.historiapaciente.Agudezavisual,
                                Altonivelestres: this.historiapaciente.Altonivelestres,
                                Antecedentes: this.historiapaciente.Antecedentes,

                                Patologiacancelactual: this.historiapaciente.Patologiacancelactual,
                                fdxcanceractual: this.historiapaciente.fdxcanceractual,
                                flaboratoriopatologia: this.historiapaciente.flaboratoriopatologia,
                                tumorsegunbiopsia: this.historiapaciente.tumorsegunbiopsia,
                                localizacioncancer: this.historiapaciente.localizacioncancer,
                                Dukes: this.historiapaciente.Dukes,
                                gleason: this.historiapaciente.gleason,
                                Her2: this.historiapaciente.Her2,
                                estadificacioninicial: this.historiapaciente.estadificacioninicial,
                                fechaestadificacion: this.historiapaciente.fechaestadificacion,

                                Antropometricas: this.historiapaciente.Antropometricas,
                                Atendido_Por: this.historiapaciente.Atendido_Por,
                                Especialidad: this.historiapaciente.Especialidad,
                                Registromedico: this.historiapaciente.Registromedico,
                                Cabezacuello: this.historiapaciente.Cabezacuello,
                                Cantidadlicor: this.historiapaciente.Cantidadlicor,
                                Cardiopulmonar: this.historiapaciente.Cardiopulmonar,
                                Cardiovascular: this.historiapaciente.Cardiovascular,
                                Cédula: this.historiapaciente.Cédula,
                                cirujiaoncologica: this.historiapaciente.cirujiaoncologica,
                                Condiciongeneral: this.historiapaciente.Condiciongeneral,
                                Consumopsicoactivo: this.historiapaciente.Consumopsicoactivo,
                                Datetimeingreso: this.historiapaciente.Datetimeingreso,
                                Datetimesalida: this.historiapaciente.Datetimesalida,
                                Departamento: this.historiapaciente.Departamento,
                                Destinopaciente: this.historiapaciente.Destinopaciente,
                                Diagnostico_Principal: this.historiapaciente.Diagnostico_Principal,
                                Diagnostico_Secundario: this.historiapaciente.Diagnostico_Secundario,
                                Dietasaludable: this.historiapaciente.Dietasaludable,
                                Direccion_Residencia: this.historiapaciente.Direccion_Residencia,
                                Duermemenoseishoras: this.historiapaciente.Duermemenoseishoras,
                                Duracion: this.historiapaciente.Duracion,
                                Edad: this.historiapaciente.Edad,
                                Edad_Cumplida: this.historiapaciente.Edad,
                                Endocrinologico: this.historiapaciente.Endocrinologico,
                                Enfermedadactual: this.historiapaciente.Enfermedadactual,
                                Entidademite: this.historiapaciente.Entidademite,
                                Estilovidaobservaciones: this.historiapaciente.Estilovidaobservaciones,
                                Examenmama: this.historiapaciente.Examenmama,
                                Examenmental: this.historiapaciente.Examenmental,
                                Extremidades: this.historiapaciente.Extremidades,
                                Fecha_Nacimiento: this.historiapaciente.Fecha_Nacimiento,
                                Fecha_solicita: this.historiapaciente.Fecha_solicita,
                                Finalidad: this.historiapaciente.Finalidad,
                                fincirujia: this.historiapaciente.fincirujia,
                                finradioterapia: this.historiapaciente.finradioterapia,
                                Firma: this.historiapaciente.Firma,
                                Frecucardiaca: this.historiapaciente.Frecucardiaca,
                                Frecuenciasemana: this.historiapaciente.Frecuenciasemana,
                                Frecurespiratoria: this.historiapaciente.Frecurespiratoria,
                                Fumacantidad: this.historiapaciente.Fumacantidad,
                                Fumadorpasivo: this.historiapaciente.Fumadorpasivo,
                                Fumainicio: this.historiapaciente.Fumainicio,
                                Gastrointestinal: this.historiapaciente.Gastrointestinal,
                                Genitourinario: this.historiapaciente.Genitourinario,
                                id: this.historiapaciente.id,
                                iniciocirujia: this.historiapaciente.iniciocirujia,
                                inicioradioterapia: this.historiapaciente.inicioradioterapia,
                                intencion: this.historiapaciente.intencion,
                                Laboraen: this.historiapaciente.Laboraen,
                                Licorfrecuencia: this.historiapaciente.Licorfrecuencia,
                                Linfatico: this.historiapaciente.Linfatico,
                                Medicoordeno: this.historiapaciente.Medicoordeno,
                                Motivoconsulta: this.historiapaciente.Motivoconsulta,
                                Municipio_Afiliado: this.historiapaciente.Municipio_Afiliado,
                                ncirujias: this.historiapaciente.ncirujias,
                                Neurologico: this.historiapaciente.Neurologico,
                                Nombre: this.historiapaciente.Nombre,
                                Norefiere: this.historiapaciente.Norefiere,
                                nsesiones: this.historiapaciente.nsesiones,
                                Observaciones: this.historiapaciente.Observaciones,
                                Oftalmologico: this.historiapaciente.Oftalmologico,
                                Ojosfondojos: this.historiapaciente.Ojosfondojos,
                                Osteomioarticular: this.historiapaciente.Osteomioarticular,
                                Osteomuscular: this.historiapaciente.Osteomuscular,
                                Otorrinoralingologico: this.historiapaciente.Otorrinoralingologico,
                                Otros_Signos_Vitales: this.historiapaciente.Otros_Signos_Vitales,
                                Pielfraneras: this.historiapaciente.Pielfraneras,
                                Planmanejo: this.historiapaciente.Planmanejo,
                                Presión_Arterial: this.historiapaciente.Presión_Arterial,
                                Psicoactivocantidad: this.historiapaciente.Psicoactivocantidad,
                                Pulsosperifericos: this.historiapaciente.Pulsosperifericos,
                                recibioradioterapia: this.historiapaciente.recibioradioterapia,
                                Recomendaciones: this.historiapaciente.Recomendaciones,
                                Reflejososteotendinos: this.historiapaciente.Reflejososteotendinos,
                                Respiratorio: this.historiapaciente.Respiratorio,
                                Resultayudadiagnostica: this.historiapaciente.Resultayudadiagnostica,
                                Sexo: this.historiapaciente.Sexo,
                                Signos_Vitales: this.historiapaciente.Signos_Vitales,
                                Suenoreparador: this.historiapaciente.Suenoreparador,
                                Tactoretal: this.historiapaciente.Tactoretal,
                                Tegumentario: this.historiapaciente.Tegumentario,
                                telefono: this.historiapaciente.Celular,
                                Telefono: this.historiapaciente.Telefono,
                                Timeconsulta: this.historiapaciente.Timeconsulta,
                                tipo_afiliado: this.historiapaciente.Tipo_Afiliado,
                                Tipo_Orden: this.historiapaciente.Tipo_Orden,
                                Tipocita_id: this.historiapaciente.Tipocita_id,
                                Tipodiagnostico: this.historiapaciente.Tipodiagnostico,
                                tratamientoncologico: this.historiapaciente.tratamientoncologico,
                                FechaInicio: hora
                            };

                            this.printPDF(pdf);
                        }
                    });
                }
            },


            searchImagenologia() {
                this.filter = true
                this.cronogramacitashoy()
            },

            cronogramacitashoy() {
                this.preloadmedico = true
                if (this.filter == true) {
                    if (this.dataFilter.tipocita == '') {
                        if (this.dataFilter.documento == '') {
                            this.$alerError("Debe ingresar una cedula o servicio");
                            this.preloadmedico = false
                            return;
                        }
                    }
                }
                if (this.citas !== '') this.citas = []
                axios.post("/api/cita/cronogramahoy", this.dataFilter).then(res => {
                    this.preloadmedico = false
                    res.data.forEach(cita => {
                        if (cita.cita_paciente_id) {
                            this.citas.push(cita);
                        }
                    });
                    let status = this.citas.find(c => c.CP_Estado_id == 8);
                    if (status) {
                        this.citaPacienteId = 1;
                    }
                }).catch(err => {
                    this.preloadmedico = false
                })
            },
            inasistioPaciente(cita) {
                Swal.fire({
                    title: "¿Está Seguro(a)?",
                    text: "Se cancela la cita por inasistencia!",
                    icon: "warning",
                    input: "textarea",
                    inputPlaceholder: "Motivo",
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: "Confirmar",
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                            if (value) {
                                cita.motivo = value;
                                axios.put(`/api/cita/inasistio/${cita.id}`, cita).then(res => {
                                    this.$router.go();
                                });
                                resolve()
                            } else {
                                resolve('El Campo Motivo es Requrido')
                            }
                        })
                    }
                });
            },
            atenderPaciente(cita) {
                this.cita_paciente_id = cita.cita_paciente_id;
                this.num_doc = cita.Num_Doc;
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Se iniciará la atención al paciente!",
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        this.atenderSetTime(this.cita_paciente_id, this.num_doc);
                    }
                });
            },
            disable(cita) {
                if (cita.CP_Estado_id == 9 || cita.CP_Estado_id == 12) {
                    return true;
                } else {
                    return false;
                }
            },
            reditectToAtender(cita) {
                localStorage.setItem("PacienteCedula", cita.Num_Doc);
                if (cita.Especialidad == 'Oncologia') {
                    this.$router.push("/historias/historia_oncologica/motivoOncologico?cita_paciente=" + cita
                        .cita_paciente_id);
                } else if (cita.Especialidad == 'Imagenologia') {
                    this.$router.push("/historias/historiaclinica/imagenologia?cita_paciente=" + cita.cita_paciente_id);
                } else if (cita.Especialidad == 'Oftalmologia') {
                    this.$router.push("/historias/historia_oftamologia/oftamologia?cita_paciente=" + cita
                        .cita_paciente_id);
                } else if (cita.salud_ocupacional == 'Psicologia') {
                    this.$router.push("/historias/historia_salud_ocupacional/psicologia?cita_paciente=" + cita
                        .cita_paciente_id);
                } else if (cita.salud_ocupacional == 'Voz') {
                    this.$router.push("/historias/historia_salud_ocupacional/voz?cita_paciente=" + cita
                        .cita_paciente_id);
                } else if (cita.salud_ocupacional == 'Visiometria') {
                    this.$router.push("/historias/historia_salud_ocupacional/visiometria?cita_paciente=" + cita
                        .cita_paciente_id);
                } else if (cita.salud_ocupacional == 'Consulta Medica') {
                    this.$router.push("/historias/historia_salud_ocupacional/Consultamedica?cita_paciente=" + cita
                        .cita_paciente_id);
                } else if (cita.Especialidad == 'Enfermeria') {
                    this.$router.push("/historias/historia_enfermeria/motivoEnfermeria?cita_paciente=" + cita
                        .cita_paciente_id);
                } else {
                    this.$router.push("/historias/historiaclinica/motivoconsulta?cita_paciente=" + cita
                        .cita_paciente_id);
                }
            },
            search_paciente() {
                if (!this.cedula_paciente) return;

                axios.get(`/api/paciente/showEnabled/${this.cedula_paciente}`)
                    .then((res) => {
                        if (res.data.paciente) {
                            this.paciente = res.data.paciente;
                            this.dialog = true;
                        }
                        if (res.data.message) this.showMessage(res.data.message);
                    });
            },
            cerrarModal() {
                this.dialog = false;
                this.cedula_paciente = '';
                this.paciente = {};
                this.data.Finalidad = '';
                this.data.Actividad = '';
                this.data.lugar_atencion = '';
            },
            generate() {
                if (!this.data.Cup_id) {
                    swal({
                        title: "Consulta no programada",
                        text: "Es necesario añadir Cup!",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                    return;
                }
                if (!this.data.Finalidad) {
                    swal({
                        title: "Consulta no programada",
                        text: "Es necesario seleccionar una finalidad!",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                    return;
                }
                if (!this.data.lugar_atencion) {
                    swal({
                        title: "Consulta no programada",
                        text: "Es necesario seleccionar una sede!",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                    return;
                }
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Se realizará la creación de la orden!",
                    type: "success",
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(async willDelete => {
                    if (willDelete) {
                        await axios
                            .post(
                                `/api/cita/create_cita_paciente/${this.paciente.id}`,
                                this.data
                            ).then(async res => {
                                let citaPaciente = res.data;
                                this.atenderSetTime(citaPaciente.id, this.paciente.Num_Doc, 2);
                            });
                    }
                });
            },
            async atenderSetTime(cita_paciente_id, num_doc, tipo) {
                if (cita_paciente_id && num_doc) {
                    if (tipo != 2) {
                        axios
                            .post("/api/cita/" + cita_paciente_id + "/atender")
                            .then(res => console.log(res));
                    }
                    axios
                        .post("/api/cita/" + cita_paciente_id + "/setTime", this.type)
                        .then(res => console.log(res));
                    await axios
                        .put(`/api/cita/update-state-consulta/${cita_paciente_id}`)
                        .then(res => {
                            this.tipoEspecialidad = res.data.tipo
                        });
                    localStorage.setItem("PacienteCedula", num_doc);
                    let url = "/historias/historiaclinica/motivoconsulta?cita_paciente=";
                    if (this.tipoEspecialidad == 6) {
                        url = "/historias/historiaclinica/imagenologia?cita_paciente=";
                    } else if (this.tipoEspecialidad == 7) {
                        url = "/historias/historia_oncologica/motivoOncologico?cita_paciente=";
                    } else if (this.tipoEspecialidad == 10) {
                        url = "/historias/historia_enfermeria/motivoEnfermeria?cita_paciente=";
                    } else if (this.tipoEspecialidad == 12) {
                        url = "/historias/historia_oftamologia/oftamologia?cita_paciente=";
                    } else if (this.tipoEspecialidad == 13) {
                        url = "/historias/historia_salud_ocupacional/psicologia?cita_paciente=";
                    } else if (this.tipoEspecialidad == 14) {
                        url = "/historias/historia_salud_ocupacional/voz?cita_paciente=";
                    } else if (this.tipoEspecialidad == 15) {
                        url = "/historias/historia_salud_ocupacional/visiometria?cita_paciente=";
                    } else if (this.tipoEspecialidad == 16) {
                        url = "/historias/historia_salud_ocupacional/Consultamedica?cita_paciente=";
                    }
                    this.$router.push(url + cita_paciente_id);

                }
            },
            getNotProgramed() {
                axios.get("/api/cita/notProgramed").then(res => {
                    res.data.notProgramed.forEach(cita => {
                        if (cita.cita_paciente_id) {
                            this.notProgramed.push(cita);
                        }
                    });
                    this.actividades = res.data.activities;
                });
            },
            showMessage(message) {
                swal({
                    title: `${message}`,
                    icon: "warning",
                });
            },
            printPDF(pdf) {
                axios
                    .post("/api/pdf/print-pdf", pdf, {
                        responseType: "arraybuffer"
                    })
                    .then(res => {
                        let blob = new Blob([res.data], {
                            type: "application/pdf"
                        });
                        let link = document.createElement("a");
                        link.href = window.URL.createObjectURL(blob);
                        window.open(link.href, "_blank");
                    });
            },
            clearFilter() {
                this.dataFilter = {
                    fecha_inicio: moment().format("YYYY-MM-DD"),
                    documento: '',
                    tipocita: ''
                }
            },
            sedesSumimedical() {
                axios.get('/api/helpdesk/sedes').then(res => {
                    this.sedes = res.data;
                })
            },
            imprimirSST(id) {
                const pdf = {
                    type: 'historiasst',
                    id: id
                }
                axios
                    .post("/api/pdf/print-pdf", pdf, {
                        responseType: "arraybuffer"
                    })
                    .then(res => {
                        let blob = new Blob([res.data], {
                            type: "application/pdf"
                        });
                        let link = document.createElement("a");
                        link.href = window.URL.createObjectURL(blob);
                        window.open(link.href, "_blank");
                    })
                    .catch(err => console.log(err.response));
            }
        }
    };

</script>
