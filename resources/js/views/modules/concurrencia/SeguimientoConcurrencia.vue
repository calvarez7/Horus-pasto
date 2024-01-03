<template>
  <v-container fluid grid-list-xl>
    <v-layout row wrap>
      <v-flex v-for="ref in referencias" :key="ref.color" d-flex lg3 sm6 xs12>
        <Widget
          :color="ref.color"
          :icon="ref.icon"
          :title="ref.title + ' Pacientes'"
          :subTitle="ref.subTitle"
          :supTitle="'El costo es $' + ref.supTitle"
        />
      </v-flex>
      <v-flex xs12>
        <v-card>
          <v-card-title>
            <h3>Seguimiento Concurrencia</h3>
            <v-spacer></v-spacer>
            <v-text-field
              v-model="search"
              append-icon="search"
              label="Buscar"
              single-line
              hide-details
            >
            </v-text-field>
          </v-card-title>
          <v-data-table
            :headers="headers"
            :items="listaSeguimiento"
            :search="search"
          >
            <template v-slot:items="props">
              <td>{{ props.item.registroconcurrencias_id }}</td>
              <td>{{ props.item.Ut }}</td>
              <td>
                {{ props.item.Primer_Nom }} {{ props.item.SegundoNom }}
                {{ props.item.Primer_Ape }}
                {{ props.item.Segundo_Ape }}
              </td>
              <td>{{ props.item.Num_Doc }}</td>
              <td>{{ props.item.especialidad_tratante }}</td>
              <td>{{ props.item.ips_atencion }}</td>
              <td>{{ props.item.auditor }}</td>
              <td>{{ props.item.nombre_cie10 }}</td>
              <td v-if="props.item.ppp == null">
                <v-chip dark class="success">No</v-chip>
              </td>
              <td v-if="props.item.ppp != null">
                <v-chip dark class="error">Si</v-chip>
              </td>
              <td v-if="props.item.abrazarte == null">
                <v-chip dark class="success">No</v-chip>
              </td>
              <td v-if="props.item.abrazarte != null">
                <v-chip dark class="error">Si</v-chip>
              </td>
              <td v-if="props.item.latir_sentido == null">
                <v-chip dark class="success">No</v-chip>
              </td>
              <td v-if="props.item.latir_sentido != null">
                <v-chip dark class="error">Si</v-chip>
              </td>
              <td>{{ props.item.fecha_ingreso_c }}</td>
              <td>
                <v-chip :class="semaforo(props.item)"
                  >{{ `${props.item.dias_estancia} DÍA(S)` }}
                </v-chip>
              </td>
              <td>
                <v-btn
                  fab
                  dark
                  small
                  color="purple"
                  @click="openRegistro(props.item)"
                  v-if="can('editar.concurrencia')"
                >
                  <v-icon dark>mdi-pencil</v-icon>
                </v-btn>
              </td>
            </template>
            <template v-slot:no-results>
              <v-alert :value="true" color="error" icon="warning">
                Your search for "{{ search }}" found no results.
              </v-alert>
            </template>
          </v-data-table>
        </v-card>
      </v-flex>
    </v-layout>

    <v-dialog v-model="dialog" width="1700px" persistent>
      <v-card>
        <v-card-title class="headline success" style="color: white">
          <span class="title layout justify-center font-weight-light"
            >Concurrencia</span
          >
        </v-card-title>
        <v-card-text>
          <v-container grid-list-md fluid>
            <v-layout wrap>
              <v-flex xs12 sm6 md4>
                <v-text-field
                  label="Entidad"
                  v-model="concurrencia.Ut"
                  outline
                  readonly
                >
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm6 md4>
                <v-text-field
                  label="IPS del paciente"
                  v-model="concurrencia.NombreIPS"
                  outline
                  readonly
                >
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm6 md4>
                <v-text-field
                  label="Medico Familiar"
                  v-model="concurrencia.medico_familia"
                  outline
                  readonly
                >
                </v-text-field>
              </v-flex>

              <v-flex xs12 sm6 md2>
                <v-text-field
                  label="Tipo identificacion"
                  v-model="concurrencia.Tipo_Doc"
                  outline
                  readonly
                >
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm6 md2>
                <v-text-field
                  label="Identificacion"
                  v-model="concurrencia.Num_Doc"
                  outline
                  readonly
                >
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm6 md7>
                <v-text-field
                  label="Nombre completo"
                  v-model="concurrencia.paciente_nombre"
                  readonly
                  outline
                >
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm6 md1>
                <v-text-field
                  label="Años"
                  v-model="concurrencia.Edad_Cumplida"
                  readonly
                  outline
                >
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm6 md2>
                <v-text-field
                  label="Fecha ingreso"
                  v-model="concurrencia.fecha_ingreso_concurrencia"
                  @change="total()"
                  type="date"
                  required
                >
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm6 md4>
                <v-autocomplete
                  label="Diagnostico de ingreso"
                  :items="cieConcat"
                  item-disabled="customDisabled"
                  append-icon="search"
                  item-text="nombre"
                  item-value="id"
                  v-model="concurrencia.cie10_id"
                >
                </v-autocomplete>
              </v-flex>
              <v-flex xs12 sm6 md2>
                <v-autocomplete
                  label="Tipo Hospitalización"
                  :items="[
                    'Hospitalización médica',
                    'Hospitalización quirúrgica',
                  ]"
                  v-model="concurrencia.tipo_hospitalizacion"
                  required
                >
                </v-autocomplete>
              </v-flex>
              <v-flex xs12 sm6 md2>
                <v-autocomplete
                  :items="[
                    'Hospitalización obstetrica',
                    'Hospitalización',
                    'Intermedio Adulto',
                    'Intermedio Neonatal',
                    'Intermedio Pediatría',
                    'UCI Adulto',
                    'UCI Neonatal',
                    'UCI Pediatría',
                  ]"
                  label="Unidad funcional"
                  v-model="concurrencia.unidad_funcional"
                  required
                >
                </v-autocomplete>
              </v-flex>
              <v-flex xs12 sm6 md2>
                <v-select
                  :items="[
                    'Consulta Externa',
                    'Medicina Domiciliaria',
                    'Prioritaria',
                    'Programado',
                    'Referencia',
                    'Urgencias',
                    'Traslado no regulado',
                  ]"
                  label="Via de ingreso"
                  v-model="concurrencia.via_ingreso"
                  required
                >
                </v-select>
              </v-flex>
              <v-flex xs12 sm6 md6>
                <v-autocomplete
                  :items="reIngreso15"
                  label="Reingreso hospitalización 15 dias, por el mismo diagnóstico egreso"
                  v-model="concurrencia.reingreso_hospitalización15días"
                  required
                >
                </v-autocomplete>
              </v-flex>
              <v-flex xs12 sm6 md6>
                <v-autocomplete
                  :items="reIngreso30"
                  label="Reingreso hospitalización 30 dias, por el mismo diagnóstico egreso"
                  v-model="concurrencia.reingreso_hospitalización30días"
                  required
                >
                </v-autocomplete>
              </v-flex>
              <v-flex xs12 sm6 md6>
                <v-autocomplete
                  v-model="concurrencia.ips_atencion"
                  append-icon="search"
                  :items="proveedores"
                  item-text="Nombre"
                  hide-details
                  label="Institución de atención"
                >
                </v-autocomplete>
              </v-flex>
              <v-flex xs12 sm6 md2>
                <v-text-field
                  label="Codigo Hospitalización"
                  v-model="concurrencia.codigo_hospitalizacion"
                >
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm6 md2>
                <v-text-field
                  label="Cama o Habitacion"
                  v-model="concurrencia.cama"
                >
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm6 md2>
                <v-select
                  :items="[
                    'Anestesiología',
                    'Cardiología',
                    'Cirugía bariátrica',
                    'Cirugía cardiovascular',
                    'Cirugía de cabeza y cuello',
                    'Cirugía de tórax',
                    'Cirugía gastrointestinal',
                    'Cirugía general',
                    'Cirugía hepatobiliar',
                    'Cirugía infantil',
                    'Cirugía maxilofacial',
                    'Cirugía oncológica',
                    'Cirugía plástica',
                    'Cirugía vascular',
                    'Coloproctología',
                    'Cuidado critico',
                    'Dermatología',
                    'Electrofisiología',
                    'Endocrinología',
                    'Fisiatría',
                    'Gastroenterología',
                    'Genética',
                    'Ginecología',
                    'Ginecooncología',
                    'Hematología',
                    'Hematoncología',
                    'Hemodinamia',
                    'Hepatología',
                    'Infectología',
                    'Inmunología',
                    'Mastología',
                    'Medicina del dolor',
                    'Medicina general',
                    'Medicina Interna',
                    'Medicina nuclear',
                    'Nefrología',
                    'Neonatología',
                    'Neumología',
                    'Neurocirugía',
                    'Neurología',
                    'Neuroradiología',
                    'Obstetricia',
                    'Oftalmología',
                    'Oncología',
                    'Ortopedia',
                    'Ortopedia oncológica',
                    'Otorrinolaringología',
                    'Pediatría',
                    'Psiquiatría',
                    'Radiología',
                    'Radioterapia',
                    'Reumatología',
                    'Toxicología',
                    'Urgentología',
                    'Urología',
                    'Vascular periferico (Internista)',
                  ]"
                  label="Especialidad tratante"
                  v-model="concurrencia.especialidad_tratante"
                >
                </v-select>
              </v-flex>
              <v-flex xs12 sm6 md2>
                <v-text-field
                  label="Peso Neonato"
                  v-model="concurrencia.peso_rn"
                  type="number"
                  min="0"
                >
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm6 md2>
                <v-text-field
                  label="Edad gestacional"
                  v-model="concurrencia.edad_gestacional"
                  type="number"
                  min="0"
                >
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm6 md2>
                <v-text-field
                  label="Numero de Factura"
                  v-model="concurrencia.numero_factura"
                >
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm6 md4>
                <v-autocomplete
                  label="Alto Costo"
                  v-model="concurrencia.alto_costo"
                  :items="alto_costos"
                >
                </v-autocomplete>
              </v-flex>
              <v-flex xs12 sm6 md2>
                <v-text-field
                  label="Costo Total"
                  type="number"
                  v-model="concurrencia.costo_total_global"
                >
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm6 md6>
                <v-autocomplete
                  label="Reporte Patologia Diagnostica"
                  v-model="concurrencia.reporte_patologia_diagnostica"
                  :items="reporte_patologia"
                >
                </v-autocomplete>
              </v-flex>
              <v-flex xs12 sm6 md6>
                <v-autocomplete
                  label="Hospitalizacion Evitable"
                  v-model="concurrencia.hospitalizacion_evitable"
                  :items="hospitalizacion_e"
                >
                </v-autocomplete>
              </v-flex>
              <v-flex xs12 sm6 md6>
                <v-autocomplete
                  label="Diagnostico de Concurrencia"
                  :items="cieConcat"
                  item-disabled="customDisabled"
                  append-icon="search"
                  item-text="nombre"
                  item-value="id"
                  v-model="concurrencia.dx_concurrencia"
                >
                </v-autocomplete>
              </v-flex>
              <v-flex xs12 sm12 md6>
                <v-text-field
                  label="Fecha Egreso"
                  v-model="concurrencia.fecha_egreso"
                  type="date"
                  outline
                  required
                >
                </v-text-field>
              </v-flex>
              <v-flex xs12 sm12 md6>
                <v-autocomplete
                  label="Diagnostico de Egreso Primario"
                  outline
                  :items="cieConcat"
                  item-disabled="customDisabled"
                  append-icon="search"
                  item-text="nombre"
                  v-model="concurrencia.cie10_id_egresoAsociado"
                >
                </v-autocomplete>
              </v-flex>
              <v-flex xs12 sm12 md6>
                <v-autocomplete
                  label="Destino Egreso"
                  :items="d_egreso"
                  v-model="concurrencia.destino_egreso"
                  outline
                >
                </v-autocomplete>
              </v-flex>
              <v-flex xs12 sm12 md12>
                <v-textarea
                  readonly
                  label="Nota de Ingreso"
                  v-model="concurrencia.lider_concurrencia"
                >
                </v-textarea>
              </v-flex>
              <v-flex xs12 md12 text-xs-center>
                <v-btn
                  color="success"
                  dark
                  @click="editarRegistrarCocurrencia()"
                  >Guardar
                </v-btn>
                <v-btn
                  color="warning"
                  v-if="
                    concurrencia.destino_egreso != null &&
                    concurrencia.fecha_egreso != null
                  "
                  dark
                  @click="darAlta()"
                  >Dar Alta
                </v-btn>
              </v-flex>
            </v-layout>

            <v-expansion-panel focusable>
              <v-expansion-panel-content>
                <template v-slot:actions>
                  <v-badge overlap color="primary">
                    <template v-if="costo.length > 0" v-slot:badge>
                      <v-icon color="white">done</v-icon>
                    </template>
                  </v-badge>
                </template>
                <template v-slot:header>
                  <span class="title layout justify-center font-weight-light"
                    >Ordenamiento</span
                  >
                </template>
                <v-card>
                  <v-card-text>
                    <v-container grid-list-md fluid class="pa-0">
                      <v-layout wrap align-center justify-center>
                        <v-flex xs12>
                          <v-layout row wrap>
                            <v-flex xs12 sm6 md8>
                              <v-autocomplete
                                v-model="costos.cup_id"
                                label="Seleccione el cups"
                                :item-text="
                                  (cups) => cups.Codigo + ' - ' + cups.Nombre
                                "
                                item-value="id"
                                :items="cups"
                              >
                              </v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm6 md2>
                              <v-text-field
                                v-model="costos.cantidad"
                                type="number"
                                label="Cantidad"
                              >
                              </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 md2>
                              <v-text-field
                                v-model="costos.precio"
                                type="number"
                                label="Costo"
                              >
                              </v-text-field>
                            </v-flex>
                            <v-flex xs12 md12 text-xs-center>
                              <v-btn color="primary" @click="agregarCosto()"
                                >Agregar
                              </v-btn>
                            </v-flex>
                          </v-layout>
                        </v-flex>
                      </v-layout>
                    </v-container>
                  </v-card-text>
                  <v-card>
                    <v-card-title>
                      Costo
                      <v-spacer></v-spacer>
                      <span
                        >Total: <b>{{ totalCosto }}</b></span
                      >
                      <v-spacer></v-spacer>
                      <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Buscar"
                        single-line
                        hide-details
                      >
                      </v-text-field>
                    </v-card-title>
                    <v-data-table
                      :headers="headersCosto"
                      :items="costo"
                      no-data-text="Sin datos para mostrar"
                      class="elevation-1"
                    >
                      <template v-slot:items="props">
                        <td>{{ props.item.id }}</td>
                        <td>{{ props.item.Codigo }}</td>
                        <td>{{ props.item.servicios }}</td>
                        <td>{{ props.item.cantidad }}</td>
                        <td>
                          <b>{{ props.item.valor }}</b>
                        </td>
                        <td>{{ props.item.created_at }}</td>
                        <td>{{ props.item.usuario }}</td>
                      </template>
                    </v-data-table>
                  </v-card>
                  <v-flex xs12 md12 text-xs-center>
                    <br />
                    <span
                      ><b>Estancia Hospitalizacion:</b>
                      {{ cantidadDias.dias_estancia_basicos }} </span
                    ><br />
                    <span
                      ><b>Estancia UCE:</b>
                      {{ cantidadDias.dias_estancia_intermedio }} </span
                    ><br />
                    <span
                      ><b>Estancia UCI:</b>
                      {{ cantidadDias.dias_estancia_intensivo }} </span
                    ><br />
                    <span
                      ><b>Estancia Total:</b>
                      {{ cantidadDias.estancia_total_dias }}
                    </span>
                  </v-flex>
                </v-card>
              </v-expansion-panel-content>

              <v-expansion-panel-content>
                <template v-slot:actions>
                  <v-badge overlap color="error">
                    <template
                      v-if="ListaEventoCentinela.length > 0"
                      v-slot:badge
                    >
                      <v-icon color="white">done</v-icon>
                    </template>
                  </v-badge>
                </template>
                <template v-slot:header>
                  <span class="title layout justify-center font-weight-light"
                    >Eventos Centinela</span
                  >
                </template>
                <v-card>
                  <v-card-text>
                    <v-container grid-list-md fluid class="pa-0">
                      <v-layout wrap align-center justify-center>
                        <v-flex xs12>
                          <v-layout row wrap>
                            <v-flex xs12 sm12 md12>
                              <v-autocomplete
                                :items="eventos_centinelas"
                                label="Eventos centinelas"
                                v-model="eventosCentinela.eventos_centinelas"
                              >
                              </v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm12 md12>
                              <v-textarea
                                label="Observaciones de centinela"
                                v-model="eventosCentinela.observacion_centinela"
                              >
                              </v-textarea>
                            </v-flex>
                            <v-flex xs12 md12 text-xs-center>
                              <v-btn color="primary" @click="agregarCentinela()"
                                >Agregar
                              </v-btn>
                            </v-flex>
                          </v-layout>
                        </v-flex>
                      </v-layout>
                    </v-container>
                    <v-flex xs12>
                      <v-card>
                        <v-data-table
                          :items="ListaEventoCentinela"
                          class="elevation-1"
                          hide-actions
                          :headers="headerEventoCentinela"
                        >
                          <template v-slot:items="props">
                            <td>{{ props.item.eventos_seguridad }}</td>
                            <td>{{ props.item.observacion_seguridad }}</td>
                            <td>
                              <v-btn
                                fab
                                dark
                                v-if="!props.item.actualizar"
                                color="error"
                                small
                                @click="eventosClear(props.item.id)"
                              >
                                <v-icon dark>close</v-icon>
                              </v-btn>
                            </td>
                          </template>
                        </v-data-table>
                      </v-card>
                    </v-flex>
                  </v-card-text>
                </v-card>
              </v-expansion-panel-content>

              <v-expansion-panel-content>
                <template v-slot:actions>
                  <v-badge overlap color="error">
                    <template
                      v-if="listarEventoSeguridad.length > 0"
                      v-slot:badge
                    >
                      <v-icon color="white">done</v-icon>
                    </template>
                  </v-badge>
                </template>
                <template v-slot:header>
                  <span class="title layout justify-center font-weight-light"
                    >Eventos seguridad</span
                  >
                </template>
                <v-card>
                  <v-card-text>
                    <v-container grid-list-md fluid class="pa-0">
                      <v-layout wrap align-center justify-center>
                        <v-flex xs12>
                          <v-layout row wrap>
                            <v-flex xs12 sm12 md12>
                              <v-autocomplete
                                :items="eventos_seguridad"
                                label="Eventos seguridad"
                                v-model="eventoSeguridad.Seguridad"
                              >
                              </v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm12 md12>
                              <v-textarea
                                label="Observaciones de seguridad"
                                v-model="eventoSeguridad.observacionnSeguridad"
                              >
                              </v-textarea>
                            </v-flex>
                            <v-flex xs12 md12 text-xs-center>
                              <v-btn color="primary" @click="agregarSeguridad()"
                                >Agregar
                              </v-btn>
                            </v-flex>
                          </v-layout>
                        </v-flex>
                      </v-layout>
                    </v-container>
                    <v-flex xs12>
                      <v-card>
                        <v-data-table
                          :items="listarEventoSeguridad"
                          class="elevation-1"
                          hide-actions
                          :headers="headerEventoSeguridad"
                        >
                          <template v-slot:items="props">
                            <td>{{ props.item.eventos_seguridad }}</td>
                            <td>{{ props.item.observacion_seguridad }}</td>
                            <td>
                              <v-btn
                                fab
                                dark
                                v-if="!props.item.actualizar"
                                color="error"
                                small
                                @click="eventosClear(props.item.id)"
                              >
                                <v-icon dark>close</v-icon>
                              </v-btn>
                            </td>
                          </template>
                        </v-data-table>
                      </v-card>
                    </v-flex>
                  </v-card-text>
                </v-card>
              </v-expansion-panel-content>

              <v-expansion-panel-content>
                <template v-slot:actions>
                  <v-badge overlap color="error">
                    <template
                      v-if="listarEventoNotificacion.length > 0"
                      v-slot:badge
                    >
                      <v-icon color="white">done</v-icon>
                    </template>
                  </v-badge>
                </template>
                <template v-slot:header>
                  <span class="title layout justify-center font-weight-light"
                    >Eventos de notificacion inmediata</span
                  >
                </template>
                <v-card>
                  <v-card-text>
                    <v-container grid-list-md fluid class="pa-0">
                      <v-layout wrap align-center justify-center>
                        <v-flex xs12>
                          <v-layout row wrap>
                            <v-flex xs12 sm12 md12>
                              <v-autocomplete
                                :items="eventos_notificacion"
                                label="Eventos de notificacion inmedita"
                                v-model="
                                  eventoNotificacion.eventos_notificacion_inmediata
                                "
                              >
                              </v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm12 md12>
                              <v-textarea
                                label="Descripción gestion"
                                v-model="
                                  eventoNotificacion.observacion_inmediata
                                "
                              >
                              </v-textarea>
                            </v-flex>
                            <v-flex xs12 md12 text-xs-center>
                              <v-btn
                                color="primary"
                                @click="agregarNotificacion()"
                              >
                                Agregar
                              </v-btn>
                            </v-flex>
                          </v-layout>
                        </v-flex>
                      </v-layout>
                    </v-container>
                    <v-flex xs12>
                      <v-card>
                        <v-data-table
                          :items="listarEventoNotificacion"
                          class="elevation-1"
                          hide-actions
                          :headers="headerNotificacionInmediata"
                        >
                          <template v-slot:items="props">
                            <td>{{ props.item.eventos_seguridad }}</td>
                            <td>{{ props.item.observacion_seguridad }}</td>
                            <td>
                              <v-btn
                                fab
                                dark
                                v-if="!props.item.actualizar"
                                color="error"
                                small
                                @click="eventosClear(props.item.id)"
                              >
                                <v-icon dark>close</v-icon>
                              </v-btn>
                            </td>
                          </template>
                        </v-data-table>
                      </v-card>
                    </v-flex>
                  </v-card-text>
                </v-card>
              </v-expansion-panel-content>

              <v-expansion-panel-content>
                <template v-slot:actions>
                  <v-badge overlap color="error">
                    <template
                      v-if="listarCostosEvitado.length > 0"
                      v-slot:badge
                    >
                      <v-icon color="white">done</v-icon>
                    </template>
                  </v-badge>
                </template>
                <template v-slot:header>
                  <span class="title layout justify-center font-weight-light"
                    >Costo Evitado</span
                  >
                </template>
                <v-card>
                  <v-card-text>
                    <v-container grid-list-md fluid class="pa-0">
                      <v-layout wrap align-center justify-center>
                        <v-flex xs12>
                          <v-layout row wrap>
                            <v-flex xs12 sm12 md12>
                              <v-autocomplete
                                :items="costo_evitado"
                                label="Costo evitado"
                                v-model="costoEvitado.costo_evitado"
                              >
                              </v-autocomplete>
                            </v-flex>
                            <v-flex
                              xs12
                              sm12
                              md12
                              v-if="
                                costoEvitado.costo_evitado == 'Alta temprana'
                              "
                            >
                              <v-autocomplete
                                :items="alta_temprana"
                                label="TIpo Alta Temprana"
                                v-model="costoEvitado.tipo_altaTemprana"
                              >
                              </v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm12 md6>
                              <v-text-field
                                label="Descripción costo"
                                v-model="costoEvitado.observacion_costo"
                              >
                              </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12 md6>
                              <v-text-field
                                label="Valor costo evitado"
                                type="number"
                                min="0"
                                v-model="costoEvitado.valor_evitado"
                              >
                              </v-text-field>
                            </v-flex>
                            <v-flex xs12 md12 text-xs-center>
                              <v-btn
                                color="primary"
                                @click="agregarCostoEvitado()"
                              >
                                Agregar
                              </v-btn>
                            </v-flex>
                          </v-layout>
                        </v-flex>
                      </v-layout>
                    </v-container>
                    <v-flex xs12>
                      <v-card>
                        <v-data-table
                          :items="listarCostosEvitado"
                          class="elevation-1"
                          hide-actions
                          :headers="headerCostoEvitado"
                        >
                          <template v-slot:items="props">
                            <td>{{ props.item.eventos_seguridad }}</td>
                            <td>{{ props.item.observacion_seguridad }}</td>
                            <td>{{ props.item.tipo_altaTemprana }}</td>
                            <td>{{ props.item.valor_costo_evitado }}</td>
                            <td>
                              <v-btn
                                fab
                                dark
                                v-if="!props.item.actualizar"
                                color="error"
                                small
                                @click="eventosClear(props.item.id)"
                              >
                                <v-icon dark>close</v-icon>
                              </v-btn>
                            </td>
                          </template>
                        </v-data-table>
                      </v-card>
                    </v-flex>
                  </v-card-text>
                </v-card>
              </v-expansion-panel-content>

              <v-expansion-panel-content>
                <template v-slot:actions>
                  <v-badge overlap color="error">
                    <template
                      v-if="listarCostosEvitable.length > 0"
                      v-slot:badge
                    >
                      <v-icon color="white">done</v-icon>
                    </template>
                  </v-badge>
                </template>
                <template v-slot:header>
                  <span class="title layout justify-center font-weight-light"
                    >Costo Evitable</span
                  >
                </template>
                <v-card>
                  <v-card-text>
                    <v-container grid-list-md fluid class="pa-0">
                      <v-layout wrap align-center justify-center>
                        <v-flex xs12>
                          <v-layout row wrap>
                            <v-flex xs12 sm12 md6>
                              <v-autocomplete
                                :items="costo_evitables"
                                label="Costo evitable"
                                v-model="costoEvitable.costo_evitable"
                              >
                              </v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm12 md6>
                              <v-autocomplete
                                :items="objeciones"
                                label="Objeciones"
                                v-model="costoEvitable.objeciones"
                              >
                              </v-autocomplete>
                            </v-flex>
                            <v-flex xs12 sm12 md6>
                              <v-text-field
                                label="Descripción costo"
                                v-model="costoEvitable.observacion_evitable"
                              >
                              </v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12 md6>
                              <v-text-field
                                label="Valor evitable"
                                v-model="costoEvitable.valor_evitable"
                                type="number"
                                min="0"
                              >
                              </v-text-field>
                            </v-flex>
                            <v-flex xs12 md12 text-xs-center>
                              <v-btn
                                color="primary"
                                @click="agregarCostoEvitable()"
                              >
                                Agregar
                              </v-btn>
                            </v-flex>
                          </v-layout>
                        </v-flex>
                      </v-layout>
                    </v-container>
                    <v-flex xs12>
                      <v-card>
                        <v-data-table
                          :items="listarCostosEvitable"
                          class="elevation-1"
                          hide-actions
                          :headers="headerCostoEvitable"
                        >
                          <template v-slot:items="props">
                            <td>{{ props.item.eventos_seguridad }}</td>
                            <td>{{ props.item.observacion_seguridad }}</td>
                            <td>{{ props.item.objeciones }}</td>
                            <td>{{ props.item.valor_costo_evitable }}</td>
                            <td>
                              <v-btn
                                fab
                                dark
                                v-if="!props.item.actualizar"
                                color="error"
                                small
                                @click="eventosClear(props.item.id)"
                              >
                                <v-icon dark>close</v-icon>
                              </v-btn>
                            </td>
                          </template>
                        </v-data-table>
                      </v-card>
                    </v-flex>
                  </v-card-text>
                </v-card>
              </v-expansion-panel-content>
            </v-expansion-panel>
            <br />
            <v-layout wrap>
              <v-flex xs12>
                <v-textarea
                  solo
                  name="input-7-4"
                  label="Nota de seguimiento"
                  v-model="nota_concurrencia.nota"
                ></v-textarea>
              </v-flex>
              <v-btn color="success" dark @click="agregarNota()"
                >Agregar Nota Seguimiento</v-btn
              >
            </v-layout>
            <v-flex v-for="(item, index) in notaSeguimiento" :key="index">
              <v-layout row wrap>
                <v-flex xs12 md12>
                  <span
                    v-if="item.nota_dss != null"
                    class="title layout justify-center"
                    style="color: red"
                    >Nota Dirección Servicios de Salud</span
                  >
                  <v-spacer></v-spacer>
                  <span v-if="item.nota_dss != null"
                    ><b>Realizado por:</b> {{ item.nameDss }}
                    {{ item.apellidoDss }}</span
                  >
                  <span v-if="item.nota_dss != null"
                    ><b>fecha seguimiento:</b>
                    {{ item.updated_at.split(".")[0] }}</span
                  >
                  <br />
                  <span v-if="item.nota_dss != null"
                    ><b>Nota: </b>
                    {{ item.nota_dss }}
                  </span>
                  <span
                    v-if="item.nota_aac != null"
                    class="title layout justify-center"
                    style="color: red"
                    >Nota Auditoria Alto Costo</span
                  >
                  <v-spacer></v-spacer>
                  <span v-if="item.nota_aac != null"
                    ><b>Realizado por:</b> {{ item.nameAac }}
                    {{ item.apellidoAac }}</span
                  >
                  <span v-if="item.nota_aac != null"
                    ><b>fecha seguimiento:</b>
                    {{ item.updated_at.split(".")[0] }}</span
                  >
                  <br />
                  <span v-if="item.nota_aac != null"
                    ><b>Nota: </b>{{ item.nota_aac }}
                  </span>
                  <span class="title layout justify-center"
                    >Nota Seguimiento {{ item.created_at.split(".")[0] }}</span
                  >
                  <v-spacer></v-spacer>
                  <span><b># Seguimiento:</b> {{ item.id }}</span>
                  <span
                    ><b>Realizado por:</b> {{ item.name }}
                    {{ item.apellido }}</span
                  >
                  <span
                    ><b>fecha seguimiento:</b>
                    {{ item.created_at.split(".")[0] }}</span
                  >
                  <v-textarea
                    readonly
                    outlined
                    label="Nota"
                    :value="item.seguimientoConcurrencia"
                  >
                  </v-textarea>
                  <v-flex xs12 md12 text-xs-center>
                    <v-btn
                      color="primary"
                      v-if="
                        can('concurrencia.notaDSS') && item.nota_dss == null
                      "
                      dark
                      @click="abrirDSS(item)"
                    >
                      Nota Dirección de servicios de salud</v-btn
                    >
                    <v-btn
                      color="primary"
                      v-if="
                        can('concurrencia.notaACC') && item.nota_aac == null
                      "
                      dark
                      @click="abrirAAC(item)"
                    >
                      Nota Auditoria Alto Costo</v-btn
                    >
                    <v-spacer></v-spacer>
                    <v-divider></v-divider>
                  </v-flex>
                </v-flex>
              </v-layout>
            </v-flex>
            <br />
            <v-flex v-if="!notaSeguimiento.length">
              <v-card color="error" dark>
                <v-card-text>
                  <v-flex xs12 md12>
                    <span
                      ><b
                        >El paciente actualmente no cuenta con ningun
                        seguimiento.</b
                      ></span
                    >
                  </v-flex>
                </v-card-text>
              </v-card>
            </v-flex>
          </v-container>
          <v-dialog v-model="dialogNotaDSS" width="700" persistent>
            <v-card>
              <v-card-title class="headline success" style="color: white">
                <span class="title layout justify-center font-weight-light"
                  >Nota Dirección de Servicios de Salud</span
                >
              </v-card-title>

              <v-card-text>
                <v-textarea label="Nota" v-model="notaDSS.nota"></v-textarea>
              </v-card-text>

              <v-divider></v-divider>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="error" text @click="dialogNotaDSS = false">
                  Cerrar
                </v-btn>
                <v-btn color="primary" text @click="guardarDSS()">
                  Guardar
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-dialog v-model="dialogNotaAAC" width="700" persistent>
            <v-card>
              <v-card-title class="headline success" style="color: white">
                <span class="title layout justify-center font-weight-light"
                  >Nota Auditoria Alto Costo</span
                >
              </v-card-title>

              <v-card-text>
                <v-textarea label="Nota" v-model="notaAAC.nota"></v-textarea>
              </v-card-text>

              <v-divider></v-divider>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="error" text @click="dialogNotaAAC = false">
                  Cerrar
                </v-btn>
                <v-btn color="primary" text @click="guardarAAC()">
                  Guardar
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red" dark @click="dialog = false">Terminar</v-btn>
        </v-card-actions>
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
  </v-container>
</template>

<script>
import moment from "moment";
import Widget from "../../../components/referencia/Widget.vue";
import { mapGetters } from "vuex";
moment.locale("es");

export default {
  name: "ReferenciaReporte",
  components: {
    Widget,
  },
  data: () => {
    return {
      count: {
        _a2: 0,
        _a3: 0,
        _a9: 0,
      },
      dialog: false,
      loading: false,
      dialogNotaDSS: false,
      dialogNotaAAC: false,
      search: "",
      referencias: [
        {
          color: "#00b297",
          icon: "history",
          title: "0",
          subTitle: "Total ingresados",
          supTitle: `Hasta la fecha`,
        },
        {
          color: "#00c851",
          icon: "watch_later",
          title: "0",
          subTitle: "Menores a 4 Días",
          supTitle: `En concurrencia`,
        },
        {
          color: "#f80",
          icon: "archive",
          title: "0",
          subTitle: "Entre 4 a 5 Días",
          supTitle: `En concurrencia`,
        },
        {
          color: "#dc3545",
          icon: "notification_important",
          title: "0",
          subTitle: "Mayores a 5 Días",
          supTitle: `En concurrencia`,
        },
      ],
      listaSeguimiento: [],
      ListaEventoCentinela: [],
      listarCostosEvitable: [],
      nota_concurrencia: {
        nota: null,
      },
      notaDSS: {
        nota: null,
      },
      notaAAC: {
        nota: null,
      },
      concurrencia: [
        {
          Ut: null,
          NombreIPS: null,
          medico_familia: null,
          Tipo_Doc: null,
          Num_Doc: null,
          paciente_nombre: null,
          Edad_Cumplida: null,
          fecha_ingreso_concurrencia: null,
          cie10_id: null,
          tipo_hospitalizacion: null,
          unidad_funcional: null,
          via_ingreso: null,
          reingreso_hospitalización15días: null,
          reingreso_hospitalización30días: null,
          ips_atencion: null,
          codigo_hospitalizacion: null,
          cama: null,
          especialidad_tratante: null,
          peso_rn: null,
          edad_gestacional: null,
          numero_factura: null,
          alto_costo: null,
          costo_total_global: null,
          reporte_patologia_diagnostica: null,
          hospitalizacion_evitable: null,
          dx_concurrencia: null,
          fecha_egreso: null,
          cie10_id_egresoAsociado: null,
          destino_egreso: null,
          lider_concurrencia: null,
        },
      ],
      costoEvitado: {
        costo_evitado: null,
        observacion_costo: null,
        tipo_altaTemprana: null,
        valor_evitado: null,
        type: 26,
      },
      costoEvitable: {
        costo_evitable: null,
        objeciones: null,
        observacion_evitable: null,
        valor_evitable: null,
        type: 27,
      },
      listarCostosEvitado: [],
      listarEventoNotificacion: [],
      eventosCentinela: {
        eventos_centinelas: null,
        observacion_centinela: null,
        type: 24,
      },
      listarEventoSeguridad: [],
      eventoSeguridad: {
        Seguridad: null,
        observacionnSeguridad: null,
        type: 23,
      },
      eventoNotificacion: {
        eventos_notificacion_inmediata: null,
        observacion_inmediata: null,
        type: 25,
      },
      cups: [],
      headers: [
        {
          text: "id",
          align: "left",
          sortable: false,
          value: "id",
        },
        {
          text: "Entidad",
        },
        {
          text: "Paciente",
          value: "calories",
        },
        {
          text: "Cédula",
          value: "Num_Doc",
        },
        {
          text: "Especialidad",
          value: "Especialidad_remi",
        },
        {
          text: "Atendido en",
          value: "ips_atencion",
        },
        {
          text: "Usuario Ingresa",
          value: "auditor",
        },
        {
          text: "Cie10 Ingreso",
          value: "nombre_cie10",
        },
        {
          text: "Punta Piramide",
          value: "ppp",
        },
        {
          text: "Abrazarte",
          value: "abrazarte",
        },
        {
          text: "Latir Con Sentido",
          value: "latir_sentido",
        },
        {
          text: "F. inicio registro",
          align: "left",
          sortable: false,
          value: "create_at",
        },
        {
          text: "Dias Estancia",
          value: "state",
        },
        {
          text: "Seguimiento",
        },
      ],
      costos: {
        cup_id: "",
        cantidad: "",
        precio: "",
      },
      costo: [],
      cie10s: [],
      notaSeguimiento: [],
      proveedores: [],
      concurrenciaRegistrada: null,
      cantidadDias: "",
      totalCosto: "",
      headersCosto: [
        {
          text: "id",
          value: "id",
        },
        {
          text: "Codigo",
          value: "Codigo",
        },
        {
          text: "Servicio",
          value: "Nombre",
        },
        {
          text: "Cantidad",
          value: "Cantidad",
        },
        {
          text: "Costo",
          value: "valor",
        },
        {
          text: "Fecha",
          value: "created_at",
        },
        {
          text: "Usuario",
          value: "usuario",
        },
      ],
      headerEventoCentinela: [
        {
          text: "Evento centinela",
        },
        {
          text: "Observación de centinela",
        },
        {
          text: "Eliminar",
        },
      ],
      headerEventoSeguridad: [
        {
          text: "Evento de seguridad",
        },
        {
          text: "Observación de seguridad",
        },
        {
          text: "Eliminar",
        },
      ],
      headerNotificacionInmediata: [
        {
          text: "Evento de notificación inmediata",
        },
        {
          text: "Descripción de gestión",
        },
        {
          text: "Eliminar",
        },
      ],
      headerCostoEvitable: [
        {
          text: "Evento evitable",
        },
        {
          text: "Descripción costo",
        },
        {
          text: "Objecion",
        },
        {
          text: "Valor costo evitable",
        },
        {
          text: "Eliminar",
        },
      ],
      headerCostoEvitado: [
        {
          text: "Evento evitado",
        },
        {
          text: "Descripción costo",
        },
        {
          text: "Tipo alta temprana",
        },
        {
          text: "Valor costo evitado",
        },
        {
          text: "Eliminar",
        },
      ],

      reIngreso15: [
        "No es un reingreso a hospitalización por la misma causa antes de 15 días",
        "Altas tempranas no pertinentes.",
        "Altas voluntarias.",
        "Complicación posterior a la realización de procedimiento.",
        "Deterioro del estado clínico.",
        "Disfuncionalidad de catéteres o sondas (patologías crónicas)",
        "Eventos relacionados con uso de medicamentos prescritos",
        "Falta oportunidad en procedimiento ambulatorios posterior al alta (diferidas).",
        "Infección de sitio operatorio – ISO.",
        "No adherencia al tratamiento por parte del paciente.",
        "No dispensación medicamento posterior al alta.",
        "No ingreso a programas ni captación de gestión de riesgo posterior al alta.",
      ],
      reIngreso30: [
        "No es un reingreso a hospitalización por la misma causa antes de 30 días",
        "Altas tempranas no pertinentes.",
        "Altas voluntarias.",
        "Complicación posterior a la realización de procedimiento.",
        "Deterioro del estado clínico.",
        "Disfuncionalidad de catéteres o sondas (patologías crónicas)",
        "Eventos relacionados con uso de medicamentos prescritos",
        "Falta oportunidad en procedimiento ambulatorios posterior al alta (diferidas).",
        "Infección de sitio operatorio – ISO.",
        "No adherencia al tratamiento por parte del paciente.",
        "No dispensación medicamento posterior al alta.",
        "No ingreso a programas ni captación de gestión de riesgo posterior al alta.",
      ],
      eventos_centinelas: [
        "Bajo Peso al nacer",
        "Hospitalización por EDA en niños de 3 a 5 años",
        "Hospitalización por neumonia en niños y niñas de 3 a 5 años",
        "Muerte Materna",
        "Muerte por Dengue",
        "Muerte por Malaria",
        "Otitis Media Supurativa en menores de 5 años",
      ],
      eventos_seguridad: [
        "Asalto sexual en la institución",
        "Asfixia perinatal",
        "Cirugía en parte equivocada o en paciente equivocado",
        "Cirugías o procedimientos cancelados por factores atribuibles al desempeño de la organización o de los profesionales",
        "Consumo intra - institucional de Psicoactivos",
        "Deterioro del paciente en la clasificación en la escala de Glasgow sin tratamiento",
        "Distocia inadvertida",
        "Entrega equivocada de reportes de laboratorio",
        "Entrega equivocada de un neonato",
        "Error en el diagnostico",
        "Estancia prolongada por no disponibilidad de insumos o medicamentos",
        "Eventos asociados al uso de dispositivos médicos y equipos biomédicos",
        "Eventos postransfusionales",
        "Eventos relacionados con la administración de medicamentos",
        "Eventración o evisceración o dehiscencia de sutura",
        "Extubación o decanulación no programada",
        "Flebitis mecánica - Flebitis en sitios de venopunción",
        "Fuga de pacientes siquiátricos y menores de 14 años hospitalizados",
        "Infección del Sitio Operatorio - ISO",
        "Infección del Torrente Sanguineo Asociado a catéter en UCI",
        "Infección del Tracto Urinario Asociado a Catéter en UCI",
        "Infecciones asociadas a la atención en salud - IAAS (Nosocomiales)",
        "Ingreso no programado a UCI luego de procedimiento que implica la administración de anestesia",
        "Lesión de órgano secundario a procedimiento",
        "Lesiones causadas por caídas institucional",
        "Luxación post - quirúrgica en reemplazo de cadera",
        "Maternas con convulsión intrahospitalaria",
        "Neumonia Asociada a Ventilador Mecanico en UCI",
        "Neumotórax por ventilación mecánica o asociado a paso de catéter venoso central",
        "Otros eventos de seguridad del paciente identificados",
        "Pacientes con diagnóstico que apendicitis que no son atendidos después de 12 horas de realizado el diagnostico",
        "Pacientes con hipotensión severa en post – quirúrgico",
        "Pacientes con infarto en las siguientes 72 horas post – quirúrgico",
        "Pacientes con neumonías broncoaspirativas en pediatría o UCI neonatal",
        "Pacientes con trombosis venosa profunda a quienes no se les realiza control de pruebas de coagulación",
        "Pacientes con úlceras de posición",
        "Pérdida de pertenencias de usuarios",
        "Problemas relacionados con el uso de medicamentos (PRUM)",
        "Quemaduras por equipos biomédicos",
        "Quemaduras por lámparas de fototerapia y para electrocauterio",
        "Reacción adversa a los medicamentos",
        "Reingreso a hospitalización por la misma causa antes de 15 días",
        "Reingreso al servicio de urgencias por misma causa antes de 72 Horas",
        "reingreso por la misma causa o causa asociada antes de 30 dias",
        "Retención de cuerpos extraños en pacientes internados",
        "Retiro accidental o autoretiro de dispositivos invasivos",
        "Revisión de reemplazos articulares por inicio tardío de la rehabilitación",
        "Robo intra – institucional de niños",
        "Ruptura prematura de membranas sin conducta definida",
        "Secuelas post – reanimación",
        "Shock hipovolémico post – parto (Código rojo)",
        "Suicidio de pacientes internados",
      ],
      eventos_notificacion: [
        "Anomalías congénitas",
        "Hipotiroidismo congénito",
        "Mortalidad EDA <5 años",
        "Mortalidad infantil <1 año",
        "Mortalidad IRA/neumonía <5",
        "Mortalidad IRA/neumonía >65",
        "Mortalidad perinatal",
        "Mortalidad por otra EISP",
        "Sífilis congénita",
        "Sífilis gestacional",
        "VIH/gestante infectada",
        "VIH/mortalidad por VIH-SIDA",
        "VIH/RN vivo gestante infectada",
      ],
      costo_evitado: [
        "Alta temprana",
        "Tecnología no realizada",
        "Traslado a IPS con menor  tarifa o nivel",
        "Estancia evitada por servicios y/o procedimientos diferidos (ambulatorios)",
        "Traslados en ambulancia",
      ],
      alta_temprana: [
        "Cambio de tratamiento a oral",
        "Domiciliaria agudos",
        "Oxigeno domiciliario",
        "Terapias domiciliarias",
        "Gestión entrega insumos/medicamentos por alta condicionada",
        "Renegociación de tarifa",
      ],
      costo_evitables: [
        "Pertinencia",
        "Cobertura",
        "Oportunidad",
        "Autorizaciones",
      ],
      objeciones: [
        "Ayudas diagnósticas",
        "Procedimientos",
        "Estancia",
        "Medicamentos",
        "Valoración especialista",
      ],
      alto_costos: [
        "Cáncer",
        "Cirugía cardiovascular",
        "Cirugía enfermedades congénitas",
        "Diálisis (CAPD/hemo)",
        "Terapia Ecmo",
        "Electrofisiología",
        "Gran quemado",
        "Hemodinamia",
        "Neurocirugía",
        "Neurointervencionismo",
        "Quimioterapia / radioterapia",
        "Reemplazos articulares",
        "Terapia biológica",
        "Trasplante",
        "Trauma mayor",
        "UCI",
        "VIH",
      ],
      incumplimientos: [
        "Dotación",
        "Historia Clínica Y Registros",
        "Infraestructura",
        "Interdependencia",
        "Medicamentos, Dispositivos Médicos e Insumos",
        "Procesos Prioritarios ",
        "Talento Humano",
      ],
      d_egreso: [
        "Muerte en menor de 5 años",
        "Alta",
        "Fuga",
        "Medicina domiciliaria",
        "Muerte Materna",
        "Muerte Perinatal",
        "Muerte por Maltrato",
        "Muerto",
        "Refencia a mayor nivel",
        "Refencia a menor nivel",
        "Referencia a menor tarifa pactada",
        "Salida Voluntaria",
      ],
      hospitalizacion_e: [
        "AIT",
        "Amenaza de parto pretérmino",
        "Angina inestable",
        "Angina no especificada",
        "Anticoagulados",
        "Asma",
        "Bloqueo A-V",
        "Cáncer de cérvix",
        "Cáncer de próstata",
        "Cáncer de seno",
        "Cáncer de colón",
        "Celulitis",
        "Cetoacidosis diabética",
        "Coma hiperosmolar",
        "Diabetes gestacional descompensada",
        "DM descompensada",
        "Eclampsia",
        "ECV Hemorrágico",
        "ECV Isquémico",
        "Emergencia HTA",
        "Enfermedad Coronaria Severa",
        "Enfermedad renal Crónica",
        "Enfermedad renal crónica agudizada",
        "EPOC descompensado",
        "Falla de remisión oportuna del RN",
        "Fibrilación auricular",
        "HTA No controlada",
        "HTA inducida en el embarazo descompensada",
        "IAM",
        "Isquemia critica ( Enfermedad arterial oclusiva)",
        "ICC descompensada",
        "Infección del tracto urinario (ITU)",
        "Infecciones neonatales tempranas en madres con antecedente de patología infecciosa evitables como IVU, corioamnionitis, neumonía.",
        "Macrosomía",
        "Neumonía",
        "No dispensación de medicamentos",
        "Patología Psiquiatrica",
        "Pre-eclampsia",
        "RCIU",
        "Recién nacido con TORCH",
        "RN pretérmino",
        "RPM",
        "SDR de RN (TTRN, pulmón húmedo)",
        "TBC congénita",
        "Taquicardia supraventricular",
        "Tétanos neonatal",
        "Trastorno de electrolitos en paciente polimedicado",
        "Trastorno electrolítico con madre diabética",
        "Urgencia dialítica",
        "Urgencia HTA",
        "Covid en seguimiento",
        "VIH (Hospitalización por patología asociada a VIH)",
      ],
      reporte_patologia: [
        "Anticoagulados - Z921",
        "Anticoagulados (Novo)",
        "Consumo de sustancias Psicoactivas",
        "Diabetes Mellitus - E109",
        "DM (Novo)",
        "Diabetes Mellitus- HTA",
        "Dislipidemia",
        "Enfermedad renal crónica - N189",
        "EPOC - J449",
        "EPOC (Novo)",
        "Hipertensión Arterial - I10x",
        "HTA (Novo)",
        "Oncológico",
        "Paciente Polimedicado",
        "Tuberculosis",
        "Victima de violencia sexual",
        "VIH ",
        "VIH / Novo",
      ],
    };
  },

  mounted() {
    this.seguiminetoConcurrencia();
    this.sedeProveedores();
    this.counter();
    this.fetchCie10s();
    this.fetchCups();
  },
  computed: {
    ...mapGetters(["can"]),
    cieConcat() {
      return (this.cie10Array = this.cie10s.map((cie) => {
        return {
          id: cie.id,
          codigo: cie.Codigo_CIE10,
          nombre: `${cie.Codigo_CIE10} - ${cie.Descripcion}`,
          customDisabled: false,
        };
      }));
    },
  },
  methods: {
    counter() {
      axios.get("/api/referencia/counterSeguimiento").then((res) => {
        this.referencias[0].title = `${res.data.total}`;
        this.referencias[0].supTitle = `${res.data.suma_total}`;
        this.referencias[1].title = `${res.data.menor}`;
        this.referencias[1].supTitle = `${res.data.suma_menor}`;
        this.referencias[2].title = `${res.data.entre}`;
        this.referencias[2].supTitle = `${res.data.suma_entre}`;
        this.referencias[3].title = `${res.data.mayor}`;
        this.referencias[3].supTitle = `${res.data.suma_mayor}`;
      });
    },

    seguiminetoConcurrencia() {
      axios
        .get("/api/referencia/listarSeguimiento")
        .then((res) => {
          this.listaSeguimiento = res.data;
        })
        .catch((e) => {
          this.$alerError("Error");
        });
    },

    fetchCups() {
      axios.get("/api/cups/all").then((res) => (this.cups = res.data));
    },

    fetchCie10s() {
      axios.get("/api/cie10/all").then((res) => {
        this.cie10s = res.data;
      });
    },

    sedeProveedores() {
      axios
        .get("/api/sedeproveedore/sedeproveedores")
        .then((res) => {
          this.proveedores = res.data;
        })
        .catch((err) => console.log(err));
    },

    verCosto() {
      axios
        .post(
          "/api/referencia/costoConcurrencia/" +
            this.concurrencia.registroconcurrencias_id
        )
        .then((res) => {
          this.costo = res.data.datos;
          this.totalCosto = res.data.total_suma.total_suma;
          this.cantidadDias = res.data.dias;
        })
        .catch((e) => {
          this.$alerError("Error");
        });
    },

    openRegistro(seguimiento) {
      this.concurrencia = seguimiento;
      this.concurrencia.cie10_id = parseInt(seguimiento.cie10_id);
      this.concurrencia.dx_concurrencia = parseInt(seguimiento.dx_concurrencia);
      this.dialog = true;
      this.verCosto();
      this.listarCentinela();
      this.listarSeguridad();
      this.listarNotificacion();
      this.listarCostoEvitado();
      this.listarCostoEvitable();
      this.notasConcurrencia();
    },

    agregarCentinela() {
      axios
        .post(
          "/api/referencia/guardarEventosCentinela/" +
            this.concurrencia.registroconcurrencias_id,
          this.eventosCentinela
        )
        .then((res) => {
          this.listarCentinela();
          this.$alerSuccess("Guardado con exito!");
        })
        .catch((e) => {
          this.$alerError("Error");
        });
    },

    listarCentinela() {
      axios
        .get(
          "/api/referencia/listarEventosCentinela/" +
            this.concurrencia.registroconcurrencias_id
        )
        .then((res) => {
          this.ListaEventoCentinela = res.data;
        })
        .catch((e) => {
          this.$alerError("Error");
        });
    },

    agregarSeguridad() {
      axios
        .post(
          "/api/referencia/guardarEventosSeguridad/" +
            this.concurrencia.registroconcurrencias_id,
          this.eventoSeguridad
        )
        .then((res) => {
          this.listarSeguridad();
          this.$alerSuccess("Guardado con exito!");
        })
        .catch((e) => {
          this.$alerError("Error");
        });
    },

    listarSeguridad() {
      axios
        .get(
          "/api/referencia/listarEventosSeguridad/" +
            this.concurrencia.registroconcurrencias_id
        )
        .then((res) => {
          this.listarEventoSeguridad = res.data;
        })
        .catch((e) => {
          this.$alerError("Error");
        });
    },

    agregarNotificacion() {
      axios
        .post(
          "/api/referencia/guardarNotificacion/" +
            this.concurrencia.registroconcurrencias_id,
          this.eventoNotificacion
        )
        .then((res) => {
          this.listarNotificacion();
          this.$alerSuccess("Guardado con exito!");
        })
        .catch((e) => {
          this.$alerError("Error");
        });
    },

    listarNotificacion() {
      axios
        .get(
          "/api/referencia/listarNotificacion/" +
            this.concurrencia.registroconcurrencias_id
        )
        .then((res) => {
          this.listarEventoNotificacion = res.data;
        })
        .catch((e) => {
          this.$alerError("Error");
        });
    },

    agregarCostoEvitado() {
      axios
        .post(
          "/api/referencia/guardarCostoEvitado/" +
            this.concurrencia.registroconcurrencias_id,
          this.costoEvitado
        )
        .then((res) => {
          this.listarCostoEvitado();
          this.clearCostoEvitado();
          this.$alerSuccess("Guardado con exito!");
        })
        .catch((e) => {
          this.$alerError("Error");
        });
    },

    clearCostoEvitado() {
      this.costoEvitado.costo_evitado = null;
      this.costoEvitado.observacion_costo = null;
      this.costoEvitado.tipo_altaTemprana = null;
      this.costoEvitado.valor_evitado = null;
    },

    listarCostoEvitado() {
      axios
        .get(
          "/api/referencia/listarCostoEvitado/" +
            this.concurrencia.registroconcurrencias_id
        )
        .then((res) => {
          this.listarCostosEvitado = res.data;
        })
        .catch((e) => {
          this.$alerError("Error");
        });
    },

    agregarCostoEvitable() {
      axios
        .post(
          "/api/referencia/guardarCostoEvitable/" +
            this.concurrencia.registroconcurrencias_id,
          this.costoEvitable
        )
        .then((res) => {
          this.listarCostoEvitable();
          this.clearCostoEvitable();
          this.$alerSuccess("Guardado con exito!");
        })
        .catch((e) => {
          this.$alerError("Error");
        });
    },

    clearCostoEvitable() {
      this.costoEvitable.costo_evitable = null;
      this.costoEvitable.objeciones = null;
      this.costoEvitable.observacion_evitable = null;
      this.costoEvitable.valor_evitable = null;
    },
    listarCostoEvitable() {
      axios
        .get(
          "/api/referencia/listarCostoEvitable/" +
            this.concurrencia.registroconcurrencias_id
        )
        .then((res) => {
          this.listarCostosEvitable = res.data;
        })
        .catch((e) => {
          this.$alerError("Error");
        });
    },

    agregarCosto() {
      axios
        .post(
          "/api/referencia/guardarCosto/" +
            this.concurrencia.registroconcurrencias_id,
          this.costos
        )
        .then((res) => {
          this.verCosto();
          this.costos.cup_id = "";
          this.costos.cantidad = "";
          this.costos.precio = "";
          this.$alerSuccess("Agregado correctamente");
        })
        .catch((e) => {
          this.$alerError("Error");
        });
    },

    semaforo(items) {
      if (items.dias_estancia <= 3) {
        return "success white--text";
      } else if (items.dias_estancia <= 5) {
        return "yellow white--text";
      } else {
        return "error white--text";
      }
    },

    sedeProveedores() {
      axios
        .get("/api/sedeproveedore/sedeproveedores")
        .then((res) => {
          this.proveedores = res.data;
        })
        .catch((err) => console.log(err));
    },

    editarRegistrarCocurrencia() {
      this.preload = true;
      if (this.concurrencia.fecha_ingreso_concurrencia == null) {
        this.preload = false;
        swal({
          title: "Por favor ingrese la fecha de ingreso!",
          icon: "error",
          buttons: true,
        });
        return;
      }
      if (!this.concurrencia.cie10_id) {
        this.preload = false;
        swal({
          title: "Por favor ingrese el diagnostico de ingreso!",
          icon: "error",
          buttons: true,
        });
        return;
      }
      if (this.concurrencia.tipo_hospitalizacion == null) {
        this.preload = false;
        swal({
          title: "Por favor ingrese el tipo de hospitalización!",
          icon: "error",
          buttons: true,
        });
        return;
      }
      if (this.concurrencia.unidad_funcional == null) {
        this.preload = false;
        swal({
          title: "Por favor ingrese la unidad funcional!",
          icon: "error",
          buttons: true,
        });
        return;
      }
      if (this.concurrencia.via_ingreso == null) {
        this.preload = false;
        swal({
          title: "Por favor ingrese via de ingreso!",
          icon: "error",
          buttons: true,
        });
        return;
      }
      if (this.concurrencia.reingreso_hospitalización15días == null) {
        this.preload = false;
        swal({
          title: "Por favor ingrese reingreso hospitalización 15 dias!",
          icon: "error",
          buttons: true,
        });
        return;
      }
      if (this.concurrencia.reingreso_hospitalización30días == null) {
        this.preload = false;
        swal({
          title: "Por favor ingrese reingreso hospitalización 30 dias!",
          icon: "error",
          buttons: true,
        });
        return;
      }
      if (this.concurrencia.ips_atencion == null) {
        this.preload = false;
        swal({
          title: "Por favor ingrese la insitución de atención!",
          icon: "error",
          buttons: true,
        });
        return;
      }
      axios
        .post(
          "/api/referencia/editarSeguimientoCocurrencia/" +
            this.concurrencia.registroconcurrencias_id,
          this.concurrencia
        )
        .then((res) => {
          // this.concurrencia = res.data.data
          this.preload = false;
          this.$alerSuccess("Se actualizo correctamente!");
        })
        .catch((e) => {
          this.preload = false;
          this.$alerError("Error");
        });
    },

    agregarNota() {
      axios
        .post(
          "/api/referencia/seguimientoConcurrencia/" +
            this.concurrencia.registroconcurrencias_id,
          this.nota_concurrencia
        )
        .then((res) => {
          this.$alerSuccess("Seguimiento guardado con exito!");
          this.notasConcurrencia();
          this.nota_concurrencia.nota = null;
        })
        .catch((e) => {
          this.$alerError("Error");
        });
    },

    notasConcurrencia() {
      axios
        .get(
          "/api/referencia/listarNotas/" +
            this.concurrencia.registroconcurrencias_id
        )
        .then((res) => {
          this.notaSeguimiento = res.data;
        })
        .catch((e) => {
          this.$alerError("Error");
        });
    },

    abrirDSS(items) {
      this.dialogNotaDSS = true;
      this.notaDSS = items;
    },

    abrirAAC(items) {
      this.dialogNotaAAC = true;
      this.notaAAC = items;
    },

    guardarDSS() {
      axios
        .post("/api/referencia/notaDss/" + this.notaDSS.id, this.notaDSS)
        .then((res) => {
          this.$alerSuccess("Nota guardada con exito!");
          this.notasConcurrencia();
          this.dialogNotaDSS = false;
          this.notaDSS.nota = null;
        })
        .catch((e) => {
          this.$alerError("Error");
        });
    },

    guardarAAC() {
      axios
        .post("/api/referencia/notaAac/" + this.notaAAC.id, this.notaAAC)
        .then((res) => {
          this.$alerSuccess("Nota guardada con exito!");
          this.notasConcurrencia();
          this.dialogNotaAAC = false;
          this.notaAAC.nota = null;
        })
        .catch((e) => {
          this.$alerError("Error");
        });
    },

    darAlta() {
      if (this.concurrencia.destino_egreso == null) {
        return this.$alerError("Debe llenar el campo de destino de egreso");
      }

      if (this.concurrencia.cie10_id_egresoAsociado == null) {
        return this.$alerError("Debe llenar el campo de cie10 de egreso");
      }

      axios
        .post(
          "/api/referencia/altaConcurrenncia/" +
            this.concurrencia.registroconcurrencias_id
        )
        .then((res) => {
          this.$alerSuccess("Paciente de alta con exito!");
          this.dialog = false;
          this.seguiminetoConcurrencia();
          this.counter();
        })
        .catch((e) => {
          this.$alerError(e.response.mensaje);
        });
    },
    eventosClear($id) {
      axios
        .post("/api/referencia/eliminarEvento/" + $id)
        .then((res) => {
          this.$alerSuccess("Se Elimino Correctamente");
          this.listarCentinela();
          this.listarSeguridad();
          this.listarNotificacion();
          this.listarCostoEvitado();
          this.listarCostoEvitable();
        })
        .catch((error) => {
          this.$alerError("Error al Eliminar!");
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.buttom-nav-anexo {
  width: 30% !important;
  margin: 0 35% !important;
  border-radius: 40px;
  /* border-radius: 25px 25px 0 0; */
}
</style>
