<template>
  <div>
    <v-toolbar flat color="white">
      <v-toolbar-title>Recomendaciones por cups</v-toolbar-title>
      <v-divider class="mx-2" inset vertical></v-divider>
      <v-spacer></v-spacer>
      <v-dialog v-model="dialog" persistent max-width="500px">
        <template v-slot:activator="{ on }">
          <v-btn color="primary" dark class="mb-2" v-on="on">Agregar</v-btn>
        </template>
        <v-card>
          <v-card-title>
            <span class="headline">{{ formTitle }} {{editedIndex}}</span>
          </v-card-title>

          <v-card-text>
            <v-container grid-list-md>
              <v-layout wrap>
                <v-flex xs12 sm12 md12 v-if="editedIndex == -1">
                  <v-autocomplete
                    :items="cups"
                    item-text="codigo_nombre"
                    item-value="id"
                    v-model="editedItem.cup_id"
                    label="Cups"
                  ></v-autocomplete>
                </v-flex>
                <v-flex xs12 sm12 md12>
                  <v-textarea
                    v-model="editedItem.recomendacion_cup"
                    label="Recomendación"
                  ></v-textarea>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="error" dark @click="close">Cancel</v-btn>
            <v-btn color="success" dark @click="save">Guardar</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-toolbar>
    <v-data-table
      :headers="headers"
      :items="listarRecomendaciones"
      class="elevation-1"
    >
      <template v-slot:items="props">
        <td>{{ props.item.id_recomendacion }}</td>
        <td class="text-xs-left">{{ props.item.codigo }}</td>
        <td class="text-xs-left">{{ props.item.Nombre_cups }}</td>
        <td class="text-xs-left">{{ props.item.recomendacion_cup }}</td>
        <td class="text-xs-left">
          <v-chip v-if="props.item.estado == true" color="success" dark
            >Activo</v-chip
          >
          <v-chip v-else color="error" dark>Inactivo</v-chip>
        </td>
        <td class="justify-center layout px-0">
          <v-icon
            small
            class="mr-2"
            color="warning"
            @click="editItem(props.item)"
          >
            edit
          </v-icon>
          <v-icon small @click="deleteItem(props.item.id_recomendacion)" color="error">
            delete
          </v-icon>
        </td>
      </template>
    </v-data-table>
  </div>
</template>

<script>
export default {
  data: () => ({
    dialog: false,
    cups: [],
    headers: [
      {
        text: "ID",
        align: "left",
        sortable: false,
        value: "id_recomendacion",
      },
      { text: "Cups", value: "codigo" },
      { text: "Nombre Servicio", value: "Nombre_cups" },
      { text: "Descripción", value: "recomendacion_cup" },
      { text: "Estado", value: "estado" },
      { text: "Acciones", align: "left", sortable: false },
    ],
    listarRecomendaciones: [],
    editedIndex: -1,
    editedItem: {
      cup_id: null,
      recomendacion_cup: null,
    },
    defaultItem: {
      cup_id: null,
      recomendacion_cup: null,
    },
  }),

  computed: {
    formTitle() {
      return this.editedIndex === -1
        ? "Crear Recomendación"
        : "Editar Recomendación";
    },
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
  },

  created() {
    this.fetchCups();
    this.fetchRecomendaciones();
  },

  methods: {
    fetchCups() {
      axios.get("/api/cups/all").then((res) => (this.cups = res.data));
    },

    fetchRecomendaciones() {
      axios
        .get("/api/recomendacion-cup/all")
        .then((res) => (this.listarRecomendaciones = res.data));
    },

    editItem(item) {
      this.editedIndex = this.listarRecomendaciones.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      axios.post("/api/recomendacion-cup/editState/" + item)
          .then((res) => (this.fetchRecomendaciones()));
    },

    close() {
      this.dialog = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    },

    save() {
      if (this.editedIndex > -1) {
        axios
          .post("/api/recomendacion-cup/edit" , this.editedItem)
          .then((res) => ((this.dialog = false), this.fetchRecomendaciones()));
      } else {
        axios
          .post("/api/recomendacion-cup/create", this.editedItem)
          .then((res) => ((this.dialog = false), this.fetchRecomendaciones()));
      }
      this.close();
    },
  },
};
</script>
