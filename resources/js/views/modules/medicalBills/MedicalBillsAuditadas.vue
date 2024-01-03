<template>
    <v-layout row wrap>

        <v-dialog v-model="dialogInvoice" persistent max-width="976px">
            <v-card>
                <v-toolbar flat color="primary" dark>
                    <v-toolbar-title>Glosas Facturas</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex xs12>
                                <v-card>
                                    <v-card-title>
                                        <v-spacer></v-spacer>
                                        <v-text-field v-model="searchFactura" append-icon="search" label="Buscar"
                                            single-line hide-details></v-text-field>
                                    </v-card-title>
                                    <v-data-table class="elevation-1" :headers="headersInvoice" :items="invoice"
                                        :search="searchFactura">
                                        <template v-slot:items="props">
                                            <td>{{ props.item.numero_factura }}</td>
                                            <td>{{ props.item.valor_Neto }}</td>
                                            <td>{{ props.item.servicio }}</td>
                                            <td>
                                                <v-tooltip top>
                                                    <template v-slot:activator="{ on }">
                                                        <v-btn text icon color="deep-orange" dark v-on="on"
                                                            @click="show(props.item.numero_factura)">
                                                            <v-icon>file_copy</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Adjunto Factura</span>
                                                </v-tooltip>
                                            </td>
                                            <v-tooltip top>
                                                <template v-slot:activator="{ on }">
                                                    <v-btn fab color="primary" small v-on="on"
                                                        @click="showGlosa(props.item)">
                                                        <v-icon>remove_red_eye</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Ver glosas</span>
                                            </v-tooltip>
                                        </template>
                                        <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                                            Your search for "{{ searchFactura }}" found no results.
                                        </v-alert>
                                    </v-data-table>
                                </v-card>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="dialogInvoice = false">Cancelar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogGlosa" v-if="dialogGlosa" fullscreen hide-overlay transition="dialog-bottom-transition" scrollable>
            <v-card tile>
                <v-toolbar card dark color="primary">
                    <v-btn icon dark @click="dialogGlosa = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Glosas</v-toolbar-title>
                    <v-spacer></v-spacer>
                </v-toolbar>
                <template>

                    <v-card-text class="layout justify-center" v-if="invoiceServiceAuditadas.length == 0">
                        Actualmente esta factura {{ af.numero_factura }} no cuenta con
                        glosas, si desea devolver a
                        asignados de click en el
                        boton &nbsp;<p style="color:orange"> "Devolver Auditoria"</p>.
                    </v-card-text>

                    <v-card-text v-else>


                        <v-layout wrap>
                            <v-flex xs12>
                                <v-card>
                                    <v-card-title>
                                        Factura # {{ af.numero_factura }}
                                        <v-spacer></v-spacer>
                                        <v-text-field v-model="searchAc" append-icon="search" label="Buscar"
                                                      single-line hide-details></v-text-field>
                                    </v-card-title>
                                    <v-data-table :headers="headersAc" :items="invoiceServiceAuditadas"
                                                  :search="searchAc" hide-actions>
                                        <template v-slot:items="props">
                                            <td class="text-xs-left">
                                                <v-text-field rows="1" cols="1" v-model="props.item.codigo"
                                                              item-text="codigo" item-value="codigo" readonly>
                                                </v-text-field>
                                            </td>
                                            <td class="text-xs-left">
                                                <v-textarea v-model="props.item.concepto" rows="1" cols="50"
                                                            readonly>
                                                </v-textarea>
                                            </td>
                                            <td class="text-xs-left">
                                                <v-textarea v-model="props.item.descripcion" rows="1" cols="40"
                                                            readonly>
                                                </v-textarea>
                                            </td>
                                            <td class="text-xs-left">
                                                <v-flex sm12 xs12 md12>
                                                    <v-text-field v-model="props.item.valor" readonly>
                                                    </v-text-field>
                                                </v-flex>
                                            </td>
                                        </template>
                                        <template v-slot:no-results>
                                            <v-alert :value="true" color="error" icon="warning">
                                                Your search for "{{ searchAc }}" found no results.
                                            </v-alert>
                                        </template>
                                    </v-data-table>
                                </v-card>
                            </v-flex>
                        </v-layout>



<!--                        <v-expansion-panel v-if="invoiceServiceAuditadas.AC">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-if="invoiceServiceAuditadas.AC.length > 0">-->
<!--                                <template v-slot:actions>-->
<!--                                    <v-icon color="white">$vuetify.icons.expand</v-icon>-->
<!--                                </template>-->
<!--                                <template v-slot:header>-->
<!--                                    <div class="white&#45;&#45;text">AC</div>-->
<!--                                </template>-->
<!--                                <v-layout wrap>-->
<!--                                    <v-flex xs12>-->
<!--                                        <v-card>-->
<!--                                            <v-card-title>-->
<!--                                                Factura # {{ af.numero_factura }}-->
<!--                                                <v-spacer></v-spacer>-->
<!--                                                <v-text-field v-model="searchAc" append-icon="search" label="Buscar"-->
<!--                                                    single-line hide-details></v-text-field>-->
<!--                                            </v-card-title>-->
<!--                                            <v-data-table :headers="headersAc" :items="invoiceServiceAuditadas.AC"-->
<!--                                                :search="searchAc">-->
<!--                                                <template v-slot:items="props">-->
<!--                                                    <td class="text-xs-left">{{ props.item.Num_Doc }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.Fecha_Consulta }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.Codigo_Consulta }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.Diagnostico_Principal }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorneto_Pagar }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-text-field rows="1" cols="1" v-model="props.item.codigo"-->
<!--                                                            item-text="codigo" item-value="codigo" readonly>-->
<!--                                                        </v-text-field>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.concepto" rows="1" cols="50"-->
<!--                                                            readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcion" rows="1" cols="40"-->
<!--                                                            readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-flex sm12 xs12 md12>-->
<!--                                                            <v-text-field v-model="props.item.valor" readonly>-->
<!--                                                            </v-text-field>-->
<!--                                                        </v-flex>-->
<!--                                                    </td>-->
<!--                                                </template>-->
<!--                                                <template v-slot:no-results>-->
<!--                                                    <v-alert :value="true" color="error" icon="warning">-->
<!--                                                        Your search for "{{ searchAc }}" found no results.-->
<!--                                                    </v-alert>-->
<!--                                                </template>-->
<!--                                            </v-data-table>-->
<!--                                        </v-card>-->
<!--                                    </v-flex>-->
<!--                                </v-layout>-->
<!--                            </v-expansion-panel-content>-->
<!--                        </v-expansion-panel>-->

<!--                        <v-divider class="mx-2" inset vertical></v-divider>-->
<!--                        <v-expansion-panel v-if="invoiceServiceAuditadas.AP">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-if="invoiceServiceAuditadas.AP.length > 0">-->
<!--                                <template v-slot:actions>-->
<!--                                    <v-icon color="white">$vuetify.icons.expand</v-icon>-->
<!--                                </template>-->
<!--                                <template v-slot:header>-->
<!--                                    <div class="white&#45;&#45;text">AP</div>-->
<!--                                </template>-->
<!--                                <v-layout wrap>-->
<!--                                    <v-flex xs12>-->
<!--                                        <v-card>-->
<!--                                            <v-card-title>-->
<!--                                                Factura # {{ af.numero_factura }}-->
<!--                                                <v-spacer></v-spacer>-->
<!--                                                <v-text-field v-model="searchAp" append-icon="search" label="Buscar"-->
<!--                                                    single-line hide-details></v-text-field>-->
<!--                                            </v-card-title>-->
<!--                                            <v-data-table :headers="headersAp" :items="invoiceServiceAuditadas.AP"-->
<!--                                                :search="searchAp">-->
<!--                                                <template v-slot:items="props">-->
<!--                                                    <td class="text-xs-left">{{ props.item.Num_Doc }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.Fecha_Procedimiento }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.Numero_Autorizacion }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.Codigo_Procedimiento }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.Valor_Procedimiento }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-text-field rows="1" cols="1" v-model="props.item.codigo"-->
<!--                                                            readonly item-text="codigo" item-value="codigo">-->
<!--                                                        </v-text-field>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.concepto" rows="1" cols="50"-->
<!--                                                            readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcion" readonly rows="1"-->
<!--                                                            cols="40">-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-flex sm12 xs12 md12>-->
<!--                                                            <v-text-field v-model="props.item.valor" readonly>-->
<!--                                                            </v-text-field>-->
<!--                                                        </v-flex>-->
<!--                                                    </td>-->
<!--                                                </template>-->
<!--                                                <template v-slot:no-results>-->
<!--                                                    <v-alert :value="true" color="error" icon="warning">-->
<!--                                                        Your search for "{{ searchAp }}" found no results.-->
<!--                                                    </v-alert>-->
<!--                                                </template>-->
<!--                                            </v-data-table>-->
<!--                                        </v-card>-->
<!--                                    </v-flex>-->
<!--                                </v-layout>-->
<!--                            </v-expansion-panel-content>-->
<!--                        </v-expansion-panel>-->

<!--                        <v-divider class="mx-2" inset vertical></v-divider>-->
<!--                        <v-expansion-panel v-if="invoiceServiceAuditadas.AT">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-if="invoiceServiceAuditadas.AT.length > 0">-->
<!--                                <template v-slot:actions>-->
<!--                                    <v-icon color="white">$vuetify.icons.expand</v-icon>-->
<!--                                </template>-->
<!--                                <template v-slot:header>-->
<!--                                    <div class="white&#45;&#45;text">AT</div>-->
<!--                                </template>-->
<!--                                <v-layout wrap>-->
<!--                                    <v-flex xs12>-->
<!--                                        <v-card>-->
<!--                                            <v-card-title>-->
<!--                                                Factura # {{ af.numero_factura }}-->
<!--                                                <v-spacer></v-spacer>-->
<!--                                                <v-text-field v-model="searchAt" append-icon="search" label="Buscar"-->
<!--                                                    single-line hide-details></v-text-field>-->
<!--                                            </v-card-title>-->
<!--                                            <v-data-table :headers="headersAt" :items="invoiceServiceAuditadas.AT"-->
<!--                                                :search="searchAt">-->
<!--                                                <template v-slot:items="props">-->
<!--                                                    <td class="text-xs-left">{{ props.item.Num_Doc }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.Nombre_Servicio }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.Cantidad }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.Valor_Unitario }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.Valor_Total }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-text-field rows="1" cols="1" v-model="props.item.codigo"-->
<!--                                                            readonly item-text="codigo" item-value="codigo">-->
<!--                                                        </v-text-field>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.concepto" rows="1" cols="50"-->
<!--                                                            readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcion" readonly rows="1"-->
<!--                                                            cols="40">-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-flex sm12 xs12 md12>-->
<!--                                                            <v-text-field v-model="props.item.valor" readonly>-->
<!--                                                            </v-text-field>-->
<!--                                                        </v-flex>-->
<!--                                                    </td>-->
<!--                                                </template>-->
<!--                                                <template v-slot:no-results>-->
<!--                                                    <v-alert :value="true" color="error" icon="warning">-->
<!--                                                        Your search for "{{ searchAt }}" found no results.-->
<!--                                                    </v-alert>-->
<!--                                                </template>-->
<!--                                            </v-data-table>-->
<!--                                        </v-card>-->
<!--                                    </v-flex>-->
<!--                                </v-layout>-->
<!--                            </v-expansion-panel-content>-->
<!--                        </v-expansion-panel>-->

<!--                        <v-divider class="mx-2" inset vertical></v-divider>-->
<!--                        <v-expansion-panel v-if="invoiceServiceAuditadas.AM">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-if="invoiceServiceAuditadas.AM.length > 0">-->
<!--                                <template v-slot:actions>-->
<!--                                    <v-icon color="white">$vuetify.icons.expand</v-icon>-->
<!--                                </template>-->
<!--                                <template v-slot:header>-->
<!--                                    <div class="white&#45;&#45;text">AM</div>-->
<!--                                </template>-->
<!--                                <v-layout wrap>-->
<!--                                    <v-flex xs12>-->
<!--                                        <v-card>-->
<!--                                            <v-card-title>-->
<!--                                                Factura # {{ af.numero_factura }}-->
<!--                                                <v-spacer></v-spacer>-->
<!--                                                <v-text-field v-model="searchAm" append-icon="search" label="Buscar"-->
<!--                                                    single-line hide-details></v-text-field>-->
<!--                                            </v-card-title>-->
<!--                                            <v-data-table :headers="headersAm" :items="invoiceServiceAuditadas.AM"-->
<!--                                                :search="searchAm">-->
<!--                                                <template v-slot:items="props">-->
<!--                                                    <td class="text-xs-left">{{ props.item.Num_Doc }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.Producto }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.Numero_Unidades }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.Valorunitario_Medicamento }}-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.Valortotal_Medicamento }}-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-text-field rows="1" cols="1" v-model="props.item.codigo"-->
<!--                                                            item-text="codigo" item-value="codigo">-->
<!--                                                        </v-text-field>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.concepto" rows="1" cols="50"-->
<!--                                                            readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcion" readonly rows="1"-->
<!--                                                            cols="40">-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-flex sm12 xs12 md12>-->
<!--                                                            <v-text-field v-model="props.item.valor" readonly>-->
<!--                                                            </v-text-field>-->
<!--                                                        </v-flex>-->
<!--                                                    </td>-->
<!--                                                </template>-->
<!--                                                <template v-slot:no-results>-->
<!--                                                    <v-alert :value="true" color="error" icon="warning">-->
<!--                                                        Your search for "{{ searchAm }}" found no results.-->
<!--                                                    </v-alert>-->
<!--                                                </template>-->
<!--                                            </v-data-table>-->
<!--                                        </v-card>-->
<!--                                    </v-flex>-->
<!--                                </v-layout>-->
<!--                            </v-expansion-panel-content>-->
<!--                        </v-expansion-panel>-->

                    </v-card-text>
                </template>
                <div style="flex: 1 1 auto;"></div>
                <v-btn color="warning" dark text @click="devolverFactura()">
                    Devolver Auditoria
                </v-btn>
            </v-card>
        </v-dialog>

        <v-flex xs12>
            <v-card-title>
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details>
                </v-text-field>
            </v-card-title>
            <v-data-table :headers="headers" :items="allAuditadas" :search="search">
                <template v-slot:items="props">
                    <td>{{ props.item.Nombre }}</td>
                    <td>{{ props.item.Nit }}</td>
                    <td>{{ props.item.totalFacturas }}</td>
                    <td><strong> $ {{ props.item.totalNeto }}</strong></td>
                    <v-btn color="primary" :loading="loading" :disabled="loading" dark
                        @click="invoiceAuditadas(props.item)">Ver</v-btn>
                    <v-tooltip top>
                        <template v-slot:activator="{ on }">
                            <v-btn fab color="success" small v-on="on" @click="notifyglosa(props.item)">
                                <v-icon>check_circle_outline</v-icon>
                            </v-btn>
                        </template>
                        <span>Notificar a prestador</span>
                    </v-tooltip>
                </template>
                <template v-slot:no-results>
                    <v-alert :value="true" color="error" icon="warning">
                        Your search for "{{ search }}" found no results.
                    </v-alert>
                </template>
            </v-data-table>
        </v-flex>

        <v-dialog v-model="preload" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Estamos procesando su información
                    <v-progress-linear indeterminate color="white" class="mb-0">
                    </v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>

    </v-layout>
</template>

<script>
    import Swal from 'sweetalert2';
    import {
        AdjuntoService
    } from '../../../services';
    export default {
        name: 'MedicalBillsAuditadas',
        data: () => {
            return {
                search: '',
                headers: [{
                        text: 'Nombre prestador',
                        align: 'left',
                        value: 'Nombre'
                    },
                    {
                        text: 'Nit',
                        value: 'Nit'
                    },
                    {
                        text: 'Total facturas',
                        value: 'totalFacturas'
                    },
                    {
                        text: 'Total valor neto',
                        value: 'totalNeto'
                    },
                    {
                        text: '',
                        value: ''
                    }
                ],
                allAuditadas: [],
                prestador: '',
                loading: false,
                invoice: [],
                dialogInvoice: false,
                searchFactura: '',
                headersInvoice: [{
                        text: '# Factura',
                        align: 'left',
                        value: 'numero_factura'
                    },
                    {
                        text: 'Valor neto',
                        align: 'left'
                    },
                    {
                        text: 'Servicio',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: '',
                        sortable: false
                    },
                    {
                        text: '',
                        sortable: false
                    }
                ],
                dialogGlosa: false,
                invoiceServiceAuditadas: [],
                af: [],
                searchAc: '',
                searchAp: '',
                searchAt: '',
                searchAm: '',
                headersAc: [
                    {
                        text: 'Codigo',
                        align: 'left'
                    },
                    {
                        text: 'Concepto',
                        align: 'left'
                    },
                    {
                        text: 'Descripción',
                        align: 'left'
                    },
                    {
                        text: 'Valor',
                        align: 'left'
                    }
                ],
                headersAp: [{
                        text: 'Documento',
                        align: 'left',
                        value: 'Num_Doc'
                    },
                    {
                        text: 'Fecha',
                        align: 'left',
                        value: 'Fecha_Procedimiento'
                    },
                    {
                        text: 'Numero',
                        align: 'left',
                        value: 'Numero_Autorizacion'
                    },
                    {
                        text: 'Codigo',
                        align: 'left',
                        value: 'Codigo_Procedimiento'
                    },
                    {
                        text: 'Valor procedimiento',
                        align: 'left',
                        value: 'Valor_Procedimiento'
                    },
                    {
                        text: 'Codigo',
                        align: 'left'
                    },
                    {
                        text: 'Concepto',
                        align: 'left'
                    },
                    {
                        text: 'Descripción',
                        align: 'left'
                    },
                    {
                        text: 'Valor',
                        align: 'left'
                    }
                ],
                headersAt: [{
                        text: 'Documento',
                        align: 'left',
                        value: 'Num_Doc'
                    },
                    {
                        text: 'Nombre servicio',
                        align: 'left',
                        value: 'Nombre_Servicio'
                    },
                    {
                        text: 'Cantidad',
                        align: 'left',
                        value: 'Cantidad'
                    },
                    {
                        text: 'Valor unitario',
                        align: 'left',
                        value: 'Valor_Unitario'
                    },
                    {
                        text: 'Valor total',
                        align: 'left',
                        value: 'Valor_Total'
                    },
                    {
                        text: 'Codigo',
                        align: 'left'
                    },
                    {
                        text: 'Concepto',
                        align: 'left'
                    },
                    {
                        text: 'Descripción',
                        align: 'left'
                    },
                    {
                        text: 'Valor',
                        align: 'left'
                    }
                ],
                headersAm: [{
                        text: 'Documento',
                        align: 'left',
                        value: 'Num_Doc'
                    },
                    {
                        text: 'Producto',
                        align: 'left',
                        value: 'Producto'
                    },
                    {
                        text: 'Unidades',
                        align: 'left',
                        value: 'Numero_Unidades'
                    },
                    {
                        text: 'Valor unitario',
                        align: 'left',
                        value: 'Valorunitario_Medicamento'
                    },
                    {
                        text: 'Valor total',
                        align: 'left',
                        value: 'Valortotal_Medicamento'
                    },
                    {
                        text: 'Codigo',
                        align: 'left'
                    },
                    {
                        text: 'Concepto',
                        align: 'left'
                    },
                    {
                        text: 'Descripción',
                        align: 'left'
                    },
                    {
                        text: 'Valor',
                        align: 'left'
                    }
                ],
                preload: false
            }
        },
        mounted() {
            this.auditadas();
        },
        methods: {
            auditadas() {
                this.$emit("updateCount");
                axios.get('/api/medicalBills/auditadas').then(res => {
                    this.allAuditadas = res.data;
                }).catch(e => {
                    console.log(e)
                })
            },
            invoiceAuditadas(proveedor) {
                this.clearSearch()
                this.prestador = proveedor.Nit
                this.loading = true;
                axios.get('/api/medicalBills/invoiceAuditadas/' + proveedor.id).then(res => {
                    this.invoice = res.data;
                    this.dialogInvoice = true
                    this.loading = false;
                }).catch(e => {
                    console.log(e)
                });
            },
            async show(factura) {
                if (this.prestador == '' || factura == '') {
                    return;
                }
                let ruta = '/facturascuentasmedicas/' + this.prestador + '/' + factura + '.pdf';
                this.preload = true;
                try {
                    let adj = await AdjuntoService.get(ruta);
                    let blob = new Blob([adj[1]], {
                        type: adj[0]
                    });
                    let link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    window.open(link.href, "_blank");
                    this.preload = false
                } catch (error) {
                    this.$alerError('El adjunto de la factura no existe!');
                    this.preload = false
                }
            },
            showGlosa(afs) {
                this.clearSearch()
                this.af = afs
                this.dialogGlosa = true
                axios.get('/api/medicalBills/invoiceServiceAuditadas/' + this.af.id).then(res => {
                    this.invoiceServiceAuditadas = res.data
                })
            },
            notifyglosa(af) {
                swal({
                    title: "¿Está Seguro(a) de notificar?",
                    text: "Una vez notificada la glosa, ya no podrá regresar!",
                    icon: "info",
                    buttons: ["Cancelar", "Confirmar"],
                    dangerMode: true,
                    content: {
                        element: "input",
                        attributes: {
                            placeholder: "Email Notificacion",
                            type: "email",
                            value: af.emailNotificacion,
                        },
                    },
                }).then(willDelete => {
                    if (willDelete !== null) {
                        let correo = willDelete;
                        if(willDelete == ''){
                            correo = af.emailNotificacion
                        }
                        if(correo == null){
                           return swal("Email Requerido", "El correo de notificacion es requerido", "error");
                        }
                        this.preload = true
                        axios.post('/api/medicalBills/notifyPrestador', {
                            prestador: af.id,
                            email:correo
                        }).then(res => {
                            this.auditadas();
                            this.preload = false
                            swal({
                                title: `${res.data.message}`,
                                text: ' ',
                                type: "success",
                                timer: 3000,
                                icon: "success",
                                buttons: false
                            });
                        }).catch(e => {
                            this.preload = false
                            swal({
                                title: `${e.response.data.message}`,
                                text: ' ',
                                type: "error",
                                timer: 3000,
                                icon: "error",
                                buttons: false
                            });
                        });
                    }
                });
            },
            devolverFactura() {
                axios.post('/api/medicalBills/devolverAuditoria', {
                    af_id: this.af.id
                }).then(res => {
                    this.auditadas();
                    this.dialogInvoice = false
                    this.dialogGlosa = false
                    this.$alerSuccess('Auditoria devuelta con exito!');
                }).catch(e => {
                    console.log(e)
                });
            },
            clearSearch(){
                this.search = ''
                this.searchAc = ''
                this.searchAp = ''
                this.searchAt = ''
                this.searchAm = ''
                this.searchFactura = ''
            }
        }
    }

</script>
