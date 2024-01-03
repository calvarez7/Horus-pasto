<template>
    <v-layout row wrap>
        <!-- Dialogo de actualizacion de datos del paciente -->
        <v-dialog v-model="datospaci" persistent max-width="600px">
            <v-card>
                <v-card-title class="headline">
                    <v-card-text style="color: #62a89f!important; border-bottom: dotted">
                        <template v-if="isImgRadium">
                            <table class="tg">
                                <tr>
                                    <th class="tg-0pky">
                                        <p style="text-align:center">Actualizacion Datos del Paciente</p>
                                    </th>
                                    <th class="tg-0pky"></th>
                                    <th class="tg-0pky" v-if="this.can('imagenologia')">
                                        <img src="/storage/images/logoR.png" style="width:150px">
                                    </th>
                                </tr>
                            </table>
                        </template>
                        <template v-else-if="isImgOncologia">
                            <v-card-text style="color: #3f2b73 !important">
                                <table class="tg">
                                    <tr>
                                        <th class="tg-0pky">
                                            <p style="text-align:center">Actualizacion Datos del Paciente</p>
                                        </th>
                                        <th class="tg-0pky"></th>
                                        <th class="tg-0pky">
                                            <img src="/storage/images/logoVictoriana.png" style="width:150px">
                                        </th>
                                    </tr>
                                </table>
                            </v-card-text>
                        </template>
                        <template v-else>
                            <v-card-text style="color: #0f4599 !important">
                                <table class="tg">
                                    <tr>
                                        <th class="tg-0pky">
                                            <p style="text-align:center">Actualizacion Datos del Paciente</p>
                                        </th>
                                        <th class="tg-0pky"></th>
                                        <th class="tg-0pky">
                                            <img src="/storage/images/Logo_sumimedical.png" style="width:120px">
                                        </th>
                                    </tr>
                                </table>
                            </v-card-text>
                        </template>
                    </v-card-text>
                </v-card-title>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-flex xs12 sm4 md4 v-for="input in inputs" :key="input.id">
                            <v-select :label="input.label" v-model="paciente[input.value]" :items="input.options"
                                v-if="input.type == 'select'">
                            </v-select>
                            <v-text-field :label="input.label" v-model="paciente[input.value]" v-else>
                            </v-text-field>
                        </v-flex>
                    </v-layout>
                </v-container>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="updateDatapaci()">Actualizar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- Fin del dialogp -->
        <!-- <v-flex xs12 v-if="cuerpoHistoria">
            <v-card style="display:fixed">
                <v-stepper v-model="e1">
                    <v-stepper-header>
                        <template v-for="item in modulos">
                            <v-stepper-step @click="cargarCuerpoHC(item.path,item.id)" :key="`${item.id}-step`"
                                :step="item.id" :complete="e1 > item.id" editable>
                                {{ item.text }}
                            </v-stepper-step>
                            <v-divider v-if="item.id !== steps" :key="item.id"></v-divider>
                        </template>
                    </v-stepper-header>
                </v-stepper>
            </v-card>
        </v-flex><br><br><br><br> -->
        <v-flex xs12 pl-1 v-if="cuerpoHistoria">
            <v-card>
                <RouterView />
            </v-card>
        </v-flex>
        <v-dialog v-model="dialog" max-width="1000px">
            <v-card>
                <v-toolbar flat color="primary" dark>
                    <v-toolbar-title>Historia Clinica</v-toolbar-title>
                </v-toolbar>
                <v-tabs vertical>
                    <v-tab>
                        <v-icon left>mdi-account</v-icon>Información del paciente
                    </v-tab>
                    <v-tab>
                        <v-icon left>list_alt</v-icon>Diagnostico
                    </v-tab>

                    <v-tab-item>
                        <v-flex>
                            <v-card flat>
                                <v-card-title class="headline grey lighten-2">Datos del paciente</v-card-title>
                                <v-container>
                                    <v-layout wrap align-center>
                                        <v-flex xs6 md3 lg4>
                                            <v-text-field v-model="history.nombre" label="Nombre" outlined readonly>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.cedula" label="Cédula" outlined readonly>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.ips" label="IPS" outlined readonly>
                                            </v-text-field>
                                        </v-flex>
                                        <!-- <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.edad" label="edad" outlined readonly>
                                            </v-text-field>
                                        </v-flex> -->
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.sexo" label="sexo" outlined readonly>
                                            </v-text-field>
                                        </v-flex>
                                    </v-layout>
                                    <v-layout wrap align-center>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.tipoafiliado" label="Tipo Afiliado" outlined
                                                readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.departamento" label="Departamento" outlined
                                                readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.municipioafiliado" label="Municipio Afiliado"
                                                outlined readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg4>
                                            <v-text-field v-model="history.direccion" label="Direccion Residencia"
                                                outlined readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.telefono" label="Telefono" outlined readonly>
                                            </v-text-field>
                                        </v-flex>
                                    </v-layout>
                                    <v-layout wrap align-center>
                                        <v-flex xs6 md3 lg3>
                                            <v-text-field v-model="history.celular" label="Celular" outlined readonly>
                                            </v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.fechasolicita" label="Fecha Solicita"
                                                outlined readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg3>
                                            <v-text-field v-model="history.motivoconsulta" label="Motivoconsulta"
                                                outlined readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg4>
                                            <v-text-field v-model="history.enfermedadactual" label="Enfermedad Actual"
                                                outlined readonly></v-text-field>
                                        </v-flex>
                                    </v-layout>
                                    <v-layout wrap align-center>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.resultayudadiagnostica"
                                                label="Resulta Ayuda Diagnostica" outlined readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg3>
                                            <v-text-field v-model="history.oftalmologico" label="Oftalmologico" outlined
                                                readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.genitourinario" label="Genitourinario"
                                                outlined readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg3>
                                            <v-text-field v-model="history.otorrinoralingologico"
                                                label="Otorrinoralingologico" outlined readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.linfatico" label="Linfatico" outlined
                                                readonly></v-text-field>
                                        </v-flex>
                                    </v-layout>
                                    <v-layout wrap align-center>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.osteomioarticular" label="Osteomio Articular"
                                                outlined readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg3>
                                            <v-text-field v-model="history.neurologico" label="Oftalmologico" outlined
                                                readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.cardiovascular" label="Cardiovascular"
                                                outlined readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg3>
                                            <v-text-field v-model="history.tegumentario" label="Tegumentario" outlined
                                                readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.respiratorio" label="Respiratorio" outlined
                                                readonly></v-text-field>
                                        </v-flex>
                                    </v-layout>
                                    <v-layout wrap align-center>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.endocrinologico" label="Endocrinologico"
                                                outlined readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg3>
                                            <v-text-field v-model="history.gastrointestinal" label="Gastrointestinal"
                                                outlined readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.norefiere" label="No Refiere" outlined
                                                readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg3>
                                            <v-text-field v-model="history.timeconsulta" label="Time Consulta" outlined
                                                readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.medicoordeno" label="Medico Ordeno" outlined
                                                readonly></v-text-field>
                                        </v-flex>
                                    </v-layout>
                                    <v-layout wrap align-center>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.entidademite" label="Entidad Emite" outlined
                                                readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg3>
                                            <v-text-field v-model="history.finalidad" label="Finalidad" outlined
                                                readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.observaciones" label="Observaciones" outlined
                                                readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg3>
                                            <v-text-field v-model="history.datetimeingreso" label="DateTime Ingreso"
                                                outlined readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.datetimesalida" label="DateTime Salida"
                                                outlined readonly></v-text-field>
                                        </v-flex>
                                    </v-layout>
                                    <v-layout wrap align-center>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.antropometricas" label="Antropometricas"
                                                outlined readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg3>
                                            <v-text-field v-model="history.signosvitales" label="Signos Vitales"
                                                outlined readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.otrossignosvitales"
                                                label="Otros Signos Vitales" outlined readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg3>
                                            <v-text-field v-model="history.presionarterial" label="Presión Arterial"
                                                outlined readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.condiciongeneral" label="Condición General"
                                                outlined readonly></v-text-field>
                                        </v-flex>
                                    </v-layout>
                                    <v-layout wrap align-center>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.antropometricas" label="Antropometricas"
                                                outlined readonly></v-text-field>
                                        </v-flex>
                                    </v-layout>
                                    <!-- asd  -->
                                    <v-layout wrap align-center>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.tipoorden" label="Tipo Orden" outlined
                                                readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg3>
                                            <v-text-field v-model="history.osteomuscular" label="Osteomuscular" outlined
                                                readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.tipodiagnostico" label="Tipo Diagnostico"
                                                outlined readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg3>
                                            <v-text-field v-model="history.planmanejo" label="Plan Manejo" outlined
                                                readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.diagnosticoprincipal"
                                                label="Diagnostico Principal" outlined readonly></v-text-field>
                                        </v-flex>
                                    </v-layout>
                                    <v-layout wrap align-center>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.recomendaciones" label="Recomendaciones"
                                                outlined readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg3>
                                            <v-text-field v-model="history.destinopaciente" label="Destino Paciente"
                                                outlined readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.finalidad" label="Finalidad" outlined
                                                readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg3>
                                            <v-text-field v-model="history.atendidopor" label="Atendido Por" outlined
                                                readonly></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 md3 lg2>
                                            <v-text-field v-model="history.cups" label="Cups" outlined readonly>
                                            </v-text-field>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card>
                        </v-flex>
                    </v-tab-item>

                    <v-tab-item>
                        <v-card flat>
                            <v-card-title class="headline grey lighten-2">Diagnosticos</v-card-title>
                            <v-data-table class="fb-table-elem" :headers="diagnosticHeaders" :items="Diagnosticos"
                                item-key="id" :items-per-page="5" hide-actions expand>
                                <template v-slot:items="Diagnostico">
                                    <tr @click="Diagnostico.expanded = !Diagnostico.expanded">
                                        <td class="text-xs-center">
                                            <div class="datatable-cell-wrapper">
                                                <v-checkbox disabled color="primary"
                                                    v-model="Diagnostico.item.Esprimario" hide-details
                                                    :value="Diagnostico.item.Esprimario"></v-checkbox>
                                            </div>
                                        </td>
                                        <td class="text-xs-center">
                                            <div class="datatable-cell-wrapper">{{ Diagnostico.item.Cie10_id }}</div>
                                        </td>
                                        <td class="text-xs-center">
                                            <div class="datatable-cell-wrapper">{{ Diagnostico.item.Tipodiagnostico }}
                                            </div>
                                        </td>
                                        <td class="text-xs-center">
                                            <div class="datatable-cell-wrapper">
                                                <v-select :items="Diagnostico.item.Marcapaciente"
                                                    label="Marcar paciente" v-model="Diagnostico.item.Marcapaciente"
                                                    attach multiple chips></v-select>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <v-alert v-slot:no-results :value="true" color="error" icon="warning">Your search for
                                    "{{ search }}" found no results.</v-alert>
                                <template slot="expand" slot-scope="Diagnostico">
                                    <v-card flat>
                                        <v-card-text>
                                            <pre>{{Diagnostico}}</pre>
                                        </v-card-text>
                                        <div class="datatable-container">
                                            <v-card-text>
                                                <pre>{{Diagnostico}}</pre>
                                            </v-card-text>
                                        </div>
                                    </v-card>
                                </template>
                            </v-data-table>
                        </v-card>
                    </v-tab-item>
                </v-tabs>
            </v-card>
            <v-card>
                <v-layout row wrap>
                    <v-flex xs6 md3 lg3>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" flat @click="cerrarModal()">Cerrar</v-btn>
                        </v-card-actions>
                    </v-flex>
                    <v-flex xs6 md3 lg3>
                        <v-tooltip top>
                            <template v-slot:activator="{ on }">
                                <v-btn text icon color="blue" dark v-on="on">
                                    <v-icon @click="printhc()">assignment_turned_in</v-icon>
                                </v-btn>
                            </template>
                            <span>Historial</span>
                        </v-tooltip>
                    </v-flex>
                </v-layout>
            </v-card>
        </v-dialog>
        <template>
            <div class="text-center">
                <v-dialog v-model="preload" persistent width="300">
                    <v-card color="primary" dark>
                        <v-card-text>
                            Tranquilo procesamos tu solicitud !
                            <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                        </v-card-text>
                    </v-card>
                </v-dialog>
            </div>
        </template>
    </v-layout>
</template>
<script>
    import Swal from 'sweetalert2';
    import {
        mapGetters
    } from 'vuex';
    import {
        EventBus
    } from '../../../event-bus.js';
    import {
        constants
    } from 'crypto';

    export default {
        created() {
            this.cita_paciente = this.$route.query.cita_paciente;
            this.getPaciente();
            this.fetchAntecedentespersonales();
            this.historia();
            EventBus.$on('imagenologia', isImagenologiaBool => {
                let imgagenologia = isImagenologiaBool;
                if (imgagenologia == true) {
                    this.layoutHistorial = false
                }
            });
            EventBus.$on("step-historia", event => {
                this.e1 = event
            });
            this.getOcupacional(this.$route.query.cita_paciente);
        },
        data() {
            return {
                psicologiaOcupacional: '',
                pagination: {
                    sortBy: 'name'
                },
                isImagenologiaBool: false,
                isOncologiaBool: false,
                isEnfermeriaBool: false,
                resultados: false,
                selected: [],
                search: '',
                isMobile: false,
                e1: 1,
                steps: 1,
                dialog: false,
                datospaci: false,
                historiapaciente: [],
                labmedicos: [],
                resuLab: [],
                history: {},
                modulos: [{
                    id: 1,
                    text: ""
                }],
                cita_paciente: 0,
                paciente: {
                    Celular1: '',
                    Correo1: '',
                    Dane_Dpto: '',
                    Dane_Mpio: '',
                    Departamento: '',
                    Direccion_Residencia: '',
                    Discapacidad: '',
                    Doc_Cotizante: '',
                    Dpto_Atencion: '',
                    Edad_Cumplida: '',
                    Estado_Afiliado: '',
                    Estrato: '',
                    Etnia: '',
                    Fecha_Afiliado: '',
                    Fecha_Emision: '',
                    Fecha_Naci: '',
                    Grado_Discapacidad: '',
                    IPS: '',
                    Laboraen: '',
                    Medicofamilia: '',
                    Mpio_Afiliado: '',
                    Mpio_Atencion: '',
                    Mpio_Labora: '',
                    Mpio_Residencia: '',
                    Nivel_Sisben: '',
                    Num_Doc: '',
                    Num_Folio: '',
                    Num_Hijos: '',
                    Orden_Judicial: '',
                    Parentesco: '',
                    Primer_Ape: '',
                    Primer_Nom: '',
                    RH: '',
                    Region: '',
                    Sede_Odontologica: '',
                    SegundoNom: '',
                    Segundo_Ape: '',
                    Sexo: '',
                    Sexo_Biologico: '',
                    Subregion: '',
                    Telefono: '',
                    Tienetutela: '',
                    TipoDoc_Cotizante: '',
                    Tipo_Afiliado: '',
                    Tipo_Cotizante: '',
                    Tipo_Doc: '',
                    Tipo_Regimen: '',
                    Ut: '',
                    Vivecon: '',
                    RH: '',
                    Tienetutela: '',
                    Nivelestudio: ''
                },
                diagnosticHeaders: [{
                        text: "Marcar Principal",
                        align: "center",
                        value: "marcar"
                    },
                    {
                        text: "Diagnóstico",
                        align: "center",
                        value: "diagnostico"
                    },
                    {
                        text: "Tipo Diagnóstico",
                        align: "center",
                        value: "tipo_diagnostico"
                    },
                    {
                        text: "Marca",
                        align: "center",
                        value: "marca"
                    },
                    {
                        text: "",
                        align: "center",
                        value: ""
                    }
                ],
                Diagnosticos: [],
                inputs: [
                    {
                        label: 'Etnia',
                        value: 'Etnia',
                        type: 'select',
                        options: [
                            'Indígena',
                            'Gitano',
                            'Raizal',
                            'Palenquero',
                            'Negro(a)',
                            'Mulato(a)',
                            'Afrocolombiano(a)',
                            'Afro descendiente'
                        ]
                    },
                    {
                        label: 'Donde labora',
                        value: 'Laboraen'
                    },
                    {
                        label: 'Mpio Labora',
                        value: 'Mpio_Labora'
                    },
                    {
                        label: 'Direccion Residencia',
                        value: 'Direccion_Residencia'
                    },
                    {
                        label: 'Mpio Residencia',
                        value: 'Mpio_Residencia'
                    },
                    {
                        label: 'Telefono',
                        value: 'Telefono'
                    },
                    {
                        label: 'Correo1',
                        value: 'Correo1'
                    },
                    {
                        label: 'Estrato',
                        value: 'Estrato',
                        type: 'select',
                        options: [
                            '1',
                            '2',
                            '3',
                            '4',
                            '5',
                            '6',
                            '7',
                            '8'
                        ]

                    },
                    {
                        label: 'Número de Hijos',
                        value: 'Num_Hijos'
                    },
                    {
                        label: 'Vivecon',
                        value: 'Vivecon'
                    },
                    {
                        label: 'RH',
                        value: 'RH',
                        type: 'select',
                        options: [
                            'O+',
                            'O-',
                            'A+',
                            'A-',
                            'B+',
                            'B-',
                            'AB+',
                            'AB-'
                        ]
                    },
                    {
                        label: 'Nivelestudio',
                        value: 'Nivelestudio'
                    },
                ],
                antecedentesRCVM: ['Hipertension Arterial', 'Dislipidemia', 'Diabetes Mellitus',
                    'Enfermedad Cardiovascular'
                ],
                headers: [{
                        text: 'Fecha Resultado',
                        align: 'left',
                        sortable: false,
                        value: 'name'
                    },
                    {
                        text: 'Nombre Examen',
                        value: 'calories'
                    },
                    {
                        text: 'Ver Resultado',
                        value: 'fat'
                    }
                ],
                isImgRadium: false,
                isImgOncologia: false,
                layoutHistorial: true,
                isOftalmologiaBool: false,
                isPsicologiaBool: false,
                isVozBool: false,
                isVisiometriaBool: false,
                isMedicoOcupacional: false,
                cuerpoHistoria: false,
                nav: false,
                preload: false,
            }
        },

        computed: {
            ...mapGetters(['fullName', 'can', 'avatar'])
        },

        methods: {

            onResize() {
                if (window.innerWidth < 769)
                    this.isMobile = true;
                else
                    this.isMobile = false;
            },
            toggleAll() {
                if (this.selected.length) this.selected = []
                else this.selected = this.desserts.slice()
            },
            changeSort(column) {
                if (this.pagination.sortBy === column) {
                    this.pagination.descending = !this.pagination.descending
                } else {
                    this.pagination.sortBy = column
                    this.pagination.descending = false
                }
            },

            laboratorios() {

                this.cedula_paciente = localStorage.getItem("PacienteCedula");

                axios
                    .post("/api/historiapaciente/getlaboratorios", {
                        Num_Doc: this.cedula_paciente
                    })
                    .then(res => {
                        this.labmedicos = res.data;
                    });
            },

            resultadoLaboratorio(num_peticion) {

                const Orden = {
                    Num_orden: num_peticion
                }

                axios
                    .post("/api/historiapaciente/getResultadolaboratorios", Orden)
                    .then(res => {
                        this.resuLab = res.data;
                        this.resultados = true;
                    });
            },
            resumenhistoria() {

                this.cedula_paciente = localStorage.getItem("PacienteCedula");

                axios
                    .post("/api/historiapaciente/gethistoria", {
                        Num_Doc: this.cedula_paciente
                    })
                    .then(res => {
                        this.historiapaciente = res.data;
                    });
            },
            abrirModal(historia) {
                this.historia = historia;
                this.fillHistory();
                this.dialog = true;
            },
            cerrarModal() {
                this.dialog = false;
                this.observaciones = "";
            },
            fillHistory() {
                if (this.historia.id) {
                    this.history = this.getHistoryObj(this.historia);
                }
            },
            fillDiagnostic(autorizacion) {
                axios
                    .get("/api/cie10/diagnostico/" + autorizacion.citaPaciente_id)
                    .then(res => {
                        this.Diagnostico = res.data;
                    });
            },
            printhc() {
                if (this.historia) {
                    let pdf = this.getPDFHistorial();
                    pdf.type = "test";
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
                }
            },
            getPDFHistorial() {
                return (this.pdf = {
                    type: "test",
                    Abdomen: this.historia.Abdomen,
                    Actividadfisica: this.historia.Actividadfisica,
                    Agudezavisual: this.historia.Agudezavisual,
                    Altonivelestres: this.historia.Altonivelestres,
                    Antecedentes: this.historia.Antecedentes,
                    Antropometricas: this.historia.Antropometricas,
                    Atendido_Por: this.historia.Atendido_Por,
                    Cabezacuello: this.historia.Cabezacuello,
                    Cantidadlicor: this.historia.Cantidadlicor,
                    Cardiopulmonar: this.historia.Cardiopulmonar,
                    Cardiovascular: this.historia.Cardiovascular,
                    Cédula: this.historia.Cédula,
                    Cedulamedico: this.historia.Cedulamedico,
                    Condiciongeneral: this.historia.Condiciongeneral,
                    Consumopsicoactivo: this.historia.Consumopsicoactivo,
                    Datetimeingreso: this.historia.Datetimeingreso,
                    Datetimesalida: this.historia.Datetimesalida,
                    Departamento: this.historia.Departamento,
                    Destinopaciente: this.historia.Destinopaciente,
                    Diagnostico_Principal: this.historia.Diagnostico_Principal,
                    Diagnostico_Secundario: this.historia.Diagnostico_Secundario,
                    Dietasaludable: this.historia.Dietasaludable,
                    Direccion_Residencia: this.historia.Direccion_Residencia,
                    Duermemenoseishoras: this.historia.Duermemenoseishoras,
                    Duracion: this.historia.Duracion,
                    Edad: this.historia.Edad,
                    Edad_Cumplida: this.historia.Edad,
                    Endocrinologico: this.historia.Endocrinologico,
                    Enfermedadactual: this.historia.Enfermedadactual,
                    Entidademite: this.historia.Entidademite,
                    Especialidad: this.historia.Especialidad,
                    Estilovidaobservaciones: this.historia.Estilovidaobservaciones,
                    Examenmama: this.historia.Examenmama,
                    Examenmental: this.historia.Examenmental,
                    Extremidades: this.historia.Extremidades,
                    Fecha_Nacimiento: this.historia.Fecha_Nacimiento,
                    Fecha_solicita: this.historia.Fecha_solicita,
                    FechaInicio: this.historia.Fecha_solicita,
                    Finalidad: this.historia.Finalidad,
                    Firma: this.historia.Firma,
                    Frecucardiaca: this.historia.Frecucardiaca,
                    Frecuenciasemana: this.historia.Frecuenciasemana,
                    Frecurespiratoria: this.historia.Frecurespiratoria,
                    Fumacantidad: this.historia.Fumacantidad,
                    Fumadorpasivo: this.historia.Fumadorpasivo,
                    Fumainicio: this.historia.Fumainicio,
                    Gastrointestinal: this.historia.Gastrointestinal,
                    Genitourinario: this.historia.Genitourinario,
                    Genitourinario: this.historia.Genitourinario,
                    id: this.historia.id,
                    Laboraen: this.historia.Laboraen,
                    Licorfrecuencia: this.historia.Licorfrecuencia,
                    Linfatico: this.historia.Linfatico,
                    Medicoordeno: this.historia.Medicoordeno,
                    Motivoconsulta: this.historia.Motivoconsulta,
                    Municipio_Afiliado: this.historia.Municipio_Afiliado,
                    Neurologico: this.historia.Neurologico,
                    Nombre: this.historia.Nombre,
                    Nombre: this.historia.Nombre,
                    Norefiere: this.historia.Norefiere,
                    Observaciones: this.historia.Observaciones,
                    Oftalmologico: this.historia.Oftalmologico,
                    Ojosfondojos: this.historia.Ojosfondojos,
                    Osteomioarticular: this.historia.Osteomioarticular,
                    Osteomuscular: this.historia.Osteomuscular,
                    Osteomuscular: this.historia.Osteomuscular,
                    Otorrinoralingologico: this.historia.Otorrinoralingologico,
                    Otros_Signos_Vitales: this.historia.Otros_Signos_Vitales,
                    Pielfraneras: this.historia.Pielfraneras,
                    Planmanejo: this.historia.Planmanejo,
                    Presión_Arterial: this.historia.Presión_Arterial,
                    Psicoactivocantidad: this.historia.Psicoactivocantidad,
                    Pulsosperifericos: this.historia.Pulsosperifericos,
                    Recomendaciones: this.historia.Recomendaciones,
                    Reflejososteotendinos: this.historia.Reflejososteotendinos,
                    Registromedico: this.historia.Registromedico,
                    Respiratorio: this.historia.Respiratorio,
                    Resultayudadiagnostica: this.historia.Resultayudadiagnostica,
                    Sexo: this.historia.Sexo,
                    Indicacion: this.historia.Indicacion,
                    Hallazgos: this.historia.Hallazgos,
                    Conclusion: this.historia.Conclusion,
                    Notaclaratoria: this.historia.Notaclaratoria,
                    Tecnica: this.historia.Tecnica,
                    Cups: this.historia.Cups,
                    Radiologo: this.historia.Radiologo,
                    Firmaradiologo: this.historia.Firmaradiologo,
                    Tratamientoncologico: this.historia.tratamientoncologico,
                    Cirujiaoncologica: this.historia.cirujiaoncologica,
                    Flaboratoriopatologia: this.historia.flaboratoriopatologia,
                    Fdxcanceractual: this.historia.fdxcanceractual,
                    Ncirujias: this.historia.ncirujias,
                    Dukes: this.historia.Dukes,
                    Gleason: this.historia.gleason,
                    Her2: this.historia.Her2,
                    Tumorsegunbiopsia: this.historia.tumorsegunbiopsia,
                    Estadificacioninicial: this.historia.estadificacioninicial,
                    Fechaestadificacion: this.historia.fechaestadificacion,
                    Ips: this.historia.IPS,
                    Patologiacancelactual: this.historia.Patologiacancelactual,
                    Iniciocirujia: this.historia.iniciocirujia,
                    Fincirujia: this.historia.fincirujia,
                    Recibioradioterapia: this.historia.recibioradioterapia,
                    Inicioradioterapia: this.historia.inicioradioterapia,
                    Finradioterapia: this.historia.finradioterapia,
                    Nsesiones: this.historia.nsesiones,
                    Intencion: this.historia.intencion,

                    Signos_Vitales: this.historia.Signos_Vitales,
                    Suenoreparador: this.historia.Suenoreparador,
                    Tactoretal: this.historia.Tactoretal,
                    Tegumentario: this.historia.Tegumentario,
                    telefono: this.historia.Celular,
                    Telefono: this.historia.Telefono,
                    Timeconsulta: this.historia.Timeconsulta,
                    tipo_afiliado: this.historia.Tipo_Afiliado,
                    Tipo_Orden: this.historia.Tipo_Orden,
                    Tipodiagnostico: this.historia.Tipodiagnostico,

                    Fechaultimamenstruacion: this.Fechaultimamenstruacion,
                    Gestaciones: this.Gestaciones,
                    Partos: this.Partos,
                    Abortoprovocado: this.Abortoprovocado,
                    Abortoespontaneo: this.Abortoespontaneo,
                    Mortinato: this.Mortinato,
                    Gestantefechaparto: this.Gestantefechaparto,
                    Gestantenumeroctrlprenatal: this.Gestantenumeroctrlprenatal,
                    Eutoxico: this.Eutoxico,
                    Cesaria: this.Cesaria,
                    Cancercuellouterino: this.Cancercuellouterino,
                    Ultimacitologia: this.Ultimacitologia,
                    Resultado: this.Resultado,
                    Menarquia: this.Menarquia,
                    Ciclos: this.Ciclos,
                    Regulares: this.Regulares,
                    Observaciongineco: this.Observaciongineco
                });
            },
            getHistoryObj(historia) {
                return (this.object = {
                    abdomen: historia.Abdomen,
                    actividadfisica: historia.Actividadfisica,
                    agudezavisual: historia.Agudezavisual,
                    altonivelestres: historia.Altonivelestres,
                    antecedentes: historia.Antecedentes,
                    antropometricas: historia.Antropometricas,
                    atendido_por: historia.Atendido_Por,
                    cabezacuello: historia.Cabezacuello,
                    cantidadlicor: historia.Cantidadlicor,
                    cardiopulmonar: historia.Cardiopulmonar,
                    cardiovascular: historia.Cardiovascular,
                    cedula: historia.Cédula,
                    cedulamedico: historia.Cedulamedico,
                    celular: historia.Celular,
                    condiciongeneral: historia.Condiciongeneral,
                    consumopsicoactivo: historia.Consumopsicoactivo,
                    datetimeingreso: historia.Datetimeingreso,
                    datetimesalida: historia.Datetimesalida,
                    departamento: historia.Departamento,
                    destinopaciente: historia.Destinopaciente,
                    diagnosticoprincipal: historia.Diagnostico_Principal,
                    dietasaludable: historia.Dietasaludable,
                    direccionresidencia: historia.Direccion_Residencia,
                    duermemenoseishoras: historia.Duermemenoseishoras,
                    duracion: historia.Duracion,
                    edad: historia.Edad,
                    endocrinologico: historia.Endocrinologico,
                    enfermedadactual: historia.Enfermedadactual,
                    entidademite: historia.Entidademite,
                    especialidad: historia.Especialidad,
                    estilovidaobservaciones: historia.Estilovidaobservaciones,
                    examenmama: historia.Examenmama,
                    examenmental: historia.Examenmental,
                    extremidades: historia.Extremidades,
                    fechanacimiento: historia.Fecha_Nacimiento,
                    fechasolicita: historia.Fecha_solicita,
                    finalidad: historia.Finalidad,
                    firma: historia.Firma,
                    frecucardiaca: historia.Frecucardiaca,
                    frecuenciasemana: historia.Frecuenciasemana,
                    frecurespiratoria: historia.Frecurespiratoria,
                    fumacantidad: historia.Fumacantidad,
                    fumadorpasivo: historia.Fumadorpasivo,
                    fumainicio: historia.Fumainicio,
                    gastrointestinal: historia.Gastrointestinal,
                    genitourinario: historia.Genitourinario,
                    genitourinario: historia.Genitourinario,
                    id: historia.id,
                    laboraen: historia.Laboraen,
                    licorfrecuencia: historia.Licorfrecuencia,
                    tratamientoncologico: historia.tratamientoncologico,
                    cirujiaoncologica: historia.cirujiaoncologica,
                    flaboratoriopatologia: historia.flaboratoriopatologia,
                    fdxcanceractual: historia.fdxcanceractual,
                    ncirujias: historia.ncirujias,
                    dukes: historia.Dukes,
                    gleason: historia.gleason,
                    her2: historia.Her2,
                    tumorsegunbiopsia: historia.tumorsegunbiopsia,
                    estadificacioninicial: historia.estadificacioninicial,
                    fechaestadificacion: historia.fechaestadificacion,
                    ips: historia.IPS,
                    patologiacancelactual: historia.Patologiacancelactual,
                    iniciocirujia: historia.iniciocirujia,
                    fincirujia: historia.fincirujia,
                    recibioradioterapia: historia.recibioradioterapia,
                    inicioradioterapia: historia.inicioradioterapia,
                    finradioterapia: historia.finradioterapia,
                    nsesiones: historia.nsesiones,
                    intencion: historia.intencion,
                    linfatico: historia.Linfatico,
                    medicoordeno: historia.Medicoordeno,
                    motivoconsulta: historia.Motivoconsulta,
                    municipioafiliado: historia.Municipio_Afiliado,
                    neurologico: historia.Neurologico,
                    neurologico: historia.Neurologico,
                    nombre: historia.Nombre,
                    norefiere: historia.Norefiere,
                    observaciones: historia.Observaciones,
                    oftalmologico: historia.Oftalmologico,
                    ojosfondojos: historia.Ojosfondojos,
                    osteomioarticular: historia.Osteomioarticular,
                    osteomuscular: historia.Osteomuscular,
                    osteomuscular: historia.Osteomuscular,
                    otorrinoralingologico: historia.Otorrinoralingologico,
                    otrossignosvitales: historia.Otros_Signos_Vitales,
                    otrossignosvitales: historia.Otros_Signos_Vitales,
                    pielfraneras: historia.Pielfraneras,
                    planmanejo: historia.Planmanejo,
                    presionarterial: historia.Presión_Arterial,
                    psicoactivocantidad: historia.Psicoactivocantidad,
                    recomendaciones: historia.Recomendaciones,
                    reflejososteotendinos: historia.Reflejososteotendinos,
                    registromedico: historia.Registromedico,
                    respiratorio: historia.Respiratorio,
                    resultayudadiagnostica: historia.Resultayudadiagnostica,
                    sexo: historia.Sexo,
                    signosvitales: historia.Signos_Vitales,
                    suenoreparador: historia.Suenoreparador,
                    tactoretal: historia.Tactoretal,
                    tegumentario: historia.Tegumentario,
                    telefono: historia.Telefono,
                    timeconsulta: historia.Timeconsulta,
                    tipoafiliado: historia.Tipo_Afiliado,
                    tipodiagnostico: historia.Tipodiagnostico,
                    tipoorden: historia.Tipo_Orden,
                    indicacion: historia.Indicacion,
                    hallazgos: historia.Hallazgos,
                    conclusion: historia.Conclusion,
                    notaclaratoria: historia.Notaclaratoria,
                    tecnica: historia.Tecnica,
                    cups: historia.cups,
                    radiologo: historia.Radiologo,
                    firmaradiologo: historia.Firmaradiologo,
                    conclusion: historia.Conclusion,
                    fechaultimamenstruacion: historia.Fechaultimamenstruacion,
                    gestaciones: historia.Gestaciones,
                    partos: historia.Partos,
                    abortoprovocado: historia.Abortoprovocado,
                    abortoespontaneo: historia.Abortoespontaneo,
                    mortinato: historia.Mortinato,
                    gestantefechaparto: historia.Gestantefechaparto,
                    gestantenumeroctrlprenatal: historia.Gestantenumeroctrlprenatal,
                    eutoxico: historia.Eutoxico,
                    cesaria: historia.Cesaria,
                    cancercuellouterino: historia.Cancercuellouterino,
                    ultimacitologia: historia.Ultimacitologia,
                    resultado: historia.Resultado,
                    menarquia: historia.Menarquia,
                    ciclos: historia.Ciclos,
                    regulares: historia.Regulares,
                    observaciongineco: historia.Observaciongineco
                });
            },
            async isTipoCita() {
                return await axios.post('/api/cita/checkEspecialidad', {
                        cita_paciente: this.cita_paciente
                    })
                    .then(res => {
                        if (res.data.Nombre == 'Imagenologia') {
                            this.isImgRadium = true;
                            this.isImagenologiaBool = true
                        } else if (res.data.Nombre == 'Oncologia') {
                            this.isImgOncologia = true;
                            this.isOncologiaBool = true
                        } else if (res.data.Nombre == 'Enfermeria') {
                            this.isImgOncologia = true;
                            this.isEnfermeriaBool = true
                        } else if (res.data.Nombre == 'Oftalmologia') {
                            this.isOftalmologiaBool = true
                        } else if (res.data.salud_ocupacional == 'Psicologia') {
                            this.isPsicologiaBool = true
                        } else if (res.data.salud_ocupacional == 'Voz') {
                            this.isVozBool = true
                        } else if (res.data.salud_ocupacional == 'Visiometria') {
                            this.isVisiometriaBool = true
                        } else if (res.data.salud_ocupacional == 'Consulta Medica') {
                            this.isMedicoOcupacional = true
                        }
                        this.psicologiaOcupacional = res.data.aptitud_laboral_psicologia
                    })
                    .catch(err => console.log(err.response))
            },
            async historia() {

                await this.isTipoCita();

                // HISTORIA IMAGENOLOGIA
                if (this.isImagenologiaBool == true) {
                    this.layoutHistorial = false
                    this.cuerpoHistoria = false
                    this.modulos = [{
                        id: 1,
                        text: 'IMAGENOLOGÍA',
                        path: '/historias/historiaclinica/imagenologia?cita_paciente=' + this.cita_paciente,
                        disabled: false,
                        sexo: 'Ambos'
                    }, ]
                    this.datospaci = false;
                    this.cuerpoHistoria = true;
                    EventBus.$emit("enable-layout-hc");
                }
                // HISTORIA OFTAMOLOGIA
                else if (this.isOftalmologiaBool == true) {
                    this.layoutHistorial = false
                    this.cuerpoHistoria = false
                    this.modulos = [{
                        id: 1,
                        text: 'OFTAMOLOGIA',
                        path: '/historias/historia_oftamologia/oftamologia?cita_paciente=' + this
                            .cita_paciente,
                        disabled: false,
                        sexo: 'Ambos'
                    }, ];
                    this.datospaci = true;
                    this.nav = true;
                }
                // HISTORIA SALUD OCUPACIONAL PSICOLOGIA
                else if (this.isPsicologiaBool == true) {
                    this.layoutHistorial = false
                    this.cuerpoHistoria = false
                    this.modulos = [{
                        id: 1,
                        text: 'SALUD OCUPACIONAL PSICOLOGIA',
                        path: '/historias/historia_salud_ocupacional/psicologia?cita_paciente=' + this
                            .cita_paciente,
                        disabled: false,
                        sexo: 'Ambos'
                    }, ];
                    this.datospaci = true;
                    this.nav = true;
                }
                // HISTORIA SALUD OCUPACIONAL VOZ
                else if (this.isVozBool == true) {
                    this.layoutHistorial = false
                    this.cuerpoHistoria = false
                    this.modulos = [{
                        id: 1,
                        text: 'SALUD OCUPACIONAL VOZ',
                        path: '/historias/historia_salud_ocupacional/voz?cita_paciente=' + this
                            .cita_paciente,
                        disabled: false,
                        sexo: 'Ambos'
                    }, ];
                    this.datospaci = true;
                    this.nav = true;
                }
                // HISTORIA SALUD OCUPACIONAL VISIOMETRIA
                else if (this.isVisiometriaBool == true) {
                    this.layoutHistorial = false
                    this.cuerpoHistoria = false
                    this.modulos = [{
                        id: 1,
                        text: 'SALUD OCUPACIONAL VISIOMETRIA',
                        path: '/historias/historia_salud_ocupacional/visiometria?cita_paciente=' + this
                            .cita_paciente,
                        disabled: false,
                        sexo: 'Ambos'
                    }, ];
                    this.datospaci = true;
                    this.nav = true;
                }
                // HISTORIA SALUD OCUPACIONAL CONSULTA MEDICA OCUPACIONAL
                else if (this.isMedicoOcupacional == true) {
                    this.modulos = [{
                        id: 1,
                        text: 'CONSULTA MEDICA OCUPACIONAL',
                        path: '/historias/historia_salud_ocupacional/Consultamedica?cita_paciente=' + this
                            .cita_paciente,
                        disabled: false,
                        sexo: 'Ambos'
                    }, ];
                    this.datospaci = true;
                    this.nav = true;
                }
                // HISTORIA DE ONCOLOGIA
                else if (this.isOncologiaBool == true) {
                    this.layoutHistorial = false
                    this.cuerpoHistoria = false
                    this.modulos = [{
                            id: 1,
                            text: 'ANAMNESIS',
                            path: '/historias/historia_oncologica/motivoOncologico?cita_paciente=' + this
                                .cita_paciente,
                            disabled: false,
                            sexo: 'Ambos'
                        },
                        {
                            id: 2,
                            text: 'DESCRIPCIÓN PATOLOGIA',
                            path: '/historias/historia_oncologica/descripcionPatologia?cita_paciente=' + this
                                .cita_paciente,
                            disabled: true,
                            sexo: 'Ambos'
                        },
                        {
                            id: 3,
                            text: 'ANTECEDENTES',
                            path: '/historias/historia_oncologica/patologias?cita_paciente=' + this
                                .cita_paciente,
                            disabled: true,
                            sexo: 'Ambos'
                        },
                        {
                            id: 4,
                            text: 'EXAMEN FÍSICO',
                            path: '/historias/historia_oncologica/examensistema?cita_paciente=' + this
                                .cita_paciente,
                            disabled: true,
                            sexo: 'Ambos'
                        },
                        {
                            id: 5,
                            text: 'DIAGNOSTICOS',
                            path: '/historias/historia_oncologica/diagnostico?cita_paciente=' + this
                                .cita_paciente,
                            disabled: true,
                            sexo: 'Ambos'
                        },
                        {
                            id: 6,
                            text: 'CONDUCTA',
                            path: '/historias/historia_oncologica/conducta?cita_paciente=' + this.cita_paciente,
                            disabled: true,
                            sexo: 'Ambos'
                        },

                    ];
                    this.datospaci = true;
                    this.nav = true;
                }
                // HISTORIA DE ENFERMERIA
                else if (this.isEnfermeriaBool == true) {
                    this.layoutHistorial = false
                    this.cuerpoHistoria = false
                    this.modulos = [{
                            id: 1,
                            text: 'ANAMNESIS ENFERMERIA',
                            path: '/historias/historia_enfermeria/motivoEnfermeria?cita_paciente=' + this
                                .cita_paciente,
                            disabled: false,
                            sexo: 'Ambos'
                        },
                        {
                            id: 2,
                            text: 'ANTECEDENTES ENFERMERIA',
                            path: '/historias/historia_enfermeria/patologias?cita_paciente=' + this
                                .cita_paciente,
                            disabled: true,
                            sexo: 'Ambos'
                        },
                        {
                            id: 3,
                            text: 'EXAMEN FÍSICO ENFERMERIA',
                            path: '/historias/historia_enfermeria/examensistema?cita_paciente=' + this
                                .cita_paciente,
                            disabled: true,
                            sexo: 'Ambos'
                        },

                    ];
                    EventBus.$on('change_disabled_list_history', (nombre) => {
                        this.modulos[this.modulos.findIndex(x => x.text == nombre)].disabled = false;
                    })
                    this.datospaci = true;
                    this.nav = true;
                }
                //HISTORIA CLINICA
                else {
                    this.datospaci = false;
                    this.layoutHistorial = true
                    this.cuerpoHistoria = true
                    EventBus.$emit("enable-layout-hc");
                    EventBus.$emit('nav_historia', this.nav)
                    this.modulos = [{
                            id: 1,
                            text: 'ANAMNESIS',
                            path: '/historias/historiaclinica/motivoconsulta?cita_paciente=' + this
                                .cita_paciente,
                            disabled: false,
                            sexo: 'Ambos'
                        },
                        {
                            id: 2,
                            text: 'ANTECEDENTES',
                            path: '/historias/historiaclinica/patologias?cita_paciente=' + this.cita_paciente,
                            disabled: true,
                            sexo: 'Ambos'
                        },
                        {
                            id: 3,
                            text: 'ANTECEDENTES GINECO OBSTÉTRICOS',
                            path: '/historias/historiaclinica/gineco?cita_paciente=' + this.cita_paciente,
                            disabled: true,
                            sexo: 'F'
                        },
                        {
                            id: 4,
                            text: 'EXAMEN FÍSICO',
                            path: '/historias/historiaclinica/examensistema?cita_paciente=' + this
                                .cita_paciente,
                            disabled: true,
                            sexo: 'Ambos'
                        },
                        {
                            id: 6,
                            text: 'DIAGNOSTICOS',
                            path: '/historias/historiaclinica/diagnostico?cita_paciente=' + this.cita_paciente,
                            disabled: true,
                            sexo: 'Ambos'
                        },
                        {
                            id: 7,
                            text: 'CONDUCTA',
                            path: '/historias/historiaclinica/conducta?cita_paciente=' + this.cita_paciente,
                            disabled: true,
                            sexo: 'Ambos'
                        },
                    ];
                    EventBus.$on('change_disabled_list_history', (nombre) => {
                        if (nombre != 'ESTILOS DE VIDA') {
                            this.modulos[this.modulos.findIndex(x => x.text == nombre)].disabled = false;
                        }
                    })
                    this.nav = true;
                }
                this.steps = this.modulos.length;
            },
            updateDatapaci() {
                if (!this.paciente.Telefono) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#383737db">El Telefono es requerido!<span>'
                    })
                    return;
                } else if (!this.paciente.Correo1) {
                    Swal.fire({
                        icon: 'error',
                        title: '<span style="color:#383737db">El Correo es requerido!<span>'
                    })
                    return;
                }
                axios.put(`/api/paciente/edit/${this.paciente.id}`, this.paciente)
                    .then(res => {
                        this.datospaci = false;
                        swal({
                            title: "¡Paciente Actualizado!",
                            text: " ",
                            type: "success",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                        EventBus.$emit("enable-layout-hc");
                        EventBus.$emit('nav_historia', this.nav)
                        this.cuerpoHistoria = true;
                    })
                    .catch(err => console.log(err.response.paciente));
            },
            getPaciente() {
                this.preload = true
                axios.get('/api/cita/' + this.cita_paciente + '/datospaciente')
                    .then((res) => {
                        this.paciente = res.data
                        EventBus.$emit('informacion-paciente-layout', this.paciente)
                        EventBus.$emit('nav_historia', false)
                        this.preload = false
                    })
            },
            getOcupacional(citaPaciente) {
               axios.post('/api/cita/getSaludocupacional/' + citaPaciente)
                    .then(res => {
                    })
                    .catch(err => console.log(err.response));
            },
            fetchAntecedentespersonales() {
                axios
                    .get("/api/pacienteantecedente/antecedentes/" + this.cita_paciente + "")
                    .then(res => {
                        let addRCVM = false
                        res.data.forEach(dat => {
                            if (this.antecedentesRCVM.includes(dat.Nombre)) {
                                addRCVM = true
                            }
                        });

                        if (addRCVM) {
                            this.modulos.splice(-1, 0, {
                                text: 'RCCVM',
                                path: '/historias/historiaclinica/rcvm?cita_paciente=' + this.cita_paciente,
                                disabled: false,
                                sexo: 'Ambos'
                            })
                        }
                    });
            },
            cargarCuerpoHC(url, step) {
                this.$router.push(url)
                this.e1 = step
            },
            sexoPaciente(sexo) {
                if (sexo == 'Ambos' || this.paciente.Sexo == sexo) {
                    return true;
                }
                return false;
            }
        },

    }

</script>
<style scoped>
    .mobile {
        color: #333;
    }

    @media screen and (max-width: 768px) {
        .mobile table.v-table tr {
            max-width: 100%;
            position: relative;
            display: block;
        }

        .mobile table.v-table tr:nth-child(odd) {
            border-left: 6px solid deeppink;
        }

        .mobile table.v-table tr:nth-child(even) {
            border-left: 6px solid cyan;
        }

        .mobile table.v-table tr td {
            display: flex;
            width: 100%;
            border-bottom: 1px solid #f5f5f5;
            height: auto;
            padding: 10px;
        }

        .mobile table.v-table tr td ul li:before {
            content: attr(data-label);
            padding-right: .5em;
            text-align: left;
            display: block;
            color: #999;

        }

        .v-datatable__actions__select {
            width: 50%;
            margin: 0px;
            justify-content: flex-start;
        }

        .mobile .theme--light.v-table tbody tr:hover:not(.v-datatable__expand-row) {
            background: transparent;
        }

    }

    .flex-content {
        padding: 0;
        margin: 0;
        list-style: none;
        display: flex;
        flex-wrap: wrap;
        width: 100%;
    }

    .flex-item {
        padding: 5px;
        width: 50%;
        height: 40px;
        font-weight: bold;
    }

    table {
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
        border: 1px solid #ddd;
    }

    th,
    td {
        text-align: left;
        padding: 5px;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2
    }

</style>
