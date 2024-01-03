<template>
    <v-card>
        <v-dialog v-model="preload" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Tranquilo procesamos tu solicitud !
                    <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
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
                                                        label="Fecha Ordenamiento" outlined readonly></v-text-field>
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
                                                        <v-checkbox disabled color="primary" hide-details
                                                            v-model="Diagnostico.item.Esprimario"></v-checkbox>
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
                                        <v-alert v-slot:no-results :value="true" color="error" icon="warning">Your
                                            search for "{{ search }}" found no results.</v-alert>
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
                        <v-card-title class="headline grey lighten-2">Servicios</v-card-title>
                        <v-data-table class="fb-table-elem" :headers="cupOrderHeaders" :items="autorizacion.cupordens"
                            item-key="index" v-model="selected" select-all>
                            <template v-slot:items="props">
                                <td class="text-xs-center">
                                    <v-checkbox color="primary" :input-value="props.selected" v-model="selected"
                                        :value="props.item" multiple hide-details></v-checkbox>
                                </td>
                                <td class="text-xs-center">
                                    <div class="datatable-cell-wrapper">{{ props.item.id }}</div>
                                </td>
                                <td class="text-xs-center">
                                    <div class="datatable-cell-wrapper">{{ props.item.Cup_Codigo }}</div>
                                </td>
                                <td class="text-xs-center">
                                    <div class="datatable-cell-wrapper">{{ props.item.Cup_Nombre }}</div>
                                </td>
                                <td class="text-xs-center">
                                    <div class="datatable-cell-wrapper">{{ props.item.observaciones }}</div>
                                </td>
                                <td class="text-xs-center" @click="openDialog(props.item)">
                                    {{ props.item.Sede_Nombre }}
                                    <v-dialog v-model="prestador" persistent v-if="can('auditoria.servicios.change')"
                                        width="500">
                                        <v-card>
                                            <v-card-title class="headline grey lighten-2" primary-title>
                                                Modificación Prestador
                                            </v-card-title>
                                            <v-card-text>
                                                <v-autocomplete label="Edit" :items="filteredSedeProveedor"
                                                    item-value="id" item-text="join" v-model="cupAux.Sede_Id"
                                                    :loading="loading" required></v-autocomplete>
                                                <v-textarea name="input-7-1" label="Observacion" v-model="motivo">
                                                </v-textarea>
                                            </v-card-text>
                                            <v-divider></v-divider>
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn color="primary" text @click="updatePrestador()">
                                                    Actualizar
                                                </v-btn>
                                                <v-btn color="red" text @click="prestador = false">
                                                    Cerrar
                                                </v-btn>
                                            </v-card-actions>
                                        </v-card>
                                    </v-dialog>
                                </td>
                                <td class="text-xs-center">
                                    <div class="datatable-cell-wrapper">{{ props.item.Sede_Direccion }}</div>
                                </td>
                                <td class="text-xs-center">
                                    <div class="datatable-cell-wrapper">{{ props.item.Sede_Telefono }}</div>
                                </td>
                                <td class="text-xs-center">
                                    <v-edit-dialog :return-value.sync="props.item.Cup_Cantidad" large lazy persistent
                                        cancel-text="Cancelar" save-text="Cambiar" @save="save(props.item)">
                                        <div>{{ props.item.Cup_Cantidad }}</div>
                                        <template v-slot:input>
                                            <div class="mt-3 title">Update Cantidad</div>
                                        </template>
                                        <template v-slot:input>
                                            <v-text-field v-model="props.item.Cup_Cantidad" label="Edit" single-line
                                                counter autofocus></v-text-field>
                                        </template>
                                    </v-edit-dialog>
                                </td>
                                <td class="text-xs-center" v-show="props.item.anexo3">
                                    <v-chip color="primary" v-show="props.item.anexo3" text-color="white">ANEXO 3
                                    </v-chip>
                                </td>
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
                                <v-flex xs6>
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
                                <v-flex xs2 v-show="can('autorizacion.posfechar')">
                                    <v-checkbox v-model="posFechar" label="Pos-Fechar?" color="primary"></v-checkbox>
                                </v-flex>
                                <v-flex xs4 v-if="posFechar === true">
                                    <!--                                    <v-text-field label="Fecha Orden" type="date" v-model="datePosFechar">-->
                                    <!--                                    </v-text-field>-->
                                    <v-menu v-model="menu1" :close-on-content-click="false" :nudge-right="40" lazy
                                        transition="scale-transition" offset-y full-width min-width="290px">
                                        <template v-slot:activator="{ on }">
                                            <v-text-field v-model="datePosFechar" label="Picker without buttons"
                                                prepend-icon="event" readonly v-on="on"></v-text-field>
                                        </template>
                                        <v-date-picker color="info" :min="minDate" v-model="datePosFechar"
                                            @input="menu1 = false"></v-date-picker>
                                    </v-menu>


                                </v-flex>
                            </v-layout>
                            <v-container>
                                <v-textarea name="input-7-1" label="Observacion" v-model="observaciones">
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
                <v-flex md3>
                    <v-select v-model="month" append-icon="search" label="Seleccionar Mes" :items="months"
                        item-text="month" item-value="value" hide-details></v-select>
                </v-flex>
                <v-flex md3>
                    <v-autocomplete v-model="familyType" append-icon="search" label="Tipo Familia"
                        :items="ListFamilyType" item-text="Nombre" item-value="id" :search-input.sync="findFamily"
                        hide-details></v-autocomplete>
                </v-flex>
                <v-flex md4>
                    <v-autocomplete v-model="family" append-icon="search" label="Familia" :items="ListFamily"
                        item-text="Nombre" item-value="id" hide-details></v-autocomplete>
                </v-flex>
                <v-spacer></v-spacer>
                <v-flex md2>
                    <v-btn color="primary" round @click="find()">Buscar</v-btn>
                </v-flex>
                <v-flex xs12>
                    <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details>
                    </v-text-field>
                </v-flex>
            </v-layout>
        </v-container>
        <v-data-table :search="search" :headers="headers" :items="listaAutorizaciones" item-key="index">
            <template v-slot:items="props">
                <td class="text-xs-center">{{ props.item.created_at}}</td>
                <td class="text-xs-center">{{ contadorDias(props.item.created_at)}}</td>
                <td class="text-xs-center">{{ props.item.Departamento}}</td>
                <td class="text-xs-center">{{ props.item.Municipio}}</td>
                <td class="text-xs-center">{{ props.item.Primer_Nom}} {{ props.item.Primer_Ape}}</td>
                <td class="text-xs-center">{{ props.item.Cedula}}</td>
                <td class="text-xs-center">{{ props.item.Nombre_IPS}}</td>
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
        <v-layout row wrap v-if="can('exportar.autorizacionServicios')">
            <v-spacer></v-spacer>
            <v-btn color="dark" round @click="exportExcel()">Exportar a Excel</v-btn>
        </v-layout>
    </v-card>
</template>

<script>
    import {
        mapGetters
    } from 'vuex';
    import XLSX from "xlsx";
    import moment from 'moment';

    export default {
        name: "AutorizacionServicios",
        data() {
            return {
                minDate: moment().format('YYYY-MM-DD'),
                menu1: false,
                preload: false,
                posFechar: false,
                datePosFechar: moment().format('YYYY-MM-DD'),
                listaAutorizaciones: [],
                cupsAutorizaciones: [],
                ListSedeProveedor: [],
                ListFamilyType: [],
                ListFamily: [],
                list: {},
                historiapaciente: [],
                listOfFiles: [],
                selected: [],
                loading: false,
                month: null,
                familyType: null,
                family: null,
                history: {},
                accion: "",
                search: "",
                findFamily: '',
                auditorSign: '',
                pdf: {},
                object: {},
                objectOtr: {},
                objectAux: {},
                autorizacion: {},
                ubicaciones: [],
                procedimientos: [],
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
                listaAutorizacionesShow: [],
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
                data: {
                    Tiporden_id: 4,
                    articulos: [],
                    procedimientos: []
                },
                headers: [

                    {
                        text: "Fecha Ordenamiento",
                        sortable: false,
                        align: "center",
                        value: "FechaOrdenamiento"
                    },
                    {
                        text: "Contador",
                        sortable: false,
                        align: "center",
                        value: ""
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
                cupOrderHeaders: [{
                        text: "Orden Cup",
                        sortable: false,
                        align: "center",
                        value: "id"
                    },
                    {
                        text: "Código",
                        sortable: false,
                        align: "center",
                        value: "Cup_Codigo"
                    },
                    {
                        text: "Nombre",
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
                        text: "Prestador",
                        sortable: false,
                        align: "center",
                        value: "Sede_Nombre"
                    },
                    {
                        text: "Dirección Prestador",
                        sortable: false,
                        align: "center",
                        value: "Sede_Direccion"
                    },
                    {
                        text: "Teléfono Prestador",
                        sortable: false,
                        align: "center",
                        value: "Sede_Telefono"
                    },
                    {
                        text: "Cantidad",
                        sortable: false,
                        align: "center",
                        value: "Cup_Cantidad"
                    },
                ],
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
                dialog: false,
                prestador: false,
                observaciones: "",
                motivo: "",
                cupAux: {},
                idAutorizacion: 0,
                Diagnosticos: [],
                email: "",
                Email: "",
                emailRules: [],
                enableEmail: false,
                paciente: ''
            };
        },
        mounted() {
            this.find();
            this.fetchTipoFamilia();
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
                    let bool;
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
            },
            findFamily: function () {
                if (this.familyType) {
                    axios
                        .post("/api/familia/getFamiliesByType", {
                            familyType_id: this.familyType
                        })
                        .then(res => {
                            this.ListFamily = res.data;
                        })
                        .catch(err => console.log(err.response.data));
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
                            posFechar: this.posFechar,
                            datePosFechar: this.datePosFechar,
                            observaciones: this.observaciones,
                            autorizaciones: this.selected,
                            type: "Servicios"
                        };
                        // if (confirm) {
                        // this.order_id = this.autorizacion.Orden_id;

                        if (nameAccion == "Aprobado") {
                            this.preload = true;
                            axios
                                .post("/api/autorizacion/StateAprob", dataSend)
                                .then(async res => {
                                    this.preload = false;
                                    swal("¡Orden creada de manera exitosa!", {
                                        timer: 2000,
                                        icon: "success",
                                        buttons: false
                                    });
                                    this.setUbicacion(dataSend.autorizaciones);
                                    this.email = true;
                                })
                                .catch(err => {
                                    console.log(err.response);
                                    this.preload = false;
                                });
                        } else if (nameAccion == "Inadecuado") {
                            axios
                                .post("/api/autorizacion/StateInad", dataSend)
                                .then(res => console.log("res.data ", res.data));
                            this.cerrarModal();
                            this.find();
                        } else if (nameAccion == "Negado") {
                            axios
                                .post("/api/autorizacion/StateNeg", dataSend)
                                .then(res => console.log("res.data ", res.data));
                            this.cerrarModal();
                            this.find();
                        } else if (nameAccion == "Anulado") {
                            axios
                                .post("/api/autorizacion/StateAnu", dataSend)
                                .then(res => console.log("res.data ", res.data));
                            this.cerrarModal();
                            this.find();
                        }
                    }
                });
            },
            async imprimir() {
                var auth = this.autorizacion;
                var observaciones = this.observaciones;
                let printPages = {};

                await this.getSignByAuditor()

                this.procedimientos.forEach(ser => {
                    console.log(ser)
                    if (!printPages.hasOwnProperty(ser.sede_id))
                        printPages[ser.sede_id] = [];

                    printPages[ser.sede_id].push(ser);
                });

                for (const p in printPages) {
                    this.procedimientos = printPages[p];
                    let pdf = await this.getPDFOtros(auth, observaciones);

                    await this.print(pdf);
                }
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
                var auth = this.autorizacion;
                var observaciones = this.observaciones;
                let printPages = {};
                this.procedimientos.forEach(ser => {
                    if (!printPages.hasOwnProperty(ser.sede_id))
                        printPages[ser.sede_id] = [];

                    printPages[ser.sede_id].push(ser);
                });

                for (const p in printPages) {
                    this.procedimientos = printPages[p];
                    var pdf = this.getPDFOtros(auth, observaciones);
                    pdf.SendEmail = true;

                    axios
                        .post("/api/pdf/print-pdf", pdf, {
                            responseType: "arraybuffer"
                        })
                        .then(res => {})
                        .catch(err => (err.response));

                }
            },
            cerrarmodalemail() {
                this.cerrarModal();
                this.find();
                this.email = false;
                this.Email = "";
            },
            abrirModal(autorizacion) {
                // this.idAutorizacion = autorizacion.id;
                this.autorizacion = autorizacion;
                this.cita_paciente = autorizacion.citaPaciente_id;
                this.fillHistory(autorizacion);
                this.fillDiagnostic(autorizacion);
                this.fillListOfFiles(autorizacion);
                this.dialog = true;
                this.paciente = autorizacion.Paciente_id
            },
            cerrarModal() {
                // this.idAutorizacion = 0;
                this.autorizacion = {};
                this.dialog = false;
                this.prestador = false;
                this.motivo = "";
                this.accion = "";
                this.observaciones = "";
                this.posFechar = false;
                this.datePosFechar = moment().format('YYYY-MM-DD');
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
                    seco: object.Sexo,
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
                    fechasolicita: autorizacion.FechaOrdenamiento,
                    municipioafiliado: autorizacion.Municipio,
                    observaciones: autorizacion.Observacion,
                    telefono: autorizacion.Telefono,
                    tipoafiliado: autorizacion.Tipo_Afiliado,
                    id: autorizacion.id
                });
            },
            fillDiagnostic(autorizacion) {
                axios
                    .get(`/api/cie10/diagnostico/${autorizacion.citaPaciente_id}`)
                    .then(res => {
                        this.Diagnosticos = res.data.cie10;
                    });
            },
            exportExcel() {

                this.listaAutorizaciones.forEach(auth => {
                    auth.cupordens.forEach(cup => {

                        let obj = cup;
                        obj.Cedula = auth.Cedula;

                        this.cupsAutorizaciones.push(obj);
                    });
                });

                let data = XLSX.utils.json_to_sheet(this.listaAutorizaciones);
                let dataCup = XLSX.utils.json_to_sheet(this.cupsAutorizaciones);
                const workbook = XLSX.utils.book_new();
                const filename = "AutorizacionesServiciosPorOrden";
                const filenameCup = "AutorizacionesServiciosPorCup";
                XLSX.utils.book_append_sheet(workbook, data, filename);
                XLSX.utils.book_append_sheet(workbook, dataCup, filenameCup);
                XLSX.writeFile(workbook, `${filename}.xlsx`);
            },
            printhc() {
                if (this.historiapaciente) {
                    let pdf = this.getPDFHistorial(this.history);
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
                    FechaInicio: object.Datetimeingreso,
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
            setUbicacion(autorizaciones) {
                this.procedimientos = [];

                autorizaciones.forEach(auth => {

                    let obj = {
                        cantidad: auth.Cup_Cantidad,
                        id: auth.Cup_Id,
                        descripcion: auth.Cup_Nombre,
                        codigo: auth.Cup_Codigo,
                        nombre: auth.Servicio_Nombre,
                        observacion: auth.observaciones,
                        sede_id: auth.Sede_Id,
                        ubicacion: auth.Sede_Nombre,
                        direccion: auth.Sede_Direccion,
                        telefono: auth.Sede_Telefono,
                        Auth_Nota: this.observaciones,
                        identificador: auth.id
                    };

                    this.procedimientos.push(obj);

                });
            },
            getPDFOtros(autorizacion, observaciones) {
                return (this.objectOtr = {
                    Primer_Nom: autorizacion.Primer_Nom,
                    Segundo_Nom: autorizacion.SegundoNom,
                    Primer_Ape: autorizacion.Primer_Ape,
                    Segundo_Ape: autorizacion.Segundo_Ape,
                    Tipo_Doc: autorizacion.Tipo_Doc,
                    Num_Doc: autorizacion.Cedula,
                    Edad_Cumplida: autorizacion.Edad_Cumplida,
                    Sexo: autorizacion.Sexo,
                    IPS: autorizacion.Sede_Nombre,
                    Direccion_Residencia: autorizacion.Direccion_Residencia,
                    Correo: this.Email ? this.Email : this.autorizacion.Correo,
                    Telefono: autorizacion.Telefono,
                    Celular: autorizacion.Celular,
                    Tipo_Afiliado: autorizacion.Tipo_Afiliado,
                    Estado_Afiliado: autorizacion.Estado_Afiliado,
                    dx_principal: autorizacion.Codigo_CIE10,
                    cita_paciente_id: this.cita_paciente,
                    orden_id: autorizacion.id,
                    observaciones: observaciones,
                    fecha_auditoria: autorizacion.created_at,
                    Firma: autorizacion.Medico_Firma,
                    Firma_Auditor: this.auditorSign,
                    type: "otros",
                    servicios: this.procedimientos,
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
            find() {
                if (!this.month) {
                    this.month = moment().format('M');
                }

                let data = {
                    month: this.month
                };

                if (this.familyType) {

                    if (!this.family) {
                        swal({
                            title: "Debe elegir una familia",
                            icon: "warning"
                        });
                        return;
                    }

                    let data = {
                        month: this.month,
                        familyType: this.familyType,
                        family: this.family
                    };

                    axios
                        .post("/api/autorizacion/listaAutorizacionesServiciosByType", data)
                        .then(res => {
                            if (res.data.length > 0)
                                this.listaAutorizaciones = res.data
                            else this.listaAutorizaciones = [];
                        });

                } else {

                    let data = {
                        month: this.month
                    };

                    axios
                        .post("/api/autorizacion/allServicios", data)
                        .then(res => {
                            if (res.data.length > 0)
                                this.listaAutorizaciones = res.data
                            else this.listaAutorizaciones = [];
                        });
                }

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
            save(cup) {
                axios.post(`/api/autorizacion/UpdateServ/${cup.id}`, cup)
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
            async print(pdf) {
                await axios.post("/api/pdf/print-pdf", pdf, {
                        responseType: "arraybuffer"
                    })
                    .then(async res => {
                        let blob = new Blob([res.data], {
                            type: "application/pdf"
                        });
                        let link = document.createElement("a");
                        link.href = window.URL.createObjectURL(blob);
                        await window.open(link.href, "_blank");
                    })
                    .catch(err => console.log(err.response));
            },
            openDialog(cup) {
                this.cupAux = cup;
                this.prestador = true
            },
            updatePrestador() {

                if (this.motivo == "") {
                    swal({
                        title: "Debe llenar la Observacion",
                        icon: "warning"
                    });
                    return;
                }
                axios.post(`/api/autorizacion/UpdatePresServ/${this.cupAux.id}`, {
                        ...this.cupAux,
                        Motivo: this.motivo,
                        paciente: this.paciente
                    })
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
            showError(message) {
                swal({
                    title: "¡No puede ser!",
                    text: `${message}`,
                    icon: "warning",
                });
            },
            fetchTipoFamilia() {
                axios.get('/api/tipofamilia/all')
                    .then(res => {
                        this.ListFamilyType = res.data;
                    })
                    .catch(err => this.showError(err.response.data.message))
            },
            async getSignByAuditor() {
                await axios.get('/api/user/getSignByUser')
                    .then(res => {
                        if (res.data) {
                            this.auditorSign = res.data.Firma;
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
