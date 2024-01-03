<template>
    <div>
        <v-dialog v-model="dialogCon" width="500">
            <v-card>
                <v-card-title
                    class="headline"
                >
                    Agendar Cita
                </v-card-title>
                <v-card-text>
                    Asignar cita de tipo <b> {{ actividad_selected }} </b> al usuario <b>{{ paciente.Primer_Nom }} {{ paciente.SegundoNom }} {{ paciente.Primer_Ape}} {{ paciente.Segundo_Ape }}</b> identificado con <b>{{ paciente.Tipo_Doc }}</b>  N° <b>{{ paciente.Num_Doc }}</b> el día <b>{{ fecha_selected }}</b> a las <b>{{ data.hora_inicio | time}}</b> en la sede <b>{{ sede_selected }}</b>, <b>{{ data.consultorio }}</b> con el médico <b>{{ medico_selected }}</b>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn flat @click="dialogCon = false">cancelar</v-btn>
                    <v-btn color="primary" flat @click="agendarCita()">Aceptar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
            <v-card max-height="100%" v-show="true">
                <v-card-title>
                    <span class="title layout justify-center font-weight-light">Agenda Disponible Médico</span>
                </v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs3 px-1> <!-- especialidad -->
                            <v-autocomplete
                                v-model="especialidad_selected"
                                :items="especialidades"
                                item-text="Nombre"
                                item-value="id"
                                label="Especialidades"
                                @input="fetchSedes()"
                            ></v-autocomplete>
                        </v-flex>
                        <!-- sede -->
                        <v-flex
                            xs3
                            px-1
                        >
                            <v-select
                                v-model="sede_selected"
                                :items="sedes"
                                label="Sede"
                                item-text="Nombre"
                                item-value="id"
                                @input="actividad()"
                            ></v-select>
                        </v-flex>
                        <v-flex
                            xs3
                            px-1
                        >
                            <v-autocomplete
                                v-model="actividad_selected"
                                :items="actividades"
                                item-text="name"
                                item-value="et_id"
                                label="Actividad"
                                @input="fetchAgendas()"
                            ></v-autocomplete>
                        </v-flex>
                        <v-flex
                            xs3
                            px-1
                        >
                            <v-select
                                v-model="medico_selected"
                                :items="medicosSede"
                                label="Médico"
                            ></v-select>
                        </v-flex>
                        <v-flex xs3>
                            <!-- fecha -->
                            <v-menu
                                v-model="datePicker"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                lazy
                                transition="scale-transition"
                                offset-y
                                full-width
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-combobox
                                        v-model="data.fecha_solicitada"
                                        label="Fecha solicitada"
                                        prepend-icon="event"
                                        readonly
                                        v-on="on"

                                    ></v-combobox>
                                </template>
                                <v-date-picker color="primary" locale="es" v-model="data.fecha_solicitada" :min="today" :show-current="false" @input="agendaSolicitada()"></v-date-picker>
                            </v-menu>
                        </v-flex>
                        <v-flex xs9 px-2>
                            <v-text-field  label="Observación" v-model="data.observaciones">
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
                                            <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                                        </v-card-text>
                                    </v-card>
                                </v-dialog>
                            </div>
                        </template>
                        <template>
                            <v-data-table
                                :headers="headersAgenda"
                                :items="agendaDisponible"
                                item-key="name"
                                class="elevation-1"
                                color="primary"
                                :rows-per-page-items="[20,30,50]"
                                rows-per-page-text="Citas por página"
                            >
                                <template v-slot:items="props">
                                    <td class="text-xs-center">{{ props.item.hora_inicio | time }}</td>
                                    <td class="text-xs-center">{{ props.item.consultorio }}</td>
                                    <td class="text-xs-center">
                                        <v-btn
                                            v-show="can('cita.toAssign')" color="success" fab outline small @click="asignarCita(props.item)">
                                            <v-icon>how_to_reg</v-icon>
                                        </v-btn>
                                    </td>
                                </template>
                            </v-data-table>
                        </template>
                    </v-container>
                </v-card-text>

            </v-card>
    </div>
</template>
<script>
import moment from "moment";
import {mapGetters} from "vuex";
moment.locale("es");
export default {
    name: "agendamientoDinamico",
    filters:{
        time(date){
            return moment(date).format('HH:mm:ss');
        }
    },
    props: {
        paciente: Object,
        modulo: String,
        valor: Number
    },
    mounted() {
        this.fetchEspecialidades();
    },
    computed: {
        ...mapGetters(['can']),
    },
    data() {
        return {
            dialogCon:false,
            actividades:[],
            agendaDisponible:[],
            medicosSede:[],
            preload_cita: false,
            especialidades:[],
            especialidad_selected:null,
            sedes:[],
            sede_selected:null,
            actividad_selected: null,
            medico_selected: null,
            datePicker: false,
            today: moment().format('YYYY-MM-DD'),
            fecha:null,
            fecha_selected:null,
            data:{
                cita_id: null,
                hora_inicio: null,
                consultorio: null,
                Paciente_id: null,
                fecha_solicitada: null,
                observaciones: null,
                cita_paciente_padre:null
            },
            FechaAgendamiento:null,
            headersAgenda: [
                { text: 'Hora', align: 'center', sortable: false, value: 'Agenda_id' },
                { text: 'Consultorio', sortable: false, align: 'center', value: 'consultorio' },
                { text: '', align: 'center', value: '' },
            ],
        }
    },
    methods:{
        fetchEspecialidades(){
            axios.get(`/api/agenda/agendaDisponible/especialidades`)
                .then((res) => {
                    this.especialidades = res.data;
                });
        },
        fetchSedes(){
            axios.post(`/api/agenda/agendaDisponible/sedes`,{ especialidad: this.especialidad_selected })
                .then((res) => {
                    this.sedes = res.data
                });
        },
        fetchAgendas(){
            this.datePicker = false;
            if(this.especialidad_selected && this.sede_selected && this.actividad_selected){
                axios.post('/api/agenda/agendaDisponible', { fecha: this.fecha, sede: this.sede_selected, tipo_agenda: this.actividad_selected })
                    .then(res => {
                        this.agendas = res.data
                        this.medicosSedes();
                    });
            }
        },
        agendaSolicitada(){
            this.dateSolicitada = false;
            this.fecha = this.data.fecha_solicitada;
            this.agendaDisponibles();
        },
        asignarCita(cita){

            this.dialogCon = true;
            this.data.cita_id = cita.id;
            this.data.hora_inicio = cita.hora_inicio;
            this.data.consultorio = cita.consultorio;
            this.data.Paciente_id = this.paciente.id;
            this.FechaAgendamiento = moment(this.fecha).format('Y-MM-DD');
            this.fecha_selected = moment(this.fecha).format('dddd, D MMMM, YYYY');
        },
        agendaDisponibles(){
            let citasEnable = [];
            let citas = [];

            for (let i = 0; i < this.agendas.length; i++) {
                let citas = [];
                let medico = `${this.agendas[i].nombreMedico} ${this.agendas[i].apellidoMedico}`
                if (medico === this.medico_selected && this.agendas[i].fecha == this.fecha ) {
                    citas = this.agendas[i].citas.map(cita =>  {
                        return {
                            id: cita.id,
                            hora_inicio: cita.Hora_Inicio,
                            consultorio: this.agendas[i].nombreConsultorio
                        }
                    });
                    citasEnable = citasEnable.concat(citas);
                }
            }

            this.agendaDisponible = citasEnable.sort((a, b) =>  moment(a.hora_inicio) - moment(b.hora_inicio));
        },
        medicosSedes(){
            this.medicosSede =  this.agendas.map((agenda) => `${agenda.nombreMedico} ${agenda.apellidoMedico}`)
        },
        actividad(){
            this.actividades = this.especialidades.filter(e => this.especialidad_selected === e.id && this.sede_selected == e.sede)
        },
        agendarCita(){
            this.preload_cita = true;
            axios.put(`/api/cita/asignarcita/${this.data.cita_id}`, this.data )
                .then((res) => {
                    this.dialogCon = false;
                    this.preload_cita = false;
                    switch (this.modulo){
                        case 'demandaInducida':
                            axios.put('/api/historia/asignarCitaDemandaInducida/'+this.valor+'/'+res.data.cita_id).then(res => {
                                this.$alerSuccess(res.data.mensaje);
                                this.$emit('cerrarAgendamiento')
                            })
                            break;
                    }
                })
                .catch((err) => this.showMessage(err.response.data.message))
        },
    }
}
</script>
