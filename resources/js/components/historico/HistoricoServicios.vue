<template>
    <v-layout>
        <v-flex xs12>
            <v-card>
                <v-card-title class="headline success" style="color:white">
                    <span class="title layout justify-center font-weight-light">Historial de servicios</span>
                </v-card-title>
                <v-form @submit.prevent="find()">
                    <v-container>
                        <v-layout>
                            <v-flex xs12 md4>
                                <v-select v-model="accion" append-icon="search" label="Seleccionar acción"
                                          :items="acciones" item-text="accion" item-value="value" hide-details></v-select>
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

                <v-layout row wrap>
                    <v-dialog v-model="dialog" max-width="1500px" persistent>
                        <v-card>
                            <v-card-title class="headline grey lighten-2">Servicios</v-card-title>
                            <v-data-table class="fb-table-elem" :headers="cupOrderHeaders"
                                          :items="autorizacion.cupordens" item-key="index" v-model="selected" select-all>
                                <template v-slot:items="props">
                                    <td class="text-xs-center">
                                        <v-checkbox color="primary" :input-value="props.selected" v-model="selected"
                                                    :value="props.item" multiple hide-details></v-checkbox>
                                    </td>
                                    <td class="text-xs-center" v-if="props.item.cancelacion === 'SI'"><v-chip label color="error" text-color="white">Cancelada</v-chip></td>
                                    <td class="text-xs-center" v-else-if="props.item.cancelacion === 'NO'"><v-chip label color="success" text-color="white">ASISTENCIA</v-chip></td>
                                    <td class="text-xs-center" v-else-if="props.item.cancelacion === 'INASISTENCIA'"><v-chip label color="warning" text-color="white">Inasistencia</v-chip></td>
                                    <td class="text-xs-center" v-else></td>

                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">{{ props.item.id }}</div>
                                    </td>
                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">
                                            {{ props.item.Cup_Codigo || props.item.Servicio_Codigo }}</div>
                                    </td>
                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">
                                            {{ props.item.Cup_Nombre || props.item.Servicio_Nombre }}</div>
                                    </td>
                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">{{ props.item.observaciones }}</div>
                                    </td>
                                    <td class="text-xs-center" @click="openDialog(props.item)">
                                        {{ props.item.Sede_Nombre }}
                                        <v-dialog v-model="prestador" persistent
                                                  v-if="can('auditoria.servicios.change')" width="500">
                                            <v-card>
                                                <v-card-title class="headline grey lighten-2" primary-title>
                                                    Modificación Prestador
                                                </v-card-title>
                                                <v-card-text>
                                                    <v-autocomplete label="Edit" :items="filteredSedeProveedor"
                                                                    item-value="id" item-text="join" v-model="cupAux.Sede_Id"
                                                                    :loading="loading" required>
                                                    </v-autocomplete>
                                                    <v-textarea name="input-7-1" label="Observacion"
                                                                v-model="observaciones">
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
                                        <div class="datatable-cell-wrapper">{{ props.item.Cup_Cantidad }}</div>
                                    </td>
                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">{{ props.item.Auth_Name }}
                                            {{ props.item.Auth_Apellido }}</div>
                                    </td>
                                    <td class="text-xs-center">
                                        <div class="datatable-cell-wrapper">{{ props.item.Auth_Nota }}</div>
                                    </td>
                                    <td>
                                        <v-btn  color="warning"
                                                :key="index"
                                                :href="'/storage/adjuntosSoportes/'+autorizacion.Cedula+'/'+archivo"
                                                outline
                                                round
                                                small
                                                target="_blank"
                                                v-for="(archivo, index) in JSON.parse(props.item.soportes)">
                                            {{ archivo }}
                                        </v-btn>

                                    </td>
                                </template>
                            </v-data-table>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn v-show="show()" color="green" round @click="enableEmail = !enableEmail">Enviar
                                    por correo
                                </v-btn>
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
                                <v-tab-item v-if="can('auditoria.servicios.auditar')">
                                    <v-card>
                                        <v-card-title>
                                            <span class="headline">Acciones</span>
                                        </v-card-title>
                                        <v-card-text>
                                            <v-layout row wrap>
                                                <v-flex xs5 md3 lg6 px-1>
                                                    <v-select v-model="action" append-icon="search"
                                                              label="Seleccionar acción" :items="actions" item-text="accion"
                                                              item-value="value" hide-details>
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs5 md6 lg6 px-1>
                                                    <span>Elegir una acción para cambiar el estado de la orden
                                                        seleccionada</span>
                                                </v-flex>
                                            </v-layout>
                                            <v-layout row wrap>
                                                <v-flex xs2 v-show="can('autorizacion.posfechar.familiaristas')">
                                                    <v-checkbox
                                                        v-model="posFechar"
                                                        label="Pos-Fechar?"
                                                        color="primary"
                                                    ></v-checkbox>
                                                </v-flex>
                                                <v-flex xs4 v-if="posFechar === true">
                                                    <v-text-field label="Fecha Orden" type="date" v-model="datePosFechar">
                                                    </v-text-field>
                                                </v-flex>
                                                <v-flex xs4 v-if="posFechar === true">
                                                    <v-btn color="info" @click="cambiarFechaOrden">Cambiar</v-btn>
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
                        <v-layout wrap>
                            <v-flex>
                                <v-card>
                                    <v-card-title>
                                        <v-text-field v-model="search" append-icon="search" label="Buscar..."
                                                      single-line hide-details></v-text-field>
                                    </v-card-title>
                                    <v-data-table :headers="headers" :items="listaAutorizaciones" item-key="id"
                                                  :search="search" :expand="expand2">
                                        <template v-slot:items="props">
                                            <td>
                                                <v-btn text icon color="blue" dark>
                                                    <v-icon @click="printhc(props.item)">assignment_turned_in</v-icon>
                                                </v-btn>
                                                <span>Historia</span>
                                            </td>
                                            <td class="text-xs-center"  @click="props.expanded = !props.expanded">{{ props.item.id}}</td>
                                            <td class="text-xs-center"  @click="props.expanded = !props.expanded">{{ props.item.tipoOrden}}</td>
                                            <td class="text-xs-center"  @click="props.expanded = !props.expanded">{{ props.item.created_at}}</td>
                                            <td class="text-xs-center"  @click="props.expanded = !props.expanded">{{ props.item.Departamento}}</td>
                                            <td class="text-xs-center"  @click="props.expanded = !props.expanded">{{ props.item.Municipio}}</td>
                                            <td class="text-xs-center"  @click="props.expanded = !props.expanded">{{ props.item.Ut}}</td>
                                            <td class="text-xs-center"  @click="props.expanded = !props.expanded">{{ props.item.Primer_Nom}} {{props.item.SegundoNom}}
                                                {{ props.item.Primer_Ape}} {{props.item.Segundo_Ape}}</td>
                                            <td class="text-xs-center"  @click="props.expanded = !props.expanded">{{ props.item.Cedula}}</td>
                                            <td class="text-xs-center"  @click="props.expanded = !props.expanded">{{ props.item.Nombre_IPS}}</td>
                                            <td class="text-xs-center"  @click="props.expanded = !props.expanded">{{ props.item.Descripcion_CIE10}}</td>
                                            <td class="text-xs-center"  @click="props.expanded = !props.expanded">{{ props.item.User_Name}}
                                                {{ props.item.User_LastName}}</td>
                                            <td class="text-xs-center">
                                                <v-btn color="blue" class="ma-2 white--text"
                                                       @click="abrirModal(props.item)">
                                                    Acciones
                                                    <v-icon right dark>send</v-icon>
                                                </v-btn>
                                            </td>
                                        </template>
                                        <template v-slot:expand="props">
                                            <v-card flat>
                                                <table>
                                                    <tr v-for="i in props.item.cupordens">
                                                        <div v-show="i.cancelacion">
                                                            <td class="text-xs-center" v-if="i.cancelacion === 'SI'"><v-chip label color="error" text-color="white">Cancelada</v-chip></td>
                                                            <td class="text-xs-center" v-else-if="i.cancelacion === 'NO'"><v-chip label color="success" text-color="white">ASISTENCIA</v-chip></td>
                                                            <td class="text-xs-center" v-else-if="i.cancelacion === 'INASISTENCIA'"><v-chip label color="warning" text-color="white">Inasistencia</v-chip></td>
                                                            <td><strong>CUP: </strong>{{i.Cup_Nombre}}</td>
                                                            <td><strong>SEDE: </strong>{{i.Sede_Nombre}}</td>
                                                            <td v-show="i.cancelacion === 'NO'"><strong>FECHA SOLICITADA: </strong>{{i.fecha_solicitada}}</td>
                                                            <td v-show="i.cancelacion === 'NO'"><strong>FECHA SUGERIDA: </strong>{{i.fecha_sugerida}}</td>
                                                            <td v-show="i.cancelacion === 'SI'"><strong>FECHA CANCELACION: </strong>{{i.fecha_cancelacion}}</td>
                                                            <td v-show="i.cancelacion === 'SI'"><strong>MOTIVO: </strong>{{i.motivo}}</td>
                                                            <td v-show="i.cancelacion === 'SI'"><strong>CAUSA: </strong>{{i.causa}}</td>
                                                            <td><strong>RESPONSABLE: </strong>{{i.responsable}}</td>
                                                            <td><strong>OBSERVACIONES: </strong>{{i.observacionesPrestador}}</td>

                                                        </div>
                                                    </tr>
                                                </table>
                                            </v-card>
                                        </template>
                                        <v-alert v-slot:no-results :value="true" color="error" icon="warning">Your
                                            search for
                                            "{{ search }}" found no results.</v-alert>
                                    </v-data-table>
                                    <v-layout row wrap>
                                        <v-spacer></v-spacer>
                                        <template>
                                            <div class="text-center">
                                                <v-dialog v-model="preload_servicio" persistent width="300">
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
                                        <v-layout row wrap v-if="can('exportar.historicoServicios')">
                                            <v-btn color="warning" dark round @click="expand = !expand">Exportar a Excel
                                            </v-btn>
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
                                                        <!-- <v-spacer></v-spacer> -->
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
                                                    <v-btn color="warning" round @click="exportExcel()">Aceptar</v-btn>
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
import moment from 'moment';
export default {
    name: "Auditoria",
    data() {
        return {
            expand2: false,
            posFechar:false,
            datePosFechar: moment().format('YYYY-MM-DD'),
            preload_servicio: false,
            listaAutorizaciones: [],
            autorizaciones: [],
            listOfFiles: [],
            selected: [],
            ListSedeProveedor: [],
            dialog: false,
            expand: false,
            loading: false,
            prestador: false,
            enableEmail: false,
            Email: '',
            emailRules: [],
            accion: "",
            action: "",
            search: "",
            searchProveedor: "",
            cedula: "",
            Firma_Auditor: null,
            observaciones: "",
            autorizacion: {},
            pdf: {},
            bjectFrm: {},
            cupAux: {},
            show_start_date: false,
            start_date: null,
            show_end_date: false,
            end_date: null,
            new_proveedor: null,
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
                    text: "Tipo servicio",
                    sortable: false,
                    value: ""
                },
                {
                    text: "Fecha Ordenamiento",
                    sortable: false,
                    align: "center",
                    value: "FechaOrdenamiento"
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
            cupOrderHeaders: [{
                text: "",
                sortable: false,
                align: "center",
                value: ""
            },{
                text: "Orden Servicio",
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
                    value: "Auth_Nota"
                },
                {
                    text: "adjuntos",
                    sortable: false,
                    align: "center",
                    value: ""
                }
            ],
        };
    },
    watch: {
        searchProveedor: function () {
            if (this.searchProveedor && this.searchProveedor.length == 5) {
                this.fetchProveedor();
            }
        },
        Email: function (mail) { // e_Mail is the exact name used in v-model
            if (mail !== '') {
                this.emailRules = [v => !!(v.match(
                    /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                )) || 'Email invalido']
            } else if (mail === '') {
                this.emailRules = []
            }
        }
    },
    mounted() {
        this.fetchProveedor();
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
        }
    },
    methods: {
        clearFields() {
            this.cedula = '';
            this.listaAutorizaciones = [];
        },
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
            this.preload_servicio = true;
            axios.post("/api/autorizacion/listAuthServByState", state).then(res => {
                this.preload_servicio = false;
                if (res.data.length > 0)
                    this.listaAutorizaciones = res.data
                else this.listaAutorizaciones = [];
            });
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

            await axios.post("/api/autorizacion/getExcelForServicios", object).then(res => {
                if (res.data.length > 0) {
                    let data = XLSX.utils.json_to_sheet(res.data);
                    const workbook = XLSX.utils.book_new();
                    const filename = "Servicios";
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
                Firma: autorizacion.Firma
            });
        },
        printPDF() {

            swal({
                title: 'Esta seguro de realizar la impresión!',
                icon: "info",
                buttons: ["Cancelar", "Confirmar"],
                dangerMode: true
            }).then(async willDelete => {
                if (willDelete) {
                    var pdf = {};
                    this.fillData(this.selected);

                    let printPages = {};
                    this.data.procedimientos.forEach(ser => {
                        if (!printPages.hasOwnProperty(ser.sede_id))
                            printPages[ser.sede_id] = [];

                        printPages[ser.sede_id].push(ser);
                    });

                    for (const p in printPages) {
                        this.data.procedimientos = printPages[p];
                        pdf = await this.getPDFOtros();
                        await this.print(pdf);
                    }
                }
            });
        },
        getPDFOtros() {
            console.log("autorizacion", this.autorizacion);
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
                Correo: this.Email,
                Telefono: this.autorizacion.Telefono,
                Celular: this.autorizacion.Celular,
                Tipo_Afiliado: this.autorizacion.Tipo_Afiliado,
                Estado_Afiliado: this.autorizacion.Estado_Afiliado,
                dx_principal: this.autorizacion.Codigo_CIE10,
                cita_paciente_id: this.autorizacion.citaPaciente_id,
                orden_id: this.autorizacion.id,
                Firma: this.autorizacion.Medico_Firma,
                fecha_auditoria: this.autorizacion.created_at,
                Firma_Auditor: this.Firma_Auditor,
                type: "otros",
                servicios: this.data.procedimientos,
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
        fillData(selected) {
            this.data.procedimientos = [];

            this.Firma_Auditor = selected[0].Auth_Firma;

            console.log("Firma_Auditor", this.Firma_Auditor);

            selected.forEach(select => {

                let obj = {
                    cantidad: select.Cup_Cantidad,
                    id: select.Cup_Id,
                    descripcion: select.Cup_Nombre || select.Servicio_Nombre,
                    codigo: select.Cup_Codigo || select.Servicio_Codigo,
                    nombre: select.Cup_Nombre || select.Servicio_Nombre,
                    observacion: select.observaciones,
                    sede_id: select.Sede_Id,
                    ubicacion: select.Sede_Nombre,
                    direccion: select.Sede_Direccion,
                    telefono: select.Sede_Telefono,
                    nitPrestador: select.nitPrestador,
                    municipioPrestador: select.municipioPrestador,
                    departamentoPrestador: select.departamentoPrestador,
                    codigoHabilitacion: select.codigoHabilitacion,
                    identificador: select.id,
                    Auth_Nota: select.Auth_Nota
                };

                this.data.procedimientos.push(obj);
            });
        },
        abrirModal(autorizacion) {
            this.autorizacion = autorizacion;
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
                        autorizaciones: this.selected,
                        type: "Servicios"
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
            this.prestador = false;
            this.action = "";
            this.autorizaciones = [];
            this.observaciones = "";
            this.data.procedimientos = [];
            this.selected = [];
        },
        fillListOfFiles(auth) {
            console.log("test", auth);
            axios
                .get(`/api/file/getFilesByPaciente/${auth.citaPaciente_id}`)
                .then(res => {
                    this.listOfFiles = res.data;
                    console.log("files", res.data);
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
                (this.autorizacion.cupordens[0].Estado_id == 7 ||
                    this.autorizacion.cupordens[0].Estado_id == 1)) {
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
            var pdf = this.getPDFOtros();
            pdf.SendEmail = true;

            await axios
                .post("/api/pdf/print-pdf", pdf, {
                    responseType: "arraybuffer"
                })
                .then(res => {

                })
                .catch(err => console.log(err.response));
        },
        updatePrestador() {

            if (this.observaciones == "") {
                swal({
                    title: "Debe llenar la Observacion",
                    icon: "warning"
                });
                return;
            }
            if (this.autorizacion.tipoOrden == 'Servicios propios') {
                axios.post(`/api/autorizacion/UpdatePresServPropio/${this.cupAux.id}`, {
                    ...this.cupAux,
                    Motivo: this.motivo
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

            } else {
                axios.post(`/api/autorizacion/UpdatePresServ/${this.cupAux.id}`, {
                    ...this.cupAux,
                    Motivo: this.observaciones
                })
                    .then((res) => {
                        this.cerrarModal();
                        this.find();
                        swal({
                            title: `Orden modificada de manera exitosa`,
                            text: " ",
                            timer: 2000,
                            icon: "success",
                            buttons: false
                        });
                    })
                    .catch(err => this.showError(err.response.data.message))
            }

        },
        fetchProveedor() {
            this.loading = true;
            axios.post('/api/sedeproveedore/getSedePrestadorByName', {
                nombre: this.searchProveedor
            })
                .then(res => {
                    this.ListSedeProveedor = res.data
                    this.loading = false;
                });
        },
        showError(message) {
            swal({
                title: "¡No puede ser!",
                text: `${message}`,
                icon: "warning",
            });
        },
        openDialog(cup) {
            this.cupAux = cup;
            this.cupAux.paciente = this.autorizacion.Paciente_id
            this.prestador = true
        },
        cambiarFechaOrden(){
            console.log(this.datePosFechar);
            // if(this.datePosFechar > '2023-10-31'){
            //     return this.$alerError("Solo esta autorizado para fechas inferiores al 2023/05/31");
            // }
            if(this.selected.length === 0){
               return this.$alerError("Debe seleccionar minimo un servicio");
            }
            console.log(this.selected);
            const data = {
                'Fechaorden':this.datePosFechar,
                'servicios': this.selected
            };
            axios.post('/api/orden/cambiarFechaServicios', data)
                .then(res => {
                    this.$alerSuccess(res.data.message);
                    this.posFechar = false;
                    this.selected = [];
                    this.datePosFechar = moment().format('YYYY-MM-DD');
                })
        }
    }
};

</script>

<style lang="scss" scoped>
</style>
