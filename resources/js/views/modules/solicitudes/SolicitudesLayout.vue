<template>
  <v-layout justify-center>
    <v-flex shrink xs12>
      <RouterView />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "SolicitudesLayout",
  mounted () {
    this.setMenu();
  },
  methods: {
    setMenu() {
      this.$store.commit("setMenu", {
        title: "Módulo Solicitudes",
        items: [
          { name: "Radicar", path: "/solicitudes/radicar", can: 'solicitudes.radicar' },
          { name: "Gestión", path: "/solicitudes/gestion", can: 'solicitudes.gestion' },
          { name: "Asignadas", path: "/solicitudes/asignadas", can: 'any' },
          { name: "Admin", path: "/solicitudes/admin", can: 'solicitudes.admin' },
          { name: "Informe", path: "/solicitudes/informe", can: 'solicitudes.informe' },
        ],
        enabled: true
      });
    }
  }
}
</script>

<style lang="scss" scoped>

</style>