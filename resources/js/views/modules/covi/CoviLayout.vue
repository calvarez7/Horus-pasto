<template>
  <v-layout justify-center>
    <v-flex shirnk xs12>
      <router-view />
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "SumintranetLayout",
  components: {},
  mounted () {
    this.setMenu();
  },
  methods: {
    setMenu() {
      this.$store.commit("setMenu", {
        title: "Ruta de atención Covid-19",
        items: [
            { name: 'solicitud de ingreso', path: '/covi/ingreso', can:'solicitud.covi'},
            { name: 'Admisión', path: '/covi/admision', can:'admision.covi'},
            { name: 'Seguimiento', path: '/covi/seguimiento', can:'seguimiento.covi'},
            { name: 'Alta', path: '/covi/alta', can:'alta.covi'},
            { name: 'Reporte', path: '/covi/reporte', can:'reporte.rutacovi'}
        ],
        enabled: true
      });
    }
  }
}
</script>

<style lang="scss" scoped>

</style>
