<template>
  <div>
    <v-tabs centered color="white" icons-and-text>
      <v-tabs-slider color="primary"></v-tabs-slider>

      <v-tab href="#tab-1" color="black--text">
        Pendientes
        <v-icon color="black">done</v-icon>
      </v-tab>

      <v-tab href="#tab-2">
        Solucionados
        <v-icon color="black">done_all</v-icon>
      </v-tab>

      <v-tab-item :value="'tab-1'">
        <v-card flat>
          <v-card>
            <v-card-title>
              <v-spacer></v-spacer>
              <v-autocomplete
                :items="[
                  'Pendientes',
                  'Gestionando',
                  'Asignadas',
                  'Cierre temporal',
                  'Re Abiertos',
                  'Anulados',
                ]"
                label="Filtrar"
                single-line
                hide-details
                v-model="filter"
              >
              </v-autocomplete>
              <v-spacer></v-spacer>
              <v-autocomplete
                v-model="filtroArea_id"
                :items="allAreas"
                item-text="Nombre"
                item-value="id"
                label="Área"
                @change="getCategoriasFiltro()"
                @input="getPendientes()"
              ></v-autocomplete>
              <v-spacer></v-spacer>
              <v-autocomplete
                v-model="filtroCategoria_id"
                :items="allCategoriasFiltro"
                item-text="Nombre"
                item-value="id"
                label="Categoria"
                @input="getPendientes()"
              ></v-autocomplete>
              <v-spacer></v-spacer>
              <v-autocomplete
                v-model="filtroSede_id"
                :items="allSedes"
                item-text="nombre"
                item-value="nombre"
                label="Sede"
                @input="getPendientes()"
              ></v-autocomplete>
              <v-spacer></v-spacer>
              <v-text-field
                label="Desde"
                type="date"
                color="primary"
                v-model="form.desde"
              >
              </v-text-field>
              <v-spacer></v-spacer>
              <v-text-field
                label="Hasta"
                type="date"
                color="primary"
                v-model="form.hasta"
                @input="getPendientes()"
              >
              </v-text-field>
              <v-spacer></v-spacer>
              <v-tooltip top>
                <template v-slot:activator="{ on }">
                  <v-btn
                    fab
                    color="error"
                    small
                    v-on="on"
                    @click="clearFilter()"
                  >
                    <v-icon>delete</v-icon>
                  </v-btn>
                </template>
                <span>Quitar Filtros</span>
              </v-tooltip>
            </v-card-title>
            <v-data-table
              :loading="loading"
              :headers="headers"
              :items="allPendientes"
              :search="search"
            >
              <template v-slot:items="props">
                <td>{{ props.item.id }}</td>
                <td>{{ props.item.creado.split(".")[0] }}</td>
                <td>{{ props.item.sede }}</td>
                <td>{{ props.item.area }}</td>
                <td>{{ props.item.categoria }}</td>
                <td class="text-xs-center">
                  <v-chip
                    color="success"
                    text-color="white"
                    v-show="props.item.prioridad == 'Baja'"
                  >
                    {{ props.item.prioridad }}</v-chip
                  >
                  <v-chip
                    color="warning"
                    text-color="white"
                    v-show="props.item.prioridad == 'Media'"
                  >
                    {{ props.item.prioridad }}</v-chip
                  >
                  <v-chip
                    color="error"
                    text-color="white"
                    v-show="props.item.prioridad == 'Alta'"
                  >
                    {{ props.item.prioridad }}</v-chip
                  >
                </td>
                <td
                  class="text-xs-center"
                  v-if="props.item.tiempo_estimado >= props.item.fecha_actual"
                >
                  <v-chip
                    color="success"
                    text-color="white"
                    v-if="props.item.diferencia_dias >= 6"
                  >
                    Faltan {{ props.item.diferencia_dias }} Dias</v-chip
                  >
                  <v-chip
                    color="warning"
                    text-color="white"
                    v-if="
                      props.item.diferencia_dias < 6 &&
                      props.item.diferencia_dias > 2
                    "
                  >
                    Ojo! Faltan {{ props.item.diferencia_dias }} Dias</v-chip
                  >
                  <v-chip
                    color="error"
                    text-color="white"
                    v-if="props.item.diferencia_dias <= 2"
                  >
                    Proximo a Vencer Faltan
                    {{ props.item.diferencia_dias }} Dias</v-chip
                  >
                </td>
                <td
                  class="text-xs-center"
                  v-if="props.item.tiempo_estimado < props.item.fecha_actual"
                >
                  <v-chip color="error" text-color="white">
                    Lleva {{ props.item.diferencia_dias }} Dias Vencido</v-chip
                  >
                </td>
                <td class="text-xs-center" v-if="!props.item.tiempo_estimado">
                  <v-chip color="warning" text-color="white">
                    Sin Fecha Estimada</v-chip
                  >
                </td>
                <td>{{ props.item.asunto }}</td>
                <td>{{ props.item.estado }}</td>
                <td>
                  <v-btn
                    color="primary"
                    :disabled="loading"
                    @click="accionesPendientes(props.item), getCategorias()"
                  >
                    Acciones
                  </v-btn>
                </td>
              </template>
              <template v-slot:no-results>
                <v-alert :value="true" color="error" icon="warning">
                  Su búsqueda de "{{ search }}" no encontró resultados.
                </v-alert>
              </template>
            </v-data-table>
          </v-card>

          <v-dialog
            v-model="dialogAcciones"
            v-if="dialogAcciones"
            persistent
            width="1000"
          >
            <v-card>
              <v-toolbar flat color="primary" dark>
                <v-toolbar-title
                  >Solicitud # {{ pendientes.id }}</v-toolbar-title
                >
              </v-toolbar>
              <v-card-text>
                <v-container grid-list-md>
                  <v-layout wrap>
                    <v-flex xs6>
                      <v-text-field
                        v-model="pendientes.nombreUser"
                        readonly
                        label="NOMBRE"
                      >
                      </v-text-field>
                    </v-flex>
                    <v-flex xs6>
                      <v-text-field
                        v-model="pendientes.email"
                        readonly
                        label="EMAIL"
                      >
                      </v-text-field>
                    </v-flex>
                    <v-flex xs6>
                      <v-text-field
                        v-model="pendientes.sede"
                        readonly
                        label="SEDE"
                      >
                      </v-text-field>
                    </v-flex>
                    <v-flex xs6>
                      <v-text-field
                        v-model="pendientes.area"
                        readonly
                        label="ÁREA"
                      >
                      </v-text-field>
                    </v-flex>
                    <v-flex xs12>
                      <v-text-field
                        v-model="pendientes.asunto"
                        readonly
                        label="ASUNTO"
                      >
                      </v-text-field>
                    </v-flex>
                    <v-flex xs12>
                      <v-textarea v-model="pendientes.descripcion" readonly>
                        <template v-slot:label>
                          <div>DESCRIPCIÓN</div>
                        </template>
                      </v-textarea>
                    </v-flex>
                    <v-flex
                      xs12
                      v-for="(GesHelp, index) in pendientes.gestion_helpdesks"
                      :key="`res-${index}`"
                    >
                      <v-btn
                        v-for="(data, index2) in GesHelp.adjuntos_helpdesks"
                        :key="index2"
                      >
                        <a
                          :href="`${data.Ruta}`"
                          target="_blank"
                          style="text-decoration: none"
                        >
                          <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                          {{ index2 + 1 }}
                        </a>
                      </v-btn>
                      <v-divider class="my-2"></v-divider>
                    </v-flex>
                    <v-flex
                      xs12
                      v-for="(comenpublic, i) in comentarioPublico"
                      :key="`res1-${i}`"
                    >
                      <v-flex xs12>
                        <v-flex xs12 v-if="i < 1">
                          <v-toolbar color="primary" dark class="mb-4">
                            <v-toolbar-title
                              >comentarios al solicitante</v-toolbar-title
                            >
                          </v-toolbar>
                        </v-flex>
                        <span>
                          <strong>Categoria: </strong>
                          {{ comenpublic.categoria }}
                          <strong>Prioridad: </strong>
                          {{ comenpublic.prioridad }}
                          <strong>Fecha estimada de solucion: </strong>
                          {{ comenpublic.tiempo_estimado }}
                        </span>
                        <v-textarea v-model="comenpublic.Motivo" readonly>
                          <template v-slot:label>
                            <div>MOTIVO</div>
                          </template>
                        </v-textarea>
                        <span
                          ><strong>Nombre: </strong> {{ comenpublic.name }}
                          {{ comenpublic.apellido }}
                          <strong>Fecha: </strong>
                          {{ comenpublic.created_at.split(".")[0] }}
                          <strong v-show="comenpublic.responsable"
                            >Responsable:
                          </strong>
                          {{ comenpublic.responsable }}
                        </span>
                        <v-flex>
                          <v-btn
                            v-for="(
                              data, index3
                            ) in comenpublic.adjuntos_helpdesks"
                            :key="index3"
                          >
                            <a
                              :href="`${data.Ruta}`"
                              target="_blank"
                              style="text-decoration: none"
                            >
                              <v-icon color="dark">mdi-cloud-upload</v-icon
                              >Adjunto
                              {{ index3 + 1 }}
                            </a>
                          </v-btn>
                        </v-flex>
                        <v-divider class="my-2"></v-divider>
                      </v-flex>
                    </v-flex>
                    <v-flex
                      xs12
                      v-for="(comenReabierto, i) in comentarioReAbierto"
                      :key="`res4-${i}`"
                    >
                      <v-flex xs12>
                        <v-flex xs12 v-if="i < 1">
                          <v-toolbar color="primary" dark class="mb-4">
                            <v-toolbar-title
                              >Comentario Re Abierto</v-toolbar-title
                            >
                          </v-toolbar>
                        </v-flex>
                        <v-textarea v-model="comenReabierto.Motivo" readonly>
                          <template v-slot:label>
                            <div>MOTIVO</div>
                          </template>
                        </v-textarea>
                        <span
                          ><strong>Nombre: </strong> {{ comenReabierto.name }}
                          {{ comenReabierto.apellido }}
                          <strong>Fecha: </strong>
                          {{ comenReabierto.created_at.split(".")[0] }}
                        </span>
                        <v-flex>
                          <v-btn
                            v-for="(
                              data, index3
                            ) in comenReabierto.adjuntos_helpdesks"
                            :key="index3"
                          >
                            <a
                              :href="`${data.Ruta}`"
                              target="_blank"
                              style="text-decoration: none"
                            >
                              <v-icon color="dark">mdi-cloud-upload</v-icon
                              >Adjunto
                              {{ index3 + 1 }}
                            </a>
                          </v-btn>
                        </v-flex>
                        <v-divider class="my-2"></v-divider>
                      </v-flex>
                    </v-flex>
                    <v-flex
                      xs12
                      v-for="(comenprivate, i) in comentarioPrivado"
                      :key="`res2-${i}`"
                    >
                      <v-flex xs12>
                        <v-flex xs12 v-if="i < 1">
                          <v-toolbar color="primary" dark class="mb-4">
                            <v-toolbar-title
                              >comentarios internos</v-toolbar-title
                            >
                          </v-toolbar>
                        </v-flex>
                        <span>
                          <strong>Categoria: </strong>
                          {{ comenprivate.categoria }}
                          <strong>Prioridad: </strong>
                          {{ comenprivate.prioridad }}
                          <strong>Fecha estimada de solucion: </strong>
                          {{ comenprivate.tiempo_estimado }}
                        </span>
                        <v-textarea v-model="comenprivate.Motivo" readonly>
                          <template v-slot:label>
                            <div>MOTIVO</div>
                          </template>
                        </v-textarea>
                        <span
                          ><strong>Nombre: </strong> {{ comenprivate.name }}
                          {{ comenprivate.apellido }}
                          <strong>Fecha: </strong>
                          {{ comenprivate.created_at.split(".")[0] }}
                          <strong v-show="comenprivate.responsable"
                            >Responsable:
                          </strong>
                          {{ comenprivate.responsable }}
                        </span>
                        <v-flex>
                          <v-btn
                            v-for="(
                              data, index4
                            ) in comenprivate.adjuntos_helpdesks"
                            :key="index4"
                          >
                            <a
                              :href="`${data.Ruta}`"
                              target="_blank"
                              style="text-decoration: none"
                            >
                              <v-icon color="dark">mdi-cloud-upload</v-icon
                              >Adjunto
                              {{ index4 + 1 }}
                            </a>
                          </v-btn>
                        </v-flex>
                        <v-divider class="my-2"></v-divider>
                      </v-flex>
                    </v-flex>
                    <v-flex
                      xs12
                      v-for="(comenanulado, i) in comentarioAnulado"
                      :key="`res3-${i}`"
                    >
                      <v-flex xs12>
                        <v-flex xs12 v-if="i < 1">
                          <v-toolbar color="primary" dark class="mb-4">
                            <v-toolbar-title>Helpdesk Anulado</v-toolbar-title>
                          </v-toolbar>
                        </v-flex>
                        <v-textarea v-model="comenanulado.Motivo" readonly>
                          <template v-slot:label>
                            <div>MOTIVO</div>
                          </template>
                        </v-textarea>
                        <span
                          ><strong>Nombre: </strong> {{ comenanulado.name }}
                          {{ comenanulado.apellido }}
                          <strong>Fecha: </strong>
                          {{ comenanulado.created_at.split(".")[0] }}
                          <strong v-show="comenanulado.responsable"
                            >Responsable:
                          </strong>
                          {{ comenanulado.responsable }}
                        </span>
                        <v-flex>
                          <v-btn
                            v-for="(
                              data, index4
                            ) in comenanulado.adjuntos_helpdesks"
                            :key="index4"
                          >
                            <a
                              :href="`${data.Ruta}`"
                              target="_blank"
                              style="text-decoration: none"
                            >
                              <v-icon color="dark">mdi-cloud-upload</v-icon
                              >Adjunto
                              {{ index4 + 1 }}
                            </a>
                          </v-btn>
                        </v-flex>
                        <v-divider class="my-2"></v-divider>
                      </v-flex>
                    </v-flex>
                    <v-flex
                      xs12
                      v-for="(HelpAsignado, index5) in showAsignados"
                      :key="`res4-${index5}`"
                    >
                      <v-flex
                        xs12
                        v-for="(
                          comentAsignado, index6
                        ) in HelpAsignado.gestion_helpdesks"
                        :key="`res7-${index6}`"
                      >
                        <v-flex xs12 v-if="index6 < 1">
                          <v-toolbar color="primary" dark class="mb-4">
                            <v-toolbar-title
                              >{{ comentAsignado.Nombretipo }}
                            </v-toolbar-title>
                          </v-toolbar>
                        </v-flex>
                        <v-textarea v-model="comentAsignado.Motivo" readonly>
                          <template v-slot:label>
                            <div>MOTIVO</div>
                          </template>
                        </v-textarea>
                        <span
                          ><strong>Nombre: </strong> {{ comentAsignado.Nombre }}
                          {{ comentAsignado.Apellido }}
                          <strong>Fecha: </strong>
                          {{ comentAsignado.Faccion.split(".")[0] }}</span
                        >
                        <v-flex>
                          <v-btn
                            v-for="(
                              dataSolucionado, index6
                            ) in comentAsignado.adjuntos_helpdesks"
                            :key="index6"
                          >
                            <a
                              :href="`${dataSolucionado.Ruta}`"
                              target="_blank"
                              style="text-decoration: none"
                            >
                              <v-icon color="dark">mdi-cloud-upload</v-icon
                              >Adjunto
                              {{ index6 + 1 }}
                            </a>
                          </v-btn>
                          <v-divider class="my-2"></v-divider>
                        </v-flex>
                      </v-flex>
                      <v-flex xs12 v-if="HelpAsignado.permisohelpdesks != ''">
                        <v-subheader>ASIGNADO A</v-subheader>
                        <v-item-group multiple>
                          <v-item
                            v-for="(
                              permission, indexp
                            ) in HelpAsignado.permisohelpdesks"
                            :key="indexp"
                          >
                            <v-chip label>
                              {{ permission.nombrePermiso }}
                            </v-chip>
                          </v-item>
                        </v-item-group>
                        <v-divider class="my-2"></v-divider>
                      </v-flex>
                    </v-flex>
                    <v-flex
                      xs12
                      v-for="(respuestaHelp, indexr) in respuestas"
                      :key="indexr"
                    >
                      <v-flex xs12>
                        <v-flex xs12 v-if="indexr < 1">
                          <v-toolbar color="primary" dark class="mb-4">
                            <v-toolbar-title>Respuesta</v-toolbar-title>
                          </v-toolbar>
                        </v-flex>
                        <v-textarea v-model="respuestaHelp.Motivo" readonly>
                          <template v-slot:label>
                            <div>MOTIVO</div>
                          </template>
                        </v-textarea>
                        <span
                          ><strong>Nombre: </strong> {{ respuestaHelp.name }}
                          {{ respuestaHelp.apellido }}
                          <strong>Fecha: </strong>
                          {{ respuestaHelp.created_at.split(".")[0] }}
                          <strong v-show="respuestaHelp.responsable"
                            >Responsable:
                          </strong>
                          {{ respuestaHelp.responsable }}
                        </span>
                        <v-flex>
                          <v-btn
                            v-for="(
                              dataRespuesta, index8
                            ) in respuestaHelp.adjuntos_helpdesks"
                            :key="index8"
                          >
                            <a
                              :href="`${dataRespuesta.Ruta}`"
                              target="_blank"
                              style="text-decoration: none"
                            >
                              <v-icon color="dark">mdi-cloud-upload</v-icon
                              >Adjunto
                              {{ index8 + 1 }}
                            </a>
                          </v-btn>
                        </v-flex>
                        <v-divider class="my-2"></v-divider>
                      </v-flex>
                    </v-flex>
                    <v-flex xs12>
                      <v-toolbar color="primary" dark class="mb-4">
                        <v-toolbar-title>Acciones</v-toolbar-title>
                      </v-toolbar>
                    </v-flex>
                    <v-flex xs4>
                      <v-select
                        v-model="accion"
                        :items="estadoAcciones"
                        label="ACCIÓN"
                      >
                      </v-select>
                    </v-flex>
                    <v-flex xs8 />
                    <v-flex xs12 v-show="activarCampos(['Asignar'])">
                      <v-autocomplete
                        v-model="responsable"
                        :items="permisos"
                        item-text="name"
                        item-value="id"
                        chips
                        label="Responsable"
                        multiple
                      >
                        <template v-slot:selection="data">
                          <v-chip
                            :selected="data.selected"
                            close
                            class="chip--select-multi"
                            @input="remove_responsable(data.item.id)"
                          >
                            {{ data.item.name }}
                          </v-chip>
                        </template>
                        <template v-slot:item="data">
                          <template v-if="typeof data.item.name !== 'object'">
                            <v-list-tile-content v-text="data.item.name">
                            </v-list-tile-content>
                          </template>
                          <template v-else>
                            <v-list-tile-content>
                              <v-list-tile-title
                                v-html="data.item"
                              ></v-list-tile-title>
                              <v-list-tile-sub-title v-html="data.item">
                              </v-list-tile-sub-title>
                            </v-list-tile-content>
                          </template>
                        </template>
                      </v-autocomplete>
                    </v-flex>
                    <v-flex xs4 v-show="activarCampos(['Re asignar'])">
                      <v-autocomplete
                        v-model="allAreas.id"
                        :items="allAreas"
                        item-text="Nombre"
                        item-value="id"
                        label="Área"
                      ></v-autocomplete>
                    </v-flex>
                    <v-flex
                      xs6
                      v-show="
                        activarCampos([
                          'Solucionar',
                          'Asignar',
                          'Comentarios al Solicitante',
                          'Comentarios Internos',
                        ])
                      "
                    >
                      <v-autocomplete
                        v-model="allCategorias.id"
                        :items="allCategorias"
                        item-text="Nombre"
                        item-value="id"
                        label="Categoria"
                        @input="getActividades"
                      ></v-autocomplete>
                    </v-flex>
                    <v-flex
                      xs6
                      v-show="
                        activarCampos([
                          'Solucionar',
                          'Asignar',
                          'Comentarios al Solicitante',
                          'Comentarios Internos',
                        ])
                      "
                    >
                      <v-autocomplete
                        v-model="allActividades.id"
                        :items="allActividades"
                        item-text="Nombre"
                        item-value="id"
                        label="Actividad"
                      ></v-autocomplete>
                    </v-flex>
                    <v-flex
                      xs6
                      v-show="
                        activarCampos([
                          'Solucionar',
                          'Asignar',
                          'Comentarios al Solicitante',
                          'Comentarios Internos',
                        ])
                      "
                    >
                      <v-select
                        :items="['Solicitud', 'Incidente']"
                        v-model="tipo_reque"
                        @change="info_requerimiento"
                        label="Tipo de requerimiento"
                      ></v-select>
                    </v-flex>
                    <v-flex
                      xs6
                      v-show="
                        activarCampos([
                          'Solucionar',
                          'Asignar',
                          'Comentarios al Solicitante',
                          'Comentarios Internos',
                        ])
                      "
                    >
                      <v-select
                        :items="['Alta', 'Media', 'Baja']"
                        v-model="prioridad"
                        label="Prioridad"
                      ></v-select>
                    </v-flex>
                    <v-flex
                      xs12
                      v-show="
                        activarCampos([
                          'Solucionar',
                          'Asignar',
                          'Comentarios al Solicitante',
                          'Comentarios Internos',
                        ])
                      "
                    >
                      <v-text-field
                        type="date"
                        v-model="tiempo_estimado"
                        label="Fecha Estimada Solucion"
                      ></v-text-field>
                    </v-flex>
                    <v-flex
                      xs4
                      v-show="
                        activarCampos([
                          'Comentarios al Solicitante',
                          'Comentarios Internos',
                        ])
                      "
                    >
                      <v-switch
                        v-if="pendientes.estado == 'Pendiente'"
                        v-model="switch1"
                        color="success"
                        label="Esta gestionando?"
                      >
                      </v-switch>
                    </v-flex>
                    <v-flex xs12>
                      <v-textarea v-model="motivohelp">
                        <template v-slot:label>
                          <div>MOTIVO</div>
                        </template>
                      </v-textarea>
                    </v-flex>
                    <v-flex xs12 v-show="accion != 'Re asignar'">
                      <input
                        id="adjuntos"
                        multiple
                        ref="adjuntos"
                        type="file"
                      />
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-card-text>
              <v-divider></v-divider>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="error" @click="closeAcciones()"> Cerrar </v-btn>
                <v-btn color="success" @click="accionhelp(accion)">
                  Guardar
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-card>
      </v-tab-item>

      <v-tab-item :value="'tab-2'">
        <v-card flat>
          <v-card>
            <v-card-title>
              <v-spacer></v-spacer>
              <v-autocomplete
                v-model="filtroArea_id"
                :items="allAreas"
                item-text="Nombre"
                item-value="id"
                label="Área"
                @change="getCategoriasFiltro()"
                @input="getSolucionados()"
              ></v-autocomplete>
              <v-spacer></v-spacer>
              <v-autocomplete
                v-model="filtroCategoria_id"
                :items="allCategoriasFiltro"
                item-text="Nombre"
                item-value="id"
                label="Categoria"
                @input="getSolucionados()"
              ></v-autocomplete>
              <v-spacer></v-spacer>
              <v-autocomplete
                v-model="filtroSede_id"
                :items="allSedes"
                item-text="nombre"
                item-value="nombre"
                label="Sede"
                @input="getSolucionados()"
              ></v-autocomplete>
              <v-spacer></v-spacer>
              <v-text-field
                label="Desde"
                type="date"
                color="primary"
                v-model="form.desde"
              >
              </v-text-field>
              <v-spacer></v-spacer>
              <v-text-field
                label="Hasta"
                type="date"
                color="primary"
                v-model="form.hasta"
                @input="getSolucionados()"
              >
              </v-text-field>
              <v-spacer></v-spacer>
              <v-tooltip top>
                <template v-slot:activator="{ on }">
                  <v-btn
                    fab
                    color="error"
                    small
                    v-on="on"
                    @click="clearFilter()"
                  >
                    <v-icon>delete</v-icon>
                  </v-btn>
                </template>
                <span>Quitar Filtros</span>
              </v-tooltip>
            </v-card-title>

            <v-data-table
              :headers="headersolucionados"
              :loading="loading"
              :items="allSolucionados"
              :search="search1"
            >
              <template v-slot:items="props">
                <td>{{ props.item.id }}</td>
                <td>{{ props.item.creado.split(".")[0] }}</td>
                <td>{{ props.item.sede }}</td>
                <td>{{ props.item.area }}</td>
                <td>{{ props.item.categoria }}</td>
                <td>
                  <v-chip
                    color="success"
                    text-color="white"
                    v-show="props.item.prioridad == 'Baja'"
                  >
                    {{ props.item.prioridad }}</v-chip
                  >
                  <v-chip
                    color="warning"
                    text-color="white"
                    v-show="props.item.prioridad == 'Media'"
                  >
                    {{ props.item.prioridad }}</v-chip
                  >
                  <v-chip
                    color="error"
                    text-color="white"
                    v-show="props.item.prioridad == 'Alta'"
                  >
                    {{ props.item.prioridad }}</v-chip
                  >
                </td>
                <td>{{ props.item.asunto }}</td>
                <td>{{ props.item.estado }}</td>
                <td>
                  <v-btn
                    color="primary"
                    :disabled="loading"
                    @click="accionesSolucionados(props.item)"
                    >Ver</v-btn
                  >
                </td>
              </template>
              <template v-slot:no-results>
                <v-alert :value="true" color="error" icon="warning">
                  Su búsqueda de "{{ search1 }}" no encontró resultados.
                </v-alert>
              </template>
            </v-data-table>
          </v-card>

          <v-dialog
            v-model="dialogSolucionado"
            v-if="dialogSolucionado"
            width="1000"
          >
            <v-card>
              <v-card-text>
                <v-container grid-list-md>
                  <v-layout wrap>
                    <v-flex xs12>
                      <v-toolbar color="primary" dark class="mb-3">
                        <v-toolbar-title
                          >Solicitud # {{ solucionados.id }}</v-toolbar-title
                        >
                      </v-toolbar>
                    </v-flex>
                    <v-flex xs6>
                      <v-text-field
                        v-model="solucionados.nombreUser"
                        readonly
                        label="NOMBRE"
                      >
                      </v-text-field>
                    </v-flex>
                    <v-flex xs6>
                      <v-text-field
                        v-model="solucionados.email"
                        readonly
                        label="EMAIL"
                      >
                      </v-text-field>
                    </v-flex>
                    <v-flex xs6>
                      <v-text-field
                        v-model="solucionados.sede"
                        readonly
                        label="SEDE"
                      >
                      </v-text-field>
                    </v-flex>
                    <v-flex xs6>
                      <v-text-field
                        v-model="solucionados.area"
                        readonly
                        label="ÁREA"
                      >
                      </v-text-field>
                    </v-flex>
                    <v-flex xs6>
                      <v-text-field
                        v-if="solucionados.categoria"
                        v-model="solucionados.categoria"
                        readonly
                        label="CATEGORIA"
                      >
                      </v-text-field>
                    </v-flex>
                    <v-flex xs6>
                      <v-text-field
                        v-if="solucionados.actividad"
                        v-model="solucionados.actividad"
                        readonly
                        label="ACTIVIDAD"
                      >
                      </v-text-field>
                    </v-flex>
                    <v-flex xs12>
                      <v-text-field
                        v-model="solucionados.asunto"
                        readonly
                        label="ASUNTO"
                      >
                      </v-text-field>
                    </v-flex>
                    <v-flex xs12>
                      <v-textarea v-model="solucionados.descripcion" readonly>
                        <template v-slot:label>
                          <div>DESCRIPCIÓN</div>
                        </template>
                      </v-textarea>
                    </v-flex>
                    <v-flex
                      xs12
                      v-for="(
                        GesHelp, index3
                      ) in solucionados.gestion_helpdesks"
                      :key="`res5-${index3}`"
                    >
                      <v-btn
                        v-for="(data, index2) in GesHelp.adjuntos_helpdesks"
                        :key="index2"
                      >
                        <a
                          :href="`${data.Ruta}`"
                          target="_blank"
                          style="text-decoration: none"
                        >
                          <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                          {{ index2 + 1 }}
                        </a>
                      </v-btn>
                      <v-divider class="my-2" v-if="index3 < 1"></v-divider>
                    </v-flex>
                    <v-flex
                      xs12
                      v-for="(comenpublic, i) in comentarioPublico"
                      :key="`res1-${i}`"
                    >
                      <v-flex xs12>
                        <v-flex xs12 v-if="i < 1">
                          <v-toolbar color="primary" dark class="mb-4">
                            <v-toolbar-title>Comentario</v-toolbar-title>
                          </v-toolbar>
                        </v-flex>
                        <v-textarea v-model="comenpublic.Motivo" readonly>
                          <template v-slot:label>
                            <div>MOTIVO</div>
                          </template>
                        </v-textarea>
                        <span
                          ><strong>Nombre: </strong> {{ comenpublic.name }}
                          {{ comenpublic.apellido }}
                          <strong>Fecha: </strong>
                          {{ comenpublic.created_at.split(".")[0] }}
                          <strong v-show="comenpublic.responsable"
                            >Responsable:
                          </strong>
                          {{ comenpublic.responsable }}
                        </span>
                        <v-flex>
                          <v-btn
                            v-for="(
                              data, index3
                            ) in comenpublic.adjuntos_helpdesks"
                            :key="index3"
                          >
                            <a
                              :href="`${data.Ruta}`"
                              target="_blank"
                              style="text-decoration: none"
                            >
                              <v-icon color="dark">mdi-cloud-upload</v-icon
                              >Adjunto
                              {{ index3 + 1 }}
                            </a>
                          </v-btn>
                        </v-flex>
                        <v-divider class="my-2"></v-divider>
                      </v-flex>
                    </v-flex>
                    <v-flex
                      xs12
                      v-for="(comenprivate, i) in comentarioPrivado"
                      :key="`res2-${i}`"
                    >
                      <v-flex xs12>
                        <v-flex xs12 v-if="i < 1">
                          <v-toolbar color="primary" dark class="mb-4">
                            <v-toolbar-title
                              >Comentario privado</v-toolbar-title
                            >
                          </v-toolbar>
                        </v-flex>
                        <v-textarea v-model="comenprivate.Motivo" readonly>
                          <template v-slot:label>
                            <div>MOTIVO</div>
                          </template>
                        </v-textarea>
                        <span
                          ><strong>Nombre: </strong> {{ comenprivate.name }}
                          {{ comenprivate.apellido }}
                          <strong>Fecha: </strong>
                          {{ comenprivate.created_at.split(".")[0] }}
                          <strong v-show="comenprivate.responsable"
                            >Responsable:
                          </strong>
                          {{ comenprivate.responsable }}
                        </span>
                        <v-flex>
                          <v-btn
                            v-for="(
                              data, index8
                            ) in comenprivate.adjuntos_helpdesks"
                            :key="index8"
                          >
                            <a
                              :href="`${data.Ruta}`"
                              target="_blank"
                              style="text-decoration: none"
                            >
                              <v-icon color="dark">mdi-cloud-upload</v-icon
                              >Adjunto
                              {{ index8 + 1 }}
                            </a>
                          </v-btn>
                        </v-flex>
                        <v-divider class="my-2"></v-divider>
                      </v-flex>
                    </v-flex>
                    <v-flex
                      xs12
                      v-for="(HelpAsignado, index5) in showAsignados"
                      :key="`res4-${index5}`"
                    >
                      <v-flex
                        xs12
                        v-for="(
                          comentAsignado, index6
                        ) in HelpAsignado.gestion_helpdesks"
                        :key="`res7-${index6}`"
                      >
                        <v-flex xs12 v-if="index6 < 1">
                          <v-toolbar color="primary" dark class="mb-4">
                            <v-toolbar-title
                              >{{ comentAsignado.Nombretipo }}
                            </v-toolbar-title>
                          </v-toolbar>
                        </v-flex>
                        <v-textarea v-model="comentAsignado.Motivo" readonly>
                          <template v-slot:label>
                            <div>MOTIVO</div>
                          </template>
                        </v-textarea>
                        <span
                          ><strong>Nombre: </strong> {{ comentAsignado.Nombre }}
                          {{ comentAsignado.Apellido }}
                          <strong>Fecha: </strong>
                          {{ comentAsignado.Faccion.split(".")[0] }}</span
                        >
                        <v-flex>
                          <v-btn
                            v-for="(
                              dataSolucionado, index6
                            ) in comentAsignado.adjuntos_helpdesks"
                            :key="index6"
                          >
                            <a
                              :href="`${dataSolucionado.Ruta}`"
                              target="_blank"
                              style="text-decoration: none"
                            >
                              <v-icon color="dark">mdi-cloud-upload</v-icon
                              >Adjunto
                              {{ index6 + 1 }}
                            </a>
                          </v-btn>
                          <v-divider class="my-2"></v-divider>
                        </v-flex>
                      </v-flex>
                      <v-flex xs12 v-if="HelpAsignado.permisohelpdesks != ''">
                        <v-subheader>ASIGNADO A</v-subheader>
                        <v-item-group multiple>
                          <v-item
                            v-for="(
                              permission, indexp
                            ) in HelpAsignado.permisohelpdesks"
                            :key="indexp"
                          >
                            <v-chip label>
                              {{ permission.nombrePermiso }}
                            </v-chip>
                          </v-item>
                        </v-item-group>
                        <v-divider class="my-2"></v-divider>
                      </v-flex>
                    </v-flex>
                    <v-flex
                      xs12
                      v-for="(respuestaHelp, indexr) in respuestas"
                      :key="indexr"
                    >
                      <v-flex xs12>
                        <v-flex xs12 v-if="indexr < 1">
                          <v-toolbar color="primary" dark class="mb-4">
                            <v-toolbar-title>Respuesta</v-toolbar-title>
                          </v-toolbar>
                        </v-flex>
                        <v-textarea v-model="respuestaHelp.Motivo" readonly>
                          <template v-slot:label>
                            <div>MOTIVO</div>
                          </template>
                        </v-textarea>
                        <span
                          ><strong>Nombre: </strong> {{ respuestaHelp.name }}
                          {{ respuestaHelp.apellido }}
                          <strong>Fecha: </strong>
                          {{ respuestaHelp.created_at.split(".")[0] }}
                          <strong v-show="respuestaHelp.responsable"
                            >Responsable:
                          </strong>
                          {{ respuestaHelp.responsable }}
                        </span>
                        <v-flex>
                          <v-btn
                            v-for="(
                              dataRespuesta, index8
                            ) in respuestaHelp.adjuntos_helpdesks"
                            :key="index8"
                          >
                            <a
                              :href="`${dataRespuesta.Ruta}`"
                              target="_blank"
                              style="text-decoration: none"
                            >
                              <v-icon color="dark">mdi-cloud-upload</v-icon
                              >Adjunto
                              {{ index8 + 1 }}
                            </a>
                          </v-btn>
                        </v-flex>
                        <v-divider class="my-2"></v-divider>
                      </v-flex>
                    </v-flex>
                    <v-flex
                      xs12
                      v-for="(gesHelpSolucion, index4) in solucion"
                      :key="`res8-${index4}`"
                    >
                      <v-flex xs12>
                        <v-flex>
                          <v-toolbar color="primary" dark class="mb-4">
                            <v-toolbar-title
                              >{{ gesHelpSolucion.descripcionTipo }}
                            </v-toolbar-title>
                          </v-toolbar>
                        </v-flex>
                        <v-textarea v-model="gesHelpSolucion.Motivo" readonly>
                          <template v-slot:label>
                            <div>MOTIVO</div>
                          </template>
                        </v-textarea>
                        <span
                          >Nombre: {{ gesHelpSolucion.name }}
                          {{ gesHelpSolucion.apellido }}
                          Fecha:
                          {{ gesHelpSolucion.created_at.split(" ")[0] }}</span
                        >
                      </v-flex>
                      <v-flex>
                        <v-btn
                          v-for="(
                            dataSolucionado, index6
                          ) in gesHelpSolucion.adjuntos_helpdesks"
                          :key="index6"
                        >
                          <a
                            :href="`${dataSolucionado.Ruta}`"
                            target="_blank"
                            style="text-decoration: none"
                          >
                            <v-icon color="dark">mdi-cloud-upload</v-icon
                            >Adjunto
                            {{ index6 + 1 }}
                          </a>
                        </v-btn>
                      </v-flex>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-card-text>
            </v-card>
          </v-dialog>
        </v-card>
      </v-tab-item>
    </v-tabs>

    <v-dialog v-model="preload" persistent width="300">
      <v-card color="primary" dark>
        <v-card-text>
          Estamos procesando su información
          <v-progress-linear indeterminate color="white" class="mb-0">
          </v-progress-linear>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import Swal from "sweetalert2";
export default {
  data: () => ({
    loading: false,
    search: "",
    search1: "",
    allPendientes: [],
    headersolucionados: [
      {
        text: "id",
        align: "left",
        value: "id",
      },
      {
        text: "Fecha",
        align: "left",
        value: "creado",
      },
      {
        text: "Sede",
        align: "left",
        value: "sede",
      },
      {
        text: "Área",
        align: "left",
        value: "area",
      },
      {
        text: "Categoria",
        align: "left",
        value: "categoria",
      },
      {
        text: "Prioridad",
        align: "left",
        value: "prioridad",
      },
      {
        text: "Asunto",
        align: "left",
        value: "asunto",
      },
      {
        text: "Estado",
        align: "left",
        value: "estado",
      },
      {
        text: "Historial",
        align: "left",
      },
    ],
    headers: [
      {
        text: "id",
        align: "left",
        value: "id",
      },
      {
        text: "Fecha",
        align: "left",
        value: "",
      },
      {
        text: "Sede",
        align: "left",
        value: "sede",
      },
      {
        text: "Área",
        align: "left",
        value: "area",
      },
      {
        text: "Categoria",
        align: "left",
        value: "categoria",
      },
      {
        text: "Prioridad",
        align: "center",
        value: "prioridad",
      },
      {
        text: "Tiempo",
        align: "center",
        value: "diferencia_dias",
      },
      {
        text: "Asunto",
        align: "left",
        value: "asunto",
      },
      {
        text: "Estado",
        align: "left",
        value: "",
      },
      {
        text: "",
        align: "left",
      },
    ],
    dialogAcciones: false,
    pendientes: [],
    motivohelp: "",
    adjuntos: [],
    data: {},
    allSolucionados: [],
    dialogSolucionado: false,
    solucionados: [],
    accion: [],
    allCategorias: [],
    allActividades: [],
    tipo_reque: "",
    prioridad: "",
    tiempo_estimado: "",
    comentarioPublico: [],
    comentarioPrivado: [],
    comentarioAnulado: [],
    comentarioReAbierto: [],
    helpdesk_id: "",
    allAreas: [],
    permisos: [],
    responsable: [],
    filter: "Pendientes",
    acciones: [
      "Comentarios al Solicitante",
      "Comentarios Internos",
      "Solucionar",
      "Anular",
      "Asignar",
      "Re asignar",
    ],
    showAsignados: [],
    permisosHelpdesk: [],
    solucion: [],
    switch1: false,
    respuestas: [],
    preload: false,
    filtroArea_id: null,
    filtroCategoria_id: null,
    filtroSede_id: null,
    allCategoriasFiltro: [],
    allSedes: [],
    form: {
      desde: null,
      hasta: null,
    },
  }),
  mounted() {
    this.getPermisos();
    this.getAreas();
    this.getSedes();
    this.getCategoriasFiltro();
    this.urlemail();
  },
  computed: {
    estadoAcciones() {
      if (this.pendientes.estado == "Asignado") {
        let forDeletion = ["Solucionar", "Anular", "Re asignar"];
        let array = this.acciones.filter((item) => !forDeletion.includes(item));
        return array;
      }
      if (this.pendientes.estado == "Gestionando") {
        let forDeletion = ["Re asignar"];
        let array = this.acciones.filter((item) => !forDeletion.includes(item));
        return array;
      }
      if (this.pendientes.estado == "Cerrado temporal") {
        let forDeletion = [
          "Comentarios al Solicitante",
          "Comentarios Internos",
          "Anular",
          "Re asignar",
        ];
        let array = this.acciones.filter((item) => !forDeletion.includes(item));
        return array;
      }
      return this.acciones;
    },
  },
  methods: {
    urlemail() {
      let url = window.location.href;
      let id_pendiente = url.split("solicitudarea/");
      let id_cierreTemporal = url.split("solicitudarea/c");
      if (id_cierreTemporal[1] !== undefined) {
        this.filter = "Cierre temporal";
        this.getPendientes();
        this.search = id_cierreTemporal[1];
      } else {
        this.search = id_pendiente[1];
      }
    },
    getSedes() {
      axios.get("/api/helpdesk/sedes").then((res) => {
        this.allSedes = res.data;
      });
    },
    getAreas() {
      axios.get("/api/helpdesk/getarea").then((res) => {
        this.allAreas = res.data;
      });
    },
    getCategoriasFiltro() {
      axios
        .get(`/api/helpdesk/getcategoria/${this.filtroArea_id}`)
        .then((res) => {
          this.allCategoriasFiltro = res.data;
        });
    },

    getCategorias() {
      if (this.pendientes.area_id) {
        axios
          .get(`/api/helpdesk/getcategoria/${this.pendientes.area_id}`)
          .then((res) => {
            this.allCategorias = res.data;
          });
      }
    },
    getActividades() {
      if (this.allCategorias.id) {
        axios
          .get(`/api/helpdesk/getactividad/${this.allCategorias.id}`)
          .then((res) => {
            this.allActividades = res.data;
          });
      }
    },

    getPendientes() {
      this.loading = true;
      axios
        .post("/api/helpdesk/pendientesArea", {
          estado: this.filter,
          area: this.filtroArea_id,
          categoria: this.filtroCategoria_id,
          sede: this.filtroSede_id,
          desde: this.form.desde,
          hasta: this.form.hasta,
        })
        .then((res) => {
          this.loading = false;
          this.allPendientes = res.data;
        })
        .catch((err) => {
          this.loading = false;
        });
    },

    getSolucionados() {
      this.loading = true;
      axios
      .post("/api/helpdesk/solucionadosArea", {
          area: this.filtroArea_id,
          categoria: this.filtroCategoria_id,
          sede: this.filtroSede_id,
          desde: this.form.desde,
          hasta: this.form.hasta,
        })
        .then((res) => {
          this.loading = false;
          this.allSolucionados = res.data;
        })
        .catch((err) => {
          this.loading = false;
        });
    },
    getPermisos() {
      axios.get("/api/helpdesk/permisos").then((res) => {
        this.permisos = res.data;
      });
    },
    permisoAsignadoHelp() {
      axios
        .post(`/api/helpdesk/permisoAsignadoHelp`, {
          helpdesk_id: this.helpdesk_id,
        })
        .then((res) => {
          this.permisosHelpdesk = res.data;
        });
    },
    remove_responsable(item) {
      this.responsable.splice(this.responsable.indexOf(item), 1);
      this.responsable = [...this.responsable];
    },
    accionesSolucionados(solucionado) {
      this.dialogSolucionado = true;
      this.solucionados = solucionado;
      this.helpdesk_id = this.solucionados.id;
      this.comentariosPublico();
      this.comentariosPrivado();
      this.showSolucion();
      this.showAsignadosinArea();
      this.showRespuestas();
    },
    accionesPendientes(pendiente) {
      this.getAreas();
      this.dialogAcciones = true;
      this.pendientes = pendiente;
      console.log(this.pendientes);
      this.helpdesk_id = this.pendientes.id;
      this.allCategorias.id = parseInt(this.pendientes.id_categoria);
      this.prioridad = this.pendientes.prioridad;
      this.tiempo_estimado = this.pendientes.tiempo_estimado;
      this.comentariosPublico();
      this.comentariosPrivado();
      this.showAsignadosinArea();
      this.permisoAsignadoHelp();
      this.comentariosAnulados();
      this.comentariosReabierto();
      this.showRespuestas();
    },
    closeAcciones() {
      this.dialogAcciones = false;
      this.motivohelp = "";
      this.$refs.adjuntos.value = "";
      this.accion = "";
      this.allCategorias.id = "";
      this.allActividades.id = "";
      this.tipo_reque = "";
      this.prioridad = "";
      (this.responsable = []), (this.search = "");
    },
    accionhelp(msg) {
      if (
        (msg == "Comentarios al Solicitante") |
        (msg == "Comentarios Internos")
      ) {
        this.comentar();
      } else if (msg == "Solucionar") {
        this.solucionar();
      } else if (msg == "Anular") {
        this.anular();
      } else if (msg == "Asignar") {
        this.asignar();
      } else if (msg == "Re asignar") {
        this.reasignar();
      }
    },
    comentar() {
      if (!this.pendientes.categoria) {
        if (this.allCategorias.id == undefined) {
          this.$alerError("Debe ingresar una categoria!");
          return;
        }
        if (this.tipo_reque == "") {
          this.$alerError("Debe ingresar un tipo de requerimiento!");
          return;
        }
        if (this.prioridad == "") {
          this.$alerError("Debe ingresar una prioridad!");
          return;
        }
        if (this.tiempo_estimado == "") {
          this.$alerError("Debe ingresar una fecha estimada de solucion!");
          return;
        }
      }
      this.preload = true;
      this.data.adjuntos = this.$refs.adjuntos.files;
      let formData = new FormData();
      formData.append("helpdesk_id", this.pendientes.id);
      formData.append("motivo", this.motivohelp);
      formData.append("accion", this.accion);
      formData.append("gestionandoArea", this.switch1);
      formData.append("desde", "solicitudarea");
      formData.append("categoria", this.allCategorias.id);
      formData.append("actividad", this.allActividades.id);
      formData.append("tipo_requerimiento", this.tipo_reque);
      formData.append("prioridad", this.prioridad);
      formData.append("tiempo_estimado", this.tiempo_estimado);
      for (let i = 0; i < this.data.adjuntos.length; i++) {
        formData.append(`adjuntos[]`, this.data.adjuntos[i]);
      }
      axios
        .post(`/api/helpdesk/comentar`, formData)
        .then((res) => {
          this.preload = false;
          this.getPendientes();
          this.getSolucionados();
          this.closeAcciones();
          swal({
            title: "¡Ha comentado la solicitud con exito!",
            text: ` `,
            timer: 2000,
            icon: "success",
            buttons: false,
          });
        })
        .catch((err) => {
          this.preload = false;
          let msg = "";
          for (const pro in err.response.data) {
            if (msg) msg += `${err.response.data[pro]}`;
            else msg = err.response.data[pro];
          }
          swal({
            title: msg,
            text: " ",
            type: "error",
            icon: "error",
          });
        });
    },
    showRespuestas() {
      axios
        .post(`/api/helpdesk/showRespuestas`, {
          helpdesk_id: this.helpdesk_id,
        })
        .then((res) => {
          this.respuestas = res.data;
        });
    },
    comentariosPublico() {
      axios
        .post(`/api/helpdesk/showcomentariosPublicos`, {
          helpdesk_id: this.helpdesk_id,
        })
        .then((res) => {
          this.comentarioPublico = res.data;
        });
    },
    comentariosPrivado() {
      axios
        .post(`/api/helpdesk/showcomentariosPrivados`, {
          helpdesk_id: this.helpdesk_id,
        })
        .then((res) => {
          this.comentarioPrivado = res.data;
        });
    },

    comentariosAnulados() {
      axios
        .post(`/api/helpdesk/comentarioAnulado`, {
          helpdesk_id: this.helpdesk_id,
        })
        .then((res) => {
          this.comentarioAnulado = res.data;
        });
    },

    comentariosReabierto() {
      axios
        .post(`/api/helpdesk/comentariosReabierto`, {
          helpdesk_id: this.helpdesk_id,
        })
        .then((res) => {
          this.comentarioReAbierto = res.data;
        });
    },

    solucionar() {
      if (!this.pendientes.categoria) {
        if (this.allCategorias.id == undefined) {
          this.$alerError("Debe ingresar una categoria!");
          return;
        }
        if (this.tipo_reque == "") {
          this.$alerError("Debe ingresar un tipo de requerimiento!");
          return;
        }
        if (this.prioridad == "") {
          this.$alerError("Debe ingresar una prioridad!");
          return;
        }
        if (this.tiempo_estimado == "") {
          this.$alerError("Debe ingresar una fecha estimada de solucion!");
          return;
        }
      }
      this.preload = true;
      this.data.adjuntos = this.$refs.adjuntos.files;
      let formData = new FormData();
      formData.append("helpdesk_id", this.pendientes.id);
      formData.append("categoria", this.allCategorias.id);
      formData.append("actividad", this.allActividades.id);
      formData.append("tipo_requerimiento", this.tipo_reque);
      formData.append("prioridad", this.prioridad);
      formData.append("tiempo_estimado", this.tiempo_estimado);
      formData.append("motivo", this.motivohelp);
      formData.append("accion", this.accion);
      for (let i = 0; i < this.data.adjuntos.length; i++) {
        formData.append(`adjuntos[]`, this.data.adjuntos[i]);
      }
      axios
        .post(`/api/helpdesk/solucion/${this.pendientes.id}`, formData)
        .then((res) => {
          this.preload = false;
          this.getPendientes();
          this.getSolucionados();
          this.closeAcciones();
          swal({
            title: "¡Ha dado solución a la solicitud con exito!",
            text: ` `,
            timer: 2000,
            icon: "success",
            buttons: false,
          });
        })
        .catch((err) => {
          this.preload = false;
          let msg = "";
          for (const pro in err.response.data) {
            if (msg) msg += `${err.response.data[pro]}`;
            else msg = err.response.data[pro];
          }
          swal({
            title: msg,
            text: " ",
            type: "error",
            icon: "error",
          });
        });
    },
    showSolucion() {
      axios
        .post(`/api/helpdesk/showSolucion`, {
          helpdesk_id: this.helpdesk_id,
        })
        .then((res) => {
          this.solucion = res.data;
        });
    },
    anular() {
      this.data.adjuntos = this.$refs.adjuntos.files;
      let formData = new FormData();
      formData.append("helpdesk_id", this.pendientes.id);
      formData.append("motivo", this.motivohelp);
      formData.append("accion", this.accion);
      for (let i = 0; i < this.data.adjuntos.length; i++) {
        formData.append(`adjuntos[]`, this.data.adjuntos[i]);
      }
      axios
        .post(`/api/helpdesk/anular/${this.pendientes.id}`, formData)
        .then((res) => {
          this.getPendientes();
          this.getSolucionados();
          this.closeAcciones();
          swal({
            title: "¡Ha anulado la solicitud con exito!",
            text: ` `,
            timer: 2000,
            icon: "success",
            buttons: false,
          });
        })
        .catch((err) => {
          let msg = "";
          for (const pro in err.response.data) {
            if (msg) msg += `${err.response.data[pro]}`;
            else msg = err.response.data[pro];
          }
          swal({
            title: msg,
            text: " ",
            type: "error",
            icon: "error",
          });
        });
    },
    reasignar() {
      if (this.allAreas.id == undefined) {
        this.$alerError("Debe ingresar una área!");
        return;
      }
      let formData = new FormData();
      formData.append("helpdesk_id", this.pendientes.id);
      formData.append("motivo", this.motivohelp);
      formData.append("accion", this.accion);
      formData.append("area", this.allAreas.id);
      axios
        .post(`/api/helpdesk/reasignar/${this.pendientes.id}`, formData)
        .then((res) => {
          this.getPendientes();
          this.getSolucionados();
          this.closeAcciones();
          swal({
            title: "¡Ha re asignado la solicitud con exito!",
            text: ` `,
            timer: 2000,
            icon: "success",
            buttons: false,
          });
        })
        .catch((err) => {
          let msg = "";
          for (const pro in err.response.data) {
            if (msg) msg += `${err.response.data[pro]}`;
            else msg = err.response.data[pro];
          }
          swal({
            title: msg,
            text: " ",
            type: "error",
            icon: "error",
          });
        });
    },
    asignar() {
      if (!this.pendientes.categoria) {
        if (this.allCategorias.id == undefined) {
          this.$alerError("Debe ingresar una categoria!");
          return;
        }
        if (this.tipo_reque == "") {
          this.$alerError("Debe ingresar un tipo de requerimiento!");
          return;
        }
        if (this.prioridad == "") {
          this.$alerError("Debe ingresar una prioridad!");
          return;
        }
        if (this.tiempo_estimado == "") {
          this.$alerError("Debe ingresar una fecha estimada de solucion!");
          return;
        }
      }
      if (this.responsable.length == 0) {
        this.$alerError("Debe ingresar un responsable!");
        return;
      }
      let encuentra = false;
      for (let i = 0; i < this.responsable.length; i++) {
        let asignar = this.responsable[i];
        for (let j = 0; j < this.permisosHelpdesk.length; j++) {
          let responsable = this.permisosHelpdesk[j].id;
          if (asignar == responsable) {
            encuentra = true;
            break;
          }
        }
        if (encuentra == true) {
          swal({
            title: "¡La solicitud ya esta asignada con ese responsable!",
            text: ` `,
            timer: 2000,
            icon: "error",
            buttons: false,
          });
          break;
        }
      }
      if (encuentra == false) {
        this.preload = true;
        this.data.adjuntos = this.$refs.adjuntos.files;
        let formData = new FormData();
        formData.append("helpdesk_id", this.pendientes.id);
        formData.append("categoria", this.allCategorias.id);
        formData.append("actividad", this.allActividades.id);
        formData.append("tipo_requerimiento", this.tipo_reque);
        formData.append("prioridad", this.prioridad);
        formData.append("tiempo_estimado", this.tiempo_estimado);
        formData.append("motivo", this.motivohelp);
        formData.append("accion", this.accion);
        for (let i = 0; i < this.responsable.length; i++) {
          formData.append(`responsable[]`, this.responsable[i]);
        }
        for (let i = 0; i < this.data.adjuntos.length; i++) {
          formData.append(`adjuntos[]`, this.data.adjuntos[i]);
        }
        axios
          .post(`/api/helpdesk/asignar/${this.pendientes.id}`, formData)
          .then((res) => {
            this.preload = false;
            this.getPendientes();
            this.getSolucionados();
            this.closeAcciones();
            swal({
              title: "¡Ha asignado la solicitud con exito!",
              text: ` `,
              timer: 2000,
              icon: "success",
              buttons: false,
            });
          })
          .catch((err) => {
            this.preload = false;
            let msg = "";
            for (const pro in err.response.data) {
              if (msg) msg += `${err.response.data[pro]}`;
              else msg = err.response.data[pro];
            }
            swal({
              title: msg,
              text: " ",
              type: "error",
              icon: "error",
            });
          });
      }
    },
    showAsignadosinArea() {
      axios
        .post(`/api/helpdesk/showAsignadosinArea`, {
          helpdesk_id: this.helpdesk_id,
        })
        .then((res) => {
          this.showAsignados = res.data;
        });
    },
    info_requerimiento() {
      if (this.tipo_reque == "Solicitud") {
        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 5000,
          timerProgressBar: true,
          onOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
          },
        });
        Toast.fire({
          icon: "info",
          title:
            "Tarea o actividad que no conlleva a la suspensión de un servicio o actividad diaria",
        });
      } else {
        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 4000,
          timerProgressBar: true,
          onOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
          },
        });
        Toast.fire({
          icon: "info",
          title: "Evento que impide la realización normal de actividades",
        });
      }
    },
    activarCampos(msg) {
      if (this.accion != "") {
        let found = msg.find((m) => m == this.accion);
        if (found) return true;
        return false;
      }
    },

    clearFilter() {
      (this.filtroArea_id = null),
        (this.filtroCategoria_id = null),
        (this.filtroSede_id = null),
        (this.form.desde = null),
        (this.form.hasta = null),
        (this.allPendientes = []);
    },
  },
};
</script>
