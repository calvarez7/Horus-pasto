<template>
    <v-layout row wrap>
        <v-dialog v-model="dialog" max-width="1000px" persistent>
            <v-toolbar flat color="primary" dark>
                <v-toolbar-title>Historia Clinica</v-toolbar-title>
            </v-toolbar>
            <v-tabs vertical>
                <v-tab>
                    <v-icon left>mdi-account</v-icon>Acciones
                </v-tab>
                <v-tab>
                    <v-icon left>list_alt</v-icon>Adjuntos
                </v-tab>
                <v-tab-item v-if="can('auditoria.servicios.auditar')">
                    <v-card>
                        <v-card-title>
                            <span class="headline">Acciones</span>
                        </v-card-title>
                        <v-card-text>
                            <v-layout row wrap>
                                <v-flex xs6 md3 lg6>
                                    <v-select v-model="action" append-icon="search" label="Seleccionar acción"
                                        :items="actions" item-text="accion" item-value="value" hide-details></v-select>
                                </v-flex>
                                <v-flex xs6 md6 lg6>
                                    <span>Elegir una acción para cambiar el estado de la orden seleccionada</span>
                                </v-flex>
                            </v-layout>
                            <v-container>
                                <v-textarea name="input-7-1" label="Observacion" value v-model="observaciones">
                                </v-textarea>
                                <v-btn color="blue" class="ma-2 white--text" @click="enviarAccion(action)">
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
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" flat @click="cerrarModal()">Cerrar</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-tab-item>
            </v-tabs>
        </v-dialog>
        <v-flex xs6 md3 lg2>
            <v-select v-model="accion" append-icon="search" label="Seleccionar acción" :items="acciones"
                item-text="accion" item-value="value" hide-details></v-select>
        </v-flex>
        <v-spacer></v-spacer>

        <v-flex xs6 md3 lg2>
            <v-text-field v-model="cedula" label="Cédula" outlined></v-text-field>
        </v-flex>
        <v-spacer></v-spacer>

        <v-flex xs6 md3 lg3>
            <v-menu ref="show_start_date" :close-on-content-click="false" v-model="show_start_date" :nudge-right="40"
                :return-value.sync="start_date" lazy transition="scale-transition" offset-y full-width min-width="290px"
                max-width="290px">
                <template v-slot:activator="{ on }">
                    <v-text-field v-model="start_date" label="Fecha Inicial" prepend-icon="event" readonly v-on="on">
                    </v-text-field>
                </template>
                <v-date-picker color="primary" locale="es" v-model="start_date" full-width
                    @click="$refs.show_start_date.save(start_date)">
                    <v-spacer></v-spacer>
                    <v-btn flat color="primary" @click="show_start_date = false">Cancelar</v-btn>
                    <v-btn flat color="primary" @click="$refs.show_start_date.save(start_date)">OK</v-btn>
                </v-date-picker>
            </v-menu>
        </v-flex>
        <v-spacer></v-spacer>
        <v-flex xs6 md3 lg3>
            <v-menu ref="show_end_date" :close-on-content-click="false" v-model="show_end_date" :nudge-right="40"
                :return-value.sync="end_date" lazy transition="scale-transition" offset-y full-width min-width="290px"
                max-width="290px">
                <template v-slot:activator="{ on }">
                    <v-text-field v-model="end_date" label="Fecha Final" prepend-icon="event" readonly v-on="on">
                    </v-text-field>
                </template>
                <v-date-picker color="primary" locale="es" v-model="end_date" full-width crollable>
                    <v-spacer></v-spacer>
                    <v-btn flat color="primary" @click="show_end_date = false">Cancelar</v-btn>
                    <v-btn flat color="primary" @click="$refs.show_end_date.save(end_date)">OK</v-btn>
                </v-date-picker>
            </v-menu>
        </v-flex>
        <v-spacer></v-spacer>
        <template>
                <div class="text-center">
                    <v-dialog v-model="preload_service" persistent width="300">
                        <v-card color="primary" dark>
                            <v-card-text>
                                Tranquilo procesamos tu solicitud !
                                <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                            </v-card-text>
                        </v-card>
                    </v-dialog>
                </div>
            </template>
        <v-flex xs6 md3 lg1>
            <v-btn color="primary" round @click="find()">Buscar</v-btn>
        </v-flex>
        <template>
            <v-layout wrap>
                <v-flex>
                    <v-card>
                        <v-card-title>
                            <v-text-field v-model="search" append-icon="search" label="Buscar..." single-line
                                hide-details></v-text-field>
                        </v-card-title>
                        <v-data-table :headers="headers" :items="listaAutorizaciones" :search="search">
                            <template v-slot:items="props">
                                <td>
                                    <v-btn text icon color="blue" dark>
                                        <v-icon @click="printhc(props.item)">assignment_turned_in</v-icon>
                                    </v-btn>
                                    <span>Historia</span>
                                </td>
                                <td>
                                    <v-btn :disabled="props.item.Estado_id != '7' &&  props.item.Estado_id != '1'" text
                                        icon color="green" dark>
                                        <v-icon @click="print(props.item)">assignment_turned_in</v-icon>
                                    </v-btn>
                                    <span>Orden</span>
                                </td>
                                <td class="text-xs-center">{{ props.item.id}}</td>
                                <td class="text-xs-center">{{ props.item.Departamento}}</td>
                                <td class="text-xs-center">{{ props.item.Municipio}}</td>
                                <td class="text-xs-center">{{ props.item.FechaSolicitud}}</td>
                                <td class="text-xs-center">{{ props.item.Primer_Nom}} {{ props.item.Primer_Ape}}</td>
                                <td class="text-xs-center">{{ props.item.Cedula}}</td>
                                <td class="text-xs-center">{{ props.item.Descripcion_CIE10}}</td>
                                <td class="text-xs-center">{{ props.item.Sede_Nombre}}</td>
                                <td class="text-xs-center">{{ props.item.Nombre_IPS}}</td>
                                <td class="text-xs-center">{{ props.item.Cup_Codigo}}</td>
                                <td class="text-xs-center">{{ props.item.Cup_Nombre}}</td>
                                <td class="text-xs-center">{{ props.item.observaciones}}</td>
                                <td class="text-xs-center">{{ props.item.Auth_Nota}}</td>
                                <td class="text-xs-center">{{ props.item.User_Name}} {{ props.item.User_LastName}}</td>
                                <td class="text-xs-center">{{ props.item.FuncionarioSolicitaServicio}}</td>
                                <td class="text-xs-center">
                                    <v-btn color="blue" class="ma-2 white--text" @click="abrirModal(props.item)">
                                        Acciones
                                        <v-icon right dark>send</v-icon>
                                    </v-btn>
                                </td>
                            </template>
                            <v-alert v-slot:no-results :value="true" color="error" icon="warning">Your search for
                                "{{ search }}" found no results.</v-alert>
                        </v-data-table>
                        <v-layout row wrap >
                            <v-spacer></v-spacer>
                            <v-btn color="dark" round @click="exportExcel()">123456</v-btn>
                        </v-layout>
                    </v-card>
                </v-flex>
            </v-layout>
        </template>
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
                listaAutorizaciones: [],
                autorizaciones: [],
                procedimientos: [],
                listOfFiles: [],
                dialog: false,
                action: "",
                accion: "",
                search: "",
                cedula: "",
                observaciones: "",
                bjectFrm: {},
                pdf: {},
                show_start_date: false,
                start_date: null,
                show_end_date: false,
                end_date: null,
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
                        text: "Id",
                        sortable: false,
                        value: "id"
                    },
                    {
                        text: "Departamento",
                        sortable: false,
                        align: "center",
                        value: "Departamento"
                    },
                    {
                        text: "Municipio",
                        sortable: false,
                        align: "center",
                        value: "Municipio"
                    },
                    {
                        text: "FechaSolicitud",
                        sortable: false,
                        align: "center",
                        value: "FechaSolicitud"
                    },
                    // {
                    //   text: "FechaAutorizacion",
                    //   sortable: false,
                    //   align: "center",
                    //   value: ""
                    // },
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
                        text: "Diagnostico",
                        sortable: false,
                        align: "center",
                        value: "Descripcion_CIE10"
                    },
                    {
                        text: "Ips Remitida",
                        sortable: false,
                        align: "center",
                        value: ""
                    },
                    {
                        text: "Ips Atención",
                        sortable: false,
                        align: "center",
                        value: ""
                    },
                    {
                        text: "Cup",
                        sortable: false,
                        align: "center",
                        value: "Cup_Codigo"
                    },
                    {
                        text: "DescripcionCup",
                        sortable: false,
                        align: "center",
                        value: "Cup_Nombre"
                    },
                    {
                        text: "Observacion",
                        sortable: false,
                        align: "center",
                        value: "observaciones"
                    },
                    {
                        text: "Nota Auditoria",
                        sortable: false,
                        align: "center",
                        value: "Auth_Nota"
                    },
                    {
                        text: "Funcionario Carga Servicio",
                        sortable: false,
                        align: "center",
                        value: ""
                    },
                    {
                        text: "FuncionarioSolicitaServicio",
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
                actions: [
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
                preload_service: false
            };
        },
        computed: {
            ...mapGetters(['can'])
        },
        methods: {
            find() {
                if (!this.accion) {
                    swal({
                        title: "Debe elegir algún estado",
                        icon: "warning"
                    });
                    return;
                } else if (!this.cedula && (!this.start_date || !this.end_date)) {
                    swal({
                        title: "Debe elegir una cédula ó la fecha inicial y final en un rango de fechas",
                        icon: "warning"
                    });
                    return;
                } else if (this.start_date && !this.end_date) {
                    swal({
                        title: "Debe elegir la fecha inicial y final en un rango de fechas",
                        icon: "warning"
                    });
                    return;
                } else if (!this.start_date && this.end_date) {
                    swal({
                        title: "Debe elegir la fecha inicial y final en un rango de fechas",
                        icon: "warning"
                    });
                    return;
                }

                let state = {
                    status: this.accion,
                    cedula: this.cedula,
                    fechaStart: this.start_date,
                    fechaEnd: this.end_date
                };
                this.preload_service = true;
                axios.post("/api/autorizacion/listAuthServByState", state).then(res => {
                    this.listaAutorizaciones = res.data;
                    this.preload_service = false
                });
            },
            exportExcel() {
                if (this.listaAutorizaciones.length == 0) {
                    swal({
                        title: "Excel",
                        text: "Se requiere tener items",
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                    return;
                }

                let data = XLSX.utils.json_to_sheet(this.listaAutorizaciones);
                const workbook = XLSX.utils.book_new();
                const filename = "AutorizacionesServicios";
                XLSX.utils.book_append_sheet(workbook, data, filename);
                XLSX.writeFile(workbook, `${filename}.xlsx`);
            },
            printhc(autorizacion) {
                if (autorizacion) {
                    let cc = {
                        Num_Doc: autorizacion.Cedula
                    };
                    axios.post("/api/historiapaciente/gethistoria", cc).then(res => {
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
                            // pdf.type = "test";
                            console.log("pdf", this.pdf);
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
                console.log("this.autorizacion", autorizacion);
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
                    id: autorizacion.id
                });
            },
            print(auth) {
                this.setUbicacion(auth);

                var pdf = this.objectForm(auth);

                console.log("PDF", pdf);

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
                        // link.download = "Results.pdf";
                        // link.click();
                        this.clear();
                        // link.click();
                    })
                    .catch(err => console.log(err.response));
            },
            objectForm(auth) {
                return (this.objectFrm = {
                    Primer_Nom: auth.Primer_Nom,
                    Segundo_Nom: auth.SegundoNom,
                    Primer_Ape: auth.Primer_Ape,
                    Segundo_Ape: auth.Segundo_Ape,
                    Tipo_Doc: auth.Tipo_Doc,
                    Num_Doc: auth.Cedula,
                    Edad_Cumplida: auth.Edad_Cumplida,
                    Sexo: auth.Sexo,
                    IPS: auth.Nombre_IPS,
                    Direccion_Residencia: auth.Direccion_Residencia,
                    Correo: auth.Correo,
                    Celular: auth.Telefono,
                    Tipo_Afiliado: auth.Tipo_Afiliado,
                    Estado_Afiliado: auth.Estado_Afiliado,
                    dx_principal: auth.Codigo_CIE10,
                    cita_paciente_id: auth.citaPaciente_id,
                    orden_id: auth.Orden_id,
                    observaciones: auth.Auth_Nota,
                    type: "otros",
                    // medicamentos: this.data.articulos
                    servicios: this.procedimientos
                });
            },
            setUbicacion(autorizacion) {
                let obj = {};

                obj = {
                    cantidad: autorizacion.Cup_Cantidad,
                    id: autorizacion.Cup_Id,
                    descripcion: autorizacion.Cup_Nombre,
                    codigo: autorizacion.Cup_Codigo,
                    nombre: autorizacion.Cup_Nombre,
                    observacion: autorizacion.observaciones,
                    // ubicacion: ubicacion,
                    // direccion: ubicacion
                    ubicacion: autorizacion.Sede_Nombre,
                    direccion: autorizacion.Sede_Direccion,
                    telefono: autorizacion.Sede_Telefono
                };

                this.procedimientos.push(obj);
            },
            clear() {
                this.procedimientos = [];
            },
            abrirModal(autorizacion) {
                this.autorizaciones.push(autorizacion);
                this.fillListOfFiles(autorizacion);
                this.cita_paciente = autorizacion.citaPaciente_id;
                this.dialog = true;
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
                            autorizaciones: this.autorizaciones,
                            type: "Servicios"
                        };

                        var observaciones = this.observaciones;

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
                        this.find();
                        this.cerrarModal();
                    }
                });

            },
            cerrarModal() {
                this.idAutorizacion = 0;
                this.dialog = false;
                this.action = "";
                this.autorizaciones = [];
                this.observaciones = "";
            },
            fillListOfFiles(auth) {
                console.log("test", auth);
                axios
                    .get(`/api/file/getFiles/${auth.citaPaciente_id}`)
                    .then(res => {
                        this.listOfFiles = res.data;
                        console.log("files", res.data);
                    });
            },
            generate(anexo) {
                console.log("path", anexo);
                window.open(anexo.Path, "_blank");
            },
        }
    };

</script>

<style lang="scss" scoped>
</style>
