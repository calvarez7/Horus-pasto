<template>
    <v-layout row wrap>

        <v-dialog v-model="dialogInvoice" persistent max-width="1000px">
            <v-card>
                <v-toolbar flat color="primary" dark>
                    <v-toolbar-title>Glosar Facturas</v-toolbar-title>
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
                                            <td class="text-xs-right">
                                                <v-edit-dialog large lazy :return-value.sync="props.item.servicio"
                                                    cancel-text="Cancelar" save-text="Cambiar"
                                                    @save="saveServicio(props.item)">
                                                    <div>{{ props.item.servicio }} <v-icon color="warning" small>edit
                                                        </v-icon>
                                                    </div>
                                                    <template v-slot:input>
                                                        <v-select autocomplete :items="servicios"
                                                            item-text="descripcion" item-value="descripcion"
                                                            v-model="props.item.servicio" label="Editar Servicio">
                                                        </v-select>
                                                    </template>
                                                </v-edit-dialog>
                                            </td>
                                            <td>
                                                <v-chip :class="semaforoTd(props.item)">
                                                    {{ `${props.item.diasHabiles} DÍA(S)` }}</v-chip>
                                            </td>
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
                                                    <v-btn fab outline color="warning" small v-on="on"
                                                        @click="glosar(props.item)">
                                                        <v-icon>edit</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Glosar</span>
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
                    <v-toolbar-title>Glosar</v-toolbar-title>
                    <v-spacer></v-spacer>
                </v-toolbar>
                <v-card-text>
                    <v-layout wrap>
                        <v-flex xs12>
                            <v-card>
                                <v-card-title>
                                        <v-container grid-list-xl>
                                            <v-layout row wrap>
                                               <h2>Factura # {{ af.numero_factura }}</h2> -
                                                <v-spacer></v-spacer>
                                                <h3>Valor Restante $ {{valorGlosa}}</h3>
                                            </v-layout>
                                            <v-layout row wrap>
                                                <v-flex xs12 md2>
                                                    <v-select rows="1" cols="1" :items="codigos" label="Codigo"
                                                              v-model="formGlosa.codigo" autocomplete item-text="codigo"
                                                              item-value="codigo" @input="loadConcepto($event)">
                                                    </v-select>
                                                </v-flex>
                                                <v-flex xs12 md4>
                                                    <v-textarea label="Concepto" v-model="formGlosa.concepto" rows="1" cols="50" readonly>
                                                    </v-textarea>
                                                </v-flex>
                                                <v-flex xs12 md3>
                                                    <v-textarea label="Descripcion" v-model="formGlosa.descripcion" rows="1" cols="40">
                                                    </v-textarea>
                                                </v-flex>
                                                <v-flex xs12 md3>
                                                    <v-text-field label="Valor" v-model="formGlosa.valor" type="number"
                                                                  onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                                                  min="1"></v-text-field>
                                                </v-flex>
                                            </v-layout>
                                            <v-layout justify-end row wrap>
                                                <v-flex xs12 md3>
                                                    <v-btn color="success" @click="addglosa(null,formGlosa.codigo,formGlosa.concepto,formGlosa.descripcion,formGlosa.valor)">Agregar</v-btn>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                </v-card-title>
                                <v-data-table :headers="headersAc" :items="invoiceService" :search="searchAc" hide-actions>
                                    <template v-slot:items="props">
                                        <td class="text-xs-left">
                                            <v-select rows="1" cols="1" :items="codigos"
                                                      v-model="props.item.codigo" autocomplete item-text="codigo"
                                                      item-value="codigo" @input="loadConceptoListado($event,props.index)">
                                            </v-select>
                                        </td>
                                        <td class="text-xs-left">
                                            <v-textarea v-model="props.item.concepto" rows="1" cols="50"></v-textarea>
                                        </td>
                                        <td class="text-xs-left">
                                            <v-textarea v-model="props.item.descripcion" rows="1" cols="40"></v-textarea>
                                        </td>
                                        <td class="text-xs-left">
                                            <v-flex sm12 xs12 md12>
                                                <v-text-field v-model="props.item.valor" type="number"
                                                              onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                                              min="1"></v-text-field>
                                            </v-flex>
                                        </td>
                                        <v-tooltip top>
                                            <template v-slot:activator="{ on }">
                                                <v-btn fab color="info" :disabled="loading" small v-on="on"
                                                       @click="addglosa(props.item.id,props.item.codigo,props.item.concepto,props.item.descripcion,props.item.valor)">
                                                    <v-icon>done</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Guardar</span>
                                        </v-tooltip>
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


<!--                    <v-expansion-panel v-if="invoiceService.AC">-->
<!--                        <v-expansion-panel-content class="primary text-xs-center" v-show="invoiceService.AC.length > 0">-->
<!--                            <template v-slot:actions>-->
<!--                                <v-icon color="white">$vuetify.icons.expand</v-icon>-->
<!--                            </template>-->
<!--                            <template v-slot:header>-->
<!--                                <div class="white&#45;&#45;text">AC</div>-->
<!--                            </template>-->
<!--                            <v-layout wrap>-->
<!--                                <v-flex xs12>-->
<!--                                    <v-card>-->
<!--                                        <v-card-title>-->
<!--                                            Factura # {{ af.numero_factura }}-->
<!--                                            <v-spacer></v-spacer>-->
<!--                                            <v-text-field v-model="searchAc" append-icon="search" label="Buscar"-->
<!--                                                single-line hide-details></v-text-field>-->
<!--                                        </v-card-title>-->
<!--                                        <v-data-table :headers="headersAc" :items="invoiceService.AC" :search="searchAc">-->
<!--                                            <template v-slot:items="props">-->
<!--                                                <td class="text-xs-left">{{ props.item.Num_Doc }}</td>-->
<!--                                                <td class="text-xs-left">{{ props.item.Fecha_Consulta }}</td>-->
<!--                                                <td class="text-xs-left">{{ props.item.Codigo_Consulta }}</td>-->
<!--                                                <td class="text-xs-left">{{ props.item.Diagnostico_Principal }}</td>-->
<!--                                                <td class="text-xs-left">{{ props.item.valorneto_Pagar }}</td>-->
<!--                                                <td class="text-xs-left">-->
<!--                                                    <v-select rows="1" cols="1" :items="codigos"-->
<!--                                                        v-model="props.item.codigo" autocomplete item-text="codigo"-->
<!--                                                        item-value="codigo" @input="descripcionCodigo(props.item)">-->
<!--                                                    </v-select>-->
<!--                                                </td>-->
<!--                                                <td class="text-xs-left">-->
<!--                                                    <v-textarea v-model="props.item.concepto" rows="1" cols="50"-->
<!--                                                        readonly>-->
<!--                                                    </v-textarea>-->
<!--                                                </td>-->
<!--                                                <td class="text-xs-left">-->
<!--                                                    <v-textarea v-model="props.item.descripcion" rows="1" cols="40">-->
<!--                                                    </v-textarea>-->
<!--                                                </td>-->
<!--                                                <td class="text-xs-left">-->
<!--                                                    <v-flex sm12 xs12 md12>-->
<!--                                                        <v-text-field v-model="props.item.valor" type="number"-->
<!--                                                            onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"-->
<!--                                                            min="1"></v-text-field>-->
<!--                                                    </v-flex>-->
<!--                                                </td>-->
<!--                                                <v-tooltip top v-if="props.item.estado_id != null">-->
<!--                                                    <template v-slot:activator="{ on }">-->
<!--                                                        <v-btn fab color="success" :disabled="loading" small v-on="on"-->
<!--                                                            @click="addglosa(props.item)">-->
<!--                                                            <v-icon>done_all</v-icon>-->
<!--                                                        </v-btn>-->
<!--                                                    </template>-->
<!--                                                    <span>Guardar</span>-->
<!--                                                </v-tooltip>-->
<!--                                                <v-tooltip top v-else>-->
<!--                                                    <template v-slot:activator="{ on }">-->
<!--                                                        <v-btn fab color="info" :disabled="loading" small v-on="on"-->
<!--                                                            @click="addglosa(props.item)">-->
<!--                                                            <v-icon>done</v-icon>-->
<!--                                                        </v-btn>-->
<!--                                                    </template>-->
<!--                                                    <span>Guardar</span>-->
<!--                                                </v-tooltip>-->
<!--                                            </template>-->
<!--                                            <template v-slot:no-results>-->
<!--                                                <v-alert :value="true" color="error" icon="warning">-->
<!--                                                    Your search for "{{ searchAc }}" found no results.-->
<!--                                                </v-alert>-->
<!--                                            </template>-->
<!--                                        </v-data-table>-->
<!--                                    </v-card>-->
<!--                                </v-flex>-->
<!--                            </v-layout>-->
<!--                        </v-expansion-panel-content>-->
<!--                    </v-expansion-panel>-->

<!--                    <v-divider class="mx-2" inset vertical></v-divider>-->
<!--                    <v-expansion-panel v-if="invoiceService.AP">-->
<!--                        <v-expansion-panel-content class="primary text-xs-center" v-show="invoiceService.AP.length > 0">-->
<!--                            <template v-slot:actions>-->
<!--                                <v-icon color="white">$vuetify.icons.expand</v-icon>-->
<!--                            </template>-->
<!--                            <template v-slot:header>-->
<!--                                <div class="white&#45;&#45;text">AP</div>-->
<!--                            </template>-->
<!--                            <v-layout wrap>-->
<!--                                <v-flex xs12>-->
<!--                                    <v-card>-->
<!--                                        <v-card-title>-->
<!--                                            Factura # {{ af.numero_factura }}-->
<!--                                            <v-spacer></v-spacer>-->
<!--                                            <v-text-field v-model="searchAp" append-icon="search" label="Buscar"-->
<!--                                                single-line hide-details></v-text-field>-->
<!--                                        </v-card-title>-->
<!--                                        <v-data-table :headers="headersAp" :items="invoiceService.AP"-->
<!--                                            :search="searchAp">-->
<!--                                            <template v-slot:items="props">-->
<!--                                                <td class="text-xs-left">{{ props.item.Num_Doc }}</td>-->
<!--                                                <td class="text-xs-left">{{ props.item.Fecha_Procedimiento }}</td>-->
<!--                                                <td class="text-xs-left">{{ props.item.Numero_Autorizacion }}</td>-->
<!--                                                <td class="text-xs-left">{{ props.item.Codigo_Procedimiento }}</td>-->
<!--                                                <td class="text-xs-left">{{ props.item.Valor_Procedimiento }}</td>-->
<!--                                                <td class="text-xs-left">-->
<!--                                                    <v-select rows="1" cols="1" :items="codigos"-->
<!--                                                        v-model="props.item.codigo" autocomplete item-text="codigo"-->
<!--                                                        item-value="codigo" @input="descripcionCodigo(props.item)">-->
<!--                                                    </v-select>-->
<!--                                                </td>-->
<!--                                                <td class="text-xs-left">-->
<!--                                                    <v-textarea v-model="props.item.concepto" rows="1" cols="50"-->
<!--                                                        readonly>-->
<!--                                                    </v-textarea>-->
<!--                                                </td>-->
<!--                                                <td class="text-xs-left">-->
<!--                                                    <v-textarea v-model="props.item.descripcion" rows="1" cols="40">-->
<!--                                                    </v-textarea>-->
<!--                                                </td>-->
<!--                                                <td class="text-xs-left">-->
<!--                                                    <v-flex sm12 xs12 md12>-->
<!--                                                        <v-text-field v-model="props.item.valor" type="number"-->
<!--                                                            onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"-->
<!--                                                            min="1"></v-text-field>-->
<!--                                                    </v-flex>-->
<!--                                                </td>-->
<!--                                                <v-tooltip top v-if="props.item.estado_id != null">-->
<!--                                                    <template v-slot:activator="{ on }">-->
<!--                                                        <v-btn fab color="success" :disabled="loading" small v-on="on"-->
<!--                                                            @click="addglosa(props.item)">-->
<!--                                                            <v-icon>done_all</v-icon>-->
<!--                                                        </v-btn>-->
<!--                                                    </template>-->
<!--                                                    <span>Guardar</span>-->
<!--                                                </v-tooltip>-->
<!--                                                <v-tooltip top v-else>-->
<!--                                                    <template v-slot:activator="{ on }">-->
<!--                                                        <v-btn fab color="info" :disabled="loading" small v-on="on"-->
<!--                                                            @click="addglosa(props.item)">-->
<!--                                                            <v-icon>done</v-icon>-->
<!--                                                        </v-btn>-->
<!--                                                    </template>-->
<!--                                                    <span>Guardar</span>-->
<!--                                                </v-tooltip>-->
<!--                                            </template>-->
<!--                                            <template v-slot:no-results>-->
<!--                                                <v-alert :value="true" color="error" icon="warning">-->
<!--                                                    Your search for "{{ searchAp }}" found no results.-->
<!--                                                </v-alert>-->
<!--                                            </template>-->
<!--                                        </v-data-table>-->
<!--                                    </v-card>-->
<!--                                </v-flex>-->
<!--                            </v-layout>-->
<!--                        </v-expansion-panel-content>-->
<!--                    </v-expansion-panel>-->

<!--                    <v-divider class="mx-2" inset vertical></v-divider>-->
<!--                    <v-expansion-panel v-if="invoiceService.AT">-->
<!--                        <v-expansion-panel-content class="primary text-xs-center" v-show="invoiceService.AT.length > 0">-->
<!--                            <template v-slot:actions>-->
<!--                                <v-icon color="white">$vuetify.icons.expand</v-icon>-->
<!--                            </template>-->
<!--                            <template v-slot:header>-->
<!--                                <div class="white&#45;&#45;text">AT</div>-->
<!--                            </template>-->
<!--                            <v-layout wrap>-->
<!--                                <v-flex xs12>-->
<!--                                    <v-card>-->
<!--                                        <v-card-title>-->
<!--                                            Factura # {{ af.numero_factura }}-->
<!--                                            <v-spacer></v-spacer>-->
<!--                                            <v-text-field v-model="searchAt" append-icon="search" label="Buscar"-->
<!--                                                single-line hide-details></v-text-field>-->
<!--                                        </v-card-title>-->
<!--                                        <v-data-table :headers="headersAt" :items="invoiceService.AT"-->
<!--                                            :search="searchAt">-->
<!--                                            <template v-slot:items="props">-->
<!--                                                <td class="text-xs-left">{{ props.item.Num_Doc }}</td>-->
<!--                                                <td class="text-xs-left">{{ props.item.Nombre_Servicio }}</td>-->
<!--                                                <td class="text-xs-left">{{ props.item.Cantidad }}</td>-->
<!--                                                <td class="text-xs-left">{{ props.item.Valor_Unitario }}</td>-->
<!--                                                <td class="text-xs-left">{{ props.item.Valor_Total }}</td>-->
<!--                                                <td class="text-xs-left">-->
<!--                                                    <v-select rows="1" cols="1" :items="codigos"-->
<!--                                                        v-model="props.item.codigo" autocomplete item-text="codigo"-->
<!--                                                        item-value="codigo" @input="descripcionCodigo(props.item)">-->
<!--                                                    </v-select>-->
<!--                                                </td>-->
<!--                                                <td class="text-xs-left">-->
<!--                                                    <v-textarea v-model="props.item.concepto" rows="1" cols="50"-->
<!--                                                        readonly>-->
<!--                                                    </v-textarea>-->
<!--                                                </td>-->
<!--                                                <td class="text-xs-left">-->
<!--                                                    <v-textarea v-model="props.item.descripcion" rows="1" cols="40">-->
<!--                                                    </v-textarea>-->
<!--                                                </td>-->
<!--                                                <td class="text-xs-left">-->
<!--                                                    <v-flex sm12 xs12 md12>-->
<!--                                                        <v-text-field v-model="props.item.valor" type="number"-->
<!--                                                            onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"-->
<!--                                                            min="1"></v-text-field>-->
<!--                                                    </v-flex>-->
<!--                                                </td>-->
<!--                                                <v-tooltip top v-if="props.item.estado_id != null">-->
<!--                                                    <template v-slot:activator="{ on }">-->
<!--                                                        <v-btn fab color="success" :disabled="loading" small v-on="on"-->
<!--                                                            @click="addglosa(props.item)">-->
<!--                                                            <v-icon>done_all</v-icon>-->
<!--                                                        </v-btn>-->
<!--                                                    </template>-->
<!--                                                    <span>Guardar</span>-->
<!--                                                </v-tooltip>-->
<!--                                                <v-tooltip top v-else>-->
<!--                                                    <template v-slot:activator="{ on }">-->
<!--                                                        <v-btn fab color="info" :disabled="loading" small v-on="on"-->
<!--                                                            @click="addglosa(props.item)">-->
<!--                                                            <v-icon>done</v-icon>-->
<!--                                                        </v-btn>-->
<!--                                                    </template>-->
<!--                                                    <span>Guardar</span>-->
<!--                                                </v-tooltip>-->
<!--                                            </template>-->
<!--                                            <template v-slot:no-results>-->
<!--                                                <v-alert :value="true" color="error" icon="warning">-->
<!--                                                    Your search for "{{ searchAt }}" found no results.-->
<!--                                                </v-alert>-->
<!--                                            </template>-->
<!--                                        </v-data-table>-->
<!--                                    </v-card>-->
<!--                                </v-flex>-->
<!--                            </v-layout>-->
<!--                        </v-expansion-panel-content>-->
<!--                    </v-expansion-panel>-->

<!--                    <v-divider class="mx-2" inset vertical></v-divider>-->
<!--                    <v-expansion-panel v-if="invoiceService.AM">-->
<!--                        <v-expansion-panel-content class="primary text-xs-center" v-show="invoiceService.AM.length > 0">-->
<!--                            <template v-slot:actions>-->
<!--                                <v-icon color="white">$vuetify.icons.expand</v-icon>-->
<!--                            </template>-->
<!--                            <template v-slot:header>-->
<!--                                <div class="white&#45;&#45;text">AM</div>-->
<!--                            </template>-->
<!--                            <v-layout wrap>-->
<!--                                <v-flex xs12>-->
<!--                                    <v-card>-->
<!--                                        <v-card-title>-->
<!--                                            Factura # {{ af.numero_factura }}-->
<!--                                            <v-spacer></v-spacer>-->
<!--                                            <v-text-field v-model="searchAm" append-icon="search" label="Buscar"-->
<!--                                                single-line hide-details></v-text-field>-->
<!--                                        </v-card-title>-->
<!--                                        <v-data-table :headers="headersAm" :items="invoiceService.AM"-->
<!--                                            :search="searchAm">-->
<!--                                            <template v-slot:items="props">-->
<!--                                                <td class="text-xs-left">{{ props.item.Num_Doc }}</td>-->
<!--                                                <td class="text-xs-left">{{ props.item.Producto }}</td>-->
<!--                                                <td class="text-xs-left">{{ props.item.Numero_Unidades }}</td>-->
<!--                                                <td class="text-xs-left">{{ props.item.Valorunitario_Medicamento }}-->
<!--                                                </td>-->
<!--                                                <td class="text-xs-left">{{ props.item.Valortotal_Medicamento }}-->
<!--                                                </td>-->
<!--                                                <td class="text-xs-left">-->
<!--                                                    <v-select rows="1" cols="1" :items="codigos"-->
<!--                                                        v-model="props.item.codigo" autocomplete item-text="codigo"-->
<!--                                                        item-value="codigo" @input="descripcionCodigo(props.item)">-->
<!--                                                    </v-select>-->
<!--                                                </td>-->
<!--                                                <td class="text-xs-left">-->
<!--                                                    <v-textarea v-model="props.item.concepto" rows="1" cols="50"-->
<!--                                                        readonly>-->
<!--                                                    </v-textarea>-->
<!--                                                </td>-->
<!--                                                <td class="text-xs-left">-->
<!--                                                    <v-textarea v-model="props.item.descripcion" rows="1" cols="40">-->
<!--                                                    </v-textarea>-->
<!--                                                </td>-->
<!--                                                <td class="text-xs-left">-->
<!--                                                    <v-flex sm12 xs12 md12>-->
<!--                                                        <v-text-field v-model="props.item.valor" type="number"-->
<!--                                                            onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"-->
<!--                                                            min="1"></v-text-field>-->
<!--                                                    </v-flex>-->
<!--                                                </td>-->
<!--                                                <v-tooltip top v-if="props.item.estado_id != null">-->
<!--                                                    <template v-slot:activator="{ on }">-->
<!--                                                        <v-btn fab color="success" :disabled="loading" small v-on="on"-->
<!--                                                            @click="addglosa(props.item)">-->
<!--                                                            <v-icon>done_all</v-icon>-->
<!--                                                        </v-btn>-->
<!--                                                    </template>-->
<!--                                                    <span>Guardar</span>-->
<!--                                                </v-tooltip>-->
<!--                                                <v-tooltip top v-else>-->
<!--                                                    <template v-slot:activator="{ on }">-->
<!--                                                        <v-btn fab color="info" :disabled="loading" small v-on="on"-->
<!--                                                            @click="addglosa(props.item)">-->
<!--                                                            <v-icon>done</v-icon>-->
<!--                                                        </v-btn>-->
<!--                                                    </template>-->
<!--                                                    <span>Guardar</span>-->
<!--                                                </v-tooltip>-->
<!--                                            </template>-->
<!--                                            <template v-slot:no-results>-->
<!--                                                <v-alert :value="true" color="error" icon="warning">-->
<!--                                                    Your search for "{{ searchAm }}" found no results.-->
<!--                                                </v-alert>-->
<!--                                            </template>-->
<!--                                        </v-data-table>-->
<!--                                    </v-card>-->
<!--                                </v-flex>-->
<!--                            </v-layout>-->
<!--                        </v-expansion-panel-content>-->
<!--                    </v-expansion-panel>-->

                </v-card-text>
                <div style="flex: 1 1 auto;"></div>
                <v-btn color="green" dark text @click="saveAuditoria()">
                    Guardar Auditoria
                </v-btn>
            </v-card>
        </v-dialog>

        <v-flex xs12>
            <v-card-title>
                <v-spacer></v-spacer>
                <v-text-field v-model="search" append-icon="search" label="Buscar" single-line hide-details>
                </v-text-field>
            </v-card-title>
            <v-data-table :headers="headers" :items="allAssign" :search="search">
                <template v-slot:items="props">
                    <td>{{ props.item.Nombre }}</td>
                    <td>{{ props.item.permisos }}</td>
                    <td>{{ props.item.Nit }}</td>
                    <td>{{ props.item.totalFacturas }}</td>
                    <td><strong> $ {{ props.item.totalNeto }}</strong></td>
                    <v-btn color="#00b297" :loading="loading" :disabled="loading" dark
                        @click="invoiceAssign(props.item)">Glosar</v-btn>
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
        name: 'MedicalBillsAsignados',
        data: () => {
            return {
                search: '',
                searchAc: '',
                searchAp: '',
                searchAt: '',
                searchAm: '',
                headers: [{
                        text: 'Nombre prestador',
                        align: 'left',
                        value: 'Nombre'
                    },
                    {
                        text: 'Auditor',
                        value: 'permisos'
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
                        text: 'Semaforo',
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
                    },
                    {
                        text: ''
                    },
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
                    },
                    {
                        text: ''
                    },
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
                    },
                    {
                        text: ''
                    },
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
                    },
                    {
                        text: ''
                    },
                ],
                allAssign: [],
                loading: false,
                dialogInvoice: false,
                searchFactura: '',
                invoice: [],
                dialogGlosa: false,
                invoiceService: [],
                af: [],
                codigosglosa: [],
                prestador: '',
                preload: false,
                formGlosa:{
                    id:null,
                    codigo:null,
                    concepto:null,
                    descripcion:null,
                    valor:null
                },
          conceptosGlosas:[
            {id:'101',concepto:'ESTANCIA'},
            {id:'102',concepto:'CONSULTAS, INTERCONSULTAS Y VISITAS MÉDICAS'},
            {id:'103',concepto:'HONORARIOS MÉDICOS EN PROCEDIMIENTOS'},
            {id:'104',concepto:'HONORARIOS DE OTROS PROFESIONALES'},
            {id:'105',concepto:'DERECHOS DE SALA'},
            {id:'106',concepto:'MATERIALES'},
            {id:'107',concepto:'MEDICAMENTOS'},
            {id:'108',concepto:'AYUDAS DIAGNÓSTICAS'},
            {id:'109',concepto:'ATENCIÓN INTEGRAL'},
            {id:'110',concepto:'SERVICIO O INSUMO INCLUÍDO EN PAQUETE'},
            {id:'111',concepto:'SERVICIO O INSUMO INCLUÍDO EN LA ESTANCIA O DERECHOS DE SALA'},
            {id:'112',concepto:'FACTURA EXCEDE TOPES AUTORIZADOS'},
            {id:'113',concepto:'FACTURAR POR SEPARADO POR TIPO DE RECOBRO (CTC, ATEP, TUTELAS)'},
            {id:'114',concepto:'ERROR EN SUMA DE CONCEPTOS FACTURADOS'},
            {id:'115',concepto:'DATOS INSUFICIENTES DEL USUARIO'},
            {id:'116',concepto:'USUARIO O SERVICIO CORRESPONDE A OTRO PLAN O RESPONSABLE'},
            {id:'117',concepto:'USUARIO RETIRADO O MOROSO'},
            {id:'119',concepto:'ERROR EN DESCUENTO PACTADO'},
            {id:'120',concepto:'RECIBO DE PAGO COMPARTIDO'},
            {id:'122',concepto:'PRESCRIPCIÓN DENTRO DE LOS TÉRMINOS LEGALES O PACTADOS ENTRE LAS PARTES'},
            {id:'123',concepto:'PROCEDIMIENTO O ACTIVIDAD'},
            {id:'124',concepto:'FALTA FIRMA DEL PRESTADOR DE SERVICIOS DE SALUD.'},
            {id:'125',concepto:'EXAMEN O ACTIVIDAD PERTENECE A DETECIÓN TEMPRANA O PROTECCIÓN ESPECÍFICA'},
            {id:'126',concepto:'USUARIO O SERVICIO CORRESPONDE A CAPITACIÓN'},
            {id:'127',concepto:'SERVICIO O PROCEDIMIENTO INCLUÍDO EN OTRO'},
            {id:'128',concepto:'ORDEN CANCELADA AL PRESTADOR DE SERVICIOS DE SALUD'},
            {id:'151',concepto:'RECOBRO EN CONTRATO DE CAPITACIÓN POR SERVICIOS PRESTADOS EN OTRO PRESTADOR'},
            {id:'152',concepto:'DISMINUCIÓN EN EL NÚMERO DE PERSONAS INCLUÍDAS EN LA CAPITACIÓN'},
            {id:'201',concepto:'ESTANCIA'},
            {id:'202',concepto:'CONSULTAS, INTERCONSULTAS Y VISITAS MÉDICAS'},
            {id:'203',concepto:'HONORARIOS MÉDICOS EN PROCEDIMIENTOS'},
            {id:'204',concepto:'HONORARIOS DE OTROS PROFESIONALES ASISTENCIALES'},
            {id:'205',concepto:'DERECHOS DE SALA'},
            {id:'206',concepto:'MATERIALES'},
            {id:'207',concepto:'MEDICAMENTOS'},
            {id:'208',concepto:'AYUDAS DIAGNÓSTICAS'},
            {id:'209',concepto:'ATENCIÓN INTEGRAL'},
            {id:'223',concepto:'PROCEDIMIENTO O ACTIVIDAD'},
            {id:'229',concepto:'RECARGOS NO PACTADOS'},
            {id:'301',concepto:'ESTANCIA'},
            {id:'302',concepto:'CONSULTAS, INTERCONSULTAS Y VISITAS MÉDICAS'},
            {id:'303',concepto:'HONORARIOS MÉDICOS EN PROCEDIMIENTOS'},
            {id:'304',concepto:'HONORARIOS DE OTROS PROFESIONALES'},
            {id:'305',concepto:'DERECHOS DE SALA'},
            {id:'306',concepto:'MATERIALES'},
            {id:'307',concepto:'MEDICAMENTOS'},
            {id:'308',concepto:'AYUDAS DIAGNÓSTICAS'},
            {id:'309',concepto:'ATENCIÓN INTEGRAL'},
            {id:'320',concepto:'RECIBO DE PAGO COMPARTIDO'},
            {id:'331',concepto:'BONOS O VOUCHERS SIN FIRMA DEL PACIENTE, CON ENMENDADURAS O TACHONES'},
            {id:'332',concepto:'DETALLE DE CARGOS'},
            {id:'333',concepto:'COPIA DE HISTORIA CLÍNICA INCOMPLETA'},
            {id:'335',concepto:'FORMATO DE ATEP'},
            {id:'336',concepto:'COPIA DE LA FACTURA O DETALLE DE CARGOS PARA EXCEDENTES DE SOAT'},
            {id:'337',concepto:'ORDEN O FÓRMULA MÉDICA'},
            {id:'338',concepto:'HOJA DE TRASLADO EN AMBULANCIA'},
            {id:'339',concepto:'COMPROBANTE DE RECIBIDO DEL USUARIO'},
            {id:'340',concepto:'REGISTRO DE ANESTESIA'},
            {id:'341',concepto:'DESCRIPCIÓN QUIRÚRGICA '},
            {id:'342',concepto:'LISTA DE PRECIOS'},
            {id:'401',concepto:'ESTANCIA'},
            {id:'402',concepto:'CONSULTAS, INTERCONSULTAS Y VISITAS MÉDICAS'},
            {id:'403',concepto:'HONORARIOS MEDICOS EN PROCEDIMIENTOS'},
            {id:'406',concepto:'MATERIALES'},
            {id:'408',concepto:'AYUDAS DIAGNÓSTICAS'},
            {id:'423',concepto:'PROCEDIMIENTO O ACTIVIDAD'},
            {id:'430',concepto:'AUTORIZACIÓN DE SERVICIOS ADICIONALES'},
            {id:'443',concepto:'ORDEN O AUTORIZACIÓN DE SERVICIOS VENCIDA'},
            {id:'444',concepto:'PROFESIONAL QUE ORDENA NO ADSCRITO'},
            {id:'501',concepto:'ESTANCIA'},
            {id:'502',concepto:'CONSULTAS, INTERCONSULTAS Y VISITAS MÉDICAS'},
            {id:'506',concepto:'MATERIALES'},
            {id:'507',concepto:'MEDICAMENTOS'},
            {id:'508',concepto:'AYUDAS DIAGNÓSTICAS'},
            {id:'523',concepto:'PROCEDIMIENTO O ACTIVIDAD'},
            {id:'527',concepto:'SERVICIO O PROCEDIMIENTO INCLUÍDO EN OTRO'},
            {id:'545',concepto:'SERVICIO NO PACTADO'},
            {id:'546',concepto:'COBERTURA SIN AGOTAR EN LA PÓLIZA (SOAT)'},
            {id:'601',concepto:'ESTANCIA'},
            {id:'602',concepto:'CONSULTAS, INTERCONSULTAS Y VISITAS MÉDICAS'},
            {id:'603',concepto:'HONORARIOS MÉDICOS EN PROCEDIMIENTOS'},
            {id:'604',concepto:'HONORARIOS DE OTROS PROFESIONALES'},
            {id:'605',concepto:'DERECHOS DE SALA'},
            {id:'606',concepto:'MATERIALES'},
            {id:'607',concepto:'MEDICAMENTOS'},
            {id:'608',concepto:'AYUDAS DIAGNÓSTICAS'},
            {id:'623',concepto:'PROCEDIMIENTO O ACTIVIDAD'},
            {id:'653',concepto:'URGENCIA NO PERTINENTE'},
            {id:'816',concepto:'USUARIO O SERVICIOS CORRESPONDE A OTRO PLAN O RESPONSABLE'},
            {id:'817',concepto:'USUARIO RETIRADO O MOROSO'},
            {id:'821',concepto:'AUTORIZACIÓN PRINCIPAL NO EXISTE O NO CORRESPONDE AL PRESTADOR DE SERVICIOS DE SALUD'},
            {id:'834',concepto:'RESUMEN DE EGRESO O EPICRISIS, HOJA DE ATENCIÓN DE URGENCIAS U ODONTOGRAMA'},
            {id:'844',concepto:'PROFESIONAL QUE ORDENA NO ADSCRITO'},
            {id:'847',concepto:'FALTA DE SOPORTE DE JUSTIFICACIÓN PARA RECOBROS (CTC, TUTELAS, ARP)'},
            {id:'848',concepto:'INFORME DE ATENCIÓN INICIAL DE URGENCIAS'},
            {id:'849',concepto:'FACTURA NO CUMPLE CON LOS REQUISITOS'},
            {id:'850',concepto:'FACTURA YA CANCELADA'},
            {id:'996',concepto:'NO SUBSANADA (GLOSA O DEVOLUCIÓN INJUSTIFICADA)'},
            {id:'997',concepto:'NO SUBSANADA (GLOSA O DEVOLUCIÓN TOTALMENTE ACEPTADA)'},
            {id:'998',concepto:'SUBSANADA PARCIAL (GLOSA O DEVOLUCIÓN PARCIALMENTE ACEPTADA)'},
            {id:'999',concepto:'SUBSANADA (GLOSA O DEVOLUCIÓN NO ACEPTADA)'}]

            }
        },
        mounted() {
            this.myAssigned();
        },
        computed: {
            valorGlosa(){
                // this.af.valor_Neto
                var ValorSuma = 0;
                if(this.invoiceService.length > 0){
                    this.invoiceService.forEach(s => {
                        if(s.valor){
                            ValorSuma += parseInt(s.valor);
                        }
                    })
                }
                return parseInt(this.af.valor_Neto)-ValorSuma;
            },
            codigos() {
                return this.codigosglosa.filter((cod) => {
                    if (cod.tipo_id != 34 && cod.tipo_id != 33) {
                        return cod
                    }
                })
            },
            servicios() {
                return this.codigosglosa.filter((serv) => {
                    if (serv.tipo_id == 34) {
                        return serv
                    }
                })
            },
        },
        methods: {
            fetchCodigoglosas() {
                axios.get('/api/codigoglosa/all')
                    .then(res => this.codigosglosa = res.data);
            },
            myAssigned() {
                this.$emit("updateCount");
                axios.get('/api/medicalBills/myAssigned').then(res => {
                    this.allAssign = res.data;
                }).catch(e => {
                    console.log(e)
                })
            },
            invoiceAssign(proveedor) {
                this.clearSearch();
                this.prestador = proveedor.Nit
                this.fetchCodigoglosas()
                this.loading = true;
                axios.get('/api/medicalBills/invoiceAssign/' + proveedor.id).then(res => {
                    this.invoice = res.data;
                    this.dialogInvoice = true
                    this.loading = false;
                }).catch(e => {
                    console.log(e)
                });
            },
            glosar(afs) {
                this.clearSearch();
                this.af = afs
                this.dialogGlosa = true
                axios.get('/api/medicalBills/invoiceService/' + this.af.id).then(res => {
                    this.invoiceService = res.data
                })
            },
            // addglosa(glosa) {
                addglosa(itemId,itemCodigo,itemConcepto,itemDescripcion,itemValor) {
                    if (!itemValor) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'info',
                        title: 'No puede guardar una glosa sin valor!'
                    })
                    return
                }
                    if (parseInt(itemValor) > parseInt(this.af.valor_Neto)) {
                        this.$alertInfo('El valor es mayor que el cobrado!');
                        return
                    }
                // if (glosa.id_ac) {
                //     if (parseInt(glosa.valor) > parseInt(glosa.valorneto_Pagar)) {
                //         this.$alertInfo('El valor es mayor que el cobrado!');
                //         return
                //     }
                // }
                // if (glosa.id_ap) {
                //     if (parseInt(glosa.valor) > parseInt(glosa.Valor_Procedimiento)) {
                //         this.$alertInfo('El valor es mayor que el cobrado!');
                //         return
                //     }
                // }
                // if (glosa.id_at) {
                //     if (parseInt(glosa.valor) > parseInt(glosa.Valor_Total)) {
                //         this.$alertInfo('El valor es mayor que el cobrado!');
                //         return
                //     }
                // }
                // if (glosa.id_am) {
                //     if (parseInt(glosa.valor) > parseInt(glosa.Valortotal_Medicamento)) {
                //         this.$alertInfo('El valor es mayor que el cobrado!');
                //         return
                //     }
                // }
                this.loading = true
                // let dataglosa = Object.assign(glosa, {af_id: this.af.id});
                    let dataglosa= {
                        id:itemId,
                        codigo:itemCodigo,
                        concepto:itemConcepto,
                        descripcion:itemDescripcion,
                        valor:itemValor,
                        af_id:this.af.id
                    }
                axios.post('/api/medicalBills/glosa', dataglosa).then(res => {
                    this.loading = false
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        onOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: 'Glosa guardada con exito!'
                    })
                    this.glosar(this.af)
                }).catch(e => {
                    this.loading = false
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
            descripcionCodigo(item) {
                let finded = this.codigosglosa.find(glosa => glosa.codigo == item.codigo)
                if (item.id_ap) {
                    let objIndex = this.invoiceService.AP.findIndex((obj => obj.id_ap === item.id_ap));
                    this.invoiceService.AP[objIndex].concepto = finded.descripcion
                } else if (item.id_ac) {
                    let objIndex = this.invoiceService.AC.findIndex((obj => obj.id_ac === item.id_ac));
                    this.invoiceService.AC[objIndex].concepto = finded.descripcion
                } else if (item.id_at) {
                    let objIndex = this.invoiceService.AT.findIndex((obj => obj.id_at === item.id_at));
                    this.invoiceService.AT[objIndex].concepto = finded.descripcion
                } else if (item.id_am) {
                    let objIndex = this.invoiceService.AM.findIndex((obj => obj.id_am === item.id_am));
                    this.invoiceService.AM[objIndex].concepto = finded.descripcion
                }
            },
            semaforoTd(factura) {
                if (factura.diasHabiles >= 21) {
                    return 'error white--text';
                } else if (factura.diasHabiles >= 11) {
                    return 'yellow white--text';
                } else {
                    return 'success white--text';
                }
            },
            saveAuditoria() {
                axios.post('/api/medicalBills/saveAuditoria', {
                    af_id: this.af.id
                }).then(res => {
                    this.myAssigned();
                    this.dialogInvoice = false
                    this.dialogGlosa = false
                    this.$alerSuccess('Auditoria guardada con exito!');
                }).catch(e => {
                    console.log(e)
                });
            },
            saveServicio(factura) {
                axios.post('/api/medicalBills/saveServicio', factura).then(res => {
                    this.$alerSuccess('Servicio actualizado con exito!');
                }).catch(e => {
                    console.log(e)
                });
            },
            clearSearch(){
                this.search = '';
                this.searchAc = '';
                this.searchAp = '';
                this.searchAt = '';
                this.searchAm = '';
                this.searchFactura = '';
                this.formGlosa.id = null;
                this.formGlosa.codigo = null;
                this.formGlosa.concepto = null;
                this.formGlosa.descripcion = null;
                this.formGlosa.valor = null;
            },
            loadConcepto(item){
                const concepto = this.conceptosGlosas.filter(s => parseInt(s.id) === parseInt(item))
                this.formGlosa.concepto = concepto[0].concepto
            },
            loadConceptoListado(item,i){
                const concepto = this.conceptosGlosas.filter(s => parseInt(s.id) === parseInt(item))
                this.invoiceService[i].concepto = concepto[0].concepto
            }
        }
    }

</script>

<style lang="scss" scoped>
    .buttom-nav-anexo {
        width: 30% !important;
        margin: 0 35% !important;
        border-radius: 40px;
    }

</style>
