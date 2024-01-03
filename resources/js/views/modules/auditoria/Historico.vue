<template>
    <v-layout>
        <v-dialog v-model="dialogDetalle" persistent width="80%">
            <v-card>
                <v-toolbar flat color="primary" dark>
                    <v-toolbar-title>Resumen de Atencion Clinico</v-toolbar-title>
                </v-toolbar>

                <v-container grid-list-md text-xs-center>
                    <v-layout row wrap>
                        <v-flex v-for="(item, index) in dataHistoria" :key="index" xs3>
                            <v-label>{{ index }}</v-label>
                            <!-- <v-card-text class="px-0">{{item}}- {{(item, index)}}</v-card-text> -->
                            <div>
                                <p>{{ item }}</p>
                            </div>
                        </v-flex>
                    </v-layout>
                </v-container>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" dark @click="dialogDetalle = false">Cerrar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <template>
            <div class="text-center">
                <v-dialog v-model="preload" persistent width="800">
                    <v-card color="primary" dark>
                        <v-card-text>
                            Tranquilo procesamos tu solicitud !
                            <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                        </v-card-text>
                    </v-card>
                </v-dialog>
            </div>
        </template>

        <v-flex xs12>
            <v-card>
                <v-form @submit.prevent="find()">
                    <v-layout row wrap>
                        <v-flex xs12 md12 lg12>
                            <v-card>
                                <v-card-title class="headline success" style="color:white">
                                    <span class="title layout justify-center font-weight-light">Historias
                                        clinicas</span>
                                </v-card-title>
                                <v-container grid-list-xl>
                                    <v-layout>
                                        <v-flex xs4 sm>
                                            <v-text-field v-model="cedula" label="Cédula"></v-text-field>
                                        </v-flex>
                                        <v-flex xs3 sm>
                                            <v-text-field clearable v-model="desde" type="date" label="Desde"
                                                v-show="can('historia.consolidado')"></v-text-field>
                                        </v-flex>
                                        <v-flex xs3 sm>
                                            <v-text-field clearable v-model="hasta" type="date" label="Hasta"
                                                v-show="can('historia.consolidado')"></v-text-field>
                                        </v-flex>
                                        <v-flex xs2 sm>
                                            <v-btn color="primary" round @click="find()">Buscar</v-btn>
                                            <v-btn @click="clearFields()" v-if="cedula" fab outline small color="error">
                                                <v-icon>clear</v-icon>
                                            </v-btn>
                                        </v-flex>
                                    </v-layout>
                                    <v-card-actions>
                                        <!-- <v-spacer></v-spacer> -->
                                        <v-btn color="success" round @click="allHistorias"
                                            v-show="can('historia.consolidado')">Imprimir Consolidado</v-btn>
                                        <v-btn color="error" round @click="masivoHistorias"
                                            v-show="can('historia.masivo.dev')">Imprimir Masivo</v-btn>
                                        <v-btn color="primary" round @click="cargarHistoria()"
                                            v-show="can('historia.cargue')">Cargar historias</v-btn>
                                        <v-btn color="success" round v-on="on" @click="enlaseDinamica()">
                                            Historico Hospitalario </v-btn>
                                        <v-btn color="success" round v-on="on" @click="resultadoProdiagnostico()">
                                            Historico Imagenología
                                        </v-btn>
                                        <v-btn color="success" round v-on="on" @click="resultadoSanMarcos()">
                                            Historico Laboratorio San Marcos
                                        </v-btn>
                                    </v-card-actions>
                                </v-container>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-form>
                <v-layout row>
                    <v-dialog v-model="dialog" max-width="1000px">
                        <v-card>
                            <v-toolbar flat color="primary" dark>
                                <v-toolbar-title>Historia Clinica</v-toolbar-title>
                            </v-toolbar>
                            <v-tabs vertical>
                                <v-tab>
                                    <v-icon left>mdi-account</v-icon>Nota Aclaratoria
                                </v-tab>
                                <v-tab>
                                    <v-icon left>list_alt</v-icon>Diagnostico
                                </v-tab>

                                <v-tab-item>
                                    <v-flex>
                                        <v-card flat>
                                            <v-card-title class="headline grey lighten-2">{{ history.nombre }}
                                            </v-card-title>
                                            <form @submit.prevent="guardarNota(history.id)">
                                                <v-container>
                                                    <v-layout wrap align-center>
                                                        <v-flex xs12>
                                                            <v-textarea outline name="input-7-4"
                                                                label="Escribe Aca la Nota...." v-model="nota.aclaratoria"
                                                                required>
                                                            </v-textarea>
                                                        </v-flex>
                                                        <v-flex>
                                                            <v-card-actions>
                                                                <v-btn v-if="can('nota.create')" color="warning"
                                                                    type="submit">Guardar Nota</v-btn>
                                                            </v-card-actions>
                                                        </v-flex>
                                                        <v-flex xs12 v-for="(notaRealizada, index) in notaRealizada"
                                                            :key="notaRealizada.id">
                                                            <v-card-title style="color:black" v-if="notaRealizada != null">
                                                                <span class="title layout justify-center ">Nota
                                                                    Aclaratoria # {{ index + 1 }}</span>
                                                            </v-card-title>
                                                            <v-layout wrap align-center v-if="notaRealizada != null">
                                                                <v-flex xs12>
                                                                    <v-textarea outline name="input-7-4"
                                                                        v-model="notaRealizada.nota" readonly>
                                                                    </v-textarea>
                                                                    <span> <strong>Hora y Fecha:
                                                                        </strong>{{ notaRealizada.created_at }}</span>
                                                                    <span><strong>Usuario que realiza nota:
                                                                        </strong>{{ notaRealizada.name + ' ' +
                                                                            notaRealizada.apellido }}</span>
                                                                </v-flex>
                                                            </v-layout>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </form>
                                        </v-card>
                                    </v-flex>
                                </v-tab-item>
                                <v-tab-item>
                                    <v-card flat>
                                        <v-card-title class="headline grey lighten-2">Diagnosticos</v-card-title>
                                        <v-data-table class="fb-table-elem" :headers="diagnosticHeaders"
                                            :items="Diagnosticos" item-key="index" :items-per-page="10" hide-actions expand>
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
                                                        <div class="datatable-cell-wrapper">
                                                            {{ Diagnostico.item.Cie10_id }}
                                                        </div>
                                                    </td>
                                                    <td class="text-xs-center">
                                                        <div class="datatable-cell-wrapper">
                                                            {{ Diagnostico.item.Tipodiagnostico }}</div>
                                                    </td>
                                                    <td class="text-xs-center">
                                                        <div class="datatable-cell-wrapper">
                                                            <v-select :items="Diagnostico.item.Marcapaciente"
                                                                label="Marcar paciente"
                                                                v-model="Diagnostico.item.Marcapaciente" attach multiple
                                                                chips></v-select>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                            <v-alert v-slot:no-results :value="true" color="error" icon="warning">Your
                                                search
                                                for "{{ search }}" found no results.</v-alert>
                                            <template slot="expand" slot-scope="Diagnostico">
                                                <v-card flat>
                                                    <v-card-text>
                                                        <pre>{{ Diagnostico }}</pre>
                                                    </v-card-text>
                                                    <div class="datatable-container">
                                                        <v-card-text>
                                                            <pre>{{ Diagnostico }}</pre>
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
                            <v-layout row>
                                <v-flex xs2>
                                    <v-btn color="error" @click="cerrarModal()">Cerrar</v-btn>
                                </v-flex>
                                <v-flex xs2>
                                    <v-card-actions>
                                        <v-tooltip>
                                            <template v-slot:activator="{ on }">
                                                <v-btn v-if="dataHistoria.Datetimeingreso < '2022-04-20 00:00:00.000'"
                                                    color="blue" dark v-on="on" @click="printhc()"> Imprimir Historia
                                                    <v-icon>assignment_turned_in</v-icon>
                                                </v-btn>
                                                <v-btn v-else color="blue" dark v-on="on" @click="imprimirhc()">
                                                    Imprimir Historia
                                                    <v-icon>assignment_turned_in</v-icon>
                                                </v-btn>
                                                <v-btn color="blue" dark
                                                    v-show="history.destinopaciente === 'Hospitalización (Remision)'"
                                                    @click="Anexo(history.id, 'anexo9')"> Imprimir Anexo 9
                                                    <v-icon>assignment_turned_in</v-icon>
                                                </v-btn>
                                                <v-btn color="blue" dark
                                                    v-show="history.destinopaciente === 'Contrarreferencia (Anexo 10)'"
                                                    @click="Anexo(history.id, 'anexo10')"> Imprimir Anexo 10
                                                    <v-icon>assignment_turned_in</v-icon>
                                                </v-btn>
                                                <v-btn color="blue" v-show="history.certificado == 'Si'" dark
                                                    @click="certificadoCOVID(history.orden_id)">
                                                    Certificado Asilamiento COVID
                                                    <v-icon>assignment_turned_in</v-icon>
                                                </v-btn>
                                                <v-btn color="blue" dark v-if="mostrarBotonConsentimientos"
                                                    v-show="tieneConsentimiento == true"
                                                    @click="printConsentimientosInformados()">
                                                    Consentimientos Informados
                                                    <v-icon>assignment_turned_in</v-icon>
                                                </v-btn>
                                                <v-btn color="red lighten-2" dark @click="abrirHc()">
                                                    Ver Detalle Historia
                                                    <v-icon>visibility</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Historial</span>
                                        </v-tooltip>
                                    </v-card-actions>
                                </v-flex>
                            </v-layout>
                        </v-card>
                    </v-dialog>
                </v-layout>
                <v-layout row>
                    <v-flex xs12 md12 lg12>
                        <v-data-table :headers="header" :items="historiapaciente" item-key="index" v-if="dialog_timeline">
                            <template v-slot:items="props">
                                <td class="text-xs-center">{{ props.item.Datetimeingreso }}</td>
                                <!--                                <td class="text-xs-center">{{ props.item.Cédula}}</td>-->
                                <td class="text-xs-center">{{ props.item.Nombre }}</td>
                                <td class="text-xs-center"
                                    v-if="props.item.Destinopaciente == 'Hospitalización (Remision)'">
                                    <v-chip color="red" text-color="white">{{ props.item.Destinopaciente }}</v-chip>
                                </td>
                                <td class="text-xs-center"
                                    v-if="props.item.Destinopaciente == 'Contrarreferencia (Anexo 10)'">
                                    <v-chip color="success" text-color="white">{{ props.item.Destinopaciente }}</v-chip>
                                </td>
                                <td class="text-xs-center"
                                    v-if="props.item.Destinopaciente != 'Hospitalización (Remision)' & props.item.Destinopaciente != 'Contrarreferencia (Anexo 10)'">
                                    <v-chip color="info" text-color="white">{{ props.item.Destinopaciente }}</v-chip>
                                </td>
                                <!--                                <td class="text-xs-center">{{ props.item.Edad}}</td>-->
                                <!--                                <td class="text-xs-center">{{ props.item.Sexo}}</td>-->
                                <td class="text-xs-center">{{ props.item.Atendido_Por }}</td>
                                <td class="text-xs-center">{{ props.item.Especialidad }}</td>
                                <td class="text-xs-center">{{ props.item.Tipocita }}</td>
                                <td class="text-xs-center" v-if="props.item.Nombre_Adjunto == null">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn fab outline color="warning" small v-on="on"
                                                @click="abrirModal(props.item)">
                                                <v-icon>book</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Mas Acciones</span>
                                    </v-tooltip>
                                </td>
                                <td class="text-xs-center" v-if="props.item.Nombre_Adjunto">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn fab outline color="primary" small v-on="on"
                                                @click="consultarAdjunto(props.item.Ruta_Adjunto)">
                                                <v-icon>mdi-cloud-upload</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Ver anexo</span>
                                    </v-tooltip>
                                </td>
                            </template>
                        </v-data-table>
                    </v-flex>
                </v-layout>
                <template>
                    <div class="text-center">
                        <v-dialog v-model="preload_historico" persistent width="300">
                            <v-card color="primary" dark>
                                <v-card-text>
                                    Tranquilo procesamos tu solicitud !
                                    <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                                </v-card-text>
                            </v-card>
                        </v-dialog>
                    </div>
                </template>
            </v-card>
        </v-flex>

        <v-dialog v-model="dialogoContingencia" persistent max-width="850">
            <v-card>
                <CargueHistoriaClinicaComponent />
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="error" dark @click="dialogoContingencia = false">Cerrar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
import {
    AdjuntoService
} from '../../../services';
import {
    mapGetters
} from 'vuex';
import CargueHistoriaClinicaComponent from '../../../components/cargueHistoriaClinica/CargueHistoriaClinicaComponent.vue'
export default {
    components: {
        CargueHistoriaClinicaComponent
    },
    data: () => ({
        dialogDetalle: false,
        preload: false,
        on: '',
        dialogHC: false,
        dialogoContingencia: false,
        mostrarBoton: false,
        mostrarBotonConsentimientos: true,
        dialogosCerrados: {},
        comp: '',
        datosCita: {},
        especialidades: [],
        nota: {
            aclaratoria: null,
        },
        especialidad: null,
        preload_historico: false,
        tieneConsentimiento: false,
        dataHistoria: {},
        historiapaciente: [],
        historiaContingencia: [],
        notaRealizada: [],
        procedimientos: [],
        historia: {},
        history: {},
        cedula: "",
        desde: null,
        hasta: null,
        input: null,
        dialog: false,
        dialog_timeline: false,
        nonce: 0,
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
        header: [{
            text: 'F. Atención',
            align: 'center',
            value: 'Datetimeingreso',
            sortable: false
        },
        // {
        //     text: 'Cédula',
        //     sortable: false,
        //     align: 'center'
        // },
        {
            text: 'Nombre',
            sortable: false,
            align: 'center'
        },
        {
            text: 'Destino del Paciente',
            sortable: false,
            align: 'center'
        },
        // {
        //     text: 'Edad',
        //     sortable: false,
        //     align: 'center'
        // },
        // {
        //     text: 'Sexo',
        //     sortable: false,
        //     align: 'center'
        // },
        {
            text: 'Atendido por',
            sortable: false,
            align: 'center'
        },
        {
            text: 'Especialidad',
            sortable: false,
            align: 'center'
        },
        {
            text: 'Tipo',
            sortable: false,
            align: 'center'
        },
        {
            text: 'Ver Más..',
            sortable: false,
            align: 'center'
        }
        ],
        Diagnosticos: [],
        cedulasMedimas: [
            '98619467',
            '98626145',
            '98627091',
            '98627641',
            '98630044',
            '98630429'
        ]
    }),
    created() {
        this.getUserInfo();
        this.fetchEspecialidad();
    },
    computed: {
        ...mapGetters(['can']),
    },
    methods: {

        clearFields() {
            this.cedula = '';
            this.historiapaciente = [];
        },
        comment() {
            const time = new Date().toTimeString();
            this.events.push({
                id: this.nonce++,
                text: this.input,
                time: time.replace(
                    /:\d{2}\sGMT-\d{4}\s\((.*)\)/,
                    (match, contents, offset) => {
                        return ` ${contents
                            .split(" ")
                            .map(v => v.charAt(0))
                            .join("")}`;
                    }
                )
            });
            this.input = null;
        },

        abrirModal(historia) {
            this.consularOrdens(historia.id);
            this.dataHistoria = historia;
            this.revisarNota(historia.id);
            this.fillHistory(historia);

            const idDialogo = historia.id;

            if (this.dialogosCerrados[idDialogo]) {
                this.mostrarBotonConsentimientos = false;
            } else {
                this.mostrarBotonConsentimientos = true;
            }

            this.dialog = true;
            this.mostrarBoton = true;
        },

        cerrarModal() {
            const idDialogoCerrado = this.dataHistoria.id;

            this.dialog = false;
            this.observaciones = "";
            this.mostrarBoton = false;

            // Marcar este diálogo como cerrado
            if (!this.dialogosCerrados[idDialogoCerrado]) {
                this.$set(this.dialogosCerrados, idDialogoCerrado, true);
            }
        },

        fillHistory(historia) {
            if (historia.id) {
                this.history = this.getHistoryObj(historia);
            }
        },
        getHistoryObj(historia) {
            return {
                nombre: historia.Nombre,
                edad: historia.Edad,
                sexo: historia.Sexo,
                antropometricas: historia.Antropometricas,
                atendido_por: historia.Atendido_Por,
                registromedico: historia.Registromedico,
                especialidad: historia.Especialidad,
                cardiovascular: historia.Cardiovascular,
                celular: historia.Celular,
                condiciongeneral: historia.Condiciongeneral,
                cedula: historia.Cédula,
                datetimeingreso: historia.Datetimeingreso,
                datetimesalida: historia.Datetimesalida,
                departamento: historia.Departamento,
                destinopaciente: historia.Destinopaciente,
                diagnosticoprincipal: historia.Diagnostico_Principal,
                Diagnostico_Secundario: historia.Diagnostico_Secundario,
                direccionresidencia: historia.Direccion_Residencia,
                endocrinologico: historia.Endocrinologico,
                enfermedadactual: historia.Enfermedadactual,
                entidademite: historia.Entidademite,
                indicacion: historia.Indicacion,
                hallazgos: historia.Hallazgos,
                conclusion: historia.Conclusion,
                notaclaratoria: historia.Notaclaratoria,
                cups: historia.Cups,
                certificado: historia.certificado,
                radiologo: historia.Radiologo,
                firmaradiologo: historia.Firmaradiologo,
                fechanacimiento: historia.Fecha_Nacimiento,
                fechasolicita: historia.Fecha_solicita,
                Finalidad: historia.Finalidad,
                finalidadTrans: historia.finalidadTrans,
                gastrointestinal: historia.Gastrointestinal,
                genitourinario: historia.Genitourinario,
                laboraen: historia.Laboraen,
                linfatico: historia.Linfatico,
                medicoordeno: historia.Medicoordeno,
                motivoconsulta: historia.Motivoconsulta,
                observaciones: historia.Observaciones,
                municipioafiliado: historia.Municipio_Afiliado,
                neurologico: historia.Neurologico,
                norefiere: historia.Norefiere,
                observaciones: historia.Observaciones,
                oftalmologico: historia.Oftalmologico,
                osteomioarticular: historia.Osteomioarticular,
                osteomuscular: historia.Osteomuscular,
                otorrinoralingologico: historia.Otorrinoralingologico,
                otrossignosvitales: historia.Otros_Signos_Vitales,
                planmanejo: historia.Planmanejo,
                presionarterial: historia.Presión_Arterial,
                recomendaciones: historia.Recomendaciones,
                respiratorio: historia.Respiratorio,
                resultayudadiagnostica: historia.Resultayudadiagnostica,
                Tipocita_id: historia.Tipocita_id,
                Tipocita: historia.Tipocita,
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
                signosvitales: historia.Signos_Vitales,
                tegumentario: historia.Tegumentario,
                telefono: historia.Telefono,
                timeconsulta: historia.Timeconsulta,
                tipoafiliado: historia.Tipo_Afiliado,
                tipoorden: historia.Tipo_Orden,
                tipodiagnostico: historia.Tipodiagnostico,
                antecedentes: historia.Antecedentes,
                abdomen: historia.Abdomen,
                agudezavisual: historia.Agudezavisual,
                cabezacuello: historia.Cabezacuello,
                cardiopulmonar: historia.Cardiopulmonar,
                examenmama: historia.Examenmama,
                examenmental: historia.Examenmental,
                extremidades: historia.Extremidades,
                frecucardiaca: historia.Frecucardiaca,
                frecurespiratoria: historia.Frecurespiratoria,
                genitourinario: historia.Genitourinario,
                neurologico: historia.Neurologico,
                ojosfondojos: historia.Ojosfondojos,
                osteomuscular: historia.Osteomuscular,
                pielfraneras: historia.Pielfraneras,
                reflejososteotendinos: historia.Reflejososteotendinos,
                tactoretal: historia.Tactoretal,
                dietasaludable: historia.Dietasaludable,
                suenoreparador: historia.Suenoreparador,
                duermemenoseishoras: historia.Duermemenoseishoras,
                altonivelestres: historia.Altonivelestres,
                actividadfisica: historia.Actividadfisica,
                frecuenciasemana: historia.Frecuenciasemana,
                duracion: historia.Duracion,
                fumacantidad: historia.Fumacantidad,
                fumainicio: historia.Fumainicio,
                fumadorpasivo: historia.Fumadorpasivo,
                cantidadlicor: historia.Cantidadlicor,
                licorfrecuencia: historia.Licorfrecuencia,
                consumopsicoactivo: historia.Consumopsicoactivo,
                psicoactivocantidad: historia.Psicoactivocantidad,
                estilovidaobservaciones: historia.Estilovidaobservaciones,
                cedulamedico: historia.Cedulamedico,
                firma: historia.Firma,
                fechaultimamenstruacion: historia.Fechaultimamenstruacion,
                marcapaciente: historia.marcapaciente,
                orden_id: historia.orden_id,
                id: historia.id
            };
        },
        fillDiagnostic(autorizacion) {
            axios
                .get("/api/cie10/diagnostico/" + autorizacion.citaPaciente_id)
                .then(res => {
                    this.Diagnostico = res.data;
                });
        },
        printhc() {
            if (this.history) {
                let pdf = [];
                pdf = this.dataHistoria;
                pdf.type = "historia";
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
        getPDFHistorial(historia) {
            return {
                type: "test",
                FechaInicio: historia.fechasolicita,
                Nombre: historia.nombre,
                Edad_Cumplida: historia.edad,
                Sexo: historia.sexo,
                Antropometricas: historia.antropometricas,
                Atendido_Por: historia.atendido_por,
                Especialidad: historia.especialidad,
                Registromedico: historia.registromedico,
                Cardiovascular: historia.cardiovascular,
                telefono: historia.celular,
                Condiciongeneral: historia.condiciongeneral,
                Cédula: historia.cedula,
                Datetimeingreso: historia.datetimeingreso,
                Datetimesalida: historia.datetimesalida,
                Departamento: historia.departamento,
                Destinopaciente: historia.destinopaciente,
                Diagnostico_Principal: historia.diagnosticoprincipal,
                Diagnostico_Secundario: historia.Diagnostico_Secundario,
                Direccion_Residencia: historia.direccion_Residencia,
                Edad: historia.edad,
                Endocrinologico: historia.endocrinologico,
                Enfermedadactual: historia.enfermedadactual,
                Entidademite: historia.entidademite,
                Fecha_Nacimiento: historia.fechanacimiento,
                Fecha_solicita: historia.fecha_solicita,
                Finalidad: historia.Finalidad,
                finalidadTrans: historia.finalidadTrans,
                Gastrointestinal: historia.gastrointestinal,
                Genitourinario: historia.genitourinario,
                Laboraen: historia.laboraen,
                Indicacion: historia.indicacion,
                Hallazgos: historia.hallazgos,
                Conclusion: historia.conclusion,
                Notaclaratoria: historia.notaclaratoria,
                Cups: historia.cups,
                Radiologo: historia.radiologo,
                Regradiologo: historia.regradiologo,
                Firmaradiologo: historia.firmaradiologo,
                Linfatico: historia.linfatico,
                Medicoordeno: historia.medicoordeno,
                Motivoconsulta: historia.motivoconsulta,
                Observaciones: historia.observaciones,
                Municipio_Afiliado: historia.municipio_Afiliado,
                Neurologico: historia.neurologico,
                Nombre: historia.nombre,
                Norefiere: historia.norefiere,
                Oftalmologico: historia.oftalmologico,
                Osteomioarticular: historia.osteomioarticular,
                Osteomuscular: historia.osteomuscular,
                Otorrinoralingologico: historia.otorrinoralingologico,
                Otros_Signos_Vitales: historia.otrossignosvitales,
                Planmanejo: historia.planmanejo,
                Presión_Arterial: historia.presionarterial,
                Recomendaciones: historia.recomendaciones,
                Respiratorio: historia.respiratorio,
                Resultayudadiagnostica: historia.resultayudadiagnostica,
                tratamientoncologico: historia.tratamientoncologico,
                Tipocita_id: historia.Tipocita_id,
                Tipocita: historia.Tipocita,
                cirujiaoncologica: historia.cirujiaoncologica,
                ncirujias: historia.ncirujias,
                Dukes: historia.dukes,
                gleason: historia.gleason,
                Her2: historia.her2,
                tumorsegunbiopsia: historia.tumorsegunbiopsia,
                estadificacioninicial: historia.estadificacioninicial,
                fechaestadificacion: historia.fechaestadificacion,
                flaboratoriopatologia: historia.flaboratoriopatologia,
                fdxcanceractual: historia.fdxcanceractual,
                Patologiacancelactual: historia.patologiacancelactual,
                iniciocirujia: historia.iniciocirujia,
                fincirujia: historia.fincirujia,
                recibioradioterapia: historia.recibioradioterapia,
                inicioradioterapia: historia.inicioradioterapia,
                finradioterapia: historia.finradioterapia,
                nsesiones: historia.nsesiones,
                intencion: historia.intencion,
                Sexo: historia.sexo,
                Signos_Vitales: historia.signosvitales,
                Tegumentario: historia.tegumentario,
                Telefono: historia.telefono,
                Timeconsulta: historia.timeconsulta,
                Tipo_Afiliado: historia.tipoafiliado,
                Tipo_Orden: historia.tipo_Orden,
                Tipodiagnostico: historia.tipodiagnostico,
                IPS: historia.ips,
                Antecedentes: historia.antecedentes,
                Abdomen: historia.abdomen,
                Agudezavisual: historia.agudezavisual,
                Cabezacuello: historia.cabezacuello,
                Cardiopulmonar: historia.cardiopulmonar,
                Examenmama: historia.examenmama,
                Examenmental: historia.examenmental,
                Extremidades: historia.extremidades,
                Frecucardiaca: historia.frecucardiaca,
                Frecurespiratoria: historia.frecurespiratoria,
                Genitourinario: historia.genitourinario,
                Pulsosperifericos: historia.pulsosperifericos,
                Ojosfondojos: historia.ojosfondojos,
                Osteomuscular: historia.osteomuscular,
                Pielfraneras: historia.pielfraneras,
                Reflejososteotendinos: historia.reflejososteotendinos,
                Tactoretal: historia.tactoretal,
                Dietasaludable: historia.dietasaludable,
                Suenoreparador: historia.suenoreparador,
                Duermemenoseishoras: historia.duermemenoseishoras,
                Altonivelestres: historia.altonivelestres,
                Actividadfisica: historia.actividadfisica,
                Frecuenciasemana: historia.frecuenciasemana,
                Duracion: historia.duracion,
                Fumacantidad: historia.fumacantidad,
                Fumainicio: historia.fumainicio,
                Fumadorpasivo: historia.fumadorpasivo,
                Cantidadlicor: historia.cantidadlicor,
                Licorfrecuencia: historia.licorfrecuencia,
                Consumopsicoactivo: historia.consumopsicoactivo,
                Psicoactivocantidad: historia.psicoactivocantidad,
                Estilovidaobservaciones: historia.estilovidaobservaciones,
                Cedulamedico: historia.cedulamedico,
                Firma: historia.firma,
                Fechaultimamenstruacion: historia.fechaultimamenstruacion,
                marcapaciente: historia.marcapaciente,
                id: historia.id
            };
        },
        find() {
            if (!this.cedula) {
                swal({
                    title: "Debe ingresar un cédula",
                    icon: "warning"
                });
                return;
            }
            this.preload_historico = true;
            axios
                .post("/api/historiapaciente/gethistoria", {
                    Num_Doc: this.cedula
                })
                .then(res => {
                    this.preload_historico = false;
                    const response = res.data
                    if (this.logueado.id == 1276 || this.logueado.id == 1802) {
                        this.historiapaciente = response.filter(item => item.Especialidad == "OPTOMETRIA" ||
                            item.Especialidad == "OFTALMOLOGIA ");
                    } else {
                        this.historiapaciente = response
                    }
                    this.dialog_timeline = true;
                });
        },
        async allHistorias() {
            this.preload = true;
            if ((!this.desde && this.hasta) || (this.desde && !this.hasta)) {
                this.$alerError("Las fechas son requeridas");
                return;
            }
            if (this.desde > this.hasta) {
                this.$alerError("Las fechas Desde no puede ser mayor a la fecha Hasta");
                return;
            }
            const filters = {
                cedula: this.cedula,
                desde: this.desde,
                hasta: this.hasta
            };
            const historias = await axios.post('/api/orden/historias/consolidados', filters);
            let pdf = {}
            pdf.type = 'historiaMasivo';
            pdf.historias = historias.data;
            await this.imprimirConsolidado(pdf);
        },
        imprimirConsolidado(pdf) {
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
                    this.preload = false;
                }).catch(e => {
                    this.preload = false;

                });
        },
        fetchEspecialidad() {
            axios.get('/api/especialidad/all')
                .then(res => {
                    this.especialidades = res.data
                });
        },
        masivoHistorias() {
            let pdf = {
                type: "historiaMasivo"
            };
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
        guardarNota(id) {
            preload_historico: true,
                this.nota.id = id
            axios.post('/api/antecedente/notaAclaratoria/', this.nota)
                .then(res => {
                    this.$alerSuccess("La Nota Aclaratoria se a Guardado con exito!");
                    this.preload_historico = false;
                    this.clearNota();
                    this.revisarNota(this.nota.id)
                }).catch(er => {
                    this.preload_historico = false;
                    this.$alerError("Error al guardar!");
                });
        },
        revisarNota(id) {
            axios.get('/api/antecedente/revisarNota/' + id)
                .then(res => {
                    this.notaRealizada = res.data;
                });
        },
        clearNota() {
            this.nota.aclaratoria = null;
        },
        async Anexo(id, tipo) {
            const pdf = {
                type: 'Anexo',
                id: id,
                tipos: tipo,
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

        imprimirhc() {
            let pdf = [];
            pdf = this.dataHistoria;
            pdf.cita_paciente_id = this.dataHistoria.id;
            pdf.type = "historiaintegral";
            axios
                .post("/api/historia/imprimirhc", pdf, {
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
        enlaseDinamica() {
            window.open("http://192.168.0.30:8887", "ventana1", "width=800,height=800,scrollbars=NO");
        },

        resultadoProdiagnostico() {
            window.open("https://prodiagnostico.hiruko.com.co/login", "ventana1",
                "width=800,height=800,scrollbars=NO");
        },

        resultadoSanMarcos() {
            window.open("http://200.122.220.78:9098/resultados/#nbb", "ventana1",
                "width=800,height=800,scrollbars=NO");
        },

        getUserInfo() {
            axios.get("/api/auth/user")
                .then(res => {
                    this.logueado = res.data.user;
                })
                .catch(res => { });
        },

        certificadoCOVID(orden) {
            const pdf = {
                type: 'recomendacionOrden',
                id: orden,
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

        consularOrdens(id) {
            axios.get('/api/orden/consularOrdenes/' + id)
                .then(res => {
                    this.procedimientos = res.data;
                    console.log(this.procedimientos);
                    if (this.procedimientos.length > 0) {
                        this.tieneConsentimiento = true
                    }
                });
        },

        printConsentimientosInformados() {
            this.procedimientos.forEach((ser) => {
                axios.post('/api/orden/verificacionConsentimiento', ser).then((res) => {
                    if (res.data.consentimientoInformado == true) {
                        let pdf = {
                            type: "concentimiento",
                            id: res.data.id,
                            paciente_id: parseInt(this.dataHistoria.Paciente_id),
                            cita_paciente_id: parseInt(this.dataHistoria.id),
                        };
                        axios.post("/api/pdf/print-pdf", pdf, {
                            responseType: "arraybuffer",
                        }).then((res) => {
                            let blob = new Blob([res.data], {
                                type: "application/pdf",
                            });
                            let link = document.createElement("a");
                            link.href = window.URL.createObjectURL(blob);
                            window.open(link.href, "_blank");
                        });
                    }
                });
            })

        },

        cargarHistoria() {
            this.dialogoContingencia = true
        },

        async consultarAdjunto(ruta) {
            this.preload = true
            try {
                let adj = await AdjuntoService.get(ruta);
                let blob = new Blob([adj[1]], {
                    type: adj[0]
                });
                let link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                window.open(link.href, "_blank");
                this.preload = false
            } catch (error) {
                this.preload = false
            }
        },

    }

};

</script>

<style lang="scss" scoped></style>
