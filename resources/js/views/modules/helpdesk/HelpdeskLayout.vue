<template>
  <v-layout justify-center>
    <v-flex shrink xs12>
      <RouterView />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "HelpdeskLayout",
  mounted() {
    this.setMenu();
  },
  methods: {
    setMenu() {
      this.$store.commit("setMenu", {
        title: "Módulo Helpdesk",
        items: [
           { name: "Generar", path: "/helpdesk", can: 'any' },
           { name: "Mis Solicitudes", path: "/helpdesk/misolicitud/", can: 'any' },
           { name: "Solicitudes Área", path: "/helpdesk/solicitudarea/", can: 'areahelp.enter' },
           { name: "Mis Asignadas", path: "/helpdesk/miasignada/", can: 'asignadohelp.enter' },
           { name: "Informe", path: "/helpdesk/informe", can: 'helpdesk.informe' },
           { name: "Admin", path: "/helpdesk/admin", can: 'adminhelp.enter' }
        ],
        enabled: true
      });
    }
  }
};
</script>

<style lang="scss" scoped>
</style>