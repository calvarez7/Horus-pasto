<template>
  <v-layout justify-center>
    <v-flex shrink xs12>
      <RouterView />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "ImagenologiaLayout",
  mounted() {
    this.setMenu();
  },
  methods: {
    setMenu() {
      this.$store.commit("setMenu", {
        title: "Módulo Imagenologia",
        items: [
           { name: "Admisión", path: "/imagenologia/admision", can: 'imagenologia.admision' },
           { name: "Gestión", path: "/imagenologia/gestion", can: 'imagenologia.gestion' },
           { name: "Historico", path: "/imagenologia/historico", can: 'imagenologia.historico' },
           { name: "Admin", path: "/imagenologia/admin", can: 'imagenologia.admin' },
        ],
        enabled: true
      });
    }
  }
};
</script>

<style lang="scss" scoped>
</style>