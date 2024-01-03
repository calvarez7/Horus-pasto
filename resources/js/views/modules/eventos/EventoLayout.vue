<template>
  <v-layout justify-center>
    <v-flex shrink xs12>
      <RouterView />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "EventoLayout",
  mounted() {
    this.setMenu();
  },
  methods: {
    setMenu() {
      this.$store.commit("setMenu", {
        title: "Módulo de Gestión de Sucesos",
        items: [
           { name: "Reporte", path: "/evento/reporte", can: 'any' },
           { name: "Asignación", path: "/evento/analisis", can: 'evento.analisis' },
           { name: "Análisis", path: "/evento/asignados", can: 'any' },
           { name: "Plan de Mejora", path: "/evento/planmejora", can: 'any' },
           { name: "Informe", path: "/evento/informes", can: 'evento.informes' },
           { name: "Admin", path: "/evento/admin", can: 'evento.admin' }
        ],
        enabled: true
      });
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
