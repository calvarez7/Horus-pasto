<template>
    <v-layout>
        <v-flex xs12>
            <v-card>
                <v-form @submit.prevent="find()">
                    <v-layout row wrap>
                        <v-flex xs12 md12 lg12>
                            <v-card>
                                <v-card-title class="headline success" style="color:white">
                                    <span class="title layout justify-center font-weight-light">Certificado Ocupacional</span>
                                </v-card-title>
                                <v-container>
                                    <v-layout row wrap>
                                        <v-flex xs3 sm>
                                            <v-text-field v-model="cedula" type="number" label="Cédula"></v-text-field>
                                        </v-flex>
                                        <v-spacer></v-spacer>
                                        <v-flex xs9 sm>
                                            <v-btn color="primary" round @click="find()">Buscar</v-btn>
                                            <v-btn @click="clearFields()" v-if="cedula" fab outline small color="error">
                                                <v-icon>clear</v-icon>
                                            </v-btn>
                                        </v-flex>
                                    </v-layout>
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
                                    <v-icon left>mdi-account</v-icon>Información del paciente
                                </v-tab>
                                <v-tab>
                                    <v-icon left>list_alt</v-icon>Diagnostico
                                </v-tab>

                                <v-tab-item>
                                    <v-flex>
                                        <v-card flat>
                                            <v-card-title class="headline grey lighten-2">Datos del paciente
                                            </v-card-title>
                                            <v-container>
                                                <v-layout wrap align-center>
                                                    <v-flex xs6 md3 lg4>
                                                        <v-text-field v-model="history.nombre" label="Nombre" outlined
                                                            readonly>
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 md3 lg2>
                                                        <v-text-field v-model="history.cedula" label="Cédula" outlined
                                                            readonly>
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 md3 lg2>
                                                        <v-text-field v-model="history.ips" label="IPS" outlined
                                                            readonly>
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 md3 lg2>
                                                        <v-text-field v-model="history.edad" label="edad" outlined
                                                            readonly>
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 md3 lg2>
                                                        <v-text-field v-model="history.sexo" label="sexo" outlined
                                                            readonly>
                                                        </v-text-field>
                                                    </v-flex>
                                                </v-layout>
                                                <v-layout wrap align-center>
                                                    <v-flex xs6 md3 lg2>
                                                        <v-text-field v-model="history.tipo_afiliado"
                                                            label="Tipo Afiliado" outlined readonly></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 md3 lg3>
                                                        <v-text-field v-model="history.departamento"
                                                            label="Departamento" outlined readonly></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 md3 lg2>
                                                        <v-text-field v-model="history.municipio_afiliado"
                                                            label="Municipio Afiliado" outlined readonly></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 md3 lg3>
                                                        <v-text-field v-model="history.direccion"
                                                            label="Direccion Residencia" outlined readonly>
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 md3 lg2>
                                                        <v-text-field v-model="history.telefono" label="Telefono"
                                                            outlined readonly></v-text-field>
                                                    </v-flex>
                                                </v-layout>
                                                <v-layout wrap align-center>
                                                    <v-flex xs6 md3 lg2>
                                                        <v-text-field v-model="history.celular" label="Celular" outlined
                                                            readonly></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 md3 lg3>
                                                        <v-text-field v-model="history.fecha_solicita"
                                                            label="Fecha Solicita" outlined readonly></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 md3 lg2>
                                                        <v-text-field v-model="history.municipio_afiliado"
                                                            label="Municipio Afiliado" outlined readonly></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 md3 lg3>
                                                        <v-text-field v-model="history.motivoconsulta"
                                                            label="Motivoconsulta" outlined readonly></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 md3 lg2>
                                                        <v-text-field v-model="history.enfermedadactual"
                                                            label="Enfermedad Actual" outlined readonly></v-text-field>
                                                    </v-flex>
                                                </v-layout>
                                                <v-layout wrap align-center>
                                                    <v-flex xs6 md3 lg2>
                                                        <v-text-field v-model="history.resultayudadiagnostica"
                                                            label="Resulta Ayuda Diagnostica" outlined readonly>
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 md3 lg3>
                                                        <v-text-field v-model="history.observaciones"
                                                            label="Observaciones" outlined readonly></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 md3 lg2>
                                                        <v-text-field v-model="history.norefiere" label="No Refiere"
                                                            outlined readonly></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 md3 lg3>
                                                        <v-text-field v-model="history.timeconsulta"
                                                            label="Tiempo Consulta" outlined readonly></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 md3 lg2>
                                                        <v-text-field v-model="history.medicoordeno"
                                                            label="Medico Ordeno" outlined readonly></v-text-field>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-card>
                                    </v-flex>
                                </v-tab-item>
                                <v-tab-item>
                                    <v-card flat>
                                        <v-card-title class="headline grey lighten-2">Diagnosticos</v-card-title>
                                        <v-data-table class="fb-table-elem" :headers="diagnosticHeaders"
                                            :items="Diagnosticos" item-key="index" :items-per-page="10" hide-actions
                                            expand>
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
                        <v-data-table :headers="header" :items="certificado" item-key="index"
                            v-if="dialog_timeline">
                            <template v-slot:items="props">
                                <td>{{ props.item.Num_Doc}}</td>
                                <td>{{ props.item.Primer_Nom}} {{ props.item.SegundoNom}} {{ props.item.Primer_Ape}} {{ props.item.Segundo_Ape}}</td>
                                <td>{{ props.item.Edad_Cumplida}}</td>
                                <td>{{ props.item.Sexo}}</td>
                                <td>{{ props.item.salud_ocupacional}}</td>
                                <td>{{ props.item.Datetimeingreso}}</td>
                                <td v-if="props.item.salud_ocupacional == 'Psicologia'">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn v-if="can('Imprimir.Psicologia')" fab outline color="warning" small v-on="on"
                                                @click="printhc(props.item)">
                                                <v-icon>book</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Ver Historia</span>
                                    </v-tooltip>
                                </td>
                                <td v-if="props.item.salud_ocupacional == 'Consulta Medica'">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn v-if="can('Imprimir.ConsultaMedica')" fab outline color="warning" small v-on="on"
                                                   @click="printhc(props.item)">
                                                <v-icon>book</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Ver Historia</span>
                                    </v-tooltip>
                                </td>
                                <td v-if="props.item.salud_ocupacional == 'Visiometria'">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn v-if="can('Imprimir.Visiometria')" fab outline color="warning" small v-on="on"
                                                   @click="printhc(props.item)">
                                                <v-icon>book</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Ver Historia</span>
                                    </v-tooltip>
                                </td>
                                 <td v-if="props.item.salud_ocupacional == 'Voz'">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn v-if="can('Imprimir.Visiometria')" fab outline color="warning" small v-on="on"
                                                   @click="printhc(props.item)">
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
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        data: () => ({
            preload_historico: false,
            dataHistoria:{},
            certificado: [],
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
                {text: 'Num_Doc', value: 'Num_Doc'},
                {text: 'Nombre', value: 'Nombre'},
                {text: 'Edad', value: 'Edad'},
                {text: 'Sexo', value: 'Sexo'},
                {text: 'Tipo cita'},
                {text: 'Fecha'},
                {text: 'Generar Certificado'}

            ],
            Diagnosticos: []
        }),
        created() {
            // this.resumenhistoria();
        },
        computed:{
            ...mapGetters(['can'])
        },

        methods: {
            clearFields() {
                this.cedula = '';
                this.historiapaciente = [];
                this.dialog_timeline = false
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
                this.dataHistoria = historia;
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
                if (historia.id) {
                    this.history = this.getHistoryObj(historia);
                }
            },
            fillDiagnostic(autorizacion) {
                axios
                    .get("/api/cie10/diagnostico/" + autorizacion.citaPaciente_id)
                    .then(res => {
                        this.Diagnostico = res.data;
                    });
            },
            printhc(items) {
                    let pdf = {type:"evaluacionOcupacional", data:items};
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
                    .post("/api/cita/certificado", {Num_Doc: this.cedula})
                    .then(res => {
                        this.preload_historico = false;
                        this.certificado = res.data;
                        this.dialog_timeline = true;
                    });
            }
        }
    };

</script>

<style lang="scss" scoped>
</style>
