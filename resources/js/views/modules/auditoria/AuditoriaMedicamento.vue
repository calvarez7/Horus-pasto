<template>
    <v-layout>
        <v-flex xs12>
            <v-card>
                <v-card-title class="headline success" style="color:white">
                    <span class="title layout justify-center font-weight-light">Medicamentos</span>
                </v-card-title>
                <v-form @submit.prevent="find()">
                    <v-container>
                        <v-layout>
                            <v-flex xs12 md4>
                                <v-select v-model="accion" append-icon="search" label="Seleccionar acción" :items="acciones"
                            item-text="accion" item-value="value" hide-details></v-select>
                            </v-flex>

                            <v-flex xs12 md4>
                                <v-text-field v-model="cedula" label="Cédula" outlined></v-text-field>
                            </v-flex>

                            <v-flex xs12 md4>
                                <v-btn color="primary" round @click="find()">Buscar</v-btn>
                                <v-btn @click="clearFields()" v-if="cedula" fab outline small color="error">
                                    <v-icon>clear</v-icon>
                                </v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-form>
            </v-card>
            <v-card>
                <v-layout row wrap>
                    <v-dialog v-model="dialog" max-width="1000px" persistent>
                        <v-card>
                            <v-card-title class="headline grey lighten-2">Medicamentos</v-card-title>
                            <v-data-table class="fb-table-elem" :headers="dataArticuloOrderHeaders"
                                :items="autorizacion.detaarticulordens" item-key="index" v-model="selected" select-all>
                                <template v-slot:items="props">
                                    <td class="text-xs-center">
                                        <v-checkbox color="primary" :input-value="props.selected" v-model="selected"
                                            :value="props.item" multiple hide-details></v-checkbox>
                                    </td>
                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">{{ props.item.id }}</div>
                                    </td>
                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">{{ props.item.Codigo }}</div>
                                    </td>
                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">{{ props.item.Nombre }}</div>
                                    </td>
                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">{{ props.item.Via }}</div>
                                    </td>
                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">{{ props.item.Unidad_Medida }}</div>
                                    </td>
                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">{{ props.item.Frecuencia }}</div>
                                    </td>
                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">{{ props.item.Unidad_Tiempo }}</div>
                                    </td>
                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">{{ props.item.Duracion }}</div>
                                    </td>
                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">{{ props.item.Num_Meses }}</div>
                                    </td>
                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">{{ props.item.Observacion }}</div>
                                    </td>
                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">{{ props.item.Cantidad_Medico }}</div>
                                    </td>
                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">{{ props.item.Auth_Name }}
                                            {{ props.item.Auth_Apellido }}</div>
                                    </td>
                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">{{ props.item.Auth_Nota }}</div>
                                    </td>
                                </template>
                            </v-data-table>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn v-show="show()" color="green" round @click="enableEmail = !enableEmail">Enviar
                                    por correo</v-btn>
                                <v-expand-x-transition>
                                    <v-card v-show="show() && enableEmail" height="200" width="500" class="mx-auto">
                                        <v-card-text>
                                            <v-layout row wrap>
                                                <v-flex xs10>
                                                    <v-text-field v-model="Email" :rules="emailRules"
                                                        prepend-icon="mail" label="Email"></v-text-field>
                                                </v-flex>
                                            </v-layout>
                                        </v-card-text>
                                        <v-card-actions>
                                            <v-btn color="primary" round @click="SendEmail()">Enviar</v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-expand-x-transition>
                                <v-btn v-show="show()" color="primary" round @click="printPDF()">Imprimir</v-btn>
                            </v-card-actions>
                        </v-card>
                        <v-card>
                            <v-tabs vertical>
                                <v-tab>
                                    <v-icon left>mdi-account</v-icon>Acciones
                                </v-tab>
                                <v-tab>
                                    <v-icon left>list_alt</v-icon>Adjuntos
                                </v-tab>
                                <v-tab-item v-if="can('auditoria.medicamentos.auditar')">
                                    <v-card>

                                        <v-dialog v-model="dialogSede" persistent width="500">
                                            <v-card>
                                                <v-card-title class="headline grey lighten-2" primary-title>
                                                    Modificación Sede Entrega
                                                </v-card-title>
                                                <v-card-text>
                                                    <v-autocomplete label="Sede" :items="filteredSedeProveedor"
                                                        item-value="id" item-text="join"
                                                        v-model="sedeProveedorEntregaId" :loading="loading" required>
                                                    </v-autocomplete>
                                                    <v-textarea outline name="input-7-4" label="Observación"
                                                        v-model="sedeProveedorObservacion"></v-textarea>
                                                </v-card-text>
                                                <v-divider></v-divider>
                                                <v-card-actions>
                                                    <v-spacer></v-spacer>
                                                    <v-btn color="primary" text @click="updatePrestador()">
                                                        Actualizar
                                                    </v-btn>
                                                    <v-btn color="red" text
                                                        @click="dialogSede = false,sedeProveedorEntregaId = 0,sedeProveedorObservacion = ''">
                                                        Cerrar
                                                    </v-btn>
                                                </v-card-actions>
                                            </v-card>
                                        </v-dialog>

                                        <v-card-title>
                                            <span class="headline">Acciones</span>
                                        </v-card-title>
                                        <v-card-text>
                                            <v-layout row wrap>
                                                <v-flex xs12 md12 lg12>
                                                    <v-btn class="mx-0" color="primary" large @click="dialogSede = true"
                                                        v-show="can('auditoria.medicamentos.sede.change')">Cambiar Sede
                                                    </v-btn>
                                                    <v-spacer></v-spacer>
                                                    <v-divider></v-divider>
                                                    <v-spacer></v-spacer>
                                                </v-flex>
                                                <v-flex xs6 md3 lg6>
                                                    <v-select v-model="action" append-icon="search"
                                                        label="Seleccionar acción" :items="actions" item-text="accion"
                                                        item-value="value" hide-details>
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs6 md6 lg6>
                                                    <span>Elegir una acción para cambiar el estado de la orden
                                                        seleccionada</span>
                                                </v-flex>
                                            </v-layout>
                                            <v-container>
                                                <v-textarea name="input-7-1" label="Observacion" value
                                                    v-model="observaciones">
                                                </v-textarea>
                                                <v-btn v-show="selected.length > 0" color="blue"
                                                    class="ma-2 white--text" @click="enviarAccion(action)">
                                                    Enviar
                                                    <v-icon right dark>send</v-icon>
                                                </v-btn>
                                            </v-container>
                                        </v-card-text>
                                    </v-card>
                                </v-tab-item>
                                <v-tab-item>
                                    <v-card flat>
                                        <v-card-title class="headline grey lighten-2">Archivos Paciente
                                        </v-card-title>
                                        <v-data-table class="fb-table-elem" :headers="filesHeaders" :items="listOfFiles"
                                            item-key="index">
                                            <template v-slot:items="props">
                                                <td class="text-xs-center">
                                                    <div class="datatable-cell-wrapper">{{ props.item.Name }}</div>
                                                </td>
                                                <td class="text-xs-center">
                                                    <div class="datatable-cell-wrapper">{{ props.item.created_at }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <v-btn text icon color="blue" dark>
                                                        <v-icon @click="generate(props.item)">assignment_turned_in
                                                        </v-icon>
                                                    </v-btn>
                                                    <span>Anexo</span>
                                                </td>

                                            </template>
                                        </v-data-table>
                                    </v-card>
                                </v-tab-item>
                            </v-tabs>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" flat @click="cerrarModal()">Cerrar</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <template>
                        <template>
                            <div class="text-center">
                                <v-dialog v-model="preload_medicamentos" persistent width="800">
                                    <v-card color="primary" dark>
                                        <v-card-text>
                                            Tranquilo procesamos tu solicitud !
                                            <v-progress-linear indeterminate color="white" class="mb-0">
                                            </v-progress-linear>
                                        </v-card-text>
                                    </v-card>
                                </v-dialog>
                            </div>
                        </template>
                        <v-layout wrap>
                            <v-flex>
                                <v-card>
                                    <v-card-title>
                                        <v-text-field v-model="search" append-icon="search" label="Buscar..."
                                            single-line hide-details></v-text-field>
                                    </v-card-title>
                                    <v-data-table :headers="headers" :items="listaAutorizaciones" item-key="index"
                                        :search="search">
                                        <template v-slot:items="props">
                                            <td>
                                                <v-btn text icon color="blue" dark>
                                                    <v-icon @click="printhc(props.item)">assignment_turned_in</v-icon>
                                                </v-btn>
                                                <span>Historia</span>
                                            </td>
                                            <td class="text-xs-center">{{ props.item.id}}</td>
                                            <td class="text-xs-center">{{ props.item.created_at}}</td>
                                            <td class="text-xs-center">{{ props.item.Fechaorden}}</td>
                                            <td class="text-xs-center">{{ props.item.Departamento}}</td>
                                            <td class="text-xs-center">{{ props.item.Municipio}}</td>
                                            <td class="text-xs-center">{{ props.item.Ut}}</td>
                                            <td class="text-xs-center">{{ props.item.Primer_Nom}}{{ props.item.SegundoNom}}
                                                {{ props.item.Primer_Ape}}{{ props.item.Segundo_Ape}}</td>
                                            <td class="text-xs-center">{{ props.item.Cedula}}</td>
                                            <td class="text-xs-center">{{ props.item.Nombre_IPS}}</td>
                                            <td class="text-xs-center">{{ props.item.Descripcion_CIE10}}</td>
                                            <td class="text-xs-center">{{ props.item.User_Name}}
                                                {{ props.item.User_LastName}}</td>
                                            <td class="text-xs-center">
                                                <v-btn color="blue" class="ma-2 white--text"
                                                       @click="abrirModal(props.item)">
                                                    Acciones
                                                    <v-icon right dark>send</v-icon>
                                                </v-btn>
                                            </td>
                                        </template>
                                        <v-alert v-slot:no-results :value="true" color="error" icon="warning">Your
                                            search for
                                            "{{ search }}" found no results.</v-alert>
                                    </v-data-table>
                                    <v-layout row wrap>
                                        <v-spacer></v-spacer>
                                        <v-layout row wrap v-if="can('exportar.historicoMedicamentos')">
                                            <v-btn color="dark" round @click="expand = !expand">Exportar a Excel</v-btn>
                                        </v-layout>
                                        <v-expand-x-transition>
                                            <v-card v-show="expand" height="200" width="500" class="mx-auto">
                                                <v-card-text>
                                                    <v-layout row wrap>
                                                        <v-flex xs6>
                                                            <v-menu ref="show_start_date"
                                                                :close-on-content-click="false"
                                                                v-model="show_start_date" :nudge-right="40"
                                                                :return-value.sync="start_date" lazy
                                                                transition="scale-transition" offset-y full-width
                                                                min-width="250px" max-width="250px">
                                                                <template v-slot:activator="{ on }">
                                                                    <v-text-field v-model="start_date"
                                                                        label="Fecha Inicial" prepend-icon="event"
                                                                        readonly v-on="on">
                                                                    </v-text-field>
                                                                </template>
                                                                <v-date-picker color="primary" locale="es"
                                                                    v-model="start_date" full-width
                                                                    @click="$refs.show_start_date.save(start_date)">
                                                                    <v-spacer></v-spacer>
                                                                    <v-btn flat color="primary"
                                                                        @click="show_start_date = false">
                                                                        Cancelar
                                                                    </v-btn>
                                                                    <v-btn flat color="primary"
                                                                        @click="$refs.show_start_date.save(start_date)">
                                                                        OK
                                                                    </v-btn>
                                                                </v-date-picker>
                                                            </v-menu>
                                                        </v-flex>
                                                        <v-flex xs6>
                                                            <v-menu ref="show_end_date" :close-on-content-click="false"
                                                                v-model="show_end_date" :nudge-right="40"
                                                                :return-value.sync="end_date" lazy
                                                                transition="scale-transition" offset-y full-width
                                                                min-width="250px" max-width="250px">
                                                                <template v-slot:activator="{ on }">
                                                                    <v-text-field v-model="end_date" label="Fecha Final"
                                                                        prepend-icon="event" readonly v-on="on">
                                                                    </v-text-field>
                                                                </template>
                                                                <v-date-picker color="primary" locale="es"
                                                                    v-model="end_date" full-width crollable>
                                                                    <v-spacer></v-spacer>
                                                                    <v-btn flat color="primary"
                                                                        @click="show_end_date = false">
                                                                        Cancelar</v-btn>
                                                                    <v-btn flat color="primary"
                                                                        @click="$refs.show_end_date.save(end_date)">
                                                                        OK</v-btn>
                                                                </v-date-picker>
                                                            </v-menu>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-card-text>
                                                <v-card-actions>
                                                    <v-btn color="primary" round @click="exportExcel()">Aceptar</v-btn>
                                                </v-card-actions>
                                            </v-card>
                                        </v-expand-x-transition>
                                    </v-layout>
                                </v-card>
                            </v-flex>
                        </v-layout>
                    </template>
                </v-layout>
            </v-card>
        </v-flex>
    </v-layout>

</template>
<script>
import XLSX from "xlsx";
import {
    mapGetters
} from 'vuex';
export default {
    name: "Auditoria",
    data() {
        return {
            sedeProveedorObservacion: "",
            loading: false,
            ListSedeProveedor: [],
            sedeProveedorEntregaId: 0,
            dialogSede: false,
            listaAutorizaciones: [],
            autorizaciones: [],
            listOfFiles: [],
            selected: [],
            dialog: false,
            expand: false,
            enableEmail: false,
            accion: "",
            action: "",
            search: "",
            cedula: "",
            Firma_Auditor: null,
            observaciones: "",
            autorizacion: {},
            pdf: {},
            bjectFrm: {},
            show_start_date: false,
            start_date: null,
            show_end_date: false,
            end_date: null,
            Email: '',
            emailRules: [],
            filterArticulos: [],
            data: {
                Tiporden_id: 3,
                articulos: [],
                procedimientos: []
            },
            headers: [{
                text: "Historia",
                sortable: false,
                value: ""
            },
                {
                    text: "Orden",
                    sortable: false,
                    value: ""
                },
                {
                    text: "Fecha Solicitud",
                    sortable: false,
                    align: "center",
                    value: "FechaSolicitud"
                },
                {
                    text: "Fecha Orden",
                    sortable: false,
                    align: "center",
                    value: "Fecha_Orden"
                },
                {
                    text: "Departamento",
                    sortable: false,
                    align: "center",
                    value: ""
                },
                {
                    text: "Municipio",
                    sortable: false,
                    align: "center",
                    value: "Municipio"
                },{
                    text: "Contrato",
                    sortable: false,
                    align: "center",
                },
                {
                    text: "Nombre",
                    sortable: false,
                    align: "center",
                    value: "Primer_Nom"
                },
                {
                    text: "Cedula",
                    sortable: false,
                    align: "center",
                    value: "Cedula"
                },
                {
                    text: "Ips Atención",
                    sortable: false,
                    align: "center",
                    value: "Nombre_IPS"
                },
                {
                    text: "Diagnostico",
                    sortable: false,
                    align: "center",
                    value: "Descripcion_CIE10"
                },
                {
                    text: "Funcionario Carga Servicio",
                    sortable: false,
                    align: "center",
                    value: ""
                },
                {
                    text: "Acciones",
                    sortable: false,
                    align: "center",
                    value: ""
                }
            ],
            acciones: [{
                accion: "Aprobado Sin Autorización",
                value: "1"
            },
                {
                    accion: "Aprobado Con Autorización",
                    value: "7"
                },
                {
                    accion: "PENDIENTE",
                    value: "15"
                },
                {
                    accion: "INADECUADO",
                    value: "24"
                },
                {
                    accion: "NEGADO",
                    value: "25"
                },
                {
                    accion: "ANULADO",
                    value: "26"
                }
            ],
            actions: [{
                accion: "INADECUADO",
                value: "Inadecuado"
            },
                {
                    accion: "NEGADO",
                    value: "Negado"
                },
                {
                    accion: "ANULADO",
                    value: "Anulado"
                }
            ],
            filesHeaders: [{
                text: "Nombre",
                align: "center",
                value: "Name"
            },
                {
                    text: "Fecha",
                    align: "center",
                    value: "created_at"
                },
                {
                    text: "",
                    align: "center",
                    value: ""
                }
            ],
            dataArticuloOrderHeaders: [{
                text: "Orden Medicamento",
                sortable: false,
                align: "center",
                value: "id"
            },
                {
                    text: "Código Sumi",
                    sortable: false,
                    align: "center",
                    value: "Codigo"
                },
                {
                    text: "Nombre Sumi",
                    sortable: false,
                    align: "center",
                    value: "Nombre"
                },
                {
                    text: "Via",
                    sortable: false,
                    align: "center",
                    value: ""
                },
                {
                    text: "Unidad Medida",
                    sortable: false,
                    align: "center",
                    value: ""
                },
                {
                    text: "Frecuencia",
                    sortable: false,
                    align: "center",
                    value: ""
                },
                {
                    text: "Unidad Tiempo",
                    sortable: false,
                    align: "center",
                    value: ""
                },
                {
                    text: "Duracion",
                    sortable: false,
                    align: "center",
                    value: ""
                },
                {
                    text: "Número Meses",
                    sortable: false,
                    align: "center",
                    value: ""
                },
                {
                    text: "Observacion",
                    sortable: false,
                    align: "center",
                    value: ""
                },
                {
                    text: "Cantidad Médico",
                    sortable: false,
                    align: "center",
                    value: ""
                },
                {
                    text: "Auditor",
                    sortable: false,
                    align: "center",
                    value: ""
                },
                {
                    text: "Nota Auditoria",
                    sortable: false,
                    align: "center",
                    value: ""
                }
            ],
            preload_medicamentos: false
        };
    },
    watch: {
        Email: function (mail) {
            if (mail !== '') {
                this.emailRules = [v => /.+@.+\..+/.test(v) || 'E-mail no valido']
            } else if (mail === '') {
                this.emailRules = []
            }
        }
    },
    computed: {
        ...mapGetters(['can']),
        filteredSedeProveedor() {
            console.log("list", this.ListSedeProveedor);
            return this.ListSedeProveedor.map(SedeProveedor => {
                return {
                    ...SedeProveedor,
                    join: `${SedeProveedor.Codigo_habilitacion} - ${SedeProveedor.Nombre}`
                }
            });
        },
    },
    methods: {
        find() {
            if (!this.accion) {
                swal({
                    title: "Debe elegir algún estado",
                    icon: "warning"
                });
                return;
            } else if (!this.cedula) {
                swal({
                    title: "Debe elegir una cédula",
                    icon: "warning"
                });
                return;
            }

            let state = {
                status: this.accion,
                cedula: this.cedula
            };
            this.preload_medicamentos = true
            axios.post("/api/autorizacion/listAuthMedicByState", state).then(res => {
                this.preload_medicamentos = false;
                if (res.data.length > 0)
                    this.listaAutorizaciones = res.data
                else this.listaAutorizaciones = [];
                this.fetchProveedor();
            });
        },
        clearFields(){
            this.cedula = '',
            this.accion = ''
        },
        async exportExcel() {

            if (!this.accion) {
                swal({
                    title: "Excel",
                    text: "Se requiere una acción",
                    timer: 2000,
                    icon: "error",
                    buttons: false
                });
                return;
            }

            if (!this.start_date) {
                swal({
                    title: "Excel",
                    text: "Se requiere una fecha inicial",
                    timer: 2000,
                    icon: "error",
                    buttons: false
                });
                return;
            }

            if (!this.end_date) {
                swal({
                    title: "Excel",
                    text: "Se requiere una fecha final",
                    timer: 2000,
                    icon: "error",
                    buttons: false
                });
                return;
            }

            if (this.start_date > this.end_date) {
                swal({
                    title: "Excel",
                    text: "La fecha inicial es mayor a la fecha final",
                    timer: 2000,
                    icon: "error",
                    buttons: false
                });
                return;
            }

            let object = {
                status: this.accion,
                fechaStart: this.start_date,
                fechaEnd: this.end_date
            };

            await axios.post("/api/autorizacion/getExcelForMedicamentos", object).then(res => {
                if (res.data.length > 0) {
                    let data = XLSX.utils.json_to_sheet(res.data);
                    const workbook = XLSX.utils.book_new();
                    const filename = "Medicamentos";
                    XLSX.utils.book_append_sheet(workbook, data, filename);
                    XLSX.writeFile(workbook, `${filename}.xlsx`);
                } else {
                    swal({
                        title: "Excel",
                        text: "No hay datos para la acción y fechas seleccionadas",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                }
            });

        },
        printhc(autorizacion) {
            if (autorizacion) {
                console.log("cita", autorizacion);
                let cc = {
                    Num_Doc: autorizacion.Cedula
                };
                axios.post("/api/historiapaciente/gethistoria", cc).then(res => {
                    console.log("hc", res.data);
                    this.historiapaciente = res.data.find(h => h.id == autorizacion.citaPaciente_id);
                    if (!this.historiapaciente) {
                        swal({
                            title: "Historial",
                            text: "El historial no puede ser mostrado",
                            timer: 2000,
                            icon: "error",
                            buttons: false
                        });
                    } else {
                        let pdf = this.getPDFHistorial(this.historiapaciente);
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
                    });
                }
            },
            getPDFHistorial(autorizacion) {
                return (this.pdf = {
                    type: "test",
                    FechaInicio: autorizacion.Fecha_solicita,
                    Nombre: autorizacion.Nombre,
                    Edad_Cumplida: autorizacion.Edad,
                    Sexo: autorizacion.Sexo,
                    Antropometricas: autorizacion.Antropometricas,
                    Atendido_Por: autorizacion.Atendido_Por,
                    Cardiovascular: autorizacion.Cardiovascular,
                    telefono: autorizacion.Celular,
                    Condiciongeneral: autorizacion.Condiciongeneral,
                    Cédula: autorizacion.Cédula,
                    Datetimeingreso: autorizacion.Datetimeingreso,
                    Datetimesalida: autorizacion.Datetimesalida,
                    Departamento: autorizacion.Departamento,
                    Destinopaciente: autorizacion.Destinopaciente,
                    Diagnostico_Principal: autorizacion.Diagnostico_Principal,
                    Diagnostico_Secundario: autorizacion.Diagnostico_Secundario,
                    Direccion_Residencia: autorizacion.Direccion_Residencia,
                    Edad: autorizacion.Edad,
                    Endocrinologico: autorizacion.Endocrinologico,
                    Enfermedadactual: autorizacion.Enfermedadactual,
                    Entidademite: autorizacion.Entidademite,
                    Fecha_Nacimiento: autorizacion.Fecha_Nacimiento,
                    Fecha_solicita: autorizacion.Fecha_solicita,
                    Finalidad: autorizacion.Finalidad,
                    Gastrointestinal: autorizacion.Gastrointestinal,
                    Genitourinario: autorizacion.Genitourinario,
                    Laboraen: autorizacion.Laboraen,
                    Linfatico: autorizacion.Linfatico,
                    Medicoordeno: autorizacion.Medicoordeno,
                    Motivoconsulta: autorizacion.Motivoconsulta,
                    Municipio_Afiliado: autorizacion.Municipio_Afiliado,
                    Neurologico: autorizacion.Neurologico,
                    Nombre: autorizacion.Nombre,
                    Norefiere: autorizacion.Norefiere,
                    Observaciones: autorizacion.Observaciones,
                    Oftalmologico: autorizacion.Oftalmologico,
                    Osteomioarticular: autorizacion.Osteomioarticular,
                    Osteomuscular: autorizacion.Osteomuscular,
                    Otorrinoralingologico: autorizacion.Otorrinoralingologico,
                    Otros_Signos_Vitales: autorizacion.Otros_Signos_Vitales,
                    Planmanejo: autorizacion.Planmanejo,
                    Presión_Arterial: autorizacion.Presión_Arterial,
                    Recomendaciones: autorizacion.Recomendaciones,
                    Respiratorio: autorizacion.Respiratorio,
                    Resultayudadiagnostica: autorizacion.Resultayudadiagnostica,
                    Sexo: autorizacion.Sexo,
                    Signos_Vitales: autorizacion.Signos_Vitales,
                    Tegumentario: autorizacion.Tegumentario,
                    Telefono: autorizacion.Telefono,
                    Timeconsulta: autorizacion.Timeconsulta,
                    tipo_afiliado: autorizacion.Tipo_Afiliado,
                    Tipo_Orden: autorizacion.Tipo_Orden,
                    Tipodiagnostico: autorizacion.Tipodiagnostico,
                    id: autorizacion.id,
                    Firma: autorizacion.Firma,
                });
            },
            async printPDF() {

                swal({
                    title: 'Esta seguro de realizar la impresión!',
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(async willDelete => {
                    if (willDelete) {

                    await this.fillData(this.selected);
                    let pdf = {};
                    let ordens = [];
                    await axios.get(`/api/orden/citapaciente/${this.cita_paciente}/ordens`)
                        .then(res => ordens = res.data);
                    let [page, numMeses] = ordens[0].paginacion.split('/');
                    let totalOrdens = ordens.length;
                    for (let i = 0; i < totalOrdens; i++) {
                        this.filterArticulos = this.data.articulos.filter(medi => parseInt(page) <=
                            parseInt(medi.NumMeses));
                        pdf = await this.objectForm(i + 1, page, numMeses, ordens[i].id);
                        await this.print(pdf);
                        page++;
                    }
                }
            });
        },
        objectForm(numero, page, total, orden_id) {
            return (this.objectFrm = {
                Primer_Nom: this.autorizacion.Primer_Nom,
                Segundo_Nom: this.autorizacion.Segundo_Nom,
                Primer_Ape: this.autorizacion.Primer_Ape,
                Segundo_Ape: this.autorizacion.Segundo_Ape,
                Tipo_Doc: this.autorizacion.Tipo_Doc,
                Num_Doc: this.autorizacion.Cedula,
                Edad_Cumplida: this.autorizacion.Edad_Cumplida,
                Sexo: this.autorizacion.Sexo,
                IPS: this.autorizacion.Nombre_IPS,
                Direccion_Residencia: this.autorizacion.Direccion_Residencia,
                // Correo: this.autorizacion.Correo,
                Correo: this.Email ? this.Email : this.autorizacion.Correo,
                Telefono: this.autorizacion.Telefono,
                Celular: this.autorizacion.Celular,
                Tipo_Afiliado: this.autorizacion.Tipo_Afiliado,
                Estado_Afiliado: this.autorizacion.Estado_Afiliado,
                dx_principal: this.autorizacion.Codigo_CIE10,
                cita_paciente_id: this.autorizacion.citaPaciente_id,
                orden_id: orden_id,
                fecha_solicitud: this.autorizacion.Fechaorden,
                Firma: this.autorizacion.Medico_Firma,
                fecha_auditoria: this.autorizacion.created_at,
                Firma_Auditor: this.Firma_Auditor,
                type: "formula",
                medicamentos: this.filterArticulos,
                numero,
                page,
                total
            });
        },
        fillData(selected) {
            this.data.articulos = [];

            this.Firma_Auditor = selected[0].Auth_Firma;

            selected.forEach(select => {

                let object = {
                    id: select.Codesumi_id,
                    Codigo: select.Codigo,
                    nombre: select.Nombre,
                    Cantidadosis: select.Cantidad_Dosis,
                    Unidadmedida: select.Unidad_Medida,
                    Via: select.Via,
                    Frecuencia: select.Frecuencia,
                    Unidadtiempo: select.Unidad_Tiempo,
                    Duracion: select.Duracion,
                    Cantidadmensual: select.Cantidad_Mensual,
                    NumMeses: select.Num_Meses,
                    Observacion: select.Observacion,
                    Requiere_autorizacion: select.Requiere_autorizacion,
                    Cantidadpormedico: select.Cantidad_Medico,
                    PosViaAdmin: select.Via,
                    Auth_Nota: select.Auth_Nota,
                };

                this.data.articulos.push(object);
            });
        },
        abrirModal(autorizacion) {
            this.autorizacion = autorizacion;
            this.fillListOfFiles(autorizacion);
            this.cita_paciente = autorizacion.citaPaciente_id;
            this.dialog = true;
            this.Email = "";
        },
        enviarAccion(nameAccion) {

            if (nameAccion == "") {
                swal({
                    title: "Debe elegir una acción",
                    icon: "warning"
                });
                return;
            }

            if (this.observaciones == "") {
                swal({
                    title: "Debe llenar la Observacion",
                    icon: "warning"
                });
                return;
            }

            const msg =
                "Esta seguro de cambiar el estado de esta orden a " + nameAccion;
            swal({
                title: msg,
                icon: "warning",
                buttons: ["Cancelar", "Confirmar"],
                dangerMode: true
            }).then(willDelete => {
                if (willDelete) {
                    let dataSend = {
                        observaciones: this.observaciones,
                        autorizaciones: this.selected,
                        type: "Medicamentos"
                    }

                    if (nameAccion == "Inadecuado") {
                        axios
                            .post("/api/autorizacion/StateInad", dataSend)
                            .then(res => console.log("res.data ", res.data))
                            .catch(err => console.log(err.response));
                    } else if (nameAccion == "Negado") {
                        axios
                            .post("/api/autorizacion/StateNeg", dataSend)
                            .then(res => console.log("res.data ", res.data))
                            .catch(err => console.log(err.response));
                    } else if (nameAccion == "Anulado") {
                        axios
                            .post("/api/autorizacion/StateAnu", dataSend)
                            .then(res => console.log("res.data ", res.data))
                            .catch(err => console.log(err.response));
                    }
                    this.cerrarModal();
                    this.find();
                }
            });

        },
        cerrarModal() {
            this.idAutorizacion = 0;
            this.dialog = false;
            this.action = "";
            this.autorizaciones = [];
            this.observaciones = "";
            this.data.articulos = [];
            this.selected = [];
        },
        fillListOfFiles(auth) {
            axios
                .get(`/api/file/getFilesByPaciente/${auth.citaPaciente_id}`)
                .then(res => {
                    this.listOfFiles = res.data;
                });
        },
        generate(anexo) {
            console.log("path", anexo);
            window.open(anexo.Path, "_blank");
        },
        async print(pdf) {
            await axios
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
        show() {
            if (this.selected.length > 0 &&
                (this.autorizacion.detaarticulordens[0].Estado_id == 7 ||
                    this.autorizacion.detaarticulordens[0].Estado_id == 1)) {
                return true;
            }
            return false;
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
            swal({
                title: '¿Desea actualizar el email del usuario en los datos del paciente?',
                icon: "warning",
                buttons: ["Cancelar", "Confirmar"],
                dangerMode: true
            }).then(async willDelete => {
                if (willDelete) {
                    await axios
                        .put(`/api/paciente/update_email/${this.autorizacion.Paciente_id}`, {
                            Correo: this.Email
                        })
                        .then(res => {
                            swal({
                                title: "¡Orden enviada y correo actualizado con exito!",
                                text: ` `,
                                timer: 3000,
                                icon: "success",
                                buttons: false
                            });
                        });
                    this.Email = "";
                } else {
                    swal({
                        title: "¡Orden enviada con exito!",
                        text: ` `,
                        timer: 3000,
                        icon: "success",
                        buttons: false
                    });
                    this.Email = "";
                }
            });

            this.fillData(this.selected);
            this.filterArticulos = this.data.articulos;
            var pdf = this.objectForm(null, null, null, this.autorizacion.id);
            pdf.SendEmail = true;

            await axios
                .post("/api/pdf/print-pdf", pdf, {
                    responseType: "arraybuffer"
                })
                .then(res => {})
                .catch(err => (err.response));
        },
        fetchProveedor() {
            this.loading = true;
            axios.post('/api/sedeproveedore/getSedePrestadorByName', {
                nombre: this.searchProveedor
            })
                .then(res => {
                    this.ListSedeProveedor = res.data
                    this.loading = false;
                })
                .catch(err => this.showError(err.response.data.message))
        },
        updatePrestador() {
            console.log(this.autorizacion)
            if (this.sedeProveedorEntregaId == 0) {
                swal({
                    title: "Debe elegir una sede",
                    icon: "warning"
                });
                return;
            }
            if (this.sedeProveedorObservacion === "") {
                swal({
                    title: "Debe ingresar una observación",
                    icon: "warning"
                });
                return;
            }
            const data = {
                ubicacion_id: this.sedeProveedorEntregaId,
                observacion_ubicacion: this.sedeProveedorObservacion
            };
            axios.post("/api/orden/updadateOrdenByCita/" + this.autorizacion.Cita_id, data)
                .then((res) => {
                    this.$alerSuccess(res.data.message);
                    this.sedeProveedorEntregaId = 0;
                    this.dialogSede = false;
                    this.sedeProveedorObservacion = "";
                })
        }
    }
};

</script>

<style lang="scss" scoped>
</style>
