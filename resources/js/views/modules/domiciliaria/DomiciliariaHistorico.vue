<template>
    <v-container pa-0>
        <v-layout row wrap>
            <v-flex xs6 md3 lg3>
                <v-text-field v-model="cedula" type="number" label="Cédula" outlined></v-text-field>
            </v-flex>
            <v-spacer></v-spacer>
            <v-flex xs6 md3 lg3>
                <v-btn color="primary" round @click="find()">Buscar</v-btn>
            </v-flex>
        </v-layout>
        <v-layout row>
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
                                            <v-flex xs6 md3 lg2>
                                                <v-text-field v-model="history.edad" label="edad" outlined readonly>
                                                </v-text-field>
                                            </v-flex>
                                            <v-flex xs6 md3 lg2>
                                                <v-text-field v-model="history.sexo" label="sexo" outlined readonly>
                                                </v-text-field>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout wrap align-center>
                                            <v-flex xs6 md3 lg2>
                                                <v-text-field v-model="history.tipo_afiliado" label="Tipo Afiliado"
                                                              outlined readonly></v-text-field>
                                            </v-flex>
                                            <v-flex xs6 md3 lg3>
                                                <v-text-field v-model="history.departamento" label="Departamento"
                                                              outlined readonly></v-text-field>
                                            </v-flex>
                                            <v-flex xs6 md3 lg2>
                                                <v-text-field v-model="history.municipio_afiliado"
                                                              label="Municipio Afiliado" outlined readonly></v-text-field>
                                            </v-flex>
                                            <v-flex xs6 md3 lg3>
                                                <v-text-field v-model="history.direccion" label="Direccion Residencia"
                                                              outlined readonly></v-text-field>
                                            </v-flex>
                                            <v-flex xs6 md3 lg2>
                                                <v-text-field v-model="history.telefono" label="Telefono" outlined
                                                              readonly></v-text-field>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout wrap align-center>
                                            <v-flex xs6 md3 lg2>
                                                <v-text-field v-model="history.celular" label="Celular" outlined
                                                              readonly></v-text-field>
                                            </v-flex>
                                            <v-flex xs6 md3 lg3>
                                                <v-text-field v-model="history.fecha_solicita" label="Fecha Solicita"
                                                              outlined readonly></v-text-field>
                                            </v-flex>
                                            <v-flex xs6 md3 lg2>
                                                <v-text-field v-model="history.municipio_afiliado"
                                                              label="Municipio Afiliado" outlined readonly></v-text-field>
                                            </v-flex>
                                            <v-flex xs6 md3 lg3>
                                                <v-text-field v-model="history.motivoconsulta" label="Motivoconsulta"
                                                              outlined readonly></v-text-field>
                                            </v-flex>
                                            <v-flex xs6 md3 lg2>
                                                <v-text-field v-model="history.enfermedadactual"
                                                              label="Enfermedad Actual" outlined readonly></v-text-field>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout wrap align-center>
                                            <v-flex xs6 md3 lg2>
                                                <v-text-field v-model="history.resultayudadiagnostica"
                                                              label="Resulta Ayuda Diagnostica" outlined readonly></v-text-field>
                                            </v-flex>
                                            <v-flex xs6 md3 lg3>
                                                <v-text-field v-model="history.observaciones" label="Observaciones"
                                                              outlined readonly></v-text-field>
                                            </v-flex>
                                            <v-flex xs6 md3 lg2>
                                                <v-text-field v-model="history.norefiere" label="No Refiere" outlined
                                                              readonly></v-text-field>
                                            </v-flex>
                                            <v-flex xs6 md3 lg3>
                                                <v-text-field v-model="history.timeconsulta" label="Tiempo Consulta"
                                                              outlined readonly></v-text-field>
                                            </v-flex>
                                            <v-flex xs6 md3 lg2>
                                                <v-text-field v-model="history.medicoordeno" label="Medico Ordeno"
                                                              outlined readonly></v-text-field>
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
                                              item-key="index" :items-per-page="10" hide-actions expand>
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
                                                <div class="datatable-cell-wrapper">{{ Diagnostico.item.Cie10_id }}
                                                </div>
                                            </td>
                                            <td class="text-xs-center">
                                                <div class="datatable-cell-wrapper">
                                                    {{ Diagnostico.item.Tipodiagnostico }}</div>
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
                                    <v-alert v-slot:no-results :value="true" color="error" icon="warning">Your search
                                        for "{{ search }}" found no results.</v-alert>
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
                        <v-flex xs6 md1 lg1>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" flat @click="cerrarModal()">Cerrar</v-btn>
                            </v-card-actions>
                        </v-flex>
                        <v-flex xs6 md1 lg1>
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
        </v-layout>
        <v-layout row>
            <v-flex xs12 md12 lg12>

                <v-data-table :headers="header" :items="historiapaciente" item-key="index" v-if="dialog_timeline" >
                    <template v-slot:items="props">
                        <td class="text-xs-center">{{ props.item.Datetimeingreso}}</td>
                        <td class="text-xs-center">{{ props.item.Cédula}}</td>
                        <td class="text-xs-center">{{ props.item.Nombre}}</td>
                        <td class="text-xs-center">{{ props.item.Fecha_Nacimiento}}</td>
                        <td class="text-xs-center">{{ props.item.Edad}}</td>
                        <td class="text-xs-center">{{ props.item.Sexo}}</td>
                        <td class="text-xs-center">{{ props.item.Atendido_Por}}</td>
                        <td class="text-xs-center">{{ props.item.Especialidad}}</td>
                        <td class="text-xs-center">{{props.item.Tipocita}}</td>
                        <td class="text-xs-center">
                            <v-tooltip top>
                                <template v-slot:activator="{ on }">
                                    <v-btn fab outline color="warning" small v-on="on"
                                           @click="abrirModal(props.item)">
                                        <v-icon>book</v-icon>
                                    </v-btn>
                                </template>
                                <span>Ver Historia</span>
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
    </v-container>
</template>

<script>
    export default {
        data: () => ({
            preload_historico: false,
            historiapaciente: [],
            historia: {},
            history: {},
            cedula: "",
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
            header: [
                {
                    text: 'F. Atención',
                    align: 'center',
                    value: 'Datetimeingreso',
                    sortable: false
                },
                {
                    text: 'Cédula',
                    sortable: false,
                    align: 'center'
                },
                {
                    text: 'Nombre',
                    sortable: false,
                    align: 'center'
                },
                {
                    text: 'Fecha Nacimiento',
                    sortable: false,
                    align: 'center'
                },
                {
                    text: 'Edad',
                    sortable: false,
                    align: 'center'
                },
                {
                    text: 'Sexo',
                    sortable: false,
                    align: 'center'
                },
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
                },
            ],
            Diagnosticos: []
        }),
        created() {
            // this.resumenhistoria();
        },

        methods: {
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
                this.fillHistory(historia);
                //   this.fillDiagnostic(autorizacion);
                this.dialog = true;
            },
            cerrarModal() {
                this.dialog = false;
                //   this.estadoElegido = "";
                this.observaciones = "";
            },
            fillHistory(historia) {
                console.log("his", historia)
                if (historia.id) {
                    this.history = this.getHistoryObj(historia);
                    console.log("history", this.history);
                }
            },
            getHistoryObj(historia) {
                return {
                    nombre: historia.Nombre,
                    edad: historia.Edad,
                    sexo: historia.Sexo,
                    antropometricas: historia.Antropometricas,
                    atendido_por: historia.Atendido_Por,
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
                    tratamientoncologico: historia.tratamientoncologico,
                    Tipocita_id: historia.Tipocita_id,
                    Tipocita: historia.Tipocita,
                    cirujiaoncologica: historia.cirujiaoncologica,
                    ncirujias: historia.ncirujias,
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
                    registromedico: historia.Registromedico,
                    firma: historia.Firma,
                    fechaultimamenstruacion: historia.Fechaultimamenstruacion,
                    marcapaciente: historia.marcapaciente,
                    especialidad: historia.Especialidad,
                    id: historia.id
                };
            },
            fillDiagnostic(autorizacion) {
                axios
                    .get("/api/cie10/diagnostico/" + autorizacion.citaPaciente_id)
                    .then(res => {
                        this.Diagnostico = res.data;
                        console.log("Diagnostico", this.Diagnostico);
                    });
            },
            printhc() {
                if (this.history) {
                    console.log("hi", this.history)
                    let pdf = this.getPDFHistorial(this.history);
                    pdf.type = "test";
                    console.log("pdf", pdf);
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
                console.log("PDF", historia);
                return {
                    type: "test",
                    FechaInicio: historia.fechasolicita,
                    Nombre: historia.nombre,
                    Edad_Cumplida: historia.edad,
                    Sexo: historia.sexo,
                    Antropometricas: historia.antropometricas,
                    Atendido_Por: historia.atendido_por,
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
                    IPS: historia.IPS,
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
                    Registromedico: historia.registromedico,
                    Firma: historia.firma,
                    Fechaultimamenstruacion: historia.fechaultimamenstruacion,
                    marcapaciente: historia.marcapaciente,
                    Especialidad: historia.especialidad,
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
                    .post("/api/historiapaciente/getdomiciliaria", {
                        Num_Doc: this.cedula
                    })
                    .then(res => {
                        this.preload_historico = false;
                        this.historiapaciente = res.data;
                        console.log('datosPerra', this.historiapaciente);
                        this.dialog_timeline = true;
                    });
            }
        }
    };

</script>

<style lang="scss" scoped>
</style>
