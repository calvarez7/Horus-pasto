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
            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex xs12 md3>
                    <v-select :items="[
                      { value: 18, nombre: 'Pendientes' },
                      { value: 19, nombre: 'Gestionando' },
                    ]" label="Estado" item-text="nombre" item-value="value" v-model="form.estado">
                    </v-select>
                  </v-flex>
                  <v-flex xs12 md3>
                    <v-text-field label="Documento" v-model="form.documento">
                    </v-text-field>
                  </v-flex>

                  <v-flex xs2 md2>
                    <v-text-field label="Desde" type="date" color="primary" v-model="form.desde">
                    </v-text-field>
                  </v-flex>

                  <v-flex xs2 md2>
                    <v-text-field label="Hasta" type="date" color="primary" v-model="form.hasta">
                    </v-text-field>
                  </v-flex>

                  <v-flex xs2 md2 sm2 px-2>
                    <v-text-field label="Radicado" v-model="form.radicado">
                    </v-text-field>
                  </v-flex>

                  <v-flex xs12 md8>
                    <v-autocomplete label="Tipo Solicitud" :items="tipoSolicitudes" item-text="nombre" item-value="nombre"
                      v-model="form.solicitud">
                    </v-autocomplete>
                  </v-flex>

                  <v-flex xs12 md4>
                    <v-tooltip top>
                      <template v-slot:activator="{ on }">
                        <v-btn fab color="success" small v-on="on" @click="getPendientes()">
                          <v-icon>mdi-account-search</v-icon>
                        </v-btn>
                      </template>
                      <span>Buscar</span>
                    </v-tooltip>

                    <v-tooltip top>
                      <template v-slot:activator="{ on }">
                        <v-btn fab color="error" small v-on="on" @click="clearFiltter()">
                          <v-icon>delete</v-icon>
                        </v-btn>
                      </template>
                      <span>Quitar Filtros</span>
                    </v-tooltip>
                  </v-flex>
                </v-layout>
              </v-container>

              <v-container grid-list-md>
                <v-layout wrap>
                  <v-text-field v-model="search" append-icon="search" label="Buscar por IPS, Municipio o Departamento"
                    single-line hide-details outline></v-text-field>
                </v-layout>
              </v-container>

              <v-flex xs12 md12 sm12>
                <v-data-table :headers="headers" :items="allPendientes" item-key="id" :search="search">
                  <template v-slot:items="props">
                    <td>{{ props.item.id }}</td>
                    <td>{{ props.item.created_at.split(".")[0] }}</td>
                    <td>{{ props.item.documento }}</td>
                    <td>
                      {{ props.item.pnombre + " " + props.item.papellido }}
                    </td>
                    <td>{{ props.item.departamento }}</td>
                    <td>{{ props.item.municipio }}</td>
                    <td>{{ props.item.ips }}</td>
                    <td>{{ props.item.nombreSolicitud }}</td>
                    <td>{{ props.item.estado }}</td>
                    <td>
                      <v-tooltip top>
                        <template v-slot:activator="{ on }">
                          <v-btn :loading="loading" fab color="primary" small v-on="on"
                            @click="openPendientes(props.item)">
                            <v-icon>question_answer</v-icon>
                          </v-btn>
                        </template>
                        <span>Ver</span>
                      </v-tooltip>
                    </td>
                  </template>
                </v-data-table>
              </v-flex>
            </v-card-text>
          </v-card>

          <v-dialog v-model="dialogAcciones" v-if="dialogAcciones" persistent width="1000">
            <v-card>
              <v-toolbar flat color="primary" dark>
                <v-toolbar-title>Radicado # {{ pendientes.id }}</v-toolbar-title>
              </v-toolbar>
              <v-card-text>
                <v-container grid-list-md>
                  <v-layout wrap>
                    <v-flex xs2>
                      <v-text-field v-model="pendientes.tipodoc" readonly label="Tipo Documento">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs2>
                      <v-text-field v-model="pendientes.documento" readonly label="Documento">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs3>
                      <v-text-field v-model="pendientes.pnombre" readonly label="Nombre">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs3>
                      <v-text-field v-model="pendientes.papellido" readonly label="Apellido">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs2>
                      <v-text-field v-model="pendientes.sapellido" readonly label="Segundo Apellido">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs2>
                      <v-text-field v-model="pendientes.telefono" readonly label="Telefono">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs2>
                      <v-text-field v-model="pendientes.celular" readonly label="Celular">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs2>
                      <v-text-field v-model="pendientes.direccion" readonly label="Dirección">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs3>
                      <v-text-field v-model="pendientes.departamento" readonly label="Departamento">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs3>
                      <v-text-field v-model="pendientes.municipio" readonly label="Municipio">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs6>
                      <v-text-field v-model="pendientes.ips" readonly label="IPS">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs6>
                      <v-text-field v-model="pendientes.nombreSolicitud" readonly label="Tipo Solicitud">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs12>
                      <v-textarea v-model="pendientes.descripcion" readonly label="Observación">
                      </v-textarea>
                      <span><strong>Fecha: </strong>
                        {{ pendientes.created_at.split(".")[0] }}</span>
                      <span v-if="pendientes.colaborador"><strong>Colaborador: </strong>
                        {{ pendientes.colaborador }}</span>
                    </v-flex>
                    <v-flex>
                      <v-btn v-for="(adjuntoR, index) in pendientes.adjuntoradicado" :key="index"
                        :disabled="search_adjunto">
                        <a @click="consultarAdjunto(adjuntoR.ruta)">
                          <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                          {{ index + 1 }}
                        </a>
                      </v-btn>
                      <v-divider class="my-4"></v-divider>
                    </v-flex>
                    <v-flex xs12 v-for="(comenprivate, i) in comentarioPrivado" :key="`res2-${i}`">
                      <v-flex xs12>
                        <v-flex xs12 v-if="i < 1">
                          <v-toolbar color="primary" dark class="mb-4">
                            <v-toolbar-title>Comentario privado</v-toolbar-title>
                          </v-toolbar>
                        </v-flex>
                        <v-textarea v-model="comenprivate.motivo" readonly>
                          <template v-slot:label>
                            <div>MOTIVO</div>
                          </template>
                        </v-textarea>
                        <span><strong>Nombre: </strong> {{ comenprivate.name }}
                          {{ comenprivate.apellido }}
                          <strong>Fecha: </strong>
                          {{ comenprivate.created_at.split(".")[0] }}
                        </span>
                        <v-flex>
                          <v-btn v-for="(
                              data, index4
                            ) in comenprivate.adjuntosgestion" :key="index4" :disabled="search_adjunto">
                            <a @click="consultarAdjunto(data.ruta)">
                              <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                              {{ index4 + 1 }}
                            </a>
                          </v-btn>
                        </v-flex>
                        <v-divider class="my-2"></v-divider>
                      </v-flex>
                    </v-flex>
                    <v-flex xs12 v-for="(comenpublic, i) in comentarioPublico" :key="`res1-${i}`">
                      <v-flex xs12>
                        <v-flex xs12 v-if="i < 1">
                          <v-toolbar color="primary" dark class="mb-4">
                            <v-toolbar-title>Comentario</v-toolbar-title>
                          </v-toolbar>
                        </v-flex>
                        <v-textarea v-model="comenpublic.motivo" readonly>
                          <template v-slot:label>
                            <div>MOTIVO</div>
                          </template>
                        </v-textarea>
                        <span><strong v-if="comenpublic.name">Nombre: </strong>
                          {{ comenpublic.name }}
                          {{ comenpublic.apellido }}
                          <strong v-if="!comenpublic.name">Paciente </strong>
                          <strong>Fecha: </strong>
                          {{ comenpublic.created_at.split(".")[0] }}
                        </span>
                        <v-flex>
                          <v-btn v-for="(
                              data, index3
                            ) in comenpublic.adjuntosgestion" :key="index3" :disabled="search_adjunto">
                            <a @click="consultarAdjunto(data.ruta)">
                              <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                              {{ index3 + 1 }}
                            </a>
                          </v-btn>
                        </v-flex>
                        <v-divider class="my-2"></v-divider>
                      </v-flex>
                    </v-flex>
                    <v-flex xs12 v-for="(dev, i) in devoluciones" :key="`dev-${i}`">
                      <v-flex xs12>
                        <v-flex xs12 v-if="i < 1">
                          <v-toolbar color="warning" dark class="mb-4">
                            <v-toolbar-title>Devolución</v-toolbar-title>
                          </v-toolbar>
                        </v-flex>
                        <v-textarea v-model="dev.motivo" readonly>
                          <template v-slot:label>
                            <div>MOTIVO</div>
                          </template>
                        </v-textarea>
                        <span><strong>Nombre: </strong> {{ dev.name }}
                          {{ dev.apellido }}
                          <strong>Fecha: </strong>
                          {{ dev.created_at.split(".")[0] }}
                        </span>
                        <v-divider class="my-2"></v-divider>
                      </v-flex>
                    </v-flex>
                    <v-flex xs12>
                      <v-toolbar color="primary" dark class="mb-5">
                        <v-toolbar-title>Acciones</v-toolbar-title>
                      </v-toolbar>
                    </v-flex>
                    <v-flex xs4>
                      <v-select v-model="pendientes.accion" :items="acciones" label="ACCIÓN">
                      </v-select>
                    </v-flex>
                    <v-flex xs8 />
                    <v-flex xs4 v-if="(pendientes.accion == 'Comentarios al Solicitante') |
                      (pendientes.accion == 'Comentarios Internos')
                      ">
                      <v-switch v-if="pendientes.estado_id == 18" v-model="pendientes.switch1" color="success"
                        label="Esta gestionando?">
                      </v-switch>
                    </v-flex>
                    <v-flex xs12 v-if="pendientes.accion == 'Asignar'">
                      <v-autocomplete :items="user_activos" label="Usuario" item-text="nombre" item-value="id"
                        v-model="pendientes.usuarios" multiple />
                    </v-flex>
                    <v-flex xs12 v-if="pendientes.accion != 'Asignar'">
                      <v-textarea v-model="pendientes.motivo">
                        <template v-slot:label>
                          <div>MOTIVO</div>
                        </template>
                      </v-textarea>
                    </v-flex>
                    <v-flex xs12 v-show="pendientes.accion != 'Asignar'">
                      <input id="adjuntos" multiple ref="adjuntos" type="file" />
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-card-text>
              <v-divider></v-divider>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="error" @click="dialogAcciones = false">
                  Cerrar
                </v-btn>
                <v-btn color="success" @click="accion(pendientes.accion)">
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
            <v-card-title> SOLUCIONADOS </v-card-title>
            <v-card-text>
              <v-layout row wrap>
                <v-flex xs2 md2 sm2 px-2>
                  <v-text-field label="Desde" type="date" color="primary" v-model="dateDesde">
                  </v-text-field>
                </v-flex>

                <v-flex xs2 md2 sm2 px-2>
                  <v-text-field label="Hasta" type="date" color="primary" v-model="dateHasta">
                  </v-text-field>
                </v-flex>

                <v-flex xs4 sm4 px-2 class="text-xs-right">
                  <VAutocomplete :items="tipoSolicitudes" v-model="solicitud" label="Tipo Solicitud" item-text="nombre"
                    item-value="nombre" />
                </v-flex>

                <v-flex xs4 sm4 px-2>
                  <VAutocomplete :items="departamentos" v-model="departamento" item-value="Nombre" item-text="Nombre"
                    label="Departamento" />
                </v-flex>

                <v-flex xs4 sm4 px-2>
                  <VAutocomplete :items="municipios" v-model="municipio" item-value="Nombre" item-text="Nombre"
                    label="Municipio" />
                </v-flex>

                <v-flex xs4 sm4 px-2>
                  <v-text-field v-model="documento" label="Documento" />
                </v-flex>

                <v-flex xs1 sm1 px-2>
                  <v-tooltip top>
                    <template v-slot:activator="{ on }">
                      <v-btn fab color="success" small v-on="on" @click="getSolvedFilter()">
                        <v-icon>search</v-icon>
                      </v-btn>
                    </template>
                    <span>Buscar</span>
                  </v-tooltip>
                </v-flex>

                <v-flex xs1 md1 sm1 px-2>
                  <v-tooltip top>
                    <template v-slot:activator="{ on }">
                      <v-btn fab color="error" small v-on="on" @click="clearFilterSolved">
                        <v-icon>delete</v-icon>
                      </v-btn>
                    </template>
                    <span>Quitar Filtros</span>
                  </v-tooltip>
                </v-flex>

                <v-flex xs12 md12 sm12>
                  <v-data-table :headers="headers" :loading="loading" :items="allSolved">
                    <template v-slot:items="props">
                      <td>{{ props.item.id }}</td>
                      <td>{{ props.item.created_at.split(".")[0] }}</td>
                      <td>{{ props.item.documento }}</td>
                      <td>
                        {{ props.item.pnombre + " " + props.item.papellido }}
                      </td>
                      <td>{{ props.item.departamento }}</td>
                      <td>{{ props.item.municipio }}</td>
                      <td>{{ props.item.ips }}</td>
                      <td>{{ props.item.nombreSolicitud }}</td>
                      <td>{{ props.item.estado }}</td>
                      <td>
                        <v-tooltip top>
                          <template v-slot:activator="{ on }">
                            <v-btn :loading="loading" fab color="primary" small v-on="on"
                              @click="openSolucionados(props.item)">
                              <v-icon>question_answer</v-icon>
                            </v-btn>
                          </template>
                          <span>Ver</span>
                        </v-tooltip>
                      </td>
                    </template>
                  </v-data-table>
                </v-flex>
              </v-layout>
            </v-card-text>
          </v-card>

          <v-dialog v-model="dialogSolucionados" v-if="dialogSolucionados" persistent width="1000">
            <v-card>
              <v-toolbar flat color="primary" dark>
                <v-toolbar-title>Radicado # {{ solucionados.id }}</v-toolbar-title>
              </v-toolbar>
              <v-card-text>
                <v-container grid-list-md>
                  <v-layout wrap>
                    <v-flex xs2>
                      <v-text-field v-model="solucionados.tipodoc" readonly label="Tipo Documento">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs2>
                      <v-text-field v-model="solucionados.documento" readonly label="Documento">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs3>
                      <v-text-field v-model="solucionados.pnombre" readonly label="Nombre">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs3>
                      <v-text-field v-model="solucionados.papellido" readonly label="Apellido">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs2>
                      <v-text-field v-model="solucionados.sapellido" readonly label="Segundo Apellido">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs2>
                      <v-text-field v-model="solucionados.telefono" readonly label="Telefono">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs2>
                      <v-text-field v-model="solucionados.celular" readonly label="Celular">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs2>
                      <v-text-field v-model="solucionados.direccion" readonly label="Dirección">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs3>
                      <v-text-field v-model="solucionados.departamento" readonly label="Departamento">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs3>
                      <v-text-field v-model="solucionados.municipio" readonly label="Municipio">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs6>
                      <v-text-field v-model="solucionados.ips" readonly label="IPS">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs6>
                      <v-text-field v-model="solucionados.nombreSolicitud" readonly label="Tipo Solicitud">
                      </v-text-field>
                    </v-flex>
                    <v-flex xs12>
                      <v-textarea v-model="solucionados.descripcion" readonly label="Observación">
                      </v-textarea>
                      <span><strong>Fecha: </strong>
                        {{ solucionados.created_at.split(".")[0] }}</span>
                      <span v-if="solucionados.colaborador"><strong>Colaborador: </strong>
                        {{ solucionados.colaborador }}</span>
                    </v-flex>
                    <v-flex>
                      <v-btn v-for="(
                          adjuntoR, index
                        ) in solucionados.adjuntoradicado" :key="index" :disabled="search_adjunto">
                        <a @click="consultarAdjunto(adjuntoR.ruta)">
                          <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                          {{ index + 1 }}
                        </a>
                      </v-btn>
                      <v-divider class="my-4"></v-divider>
                    </v-flex>
                    <v-flex xs12 v-for="(comenprivate, i) in comentarioPrivado" :key="`res2-${i}`">
                      <v-flex xs12>
                        <v-flex xs12 v-if="i < 1">
                          <v-toolbar color="primary" dark class="mb-4">
                            <v-toolbar-title>Comentario privado</v-toolbar-title>
                          </v-toolbar>
                        </v-flex>
                        <v-textarea v-model="comenprivate.motivo" readonly>
                          <template v-slot:label>
                            <div>MOTIVO</div>
                          </template>
                        </v-textarea>
                        <span><strong>Nombre: </strong> {{ comenprivate.name }}
                          {{ comenprivate.apellido }}
                          <strong>Fecha: </strong>
                          {{ comenprivate.created_at.split(".")[0] }}
                        </span>
                        <v-flex>
                          <v-btn v-for="(
                              data, index4
                            ) in comenprivate.adjuntosgestion" :key="index4" :disabled="search_adjunto">
                            <a @click="consultarAdjunto(data.ruta)">
                              <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                              {{ index4 + 1 }}
                            </a>
                          </v-btn>
                        </v-flex>
                        <v-divider class="my-2"></v-divider>
                      </v-flex>
                    </v-flex>
                    <v-flex xs12 v-for="(comenpublic, i) in comentarioPublico" :key="`res1-${i}`">
                      <v-flex xs12>
                        <v-flex xs12 v-if="i < 1">
                          <v-toolbar color="primary" dark class="mb-4">
                            <v-toolbar-title>Comentario</v-toolbar-title>
                          </v-toolbar>
                        </v-flex>
                        <v-textarea v-model="comenpublic.motivo" readonly>
                          <template v-slot:label>
                            <div>MOTIVO</div>
                          </template>
                        </v-textarea>
                        <span><strong v-if="comenpublic.name">Nombre: </strong>
                          {{ comenpublic.name }}
                          {{ comenpublic.apellido }}
                          <strong v-if="!comenpublic.name">Paciente </strong>
                          <strong>Fecha: </strong>
                          {{ comenpublic.created_at.split(".")[0] }}
                        </span>
                        <v-flex>
                          <v-btn v-for="(
                              data, index3
                            ) in comenpublic.adjuntosgestion" :key="index3" :disabled="search_adjunto">
                            <a @click="consultarAdjunto(data.ruta)">
                              <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                              {{ index3 + 1 }}
                            </a>
                          </v-btn>
                        </v-flex>
                        <v-divider class="my-2"></v-divider>
                      </v-flex>
                    </v-flex>
                    <v-flex xs12 v-for="(dev, i) in devoluciones" :key="`dev-${i}`">
                      <v-flex xs12>
                        <v-flex xs12 v-if="i < 1">
                          <v-toolbar color="warning" dark class="mb-4">
                            <v-toolbar-title>Devolución</v-toolbar-title>
                          </v-toolbar>
                        </v-flex>
                        <v-textarea v-model="dev.motivo" readonly>
                          <template v-slot:label>
                            <div>MOTIVO</div>
                          </template>
                        </v-textarea>
                        <span><strong>Nombre: </strong> {{ dev.name }}
                          {{ dev.apellido }}
                          <strong>Fecha: </strong>
                          {{ dev.created_at.split(".")[0] }}
                        </span>
                        <v-divider class="my-2"></v-divider>
                      </v-flex>
                    </v-flex>
                    <v-flex xs12>
                      <v-flex xs12>
                        <v-toolbar color="success" dark class="mb-4">
                          <v-toolbar-title>Respuesta</v-toolbar-title>
                        </v-toolbar>
                      </v-flex>
                      <v-flex v-for="(res, i) in solucionados.gestion" :key="`res-${i}`">
                        <v-textarea v-model="res.motivo" readonly>
                          <template v-slot:label>
                            <div>MOTIVO</div>
                          </template>
                        </v-textarea>
                        <span><strong>Nombre: </strong> {{ res.name }}
                          {{ res.apellido }}
                          <strong>Fecha: </strong>
                          {{ res.created_at.split(".")[0] }}
                        </span>
                        <v-flex>
                          <v-btn v-for="(datag, index7) in res.adjuntosgestion" :key="index7" :disabled="search_adjunto">
                            <a @click="consultarAdjunto(datag.ruta)">
                              <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                              {{ index7 + 1 }}
                            </a>
                          </v-btn>
                        </v-flex>
                      </v-flex>
                      <v-divider class="my-2"></v-divider>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-card-text>
              <v-divider></v-divider>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="error" @click="dialogSolucionados = false">
                  Cerrar
                </v-btn>
              </v-card-actions>
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
import moment from "moment";
import Swal from "sweetalert2";
import { AdjuntoService } from "../../../services";
export default {
  data: () => ({
    search: null,
    form: {
      estado: null,
      radicado: null,
      documento: null,
      departamento: null,
      desde: null,
      hasta: null,
      municipio: null,
      solicitud: null,
    },
    users: [],
    loading: false,
    allPendientes: [],
    tipoSolicitudes: [],
    dialogAcciones: false,
    pendientes: [],
    search_adjunto: false,
    acciones: [
      "Comentarios al Solicitante",
      "Comentarios Internos",
      "Asignar",
      "Respuesta",
    ],
    preload: false,
    adjuntos: [],
    comentarioPublico: [],
    comentarioPrivado: [],
    devoluciones: [],
    search: "",
    allSolved: [],
    solucionados: [],
    dialogSolucionados: false,
    headers: [
      {
        text: "Radicado",
        align: "left",
        value: "id",
      },
      {
        text: "Fecha",
        align: "left"
      },
      {
        text: "Documento",
        align: "left",
        sortable: false,
      },
      {
        text: "Nombre",
        align: "left",
        sortable: false,
      },
      {
        text: "Departamento",
        align: "left",
        value: "departamento",
      },
      {
        text: "Municipio",
        align: "left",
        value: "municipio",
      },
      {
        text: "IPS",
        align: "left",
        value: "ips",
      },
      {
        text: "Solicitud",
        align: "left",
      },
      {
        text: "Estado",
        align: "left",
      },
      {
        text: "",
        align: "left",
        sortable: false,
      },
    ],
    departamentos: [],
    municipios: [],
    solicitud: "",
    departamento: "",
    municipio: "",
    documento: "",
    dateDesde: moment().format("YYYY-MM-DD"),
    dateHasta: moment().format("YYYY-MM-DD"),
  }),

  mounted() {
    this.getTipos();
    this.getUser();
    this.fetchDepartamentos();
    this.fetchMunicipios();
  },

  computed: {
    allDepartamentos() {
      return this.allPendientes.map((ap) => {
        return {
          nombre: ap.departamento,
        };
      });
    },
    allMunicipios() {
      return this.allPendientes.map((ap) => {
        return {
          nombre: ap.municipio,
        };
      });
    },
    allIPS() {
      return this.allPendientes.map((ap) => {
        return {
          nombre: ap.ips,
        };
      });
    },
    user_activos() {
      let finded = [];
      this.users.forEach(u => {
        if (u.estado == 1) {
          finded.push(u)
        }
      });
      return finded;
    },
  },

  methods: {
    fetchDepartamentos() {
      axios.get("/api/departamento/all").then((res) => {
        this.departamentos = res.data;
      });
    },

    fetchMunicipios() {
      axios.get("/api/municipio/all").then((res) => {
        this.municipios = res.data;
      });
    },

    getTipos() {
      axios.get("/api/redvital/getTipoSolicitud").then((res) => {
        this.tipoSolicitudes = res.data;
      });
    },

    getPendientes() {
      this.allPendientes = [];
      if (this.form.estado == null) {
        this.$alerError(
          "El campo estado es obligatorio para realizar la busqueda."
        );
        return;
      }
      this.preload = true;
      axios
        .post("/api/solicitud/pendientesGestion", {
          estado: this.form.estado,
          radicado: this.form.radicado,
          documentos: this.form.documento,
          desde: this.form.desde,
          hasta: this.form.hasta,
          solicitud: this.form.solicitud,
        })
        .then((res) => {
          this.preload = false;
          this.allPendientes = res.data;
        });
    },

    clearFiltter() {
      (this.form.estado = null),
        (this.form.radicado = null),
        (this.form.documento = null),
        (this.form.departamento = null),
        (this.form.desde = null),
        (this.form.hasta = null),
        (this.form.municipio = null),
        (this.form.solicitud = null);
    },

    async openPendientes(item) {
      this.loading = true;
      this.pendientes = item;
      await this.comentariosPublico(item.id);
      await this.comentariosPrivado(item.id);
      await this.devolucion(item.id);
      this.loading = false;
      this.dialogAcciones = true;
    },

    async consultarAdjunto(ruta) {
      this.search_adjunto = true;
      try {
        let adj = await AdjuntoService.get(ruta);
        let blob = new Blob([adj[1]], {
          type: adj[0],
        });
        let link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        window.open(link.href, "_blank");
        this.search_adjunto = false;
      } catch (error) {
        this.search_adjunto = false;
      }
    },

    getUser() {
      axios.get("/api/user/all").then(
        (res) =>
        (this.users = res.data.map((us) => {
          return {
            id: us.id,
            nombre: `${us.cedula} - ${us.name} ${us.apellido}`,
            estado: us.estado_user,
          };
        }))
      );
    },

    closeAcciones() {
      this.dialogAcciones = false;
      this.$refs.adjuntos.value = "";
    },

    accion(msg) {
      if (
        (msg == "Comentarios al Solicitante") |
        (msg == "Comentarios Internos")
      ) {
        this.comentar();
      } else if (msg == "Respuesta") {
        this.respuesta();
      } else if (msg == "Asignar") {
        this.asignar();
      }
    },

    comentar() {
      this.adjuntos = this.$refs.adjuntos.files;
      let filesize = 0;
      for (let index = 0; index < this.adjuntos.length; index++) {
        filesize += this.adjuntos[index].size;
        if (filesize > 8000000) {
          Swal.fire({
            title: "Error",
            text: `Los adjuntos de la solicitud superan el peso de 8MB.`,
            icon: "error",
            allowOutsideClick: false,
          });
          return;
        }
      }
      if (
        (this.pendientes.motivo == undefined) |
        (this.pendientes.motivo == "")
      ) {
        this.$alerError("El campo motivo es obligatorio.");
        return;
      }
      this.preload = true;
      let formData = new FormData();
      formData.append("radicaciononline_id", this.pendientes.id);
      formData.append("motivo", this.pendientes.motivo);
      formData.append("accion", this.pendientes.accion);
      formData.append("gestionando", this.pendientes.switch1);
      for (let i = 0; i < this.adjuntos.length; i++) {
        formData.append(`adjuntos[]`, this.adjuntos[i]);
      }
      axios
        .post(`/api/solicitud/comentar`, formData)
        .then((res) => {
          this.getPendientes();
          this.preload = false;
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

    async comentariosPublico(item) {
      await axios
        .post(`/api/solicitud/showcomentariosPublicos`, {
          radicaciononline_id: item,
        })
        .then((res) => {
          this.comentarioPublico = res.data;
        });
    },

    async comentariosPrivado(item) {
      await axios
        .post(`/api/solicitud/showcomentariosPrivados`, {
          radicaciononline_id: item,
        })
        .then((res) => {
          this.comentarioPrivado = res.data;
        });
    },

    asignar() {
      if (this.pendientes.usuarios == undefined) {
        this.$alerError("Debe seleccionar un usuario.");
        return;
      }
      if (this.pendientes.usuarios.length == 0) {
        this.$alerError("Debe seleccionar un usuario.");
        return;
      }
      let formData = new FormData();
      formData.append("radicaciononline_id", this.pendientes.id);
      for (let i = 0; i < this.pendientes.usuarios.length; i++) {
        formData.append(`users[]`, this.pendientes.usuarios[i]);
      }
      this.preload = true;
      axios
        .post(`/api/solicitud/asignar`, formData)
        .then((res) => {
          this.getPendientes();
          this.preload = false;
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
    },

    respuesta() {
      this.adjuntos = this.$refs.adjuntos.files;
      let filesize = 0;
      for (let index = 0; index < this.adjuntos.length; index++) {
        filesize += this.adjuntos[index].size;
        if (filesize > 8000000) {
          Swal.fire({
            title: "Error",
            text: `Los adjuntos de la solicitud superan el peso de 8MB.`,
            icon: "error",
            allowOutsideClick: false,
          });
          return;
        }
      }
      if (
        (this.pendientes.motivo == undefined) |
        (this.pendientes.motivo == "")
      ) {
        this.$alerError("El campo motivo es obligatorio.");
        return;
      }
      this.preload = true;
      let formData = new FormData();
      formData.append("radicaciononline_id", this.pendientes.id);
      formData.append("motivo", this.pendientes.motivo);
      for (let i = 0; i < this.adjuntos.length; i++) {
        formData.append(`adjuntos[]`, this.adjuntos[i]);
      }
      axios
        .post(`/api/solicitud/respuesta`, formData)
        .then((res) => {
          this.getPendientes();
          this.preload = false;
          this.closeAcciones();
          swal({
            title: "¡Ha dado respuesta a la solicitud con exito!",
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

    async devolucion(item) {
      await axios
        .post(`/api/solicitud/showDevoluciones`, {
          radicaciononline_id: item,
        })
        .then((res) => {
          this.devoluciones = res.data;
        });
    },

    getSolvedFilter() {
      this.loading = true;
      axios
        .post("/api/solicitud/solucionadosGestion", {
          desde: this.dateDesde,
          hasta: this.dateHasta,
          solicitud: this.solicitud,
          departamento: this.departamento,
          municipio: this.municipio,
          documento: this.documento,
        })
        .then((res) => {
          this.loading = false;
          this.allSolved = res.data;
        });
    },

    async openSolucionados(item) {
      this.loading = true;
      this.solucionados = item;
      await this.comentariosPublico(item.id);
      await this.comentariosPrivado(item.id);
      await this.devolucion(item.id);
      this.loading = false;
      this.dialogSolucionados = true;
    },

    clearFilterSolved() {
      this.dateDesde = moment().format("YYYY-MM-DD");
      this.dateHasta = moment().format("YYYY-MM-DD");
      this.solicitud = "";
      this.departamento = "";
      this.municipio = "";
      this.documento = "";
    },
  },
};
</script>
