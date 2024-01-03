<template>
    <v-card>
        <v-container pt-3 pb-1>
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
                            <v-tab>
                                <v-icon left>list_alt</v-icon>Adjuntos
                            </v-tab>

                            <v-tab-item>
                                <v-flex>
                                    <v-card flat>
                                        <v-card-title class="headline grey lighten-2">Datos del paciente</v-card-title>
                                        <v-container>
                                            <v-layout wrap align-center>
                                                <v-flex xs6 md3 lg4>
                                                    <v-text-field v-model="history.nombre" label="Nombre" outlined
                                                        readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs6 md3 lg2>
                                                    <v-text-field v-model="history.cedula" label="Cédula" outlined
                                                        readonly></v-text-field>
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
                                                    <v-text-field v-model="history.direccion"
                                                        label="Direccion Residencia" outlined readonly></v-text-field>
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
                                                    <v-text-field v-model="history.observaciones" label="Observaciones"
                                                        outlined readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs6 md3 lg2>
                                                    <v-text-field v-model="history.norefiere" label="No Refiere"
                                                        outlined readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs6 md3 lg3>
                                                    <v-text-field v-model="history.timeconsulta" label="Tiempo Consulta"
                                                        outlined readonly></v-text-field>
                                                </v-flex>
                                                <v-flex xs6 md3 lg2>
                                                    <v-text-field v-model="history.medicoordeno" label="Medico Ordeno"
                                                        outlined readonly></v-text-field>
                                                </v-flex>


                                                <v-flex xs12>
                                                    <v-chip color="red" text-color="white"
                                                        v-if="history.Tienetutela == 1" readonly>
                                                        TIENE TUTELA
                                                    </v-chip>
                                                    <v-chip color="green" text-color="white"
                                                        v-if="history.Tienetutela == 0 || history.Tienetutela == null"
                                                        readonly>
                                                        NO TIENE TUTELA
                                                    </v-chip>
                                                    <v-chip color="green" text-color="white"
                                                        v-if="history.victima_conficto_armado == false || history.victima_conficto_armado == null "
                                                        readonly>
                                                        CÓDIGO BLANCO: No
                                                    </v-chip>
                                                    <v-chip color="blue" dark
                                                        v-if="history.victima_conficto_armado == true" readonly>
                                                        CÓDIGO BLANCO: Si
                                                    </v-chip>
                                                    <v-chip color="success" dark
                                                        v-if="history.portabilidad == false || history.portabilidad == null "
                                                        readonly>
                                                        TIENE PORTABILIAD: No
                                                    </v-chip>
                                                    <v-chip color="blue" dark v-if="history.portabilidad == true"
                                                        readonly>
                                                        TIENE PORTABILIAD: Si
                                                    </v-chip>
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
                                        :items="ciePrincipal" item-key="index" hide-actions expand>
                                        <template v-slot:items="Diagnostico">
                                            <tr @click="Diagnostico.expanded = !Diagnostico.expanded">
                                                <td class="text-xs-center">
                                                    <div class="datatable-cell-wrapper">
                                                        <v-checkbox disabled color="primary"
                                                            v-model="Diagnostico.item.Esprimario" hide-details>
                                                        </v-checkbox>
                                                    </div>
                                                </td>
                                                <td class="text-xs-center">
                                                    <div class="datatable-cell-wrapper">{{ Diagnostico.item.Nombre }}
                                                    </div>
                                                </td>
                                                <td class="text-xs-center">
                                                    <div class="datatable-cell-wrapper">
                                                        {{ Diagnostico.item.Tipodiagnostico }}</div>
                                                </td>
                                            </tr>
                                        </template>
                                        <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                                            Your
                                            search for "{{ search }}" found no results.
                                        </v-alert>
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
                                </v-card>
                            </v-tab-item>
                        </v-tabs>
                    </v-card>
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
                                    <v-chip color="primary" v-show="props.item.mipres" text-color="white">MIPRES</v-chip>
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
                            </template>
                        </v-data-table>
                    </v-card>
                    <v-card>

                        <v-dialog v-model="dialogSede" persistent
                                  width="500">
                            <v-card>
                                <v-card-title class="headline grey lighten-2" primary-title>
                                    Modificación Sede Entrega
                                </v-card-title>
                                <v-card-text>
                                    <v-autocomplete label="Sede" :items="filteredSedeProveedor"
                                                    item-value="id" item-text="join" v-model="sedeProveedorEntregaId"
                                                    :loading="loading" required></v-autocomplete>

                                    <v-textarea
                                        outline
                                        name="input-7-4"
                                        label="Observación"
                                        v-model="sedeProveedorObservacion"
                                    ></v-textarea>
                                </v-card-text>
                                <v-divider></v-divider>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="primary" text @click="updatePrestador()">
                                        Actualizar
                                    </v-btn>
                                    <v-btn color="red" text @click="dialogSede = false,sedeProveedorEntregaId = 0,sedeProveedorObservacion = ''">
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
                                    <v-btn class="mx-0" color="primary" large @click="dialogSede = true" v-show="can('auditoria.medicamentos.sede.change')">Cambiar Sede</v-btn>
                                    <v-spacer></v-spacer>
                                    <v-divider></v-divider>
                                    <v-spacer></v-spacer>
                                </v-flex>
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
                    <v-select v-model="year" label="Seleccionar Año" :items="[actualYear-3,actualYear-2,actualYear-1,actualYear,actualYear+1]"
                        item-text="month" item-value="value" hide-details></v-select>
                </v-flex>
                <v-flex xs6 md4 lg5>
                    <v-select v-model="month" label="Seleccionar Mes" :items="months"
                              item-text="month" item-value="value" hide-details></v-select>
                </v-flex>

<!--                <v-flex xs12 md12 lg12>-->
<!--                    <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>-->
<!--                    </v-text-field>-->
<!--                </v-flex>-->
            </v-layout>
            <v-layout row wrap>
                <v-flex xs6 md4 lg5>
                    <v-text-field label="Cedula" v-model="cedula" type="number"></v-text-field>
                </v-flex>
                <v-flex xs6 md4 lg5>
                    <v-text-field label="Número Orden" v-model="orden" type="number"></v-text-field>
                </v-flex>
                <v-spacer></v-spacer>
                                <v-flex xs6 md3 lg2>
                                    <v-btn color="primary" round @click="find()">Buscar</v-btn>
                                </v-flex>
            </v-layout>

        </v-container>
        <v-data-table :search="search" :headers="headers" :items="listaAutorizaciones" item-key="index" hide-actions>
            <template v-slot:items="props">
                <td class="text-xs-center">{{ props.item.created_at}}</td>
                <td class="text-xs-center">{{ contadorDias(props.item.created_at)}}</td>
                <!-- <td class="text-xs-center">{{ props.item.Fechaorden}}</td>
                <td class="text-xs-center">{{ props.item.Departamento}}</td> -->
                <td class="text-xs-center">{{ props.item.Municipio}}</td>
                <td class="text-xs-center">{{ props.item.Nombre_IPS}}</td>
                <td class="text-xs-center">{{ props.item.Primer_Nom}} {{ props.item.Primer_Ape}}</td>
                <td class="text-xs-center">{{ props.item.Cedula}}</td>
                <td class="text-xs-center">{{ props.item.Descripcion_CIE10}}</td>
                <td class="text-xs-center">{{ props.item.User_Name}} {{ props.item.User_LastName}}</td>
                <td>{{ props.item.id}}</td>
                <td class="text-xs-center">
                    <v-btn color="blue" class="ma-2 white--text" @click="abrirModal(props.item)">
                        Acciones
                        <v-icon right dark>send</v-icon>
                    </v-btn>
                </td>
            </template>
            <template v-slot:no-data>
                <v-alert :value="true" color="error" icon="warning">No hay movimientos en este Estado.</v-alert>
            </template>
        </v-data-table>
        <v-container grid-list-md>
            <v-layout row wrap>
                <v-flex xs12 md10>
                    <div class="text-xs-center">
                        <v-pagination v-model="page" :length="pages" total-visible="10" @input="find()"></v-pagination>
                    </div>
                </v-flex>
                <v-flex xs12 md2>
                    <div class="text-xs-center mt-0">
                        <v-select v-model="items" :items="[5,10,20,50,100]" @change="find()"></v-select>
                    </div>
                </v-flex>
            </v-layout>
        </v-container>
        <v-layout row wrap v-if="can('exportar.autorizacionMedicamentos')">
            <v-spacer></v-spacer>
            <v-btn color="dark" round @click="exportExcel()">Exportar a Excel</v-btn>
        </v-layout>
    </v-card>
</template>
<script>
    import {
        Movimiento
    } from "../../../models/Movimiento";
    import XLSX from "xlsx";
    import moment from 'moment';
    import {
        mapGetters
    } from 'vuex';

    export default {
        data() {
            return {
                orden:null,
                cedula:null,
                actualYear:null,
                year:null,
                items:5,
                pages: 0,
                page: 1,
                loading: false,
                ListSedeProveedor: [],
                sedeProveedorEntregaId: 0,
                sedeProveedorObservacion:"",
                dialogSede : false,
                listaAutorizaciones: [],
                detaAutorizaciones: [],
                listOfFiles: [],
                historiapaciente: [],
                selected: [],
                month: null,
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
                        value: "01"
                    },
                    {
                        month: "Febrero",
                        value: "02"
                    },
                    {
                        month: "Marzo",
                        value: "03"
                    },
                    {
                        month: "Abril",
                        value: "04"
                    },
                    {
                        month: "Mayo",
                        value: "05"
                    },
                    {
                        month: "Junio",
                        value: "06"
                    },
                    {
                        month: "Julio",
                        value: "07"
                    },
                    {
                        month: "Agosto",
                        value: "08"
                    },
                    {
                        month: "Septiembre",
                        value: "09"
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
                headers: [

                    {
                        text: "Fecha Solicitud",
                        sortable: false,
                        align: "center",
                        value: "FechaSolicitud"
                    },
                    {
                        text: "Contador",
                        sortable: false,
                        align: "center",
                        value: ""
                    },
                    // {
                    //     text: "Fecha Orden",
                    //     sortable: false,
                    //     align: "center",
                    //     value: "Fecha_Orden"
                    // },
                    // {
                    //     text: "Departamento",
                    //     sortable: false,
                    //     align: "center",
                    //     value: ""
                    // },
                    {
                        text: "Municipio",
                        sortable: false,
                        align: "center",
                        value: "Municipio"
                    },
                    {
                        text: "Ips Primaria",
                        sortable: false,
                        align: "center",
                        value: "Nombre_IPS"
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
                        text: "Orden",
                        sortable: false,
                        align: "center",
                        value: "id"
                    },
                    {
                        text: "Acciones",
                        sortable: false,
                        align: "center",
                        value: ""
                    },
                ],
                data: {
                    Tiporden_id: 3,
                    articulos: [],
                    procedimientos: []
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
                dataArticuloOrderHeaders: [
                    {
                        text: "",
                        sortable: false,
                        align: "center",
                        value: ""
                    },
                    {
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
                    }
                ],
                estadosMovimiento: Movimiento.estados,
                dialog: false,
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
            const d = new Date();
            this.actualYear = parseInt(d.getFullYear());
            this.year = parseInt(d.getFullYear());
            this.find();
            this.fetchProveedor();
        },
        computed: {
            ...mapGetters(['can']),
            filteredSedeProveedor() {
                return this.ListSedeProveedor.map(SedeProveedor => {
                    return {
                        ...SedeProveedor,
                        join: `${SedeProveedor.Codigo_habilitacion} - ${SedeProveedor.Nombre}`
                    }
                });
            },
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
                            type: "Medicamentos",
                            ...this.autorizacion,
                        };

                        if (nameAccion == "Aprobado") {
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

                var pdf = this.objectForm(this.observaciones);
                var pdfMypres = this.objectFormMypres(this.observaciones);

                if (pdf.medicamentos.length > 0) {

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
            }
                axios
                    .post("/api/pdf/print-pdf", pdfMypres, {
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
            abrirModal(autorizacion) {
                this.selected = [];
                this.autorizacion = autorizacion;
                this.cita_paciente = autorizacion.citaPaciente_id;
                this.fillHistory(autorizacion);
                this.fillDiagnostic(autorizacion);
                this.fillListOfFiles(autorizacion);
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
            fillHistory(autorizacion) {
                let cc = {
                    Num_Doc: autorizacion.Cedula
                };
                axios.post("/api/historiapaciente/gethistoria", cc).then(res => {
                    this.historiapaciente = res.data;
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
                    });
            },
            exportExcel() {
                this.listaAutorizaciones.forEach(auth => {
                    auth.detaarticulordens.forEach(deta => {

                        let obj = deta;
                        obj.Cedula = auth.Cedula;

                        this.detaAutorizaciones.push(obj);
                    });
                });

                let data = XLSX.utils.json_to_sheet(this.listaAutorizaciones);
                let dataDeta = XLSX.utils.json_to_sheet(this.detaAutorizaciones);
                const workbook = XLSX.utils.book_new();
                const filename = "AutorizacionesMedicamentos";
                const filenameDeta = "AutorizacionesServiciosPorDeta";
                XLSX.utils.book_append_sheet(workbook, data, filename);
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
                let obj = [];
                this.data.articulos.forEach(s => {
                    if(!s.mipres){
                        obj.push(s)
                    }
                });
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
                    medicamentos: obj,
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
            objectFormMypres(observaciones) {
                let objMipres = [];
                this.data.articulos.forEach(s => {
                    if(s.mipres){
                        objMipres.push(s)
                    }
                });
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
                    medicamentos: objMipres,
                    nombrePrestador: this.autorizacion.nombrePrestador,
                    direccionPrestador: this.autorizacion.direccionPrestador,
                    nitPrestador: this.autorizacion.nitPrestador,
                    telefono1Prestador: this.autorizacion.telefono1Prestador,
                    telefono2Prestador: this.autorizacion.telefono2Prestador,
                    codigoHabilitacion: this.autorizacion.codigoHabilitacion,
                    municipioPrestador: this.autorizacion.municipioPrestador,
                    departamentoPrestador: this.autorizacion.departamentoPrestador,
                    mensaje: 'M I P R E S'
                });
            },
            fillData(autorizaciones) {
                this.data.articulos = [];

                autorizaciones.forEach(auth => {
                    console.log(auth);
                    let object = {
                        id: auth.Codesumi_id,
                        nombre: auth.Nombre,
                        Codigo: auth.Codigo,
                        Cantidadosis: auth.Cantidad_Dosis,
                        Unidadmedida: auth.Unidad_Medida,
                        Via: auth.Via,
                        Frecuencia: auth.Frecuencia,
                        Unidadtiempo: auth.Unidad_Tiempo,
                        Duracion: auth.Duracion,
                        Cantidadmensual: auth.Cantidad_Mensual,
                        NumMeses: auth.Num_Meses,
                        Observacion: auth.Observacion,
                        Requiere_autorizacion: auth.Requiere_autorizacion,
                        Cantidadpormedico: auth.Cantidad_Medico,
                        PosViaAdmin: auth.Via,
                        Auth_Nota: this.observaciones,
                        mipres: auth.mipres
                    };

                    this.data.articulos.push(object);

                });

            },
            find() {
                if (!this.month) {
                    this.month = moment().format('M');
                }

                let data = {
                    month: this.month,
                    year: this.year,
                    orden: this.orden,
                    cedula: this.cedula
                };

                axios
                    .post('/api/autorizacion/allMedicamentos?page=' + this.page+'&items='+this.items, data)
                    .then(res => {
                            this.listaAutorizaciones = res.data.data;
                        this.pages = res.data.last_page;
                    });
            },
            fillListOfFiles(auth) {
                axios
                    .get(`/api/file/getFilesByPaciente/${auth.citaPaciente_id}`)
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
                        this.auditorSign = res.data.Firma;
                    }

                })
                .catch(err => this.showError(err.response.data.message))
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
                const data ={
                    ubicacion_id: this.sedeProveedorEntregaId,
                    observacion_ubicacion: this.sedeProveedorObservacion
                };
                axios.post("/api/orden/updadateOrdenByCita/"+this.autorizacion.Cita_id,data)
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
