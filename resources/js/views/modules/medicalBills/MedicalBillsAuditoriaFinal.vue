<template>
    <div>

        <v-bottom-nav :value="true" color="transparent">
            <v-btn color="primary" flat
                @click="pendientes = true, cerradas = false, conciliacion = false, conciliacionSaldo = false, auditadasFinal()">
                <span>Pendientes</span>
                <v-icon>block</v-icon>
            </v-btn>
            <v-btn color="success" flat
                @click="conciliacion = true, pendientes = false, cerradas = false, conciliacionSaldo = false, facturasConciliacion()">
                <span>Conciliación</span>
                <v-icon>list_alt</v-icon>
            </v-btn>
            <v-btn color="warning" flat
                @click="conciliacionSaldo = true, conciliacion = false, pendientes = false, cerradas = false, facturasConciliacionSaldo()">
                <span>Conciliadas con saldo</span>
                <v-icon>perm_media</v-icon>
            </v-btn>
            <v-btn color="error" flat
                @click="cerradas = true, conciliacion = false, pendientes = false, conciliacionSaldo = false, facturasCerradas()">
                <span>Cerradas</span>
                <v-icon>check_circle</v-icon>
            </v-btn>
        </v-bottom-nav>

        <v-layout row wrap>

            <v-flex xs12 v-show="pendientes">
                <v-card-title>
                    PENDIENTES
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
                    </template>
                    <template v-slot:no-results>
                        <v-alert :value="true" color="error" icon="warning">
                            Your search for "{{ search }}" found no results.
                        </v-alert>
                    </template>
                </v-data-table>
            </v-flex>

            <v-flex xs12 v-show="conciliacion">
                <v-card-title>
                    CONCILIACIÓN
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search2" append-icon="search" label="Buscar" single-line hide-details>
                    </v-text-field>
                </v-card-title>
                <v-data-table :headers="headers" :items="allConciliacion" :search="search2">
                    <template v-slot:items="props">
                        <td>{{ props.item.Nombre }}</td>
                        <td>{{ props.item.Nit }}</td>
                        <td>{{ props.item.totalFacturas }}</td>
                        <td><strong> $ {{ props.item.totalNeto }}</strong></td>
                        <v-btn color="primary" :loading="loading" :disabled="loading" dark
                            @click="invoiceConciliacion(props.item)">Ver</v-btn>
                    </template>
                    <template v-slot:no-results>
                        <v-alert :value="true" color="error" icon="warning">
                            Your search for "{{ search2 }}" found no results.
                        </v-alert>
                    </template>
                </v-data-table>
            </v-flex>

            <v-flex xs12 v-show="conciliacionSaldo">
                <v-card-title>
                    CONCILIACIÓN CON SALDO
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search4" append-icon="search" label="Buscar" single-line hide-details>
                    </v-text-field>
                </v-card-title>
                <v-data-table :headers="headers" :items="allConciliacionSaldo" :search="search4">
                    <template v-slot:items="props">
                        <td>{{ props.item.Nombre }}</td>
                        <td>{{ props.item.Nit }}</td>
                        <td>{{ props.item.totalFacturas }}</td>
                        <td><strong> $ {{ props.item.totalNeto }}</strong></td>
                        <v-btn color="primary" :loading="loading" :disabled="loading" dark
                            @click="invoiceConciliacionSaldo(props.item)">Ver</v-btn>
                    </template>
                    <template v-slot:no-results>
                        <v-alert :value="true" color="error" icon="warning">
                            Your search for "{{ search4 }}" found no results.
                        </v-alert>
                    </template>
                </v-data-table>
            </v-flex>

            <v-flex xs12 v-show="cerradas">
                <v-card-title>
                    CERRADAS
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search3" append-icon="search" label="Buscar" single-line hide-details>
                    </v-text-field>
                </v-card-title>
                <v-data-table :headers="headers" :items="allCerradas" :search="search3">
                    <template v-slot:items="props">
                        <td>{{ props.item.Nombre }}</td>
                        <td>{{ props.item.Nit }}</td>
                        <td>{{ props.item.totalFacturas }}</td>
                        <td><strong> $ {{ props.item.totalNeto }}</strong></td>
                        <v-btn color="primary" :loading="loading" :disabled="loading" dark
                            @click="invoiceCerradas(props.item)">Ver</v-btn>
                    </template>
                    <template v-slot:no-results>
                        <v-alert :value="true" color="error" icon="warning">
                            Your search for "{{ search3 }}" found no results.
                        </v-alert>
                    </template>
                </v-data-table>
            </v-flex>

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

            <v-dialog v-model="dialogGlosa" v-if="dialogGlosa" fullscreen hide-overlay
                transition="dialog-bottom-transition" scrollable>
                <v-card tile>
                    <v-toolbar card dark color="primary">
                        <v-btn icon dark @click="dialogGlosa = false">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Glosas</v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-card-text>

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
                                                  :search="searchAc">
                                        <template v-slot:items="props">
                                            <td class="text-xs-left">{{ props.item.codigo }}</td>
                                            <td class="text-xs-left">
                                                <v-textarea v-model="props.item.descripcionPrestador" rows="1"
                                                            cols="40" readonly>
                                                </v-textarea>
                                            </td>
                                            <td class="text-xs-left">{{ props.item.valorAPrestador }}</td>
                                            <td class="text-xs-left">{{ props.item.valorNPrestador }}</td>
                                            <td class="text-xs-left">
                                                <v-textarea v-model="props.item.descripcionSumi" rows="1"
                                                            cols="40">
                                                </v-textarea>
                                            </td>
                                            <td class="text-xs-left">
                                                <v-flex sm12 xs12 md12>
                                                    <v-text-field v-model="props.item.valorASumi" type="number"
                                                                  onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                                                  min="1"></v-text-field>
                                                </v-flex>
                                            </td>
                                            <td class="text-xs-left">
                                                <v-flex sm12 xs12 md12>
                                                    <v-text-field v-model="props.item.valorNSumi" type="number"
                                                                  onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                                                  min="1"></v-text-field>
                                                </v-flex>
                                            </td>
                                            <v-tooltip top v-if="props.item.archivo != null">
                                                <template v-slot:activator="{ on }">
                                                    <v-btn fab color="warning"
                                                           @click="showAdjunto(props.item.archivo)"
                                                           :disabled="loading" small v-on="on">
                                                        <v-icon>file_copy</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Ver adjunto</span>
                                            </v-tooltip>
                                            <v-menu>
                                                <template #activator="{ on: menu }">
                                                    <v-tooltip top>
                                                        <template #activator="{ on: tooltip }">
                                                            <v-btn v-if="props.item.estado_sumi != null" small
                                                                   color="success" fab
                                                                   v-on="{ ...tooltip, ...menu }">
                                                                <v-icon>compare_arrows</v-icon>
                                                            </v-btn>
                                                            <v-btn v-else small color="primary" fab
                                                                   v-on="{ ...tooltip, ...menu }">
                                                                <v-icon>compare_arrows</v-icon>
                                                            </v-btn>
                                                        </template>
                                                        <span>Acción
                                                                    ({{ tipos(props.item.estado_sumi) }})</span>
                                                    </v-tooltip>
                                                </template>
                                                <v-list>
                                                    <v-list-tile v-for="(item, index) in items" :key="index"
                                                                 @click="addglosa(props.item, item.title)">
                                                        <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                                                    </v-list-tile>
                                                </v-list>
                                            </v-menu>
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
<!--                                                    <td class="text-xs-left">{{ props.item.codigo }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionPrestador" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorAPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionSumi" rows="1"-->
<!--                                                            cols="40">-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-flex sm12 xs12 md12>-->
<!--                                                            <v-text-field v-model="props.item.valorASumi" type="number"-->
<!--                                                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"-->
<!--                                                                min="1"></v-text-field>-->
<!--                                                        </v-flex>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-flex sm12 xs12 md12>-->
<!--                                                            <v-text-field v-model="props.item.valorNSumi" type="number"-->
<!--                                                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"-->
<!--                                                                min="1"></v-text-field>-->
<!--                                                        </v-flex>-->
<!--                                                    </td>-->
<!--                                                    <v-tooltip top v-if="props.item.archivo != null">-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="warning"-->
<!--                                                                @click="showAdjunto(props.item.archivo)"-->
<!--                                                                :disabled="loading" small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Ver adjunto</span>-->
<!--                                                    </v-tooltip>-->
<!--                                                    <v-menu>-->
<!--                                                        <template #activator="{ on: menu }">-->
<!--                                                            <v-tooltip top>-->
<!--                                                                <template #activator="{ on: tooltip }">-->
<!--                                                                    <v-btn v-if="props.item.estado_sumi != null" small-->
<!--                                                                        color="success" fab-->
<!--                                                                        v-on="{ ...tooltip, ...menu }">-->
<!--                                                                        <v-icon>compare_arrows</v-icon>-->
<!--                                                                    </v-btn>-->
<!--                                                                    <v-btn v-else small color="primary" fab-->
<!--                                                                        v-on="{ ...tooltip, ...menu }">-->
<!--                                                                        <v-icon>compare_arrows</v-icon>-->
<!--                                                                    </v-btn>-->
<!--                                                                </template>-->
<!--                                                                <span>Acción-->
<!--                                                                    ({{ tipos(props.item.estado_sumi) }})</span>-->
<!--                                                            </v-tooltip>-->
<!--                                                        </template>-->
<!--                                                        <v-list>-->
<!--                                                            <v-list-tile v-for="(item, index) in items" :key="index"-->
<!--                                                                @click="addglosa(props.item, item.title)">-->
<!--                                                                <v-list-tile-title>{{ item.title }}</v-list-tile-title>-->
<!--                                                            </v-list-tile>-->
<!--                                                        </v-list>-->
<!--                                                    </v-menu>-->
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
<!--                                                    <td class="text-xs-left">{{ props.item.codigo }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionPrestador" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorAPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionSumi" rows="1"-->
<!--                                                            cols="40">-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-flex sm12 xs12 md12>-->
<!--                                                            <v-text-field v-model="props.item.valorASumi" type="number"-->
<!--                                                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"-->
<!--                                                                min="1"></v-text-field>-->
<!--                                                        </v-flex>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-flex sm12 xs12 md12>-->
<!--                                                            <v-text-field v-model="props.item.valorNSumi" type="number"-->
<!--                                                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"-->
<!--                                                                min="1"></v-text-field>-->
<!--                                                        </v-flex>-->
<!--                                                    </td>-->
<!--                                                    <v-tooltip top v-if="props.item.archivo != null">-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="warning"-->
<!--                                                                @click="showAdjunto(props.item.archivo)"-->
<!--                                                                :disabled="loading" small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Ver adjunto</span>-->
<!--                                                    </v-tooltip>-->
<!--                                                    <v-menu>-->
<!--                                                        <template #activator="{ on: menu }">-->
<!--                                                            <v-tooltip top>-->
<!--                                                                <template #activator="{ on: tooltip }">-->
<!--                                                                    <v-btn v-if="props.item.estado_sumi != null" small-->
<!--                                                                        color="success" fab-->
<!--                                                                        v-on="{ ...tooltip, ...menu }">-->
<!--                                                                        <v-icon>compare_arrows</v-icon>-->
<!--                                                                    </v-btn>-->
<!--                                                                    <v-btn v-else small color="primary" fab-->
<!--                                                                        v-on="{ ...tooltip, ...menu }">-->
<!--                                                                        <v-icon>compare_arrows</v-icon>-->
<!--                                                                    </v-btn>-->
<!--                                                                </template>-->
<!--                                                                <span>Acción-->
<!--                                                                    ({{ tipos(props.item.estado_sumi) }})</span>-->
<!--                                                            </v-tooltip>-->
<!--                                                        </template>-->
<!--                                                        <v-list>-->
<!--                                                            <v-list-tile v-for="(item, index) in items" :key="index"-->
<!--                                                                @click="addglosa(props.item, item.title)">-->
<!--                                                                <v-list-tile-title>{{ item.title }}</v-list-tile-title>-->
<!--                                                            </v-list-tile>-->
<!--                                                        </v-list>-->
<!--                                                    </v-menu>-->
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
<!--                                                    <td class="text-xs-left">{{ props.item.codigo }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionPrestador" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorAPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionSumi" rows="1"-->
<!--                                                            cols="40">-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-flex sm12 xs12 md12>-->
<!--                                                            <v-text-field v-model="props.item.valorASumi" type="number"-->
<!--                                                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"-->
<!--                                                                min="1"></v-text-field>-->
<!--                                                        </v-flex>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-flex sm12 xs12 md12>-->
<!--                                                            <v-text-field v-model="props.item.valorNSumi" type="number"-->
<!--                                                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"-->
<!--                                                                min="1"></v-text-field>-->
<!--                                                        </v-flex>-->
<!--                                                    </td>-->
<!--                                                    <v-tooltip top v-if="props.item.archivo != null">-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="warning"-->
<!--                                                                @click="showAdjunto(props.item.archivo)"-->
<!--                                                                :disabled="loading" small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Ver adjunto</span>-->
<!--                                                    </v-tooltip>-->
<!--                                                    <v-menu>-->
<!--                                                        <template #activator="{ on: menu }">-->
<!--                                                            <v-tooltip top>-->
<!--                                                                <template #activator="{ on: tooltip }">-->
<!--                                                                    <v-btn v-if="props.item.estado_sumi != null" small-->
<!--                                                                        color="success" fab-->
<!--                                                                        v-on="{ ...tooltip, ...menu }">-->
<!--                                                                        <v-icon>compare_arrows</v-icon>-->
<!--                                                                    </v-btn>-->
<!--                                                                    <v-btn v-else small color="primary" fab-->
<!--                                                                        v-on="{ ...tooltip, ...menu }">-->
<!--                                                                        <v-icon>compare_arrows</v-icon>-->
<!--                                                                    </v-btn>-->
<!--                                                                </template>-->
<!--                                                                <span>Acción-->
<!--                                                                    ({{ tipos(props.item.estado_sumi) }})</span>-->
<!--                                                            </v-tooltip>-->
<!--                                                        </template>-->
<!--                                                        <v-list>-->
<!--                                                            <v-list-tile v-for="(item, index) in items" :key="index"-->
<!--                                                                @click="addglosa(props.item, item.title)">-->
<!--                                                                <v-list-tile-title>{{ item.title }}</v-list-tile-title>-->
<!--                                                            </v-list-tile>-->
<!--                                                        </v-list>-->
<!--                                                    </v-menu>-->
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
<!--                                                    <td class="text-xs-left">{{ props.item.codigo }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionPrestador" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorAPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionSumi" rows="1"-->
<!--                                                            cols="40">-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-flex sm12 xs12 md12>-->
<!--                                                            <v-text-field v-model="props.item.valorASumi" type="number"-->
<!--                                                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"-->
<!--                                                                min="1"></v-text-field>-->
<!--                                                        </v-flex>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-flex sm12 xs12 md12>-->
<!--                                                            <v-text-field v-model="props.item.valorNSumi" type="number"-->
<!--                                                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"-->
<!--                                                                min="1"></v-text-field>-->
<!--                                                        </v-flex>-->
<!--                                                    </td>-->
<!--                                                    <v-tooltip top v-if="props.item.archivo != null">-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="warning"-->
<!--                                                                @click="showAdjunto(props.item.archivo)"-->
<!--                                                                :disabled="loading" small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Ver adjunto</span>-->
<!--                                                    </v-tooltip>-->
<!--                                                    <v-menu>-->
<!--                                                        <template #activator="{ on: menu }">-->
<!--                                                            <v-tooltip top>-->
<!--                                                                <template #activator="{ on: tooltip }">-->
<!--                                                                    <v-btn v-if="props.item.estado_sumi != null" small-->
<!--                                                                        color="success" fab-->
<!--                                                                        v-on="{ ...tooltip, ...menu }">-->
<!--                                                                        <v-icon>compare_arrows</v-icon>-->
<!--                                                                    </v-btn>-->
<!--                                                                    <v-btn v-else small color="primary" fab-->
<!--                                                                        v-on="{ ...tooltip, ...menu }">-->
<!--                                                                        <v-icon>compare_arrows</v-icon>-->
<!--                                                                    </v-btn>-->
<!--                                                                </template>-->
<!--                                                                <span>Acción-->
<!--                                                                    ({{ tipos(props.item.estado_sumi) }})</span>-->
<!--                                                            </v-tooltip>-->
<!--                                                        </template>-->
<!--                                                        <v-list>-->
<!--                                                            <v-list-tile v-for="(item, index) in items" :key="index"-->
<!--                                                                @click="addglosa(props.item, item.title)">-->
<!--                                                                <v-list-tile-title>{{ item.title }}</v-list-tile-title>-->
<!--                                                            </v-list-tile>-->
<!--                                                        </v-list>-->
<!--                                                    </v-menu>-->
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
                    <div style="flex: 1 1 auto;"></div>
                    <v-btn color="success" dark text @click="saveAuditoriaFinal(invoiceServiceAuditadas)">
                        Guardar Auditoria Final
                    </v-btn>
                </v-card>
            </v-dialog>

            <v-dialog v-model="dialogInvoiceConciliacion" persistent max-width="976px">
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
                                            <v-tooltip top>
                                                <template v-slot:activator="{ on }">
                                                    <v-btn text icon color="success" :disabled="loading" dark v-on="on"
                                                        @click="accionConciliacion(invoicesConciliacion)">
                                                        <v-icon>settings</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Acción Conciliación</span>
                                            </v-tooltip>

                                            <v-tooltip top>
                                                <template v-slot:activator="{ on }">
                                                    <v-btn text icon color="warning" :disabled="loading" dark v-on="on"
                                                           @click="accionConciliacionAdministrativa(invoicesConciliacion)">
                                                        <v-icon>settings</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Conciliación Administrativa</span>
                                            </v-tooltip>

                                            <v-spacer></v-spacer>
                                            <v-text-field v-model="searchFacturaC" append-icon="search" label="Buscar"
                                                single-line hide-details></v-text-field>
                                        </v-card-title>
                                        <v-data-table class="elevation-1" :headers="headersInvoice"
                                            :items="invoicesConciliacion" :search="searchFacturaC">
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
                                                            @click="showGlosaConciliacion(props.item)">
                                                            <v-icon>remove_red_eye</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Ver glosas</span>
                                                </v-tooltip>
                                            </template>
                                            <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                                                Your search for "{{ searchFacturaC }}" found no results.
                                            </v-alert>
                                        </v-data-table>
                                    </v-card>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" flat @click="dialogInvoiceConciliacion = false">Cancelar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-dialog v-model="dialogAccionConciliacion" persistent max-width="650px">
                <v-card>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm12 md12>
                                    <v-text-field label="NIT" v-model="prestador" readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm12 md12>
                                    <v-text-field label="Nombre" v-model="namePrestador" readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm12 md12>
                                    <v-text-field label="Total Persiste Sumimedical"
                                        v-model="showPConciliacion['Total']" readonly></v-text-field>
                                </v-flex>
                                <v-flex xs3 sm3 md3>
                                    <v-btn color="success" @click="importExcel()">
                                        <v-icon>attachment</v-icon>
                                    </v-btn>
                                </v-flex>
                                <input type="file" v-show="false" ref="file" @change="onFilePicked">
                                <v-flex xs9 sm9 md9>
                                    <v-text-field readonly label="Nombre Excel" v-model="nameFile">
                                    </v-text-field>
                                    <v-divider class="my-4"></v-divider>
                                </v-flex>
                                <span>Adjuntar Acta de conciliación</span>
                                <v-flex xs12 sm12 md12>
                                    <input ref="adjuntos" type="file" @change="addActa" />
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" @click="closeAccionConciliacion()">Salir</v-btn>
                        <v-btn color="success" @click="saveConciliacion()">Guardar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>




            <v-dialog v-model="dialogAccionConciliacionAdministrativa" persistent max-width="650px">
                <v-card>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm12 md12>
                                    <v-text-field label="NIT" v-model="prestador" readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm12 md12>
                                    <v-text-field label="Nombre" v-model="namePrestador" readonly>
                                    </v-text-field>
                                </v-flex>

                                <v-flex xs12>
                                    <div class="text-xs-center">
                                    <v-dialog v-model="dialog" width="1000">
                                        <template v-slot:activator="{ on }">
                                            <v-btn color="primary" dark v-on="on">Ver Plantilla</v-btn>
                                        </template>
                                        <v-img height="300" width="1500" max-height="60"
                                               src="/images/plantillaConciliacionAdministrativa.PNG"></v-img>
                                    </v-dialog>
                                    </div>
                                </v-flex>


                                <v-flex xs3 sm3 md3>
                                    <v-btn color="success" @click="importExcel()">
                                        <v-icon>attachment</v-icon>
                                    </v-btn>
                                </v-flex>
                                <input type="file" v-show="false" ref="file" @change="onFilePicked">
                                <v-flex xs9 sm9 md9>
                                    <v-text-field readonly label="Nombre Excel" v-model="nameFile">
                                    </v-text-field>
                                    <v-divider class="my-4"></v-divider>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" @click="closeAccionConciliacion()">Salir</v-btn>
                        <v-btn color="success" @click="saveConciliacionAdministrativa()">Guardar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>



            <v-dialog v-model="dialogGlosaConciliacion" v-if="dialogGlosaConciliacion" fullscreen hide-overlay
                transition="dialog-bottom-transition" scrollable>
                <v-card tile>
                    <v-toolbar card dark color="primary">
                        <v-btn icon dark @click="dialogGlosaConciliacion = false">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Glosas</v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-card-text>

                        <v-layout wrap>
                            <v-flex xs12>
                                <v-card>
                                    <v-card-title>
                                        Factura # {{ af.numero_factura }}
                                        <v-spacer></v-spacer>
                                        <v-text-field v-model="searchAc" append-icon="search" label="Buscar"
                                                      single-line hide-details></v-text-field>
                                    </v-card-title>
                                    <v-data-table :headers="headersAc" :items="invoiceServiceConciliadas"
                                                  :search="searchAc">
                                        <template v-slot:items="props">
                                            <td class="text-xs-left">{{ props.item.codigo }}</td>
                                            <td class="text-xs-left">
                                                <v-textarea v-model="props.item.descripcionPrestador" rows="1"
                                                            cols="40" readonly>
                                                </v-textarea>
                                            </td>
                                            <td class="text-xs-left">{{ props.item.valorAPrestador }}</td>
                                            <td class="text-xs-left">{{ props.item.valorNPrestador }}</td>
                                            <td class="text-xs-left">
                                                <v-textarea v-model="props.item.descripcionSumi" rows="1"
                                                            cols="40" readonly>
                                                </v-textarea>
                                            </td>
                                            <td class="text-xs-left">{{ props.item.valorASumi }}</td>
                                            <td class="text-xs-left">{{ props.item.valorNSumi }}</td>
                                            <v-tooltip top v-if="props.item.archivo != null">
                                                <template v-slot:activator="{ on }">
                                                    <v-btn fab color="warning"
                                                           @click="showAdjunto(props.item.archivo)"
                                                           :disabled="loading" small v-on="on">
                                                        <v-icon>file_copy</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Ver adjunto</span>
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

<!--                        <v-expansion-panel v-if="invoiceServiceConciliadas.AC">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-if="invoiceServiceConciliadas.AC.length > 0">-->
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
<!--                                            <v-data-table :headers="headersAc" :items="invoiceServiceConciliadas.AC"-->
<!--                                                :search="searchAc">-->
<!--                                                <template v-slot:items="props">-->
<!--                                                    <td class="text-xs-left">{{ props.item.codigo }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionPrestador" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorAPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionSumi" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorASumi }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNSumi }}</td>-->
<!--                                                    <v-tooltip top v-if="props.item.archivo != null">-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="warning"-->
<!--                                                                @click="showAdjunto(props.item.archivo)"-->
<!--                                                                :disabled="loading" small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Ver adjunto</span>-->
<!--                                                    </v-tooltip>-->
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
<!--                        <v-expansion-panel v-if="invoiceServiceConciliadas.AP">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-if="invoiceServiceConciliadas.AP.length > 0">-->
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
<!--                                            <v-data-table :headers="headersAp" :items="invoiceServiceConciliadas.AP"-->
<!--                                                :search="searchAp">-->
<!--                                                <template v-slot:items="props">-->
<!--                                                    <td class="text-xs-left">{{ props.item.codigo }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionPrestador" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorAPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionSumi" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorASumi }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNSumi }}</td>-->
<!--                                                    <v-tooltip top v-if="props.item.archivo != null">-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="warning"-->
<!--                                                                @click="showAdjunto(props.item.archivo)"-->
<!--                                                                :disabled="loading" small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Ver adjunto</span>-->
<!--                                                    </v-tooltip>-->
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
<!--                        <v-expansion-panel v-if="invoiceServiceConciliadas.AT">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-if="invoiceServiceConciliadas.AT.length > 0">-->
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
<!--                                            <v-data-table :headers="headersAt" :items="invoiceServiceConciliadas.AT"-->
<!--                                                :search="searchAt">-->
<!--                                                <template v-slot:items="props">-->
<!--                                                    <td class="text-xs-left">{{ props.item.codigo }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionPrestador" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorAPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionSumi" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorASumi }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNSumi }}</td>-->
<!--                                                    <v-tooltip top v-if="props.item.archivo != null">-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="warning"-->
<!--                                                                @click="showAdjunto(props.item.archivo)"-->
<!--                                                                :disabled="loading" small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Ver adjunto</span>-->
<!--                                                    </v-tooltip>-->
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
<!--                        <v-expansion-panel v-if="invoiceServiceConciliadas.AM">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-if="invoiceServiceConciliadas.AM.length > 0">-->
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
<!--                                            <v-data-table :headers="headersAm" :items="invoiceServiceConciliadas.AM"-->
<!--                                                :search="searchAm">-->
<!--                                                <template v-slot:items="props">-->
<!--                                                    <td class="text-xs-left">{{ props.item.codigo }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionPrestador" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorAPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionSumi" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorASumi }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNSumi }}</td>-->
<!--                                                    <v-tooltip top v-if="props.item.archivo != null">-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="warning"-->
<!--                                                                @click="showAdjunto(props.item.archivo)"-->
<!--                                                                :disabled="loading" small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Ver adjunto</span>-->
<!--                                                    </v-tooltip>-->
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
                    <div style="flex: 1 1 auto;"></div>
                </v-card>
            </v-dialog>

            <v-dialog v-model="dialogInvoiceConciliacionSaldo" persistent max-width="976px">
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
                                            <v-tooltip top>
                                                <template v-slot:activator="{ on }">
                                                    <v-btn text icon color="error" :disabled="loading" dark v-on="on"
                                                        @click="accionConciliacionSaldo(invoicesConciliacionSaldo)">
                                                        <v-icon>settings</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Acción Conciliación Saldo</span>
                                            </v-tooltip>
                                            <v-spacer></v-spacer>
                                            <v-text-field v-model="searchFacturaCS" append-icon="search" label="Buscar"
                                                single-line hide-details></v-text-field>
                                        </v-card-title>
                                        <v-data-table class="elevation-1" :headers="headersInvoice"
                                            :items="invoicesConciliacionSaldo" :search="searchFacturaCS">
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
                                                            @click="showGlosaConciliacionSaldo(props.item)">
                                                            <v-icon>remove_red_eye</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Ver glosas</span>
                                                </v-tooltip>
                                            </template>
                                            <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                                                Your search for "{{ searchFacturaCS }}" found no results.
                                            </v-alert>
                                        </v-data-table>
                                    </v-card>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" flat @click="dialogInvoiceConciliacionSaldo = false">Cancelar
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-dialog v-model="dialogAccionConciliacionSaldo" persistent max-width="650px">
                <v-card>
                    <v-card-text>
                        <v-container grid-list-md>
                            <v-layout wrap>
                                <v-flex xs12 sm12 md12>
                                    <v-text-field label="NIT" v-model="prestador" readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm12 md12>
                                    <v-text-field label="Nombre" v-model="namePrestador" readonly>
                                    </v-text-field>
                                </v-flex>
                                <v-flex xs12 sm12 md12>
                                    <v-text-field label="Total No Acuerdo" v-model="showPConciliacionSaldo['Total']"
                                        readonly></v-text-field>
                                </v-flex>
                                <v-flex xs3 sm3 md3>
                                    <v-btn color="success" @click="importExcel()">
                                        <v-icon>attachment</v-icon>
                                    </v-btn>
                                </v-flex>
                                <input type="file" v-show="false" ref="file" @change="onFilePicked">
                                <v-flex xs9 sm9 md9>
                                    <v-text-field readonly label="Nombre Excel" v-model="nameFile">
                                    </v-text-field>
                                    <v-divider class="my-4"></v-divider>
                                </v-flex>
                                <span>Adjuntar Acta de conciliación</span>
                                <v-flex xs12 sm12 md12>
                                    <input ref="adjuntos" type="file" @change="addActa" />
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" @click="closeAccionConciliacion()">Salir</v-btn>
                        <v-btn color="success" @click="saveConciliacionSaldo()">Guardar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-dialog v-model="dialogGlosaConciliacionSaldo" v-if="dialogGlosaConciliacionSaldo" fullscreen hide-overlay
                transition="dialog-bottom-transition" scrollable>
                <v-card tile>
                    <v-toolbar card dark color="primary">
                        <v-btn icon dark @click="dialogGlosaConciliacionSaldo = false">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Glosas</v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-card-text>


                        <v-layout wrap>
                            <v-flex xs12>
                                <v-card>
                                    <v-card-title>
                                        Factura # {{ af.numero_factura }}
                                        <v-spacer></v-spacer>
                                        <v-text-field v-model="searchAc" append-icon="search" label="Buscar"
                                                      single-line hide-details></v-text-field>
                                    </v-card-title>
                                    <v-data-table :headers="headersAc" :items="invoiceServiceConciliadaSaldo"
                                                  :search="searchAc">
                                        <template v-slot:items="props">
                                            <td class="text-xs-left">{{ props.item.codigo }}</td>
                                            <td class="text-xs-left">
                                                <v-textarea v-model="props.item.descripcionPrestador" rows="1"
                                                            cols="40" readonly>
                                                </v-textarea>
                                            </td>
                                            <td class="text-xs-left">{{ props.item.valorAPrestador }}</td>
                                            <td class="text-xs-left">{{ props.item.valorNPrestador }}</td>
                                            <td class="text-xs-left">
                                                <v-textarea v-model="props.item.descripcionSumi" rows="1"
                                                            cols="40" readonly>
                                                </v-textarea>
                                            </td>
                                            <td class="text-xs-left">{{ props.item.valorASumi }}</td>
                                            <td class="text-xs-left">{{ props.item.valorNSumi }}</td>
                                            <v-tooltip top v-if="props.item.archivo != null">
                                                <template v-slot:activator="{ on }">
                                                    <v-btn fab color="warning"
                                                           @click="showAdjunto(props.item.archivo)"
                                                           :disabled="loading" small v-on="on">
                                                        <v-icon>file_copy</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Ver adjunto</span>
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

<!--                        <v-expansion-panel v-if="invoiceServiceConciliadaSaldo.AC">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-if="invoiceServiceConciliadaSaldo.AC.length > 0">-->
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
<!--                                            <v-data-table :headers="headersAc" :items="invoiceServiceConciliadaSaldo.AC"-->
<!--                                                :search="searchAc">-->
<!--                                                <template v-slot:items="props">-->
<!--                                                    <td class="text-xs-left">{{ props.item.codigo }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionPrestador" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorAPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionSumi" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorASumi }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNSumi }}</td>-->
<!--                                                    <v-tooltip top v-if="props.item.archivo != null">-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="warning"-->
<!--                                                                @click="showAdjunto(props.item.archivo)"-->
<!--                                                                :disabled="loading" small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Ver adjunto</span>-->
<!--                                                    </v-tooltip>-->
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
<!--                        <v-expansion-panel v-if="invoiceServiceConciliadaSaldo.AP">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-if="invoiceServiceConciliadaSaldo.AP.length > 0">-->
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
<!--                                            <v-data-table :headers="headersAp" :items="invoiceServiceConciliadaSaldo.AP"-->
<!--                                                :search="searchAp">-->
<!--                                                <template v-slot:items="props">-->
<!--                                                    <td class="text-xs-left">{{ props.item.codigo }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionPrestador" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorAPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionSumi" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorASumi }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNSumi }}</td>-->
<!--                                                    <v-tooltip top v-if="props.item.archivo != null">-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="warning"-->
<!--                                                                @click="showAdjunto(props.item.archivo)"-->
<!--                                                                :disabled="loading" small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Ver adjunto</span>-->
<!--                                                    </v-tooltip>-->
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
<!--                        <v-expansion-panel v-if="invoiceServiceConciliadaSaldo.AT">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-if="invoiceServiceConciliadaSaldo.AT.length > 0">-->
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
<!--                                            <v-data-table :headers="headersAt" :items="invoiceServiceConciliadaSaldo.AT"-->
<!--                                                :search="searchAt">-->
<!--                                                <template v-slot:items="props">-->
<!--                                                    <td class="text-xs-left">{{ props.item.codigo }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionPrestador" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorAPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionSumi" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorASumi }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNSumi }}</td>-->
<!--                                                    <v-tooltip top v-if="props.item.archivo != null">-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="warning"-->
<!--                                                                @click="showAdjunto(props.item.archivo)"-->
<!--                                                                :disabled="loading" small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Ver adjunto</span>-->
<!--                                                    </v-tooltip>-->
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
<!--                        <v-expansion-panel v-if="invoiceServiceConciliadaSaldo.AM">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-if="invoiceServiceConciliadaSaldo.AM.length > 0">-->
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
<!--                                            <v-data-table :headers="headersAm" :items="invoiceServiceConciliadaSaldo.AM"-->
<!--                                                :search="searchAm">-->
<!--                                                <template v-slot:items="props">-->
<!--                                                    <td class="text-xs-left">{{ props.item.codigo }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionPrestador" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorAPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionSumi" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorASumi }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNSumi }}</td>-->
<!--                                                    <v-tooltip top v-if="props.item.archivo != null">-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="warning"-->
<!--                                                                @click="showAdjunto(props.item.archivo)"-->
<!--                                                                :disabled="loading" small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Ver adjunto</span>-->
<!--                                                    </v-tooltip>-->
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
                    <div style="flex: 1 1 auto;"></div>
                </v-card>
            </v-dialog>

            <v-dialog v-model="dialogInvoiceCerradas" persistent max-width="976px">
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
                                            <v-text-field v-model="searchFacturaClose" append-icon="search"
                                                label="Buscar" single-line hide-details></v-text-field>
                                        </v-card-title>
                                        <v-data-table class="elevation-1" :headers="headersInvoice"
                                            :items="invoicesCerradas" :search="searchFacturaClose">
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
                                                            @click="showGlosaCerradas(props.item)">
                                                            <v-icon>remove_red_eye</v-icon>
                                                        </v-btn>
                                                    </template>
                                                    <span>Ver glosas</span>
                                                </v-tooltip>
                                            </template>
                                            <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                                                Your search for "{{ searchFacturaClose }}" found no results.
                                            </v-alert>
                                        </v-data-table>
                                    </v-card>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" flat @click="dialogInvoiceCerradas = false">Cancelar</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-dialog v-model="dialogGlosaCerradas" v-if="dialogGlosaCerradas" fullscreen hide-overlay
                transition="dialog-bottom-transition" scrollable>
                <v-card tile>
                    <v-toolbar card dark color="primary">
                        <v-btn icon dark @click="dialogGlosaCerradas = false">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Glosas</v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-card-text>

                        <v-layout wrap>
                            <v-flex xs12>
                                <v-card>
                                    <v-card-title>
                                        Factura # {{ af.numero_factura }}
                                        <v-spacer></v-spacer>
                                        <v-text-field v-model="searchAc" append-icon="search" label="Buscar"
                                                      single-line hide-details></v-text-field>
                                    </v-card-title>
                                    <v-data-table :headers="headersAc" :items="invoiceServiceCerradas"
                                                  :search="searchAc">
                                        <template v-slot:items="props">
                                            <td class="text-xs-left">{{ props.item.codigo }}</td>
                                            <td class="text-xs-left">
                                                <v-textarea v-model="props.item.descripcionPrestador" rows="1"
                                                            cols="40" readonly>
                                                </v-textarea>
                                            </td>
                                            <td class="text-xs-left">{{ props.item.valorAPrestador }}</td>
                                            <td class="text-xs-left">{{ props.item.valorNPrestador }}</td>
                                            <td class="text-xs-left">
                                                <v-textarea v-model="props.item.descripcionSumi" rows="1"
                                                            cols="40" readonly>
                                                </v-textarea>
                                            </td>
                                            <td class="text-xs-left">{{ props.item.valorASumi }}</td>
                                            <td class="text-xs-left">{{ props.item.valorNSumi }}</td>
                                            <v-tooltip top v-if="props.item.archivo != null">
                                                <template v-slot:activator="{ on }">
                                                    <v-btn fab color="warning"
                                                           @click="showAdjunto(props.item.archivo)"
                                                           :disabled="loading" small v-on="on">
                                                        <v-icon>file_copy</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Ver adjunto</span>
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

<!--                        <v-expansion-panel v-if="invoiceServiceCerradas.AC">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-if="invoiceServiceCerradas.AC.length > 0">-->
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
<!--                                            <v-data-table :headers="headersAc" :items="invoiceServiceCerradas.AC"-->
<!--                                                :search="searchAc">-->
<!--                                                <template v-slot:items="props">-->
<!--                                                    <td class="text-xs-left">{{ props.item.codigo }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionPrestador" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorAPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionSumi" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorASumi }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNSumi }}</td>-->
<!--                                                    <v-tooltip top v-if="props.item.archivo != null">-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="warning"-->
<!--                                                                @click="showAdjunto(props.item.archivo)"-->
<!--                                                                :disabled="loading" small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Ver adjunto</span>-->
<!--                                                    </v-tooltip>-->
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
<!--                        <v-expansion-panel v-if="invoiceServiceCerradas.AP">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-if="invoiceServiceCerradas.AP.length > 0">-->
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
<!--                                            <v-data-table :headers="headersAp" :items="invoiceServiceCerradas.AP"-->
<!--                                                :search="searchAp">-->
<!--                                                <template v-slot:items="props">-->
<!--                                                    <td class="text-xs-left">{{ props.item.codigo }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionPrestador" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorAPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionSumi" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorASumi }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNSumi }}</td>-->
<!--                                                    <v-tooltip top v-if="props.item.archivo != null">-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="warning"-->
<!--                                                                @click="showAdjunto(props.item.archivo)"-->
<!--                                                                :disabled="loading" small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Ver adjunto</span>-->
<!--                                                    </v-tooltip>-->
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
<!--                        <v-expansion-panel v-if="invoiceServiceCerradas.AT">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-if="invoiceServiceCerradas.AT.length > 0">-->
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
<!--                                            <v-data-table :headers="headersAt" :items="invoiceServiceCerradas.AT"-->
<!--                                                :search="searchAt">-->
<!--                                                <template v-slot:items="props">-->
<!--                                                    <td class="text-xs-left">{{ props.item.codigo }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionPrestador" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorAPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionSumi" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorASumi }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNSumi }}</td>-->
<!--                                                    <v-tooltip top v-if="props.item.archivo != null">-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="warning"-->
<!--                                                                @click="showAdjunto(props.item.archivo)"-->
<!--                                                                :disabled="loading" small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Ver adjunto</span>-->
<!--                                                    </v-tooltip>-->
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
<!--                        <v-expansion-panel v-if="invoiceServiceCerradas.AM">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-if="invoiceServiceCerradas.AM.length > 0">-->
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
<!--                                            <v-data-table :headers="headersAm" :items="invoiceServiceCerradas.AM"-->
<!--                                                :search="searchAm">-->
<!--                                                <template v-slot:items="props">-->
<!--                                                    <td class="text-xs-left">{{ props.item.codigo }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionPrestador" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorAPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNPrestador }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionSumi" rows="1"-->
<!--                                                            cols="40" readonly>-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorASumi }}</td>-->
<!--                                                    <td class="text-xs-left">{{ props.item.valorNSumi }}</td>-->
<!--                                                    <v-tooltip top v-if="props.item.archivo != null">-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="warning"-->
<!--                                                                @click="showAdjunto(props.item.archivo)"-->
<!--                                                                :disabled="loading" small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Ver adjunto</span>-->
<!--                                                    </v-tooltip>-->
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
                    <div style="flex: 1 1 auto;"></div>
                </v-card>
            </v-dialog>

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

    </div>
</template>

<script>
    import Swal from 'sweetalert2';
    import {
        AdjuntoService
    } from '../../../services';
    export default {
        name: 'MedicalBillsAuditoriaFinal',
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
                dialog:false,
                dialogAccionConciliacionAdministrativa:false,
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
                headersAc: [{
                        text: 'Codigo respuesta',
                        align: 'left',
                        value: 'codigo',
                        sortable: false
                    },
                    {
                        text: 'Descripción',
                        align: 'left',
                        value: 'descripcionPrestador',
                        sortable: false
                    },
                    {
                        text: 'Valor aceptado',
                        align: 'left',
                        value: 'valorAPrestador',
                        sortable: false
                    },
                    {
                        text: 'Valor no aceptado',
                        value: 'valorNPrestador',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Descripción',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Levanta sumimedical',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Persiste sumimedical',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: '',
                        align: 'left',
                        sortable: false
                    }
                ],
                headersAp: [{
                        text: 'Codigo respuesta',
                        align: 'left',
                        value: 'codigo',
                        sortable: false
                    },
                    {
                        text: 'Descripción',
                        align: 'left',
                        value: 'descripcionPrestador',
                        sortable: false
                    },
                    {
                        text: 'Valor aceptado',
                        align: 'left',
                        value: 'valorAPrestador',
                        sortable: false
                    },
                    {
                        text: 'Valor no aceptado',
                        value: 'valorNPrestador',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Descripción',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Levanta sumimedical',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Persiste sumimedical',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: '',
                        align: 'left',
                        sortable: false
                    }
                ],
                headersAt: [{
                        text: 'Codigo respuesta',
                        align: 'left',
                        value: 'codigo',
                        sortable: false
                    },
                    {
                        text: 'Descripción',
                        align: 'left',
                        value: 'descripcionPrestador',
                        sortable: false
                    },
                    {
                        text: 'Valor aceptado',
                        align: 'left',
                        value: 'valorAPrestador',
                        sortable: false
                    },
                    {
                        text: 'Valor no aceptado',
                        value: 'valorNPrestador',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Descripción',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Levanta sumimedical',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Persiste sumimedical',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: '',
                        align: 'left',
                        sortable: false
                    }
                ],
                headersAm: [{
                        text: 'Codigo respuesta',
                        align: 'left',
                        value: 'codigo',
                        sortable: false
                    },
                    {
                        text: 'Descripción',
                        align: 'left',
                        value: 'descripcionPrestador',
                        sortable: false
                    },
                    {
                        text: 'Valor aceptado',
                        align: 'left',
                        value: 'valorAPrestador',
                        sortable: false
                    },
                    {
                        text: 'Valor no aceptado',
                        value: 'valorNPrestador',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Descripción',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Levanta sumimedical',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Persiste sumimedical',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: '',
                        align: 'left',
                        sortable: false
                    }
                ],
                pendientes: false,
                conciliacion: false,
                cerradas: false,
                tipoFinal: false,
                items: [{
                        title: 'Conciliar',
                    },
                    {
                        title: 'Cerrar'
                    }
                ],
                allConciliacion: [],
                dialogInvoiceConciliacion: false,
                invoicesConciliacion: [],
                search2: '',
                searchFacturaC: '',
                dialogGlosaConciliacion: false,
                invoiceServiceConciliadas: [],
                search3: '',
                allCerradas: [],
                invoicesCerradas: [],
                dialogInvoiceCerradas: false,
                searchFacturaClose: '',
                invoiceServiceCerradas: [],
                dialogGlosaCerradas: false,
                dialogAccionConciliacion: false,
                showPConciliacion: [],
                namePrestador: '',
                preload: false,
                nameFile: '',
                excels: {},
                conciliacionSaldo: false,
                search4: '',
                allConciliacionSaldo: [],
                invoicesConciliacionSaldo: [],
                dialogInvoiceConciliacionSaldo: false,
                searchFacturaCS: '',
                dialogGlosaConciliacionSaldo: false,
                invoiceServiceConciliadaSaldo: [],
                dialogAccionConciliacionSaldo: false,
                showPConciliacionSaldo: [],
                actas: {},
                prestador_id: ''
            }
        },
        methods: {
            auditadasFinal() {
                this.$emit("updateCount");
                axios.get('/api/medicalBills/auditoriaFinal').then(res => {
                    this.allAuditadas = res.data;
                }).catch(e => {
                    console.log(e)
                })
            },
            facturasConciliacion() {
                axios.get('/api/medicalBills/facturasFinalizadas').then(res => {
                    this.allConciliacion = res.data;
                }).catch(e => {
                    console.log(e)
                })
            },
            facturasConciliacionSaldo() {
                axios.get('/api/medicalBills/facturasConSaldo').then(res => {
                    this.allConciliacionSaldo = res.data;
                }).catch(e => {
                    console.log(e)
                })
            },
            facturasCerradas() {
                axios.get('/api/medicalBills/facturasCerradas').then(res => {
                    this.allCerradas = res.data;
                }).catch(e => {
                    console.log(e)
                })
            },
            invoiceAuditadas(proveedor) {
                this.clearSearch();
                this.prestador = proveedor.Nit
                this.prestador_id = proveedor.id
                this.loading = true;
                axios.get('/api/medicalBills/invoiceAuditoriaFinal/' + proveedor.id).then(res => {
                    this.invoice = res.data;
                    this.dialogInvoice = true
                    this.loading = false;
                }).catch(e => {
                    console.log(e)
                });
            },
            invoiceConciliacion(proveedor) {
                this.namePrestador = proveedor.Nombre
                this.clearSearch();
                this.prestador = proveedor.Nit
                this.prestador_id = proveedor.id
                this.loading = true;
                axios.get('/api/medicalBills/invoiceConciliacion/' + proveedor.id).then(res => {
                    this.invoicesConciliacion = res.data;
                    this.dialogInvoiceConciliacion = true
                    this.loading = false;
                }).catch(e => {
                    console.log(e)
                });
            },
            invoiceConciliacionSaldo(proveedor) {
                this.namePrestador = proveedor.Nombre
                this.clearSearch();
                this.prestador = proveedor.Nit
                this.prestador_id = proveedor.id
                this.loading = true;
                axios.get('/api/medicalBills/invoiceConciliacionSaldo/' + proveedor.id).then(res => {
                    this.invoicesConciliacionSaldo = res.data;
                    this.dialogInvoiceConciliacionSaldo = true
                    this.loading = false;
                }).catch(e => {
                    console.log(e)
                });
            },
            invoiceCerradas(proveedor) {
                this.clearSearch();
                this.prestador = proveedor.Nit
                this.prestador_id = proveedor.id
                this.loading = true;
                axios.get('/api/medicalBills/invoiceCerradas/' + proveedor.id).then(res => {
                    this.invoicesCerradas = res.data;
                    this.dialogInvoiceCerradas = true
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
                this.clearSearch();
                this.af = afs
                this.dialogGlosa = true
                axios.get('/api/medicalBills/invoiceServiceAuditoriaFinal/' + this.af.id).then(res => {
                    this.invoiceServiceAuditadas = res.data
                })
            },
            showGlosaConciliacion(afs) {
                this.clearSearch();
                this.af = afs
                this.dialogGlosaConciliacion = true
                axios.get('/api/medicalBills/invoiceServiceConciliacion/' + this.af.id).then(res => {
                    this.invoiceServiceConciliadas = res.data
                })
            },
            showGlosaConciliacionSaldo(afs) {
                this.clearSearch();
                this.af = afs
                this.dialogGlosaConciliacionSaldo = true
                axios.get('/api/medicalBills/invoiceServiceConciliacionSaldo/' + this.af.id).then(res => {
                    this.invoiceServiceConciliadaSaldo = res.data
                })
            },
            showGlosaCerradas(afs) {
                this.clearSearch();
                this.af = afs
                this.dialogGlosaCerradas = true
                axios.get('/api/medicalBills/invoiceServiceCerradas/' + this.af.id).then(res => {
                    this.invoiceServiceCerradas = res.data
                })
            },
            clearSearch() {
                this.search4 = ''
                this.search = ''
                this.searchAc = ''
                this.searchAp = ''
                this.searchAt = ''
                this.searchAm = ''
                this.searchFactura = ''
                this.search2 = ''
                this.searchFacturaC = ''
                this.searchFacturaClose = ''
                this.search3 = ''
                this.searchFacturaCS = ''
            },
            addglosa(glosa, tipo) {
                if (!glosa.descripcionSumi || !glosa.valorASumi || !glosa.valorNSumi) {
                    this.$alerError('No puede guardar una respuesta sin llenar los campos obligatorios!');
                    return
                }
                let sumaPrestador = parseInt(glosa.valorAPrestador) + parseInt(glosa.valorNPrestador);
                let sumaSumi = parseInt(glosa.valorASumi) + parseInt(glosa.valorNSumi);
                if( sumaPrestador < sumaSumi ){
                    this.$alerError('La suma de los valores Sumimedical son mayores a los del Prestador!');
                    return
                }
                this.loading = true
                let data = {
                    glosa: glosa,
                    tipo: tipo
                }
                axios.post('/api/medicalBills/respuestaSumi', data).then(res => {
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
                        title: 'Respuesta a glosa guardada con exito!'
                    })
                    this.showGlosa(this.af)
                }).catch(e => {
                    this.loading = false
                    console.log(e)
                });
            },
            showAdjunto(ruta) {
                window.open(ruta, "_blank");
            },
            tipos(val) {
                if (val == 1) {
                    return 'Conciliación'
                } else if (val == 2) {
                    return 'Cerrado'
                } else {
                    return 'Ninguna'
                }
            },
            saveAuditoriaFinal(factura) {
                let vacio = false;
                for (let i = 0; i < factura.length; i++) {
                    const element = factura[i]
                    if (element.estado_sumi == null) {
                        vacio = true;
                        break;
                    }
                }
                // if (vacio == false) {
                //     for (let i = 0; i < factura.AP.length; i++) {
                //         const element = factura.AP[i]
                //         if (element.estado_sumi == null) {
                //             vacio = true;
                //             break;
                //         }
                //     }
                // }
                // if (vacio == false) {
                //     for (let i = 0; i < factura.AT.length; i++) {
                //         const element = factura.AT[i]
                //         if (element.estado_sumi == null) {
                //             vacio = true;
                //             break;
                //         }
                //     }
                // }
                // if (vacio == false) {
                //     for (let i = 0; i < factura.AM.length; i++) {
                //         const element = factura.AM[i]
                //         if (element.estado_sumi == null) {
                //             vacio = true;
                //             break;
                //         }
                //     }
                // }
                if (vacio == true) {
                    swal({
                        title: "¡No ha dado respuesta a todas las glosas!",
                        text: ` `,
                        timer: 2000,
                        icon: "error",
                        buttons: false
                    });
                    return;
                }
                axios.post('/api/medicalBills/saveAuditoriaFinal', {
                    af_id: this.af.id
                }).then(res => {
                    this.auditadasFinal();
                    this.dialogGlosa = false
                    this.dialogInvoice = false
                    this.$alerSuccess('Auditoria final guardada con exito!');
                }).catch(e => {
                    console.log(e)
                });
            },
            addActa(e){
                const acta = e.target.files
                this.actas.file = acta[0]
            },
            saveConciliacionAdministrativa(){
                if (this.excels.file == undefined) {
                    return;
                }
                let formData = new FormData();
                formData.append('excel', this.excels.file);
                formData.append('prestador_id', this.prestador_id)
                formData.append('nit_prestador', this.prestador)
                this.preload = true
                axios.post('/api/medicalBills/saveConciliacionAdministrativa', formData).then(res => {
                    this.closeAccionConciliacion();
                    this.facturasConciliacion();
                    this.preload = false
                    this.dialogInvoiceConciliacion = false
                    this.$alerSuccess('Conciliación Administrativa guardada con exito!')
                }).catch(e => {
                    this.preload = false
                    this.$alerError(e.response.data.message);
                });
            },
            saveConciliacion() {
                if (this.excels.file == undefined) {
                    return;
                }
                let formData = new FormData();
                formData.append('excel', this.excels.file);
                formData.append('adjunto', this.actas.file);
                formData.append('prestador_id', this.prestador_id)
                formData.append('nit_prestador', this.prestador)
                this.preload = true
                axios.post('/api/medicalBills/saveConciliacion', formData).then(res => {
                    this.closeAccionConciliacion();
                    this.facturasConciliacion();
                    this.preload = false
                    this.dialogInvoiceConciliacion = false
                    this.$alerSuccess('Conciliación guardada con exito!')
                }).catch(e => {
                    this.preload = false
                    this.$alerError(e.response.data.message);
                });
            },
            saveConciliacionSaldo() {
                if (this.excels.file == undefined) {
                    return;
                }
                let formData = new FormData();
                formData.append('excel', this.excels.file);
                formData.append('adjunto', this.actas.file);
                formData.append('prestador_id', this.prestador_id)
                formData.append('nit_prestador', this.prestador)
                this.preload = true
                axios.post('/api/medicalBills/saveConciliacionSaldo', formData).then(res => {
                    this.closeAccionConciliacion();
                    this.facturasConciliacionSaldo();
                    this.preload = false
                    this.dialogInvoiceConciliacionSaldo = false
                    this.$alerSuccess('Conciliación guardada con exito!')
                }).catch(e => {
                    this.preload = false
                    this.$alerError(e.response.data.message);
                });
            },
            accionConciliacion(invoice) {
                let formatDate = new FormData();
                for (let i = 0; i < invoice.length; i++) {
                    formatDate.append('af_id[]', invoice[i].id)
                }
                this.preload = true
                axios.post('/api/medicalBills/showPersisteSumi', formatDate).then(res => {
                    this.preload = false
                    this.dialogAccionConciliacion = true
                    this.showPConciliacion = res.data
                }).catch(e => {
                    this.preload = false
                    console.log(e)
                });
            },
            accionConciliacionAdministrativa(){
                this.dialogAccionConciliacionAdministrativa = true
            },
            accionConciliacionSaldo(invoice) {
                let formatDate = new FormData();
                for (let i = 0; i < invoice.length; i++) {
                    formatDate.append('af_id[]', invoice[i].id)
                }
                this.preload = true
                axios.post('/api/medicalBills/showPersisteSumiSaldo', formatDate).then(res => {
                    this.preload = false
                    this.dialogAccionConciliacionSaldo = true
                    this.showPConciliacionSaldo = res.data
                }).catch(e => {
                    this.preload = false
                    console.log(e)
                });
            },
            importExcel() {
                this.nameFile = ''
                this.$refs.file.value = null
                this.$refs.file.click()
            },
            onFilePicked(e) {
                let ex = e.target.files
                this.nameFile = ex[0].name
                this.excels.file = ex[0]
            },
            closeAccionConciliacion() {
                this.nameFile = ''
                this.$refs.file.value = null
                this.dialogAccionConciliacion = false
                this.dialogAccionConciliacionSaldo = false
                this.dialogAccionConciliacionAdministrativa = false;
                this.excels.file = ''
                this.actas.file = ''
                this.$refs.adjuntos.value = null
            }

        }
    }

</script>
