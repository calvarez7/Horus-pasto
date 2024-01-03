<template>
  <div>
    <v-card>
      <v-card-title>
        <v-layout row wrap>
          <span> ASIGNADOS </span>
        </v-layout>
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
        :items="allAssigned"
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
                    <v-text-field v-model="pqrsf.Email" readonly label="EMAIL">
                    </v-text-field>
                  </v-flex>
                  <v-flex xs6>
                    <v-text-field v-model="pqrsf.IPS" readonly label="IPS">
                    </v-text-field>
                  </v-flex>
                  <v-flex xs12 v-if="pqrsfSubcategorias.length > 0">
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
                  <v-flex xs12 v-if="pqrareas.length > 0">
                    <v-subheader>AREA ACTUAL</v-subheader>
                    <v-item-group multiple>
                      <v-item v-for="(data, index) in pqrareas" :key="index">
                        <v-chip label :selected="data.selected">
                          {{ data.Nombre }}
                        </v-chip>
                      </v-item>
                    </v-item-group>
                    <v-divider class="my-2"></v-divider>
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
                    <v-text-field
                      v-model="pqrsf.Tiposolicitud"
                      readonly
                      label="TIPO DE SOLICITUD"
                    >
                    </v-text-field>
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
                    <v-text-field
                      readonly
                      v-model="pqrsf.Atr_calidad"
                      label="ATRIBUTO DE CALIDAD"
                    >
                    </v-text-field>
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
                    v-for="(GesPqr, index) in pqrsf.gestion_pqrsfs"
                    :key="`res-${index}`"
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
                      <v-flex v-show="GesPqr.adjuntos_pqrsf.length > 0">
                        <v-btn
                          v-for="(data, index) in GesPqr.adjuntos_pqrsf"
                          :key="index"
                        >
                          <a
                            :href="`${data.Ruta}`"
                            target="_blank"
                            style="text-decoration: none"
                          >
                            <v-icon color="dark">mdi-cloud-upload</v-icon
                            >Adjunto
                            {{ index + 1 }}
                          </a>
                        </v-btn>
                      </v-flex>
                    </v-flex>
                    <v-divider class="my-2" v-if="index < 1"></v-divider>
                  </v-flex>
                  <v-flex
                    xs12
                    v-for="(GesPqrR, index) in pqrsf.gestion_pqrsfs"
                    :key="`resp-${index}`"
                  >
                    <v-layout wrap>
                      <v-flex xs12 v-if="GesPqrR.Tipo_id == 8">
                        <v-flex xs12>
                          <v-textarea
                            v-model="GesPqrR.Motivo"
                            readonly
                            :label="`RESPUESTA`"
                          >
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
                                <v-btn>
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
                              </v-flex>
                            </v-layout>
                          </v-container>
                        </template>
                        <v-divider class="my-2"></v-divider>
                      </v-flex>
                    </v-layout>
                  </v-flex>
                  <v-toolbar
                    v-if="
                      (pqrsf.Estado == 'Inadecuado') |
                        (pqrsf.Estado == 'Reasignada')
                    "
                    color="primary"
                    dark
                    class="mb-4"
                  >
                    <v-toolbar-title>Reasignada</v-toolbar-title>
                  </v-toolbar>
                  <v-flex
                    xs12
                    v-for="(GesPqrR, index) in pqrsf.gestion_pqrsfs"
                    :key="`r-${index}`"
                  >
                    <v-layout wrap>
                      <v-flex xs12 v-if="GesPqrR.Tipo_id == 10">
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
                  <v-flex xs6>
                    <v-autocomplete
                      :items="permisoPqrsf"
                      item-text="name"
                      item-value="name"
                      label="QUIEN RESPONDE"
                      v-model="responsablerespuesta"
                    ></v-autocomplete>
                  </v-flex>
                  <v-flex xs12>
                    <v-divider class="my-2"></v-divider>
                    <v-textarea v-model="respuesta">
                      <template v-slot:label>
                        <div>RESPUESTA</div>
                      </template>
                    </v-textarea>
                  </v-flex>
                  <v-flex xs5>
                    <span class="red--text">
                      Importante:<br />
                      Devolver: no me pertenece<br />
                      Pacial: todavia esta gestionando<br />
                      Final: gestionado
                    </span>
                    <p></p>
                    <v-combobox
                      v-model="tiporespuesta"
                      :items="['PARCIAL', 'FINAL', 'DEVOLVER']"
                      chips
                      label="TIPO DE RESPUESTA"
                    >
                    </v-combobox>
                  </v-flex>
                  <v-flex xs12>
                    <input id="adjuntos" multiple ref="adjuntos" type="file" />
                  </v-flex>
                </v-layout>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="agregarRespuesta(pqrsf)"
            >Responder</v-btn
          >
          <v-btn color="error" @click="gestion = false">Salir</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
import Swal from "sweetalert2";
export default {
  name: "PqrsfAsignado",
  data() {
    return {
      search: "",
      loading: false,
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
      allAssigned: [],
      gestion: false,
      pqrsf: {
        permisospqrsf: [],
      },
      pqrsfSubcategorias: [],
      pqrsfDetallearticulo: [],
      pqrsfcups: [],
      pqrareas: [],
      pqrips: [],
      pqrempleados: [],
      respuesta: "",
      tiporespuesta: "PARCIAL",
      permisos: [],
      responsablerespuesta: "",
      adjuntos: [],
    };
  },
  mounted() {
    this.getAssignedFilter();
    this.getPermisos();
  },
  computed: {
    permisoPqrsf() {
      let permisosUser = this.permisos;
      let permisosPqrsf = this.pqrsf.permisospqrsf;
      let findPermisos = [];
      permisosPqrsf.forEach((pqrsf) => {
        let finded = permisosUser.find((user) => user.name == pqrsf.name);
        if (finded) findPermisos.push(finded);
      });
      if (findPermisos.length == 1)
        this.responsablerespuesta = findPermisos[0].name;
      return findPermisos;
    },
  },
  methods: {
    getPermisos() {
      axios.get("/api/pqrsf/permisos").then((res) => {
        this.permisos = res.data;
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
    semaforoTd(asignadas) {
      if (asignadas.Priorizacion == "Urgente") {
        if (asignadas.diasHabiles >= 1) {
          return "error white--text";
        } else {
          return "success white--text";
        }
      } else if (asignadas.Priorizacion == "Prioritaria") {
        if (asignadas.diasHabiles <= 1) {
          return "success white--text";
        } else if (asignadas.diasHabiles == 2) {
          return "yellow white--text";
        } else {
          return "error white--text";
        }
      } else if (asignadas.Priorizacion == "No Prioritaria") {
        if (asignadas.diasHabiles <= 3) {
          return "success white--text";
        } else if (asignadas.diasHabiles <= 6) {
          return "yellow white--text";
        } else {
          return "error white--text";
        }
      }
    },
    getAssignedFilter() {
      this.$emit("updateCount", this.canal);
      this.loading = true;
      axios.post("/api/pqrsf/assignedGestion").then((res) => {
        this.loading = false;
        this.allAssigned = res.data;
      });
    },
    manage(pqr) {
      this.pqrsf = pqr;
      this.gestion = true;
    },
    clearRespuesta() {
      this.respuesta = "";
      this.responsablerespuesta = "";
      this.tiporespuesta = "PARCIAL";
      this.$refs.adjuntos.value = "";
      this.gestion = false;
    },
    agregarRespuesta(pqrsf) {
      if (this.responsablerespuesta == "") {
        swal({
          title: "¡Debe seleccionar quien responde!",
          text: ` `,
          timer: 2000,
          icon: "error",
          buttons: false,
        });
        return;
      }
      this.adjuntos = this.$refs.adjuntos.files;
      let formData = new FormData();
      formData.append("pqrsf_id", pqrsf.id);
      formData.append("paciente_id", pqrsf.Paciente_id);
      formData.append("respuesta", this.respuesta);
      formData.append("responsablerespuesta", this.responsablerespuesta);
      formData.append("tiporespuesta", this.tiporespuesta);
      for (let i = 0; i < this.adjuntos.length; i++) {
        formData.append(`adjuntos[]`, this.adjuntos[i]);
      }
      axios
        .post(`/api/pqrsf/respuestas`, formData, {})
        .then((res) => {
          this.getAssignedFilter();
          this.clearRespuesta();
          this.$emit("updateCount", this.canal);
          this.$alerSuccess("¡Respuesta guardada con exito!");
        })
        .catch((err) => {
          this.$alerError("¡Debe ingresar todos los campos para respuesta!");
        });
    },
  },
};
</script>
