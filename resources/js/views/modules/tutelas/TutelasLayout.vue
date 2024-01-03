<template>
  <v-layout justify-center>
    <v-flex shrink xs12>
      <RouterView />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "TutelasLayout",
  mounted() {
    this.setMenu();
  },
  methods: {
    setMenu() {
      this.$store.commit("setMenu", {
        title: "Módulo Tutelas",
        items: [
           { name: "Tutelas asignadas", path: "/tutelas", can: 'admintutelas.enter' },
           { name: "Gestión de tutelas", path: "/tutelas/asignadas", can: 'tutelas.enter' },
           { name: "Reportes Tutelas", path: "/tutelas/reporte", can: 'reportetutelas.enter' },
           { name: "Admin Tutelas", path: "/tutelas/admintutelas", can: 'admintutelas.enter' }

        ],
        enabled: true
      });
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
