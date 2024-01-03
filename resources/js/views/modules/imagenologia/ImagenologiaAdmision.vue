<template>
    <v-card>
        <template>
            <v-card>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-layout row wrap>
                                    <v-flex xs3>
                                        <v-text-field prepend-icon="calendar_today" label="Fecha inicio" type="date"
                                            color="primary" v-model="data.fecha_inicio">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-text-field prepend-icon="calendar_today" label="Fecha final" type="date"
                                            color="primary" v-model="data.fecha_fin">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-text-field v-model="data.documento" label="Documento"></v-text-field>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-autocomplete label="Especialista" :items="especialistas" item-value="id"
                                            item-text="fullname" v-model="data.medico">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-autocomplete label="Servicio" :items="servicios" v-model="data.tipocita">
                                        </v-autocomplete>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-select :items="sedesFilter" v-model="data.sede" label="Sede"></v-select>
                                    </v-flex>
                                    <v-flex xs1>
                                        <v-btn color="primary" @click="citasImagenes()">Buscar</v-btn>
                                    </v-flex>
                                    <v-flex xs1>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn text icon color="error" dark v-on="on" @click="clearFilter()">
                                                    <v-icon>clear</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Limpiar</span>
                                        </v-tooltip>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
            </v-card>
        </template>

        <v-dialog v-model="dialogDate" persistent max-width="1000px">
            <v-card max-height="100%" v-show="true">
                <v-toolbar color="primary" flat dark>
                    <v-flex xs12 text-xs-center>
                        <v-toolbar-title>Agenda Medico Disponible</v-toolbar-title>
                    </v-flex>
                </v-toolbar>
                <v-divider></v-divider>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs3 px-1>
                            <v-autocomplete v-model="especialidad_selected" :items="especialidades" item-text="Nombre"
                                item-value="id" label="Especialidades" readonly @input="fetchSedes()"></v-autocomplete>
                        </v-flex>
                        <v-flex xs3 px-1>
                            <v-select v-model="sede_selected" :items="sedes" label="Sede" item-text="Nombre"
                                item-value="id" @input="fetchAgendas()"></v-select>
                        </v-flex>
                        <v-flex xs6 px-1>
                            <v-autocomplete v-model="cup" :items="cups" item-text="nombre" item-value="id" label="Cup"
                                @input="orden(cup)"></v-autocomplete>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="orden_id" readonly label="Orden"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-autocomplete v-model="actividad_selected" :items="actividades" item-text="name"
                                item-value="et_id" label="Actividad" @input="fetchAgendas()"></v-autocomplete>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-select v-model="medico_selected" :items="medicosSede" label="Médico"></v-select>
                        </v-flex>
                        <v-flex xs3 px-1>
                            <v-select :items="tipospaciente" label="Tipo paciente" v-model="tipo_paciente"></v-select>
                        </v-flex>
                        <v-flex xs3 px-1>
                            <v-select :items="lados" label="Lado" v-model="lado"></v-select>
                        </v-flex>
                        <v-flex xs3 px-1>
                            <v-select :items="prioridades" label="Prioridad" v-model="prioridad"></v-select>
                        </v-flex>
                        <v-flex xs3 px-1>
                            <v-select :items="lecturas" label="Lectura" v-model="lectura"></v-select>
                        </v-flex>
                        <v-flex xs3 px-1>
                            <v-select :items="tecnicas" label="Tecnica" v-model="tecnica"></v-select>
                        </v-flex>
                        <v-flex xs4 px-1 v-if="tipo_paciente == 'Hospitalario' || tipo_paciente == 'Urgencias'">
                            <v-text-field label="Ubicación" v-model="ubicacion"></v-text-field>
                        </v-flex>
                        <v-flex xs3 px-1 v-if="tipo_paciente == 'Hospitalario' || tipo_paciente == 'Urgencias'">
                            <v-text-field label="Cama" v-model="cama"></v-text-field>
                        </v-flex>
                        <v-flex xs2 px-1 v-if="tipo_paciente == 'Hospitalario' || tipo_paciente == 'Urgencias'">
                            <v-select :items="['Si', 'No']" label="Aislado" v-model="aislado"></v-select>
                        </v-flex>
                        <v-flex xs12 px-1 v-if="aislado == 'Si' & tipo_paciente == 'Hospitalario' || 
                        aislado == 'Si' & tipo_paciente == 'Urgencias'">
                            <v-text-field label="Observación aislado" v-model="obs_aislado"></v-text-field>
                        </v-flex>
                        <v-flex xs5 px-2>
                            <v-text-field label="Fecha y hora orden" type="datetime-local"
                                format-value="yyyy-MM-ddTHH:mm" color="primary" v-model="fecha_orden">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs3>
                            <v-menu v-model="datePicker" :close-on-content-click="false" :nudge-right="40" lazy
                                transition="scale-transition" offset-y full-width min-width="290px">
                                <template v-slot:activator="{ on }">
                                    <v-combobox v-model="dataCita.fecha_solicitada" label="Fecha solicitada"
                                        prepend-icon="event" readonly v-on="on"></v-combobox>
                                </template>
                                <v-date-picker color="primary" locale="es" v-model="dataCita.fecha_solicitada"
                                    :min="today" :show-current="false" @input="agendaSolicitada()"></v-date-picker>
                            </v-menu>
                        </v-flex>
                        <v-flex xs9 px-2>
                            <v-text-field label="Observación" v-model="dataCita.observaciones">
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
                                        <v-btn v-show="paciente" color="success" fab outline small
                                            @click="asignarCita(props.item)">
                                            <v-icon>how_to_reg</v-icon>
                                        </v-btn>
                                    </td>
                                </template>
                            </v-data-table>
                        </template>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" @click="clearFields()">Cancelar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogCon" width="500">
            <v-card>
                <v-card-title class="headline" style="color:white;background-color:#0074a6">
                    <span class="title layout justify-center font-weight-light">Agendar cita</span>
                </v-card-title>
                <v-card-text>
                    Asignar cita al usuario <b>{{ paciente.Primer_Nom }}
                        {{ paciente.SegundoNom }} {{ paciente.Primer_Ape}} {{ paciente.Segundo_Ape }}</b> identificado
                    con <b>{{ paciente.Tipo_Doc }}</b> N° <b>{{ paciente.Num_Doc }}</b> el día
                    <b>{{ fecha_selected }}</b> a las <b>{{ dataCita.hora_inicio | time}}</b> en la sede
                    <b>{{ sede_selected }}</b>, <b>{{ dataCita.consultorio }}</b> con el médico
                    <b>{{ medico_selected }}</b>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn flat color="error" @click="clearFields()">
                        cancelar
                    </v-btn>
                    <v-btn color="primary" flat @click="agendarCita()">
                        Aceptar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogGestion" persistent max-width="1000px">
            <v-card max-height="100%" v-show="true">
                <v-toolbar color="primary" flat dark>
                    <v-flex xs12 text-xs-center>
                        <v-toolbar-title>Gestión</v-toolbar-title>
                    </v-flex>
                </v-toolbar>
                <v-divider></v-divider>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs3 px-1>
                            <v-text-field v-model="datosCita.documento" readonly label="Documento"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.nombre" readonly label="Paciente"></v-text-field>
                        </v-flex>
                        <v-flex xs2 px-1>
                            <v-text-field v-model="datosCita.edad" readonly label="Edad"></v-text-field>
                        </v-flex>
                        <v-flex xs3 px-1>
                            <v-text-field v-model="datosCita.erp" readonly label="ERP"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.fecha_cita.split('.')[0]" readonly
                                label="Fecha y hora cita">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.especialista" readonly label="Especialista"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.prioridad" readonly label="Prioridad"></v-text-field>
                        </v-flex>
                        <v-flex xs12 px-1>
                            <v-text-field v-model="datosCita.tipo_agenda" readonly label="Tipo cita"></v-text-field>
                        </v-flex>
                        <v-flex xs6 px-1>
                            <v-text-field v-model="datosCita.fecha_orden" readonly label="Fecha y hora orden">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs6 px-1>
                            <v-text-field v-model="datosCita.tipo_paciente" readonly label="Tipo paciente">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.tecnica" readonly label="Tecnica"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.lado" readonly label="lado"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1>
                            <v-text-field v-model="datosCita.lectura" readonly label="Lectura"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1
                            v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                            <v-text-field v-model="datosCita.ubicacion" readonly label="Ubicación"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1
                            v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                            <v-text-field v-model="datosCita.cama" readonly label="Cama"></v-text-field>
                        </v-flex>
                        <v-flex xs4 px-1
                            v-if="datosCita.tipo_paciente == 'Hospitalario' || datosCita.tipo_paciente == 'Urgencias'">
                            <v-text-field v-model="datosCita.aislado" readonly label="Aislado"></v-text-field>
                        </v-flex>
                        <v-flex xs12 px-1 v-if="datosCita.aislado == 'Si'">
                            <v-text-field v-model="datosCita.observacion_aislado" readonly label="Observación aislado">
                            </v-text-field>
                        </v-flex>
                        <v-flex xs12 v-if="observa_adjuntos">
                            <v-layout wrap>
                                <v-flex xs12 v-for="(obs, index) in observa_adjuntos" :key="index">
                                    <v-flex xs12 v-if="obs.observacion.length > 0">
                                        <v-toolbar color="primary" dark class="mb-3">
                                            <v-toolbar-title>Observaciones</v-toolbar-title>
                                        </v-toolbar>
                                    </v-flex>
                                    <v-flex xs12 v-for="(observ, index) in obs.observacion" :key="index">
                                        <v-textarea readonly :value="observ.nota" :label="`OBSERVACIÓN ${index+1}`">
                                        </v-textarea>
                                        <span><strong>Fecha: </strong>{{ observ.created_at.split('.')[0] }}
                                            <strong>Nombre: </strong>{{ observ.name }} {{ observ.apellido }}
                                        </span>
                                        <v-divider class="my-4"></v-divider>
                                    </v-flex>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                        <v-flex xs12 v-if="observa_adjuntos">
                            <v-layout wrap>
                                <v-flex v-for="(data, index) in observa_adjuntos" :key="index">
                                    <v-flex xs12 v-if="data.adjuntos_cita.length > 0 && index < 1">
                                        <v-toolbar color="primary" dark class="mb-3">
                                            <v-toolbar-title>Adjuntos</v-toolbar-title>
                                        </v-toolbar>
                                    </v-flex>
                                    <v-flex v-for="(adjunt, index) in data.adjuntos_cita" :key="`adj-${index}`">
                                        <v-btn>
                                            <a :href="`${adjunt.ruta}`" target="_blank" style="text-decoration:none">
                                                <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto {{index+1}}
                                            </a>
                                        </v-btn>
                                        <span><strong>Fecha: </strong>{{ adjunt.created_at.split('.')[0] }}
                                            <strong>Nombre: </strong>{{ adjunt.name }} {{ adjunt.apellido }}
                                        </span>
                                    </v-flex>
                                    <v-divider class="my-4"></v-divider>
                                </v-flex>
                            </v-layout>
                        </v-flex>
                        <v-flex xs12 px-1>
                            <v-textarea v-model="observa_admision" label="Observación"></v-textarea>
                        </v-flex>
                        <v-flex xs12>
                            <input id="adjuntos" multiple ref="adjuntos" type="file" />
                        </v-flex>
                    </v-layout>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="info" @click="adjuntar(datosCita)">
                        <span>Adjuntar</span>
                        <v-icon>cloud_upload</v-icon>
                    </v-btn>
                    <v-btn color="success" @click="observacion(datosCita)">
                        <span>Observación</span>
                        <v-icon>edit</v-icon>
                    </v-btn>
                    <v-btn color="purple" dark @click="inasistioPaciente(datosCita)">
                        <span>Inasistió</span>
                        <v-icon>alarm</v-icon>
                    </v-btn>
                    <v-btn color="warning" @click="modalCancelar(datosCita)">
                        <span>Cancelar</span>
                        <v-icon>clear</v-icon>
                    </v-btn>
                    <v-btn color="error" @click="closeGestion()">
                        <span>Salir</span>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <template>
            <div class="text-center">
                <v-dialog v-model="dialogConfirmar" width="350">
                    <v-card>
                        <v-card-text align="center" justify="center">
                            <p>¿Donde sigue la atención el paciente?</p>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="success" @click="confirmarEnfermeria()">
                                <span>Enfermeria</span>
                            </v-btn>
                            <v-btn color="warning" @click="confirmarTecnologo()">
                                <span>Tecnologo</span>
                            </v-btn>
                            <v-btn color="purple" dark @click="confirmarRadiologo()">
                                <span>Medico</span>
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </div>
        </template>

        <v-dialog v-model="motivoCancelar" persistent max-width="600px">
            <v-card>
                <v-toolbar color="primary" flat dark>
                    <v-flex xs12 text-xs-center>
                        <v-toolbar-title>Motivo</v-toolbar-title>
                    </v-flex>
                </v-toolbar>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-textarea v-model="cancelar.motivo" label="Motivo por el cual se cancela la cita">
                                </v-textarea>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" flat @click="motivoCancelar = false">Cerrar</v-btn>
                    <v-btn color="success" flat @click="cancelarCita()">
                        Enviar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-card-title v-show="datatable">
            <v-spacer></v-spacer>
            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
        </v-card-title>
        <v-data-table v-show="datatable" :search="search" :headers="headers" :items="citas" class="elevation-1">
            <template v-slot:items="props">
                <td>{{ props.item.Hora_Inicio.split(' ')[0] }}</td>
                <td>{{ props.item.Hora_Inicio | time }}</td>
                <td class="text-xs-right">
                    {{ props.item.Primer_Nom }} {{ props.item.SegundoNom }}
                    {{ props.item.Primer_Ape }} {{ props.item.Segundo_Ape }}
                </td>
                <td>
                    <v-chip class="text-xs-right" :class="ColorTd(props.item.tipo_paciente)">
                        {{ props.item.tipo_paciente }}</v-chip>
                </td>
                <td class="text-xs-right">{{ props.item.Num_Doc }}</td>
                <td class="text-xs-right">{{ props.item.Edad_Cumplida }}</td>
                <td class="text-xs-right">{{ props.item.Tipo_agenda }}</td>
                <td class="justify-center layout px-0">
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <v-btn text icon color="green" dark v-on="on" @click="confirmar(props.item)">
                                <v-icon>done_all</v-icon>
                            </v-btn>
                        </template>
                        <span>Confirmar</span>
                    </v-tooltip>
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <v-btn text icon color="warning" dark v-on="on" @click="reasignarCita(props.item)">
                                <v-icon>loop</v-icon>
                            </v-btn>
                        </template>
                        <span>Reasignar</span>
                    </v-tooltip>
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <v-btn text icon color="primary" dark v-on="on" @click="gestion(props.item)">
                                <v-icon>view_headline</v-icon>
                            </v-btn>
                        </template>
                        <span>Gestión</span>
                    </v-tooltip>
                </td>
            </template>
        </v-data-table>

    </v-card>
</template>

<script>
    import Swal from 'sweetalert2';
    import moment from "moment";
    moment.locale("es");
    export default {
        data() {
            return {
                sedesFilter: ['Magisterio Clinica Victoriana', 'Magisterio VILLANUEVA'],
                tipospaciente: ['Ambulatorio', 'Hospitalario', 'Urgencias'],
                datatable: false,
                search: '',
                headers: [{
                        text: "Fecha",
                        value: "Hora_Inicio",
                        align: "center",
                        sortable: true
                    },
                    {
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
                        text: "Tipo",
                        value: "tipo_paciente",
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
                        text: "Acciones",
                        value: "",
                        align: "center"
                    }
                ],
                data: {
                    fecha_inicio: moment().format("YYYY-MM-DD"),
                    fecha_fin: moment().format("YYYY-MM-DD"),
                    documento: '',
                    medico: '',
                    sede: '',
                    tipocita: ''
                },
                citas: [],
                especialistas: [],
                dialogDate: false,
                especialidad_selected: null,
                especialidades: [],
                sede_selected: null,
                actividad_selected: null,
                fecha_selected: null,
                lado: '',
                tecnica: '',
                prioridad: '',
                lectura: '',
                fecha: null,
                agendas: [],
                medico_selected: null,
                today: moment().format('YYYY-MM-DD'),
                datePicker: false,
                preload_cita: false,
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
                dataCita: {
                    cita_id: null,
                    hora_inicio: null,
                    consultorio: null,
                    Paciente_id: null,
                    fecha_solicitada: null,
                    observaciones: null,
                    cita_paciente_padre: null
                },
                dateSolicitada: false,
                paciente: {
                    id: '',
                    Primer_Nom: '',
                    SegundoNom: '',
                    Primer_Ape: '',
                    Segundo_Ape: '',
                    Tipo_Doc: '',
                    Num_Doc: ''
                },
                dialogCon: false,
                sedes: [],
                cancelar: {
                    id: null,
                    Paciente_id: null,
                    motivo: null,
                },
                cita: {
                    id: null,
                    Paciente_id: null,
                    motivo: null,
                },
                dialogGestion: false,
                datosCita: {
                    nombre: '',
                    documento: '',
                    edad: '',
                    especialista: '',
                    erp: '',
                    fecha_cita: '',
                    cita_id: '',
                    paciente_id: '',
                    motivo: '',
                    cita_paciente_id: '',
                    tecnica: '',
                    prioridad: '',
                    lado: '',
                    lectura: ''
                },
                motivoCancelar: false,
                reasignado: null,
                adjuntos: [],
                lados: ['Derecho', 'Izquierdo'],
                prioridades: ['Urgente', 'Normal'],
                lecturas: ['Si', 'No'],
                tecnicas: ['Constrastada', 'Simple'],
                observa_admision: '',
                observ: {},
                cups: [],
                orden_id: '',
                tipo_paciente: '',
                cup: '',
                aislado: '',
                fecha_orden: '',
                ubicacion: '',
                cama: '',
                obs_aislado: '',
                observa_adjuntos: [],
                dialogConfirmar: false,
                confirmaciones: {},
                tipocitas: [],
                servicios: ['Ecografia', 'Tomografia', 'Radiografia ', 'Mamografia', 'Ginecologica',
                    'Doppler', 'Intervencionista', 'Obstetrica', 'Biopsias', 'Drenajes', 'Ecocardiografia'
                ]
            }
        },
        filters: {
            time(date) {
                return moment(date).format('HH:mm:ss');
            }
        },
        computed: {
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
            actividades() {
                return this.especialidades.filter(e => this.especialidad_selected === e.id && this.sede_selected == e
                    .sede)
            },
            medicosSede() {
                return this.agendas.map((agenda) => `${agenda.nombreMedico} ${agenda.apellidoMedico}`)
            },
        },
        mounted() {
            this.radiologo();
            this.fetchEspecialidades();
            this.tipoCita();
        },
        methods: {
            citasImagenes() {
                this.preload_cita = true;
                axios.post(`/api/cita/admisiones`, this.data).then(res => {
                    this.citas = res.data
                    this.preload_cita = false;
                    if (this.citas !== '') this.datatable = true;
                }).catch(err => {
                    this.preload_cita = false;
                });
            },
            radiologo() {
                axios.get('/api/imagenologia/radiologos').then(res => {
                    this.especialistas = res.data.map(espe => {
                        return {
                            id: espe.id,
                            fullname: `${espe.name} ${espe.apellido}`
                        };
                    });
                });
            },
            tipoCita() {
                axios.get('/api/imagenologia/tipoCita').then(res => {
                    this.tipocitas = res.data
                });
            },
            clearFilter() {
                this.data = {
                    fecha_inicio: moment().format("YYYY-MM-DD"),
                    fecha_fin: moment().format("YYYY-MM-DD"),
                    documento: '',
                    medico: '',
                    sede: '',
                    tipocita: ''
                }
            },
            reasignarCita(paciente) {
                this.cupsPaciente(paciente.paciente_id)
                this.cancelar = {
                    id: paciente.id,
                    Paciente_id: paciente.paciente_id,
                    motivo: "Cita reasignada"
                }
                this.paciente = {
                    id: paciente.paciente_id,
                    Primer_Nom: paciente.Primer_Nom,
                    Primer_Ape: paciente.Primer_Ape,
                    Tipo_Doc: paciente.Tipo_Doc,
                    Num_Doc: paciente.Num_Doc,
                    SegundoNom: paciente.SegundoNom,
                    Segundo_Ape: paciente.Segundo_Ape
                }
                this.dialogDate = true
            },
            fetchEspecialidades() {
                axios.get(`/api/agenda/agendaDisponible/especialidades`)
                    .then((res) => {
                        this.especialidades = res.data;
                        this.especialidad_selected = 58;
                        this.fetchSedes();
                        this.fetchAgendas();
                    });
            },
            cupsPaciente(Paciente_id) {
                axios.get(`/api/cita/cupspaciente/${Paciente_id}`)
                    .then((res) => {
                        this.cups = res.data.map(cup => {
                            return {
                                id: `${cup.id}-${cup.Orden_id}`,
                                nombre: cup.Nombre
                            };
                        });
                    })
            },
            orden(cup) {
                this.orden_id = cup.split('-')[1]
            },
            fetchSedes() {
                axios.post(`/api/agenda/agendaDisponible/sedes`, {
                        especialidad: this.especialidad_selected
                    })
                    .then((res) => {
                        this.sedes = res.data
                    });
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
            agendaSolicitada() {
                this.dateSolicitada = false;
                this.fecha = this.dataCita.fecha_solicitada;
                this.fetchAgendas();
            },
            asignarCita(cita) {
                this.dialogCon = true;
                this.dataCita.cita_id = cita.id;
                this.dataCita.hora_inicio = cita.hora_inicio;
                this.dataCita.consultorio = cita.consultorio;
                this.dataCita.Paciente_id = this.paciente.id;
                this.dataCita.tecnica = this.tecnica;
                this.dataCita.prioridad = this.prioridad;
                this.dataCita.lado = this.lado;
                this.dataCita.lectura = this.lectura;
                this.dataCita.ubicacion = this.ubicacion;
                this.dataCita.cama = this.cama;
                this.dataCita.aislado = this.aislado;
                this.dataCita.obs_aislado = this.obs_aislado;
                this.dataCita.fecha_orden = this.fecha_orden;
                this.dataCita.tipo_paciente = this.tipo_paciente;
                this.dataCita.cup = this.cup;
                this.fecha_selected = moment(this.fecha).format('dddd, D MMMM, YYYY');
            },
            agendarCita() {
                this.preload_cita = true;
                axios.put(`/api/cita/asignarcita/${this.dataCita.cita_id}`, this.dataCita)
                    .then((res) => {
                        this.preload_cita = false;
                        this.reasignado = true
                        this.cancelarCita();
                    }).catch(err => {
                        this.preload_cita = false;
                        this.$alerError(err.response.data.message);
                    });
            },
            clearFields() {
                this.agendas = [],
                    this.dialogGestion = false
                this.motivoCancelar = false
                this.dialogDate = false
                this.dialogCon = false
                this.medico_selected = null,
                    this.lado = '',
                    this.tecnica = '',
                    this.prioridad = '',
                    this.lectura = '',
                    this.cups = [],
                    this.orden_id = '',
                    this.tipo_paciente = '',
                    this.cup = '',
                    this.aislado = '',
                    this.fecha_orden = '',
                    this.ubicacion = '',
                    this.cama = '',
                    this.obs_aislado = '',
                    this.sede_selected = null,
                    this.actividad_selected = null,
                    this.fecha_selected = null,
                    this.fecha = moment().format('YYYY-MM-DD'),
                    this.dataCita = {
                        Paciente_id: null,
                        cita_id: null,
                        hora_inicio: null,
                        consultorio: null,
                        cita_paciente_padre: null,
                        fecha_solicitada: moment().format('YYYY-MM-DD'),
                    }
                this.paciente = {
                    id: '',
                    Primer_Nom: '',
                    Primer_Ape: '',
                    Tipo_Doc: '',
                    Num_Doc: '',
                    SegundoNom: '',
                    Segundo_Ape: ''
                }
                this.cancelar = {
                    id: null,
                    Paciente_id: null,
                    motivo: null,
                }
            },
            cancelarCita() {
                if (this.cancelar.motivo == null) {
                    this.$alerError("Tiene que ingresar un motivo!")
                    return
                } else if (this.reasignado == null && this.cancelar.motivo.length < 20) {
                    this.$alerError("El motivo debe contener minimo 20 caracteres!")
                    return
                }
                axios.put(`/api/cita/cancelar/${this.cancelar.id}`, this.cancelar)
                    .then((res) => {
                        if (this.reasignado == true) {
                            this.$alerSuccess("Cita reasignada con exito!");
                            this.reasignado = null
                        } else {
                            this.$alerSuccess(res.data.message);
                        }
                        this.clearFields();
                        this.citasImagenes();
                    }).catch(err => {
                        this.$alerError(err.response.data.message)
                    });
            },
            confirmar(datos) {
                this.dialogConfirmar = true
                this.confirmaciones = {
                    cita_id: datos.id,
                    paciente_id: datos.paciente_id,
                }
            },
            confirmarEnfermeria() {
                this.cita = {
                    id: this.confirmaciones.cita_id,
                    Paciente_id: this.confirmaciones.paciente_id,
                    motivo: "Cita enviada a enfermeria"
                }
                const msg = "Esta seguro de confirmar la cita y enviar a enfermeria?";
                swal({
                    title: msg,
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.put(`/api/cita/confirmar/${this.cita.id}`, this.cita)
                            .then((res) => {
                                this.dialogConfirmar = false
                                swal({
                                    title: "¡Cita enviada a enfermeria!",
                                    text: `${res.data.message}`,
                                    timer: 3000,
                                    icon: "success",
                                    buttons: false
                                });
                                this.citasImagenes();
                            })
                    }
                });
            },
            confirmarTecnologo() {
                this.cita = {
                    id: this.confirmaciones.cita_id,
                    Paciente_id: this.confirmaciones.paciente_id,
                    motivo: "Cita enviada a tecnologo"
                }
                const msg = "Esta seguro de confirmar la cita y enviar a tecnologo?";
                swal({
                    title: msg,
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.put(`/api/imagenologia/confirmarEnfermeria/${this.cita.id}`, this.cita)
                            .then((res) => {
                                this.dialogConfirmar = false
                                swal({
                                    title: "¡Cita enviada a tecnologo!",
                                    text: `${res.data.message}`,
                                    timer: 3000,
                                    icon: "success",
                                    buttons: false
                                });
                                this.citasImagenes();
                            })
                    }
                });
            },
            confirmarRadiologo() {
                this.cita = {
                    id: this.confirmaciones.cita_id,
                    Paciente_id: this.confirmaciones.paciente_id,
                    motivo: "Cita enviada a medico"
                }
                const msg = "Esta seguro de confirmar la cita y enviar a medico?";
                swal({
                    title: msg,
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.put(`/api/imagenologia/confirmarTecnologo/${this.cita.id}`, this.cita)
                            .then((res) => {
                                this.dialogConfirmar = false
                                swal({
                                    title: "¡Cita enviada a medico!",
                                    text: `${res.data.message}`,
                                    timer: 3000,
                                    icon: "success",
                                    buttons: false
                                });
                                this.citasImagenes();
                            })
                    }
                });
            },
            observacion_adjunto(datos) {
                axios.post(`/api/imagenologia/observacion_adjunto/${datos.id}`).then(res => {
                    this.observa_adjuntos = res.data
                });
            },
            gestion(datos) {
                this.observacion_adjunto(datos);
                this.datosCita = {
                    nombre: `${datos.Primer_Nom} ${datos.Primer_Ape} ${datos.Segundo_Ape}`,
                    documento: datos.Num_Doc,
                    edad: datos.Edad_Cumplida,
                    especialista: `${datos.nameEspecialista} ${datos.apellidoEspecialista}`,
                    erp: datos.Ut,
                    fecha_cita: datos.Hora_Inicio,
                    cita_id: datos.id,
                    paciente_id: datos.paciente_id,
                    cita_paciente_id: datos.cita_paciente_id,
                    tecnica: datos.tecnica,
                    prioridad: datos.prioridad,
                    lado: datos.lado,
                    lectura: datos.lectura,
                    tipo_paciente: datos.tipo_paciente,
                    tipo_agenda: datos.Tipo_agenda,
                    fecha_orden: datos.fecha_orden,
                    ubicacion: datos.ubicacion,
                    cama: datos.cama,
                    aislado: datos.aislado,
                    observacion_aislado: datos.observacion_aislado
                }
                this.dialogGestion = true
            },
            modalCancelar(datos) {
                this.motivoCancelar = true
                this.cancelar = {
                    id: datos.cita_id,
                    Paciente_id: datos.paciente_id,
                    motivo: this.motivo
                }
            },
            inasistioPaciente(citas) {
                this.cita = {
                    id: citas.cita_id,
                    cita_paciente_id: citas.cita_paciente_id
                }
                swal({
                    title: "¿Está Seguro(a)?",
                    text: "Se cancela la cita por inasistencia!",
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(willDelete => {
                    if (willDelete) {
                        axios.put(`/api/cita/inasistio/${this.cita.id}`, this.cita)
                            .then(res => {
                                this.$alerSuccess("Cita cancelada por inasistencia del paciente!");
                                this.clearFields();
                                this.citasImagenes();
                            });
                    }
                });
            },
            observacion(citas) {
                let caracteres = this.observa_admision.length > 20
                if (caracteres == false) {
                    this.$alerError('Debe ingresar una observación mayor a 20 caracteres!');
                    return
                }
                this.observ = {
                    nota: this.observa_admision,
                    cita_paciente_id: citas.cita_paciente_id,
                    tipo: 'admin'
                }
                this.preload_cita = true
                axios.post('/api/cita/observacion', this.observ).then(res => {
                    let cita_id = {
                        id: citas.cita_id
                    }
                    this.observacion_adjunto(cita_id);
                    this.observa_admision = ''
                    this.preload_cita = false
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: 'Observación agregada con exito!'
                    })
                })
            },
            closeGestion() {
                this.dialogGestion = false
                this.$refs.adjuntos.value = ''
                this.citasImagenes();
            },
            adjuntar(datos) {
                this.adjuntos = this.$refs.adjuntos.files;
                if (this.adjuntos.length == 0) {
                    this.$alerError('Debe adjuntar un archivo!');
                    return
                }
                let formData = new FormData();
                formData.append('documento', datos.documento)
                formData.append('cita_id', datos.cita_id)
                for (let i = 0; i < this.adjuntos.length; i++) {
                    formData.append(`adjuntos[]`, this.adjuntos[i]);
                }
                this.preload_cita = true
                axios.post(`/api/cita/adjuntoAdmision`, formData)
                    .then(res => {
                        let cita_id = {
                            id: datos.cita_id
                        }
                        this.observacion_adjunto(cita_id);
                        this.$refs.adjuntos.value = ''
                        this.preload_cita = false
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 4000,
                            timerProgressBar: true,
                            onOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })
                        Toast.fire({
                            icon: 'success',
                            title: 'Adjunto agregado con exito!'
                        })
                    })
                    .catch(err => {
                        this.preload_cita = false
                        this.$alerError(err.response.data.message)
                    });
            },
            ColorTd(tipo) {
                if (tipo == 'Hospitalario') {
                    return 'yellow black--text';
                } else if (tipo == 'Ambulatorio') {
                    return 'success white--text';
                } else {
                    return 'error white--text';
                }
            }
        }
    }

</script>
