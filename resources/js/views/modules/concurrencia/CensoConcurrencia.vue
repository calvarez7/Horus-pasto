<template>
  <div>
    <v-toolbar flat color="white">
      <v-toolbar-title>Censo Concurrencia</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-flex xs12 sm4 d-flex>
        <span :key="index" v-for="(light, index) in lightMenu">
          <v-icon :color="light.color">indeterminate_check_box</v-icon>
          {{ light.text }} : {{ light.valor }}
        </span>
      </v-flex>
      <v-btn color="primary" dark @click="dialog = true"> Cargar Censo </v-btn>
      <v-btn color="primary" dark @click="descargarPlantilla()">
        Descargar Plantilla</v-btn
      >
    </v-toolbar>
    <v-dialog v-model="dialog" persistent max-width="800">
      <v-card>
        <v-form>
          <v-card-title>Cargar archivo de censo</v-card-title>
          <v-card-text>
            <v-layout row wrap>
              <v-flex xs3>
                <v-btn color="primary" dark outline round @click="uploadFiles">
                  <v-icon left>backup</v-icon>
                  Subir archivos
                </v-btn>
              </v-flex>
              <v-flex xs9 px-2>
                <input
                  hidden
                  name="file"
                  ref="files"
                  type="file"
                  accept=".xlsx"
                  @change="setName"
                />
                <VTextField
                  disabled
                  name="name"
                  :rules="[
                    (v) => !!v || 'Se necesitan cargar archivos para validar',
                  ]"
                  single-line
                  v-model="namefile"
                  @click="uploadFiles"
                />
              </v-flex>
            </v-layout>
          </v-card-text>
          <v-card-actions>
            <VSpacer />
            <v-btn color="error" @click="(dialog = false), clear()">
              Cerrar
            </v-btn>
            <v-btn color="primary" @click="cargar()"> Cargar </v-btn>
          </v-card-actions>
        </v-form>
      </v-card>
    </v-dialog>
    <v-tabs centered color="cyan" dark icons-and-text>
      <v-tabs-slider color="yellow"></v-tabs-slider>

      <v-tab href="#tab-1">
        Censo Actual
        <v-icon>account_box</v-icon>
      </v-tab>

      <v-tab href="#tab-2">
        Graficas
        <v-icon>mdi-chart-line</v-icon>
      </v-tab>

      <v-tab href="#tab-3">
        Informes
        <v-icon>mdi-table-edit</v-icon>
      </v-tab>

      <v-tab-item :value="'tab-1'">
        <v-card flat>
          <v-data-table :headers="headers" :items="listaCenso">
            <template v-slot:items="props">
              <td>{{ props.item.ips }}</td>
              <td class="text-xs-right">{{ props.item.atencion_admision }}</td>
              <td class="text-xs-right">
                {{ props.item.tipo_identficicacion }}
              </td>
              <td class="text-xs-right">
                <v-chip
                  v-if="props.item.id_RegistroConcurrencia == null"
                  color="error"
                  dark
                  >{{ props.item.numero_identificacion }}</v-chip
                >
                <v-chip v-else color="success" dark>{{
                  props.item.numero_identificacion
                }}</v-chip>
              </td>
              <td class="text-xs-right">{{ props.item.codigo }}</td>
              <td class="text-xs-right">{{ props.item.diganostico_cie10 }}</td>
              <td class="text-xs-right">{{ props.item.fecha_ingreso }}</td>
              <td class="text-xs-right">{{ props.item.nombres_apellidos }}</td>
              <td class="text-xs-right">{{ props.item.ubicacion }}</td>
              <td class="text-xs-right">{{ props.item.especialidad }}</td>
              <td class="text-xs-right">{{ props.item.dias_estancia }}</td>
              <td class="text-xs-right">{{ props.item.via_ingreso }}</td>
              <td class="text-xs-right">{{ props.item.Asegurador }}</td>
              <td class="text-xs-right">{{ props.item.grupo_diagnostico }}</td>
              <td class="text-xs-right">{{ props.item.ips_basica }}</td>
              <td class="text-xs-right">{{ props.item.Alta }}</td>
              <td class="text-xs-right">{{ props.item.areas_reporte }}</td>
              <td class="text-xs-right">{{ props.item.pym }}</td>
            </template>
          </v-data-table>
        </v-card>
      </v-tab-item>
      <v-tab-item :value="'tab-2'">
        <v-card flat> </v-card>
      </v-tab-item>
      <v-tab-item :value="'tab-3'">
        <v-card flat>

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
export default {
  data() {
    return {
      dialog: false,
      preload: false,
      namefile: "Seleccionar archivo",
      files: [],
      listaCenso: [],
      lightMenu: [
        {
          color: "#0BB9E0",
          text: "Total",
          valor: 0,
        },
        {
          color: "#E0180B",
          text: "No Registran",
          valor: 0,
        },
        {
          color: "#1867c0",
          text: "Registran",
          valor: 0,
        },
      ],
      headers: [
        {
          text: "IPS",
        },
        {
          text: "Atención o Admision",
        },
        {
          text: "Tipo de Identficicación",
        },
        {
          text: "Número Idenficación",
        },
        {
          text: "CODIGO",
        },
        {
          text: "DIAGNOSTICO CIE10",
        },
        {
          text: "Fecha Ingreso",
        },
        {
          text: "Nombres y apellidos",
        },
        {
          text: "Ubicación",
        },
        {
          text: "Especialidad",
        },
        {
          text: "Dias Estancia",
        },
        {
          text: "Vía de ingreso",
        },
        {
          text: "Asegurador",
        },
        {
          text: "GRUPO DIAGNÓSTICO",
        },
        {
          text: "IPS básica",
        },
        {
          text: "Alta",
        },
        {
          text: "Areas de reporte",
        },
        {
          text: "PYM",
        },
        {
          text: "Pendiente reporte",
        },
      ],
    };
  },

  mounted() {
    this.listarCensos();
  },

  methods: {
    setName() {
      if (this.$refs.files.files.length === 0)
        return (this.namefile = "Seleccionar archivos");
      return (this.namefile =
        this.$refs.files.files.length === 1
          ? this.$refs.files.files[0].name
          : `${this.$refs.files.files.length} archivos para cargar`);
    },
    uploadFiles() {
      this.$refs.files.click();
    },

    async cargar() {
      if (this.$refs.files.files.length === 0) {
        this.$alerError("El archivo de carga es requerido");
        return;
      }
      this.preload = true;
      let formData = new FormData();
      formData.append("data", this.$refs.files.files[0]);
      await axios
        .post("/api/referencia/censoConcurrencia", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((res) => {
          this.preload = false;
          this.listarCensos();
          this.$alerSuccess(res.data.message);
          this.dialog = false;
          this.clear();
        })
        .catch((e) => {
          this.$alerError(e.response.data.message);
          this.dialog = false;
          this.preload = false;
          this.clear();
        });
    },

    clear() {
      for (const prop of Object.getOwnPropertyNames(this.cargaInicial)) {
        this.cargaInicial[prop] = null;
      }
      this.$refs.files.value = null;
      this.namefile = "Seleccionar archivos";
    },

    listarCensos() {
      this.preload = true;
      axios
        .get("/api/referencia/listarCenso")
        .then((res) => {
          this.preload = false;
          this.listaCenso = res.data.resultado;
          this.lightMenu[2].valor = res.data.cruzan;
          this.lightMenu[1].valor = res.data.noCruzan;
          this.lightMenu[0].valor = res.data.total;
        })
        .catch((err) => {
          console.log(err);
          this.preload = flase;
        });
    },

    descargarPlantilla() {
      this.preload = true;
      axios({
        method: "post",
        url: "/api/referencia/formato",
        responseType: "blob",
      })
        .then((res) => {
          let blob = new Blob([res.data], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8",
          });
          let url = window.URL.createObjectURL(blob);
          let a = document.createElement("a");

          a.download = "Formato";
          a.href = url;
          document.body.appendChild(a);
          a.click();
          document.body.removeChild(a);
          this.preload = false;
        })
        .catch((err) => {
          (this.preload = false), console.log(err);
        });
    },
  },
};
</script>
