<template>
  <v-card>
    <v-container fluid grid-list-xl>
      <v-container pt-3 pb-1>
        <v-layout row>
          <div class="text-center">
            <v-dialog v-model="preload" persistent width="800">
              <v-card color="primary" dark>
                <v-card-text>
                  Tranquilo procesamos tu solicitud !
                  <v-progress-linear
                    indeterminate
                    color="white"
                    class="mb-0"
                  ></v-progress-linear>
                </v-card-text>
              </v-card>
            </v-dialog>
          </div>

          <v-dialog v-model="dialog" persistent max-width="90%">
            <v-card>
              <v-card-title class="headline success" style="color: white">
                <span class="headline">Lista Medicamentos a Entregar</span>
              </v-card-title>
              <v-card-text>
                <v-flex>
                  <v-card-text>
                    <v-checkbox
                      color="warning"
                      v-model="domicilio"
                      label="Enviar al Domicilio"
                    >
                    </v-checkbox>
                    <v-text-field
                      v-show="domicilio"
                      v-model="namedomiciliario"
                      label="* Ingresa el Nombre del Domiciliario"
                    >
                    </v-text-field>
                  </v-card-text>
                </v-flex>
                <v-spacer></v-spacer>
                <!-- <v-text-field type="datetime-local" :value="Fechadispensacion" v-model="Fechadispensacion"></v-text-field> -->
                <v-data-table
                  :loading="loading"
                  :headers="headersDetalle"
                  :items="detalleOrdenPaciente"
                  rowsPerPageText="Filas por página"
                  v-model="selected"
                  :select-all="dispensar"
                >
                  <template v-slot:headers>
                    <tr>
                      <th></th>
                      <th>Código</th>
                      <th>Nombre:</th>
                      <th>Entregar</th>
                      <th>Lote</th>
                    </tr>
                  </template>
                  <template v-slot:items="props">
                    <tr>
                      <td
                        :bgcolor="
                          !props.item.color ? 'lightblue' : props.item.color
                        "
                      >
                        <v-checkbox
                          color="primary"
                          :input-value="props.selected"
                          v-model="selected"
                          :value="props.item"
                          multiple
                          hide-details
                          :disabled="
                            (!dispensar && !can('orden.dispenseNext')) ||
                            !can('orden.dispense')
                          "
                        >
                        </v-checkbox>
                      </td>
                      <td
                        :bgcolor="
                          !props.item.color ? 'lightblue' : props.item.color
                        "
                        class="text-xs-center"
                      >
                        {{ props.item.Codigo }}
                      </td>
                      <td
                        :bgcolor="
                          !props.item.color ? 'lightblue' : props.item.color
                        "
                        class="text-xs-center"
                      >
                        {{ props.item.medicamento
                        }}{{
                          props.item.tipoOrden === 7
                            ? " (" +
                              props.item.concentracion +
                              " " +
                              props.item.unidadMedida +
                              ")"
                            : ""
                        }}
                      </td>
                      <td
                        :bgcolor="
                          !props.item.color ? 'lightblue' : props.item.color
                        "
                        class="text-xs-center"
                      >
                        {{ props.item.Cantidadmensualdisponible }}
                      </td>
                      <td
                        :bgcolor="
                          !props.item.color ? 'lightblue' : props.item.color
                        "
                        class="text-xs-center"
                      >
                        <v-btn
                          v-if="props.selected"
                          color="error"
                          round
                          dark
                          v-on:click="estadoPendiente(props.item.id)"
                          >Pendiente</v-btn
                        >
                        <v-btn
                          v-if="props.selected"
                          color="warning"
                          round
                          dark
                          v-on:click="findLote(props.item)"
                          >Lotes</v-btn
                        >
                      </td>
                      <td
                        :bgcolor="
                          !props.item.color ? 'lightblue' : props.item.color
                        "
                        class="text-xs-center"
                      >
                        {{ props.item.lotes | lotesFilter }}
                      </td>
                      <!-- <td class="text-xs-center">
                                                                        <v-btn color="warning" round dark v-on:click="guardarMedicamentoEntregado(props.item.id)">Dispensar</v-btn>
                                            </td>-->
                      <td
                        :bgcolor="
                          !props.item.color ? 'lightblue' : props.item.color
                        "
                        v-if="props.item.cobro"
                      >
                        <v-icon large color="yellow darken-2"
                          >attach_money</v-icon
                        >
                      </td>
                      <td
                        :bgcolor="
                          !props.item.color ? 'lightblue' : props.item.color
                        "
                        v-else-if="props.item.mipres"
                      >
                        <v-chip
                          color="primary"
                          v-show="props.item.mipres"
                          text-color="white"
                        >
                          MIPRES</v-chip
                        >
                      </td>
                      <td
                        :bgcolor="
                          !props.item.color ? 'lightblue' : props.item.color
                        "
                      >
                        <v-icon
                          v-if="props.item.colorMed == 'LASA - AMARILLO'"
                          large
                          color="yellow"
                          >mdi-pill</v-icon
                        >
                        <v-icon
                          v-else-if="props.item.colorMed == 'LASA - VERDE'"
                          large
                          color="green"
                          >mdi-pill</v-icon
                        >
                        <v-icon
                          v-else-if="props.item.colorMed == 'MAXIMA ALERTA'"
                          large
                          color="red"
                          >mdi-pill</v-icon
                        >
                        <v-icon
                          v-else-if="props.item.colorMed == 'LASA-AZUL'"
                          large
                          color="blue"
                          >mdi-pill</v-icon
                        >
                      </td>
                    </tr>
                  </template>
                  mdi-pill
                  <v-alert
                    v-slot:no-results
                    :value="true"
                    color="error"
                    icon="warning"
                  >
                    Your search for "{{ search }}" found no results.
                  </v-alert>
                </v-data-table>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="error" @click="terminarDetalleOrden()"
                  >Cancelar</v-btn
                >
                <v-btn
                  color="success"
                  v-show="selected.length > 0 && can('orden.dispense')"
                  :loading="loadingDispensar"
                  :disabled="loadingDispensar"
                  @click="dispensarMedicamento()"
                  >Dispensar</v-btn
                >
              </v-card-actions>
            </v-card>
          </v-dialog>

          <v-dialog v-model="dialogProximo" persistent max-width="90%">
            <v-card>
              <v-card-title class="headline success" style="color: white">
                <span class="headline">Lista Medicamentos a Entregar</span>
              </v-card-title>
              <v-card-text>
                <v-spacer></v-spacer>
                <!-- <v-text-field type="datetime-local" :value="Fechadispensacion" v-model="Fechadispensacion"></v-text-field> -->
                <v-data-table
                  :loading="loading"
                  :headers="headersDetalle"
                  :items="detalleOrdenPaciente"
                  rowsPerPageText="Filas por página"
                  v-model="selected"
                  :select-all="dispensar"
                >
                  <template v-slot:headers>
                    <tr>
                      <th>Código</th>
                      <th>Nombre:</th>
                      <!-- <th>Disponibles</th> -->
                      <th>Entregar</th>
                    </tr>
                  </template>
                  <template v-slot:items="props">
                    <tr>
                      <td class="text-xs-center">{{ props.item.Codigo }}</td>
                      <td class="text-xs-center">
                        {{ props.item.medicamento
                        }}{{
                          props.item.tipoOrden === 7
                            ? " (" +
                              props.item.concentracion +
                              " " +
                              props.item.unidadMedida +
                              ")"
                            : ""
                        }}
                      </td>
                      <td class="text-xs-center">
                        {{ props.item.Cantidadmensualdisponible }}
                      </td>
                      <td class="text-xs-center">{{ props.item.color }}</td>
                    </tr>
                  </template>
                  <v-alert
                    v-slot:no-results
                    :value="true"
                    color="error"
                    icon="warning"
                  >
                    Your search for "{{ search }}" found no results.
                  </v-alert>
                </v-data-table>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="error" @click="terminarDetalleOrden()"
                  >Cancelar</v-btn
                >
              </v-card-actions>
            </v-card>
          </v-dialog>

          <v-dialog v-model="lote" max-width="80%" persistent>
            <v-card>
              <v-card-title>
                <v-flex xs6>
                  <span class="headline">Lista Lotes</span>
                </v-flex>
                <v-spacer></v-spacer>
                <v-flex xs2>
                  <span>
                    <b>Cantidad orden : </b>{{ this.medicamento.pEntregar
                    }}{{
                      this.medicamento.tipoOrden === 7
                        ? " " + this.medicamento.unidadMedida
                        : ""
                    }}
                  </span>
                </v-flex>
                <v-flex xs2>
                  <span>
                    <b>Cantidad a entregar : </b
                    >{{ this.medicamento.cantidadEntrega
                    }}{{
                      this.medicamento.tipoOrden === 7
                        ? " " + this.medicamento.unidadMedida
                        : ""
                    }}
                  </span>
                </v-flex>
                <v-flex xs2>
                  <span>
                    <b>Pendiente : </b>{{ this.medicamento.pendiente
                    }}{{
                      this.medicamento.tipoOrden === 7
                        ? " " + this.medicamento.unidadMedida
                        : ""
                    }}
                  </span>
                </v-flex>
              </v-card-title>
              <v-card-text>
                <v-data-table
                  :headers="headersDetalle"
                  :items="listaLote"
                  rowsPerPageText="Filas por página"
                  v-model="loteSelected"
                  :select-all="dispensar"
                >
                  <template v-slot:headers>
                    <tr>
                      <th></th>
                      <th>Lote</th>
                      <th>F.Vence</th>
                      <th>CUM</th>
                      <th>Titular</th>
                      <th>Disponible (Unidades)</th>
                      <th>Aprovechamiento</th>
                      <th>P.Entregar</th>
                      <th width="25%">Cantidad</th>
                    </tr>
                  </template>
                  <template v-slot:items="props">
                    <tr>
                      <td>
                        <v-checkbox
                          color="primary"
                          :input-value="props.selected"
                          v-model="loteSelected"
                          :value="props.item"
                          multiple
                          hide-details
                          :disabled="!dispensar && !can('orden.dispenseNext')"
                        ></v-checkbox>
                      </td>
                      <td class="text-xs-center">{{ props.item.Numlote }}</td>
                      <td class="text-xs-center">{{ props.item.Fvence }}</td>
                      <td class="text-xs-center">
                        {{ props.item.CUM_completo }}
                      </td>
                      <td class="text-xs-center">{{ props.item.Titular }}</td>
                      <td class="text-xs-center">
                        {{ props.item.Cantidadisponible }}
                        {{
                          props.item.tipoOrden === 7
                            ? "(" +
                              parseInt(props.item.Cantidadisponible) *
                                parseInt(props.item.concentracion) +
                              " " +
                              props.item.unidadMedida +
                              ")"
                            : ""
                        }}
                      </td>
                      <td
                        v-if="props.item.tipoOrden === 7"
                        class="text-xs-center"
                      >
                        {{ props.item.Aprovechamiento }}
                        {{ props.item.unidadMedida }}
                      </td>
                      <td v-else class="text-xs-center">0</td>
                      <td class="text-xs-center">{{ props.item.pEntregar }}</td>
                      <td class="text-xs-center">
                        <v-layout row wrap justify-center>
                          <v-flex xs7>
                            <v-text-field
                              v-if="props.selected"
                              :name="`cantidadEntregada${props.item.id}`"
                              type="number"
                              @focus="focusInput(props.item)"
                              @input="pendiente($event, props.item)"
                              v-model="props.item.cantidad"
                            ></v-text-field>
                          </v-flex>
                          {{ props.item.unidadMedida }}
                        </v-layout>
                      </td>
                    </tr>
                  </template>
                </v-data-table>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn flat color="blue" @click="lote = false">Cancelar</v-btn>
                <v-btn
                  flat
                  color="blue"
                  v-show="loteSelected.length > 0"
                  @click="seleccionarLotes()"
                >
                  Seleccionar</v-btn
                >
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-layout>
      </v-container>
      <v-card-title>
        <v-flex xs12>
          <v-layout row wrap>
            <v-flex 12>
              <v-autocomplete
                :items="listaBodegas"
                v-model="bodega_id"
                label="Farmacia"
                item-value="id"
                item-text="Nombre"
              ></v-autocomplete>
            </v-flex>
          </v-layout>
        </v-flex>
        <v-flex xs12>
          <v-layout row wrap>
            <v-flex xs5>
              <v-form @submit.prevent="search_paciente()">
                <v-layout row wrap>
                  <v-flex xs10>
                    <v-text-field
                      v-model="cedula_paciente"
                      label="Número de Documento"
                    >
                    </v-text-field>
                  </v-flex>
                  <v-flex xs2>
                    <v-btn
                      @click="search_paciente()"
                      @keyup.enter="search_paciente()"
                      v-if="!paciente.id"
                      fab
                      outline
                      small
                      color="success"
                    >
                      <v-icon>search</v-icon>
                    </v-btn>
                    <v-btn
                      @click="clearFields()"
                      v-if="paciente.id"
                      fab
                      outline
                      small
                      color="error"
                    >
                      <v-icon>clear</v-icon>
                    </v-btn>
                  </v-flex>
                </v-layout>
              </v-form>
            </v-flex>
            <v-spacer></v-spacer>
            <v-flex xs7>
              <v-layout row wrap>
                <v-flex xs4>
                  <v-menu
                    ref="menu"
                    v-model="menu"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    :return-value.sync="startDate"
                    lazy
                    transition="scale-transition"
                    offset-y
                    full-width
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        v-model="startDate"
                        label="Fecha inicio"
                        prepend-icon="event"
                        readonly
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      v-model="startDate"
                      color="primary"
                      locale="es"
                      no-title
                      scrollable
                    >
                      <v-spacer></v-spacer>
                      <v-btn flat color="primary" @click="menu = false"
                        >Cancelar</v-btn
                      >
                      <v-btn
                        flat
                        color="primary"
                        @click="$refs.menu.save(startDate)"
                        >OK</v-btn
                      >
                    </v-date-picker>
                  </v-menu>
                </v-flex>
                <v-flex xs4 ml-3>
                  <v-menu
                    ref="menu1"
                    v-model="menu1"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    :return-value.sync="finishDate"
                    lazy
                    transition="scale-transition"
                    offset-y
                    full-width
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        v-model="finishDate"
                        label="Fecha final"
                        prepend-icon="event"
                        readonly
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      v-model="finishDate"
                      color="primary"
                      locale="es"
                      no-title
                      scrollable
                    >
                      <v-spacer></v-spacer>
                      <v-btn flat color="primary" @click="menu1 = false"
                        >Cancelar</v-btn
                      >
                      <v-btn
                        flat
                        color="primary"
                        @click="$refs.menu1.save(finishDate)"
                        >OK</v-btn
                      >
                    </v-date-picker>
                  </v-menu>
                </v-flex>
                <v-btn
                  v-if="can('bodegaPendiente.export')"
                  round
                  @click="exportPending()"
                  color="warning"
                  dark
                  inabled
                >
                  <v-icon left>cloud_download</v-icon>Exportar pendientes
                </v-btn>
              </v-layout>
            </v-flex>
          </v-layout>
        </v-flex>
        <v-form v-model="valid">
          <v-container fluid grid-list-xl>
            <v-layout row wrap v-show="paciente.id" justify-space-between>
              <v-flex xs12 md4>
                <b>Entidad: </b>
                <b style="color: red">{{ paciente.Ut }}</b>
              </v-flex>

              <v-flex xs12 md4>
                <b>Tipo de Categoría: </b>
                <b style="color: blue">{{ paciente.tipo_categoria }}</b>
              </v-flex>

              <v-flex xs12 md4>
                <b>Nombre completo:</b>
                {{
                  ` ${
                    paciente.Primer_Nom
                      ? `${paciente.Primer_Nom.charAt(
                          0
                        ).toUpperCase()}${paciente.Primer_Nom.slice(1)}`
                      : ""
                  } ${
                    paciente.SegundoNom
                      ? `${paciente.SegundoNom.charAt(
                          0
                        ).toUpperCase()}${paciente.SegundoNom.slice(1)}`
                      : ""
                  } ${
                    paciente.Primer_Ape
                      ? `${paciente.Primer_Ape.charAt(
                          0
                        ).toUpperCase()}${paciente.Primer_Ape.slice(1)}`
                      : ""
                  } ${
                    paciente.Segundo_Ape
                      ? `${paciente.Segundo_Ape.charAt(
                          0
                        ).toUpperCase()}${paciente.Segundo_Ape.slice(1)}`
                      : ""
                  } `
                }}
              </v-flex>

              <v-flex xs12 md4>
                <b>Tipo y número de documento: </b>
                {{ paciente.Tipo_Doc.toUpperCase() + " " + paciente.Num_Doc }}
              </v-flex>

              <v-flex xs12 md4>
                <b>Sede atención:</b>
                {{ paciente.NombreIPS }}
              </v-flex>

              <v-flex xs12 md4>
                <b>Edad:</b>
                {{ paciente.Edad_Cumplida }}
              </v-flex>

              <v-flex xs12 md4>
                <b>Dirección:</b>
                {{ paciente.Direccion_Residencia }}
              </v-flex>

              <v-flex xs12 md4>
                <b>Telefono:</b>
                {{ paciente.Telefono }}
              </v-flex>

              <v-flex xs12 md4>
                <b>Tutela:</b>
                <b v-if="paciente.Tienetutela" style="color: red">Activa</b>
                <span v-else>No aplica</span>
              </v-flex>
                <v-flex xs12>
              <v-chip
                color="red"
                text-color="white"
                v-if="paciente.Tienetutela == 1"
                readonly
              >
                TIENE TUTELA
              </v-chip>
              <v-chip
                color="green"
                text-color="white"
                v-if="paciente.Tienetutela == 0 || paciente.Tienetutela == null"
                readonly
              >
                NO TIENE TUTELA
              </v-chip>
              <v-chip
                color="green"
                text-color="white"
                v-if="
                  paciente.victima_conficto_armado == false ||
                  paciente.victima_conficto_armado == null
                "
                readonly
              >
                CÓDIGO BLANCO: No
              </v-chip>
              <v-chip
                color="blue"
                dark
                v-if="paciente.victima_conficto_armado == true"
                readonly
              >
                CÓDIGO BLANCO: Si
              </v-chip>
              <v-chip
                color="success"
                dark
                v-if="
                  paciente.portabilidad == false ||
                  paciente.portabilidad == null
                "
                readonly
              >
                TIENE PORTABILIAD: No
              </v-chip>
              <v-chip
                color="blue"
                dark
                v-if="paciente.portabilidad == true"
                readonly
              >
                TIENE PORTABILIDAD: Si
              </v-chip>
              <v-chip
                color="red"
                text-color="white"
                v-if="paciente.domiciliaria == true"
                readonly
              >
                PACIENTE DOMICILIARIO: Si
              </v-chip>
              <v-chip
                color="success"
                text-color="white"
                v-if="
                  paciente.domiciliaria == false ||
                  paciente.domiciliaria == null
                "
                readonly
              >
                PACIENTE DOMICILIARIO: No
              </v-chip>
              <v-chip
                color="success"
                text-color="white"
                v-if="paciente.ppp == false || paciente.ppp == null"
                readonly
              >
                PUNTA PIRAMIDE: No
              </v-chip>
              <v-chip
                color="red"
                text-color="white"
                v-if="paciente.ppp == true"
                readonly
              >
                PUNTA PIRAMIDE: Si
              </v-chip>
              <v-chip
                color="success"
                text-color="white"
                v-if="paciente.abrazarte == false || paciente.abrazarte == null"
                readonly
              >
                ABRAZARTE : No
              </v-chip>
              <v-chip
                color="red"
                text-color="white"
                v-if="paciente.abrazarte == true"
                readonly
              >
                ABRAZARTE : Si
              </v-chip>
              <v-chip
                color="success"
                text-color="white"
                v-if="
                  paciente.latir_sentido == false ||
                  paciente.latir_sentido == null
                "
                readonly
              >
                LATIR CON SENTIDO: No
              </v-chip>
              <v-chip
                color="red"
                text-color="white"
                v-if="paciente.latir_sentido == true"
                readonly
              >
                LATIR CON SENTIDO: Si
              </v-chip>
            </v-flex>
            </v-layout>
          </v-container>
        </v-form>
      </v-card-title>
      <template>
        <v-dialog v-model="dialogfirmama" persistent max-width="600px"
          >holi</v-dialog
        >
        <div>
          <v-tabs
            v-model="active"
            color="primary"
            dark
            slider-color="secondary"
          >
            <v-tab @click="getOrdenesMedicamentosPendientes()" ripple>
              Activo
            </v-tab>
            <v-tab
              v-if="can('dispensacionPendientes.view')"
              @click="getOrdenesMedicamentosPendientes('pendiente')"
              ripple
            >
              Pendiente
            </v-tab>
            <v-tab @click="getOrdenesMedicamentosHistorial()" ripple>
              Dispensado
            </v-tab>
            <v-tab @click="getOrdenesMedicamentosProximos()" ripple>
              Próximo
            </v-tab>
            <v-tab
              v-if="can('dispensacionOncologica.view')"
              @click="getOrdenesMedicamentosPendientesOncologicos()"
              ripple
            >
              Activo (Oncología)
            </v-tab>
            <v-tab-item>
              <v-card flat>
                <v-card-text>
                  <v-layout row wrap>
                    <v-flex xs12>
                      <v-data-table
                        :headers="headers"
                        :items="ordenPaciente"
                        :loading="loading"
                        rowsPerPageText="Filas por página"
                        :rows-per-page-items="[10]"
                      >
                        <template v-slot:items="props">
                          <tr>
                            <td class="text-xs-center">{{ props.item.id }}</td>
                            <!-- <td class="text-xs-center">{{ props.item.created_at | date }}</td> -->
                            <td class="text-xs-center">
                              {{ props.item.Fechaorden | date }}
                            </td>
                            <td class="text-xs-center">
                              {{ props.item.paginacion }}
                            </td>
                            <td class="text-xs-center">
                              <v-chip
                                v-if="props.item.Estado_id == 1"
                                color="primary"
                                text-color="white"
                                >Disponible</v-chip
                              >
                              <v-chip
                                v-if="props.item.Estado_id == 7"
                                color="primary"
                                text-color="white"
                                >Disponible</v-chip
                              >
                              <v-chip
                                v-else-if="props.item.Estado_id == 17"
                                color="green"
                                text-color="white"
                                >Dispensado</v-chip
                              >
                              <v-chip
                                v-else-if="props.item.Estado_id == 18"
                                color="red"
                                text-color="white"
                                >Pendiente</v-chip
                              >
                            </td>
                            <td class="text-xs-right">
                              <v-btn
                                outline
                                color="warning"
                                round
                                small
                                @click="
                                  verDetalleOrdenamient(props.item, 'libre')
                                "
                                >Ver Detalle
                              </v-btn>
                            </td>
                            <td class="text-xs-right">
                              <v-btn
                                outline
                                color="danger"
                                round
                                small
                                @click="Print(props.item, 1, 'formula')"
                                >Imprimir
                              </v-btn>
                            </td>
                            <td
                              class="text-xs-right"
                              v-show="parseInt(props.item.valorOrden) > 0"
                            >
                              <v-chip label color="success" text-color="white"
                                >$ {{ props.item.valorOrden }}</v-chip
                              >
                            </td>
                          </tr>
                        </template>
                      </v-data-table>
                    </v-flex>
                  </v-layout>
                </v-card-text>
              </v-card>
            </v-tab-item>
            <v-tab-item v-if="can('dispensacionPendientes.view')">
              <v-card flat>
                <v-card-text>
                  <v-layout row wrap>
                    <v-flex xs12>
                      <v-data-table
                        :headers="headers"
                        :items="ordenPaciente"
                        :loading="loading"
                        rowsPerPageText="Filas por página"
                        :rows-per-page-items="[10]"
                      >
                        <template v-slot:items="props">
                          <tr>
                            <td class="text-xs-center">{{ props.item.id }}</td>
                            <td class="text-xs-center">
                              {{ props.item.Fechaorden | date }}
                            </td>
                            <td class="text-xs-center">
                              {{ props.item.paginacion }}
                            </td>
                            <td class="text-xs-center">
                              <v-chip color="red" text-color="white"
                                >Pendiente</v-chip
                              >
                            </td>
                            <td class="text-xs-right">
                              <v-btn
                                outline
                                color="warning"
                                round
                                small
                                @click="
                                  verDetalleOrdenamient(props.item, 'pendiente')
                                "
                              >
                                Ver Detalle
                              </v-btn>
                            </td>
                            <td class="text-xs-right">
                              <v-btn
                                outline
                                color="danger"
                                round
                                small
                                @click="Print(props.item, 1, 'pendiente')"
                                >Imprimir pendiente
                              </v-btn>
                            </td>
                          </tr>
                        </template>
                      </v-data-table>
                    </v-flex>
                  </v-layout>
                </v-card-text>
              </v-card>
            </v-tab-item>
            <v-tab-item>
              <v-card flat>
                <v-card-title primary-title>
                  <v-flex xs2>
                    <v-menu
                      ref="inicialdate"
                      v-model="menu2"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      :return-value.sync="inicialdate"
                      lazy
                      transition="scale-transition"
                      offset-y
                      full-width
                      min-width="290px"
                    >
                      <template v-slot:activator="{ on }">
                        <v-text-field
                          v-model="inicialdate"
                          label="Fecha inicial"
                          prepend-icon="event"
                          readonly
                          v-on="on"
                        ></v-text-field>
                      </template>
                      <v-date-picker
                        v-model="inicialdate"
                        color="primary"
                        locale="es"
                        no-title
                        scrollable
                      >
                        <v-spacer></v-spacer>
                        <v-btn flat color="primary" @click="menu2 = false"
                          >Cancelar</v-btn
                        >
                        <v-btn flat color="primary" @click="saveInicialDate()"
                          >OK</v-btn
                        >
                      </v-date-picker>
                    </v-menu>
                  </v-flex>
                  <v-flex xs2>
                    <v-menu
                      ref="finishdate"
                      v-model="menu3"
                      :close-on-content-click="false"
                      :nudge-right="40"
                      :return-value.sync="finishdate"
                      lazy
                      transition="scale-transition"
                      offset-y
                      full-width
                      min-width="290px"
                    >
                      <template v-slot:activator="{ on }">
                        <v-text-field
                          v-model="finishdate"
                          label="Fecha final"
                          prepend-icon="event"
                          readonly
                          v-on="on"
                        />
                      </template>
                      <v-date-picker
                        v-model="finishdate"
                        color="primary"
                        locale="es"
                        no-title
                        scrollable
                      >
                        <v-spacer></v-spacer>
                        <v-btn flat color="primary" @click="menu3 = false"
                          >Cancelar</v-btn
                        >
                        <v-btn flat color="primary" @click="saveFinishDate()"
                          >OK</v-btn
                        >
                      </v-date-picker>
                    </v-menu>
                  </v-flex>
                  <v-spacer></v-spacer>
                  <v-flex xs6>
                    <v-text-field
                      v-model="searchDispensado"
                      label="Buscar..."
                      id="id"
                    >
                    </v-text-field>
                  </v-flex>
                </v-card-title>
                <v-card-text>
                  <v-layout row wrap>
                    <v-flex xs12>
                      <v-data-table
                        :headers="headersDispensado"
                        :items="medicamentosPacienteDispensado"
                        :loading="loading"
                        rowsPerPageText="Filas por página"
                        :rows-per-page-items="[10]"
                        :search="searchDispensado"
                      >
                        <template v-slot:items="props">
                          <tr>
                            <td class="text-xs-center">{{ props.item.id }}</td>
                            <td class="text-xs-center">
                              {{ props.item.Producto }}
                            </td>
                            <td class="text-xs-center">
                              {{ props.item.CantidadtotalFactura }}
                            </td>
                            <td class="text-xs-center">
                              {{ props.item.created_at | date }}
                            </td>
                            <td class="text-xs-center">
                              {{ props.item.bodega }}
                            </td>
                          </tr>
                        </template>
                      </v-data-table>
                    </v-flex>
                  </v-layout>
                </v-card-text>
              </v-card>
            </v-tab-item>
            <v-tab-item>
              <v-card flat>
                <v-card-text>
                  <v-layout row wrap>
                    <v-flex xs12>
                      <v-data-table
                        :headers="headersProximas"
                        :items="ordenPacienteProximas"
                        :loading="loading"
                        rowsPerPageText="Filas por página"
                        :rows-per-page-items="[10]"
                      >
                        <template v-slot:items="props">
                          <tr>
                            <td class="text-xs-center">{{ props.item.id }}</td>
                            <!-- <td class="text-xs-center">{{ props.item.created_at | date }}</td> -->
                            <td
                              v-if="can('medicamentos.cambiarfecha.proximos')"
                              class="text-xs-center"
                            >
                              <v-edit-dialog
                                :return-value.sync="props.item.Fechaorden"
                                large
                                lazy
                                persistent
                                @save="CambiarFechaOrden(props.item)"
                                save-text="Cambiar"
                              >
                                <div>{{ props.item.Fechaorden | date }}</div>
                                <template v-slot:input>
                                  <div class="mt-3 title">Update Iron</div>
                                </template>
                                <template v-slot:input>
                                  <v-text-field
                                    type="date"
                                    v-model="props.item.Fechaorden"
                                    single-line
                                    autofocus
                                  ></v-text-field>
                                </template>
                              </v-edit-dialog>
                            </td>
                            <td v-else>
                              {{ props.item.Fechaorden | date }}
                            </td>
                            <td class="text-xs-center">
                              {{ props.item.paginacion }}
                            </td>
                            <td class="text-xs-center">
                              {{ props.item.Estado_id | state }}
                            </td>
                            <td
                              v-if="can('medicamentos.dispensar.proximos')"
                              class="text-xs-right"
                            >
                              <v-btn
                                outline
                                color="warning"
                                round
                                small
                                @click="verDetalleOrdenamient(props.item)"
                                >Ver Detalle
                              </v-btn>
                            </td>
                            <td v-else class="text-xs-right">
                              <v-btn
                                outline
                                color="warning"
                                round
                                small
                                @click="
                                  verDetalleOrdenamientProximo(props.item)
                                "
                                >Ver Detalle
                              </v-btn>
                            </td>

                            <td class="text-xs-right">
                              <v-btn
                                outline
                                color="danger"
                                round
                                small
                                @click="Print(props.item, 1, 'formula')"
                                >Imprimir
                              </v-btn>
                            </td>
                          </tr>
                        </template>
                      </v-data-table>
                    </v-flex>
                  </v-layout>
                </v-card-text>
              </v-card>
            </v-tab-item>
            <v-tab-item>
              <v-card flat>
                <v-card-text>
                  <v-layout row wrap>
                    <v-flex xs12>
                      <v-data-table
                        :headers="headersOncologia"
                        :items="ordenPacienteOncologia"
                        :loading="loading"
                        rowsPerPageText="Filas por página"
                        :rows-per-page-items="[10]"
                      >
                        <template v-slot:items="props">
                          <tr>
                            <td class="text-xs-center">{{ props.item.id }}</td>
                            <!-- <td class="text-xs-center">{{ props.item.created_at | date }}</td> -->
                            <td class="text-xs-center">
                              {{ props.item.Fechaorden | date }}
                            </td>
                            <td class="text-xs-center">
                              {{ props.item.paginacion }}
                            </td>
                            <td class="text-xs-center">{{ props.item.day }}</td>
                            <td class="text-xs-center">
                              <v-btn
                                outline
                                color="warning"
                                round
                                small
                                @click="verDetalleOrdenamient(props.item)"
                                >Ver Detalle
                              </v-btn>
                            </td>

                            <td class="text-xs-right">
                              <v-btn
                                outline
                                color="danger"
                                round
                                small
                                v-if="props.item.Estado_id == 18"
                                @click="Print(props.item, 1)"
                                >Imprimir parcialmente
                              </v-btn>
                            </td>
                          </tr>
                        </template>
                      </v-data-table>
                    </v-flex>
                  </v-layout>
                </v-card-text>
              </v-card>
            </v-tab-item>
          </v-tabs>
        </div>
      </template>
    </v-container>
  </v-card>
</template>

<script>
import { mapGetters } from "vuex";
import moment from "moment";
moment.locale("es");

import { parse } from "path";
import swal from "sweetalert";

export default {
  name: "EntregaMedicamentos",
  data() {
    return {
      loadingDispensar: false,
      domicilio: false,
      dialogProximo: false,
      namedomiciliario: "",
      startDate: moment().format("YYYY-MM-DD"),
      finishDate: moment().format("YYYY-MM-DD"),
      dialogfirmama: false,
      menu: false,
      menu1: false,
      menu2: false,
      menu3: false,
      inicialdate: moment().format("YYYY-MM-DD"),
      finishdate: moment().format("YYYY-MM-DD"),
      dispensando: false,
      searchDispensado: "",
      active: 0,
      dispensar: true,
      search: "",
      preload: false,
      show_date: false,
      show_time: false,
      Fechadispensacion: moment().format("YYYY-MM-DD"),
      Horadispensacion: moment().format("HH:mm"),
      headers: [
        {
          text: "Número Orden",
          align: "center",
          value: "Principio_Activo",
        },
        {
          text: "Fecha",
          align: "center",
          value: "Titular",
        },
        {
          text: "Página",
          align: "center",
          value: "",
        },
        {
          text: "Estado",
          align: "center",
          value: "",
        },
        {
          text: "Detalle",
          align: "center",
          value: "",
        },
        {
          text: "",
          align: "center",
          value: "",
        },
      ],
      headersProximas: [
        {
          text: "Número Orden",
          align: "center",
          value: "Principio_Activo",
        },
        {
          text: "Fecha",
          align: "center",
          value: "Titular",
        },
        {
          text: "Página",
          align: "center",
          value: "",
        },
        {
          text: "Estado",
          align: "center",
          value: "",
        },
        {
          text: "Detalle",
          align: "center",
          value: "",
        },
        {
          text: "",
          align: "center",
          value: "",
        },
      ],
      headersDispensado: [
        {
          text: "Número Orden",
          align: "center",
          value: "id",
        },
        {
          text: "Producto",
          align: "center",
          value: "Producto",
        },
        {
          text: "Cantidad",
          align: "center",
          value: "CantidadtotalFactura",
        },
        {
          text: "fecha dispensado",
          align: "center",
          value: "created_at",
        },
        {
          text: "bodega",
          align: "center",
          value: "bodega",
        },
      ],
      headersDetalle: [
        {
          text: "id",
          align: "left",
          value: "id",
        },
        {
          text: "Nombre",
          align: "left",
          value: "Principio_Activo",
        },
        {
          text: "Cantidad",
          align: "left",
          value: "Titular",
        },
        {
          text: "Cantidad Entregada",
          align: "left",
          value: "Titular",
        },
      ],
      headersOncologia: [
        {
          text: "Número Orden",
          align: "center",
          value: "Principio_Activo",
        },
        {
          text: "Fecha",
          align: "center",
          value: "Titular",
        },
        {
          text: "Ciclo",
          align: "center",
          value: "",
        },
        {
          text: "Dia",
          align: "center",
          value: "",
        },
        {
          text: "Detalle",
          align: "center",
          value: "",
        },
      ],
      dialog: false,
      lote: false,
      cedula_paciente: "",
      bodega_id: 0,
      paciente: {
        Primer_Nom: "",
        Primer_Ape: "",
        Tipo_Doc: "",
        Num_Doc: "",
        Edad_Cumplida: "",
      },
      getlote: {
        Codesumi_id: "",
        Bodega_id: "",
      },
      ordenPaciente: [],
      detalleOrdenPaciente: [],
      ordenPacienteOncologia: [],
      selected: [],
      loteSelected: [],
      listaBodegas: [],
      medicamentos: [],
      listaLote: [],
      medicamento: {
        cantidadEntrega: 0,
        pendiente: 0,
        pEntregar: 0,
        concentracion: null,
        unidadMedida: null,
        cantidadEntregaUM: 0,
        pendienteUM: 0,
        pEntregarUM: 0,
      },
      ordenPacienteProximas: [],
      medicamentosPacienteDispensado: [],
      loading: false,
    };
  },
  computed: {
    ...mapGetters(["can"]),
  },
  filters: {
    state: (estado) => {
      if (estado == 1) return "Disponible";
      if (estado == 17) return "Dispensado";
      if (estado == 18) return "Dispensado parcial";
    },
    date: (Fechaorden) => {
      return moment(Fechaorden).format("LL");
    },
    lotesFilter: (lotes) => {
      let numLotes = "";
      lotes.forEach((lote) => {
        if (numLotes != "") numLotes = numLotes.concat(`,${lote.Numlote}`);
        else numLotes = numLotes.concat(`${lote.Numlote}`);
      });
      return numLotes;
    },
  },
  mounted() {
    this.fetchBodegas();
  },
  methods: {
    findLote(articulo) {
      this.loteSelected = [];
      this.getlote.Codesumi_id = articulo.codesumi_id;
      this.getlote.Bodega_id = this.bodega_id;
      this.medicamento = {
        cantidadEntrega: 0,
        cantidadEntregaUM: 0,
        pEntregar: articulo.Cantidadmensualdisponible,
        pendiente: articulo.Cantidadmensualdisponible,
        concentracion: articulo.concentracion,
        unidadMedida: articulo.unidadMedida,
        pEntregarUM:
          articulo.concentracion * articulo.Cantidadmensualdisponible,
        pendienteUM:
          articulo.concentracion * articulo.Cantidadmensualdisponible,
        tipoOrden: articulo.tipoOrden,
      };
      axios
        .post("/api/orden/lotes", this.getlote)
        .then((res) => {
          this.listaLote = res.data.map((dat) => {
            return {
              id: dat.id,
              Articulo_id: articulo.id,
              Pendiente: articulo.Cantidadmensualdisponible,
              Numlote: dat.Numlote,
              Fvence: dat.Fvence,
              CUM_completo: dat.CUM_completo,
              Titular: dat.Titular,
              Cantidadisponible: dat.Cantidadisponible,
              cantidad: 0,
              pEntregar: parseInt(articulo.Cantidadmensualdisponible),
              Aprovechamiento: dat.aprovechamiento,
              unidadMedida: dat.unidadMedida,
              concentracion: dat.concentracion,
              cantidaDisponibleUM: dat.concentracion * dat.Cantidadisponible,
              tipoOrden: articulo.tipoOrden,
            };
          });
        })
        .catch((err) => console.log(err.response.data));
      this.lote = true;
    },
    fetchBodegas() {
      axios.get("/api/bodega/getBodegaByRole").then((res) => {
        console.log("Bodegas", res.data);
        if (res.data.length > 0) {
          this.listaBodegas = res.data;
        }
      });
    },
    verDetalleOrdenamient(orden, msg = "") {
      this.selected = [];
      this.detalleOrdenPaciente = [];
      this.loading = true;
      let tipoOrden = 3;
      if (orden.tipo_orden === "Oncologia") {
        tipoOrden = 7;
      }
      axios
        .post("/api/orden/getArticulosOrden", {
          Orden_id: orden.id,
          msg: msg,
        })
        .then((res) => {
          this.detalleOrdenPaciente = res.data.map((articulo) => {
            var p_entrega = 0;
            for (let i = 0; i < this.listaLote.length; i++) {
              if (
                this.bodega_id == this.listaLote[i].bodega_id &&
                articulo.codesumi_id == this.listaLote[i].codesumi_id
              ) {
                p_entrega += this.listaLote[i].cantidad;
              }
            }

            return {
              Codigo: articulo.Codigo,
              Cantidadmensual: articulo.Cantidadmensual,
              Cantidadmensualdisponible: articulo.Cantidadmensualdisponible,
              Cantidadispensar: articulo.Cantidadmensualdisponible,
              Cantidadosis: articulo.Cantidadosis,
              Duracion: articulo.Duracion,
              Estado_id: articulo.Estado_id,
              Frecuencia: articulo.Frecuencia,
              NumMeses: articulo.NumMeses,
              Observacion: articulo.Observacion,
              Orden_id: articulo.Orden_id,
              Unidadmedida: articulo.Unidadmedida,
              Unidadtiempo: articulo.Unidadtiempo,
              Via: articulo.Via,
              Titular: articulo.Titular,
              codesumi_id: articulo.codesumi_id,
              created_at: articulo.created_at,
              id: articulo.id,
              medicamento: articulo.medicamento,
              updated_at: articulo.updated_at,
              p_entrega: p_entrega,
              colorMed: articulo.color,
              lotes: [],
              concentracion: articulo.concentracion,
              unidadMedida: articulo.unidadMedida,
              tipoOrden: parseInt(tipoOrden),
              condicion: msg,
              cobro: articulo.cobro,
              mipres: articulo.mipres,
            };
          });
          console.log("guille", this.detalleOrdenPaciente);

          this.loading = false;
        });
      this.dialog = true;
    },

    verDetalleOrdenamientProximo(orden) {
      this.selected = [];
      this.detalleOrdenPaciente = [];
      this.loading = true;
      let tipoOrden = 3;
      if (orden.tipo_orden === "Oncologia") {
        tipoOrden = 7;
      }
      axios
        .post("/api/orden/getArticulosOrden", {
          Orden_id: orden.id,
        })
        .then((res) => {
          this.detalleOrdenPaciente = res.data.map((articulo) => {
            var p_entrega = 0;
            for (let i = 0; i < this.listaLote.length; i++) {
              if (
                this.bodega_id == this.listaLote[i].bodega_id &&
                articulo.codesumi_id == this.listaLote[i].codesumi_id
              ) {
                p_entrega += this.listaLote[i].cantidad;
              }
            }

            return {
              Codigo: articulo.Codigo,
              Cantidadmensual: articulo.Cantidadmensual,
              Cantidadmensualdisponible: articulo.Cantidadmensualdisponible,
              Cantidadispensar: articulo.Cantidadmensualdisponible,
              Cantidadosis: articulo.Cantidadosis,
              Duracion: articulo.Duracion,
              Estado_id: articulo.Estado_id,
              Frecuencia: articulo.Frecuencia,
              NumMeses: articulo.NumMeses,
              Observacion: articulo.Observacion,
              Orden_id: articulo.Orden_id,
              Unidadmedida: articulo.Unidadmedida,
              Unidadtiempo: articulo.Unidadtiempo,
              Via: articulo.Via,
              codesumi_id: articulo.codesumi_id,
              created_at: articulo.created_at,
              id: articulo.id,
              medicamento: articulo.medicamento,
              updated_at: articulo.updated_at,
              p_entrega: p_entrega,
              lotes: [],
              concentracion: articulo.concentracion,
              unidadMedida: articulo.unidadMedida,
              tipoOrden: parseInt(tipoOrden),
            };
          });

          this.loading = false;
        });
      this.dialogProximo = true;
    },

    guardarMedicamentoEntregado(idDetalleOrden) {
      const cantidadEntregada = document.querySelector(
        "input[name=cantidadEntregada" + idDetalleOrden + "]"
      ).value;
      // en este guarda el ordenamiento detalle con la cantidad entregadad
    },
    search_paciente() {
      if (!this.cedula_paciente) return;

      this.active = 0;
      this.ordenPaciente = [];

      axios
        .get(`/api/paciente/showEnabled/${this.cedula_paciente}`)
        .then((res) => {
          if (res.data.paciente) {
            this.paciente = res.data.paciente;
            this.sede_selected = this.paciente.IPS;
            if (
              parseInt(res.data.paciente.entidad_id) === 1 ||
              parseInt(res.data.paciente.entidad_id) === 3 ||
              parseInt(res.data.paciente.entidad_id) === 5
            ) {
              this.getOrdenesMedicamentosPendientes();
            } else {
              this.$alerError("El paciente no tiene derecho a dispensación");
            }
          }
          if (res.data.message) this.$alerError(res.data.message);
        })
        .catch((e) => {
          console.log(e);
        });
    },
    getOrdenesMedicamentosPendientes(msg) {
      if (!this.paciente.id) return;
      this.dispensar = true;
      this.loading = true;
      axios
        .post("/api/orden/historicoPendiente", {
          Num_Doc: this.cedula_paciente,
          tipo: msg,
        })
        .then((res) => {
          this.ordenPaciente = res.data;
          this.loading = false;
        });
    },
    getOrdenesMedicamentosHistorial() {
      if (!this.cedula_paciente) return;
      this.loading = true;
      axios
        .post("/api/orden/historicoDispensado", {
          Num_Doc: this.cedula_paciente,
          inicialdate: this.inicialdate,
          finishdate: this.finishdate,
        })
        .then((res) => {
          this.medicamentosPacienteDispensado = res.data;
          this.loading = false;
        });
    },
    getOrdenesMedicamentosProximos() {
      if (!this.cedula_paciente) return;
      this.dispensar = false;
      this.loading = true;
      axios
        .post("/api/orden/ordenesProximas", {
          Num_Doc: this.cedula_paciente,
        })
        .then((res) => {
          this.ordenPacienteProximas = res.data;
          this.loading = false;
        });
    },
    getOrdenesMedicamentosPendientesOncologicos() {
      if (!this.cedula_paciente) return;
      this.dispensar = true;
      this.loading = true;
      axios
        .post("/api/orden/historicoPendienteOncologico", {
          Num_Doc: this.cedula_paciente,
        })
        .then((res) => {
          this.ordenPacienteOncologia = res.data;
          this.loading = false;
        });
    },
    saveInicialDate() {
      this.$refs.inicialdate.save(this.inicialdate);
      this.getOrdenesMedicamentosHistorial();
    },
    saveFinishDate() {
      this.$refs.finishdate.save(this.finishdate);
      this.getOrdenesMedicamentosHistorial();
    },
    terminarDetalleOrden() {
      this.dialog = false;
      this.dialogProximo = false;
      this.Fechadispensacion = moment().format("YYYY-MM-DD");
      this.Horadispensacion = moment().format("HH:mm");
    },
    terminarLotes() {
      this.lote = false;
    },
    clearFields() {
      (this.paciente = {
        Primer_Nom: "",
        SegundoNom: "",
        Primer_Ape: "",
        Segundo_Ape: "",
        Tipo_Doc: "",
        Num_Doc: "",
        Edad_Cumplida: "",
      }),
        ((this.ordenPaciente = []), (this.ordenPacienteOncologia = []));
    },
    toggleAll() {
      if (this.selected.length) this.selected = [];
      else this.selected = this.detalleOrdenPaciente.slice();
    },
    intervalHours(h) {
      if (h > 6 && h < 20) return true;
      return false;
    },

    async dispensarMedicamento() {
      try {
        /** Validamos que todos los campos esten llenos */
        if (!this.bodega_id) {
          swal({
            title: "Debe escoger una bodega",
            icon: "warning",
          });
          return;
        }
        if (this.Fechadispensacion == "") {
          swal({
            title: "Debe llenar la fecha de dispensación",
            icon: "warning",
          });
          return;
        }
        if (this.Horadispensacion == "") {
          swal({
            title: "Debe llenar la hora de dispensación",
            icon: "warning",
          });
          return;
        }

        const request = {
          bodega_id: this.bodega_id,
          domicilio: this.domicilio,
          namedomiciliario: this.namedomiciliario,
          medicamentos: this.selected,
          Fechadispensacion: `${this.Fechadispensacion} ${this.Horadispensacion}:00.000`,
        };

        this.loadingDispensar = true;
        this.preload = true;

        /** Ejecutamos */
        const { data } = await axios.post("/api/movimiento/dispensar", request);
        swal({
          title: data.message,
          text: "",
          timer: 3000,
          icon: "success",
          buttons: false,
        });

        this.preload = false;
        this.loadingDispensar = false;
        this.dialog = false;
        this.dispensando = false;
        this.active = 0;
        this.selected = [];
        this.getOrdenesMedicamentosPendientes();
      } catch (error) {
        this.loadingDispensar = false;
        this.preload = false;
      }
    },

    dispensarLotes(Lotes) {
      var cont = 0;

      for (let i = 0; i < Lotes.length; i++) {
        cont += Lotes[i].cantidad;
      }

      if (cont == 0) this.showMessage("Lotes con cantidades en cero");
      else
        axios
          .post("/api/movimiento/dispensar")
          .then((res) => console.log("res :", res));
    },
    pendiente(e, lote) {
      let concentracion = 1;
      if (lote.tipoOrden === 7) {
        if (parseInt(lote.concentracion)) {
          concentracion = parseInt(lote.concentracion);
        }
      }
      lote.cantidad = parseInt(lote.cantidad);
      if (e && parseInt(e) > 0) {
        if (parseInt(lote.pEntregar) >= parseInt(e)) {
          this.medicamento.cantidadEntrega = 0;
          this.medicamento.cantidadEntregaUM = 0;
          let pendiente = 0;
          let pendienteUM = 0;
          this.loteSelected.forEach((lot) => {
            this.medicamento.cantidadEntrega += parseInt(lot.cantidad) || 0;
            this.medicamento.cantidadEntregaUM += parseInt(lot.cantidad) || 0;
            pendiente += parseInt(e) || 0;
            pendienteUM += parseInt(e) || 0;
            if (lot.id == lote.id) {
              if (
                concentracion * parseInt(lot.Cantidadisponible) <
                parseInt(e)
              ) {
                this.medicamento.cantidadEntrega -= parseInt(e);
                this.medicamento.cantidadEntregaUM -= parseInt(e);
                lote.cantidad = 0;
                pendiente -= parseInt(e);
                pendienteUM -= parseInt(e);
                swal({
                  title:
                    "La cantidad ingresada sobrepasa la cantidad disponible del lote",
                  text: " ",
                  icon: "warning",
                });
              }
            }
          });
          if (
            parseInt(this.medicamento.cantidadEntrega) >
            parseInt(lote.pEntregar)
          ) {
            this.loteSelected.forEach((lot) => {
              lot.cantidad = 0;
              pendiente += 0;
              pendienteUM += 0;
            });
            lote.cantidad = 0;

            swal({
              title:
                "La cantidad a dispensar sobrepasa la cantidad de la orden",
              text: " ",
              icon: "warning",
            });
          }
          this.medicamento.pendiente = this.medicamento.pEntregar - pendiente;
          this.medicamento.pendienteUM =
            this.medicamento.pEntregarUM - pendienteUM;
        } else {
          if (parseInt(lote.pEntregar) < parseInt(e)) {
            this.loteSelected.forEach((lot) => {
              lot.cantidad = 0;
            });
            lote.cantidad = 0;
            this.medicamento.cantidadEntrega = 0;
            this.medicamento.cantidadEntregaUM = 0;
            this.medicamento.pendiente = this.medicamento.pEntregar;
            this.medicamento.pendienteUM = this.medicamento.pEntregarUM;
            swal({
              title: "La cantidad ingresada sobrepasa la cantidad de la orden",
              text: " ",
              icon: "warning",
            });
          }
        }
        if (lote.tipoOrden === 7) {
          if (!parseInt(lote.concentracion)) {
            this.medicamento.cantidadEntrega = 0;
            this.medicamento.pendiente = this.medicamento.pEntregar;
            lote.cantidad = 0;
            swal({
              title: "El medicamento no tiene la concentración parametrizada",
              text: " ",
              icon: "warning",
            });
          }
        }
      } else {
        lote.cantidad = 0;
        this.medicamento.cantidadEntrega = 0;
        this.medicamento.pendiente = this.medicamento.pEntregar;
        swal({
          title: "La cantidad no puede ser inferior a cero",
          text: " ",
          icon: "warning",
        });
      }
    },
    async seleccionarLotes() {
      let articulo;
      let flag = false;
      if (
        this.medicamento.cantidadEntrega > 0 &&
        this.medicamento.cantidadEntrega <= this.medicamento.pEntregar
      ) {
        articulo = await this.detalleOrdenPaciente.find(
          (art) => art.id == this.loteSelected[0].Articulo_id
        );
        articulo.lotes = this.loteSelected;
        let sumatoria = 0;
        await this.loteSelected.forEach((lote) => {
          let cantidad = parseInt(lote.cantidad) || 0;
          if (cantidad <= 0) {
            flag = true;
          }
          sumatoria += cantidad;
        });
        if (parseInt(sumatoria) === parseInt(this.medicamento.pEntregar)) {
          articulo.color = "lightgreen";
        } else if (parseInt(sumatoria) < parseInt(this.medicamento.pEntregar)) {
          articulo.color = "lightyellow";
        }
        if (flag) {
          swal({
            title:
              "No puede selecccionar lotes con cantidad igual o menor a cero",
            text: " ",
            icon: "warning",
          });
        } else {
          articulo.p_entrega = this.medicamento.cantidadEntrega;
          this.loteSelected = [];
          this.lote = false;
        }
      } else {
        const title =
          this.medicamento.cantidadEntrega >= this.medicamento.pEntregar
            ? "No puede dispensar más de la orden"
            : "Debe ingresar un valor mayor a 0";
        swal({
          title: title,
          text: " ",
          icon: "warning",
        });
      }
    },
    async Print(medicamentos, type, msg) {
      let object = Object.assign({}, medicamentos);
      let objectMipres = Object.assign({}, medicamentos);

      if (type === 1) {
        const changeCant = (deta) => ({
          ...deta,
          Cantidadpormedico: deta.Cantidadmensualdisponible,
        });
        await axios
          .post("/api/orden/getArticulosOrden", {
            Orden_id: object.id,
            msg: msg,
          })
          .then((res) => {
            let obj = res.data.filter((s) => !s.mipres);
            let objMipres = res.data.filter((s) => parseInt(s.mipres) === 1);

            object.detaarticulordens = obj.map(changeCant);
            objectMipres.detaarticulordens = objMipres.map(changeCant);
          });
      } else {
        await axios
          .post("/api/orden/getArticulosOrden", {
            Orden_id: object.id,
            msg: msg,
          })
          .then((res) => {
            object.detaarticulordens = res.data;
          });
      }

      if (object.detaarticulordens.length > 0) {
        var pdf = {};
        this.fillData(object);
        pdf = this.getPDFFormula(object);
        pdf.type = msg;

        axios
          .post("/api/pdf/print-pdf", pdf, {
            responseType: "arraybuffer",
          })
          .then((res) => {
            let blob = new Blob([res.data], {
              type: "application/pdf",
            });
            let link = document.createElement("a");
            link.href = window.URL.createObjectURL(blob);
            window.open(link.href, "_blank");
          })
          .catch((err) => console.log(err.response));
      }

      if (objectMipres.detaarticulordens.length > 0) {
        var pdfMipres = {};
        this.fillData(objectMipres);

        pdfMipres = this.getPDFFormula(objectMipres);
        pdfMipres.type = msg;
        pdfMipres.mensaje = "M I P R E S";
        axios
          .post("/api/pdf/print-pdf", pdfMipres, {
            responseType: "arraybuffer",
          })
          .then((res) => {
            let blob = new Blob([res.data], {
              type: "application/pdf",
            });
            let link = document.createElement("a");
            link.href = window.URL.createObjectURL(blob);
            window.open(link.href, "_blank");
          })
          .catch((err) => console.log(err.response));
      }
    },
    getPDFFormula(medicamentos) {
      return (this.object = {
        Primer_Nom: this.paciente.Primer_Nom,
        Segundo_Nom: this.paciente.SegundoNom,
        Primer_Ape: this.paciente.Primer_Ape,
        Segundo_Ape: this.paciente.Segundo_Ape,
        Tipo_Doc: this.paciente.Tipo_Doc,
        Num_Doc: this.paciente.Num_Doc,
        Edad_Cumplida: this.paciente.Edad_Cumplida,
        Sexo: this.paciente.Sexo,
        IPS: this.paciente.NombreIPS,
        Direccion_Residencia: this.paciente.Direccion_Residencia,
        Correo: this.paciente.Correo,
        Telefono: this.paciente.Telefono,
        Tipo_Afiliado: this.paciente.Tipo_Afiliado,
        Estado_Afiliado: this.paciente.Estado_Afiliado,
        orden_id: medicamentos.id,
        type: "pendiente",
        medicamentos: this.medicamentos,
        cita_paciente_id: medicamentos.Cita_id,
      });
    },
    fillData(autorizacion) {
      this.medicamentos = [];
      autorizacion.detaarticulordens.forEach((med) => {
        let object = {
          id: med.codesumi_id,
          nombre: med.medicamento,
          Cantidadosis: med.Cantidadosis,
          Unidadmedida: med.Unidadmedida,
          Via: med.Via,
          Frecuencia: med.Frecuencia,
          Unidadtiempo: med.Unidadtiempo,
          Duracion: med.Duracion,
          Cantidadmensual: med.Cantidadmensual,
          NumMeses: med.NumMeses,
          Observacion: med.Observacion,
          Cantidadpormedico: med.Cantidadpormedico,
          PosViaAdmin: med.Via,
        };

        this.medicamentos.push(object);
      });
    },
    focusInput(lote) {
      lote.cantidad = "";
    },
    exportPending() {
      this.dialogExport = true;
      axios
        .post(`/api/bodega/${this.bodega_id}/pendientes`, {
          startDate: this.startDate,
          finishDate: this.finishDate,
        })
        .then((res) => {
          var CsvString = "";
          CsvString += "Tipo Doc" + ";";
          CsvString += "Num Doc" + ";";
          CsvString += "Primer Nombre" + ";";
          CsvString += "Segundo Nombre" + ";";
          CsvString += "Primer Apellido" + ";";
          CsvString += "Segundo Apellido" + ";";
          CsvString += "Telefono" + ";";
          CsvString += "Celular1" + ";";
          CsvString += "Celular2" + ";";
          CsvString += "Correo1" + ";";
          CsvString += "Correo2" + ";";
          CsvString += "Numero orden" + ";";
          CsvString += "Molecula" + ";";
          CsvString += "Cantidad" + ";";
          CsvString += "Fecha orden" + ";";
          CsvString += "Fecha pendiente" + ";";
          CsvString += "\r\n";

          res.data.forEach(function (RowItem, RowIndex) {
            for (const key in RowItem) {
              CsvString += RowItem[key] + ";";
            }
            CsvString += "\r\n";
          });
          CsvString = "data:application/csv," + encodeURIComponent(CsvString);
          var x = document.createElement("A");
          x.setAttribute("href", CsvString);
          x.setAttribute("download", "pendientes.csv");
          document.body.appendChild(x);
          x.click();
          this.dialogExport = false;
        });
    },
    actualizarEstadoOrden(orden, estado) {
      const data = {
        Estado_id: estado,
        bodega: this.bodega_id,
      };
      axios
        .post("/api/orden/updateOrderInPending/" + orden, data)
        .then((res) => {
          if (res.status === 200) {
            this.$alerSuccess(res.data.message);
          }
          this.getOrdenesMedicamentosPendientes();
        })
        .catch((e) => {
          console.log(e);
        });
    },
    async estadoPendiente(id) {
      let articulo;
      articulo = await this.detalleOrdenPaciente.find((art) => art.id == id);
      articulo.color = "lightcoral";
      articulo.lotes = [];
      this.loteSelected = [];
    },
    CambiarFechaOrden(item) {
      this.preload = true;
      axios
        .post("/api/orden/cambiarfecha", item)
        .then((res) => {
          this.getOrdenesMedicamentosProximos();
          this.preload = false;
        })
        .catch((e) => {
          this.preload = false;
          console.log(e.response);
        });
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
