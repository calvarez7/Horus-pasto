<template>
  <v-card>
    <v-card-title class="headline success" style="color: white">
      <span class="title layout justify-center font-weight-light"
        >Datos Paciente</span
      >
    </v-card-title>
    <vDivider />
    <v-card-text>
      <v-container grid-list-md fluid class="pa-0">
        <v-layout wrap>
          <v-flex v-if="showReference" xs4>
            <vTextField
              label="Radicado"
              :readonly="!noreadonlyradicado"
              v-model="paciente.rzeuz"
              :rules="[(v) => !!v || 'Se debe ingresar el radicado.']"
            />
          </v-flex>
          <v-flex xs2>
            <vTextField label="Nombre" readonly v-model="paciente.Primer_Nom" />
          </v-flex>
          <v-flex xs2>
            <vTextField
              label="Apellido"
              readonly
              v-model="paciente.Primer_Ape"
            />
          </v-flex>
          <v-flex xs1>
            <vTextField label="Tipo" readonly v-model="paciente.Tipo_Doc" />
          </v-flex>
          <v-flex xs2>
            <vTextField label="Documento" readonly v-model="paciente.Num_Doc" />
          </v-flex>
          <v-flex xs1>
            <vTextField
              label="Edad"
              readonly
              v-model="paciente.Edad_Cumplida"
            />
          </v-flex>
          <v-flex xs2>
            <vTextField label="Regimen" readonly v-model="paciente.Ut" />
          </v-flex>
          <v-flex xs2>
            <vTextField
              label="Tipo"
              readonly
              v-model="paciente.Tipo_Afiliado"
            />
          </v-flex>
          <v-flex xs2 v-show="paciente.Ut === 'MEDIMAS'">
            <vTextField label="Nivel" readonly v-model="paciente.nivel" />
          </v-flex>
          <v-flex xs2>
            <vTextField label="Telefono" readonly v-model="paciente.Telefono" />
          </v-flex>
          <v-flex xs2>
            <vTextField label="Celular" readonly v-model="paciente.Celular1" />
          </v-flex>
          <v-flex xs2 v-show="paciente.Ut === 'MEDIMAS'">
            <vTextField
              label="Sede"
              readonly
              v-model="paciente.Mpio_Afiliado"
            />
          </v-flex>
          <v-flex xs4>
            <vTextField
              label="Celular 2 (opcional)"
              readonly
              v-model="paciente.Celular2"
            />
          </v-flex>
          <v-flex xs6>
            <vTextField label="Correo" readonly v-model="paciente.Correo1" />
          </v-flex>
          <v-flex xs6>
            <vTextField
              label="Correo 2 (opcional)"
              readonly
              v-model="paciente.Correo2"
            />
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
      </v-container>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  name: "PacienteData",
  props: {
    paciente: Object,
    noreadonlyradicado: {
      type: Boolean,
      default: () => false,
    },
    showReference: {
      type: Boolean,
      default: () => false,
    },
  },
  methods: {
    actualizarDatosPersonales() {
      this.$emit("actualizarDatosPersonales");
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
