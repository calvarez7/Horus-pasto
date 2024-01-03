<template>
  <v-layout justify-center>
    <v-flex shrink xs12>
      <RouterView />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "TeleConceptoLayout",
  mounted() {
    this.setMenu();
  },
  methods: {
    setMenu() {
      this.$store.commit("setMenu", {
        title: "Módulo Teleapoyo",
        items: [{ name: "Crear teleapoyo", path: "/teleconcepto", can:'teleconcepto.enter' },
                { name: "Pendientes", path: "/teleconcepto/pendientes", can:'teleconcepto.enter' },
                { name: "GIRS", path: "/teleconcepto/girs", can:'teleconcepto.enter' },
                { name: "Solucionados", path: "/teleconcepto/solucionados", can:'teleconcepto.enter' },
                { name: "Reportes", path: "/teleconcepto/reportes", can:'teleconcepto.reporte.enter' }],
        enabled: true
      });
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
