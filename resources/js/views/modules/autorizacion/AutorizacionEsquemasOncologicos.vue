<template>
    <v-card>
        <v-container pt-3 pb-1>
            <v-layout row>
                <v-dialog v-model="dialogDetails" max-width="1200px">
                    <v-card>
                        <v-toolbar flat color="primary" dark>
                            <v-toolbar-title>Detalles Orden</v-toolbar-title>
                        </v-toolbar>
                        <v-data-table class="fb-table-elem" :headers="headerDetalle" :items="listaDetalleOrden">
                            <template v-slot:items="props">
                                <td class="text-xs-center">{{ props.item.id }}</td>
                                <td class="text-xs-center">{{ props.item.Codigo }}</td>
                                <td class="text-xs-center">{{ props.item.Nombre }}</td>
                                <td class="text-xs-center">{{ props.item.Via }}</td>
                                <td class="text-xs-center">{{ Math.round(props.item.Cantidad_Medico) }} {{ props.item.Unidad_Medida }}</td>
                                <td class="text-xs-center">{{ props.item.Frecuencia }} / {{ props.item.Unidad_Tiempo }}</td>
                            </template>
                        </v-data-table>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="dialog" max-width="1000px">
                    <v-card>
                        <v-toolbar flat color="primary" dark>
                            <v-toolbar-title>Historia Clinica</v-toolbar-title>
                        </v-toolbar>
                    </v-card>

                    <v-card>
                        <v-expansion-panel focusable>
                            <v-expansion-panel-content>
                                <template v-slot:header>
                                    <div class="headline grey lighten-2">Archivos Adjuntos</div>
                                </template>
                                <v-card>
                                    <v-data-table class="fb-table-elem" :headers="filesHeaders" :items="listOfFiles"
                                                  item-key="index">
                                        <template v-slot:items="props">
                                            <td class="text-xs-center">
                                                <div class="datatable-cell-wrapper">{{ props.item.Name }}</div>
                                            </td>
                                            <td class="text-xs-center">
                                                <div class="datatable-cell-wrapper">{{ props.item.created_at }}</div>
                                            </td>
                                            <td>
                                                <v-btn text icon color="blue" dark>
                                                    <v-icon @click="generate(props.item)">assignment_turned_in</v-icon>
                                                </v-btn>
                                                <span>Anexo</span>
                                            </td>
                                        </template>
                                    </v-data-table>
                                </v-card>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                    </v-card>

                    <v-card>
                        <v-card-title class="headline grey lighten-2">Medicamentos</v-card-title>
                        <v-flex xs4>
                            <v-menu
                                v-model="isActiveDateOrder"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                lazy
                                transition="scale-transition"
                                offset-y
                                full-width
                                max-width="290px"
                                min-width="290px"
                                >
                                <template v-slot:activator="{ on }">
                                    <VTextField
                                        label="Fecha Autorización"
                                        persistent-hint
                                        prepend-icon="event"
                                        readonly
                                        v-model="fechaorden"
                                        v-on="on" />
                                </template>
                                <VDatePicker
                                    color="primary"
                                    locale="es"
                                    no-title
                                    v-model="fechaorden"
                                    @input="isActiveDateOrder = false" />
                            </v-menu>
                        </v-flex>
                        <v-data-table
                            class="fb-table-elem"
                            :headers="dataArticuloOrderHeaders"
                            :items="listaOrdenesOncologia"
                            item-key="index"
                            v-model="selected"
                            select-all>
                            <template v-slot:items="props">
                                <td class="text-xs-center">
                                    <v-checkbox color="primary" :input-value="props.selected" v-model="selected"
                                                :value="props.item" multiple hide-details></v-checkbox>
                                </td>
                                <td class="text-xs-center"><a href="javascript:void(0)" @click="dialogDetails = true,listaDetalleOrden = props.item.detaarticulordens">{{ props.item.id }}</a></td>
                                <td class="text-xs-center">{{ props.item.day }}</td>
                                <td class="text-xs-center">{{ props.item.paginacion }}</td>
                                <td class="text-xs-center">{{props.item.created_at}}</td>
                            </template>
                        </v-data-table>
                    </v-card>
                    <v-card>
                        <v-card-title>
                            <span class="headline">Acciones</span>
                        </v-card-title>
                        <v-card-text>
                            <v-layout row wrap>
                                <v-flex xs6 md3 lg5>
                                    <v-select v-model="accion" append-icon="search" label="Seleccionar acción"
                                        :items="acciones" item-text="accion" item-value="value" hide-details></v-select>
                                </v-flex>
                                <v-spacer></v-spacer>
                                <v-flex xs6 md3 lg3>
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-btn :disable="historiapaciente.length == 0" text icon color="blue" dark
                                                v-on="on">
                                                <v-icon @click="printhc()">assignment_turned_in</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Historial</span>
                                    </v-tooltip>
                                </v-flex>
                            </v-layout>
                            <v-container>
                                <v-textarea name="input-7-1" label="Observacion" value v-model="observaciones">
                                </v-textarea>
                                <v-btn v-show="selected.length > 0" color="blue" class="ma-2 white--text"
                                    @click="enviarAccion(accion)">
                                    Enviar
                                    <v-icon right dark>send</v-icon>
                                </v-btn>
                            </v-container>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" flat @click="cerrarModal()">Cerrar</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="email" persistent max-width="1000px">
                    <v-card>
                        <v-toolbar flat color="primary" dark>
                            <v-toolbar-title>Enviar o imprimir</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <v-btn color="primary" @click="imprimir()" round>Imprimir</v-btn>
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
            </v-layout>
        </v-container>
        <v-container>
            <v-layout row wrap>
                <v-flex xs6 md4 lg5>
                    <v-select v-model="month" append-icon="search" label="Seleccionar Mes" :items="months"
                        item-text="month" item-value="value" hide-details></v-select>
                </v-flex>
                <v-spacer></v-spacer>
                <v-flex xs6 md3 lg2>
                    <v-btn color="primary" round @click="find()">Buscar</v-btn>
                </v-flex>
                <v-flex xs12 md12 lg12>
                    <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                    </v-text-field>
                </v-flex>
            </v-layout>
        </v-container>
        <v-data-table :search="search" :headers="headers" :items="listaAutorizaciones" item-key="index">
            <template v-slot:items="props">
                <td>
                    <v-btn color="info" @click="abrirModal(props.item.ordens,props.item.Num_Doc)" >Aplicaciones</v-btn>
                </td>
                <td class="text-xs-center">{{ props.item.Num_Doc }}</td>
                <td class="text-xs-center">{{ props.item.paciente }}</td>
                <td class="text-xs-center">{{ props.item.scheme_name }}</td>
                <td class="text-xs-center">{{ props.item.Descripcion_CIE10}}</td>
            </template>
            <template v-slot:no-data>
                <v-alert :value="true" color="error" icon="warning">No hay movimientos en este Estado.</v-alert>
            </template>
        </v-data-table>
        <v-layout row wrap v-if="can('exportar.autorizacionOncologia')">
            <v-spacer></v-spacer>
            <v-btn color="dark" round @click="exportExcel()">Exportar a Excel</v-btn>
        </v-layout>
    </v-card>
</template>
<script>
    import {
        Transaction
    } from "../../../models/Transaction";
    import {
        Movimiento
    } from "../../../models/Movimiento";
    import {
        mapGetters
    } from 'vuex';
    import XLSX from "xlsx";
    import moment from 'moment';
    import {
        async
    } from 'q';

    export default {
        name: 'AutorizacionEsquemasOncologicos',
        data() {
            return {
                listaAutorizaciones: [],
                detaAutorizaciones: [],
                listaOrdenesOncologia: [],
                listOfFiles: [],
                historiapaciente: [],
                listaDetalleOrden: [],
                selected: [],
                isActiveDateOrder: false,
                month: null,
                fechaorden: null,
                accion: "",
                auditorSign: '',
                search: "",
                object: {},
                objectFrm: {},
                objectAux: {},
                autorizacion: {},
                pdf: {
                    FechaInicio: "",
                    Nombre: "",
                    Edad_Cumplida: "",
                    Sexo: "",
                    Antropometricas: "",
                    Atendido_Por: "",
                    Cardiovascular: "",
                    telefono: "",
                    Condiciongeneral: "",
                    Cédula: "",
                    Datetimeingreso: "",
                    Datetimesalida: "",
                    Departamento: "",
                    Destinopaciente: "",
                    Diagnostico_Principal: "",
                    Direccion_Residencia: "",
                    Edad: "",
                    Endocrinologico: "",
                    Enfermedadactual: "",
                    Entidademite: "",
                    Fecha_Nacimiento: "",
                    Fecha_solicita: "",
                    Finalidad: "",
                    Gastrointestinal: "",
                    Genitourinario: "",
                    Laboraen: "",
                    Linfatico: "",
                    Medicoordeno: "",
                    Motivoconsulta: "",
                    Municipio_Afiliado: "",
                    Neurologico: "",
                    Nombre: "",
                    Norefiere: "",
                    Observaciones: "",
                    Oftalmologico: "",
                    Osteomioarticular: "",
                    Osteomuscular: "",
                    Otorrinoralingologico: "",
                    Otros_Signos_Vitales: "",
                    Planmanejo: "",
                    Presión_Arterial: "",
                    Recomendaciones: "",
                    Respiratorio: "",
                    Resultayudadiagnostica: "",
                    Sexo: "",
                    Signos_Vitales: "",
                    Tegumentario: "",
                    Telefono: "",
                    Timeconsulta: "",
                    tipo_afiliado: "",
                    Tipo_Orden: "",
                    Tipodiagnostico: "",
                    id: "",
                    type: ""
                },
                history: {},
                acciones: [{
                        accion: "APROBADO",
                        value: "Aprobado"
                    },
                    {
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
                months: [{
                        month: "Enero",
                        value: "1"
                    },
                    {
                        month: "Febrero",
                        value: "2"
                    },
                    {
                        month: "Marzo",
                        value: "3"
                    },
                    {
                        month: "Abril",
                        value: "4"
                    },
                    {
                        month: "Mayo",
                        value: "5"
                    },
                    {
                        month: "Junio",
                        value: "6"
                    },
                    {
                        month: "Julio",
                        value: "7"
                    },
                    {
                        month: "Agosto",
                        value: "8"
                    },
                    {
                        month: "Septiembre",
                        value: "9"
                    },
                    {
                        month: "Octubre",
                        value: "10"
                    },
                    {
                        month: "Noviembre",
                        value: "11"
                    },
                    {
                        month: "Diciembre",
                        value: "12"
                    }
                ],
                headers: [{
                    text: "",
                    sortable: false,
                    align: "center",
                    value: ""
                },{
                    text: "N.Documento",
                    sortable: false,
                    align: "center",
                    value: "Num_Doc"
                },{
                    text: "Paciente",
                    sortable: false,
                    align: "center",
                    value: "paciente"
                },{
                    text: "Esquema",
                    sortable: false,
                    align: "center",
                    value: ""
                },{
                    text: "Diagnostico",
                    sortable: false,
                    align: "center",
                    value: ""
                }],
                data: {
                    type: 'oncologia',
                    ordenes: [],
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
                        text: "Dia Aplicación",
                        sortable: false,
                        align: "center",
                        value: "Codigo"
                    },
                    {
                        text: "Ciclo",
                        sortable: false,
                        align: "center",
                        value: "Nombre"
                    },
                    {
                        text: "Fecha Ordenamiento",
                        sortable: false,
                        align: "center",
                        value: ""
                    },

                ],
                headerDetalle: [{
                    text: "Id",
                    sortable: false,
                    align: "center",
                    value: ""
                },{
                    text: "CodeSumi",
                    sortable: false,
                    align: "center",
                    value: ""
                },{
                    text: "Medicamento",
                    sortable: false,
                    align: "center",
                    value: ""
                },{
                    text: "Via",
                    sortable: false,
                    align: "center",
                    value: ""
                },{
                    text: "Dosis Formulada",
                    sortable: false,
                    align: "center",
                    value: ""
                },{
                    text: "Frecuencia",
                    sortable: false,
                    align: "center",
                    value: ""
                },{
                    text: "Observación",
                    sortable: false,
                    align: "center",
                    value: ""
                },{
                    text: "Nota farmacia",
                    sortable: false,
                    align: "center",
                    value: ""
                }],
                estadosMovimiento: Movimiento.estados,
                dialog: false,
                dialogDetails: false,
                observaciones: "",
                idAutorizacion: 0,
                estadosMovimientoFormat: [],
                paciente: {},
                Diagnosticos: [],
                email: "",
                Email: "",
                emailRules: [],
                enableEmail: false
            };
        },
        mounted() {
            this.find();
        },
        computed: {
            ...mapGetters(['can']),
            ciePrincipal() {
                return this.Diagnosticos.map(diagnostico => {
                    let bool = false;
                    (diagnostico.Esprimario == 1) ? bool = true: bool = false;
                    return {
                        Esprimario: bool,
                        Nombre: diagnostico.Nombre,
                        Tipodiagnostico: diagnostico.Tipodiagnostico
                    };
                });
            }
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
        methods: {
            contadorDias(fecha) {},
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
                    "Esta seguro de cambiar el estado de esta Autorizacion a " + nameAccion;
                swal({
                    title: msg,
                    icon: "warning",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true
                }).then(async willDelete => {
                    if (willDelete) {
                        let dataSend = {
                            observaciones: this.observaciones,
                            autorizaciones: this.selected,
                            fechaAutorizacion: this.fechaorden,
                            type: "oncologia"
                        };

                        if (nameAccion == "Aprobado") {
                            if(!this.fechaorden) {
                                return swal({
                                    title: "Debe seleccionar la fecha de dispensacion",
                                    icon: "warning"
                                });
                            }
                            await axios
                              .post("/api/autorizacion/StateAprob", dataSend)
                              .then(res => {
                                  this.fillData(dataSend.autorizaciones);
                                  swal("¡Aprobación generada de manera exitosa!", {
                                      timer: 2000,
                                      icon: "success",
                                      buttons: false
                                  });
                              })
                                .catch(err => console.log(err.response));
                            this.email = true;
                        } else if (nameAccion == "Inadecuado") {
                            axios
                                .post("/api/autorizacion/StateInad", dataSend)
                                .then(res => console.log("res.data ", res.data));
                        } else if (nameAccion == "Negado") {
                            axios
                                .post("/api/autorizacion/StateNeg", dataSend)
                                .then(res => console.log("res.data ", res.data));
                        } else if (nameAccion == "Anulado") {
                            axios
                                .post("/api/autorizacion/StateAnu", dataSend)
                                .then(res => console.log("res.data ", res.data));
                        }
                         this.cerrarModal();
                         this.find();



                    }
                });
            },
            async imprimir() {
                await this.getSignByAuditor()
                this.data.ordenes.forEach(r => {
                    let pdf = {
                        type: 'oncologia',
                        firmaAuditor: this.data.firmaAuditor,
                        doc: this.data.doc,
                        orden: r.id,
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

                })
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
                await this.getSignByAuditor()
                this.fillData(this.selected);
                var pdf = this.objectForm(this.observaciones);
                pdf.SendEmail = true;

                axios.post("/api/pdf/print-pdf", pdf, {
                    responseType: "arraybuffer"
                })
                .then(res => {})
                .catch(err => (err.response));
            },
            abrirModal(ordenes,cc) {
                this.data.doc = cc;
                this.fillHistory(cc);
                this.fillListOfFiles(ordenes[0].Cita_id);
                this.listaOrdenesOncologia = ordenes;
                this.dialog = true;
                this.enableEmail = false;
            },
            cerrarmodalemail() {
                this.email = false;
                this.Email = "";
            },
            cerrarModal() {
                this.idAutorizacion = 0;
                this.dialog = false;
                this.accion = "";
                this.observaciones = "";
            },
            fillHistory(cc) {
                let doc = {
                    Num_Doc: cc
                };
                axios.post("/api/historiapaciente/gethistoria", doc).then(res => {
                    this.historiapaciente = res.data;
                    console.log("historiapaciente", this.historiapaciente);
                    if (this.historiapaciente.length == 0) {
                        swal({
                            title: "Historia Clinica",
                            text: "No se tiene historia clinica desde la base de datos",
                            timer: 2000,
                            icon: "error",
                            buttons: false
                        });

                        this.history = this.getObjAux(autorizacion);
                    } else {
                        this.history = this.getObjHistorial(autorizacion);
                    }
                });
            },
            getObjHistorial(autorizacion) {

                let object = this.historiapaciente.find(historia => historia.id == autorizacion.Cita_id);

                if (!object) {
                    object = this.historiapaciente[0];
                }

                return (this.object = {
                    nombre: object.Nombre,
                    edad: object.Edad,
                    sexo: object.Sexo,
                    antropometricas: object.Antropometricas,
                    atendido_por: object.Atendido_Por,
                    cardiovascular: object.Cardiovascular,
                    celular: object.Celular,
                    condiciongeneral: object.Condiciongeneral,
                    cedula: object.Cédula,
                    datetimeingreso: object.Datetimeingreso,
                    datetimesalida: object.Datetimesalida,
                    departamento: object.Departamento,
                    destinopaciente: object.Destinopaciente,
                    diagnosticoprincipal: object.Diagnostico_Principal,
                    Diagnostico_Secundario: object
                        .Diagnostico_Secundario,
                    direccionresidencia: object.Direccion_Residencia,
                    endocrinologico: object.Endocrinologico,
                    enfermedadactual: object.Enfermedadactual,
                    entidademite: object.Entidademite,
                    fechanacimiento: object.Fecha_Nacimiento,
                    fechasolicita: object.Fecha_solicita,
                    finalidad: object.Finalidad,
                    gastrointestinal: object.Gastrointestinal,
                    genitourinario: object.Genitourinario,
                    laboraen: object.Laboraen,
                    linfatico: object.Linfatico,
                    medicoordeno: object.Medicoordeno,
                    motivoconsulta: object.Motivoconsulta,
                    municipioafiliado: object.Municipio_Afiliado,
                    neurologico: object.Neurologico,
                    norefiere: object.Norefiere,
                    observaciones: object.Observaciones,
                    oftalmologico: object.Oftalmologico,
                    osteomioarticular: object.Osteomioarticular,
                    osteomuscular: object.Osteomuscular,
                    otorrinoralingologico: object.Otorrinoralingologico,
                    otrossignosvitales: object.Otros_Signos_Vitales,
                    otrossignosvitales: object.Otros_Signos_Vitales,
                    planmanejo: object.Planmanejo,
                    presionarterial: object.Presión_Arterial,
                    recomendaciones: object.Recomendaciones,
                    respiratorio: object.Respiratorio,
                    resultayudadiagnostica: object.Resultayudadiagnostica,
                    signosvitales: object.Signos_Vitales,
                    tegumentario: object.Tegumentario,
                    telefono: object.Telefono,
                    timeconsulta: object.Timeconsulta,
                    tipoafiliado: object.Tipo_Afiliado,
                    tipoorden: object.Tipo_Orden,
                    tipodiagnostico: object.Tipodiagnostico,
                    id: object.id
                });
            },
            getObjAux(autorizacion) {
                return (this.objectAux = {
                    nombre: `${autorizacion.Primer_Nom} ${autorizacion.Segundo_Nom} ${autorizacion.Primer_Ape} ${autorizacion.Segundo_Ape}`,
                    edad: autorizacion.Edad_Cumplida,
                    sexo: autorizacion.Sexo,
                    cedula: autorizacion.Cédula,
                    departamento: autorizacion.Departamento,
                    direccionresidencia: autorizacion.Direccion_Residencia,
                    fechasolicita: autorizacion.FechaSolicitud,
                    municipioafiliado: autorizacion.Municipio,
                    observaciones: autorizacion.Observacion,
                    telefono: autorizacion.Telefono,
                    tipoafiliado: autorizacion.Tipo_Afiliado,
                    id: autorizacion.id
                });
            },
            fillDiagnostic(autorizacion) {
                axios
                    .get("/api/cie10/diagnostico/" + autorizacion.citaPaciente_id)
                    .then(res => {
                        this.Diagnosticos = res.data.cie10;
                        console.log("Diagnosticos", this.Diagnosticos);
                    });
            },
            exportExcel() {
                this.listaAutorizaciones.forEach(auth => {
                    auth.ordens.forEach(or => {

                        let obj = or;
                        obj.Cedula = auth.Num_Doc;

                        this.listaOrdenesOncologia.push(obj);
                        or.detaarticulordens.forEach(deta => {

                            let objD = deta;
                            deta.Cedula = auth.Num_Doc;

                            this.listaDetalleOrden.push(objD)
                        })


                    });
                });

                let data = XLSX.utils.json_to_sheet(this.listaAutorizaciones);
                let dataOrd = XLSX.utils.json_to_sheet(this.listaOrdenesOncologia);
                let dataDeta = XLSX.utils.json_to_sheet(this.listaDetalleOrden);
                const workbook = XLSX.utils.book_new();
                const filename = "AutorizacionesOncologia";
                const filenameOrdens = "AutorizacionesOrdenesOncologia";
                const filenameDeta = "AutorizacionesMedicamentosOnco";
                XLSX.utils.book_append_sheet(workbook, data, filename);
                XLSX.utils.book_append_sheet(workbook, dataOrd, filenameOrdens);
                XLSX.utils.book_append_sheet(workbook, dataDeta, filenameDeta);
                XLSX.writeFile(workbook, `${filename}.xlsx`);
            },
            printhc() {
                if (this.historiapaciente) {
                    let pdf = this.getPDFHistorial(this.history)
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
            getPDFHistorial(history) {

                let object = this.historiapaciente.find(historia => historia.id == history.id);

                if (!object) {
                    object = this.historiapaciente[0];
                }

                return (this.pdf = {
                    type: "test",
                    FechaInicio: object.Fecha_solicita,
                    Nombre: object.Nombre,
                    Edad_Cumplida: object.Edad,
                    Sexo: object.Sexo,
                    Antropometricas: object.Antropometricas,
                    Atendido_Por: object.Atendido_Por,
                    Cardiovascular: object.Cardiovascular,
                    telefono: object.Celular,
                    Condiciongeneral: object.Condiciongeneral,
                    Cédula: object.Cédula,
                    Datetimeingreso: object.Datetimeingreso,
                    Datetimesalida: object.Datetimesalida,
                    Departamento: object.Departamento,
                    Destinopaciente: object.Destinopaciente,
                    Diagnostico_Principal: object.Diagnostico_Principal,
                    Diagnostico_Secundario: object
                        .Diagnostico_Secundario,
                    Direccion_Residencia: object.Direccion_Residencia,
                    Edad: object.Edad,
                    Endocrinologico: object.Endocrinologico,
                    Enfermedadactual: object.Enfermedadactual,
                    Entidademite: object.Entidademite,
                    Fecha_Nacimiento: object.Fecha_Nacimiento,
                    Fecha_solicita: object.Fecha_solicita,
                    Finalidad: object.Finalidad,
                    Gastrointestinal: object.Gastrointestinal,
                    Genitourinario: object.Genitourinario,
                    Laboraen: object.Laboraen,
                    Linfatico: object.Linfatico,
                    Medicoordeno: object.Medicoordeno,
                    Motivoconsulta: object.Motivoconsulta,
                    Municipio_Afiliado: object.Municipio_Afiliado,
                    Neurologico: object.Neurologico,
                    Nombre: object.Nombre,
                    Norefiere: object.Norefiere,
                    Observaciones: object.Observaciones,
                    Oftalmologico: object.Oftalmologico,
                    Osteomioarticular: object.Osteomioarticular,
                    Osteomuscular: object.Osteomuscular,
                    Otorrinoralingologico: object.Otorrinoralingologico,
                    Otros_Signos_Vitales: object.Otros_Signos_Vitales,
                    Planmanejo: object.Planmanejo,
                    Presión_Arterial: object.Presión_Arterial,
                    Recomendaciones: object.Recomendaciones,
                    Respiratorio: object.Respiratorio,
                    Resultayudadiagnostica: object.Resultayudadiagnostica,
                    Sexo: object.Sexo,
                    Signos_Vitales: object.Signos_Vitales,
                    Tegumentario: object.Tegumentario,
                    Telefono: object.Telefono,
                    Timeconsulta: object.Timeconsulta,
                    tipo_afiliado: object.Tipo_Afiliado,
                    Tipo_Orden: object.Tipo_Orden,
                    Tipodiagnostico: object.Tipodiagnostico,
                    id: object.id
                });
            },
            objectForm(observaciones) {
                return (this.objectFrm = {
                    Primer_Nom: this.autorizacion.Primer_Nom,
                    Segundo_Nom: this.autorizacion.SegundoNom,
                    Primer_Ape: this.autorizacion.Primer_Ape,
                    Segundo_Ape: this.autorizacion.Segundo_Ape,
                    Tipo_Doc: this.autorizacion.Tipo_Doc,
                    Num_Doc: this.autorizacion.Cedula,
                    Edad_Cumplida: this.autorizacion.Edad_Cumplida,
                    Sexo: this.autorizacion.Sexo,
                    IPS: this.autorizacion.Nombre_IPS,
                    Direccion_Residencia: this.autorizacion.Direccion_Residencia,
                    Correo: this.Email ? this.Email : this.autorizacion.Correo,
                    Telefono: this.autorizacion.Telefono,
                    Celular: this.autorizacion.Celular,
                    Tipo_Afiliado: this.autorizacion.Tipo_Afiliado,
                    Estado_Afiliado: this.autorizacion.Estado_Afiliado,
                    dx_principal: this.autorizacion.Codigo_CIE10,
                    cita_paciente_id: this.cita_paciente,
                    orden_id: this.autorizacion.id,
                    fecha_solicitud: this.autorizacion.Fechaorden,
                    fecha_auditoria: this.autorizacion.created_at,
                    Firma: this.autorizacion.Medico_Firma,
                    Firma_Auditor: this.auditorSign,
                    observaciones: observaciones,
                    type: "formula",
                    medicamentos: this.data.articulos,
                    nombrePrestador: this.autorizacion.nombrePrestador,
                    direccionPrestador: this.autorizacion.direccionPrestador,
                    nitPrestador: this.autorizacion.nitPrestador,
                    telefono1Prestador: this.autorizacion.telefono1Prestador,
                    telefono2Prestador: this.autorizacion.telefono2Prestador,
                    codigoHabilitacion: this.autorizacion.codigoHabilitacion,
                    municipioPrestador: this.autorizacion.municipioPrestador,
                    departamentoPrestador: this.autorizacion.departamentoPrestador,
                });
            },
            fillData(autorizaciones) {
                this.data.ordenes = [];

                autorizaciones.forEach(auth => {

                    let object = {
                        id: auth.id,
                        Auth_Nota: this.observaciones
                    };

                    this.data.ordenes.push(object);

                });

            },
            find() {
                if (!this.month) {
                    this.month = moment().format('M');
                }

                let data = {
                    month: this.month
                };
                axios
                    .post("/api/autorizacion/getOncologicalOrdersToAuthorizate", data)
                    .then(res => {
                        console.log(res.data)
                        if (res.data.length > 0)
                            this.listaAutorizaciones = res.data
                        else this.listaAutorizaciones = [];
                    });
            },
            fillListOfFiles(citaPaciente) {
                axios
                    .get(`/api/file/getFilesByPaciente/${citaPaciente}`)
                    .then(res => {
                        this.listOfFiles = res.data;
                    });
            },
            generate(anexo) {
                window.open(anexo.Path, "_blank");
            },
            save(art) {
                axios.post(`/api/autorizacion/UpdateMedic/${art.id}`, art)
                    .then((res) => {
                        this.cerrarModal();
                        this.find();
                        this.selected = [];
                        swal({
                            title: `Orden modificada de manera exitosa`,
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch((err) => console.log(err));
            },
            async getSignByAuditor() {
                await axios.get('/api/user/getSignByUser')
                .then(res => {
                    if (res.data) {
                        this.data.firmaAuditor = res.data.Firma;
                    }

                })
                .catch(err => this.showError(err.response.data.message))
            }
        }
    };

</script>

<style lang="scss" scoped>
    table.table {
        margin: 0 auto;
        width: 98%;
        max-width: 98%;
    }

    .datatable-cell-wrapper {
        width: inherit;
        position: relative;
        z-index: 4;
        padding: 10px 24px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .datatable__expand-content .card__text {
        z-index: 3;
        position: relative;
    }

    .datatable-container {
        position: absolute;
        color: black;
        background-color: white;
        top: 0px;
        left: -14px;
        right: -14px;
        bottom: 0;
        z-index: 2;
        border: 1px solid #ccc;
        box-shadow: 0 4px 5px 0 rgba(0, 0, 0, 0.15), 0 1px 10px 0 rgba(0, 0, 0, 0.15),
            0 2px 4px -1px rgba(0, 0, 0, 0.2);
    }

</style>
