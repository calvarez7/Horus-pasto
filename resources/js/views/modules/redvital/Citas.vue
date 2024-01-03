<template>
    <v-content>

        <v-layout class="mt-5 my-5">
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm12 md10>
                        <v-card class="elevation-12">

                            <v-card v-show="validacion()">
                                <v-bottom-nav :value="true" color="transparent">
                                    <v-btn color="primary" flat @click="agendar()">
                                        <span>Agendar</span>
                                        <v-icon>event</v-icon>
                                    </v-btn>
                                    <v-btn color="primary" flat @click="getpendientes()">
                                        <span>Pendientes</span>
                                        <v-icon>history</v-icon>
                                    </v-btn>
                                </v-bottom-nav>
                            </v-card>

                            <v-card v-show="showagendar" class="py-4">
                                <v-layout row wrap>
                                    <v-flex xs12 sm12 md6 px-2>
                                        <v-card>
                                            <v-toolbar color="primary" flat dark>
                                                <v-flex xs12 text-xs-center>
                                                    <v-toolbar-title>Actualizar datos de contacto</v-toolbar-title>
                                                </v-flex>
                                            </v-toolbar>
                                            <v-layout row wrap>
                                                <v-flex xs12 md12 text-xs-justify>
                                                    <span>Para nosotros es de vital importancia mantener los datos de
                                                        contacto actualizados
                                                        por si requerimos contactarnos con usted, muchas gracias.
                                                    </span>
                                                </v-flex>
                                                <v-flex xs11 md11 px-2>
                                                    <v-text-field label="Correo" v-model="paciente.Correo1">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs1 md1>
                                                    <v-checkbox v-model="checkboxCorreo" color="redvitalAzul">
                                                    </v-checkbox>
                                                </v-flex>
                                                <v-flex xs11 md5 px-2>
                                                    <v-text-field label="Celular" v-model="paciente.Celular1"
                                                        type="number" maxlength="10"
                                                        oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                        onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                                        min="1">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs1 md1>
                                                    <v-checkbox v-model="checkboxCelular" color="redvitalAzul">
                                                    </v-checkbox>
                                                </v-flex>
                                                <v-flex xs12 md6 px-2>
                                                    <v-text-field label="Telefono" type="number"
                                                        onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                                        oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                        maxlength="7" min="1" v-model="paciente.Telefono">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs11 md11 px-2>
                                                    <v-text-field label="Dirección"
                                                        v-model="paciente.Direccion_Residencia">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs1 md1>
                                                    <v-checkbox v-model="checkboxDireccion" color="redvitalAzul">
                                                    </v-checkbox>
                                                </v-flex>
                                                <v-flex xs12 md12 px-2>
                                                    <v-checkbox v-model="checkbox" color="redvitalAzul"
                                                        label="Con el diligenciamiento de este formato autorizo expresamente el uso de mis datos personales según Ley 1581 de 2012">
                                                    </v-checkbox>
                                                </v-flex>
                                                <v-flex xs12 md3 px-1>
                                                    <v-btn small dark color="redvitalVerde"
                                                        @click="dataPaciente('validar')">
                                                        Validar y actualizar
                                                    </v-btn>
                                                </v-flex>
                                            </v-layout>
                                        </v-card>
                                        <v-divider class="mt-4"></v-divider>
                                    </v-flex>
                                    <v-flex v-show="validation" xs12 sm12 md6 px-2>
                                        <v-flex>
                                            <v-autocomplete v-model="actividad_selected" :items="actividades"
                                                item-text="nombreActividad" item-value="id" label="Servicio"
                                                @input="fetchAgendas()"></v-autocomplete>
                                        </v-flex>
                                        <v-flex v-if="actividad_selected">
                                            <v-text-field v-if="medico_familia == 'null null'"
                                                v-model="medico_familia_null" readonly label="Médico de Familia">
                                            </v-text-field>
                                            <v-text-field v-else v-model="medico_familia" readonly
                                                label="Médico de Familia">
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex v-if="actividad_selected">
                                            <v-checkbox label="Otro Médico" v-model="checkOtroMedico"
                                                color="redvitalAzul" @change="fetchAgendas()">
                                            </v-checkbox>
                                        </v-flex>
                                        <v-flex v-if="checkOtroMedico">
                                            <v-autocomplete v-model="medico_selected" @input="fetchAgendas()"
                                                :items="medicos" label="Médico">
                                            </v-autocomplete>
                                        </v-flex>
                                        <v-menu v-model="menu1" :close-on-content-click="false" full-width
                                            max-width="290">
                                            <template v-slot:activator="{ on }">
                                                <v-text-field clearable placeholder="Click y seleccione fecha deseada"
                                                    label="Fecha disponible" v-model="fecha" readonly v-on="on">
                                                </v-text-field>
                                            </template>
                                            <v-date-picker v-model="fecha" @change="menu1 = false"
                                                :allowed-dates="diasDisponibles" :events="diasEvents"
                                                :show-current="false" event-color="green lighten-1" locale="es"
                                                color="success">
                                            </v-date-picker>
                                        </v-menu>
                                        <v-data-table :headers="headersCita" :items="agendaDisponible" item-key="name"
                                            class="elevation-1" color="primary" :rows-per-page-items="[3,5,10]"
                                            rows-per-page-text="Citas por página">
                                            <template v-slot:items="props">
                                                <td>{{ props.item.hora_inicio | time }}</td>
                                                <td>{{ props.item.medico }}</td>
                                                <td>
                                                    <v-btn small color="redvitalVerde" dark
                                                        @click="openConfirmar(props.item)">
                                                        Agendar
                                                    </v-btn>
                                                </td>
                                            </template>
                                        </v-data-table>
                                    </v-flex>
                                    <v-dialog v-model="showdatos" persistent max-width="500px">
                                        <v-card class="mt-2">
                                            <v-toolbar color="primary" flat dark>
                                                <v-flex xs12 text-xs-center>
                                                    <v-toolbar-title>Datos de la cita</v-toolbar-title>
                                                </v-flex>
                                            </v-toolbar>
                                            <v-card-text>
                                                Sr <b>{{ paciente.Primer_Nom }} {{ paciente.SegundoNom }}
                                                    {{ paciente.Primer_Ape}} {{ paciente.Segundo_Ape }}</b>
                                                identificado con <b>{{ paciente.Tipo_Doc }}</b> N°
                                                <b>{{ paciente.Num_Doc }}</b>
                                                va agendar una cita de tipo <b>{{actividad}}</b>
                                                el día <b>{{ datos_cita.fecha_selected }}</b> a las
                                                <b>{{ datos_cita.hora_inicio | time}}</b> con el médico
                                                <b>{{ datos_cita.medico }}</b>.
                                            </v-card-text>
                                            <v-divider></v-divider>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="error" @click="showdatos = false">
                                                    Cancelar
                                                </v-btn>
                                                <v-btn dark color="redvitalVerde" @click="confirmarcita()">
                                                    Confirmar
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                    <v-dialog v-model="preload_cita" persistent width="300">
                                        <v-card color="primary" dark>
                                            <v-card-text>
                                                Estamos procesando su información
                                                <v-progress-linear indeterminate color="white" class="mb-0">
                                                </v-progress-linear>
                                            </v-card-text>
                                        </v-card>
                                    </v-dialog>
                                </v-layout>
                            </v-card>

                            <v-card v-show="showpendientes">
                                <v-card-title>
                                    <v-spacer></v-spacer>
                                    <v-text-field v-model="search" append-icon="search" label="Buscar" single-line
                                        hide-details>
                                    </v-text-field>
                                </v-card-title>
                                <v-card-text>
                                    <v-data-table :loading="loading" :headers="headers" :items="allcita_pendiente"
                                        :search="search">
                                        <template v-slot:items="props">
                                            <td class="text-xs-center">{{ props.item.Especialidad }}</td>
                                            <td class="text-xs-center">{{ props.item.Tipo_agenda }}</td>
                                            <td class="text-xs-center">
                                                {{ props.item.Fecha | fecha_cita_pendiente }}</td>
                                            <td class="text-xs-center">
                                                {{ props.item.Hora_Inicio | hora_cita_pendiente}}</td>
                                            <td class="text-xs-center">{{ props.item.Nombre_medico }}
                                                {{ props.item.Apellido_medico}}</td>
                                                <td>
                                                <v-btn small color="success" dark @click="imprimirRecordatorio(props.item.id)">Recordatorio
                                                </v-btn>
                                            </td>
                                            <td>
                                                <v-btn small color="error" dark @click="openMotivo(props.item)">Cancelar
                                                </v-btn>
                                            </td>
                                        </template>
                                        <template v-slot:no-results>
                                            <v-alert :value="true" color="error" icon="warning">
                                                Su búsqueda de "{{ search }}" no encontró resultados.
                                            </v-alert>
                                        </template>
                                    </v-data-table>
                                </v-card-text>
                            </v-card>

                            <v-dialog v-model="motivoCancelar" persistent max-width="600px">
                                <v-card>
                                    <v-toolbar dark color="redvitalVerde">
                                        <v-flex text-center flat dark>
                                            <v-toolbar-title>Motivo
                                            </v-toolbar-title>
                                        </v-flex>
                                        <v-spacer></v-spacer>
                                    </v-toolbar>
                                    <v-card-text>
                                        <v-container grid-list-md>
                                            <v-layout wrap>
                                                <v-flex xs12>
                                                    <v-textarea v-model="cancelar.motivo"
                                                        label="Por qué cancela la cita">
                                                    </v-textarea>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="error" @click="motivoCancelar = false, cancelar.motivo = ''">
                                            Cerrar</v-btn>
                                        <v-btn color="redvitalVerde" dark @click="cancelarCita()">
                                            Enviar</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>

                        </v-card>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-layout>

    </v-content>
</template>
<script>
    import moment from 'moment';
    import Vue from 'vue';
    import Swal from 'sweetalert2';
    export default {
        name: "Citas",
        props: {
            paciente: Object
        },
        data() {
            return {
                search: '',
                headers: [{
                        text: 'Especialidad',
                        align: 'center',
                        sortable: false,
                        value: 'Especialidad'
                    },
                    {
                        text: 'Actividad',
                        align: 'center',
                        sortable: false,
                        value: 'Tipo_agenda'
                    },
                    {
                        text: 'Fecha',
                        align: 'center',
                        sortable: false,
                        value: 'Fecha'
                    },
                    {
                        text: 'Hora de la cita ',
                        align: 'center',
                        sortable: false,
                        value: 'Hora_Inicio'
                    },
                    {
                        text: 'Nombre medico',
                        align: 'center',
                        sortable: false,
                        value: 'Nombre_medico'
                    },
                    {
                        text: '',
                        align: 'center',
                        sortable: false,
                        value: ''
                    },
                ],
                showpendientes: false,
                showagendar: false,
                allcita_pendiente: [],
                loading: false,
                motivoCancelar: false,
                cancelar: {},
                agendas: [],
                fecha: '',
                menu1: false,
                headersCita: [{
                        text: 'Hora',
                        align: 'left',
                        sortable: false,
                        value: 'hora_inicio'
                    },
                    {
                        text: 'Medico',
                        sortable: false,
                        align: 'left',
                        value: 'medico'
                    },
                    {
                        text: '',
                        align: 'left',
                        value: '',
                        sortable: false
                    },
                ],
                showdatos: false,
                datos_cita: {},
                preload_cita: false,
                checkbox: false,
                datosPaciente: [],
                validation: false,
                checkboxCorreo: false,
                checkboxCelular: false,
                checkboxDireccion: false,
                medico_selected: null,
                actividad_selected: null,
                actividad: '',
                actividades: [],
                checkOtroMedico: false,
                medico_familia: null,
                medico_familia_null: 'No tiene, marque la casilla ( Otro Médico )'

            }
        },
        computed: {
            agendaDisponible() {
                let citasEnable = [];
                for (let i = 0; i < this.agendas.length; i++) {
                    let citas = [];
                    let medico = `${this.agendas[i].nombreMedico} ${this.agendas[i].apellidoMedico}`
                    let medicoSelected = '';
                    if (this.checkOtroMedico == false) {
                        if (this.medico_familia != null) {
                            medicoSelected = this.medico_familia
                        }
                    } else if (this.medico_selected != null) {
                        medicoSelected = this.medico_selected.split('-')[0]
                    }
                    if (medico === medicoSelected && this.agendas[i].fecha === this.fecha) {
                        citas = this.agendas[i].citas.map(cita => {
                            return {
                                id: cita.id,
                                hora_inicio: cita.Hora_Inicio,
                                medico: `${this.agendas[i].nombreMedico} ${this.agendas[i].apellidoMedico}`
                            }
                        });
                        citasEnable = citasEnable.concat(citas);
                    }
                }
                return citasEnable.sort((a, b) => moment(a.hora_inicio) - moment(b.hora_inicio));
            },
            medicos() {
                let medicos = [];
                if (this.actividad_selected == null) return;
                this.agendas.forEach(s => {
                    if (s.citas.length > 0) {
                        let medico = '';
                        if (`${this.paciente.nombreMedicoFamilia } ${this.paciente.apellidoMedicoFamilia}` !=
                            `${s.nombreMedico} ${s.apellidoMedico}`) {
                            medicos.push(`${s.nombreMedico} ${s.apellidoMedico}`);
                        }
                    }
                })
                return medicos;
            },
        },
        mounted() {
            this.validacion();
            moment.locale('es');
        },
        filters: {
            fecha_cita_pendiente(fecha) {
                return moment(fecha).format('dddd, D MMMM, YYYY');
            },
            hora_cita_pendiente(Hora_Inicio) {
                return moment(Hora_Inicio).format('HH:mm');
            },
            time(date) {
                return moment(date).format('HH:mm');
            }
        },
        methods: {
            validacion() {
                if ((this.paciente.Estado_Afiliado == 1 && this.paciente.prestador_id == 67) &&
                    (this.paciente.entidad_id == 1 || this.paciente.entidad_id == 3 || this.paciente.entidad_id == 5)) {
                    return true;
                } else {
                    this.$router.push("/gestion");
                }
            },
            agendar() {
                this.showagendar = true
                this.showpendientes = false
            },
            getpendientes() {
                this.showpendientes = true
                this.showagendar = false
                this.citaPendiente();
            },
            citaPendiente() {
                this.loading = true
                axios.post(`/api/redvital/citaspendientes`, {
                    Paciente_id: this.paciente.id
                }).then((res) => {
                    this.loading = false
                    this.allcita_pendiente = res.data;
                }).catch((err) => {
                    this.loading = false
                    console.log(err.response)
                })
            },
            openMotivo(cita) {
                this.cancelar = {
                    id: cita.id,
                    motivo: '',
                    Paciente_id: this.paciente.id
                }
                this.motivoCancelar = true;
            },
            cancelarCita() {
                if (this.cancelar.motivo.length < 20) {
                    this.$alerError("Debe ingresar un motivo minimo de 20 caracteres!")
                    return;
                }
                axios.put(`/api/redvital/cancelar/${this.cancelar.id}`, this.cancelar)
                    .then((res) => {
                        this.motivoCancelar = false;
                        swal({
                            title: "¡Cita Cancelada!",
                            text: `${res.data.message}`,
                            timer: 3000,
                            icon: "success",
                            buttons: false
                        });
                        this.cancelar = {};
                        this.getpendientes();
                    })
            },
            fetchAgendas() {
                if (this.actividad_selected == null) return;
                this.fecha = ''
                this.medico_familia = `${this.paciente.nombreMedicoFamilia } ${this.paciente.apellidoMedicoFamilia}`
                this.actividades.map((act) => {
                    if (act.id === this.actividad_selected) {
                        this.actividad = act.nombreActividad
                    }
                })
                axios.post(`/api/redvital/agendaDisponible/${this.actividad_selected}`,
                        this.paciente)
                    .then((res) => {
                        this.agendas = res.data
                    });
            },
            fetchActividades() {
                axios.get(`/api/redvital/actividades`)
                    .then((res) => {
                        this.actividades = res.data
                    });
            },
            diasDisponibles(val) {
                let dia = null;
                this.agendas.forEach((agenda) => {
                    if (agenda.citas.length > 0) {
                        if (agenda.fecha == val) {
                            dia = val;
                        }
                    }
                })
                if (dia) return dia
            },
            diasEvents(val) {
                let dia = null;
                let many = false;
                this.agendas.forEach((agenda) => {
                    if (agenda.citas.length > 0) {
                        if (agenda.fecha == val) {
                            let medico = `${agenda.nombreMedico} ${agenda.apellidoMedico}`
                            let medicoSelected = '';
                            if (this.checkOtroMedico == false) {
                                if (this.medico_familia != null) {
                                    medicoSelected = this.medico_familia
                                }
                            } else if (this.medico_selected != null) {
                                medicoSelected = this.medico_selected.split('-')[0]
                            }
                            if (medico === medicoSelected) {
                                dia = val;
                                if (agenda.citas[0].Hora_Inicio.substring(0, 10) === dia) many = true;
                            }
                        }
                    }
                })
                if (dia) {
                    if (many) return ['green lighten-1', 'red']
                    else return true
                }
                return false
            },
            openConfirmar(data) {
                this.showdatos = true
                this.datos_cita = {
                    id: data.id,
                    fecha_selected: moment(this.fecha).format('dddd, D MMMM, YYYY'),
                    hora_inicio: data.hora_inicio,
                    fecha_solicitada: this.fecha,
                    medico: data.medico,
                    paciente_id: this.paciente.id
                }
            },
            confirmarcita() {
                this.preload_cita = true
                axios.put(`/api/redvital/asignarcita/${this.datos_cita.id}`, this.datos_cita)
                    .then((res) => {
                        this.preload_cita = false
                        this.showdatos = false
                        this.fecha = ''
                        this.checkOtroMedico = false
                        this.medico_selected = null
                        this.actividad_selected = null
                        this.$alerSuccess(res.data.message)
                    }).catch((err) => {
                        this.preload_cita = false
                        this.showdatos = false
                        this.fecha = ''
                        this.$alerError(err.response.data.message)
                    })
            },
            updatedatos() {
                if (this.checkbox == false) {
                    this.$alerError("Debe aceptar el uso de sus datos personales según Ley 1581 de 2012!")
                    return
                }
                if (this.paciente.Correo1) {
                    var regex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
                    if (!regex.test(this.paciente.Correo1)) {
                        this.$alerError("Debe ingresar un correo valido")
                        return;
                    }
                }
                this.preload_cita = true
                axios.put(`/api/redvital/updatepaciente/${this.paciente.id}`, this.paciente)
                    .then((res1) => {
                        axios.get(`/api/redvital/datapaciente/${this.paciente.id}`).then((res) => {
                            this.datosPaciente = res.data
                            if (this.datosPaciente.Correo1 == null |
                                this.datosPaciente.Celular1 == null |
                                this.datosPaciente.Direccion_Residencia == null) {
                                this.$alerError("No actualizo los datos obligatorios!")
                                this.preload_cita = false
                                this.validation = false;
                                return;
                            } else {
                                this.checkboxCorreo = false
                                this.checkboxCelular = false
                                this.checkboxDireccion = false
                                this.checkbox = false
                                this.preload_cita = false
                                this.$alerSuccess(res1.data.message)
                                this.validation = true;
                            }
                        })
                    }).catch((err) => {
                        this.preload_cita = false
                        this.$alerError(err.response.data.message)
                    })
            },
            dataPaciente(msg) {
                if (msg == 'validar') {
                    if (this.checkboxCorreo == false |
                        this.checkboxCelular == false |
                        this.checkboxDireccion == false) {
                        this.$alerError("Debe confirmar que sus datos sean correctos y esten actualizados")
                        return;
                    } else {
                        if (this.paciente.Correo1 == null |
                            this.paciente.Celular1 == null |
                            this.paciente.Direccion_Residencia == null) {
                            this.validation = false;
                            this.$alerError("No ha llenado los datos obligatorios!")
                            return;
                        } else {
                            this.fetchActividades()
                            this.updatedatos();
                        }
                    }
                }
            },

            imprimirRecordatorio(cita){
                const pdf = {
                    type: 'cita',
                    id: cita,
                    paciente_id: this.paciente.id
                }
                axios
                    .post("/api/redvital/print-recordatorio", pdf, {
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
            }

        }
    }

</script>
