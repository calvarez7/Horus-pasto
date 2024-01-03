<template>
  <v-layout justify-center>
    <v-flex shrink xs12>
      <RouterView />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "PqrsfLayout",
  mounted() {
    this.setMenu();
  },
  methods: {
    setMenu() {
      this.$store.commit("setMenu", {
        title: "Módulo Pqrsf",
        items: [
           { name: "Formulario", path: "/pqrsf/formulario", can: 'any'},
           { name: "Gestión", path: "/pqrsf/gestion", can: 'any'},
           { name: "Gestión Externa", path: "/pqrsf/gestion-externa", can: 'pqrsf.externa'},
           { name: "Gestión Interna", path: "/pqrsf/gestion-interna", can: 'pqrsf.interna'}
        ],
        enabled: true
      });
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
