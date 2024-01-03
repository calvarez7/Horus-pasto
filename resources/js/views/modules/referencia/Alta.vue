<template>
    <v-card>
        <v-card-title>
            <h3>Seguimiento Concurrencia</h3>
            <v-spacer></v-spacer>
            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
        </v-card-title>
        <v-data-table :headers="headers" :items="seguimiento" :search="search">
            <template v-slot:items="props">
                <td >{{ props.item.id }}</td>
                <td>{{ props.item.Primer_Nom }} {{ props.item.SegundoNom }} {{ props.item.Primer_Ape }}
                    {{ props.item.Segundo_Ape }}</td>
                <td>{{ props.item.Num_Doc }}</td>
                <td>{{ props.item.Especialidad_remi }}</td>
                <td>{{ props.item.ips_atencion }}</td>
                <td v-if="props.item.estadocita == '4'">
                    <v-chip color="warning" text-color="white">Pendiente por atención</v-chip>
                </td>
                <td v-if="props.item.estadocita == '12'">
                    <v-chip color="red" text-color="white">Inasistencia</v-chip>
                </td>
                <td v-if="props.item.estadocita == '9'">
                    <v-chip color="succes" text-color="white">Paciente atendido</v-chip>
                </td>
                <td v-if="props.item.estadocita == null">
                    <v-chip color="primary" text-color="white">No tiene cita asignada</v-chip>
                </td>
                <td>{{ props.item.fechacita }}</td>
                <td v-if="props.item.state == '44'">
                    <v-chip color="success" text-color="white">De Alta</v-chip>
                </td>
                <td>
                    <v-btn fab dark small color="success" @click="verConcurrencias(props.item.referencia_id)">
                        <v-icon dark>mdi-eye</v-icon>
                    </v-btn>
                </td>
                <td v-if="props.item.Cup_id == null">
                    <v-btn v-show="can('cita.concurrencia')" fab dark small color="primary"
                        @click="asignarCita(props.item)">
                        <v-icon dark>mdi-account-check</v-icon>
                    </v-btn>
                </td>
                <td v-if="props.item.estadocita == 4">
                    <v-btn small fab disabled>
                        <v-icon dark>mdi-account-check</v-icon>
                    </v-btn>
                </td>
                <td v-if="props.item.estadocita == null">
                    <v-btn  v-show="can('transcripcion.concurrencia')" fab dark small color="warning" @click="dialogtranscripcionData(props.item)">
                        <v-icon dark>mdi-clipboard-flow</v-icon>
                    </v-btn>
                </td>
                <td v-if="props.item.Cita_id != null">
                    <v-btn small fab disabled>
                        <v-icon dark>mdi-clipboard-flow</v-icon>
                    </v-btn>
                    <!-- <v-btn  v-show="can('transcripcion.concurrencia')" fab dark small color="warning" @click="dialogtranscripcionData(props.item)">
                        <v-icon dark>mdi-clipboard-flow</v-icon>
                    </v-btn> -->
                </td>
                <!-- <td v-if="props.item.estadocita == '9'">
                    <v-btn small fab>
                        <v-icon @click="printhc()">assignment_turned_in</v-icon>
                    </v-btn>
                </td> -->
            </template>
            <template v-slot:no-results>
                <v-alert :value="true" color="error" icon="warning">
                    Your search for "{{ search }}" found no results.
                </v-alert>
            </template>
        </v-data-table>

        <v-dialog v-model="dialogverSeguimiento" v-if="dialogverSeguimiento" max-width="1200px">
            <v-card>
                <v-card-title class="headline success" style="color:white">
                    <span class="title layout justify-center font-weight-light">Seguimientos del paciente</span>
                </v-card-title>
                <v-card-text>
                    <v-layout>
                        <v-expansion-panel v-if="concurrenciaseguimientos != ''">
                            <v-expansion-panel-content v-for="(item, index) in concurrenciaseguimientos.seguimientos"
                                :key="index">
                                <template v-slot:header>
                                    <span><b># Seguimiento:</b> {{item.id}}</span>
                                    <span><b>Realizado por:</b> {{item.name}} {{item.apellido}}</span>
                                    <span><b>fecha seguimiento:</b> {{item.created_at.split('.')[0]}}</span>
                                </template>
                                <v-card>
                                    <v-container grid-list-md>
                                        <v-layout row wrap>
                                            <v-flex xs12 md12>
                                                <v-textarea readonly outlined label="Seguimiento"
                                                    :value="item.seguimientoConcurrencia">
                                                </v-textarea>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                        <v-card v-else class="mx-auto" color="error" dark max-width="400">
                            <v-card-text>
                                El paciente actualmente no cuenta con ningun seguimiento, para agregar uno dar click en
                                el icono + verde.
                            </v-card-text>
                        </v-card>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogDate" persistent max-width="1000px">
            <v-card max-height="100%" v-show="true">
                <v-card-title class="headline success lighten-1" dark>
                    <span class="title layout justify-center font-weight-light">Agenda Disponible Médico</span>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs3 px-1>
                            <!-- especialidad -->
                            <v-autocomplete v-model="especialidad_selected" :items="especialidades" item-text="Nombre"
                                item-value="id" label="Especialidades" @input="fetchSedes()"></v-autocomplete>
                        </v-flex>
                        <!-- sede -->
                        <v-flex xs3 px-1>
                            <v-select v-model="sede_selected" :items="sedes" label="Sede" item-text="Nombre"
                                item-value="id" @input="fetchAgendas()"></v-select>
                        </v-flex>
                        <v-flex xs3 px-1>
                            <v-autocomplete v-model="actividad_selected" :items="actividades" item-text="name"
                                item-value="et_id" label="Actividad" @input="fetchAgendas()"></v-autocomplete>
                        </v-flex>
                        <v-flex xs3 px-1>
                            <v-select v-model="medico_selected" :items="medicosSede" label="Médico"></v-select>
                        </v-flex>
                        <v-flex xs3>
                            <!-- fecha -->
                            <v-menu v-model="datePicker" :close-on-content-click="false" :nudge-right="40" lazy
                                transition="scale-transition" offset-y full-width min-width="290px">
                                <template v-slot:activator="{ on }">
                                    <v-combobox v-model="data.fecha_solicitada" label="Fecha solicitada"
                                        prepend-icon="event" readonly v-on="on"></v-combobox>
                                </template>
                                <v-date-picker color="primary" locale="es" v-model="data.fecha_solicitada" :min="today"
                                    :show-current="false" @input="agendaSolicitada()"></v-date-picker>
                            </v-menu>
                        </v-flex>
                        <v-flex xs9 px-2>
                            <v-text-field label="Observación" v-model="datoscita.observaciones">
                            </v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-container grid-list-md fluid class="pa-0">
                        <template>
                            <div class="text-center">
                                <v-dialog v-model="preload_cita" persistent width="300">
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
                        <template>
                            <v-data-table :headers="headersAgenda" :items="agendaDisponible" item-key="name"
                                class="elevation-1" color="primary" :rows-per-page-items="[20,30,50]"
                                rows-per-page-text="Citas por página">
                                <template v-slot:items="props">
                                    <td class="text-xs-center">{{ props.item.hora_inicio | time }}</td>
                                    <td class="text-xs-center">{{ props.item.consultorio }}</td>
                                    <td class="text-xs-center">
                                        <v-btn v-show="can('cita.toAssign')" color="success" fab outline small
                                            @click="agendarCita(props.item)">
                                            <v-icon>how_to_reg</v-icon>
                                        </v-btn>
                                    </td>
                                </template>
                            </v-data-table>
                        </template>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" dark @click="dialogDate = false, clearFields()">Cancelar
                        </v-btn>
                    </v-card-actions>
                </v-card-actions>
            </v-card>

        </v-dialog>

        <v-dialog v-model="preload_cita" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Estamos procesando su información
                    <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogtranscripcion" max-width="1200px">
            <v-card max-height="100%">
                <v-card-title class="headline success" style="color:white">
                    <span class="title layout justify-center font-weight-light">Gestión</span>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-layout row wrap>
                        <v-container grid-list-xl>
                            <v-layout wrap>
                                <v-flex xs12 sm6 md6>
                                    <vAutocomplete item-text="Nombre" item-value="id" :items="tipo_citas"
                                        label="Gestión" v-model="data.Tipocita_id" />
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <vAutocomplete :items="ambitos" label="Ámbito de la atención"
                                        v-model="data.ambito" />
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-text-field label="Médico que Ordenó" v-model="data.medicoordeno"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-autocomplete v-model="data.entidademite" append-icon="search"
                                        label="Buscar prestador..." :items="fullnameprestador" item-text="fullname"
                                        item-value="id" hide-details @input="buscarSedesPrestador()"></v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm6 md6>
                                    <v-autocomplete :items="[
                                            'Accidente de Trabajo',
                                            'Detección temprana de enfermedad general',
                                            'Detección temprana de enfermedad profesional',
                                            'Diagnóstico',
                                            'No aplica',
                                            'Protección específica',
                                            'Terapéutico','Teleconsulta'
                                        ]" label="Finalidad" append-icon="search" v-model="data.Finalidad">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <v-autocomplete v-model="data.Cie10_id" append-icon="search" :items="cieConcat"
                                        item-disabled="customDisabled" item-text="nombre" item-value="id"
                                        label="Diagnóstico">
                                    </v-autocomplete>
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <input type="file" id="file" ref="myFiles" class="custom-file-input" multiple
                                        v-on:change="onFilePicked" />
                                </v-flex>
                                <v-flex xs12 sm6>
                                    <span style="color:red">Los archivos deben tener un tamaño máximo de 10MB</span>
                                </v-flex>
                            </v-layout>
                        </v-container>
                        <v-flex xs12>
                            <v-textarea outline name="input-7-4" label="Observaciones" v-model="data.observaciones">
                            </v-textarea>
                        </v-flex>
                        <v-btn color="success"
                            v-show="can('transcripcion_entidad') || paciente.entidad_id == 1 || paciente.entidad_id == 3  || paciente.entidad_id == 5"
                            dark @click="guardarTranscripcion()">Guardar</v-btn>
                    </v-layout>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-card>
</template>

<script>
    import moment from "moment";
    import {
        EventBus
    } from "../../../event-bus.js";
    import {
        mapGetters
    } from "vuex";

    moment.locale("es");
    export default {
        name: 'seguimientoConcurrencia',
        data() {
            return {
                ambitos: [
                    'Ambulatorio',
                    'Hospitalario',
                    'Urgencias',
                ],
                data: {
                    Tipocita_id: 1,
                    adjunto: null,
                    ambito: 'Ambulatorio',
                },
                tipo_citas: [],
                prestadores: [],
                cie10s: [],
                concurrenciaseguimientos: [],
                seguimientoCon: {
                    seguimientoConcurrencia: '',
                    estado: '',
                    referencia_id: '',
                    id_referencia: ''
                },
                citaPaciente: [],
                estados: ['En seguimiento', 'De alta'],
                editregistro: false,
                dialogDate: false,
                dialogtranscripcion: false,
                seguimientoReferencia: false,
                dialogverSeguimiento: false,
                especialidades: [],
                especialidad_selected: null,
                sedes: [],
                sede_selected: null,
                actividad_selected: null,
                medico_selected: null,
                agendas: [],
                seguimiento: [],
                especialidades: [],
                especialidad_selected: null,
                datePicker: false,
                dateSolicitada: false,
                fecha_selected: null,
                fecha: null,
                asignar: false,
                preload_cita: false,
                today: moment().format('YYYY-MM-DD'),
                search: '',
                cedula_paciente: "",
                headersAgenda: [{
                        text: 'Hora',
                        align: 'center',
                        sortable: false,
                        value: 'Agenda_id'
                    },
                    {
                        text: 'Consultorio',
                        sortable: false,
                        align: 'center',
                        value: 'consultorio'
                    },
                    {
                        text: '',
                        align: 'center',
                        value: ''
                    },
                ],
                paciente: {
                    Primer_Nom: '',
                    Primer_Ape: '',
                    Tipo_Doc: '',
                    Num_Doc: '',
                    Edad_Cumplida: ''
                },
                files: "",
                headers: [{
                        text: 'id',
                        align: 'left',
                        sortable: false,
                        value: 'id'
                    },
                    {
                        text: 'Paciente',
                        value: 'calories'
                    },
                    {
                        text: 'Cédula',
                        value: 'Num_Doc'
                    },
                    {
                        text: 'Especialidad',
                        value: 'Especialidad_remi'
                    },
                    {
                        text: 'Atendido en',
                        value: 'ips_atencion'
                    },
                    {
                        text: 'Estado Cita'
                    },
                    {
                        text: 'Fecha Cita'
                    },
                    {
                        text: 'Ver seguimientos'
                    },
                    {
                        text: 'Asignar Cita'
                    },
                    {
                        text: 'Transcripción'
                    }
                ],
                data_seguimiento: [],
                datoscita: {}
            }
        },
        mounted() {
            this.seguimientoConcurrencia();
            this.fetchEspecialidades();
            this.fetchCie10s();
            this.fetchPrestadores();
            this.fetchGestion();
        },
        filters: {
            date: Fechaorden => {
                return moment(Fechaorden).format("LL");
            },
            time(date) {
                return moment(date).format('HH:mm:ss');
            }
        },
        computed: {
            ...mapGetters(['can']),
            agendaDisponible() {
                let citasEnable = [];
                let citas = [];
                for (let i = 0; i < this.agendas.length; i++) {
                    let citas = [];
                    let medico = `${this.agendas[i].nombreMedico} ${this.agendas[i].apellidoMedico}`
                    if (medico === this.medico_selected &&
                        this.agendas[i].fecha == this.fecha) {
                        citas = this.agendas[i].citas.map(cita => {
                            return {
                                id: cita.id,
                                hora_inicio: cita.Hora_Inicio,
                                consultorio: this.agendas[i].nombreConsultorio
                            }
                        });
                        citasEnable = citasEnable.concat(citas);
                    }
                }

                return citasEnable.sort((a, b) => moment(a.hora_inicio) - moment(b.hora_inicio));
            },
            fullnameprestador() {
                return this.prestadores.map((prestador) => {
                    return {
                        id: prestador.id,
                        fullname: `${prestador.Nit} - ${prestador.Nombre}`
                    }
                })
            },
            actividades() {
                return this.especialidades.filter(e => this.especialidad_selected === e.id && this.sede_selected == e
                    .sede)
            },
            medicosSede() {
                return this.agendas.map((agenda) => `${agenda.nombreMedico} ${agenda.apellidoMedico}`)
            },
            cieConcat() {
                return this.cie10Array = this.cie10s.map(cie => {
                    return {
                        id: cie.id,
                        codigo: cie.Codigo_CIE10,
                        nombre: `${cie.Codigo_CIE10} - ${cie.Descripcion}`
                    };
                });
            },
        },
        methods: {
            onFilePicked() {
                this.files = this.$refs.myFiles.files;
            },
            buscarSedesPrestador() {
                axios.post('/api/sedeproveedore/all', {
                        Prestador_id: this.Prestador_id
                    })
                    .then((res) => {
                        this.sedesprestadores = res.data
                    })
                    .catch((err) => console.log(err));
            },
            fetchCie10s() {
                axios.get("/api/cie10/all").then(res => {
                    this.cie10s = res.data;
                });
            },
            fetchGestion() {
                axios.get('/api/tipocita/all')
                    .then(res => this.tipo_citas = res.data.filter(f => f.id == 1))
                    .catch(err => console.log(err.response))
            },
            asignarCita(datos) {
                this.dialogDate = true;
                this.datoscita.Paciente_id = datos.id_paci;
                this.datoscita.referencia_id = datos.referencia_id;
            },
            agendarCita(cita) {
                this.datoscita.cita_id = cita.id;
                this.preload_cita = true;
                axios.put(`/api/cita/asignarcita/${this.datoscita.cita_id}`, this.datoscita)
                    .then((res) => {
                        this.dialogDate = false;
                        this.preload_cita = false;
                        this.clearFields();
                        this.$alerSuccess('Cita asignada con exito!')
                        this.seguimientoConcurrencia();
                    })
                    .catch((err) => this.showMessage(err.response.data.message))
            },
            fetchPrestadores() {
                axios.get('/api/prestador/all')
                    .then((res) => this.prestadores = res.data)
                    .catch((err) => console.log(err));
            },
            dialogtranscripcionData(datos) {
                this.dialogtranscripcion = true
                this.data.paciente_id = datos.id_paci
                this.data.referencia_id = datos.referencia_id
                this.paciente.Primer_Nom = datos.Primer_Nom
                this.paciente.Primer_Ape = datos.Primer_Ape
                this.paciente.Tipo_Doc = datos.Tipo_Doc
                this.paciente.Num_Doc = datos.Num_Doc
                this.paciente.Edad_Cumplida = datos.Edad_Cumplida
            },
            async guardarTranscripcion() {
                if (!this.data.Tipocita_id) {
                    swal({
                        title: "Por favor seleccione un tipo de gestión!!",
                        text: "Requerido",
                        icon: "error",
                    });
                    return;
                }
                if (!this.data.Finalidad) {
                    swal({
                        title: "Es necesario seleccionar una finalidad!",
                        text: "Requerido",
                        timer: 4000,
                        icon: "error",
                        buttons: false
                    });
                    return;
                }
                if (!this.data.Cie10_id) {
                    swal({
                        title: "Por favor seleccione un diagnostico principal!",
                        text: "Requerido",
                        timer: 4000,
                        icon: "error",
                        buttons: false
                    });
                    return;
                }

                if (!this.data.medicoordeno) {
                    swal({
                        title: "Gestión",
                        text: "El campo 'Médico que Ordenó' es requerido",
                        timer: 4000,
                        icon: "error",
                        buttons: false
                    });
                    return;
                }
                if (this.data.paciente_id) {
                    await axios.post(`/api/cita/create_cita_paciente/${this.data.paciente_id}`, this.data)
                        .then(async res => {
                            this.citaPaciente = res.data;
                            this.setDiagnostico();

                            if (this.files.length > 0) {
                                let formData = new FormData();

                                for (var i = 0; i < this.files.length; i++) {
                                    let file = this.files[i];

                                    formData.append("files[" + i + "]", file);
                                }

                                await axios
                                    .post(`/api/file/setFiles/${this.citaPaciente.id}`, formData, {
                                        headers: {
                                            "Content-Type": "multipart/form-data"
                                        }
                                    })
                                    .then(function () {
                                        console.log("SUCCESS!!");
                                    })
                                    .catch(err => {
                                        swal({
                                            title: "Incapacidad",
                                            text: err.response,
                                            timer: 2000,
                                            icon: "error",
                                            buttons: false
                                        });
                                        console.log(err.response);
                                    });
                            }
                            this.$router.push(
                                "/referencia/alta?cita_paciente=" + this.citaPaciente.id
                            );
                            EventBus.$emit("call-order", this.paciente);
                            this.dialogtranscripcion = false;
                            this.seguimientoConcurrencia();
                        });
                } else {
                    swal({
                        title: "Transcripción",
                        text: "Por favor ingrese un paciente!",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                }
            },
            showMessage(message) {
                this.preload_cita = false;
                swal({
                    title: `${message}`,
                    icon: "warning",
                });
            },
            clearFields() {
                this.medico_selected = null,
                    this.sede_selected = null,
                    this.actividad_selected = null,
                    this.especialidad_selected = null,
                    this.datoscita = {
                        Paciente_id: null,
                        cita_id: null,
                        referencia_id: null,
                        observaciones: null,
                    }
            },
            agendaSolicitada() {
                this.dateSolicitada = false;
                this.fecha = this.data.fecha_solicitada;
                this.fetchAgendas();
            },
            fetchEspecialidades() {
                axios.get(`/api/agenda/agendaDisponible/especialidades`)
                    .then((res) => {
                        this.especialidades = res.data;
                        this.especialidad_selected = 49;
                        this.fetchSedes();
                        this.sede_selected = 7;
                        this.fetchAgendas();
                    });
            },

            setDiagnostico() {

                let data = {
                    Cie10_id: this.data.Cie10_id
                }

                axios.post(`/api/cie10/setDiagnostic/${this.citaPaciente.id}`, data).then(res => {
                    console.log("res", res.data);
                });

            },
            fetchSedes() {
                axios.post(`/api/agenda/agendaDisponible/sedes`, {
                        especialidad: this.especialidad_selected
                    })
                    .then((res) => {
                        this.sedes = res.data
                        console.log(res.data)
                    });
            },
            setCie10() {
                let object = this.cie10s.find(cie => cie.id == this.data.Cie10_id);
                this.paciente.Cie10_id = object.Codigo_CIE10;
            },
            fetchAgendas() {
                this.datePicker = false;
                if (this.especialidad_selected && this.sede_selected && this.actividad_selected) {
                    axios.post('/api/agenda/agendaDisponible', {
                            fecha: this.fecha,
                            sede: this.sede_selected,
                            tipo_agenda: this.actividad_selected
                        })
                        .then(res => {
                            this.agendas = res.data
                        });
                }

            },
            fetchCie10s() {
                axios.get("/api/cie10/all").then(res => {
                    this.cie10s = res.data;
                });
            },
            total() {
                let p = Number(this.data_seguimiento.dias_estancia_intensivo) +
                    Number(this.data_seguimiento.dias_estancia_intermedio) +
                    Number(this.data_seguimiento.dias_estancia_basicos)
                return this.data_seguimiento.estancia_total_dias = p
            },
            seguimientoConcurrencia() {
                axios.get('/api/referencia/Altaconcurrencia').then(res => {
                        this.seguimiento = res.data;
                    })
                    .catch(e => {
                        this.$alerError('Error')
                    })
            },
            guardarseguimiento() {
                axios.post('/api/referencia/seguimientoConcurrencia', this.seguimientoCon).then(res => {
                        this.$alerSuccess("Seguimiento guardado con exito!");
                        this.seguimientoReferencia = false
                        this.clearData()
                    })
                    .catch(e => {
                        this.$alerError('Error')
                    })
            },
            openRegistro(seguimiento) {
                this.data_seguimiento = seguimiento
                this.editregistro = true
                this.fetchCie10s()
            },
            updateRegistro() {
                swal({
                    title: 'Esta seguro de actualizar los cambios?',
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then((confirm) => {
                    if (confirm) {
                        axios.post('/api/referencia/editregistro/', this.data_seguimiento).then(res => {
                            this.$alerSuccess("Registro actualizado con exito!");
                            this.seguimientoConcurrencia();
                            this.editregistro = false
                        })
                    }
                });
            },
            verConcurrencias(referencia_id) {
                axios.get('/api/referencia/verseguimientos/' + referencia_id).then(res => {
                    this.concurrenciaseguimientos = res.data;
                    this.dialogverSeguimiento = true;

                })
            },
            cargarDetalles(referencia_id) {
                this.seguimientoCon.id_referencia = referencia_id;
                this.dialogverSeguimiento = true;
            },
            clearData() {
                for (const prop of Object.getOwnPropertyNames(this.seguimientoCon)) {
                    this.seguimientoCon[prop] = null;
                }
            }
        }
    }

</script>

<style>
    .search-field input::placeholder {
        color: black !important;
    }

</style>
