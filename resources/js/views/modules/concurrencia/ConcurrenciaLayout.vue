<template>
    <v-layout justify-center>
      <v-flex shirnk xs12>
        <router-view />
      </v-flex>
    </v-layout>
  </template>

  <script>
  export default {
    name: "ConcurrenciaLayout",
    components: {},
    mounted () {
      this.setMenu();
    },
    methods: {
      setMenu() {
        this.$store.commit("setMenu", {
          title: "Modelo de concurrencia",
          items: [
                { name: "Ingreso", path: "/concurrencia/ingreso", can:'ingreso.concurrencia' },
                { name: "Seguimiento", path: "/concurrencia/seguimiento", can:'seguimiento.concurrencia' },
                { name: "Alta", path: "/concurrencia/alta", can:'alta.concurrencia' },
                { name: "Censo", path: "/concurrencia/censo", can:'censo.concurrencia' },
                { name: "Reportes", path: "/concurrencia/reporte", can:'alta.concurrencia'}],
          enabled: true
        });
      }
    }
  }
  </script>

  <style lang="scss" scoped>

  </style>
