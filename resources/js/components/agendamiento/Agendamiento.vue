<template>
    <v-container grid-list-md fluid class="pa-0">
        <v-layout row wrap>
            <v-flex px-2 md4 xs1>
                <v-autocomplete :items="allEspelidades" v-model="especialidad" item-text="Nombre" return-object
                    label="Especialidad" @change="fetchEspecialidadesMedico()">

                </v-autocomplete>
            </v-flex>
            <v-flex px-2 md4 xs1>
                <v-autocomplete :items="especialidadesMedico" v-model="data.medico_id" label="Médico"
                    :item-text="especialidadesMedico=>especialidadesMedico.cedula+' - '+especialidadesMedico.name+' - '+ especialidadesMedico.apellido"
                    item-value="id" required>
                </v-autocomplete>
            </v-flex>
            <v-flex px-2 md4 xs1>
                <v-autocomplete :items="actividades" v-model="data.especialidad_id" item-text="nombreActividad"
                    return-object label="Actividad" @change="modalidadActividad(data.especialidad_id)">
                </v-autocomplete>
            </v-flex>
             <v-flex px-2 md4 xs1>
                <v-text-field v-model="modalidad" label="Modalidad" readonly>
                </v-text-field>
            </v-flex>
            <v-flex px-2 md4 xs1>
                <v-select :items="sedes" label="Sede" item-text="Nombre" item-value="id" v-model="sede"
                    @change="fetchConsultorios">
                </v-select>
            </v-flex>
            <v-flex px-2 md4 xs1>
                <v-select :items="consultorios" label="Consultorio" item-text="Nombre" item-value="id"
                    v-model="data.consultorio_id" @change="setEvents">
                </v-select>
            </v-flex>
            <v-flex px-3 md4 xs1>
                <v-menu ref="menu" v-model="menu2" :close-on-content-click="false" :nudge-right="40"
                    :return-value.sync="data.hora_inicio" lazy transition="scale-transition" offset-y full-width
                    max-width="290px" min-width="290px">
                    <template v-slot:activator="{ on }">
                        <v-text-field v-model="data.hora_inicio" label="Hora Inicial" prepend-icon="access_time"
                            readonly v-on="on" @change="setEvents"></v-text-field>
                    </template>
                    <v-time-picker :allowedMinutes="v => v%5 == 0" :allowed-hours="intervalHours" :max="data.hora_final"
                        v-if="menu2" v-model="data.hora_inicio" format="24hr" full-width
                        @click:minute="$refs.menu.save(data.hora_inicio)" @change="setEvents"></v-time-picker>
                </v-menu>
            </v-flex>
            <v-flex px-3 md4 xs1>
                <v-menu ref="menu1" v-model="menu3" :close-on-content-click="false" :nudge-right="40"
                    :return-value.sync="data.hora_final" lazy transition="scale-transition" offset-y full-width
                    max-width="290px" min-width="290px">
                    <template v-slot:activator="{ on }">
                        <v-text-field v-model="data.hora_final" label="Hora Final" prepend-icon="access_time" readonly
                            v-on="on" @change="setEvents"></v-text-field>
                    </template>
                    <v-time-picker :allowedMinutes="v => v%5 == 0" :allowed-hours="intervalHours"
                        :min="data.hora_inicio" v-if="menu3" v-model="data.hora_final" format="24hr" full-width
                        @click:minute="$refs.menu1.save(data.hora_final)" @change="setEvents"></v-time-picker>
                </v-menu>
            </v-flex>
        </v-layout>
        <template>
            <div class="text-center">
                <v-dialog v-model="preload_Agenda" persistent width="300">
                    <v-card color="primary" dark>
                        <v-card-text>
                            Tranquilo procesamos tu solicitud !
                            <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                        </v-card-text>
                    </v-card>
                </v-dialog>
            </div>
        </template>
        <v-layout row wrap>
            <v-flex xs10>
                <v-btn color="warning" @click="emptyData()" round>Limpiar campos</v-btn>
            </v-flex>
            <v-flex xs2 pr-3>
                <v-btn color="success" block @click="saveAgenda()" round>Guardar</v-btn>
            </v-flex>
        </v-layout>
        <v-layout wrap justify-space-around fill-height>
            <v-flex xs12 md3>
                <v-date-picker color="primary" v-model="data.fechas" width="280" class="mt-3" locale="es"
                    @input="setEvents" multiple :min="date" first-day-of-week="1" ></v-date-picker>
                <v-layout row wrap>
                    <v-flex sm12 px-3>
                        <v-combobox v-model="data.fechas" multiple chips small-chips label="Fechas escogidas"
                            prepend-icon="event" readonly></v-combobox>
                    </v-flex>
                </v-layout>
            </v-flex>
            <v-flex xs12 md9 class="mb-3" height="10px">
                <v-sheet height="400">
                    <v-calendar ref="calendar" :first-interval="6" :interval-count="16" :interval-height="40"
                        :weekdays="[1,2,3,4,5,6,0]" :type="type" color="primary" locale="es" v-model="date"
                        :value="date">
                        <template slot="dayBody" slot-scope="{ date, timeToY, minutesToPixels }">
                            <template v-for="event in eventsMap[date]">
                                <v-menu :key="event.id" v-model="event.estado" full-width offset-x>
                                    <template v-slot:activator="{ on }">
                                        <div v-ripple v-if="event.time"
                                            :style="{ top: timeToY(event.time) + 'px', height: minutesToPixels(event.duration) + 'px' }"
                                            class="with-time" :class="event.estado ? 'my-event' : 'my-block-event'"
                                            @click="open(date)" v-on="on"></div>
                                    </template>
                                    <v-card color="grey lighten-4" min-width="350px" flat>
                                        <v-toolbar color="primary" dark>
                                            <v-btn icon @click="event.estado = !event.estado">
                                                <v-icon>block</v-icon>
                                            </v-btn>
                                            <v-toolbar-title v-html="`${event.date} - ${event.time}`"></v-toolbar-title>
                                            <v-spacer></v-spacer>
                                        </v-toolbar>
                                    </v-card>
                                </v-menu>
                            </template>

                            <template v-for="e in newEvents[date]">
                                <v-menu :key="e.id" v-model="e.open" full-width offset-x>
                                    <template v-slot:activator="{ on }">
                                        <div v-ripple v-if="e.time"
                                            :style="{ top: timeToY(e.time) + 'px', height: minutesToPixels(e.duration) + 'px' }"
                                            class="with-time" :class="e.estado ? 'my-event' : 'my-block-event'"
                                            @click="open(date)" v-on="on"></div>
                                    </template>
                                    <v-card color="grey lighten-4" min-width="350px" flat>
                                        <v-toolbar color="primary" dark>
                                            <v-btn icon @click="e.estado = !e.estado">
                                                <v-icon>block</v-icon>
                                            </v-btn>
                                            <v-toolbar-title v-html="`${e.date} - ${e.time}`"></v-toolbar-title>
                                            <v-spacer></v-spacer>
                                        </v-toolbar>
                                    </v-card>
                                </v-menu>
                            </template>
                        </template>
                        <template slot="interval" slot-scope="{date}">
                            <div @click="open(date)"></div>
                        </template>
                    </v-calendar>
                </v-sheet>
                <v-layout justify-space-between sm12 xs12>
                    <v-flex sm4 xs12 class="text-sm-left text-xs-center">
                        <v-btn @click="$refs.calendar.prev()">
                            <v-icon dark left>keyboard_arrow_left</v-icon>Anterior
                        </v-btn>
                    </v-flex>
                    <v-flex sm4 xs12 class="text-sm-right text-xs-center">
                        <v-btn @click="$refs.calendar.next()">
                            Siguiente
                            <v-icon right dark>keyboard_arrow_right</v-icon>
                        </v-btn>
                    </v-flex>
                </v-layout>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    import Swal from 'sweetalert2';
    import moment from "moment";
    export default {
        name: "Agendamiento",
        data() {
            return {
                preload_Agenda: false,
                menu2: false,
                menu3: false,
                tipos_agenda: [],
                logueado: [],
                medicos: [],
                sedes: [],
                sede: null,
                modalidad: null,
                consultorios: [],
                type: "week",
                save: true,
                dialog: false,
                actividades: [],
                especialidad: 0,
                allEspelidades: [],
                especialidadesMedico: [],
                date: moment().format("YYYY-MM-DD"),
                data: {
                    fechas: [moment().format("YYYY-MM-DD")],
                    citas: [],
                    hora_inicio: "",
                    hora_final: "",
                    consultorio_id: null,
                    medico_id: null,
                    especialidad_id: null
                },
                events: []
            };
        },
        computed: {
            // convert the list of events into a map of lists keyed by date
            eventsMap() {
                const map = {};
                this.events.forEach(e => (map[e.date] = map[e.date] || []).push(e));
                return map;
            },
            newEvents() {
                const map = {};
                this.data.citas.forEach(e => (map[e.date] = map[e.date] || []).push(e));
                return map;
            }
        },
        mounted() {
            this.fetchAgendas();
            this.fetchSedes();
            this.fetchMedicos();
            this.getUserInfo();
            this.fetchEspecialidades();
            window.app = this.$refs.app;
        },
        methods: {
            fetchAgendas() {
                axios
                    .get("/api/tipoagenda/habilitados")
                    .then(res => (this.tipos_agenda = res.data));
            },
            fetchSedes() {
                axios.get("/api/sede/enabled").then(res => (this.sedes = res.data));
            },
            fetchConsultorios() {
                axios
                    .get(`/api/sede/${this.sede}/consultorio/all`)
                    .then(res => (this.consultorios = res.data));
            },
            fetchMedicos() {
                axios.get(`/api/user/medicos/`).then(res => {
                    this.medicos = res.data.map(medico => {
                        return {
                            id: medico.id,
                            fullname: `${medico.cedula} - ${medico.name} ${medico.apellido}`
                        };
                    });
                });
            },
            fetchEspecialidades() {
                axios.get('/api/especialidad/all/').
                then(res => {
                    const response = res.data
                    if (this.logueado.id == 1276) {
                        this.allEspelidades = response.filter(item => item.Nombre == "Optometria" || item
                            .Nombre == "Oftalmologia");
                    } else {
                        this.allEspelidades = response
                    }
                });
            },
            fetchEspecialidadesMedico() {
                this.fetchActividades();
                axios
                    .get(`/api/especialidad/${this.especialidad.Nombre}/especialidadMedicos`)
                    .then(res => {
                        this.especialidadesMedico = res.data
                    });
            },
            fetchActividades() {
                axios
                    .get(`/api/especialidad/tipoactividad/${this.especialidad.id}`)
                    .then(res => (
                        this.actividades = res.data));
            },
            showError(message) {
                swal({
                    title: "¡No puede ser!",
                    text: `${message.message}`,
                    icon: "warning"
                });
            },
            emptyData() {
                this.dialogDetalle = false;
                this.sede = null;
                this.especialidad = null;
                this.modalidad = null;
                this.data = {
                    fechas: [moment().format("YYYY-MM-DD")],
                    citas: [],
                    hora_inicio: "",
                    hora_final: "",
                    consultorio_id: null,
                    medico_id: null,
                    especialidad_id: null
                };
            },
            saveAgenda() {
                if (this.data.citas.length > 0) {
                    axios.post("/api/agenda/create", this.data).then(res => {
                            this.preload_Agenda = false
                            this.emptyData()
                             this.$alerSuccess('Agenda creada con exito!.');
                        }).catch(err => this.$alerError(err.response.data.message));
                } else {
                    swal({
                        title: "¡No puede ser!",
                        text: `No hay citas para crear agenda`,
                        icon: "warning"
                    });
                }
            },
            open(e) {
                //console.log('e :', e);
            },
            intervalHours(h) {
                if (h > 5 && h < 23) return true
                return false;
            },
            setEvents() {
                if (
                    this.data.especialidad_id &&
                    this.data.medico_id &&
                    this.data.consultorio_id &&
                    this.data.hora_inicio &&
                    this.data.hora_final
                ) {
                    //this.events = [];
                    this.data.citas = [];
                    //await this.fetchAgendas();
                    this.data.fechas.forEach(fecha => {
                        let time = moment(`${fecha} ${this.data.hora_final}`).diff(
                            moment(`${fecha} ${this.data.hora_inicio}`),
                            "YYYY-MM-DD HH:mm:ss"
                        );
                        let hours =
                            (moment.duration(time).hours() * 60) /
                            this.data.especialidad_id.Duracion;
                        let minutes = Math.floor(
                            moment.duration(time).minutes() / this.data.especialidad_id.Duracion
                        );
                        time = hours + minutes;
                        for (let i = 0; i < time; i++) {
                            this.data.citas.push({
                                title: this.data.especialidad_id.name,
                                date: fecha,
                                time: moment(`${fecha} ${this.data.hora_inicio}`)
                                    .add(this.data.especialidad_id.Duracion * i, "minutes")
                                    .format("HH:mm"),
                                horaFin: moment(`${fecha} ${this.data.hora_inicio}`)
                                    .add(
                                        this.data.especialidad_id.Duracion * i +
                                        this.data.especialidad_id.Duracion,
                                        "minutes"
                                    )
                                    .format("HH:mm"),
                                duration: this.data.especialidad_id.Duracion,
                                estado: true,
                                open: false
                            });
                        }
                    });
                }
            },

            getUserInfo() {
                axios.get("/api/auth/user")
                    .then(res => {
                        this.logueado = res.data.user;
                    })
                    .catch(res => {});
            },

            modalidadActividad(item){
                this.modalidad = item.modalidad
                console.log(item)
            }
        }
    };

</script>

<style scope>
    .my-event {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        border-radius: 2px;
        background-color: #1867c0;
        color: #fff;
        border: 1px solid #1867c0;
        font-size: 12px;
        padding: 3px;
        cursor: pointer;
        margin-bottom: 1px;
        left: 4px;
        margin-right: 8px;
        position: relative;
    }

    .my-block-event {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        border-radius: 2px;
        background-color: #888888;
        color: #fff;
        border: 1px solid #888888;
        font-size: 12px;
        padding: 3px;
        cursor: pointer;
        margin-bottom: 1px;
        left: 4px;
        margin-right: 8px;
        position: relative;
    }

    .my-event.with-time {
        position: absolute;
        right: 4px;
        margin-right: 0px;
        border: #000 1px solid;
    }

    .my-block-event.with-time {
        position: absolute;
        right: 4px;
        margin-right: 0px;
        border: #000 1px solid;
    }

</style>
