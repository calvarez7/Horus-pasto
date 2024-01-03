<template>
    <v-layout row justify-center>
        <v-flex xs12>
            <v-card>
                <v-container>
                    <v-layout row wrap>
                        <v-flex xs12 md12 lg12>
                            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                            </v-text-field>
                        </v-flex>
                    </v-layout>
                </v-container>
                <v-data-table :search="search" :headers="headers" :items="listaSolicitudes">
                    <template v-slot:items="props">
                        <td class="text-xs-left">{{ props.item.id }}</td>
                        <td class="text-xs-left">{{ props.item.paciente }}</td>
                        <td class="text-xs-left">{{ props.item.Num_Doc }}</td>
                        <td class="text-xs-left">{{ props.item.fecharegistro }}</td>
                        <td class="text-xs-left">{{ props.item.IPS }}</td>
                        <td>
                            <v-chip color="red" text-color="white">{{ props.item.estado }}</v-chip>
                        </td>
                        <td>
                            <v-chip text-color="black">{{ props.item.destino_paciente }}</v-chip>
                        </td>
                        <td class="text-xs-left">{{ props.item.name }} {{ props.item.apellido }}</td>
                        <td class="text-xs-left">
                            <v-btn round color="success" @click="imprimir(props.item.id,'ficha')">Ficha</v-btn>
                        </td>
                        <td class="text-xs-left">
                            <v-btn round color="success" @click="imprimir(props.item.id,'historia')">Historia</v-btn>
                        </td>

                        <td class="text-xs-left">
                            <v-btn round small color="warning" @click="cargarDetalles(props.item.id)">Generar admisión
                            </v-btn>
                        </td>

                    </template>
                </v-data-table>
                <v-dialog v-model="dialogDetalle" persistent max-width="1600px">
                    <v-card>
                        <v-card-title class="headline success" style="color:white">
                            <span class="title layout justify-center font-weight-light">Admisión de pacientes</span>
                        </v-card-title>
                        <v-card-text>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <form @submit.prevent="guardarAdmision">
                                        <v-container>
                                            <v-layout row wrap>
                                                <v-flex xs12 sm4 d-flex>
                                                    <v-select :items="tvivienda" label="Tipo de vivienda"
                                                        v-model="formSeguimiento.tipo_vivienda"></v-select>
                                                </v-flex>
                                                <v-flex xs12 sm4 d-flex
                                                    v-if="formSeguimiento.tipo_vivienda == 'OTRO'">
                                                    <v-text-field label="Cual tipo de vivienda"
                                                        v-model="formSeguimiento.otravivienda"></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm4 d-flex>
                                                    <v-select :items="thabitacion" label="Tipo de habitación"
                                                        v-model="formSeguimiento.tipo_habitacion">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 sm4 d-flex>
                                                    <v-select :items="tcontacto" label="Tipo de contacto "
                                                        v-model="formSeguimiento.tipo_contacto"></v-select>
                                                </v-flex>
                                                <v-flex xs12 sm2 d-flex>
                                                    <v-select :items="reportacontactos"
                                                        v-model="formSeguimiento.reportar_contactos"
                                                        label="Va a reportar contactos">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 sm4 d-flex
                                                    v-if="formSeguimiento.reportar_contactos == 'SI'">
                                                    <v-text-field label="Nombre del Contacto"
                                                        v-model="formSeguimiento.nombre_cuidador"></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm4 d-flex
                                                    v-if="formSeguimiento.reportar_contactos == 'SI'">
                                                    <v-text-field label="Documento de Identidad"
                                                        v-model="formSeguimiento.documento_cuidador"></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm4 d-flex
                                                    v-if="formSeguimiento.reportar_contactos == 'SI'">
                                                    <v-text-field label="Aseguradora del Contacto"
                                                        v-model="formSeguimiento.aseguradora_cuidador"></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm4 d-flex
                                                    v-if="formSeguimiento.reportar_contactos == 'SI'">
                                                    <v-autocomplete v-model="formSeguimiento.municipio_cuidador_id"
                                                        append-icon="search" :items="municipios" item-text="municipio"
                                                        item-value="id" label="Municipio del Contacto">
                                                    </v-autocomplete>
                                                </v-flex>
                                                <v-flex xs12 sm4 d-flex
                                                    v-if="formSeguimiento.reportar_contactos == 'SI'">
                                                    <v-text-field label="Dirección del Contacto"
                                                        v-model="formSeguimiento.direccion_cuidador"></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm4 d-flex
                                                    v-if="formSeguimiento.reportar_contactos == 'SI'">
                                                    <v-text-field label="Teléfono del Contacto"
                                                        v-model="formSeguimiento.telefono_cuidador"></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm4 d-flex
                                                    v-if="formSeguimiento.reportar_contactos == 'SI'">
                                                    <v-text-field label="Correo Electrónico"
                                                        v-model="formSeguimiento.correo_cuidador"></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm4 d-flex
                                                    v-if="formSeguimiento.reportar_contactos == 'SI'">
                                                    <v-select :items="parentescos"
                                                        v-model="formSeguimiento.parentesco_cuidador"
                                                        label="Parentesco del Contacto">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 sm4 d-flex>
                                                    <v-select :items="rcontactos"
                                                        v-model="formSeguimiento.cumplir_aislamiento"
                                                        label="La persona aislada y el grupo familiar se comprometen a cumplir con el aislamiento">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 sm4 d-flex
                                                    v-if="formSeguimiento.reportar_contactos == 'SI'">
                                                    <v-select :items="rcontactos" v-model="formSeguimiento.contactado"
                                                        label="Contactado">
                                                    </v-select>
                                                </v-flex>
                                                <!-- campo nuevo -->
                                                <v-flex xs12 sm4 d-flex
                                                    v-if="formSeguimiento.reportar_contactos == 'SI'">
                                                    <v-select
                                                        :items="rcontactos"
                                                        v-model="formSeguimiento.presupuestocomun"
                                                        label="PRESUPUESTO COMÚN">
                                                    </v-select>
                                                </v-flex>
                                                <!-- campo nuevo -->
                                                <v-flex xs12 sm4 d-flex>
                                                    <v-select :items="rcontactos"
                                                        v-model="formSeguimiento.vacunado_influenza"
                                                        label="Se ha vacunado contra la Influenza el último año">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 sm4 d-flex>
                                                    <v-select :items="rcontactos"
                                                        v-model="formSeguimiento.utilizado_antibioticos"
                                                        label="Ha utilizado antibióticos la última semana">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 sm4 d-flex>
                                                    <v-text-field label="Fecha de Inicio del aislamiento"
                                                        v-model="formSeguimiento.fecha_inicio_aislamiento" type="date">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm4 d-flex>
                                                    <v-select :items="rcontactos" v-model="discapacidad"
                                                        label="Presenta discapacidad">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 sm4 d-flex v-if="discapacidad == 'SI'">
                                                    <v-select :items="discapacidades"
                                                        v-model="formSeguimiento.presenta_discapacidades"
                                                        label="Presenta discapacidad">
                                                    </v-select>
                                                </v-flex>
                                                <!-- campo nuevo -->
                                                <v-flex xs12 sm4 d-flex>
                                                    <v-select :items="motivoaislamiento"
                                                        v-model="formSeguimiento.motivoaislamiento"
                                                        label="Motivo De Aislamiento">
                                                    </v-select>
                                                </v-flex>
                                                <!-- campo nuevo -->
                                                <v-flex xs12 sm4 d-flex>
                                                    <v-select :items="rcontactos"
                                                        v-model="formSeguimiento.presenta_impedimento_aislamiento_domi"
                                                        label="El paciente presenta alguna condición que impida el aislamiento domiciliario">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 sm4 d-flex>
                                                    <v-select :items="resolucion521"
                                                        v-model="formSeguimiento.clasificacion_resolucion_521"
                                                        label="Clasificación del paciente según Resolución 521">
                                                    </v-select>
                                                </v-flex>

                                                <v-flex xs12 sm2 md2>
                                                    <v-select :items="destinopaciente" label="Destino del paciente"
                                                        v-model="formSeguimiento.destino_paciente">
                                                    </v-select>
                                                </v-flex>

                                                <v-flex xs12 sm4 d-flex>
                                                    <v-select :items="asignados"
                                                        v-model="formSeguimiento.usuario_seguimiento_id" item-value="id"
                                                        :item-text="asignados => asignados.nombre+' ('+asignados.cantidad+')'"
                                                        label="Medico Responsable">
                                                    </v-select>
                                                </v-flex>

                                                <v-flex xs12 sm4 md4>
                                                    <v-card-actions>
                                                        <v-spacer></v-spacer>
                                                        <v-btn color="red" dark @click="dialogDetalle = false">Cerrar
                                                        </v-btn>
                                                        <v-btn color="success" dark type="submit">Guardar</v-btn>
                                                    </v-card-actions>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </form>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-dialog>
            </v-card>
        </v-flex>
    </v-layout>
</template>
<script>
    import {
        mapGetters
    } from "vuex";
    export default {
        name: "segumientoCovid",
        data() {
            return {
                motivoaislamiento: ['Casos Sospechoso','Viajero Internacional De Cualquier Procedencia','Contacto De Caso Confirmado','Caso Confirmado','No Aplica'],
                destinopaciente: ['Seguimiento','Oximetrías','Hospitalización','Hospitalización UCI/UCE','Alta descartado', 'Alta recuperado', 'Alta fallecido'],
                asignados: [],
                resolucion521: [
                    'Grupo 1. Pacientes mayores de 70 años.',
                    'Grupo 2. Pacientes con patologías crónicas de base controladas y riesgo bajo.',
                    'Grupo 3. Pacientes con patologías de base no controlada o riesgo medio o alto y Gestantes.',
                    'Ninguna de las anteriores.'
                ],
                discapacidad: null,
                discapacidades: ['COGNITIVA', 'FISICA', 'SENSORIAL', 'MULTIPLES'],
                tvivienda: ['CASA', 'APARTAMENTO','INSTITUCION DE SALUD','FINCA','ALBERGUE','OTRO'],
                thabitacion: ['INDIVIDUAL', 'COMPARTIDA'],
                tcontacto: ['SOCIAL', 'FAMILIAR', 'DE VUELO', 'PERSONAL DE LA SALUD', 'DESCONOCIDO', 'SALA DE ESPERA'],
                tipomuestra: ['PCR', 'Antígeno', 'Serológicas'],
                reportacontactos: ['SI','NO LOS CONOCE','LOS CONOCE PERO NO TIENE LOS DATOS DE CONTACTO','NO QUISO RELACIONAR CONTACTOS','YA RELACIONO CONTACTOS'],
                rcontactos: ['SI', 'NO'],
                parentescos: ['CONYUGE O COMPAÑERO(A) PERMANENTE', 'HIJO(A)', 'PADRE O MADRE',
                    'SEGUNDO GRADO DE CONSANGUINIDAD', 'TERCER GRADO DE CONSANGUINIDAD',
                    'MENOS DE 12 AÑOS SIN CONSANGUINIDAD', 'PADRE O MADRE DEL CONYUGE', 'OTROS NO PARIENTES',
                    'AMIGO O CONOCIDO', 'VECINO', 'SIN RELACION'
                ],
                search: "",
                dialogDetalle: false,
                listaSolicitudes: [],
                menuFechaRealizacion: false,
                menuFechaResultado: false,
                menuFechaIngreso: false,
                menuFechaSalida: false,
                menuFechaInicioSintomas: false,
                idSolicitud: null,
                municipios: [],
                poblacion_riesgo: [],
                formSeguimiento: {
                    cita_paciente: null,
                    tipo_vivienda: null,
                    tipo_habitacion: null,
                    tipo_contacto: null,
                    reportar_contactos: null,
                    nombre_cuidador: null,
                    documento_cuidador: null,
                    aseguradora_cuidador: null,
                    municipio_cuidador_id: null,
                    direccion_cuidador: null,
                    telefono_cuidador: null,
                    correo_cuidador: null,
                    parentesco_cuidador: null,
                    cumplir_aislamiento: null,
                    contactado: null,
                    vacunado_influenza: null,
                    utilizado_antibioticos: null,
                    fecha_inicio_aislamiento: null,
                    presenta_discapacidades: null,
                    presenta_impedimento_aislamiento_domi: null,
                    clasificacion_resolucion_521: null,
                    user_asignado_id: null,
                    usuario_seguimiento_id: null,
                    destino_paciente: null,
                    seguimiento_atencion_contingencia_id: null,
                    motivoaislamiento: null,
                    otravivienda: null,
                    presupuestocomun: null,
                },
                headers: [{
                        text: "# Solicitud",
                        value: "id"
                    },
                    {
                        text: "Paciente",
                        value: "paciente"
                    },
                    {
                        text: "Cédula",
                        value: "Num_Doc"
                    },
                    {
                        text: "Fecha Creacion",
                        value: "Fecha"
                    },
                    {
                        text: "IPS",
                        value: "IPS"
                    },
                    {
                        text: "Estado",
                        value: "estado"
                    },
                    {
                        text: "Destino Paciente"
                    },
                    {
                        text: "Usuario Crea",
                        value: "user_crea"
                    },
                    {
                        text: "Ficha",
                        value: ""
                    }, {
                        text: "Historia",
                        value: ""
                    }, {
                        text: "Generar Admisión",
                        value: ""
                    }
                ],
            }
        },
        computed: {
            ...mapGetters(['can']),
        },
        methods: {
            listarSolicitudes() {
                axios.get('/api/covid/admision').then(res => {
                    this.listaSolicitudes = res.data;
                })
            },
            cargarDetalles(id) {
                this.formSeguimiento.seguimiento_atencion_contingencia_id = id;
                this.dialogDetalle = true;
            },
            fetchMunicipios() {
                axios.get('/api/municipio/lista')
                    .then(res => {
                        this.municipios = res.data
                    })
            },
            guardarAdmision() {
                if (this.formSeguimiento.destino_paciente == null) {
                    this.$alerError('Seleccione el destino del paciente.')
                    return;
                }
                axios.post('/api/covid/registroadmision', this.formSeguimiento).then(res => {
                    this.clearData();
                    this.$alerSuccess(res.data.message);
                    this.listarSolicitudes();
                })
            },
            clearData() {
                this.poblacion_riesgo = [];
                for (const prop of Object.getOwnPropertyNames(this.formSeguimiento)) {
                    this.formSeguimiento[prop] = null;
                }
                this.dialogDetalle = false;
            },
            async imprimir(id, tipo) {
                const pdf = {
                    type: 'covid',
                    id: id,
                    tipo: tipo
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
            },
            getAsignados() {
                axios.get('/api/covid/asignados').then(res => {
                    this.asignados = res.data;
                    console.log(this.asignados);
                })
            }
        },
        mounted() {
            this.listarSolicitudes();
            this.fetchMunicipios();
            this.getAsignados();
        }
    }

</script>
