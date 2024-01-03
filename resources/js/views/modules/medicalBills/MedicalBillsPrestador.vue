<template>
    <div>

        <v-bottom-nav :value="true" color="transparent">
            <v-btn color="primary" flat @click="pendientes = true, cerradas = false, conciliacion = false">
                <span>Pendientes</span>
                <v-icon>block</v-icon>
            </v-btn>
            <v-btn color="success" flat @click="conciliacion = true, pendientes = false, cerradas = false">
                <span>Conciliación</span>
                <v-icon>list_alt</v-icon>
            </v-btn>
            <v-btn color="error" flat @click="cerradas = true, conciliacion = false, pendientes = false">
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
                <v-data-table :headers="headers" :items="allGlosas" :search="search">
                    <template v-slot:items="props">
                        <td>{{ props.item.Nit }}</td>
                        <td>{{ props.item.created_at.split(' ')[0] }}</td>
                        <td>{{ props.item.numero_factura }}</td>
                        <td><strong> $ {{ props.item.valor_Neto }}</strong></td>
                        <v-btn color="#00b297" :loading="loading" :disabled="loading" dark
                            @click="glosasPrestador(props.item)">Respuesta Glosa</v-btn>
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
                <v-data-table :headers="headers" :items="allGlosasConciliacion" :search="search2">
                    <template v-slot:items="props">
                        <td>{{ props.item.Nit }}</td>
                        <td>{{ props.item.created_at.split(' ')[0] }}</td>
                        <td>{{ props.item.numero_factura }}</td>
                        <td><strong> $ {{ props.item.valor_Neto }}</strong></td>
                        <v-btn color="primary" :loading="loading" :disabled="loading" dark
                            @click="showGlosasPrestadorConciliacion(props.item)">Ver</v-btn>
                    </template>
                    <template v-slot:no-results>
                        <v-alert :value="true" color="error" icon="warning">
                            Your search for "{{ search2 }}" found no results.
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
                <v-data-table :headers="headers" :items="allGlosasCerradas" :search="search3">
                    <template v-slot:items="props">
                        <td>{{ props.item.Nit }}</td>
                        <td>{{ props.item.created_at.split(' ')[0] }}</td>
                        <td>{{ props.item.numero_factura }}</td>
                        <td><strong> $ {{ props.item.valor_Neto }}</strong></td>
                        <v-btn color="primary" :loading="loading" :disabled="loading" dark
                            @click="showGlosasPrestadorCerradas(props.item)">Ver</v-btn>
                    </template>
                    <template v-slot:no-results>
                        <v-alert :value="true" color="error" icon="warning">
                            Your search for "{{ search3 }}" found no results.
                        </v-alert>
                    </template>
                </v-data-table>
            </v-flex>

            <v-dialog v-model="dialogGlosa" v-if="dialogGlosa" fullscreen hide-overlay
                transition="dialog-bottom-transition" scrollable>
                <v-card tile>
                    <v-toolbar card dark color="primary">
                        <v-btn icon dark @click="dialogGlosa = false">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Respuesta</v-toolbar-title>
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
                                    <v-data-table :headers="headersfactura" :items="invoiceService"
                                                  :search="searchAc">
                                        <template v-slot:items="props">
                                            <td>{{ props.item.codglosa }}</td>
                                            <td class="text-xs-left">
                                                <v-textarea v-model="props.item.descripcion" readonly rows="1"
                                                            cols="50">
                                                </v-textarea>
                                            <td>{{ props.item.valor }}</td>
                                            <td class="text-xs-left">
                                                <v-select rows="10" cols="60" :items="codigos"
                                                          v-model="props.item.codigo" autocomplete item-text="codigo"
                                                          item-value="codigo">
                                                </v-select>
                                            </td>
                                            <td class="text-xs-left">
                                                <v-textarea v-model="props.item.descripcionPrestador" rows="1"
                                                            cols="50">
                                                </v-textarea>
                                            </td>
                                            <td class="text-xs-left">
                                                <v-flex sm12 xs12 md12>
                                                    <v-text-field v-model="props.item.valorAPrestador"
                                                                  type="number"
                                                                  onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                                                  min="1"></v-text-field>
                                                </v-flex>
                                            </td>
                                            <td class="text-xs-left">
                                                <v-flex sm12 xs12 md12>
                                                    <v-text-field v-model="props.item.valorNPrestador"
                                                                  type="number"
                                                                  onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                                                                  min="1"></v-text-field>
                                                </v-flex>
                                            </td>
                                            <v-tooltip top>
                                                <template v-slot:activator="{ on }">
                                                    <input v-show="false" @change="onFilePicked" type="file"
                                                           ref="adjunto">
                                                    <v-btn v-if="props.item.archivo != null" fab color="primary"
                                                           @click="addAdjunto(props.item)" :disabled="loading"
                                                           small v-on="on">
                                                        <v-icon>file_copy</v-icon>
                                                    </v-btn>
                                                    <v-btn v-else fab color="warning"
                                                           @click="addAdjunto(props.item)" :disabled="loading"
                                                           small v-on="on">
                                                        <v-icon>file_copy</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Adjuntar</span>
                                            </v-tooltip>
                                            <v-tooltip top v-if="props.item.estado_respuesta != null">
                                                <template v-slot:activator="{ on }">
                                                    <v-btn fab color="success" :disabled="loading" small
                                                           v-on="on" @click="addglosa(props.item)">
                                                        <v-icon>done_all</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Guardar</span>
                                            </v-tooltip>
                                            <v-tooltip top v-else>
                                                <template v-slot:activator="{ on }">
                                                    <v-btn fab color="info" :disabled="loading" small v-on="on"
                                                           @click="addglosa(props.item)">
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


<!--                        <v-expansion-panel v-if="invoiceService.AC">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-show="invoiceService.AC.length > 0">-->
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
<!--                                            <v-data-table :headers="headersfactura" :items="invoiceService.AC"-->
<!--                                                :search="searchAc">-->
<!--                                                <template v-slot:items="props">-->
<!--                                                    <td>{{ props.item.codglosa }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcion" readonly rows="1"-->
<!--                                                            cols="50">-->
<!--                                                        </v-textarea>-->
<!--                                                    <td>{{ props.item.valor }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-select rows="10" cols="60" :items="codigos"-->
<!--                                                            v-model="props.item.codigo" autocomplete item-text="codigo"-->
<!--                                                            item-value="codigo">-->
<!--                                                        </v-select>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionPrestador" rows="1"-->
<!--                                                            cols="50">-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-flex sm12 xs12 md12>-->
<!--                                                            <v-text-field v-model="props.item.valorAPrestador"-->
<!--                                                                type="number"-->
<!--                                                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"-->
<!--                                                                min="1"></v-text-field>-->
<!--                                                        </v-flex>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-flex sm12 xs12 md12>-->
<!--                                                            <v-text-field v-model="props.item.valorNPrestador"-->
<!--                                                                type="number"-->
<!--                                                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"-->
<!--                                                                min="1"></v-text-field>-->
<!--                                                        </v-flex>-->
<!--                                                    </td>-->
<!--                                                    <v-tooltip top>-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <input v-show="false" @change="onFilePicked" type="file"-->
<!--                                                                ref="adjunto">-->
<!--                                                            <v-btn v-if="props.item.archivo != null" fab color="primary"-->
<!--                                                                @click="addAdjunto(props.item)" :disabled="loading"-->
<!--                                                                small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                            <v-btn v-else fab color="warning"-->
<!--                                                                @click="addAdjunto(props.item)" :disabled="loading"-->
<!--                                                                small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Adjuntar</span>-->
<!--                                                    </v-tooltip>-->
<!--                                                    <v-tooltip top v-if="props.item.estado_respuesta != null">-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="success" :disabled="loading" small-->
<!--                                                                v-on="on" @click="addglosa(props.item)">-->
<!--                                                                <v-icon>done_all</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Guardar</span>-->
<!--                                                    </v-tooltip>-->
<!--                                                    <v-tooltip top v-else>-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="info" :disabled="loading" small v-on="on"-->
<!--                                                                @click="addglosa(props.item)">-->
<!--                                                                <v-icon>done</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Guardar</span>-->
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
<!--                        <v-expansion-panel v-if="invoiceService.AP">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-show="invoiceService.AP.length > 0">-->
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
<!--                                            <v-data-table :headers="headersfactura" :items="invoiceService.AP"-->
<!--                                                :search="searchAp">-->
<!--                                                <template v-slot:items="props">-->
<!--                                                    <td>{{ props.item.codglosa }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcion" readonly rows="1"-->
<!--                                                            cols="50">-->
<!--                                                        </v-textarea>-->
<!--                                                    <td>{{ props.item.valor }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-select rows="10" cols="60" :items="codigos"-->
<!--                                                            v-model="props.item.codigo" autocomplete item-text="codigo"-->
<!--                                                            item-value="codigo">-->
<!--                                                        </v-select>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionPrestador" rows="1"-->
<!--                                                            cols="50">-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-flex sm12 xs12 md12>-->
<!--                                                            <v-text-field v-model="props.item.valorAPrestador"-->
<!--                                                                type="number"-->
<!--                                                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"-->
<!--                                                                min="1"></v-text-field>-->
<!--                                                        </v-flex>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-flex sm12 xs12 md12>-->
<!--                                                            <v-text-field v-model="props.item.valorNPrestador"-->
<!--                                                                type="number"-->
<!--                                                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"-->
<!--                                                                min="1"></v-text-field>-->
<!--                                                        </v-flex>-->
<!--                                                    </td>-->
<!--                                                    <v-tooltip top>-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <input v-show="false" @change="onFilePicked" type="file"-->
<!--                                                                ref="adjunto">-->
<!--                                                            <v-btn v-if="props.item.archivo != null" fab color="primary"-->
<!--                                                                @click="addAdjunto(props.item)" :disabled="loading"-->
<!--                                                                small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                            <v-btn v-else fab color="warning"-->
<!--                                                                @click="addAdjunto(props.item)" :disabled="loading"-->
<!--                                                                small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Adjuntar</span>-->
<!--                                                    </v-tooltip>-->
<!--                                                    <v-tooltip top v-if="props.item.estado_respuesta != null">-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="success" :disabled="loading" small-->
<!--                                                                v-on="on" @click="addglosa(props.item)">-->
<!--                                                                <v-icon>done_all</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Guardar</span>-->
<!--                                                    </v-tooltip>-->
<!--                                                    <v-tooltip top v-else>-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="info" :disabled="loading" small v-on="on"-->
<!--                                                                @click="addglosa(props.item)">-->
<!--                                                                <v-icon>done</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Guardar</span>-->
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
<!--                        <v-expansion-panel v-if="invoiceService.AT">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-show="invoiceService.AT.length > 0">-->
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
<!--                                            <v-data-table :headers="headersfactura" :items="invoiceService.AT"-->
<!--                                                :search="searchAt">-->
<!--                                                <template v-slot:items="props">-->
<!--                                                    <td>{{ props.item.codglosa }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcion" readonly rows="1"-->
<!--                                                            cols="50">-->
<!--                                                        </v-textarea>-->
<!--                                                    <td>{{ props.item.valor }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-select rows="10" cols="60" :items="codigos"-->
<!--                                                            v-model="props.item.codigo" autocomplete item-text="codigo"-->
<!--                                                            item-value="codigo">-->
<!--                                                        </v-select>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionPrestador" rows="1"-->
<!--                                                            cols="50">-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-flex sm12 xs12 md12>-->
<!--                                                            <v-text-field v-model="props.item.valorAPrestador"-->
<!--                                                                type="number"-->
<!--                                                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"-->
<!--                                                                min="1"></v-text-field>-->
<!--                                                        </v-flex>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-flex sm12 xs12 md12>-->
<!--                                                            <v-text-field v-model="props.item.valorNPrestador"-->
<!--                                                                type="number"-->
<!--                                                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"-->
<!--                                                                min="1"></v-text-field>-->
<!--                                                        </v-flex>-->
<!--                                                    </td>-->
<!--                                                    <v-tooltip top>-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <input v-show="false" @change="onFilePicked" type="file"-->
<!--                                                                ref="adjunto">-->
<!--                                                            <v-btn v-if="props.item.archivo != null" fab color="primary"-->
<!--                                                                @click="addAdjunto(props.item)" :disabled="loading"-->
<!--                                                                small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                            <v-btn v-else fab color="warning"-->
<!--                                                                @click="addAdjunto(props.item)" :disabled="loading"-->
<!--                                                                small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Adjuntar</span>-->
<!--                                                    </v-tooltip>-->
<!--                                                    <v-tooltip top v-if="props.item.estado_respuesta != null">-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="success" :disabled="loading" small-->
<!--                                                                v-on="on" @click="addglosa(props.item)">-->
<!--                                                                <v-icon>done_all</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Guardar</span>-->
<!--                                                    </v-tooltip>-->
<!--                                                    <v-tooltip top v-else>-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="info" :disabled="loading" small v-on="on"-->
<!--                                                                @click="addglosa(props.item)">-->
<!--                                                                <v-icon>done</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Guardar</span>-->
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
<!--                        <v-expansion-panel v-if="invoiceService.AM">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-show="invoiceService.AM.length > 0">-->
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
<!--                                            <v-data-table :headers="headersfactura" :items="invoiceService.AM"-->
<!--                                                :search="searchAm">-->
<!--                                                <template v-slot:items="props">-->
<!--                                                    <td>{{ props.item.codglosa }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcion" readonly rows="1"-->
<!--                                                            cols="50">-->
<!--                                                        </v-textarea>-->
<!--                                                    <td>{{ props.item.valor }}</td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-select rows="10" cols="60" :items="codigos"-->
<!--                                                            v-model="props.item.codigo" autocomplete item-text="codigo"-->
<!--                                                            item-value="codigo">-->
<!--                                                        </v-select>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-textarea v-model="props.item.descripcionPrestador" rows="1"-->
<!--                                                            cols="50">-->
<!--                                                        </v-textarea>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-flex sm12 xs12 md12>-->
<!--                                                            <v-text-field v-model="props.item.valorAPrestador"-->
<!--                                                                type="number"-->
<!--                                                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"-->
<!--                                                                min="1"></v-text-field>-->
<!--                                                        </v-flex>-->
<!--                                                    </td>-->
<!--                                                    <td class="text-xs-left">-->
<!--                                                        <v-flex sm12 xs12 md12>-->
<!--                                                            <v-text-field v-model="props.item.valorNPrestador"-->
<!--                                                                type="number"-->
<!--                                                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"-->
<!--                                                                min="1"></v-text-field>-->
<!--                                                        </v-flex>-->
<!--                                                    </td>-->
<!--                                                    <v-tooltip top>-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <input v-show="false" @change="onFilePicked" type="file"-->
<!--                                                                ref="adjunto">-->
<!--                                                            <v-btn v-if="props.item.archivo != null" fab color="primary"-->
<!--                                                                @click="addAdjunto(props.item)" :disabled="loading"-->
<!--                                                                small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                            <v-btn v-else fab color="warning"-->
<!--                                                                @click="addAdjunto(props.item)" :disabled="loading"-->
<!--                                                                small v-on="on">-->
<!--                                                                <v-icon>file_copy</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Adjuntar</span>-->
<!--                                                    </v-tooltip>-->
<!--                                                    <v-tooltip top v-if="props.item.estado_respuesta != null">-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="success" :disabled="loading" small-->
<!--                                                                v-on="on" @click="addglosa(props.item)">-->
<!--                                                                <v-icon>done_all</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Guardar</span>-->
<!--                                                    </v-tooltip>-->
<!--                                                    <v-tooltip top v-else>-->
<!--                                                        <template v-slot:activator="{ on }">-->
<!--                                                            <v-btn fab color="info" :disabled="loading" small v-on="on"-->
<!--                                                                @click="addglosa(props.item)">-->
<!--                                                                <v-icon>done</v-icon>-->
<!--                                                            </v-btn>-->
<!--                                                        </template>-->
<!--                                                        <span>Guardar</span>-->
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
                    <v-btn color="green" dark text @click="saveAuditoria(invoiceService)">
                        Guardar Respuesta
                    </v-btn>
                </v-card>
            </v-dialog>

            <v-dialog v-model="dialogGlosaC" v-if="dialogGlosaC" fullscreen hide-overlay
                transition="dialog-bottom-transition" scrollable>
                <v-card tile>
                    <v-toolbar card dark color="primary">
                        <v-btn icon dark @click="dialogGlosaC = false">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Respuesta</v-toolbar-title>
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
                                    <v-data-table :headers="headersfacturaC" :items="invoiceServiceC"
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


<!--                        <v-expansion-panel v-if="invoiceServiceC.AC">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-show="invoiceServiceC.AC.length > 0">-->
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
<!--                                            <v-data-table :headers="headersfacturaC" :items="invoiceServiceC.AC"-->
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
<!--                        <v-expansion-panel v-if="invoiceServiceC.AP">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-show="invoiceServiceC.AP.length > 0">-->
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
<!--                                            <v-data-table :headers="headersfacturaC" :items="invoiceServiceC.AP"-->
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
<!--                        <v-expansion-panel v-if="invoiceServiceC.AT">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-show="invoiceServiceC.AT.length > 0">-->
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
<!--                                            <v-data-table :headers="headersfacturaC" :items="invoiceServiceC.AT"-->
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
<!--                        <v-expansion-panel v-if="invoiceServiceC.AM">-->
<!--                            <v-expansion-panel-content class="primary text-xs-center"-->
<!--                                v-show="invoiceServiceC.AM.length > 0">-->
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
<!--                                            <v-data-table :headers="headersfacturaC" :items="invoiceServiceC.AM"-->
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

            <v-dialog v-model="dialogGlosaCerradas" v-if="dialogGlosaCerradas" fullscreen hide-overlay
                transition="dialog-bottom-transition" scrollable>
                <v-card tile>
                    <v-toolbar card dark color="primary">
                        <v-btn icon dark @click="dialogGlosaCerradas = false">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Respuesta</v-toolbar-title>
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
                                    <v-data-table :headers="headersfacturaC" :items="invoiceServiceCerradas"
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
<!--                            <v-expansion-panel-content class="primary text-xs-center" v-show="invoiceServiceCerradas.AC.length > 0">-->
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
<!--                                            <v-data-table :headers="headersfacturaC" :items="invoiceServiceCerradas.AC"-->
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
<!--                                v-show="invoiceServiceCerradas.AP.length > 0">-->
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
<!--                                            <v-data-table :headers="headersfacturaC" :items="invoiceServiceCerradas.AP"-->
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
<!--                                v-show="invoiceServiceCerradas.AT.length > 0">-->
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
<!--                                            <v-data-table :headers="headersfacturaC" :items="invoiceServiceCerradas.AT"-->
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
<!--                                v-show="invoiceServiceCerradas.AM.length > 0">-->
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
<!--                                            <v-data-table :headers="headersfacturaC" :items="invoiceServiceCerradas.AM"-->
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

        </v-layout>

    </div>
</template>

<script>
    import Swal from 'sweetalert2';
    export default {
        name: 'MedicalBillsPrestador',
        data: () => {
            return {
                search: '',
                searchAc: '',
                searchAp: '',
                searchAt: '',
                searchAm: '',
                headers: [{
                        text: 'Nit',
                        value: 'Nit',
                        sortable: false
                    },
                    {
                        text: 'Fecha radicación factura',
                        value: 'created_at'
                    },
                    {
                        text: '# Factura',
                        value: 'numero_factura',
                        sortable: false
                    },
                    {
                        text: 'Valor factura',
                        value: 'valor_Neto'
                    },
                    {
                        text: '',
                        value: ''
                    }
                ],
                headersfactura: [{
                        text: 'Codigo glosa',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Descripción glosa',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Valor glosa',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Codigo',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Descripción',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Valor aceptado',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Valor no aceptado',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: '',
                        sortable: false
                    },
                ],
                headersfacturaC: [{
                        text: 'Codigo glosa',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Descripción glosa',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Valor aceptado',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Valor no aceptado',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Descripción',
                        align: 'left',
                        sortable: false
                    },
                    {
                        text: 'Leventa sumimedical',
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
                        sortable: false
                    },
                ],
                allGlosas: [],
                loading: false,
                invoiceService: [],
                dialogGlosa: false,
                id_glosa: '',
                pendientes: false,
                conciliacion: false,
                cerradas: false,
                allGlosasConciliacion: [],
                search2: '',
                dialogGlosaC: false,
                invoiceServiceC: [],
                allGlosasCerradas: [],
                search3: '',
                dialogGlosaCerradas: false,
                invoiceServiceCerradas: []
            }
        },
        mounted() {
            this.myGlosas();
            this.myGlosasConciliacion();
            this.myGlosasCerradas();
        },
        computed: {
            codigos() {
                return this.codigosglosa.filter((cod) => {
                    if (cod.tipo_id == 33) {
                        return cod
                    }
                })
            },
        },
        methods: {
            fetchCodigoglosas() {
                axios.get('/api/codigoglosa/all')
                    .then(res => this.codigosglosa = res.data);
            },
            myGlosas() {
                this.$emit("updateCount");
                axios.get('/api/medicalBills/invoicePrestadores').then(res => {
                    this.allGlosas = res.data;
                }).catch(e => {
                    console.log(e)
                })
            },
            myGlosasConciliacion() {
                axios.get('/api/medicalBills/invoicePrestadoresConciliacion').then(res => {
                    this.allGlosasConciliacion = res.data;
                }).catch(e => {
                    console.log(e)
                })
            },
            myGlosasCerradas() {
                axios.get('/api/medicalBills/invoicePrestadoresCerradas').then(res => {
                    this.allGlosasCerradas = res.data;
                }).catch(e => {
                    console.log(e)
                })
            },
            glosasPrestador(afs) {
                this.clearSearch();
                this.fetchCodigoglosas();
                this.af = afs
                axios.get('/api/medicalBills/invoiceServiceprestador/' + this.af.id).then(res => {
                    this.invoiceService = res.data
                    this.dialogGlosa = true
                })
            },
            showGlosasPrestadorConciliacion(afs) {
                this.clearSearch();
                this.af = afs
                axios.get('/api/medicalBills/invoiceServiceConciliacion/' + this.af.id).then(res => {
                    this.invoiceServiceC = res.data
                    this.dialogGlosaC = true
                })
            },
            showGlosasPrestadorCerradas(afs) {
                this.clearSearch();
                this.af = afs
                this.dialogGlosaCerradas = true
                axios.get('/api/medicalBills/invoiceServiceCerradas/' + this.af.id).then(res => {
                    this.invoiceServiceCerradas = res.data
                })
            },
            addglosa(glosa) {
                if (!glosa.codigo || !glosa.descripcionPrestador || !glosa.valorAPrestador || !glosa.valorNPrestador) {
                    this.$alerError('No puede guardar una respuesta sin llenar los campos obligatorios!');
                    return
                }
                let totalglosa = parseInt(glosa.valorAPrestador) + parseInt(glosa.valorNPrestador);
                if (parseInt(totalglosa) != parseInt(glosa.valor)) {
                    this.$alerError(
                        'La suma del valor aceptado y no aceptado no puede ser diferente a el valor de glosa!');
                    return
                }
                this.loading = true
                axios.post('/api/medicalBills/respuestaPrestador', glosa).then(res => {
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
                    this.glosasPrestador(this.af)
                }).catch(e => {
                    this.loading = false
                    console.log(e)
                });
            },
            saveAuditoria(factura) {
                let vacio = false;
                for (let i = 0; i < factura.length; i++) {
                    const element = factura[i]
                    if (element.estado_respuesta == null) {
                        vacio = true;
                        break;
                    }
                }
                // if (vacio == false) {
                //     for (let i = 0; i < factura.AP.length; i++) {
                //         const element = factura.AP[i]
                //         if (element.estado_respuesta == null) {
                //             vacio = true;
                //             break;
                //         }
                //     }
                // }
                // if (vacio == false) {
                //     for (let i = 0; i < factura.AT.length; i++) {
                //         const element = factura.AT[i]
                //         if (element.estado_respuesta == null) {
                //             vacio = true;
                //             break;
                //         }
                //     }
                // }
                // if (vacio == false) {
                //     for (let i = 0; i < factura.AM.length; i++) {
                //         const element = factura.AM[i]
                //         if (element.estado_respuesta == null) {
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
                axios.post('/api/medicalBills/saveAuditoriaPrestador', {
                    af_id: this.af.id
                }).then(res => {
                    this.myGlosas();
                    this.myGlosasConciliacion();
                    this.myGlosasCerradas();
                    this.dialogGlosa = false
                    this.$alerSuccess('Respuesta guardada con exito!');
                }).catch(e => {
                    console.log(e)
                });
            },
            addAdjunto(glosa) {
                this.$refs.adjunto.value = ''
                this.id_glosa = glosa.id_glosas
                this.$refs.adjunto.click()
            },
            onFilePicked(e) {
                const file = e.target.files
                var ext = file[0].name.split('.').pop();
                ext = ext.toLowerCase();
                if (file[0].size > 7000000) {
                    this.$alerError('El adjunto no debe superar los 7MB, comprima el PDF y intente de nuevo');
                    this.$refs.adjunto.value = ''
                    return
                }
                if (ext != 'pdf') {
                    this.$alerError('El adjunto solo puede ser formato PDF');
                    this.$refs.adjunto.value = ''
                    return
                }
                if (file[0] != undefined) {
                    let formData = new FormData();
                    formData.append('adjunto', file[0]);
                    formData.append('glosa_id', this.id_glosa)
                    axios.post('/api/medicalBills/adjunto', formData).then((res) => {
                        this.glosasPrestador(this.af)
                        this.$alerSuccess('Adjunto cargado con exito!');
                    }).catch((error) => {
                        this.$alerError('Error al cargar el adjunto, intente de nuevo');
                    });
                }
            },
            clearSearch() {
                this.search = ''
                this.searchAc = ''
                this.searchAp = ''
                this.searchAt = ''
                this.searchAm = ''
                this.search2 = ''
                this.search3 = ''
            },
            showAdjunto(ruta) {
                window.open(ruta, "_blank");
            },
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
