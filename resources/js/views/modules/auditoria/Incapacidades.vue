<template>
    <v-layout>
        <v-flex xs12>
            <v-card>
                <v-card-title class="headline success" style="color:white">
                    <span class="title layout justify-center font-weight-light">Historico de incapacidades</span>
                </v-card-title>
                <v-form @submit.prevent="find()">
                    <v-container>
                        <v-layout row wrap>
                            <v-flex xs6 sm>
                                <v-text-field v-model="cedula" label="Cédula"></v-text-field>
                            </v-flex>
                            <v-spacer></v-spacer>
                            <v-flex xs6 sm>
                                <v-btn color="primary" round @click="find()">Buscar</v-btn>
                                <v-btn @click="clearFields()" v-if="cedula" fab outline small color="error"><v-icon>clear</v-icon></v-btn>
                                <v-btn color="warning" v-if="can('auditoria.incapacidad.excel')" dark round @click="getExcel()">Exportar Excel</v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
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
                                                    <v-flex xs6 md3 lg5 class="pr-5">
                                                        <v-text-field v-model="inc.Nombre" label="Nombre" outlined
                                                            readonly>
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 md3 lg2 class="pr-5">
                                                        <v-text-field v-model="inc.Cedula" label="Cédula" outlined
                                                            readonly>
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 md3 lg1 class="pr-5">
                                                        <v-text-field v-model="inc.Edad_Cumplida" label="edad" outlined
                                                            readonly></v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 md3 lg1 class="pr-5">
                                                        <v-text-field v-model="inc.Sexo" label="sexo" outlined readonly>
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs6 md3 lg2 class="pr-5">
                                                        <v-text-field v-model="inc.Celular" label="Celular" outlined
                                                            readonly>
                                                        </v-text-field>
                                                    </v-flex>
                                                    <v-flex xs12 class="pr-5">
                                                        <v-textarea v-if="inc.Estado_id == 26" v-model="inc.DesAnulado" label="Motivo de anulacion" outlined
                                                                      readonly>
                                                        </v-textarea>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                            <v-container>
                                                <v-textarea
                                                    v-if="inc.Estado_id == 1 && can('auditoria.incapacidad.excel')"
                                                    name="input-7-1" label="Observacion" value v-model="observaciones">
                                                </v-textarea>
                                            </v-container>
                                        </v-card>
                                    </v-flex>
                                </v-tab-item>
                                <v-tab-item>
                                    <v-card flat>
                                        <v-card-title class="headline grey lighten-2">Diagnosticos</v-card-title>
                                        <v-data-table class="fb-table-elem" :headers="diagnosticHeaders"
                                            :items="Diagnosticos" item-key="id" :items-per-page="5" hide-actions expand>
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
                                <v-flex xs3 md3 lg3>
                                    <v-card-actions>
                                        <v-btn color="blue darken-1" flat @click="cerrarModal()">Cerrar</v-btn>
                                    </v-card-actions>
                                </v-flex>
                                <v-flex xs3 md3 lg3>
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn v-if="inc.Estado_id == 1" text icon color="green" dark v-on="on">
                                                <v-icon @click="print()">assignment_turned_in</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Incapacidad</span>
                                    </v-tooltip>
                                </v-flex>
                                <v-flex xs3 md3 lg3>
                                    <v-card-actions>
                                        <v-btn color="primary" @click="email = true" round>Enviar</v-btn>
                                    </v-card-actions>
                                </v-flex>
                                <v-flex xs3 md3 lg3>
                                    <v-card-actions>
                                        <v-btn v-if="inc.Estado_id == 1  && can('auditoria.incapacidad.anular')"
                                            color="red" dark @click="Anular()">Anular</v-btn>
                                    </v-card-actions>
                                </v-flex>
                            </v-layout>
                        </v-card>
                    </v-dialog>
                </v-layout>
                <template>
                    <div class="text-center">
                        <v-dialog v-model="preload_incapacidades" persistent width="300">
                            <v-card color="primary" dark>
                                <v-card-text>
                                    Tranquilo procesamos tu solicitud !
                                    <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                                </v-card-text>
                            </v-card>
                        </v-dialog>
                    </div>
                </template>
                <v-dialog v-model="email" persistent max-width="1000px">
                    <v-card>
                        <v-toolbar flat color="primary" dark>
                            <v-toolbar-title>Enviar o imprimir</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <v-btn color="green" round @click="enableEmail = !enableEmail">Enviar por correo</v-btn>
                            <v-expand-x-transition>
                                <v-card v-show="enableEmail" height="200" width="500" class="mx-auto">
                                    <v-card-text>
                                        <v-layout row wrap>
                                            <v-flex xs10>
                                                <v-text-field v-model="Email" :rules="emailRules" prepend-icon="mail"
                                                    label="Email"></v-text-field>
                                            </v-flex>
                                        </v-layout>
                                    </v-card-text>
                                    <v-card-actions>
                                        <v-btn color="primary" @click="SendEmail()" round>Enviar</v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-expand-x-transition>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" flat @click="cerrarmodalemail()">Cerrar</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-layout row>
                    <v-flex xs12 md12 lg12 v-if="dialog_timeline">
                        <v-data-table :headers="headers" :items="incapacidades" class="elevation-1">
                            <template v-slot:items="props">
                                <td class="text-xs-right">{{ props.item.Nombre }}</td>
                                <td class="text-xs-right">{{ props.item.Cedula }}</td>
                                <td class="text-xs-right">{{ props.item.Edad_Cumplida }}</td>
                                <td class="text-xs-right">{{ props.item.Dias }}</td>
                                <td class="text-xs-right">{{ props.item.Contingencia }}</td>
                                <td class="text-xs-right">{{ props.item.Fechainicio }}</td>
                                <td class="text-xs-right">{{ props.item.Fechafinal }}</td>
                                <td class="text-xs-right">{{ props.item.Prorroga }}</td>
                                <td class="text-xs-right">{{ props.item.Laboraen }}</td>
                                <td class="text-xs-right" @click="abrirModal(props.item)">
                                    <v-chip v-if="props.item.Estado === 'Activo'" class="ma-2" color="green"
                                        text-color="white">
                                        <v-icon dark small>done </v-icon>{{ props.item.Estado }}
                                    </v-chip>
                                    <v-chip v-if="props.item.Estado === 'Anulado'" class="ma-2" color="red"
                                        text-color="white">
                                        <v-icon dark small>close </v-icon>{{ props.item.Estado }}
                                    </v-chip>
                                </td>
                            </template>
                        </v-data-table>
                    </v-flex>
                </v-layout>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    import Swal from 'sweetalert2';
    import XLSX from "xlsx";
    import {
        mapGetters
    } from 'vuex';
    export default {
        data: () => ({
            enableEmail: false,
            email: false,
            preload_incapacidades: false,
            incapacidades: [],
            incapacidad: {},
            inc: {},
            cedula: "",
            observaciones: "",
            input: null,
            dialog: false,
            dialog_timeline: false,
            nonce: 0,
            pdf: {
                order_id: "",
                type: "incapacidad",
                Correo: "",
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
            headers: [{
                    text: 'Nombre'
                },
                {
                    text: 'Cédula'
                },
                {
                    text: 'Edad'
                },
                {
                    text: 'Días'
                },
                {
                    text: 'Tipo'
                },
                {
                    text: 'F. Inicio',
                    value: 'Fechainicio'
                },
                {
                    text: 'F. Final',
                },
                {
                    text: 'Prorroga',
                    value: 'Prorroga'
                },
                {
                    text: 'Labora',
                },
                {
                    text: 'Estado',
                    value: 'Estado'
                }
            ],
            Diagnosticos: []
        }),
        computed: {
            ...mapGetters(['can'])
        },
        methods: {
            clearFields() {
                this.cedula = '';
                this.incapacidades = [];
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
            abrirModal(inc) {
                this.fillInc(inc);
                this.dialog = true;
            },
            cerrarModal() {
                this.dialog = false;
                this.observaciones = "";
            },
            fillInc(inc) {
                if (inc.id) {
                    this.inc = inc;
                }
            },
            fillDiagnostic(autorizacion) {
                axios
                    .get("/api/cie10/diagnostico/" + autorizacion.citaPaciente_id)
                    .then(res => {
                        this.Diagnostico = res.data;
                        console.log("Diagnostico", this.Diagnostico);
                    });
            },
            async SendEmail() {
                var regex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
                if (!regex.test(this.Email)) {
                    swal({
                        title: "Debe ingresar un Email valido",
                        icon: "warning"
                    });
                    return;
                }
                let pdf = this.getPDFIncap();
                pdf.SendEmail = true;
                pdf.Correo = this.Email;

                axios.post("/api/pdf/print-pdf", pdf, {
                        responseType: "arraybuffer"
                    })
                    .then(res => {
                        swal({
                            title: "¡Orden enviada con exito!",
                            text: ` `,
                            timer: 3000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch(err => (err.response));
            },
            print() {

                if (this.inc) {

                    let pdf = this.getPDFIncap();
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
            getPDFIncap() {
                console.log("pdf", this.inc);
                return (this.pdf = {
                    type: "incapacidad",
                    FechaInicio: this.inc.Fechainicio,
                    Nombre: this.inc.Nombre,
                    Edad_Cumplida: this.inc.Edad_Cumplida,
                    Sexo: this.inc.Sexo,
                    medico_ordena: this.inc.Medicoordeno,
                    Celular: this.inc.Celular,
                    Correo: this.inc.Correo,
                    Num_Doc: this.inc.Cedula,
                    cita_paciente_id: this.inc.citaPaciente_id,
                    orden_id: this.inc.Orden_id,
                    provincapacidad: this.inc.Provincapacidad,
                    Fechacreaincapacidad: this.inc.Fechacreaincapacidad,
                    hj: this.inc.hj,
                    afilia: this.inc.afilia,
                    Ut: this.inc.Ut,
                    ips1: this.inc.ips1,
                    ma: this.inc.ma,
                    TTipod: this.inc.TTipod,
                    Proveedor: this.inc.Proveedor,
                    dx_principal: this.inc.Diagnostico.substring(0, 4),
                    CantidadDias: this.inc.Dias,
                    FechaFinal: this.inc.Fechafinal,
                    Prorroga: this.inc.Prorroga,
                    tel: this.inc.tel,
                    TextCie10: this.inc.Diagnostico.substring(0, 4),
                    Contingencia: this.inc.Contingencia,
                    Descripcion: this.inc.Descripcion,
                    Laboraen: this.inc.Laboraen,
                    Firma: this.inc.Firma,
                    especialidad_medico: this.inc.especialidad_medico,
                    Rmedico: this.inc.Rmedico,
                    SendEmail: false
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

                let inc = {
                    cedula: this.cedula
                };
                this.preload_incapacidades = true;
                axios
                    .post("/api/incapacidad/getIncByCedula", inc)
                    .then(res => {
                        console.log('procedure', res.data);
                        this.preload_incapacidades = false;
                        this.incapacidades = res.data;
                        this.dialog_timeline = true;
                    });
            },
            Anular() {

                if (this.observaciones == "") {
                    swal({
                        title: "Debe llenar la Observacion",
                        icon: "warning"
                    });
                    return;
                }

                let obj = {
                    Id: this.inc.id,
                    Nombre: this.inc.Nombre,
                    Inicio: this.inc.Fechainicio,
                    Fin: this.inc.Fechafinal,
                    Observacion: this.observaciones
                }

                axios
                    .post("/api/incapacidad/changeInc", obj)
                    .then(res => {

                        swal({
                            title: 'Incapacidad',
                            text: res.data.message,
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                        this.cerrarModal();
                        this.find();
                    });

            },
            getExcel() {
                axios
                    .get("/api/incapacidad/AllExcel")
                    .then(res => {
                        let data = XLSX.utils.json_to_sheet(res.data);
                        const workbook = XLSX.utils.book_new();
                        const filename = "Incapacidades";
                        XLSX.utils.book_append_sheet(workbook, data, filename);
                        XLSX.writeFile(workbook, `${filename}.xlsx`);
                    });
            }
        }
    };

</script>

<style lang="scss" scoped>
</style>
