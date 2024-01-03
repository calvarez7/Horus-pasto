<template>
  <div>
    <v-card>
      <v-card-title>
        <v-layout row wrap>
          <span> PRE-SOLUCIONADOS </span>
        </v-layout>
        <v-combobox
          :items="['Web', 'Supersalud']"
          label="Canal"
          single-line
          hide-details
          v-model="canal"
          @input="getPresolutionFilter()"
        ></v-combobox>
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="search"
          label="Search"
          single-line
          hide-details
        >
        </v-text-field>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :loading="loading"
        :items="allPresolucion"
        :search="search"
      >
        <template v-slot:items="props">
          <td>{{ props.item.id }}</td>
          <td>{{ props.item.cc }}</td>
          <td>{{ props.item.doc }}</td>
          <td>{{ props.item.Solicitud }}</td>
          <td>
            <v-menu open-on-hover right offset-y>
              <template v-slot:activator="{ on }">
                <v-icon v-on="on">message</v-icon>
              </template>

              <v-list>
                <v-list-tile
                  v-for="(data, index) in props.item.permisospqrsf"
                  :key="index"
                >
                  {{ data.name }}
                </v-list-tile>
              </v-list>
            </v-menu>
          </td>
          <td>
            <v-chip :class="semaforoTd(props.item)">{{
              `${props.item.diasHabiles} DÍA(S)`
            }}</v-chip>
          </td>
          <td>{{ props.item.created_at.split(" ")[0] }}</td>
          <td>{{ props.item.Canal }}</td>
          <td>
            <v-btn
              :disabled="loading"
              @click="
                manage(props.item),
                  pqrsfSubcategories(props.item),
                  pqrsfDetallearticulos(props.item),
                  pqrsfCups(props.item),
                  pqrsfArea(props.item),
                  pqrsfIps(props.item),
                  pqrsfEmpleados(props.item)
              "
              color="blue"
              flat
              icon
            >
              <v-icon>library_books</v-icon>
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

    <v-dialog v-model="gestion" persistent width="1200">
      <v-card>
        <v-toolbar flat color="primary" dark>
          <v-flex xs12 text-xs-center flat dark>
            <v-toolbar-title>GESTIÓN</v-toolbar-title>
          </v-flex>
        </v-toolbar>
        <v-card-text>
          <v-container grid-list-md>
            <v-layout wrap>
              <v-flex xs12>
                <v-layout row wrap>
                  <v-flex xs3 v-show="pqrsf.cc">
                    <v-text-field v-model="pqrsf.cc" readonly label="CEDULA">
                    </v-text-field>
                  </v-flex>
                  <v-flex xs3 v-show="pqrsf.doc">
                    <v-text-field v-model="pqrsf.doc" readonly label="CEDULA">
                    </v-text-field>
                  </v-flex>
                  <v-flex xs2>
                    <v-text-field
                      v-model="pqrsf.Edad_Cumplida"
                      readonly
                      label="EDAD"
                    >
                    </v-text-field>
                  </v-flex>
                  <v-flex xs2>
                    <v-text-field
                      v-model="pqrsf.Nombre"
                      readonly
                      label="NOMBRE"
                    >
                    </v-text-field>
                  </v-flex>
                  <v-flex xs2>
                    <v-text-field
                      v-model="pqrsf.Apellido"
                      readonly
                      label="APELLIDO"
                    >
                    </v-text-field>
                  </v-flex>
                  <v-flex xs3>
                    <v-text-field
                      v-model="pqrsf.Telefono"
                      readonly
                      label="TELEFONO"
                    >
                    </v-text-field>
                  </v-flex>
                  <v-flex xs6>
                    <v-text-field v-model="pqrsf.Email" label="EMAIL">
                    </v-text-field>
                  </v-flex>
                  <v-flex xs6>
                    <v-text-field v-model="pqrsf.IPS" readonly label="IPS">
                    </v-text-field>
                  </v-flex>
                  <!-- <v-flex >
                    <v-subheader>SUBCATEGORIA ACTUAL</v-subheader>
                    <v-item-group multiple>
                      <v-item
                        v-for="(data, index) in pqrsfSubcategorias"
                        :key="index"
                      >
                        <v-chip label :selected="data.selected">
                          {{ data.Nombre }}
                        </v-chip>
                      </v-item>
                    </v-item-group>
                    <v-divider class="my-2"></v-divider>
                  </v-flex> -->

                  <v-flex xs11>
                    <v-autocomplete
                      v-model="pqrsfSubcategorias"
                      :items="allSubcategorias"
                      item-text="Nombre"
                      item-value="id"
                      hint="*Para actualizar debe borrar todos y agregar nuevamente solo los que van a actualizar"
                      persistent-hint
                      chips
                      label="SUBCATEGORIA ACTUAL"
                      multiple
                    >
                      <template v-slot:selection="data">
                        <v-chip
                          :selected="data.selected"
                          close
                          class="chip--select-multi"
                          @input="remove_subcategoria(data.item.id)"
                        >
                          {{ data.item.Nombre.substring(0, 100) }}
                        </v-chip>
                      </template>
                      <template v-slot:item="data">
                        <template v-if="typeof data.item.Nombre !== 'object'">
                          <v-list-tile-content v-text="data.item.Nombre">
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
                  <v-flex xs1>
                    <v-tooltip bottom>
                      <template v-slot:activator="{ on }">
                        <v-btn
                          v-on="on"
                          color="success"
                          fab
                          small
                          dark
                          @click="saveSubcategories(pqrsf)"
                        >
                          <v-icon>add</v-icon>
                        </v-btn>
                      </template>
                      <span>Actualizar Subcategoria</span>
                    </v-tooltip>
                  </v-flex>

                  <v-flex xs12 v-if="pqrsfDetallearticulo.length > 0">
                    <v-subheader>MEDICAMENTO ACTUAL</v-subheader>
                    <v-item-group multiple>
                      <v-item
                        v-for="(data, index) in pqrsfDetallearticulo"
                        :key="index"
                      >
                        <v-chip label :selected="data.selected">
                          {{ data.Producto }}
                        </v-chip>
                      </v-item>
                    </v-item-group>
                    <v-divider class="my-2"></v-divider>
                  </v-flex>
                  <v-flex xs12 v-if="pqrsfcups.length > 0">
                    <v-subheader>SERVICIO ACTUAL</v-subheader>
                    <v-item-group multiple>
                      <v-item v-for="(data, index) in pqrsfcups" :key="index">
                        <v-chip label :selected="data.selected">
                          {{ data.Nombre }}
                        </v-chip>
                      </v-item>
                    </v-item-group>
                    <v-divider class="my-2"></v-divider>
                  </v-flex>
                  <!-- <v-flex xs12 v-if="pqrareas.length > 0">
                    <v-subheader>AREA ACTUAL</v-subheader>
                    <v-item-group multiple>
                      <v-item v-for="(data, index) in pqrareas" :key="index">
                        <v-chip label :selected="data.selected">
                          {{ data.Nombre }}
                        </v-chip>
                      </v-item>
                    </v-item-group>
                    <v-divider class="my-2"></v-divider>
                  </v-flex> -->

                  <v-flex xs11>
                    <v-autocomplete
                      v-model="pqrareas"
                      :items="allAreas"
                      item-text="Nombre"
                      item-value="id"
                      hint="*Para actualizar debe borrar todos y agregar nuevamente solo los que van a actualizar"
                      persistent-hint
                      chips
                      label="AREA"
                      multiple
                    >
                      <template v-slot:selection="data">
                        <v-chip
                          :selected="data.selected"
                          close
                          class="chip--select-multi"
                          @input="remove_area(data.item.id)"
                        >
                          {{ data.item.Nombre.substring(0, 100) }}
                        </v-chip>
                      </template>
                      <template v-slot:item="data">
                        <template v-if="typeof data.item.Nombre !== 'object'">
                          <v-list-tile-content v-text="data.item.Nombre">
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
                  <v-flex xs1>
                    <v-tooltip bottom>
                      <template v-slot:activator="{ on }">
                        <v-btn
                          v-on="on"
                          color="success"
                          fab
                          small
                          dark
                          @click="saveAreas(pqrsf)"
                        >
                          <v-icon>add</v-icon>
                        </v-btn>
                      </template>
                      <span>Actualizar Area</span>
                    </v-tooltip>
                  </v-flex>

                  <v-flex xs12 v-if="pqrips.length > 0">
                    <v-subheader>IPS ACTUAL</v-subheader>
                    <v-item-group multiple>
                      <v-item v-for="(data, index) in pqrips" :key="index">
                        <v-chip label :selected="data.selected">
                          {{ data.Nombre }}
                        </v-chip>
                      </v-item>
                    </v-item-group>
                    <v-divider class="my-2"></v-divider>
                  </v-flex>
                  <v-flex xs12 v-if="pqrempleados.length > 0">
                    <v-subheader>EMPLEADO ACTUAL</v-subheader>
                    <v-item-group multiple>
                      <v-item
                        v-for="(data, index) in pqrempleados"
                        :key="index"
                      >
                        <v-chip label :selected="data.selected">
                          {{ data.Nombre }}
                        </v-chip>
                      </v-item>
                    </v-item-group>
                    <v-divider class="my-2"></v-divider>
                  </v-flex>
                  <v-flex xs3>
                    <v-select
                      :items="tipoSolicitud"
                      v-model="pqrsf.Tiposolicitud"
                      label="TIPO DE SOLICITUD"
                    ></v-select>
                  </v-flex>
                  <v-flex xs3>
                    <v-text-field
                      readonly
                      v-model="pqrsf.Priorizacion"
                      label="PRIORIZACIÓN"
                    >
                    </v-text-field>
                  </v-flex>
                  <v-flex xs2>
                    <v-text-field
                      readonly
                      v-model="pqrsf.Reabierta"
                      label="REABIERTA"
                    >
                    </v-text-field>
                  </v-flex>
                  <v-flex xs3>
                    <v-select
                      :items="atributodecalidad"
                      v-model="pqrsf.Atr_calidad"
                      label="ATRIBUTO DE CALIDAD"
                    ></v-select>
                  </v-flex>
                  <v-flex xs1>
                    <v-tooltip bottom>
                      <template v-slot:activator="{ on }">
                        <v-btn
                          v-on="on"
                          color="primary"
                          fab
                          small
                          dark
                          @click="update(pqrsf)"
                        >
                          <v-icon>cached</v-icon>
                        </v-btn>
                      </template>
                      <span>Actualizar</span>
                    </v-tooltip>
                  </v-flex>
                  <v-flex xs12>
                    <v-textarea
                      label="DESCRIPCIÓN DEL CASO"
                      v-model="pqrsf.Descripcion"
                      readonly
                    >
                    </v-textarea>
                  </v-flex>
                  <v-flex
                    xs12
                    v-if="
                      pqrsf.Tiposolicitud == 'Queja' ||
                      pqrsf.Tiposolicitud == 'Reclamo'
                    "
                  >
                    <v-text-field label="DEBER" v-model="pqrsf.deber" readonly>
                    </v-text-field>
                  </v-flex>
                  <v-flex
                    xs12
                    v-if="
                      pqrsf.Tiposolicitud == 'Queja' ||
                      pqrsf.Tiposolicitud == 'Reclamo'
                    "
                  >
                    <v-textarea
                      label="DERECHO"
                      v-model="pqrsf.derecho"
                      readonly
                    >
                    </v-textarea>
                  </v-flex>
                  <v-flex
                    xs12
                    v-for="(GesPqr, index) in pqrsf.gestion_pqrsfs"
                    :key="`r-${index}`"
                  >
                    <v-flex v-if="GesPqr.Tipo_id == 3">
                      <v-flex xs12 md12 v-if="GesPqr.name">
                        <span>
                          <strong>Nombre:</strong> {{ GesPqr.name }}
                          {{ GesPqr.apellido }}</span
                        >
                      </v-flex>
                      <v-flex xs12 md12 v-else>
                        <span>
                          <strong>Paciente:</strong> {{ pqrsf.Nombre }}
                          {{ pqrsf.Apellido }}</span
                        >
                      </v-flex>
                      <v-btn
                        v-for="(data, index) in GesPqr.adjuntos_pqrsf"
                        :key="index"
                      >
                        <a
                          :href="`${data.Ruta}`"
                          target="_blank"
                          style="text-decoration: none"
                        >
                          <v-icon color="dark">mdi-cloud-upload</v-icon>Adjunto
                          {{ index + 1 }}
                        </a>
                      </v-btn>
                    </v-flex>
                  </v-flex>
                  <v-flex
                    xs12
                    v-for="(GesPqrRDev, indexDev) in pqrsf.gestion_pqrsfs"
                    :key="`dev-${indexDev}`"
                  >
                    <v-flex xs12 v-show="GesPqrRDev.Tipo_id == 22">
                      <v-flex xs12>
                        <v-toolbar color="info" dark class="mb-2">
                          <v-toolbar-title>Devolución</v-toolbar-title>
                        </v-toolbar>
                      </v-flex>
                      <v-flex xs12>
                        <v-textarea v-model="GesPqrRDev.Motivo" readonly>
                          <template v-slot:label>
                            <div>MOTIVO</div>
                          </template>
                        </v-textarea>
                        <span
                          ><strong>Nombre:</strong> {{ GesPqrRDev.name }}
                          {{ GesPqrRDev.apellido }}
                          <strong>Fecha:</strong>
                          {{ GesPqrRDev.created_at.split(".")[0] }}
                          <strong>Responsable:</strong>
                          {{ GesPqrRDev.Responsable }}</span
                        >
                      </v-flex>
                      <template>
                        <v-container>
                          <v-layout>
                            <v-flex
                              xs6
                              md3
                              v-for="(data, index) in GesPqrRDev.adjuntos_pqrsf"
                              :key="index"
                            >
                              <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                  <v-btn outline>
                                    <a
                                      :href="`${data.Ruta}`"
                                      target="_blank"
                                      style="text-decoration: none"
                                    >
                                      <v-icon color="dark"
                                        >mdi-cloud-upload
                                      </v-icon>
                                      Adjunto
                                      {{ index + 1 }}
                                    </a>
                                  </v-btn>
                                  <v-btn
                                    v-on="on"
                                    color="success"
                                    fab
                                    small
                                    dark
                                    @click="addAdjuntos(data.Ruta)"
                                  >
                                    <v-icon>add</v-icon>
                                  </v-btn>
                                </template>
                                <span>Agregar Adjunto {{ index + 1 }}</span>
                              </v-tooltip>
                            </v-flex>
                          </v-layout>
                        </v-container>
                      </template>
                      <v-divider class="my-1"></v-divider>
                    </v-flex>
                  </v-flex>
                  <v-flex
                    xs12
                    v-for="(GesPqrR, indexr) in pqrsf.gestion_pqrsfs"
                    :key="`rea-${indexr}`"
                  >
                    <v-layout wrap>
                      <v-flex xs12 v-if="GesPqrR.Tipo_id == 10">
                        <v-flex xs12>
                          <v-toolbar color="#FBCA1C" dark class="mb-2">
                            <v-toolbar-title>Reasignada</v-toolbar-title>
                          </v-toolbar>
                        </v-flex>
                        <v-flex xs12>
                          <v-textarea v-model="GesPqrR.Motivo" readonly>
                            <template v-slot:label>
                              <div>MOTIVO</div>
                            </template>
                          </v-textarea>
                          <span
                            ><strong>Nombre:</strong> {{ GesPqrR.name }}
                            {{ GesPqrR.apellido }}
                            <strong>Fecha:</strong>
                            {{ GesPqrR.created_at.split(".")[0] }}</span
                          >
                        </v-flex>
                        <v-divider class="my-4"></v-divider>
                      </v-flex>
                    </v-layout>
                  </v-flex>
                  <v-flex
                    xs12
                    v-for="(GesPqrR, index) in pqrsf.gestion_pqrsfs"
                    :key="`res-${index}`"
                  >
                    <v-layout wrap>
                      <v-flex xs12 v-if="GesPqrR.Tipo_id == 8">
                        <v-flex xs12>
                          <v-toolbar color="primary" dark class="mb-2">
                            <v-toolbar-title>Respuesta</v-toolbar-title>
                          </v-toolbar>
                        </v-flex>
                        <v-flex xs12>
                          <v-textarea v-model="GesPqrR.Motivo" readonly>
                            <template v-slot:label>
                              <div>MOTIVO</div>
                            </template>
                          </v-textarea>
                          <span
                            ><strong>Nombre:</strong> {{ GesPqrR.name }}
                            {{ GesPqrR.apellido }}
                            <strong>Fecha:</strong>
                            {{ GesPqrR.created_at.split(".")[0] }}
                            <strong>Responsable:</strong>
                            {{ GesPqrR.Responsable }}</span
                          >
                        </v-flex>
                        <template>
                          <v-container>
                            <v-layout>
                              <v-flex
                                xs6
                                md3
                                v-for="(data, index) in GesPqrR.adjuntos_pqrsf"
                                :key="index"
                              >
                                <v-tooltip bottom>
                                  <template v-slot:activator="{ on }">
                                    <v-btn outline>
                                      <a
                                        :href="`${data.Ruta}`"
                                        target="_blank"
                                        style="text-decoration: none"
                                      >
                                        <v-icon color="dark"
                                          >mdi-cloud-upload
                                        </v-icon>
                                        Adjunto
                                        {{ index + 1 }}
                                      </a>
                                    </v-btn>
                                    <v-btn
                                      v-on="on"
                                      color="success"
                                      fab
                                      small
                                      dark
                                      @click="addAdjuntos(data.Ruta)"
                                    >
                                      <v-icon>add</v-icon>
                                    </v-btn>
                                  </template>
                                  <span>Agregar Adjunto {{ index + 1 }}</span>
                                </v-tooltip>
                              </v-flex>
                            </v-layout>
                          </v-container>
                        </template>
                        <v-divider class="my-1"></v-divider>
                      </v-flex>
                    </v-layout>
                  </v-flex>
                  <v-toolbar color="primary" dark class="mb-2">
                    <v-toolbar-title>Respuesta Final</v-toolbar-title>
                  </v-toolbar>
                  <v-flex xs12>
                    <v-divider class="my-2"></v-divider>
                    <v-textarea v-model="respuesta">
                      <template v-slot:label>
                        <div>MOTIVO</div>
                      </template>
                    </v-textarea>
                  </v-flex>
                  <v-flex xs12>
                    <input id="adjuntos" multiple ref="adjuntos" type="file" />
                    <v-divider class="my-2"></v-divider>
                  </v-flex>
                  <v-flex xs6>
                    <v-btn
                      color="success"
                      :disabled="loading"
                      @click="respuestaFinal(pqrsf)"
                    >
                      Solucionar</v-btn
                    >
                    <v-tooltip bottom>
                      <template v-slot:activator="{ on }">
                        <v-btn
                          v-on="on"
                          color="error"
                          fab
                          small
                          dark
                          @click="deleteAdjuntos()"
                        >
                          <v-icon>remove</v-icon>
                        </v-btn>
                      </template>
                      <span>Eliminar Adjunto</span>
                    </v-tooltip>
                  </v-flex>
                </v-layout>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="warning" dark @click="openReasignar(pqrsf)"
            >Reasignar</v-btn
          >
          <v-btn color="error" @click="closeGestion()">Salir</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="dialogReasignar" persistent max-width="600px">
      <v-card>
        <v-toolbar flat color="primary" dark>
          <v-toolbar-title>REASIGNAR</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-container grid-list-md>
            <v-layout wrap>
              <v-flex xs12>
                <v-layout row wrap>
                  <v-flex xs12>
                    <span>Seleccione a quien quiera reasignar</span>
                  </v-flex>
                  <v-flex xs12>
                    <v-autocomplete
                      v-model="responsable"
                      :items="allPermissions"
                      item-text="name"
                      item-value="id"
                      chips
                      multiple
                    >
                      <template v-slot:selection="data">
                        <v-chip
                          :selected="data.selected"
                          close
                          class="chip--select-multi"
                          @input="remove_permission(data.item.id)"
                        >
                          {{ data.item.name }}
                        </v-chip>
                      </template>
                      <template v-slot:item="data">
                        <template v-if="typeof data.item.name !== 'object'">
                          <v-list-tile-content
                            v-text="data.item.name"
                          ></v-list-tile-content>
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
                  <v-flex xs12>
                    <v-divider class="my-2"></v-divider>
                    <v-textarea v-model="motivoreasignar">
                      <template v-slot:label>
                        <div>MOTIVO</div>
                      </template>
                    </v-textarea>
                  </v-flex>
                </v-layout>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="reasignar(pqrsf)">Reasignar</v-btn>
          <v-btn color="error" @click="closeReasignar()">Salir</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="dialogProcesando" persistent width="300">
      <v-card color="primary" dark>
        <v-card-text>
          Estamos procesando su solicitud
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
<script>
import Swal from "sweetalert2";
export default {
  name: "PqrsfPresolucionado",
  data() {
    return {
      search: "",
      headers: [
        {
          text: "Radicado",
          align: "left",
          value: "id",
        },
        {
          text: "Cedula",
          align: "left",
          value: "cc",
        },
        {
          text: "Cedula Super",
          align: "left",
          value: "doc",
        },
        {
          text: "Tipo de solicitud",
          align: "left",
          value: "Solicitud",
        },
        {
          text: "Asignado a",
          align: "left",
          value: "IPS",
        },
        {
          text: "Semaforo",
          align: "left",
          value: "diasHabiles",
        },
        {
          text: "Fecha Generado",
          align: "left",
          value: "Creado",
        },
        {
          text: "Canal",
          align: "left",
          value: "Canal",
        },
        {
          text: "",
          align: "Right",
        },
      ],
      allPresolucion: [],
      loading: false,
      canal: "Web",
      gestion: false,
      pqrsf: [],
      allAreas: [],
      pqrsfSubcategorias: [],
      pqrsfDetallearticulo: [],
      allSubcategorias: [],
      pqrsfcups: [],
      pqrareas: [],
      pqrips: [],
      pqrempleados: [],
      respuesta: "",
      addAdjunto: [],
      adjuntos: [],
      dialogReasignar: false,
      allPermissions: [],
      responsable: "",
      motivoreasignar: "",
      dialogProcesando: false,
      atributodecalidad: [
        "Accesibilidad",
        "Oportunidad",
        "Seguridad",
        "Pertinencia",
        "Continuidad",
        "Satisfacción del Usuario",
        "No Aplica",
      ],
      tipoSolicitud: [
        "Petición",
        "Queja",
        "Reclamo",
        "Sugerencia",
        "Felicitación",
        "Información",
        "Solicitud",
      ],
    };
  },
  mounted() {
    this.getPresolutionFilter();
    this.permissionspqrsf();
    this.showSubcategories();
    this.getAreas();
  },
  methods: {
    permissionspqrsf() {
      axios.get("/api/pqrsf/permissionpqrsf").then((res) => {
        this.allPermissions = res.data;
      });
    },
    pqrsfSubcategories(pqr) {
      axios.post(`/api/pqrsf/pqrsfsubcategorias/${pqr.id}`).then((res) => {
        this.pqrsfSubcategorias = res.data;
      });
    },
    pqrsfDetallearticulos(pqr) {
      axios.post(`/api/pqrsf/pqrsfDetallearticulos/${pqr.id}`).then((res) => {
        this.pqrsfDetallearticulo = res.data;
      });
    },
    pqrsfCups(pqr) {
      axios.post(`/api/pqrsf/pqrsfCups/${pqr.id}`).then((res) => {
        this.pqrsfcups = res.data;
      });
    },
    pqrsfArea(pqr) {
      axios.post(`/api/pqrsf/pqrsfAreas/${pqr.id}`).then((res) => {
        this.pqrareas = res.data;
      });
    },
    pqrsfIps(pqr) {
      axios.post(`/api/pqrsf/pqrsfIps/${pqr.id}`).then((res) => {
        this.pqrips = res.data;
      });
    },
    pqrsfEmpleados(pqr) {
      axios.post(`/api/pqrsf/pqrsfEmpleados/${pqr.id}`).then((res) => {
        this.pqrempleados = res.data;
      });
    },
    remove_permission(item) {
      this.responsable.splice(this.responsable.indexOf(item), 1);
      this.responsable = [...this.responsable];
    },
    semaforoTd(pre_solucionadas) {
      if (pre_solucionadas.Priorizacion == "Urgente") {
        if (pre_solucionadas.diasHabiles >= 1) {
          return "error white--text";
        } else {
          return "success white--text";
        }
      } else if (pre_solucionadas.Priorizacion == "Prioritaria") {
        if (pre_solucionadas.diasHabiles <= 1) {
          return "success white--text";
        } else if (pre_solucionadas.diasHabiles == 2) {
          return "yellow white--text";
        } else {
          return "error white--text";
        }
      } else if (pre_solucionadas.Priorizacion == "No Prioritaria") {
        if (pre_solucionadas.diasHabiles <= 3) {
          return "success white--text";
        } else if (pre_solucionadas.diasHabiles <= 6) {
          return "yellow white--text";
        } else {
          return "error white--text";
        }
      }
    },
    getPresolutionFilter() {
      this.$emit("updateCount", this.canal);
      this.loading = true;
      this.data = {
        canal: this.canal,
      };
      axios.post("/api/pqrsf/pre_solution", this.data).then((res) => {
        this.loading = false;
        this.allPresolucion = res.data;
      });
    },
    manage(pqr) {
      this.pqrsf = pqr;
      this.gestion = true;
    },
    addAdjuntos(adjunto) {
      this.adjuntosRespuesta = adjunto;
      this.addAdjunto.push(this.adjuntosRespuesta);
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
        icon: "success",
        title: "Adjunto agregado con exito!",
      });
    },
    closeGestion() {
      this.gestion = false;
      this.addAdjunto = [];
      (this.respuesta = ""), (this.$refs.adjuntos.value = "");
    },
    deleteAdjuntos() {
      this.addAdjunto = [];
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
        icon: "success",
        title: "Adjunto eliminados con exito!",
      });
    },
    respuestaFinal(pqrsf) {
      if ((pqrsf.Email == "") | (pqrsf.Email == undefined)) {
        this.$alerError("Email no puede estar vacio!");
        return;
      }
      var regex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
      if (!regex.test(pqrsf.Email)) {
        swal({
          title: "Debe ingresar un Email valido",
          icon: "warning",
        });
        return;
      }
      this.adjuntos = this.$refs.adjuntos.files;
      let formData = new FormData();
      formData.append("respuesta", this.respuesta);
      formData.append("pqrsf_id", pqrsf.id);
      formData.append("paciente_id", pqrsf.Paciente_id);
      for (let i = 0; i < this.addAdjunto.length; i++) {
        formData.append(`add_adjunto[]`, this.addAdjunto[i]);
      }
      for (let i = 0; i < this.adjuntos.length; i++) {
        formData.append(`adjuntos[]`, this.adjuntos[i]);
      }
      const msg = "Esta seguro de enviar esta respuesta al usuario?";
      swal({
        title: msg,
        icon: "info",
        buttons: ["Cancelar", "Confirmar"],
        dangerMode: true,
      }).then((willDelete) => {
        if (willDelete) {
          this.dialogProcesando = true;
          axios
            .post(`/api/pqrsf/solution/${pqrsf.id}`, formData)
            .then((res) => {
              this.dialogProcesando = false;
              this.addAdjunto = [];
              this.getPresolutionFilter();
              this.closeGestion();
              this.$alerSuccess("Solución enviada con exito!");
            })
            .catch((err) => {
              this.dialogProcesando = false;
              this.$alerError("¡Debe ingresar todos los campos obligatorios!");
            });
        }
      });
    },
    openReasignar(pqr) {
      this.pqrsf = pqr;
      this.dialogReasignar = true;
    },
    closeReasignar() {
      this.dialogReasignar = false;
      this.motivoreasignar = "";
      this.responsable = [];
    },
    reasignar(pqrsf) {
      if ((pqrsf.Email == "") | (pqrsf.Email == undefined)) {
        this.$alerError("Email no puede estar vacio!");
        return;
      }
      var regex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
      if (!regex.test(pqrsf.Email)) {
        swal({
          title: "Debe ingresar un Email valido",
          icon: "warning",
        });
        return;
      }
      let formData = new FormData();
      formData.append("documento1", pqrsf.cc);
      formData.append("documento2", pqrsf.doc);
      formData.append("pqrsf_id", pqrsf.id);
      formData.append("estado", pqrsf.Estado);
      formData.append("paciente_id", pqrsf.Paciente_id);
      formData.append("motivo", this.motivoreasignar);
      for (let i = 0; i < this.responsable.length; i++) {
        formData.append(`responsable[]`, this.responsable[i]);
      }
      const msg = "Esta seguro de reasignar la PQRSF?";
      swal({
        title: msg,
        icon: "info",
        buttons: ["Cancelar", "Confirmar"],
        dangerMode: true,
      }).then((willDelete) => {
        if (willDelete) {
          this.dialogProcesando = true;
          axios
            .post(`/api/pqrsf/reasignar`, formData)
            .then((res) => {
              this.dialogProcesando = false;
              this.getPresolutionFilter();
              this.closeReasignar();
              this.$alerSuccess("Pqrsf reasignada con exito!");
              this.closeGestion();
            })
            .catch((err) => {
              this.dialogProcesando = false;
              this.$alerError("Debe llenar los campos obligatorios!");
            });
        }
      });
    },
    update(pqrsf) {
      if ((pqrsf.Email == "") | (pqrsf.Email == undefined)) {
        this.$alerError("Email no puede estar vacio!");
        return;
      }
      var regex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
      if (!regex.test(pqrsf.Email)) {
        swal({
          title: "Debe ingresar un Email valido",
          icon: "warning",
        });
        return;
      }
      let data = {
        pqrsf_id: pqrsf.id,
        Atr_calidad: pqrsf.Atr_calidad,
        Email: pqrsf.Email,
        Tiposolicitud: pqrsf.Tiposolicitud,
        Paciente_id: pqrsf.Paciente_id,
      };
      axios
        .post(`/api/pqrsf/reclasificar`, data)
        .then((res) => {
          this.getPresolutionFilter();
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
            icon: "success",
            title: "Pqrsf Actualizada con Exito!",
          });
        })
        .catch((err) => {
          this.$alerError("Error al actualizar intenta de nuevo!");
        });
    },

    showSubcategories() {
      axios.get("/api/pqrsf/allSubcategorias").then((res) => {
        this.allSubcategorias = res.data;
      });
    },

    remove_subcategoria(item) {
      this.pqrsfSubcategorias.splice(this.pqrsfSubcategorias.indexOf(item), 1);
      this.pqrsfSubcategorias = [...this.pqrsfSubcategorias];
    },

    saveSubcategories(pqrsf) {
      if (this.pqrsfSubcategorias == "") {
        this.$alerError("Debe ingresar una subcategoria");
        return;
      }
      let formData = new FormData();
      for (let i = 0; i < this.pqrsfSubcategorias.length; i++) {
        formData.append(`subcategoria[]`, this.pqrsfSubcategorias[i]);
      }

      axios
        .post(`/api/pqrsf/actualizarSubcategoria/${pqrsf.id}`, formData)
        .then((res) => {
          this.pqrsfSubcategorias = "";
          this.pqrsfSubcategories(pqrsf);
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
            icon: "success",
            title: "Subcategoria agregada con exito!",
          });
        });
    },

    getAreas() {
      axios.get("/api/pqrsf/allareas").then((res) => {
        this.allAreas = res.data;
      });
    },

    remove_area(item) {
      this.pqrareas.splice(this.pqrareas.indexOf(item), 1);
      this.pqrareas = [...this.pqrareas];
    },

    saveAreas(pqrsf) {
      if (this.pqrareas == "") {
        this.$alerError("Debe ingresar un area");
        return;
      }
      let formData = new FormData();
      for (let i = 0; i < this.pqrareas.length; i++) {
        formData.append(`area[]`, this.pqrareas[i]);
      }
      axios
        .post(`/api/pqrsf/ActualizarAreas/${pqrsf.id}`, formData)
        .then((res) => {
          this.areas = "";
          this.pqrsfArea(pqrsf);
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
            icon: "success",
            title: "Area agregada con exito!",
          });
        });
    },
  },
};
</script>
