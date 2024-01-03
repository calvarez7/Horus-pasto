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
        title: "Modelo de atención covid",
        items: [
            { name: 'Carga de pruebas covi-19', path: '/resultados/registro', can:'register.covid' },
            { name: 'Consulta Resultados', path: '/resultados/consulta', can:'consulta.covid'},
            { name: 'solicitud de ingreso', path: '/resultados/registroCovi', can:'anulado.covi'},
            { name: 'Admisión', path: '/resultados/admision', can:'anulado.covi'},
            { name: 'Seguimiento', path: '/resultados/seguimientofinal', can:'seguimiento.covi'},
            { name: 'Alta', path: '/resultados/alta', can:'alta.covi'},
            { name: 'Reporte', path: '/resultados/reporte', can:'reportanulado.covi'}
        ],
        enabled: true
      });
    }
  }
}
</script>

<style lang="scss" scoped>

</style>
