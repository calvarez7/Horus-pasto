<template>
  <v-layout justify-center>
    <v-flex shrink xs12>
      <RouterView />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "ReferenciaLayout",
  mounted() {
    this.setMenu();
  },
  methods: {
    setMenu() {
      this.$store.commit("setMenu", {
        title: "Módulo Referencia",
        items: [{ name: "Registrar Anexo", path: "/referencia", can:'referencia.enter' },
                { name: "Pendientes", path: "/referencia/pendientes", can:'referencia.enter' },
                { name: "En proceso", path: "/referencia/en_proceso", can:'referencia.enter' },
                { name: "Proceso interno", path: "/referencia/proceso_interno", can:'referencia.enter' },
                // { name: "Seguimiento", path: "/referencia/seguimiento", can:'seguimiento.concurrencia' },
                // { name: "Alta", path: "/referencia/alta", can:'alta.concurrencia' },
                { name: "Reportes", path: "/referencia/reporte", can:'referencia.reportes.enter' }],
                // { name: "Reportes Concurrencia", path: "/referencia/reporteConcurrencia", can:'concurrencia.reportes' }],
        enabled: true
      });
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
