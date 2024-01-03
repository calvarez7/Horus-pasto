<template>
    <div>
        <v-container grid-list-md fluid class="pa-0">
            <v-card-title>
                <VSpacer />
                <v-flex xs5>
                    <VAutocomplete clear-icon :disabled="loading" label="Buscar médico..." :items="listMedicos"
                        item-text="nombre" item-value="id" v-model="data.medico_id" @change="fetchAgendaMedico" />
                </v-flex>
            </v-card-title>
            <v-layout row wrap v-if="can('consolidadoCitas.exportar')">
                <v-flex xs12>
                    <v-layout row wrap align-center>
                        <v-flex xs2>
                            <v-menu v-model="isActiveStartDateExportAppointment" :close-on-content-click="false"
                                :nudge-right="40" lazy transition="scale-transition" offset-y full-width
                                min-width="290px">
                                <template v-slot:activator="{ on }">
                                    <VTextField v-model="startDateExportAppointment" label="Fecha inicio"
                                        prepend-icon="event" readonly v-on="on" />
                                </template>
                                <VDatePicker color="primary" locale="es" :max="finishDateExportAppointment" no-title
                                    v-model="startDateExportAppointment"
                                    @input="isActiveStartDateExportAppointment = false" />
                            </v-menu>
                        </v-flex>
                        <v-flex xs2>
                            <v-menu v-model="isActiveFinishDateExportAppointment" :close-on-content-click="false"
                                :nudge-right="40" lazy transition="scale-transition" offset-y full-width
                                min-width="290px">
                                <template v-slot:activator="{ on }">
                                    <VTextField v-model="finishDateExportAppointment" label="Fecha final"
                                        prepend-icon="event" readonly v-on="on" />
                                </template>
                                <VDatePicker color="primary" locale="es" :min="startDateExportAppointment" no-title
                                    v-model="finishDateExportAppointment"
                                    @input="isActiveFinishDateExportAppointment = false" />
                            </v-menu>
                        </v-flex>
                        <v-flex xs2>
                            <v-btn color="warning" :disabled="disabledExportBtn" round :loading="disabledExportBtn"
                                small @click="exportAppointments">
                                exportar
                            </v-btn>
                        </v-flex>
                        <v-flex xs2>
                            <v-menu v-model="activarFechaIncio" :close-on-content-click="false" :nudge-right="40" lazy
                                transition="scale-transition" offset-y full-width min-width="290px">
                                <template v-slot:activator="{ on }">
                                    <VTextField v-model="filtros.fechaInicio" label="Fecha inicio" prepend-icon="event"
                                        readonly v-on="on" />
                                </template>
                                <VDatePicker color="primary" locale="es" :max="filtros.fechaFinal" no-title
                                    v-model="filtros.fechaInicio" @input="activarFechaIncio = false" />
                            </v-menu>
                        </v-flex>
                        <v-flex xs2>
                            <v-menu v-model="activarFechaFinal" :close-on-content-click="false" :nudge-right="40" lazy
                                transition="scale-transition" offset-y full-width min-width="290px">
                                <template v-slot:activator="{ on }">
                                    <VTextField v-model="filtros.fechaFinal" label="Fecha final" prepend-icon="event"
                                        readonly v-on="on" />
                                </template>
                                <VDatePicker color="primary" locale="es" :max="hoy" :min="filtros.fechaInicio" no-title
                                    v-model="filtros.fechaFinal" @input="activarFechaFinal = false" />
                            </v-menu>
                        </v-flex>
                        <v-flex xs2>
                            <v-btn color="warning" :disabled="disabledExportBtn" round :loading="disabledExportBtn"
                                small @click="exportdemanda()">
                                exportar Demanda Insatisfecha
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </v-flex>
            </v-layout>
            <v-layout row wrap>
                <v-flex xs12>
                    <span :key="index" v-for="(light,index) in lightMenu">
                        <v-icon :color="light.color">indeterminate_check_box</v-icon>
                        {{ light.text }}
                    </span>
                </v-flex>
            </v-layout>
            <v-layout row wrap align-center>
                <v-flex sm4 xs12 class="text-sm-left text-xs-center">
                    <v-btn :disabled="loading" @click="$refs.calendar.prev()">
                        <v-icon dark left>
                            keyboard_arrow_left
                        </v-icon>
                        Anterior
                    </v-btn>
                </v-flex>
                <VSpacer />
                <span class="headline">{{ nameMonth.current.toUpperCase() }}
                    {{ anioCalendar.current !== anioCalendar.next? ` - ${anioCalendar.current}` : ' ' }}</span>
                <span
                    class="headline">{{ nameMonth.current !== nameMonth.next? `/ ${nameMonth.next.toUpperCase()}  ` : ' - ' }}
                    {{ anioCalendar.current !== anioCalendar.next? ` - ${anioCalendar.next}` : anioCalendar.current }}</span>
                <v-btn color="warning" :disabled="loading" outline round v-show="type == 'day'"
                    @click="changeCalendarType('week')">
                    SEMANAL
                </v-btn>
                <v-btn color="warning" :disabled="loading" round outline v-show="type != 'month'"
                    @click="changeCalendarType('month')">
                    MENSUAL
                </v-btn>
                <VSpacer />
                <v-flex sm4 xs12 class="text-sm-right text-xs-center">
                    <v-btn :disabled="loading" @click="$refs.calendar.next()">
                        Siguiente
                        <v-icon right dark>
                            keyboard_arrow_right
                        </v-icon>
                    </v-btn>
                </v-flex>
            </v-layout>
            <v-layout>
                <v-dialog max-width="1000px" persistent v-model="dialog">
                    <v-card flat>
                        <v-card-title class="headline grey lighten-2" dark flat>
                            Auditorio
                        </v-card-title>
                        <v-container>
                            <v-flex xs12>
                                <v-text-field append-icon="search" hide-details label="Search" single-line
                                    v-model="search" />
                            </v-flex>
                        </v-container>
                        <v-data-table class="fb-table-elem" expand :headers="auditorioHeaders" hide-actions
                            item-key="index" :items="auditorio.citas" :items-per-page="6" :search="search">
                            <template v-slot:items="cita">
                                <tr>
                                    <td>
                                        <v-chip v-if="cita.item.estado_citapaciente == 4" color="green" dark>Por Atender
                                        </v-chip>
                                        <v-chip v-else-if="cita.item.estado_citapaciente == 9" color="#0BB9E0" dark>
                                            Atendida</v-chip>
                                        <v-chip v-else-if="cita.item.estado_citapaciente == 8" color="#E0CD0B" dark>En
                                            Consulta</v-chip>
                                    </td>
                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">{{ auditorio.medico }}</div>
                                    </td>
                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">{{ cita.item.Hora_Inicio }}</div>
                                    </td>
                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">{{ auditorio.Sede }}</div>
                                    </td>
                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">{{ cita.item.Primer_Nom }}
                                            {{ cita.item.SegundoNom }}</div>
                                    </td>
                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">{{ cita.item.Primer_Ape }}
                                            {{ cita.item.Segundo_Ape }}</div>
                                    </td>
                                </tr>
                            </template>
                            <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                                Your search for "{{ search }}" found no results.
                            </v-alert>
                        </v-data-table>
                        <v-card-actions>
                            <VSpacer />
                            <v-btn color="red" text @click="dialog = false">
                                Cerrar
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <!-- modal for change type citas -->
                <v-dialog max-width="1000px" persistent v-model="citaChange">
                    <v-card flat>
                        <v-card-title class="headline grey lighten-2" dark flat>
                            Cambiar tipo de cita
                        </v-card-title>
                        <v-container fluid>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <p>
                                        <b>Especialidad:</b> {{ agendaForChange.especialidad }}
                                    </p>
                                    <p>
                                        <b>Actividad:</b> <em>{{ agendaForChange.tAgenda }}</em> a
                                    </p>
                                    <v-flex xs8>
                                        <VAutocomplete :items="typeAgendaFilter" item-value="id"
                                            item-text="nombreActividad" v-model="agendaForChange.newActivity" />
                                    </v-flex>
                                </v-flex>
                                <v-flex xs12>
                                    <VCheckbox v-model="agendaForChange.citasSelected" color="red" hide-details
                                        :key="index" :label="cita.Hora_Inicio | hourFormat" multiple :value="cita"
                                        v-for="(cita, index) in agendaForChange.citas" />
                                </v-flex>
                            </v-layout>
                        </v-container>
                        <v-card-actions>
                            <VSpacer />
                            <v-btn :disabled="loading" text @click="citaChange = false">
                                Cerrar
                            </v-btn>
                            <v-btn color="green" dark :disabled="loading" text @click="saveChangeCitas()">
                                Guardar cambios
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <!-- Calendar slot -->
                <v-flex xs12>
                    <v-sheet min-height="auto">
                        <VProgressLinear v-show="loading" :active="loading" color="primary darken-2" indeterminate />
                        <v-calendar color="primary" :first-interval="6" intervalHeight="160" :interval-count="16"
                            locale="es" ref="calendar" :type="type" v-model="today" @click:date="daySelected()"
                            @change="dateChange">
                            <template v-slot:day="{ date }">
                                <template v-for="(event,index) in eventsMap[date]">
                                    <v-menu :disabled="loading" :key="index" full-width offset-x v-model="event.open">
                                        <template v-slot:activator="{ on }">
                                            <div :class="(event.Estado_id == 3)? 'my-event' : 'my-event-blocked'"
                                                v-html="event.especialidad + '-' + event.tAgenda" v-if="!event.time" v-on="on" v-ripple />
                                        </template>
                                        <v-card color="grey lighten-4" min-width="500px" flat>
                                            <v-toolbar dark color="primary">
                                                <VSpacer />
                                                <v-toolbar-title>
                                                    {{ event.Sede }}
                                                    <v-btn dark fab outline small v-if="can('agenda.block')"
                                                        @click="blockAgenda(event)">
                                                        <v-icon>block</v-icon>
                                                    </v-btn>
                                                    <v-btn dark fab outline small v-if="can('agenda.delete')"
                                                        @click="cancelarAgenda(event)">
                                                        <v-icon>clear</v-icon>
                                                    </v-btn>
                                                    <v-btn dark fab outline small v-if="can('cita.change')"
                                                        @click.native="openChangeDialog(event)">
                                                        <v-icon>sync</v-icon>
                                                    </v-btn>
                                                    <v-btn dark fab outline small
                                                        v-if="event.consultorio == 'Auditorio'"
                                                        @click="detailsAgenda(event)">
                                                        <v-icon>edit</v-icon>
                                                    </v-btn>
                                                </v-toolbar-title>
                                                <VSpacer />
                                            </v-toolbar>
                                            <v-card-title primary-title>
                                                <v-layout row wrap>
                                                    <v-flex xs12>
                                                        <span>{{ event.consultorio }} / Capacidad max:
                                                            {{ event.Cantidad }} / Duracion:
                                                            {{ event.Duracion }}min</span>
                                                    </v-flex>
                                                     <v-flex xs12>
                                                            <span>Actividad: {{ event.tAgenda }} / Especialidad:
                                                            {{ event.especialidad }} </span>
                                                    </v-flex>
                                                    <v-flex xs12 v-if="parseInt(event.Cantidad) > 1">
                                                        <span><strong>Hora Inicio:
                                                            </strong>{{ event.citas[0].Hora_Inicio | hourFormat }}
                                                        </span>
                                                        <ul>
                                                            <li v-for="(e,i) in event.citas" :key="i">
                                                                {{ e.Num_Doc }} {{e.Segundo_Ape}}
                                                                {{ e.Primer_Ape}}
                                                                {{e.Primer_Nom}} {{e.SegundoNom}}</li>
                                                        </ul>
                                                    </v-flex>
                                                    <v-flex xs12 v-else>
                                                        <ul>
                                                            <li v-for="(e,i) in event.citas" :key="i">
                                                                {{ e.Hora_Inicio | hourFormat }}</li>
                                                        </ul>
                                                    </v-flex>
                                                </v-layout>
                                            </v-card-title>
                                        </v-card>
                                    </v-menu>
                                </template>
                            </template>
                            <template v-slot:dayBody="{ date, timeToY, minutesToPixels }">
                                <template v-for="(event, index) in eventsWeekMap[date]">
                                    <!-- timed events -->
                                    <template>
                                        <v-menu :key="index" v-model="event.open" full-width offset-x>
                                            <template v-slot:activator="{ on }">
                                                <div v-ripple v-if="event.time"
                                                    :style="{ top: timeToY(event.time) + 'px', height: minutesToPixels(event.duration) + 'px' }"
                                                    :class="classEvent(event)" v-on="on">
                                                    {{ event | descriptionEventDay }}
                                                </div>
                                            </template>
                                            <v-card color="grey lighten-4" min-width="150px" flat>
                                                <v-card-title primary-title v-if="parseInt(event.cantidad) > 1">
                                                    <v-layout row wrap>
                                                        <v-flex xs12>
                                                            <span>{{event.Tipo_Cita}} <v-btn v-if="can('cita.block')"
                                                                    small fab outline @click="blockCita(event)">
                                                                    <v-icon>block</v-icon>
                                                                </v-btn></span>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <ul>
                                                                <li v-for="(e,i) in event.pacientes" :key="i">
                                                                    {{ e.Num_Doc }} {{e.Segundo_Ape}}
                                                                    {{ e.Primer_Ape}}
                                                                    {{e.Primer_Nom}} {{e.SegundoNom}}
                                                                </li>
                                                            </ul>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-card-title>
                                                <v-toolbar color="primary" dark v-else>
                                                    <v-spacer></v-spacer>
                                                    <v-toolbar-title>
                                                        {{ event.Primer_Nom }} {{ event.Primer_Ape }} |
                                                        {{ event.Tipo_Cita }}
                                                        <v-btn v-if="can('cita.block')" dark small fab outline
                                                            @click="blockCita(event)">
                                                            <v-icon>block</v-icon>
                                                        </v-btn>
                                                    </v-toolbar-title>
                                                </v-toolbar>
                                                <span>{{ event.observacion }}</span>
                                            </v-card>
                                        </v-menu>
                                    </template>
                                </template>
                            </template>
                        </v-calendar>
                    </v-sheet>
                </v-flex>
            </v-layout>
        </v-container>

    </div>
</template>

<script>
    import {
        mapGetters
    } from 'vuex';
    import moment from 'moment';
    import swal from 'sweetalert';
    import {
        AppointmentService
    } from './../../services';

    moment.locale('es');
    export default {
        name: 'AgendaTable',
        data() {
            return {
                agendas: [],
                filtros: {
                    informe: 2,
                    fechaFinal: moment().format('YYYY-MM-DD'),
                    fechaInicio: moment().format('YYYY-MM-DD'),
                },
                activarFechaIncio: false,
                activarFechaFinal: false,
                hoy: moment().format('YYYY-MM-DD'),
                agendaForChange: {
                    Duracion: 0,
                    Estado_id: 0,
                    especialidad: '',
                    especialidad_id: '',
                    especialidadTipoAgenda_id: '',
                    Fecha: '',
                    Hora_Final: '',
                    Hora_Inicio: '',
                    Sede: '',
                    citas: [],
                    citasSelected: [],
                    consultorio: '',
                    id: null,
                    medico: null,
                    newActivity: '',
                    open: false,
                    tAgenda: null,
                },
                anioCalendar: {
                    current: moment().format('YYYY'),
                    next: moment().format('YYYY'),
                },
                auditorioHeaders: [{
                        text: "Estado",
                        align: "center",
                        value: ""
                    },
                    {
                        text: "Medico",
                        align: "center",
                        value: "medico"
                    },
                    {
                        text: "Hora Inicio",
                        align: "center",
                        value: "Hora_Inicio"
                    },
                    {
                        text: "Sede",
                        align: "center",
                        value: "Sede"
                    },
                    {
                        text: "Nombres",
                        align: "center",
                        value: "Primer_Nom"
                    },
                    {
                        text: "Apellidos",
                        align: "center",
                        value: "Primer_Ape"
                    }

                ],
                auditorio: {},
                citaChange: false,
                data: {
                    medico_id: null,
                    startDate: moment().startOf('month').format('YYYY-MM-DD'),
                    endDate: moment().endOf('month').format('YYYY-MM-DD'),
                },
                datePicker: false,
                dialog: false,
                disabledExportBtn: false,
                startDateExportAppointment: moment().format('YYYY-MM-DD'),
                finishDateExportAppointment: moment().format('YYYY-MM-DD'),
                isActiveStartDateExportAppointment: false,
                isActiveFinistDateExportAppointment: false,
                lightMenu: [{
                        color: '#1867c0',
                        text: 'Disponible'
                    },
                    {
                        color: '#ccc',
                        text: 'Bloqueada'
                    },
                    {
                        color: '#E0180B',
                        text: 'Inasistencia'
                    },
                    {
                        color: '#098909',
                        text: 'Asignada sin atender'
                    },
                    {
                        color: '#E0CD0B',
                        text: 'En consulta'
                    },
                    {
                        color: '#0BB9E0',
                        text: 'Atendida'
                    },
                ],
                loading: false,
                medicos: [],
                nameMonth: {
                    current: moment().format('MMMM'),
                    next: moment().format('MMMM'),
                },
                save: true,
                search: '',
                today: moment().format('YYYY-MM-DD'),
                type: 'month',
                typeAgenda: []
            }
        },
        filters: {
            descriptionEventDay: (event) => {
                if ((event.estado == 3 && (!event.estado_citapaciente || event.estado_citapaciente == 6)) || event
                    .estado == 10) return `${event.title}`
                if (parseInt(event.cantidad) > 1) {
                    return `${event.Tipo_Cita}`
                } else {
                    return `${event.title} - ${event.Tipo_Doc} ${event.Num_Doc} ${event.Primer_Nom} ${event.SegundoNom} ${event.Primer_Ape} ${ event.Segundo_Ape }`
                }

            },
            estadoCita: (estado) => (estado == 3) ? 'Disponible' : 'Bloqueada',
            hourFormat: (fecha) => moment(fecha).format('LT'),

        },
        computed: {
            ...mapGetters(['can']),
            listMedicos() {
                return this.medicos.map(medico => {
                    return {
                        id: medico.id,
                        nombre: `${medico.cedula} - ${medico.name} ${medico.apellido}`
                    }
                })
            },
            // convert the list of events into a map of lists keyed by date
            eventsMap() {
                const map = {}
                this.agendas.forEach(e => (map[e.Fecha] = map[e.Fecha] || []).push(e))
                return map
            },
            eventsWeekMap() {
                let citas = [];

                for (let i = 0; i < this.agendas.length; i++) {
                    if (!citas.hasOwnProperty(this.agendas[i].Fecha)) citas[this.agendas[i].Fecha] = [];

                    citas[this.agendas[i].Fecha] = citas[this.agendas[i].Fecha].concat(this.agendas[i].citas
                        .filter(cita => {

                            if (cita.estado_citapaciente == 6) {
                                var indices = [];
                                var array = this.agendas[i].citas.map(cita => cita.id);
                                var idx = array.indexOf(cita.id);
                                while (idx != -1) {
                                    indices.push(idx);
                                    idx = array.indexOf(cita.id, idx + 1);
                                }
                                // si la cita esta varias veces
                                if (indices.length > 1) {
                                    //si hay otra cita igual con diferente estado
                                    let isDiferent = false;
                                    indices.forEach(ind => {
                                        if (this.agendas[i].citas[ind].estado_citapaciente != 6)
                                            isDiferent = true;
                                    })
                                    //si hay otra cita con estado diferente no me muestre esta actual
                                    if (isDiferent) return false;
                                    //de lo contrario muestre esta cita si tiene el id de la primera posicion de indices
                                    if (this.agendas[i].citas[indices[0]].id == cita.id) return true;

                                    return false;
                                }

                                return true
                            }

                            return true;

                        }).map(cita => {
                            return {
                                id: cita.id,
                                date: this.agendas[i].Fecha,
                                time: moment(cita.Hora_Inicio).format('HH:mm'),
                                title: moment(cita.Hora_Inicio).format('HH:mm'),
                                duracion: this.agendas.Duracion,
                                estado: cita.Estado_id,
                                estadoagenda: this.agendas[i].Estado_id,
                                Primer_Nom: cita.Primer_Nom,
                                SegundoNom: cita.SegundoNom,
                                Primer_Ape: cita.Primer_Ape,
                                Segundo_Ape: cita.Segundo_Ape,
                                Tipo_Doc: cita.Tipo_Doc,
                                Num_Doc: cita.Num_Doc,
                                Tipo_Cita: this.agendas[i].tAgenda,
                                observacion: cita.observacion,
                                estado_citapaciente: cita.estado_citapaciente,
                                cantidad: this.agendas[i].Cantidad,
                                pacientes: this.agendas[i].citas,
                            }
                        })
                    )
                }
                return citas
            },
            typeAgendaFilter() {
                return this.typeAgenda
            }
        },
        mounted() {
            this.getUserInfo();
            this.fetchMedicos();
        },
        methods: {
            blockAgenda(event) {
                let msg = '';
                if (event.Estado_id == 3) msg = 'Bloquear agenda';
                else msg = 'Desbloquear agenda';
                swal({
                    title: msg,
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((confirm) => {
                    if (confirm) {
                        axios.put(`/api/agenda/bloquear/${event.id}`)
                            .then((res) => {
                                swal({
                                    title: res.data.message,
                                    text: ' ',
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                                this.fetchAgendaMedico();
                            }).catch(err => {
                                swal({
                                    title: 'Error',
                                    text: err.response.data.message,
                                    timer: 3000,
                                    icon: "error",
                                    buttons: false
                                });
                            })
                    }

                });

            },
            blockCita(event) {
                let msg = '';
                if (event.estado == 3) msg = 'Bloquear cita';
                else if (event.estado == 10) msg = 'Desbloquear cita';
                else msg = 'No puede bloquear cita asignada.';
                swal({
                    title: msg,
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((confirm) => {
                    if (confirm) {
                        axios.put(`/api/cita/bloquear/${event.id}`)
                            .then((res) => {
                                swal({
                                    title: res.data.message,
                                    text: ' ',
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                                this.fetchAgendaMedico();
                            }).catch((err) => console.log(err.response))
                    }
                });
            },
            cancelarAgenda(event) {
                swal({
                    title: 'Eliminar agenda',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                }).then((confirm) => {
                    if (confirm) {
                        axios.put(`/api/agenda/cancelar/${event.id}`)
                            .then((res) => {
                                swal({
                                    title: "¡Agenda cancelada!",
                                    text: `${res.data.message}`,
                                    timer: 2000,
                                    icon: "success",
                                    buttons: false
                                });
                                this.fetchAgendaMedico();
                            }).catch(err => {
                                swal({
                                    title: 'Error',
                                    text: err.response.data.message,
                                    timer: 3000,
                                    icon: "error",
                                    buttons: false
                                });
                            })
                    }

                });
            },
            classEvent(event) {
                if (event.estado == 10 || event.estadoagenda == 10) return 'my-cita-event-blocked with-time';

                switch (parseInt(event.estado_citapaciente)) {
                    case 3:
                        return 'my-cita-event with-time'
                        break;
                    case 4:
                        return 'my-cita-event-asigned with-time'
                        break;
                    case 8:
                        return 'my-cita-event-inConsultation with-time'
                    case 9:
                        return 'my-cita-event-attended with-time'
                    case 12:
                        return 'my-cita-event-absence with-time'
                    default:
                        if (event.estado == 3) return 'my-cita-event with-time';
                        return 'my-cita-event-blocked with-time'

                }
            },
            changeCalendarType(type) {
                this.type = type;
            },
            detailsAgenda(event) {

                let eventAux = event;

                this.auditorio = eventAux;

                this.auditorio.citas = eventAux.citas.filter(cita => cita.estado_citapaciente == 4 || cita
                    .estado_citapaciente == 9 || cita.estado_citapaciente == 8);

                this.dialog = true;
            },
            dateChange(e) {
                if (e.start.date == this.data.startDate && e.end.date == this.data.endDate) return
                this.nameMonth = {
                    current: moment(e.start.date).format('MMMM'),
                    next: moment(e.end.date).format('MMMM'),
                };
                this.anioCalendar = {
                    current: moment(e.start.date).format('YYYY'),
                    next: moment(e.end.date).format('YYYY'),
                }
                this.data.startDate = e.start.date;
                this.data.endDate = e.end.date;
                this.fetchAgendaMedico();
            },
            daySelected(e) {
                switch (this.type) {
                    case 'month':
                        this.type = 'week'
                        break;
                    case 'week':
                        this.type = 'day'
                        break;

                    default:
                        break;
                }
            },
            exportAppointments: async function () {
                this.disabledExportBtn = true;
                try {
                    let data = await AppointmentService.getAppointmentsBlobTypeByDateRangeAsync(this
                        .startDateExportAppointment, this.finishDateExportAppointment);

                    let blob = new Blob([data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                    });
                    let url = window.URL.createObjectURL(blob);
                    let a = document.createElement('a');

                    a.download =
                        `citasConsolidado${this.startDateExportAppointment}_${this.finishDateExportAppointment}.xlsx`;
                    a.href = url;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    this.disabledExportBtn = false;
                } catch (error) {
                    console.log(error)
                }
            },
            fetchAgendaMedico() {
                if (!this.data.medico_id) return;
                this.datePicker = false;
                this.loading = true;
                axios.post('/api/agenda/agendamedicos', this.data)
                    .then(res => {
                        this.agendas = res.data;
                        this.loading = false;
                    })
                    .catch(err => console.log('err', err));
            },
            fetchMedicos() {
                axios.get('/api/user/medicos')
                    .then(res => {
                        const response = res.data
                        if (this.logueado.id == 1276) {
                            this.medicos = response.filter(item => item.especialidad_medico ==
                                "MEDICO OPTOMETRIA" || item.especialidad_medico == "MEDICO OFTALMOLOGIA ");
                        } else {
                            this.medicos = response
                        }
                    });
            },
            getActivityBySpecialty(especialidad) {
                axios.post(`/api/especialidad/${especialidad}/getActivityBySpecialty`)
                    .then(res => this.typeAgenda = res.data)
            },
            open(event) {
                alert(event.title);
            },
            openChangeDialog(event) {
                this.getActivityBySpecialty(event.especialidad_id);
                this.agendaForChange = event;
                this.citaChange = true
            },
            saveChangeCitas() {
                if (!this.agendaForChange.newActivity || this.agendaForChange.citasSelected.length == 0) return
                this.loading = true;
                axios.post('/api/agenda/change', this.agendaForChange)
                    .then(({
                        data
                    }) => {
                        this.fetchAgendaMedico();
                        this.citaChange = false;

                        swal({
                            title: data.message,
                            text: ' ',
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                        // const Toast = Swal.mixin({
                        //     toast: true,
                        //     position: 'bottom',
                        //     background: 'success',
                        //     showConfirmButton: false,
                        //     timer: 3000,
                        //     timerProgressBar: true,
                        //     onOpen: (toast) => {
                        //         toast.addEventListener('mouseenter', Swal.stopTimer)
                        //         toast.addEventListener('mouseleave', Swal.resumeTimer)
                        //     }
                        // })

                        // Toast.fire({
                        //     icon: 'success',
                        //     title: 'Signed in successfully'
                        // });
                    })

            },

            getUserInfo() {
                axios.get("/api/auth/user")
                    .then(res => {
                        this.logueado = res.data.user;
                    })
                    .catch(res => {});
            },

            async exportdemanda() {
                this.disabledExportBtn = true;
                try {
                    axios({
                        method: 'post',
                        url: '/api/paciente/generarInforme',
                        responseType: 'blob',
                        params: this.filtros
                    }).then(res => {
                        let blob = new Blob([res.data], {
                            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
                        });
                        let url = window.URL.createObjectURL(blob);
                        let a = document.createElement('a');

                        a.download =
                            `citasDemandaInsatifecha${this.fechaInicio}_${this.fechaFinal}.xlsx`;
                        a.href = url;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        this.disabledExportBtn = false;
                    }).catch(err => {
                        this.preload = false,
                            console.log(err)
                    })
                } catch (error) {
                    console.log(error)
                }
            }

        },
    }

</script>

<style lang="stylus" scoped>
    .my-event-blocked {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        border-radius: 2px;
        background-color: #ccc;
        color: #ffffff;
        border: 1px solid #ccc;
        width: 100%;
        font-size: 12px;
        padding: 3px;
        cursor: pointer;
        margin-bottom: 1px;
    }

    .my-event {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        border-radius: 2px;
        background-color: #1867c0;
        color: #ffffff;
        border: 1px solid #1867c0;
        width: 100%;
        font-size: 12px;
        padding: 3px;
        cursor: pointer;
        margin-bottom: 1px;

    }

    .my-cita-event {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        border-radius: 2px;
        background-color: #1867c0;
        color: #ffffff;
        border: 1px solid #1867c0;
        width: 100%;
        font-size: 12px;
        padding: 3px;
        cursor: pointer;
        margin-bottom: 1px;
    }

    .my-cita-event.with-time {
        position: absolute;
        margin-right: 0px;
        border: #000 1px solid;
    }

    .my-cita-event-blocked {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        border-radius: 2px;
        background-color: #ccc;
        color: #ffffff;
        border: 1px solid #ccc;
        width: 100%;
        font-size: 12px;
        padding: 3px;
        cursor: pointer;
        margin-bottom: 1px;
    }

    .my-cita-event-blocked.with-time {
        position: absolute;
        margin-right: 0px;
        border: #ccc 1px solid;
    }

    .my-cita-event-asigned {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        border-radius: 2px;
        background-color: #098909;
        color: #ffffff;
        border: 1px solid #098909;
        width: 100%;
        font-size: 12px;
        padding: 3px;
        cursor: pointer;
        margin-bottom: 1px;
    }

    .my-cita-event-asigned.with-time {
        position: absolute;
        margin-right: 0px;
        border: #000 1px solid;
    }

    .my-cita-event-absence {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        border-radius: 2px;
        background-color: #E0180B;
        color: #ffffff;
        border: 1px solid #E0180B;
        width: 100%;
        font-size: 12px;
        padding: 3px;
        cursor: pointer;
        margin-bottom: 1px;
    }

    .my-cita-event-absence.with-time {
        position: absolute;
        margin-right: 0px;
        border: #000 1px solid;
    }

    .my-cita-event-inConsultation {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        border-radius: 2px;
        background-color: #E0CD0B;
        color: #ffffff;
        border: 1px solid #E0CD0B;
        width: 100%;
        font-size: 12px;
        padding: 3px;
        cursor: pointer;
        margin-bottom: 1px;
    }

    .my-cita-event-inConsultation.with-time {
        position: absolute;
        margin-right: 0px;
        border: #000 1px solid;
    }

    .my-cita-event-attended {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        border-radius: 2px;
        background-color: #0BB9E0;
        color: #ffffff;
        border: 1px solid #0BB9E0;
        width: 100%;
        font-size: 12px;
        padding: 3px;
        cursor: pointer;
        margin-bottom: 1px;
    }

    .my-cita-event-attended.with-time {
        position: absolute;
        margin-right: 0px;
        border: #000 1px solid;
    }

</style>
