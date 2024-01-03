<template>
    <v-card>
        <template>
            <v-card>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-layout row wrap>
                                    <v-flex xs3>
                                        <v-text-field prepend-icon="calendar_today" label="Fecha inicio" type="date"
                                            color="primary" v-model="data.fecha_inicio">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-text-field prepend-icon="calendar_today" label="Fecha final" type="date"
                                            color="primary" v-model="data.fecha_fin">
                                        </v-text-field>
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-text-field v-model="data.documento" label="Documento"></v-text-field>
                                    </v-flex>
                                    <v-flex xs1>
                                        <v-btn color="primary" @click="searchHistorico()">Buscar</v-btn>
                                    </v-flex>
                                    <v-flex xs1>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn text icon color="error" dark v-on="on" @click="clearFilter()">
                                                    <v-icon>clear</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Limpiar</span>
                                        </v-tooltip>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
            </v-card>
        </template>

        <v-card-title v-show="datatable">
            <v-spacer></v-spacer>
            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
        </v-card-title>
        <v-data-table v-show="datatable" :search="search" :headers="headers" :items="historiapaciente"
            class="elevation-1">
            <template v-slot:items="props">
                <td>{{ props.item.Datetimeingreso.split('.')[0] }}</td>
                <td>{{ props.item.Primer_Nom }} {{ props.item.Primer_Ape }}</td>
                <td>{{ props.item.tipo_paciente }}</td>
                <td>{{ props.item.Num_Doc }}</td>
                <td>{{ props.item.Edad_Cumplida }}</td>
                <td>{{ props.item.Tipo_agenda }}</td>
                <td>{{ props.item.nombremedico}} {{ props.item.apellidomedico }}</td>
                <td class="justify-center layout px-0">
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <v-btn text icon color="indigo" dark v-on="on" @click="openotaclaratoria(props.item)">
                                <v-icon>add</v-icon>
                            </v-btn>
                        </template>
                        <span>Agregar nota</span>
                    </v-tooltip>
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <v-btn text icon color="green" dark v-on="on" @click="printRadiologo(props.item)">
                                <v-icon>description</v-icon>
                            </v-btn>
                        </template>
                        <span>Radiologo y Insumos</span>
                    </v-tooltip>
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <v-btn text icon color="primary" dark v-on="on" @click="printEnfermeria(props.item)">
                                <v-icon>assignment</v-icon>
                            </v-btn>
                        </template>
                        <span>Enfermeria y Tecnologo</span>
                    </v-tooltip>
                </td>
            </template>
        </v-data-table>

        <v-dialog v-model="dialogNota" persistent max-width="1000px">
            <v-card max-height="100%" v-show="true">
                <v-toolbar color="primary" flat dark>
                    <v-flex xs12 text-xs-center>
                        <v-toolbar-title>Agregar nota</v-toolbar-title>
                    </v-flex>
                </v-toolbar>
                <v-divider></v-divider>
                <v-card-text>
                    <v-layout row wrap>
                        <v-flex xs12>
                            <v-text-field label="Nota" textarea v-model="nota">
                            </v-text-field>
                        </v-flex>
                    </v-layout>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="success" @click="addnotaclaratoria()">Guardar</v-btn>
                    <v-btn color="error" @click="dialogNota = false, nota = ''">Salir</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </v-card>
</template>

<script>
    import Swal from 'sweetalert2';
    import moment from "moment";
    moment.locale("es");
    export default {
        data() {
            return {
                search: '',
                headers: [{
                        text: "Fecha y hora",
                        value: "Datetimeingreso",
                        align: "center",
                        sortable: true
                    },
                    {
                        text: "Paciente",
                        value: "Primer_Nom",
                        align: "center"
                    },
                    {
                        text: "Tipo",
                        value: "tipo_paciente",
                        align: "center"
                    },
                    {
                        text: "Cédula",
                        value: "Num_Doc",
                        align: "center"
                    },
                    {
                        text: "Edad",
                        value: "Edad_Cumplida",
                        align: "center"
                    },
                    {
                        text: "Tipo Cita",
                        value: "Tipo_agenda",
                        align: "center"
                    },
                    {
                        text: "Atendido Por",
                        value: "nameEspecialista",
                        align: "center"
                    },
                    {
                        text: "Acciones",
                        value: "",
                        align: "center"
                    }
                ],
                data: {
                    fecha_inicio: moment().format("YYYY-MM-DD"),
                    fecha_fin: moment().format("YYYY-MM-DD"),
                    documento: ''
                },
                datatable: false,
                historiapaciente: [],
                dialogNota: false,
                nota: '',
                cita: {}
            }
        },
        methods: {
            clearFilter() {
                this.data = {
                    fecha_inicio: moment().format("YYYY-MM-DD"),
                    fecha_fin: moment().format("YYYY-MM-DD"),
                    documento: ''
                }
            },
            searchHistorico() {
                if (!this.data.documento) {
                    swal({
                        title: "Debe ingresar un documento",
                        icon: "error"
                    });
                    return;
                }
                axios.post("/api/historiapaciente/gethistoriaradium", {
                    Num_Doc: this.data.documento,
                    Finicio: this.data.fecha_inicio,
                    Ffinal: this.data.fecha_fin
                }).then(res => {
                    this.datatable = true;
                    this.historiapaciente = res.data;
                });
            },
            printRadiologo(datos) {
                let data = {
                    citapaciente: datos.citapaciente_id
                }
                axios.post('/api/imagenologia/insumosGuardados', data).then(res => {
                    let insumosguardados = res.data
                    axios.post('/api/imagenologia/notasAclaratorias', data).then(res2 => {
                        let notaAclaratoria = res2.data
                        let pdf = {
                            type: "radium",
                            Nombrepaciente: `${datos.Primer_Nom} ${datos.Primer_Ape} ${datos.Segundo_Ape}`,
                            Hallazgos: datos.Hallazgos,
                            Conclusion: datos.Conclusion,
                            Indicacion: datos.Indicacion,
                            Tecnica: datos.tecnica_radiologo,
                            Notaclaratoria: datos.Notaclaratoria,
                            Num_Doc: datos.Num_Doc,
                            Fecha_Naci: datos.Fecha_Naci.split(' ')[0],
                            Tipo_Afiliado: datos.Tipo_Afiliado,
                            Departamento: datos.Departamento,
                            Telefono: datos.Telefono,
                            Mpio_Afiliado: datos.Mpio_Afiliado,
                            Ocupacion: datos.Ocupacion,
                            Nombreacompanante: datos.Nombreacompanante,
                            Telefonoacompanante: datos.Telefonoacompanante,
                            Nombreresponsable: datos.Nombreresponsable,
                            Telefonoresponsable: datos.Telefonoresponsable,
                            Parentesco: datos.Parentesco,
                            Aseguradora: datos.Aseguradora,
                            Tipovinculacion: datos.Tipovinculacion,
                            Edad_Cumplida: datos.Edad_Cumplida,
                            Sexo: datos.Sexo,
                            Firma: datos.Firma,
                            Insumos: insumosguardados,
                            Notaclaratorias: notaAclaratoria
                        };
                        this.printPDF(pdf);
                    })
                })
            },
            printEnfermeria(datos) {
                let data = {
                    cita_paciente_id: datos.citapaciente_id
                }
                axios.post('/api/imagenologia/notasEnfermeria', data).then(res => {
                    let notas = res.data
                    axios.post('/api/imagenologia/estudioTecnologo', data).then(res2 => {
                        let estudio = res2.data
                        let pdf = {
                            type: "radium",
                            Nombrepaciente: `${datos.Primer_Nom} ${datos.Primer_Ape} ${datos.Segundo_Ape}`,
                            Num_Doc: datos.Num_Doc,
                            Fecha_Naci: datos.Fecha_Naci.split(' ')[0],
                            Tipo_Afiliado: datos.Tipo_Afiliado,
                            Departamento: datos.Departamento,
                            Telefono: datos.Telefono,
                            Mpio_Afiliado: datos.Mpio_Afiliado,
                            Ocupacion: datos.Ocupacion,
                            Nombreacompanante: datos.Nombreacompanante,
                            Telefonoacompanante: datos.Telefonoacompanante,
                            Nombreresponsable: datos.Nombreresponsable,
                            Telefonoresponsable: datos.Telefonoresponsable,
                            Parentesco: datos.Parentesco,
                            Aseguradora: datos.Aseguradora,
                            Tipovinculacion: datos.Tipovinculacion,
                            Edad_Cumplida: datos.Edad_Cumplida,
                            Sexo: datos.Sexo,
                            Firma: datos.Firma,
                            Notas: notas,
                            Estudio: estudio
                        };
                        this.printPDF(pdf);
                    })
                })
            },
            printPDF(pdf) {
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
            openotaclaratoria(datos) {
                this.dialogNota = true
                this.cita = {
                    cita_paciente_id: datos.citapaciente_id
                }
            },
            addnotaclaratoria(){
                if(this.nota == '') return
                let datos = {
                    cita_paciente_id: this.cita.cita_paciente_id,
                    nota: this.nota
                }
                axios.post('/api/imagenologia/addnotaclaratoria', datos).then(res => {
                    this.$alerSuccess("Nota agregada con exito!")
                    this.dialogNota = false
                    this.nota = ''
                })
            }

        }
    }

</script>
