<template>
    <div>
        <v-card>
            <v-bottom-nav :color="color" :value="true" dark>
                <v-btn v-model="demanda" dark>
                    <span>Demanda inducida</span>
                    <v-icon>ondemand_video</v-icon>
                </v-btn>
                <v-btn v-model="misDemandas" dark @click="listarMisDemandasInducidas()">
                    <span>Mis generadas</span>
                    <v-icon>music_note</v-icon>
                </v-btn>
            </v-bottom-nav>
        </v-card>

        <v-flex xs12 sm12 md12>
            <v-card max-height="100%" class="mb-3" v-if="demanda == true">
                <v-container grid-list-xl justify-end>
                    <v-layout row wrap>
                        <v-toolbar flat color="white">
                            <v-toolbar-title>Generar nueva demanda inducida</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-text-field label="Ingrese el Documento del Paciente...." v-model="buscarAfiliado"
                                append-icon="search" @keyup.enter="demandaInducidaPaciente()" clearable
                                @click:clear="clearData">
                            </v-text-field>
                        </v-toolbar>
                    </v-layout>
                </v-container>
                <v-form v-if="pacienteDemandaInducida.Num_Doc">
                    <v-card-title class="headline primary" style="color:white">
                        <span class="justify-center title layout font-weight-light">Datos del afiliado</span>
                    </v-card-title>
                    <v-card-title>
                        <form>
                            <v-layout row wrap>
                                <v-toolbar dark color="success">
                                    <v-toolbar-title style="font-size: 12px;"><b style="color:blue">Tipo Doc:</b>
                                        {{pacienteDemandaInducida.Tipo_Doc}}</v-toolbar-title>
                                    <v-toolbar-title style="font-size: 12px;"><b style="color:blue">DOCUMENTO:</b>
                                        {{pacienteDemandaInducida.Num_Doc}}</v-toolbar-title>
                                    <v-toolbar-title style="font-size: 12px;"><b style="color:blue">CICLO VITAL:</b>
                                        {{pacienteDemandaInducida.ciclo_vida}}
                                    </v-toolbar-title>
                                    <v-toolbar-title style="font-size: 12px;"><b style="color:blue">TUTELA:</b>
                                        {{pacienteDemandaInducida.Tienetutela == true?'SI':'NO'}}
                                    </v-toolbar-title>
                                    <v-toolbar-title style="font-size: 12px;"><b style="color:blue">CÓDIGO
                                            BLANCO:</b>
                                        {{pacienteDemandaInducida.victima_conficto_armado == true?'SI':'NO'}}
                                    </v-toolbar-title>
                                    <v-toolbar-title style="font-size: 12px;"><b style="color:blue">PORTABILIAD:</b>
                                        {{pacienteDemandaInducida.portabilidad == true?'SI':'NO'}}
                                    </v-toolbar-title>
                                    <v-toolbar-title style="font-size: 12px;"><b style="color:blue">EDAD:</b>
                                        {{pacienteDemandaInducida.Edad_Cumplida}}
                                    </v-toolbar-title>
                                    <v-toolbar-title style="font-size: 12px;"><b style="color:blue">SEXO:</b>
                                        {{pacienteDemandaInducida.Sexo == 'F'?'Femenino': 'Masculino'}}
                                    </v-toolbar-title>
                                    <v-spacer></v-spacer>
                                    <v-btn small flat>
                                        {{ pacienteDemandaInducida.Primer_Nom }}
                                        {{ pacienteDemandaInducida.Primer_Ape }}{{ pacienteDemandaInducida.Segundo_Ape }}
                                        <v-flex ml-2>
                                            <v-avatar size="40">
                                                <v-icon dark>mdi-account</v-icon>
                                            </v-avatar>
                                        </v-flex>
                                    </v-btn>
                                </v-toolbar>
                                <v-layout>
                                    <v-form>
                                        <v-container fluid>
                                            <v-layout row wrap>
                                                <v-flex xs12 sm6 md2>
                                                    <v-text-field readonly
                                                        v-model="pacienteDemandaInducida.Departamento"
                                                        label="Departamento"></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm6 md3>
                                                    <v-text-field readonly
                                                        v-model="pacienteDemandaInducida.Municipio_Atencion"
                                                        label="Municipio atención"></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm6 md5>
                                                    <v-text-field readonly
                                                        v-model="pacienteDemandaInducida.Punto_Atencion"
                                                        label="IPS asignada">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm6 md2>
                                                    <v-text-field readonly v-model="pacienteDemandaInducida.Fecha_Naci"
                                                        label="Fecha Nacimiento"></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm6 md5>
                                                    <v-text-field readonly
                                                        v-model="pacienteDemandaInducida.Direccion_Residencia"
                                                        label="Direcciónn"></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm6 md2>
                                                    <v-text-field readonly v-model="pacienteDemandaInducida.Telefono"
                                                        label="Teléfono">
                                                    </v-text-field>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </v-form>
                                </v-layout>


                                <v-flex xs12>
                                    <!-- GUARDAR ANTECEDENTES PERSONALES -->
                                    <v-container grid-list-xs>
                                        <v-layout row wrap>
                                            <v-container fluid grid-list-xl>
                                                <v-layout wrap align-center>
                                                    <v-flex xs12 sm4 md4>
                                                        <v-autocomplete v-model="pacienteDemandaInducida.cie10_id"
                                                            append-icon="search" :items="cieConcat"
                                                            item-disabled="customDisabled" item-text="nombre"
                                                            label="Antecedentes personales">
                                                        </v-autocomplete>
                                                    </v-flex>

                                                    <v-flex xs12 sm6 md6>
                                                        <v-textarea solo name="input-1-1"
                                                            label="Escribir Descripcion Antecedente"
                                                            v-model="pacienteDemandaInducida.Descripcion">
                                                        </v-textarea>
                                                    </v-flex>

                                                    <v-flex xs12 sm1 md1>
                                                        <v-btn fab dark color="success"
                                                            @click="guardarAntecedentesPersonales()">
                                                            <v-icon dark>add</v-icon>
                                                        </v-btn>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-layout>
                                    </v-container>

                                    <v-card-title primary-title>
                                        <v-flex xs12 sm12 d-flex>
                                            <v-data-table :items="allAntecedentesPaciente"
                                                :headers="headerAntecedentePaciente" hide-actions class="elevation-1">
                                                <template v-slot:items="props">
                                                    <td>{{ props.item.created_at }}</td>
                                                    <td class="text-xs">{{ props.item.medico }}</td>
                                                    <td class="text-xs">{{ props.item.patologias }}</td>
                                                    <td class="text-xs">
                                                        <v-tooltip top>
                                                            <template v-slot:activator="{ on }">
                                                                <v-btn text icon color="red" dark v-on="on">
                                                                    <v-icon @click="deleteAntecedente(props.item.id)">
                                                                        mdi-delete-forever
                                                                    </v-icon>
                                                                </v-btn>
                                                            </template>
                                                            <span>Historial</span>
                                                        </v-tooltip>
                                                    </td>
                                                </template>
                                            </v-data-table>
                                        </v-flex>
                                    </v-card-title>
                                    <v-card-title class="headline primary" style="color:white">
                                        <span class="justify-center title layout font-weight-light">Programa a
                                            direccionar</span>
                                    </v-card-title>
                                    <v-form>
                                        <v-container fluid>
                                            <v-layout row wrap>
                                                <v-flex xs1 sm1 md1>
                                                    <v-checkbox
                                                        v-model="pacienteDemandaInducida.demanda_inducida_efectiva"
                                                        label="Efectiva" color='primary'></v-checkbox>
                                                </v-flex>
                                                <v-flex xs12 sm12 md3>
                                                    <v-autocomplete
                                                        v-model="pacienteDemandaInducida.tipoDemandaInducida"
                                                        :items='tiposDemandaInducida' label="Tipo demanda inducida">
                                                    </v-autocomplete>
                                                </v-flex>
                                                <v-flex xs12 sm12 md3>
                                                    <v-autocomplete v-model="pacienteDemandaInducida.programaremitido"
                                                        :items='listaDemandaInducida'
                                                        label="Programa de demanda inducida"
                                                        @change="limpiarListaInducida()">
                                                    </v-autocomplete>
                                                </v-flex>
                                                <v-flex xs12 sm12 md4
                                                    v-if="pacienteDemandaInducida.programaremitido == 'RIESGO CARDIOVASCULAR'">
                                                    <v-text-field type='date'
                                                        v-model="pacienteDemandaInducida.fecha_dx_riesgocardiovascular"
                                                        label="Fecha dx riesgo cardio vascular"></v-text-field>
                                                </v-flex>
                                                <v-flex xs12 sm12 md5
                                                    v-if="pacienteDemandaInducida.programaremitido == 'EVENTOS SALUD PUBLICA'">
                                                    <v-textarea solo name="input-7-4"
                                                        v-model="pacienteDemandaInducida.descripcion_evento_saludpublica"
                                                        label="Descripcion de eventos de salud publica">
                                                    </v-textarea>
                                                </v-flex>
                                                <v-flex xs12 sm12 md5
                                                    v-if="pacienteDemandaInducida.programaremitido == 'OTROS PROGRAMAS'">
                                                    <v-textarea solo name="input-7-4"
                                                        v-model="pacienteDemandaInducida.descripcion_otro_programa"
                                                        label="Descripcion de otro programa">
                                                    </v-textarea>
                                                </v-flex>
                                                <v-flex xs12 sm12 md12>
                                                    <input id="adjuntos" ref="adjuntos" type="file" />
                                                    <span>(máximo 1 archivo y 5 MB, formatos permitidos jpg, jpeg, png,
                                                        pdf)</span>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                    </v-form>
                                </v-flex>
                            </v-layout>
                        </form>

                    </v-card-title>
                    <div class="text-xs-center">
                        <v-btn round color="success" dark @click="saveDemandaInducida()">Generar demanda
                            inducida</v-btn>
                    </div>
                </v-form>
            </v-card>
        </v-flex>
        <v-flex xs12 sm12 md12>
            <v-card max-height="100%" class="mb-3" v-if="misDemandas == true">
                <v-toolbar flat color="white">
                    <v-toolbar-title>Mis demandas inducidas generadas</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <!-- <v-btn color="success" class="white--text">
                        Exportar
                        <v-icon right dark>mdi-file-excel</v-icon>
                    </v-btn> -->
                </v-toolbar>
                <v-data-table :headers="headersDemandaInducida" :items="misDemandasInducidas" :loading="loadingDI"
                    class="elevation-1">
                    <v-progress-linear color="blue" indeterminate></v-progress-linear>
                    <template v-slot:items="props">
                        <td>{{ props.item.id }}</td>
                        <td>{{ props.item.Tipo_Doc }}</td>
                        <td>{{ props.item.Num_Doc }}</td>
                        <td>{{ props.item.Paciente }}</td>
                        <td>{{ props.item.IPS }}</td>
                        <td>
                            <v-chip v-if="props.item.demanda_inducida_efectiva === true" color="green" text-color="white">Si</v-chip>
                            <v-chip v-else color="red" text-color="white">No</v-chip>
                        </td>
                        <td>{{ props.item.programaremitido }}</td>
                        <td>{{ props.item.tipoDemandaInducida }}</td>
                    </template>
                </v-data-table>
            </v-card>
        </v-flex>
    </div>
</template>

<script>
    import axios from 'axios';
    import {
        mapGetters
    } from "vuex";
    export default {
        name: 'HorusHealthRemisionProgramas',

        data() {
            return {
                adjuntos: [],
                bottomNav: 3,
                demanda: '',
                misDemandas: '',
                pacienteDemandaInducida: {
                    Departamento: '',
                    municipio_nacimiento: '',
                    Primer_Nom: '',
                    SegundoNom: '',
                    Primer_Ape: '',
                    Segundo_Ape: '',
                    IPS: '',
                    Tipo_Doc: '',
                    Num_Doc: '',
                    Edad_Cumplida: '',
                    Fecha_Naci: '',
                    Sexo: '',
                    Direccion_Residencia: '',
                    Telefono: '',
                    tipoDemandaInducida: '',
                    programaremitido: '',
                    fecha_dx_riesgocardiovascular: '',
                    descripcion_evento_saludpublica: '',
                    descripcion_grupo_riesgo: '',
                    demanda_inducida_efectiva: false,
                },
                buscarAfiliado: '',
                cie10s: [],
                allAntecedentesPaciente: [],
                tiposDemandaInducida: ['DEMANDA INDUCIDA TELEFONICA', 'DEMANDA INDUCIDA PRESENCIAL',
                    'REMITIDO POR MEDICO O ENFERMERA', 'DEMANDA INDUCIDA ESPONTANEA', 'REFERIDO GESTORES'
                ],
                headerAntecedentePaciente: [{
                        text: 'Fecha'
                    },
                    {
                        text: 'Médico'
                    },
                    {
                        text: 'Antecedente'
                    },
                ],
                misDemandasInducidas: [],
                headersDemandaInducida: [{
                        text: 'id'
                    },
                    {
                        text: 'T. Doc'
                    },
                    {
                        text: 'Documento'
                    },
                    {
                        text: 'Paciente'
                    },
                    {
                        text: 'IPS'
                    },
                    {
                        text: 'Efectiva'
                    },
                    {
                        text: 'Programa'
                    },
                    {
                        text: 'Tipo Demanda'
                    },
                ],
                loadingDI: false,
            };
        },

        created() {
            this.fetchAntecedentes();
            this.fetchCie10s();
            this.listarMisDemandasInducidas();
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
            color() {
                switch (this.bottomNav) {
                    case 0:
                        return 'blue-grey'
                    case 1:
                        return 'teal'
                    case 2:
                        return 'brown'
                    case 3:
                        return 'indigo'
                }
            },
            listaDemandaInducida: function () {
                if (this.pacienteDemandaInducida.ciclo_vida === '1ra Infancia') {
                    return this.programaDemandaInducida = [
                        'VACUNACION', 'CONSULTA PRIMERA INFANCIA  (0-5 años)',
                        'CONTROL RECIEN NACIDO',
                        'SALUD ORAL',
                        'TALLERES EDUCATIVOS',
                        'RIESGO CARDIOVASCULAR',
                        'EVENTOS SALUD PUBLICA',
                        'OTROS PROGRAMAS'
                    ]
                } else if (this.pacienteDemandaInducida.ciclo_vida === 'Infancia') {
                    return this.programaDemandaInducida = [
                        'VACUNACION',
                        'CONSULTA INFANCIA (6-11años)',
                        'SALUD ORAL',
                        'TALLERES EDUCATIVOS',
                        'RIESGO CARDIOVASCULAR',
                        'EVENTOS SALUD PUBLICA',
                        'OTROS PROGRAMAS'
                    ]
                } else if (this.pacienteDemandaInducida.ciclo_vida === 'Adolescencia' && this
                    .pacienteDemandaInducida.Sexo === 'M') {
                    return this.programaDemandaInducida = [
                        'VACUNACION',
                        'CONSULTA ADOLESCENCIA (12-17 años)',
                        'SALUD ORAL',
                        'PRECONCEPCIONAL',
                        'PLANIFICACIÓN FAMILIAR',
                        'TALLERES EDUCATIVOS',
                        'RIESGO CARDIOVASCULAR',
                        'EVENTOS SALUD PUBLICA',
                        'OTROS PROGRAMAS'
                    ]
                } else if (this.pacienteDemandaInducida.ciclo_vida === 'Juventud' && this.pacienteDemandaInducida
                    .Sexo === 'F') {
                    return this.programaDemandaInducida = [
                        'VACUNACION',
                        'CONSULTA ADOLESCENCIA (12-17 años)',
                        'PLANIFICACIÓN FAMILIAR',
                        'PRECONCEPCIONAL',
                        'CONTROL PRENATAL',
                        'CONSULTA NUTRICION PRENATAL',
                        'ASESORIA  LACTANCIA',
                        'CURSO PSICOPROFILACTICO',
                        'CITOLOGIA',
                        'CONTROL POSPARTO',
                        'SALUD ORAL',
                        'TALLERES EDUCATIVOS',
                        'RIESGO CARDIOVASCULAR',
                        'EVENTOS SALUD PUBLICA',
                        'OTROS PROGRAMAS'
                    ]
                } else if (this.pacienteDemandaInducida.ciclo_vida === 'Juventud' && this.pacienteDemandaInducida
                    .Sexo === 'M') {
                    return this.programaDemandaInducida = [
                        'VACUNACION',
                        'CONSULTA JUVENTUD (18-28 años)',
                        'SALUD ORAL',
                        'PRECONCEPCIONAL',
                        'PLANIFICACIÓN FAMILIAR',
                        'TALLERES EDUCATIVOS',
                        'RIESGO CARDIOVASCULAR',
                        'EVENTOS SALUD PUBLICA',
                        'OTROS PROGRAMAS'
                    ]
                } else if (this.pacienteDemandaInducida.ciclo_vida === 'Adultez' && this.pacienteDemandaInducida
                    .Sexo === 'F') {
                    return this.programaDemandaInducida = [
                        'VACUNACION',
                        'CONSULTA ADULTEZ (29-59 años)',
                        'PLANIFICACIÓN FAMILIAR',
                        'PRECONCEPCIONAL',
                        'CONTROL PRENATAL',
                        'CONSULTA NUTRICION PRENATAL',
                        'ASESORIA  LACTANCIA',
                        'CURSO PSICOPROFILACTICO',
                        'CITOLOGIA',
                        'MAMOGRAFIA',
                        'CONTROL POSPARTO',
                        'CONTROL RECIEN NACIDO',
                        'SALUD ORAL',
                        'TALLERES EDUCATIVOS',
                        'RIESGO CARDIOVASCULAR',
                        'EVENTOS SALUD PUBLICA',
                        'OTROS PROGRAMAS'
                    ]
                } else if (this.pacienteDemandaInducida.ciclo_vida === 'Adultez' && this.pacienteDemandaInducida
                    .Sexo === 'M') {
                    return this.programaDemandaInducida = [
                        'VACUNACION',
                        'CONSULTA ADULTEZ (29-59 años)',
                        'PLANIFICACIÓN FAMILIAR',
                        'PRECONCEPCIONAL',
                        'CURSO PSICOPROFILACTICO',
                        'TAMIZAJE PROSTATA',
                        'SALUD ORAL',
                        'TALLERES EDUCATIVOS',
                        'RIESGO CARDIOVASCULAR',
                        'EVENTOS SALUD PUBLICA',
                        'OTROS PROGRAMAS'
                    ]
                } else if (this.pacienteDemandaInducida.ciclo_vida === 'Vejez' && this.pacienteDemandaInducida
                    .Sexo === 'F') {
                    return this.programaDemandaInducida = [
                        'VACUNACION',
                        'CONSULTA VEJEZ (60 años o más)',
                        'CITOLOGIA',
                        'MAMOGRAFIA',
                        'SALUD ORAL',
                        'TALLERES EDUCATIVOS',
                        'RIESGO CARDIOVASCULAR',
                        'EVENTOS SALUD PUBLICA',
                        'OTROS PROGRAMAS'
                    ]
                } else if (this.pacienteDemandaInducida.ciclo_vida === 'Vejez' && this.pacienteDemandaInducida
                    .Sexo === 'M') {
                    return this.programaDemandaInducida = [
                        'VACUNACION',
                        'CONSULTA VEJEZ (60 años o más)',
                        'PLANIFICACIÓN FAMILIAR',
                        'PRECONCEPCIONAL',
                        'TAMIZAJE PROSTATA',
                        'SALUD ORAL',
                        'TALLERES EDUCATIVOS',
                        'RIESGO CARDIOVASCULAR',
                        'EVENTOS SALUD PUBLICA',
                        'OTROS PROGRAMAS'
                    ]
                }
            }
        },

        methods: {
            limpiarListaInducida() {
                this.pacienteDemandaInducida.fecha_dx_riesgocardiovascular = ''
                this.pacienteDemandaInducida.descripcion_evento_saludpublica = ''
                this.pacienteDemandaInducida.descripcion_grupo_riesgo = ''
            },
            demandaInducidaPaciente() {
                axios.get(`/api/historia/demandaInducida/${this.buscarAfiliado}`).then(res => {
                    this.pacienteDemandaInducida = res.data,
                        this.fetchAntecedentes();
                })
            },
            fetchCie10s() {
                axios.get("/api/cie10/all").then(res => {
                    this.cie10s = res.data;
                });
            },
            clearData() {
                for (const key in this.pacienteDemandaInducida) {
                    this.pacienteDemandaInducida[key] = ''
                }
                this.buscarAfiliado = ''
            },

            fetchAntecedentes() {
                this.preloasistenciaEscolar = true
                const data = {
                    paciente_id: this.pacienteDemandaInducida.paciente_id
                }
                axios.post("/api/historia/fetchAntecedentePersonal", data)
                    .then(res => {
                        this.allAntecedentesPaciente = res.data;
                        this.preloasistenciaEscolar = false
                    });
            },
            guardarAntecedentesPersonales() {
                if (!this.pacienteDemandaInducida.cie10_id) {
                    swal("La patologia es requerida!")
                    return;
                } else if (!this.pacienteDemandaInducida.Descripcion) {
                    swal("La descripcion de la patología es requerida!")
                    return;
                }
                this.preloadmedico = true
                let data = {
                    paciente_id: this.pacienteDemandaInducida.paciente_id,
                    patologias: this.pacienteDemandaInducida.cie10_id,
                    descricion_demanda_inducida: this.pacienteDemandaInducida.Descripcion
                }
                axios.post("/api/historia/saveAntecedentesPersonal", data)
                    .then(res => {
                        this.$alertHistoria('<span style="color:#fff">' + res.data.message + '<span>');
                        this.fetchAntecedentes();
                        this.preloadmedico = false;
                        this.limpiarAntecedentesInducida()
                    })
                    .catch(err => console.log(err));
            },
            limpiarAntecedentesInducida() {
                this.pacienteDemandaInducida.cie10_id = ''
                this.pacienteDemandaInducida.Descripcion = ''
            },
            saveDemandaInducida() {
                this.preload = true;
                if (this.pacienteDemandaInducida.demanda_inducida_efectiva == '') {
                    this.$alerError(
                        "Debes marcar si fue o no efectiva la demanda inducida!"
                    )
                    return
                }
                if (this.pacienteDemandaInducida.tipoDemandaInducida == undefined) {
                    this.$alerError(
                        "Debe seleccionar un tipo de demanda inducida!"
                    )
                    return
                }
                if (this.pacienteDemandaInducida.programaremitido == undefined) {
                    this.$alerError(
                        "Debe seleccionar un programa de demanda inducida!"
                    )
                    return
                }
                if (this.pacienteDemandaInducida.riesgocardiovascular == true && this.pacienteDemandaInducida
                    .fecha_dx_riesgocardiovascular == undefined) {
                    this.$alerError(
                        "Debe registrar la fecha del dx!"
                    )
                    return
                }
                if (this.pacienteDemandaInducida.eventoSaludPublica == true && this.pacienteDemandaInducida
                    .descripcion_evento_saludpublica == undefined) {
                    this.$alerError(
                        "Debe registrar la descripcion del evento de salud publica!"
                    )
                    return
                }
                if (this.pacienteDemandaInducida.otrogrupoderiesgo == true && this.pacienteDemandaInducida
                    .descripcion_grupo_riesgo.length >= 0) {
                    this.$alerError(
                        "Debe registrar la descripcion del otro grupo de riesgo!"
                    )
                    return
                }
                if (this.$refs.adjuntos.files.length == 0) {
                    this.$alerError("Es requerido adjuntar la historia clinica");
                    return;
                }

                let formData = new FormData();
                formData.append('paciente_id', this.pacienteDemandaInducida.id)
                formData.append('tipoDemandaInducida', this.pacienteDemandaInducida.tipoDemandaInducida)
                formData.append('programaremitido', this.pacienteDemandaInducida.programaremitido)
                formData.append('fecha_dx_riesgocardiovascular', this.pacienteDemandaInducida
                    .fecha_dx_riesgocardiovascular)
                formData.append('descripcion_evento_saludpublica', this.pacienteDemandaInducida
                    .descripcion_evento_saludpublica)
                formData.append('descripcion_grupo_riesgo', this.pacienteDemandaInducida.descripcion_grupo_riesgo)
                formData.append('demanda_inducida_efectiva', this.pacienteDemandaInducida.demanda_inducida_efectiva)
                formData.append(`adjuntos`, this.$refs.adjuntos.files[0]);

                axios.post("/api/historia/saveDemandaInducida", formData).then(res => {
                    this.$alerSuccess(res.data.mensaje);
                    this.clearData();
                    this.limpiarDatosDemandaInducida();
                    this.listarMisDemandasInducidas();
                }).catch(err => {
                    this.preload = false,
                        console.log(err)
                });
            },
            listarMisDemandasInducidas() {
                this.loadingDI = true
                axios.get('/api/historia/mis-demandas-inducida').then(res => {
                    this.misDemandasInducidas = res.data
                    this.loadingDI = false
                }).catch(error => {
                    console.log(error.response);
                })
            },

        },
    };

</script>

<style lang="scss" scoped>

</style>
