<template>
  <v-layout>
    <v-flex shrink xs4 mr-1>
      <v-card max-height="100%" class="mb-3">
        <v-card-title class="headline success" style="color: white">
          <span class="title layout justify-center font-weight-light"
            >Buscar Paciente</span
          >
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <v-container grid-list-md fluid class="pa-0">
            <v-layout wrap align-center justify-center>
              <v-flex xs12>
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
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
    </v-flex>
    <v-flex shrink xs8 ml-1 v-if="paciente.id">
      <v-card max-height="100%" class="mb-3">
        <v-card-title class="headline success" style="color: white">
          <span class="title layout justify-center font-weight-light"
            >Datos Paciente</span
          >
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <v-container grid-list-md fluid class="pa-0">
            <v-layout wrap>
              <v-flex xs3>
                <v-text-field
                  v-model="paciente.Ut"
                  readonly
                  label="Entidad"
                ></v-text-field>
              </v-flex>
              <v-flex xs5>
                <v-text-field
                  v-show="paciente.Ut == 'REDVITAL UT'"
                  v-model="paciente.NombreIPS"
                  readonly
                  label="IPS"
                ></v-text-field>
                <v-text-field
                  v-show="paciente.Ut == 'MEDIMAS'"
                  v-model="paciente.Mpio_Afiliado"
                  readonly
                  label="IPS"
                ></v-text-field>
                <v-text-field
                  v-show="paciente.Ut == 'FERROCARRILES NACIONALES'"
                  v-model="paciente.Mpio_Afiliado"
                  readonly
                  label="IPS"
                ></v-text-field>
              </v-flex>
              <v-flex xs2>
                <v-text-field
                  v-model="paciente.tipo_categoria"
                  readonly
                  label="Régimen"
                >
                </v-text-field>
              </v-flex>
              <v-flex xs3>
                <v-text-field
                  v-model="paciente.Tipo_Afiliado"
                  label="Tipo"
                  readonly
                ></v-text-field>
              </v-flex>
              <v-flex xs1>
                <v-text-field v-model="paciente.nivel" readonly label="Nivel">
                </v-text-field>
              </v-flex>
              <v-flex xs4>
                <v-text-field
                  v-model="paciente.Medicofamilia"
                  readonly
                  label="Medico Familia"
                >
                </v-text-field>
              </v-flex>
              <v-flex xs3>
                <v-text-field
                  v-model="paciente.Primer_Nom"
                  readonly
                  label="Nombre"
                ></v-text-field>
              </v-flex>
              <v-flex xs3>
                <v-text-field
                  v-model="paciente.Primer_Ape"
                  readonly
                  label="Apellido"
                ></v-text-field>
              </v-flex>
              <v-flex xs1>
                <v-text-field
                  v-model="paciente.Sexo"
                  readonly
                  label="Sexo"
                ></v-text-field>
              </v-flex>
              <v-flex xs1>
                <v-select
                  :items="Tipo_Doc"
                  label="Tipo Documento"
                  v-model="paciente.Tipo_Doc"
                >
                </v-select>
              </v-flex>
              <v-flex xs3>
                <v-text-field
                  v-model="paciente.Num_Doc"
                  readonly
                  label="Número Documento"
                >
                </v-text-field>
              </v-flex>
              <v-flex xs3 v-if="paciente.Fecha_Naci">
                <v-text-field
                  v-model="paciente.Fecha_Naci.split(' ')[0]"
                  readonly
                  label="Fecha Nacimiento"
                ></v-text-field>
              </v-flex>
              <v-flex xs1>
                <v-text-field
                  v-model="paciente.Edad_Cumplida"
                  readonly
                  label="Edad"
                ></v-text-field>
              </v-flex>
              <v-flex xs4>
                <v-text-field
                  v-model="paciente.Departamento"
                  readonly
                  label="Departamento"
                >
                </v-text-field>
              </v-flex>
              <v-flex xs4>
                <v-text-field
                  v-model="paciente.Mpio_Afiliado"
                  readonly
                  label="Municipio"
                >
                </v-text-field>
              </v-flex>
              <v-flex xs4>
                <v-text-field
                  v-model="paciente.Telefono"
                  label="Telefono"
                ></v-text-field>
              </v-flex>
              <v-flex xs4>
                <v-text-field
                  v-model="paciente.Celular1"
                  label="Celular"
                  type="number"
                  maxlength="10"
                  oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                  onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
                  min="1"
                >
                </v-text-field>
              </v-flex>
              <v-flex xs6>
                <v-text-field
                  v-model="paciente.Celular2"
                  label="Celular 2 (opcional)"
                ></v-text-field>
              </v-flex>
              <v-flex xs6>
                <v-text-field
                  v-model="paciente.Correo1"
                  label="Correo"
                ></v-text-field>
              </v-flex>
              <v-flex xs6>
                <v-text-field
                  v-model="paciente.Correo2"
                  label="Correo 2 (opcional)"
                ></v-text-field>
              </v-flex>
              <v-flex xs6>
                <v-text-field
                  v-model="paciente.Direccion_Residencia"
                  label="Direccion Residencia"
                >
                </v-text-field>
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
                  v-if="
                    paciente.Tienetutela == 0 || paciente.Tienetutela == null
                  "
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
                  TIENE PORTABILIAD: Si
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
                  v-if="
                    paciente.abrazarte == false || paciente.abrazarte == null
                  "
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
            <v-layout row wrap v-show="paciente.id">
              <!-- <v-layout row wrap>
                                <v-flex xs6>
                                    <span class="error--text"><span style="color:black">ENTIDAD :</span> {{ paciente.Ut }}</span><br />
                                    <span class="error--text">Punto de atención : {{ paciente.NombreIPS }}</span><br />
                                    <span class="error--text">Médico de familia : {{paciente.Medicofamilia}}</span>
                                </v-flex>
                            </v-layout> -->
              <v-spacer></v-spacer>
              <v-btn color="warning" @click="actualizarDatosPersonales()" round
                >Actualizar</v-btn
              >
            </v-layout>
          </v-container>
        </v-card-text>
      </v-card>
      <v-card max-height="100%" v-show="paciente.id">
        <v-card-title class="headline success" style="color: white">
          <span class="title layout justify-center font-weight-light"
            >Gestión</span
          >
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <v-layout row wrap>
            <!-- inicio -->
            <v-container grid-list-xl>
              <v-layout wrap>
                <v-flex xs12 sm6 md6>
                  <vAutocomplete
                    item-text="Nombre"
                    item-value="id"
                    :items="tipo_citas"
                    label="Gestión"
                    v-model="data.Tipocita_id"
                  />
                </v-flex>
                <v-flex xs12 sm6 md6>
                  <vAutocomplete
                    :items="ambitos"
                    label="Ámbito de la atención"
                    v-model="data.ambito"
                  />
                </v-flex>
                <v-flex xs12 sm12 md12>
                  <v-alert
                    :value="true"
                    color="error"
                    icon="priority_high"
                    outline
                  >
                    Recuerde que si no indica el "Médico que Ordenó" toda la
                    transcripción sera cargada a su usuario y respectivamente
                    cobrada al mismo.
                  </v-alert>
                </v-flex>
                <v-flex xs12 sm12 md12>
                  <v-select
                    v-model="medicoSumimedical"
                    color="primary"
                    label="Selecciona la entidad que emite"
                    :items="ipsEmite"
                  >
                  </v-select>
                </v-flex>
                <v-flex
                  xs12
                  sm6
                  md6
                  v-if="
                    medicoSumimedical == 'Sumimedical' ||
                    medicoSumimedical == 'Clinica Victoriana'
                  "
                >
                  <vAutocomplete
                    :items="medicoSumi"
                    :item-text="
                      (medicoSumi) =>
                        medicoSumi.name +
                        ' ' +
                        medicoSumi.apellido +
                        ' (' +
                        medicoSumi.cedula +
                        ')'
                    "
                    item-value="id"
                    label="Médico que Ordenó"
                    v-model="data.medicoordeno"
                  />
                </v-flex>
                <v-flex xs12 sm6 md6 v-if="medicoSumimedical == 'Ips Externa'">
                  <v-select
                    label="Médico que Ordenó"
                    :items="IPSexterna"
                    v-model="data.medicoordeno"
                  >
                  </v-select>
                </v-flex>
                <v-flex xs12 sm6 md6 v-if="medicoSumimedical == 'Ips Externa'">
                  <!-- <v-text-field label="Entidad que Emite" v-model="data.entidademite"></v-text-field> -->
                  <v-autocomplete
                    v-model="data.entidademite"
                    append-icon="search"
                    label="Buscar prestador..."
                    :items="fullnameprestador"
                    item-text="fullname"
                    item-value="id"
                    hide-details
                    @input="buscarSedesPrestador()"
                  ></v-autocomplete>
                </v-flex>
                <v-flex xs12 sm6 md6 v-if="medicoSumimedical == 'Sumimedical'">
                  <!-- <v-text-field label="Entidad que Emite" v-model="data.entidademite"></v-text-field> -->
                  <v-autocomplete
                    v-model="data.entidademite"
                    append-icon="search"
                    label="Buscar prestador..."
                    :items="fullnameprestadorSumimedical"
                    item-text="fullname"
                    item-value="id"
                    hide-details
                    @input="buscarSedesPrestador()"
                  ></v-autocomplete>
                </v-flex>
                <v-flex
                  xs12
                  sm6
                  md6
                  v-if="medicoSumimedical == 'Clinica Victoriana'"
                >
                  <!-- <v-text-field label="Entidad que Emite" v-model="data.entidademite"></v-text-field> -->
                  <v-autocomplete
                    v-model="data.entidademite"
                    append-icon="search"
                    label="Buscar prestador..."
                    :items="fullnameprestadorClinicaVictoriana"
                    item-text="fullname"
                    item-value="id"
                    hide-details
                    @input="buscarSedesPrestador()"
                  ></v-autocomplete>
                </v-flex>
                <v-flex xs12 sm6 md6>
                  <v-autocomplete
                    :items="[
                      'Accidente de Trabajo',
                      'Detección temprana de enfermedad general',
                      'Detección temprana de enfermedad profesional',
                      'Diagnóstico',
                      'No aplica',
                      'Protección específica',
                      'Terapéutico',
                      'Teleconsulta',
                    ]"
                    label="Finalidad"
                    append-icon="search"
                    v-model="data.Finalidad"
                  >
                  </v-autocomplete>
                </v-flex>
                <v-flex xs12 sm6>
                  <v-autocomplete
                    v-model="data.Cie10_id"
                    append-icon="search"
                    :items="cieConcat"
                    item-disabled="customDisabled"
                    item-text="nombre"
                    item-value="id"
                    label="Diagnóstico"
                  >
                  </v-autocomplete>
                </v-flex>
                <v-flex
                  xs12
                  sm12
                  v-show="data.Tipocita_id == 3 || data.Tipocita_id == 6"
                >
                  <v-autocomplete
                    v-model="data.Cup_id"
                    append-icon="search"
                    :items="cupConcat"
                    item-text="nombre"
                    item-value="id"
                    label="Cups"
                    :search-input.sync="cup_search"
                  />
                </v-flex>
                <v-flex xs12 sm12 v-show="data.Tipocita_id == 6">
                  <v-container grid-list-xs>
                    <v-textarea
                      name="input-7-1"
                      label="Indicacion"
                      value=""
                      v-model="data.imagenologia.Indicacion"
                      autofocus
                    >
                    </v-textarea>
                    <v-textarea
                      name="input-7-1"
                      label="Tecnica"
                      value=""
                      v-model="data.imagenologia.Tecnica"
                    >
                    </v-textarea>
                    <v-textarea
                      name="input-7-1"
                      label="Hallazgos"
                      value=""
                      v-model="data.imagenologia.Hallazgos"
                    >
                    </v-textarea>
                    <v-textarea
                      name="input-7-1"
                      label="Conclusión"
                      value=""
                      v-model="data.imagenologia.Conclusion"
                    >
                    </v-textarea>
                    <v-textarea
                      name="input-7-1"
                      label="Notaclaratoria"
                      value=""
                      v-model="data.imagenologia.Notaclaratoria"
                    >
                    </v-textarea>
                  </v-container>
                </v-flex>
                <v-flex xs12 sm6>
                  <input
                    type="file"
                    id="file"
                    ref="myFiles"
                    class="custom-file-input"
                    multiple
                    v-on:change="onFilePicked"
                  />
                </v-flex>
                <v-flex xs12 sm6>
                  <span style="color: red"
                    >Los archivos deben tener un tamaño máximo de 10MB</span
                  >
                </v-flex>
              </v-layout>
            </v-container>
            <v-flex xs12>
              <v-textarea
                outline
                name="input-7-4"
                label="Observaciones"
                v-model="data.observaciones"
              >
              </v-textarea>
            </v-flex>
            <v-btn
              color="success"
              v-show="
                can('transcripcion_entidad') ||
                paciente.entidad_id == 1 ||
                paciente.entidad_id == 3 ||
                paciente.entidad_id == 5
              "
              dark
              @click="guardarTranscripcion()"
              >Guardar</v-btn
            >
          </v-layout>
        </v-card-text>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
import { mapGetters } from "vuex";
import moment from "moment";
import { EventBus } from "../../../event-bus.js";
import axios from "axios";

export default {
  components: {},
  data() {
    return {
      IPSexterna: ["Medico general externo", "Medico especialista externo"],
      ipsEmite: ["Sumimedical", "Clinica Victoriana", "Ips Externa"],
      medicoSumimedical: false,
      radiologos: [],
      medicoSumi: [],
      Tipo_Doc: ["CC", "CE", "PA", "RC", "TI"],
      ambitos: ["Ambulatorio", "Hospitalario", "Urgencias"],
      files: "",
      cedula_paciente: "",
      object: {},
      citaPaciente: "",
      paciente: {
        Primer_Nom: "",
        Primer_Ape: "",
        Tipo_Doc: "",
        Num_Doc: "",
        entidad_id: 0,
      },
      ubicacion: {
        Telefono: "",
        Celular1: "",
        Celular2: "",
        Correo1: "",
        Correo2: "",
        Edad_Cumplida: "",
        Direccion_Residencia: "",
        Tipo_Doc: "",
      },
      data: {
        medicoordeno: "",
        entidademite: "",
        Finalidad: "",
        observaciones: "",
        Cie10_id: "",
        Tipocita_id: 1,
        adjunto: null,
        ambito: "Ambulatorio",
        Cup_id: null,
        imagenologia: {
          Indicacion: null,
          Hallazgos: null,
          Conclusion: null,
          Notaclaratoria: null,
          Tecnica: null,
          medico_imagenologia: null,
        },
      },
      cie10s: [],
      cups: [],
      cie10Array: [],
      tipo_citas: [],
      cup_search: "",
      Prestador_id: "",
      prestadores: [],
    };
  },
  computed: {
    ...mapGetters(["can"]),
    fullnameprestador() {
      return this.prestadores
        .filter((item) => item.id != 67 && item.id != 710)
        .map((prestador) => {
          return {
            id: prestador.id,
            fullname: `${prestador.Nit} - ${prestador.Nombre}`,
          };
        });
    },
    fullnameprestadorSumimedical() {
      return this.prestadores
        .filter((item) => item.id == 67)
        .map((prestador) => {
          return {
            id: prestador.id,
            fullname: `${prestador.Nit} - ${prestador.Nombre}`,
          };
        });
    },
    fullnameprestadorClinicaVictoriana() {
      return this.prestadores
        .filter((item) => item.id == 710)
        .map((prestador) => {
          return {
            id: prestador.id,
            fullname: `${prestador.Nit} - ${prestador.Nombre}`,
          };
        });
    },
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
    cupConcat() {
      return this.cups.map((cup) => {
        return {
          id: cup.id,
          nombre: `${cup.Codigo} - ${cup.Nombre}`,
          customDisabled: false,
        };
      });
    },
  },
  mounted() {
    this.fetchCie10s();
    this.fetchGestion();
    this.fetchPrestadores();
    this.fetchRadiologos();
  },
  watch: {
    cup_search() {
      if (this.cup_search && this.cup_search.length === 3) {
        this.search_cup();
      }
    },
  },
  methods: {
    fetchPrestadores() {
      axios
        .get("/api/prestador/all")
        .then((res) => (this.prestadores = res.data))
        .catch((err) => console.log(err));
    },
    fetchRadiologos() {
      axios
        .get("/api/imagenologia/radiologos")
        .then((res) => (this.radiologos = res.data))
        .catch((err) => console.log(err));
    },
    buscarSedesPrestador() {
      axios
        .post("/api/sedeproveedore/all", {
          Prestador_id: this.Prestador_id,
        })
        .then((res) => {
          this.sedesprestadores = res.data;
        })
        .catch((err) => console.log(err));
    },
    getPDFMedicamentos() {
      return (this.object = {
        Primer_Nom: this.paciente.Primer_Nom,
        Primer_Ape: this.paciente.Primer_Ape,
        Tipo_Doc: this.paciente.Tipo_Doc,
        Num_Doc: this.paciente.Num_Doc,
        type: "formula",
      });
    },
    search_paciente() {
      if (!this.cedula_paciente) return;

      localStorage.setItem("PacienteCedula", this.cedula_paciente);

      axios
        .get(`/api/paciente/trascripciones/${this.cedula_paciente}`)
        .then((res) => {
          if (res.data.paciente) {
            axios
              .get(
                "/api/entidades/validar/" +
                  res.data.paciente.entidad_id +
                  "/generar_ordenes"
              )
              .then((res2) => {
                if (res2.data.acceso) {
                  this.fechMedicos();
                  this.paciente = res.data.paciente;
                  this.sede_selected = this.paciente.IPS;
                } else {
                  swal({
                    title: res2.data.message,
                    text: ` `,
                    icon: "error",
                  });
                }
              });
          }
          if (res.data.message) this.showMessage(res.data.message);
        });
    },
    search_cup() {
      axios
        .post("/api/cups/search", {
          search: this.cup_search,
        })
        .then((res) => {
          this.cups = res.data;
        })
        .catch((err) => console.log(err.response));
    },
    actualizarDatosPersonales() {
      axios
        .put(
          `/api/paciente/edit_ubicacion_data/${this.paciente.id}`,
          this.paciente
        )
        .then((res) => {
          swal({
            title: "¡Datos Actualizados!",
            text: ` `,
            timer: 2000,
            icon: "success",
            buttons: false,
          });
        });
    },
    fetchGestion() {
      axios
        .get("/api/tipocita/all")
        .then((res) => (this.tipo_citas = res.data.filter((f) => f.id == 1)))
        .catch((err) => console.log(err.response));
    },
    async guardarTranscripcion() {
        if(!this.data.entidademite){
            swal({
                title: "Por favor seleccione un Prestador!!",
                text: "Requerido",
                icon: "error",
            });
            return;
        }
      if (!this.data.Tipocita_id) {
        swal({
          title: "Por favor seleccione un tipo de gestión!!",
          text: "Requerido",
          icon: "error",
        });
        return;
      }
      if (!this.data.Finalidad) {
        swal({
          title: "Es necesario seleccionar una finalidad!",
          text: "Requerido",
          timer: 4000,
          icon: "error",
          buttons: false,
        });
        return;
      }
      if (this.data.Tipocita_id == 3) {
        if (!this.data.medicoordeno) {
          swal({
            title: "Requerido medico que Ordena",
            text: "Requerido",
            timer: 4000,
            icon: "error",
            buttons: false,
          });
          return;
        }
        if (!this.data.entidademite) {
          swal({
            title: "Requerido el Prestador!",
            text: "Requerido",
            timer: 4000,
            icon: "error",
            buttons: false,
          });
          return;
        }
        if (!this.data.Cup_id) {
          swal({
            title: "Requerido el CUP!",
            text: "Requerido",
            timer: 4000,
            icon: "error",
            buttons: false,
          });
          return;
        }
      }
      if (!this.data.Cie10_id) {
        swal({
          title: "Por favor seleccione un diagnostico principal!",
          text: "Requerido",
          timer: 4000,
          icon: "error",
          buttons: false,
        });
        return;
      }

      if (!this.data.medicoordeno) {
        swal({
          title: "Gestión",
          text: "El campo 'Médico que Ordenó' es requerido",
          timer: 4000,
          icon: "error",
          buttons: false,
        });
        return;
      }

      if (this.files.length <= 0) {
        swal({
          title: "Gestión",
          text: "Debe adjuntar por lo menos un archivo!",
          timer: 4000,
          icon: "error",
          buttons: false,
        });
        return;
      }

      if (this.medicoSumimedical == false) {
        var regex =
          /^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/g;
        if (!regex.test(this.data.medicoordeno)) {
          swal({
            title: "Gestión",
            text: "El campo 'Médico que Ordenó' debe contener un nombre valido!",
            timer: 4000,
            icon: "error",
            buttons: false,
          });
          return;
        }

        if (this.data.medicoordeno.length < 3) {
          swal({
            title: "Gestión",
            text: "El campo 'Médico que Ordenó' debe contener por lo menos 3 caracteres!",
            timer: 4000,
            icon: "error",
            buttons: false,
          });
          return;
        }
      }

      if (this.paciente.id) {
        this.paciente.medicoordeno = this.data.medicoordeno;
        this.paciente.entidademite = this.data.entidademite;
        this.paciente.finalidad = this.data.Finalidad;
        this.paciente.observaciones = this.data.observaciones;

        this.setCie10();

        await axios
          .post(`/api/cita/create_cita_paciente/${this.paciente.id}`, this.data)
          .then(async (res) => {
            this.citaPaciente = res.data;
            localStorage.setItem("citapaciente_id", this.citaPaciente.id);

            this.setDiagnostico();

            if (this.files.length > 0) {
              let formData = new FormData();

              for (var i = 0; i < this.files.length; i++) {
                let file = this.files[i];

                formData.append("files[" + i + "]", file);
              }

              await axios
                .post(`/api/file/setFiles/${this.citaPaciente.id}`, formData, {
                  headers: {
                    "Content-Type": "multipart/form-data",
                  },
                })
                .then(function () {
                  console.log("SUCCESS!!");
                })
                .catch((err) => {
                  swal({
                    title: "Incapacidad",
                    text: err.response,
                    timer: 2000,
                    icon: "error",
                    buttons: false,
                  });
                  console.log(err.response);
                });
            }

            this.$router.push(
              "/transcripcion?cita_paciente=" + this.citaPaciente.id
            );
            EventBus.$emit("call-order", this.paciente);
            this.clearDataTrnscripcion();
          });
      } else {
        swal({
          title: "Transcripción",
          text: "Por favor ingrese un paciente!",
          timer: 2000,
          icon: "error",
          buttons: false,
        });
      }
    },
    showMessage(message) {
      swal({
        title: `${message}`,
        icon: "warning",
      });
    },
    clearFields() {
      (this.cedula_paciente = ""),
        (this.paciente = {
          Primer_Nom: "",
          Primer_Ape: "",
          Tipo_Doc: "",
          Num_Doc: "",
          Edad_Cumplida: "",
        });
      this.cedula_paciente = "";
      this.ubicacion = {
        Telefono: "",
        Celular1: "",
        Celular2: "",
        Correo1: "",
        Correo2: "",
      };
    },
    onFilePicked() {
      this.files = this.$refs.myFiles.files;
    },
    fetchCie10s() {
      axios.get("/api/cie10/all").then((res) => {
        this.cie10s = res.data;
      });
    },
    setDiagnostico() {
      let data = {
        Cie10_id: this.data.Cie10_id,
      };

      axios
        .post(`/api/cie10/setDiagnostic/${this.citaPaciente.id}`, data)
        .then((res) => {
          console.log("res", res.data);
        });
    },
    setCie10() {
      let object = this.cie10s.find((cie) => cie.id == this.data.Cie10_id);
      this.paciente.Cie10_id = object.Codigo_CIE10;
    },
    fechMedicos() {
      axios
        .get("/api/user/medicoSumi")
        .then((res) => {
          console.log(res.data);
          this.medicoSumi = res.data;
        })
        .catch((err) => console.log(err));
    },
    changeMedico() {
      this.data.medicoordeno = "";
    },
    clearDataTrnscripcion() {
      for (const key in this.data) {
        this.data[key] = "";
      }
      this.medicoSumimedical = "";
      this.clearFields();
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
