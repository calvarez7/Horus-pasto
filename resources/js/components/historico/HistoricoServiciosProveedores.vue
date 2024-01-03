<template>
  <v-layout row wrap>
    <v-dialog v-model="dialogPrestador" persistent max-width="1200px">
      <v-card>
        <v-flex>
          <v-card flat>
            <v-card-title class="headline grey lighten-2"
              >Datos del paciente</v-card-title
            >
            <v-container>
              <v-layout wrap align-center>
                <v-flex xs6 md3 lg4>
                  <v-text-field
                    v-model="paciente.paciente"
                    label="Nombre"
                    outlined
                    readonly
                  ></v-text-field>
                </v-flex>
                <v-flex xs6 md3 lg2>
                  <v-text-field
                    v-model="paciente.Cedula"
                    label="Cédula"
                    outlined
                    readonly
                  ></v-text-field>
                </v-flex>
                <v-flex xs6 md3 lg2>
                  <v-text-field
                    v-model="paciente.Edad_Cumplida"
                    label="edad"
                    outlined
                    readonly
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs6 md3 lg2>
                  <v-text-field
                    v-model="paciente.Sexo"
                    label="sexo"
                    outlined
                    readonly
                  >
                  </v-text-field>
                </v-flex>
                <v-flex xs6 md3 lg2>
                  <v-text-field
                    v-model="paciente.Tipo_Afiliado"
                    label="Tipo Afiliado"
                    outlined
                    readonly
                  ></v-text-field>
                </v-flex>
              </v-layout>
              <v-layout wrap align-center>
                <v-flex xs6 md3 lg3>
                  <v-text-field
                    v-model="paciente.Departamento"
                    label="Departamento"
                    outlined
                    readonly
                  ></v-text-field>
                </v-flex>
                <v-flex xs6 md3 lg2>
                  <v-text-field
                    v-model="paciente.Mpio_Afiliado"
                    label="Municipio Afiliado"
                    outlined
                    readonly
                  ></v-text-field>
                </v-flex>
                <v-flex xs6 md3 lg3>
                  <v-text-field
                    v-model="paciente.Direccion_Residencia"
                    label="Direccion Residencia"
                    outlined
                    readonly
                  ></v-text-field>
                </v-flex>
                <v-flex xs6 md3 lg2>
                  <v-text-field
                    v-model="paciente.Telefono"
                    label="Telefono"
                    outlined
                    readonly
                  ></v-text-field>
                </v-flex>
                <v-flex xs6 md3 lg2>
                  <v-text-field
                    v-model="paciente.Celular"
                    label="Celular"
                    outlined
                    readonly
                  ></v-text-field>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card>
        </v-flex>
      </v-card>

      <v-card>
        <v-card-title class="headline grey lighten-2">Servicio</v-card-title>
        <v-container grid-list-md>
          <v-layout wrap>
            <v-flex xs12 class="text-xs-center">
              <h2>{{ paciente.Cup_Nombre || paciente.Servicio_Nombre }}</h2>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card>

      <v-card>
        <v-card-title class="headline grey lighten-2"
          >Detalles Servicio</v-card-title
        >
        <form @submit.prevent="guardarDetalle">
          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs6>
                  <v-radio-group
                    color="info"
                    v-model="formPrestador.cancelacion"
                  >
                    <v-radio
                      color="info"
                      v-for="n in [
                        { l: 'ASISTENCIA', d: 'NO' },
                        { l: 'CANCELADA', d: 'SI' },
                        { l: 'INASISTENCIA', d: 'INASISTENCIA' },
                        { l: 'NO CONTACTADO', d: 'nocontactado' },
                      ]"
                      :key="n.d"
                      :label="n.l"
                      :value="n.d"
                    ></v-radio>
                  </v-radio-group>
                </v-flex>
                <v-flex xs6 v-if="formPrestador.cancelacion === 'SI'">
                  <v-menu
                    v-model="menu3"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    lazy
                    transition="scale-transition"
                    offset-y
                    full-width
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <VTextField
                        clearable
                        v-model="formPrestador.fecha_cancelacion"
                        label="Fecha Cancelación"
                        prepend-icon="event"
                        readonly
                        v-on="on"
                        :required="formPrestador.cancelacion === 'SI'"
                      />
                    </template>
                    <VDatePicker
                      color="primary"
                      v-model="formPrestador.fecha_cancelacion"
                      @input="menu3 = false"
                      :required="formPrestador.cancelacion === 'SI'"
                    />
                  </v-menu>
                </v-flex>
                <v-flex xs6 v-if="formPrestador.cancelacion === 'NO'"> </v-flex>
                <v-flex xs12 v-if="formPrestador.cancelacion === 'SI'">
                  <v-autocomplete
                    :items="[
                      'ADMINISTRATIVA AFILIACIÓN',
                      'EXIGENCIAS Y CONDICIONES DEL USUARIO',
                    ]"
                    label="Causa Cancelación"
                    v-model="formPrestador.causa"
                    :required="formPrestador.cancelacion === 'SI'"
                  ></v-autocomplete>
                </v-flex>
                <v-flex xs12 v-if="formPrestador.cancelacion === 'SI'">
                  <v-autocomplete
                    :items="[
                      'ADMINISTRATIVA AFILIACIÓN',
                      'EXIGENCIAS Y CONDICIONES DEL USUARIO',
                      'NO DISPONIBILIDAD DE INSUMOS/DISPOSITIVOS/MEDICAMENTOS',
                      'NO DISPONIBILIDAD DE INFRAESTRUCTURA',
                      'NO DISPONIBILIDAD DE RECURSO HUMANO',
                      'NO DISPONIBILIDAD DE EQUIPOS MEDICOS',
                    ]"
                    label="Motivo Cancelación"
                    v-model="formPrestador.motivo"
                    :required="formPrestador.cancelacion === 'SI'"
                  ></v-autocomplete>
                </v-flex>
                <v-flex xs4 v-if="formPrestador.cancelacion === 'NO'">
                  <v-menu
                    v-model="menu1"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    lazy
                    transition="scale-transition"
                    offset-y
                    full-width
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        clearable
                        v-model="formPrestador.fecha_sugerida"
                        label="Fecha Sugerida"
                        prepend-icon="event"
                        readonly
                        v-on="on"
                        :required="formPrestador.cancelacion === 'NO'"
                      />
                    </template>
                    <v-date-picker
                      color="primary"
                      v-model="formPrestador.fecha_sugerida"
                      @input="menu1 = false"
                      :required="formPrestador.cancelacion === 'NO'"
                    />
                  </v-menu>
                </v-flex>
                <v-flex xs4 v-if="formPrestador.cancelacion === 'NO'">
                  <v-menu
                    v-model="menu2"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    lazy
                    transition="scale-transition"
                    offset-y
                    full-width
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        clearable
                        v-model="formPrestador.fecha_solicitada"
                        label="Fecha Solicitada Usuario"
                        prepend-icon="event"
                        readonly
                        v-on="on"
                        :required="formPrestador.cancelacion === 'NO'"
                      />
                    </template>
                    <v-date-picker
                      color="primary"
                      v-model="formPrestador.fecha_solicitada"
                      @input="menu2 = false"
                      :required="formPrestador.cancelacion === 'NO'"
                    />
                  </v-menu>
                </v-flex>

                <v-flex xs4 v-if="formPrestador.cancelacion === 'NO'">
                  <v-menu
                    v-model="menu7"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    lazy
                    transition="scale-transition"
                    offset-y
                    full-width
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        clearable
                        v-model="formPrestador.fecha_resultado"
                        label="Fecha Resultado"
                        prepend-icon="event"
                        readonly
                        v-on="on"
                        :required="formPrestador.cancelacion === 'NO'"
                      />
                    </template>
                    <v-date-picker
                      color="primary"
                      v-model="formPrestador.fecha_resultado"
                      @input="menu7 = false"
                      :required="formPrestador.cancelacion === 'NO'"
                    />
                  </v-menu>
                </v-flex>

                <v-flex xs12>
                  <v-divider></v-divider>
                </v-flex>
                <v-flex xs12>
                  <v-textarea
                    outline
                    label="Observaciones"
                    v-model="formPrestador.observaciones"
                    :required="formPrestador.cancelacion === 'NO'"
                  ></v-textarea>
                </v-flex>
                <v-flex xs12>
                  <v-text-field
                    label="Funcionario Responsable"
                    v-model="formPrestador.responsable"
                    required
                  ></v-text-field>
                </v-flex>
                <label v-if="formPrestador.cancelacion === 'NO'"
                  >Soporte Resultados</label
                >
                <v-flex xs12 v-if="formPrestador.cancelacion === 'NO'">
                  <input id="adjuntos" multiple ref="adjuntos" type="file" />
                </v-flex>
                <v-flex xs12 v-if="formPrestador.cancelacion === 'NO'">
                  <v-btn
                    color="warning"
                    :key="index"
                    :href="
                      '/storage/adjuntosSoportes/' +
                      paciente.Cedula +
                      '/' +
                      archivo
                    "
                    outline
                    round
                    small
                    target="_blank"
                    v-for="(archivo, index) in archivos"
                  >
                    {{ archivo }}
                  </v-btn>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>

          <v-card-title
            v-if="formPrestador.cancelacion === 'NO'"
            class="headline grey lighten-2"
            >Datos Cirugía</v-card-title
          >
          <v-card-text v-if="formPrestador.cancelacion === 'NO'">
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs6>
                  <v-text-field
                    label="Cirujano"
                    v-model="formPrestador.cirujano"
                  ></v-text-field>
                </v-flex>
                <v-flex xs6>
                  <v-text-field
                    label="Especialidad"
                    v-model="formPrestador.especialidad"
                  ></v-text-field>
                </v-flex>
                <v-flex xs4>
                  <v-menu
                    v-model="menu4"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    lazy
                    transition="scale-transition"
                    offset-y
                    full-width
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        clearable
                        v-model="formPrestador.fecha_Preanestesia"
                        label="Fecha Preanestesia"
                        prepend-icon="event"
                        readonly
                        v-on="on"
                      />
                    </template>
                    <v-date-picker
                      color="primary"
                      v-model="formPrestador.fecha_Preanestesia"
                      @input="menu4 = false"
                    />
                  </v-menu>
                </v-flex>

                <v-flex xs4>
                  <v-menu
                    v-model="menu5"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    lazy
                    transition="scale-transition"
                    offset-y
                    full-width
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        clearable
                        v-model="formPrestador.fecha_cirugia"
                        label="Fecha Cirugía"
                        prepend-icon="event"
                        readonly
                        v-on="on"
                      />
                    </template>
                    <v-date-picker
                      color="primary"
                      v-model="formPrestador.fecha_cirugia"
                      @input="menu5 = false"
                    />
                  </v-menu>
                </v-flex>

                <v-flex xs4>
                  <v-menu
                    v-model="menu6"
                    :close-on-content-click="false"
                    :nudge-right="40"
                    lazy
                    transition="scale-transition"
                    offset-y
                    full-width
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on }">
                      <v-text-field
                        clearable
                        v-model="formPrestador.fecha_ejecucion"
                        label="Fecha Ejecución"
                        prepend-icon="event"
                        readonly
                        v-on="on"
                      />
                    </template>
                    <v-date-picker
                      color="primary"
                      v-model="formPrestador.fecha_ejecucion"
                      @input="menu6 = false"
                    />
                  </v-menu>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="error" @click="dialogPrestador = false">CERRAR</v-btn>
            <v-btn
              color="success"
              @click="enableEmailPrestador = !enableEmailPrestador"
              >ENVIAR POR CORREO</v-btn
            >
            <v-expand-x-transition>
              <v-card
                v-show="enableEmailPrestador"
                height="200"
                width="500"
                class="mx-auto"
              >
                <v-card-text>
                  <v-layout row wrap>
                    <v-flex xs10>
                      <v-text-field
                        v-model="Email"
                        type="email"
                        prepend-icon="mail"
                        label="Email"
                      ></v-text-field>
                    </v-flex>
                  </v-layout>
                </v-card-text>
                <v-card-actions>
                  <v-btn color="primary" round @click="SendEmail()"
                    >Enviar</v-btn
                  >
                </v-card-actions>
              </v-card>
            </v-expand-x-transition>

            <v-btn
              v-if="formPrestador.cancelacion === 'NO'"
              color="info"
              type="submit"
              >GUARDAR E IMPRIMIR</v-btn
            >
            <v-btn v-else color="info" type="submit">GUARDAR</v-btn>
          </v-card-actions>
        </form>
      </v-card>
    </v-dialog>
    <v-dialog v-model="dialog" max-width="1000px" persistent>
      <v-card>
        <v-card-title class="headline grey lighten-2">Servicios</v-card-title>
        <v-data-table
          class="fb-table-elem"
          :headers="cupOrderHeaders"
          :items="autorizacion.cupordens"
          item-key="index"
          v-model="selected"
        >
          <template v-slot:items="props">
            <td
              class="justify-center"
              v-if="props.item.soportes && can('soportes.prestadores')"
            >
              <v-icon
                color="red"
                class="mr-2"
                @click="adjuntosSoportes(props.item.soportes)"
              >
                mdi-file-pdf
              </v-icon>
            </td>
            <td class="text-xs-center">
              <div class="datatable-cell-wrapper">{{ props.item.id }}</div>
            </td>
            <td class="text-xs-center">
              <div class="datatable-cell-wrapper">
                {{ props.item.Cup_Codigo || props.item.Servicio_Codigo }}
              </div>
            </td>
            <td class="text-xs-center">
              <div class="datatable-cell-wrapper">
                {{ props.item.Cup_Nombre || props.item.Servicio_Nombre }}
              </div>
            </td>
            <td class="text-xs-center">
              <div class="datatable-cell-wrapper">
                {{ props.item.observaciones }}
              </div>
            </td>
            <td class="text-xs-center" @click="openDialog(props.item)">
              {{ props.item.Sede_Nombre }}
              <v-dialog
                v-model="prestador"
                persistent
                v-if="can('auditoria.servicios.change')"
                width="500"
              >
                <v-card>
                  <v-card-title class="headline grey lighten-2" primary-title>
                    Modificación Prestador
                  </v-card-title>

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
              <div class="datatable-cell-wrapper">
                {{ props.item.Sede_Direccion }}
              </div>
            </td>
            <td class="text-xs-center">
              <div class="datatable-cell-wrapper">
                {{ props.item.Sede_Telefono }}
              </div>
            </td>
            <td class="text-xs-center">
              <div class="datatable-cell-wrapper">
                {{ props.item.Cup_Cantidad }}
              </div>
            </td>
            <td class="text-xs-center">
              <div class="datatable-cell-wrapper">
                {{ props.item.Auth_Name }} {{ props.item.Auth_Apellido }}
              </div>
            </td>
            <td class="text-xs-center">
              <div class="datatable-cell-wrapper">
                {{ props.item.Auth_Nota }}
              </div>
            </td>
            <td>
              <v-btn
                color="primary"
                round
                @click="asignarDatosPrestador(props.item)"
                >Imprimir</v-btn
              >
            </td>
          </template>
        </v-data-table>
      </v-card>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" flat @click="cerrarModal()">Cerrar</v-btn>
      </v-card-actions>
    </v-dialog>
    <v-flex xs12>
      <v-card>
        <v-card-text>
          <v-container grid-list-md>
            <v-layout wrap>
              <v-flex xs3>
                <v-autocomplete
                  label="Estado"
                  :items="estadosPrestadores"
                  item-text="nombre"
                  item-value="value"
                  v-model="filtersForm.estadoPrestadores"
                  required
                ></v-autocomplete>
              </v-flex>
              <v-flex md2>
                <v-select
                  v-model="filtersForm.month"
                  append-icon="search"
                  label="Seleccionar Mes"
                  :items="months"
                  item-text="month"
                  item-value="value"
                  hide-details
                  :disabled="filtersForm.numeroDocumento ? true : false"
                ></v-select>
              </v-flex>

              <v-flex md1>
                <v-select
                  v-model="filtersForm.year"
                  append-icon="search"
                  label="Seleccionar Año"
                  :items="years"
                  hide-details
                  :disabled="filtersForm.numeroDocumento ? true : false"
                ></v-select>
              </v-flex>

              <v-flex xs3>
                <v-text-field
                  append-icon="search"
                  v-model="filtersForm.numeroDocumento"
                  label="Numero Documento"
                  single-line
                  hide-details
                ></v-text-field>
              </v-flex>
              <v-flex xs6>
                <v-autocomplete
                  :items="sedes"
                  item-text="Nombre"
                  item-value="id"
                  v-model="filtersForm.sede"
                  label="Seleccionar Sedes..."
                ></v-autocomplete>
              </v-flex>
              <v-flex xs3>
                <v-btn color="success" @click="getOroderByPrestador"
                  >Filtrar</v-btn
                >
              </v-flex>
              <v-flex xs3>
                <v-btn color="warning" v-if="can('exportar.consolidadoVisiontotal')" @click="abrirDialogo()">Reporte</v-btn>
                <v-btn
                  color="primary"
                  v-show="!filtersForm.estadoPrestadores"
                  @click="exportar"
                  >Exportar</v-btn
                >
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
    </v-flex>
    <template>
      <div class="text-center">
        <v-dialog v-model="preload" persistent width="300">
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
    </template>
    <v-flex xs12>
      <v-card>
        <v-card-title>
          <v-text-field
            v-model="search"
            append-icon="search"
            label="Buscar..."
            single-line
            hide-details
          ></v-text-field>
        </v-card-title>
        <v-data-table
          :headers="headers"
          :items="listaAutorizaciones"
          item-key="index"
          :search="search"
        >
          <template v-slot:items="props">
            <!--                        <td>-->
            <!--                            <v-btn text icon color="blue" dark>-->
            <!--                                <v-icon @click="printhc(props.item)">assignment_turned_in</v-icon>-->
            <!--                            </v-btn>-->
            <!--                            <span>Historia</span>-->
            <!--                        </td>-->
            <td class="text-xs-center" v-if="props.item.cancelacion === 'SI'">
              <v-chip label color="error" text-color="white">Cancelada</v-chip>
            </td>
            <td
              class="text-xs-center"
              v-else-if="props.item.cancelacion === 'NO'"
            >
              <v-chip label color="success" text-color="white"
                >ASISTENCIA</v-chip
              >
            </td>
            <td
              class="text-xs-center"
              v-else-if="props.item.cancelacion === 'INASISTENCIA'"
            >
              <v-chip label color="warning" text-color="white"
                >Inasistencia</v-chip
              >
            </td>
            <td
              class="text-xs-center"
              v-else-if="props.item.cancelacion === 'nocontactado'"
            >
              <v-chip label color="warning" text-color="white"
                >NO CONTACTADO</v-chip
              >
            </td>
            <td class="text-xs-center" v-else></td>

            <td class="text-xs-center">{{ props.item.id }}</td>
            <td class="text-xs-center">{{ props.item.FechaOrdenamiento }}</td>
            <td class="text-xs-center">{{ props.item.fechaVigencia }}</td>
            <td class="text-xs-center">{{ props.item.diasVencido }}</td>
            <td class="text-xs-center">
              {{ props.item.Primer_Nom }} {{ props.item.Primer_Ape }}
            </td>
            <td class="text-xs-center">{{ props.item.Prestadores }}</td>
            <td class="text-xs-center">{{ props.item.Cedula }}</td>
            <td class="text-xs-center">{{ props.item.Descripcion_CIE10 }}</td>
            <td class="text-xs-center">
              {{ props.item.Cup_Codigo || props.item.Servicio_Codigo }}
            </td>
            <td class="text-xs-center">
              {{ props.item.Cup_Nombre || props.item.Servicio_Nombre }}
            </td>
            <td class="text-xs-center">{{ props.item.Cup_Cantidad }}</td>
            <td class="text-xs-center">{{ props.item.observaciones }}</td>
            <td class="text-xs-center">{{ props.item.Auth_Apellido }}</td>
            <td class="text-xs-center">{{ props.item.Auth_Nota }}</td>
            <td class="text-xs-center">
              <v-chip color="primary" text-color="white">{{
                props.item.Estado
              }}</v-chip>
            </td>

            <td class="text-xs-center">
              <v-btn
                color="blue"
                class="ma-2 white--text"
                @click="asignarDatosPrestador(props.item)"
              >
                Imprimir
                <v-icon right dark>send</v-icon>
              </v-btn>
            </td>
          </template>
          <v-alert v-slot:no-results :value="true" color="error" icon="warning"
            >Your search for "{{ search }}" found no results.</v-alert
          >
        </v-data-table>
      </v-card>
    </v-flex>
    <v-dialog v-model="modalReporte" width="500">
      <v-card>
        <v-card-title class="headline success" style="color: white">
          <span class="title layout justify-center font-weight-light"
            >Exportar Consolidados Ordenamientos</span
          >
        </v-card-title>
        <v-card-text>
          <v-flex xs12>
            <v-autocomplete
              v-model="filtros.entidad"
              item-value="id"
              item-text="nombre"
              :items="listEntidades"
              label="Selecciona Entidad"
            >
            </v-autocomplete>
          </v-flex>
          <v-flex xs12>
            <v-layout row wrap align-center>
              <v-flex xs6>
                <v-menu
                  v-model="fecha_menu1"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  lazy
                  transition="scale-transition"
                  offset-y
                  full-width
                  min-width="290px"
                >
                  <template v-slot:activator="{ on }">
                    <VTextField
                      v-model="filtros.fechaDesde"
                      label="Fecha Desde"
                      prepend-icon="event"
                      readonly
                      v-on="on"
                    />
                  </template>
                  <VDatePicker
                    color="primary"
                    v-model="filtros.fechaDesde"
                    @input="fecha_menu1 = false"
                  />
                </v-menu>
              </v-flex>
              <v-flex xs6>
                <v-menu
                  v-model="fecha_menu2"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  lazy
                  transition="scale-transition"
                  offset-y
                  full-width
                  min-width="290px"
                >
                  <template v-slot:activator="{ on }">
                    <VTextField
                      v-model="filtros.fechaHasta"
                      label="Fecha Hasta"
                      prepend-icon="event"
                      readonly
                      v-on="on"
                    />
                  </template>
                  <VDatePicker
                    color="primary"
                    v-model="filtros.fechaHasta"
                    @input="fecha_menu2 = false"
                  />
                </v-menu>
              </v-flex>
            </v-layout>
          </v-flex>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="error" text @click="modalReporte = false">
            Cancelar
          </v-btn>
          <v-btn color="primary" text @click="generarExcel()"> Exportar </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-layout>
</template>
<script>
import moment from "moment";
import { mapGetters } from "vuex";
export default {
  name: "HistoricoServicioPrestadores",
  data() {
    return {
      enableEmailPrestador: false,
      sedes: [],
      modalReporte: false,
      listEntidades: [
        { id: 1, nombre: "RedVital" },
        {
          id: 3,
          nombre: "Fondo Pasivo Social de los Ferrocarriles Nacionales",
        },
      ],
      filtros: {
        entidad: null,
        fechaDesde: null,
        fechaHasta: null,
      },
      preload: false,
      archivos: [],
      paciente: {},
      fecha_menu1: false,
      fecha_menu2: false,
      menu1: false,
      menu2: false,
      menu3: false,
      menu4: false,
      menu5: false,
      menu6: false,
      menu7: false,
      Email: "",
      filtersForm: {
        numeroDocumento: null,
        estadoPrestadores: null,
        month: null,
        year: null,
        sede: null,
      },
      estadosPrestadores: [
        { nombre: "POR GESTIONAR", value: null },
        { nombre: "CANCELADA", value: "SI" },
        { nombre: "ASISTENCIA", value: "NO" },
        { nombre: "INASISTENCIA", value: "INASISTENCIA" },
        { nombre: "NO CONTACTADO", value: "nocontactado" },
      ],
      listaAutorizaciones: [],
      autorizacion: [],
      selected: [],
      dialog: false,
      search: "",
      dialogPrestador: false,
      prestador: false,
      numeroDocumento: null,
      formPrestador: {
        fecha_solicitada: moment().format("YYYY-MM-DD"),
        fecha_sugerida: moment().format("YYYY-MM-DD"),
        fecha_cancelacion: moment().format("YYYY-MM-DD"),
        fecha_resultado: moment().format("YYYY-MM-DD"),
        cancelacion: "NO",
        observaciones: "",
        responsable: "",
        motivo: "",
        causa: "",
        cirujano: "",
        especialidad: "",
        fecha_Preanestesia: moment().format("YYYY-MM-DD"),
        fecha_cirugia: moment().format("YYYY-MM-DD"),
        fecha_ejecucion: moment().format("YYYY-MM-DD"),
      },
      data: {
        Tiporden_id: 3,
        articulos: [],
        procedimientos: [],
      },
      headers: [
        {
          text: "",
          sortable: false,
          align: "center",
          value: "",
        },
        {
          text: "Orden",
          sortable: false,
          align: "center",
          value: "id",
        },
        {
          text: "Fecha Ordenamiento",
          sortable: false,
          align: "center",
          value: "FechaOrdenamiento",
        },
        {
          text: "Fecha Vigencia",
          sortable: false,
          align: "center",
          value: "fechaVigencia",
        },
        {
          text: "Dias Vencido",
          sortable: false,
          align: "center",
          value: "diasVencido",
        },
        {
          text: "Nombre",
          sortable: false,
          align: "center",
          value: "Primer_Nom",
        },
        {
          text: "IPS",
          sortable: false,
          align: "center",
          value: "IPS",
        },
        {
          text: "Cedula",
          sortable: false,
          align: "center",
          value: "Cedula",
        },
        {
          text: "Diagnostico",
          sortable: false,
          align: "center",
          value: "Descripcion_CIE10",
        },
        {
          text: "Código",
          sortable: false,
          align: "center",
          value: "Cup_Codigo",
        },
        {
          text: "Nombre Servicio",
          sortable: false,
          align: "center",
          value: "Cup_Nombre",
        },
        {
          text: "Cantidad",
          sortable: false,
          align: "center",
          value: "Cup_Cantidad",
        },
        {
          text: "Observacion",
          sortable: false,
          align: "center",
          value: "Auth_Nota",
        },
        {
          text: "Auditor",
          sortable: false,
          align: "center",
          value: "",
        },
        {
          text: "Nota Auditoria",
          sortable: false,
          align: "center",
          value: "Auth_Nota",
        },
        {
          text: "Estado Auditor",
          sortable: false,
          align: "center",
          value: "Auth_Nota",
        },
        {
          text: "Acciones",
          sortable: false,
          align: "center",
          value: "",
        },
      ],
      cupOrderHeaders: [
        {
          text: "",
          align: "center",
          sortable: false,
          value: "",
        },
        {
          text: "Orden Servicio",
          sortable: false,
          align: "center",
          value: "id",
        },
        {
          text: "Código",
          sortable: false,
          align: "center",
          value: "Cup_Codigo",
        },
        {
          text: "Nombre",
          sortable: false,
          align: "center",
          value: "Cup_Nombre",
        },
        {
          text: "Observacion",
          sortable: false,
          align: "center",
          value: "observaciones",
        },
        {
          text: "Prestador",
          sortable: false,
          align: "center",
          value: "Sede_Nombre",
        },
        {
          text: "Dirección Prestador",
          sortable: false,
          align: "center",
          value: "Sede_Direccion",
        },
        {
          text: "Teléfono Prestador",
          sortable: false,
          align: "center",
          value: "Sede_Telefono",
        },
        {
          text: "Cantidad",
          sortable: false,
          align: "center",
          value: "Cup_Cantidad",
        },
        {
          text: "Auditor",
          sortable: false,
          align: "center",
          value: "",
        },
        {
          text: "Nota Auditoria",
          sortable: false,
          align: "center",
          value: "Auth_Nota",
        },
        {
          text: "",
          sortable: false,
          align: "center",
          value: "",
        },
      ],
      years: ["2018", "2019", "2020", "2021", "2022", "2023"],
      months: [
        {
          month: "Enero",
          value: "1",
        },
        {
          month: "Febrero",
          value: "2",
        },
        {
          month: "Marzo",
          value: "3",
        },
        {
          month: "Abril",
          value: "4",
        },
        {
          month: "Mayo",
          value: "5",
        },
        {
          month: "Junio",
          value: "6",
        },
        {
          month: "Julio",
          value: "7",
        },
        {
          month: "Agosto",
          value: "8",
        },
        {
          month: "Septiembre",
          value: "9",
        },
        {
          month: "Octubre",
          value: "10",
        },
        {
          month: "Noviembre",
          value: "11",
        },
        {
          month: "Diciembre",
          value: "12",
        },
      ],
    };
  },
  computed: {
    ...mapGetters(["can"]),
  },
  methods: {
    getOroderByPrestador() {
      if (!this.filtersForm.sede) {
        return this.$alerError('El filtro "sede" es requerido.');
      }
      this.preload = true;
      this.preload_servicio = true;
      axios
        .post("/api/autorizacion/listAuthServByState", this.filtersForm)
        .then((res) => {
          this.listaAutorizaciones = res.data;
          this.preload = false;
        })
        .catch((e) => {
          this.preload = false;
        });
    },
    abrirModal(autorizacion) {
      this.autorizacion = autorizacion;
      this.dialog = true;
    },
    show() {
      return false;
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
    asignarDatosPrestador(item) {
      this.selected = [];
      this.paciente = item;
      this.dialogPrestador = true;
      this.idServicio = item.idServicio;
      this.formPrestador.fecha_sugerida = item.fecha_sugerida;
      this.formPrestador.fecha_solicitada = item.fecha_solicitada;
      this.formPrestador.fecha_cancelacion = item.fecha_cancelacion;
      this.formPrestador.cancelacion = item.cancelacion;
      this.formPrestador.motivo = item.motivo;
      this.formPrestador.causa = item.causa;
      this.formPrestador.responsable = item.responsable;
      this.formPrestador.observaciones = item.observacionesPrestador;
      this.formPrestador.cirujano = item.cirujano;
      this.formPrestador.especialidad = item.especialidad;
      this.formPrestador.fecha_Preanestesia = item.fecha_Preanestesia;
      this.formPrestador.fecha_cirugia = item.fecha_cirugia;
      this.formPrestador.fecha_ejecucion = item.fecha_ejecucion;
      this.formPrestador.fecha_resultado = item.fecha_resultado;
      this.archivos = JSON.parse(item.soportes);
      this.selected.push(item);
    },
    guardarDetalle() {
      let formData = new FormData();
      if (
        this.formPrestador.cancelacion === "NO" &&
        !this.formPrestador.fecha_sugerida
      ) {
        this.$alerError("La Fecha Sugerida es Requerida");
        return;
      }
      if (
        this.formPrestador.cancelacion === "NO" &&
        !this.formPrestador.fecha_solicitada
      ) {
        this.$alerError("La Fecha solicitada es Requerida");
        return;
      }
      if (this.formPrestador.cancelacion === "SI") {
        this.formPrestador.fecha_solicitada = null;
        this.formPrestador.fecha_sugerida = null;
        this.formPrestador.fecha_resultado = null;
        this.formPrestador.cirujano = null;
        this.formPrestador.especialidad = null;
        this.formPrestador.fecha_Preanestesia = null;
        this.formPrestador.fecha_cirugia = null;
        this.formPrestador.fecha_ejecucion = null;
      } else if (this.formPrestador.cancelacion === "NO") {
        this.formPrestador.fecha_cancelacion = null;
        this.formPrestador.causa = null;
        this.formPrestador.motivo = null;
        this.adjuntos = this.$refs.adjuntos.files;
        for (let i = 0; i < this.adjuntos.length; i++) {
          formData.append(`adjuntos[]`, this.adjuntos[i]);
        }
      } else {
        this.formPrestador.fecha_solicitada = null;
        this.formPrestador.fecha_sugerida = null;
        this.formPrestador.fecha_cancelacion = null;
        this.formPrestador.fecha_resultado = null;
        this.formPrestador.causa = null;
        this.formPrestador.motivo = null;
        this.formPrestador.cirujano = null;
        this.formPrestador.especialidad = null;
        this.formPrestador.fecha_Preanestesia = null;
        this.formPrestador.fecha_cirugia = null;
        this.formPrestador.fecha_ejecucion = null;
      }
      for (const prop of Object.getOwnPropertyNames(this.formPrestador)) {
        formData.append([prop], this.formPrestador[prop]);
      }
      axios
        .post("/api/orden/datosPrestador/" + this.idServicio, formData)
        .then((res) => {
          this.$alerSuccess(res.data.message);
          this.dialogPrestador = false;
          this.formPrestador.fecha_solicitada = null;
          this.formPrestador.fecha_resultado = null;
          this.formPrestador.fecha_sugerida = null;
          this.formPrestador.observaciones = null;
          this.formPrestador.fecha_cancelacion = null;
          this.formPrestador.causa = null;
          this.formPrestador.motivo = null;
          this.formPrestador.responsable = null;
          this.archivos = [];
          this.getOroderByPrestador();
          this.printPDF();
        });
    },
    async printPDF() {
      var pdf = {};
      this.fillData(this.selected);
      pdf = {
        Primer_Nom: this.selected[0].Primer_Nom,
        Segundo_Nom: this.selected[0].Segundo_Nom,
        Primer_Ape: this.selected[0].Primer_Ape,
        Segundo_Ape: this.selected[0].Segundo_Ape,
        Tipo_Doc: this.selected[0].Tipo_Doc,
        Num_Doc: this.selected[0].Cedula,
        Edad_Cumplida: this.selected[0].Edad_Cumplida,
        Sexo: this.selected[0].Sexo,
        IPS: this.selected[0].Nombre_IPS,
        Direccion_Residencia: this.autorizacion.Direccion_Residencia,
        Correo: this.Email,
        Telefono: this.selected[0].Telefono,
        Celular: this.selected[0].Celular,
        Tipo_Afiliado: this.selected[0].Tipo_Afiliado,
        Estado_Afiliado: this.selected[0].Estado_Afiliado,
        dx_principal: this.selected[0].Codigo_CIE10,
        cita_paciente_id: this.selected[0].citaPaciente_id,
        orden_id: this.selected[0].id,
        Firma: this.selected[0].Medico_Firma,
        fecha_auditoria: this.selected[0].created_at,
        Firma_Auditor: this.Firma_Auditor,
        type: "otros",
        servicios: this.data.procedimientos,
        nombrePrestador: this.selected[0].nombrePrestador,
        direccionPrestador: this.selected[0].direccionPrestador,
        nitPrestador: this.selected[0].nitPrestador,
        telefono1Prestador: this.selected[0].telefono1Prestador,
        telefono2Prestador: this.selected[0].telefono2Prestador,
        codigoHabilitacion: this.selected[0].codigoHabilitacion,
        municipioPrestador: this.selected[0].municipioPrestador,
        departamentoPrestador: this.selected[0].departamentoPrestador,
      };
      if (this.formPrestador.cancelacion === "NO") {
        await this.print(pdf);
      }
    },
    fillData(selected) {
      this.data.procedimientos = [];

      this.Firma_Auditor = selected.Auth_Firma;

      selected.forEach((select) => {
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
          identificador: select.idServicio,
          Ubicacion_id: select.ubicacion_id,
          Auth_Nota: select.Auth_Nota,
        };

        this.data.procedimientos.push(obj);
      });
    },
    getPDFOtros() {
      return (this.objectFrm = {
        Num_Doc: this.selected.Cedula,
        type: "otros",
        servicios: this.data.procedimientos,
      });
    },
    async print(pdf) {
      await axios
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
          this.selected = [];
        })
        .catch((err) => console.log(err.response));
    },
    exportar() {
      this.preload = true;
      axios
        .post(
          "/api/autorizacion/exportar/servicios/prestador",
          this.listaAutorizaciones,
          {
            responseType: "blob",
          }
        )
        .then((res) => {
          let blob = new Blob([res.data], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8",
          });
          this.preload = false;
          let url = window.URL.createObjectURL(blob);
          let a = document.createElement("a");

          a.download = "ServiciosPorGestionar";
          a.href = url;
          document.body.appendChild(a);
          a.click();
          document.body.removeChild(a);
        })
        .catch((err) => {
          (this.preload = false), console.log(err);
        });
    },

    generarExcel() {
      this.preload = true;
      axios({
        method: "post",
        url: "/api/autorizacion/exportar/visionTotal",
        responseType: "blob",
        params: this.filtros,
      })
        .then((res) => {
          let blob = new Blob([res.data], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8",
          });
          let url = window.URL.createObjectURL(blob);
          let a = document.createElement("a");
          (this.filtros.entidad = null),
            (this.filtros.fechaDesde = null),
            (this.filtros.fechaHasta = null),
            (a.download = "ConsolidadoServicios.xlsx");
          a.href = url;
          document.body.appendChild(a);
          a.click();
          document.body.removeChild(a);
          (this.preload = false), (this.modalReporte = false);
        })
        .catch((err) => {
          (this.preload = false), this.$alerError("No se puede generar");
        });
    },
    abrirDialogo() {
      this.modalReporte = true;
    },

    getSedes() {
      axios.post("/api/sedeproveedore/all").then((res) => {
        this.sedes = res.data;
      });
    },
  },
  mounted() {
    if (!this.filtersForm.month) {
      this.filtersForm.month = moment().format("M");
      this.filtersForm.year = moment().format("YYYY");
    }
    this.getSedes();
    // this.getOroderByPrestador();
  },
};
</script>
