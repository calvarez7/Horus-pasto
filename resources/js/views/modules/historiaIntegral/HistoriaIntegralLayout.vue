<template>
  <v-layout justify-center>
    <v-flex shrink xs12>
      <RouterView />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "HistoriaIntegralLayout",
  mounted() {
    this.setMenu();
  },
  methods: {
    setMenu() {
      this.$store.commit("setMenu", {
        title: "Panel Médico",
        items: [
           { name: "Panel Medico", path: "/historiaIntegral", can: 'panelMedico.enter' },
           { name: "Panel Parametrización", path: "/historiaIntegral/parametrizacion", can: 'panelMedico.enter' },
        //    { name: "Admin Tutelas", path: "/tutelas/admintutelas", can: 'admintutelas.enter' }
        ],
        enabled: true
      });
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
