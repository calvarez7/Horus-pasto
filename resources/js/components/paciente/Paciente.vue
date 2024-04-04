<template>
  <div>
    <v-form>
      <v-container grid-list-md fluid class="pa-0">
        <v-card-title
          class="headline"
          style="color: white; background-color: #0074a6"
        >
          <span class="title layout justify-center font-weight-light"
            >Datos del paciente</span
          >
        </v-card-title>
        <v-layout row wrap>
          <v-flex xs12 sm6 md3 v-show="idShow.NombreCompleto">
            <v-text-field
              v-model="paciente.NombreCompleto"
              label="Nombres y Apellidos"
              readonly
            >
            </v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.Tipo_Doc">
            <v-text-field
              v-model="paciente.Tipo_Doc"
              label="Tipo de Documento de identificación"
              readonly
            >
            </v-text-field>
          </v-flex>

          <v-flex xs12 sm6 md3 v-show="idShow.Num_Doc">
            <v-text-field
              v-model="paciente.Num_Doc"
              label="Documento de Identificación"
              readonly
            >
            </v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.Fecha_Naci">
            <v-text-field
              v-model="paciente.Fecha_Naci"
              label="Fecha de Nacimiento"
              readonly
            >
            </v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.pais_nacimiento">
            <v-autocomplete
              v-model="paciente.pais_nacimiento"
              append-icon="search"
              :items="paises"
              item-text="nombre"
              item-value="id"
              label="País de Nacimiento"
            >
            </v-autocomplete>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.municipio_nacimiento">
            <v-autocomplete
              label="Municipio de Nacimiento"
              append-icon="search"
              :items="municipios"
              item-text="Nombre"
              item-value="id"
              v-model="paciente.municipio_nacimiento"
            >
            </v-autocomplete>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.Edad_Cumplida">
            <v-text-field
              v-model="paciente.Edad_Cumplida"
              label="Edad"
              readonly
            >
            </v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.ciclo_vida">
            <v-text-field v-model="ciclo_vida" label="Ciclo vital" readonly>
            </v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.Sexo">
            <v-text-field v-model="paciente.Sexo" label="Sexo"> </v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.RH">
            <v-select v-model="paciente.RH" :items="rh" label="Grupo sanguíneo">
            </v-select>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.Tipo_Afiliado">
            <v-text-field
              v-model="paciente.Tipo_Afiliado"
              label="Regimen de Afiliación"
              readonly
            >
            </v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.Ut">
            <v-text-field
              v-model="paciente.NombreIPS"
              label="Nombre IPS"
              readonly
            >
            </v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.Departamento">
            <v-autocomplete
              :items="departamento"
              item-text="departamento"
              v-model="paciente.Departamento"
              label="Departamento de Residencia"
            >
            </v-autocomplete>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.regional">
            <v-select
              v-model="paciente.regional"
              :items="regional"
              label="Division"
            >
            </v-select>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.Mpio_Residencia">
            <v-autocomplete
              label="Municipio de Residencia"
              :items="municipios"
              item-text="Nombre"
              class="field"
              item-value="Nombre"
              v-model="paciente.Mpio_Residencia"
              :placeholder="paciente.Mpio_Residencia"
            >
            </v-autocomplete>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.Direccion_Residencia">
            <v-text-field
              v-model="paciente.Direccion_Residencia"
              label="Dirección"
            >
            </v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md2 v-show="idShow.Telefono">
            <v-text-field
              v-model="paciente.Telefono"
              type="number"
              maxlength="12"
              label="Teléfonos"
            >
            </v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md2 v-show="idShow.Celular1">
            <v-text-field
              v-model="paciente.Celular1"
              label="Celular"
              type="number"
              maxlength="10"
              oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
              onkeypress="return (event.charCode >= 48 && event.charCode <= 57)"
              min="1"
            ></v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.Correo1">
            <v-text-field v-model="paciente.Correo1" label="Correo electrónico">
            </v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.Estrato">
            <v-select
              v-model="paciente.Estrato"
              :items="estrato"
              label="Estrato Socioeconómico"
            >
            </v-select>
          </v-flex>
          <v-flex xs12 sm6 md2 v-show="idShow.tipo_vivienda">
            <v-select
              v-model="paciente.tipo_vivienda"
              :items="tipovivienda"
              label="Tipo de vivienda"
            >
            </v-select>
          </v-flex>
          <v-flex xs12 sm6 md5 v-show="idShow.zona_vivienda">
            <v-select
              v-model="paciente.zona_vivienda"
              :items="zona"
              label="Zona donde se encuentra la vivienda de residencia"
            >
            </v-select>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.numero_habitaciones">
            <v-select
              v-model="paciente.numero_habitaciones"
              :items="estrato"
              label="Número de habitaciones"
            >
            </v-select>
          </v-flex>
          <v-flex xs12 sm6 md4 v-show="idShow.numero_miembros">
            <v-select
              v-model="paciente.numero_miembros"
              :items="estrato"
              label="Número de miembros que conforman el hogar"
            >
            </v-select>
          </v-flex>
          <v-flex xs12 sm6 md2 v-show="idShow.acceso_vivienda">
            <v-select
              v-model="paciente.acceso_vivienda"
              :items="acceso"
              label="Acceso a la vivienda"
            >
            </v-select>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.seguridad_vivienda">
            <v-select
              v-model="paciente.seguridad_vivienda"
              :items="seguridad"
              label="Seguridad de la vivienda"
            >
            </v-select>
          </v-flex>
          <v-flex xs12 sm6 md2 v-show="idShow.mascota">
            <v-select
              v-model="paciente.mascota"
              :items="mascotas"
              label="Mascotas"
            >
            </v-select>
          </v-flex>
          <v-flex xs12 sm6 md5 v-show="idShow.hogar_con_agua">
            <v-select
              v-model="paciente.hogar_con_agua"
              :items="sino"
              label="¿En su hogar tiene agua potable y servicio de alcantarillado?"
            >
            </v-select>
          </v-flex>
          <v-flex xs12 sm6 md5 v-show="idShow.prepara_comida_con">
            <v-select
              v-model="paciente.prepara_comida_con"
              :items="preparacion"
              label="La preparación de alimentos en su hogar se realiza con: "
            >
            </v-select>
          </v-flex>
          <v-flex xs12 sm6 md4 v-show="idShow.vivienda_con_energia">
            <v-select
              v-model="paciente.vivienda_con_energia"
              :items="sino"
              label="¿La vivienda cuenta con energía eléctrica?"
            >
            </v-select>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.Nivelestudio">
            <v-select
              v-model="paciente.Nivelestudio"
              :items="niveleducativo"
              label="Nivel Educativo"
            >
            </v-select>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.Ocupacion">
            <v-text-field v-model="paciente.Ocupacion" label="Ocupación">
            </v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.Laboraen">
            <v-text-field v-model="paciente.Laboraen" label="Donde labora">
            </v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.Mpio_Labora">
            <v-autocomplete
              label="Municipio donde labora"
              :items="municipios"
              item-text="Nombre"
              class="field"
              item-value="Nombre"
              v-model="paciente.Mpio_Labora"
              :placeholder="paciente.Mpio_Labora"
            >
            </v-autocomplete>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.estado_civil">
            <v-select
              v-model="paciente.estado_civil"
              :items="estadocivil"
              label="Estado Civil"
            >
            </v-select>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.Etnia">
            <v-select v-model="paciente.Etnia" :items="etnia" label="Etnia">
            </v-select>
          </v-flex>
          <v-flex xs12 sm6 md3 v-show="idShow.religion">
            <v-text-field v-model="paciente.religion" label="Religión">
            </v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md4 v-show="idShow.Nombreacompanante">
            <v-text-field
              v-model="paciente.Nombreacompanante"
              label="Nombre del acompañante"
            >
            </v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md4 v-show="idShow.Telefonoacompanante">
            <v-text-field
              v-model="paciente.Telefonoacompanante"
              label="Teléfono del acompañante"
              type="number"
              maxlength="12"
            >
            </v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md4 v-show="idShow.Parentesco">
            <v-select
              v-model="paciente.Parentesco"
              :items="parentesco"
              label="Parentesco"
            >
            </v-select>
          </v-flex>
          <v-flex xs12 sm6 md4 v-show="idShow.Nombreresponsable">
            <v-text-field
              v-model="paciente.Nombreresponsable"
              label="Nombre del Responsable"
            >
            </v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md4 v-show="idShow.Telefonoresponsable">
            <v-text-field
              v-model="paciente.Telefonoresponsable"
              label="Teléfono del Responsable"
              type="number"
              maxlength="12"
            >
            </v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md3>
            <v-select
              v-model="paciente.Discapacidad"
              :items="tipodiscapacidad"
              label="¿Tiene alguna Discapacitadad?"
            >
            </v-select>
            <v-flex>
              <v-text-field
                label="¿Cual?"
                v-if="paciente.Discapacidad == 'Otro'"
                v-model="paciente.Otradiscapacidad"
              ></v-text-field>
            </v-flex>
          </v-flex>
          <v-flex xs12 sm6 md3>
            <v-select
              v-model="paciente.Grado_Discapacidad"
              :items="gradodiscapacidad"
              label="¿Grado de Discapacidad?"
            >
            </v-select>
          </v-flex>
          <v-flex
            xs8
            sm6
            md4
            v-show="idShow.nombre_pareja & (datosCita.Tipo_agenda == '23')"
          >
            <v-text-field
              v-model="paciente.nombre_pareja"
              label="Nombre de la pareja"
            >
            </v-text-field>
          </v-flex>
          <v-flex xs12 sm6 md5 v-show="datosCita.Tipo_agenda == '23'">
            <v-select
              v-model="paciente.pareja_actual_padre"
              :items="sino"
              label="Su pareja es el padre de la actual gestación "
            >
            </v-select>
          </v-flex>
          <v-flex
            xs12
            sm6
            md5
            v-show="idShow.ocupacion_padre & (datosCita.Tipo_agenda == '23')"
          >
            <v-text-field
              v-model="paciente.ocupacion_padre"
              label="Ocupación del padre?"
            >
            </v-text-field>
          </v-flex>
          <v-flex
            xs12
            sm6
            md4
            v-show="
              idShow.grupo_sanguineo_padre & (datosCita.Tipo_agenda == '23')
            "
          >
            <v-select
              v-model="paciente.grupo_sanguineo_padre"
              :items="rh"
              label="Grupo sanguíneo del padre"
            >
            </v-select>
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
                paciente.portabilidad == false || paciente.portabilidad == null
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
                paciente.domiciliaria == false || paciente.domiciliaria == null
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
        <v-btn color="success" @click="updatePaciente()"
          >Guardar y Seguir</v-btn
        >
      </v-container>
    </v-form>
    <v-dialog v-model="preloadHistoria" persistent width="300">
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
  name: "Paciente",
  props: {
    datosCita: Object,
  },
  data() {
    return {
      preloadHistoria: false,
      ciclo_vida: "",
      paciente: {},
      regional: ["Putumayo", "Pasto"],
      rh: ["A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"],
      estrato: ["1", "2", "3", "4", "5", "6"],
      tipovivienda: ["Propia", "Arrendada", "Familiar", "Usufructo"],
      zona: ["Urbana", "Rural"],
      acceso: ["Facil", "Dificil"],
      seguridad: ["Buena", "Regular", "Mala"],
      preparacion: [
        "Gas pipeta",
        "Gas natural",
        "Electrica",
        "Leña",
        "Combustible",
      ],
      sino: ["Si", "No"],
      mascotas: ["Perros", "Gatos", "Otros"],
      niveleducativo: [
        "Analfabeta",
        "Inicial",
        "Preescolar",
        "Primaria",
        "Bachiller",
        "Tecnólogo/Técnico",
        "Universitario",
        "Postgrado (Especialización, Maestría, Doctorado)",
      ],
      estadocivil: [
        "Soltero",
        "Casado",
        "Union Libre",
        "Viudo",
        "Separado",
        "Divorciado",
      ],
      etnia: [
        "Afrocolombiano(a)",
        "Palenquero",
        "Indígena",
        "Afrodescendiente",
        "Raizal",
        "Room",
        "Sin pertenencia étnica",
      ],
      parentesco: [
        "Madre",
        "Padre",
        "Hijo(a)",
        "Abuelo(a)",
        "Tío(a)",
        "Primo(a)",
        "Otros",
      ],
      tipodiscapacidad: [
        "Fisica",
        "Auditiva",
        "Visual",
        "intelectual",
        "Sordoceguera",
        "Multiple",
        "Psicosocial (Mental)",
        "Otro",
        "Sin discapacidad",
      ],
      gradodiscapacidad: [
        "Nula",
        "Leve",
        "Moderada",
        "Grave",
        "Muy grave",
        "Permanente",
        "No Aplica",
      ],
      paises: [],
      municipios: [],
      departamento: [],
      idShow: {
        NombreCompleto: true,
        Tipo_Doc: true,
        Num_Doc: true,
        Fecha_Naci: true,
        pais_nacimiento: true,
        municipio_nacimiento: true,
        Edad_Cumplida: true,
        Sexo: true,
        ciclo_vida: true,
        RH: true,
        Tipo_Afiliado: true,
        Ut: true,
        Departamento: true,
        regional: true,
        Mpio_Residencia: true,
        Direccion_Residencia: true,
        Telefono: true,
        Celular1: true,
        Correo1: true,
        Estrato: true,
        tipo_vivienda: true,
        zona_vivienda: true,
        numero_habitaciones: true,
        numero_miembros: true,
        acceso_vivienda: true,
        seguridad_vivienda: true,
        hogar_con_agua: true,
        prepara_comida_con: true,
        vivienda_con_energia: true,
        mascota: true,
        Nivelestudio: true,
        Ocupacion: true,
        Laboraen: true,
        Mpio_Labora: true,
        estado_civil: true,
        Etnia: true,
        religion: true,
        Nombreacompanante: true,
        Telefonoacompanante: true,
        Nombreresponsable:true,
        Telefonoresponsable: true,
        Parentesco: true,
        nombre_pareja: true,
        pareja_actual_padre: true,
        ocupacion_padre: true,
        grupo_sanguineo_padre: true,
        Discapacidad: true,
        Grado_Discapacidad: true,
        Otradiscapacidad: true,
      },
    };
  },
  created() {
    this.validateInput();
    this.fetchpaciente();
    this.fetchMunicipios();
    this.fetchDepartamentos();
    this.fetchPaises();
  },
  methods: {
    fetchPaises() {
      axios
        .get("/api/covid/paises")
        .then((res) => {
          this.paises = res.data;
        })
        .catch((err) => console.log(err));
    },

    async fetchpaciente() {
      this.preloadHistoria = true;
      const data = await axios.get(
        `/api/paciente/pacienteEnabled/${this.datosCita.paciente_id}`
      );
      this.paciente = data.data;
      this.paciente.municipio_nacimiento = parseInt(
        data.data.municipio_nacimiento
      );
      this.paciente.pais_nacimiento = parseInt(data.data.pais_nacimiento);
      this.ciclo_vida = this.datosCita.ciclo_vital;
      this.preloadHistoria = false;
    },

    fetchMunicipios() {
      axios.get("/api/municipio/all").then((res) => {
        this.municipios = res.data;
      });
    },
    fetchDepartamentos() {
      axios
        .get("/api/domiciliaria/departamento")
        .then((res) => {
          this.departamento = res.data;
        })
        .catch((err) => console.log(err));
    },

    updatePaciente() {
      if (!this.paciente.pais_nacimiento) {
        this.$alerError("Pais de nacimiento del paciente es requerido!");
        return;
      } else if (!this.paciente.municipio_nacimiento) {
        this.$alerError("Municipio de nacimiento del paciente es requerido!");
        return;
      }
      this.paciente.ciclo_vida = this.ciclo_vida;
      axios.put("/api/paciente/updatePaciente/", this.paciente).then((res) => {
        this.$alertHistoria(
          '<span style="color:#fff">' + res.data.message + "<span>"
        );
        this.$emit("respuestaComponente");
      });
    },

    validateInput() {
      switch (this.datosCita.ciclo_vital) {
        case "1ra Infancia":
          this.idShow.Laboraen = false;
          this.idShow.Mpio_Labora = false;
          this.idShow.nombre_pareja = false;
          break;
        case "Infancia":
          this.idShow.Laboraen = false;
          this.idShow.Mpio_Labora = false;
          this.idShow.nombre_pareja = false;
          break;
        case "Adolescencia":
          break;
        case "Juventud":
          break;
        case "Adultez":
          break;
        case "Vejez":
          break;
      }
    },
  },
};
</script>

<style>
.field input::placeholder {
  color: black !important;
}
</style>