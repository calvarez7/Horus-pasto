<template>
  <v-layout justify-center>
    <v-flex shrink xs12>
      <RouterView />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "AutorizacionLayout",
  mounted() {
    this.setMenu();
  },
  methods: {
    setMenu() {
      this.$store.commit("setMenu", {
        title: "Módulo Autorización",
        items: [
          { name: "Autorización Medicamentos", path: "/autorizacion", can: 'autorizacion.enter' },
          { name: "Autorización Servicios", path: "/autorizacion/servicios", can: 'autorizacion.enter' },
          { name: "Autorización Códigos propios", path: "/autorizacion/propios", can: 'autorizacion.enter' },
          { name: "Autorización Oncologia", path: "/autorizacion/esquemas", can: 'nobody' },
          { name: "Autorización Portabilidad", path: "/autorizacion/portabilidad", can: 'autorizacion.enter' },
        ],
        enabled: true
      });
    }
  }
};
</script>

<style lang="scss" scoped>
</style>