<template>
  <v-layout justify-center>
    <v-flex shrink xs12>
      <RouterView />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "GestionRiesgoLayout",
  mounted() {
    this.setMenu();
  },
  methods: {
    setMenu() {
      this.$store.commit("setMenu", {
        title: "Gestion Del Riesgo",
        items: [
           { name: "Control", path: "/gestionriesgo/GestionRiesgoPendientes.vue", can: 'gestionriesgo.enter' },
           { name: "Admon Demanda Inducida", path: "/gestionriesgo/demandaInducida.vue", can: 'gestionriesgo.demandaInducida' },
        ],
        enabled: true
      });
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
